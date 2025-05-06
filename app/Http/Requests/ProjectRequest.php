<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ProjectRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    public $validator;

    public function attributes(): array
    {
        return [
            'title' => 'Judul Project',
            'description' => 'Deskripsi',
            'link' => 'Link',
            'thumbnail' => 'Thumbnail',
            'images' => 'Gambar Project',
            'tech_stack' => 'Teknologi Digunakan',
            'is_published' => 'Status Publikasi',
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
            'description' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'tech_stack' => 'nullable|array',
            'tech_stack.*' => 'string|max:100',
            'is_published' => 'boolean',
        ];
    }

    private function updateRules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'tech_stack' => 'nullable|array',
            'tech_stack.*' => 'string|max:100',
            'is_published' => 'nullable|boolean',
        ];
    }

    /**
     * Inisialisasi key file base64
     */
    protected function base64FileKeys(): array
    {
        return [
            'thumbnail' => 'thumbnail.jpg',
            'images.*' => 'image.jpg', // for multiple images in base64
        ];
    }
}
