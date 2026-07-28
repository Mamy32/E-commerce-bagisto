{{--
    LUXE Fashion Store — Desktop Header Container Override
    ─────────────────────────────────────────────────────
    Override of: packages/Webkul/Shop/src/Resources/views/
                 components/layouts/header/desktop/index.blade.php

    Original was 3 lines rendering only header.desktop.bottom.
    We keep the same structure. The announcement bar lives in
    header/index.blade.php ABOVE the <header> element so it renders
    exactly once and is not duplicated by Vue's x-template system.
--}}

<div class="flex flex-wrap max-lg:hidden">
    <x-shop::layouts.header.desktop.bottom />
</div>
