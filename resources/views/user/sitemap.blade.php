
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc><?php echo env("APP_URL"); ?></loc>
        <lastmod>2024-09-19T16:55:39+03:00</lastmod>
    </url>
    <?php foreach ($slugs as $slug): ?>
    <url>
        <loc><?php echo env("APP_URL"); ?>/<?php echo $slug->slug; ?></loc>
        <lastmod><?php echo $slug->updated_at->tz('Turkey')->toAtomString(); ?></lastmod>
    </url>
    <?php endforeach; ?>
</urlset>
