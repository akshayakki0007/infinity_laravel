<?php 
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   @for($i=1;$i<=$report_pages;$i++)
      <url>
         <loc><?php echo url('/report-page-'.$i).'.xml'; ?></loc>
         <lastmod><?php echo date('Y-m-d\TH:i:s', strtotime($arrReports->updated_at)); ?>+00:00</lastmod>
         <priority>1.00</priority>
      </url>
   @endfor
</urlset>