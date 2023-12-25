<?php 
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>'; 
?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <sitemap>
      <loc><?php echo url('/pages.xml'); ?></loc>
   </sitemap>
   <sitemap>
      <loc><?php echo url('/reports.xml'); ?></loc>
   </sitemap>
</sitemapindex>