<?php

namespace Database\Factories;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Testimonial>
     */
    protected $model = Testimonial::class;

    /**
     * Indonesian customer roles for testimonials.
     *
     * @var list<string>
     */
    private array $customerRoles = [
        'Pelanggan Setia',
        'Pelanggan Baru',
        'Mahasiswa',
        'Karyawan Swasta',
        'Pegawai Negeri',
        'Ibu Rumah Tangga',
        'Pengusaha',
        'Dokter',
        'Guru',
        'Desainer Grafis',
        'Programmer',
        'Fotografer',
    ];

    /**
     * Realistic Indonesian testimonial messages for an optical store.
     *
     * @var list<string>
     */
    private array $messages = [
        'Kacamata yang saya beli kualitasnya sangat bagus dan harganya terjangkau. Pelayanannya juga ramah dan profesional. Sangat puas!',
        'Lensa yang dipasang sangat jernih dan nyaman dipakai. Proses pembuatannya cepat, tidak perlu menunggu lama. Recommended!',
        'Sudah beberapa kali beli kacamata di sini, selalu puas dengan hasilnya. Koleksi framenya banyak dan trendy.',
        'Pelayanan sangat memuaskan. Staff-nya membantu saya memilih frame yang cocok dengan bentuk wajah saya. Hasilnya sempurna!',
        'Harga sangat bersaing dengan kualitas yang tidak kalah dari toko optik besar. Pasti akan beli lagi di sini.',
        'Kontak lensa yang saya beli nyaman dipakai seharian. Tidak terasa kering meskipun dipakai bekerja di depan komputer berjam-jam.',
        'Proses pengukuran minus mata sangat teliti dan hasilnya akurat. Kacamata baruku benar-benar pas, tidak pusing saat dipakai.',
        'Frame kacamatanya banyak pilihan dengan berbagai model terkini. Saya menemukan frame yang cocok dengan gaya saya.',
        'Pengiriman cepat dan produk dikemas dengan aman. Kualitas lensa sangat baik dan sesuai dengan resep dokter mata saya.',
        'Sangat puas dengan layanan dan produknya. Sudah merekomendasikan IndoOptik ke teman-teman dan keluarga.',
        'Kacamata progresifnya benar-benar membantu aktivitas sehari-hari saya. Terima kasih IndoOptik!',
        'Lensa anti radiasi yang saya beli sangat membantu mengurangi kelelahan mata saat bekerja di depan layar.',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => null,
            'name'        => fake('id_ID')->name(),
            'role'        => fake()->randomElement($this->customerRoles),
            'message'     => fake()->randomElement($this->messages),
            'rating'      => fake()->numberBetween(4, 5),
            'photo'       => null,
            'status'      => 'published',
            'is_verified' => fake()->boolean(60),
        ];
    }

    /**
     * Indicate that the testimonial belongs to a registered user.
     */
    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
        ]);
    }

    /**
     * Indicate that the testimonial is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'unpublished',
        ]);
    }

    /**
     * Indicate that the testimonial is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }

    /**
     * Set a specific star rating.
     */
    public function rating(int $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => max(1, min(5, $rating)),
        ]);
    }

    /**
     * Five-star testimonial.
     */
    public function fiveStar(): static
    {
        return $this->rating(5);
    }
}
