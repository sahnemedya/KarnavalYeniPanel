<?php

namespace App\Services;

use App\Models\{Page, SiteSettings, Contacts, FAQ};
use Illuminate\Support\Str;

class PageSchemasService
{
    /**
     * TÜM ŞEMALARI BİRLEŞTİREN ANA MOTOR
     */
    public static function generate(Page $page, $sector = 'festival')
    {
        $siteSetting = SiteSettings::first();
        $contactObj = Contacts::with('socialMedia')->first();

        // SQL yapındaki lang_id'ye göre dili belirle (1=tr, 2=en varsayımıyla)
        $locale = $page->lang_id == 2 ? 'en' : 'tr';
        app()->setLocale($locale);

        // 1. Temel Graph
        $graph = [
            self::getWebSiteSchema($siteSetting),
            self::getOrganizationSchema($siteSetting, $contactObj, $sector),
        ];

        // Eğer sayfa bir kişi hakkındaysa veya yazar belirtilecekse eklenebilir
        // Tabloda Ali Haydar Bozkurt gibi özel sayfalar olduğu için aktif bırakıldı
        $graph[] = self::getPersonEntity($contactObj);

        // 2. Breadcrumb
        $graph[] = self::getBreadcrumbSchema($page);

        // 3. Sayfa Kimliği & İçerik Tipi
        // SQL'de page_schema ilişkisi görünmüyor, varsayılan olarak Article veya WebPage döner
        $schemaType = $page->pageSchema->schema_type ?? (empty($page->content) ? 'WebPage' : 'Article');
        $graph[] = self::mapPageSchema($page, $schemaType, $sector);

        // 4. Akıllı FAQ (İçerik içindeki H2/H3 sorulardan üretir)
        $faqData = self::getSmartFAQSchema($page);
        if ($faqData) $graph[] = $faqData;

        // 5. Video Şeması (SQL: video sütunu doluysa)
        if (!empty($page->video)) {
            $graph[] = self::getVideoSchema($page);
        }

        return self::render($graph);
    }

    private static function getPersonEntity($contact)
    {
        $locale = app()->getLocale();
        return [
            "@type" => "Person",
            "@id" => url('/') . "/#person",
            "name" => "Ali Haydar Bozkurt", // Karnaval özelinde kurucu figür
            "jobTitle" => $locale === 'en' ? 'President of Carnival Committee' : 'Karnaval Komite Başkanı',
            "url" => url('/ali-haydar-bozkurt-kimdir'),
            "sameAs" => [
                "https://www.instagram.com/alihaydarbozkurt/"
            ]
        ];
    }

    public static function getWebSiteSchema($settings)
    {
        return [
            "@type" => "WebSite",
            "@id" => url('/') . "/#website",
            "name" => $settings->site_name ?? "Nisan'da Adana'da",
            "url" => url('/'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => url('/') . "/arama?q={search_term_string}",
                "query-input" => "required name=search_term_string"
            ]
        ];
    }

    public static function getOrganizationSchema($settings, $contact, $sector = 'festival')
    {
        $locale = app()->getLocale();
        return [
            "@type" => "Organization",
            "@id" => url('/') . "/#organization",
            "name" => $settings->site_name ?? "Uluslararası Portakal Çiçeği Karnavalı",
            "url" => url('/'),
            "logo" => asset('images/site/logo.svg'),
            "contactPoint" => [
                "@type" => "ContactPoint",
                "telephone" => $contact->phone ?? '',
                "contactType" => "customer service"
            ]
        ];
    }

    public static function getBreadcrumbSchema(Page $page)
    {
        $locale = app()->getLocale();
        $homeText = $locale === 'en' ? 'Home' : 'Anasayfa';

        $items = [[
            "@type" => "ListItem",
            "position" => 1,
            "name" => $homeText,
            "item" => url('/')
        ]];

        // SQL'deki parent_page ilişkisini takip eder
        if ($page->parent_page) {
            $parent = Page::find($page->parent_page);
            if ($parent) {
                $items[] = [
                    "@type" => "ListItem",
                    "position" => 2,
                    "name" => $parent->title,
                    "item" => url($parent->slug)
                ];
            }
        }

        $items[] = [
            "@type" => "ListItem",
            "position" => count($items) + 1,
            "name" => $page->title,
            "item" => url($page->slug)
        ];

        return [
            "@type" => "BreadcrumbList",
            "itemListElement" => $items
        ];
    }

