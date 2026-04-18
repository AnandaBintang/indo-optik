<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        // Resolve the product ID from the route parameter.
        // The route may bind as {product} (resource route) or {id}.
        $productId =
            $this->route("product") instanceof \App\Models\Product
                ? $this->route("product")->id
                : (int) ($this->route("product") ?? $this->route("id"));

        return [
            "name" => ["required", "string", "max:255"],

            "category_id" => ["nullable", Rule::exists("categories", "id")],

            "description" => ["nullable", "string"],

            "short_description" => ["nullable", "string", "max:500"],

            "price" => ["required", "integer", "min:0"],

            "discount_price" => ["nullable", "integer", "min:0", "lt:price"],

            "stock" => ["required", "integer", "min:0"],

            "sku" => [
                "nullable",
                "string",
                "max:100",
                Rule::unique("products", "sku")
                    ->ignore($productId)
                    ->whereNull("deleted_at"),
            ],

            "status" => ["required", "in:active,inactive"],

            "is_featured" => ["nullable", "boolean"],

            // Primary image is optional on update (keep existing if not provided)
            "image" => [
                "nullable",
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:2048",
            ],

            "image_url" => ["nullable", "url", "max:1000"],

            // Additional gallery images
            "additional_images" => ["nullable", "array", "max:10"],

            "additional_images.*" => [
                "image",
                "mimes:jpg,jpeg,png,webp",
                "max:2048",
            ],

            // IDs of existing additional images to remove
            "delete_images" => ["nullable", "array"],

            "delete_images.*" => [
                "integer",
                Rule::exists("product_images", "id"),
            ],

            "meta_title" => ["nullable", "string", "max:255"],

            "meta_description" => ["nullable", "string", "max:500"],
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
            "delete_images" => "gambar yang dihapus",
            "meta_title" => "meta title",
            "meta_description" => "meta description",
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
            "delete_images.*.exists" =>
                "Salah satu gambar yang dipilih untuk dihapus tidak ditemukan.",
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
