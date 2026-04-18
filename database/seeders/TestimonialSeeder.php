<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'user_id'     => null,
                'name'        => 'Budi Santoso',
                'role'        => 'Karyawan Swasta',
                'message'     => 'Pelayanannya sangat ramah dan profesional. Kacamata yang saya beli kualitasnya sangat bagus dan harganya terjangkau. Saya sangat puas berbelanja di IndoOptik. Pasti akan kembali lagi!',
                'rating'      => 5,
                'photo'       => null,
                'status'      => 'published',
                'is_verified' => true,
            ],
            [
                'user_id'     => null,
                'name'        => 'Siti Rahayu',
                'role'        => 'Guru SD',
                'message'     => 'Lensa kontak yang saya pesan sesuai dengan deskripsi, pengiriman cepat dan aman. Staf sangat membantu menjelaskan pilihan lensa yang sesuai dengan kebutuhan mata saya. Recommended!',
                'rating'      => 5,
                'photo'       => null,
                'status'      => 'published',
                'is_verified' => true,
            ],
            [
                'user_id'     => null,
                'name'        => 'Andi Wijaya',
                'role'        => 'Mahasiswa',
                'message'     => 'Harga bersaing, kualitas tidak perlu diragukan. Saya sudah langganan di sini selama 2 tahun dan tidak pernah kecewa. Frame kacamatanya banyak pilihan dan mengikuti tren terkini.',
                'rating'      => 5,
                'photo'       => null,
                'status'      => 'published',
                'is_verified' => false,
            ],
            [
                'user_id'     => null,
                'name'        => 'Dewi Permatasari',
                'role'        => 'Ibu Rumah Tangga',
                'message'     => 'Sangat terbantu dengan adanya konsultasi gratis sebelum membeli. Kacamata progresif yang saya dapatkan sangat nyaman dipakai seharian. Terima kasih IndoOptik!',
                'rating'      => 4,
                'photo'       => null,
                'status'      => 'published',
                'is_verified' => true,
            ],
            [
                'user_id'     => null,
                'name'        => 'Rizky Pratama',
                'role'        => 'Pengusaha',
                'message'     => 'Saya memesan kacamata untuk seluruh karyawan kantor saya di sini. Prosesnya mudah, harga grosir kompetitif, dan hasilnya memuaskan. IndoOptik memang solusi terbaik untuk kebutuhan optik.',
                'rating'      => 5,
                'photo'       => null,
                'status'      => 'published',
                'is_verified' => true,
            ],
            [
                'user_id'     => null,
                'name'        => 'Nurul Hidayah',
                'role'        => 'Dokter',
                'message'     => 'Sebagai tenaga medis, saya sangat memperhatikan kualitas lensa. IndoOptik menyediakan lensa anti-radiasi berkualitas tinggi yang sangat cocok untuk saya yang bekerja di depan layar sepanjang hari. Sangat merekomendasikan!',
                'rating'      => 4,
                'photo'       => null,
                'status'      => 'published',
                'is_verified' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
