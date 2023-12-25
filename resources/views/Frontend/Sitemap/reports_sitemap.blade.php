<?php 
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>'; 
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   @if(count($arrReports) > 0)
      @foreach($arrReports as $key => $val)
         <?php 
           $strPath = url('/report/'.$val->slug.'-'.$val->id);
         ?>
         <url>
            <loc><?php echo $strPath.'/'.$val->report_title; ?></loc>
            <lastmod><?php echo date('Y-m-d\TH:i:s', strtotime($val->updated_at)); ?>+00:00</lastmod>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
         </url>
      @endforeach
   @endif
</urlset>