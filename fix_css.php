<?php
$row = DB::table('theme_customization_translations')->where('theme_customization_id', 3)->where('locale', 'en')->first();
if ($row) {
    $options = json_decode($row->options, true);
    $css = $options['css'];
    
    // Replace the max-width: 520px block
    $oldCss = "@media (max-width: 520px) {\n    .top-collection-grid { padding-left: 15px; padding-right: 15px; }\n    .top-collection-card h3 { font-size: 12px; }\n}";
    $newCss = "@media (max-width: 520px) {\n    .top-collection-grid { padding-left: 15px; padding-right: 15px; row-gap: 24px; }\n    .top-collection-card { width: 100%; }\n    .top-collection-card h3 { font-size: 14px; }\n}";
    
    $options['css'] = str_replace($oldCss, $newCss, $css);
    
    DB::table('theme_customization_translations')->where('id', $row->id)->update(['options' => json_encode($options)]);
    echo "CSS updated.\n";
}