    public static function getSmartFAQSchema(Page $page)
    {
        $questions = collect();
        $locale = app()->getLocale();

        // 1. Manuel FAQ Tablosundan Çek (Varsa)
        if (class_exists('App\Models\FAQ')) {
            $dbFaqs = FAQ::where('page_id', $page->id)->get();
            foreach ($dbFaqs as $item) {
                $questions->push(["name" => $item->question, "acceptedAnswer" => strip_tags($item->answer)]);
            }
        }

        // 2. İçerik Analizi (AI Enriched)
        if (!empty($page->content)) {
            preg_match_all('/<(h2|h3)[^>]*>(.*?\?)<\/\1>/i', $page->content, $matches);
            if (!empty($matches[2])) {
                foreach ($matches[2] as $text) {
                    $q = strip_tags($text);
                    if (!$questions->contains('name', $q)) {
                        $enrichedAnswer = $locale === 'en'
                            ? "Detailed information about '{$q}' can be found in our comprehensive guide about {$page->title}."
                            : "{$page->title} kapsamında merak edilen '{$q}' sorusunun detaylarına sayfamızdaki ilgili bölümden ulaşabilirsiniz.";

                        $questions->push(["name" => $q, "acceptedAnswer" => $enrichedAnswer]);
                    }
                }
            }
        }

        if ($questions->isEmpty()) return null;

        return [
            "@type" => "FAQPage",
            "mainEntity" => $questions->map(fn($q) => [
                "@type" => "Question",
                "name" => $q['name'],
                "acceptedAnswer" => ["@type" => "Answer", "text" => $q['acceptedAnswer']]
            ])->toArray()
        ];
    }

    private static function mapPageSchema($page, $type, $sector)
    {
        $locale = app()->getLocale();

        $base = [
            "@id" => url($page->slug) . "/#webpage",
            "name" => $page->title,
            "url" => url($page->slug),
            "description" => Str::limit(strip_tags($page->content), 160),
            "inLanguage" => $locale,
            "datePublished" => $page->created_at ? $page->created_at->toIso8601String() : null,
            "dateModified" => $page->updated_at ? $page->updated_at->toIso8601String() : null,
        ];

        // SQL'deki 'image' sütununa göre resim yolu
        $imageUrl = $page->image ? asset('storage/pages/' . $page->image) : null;

        return match ($type) {
            'Article', 'BlogPosting' => array_merge($base, [
                "@type" => "BlogPosting",
                "headline" => $page->inside_title ?? $page->title,
                "author" => ["@id" => url('/') . "/#person"],
                "publisher" => ["@id" => url('/') . "/#organization"],
                "image" => $imageUrl
            ]),
            'Event' => array_merge($base, [
                "@type" => "Event",
                "location" => [
                    "@type" => "Place",
                    "name" => "Adana, Türkiye",
                    "address" => "Adana"
                ],
                "image" => $imageUrl
            ]),
            default => array_merge($base, ["@type" => "WebPage"])
        };
    }

    private static function getVideoSchema($page)
    {
        return [
            "@type" => "VideoObject",
            "name" => $page->title,
            "description" => Str::limit(strip_tags($page->content), 150),
            "thumbnailUrl" => $page->image ? asset('storage/pages/'.$page->image) : null,
            "contentUrl" => $page->video,
            "uploadDate" => $page->created_at ? $page->created_at->toIso8601String() : now()->toIso8601String()
        ];
    }

    public static function render($graph)
    {
        if (empty($graph)) return '';
        return '<script type="application/ld+json">' . json_encode(["@context" => "https://schema.org", "@graph" => $graph], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }
}
