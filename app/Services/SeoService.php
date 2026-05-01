<?php

namespace App\Services;

use App\Models\Blade;
use App\Models\Page;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeoService
{
    public function store(Request $request)
    {
        $status = "success";
        $message = "Seo Bilgisi Kaydedildi";

        try {
            $page = Page::find($request->page_id);
            $seo = Seo::create([
                "title" => $request->title,
                "description" => $request->description,
                "geo_title" => $request->geo_title,
                "geo_description" => $request->geo_description,
                "canonical" => $request->canonical,
                "page_id" => $request->page_id
            ]);
            $message = $seo->title . ' ' . $message;
            LogService::add("Seo Service Store", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Seo Bilgisi Kaydedilemedi";
            LogService::add("Seo Service Store", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function update(Request $request, $id)
    {
        $status = "success";
        $message = "Seo Bilgisi Güncellendi";

        try {
            $page = Page::find($request->page_id);
            $seo = Seo::findOrFail($id);
            $seo->update([
                "title" => $request->title,
                "description" => $request->description,
                "geo_title" => $request->geo_title,
                "geo_description" => $request->geo_description,
                "canonical" => $request->canonical,
                "page_id" => $request->page_id
            ]);
            $message = $seo->title . ' ' . $message;
            LogService::add("Seo Service Update", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Seo Bilgisi Güncellenemedi";
            LogService::add("Seo Service Update", $status, $message . ' => ' . $exception->getMessage());
            return ["status" => $status, "message" => $message];
        }
    }

    public function destroy($id)
    {
        try {
            $seo = Seo::findOrFail($id);
            $seo->delete();
            $status = "success";
            $message = $seo->title . " Seo Bilgisi Silindi";
            LogService::add("Seo Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        } catch (\Throwable $exception) {
            $status = "error";
            $message = "Seo Bilgisi Silinemedi";
            LogService::add("Seo Service Destroy", $status, $message);
            return ["status" => $status, "message" => $message];
        }
    }

    // Schema.org metodları (eklenen kısım)
    public static function generateMeta($page, $data = [])
    {
        // Site ayarlarını çek
        $siteSetting = self::getSiteSetting();

        $defaults = [
            'title' => ($siteSetting && $siteSetting->site_name) ? $siteSetting->site_name . ' | Emin Serhat Arıkan' : 'Emin Serhat Arıkan',
            'description' => ($siteSetting && $siteSetting->description) ? $siteSetting->description : "",
            'keywords' => ($siteSetting && $siteSetting->seo_keywords) ? $siteSetting->seo_keywords : 'Diş Hekimi',
            'og_image' => ($siteSetting && $siteSetting->logo) ? asset('images/site/' . $siteSetting->logo) : asset('images/sahne-medya-og.jpg'),
            'canonical' => url()->current()
        ];

        return array_merge($defaults, $data);
    }

    public static function organizationSchema()
    {
        // Veritabanından site ayarları ve iletişim bilgilerini çek
        $siteSetting = self::getSiteSetting();
        $contacts = self::getContacts();

        // TEMEL HASTANE BİLGİLERİ (Varsayılanlar "Sahne Medya" DEĞİL, "Ortadoğu" olmalı)
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Hospital", // <-- KRİTİK DEĞİŞİKLİK: AdvertisingAgency -> Hospital
            "name" => ($siteSetting && $siteSetting->site_name) ? $siteSetting->site_name : "Emin Serhat Arıkan",
            "url" => env('APP_URL'),
            "logo" => ($siteSetting && $siteSetting->logo) ? env('APP_URL') . '/images/site/' . $siteSetting->logo : env('APP_URL') . '/images/logo.png',
            "description" => ($siteSetting && $siteSetting->description) ? $siteSetting->description : "",
            "foundingDate" => "1988", // Bu doğruysa kalsın
            "slogan" => "" // Bu doğruysa kalsın
        ];

        // Adres bilgileri
        $addresses = [];
        if ($contacts) {
            $addresses[] = [
                "@type" => "PostalAddress",
                "streetAddress" => ($contacts->address ?? 'Güzelyalı, Turgut Özal Blv. Migros Üzeri. 81154. sk. Kat:2/3'),
                "addressLocality" => $contacts->city ?? "Adana",
                "addressRegion" => $contacts->state ?? "Çukurova",
                "postalCode" => "01000", // Gerçek posta kodunu girin
                "addressCountry" => "TR"
            ];
        } else {
            // Fallback (Sadece hastanenin adresi)
            $addresses[] = [
                "@type" => "PostalAddress",
                "streetAddress" => "Güzelyalı, Turgut Özal Blv. Migros Üzeri. 81154. sk. Kat:2/3",
                "addressLocality" => "Adana",
                "addressRegion" => "Çukurova",
                "postalCode" => "01000",
                "addressCountry" => "TR"
            ];
        }
        // İSTANBUL OFİSİ BİLGİSİNİ KALDIRIN (Eğer hastaneye ait değilse)
        // $addresses[] = [ ... ];

        $schema["address"] = $addresses;

        // İletişim bilgileri
        $contactPoints = [];
        if ($contacts && $contacts->phone) {
            $contactPoints[] = [
                "@type" => "ContactPoint",
                "telephone" => $contacts->phone,
                "contactType" => "customer service",
                "areaServed" => "TR",
                "availableLanguage" => "Turkish"
            ];
        } else {
            $contactPoints[] = [
                "@type" => "ContactPoint",
                "telephone" => "+90-322-400-01-01", // Hastanenin ana telefonu
                "contactType" => "customer service",
                "areaServed" => "TR",
                "availableLanguage" => "Turkish"
            ];
        }
        $schema["contactPoint"] = $contactPoints;
        $schema["email"] = ($contacts && $contacts->email) ? $contacts->email : "info@ortadoguhastanesi.com.tr";

        // Sosyal medya (Sadece HASTANE'nin)
        $socialLinks = [
            "https://www.instagram.com/",
            "https://www.facebook.com/",
            "https://www.linkedin.com/company/", // Emin değilim, kontrol edin
            "https://www.youtube.com/channel/",
            "https://x.com/"
        ];

        $schema["sameAs"] = $socialLinks;
        return $schema;
    }

// websiteSchema fonksiyonunu da kontrol edin, "Sahne Medya" yazmadığından emin olun
    public static function websiteSchema()
    {
        $siteSetting = self::getSiteSetting();

        return [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => ($siteSetting && $siteSetting->site_name) ? $siteSetting->site_name : "Emin Serhat Arıkan ", // <-- Düzeltildi
            "url" => env('APP_URL'),
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => env('APP_URL') . "/arama?q={search_term_string}",
                "query-input" => "required name=search_term_string"
            ]
        ];
    }

    public static function breadcrumbSchema($items)
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "name" => $item['name'],
                "item" => $item['url']
            ];
        }

        return [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $listItems
        ];
    }

    public static function pageSchemaByCategory($page)
    {
        $schema = [
            '@context' => 'https://schema.org',
        ];

        $category = strtolower($page->category->slug ?? $page->category->name ?? '');

        switch (true) {
            case str_contains($category, 'doktor'):
            case str_contains($category, 'uzman'):
                $schema['@type'] = 'Person';
                $schema['name'] = $page->title ?? '';
                $schema['jobTitle'] = $page->subtitle ?? 'Uzman';
                $schema['worksFor'] = [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                    'url' => config('app.url'),
                ];
                if (!empty($page->image)) {
                    $schema['image'] = asset('storage/' . $page->image);
                }
                break;

            case str_contains($category, 'hizmet'):
            case str_contains($category, 'birim'):
            case str_contains($category, 'alan'):
                $schema['@type'] = 'Service';
                $schema['serviceType'] = $page->title ?? '';
                $schema['provider'] = [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                    'url' => config('app.url'),
                ];
                break;

            case str_contains($category, 'blog'):
            case str_contains($category, 'haber'):
            case str_contains($category, 'duyuru'):
                $schema['@type'] = 'BlogPosting';
                $schema['headline'] = $page->title ?? '';
                $schema['description'] = strip_tags($page->description ?? '');
                $schema['datePublished'] = $page->created_at ?? now()->toDateString();
                $schema['author'] = [
                    '@type' => 'Organization',
                    'name' => config('app.name'),
                ];
                if (!empty($page->image)) {
                    $schema['image'] = asset('storage/' . $page->image);
                }
                break;

            default:
                $schema['@type'] = 'WebPage';
                $schema['name'] = $page->title ?? config('app.name');
                $schema['description'] = strip_tags($page->description ?? '');
                break;
        }

        $schema['url'] = url($page->slug ?? '');

        return $schema;
    }


    // Helper methods
    private static function getSiteSetting()
    {
        try {
            if (class_exists('\App\Models\SiteSetting')) {
                return \App\Models\SiteSetting::first();
            } elseif (class_exists('\App\Models\Setting')) {
                return \App\Models\Setting::first();
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function getContacts()
    {
        try {
            if (class_exists('\App\Models\Contact')) {
                return \App\Models\Contact::with('socialMedia')->first();
            } elseif (class_exists('\App\Models\Contacts')) {
                return \App\Models\Contacts::with('socialMedia')->first();
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
