<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kacamata    = Category::where('slug', 'kacamata')->first();
        $lensa       = Category::where('slug', 'lensa')->first();
        $kontakLensa = Category::where('slug', 'kontak-lensa')->first();

        $products = [
            // ── Kacamata ──────────────────────────────────────────────────────
            [
                'category_id'       => $kacamata?->id,
                'name'              => 'Kacamata Rayban Classic Aviator',
                'slug'              => 'kacamata-rayban-classic-aviator',
                'short_description' => 'Frame aviator ikonik dengan lensa anti-UV terbaik untuk gaya sehari-hari.',
                'description'       => '<p>Kacamata Rayban Classic Aviator hadir dengan desain yang telah melegenda sejak tahun 1930-an. Terbuat dari bahan metal premium yang ringan namun kuat, frame ini memberikan kenyamanan sepanjang hari. Lensa polarized 100% UV400 melindungi mata Anda dari sinar matahari berbahaya.</p><p>Tersedia dalam berbagai pilihan warna lensa, cocok untuk berbagai kesempatan baik formal maupun kasual.</p>',
                'price'             => 850000,
                'discount_price'    => 699000,
                'stock'             => 25,
                'sku'               => 'KMT-RBA-001',
                'image'             => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => true,
                'meta_title'        => 'Kacamata Rayban Classic Aviator | IndoOptik',
                'meta_description'  => 'Beli Kacamata Rayban Classic Aviator original dengan harga terbaik di IndoOptik. Garansi resmi, gratis ongkir.',
            ],
            [
                'category_id'       => $kacamata?->id,
                'name'              => 'Kacamata Baca Anti Radiasi Titanium',
                'slug'              => 'kacamata-baca-anti-radiasi-titanium',
                'short_description' => 'Frame titanium ultra-ringan dengan lensa anti radiasi layar digital.',
                'description'       => '<p>Dirancang khusus untuk kenyamanan membaca dan bekerja di depan layar dalam waktu lama. Material titanium murni membuatnya 40% lebih ringan dari frame biasa sehingga tidak meninggalkan bekas di hidung meski dipakai seharian.</p><p>Lensa blue-light blocking mengurangi paparan sinar biru dari monitor, laptop, dan smartphone sehingga mata tidak cepat lelah.</p>',
                'price'             => 450000,
                'discount_price'    => null,
                'stock'             => 40,
                'sku'               => 'KMT-BCT-002',
                'image'             => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => false,
                'meta_title'        => 'Kacamata Baca Anti Radiasi Titanium | IndoOptik',
                'meta_description'  => 'Kacamata baca titanium ringan dengan perlindungan anti radiasi layar digital. Nyaman untuk pengguna komputer.',
            ],
            [
                'category_id'       => $kacamata?->id,
                'name'              => 'Frame Kacamata Cat-Eye Vintage Wanita',
                'slug'              => 'frame-kacamata-cat-eye-vintage-wanita',
                'short_description' => 'Desain cat-eye vintage elegan, cocok untuk wanita modern dengan gaya retro.',
                'description'       => '<p>Frame cat-eye klasik yang terinspirasi dari gaya retro 1950-an namun dikemas dalam sentuhan modern. Terbuat dari acetate premium Italy yang kuat dan tahan lama dengan berbagai pilihan warna cerah.</p><p>Ringan di wajah dan cocok dipadukan dengan lensa minus, plus, maupun sunlens. Tersedia dalam ukuran M dan L sesuai lebar wajah.</p>',
                'price'             => 320000,
                'discount_price'    => 275000,
                'stock'             => 30,
                'sku'               => 'KMT-CEV-003',
                'image'             => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => true,
                'meta_title'        => 'Frame Kacamata Cat-Eye Vintage Wanita | IndoOptik',
                'meta_description'  => 'Frame cat-eye vintage elegan untuk wanita. Pilihan warna lengkap, bahan acetate premium Italy.',
            ],
            [
                'category_id'       => $kacamata?->id,
                'name'              => 'Kacamata Sport Polarized Outdoor',
                'slug'              => 'kacamata-sport-polarized-outdoor',
                'short_description' => 'Kacamata olahraga dengan lensa polarized dan frame fleksibel tahan benturan.',
                'description'       => '<p>Dirancang untuk para pecinta outdoor dan olahraga aktif. Frame TR-90 yang fleksibel mampu menahan benturan ringan tanpa patah. Rubber nose pad dan temple tip mencegah kacamata melorot saat berkeringat.</p><p>Lensa polarized mengurangi silau dari permukaan air dan jalanan, ideal untuk bersepeda, berlari, memancing, dan mendaki gunung.</p>',
                'price'             => 580000,
                'discount_price'    => 499000,
                'stock'             => 15,
                'sku'               => 'KMT-SPO-004',
                'image'             => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => false,
                'meta_title'        => 'Kacamata Sport Polarized Outdoor | IndoOptik',
                'meta_description'  => 'Kacamata olahraga terbaik dengan lensa polarized dan frame TR-90 fleksibel. Cocok untuk semua aktivitas outdoor.',
            ],

            // ── Lensa ─────────────────────────────────────────────────────────
            [
                'category_id'       => $lensa?->id,
                'name'              => 'Lensa Progressif Anti Radiasi Premium',
                'slug'              => 'lensa-progressif-anti-radiasi-premium',
                'short_description' => 'Lensa progressif tiga fokus dengan teknologi anti radiasi dan anti silau.',
                'description'       => '<p>Lensa progressif premium yang menggabungkan koreksi jarak jauh, menengah, dan dekat dalam satu lensa tanpa garis tampak. Diproses dengan teknologi digital surfacing untuk presisi tinggi.</p><p>Lapisan anti radiasi (AR) mengurangi pantulan cahaya, lapisan anti gores memperpanjang usia lensa, dan lapisan hydrophobic membuat lensa mudah dibersihkan. Tersedia untuk kekuatan -0.25 hingga -10.00.</p>',
                'price'             => 1200000,
                'discount_price'    => 980000,
                'stock'             => 50,
                'sku'               => 'LNS-PRG-001',
                'image'             => 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => true,
                'meta_title'        => 'Lensa Progressif Anti Radiasi Premium | IndoOptik',
                'meta_description'  => 'Lensa progressif premium dengan anti radiasi, anti gores, dan anti silau. Presisi tinggi untuk kenyamanan maksimal.',
            ],
            [
                'category_id'       => $lensa?->id,
                'name'              => 'Lensa Single Vision Tipis Index 1.67',
                'slug'              => 'lensa-single-vision-tipis-index-167',
                'short_description' => 'Lensa tipis high-index 1.67 untuk minus tinggi agar tampilan tetap ramping.',
                'description'       => '<p>Lensa single vision dengan indeks bias 1.67 menghasilkan lensa yang jauh lebih tipis dibandingkan lensa standar, ideal untuk koreksi minus atau plus tinggi. Bobot lebih ringan membuat kacamata lebih nyaman dipakai sepanjang hari.</p><p>Dilengkapi lapisan multi-coating standar: anti radiasi, anti gores, dan UV400. Kompatibel dengan semua jenis frame.</p>',
                'price'             => 750000,
                'discount_price'    => null,
                'stock'             => 60,
                'sku'               => 'LNS-SV167-002',
                'image'             => 'https://images.unsplash.com/photo-1603991197977-f5b6d4f4cdee?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => false,
                'meta_title'        => 'Lensa Single Vision Tipis Index 1.67 | IndoOptik',
                'meta_description'  => 'Lensa tipis index 1.67 untuk minus tinggi. Ringan, anti radiasi, dan anti gores. Beli di IndoOptik.',
            ],

            // ── Kontak Lensa ──────────────────────────────────────────────────
            [
                'category_id'       => $kontakLensa?->id,
                'name'              => 'Softlens Bulanan Acuvue Oasys (2 Pasang)',
                'slug'              => 'softlens-bulanan-acuvue-oasys-2-pasang',
                'short_description' => 'Softlens bulanan Acuvue Oasys dengan teknologi Hydraclear Plus untuk mata kering.',
                'description'       => '<p>Acuvue Oasys adalah softlens bulanan premium dengan teknologi Hydraclear Plus yang menjaga kadar air lensa tetap stabil selama 30 hari pemakaian. Cocok untuk pemakai yang sering mengalami mata kering akibat AC atau lama di depan layar.</p><p>Paket berisi 2 pasang (4 buah) lensa. Tersedia berbagai power dari -0.50 hingga -9.00. Perlu resep dokter mata untuk pembelian pertama.</p>',
                'price'             => 380000,
                'discount_price'    => 340000,
                'stock'             => 80,
                'sku'               => 'KL-ACO-001',
                'image'             => 'https://images.unsplash.com/photo-1587614382346-4ec70e388b28?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => true,
                'meta_title'        => 'Softlens Bulanan Acuvue Oasys 2 Pasang | IndoOptik',
                'meta_description'  => 'Beli Acuvue Oasys softlens bulanan original di IndoOptik. Teknologi Hydraclear Plus, nyaman untuk mata kering.',
            ],
            [
                'category_id'       => $kontakLensa?->id,
                'name'              => 'Softlens Harian FreshLook Colors (30 Pcs)',
                'slug'              => 'softlens-harian-freshlook-colors-30-pcs',
                'short_description' => 'Softlens harian berwarna FreshLook dengan 5 pilihan warna natural untuk mata indah.',
                'description'       => '<p>FreshLook Colors hadir dengan teknologi 3-in-1 color yang menghasilkan warna alami dan indah tanpa terlihat terlalu mencolok. Lensa sekali pakai harian ini sangat higienis karena tidak perlu cairan pembersih dan tidak ada risiko penumpukan protein.</p><p>Tersedia dalam 5 pilihan warna: Brown, Gray, Hazel, Green, dan Blue. Kemasan berisi 30 buah (cukup untuk 1 bulan pemakaian). Tersedia dalam plano (tanpa minus) dan dengan koreksi hingga -6.00.</p>',
                'price'             => 295000,
                'discount_price'    => 260000,
                'stock'             => 100,
                'sku'               => 'KL-FLC-002',
                'image'             => 'https://images.unsplash.com/photo-1584468771517-8b1fa3f5df4a?w=600&auto=format&fit=crop',
                'status'            => 'active',
                'is_featured'       => false,
                'meta_title'        => 'Softlens Harian FreshLook Colors 30 Pcs | IndoOptik',
                'meta_description'  => 'Softlens harian berwarna FreshLook Colors 30 buah. 5 pilihan warna natural, tersedia plano dan berkekuatan.',
            ],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
