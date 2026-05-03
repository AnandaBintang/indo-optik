<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'name'   => ['required', 'string', 'max:255'],
            'role'   => ['required', 'string', 'max:255'],
            'photo'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'status' => ['nullable', 'in:published,unpublished'],
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
            'name'   => 'nama',
            'role'   => 'jabatan / peran',
            'photo'  => 'foto',
            'status' => 'status',
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
            'name.required'  => 'Nama wajib diisi.',
            'role.required'  => 'Jabatan / peran wajib diisi.',
            'photo.image'    => 'File foto harus berupa gambar.',
            'photo.mimes'    => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'      => 'Ukuran foto maksimal 1 MB.',
            'status.in'      => 'Status harus published atau unpublished.',
        ];
    }

    /**
     * Prepare the data for validation.
     * Default status to 'published' when not provided.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('status') || $this->input('status') === null) {
            $this->merge(['status' => 'published']);
        }
    }
}
