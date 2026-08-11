<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

Route::get('/fix-css-all', function () {
    $rows = DB::table('theme_customization_translations')->where('theme_customization_id', 3)->get();
    
    foreach ($rows as $row) {
        $options = json_decode($row->options, true);
        if (isset($options['css'])) {
            $css = $options['css'];
            
            // Replace the max-width: 520px block
            $oldCss = "@media (max-width: 520px) {\n    .top-collection-grid { padding-left: 15px; padding-right: 15px; }\n    .top-collection-card h3 { font-size: 12px; }\n}";
            $oldCss2 = "@media (max-width:520px) { .top-collection-grid{padding-left: 15px;padding-right: 15px;} .top-collection-card h3 {font-size:18px; bottom: 10px;}}";
            
            $newCss = "@media (max-width: 520px) {\n    .top-collection-grid { padding-left: 15px; padding-right: 15px; row-gap: 24px; }\n    .top-collection-card { width: 100%; }\n    .top-collection-card h3 { font-size: 14px; }\n}";
            
            if (strpos($css, $oldCss) !== false) {
                $options['css'] = str_replace($oldCss, $newCss, $css);
            } elseif (strpos($css, $oldCss2) !== false) {
                $options['css'] = str_replace($oldCss2, $newCss, $css);
            } else {
                // If it already has the fix or something else, append it just in case? 
                // No, better to just str_replace. If they manually pasted from EN, it might already have newCss.
            }
            
            // Just to be absolutely safe, let's inject width: 100% directly if it's missing
            if (strpos($options['css'], '.top-collection-card { width: 100%; }') === false) {
                $options['css'] .= " \n@media (max-width: 520px) { .top-collection-grid { row-gap: 24px; } .top-collection-card { width: 100%; } }";
            }
            
            DB::table('theme_customization_translations')->where('id', $row->id)->update(['options' => json_encode($options)]);
        }
    }
    
    // Clear the cache so it applies immediately
    Cache::flush();
    
    return "CSS updated for ALL languages! Cache cleared. Please check your website on mobile now.";
});
