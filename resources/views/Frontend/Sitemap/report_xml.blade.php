<?php 
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   @if(count($arrReports) > 0)
      @foreach($arrReports as $key => $row)
         <url>
            <loc>{{ url('/report/'.$row->slug.'-'.$row->id) }}</loc>
            <changefreq>daily</changefreq>
            <priority>1.00</priority>
         </url>
      @endforeach
   @endif
</urlset>