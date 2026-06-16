<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->hasAdminAccess();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "category_id" => ["nullable", "exists:categories,id"],
            "description" => ["nullable", "string"],
            "short_description" => ["nullable", "string", "max:500"],
            "price" => ["required", "integer", "min:0"],
            "discount_price" => ["nullable", "integer", "min:0", "lt:price"],
            "stock" => ["required", "integer", "min:0"],
            "sku" => ["nullable", "string", "max:100", "unique:products,sku"],
            "status" => ["required", "in:active,inactive"],
            "is_featured" => ["nullable", "boolean"],
            "image" => [
                "nullable",
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:2048",
            ],
            "image_url" => ["nullable", "url", "max:1000"],
            "additional_images" => ["nullable", "array", "max:10"],
            "additional_images.*" => [
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:2048",
            ],
            "meta_title" => ["nullable", "string", "max:255"],
            "meta_description" => ["nullable", "string", "max:500"],
            "color_variants" => ["nullable", "array"],
            "color_variants.*.key" => ["nullable", "string", "max:50"],
            "color_variants.*.label" => ["nullable", "string", "max:60"],
            "color_variants.*.color" => ["nullable", "string", "max:20"],
            "color_variants.*.images" => ["nullable", "string", "max:2000"],
            "color_variants.*.image_uploads" => ["nullable", "array", "max:6"],
            "color_variants.*.image_uploads.*" => [
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:2048",
            ],
            "lens_variants" => ["nullable", "array"],
            "lens_variants.*.key" => ["nullable", "string", "max:50"],
            "lens_variants.*.label" => ["nullable", "string", "max:80"],
            "lens_variants.*.desc" => ["nullable", "string", "max:200"],
            "lens_variants.*.price" => ["nullable", "integer", "min:0"],
            "lens_variants.*.icon" => [
                "nullable",
                Rule::in([
                    "fa-solid fa-eye",
                    "fa-solid fa-display",
                    "fa-solid fa-shield-halved",
                    "fa-solid fa-sun",
                    "fa-solid fa-glasses",
                ]),
            ],
            "frame_variants" => ["nullable", "array"],
            "frame_variants.*.key" => ["nullable", "string", "max:50"],
            "frame_variants.*.label" => ["nullable", "string", "max:80"],
            "frame_variants.*.desc" => ["nullable", "string", "max:200"],
            "frame_variants.*.price" => ["nullable", "integer", "min:0"],
            "frame_variants.*.icon" => [
                "nullable",
                Rule::in([
                    "fa-solid fa-glasses",
                    "fa-solid fa-circle",
                    "fa-regular fa-circle",
                    "fa-solid fa-feather",
                    "fa-solid fa-gem",
                ]),
            ],
        ];
    }

    /**
     * Get custom attribute names for validator error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            "name" => "nama produk",
            "category_id" => "kategori",
            "description" => "deskripsi",
            "short_description" => "deskripsi singkat",
            "price" => "harga",
            "discount_price" => "harga diskon",
            "stock" => "stok",
            "sku" => "SKU",
            "status" => "status",
            "is_featured" => "produk unggulan",
            "image" => "gambar utama",
            "additional_images" => "gambar tambahan",
            "meta_title" => "meta title",
            "meta_description" => "meta description",
            "color_variants" => "varian warna",
            "lens_variants" => "varian lensa",
            "frame_variants" => "varian frame",
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "name.required" => "Nama produk wajib diisi.",
            "price.required" => "Harga produk wajib diisi.",
            "price.integer" => "Harga produk harus berupa angka.",
            "price.min" => "Harga produk tidak boleh negatif.",
            "discount_price.lt" =>
                "Harga diskon harus lebih kecil dari harga normal.",
            "stock.required" => "Stok produk wajib diisi.",
            "stock.min" => "Stok produk tidak boleh negatif.",
            "sku.unique" => "SKU sudah digunakan oleh produk lain.",
            "status.required" => "Status produk wajib dipilih.",
            "status.in" => "Status produk harus aktif atau tidak aktif.",
            "image.image" => "File gambar utama harus berupa gambar.",
            "image.mimes" =>
                "Gambar utama harus berformat JPG, JPEG, PNG, atau WebP.",
            "image.max" => "Ukuran gambar utama maksimal 2 MB.",
            "additional_images.max" => "Maksimal 10 gambar tambahan.",
            "additional_images.*.image" =>
                "Setiap gambar tambahan harus berupa file gambar.",
            "additional_images.*.mimes" =>
                "Gambar tambahan harus berformat JPG, JPEG, PNG, atau WebP.",
            "additional_images.*.max" =>
                "Ukuran setiap gambar tambahan maksimal 2 MB.",
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure discount_price is null when empty string is submitted
        if (
            $this->input("discount_price") === "" ||
            $this->input("discount_price") === null
        ) {
            $this->merge(["discount_price" => null]);
        }

        // Normalize is_featured to boolean
        $this->merge([
            "is_featured" => $this->boolean("is_featured"),
        ]);
    }
}
