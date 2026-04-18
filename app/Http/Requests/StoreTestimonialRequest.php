<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'role'    => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
            'rating'  => ['required', 'integer', 'between:1,5'],
            'photo'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'status'  => ['nullable', 'in:published,unpublished'],
        ];
    }

    /**
     * Get custom human-readable attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'    => 'nama',
            'role'    => 'jabatan / peran',
            'message' => 'pesan testimoni',
            'rating'  => 'rating',
            'photo'   => 'foto',
            'status'  => 'status',
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
            'name.required'    => 'Nama wajib diisi.',
            'message.required' => 'Pesan testimoni wajib diisi.',
            'message.min'      => 'Pesan testimoni minimal 10 karakter.',
            'rating.required'  => 'Rating wajib diisi.',
            'rating.between'   => 'Rating harus antara 1 hingga 5.',
            'photo.image'      => 'File foto harus berupa gambar.',
            'photo.mimes'      => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'        => 'Ukuran foto maksimal 1 MB.',
            'status.in'        => 'Status harus published atau unpublished.',
        ];
    }

    /**
     * Prepare the data for validation.
     * Default status to 'unpublished' when not provided.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('status') || $this->input('status') === null) {
            $this->merge(['status' => 'unpublished']);
        }
    }
}
