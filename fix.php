<?php
$tc = \Webkul\Theme\Models\ThemeCustomizationTranslation::where('theme_customization_id', 1)->where('locale', 'id')->first();
$tc_en = \Webkul\Theme\Models\ThemeCustomizationTranslation::where('theme_customization_id', 1)->where('locale', 'en')->first();
$tc_en->options = $tc->options;
$tc_en->save();

