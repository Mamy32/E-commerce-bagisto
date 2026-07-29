<?php
use Webkul\Theme\Models\ThemeCustomization;

// Update Men's collection (usually ID 10)
$mens = ThemeCustomization::find(10);
if ($mens) {
    $options = $mens->options;
    $options['filters']['category_id'] = 42;
    $options['title'] = "Men's Collection";
    $mens->options = $options;
    $mens->save();
}

// Update Women's collection (usually ID 11)
$womens = ThemeCustomization::find(11);
if ($womens) {
    $options = $womens->options;
    $options['filters']['category_id'] = 47;
    $options['title'] = "Women's Collection";
    $womens->options = $options;
    $womens->save();
}

// Disable others
ThemeCustomization::whereIn('id', [12, 13])->update(['status' => 0]);

echo "Updated product carousels\n";
