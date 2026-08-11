<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TranslateThemeToIndonesian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:translate-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translates theme customizations to Indonesian';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting theme translations to Indonesian...');

        $translations = DB::table('theme_customization_translations')->where('locale', 'en')->get();

        $replacements = [
            'Free Shipping' => 'Gratis Ongkir',
            'Enjoy free shipping on all orders' => 'Nikmati gratis ongkir untuk semua pesanan',
            'Product Replace' => 'Penggantian Produk',
            'Easy Product Replacement Available!' => 'Tersedia Penggantian Produk Mudah!',
            'Emi Available' => 'Cicilan Tersedia',
            'No cost EMI available on all major credit cards' => 'Cicilan tanpa biaya tersedia untuk semua kartu kredit',
            '24/7 Support' => 'Dukungan 24/7',
            'Dedicated 24/7 support via chat and email' => 'Dukungan khusus 24/7 via chat dan email',
            'Get Ready for our new Bold Collections!' => 'Bersiaplah untuk Koleksi Bold baru kami!',
            'Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!' => 'Memperkenalkan Koleksi Bold Baru Kami! Tingkatkan gaya Anda dengan desain berani dan pernyataan cerah. Jelajahi pola mencolok dan warna berani yang mendefinisikan ulang lemari pakaian Anda. Bersiaplah untuk menyambut hal luar biasa!',
            'View Collections' => 'LIHAT KOLEKSI',
            'Unleash Your Boldness with Our New Collection!' => 'Bebaskan Keberanian Anda dengan Koleksi Baru Kami!',
            'Our Bold Collections are here to redefine your wardrobe with fearless designs and striking, vibrant colors. From daring patterns to powerful hues, this is your chance to break away from the ordinary and step into the extraordinary.' => 'Koleksi Bold kami hadir untuk mendefinisikan ulang lemari pakaian Anda dengan desain yang berani dan warna yang mencolok. Dari pola berani hingga warna kuat, inilah kesempatan Anda untuk melepaskan diri dari hal biasa dan melangkah ke hal yang luar biasa.',
            'VIEW COLLECTIONS' => 'LIHAT KOLEKSI',
            'Performance & Style' => 'Performa & Gaya',
            'Discover our premium activewear collection, engineered for movement and designed for life.' => 'Temukan koleksi activewear premium kami, dirancang untuk pergerakan bebas dan gaya hidup modern.',
            'Men\'s Active' => 'Pakaian Olahraga Pria',
            'Women\'s Active' => 'Pakaian Olahraga Wanita',
            'SHOP NOW' => 'BELANJA SEKARANG',
            'Curated Essentials' => 'Kebutuhan Esensial Terkurasi',
            'Explore our latest pieces, crafted with uncompromising attention to detail and designed for the modern lifestyle.' => 'Jelajahi koleksi terbaru kami, dibuat dengan perhatian ekstra pada detail dan dirancang untuk gaya hidup modern.',
            'ELECTRONIC' => 'ELEKTRONIK',
            'Electronic' => 'Elektronik',
            'MEN' => 'PRIA',
            'Men' => 'Pria',
            'WOMEN' => 'WANITA',
            'Women' => 'Wanita',
            'FORMALWEAR MAN' => 'PAKAIAN FORMAL PRIA',
            'Formalwear Man' => 'Pakaian Formal Pria',
            'FORMALWEAR WOMAN' => 'PAKAIAN FORMAL WANITA',
            'Formalwear Woman' => 'Pakaian Formal Wanita',
            'WELLNESS' => 'KESEHATAN',
            'Wellness' => 'Kesehatan',
        ];

        foreach ($translations as $translation) {
            $options = $translation->options;

            foreach ($replacements as $en => $id) {
                $options = str_replace($en, $id, $options);
            }

            DB::table('theme_customization_translations')->updateOrInsert(
                [
                    'theme_customization_id' => $translation->theme_customization_id,
                    'locale' => 'id',
                ],
                [
                    'options' => $options,
                ]
            );
        }

        $this->info('Successfully translated theme customizations to Indonesian!');
    }
}
