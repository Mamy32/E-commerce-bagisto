<?php
use Webkul\Theme\Models\ThemeCustomization;

// Revert Men's collection (ID 10)
$mens = ThemeCustomization::find(10);
if ($mens) {
    $options = $mens->options;
    $options['filters']['category_id'] = 2; // Was 2 (Mens)
    $options['title'] = "Koleksi Pria"; // The default was probably this since ID locale is first
    $mens->options = $options;
    $mens->save();
}

// Revert Women's collection (ID 11)
$womens = ThemeCustomization::find(11);
if ($womens) {
    $options = $womens->options;
    $options['filters']['category_id'] = 4; // Was 4 (Womens)
    $options['title'] = "Koleksi Wanita";
    $womens->options = $options;
    $womens->save();
}

// Enable others
ThemeCustomization::whereIn('id', [12, 13])->update(['status' => 1]);

echo "Reverted product carousels\n";
