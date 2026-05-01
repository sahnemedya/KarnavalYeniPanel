<?php

namespace App\Services;

use Spatie\SchemaOrg\Schema;
use App\Models\Page;
use App\Models\Seo;

class GeoService
{
    public function generateLlmsTxt(): string
    {
        $siteName = config('app.name', 'Firma Adı');
        $siteUrl = config('app.url');

        // AI-friendly header
        $llmsContent = "# {$siteName}\n\n";

        // Sadece GEO aktif sayfaları al (published ve soft delete kontrolleri ile)
        $geoPages = Page::withGeo()
            ->with(['seo', 'parent'])
            ->where('published', 1) // Published sayfalar
            ->orderBy('parent_page')
            ->orderBy('id')
            ->get();

        if ($geoPages->count() === 0) {
            $llmsContent .= "İçerik güncelleniyor...\n\n";
            return $llmsContent;
        }

        // Hierarchical structure oluştur
        $hierarchy = $this->buildHierarchyFromPages($geoPages);

        // Recursive olarak LLMS.txt oluştur
        $llmsContent .= $this->generateHierarchicalContent($hierarchy, $siteUrl, 2);

        $llmsContent .= "---\n\n";

        return $llmsContent;
    }

    private function buildHierarchyFromPages($pages)
    {
        $hierarchy = [];
        $pageMap = [];

        // Tüm sayfaları map'e ekle
        foreach ($pages as $page) {
            $pageMap[$page->id] = [
                'page' => $page,
                'children' => [],
                'depth' => $page->getDepth()
            ];
        }

        // Parent-child ilişkilerini kur
        foreach ($pages as $page) {
            if ($page->parent_page === null || $page->parent_page === 0) {
                // Ana seviye sayfa
                $hierarchy[$page->id] = &$pageMap[$page->id];
            } else {
                // Alt seviye sayfa
                if (isset($pageMap[$page->parent_page])) {
                    // Parent GEO aktifse, alt sayfa olarak ekle
                    $pageMap[$page->parent_page]['children'][$page->id] = &$pageMap[$page->id];
                } else {
                    // Parent GEO aktif değilse, ana seviyeye ekle
                    $hierarchy[$page->id] = &$pageMap[$page->id];
                }
            }
        }

        return $hierarchy;
    }

    private function generateHierarchicalContent($hierarchy, $siteUrl, $baseDepth = 2)
    {
        $content = '';

        foreach ($hierarchy as $item) {
            $page = $item['page'];

            // Dinamik derinlik hesapla (minimum 2, maksimum 6)
            $depth = max(2, min(6, $baseDepth + $item['depth'] - 1));
            $hashes = str_repeat('#', $depth);

            // Direkt geo_title kullan
            $content .= "{$hashes} {$page->seo->geo_title}\n";
            $content .= "{$page->seo->geo_description}\n";
            $content .= "[Devamını oku]({$siteUrl}/{$page->slug})\n\n";

            // Alt sayfalar varsa recursive olarak ekle
            if (!empty($item['children'])) {
                $content .= $this->generateHierarchicalContent($item['children'], $siteUrl, $baseDepth);
            }
        }

        return $content;
    }

    // Alternative: Breadcrumb tabanlı hierarşi
    public function generateLlmsTxtWithBreadcrumbs(): string
    {
        $siteName = config('app.name', 'Firma Adı');
        $siteUrl = config('app.url');

        $llmsContent = "# {$siteName}\n\n";

        $geoPages = Page::withGeo()
            ->with(['seo', 'parent'])
            ->where('published', 1)
            ->get();

        // Breadcrumb derinliğine göre sırala
        $sortedPages = $geoPages->sortBy(function($page) {
            return $page->breadcrumbs()->count();
        });

        foreach ($sortedPages as $page) {
            $breadcrumbs = $page->breadcrumbs();
            $depth = max(2, min(6, $breadcrumbs->count() + 1));
            $hashes = str_repeat('#', $depth);

            $llmsContent .= "{$hashes} {$page->seo->geo_title}\n";
            $llmsContent .= "{$page->seo->geo_description}\n";
            $llmsContent .= "[Devamını oku]({$siteUrl}/{$page->slug})\n\n";
        }

        $llmsContent .= "---\n\n";

        return $llmsContent;
    }

    // Schema metodları
    public function generateWebsiteSchema(): string
    {
        $website = Schema::webSite()
            ->name(config('app.name'))
            ->url(config('app.url'))
            ->description('Sitenizin açıklaması')
            ->potentialAction(
                Schema::searchAction()
                    ->target(config('app.url') . '/search?q={search_term_string}')
                    ->queryInput('required name=search_term_string')
            );

        return $website->toScript();
    }

    public function generateOrganizationSchema(): string
    {
        $organization = Schema::organization()
            ->name(config('app.name'))
            ->url(config('app.url'))
            ->logo(config('app.url') . '/logo.png')
            ->contactPoint(
                Schema::contactPoint()
                    ->telephone('+90-XXX-XXX-XXXX')
                    ->contactType('customer service')
                    ->areaServed('TR')
                    ->availableLanguage(['tr', 'en'])
            )
            ->sameAs([
                'https://twitter.com/yourhandle',
                'https://linkedin.com/company/yourcompany'
            ]);

        return $organization->toScript();
    }

    public function generatePageSchema($page): string
    {
        if (!$page->hasGeo()) {
            return '';
        }

        $schema = Schema::webPage()
            ->name($page->seo->geo_title)
            ->description($page->seo->geo_description)
            ->url(config('app.url') . '/' . $page->slug)
            ->datePublished($page->created_at->toISOString())
            ->dateModified($page->updated_at->toISOString())
            ->publisher(
                Schema::organization()
                    ->name(config('app.name'))
                    ->logo(
                        Schema::imageObject()
                            ->url(config('app.url') . '/logo.png')
                            ->width(600)
                            ->height(60)
                    )
            );

        return $schema->toScript();
    }

    public function generateBreadcrumbSchema($breadcrumbs): string
    {
        $listItems = collect($breadcrumbs)->map(function($item, $index) {
            return Schema::listItem()
                ->position($index + 1)
                ->name($item['name'])
                ->item($item['url']);
        })->toArray();

        $breadcrumbList = Schema::breadcrumbList()
            ->itemListElement($listItems);

        return $breadcrumbList->toScript();
    }

    public function generatePageSchemaByCategory($page)
    {
        $schema = [
            '@context' => 'https://schema.org',
        ];

        // Sayfanın kategorisini al
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

        // Her schema'ya URL ekle
        $schema['url'] = url($page->slug ?? '');

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }
}
