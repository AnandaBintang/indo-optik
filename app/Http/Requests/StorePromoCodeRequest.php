<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
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
            "code" => [
                "required",
                "string",
                "max:50",
                "unique:promo_codes,code",
                'regex:/^[A-Z0-9_\-]+$/',
            ],
            "label" => ["nullable", "string", "max:255"],
            "type" => ["required", "in:percentage,fixed"],
            "value" => ["required", "numeric", "min:0"],
            "max_discount" => ["nullable", "numeric", "min:0"],
            "min_purchase" => ["nullable", "numeric", "min:0"],
            "expired_at" => ["nullable", "date", "after:today"],
            "usage_limit" => ["nullable", "integer", "min:1"],
            "is_active" => ["nullable", "boolean"],
        ];
    }

    /**
     * Additional validation after the base rules have passed.
     */
    public function withValidator(
        \Illuminate\Validation\Validator $validator,
    ): void {
        $validator->after(function (\Illuminate\Validation\Validator $v) {
            $type = $this->input("type");
            $value = (float) $this->input("value", 0);

            // Percentage discount value must not exceed 100
            if ($type === "percentage" && $value > 100) {
                $v->errors()->add(
                    "value",
                    "Nilai diskon persentase tidak boleh melebihi 100%.",
                );
            }

            // max_discount only makes sense for percentage type
            if ($type === "fixed" && $this->filled("max_discount")) {
                $v->errors()->add(
                    "max_discount",
                    "Batas maksimum diskon hanya berlaku untuk tipe persentase.",
                );
            }
        });
    }

    /**
     * Get custom human-readable attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            "code" => "kode promo",
            "label" => "label",
            "type" => "tipe diskon",
            "value" => "nilai diskon",
            "max_discount" => "batas maksimum diskon",
            "min_purchase" => "minimum pembelian",
            "expired_at" => "tanggal kedaluwarsa",
            "usage_limit" => "batas penggunaan",
            "is_active" => "status aktif",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "code.required" => "Kode promo wajib diisi.",
            "code.unique" => "Kode promo sudah digunakan.",
            "code.regex" =>
                "Kode promo hanya boleh berisi huruf kapital, angka, underscore, dan strip.",
            "type.required" => "Tipe diskon wajib dipilih.",
            "type.in" => "Tipe diskon harus percentage atau fixed.",
            "value.required" => "Nilai diskon wajib diisi.",
            "value.numeric" => "Nilai diskon harus berupa angka.",
            "value.min" => "Nilai diskon tidak boleh negatif.",
            "expired_at.date" => "Format tanggal kedaluwarsa tidak valid.",
            "expired_at.after" => "Tanggal kedaluwarsa harus setelah hari ini.",
            "usage_limit.integer" =>
                "Batas penggunaan harus berupa bilangan bulat.",
            "usage_limit.min" => "Batas penggunaan minimal 1.",
        ];
    }

    /**
     * Prepare the data for validation.
     * Normalize the code to uppercase before validation runs.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled("code")) {
            $this->merge([
                "code" => strtoupper(trim($this->input("code"))),
            ]);
        }

        // Normalize boolean field
        $this->merge([
            "is_active" => $this->boolean("is_active"),
        ]);

        // Treat empty strings as null for nullable numerics
        foreach (["max_discount", "min_purchase", "usage_limit"] as $field) {
            if ($this->input($field) === "") {
                $this->merge([$field => null]);
            }
        }

        if ($this->input("expired_at") === "") {
            $this->merge(["expired_at" => null]);
        }
    }
}
