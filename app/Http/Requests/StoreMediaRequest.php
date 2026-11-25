<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('PATCH') ? 'sometimes' : 'required';
        $isCreate = ! $this->isMethod('PATCH');

        return [
            'title' => [
                $required,
                'string',
                'max:255',
                Rule::unique('media', 'title')->ignore($this->route('media')),
            ],
            'type' => [$required, Rule::in(['Drama', 'Film'])],
            'genre' => [$required, 'string', 'max:100'],
            'year' => [$required, 'integer', 'between:1900,2100'],
            'rating' => [$required, 'numeric', 'between:0,10'],
            'episodes' => [
                'nullable',
                'integer',
                'min:1',
                Rule::requiredIf(fn () => $isCreate && $this->input('type') === 'Drama'),
            ],
            'duration' => [
                'nullable',
                'integer',
                'min:1',
                Rule::requiredIf(fn () => $isCreate && $this->input('type') === 'Film'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul media wajib diisi',
            'title.unique' => 'Judul media sudah ada',
            'type.required' => 'Tipe media wajib diisi',
            'type.in' => 'Tipe media harus Drama atau Film',
            'genre.required' => 'Genre wajib diisi',
            'year.required' => 'Tahun rilis wajib diisi',
            'rating.between' => 'Rating harus antara 0 dan 10',
            'episodes.required' => 'Jumlah episode wajib diisi untuk Drama',
            'duration.required' => 'Durasi (menit) wajib diisi untuk Film',
        ];
    }
}
