<?php 
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>'; 
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   @if(count($arrPages) > 0)
      @foreach($arrPages as $key => $val)
        <url>
          <loc><?php echo url($val->slug); ?></loc>
          <lastmod><?php echo date('Y-m-d\TH:i:s', strtotime($val->updated_at)); ?>+00:00</lastmod>
          <priority>0.80</priority>
        </url>
      @endforeach
    @endif
</urlset>