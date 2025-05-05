<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;


class BlogPostRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    public $validator;

    public function attributes()
    {
        return [
            'title' => 'Kolom Judul',
            'content' => 'Kolom Konten',
            'cover_image' => 'Cover Image',
            'is_published' => 'Status Publikasi',
            'tags' => 'Tag',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }

        return $this->updateRules();
    }

    private function createRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'cover_image' => 'nullable|string',
            'is_published' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'uuid|exists:tags,id',
        ];
    }

    private function updateRules(): array
    {
        return [
            'title' => 'nullable|nullable|string|max:255',
            'content' => 'nullable|nullable|string',
            'cover_image' => 'nullable|string',
            'is_published' => 'nullable|boolean',

            'tags' => 'nullable|array',
            'tags.*' => 'uuid|exists:tags,id',

            'tags_to_add' => 'sometimes|array',
            'tags_to_add.*' => 'uuid|exists:tags,id',

            'tags_to_remove' => 'sometimes|array',
            'tags_to_remove.*' => 'uuid|exists:tags,id',
        ];
    }

    /**
     * Inisialisasi key "cover_image" sebagai FILE dari base64
     */
    protected function base64FileKeys(): array
    {
        return [
            'cover_image' => 'cover.jpg',
        ];
    }
}