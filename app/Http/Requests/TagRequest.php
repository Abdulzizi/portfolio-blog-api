<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TagRequest extends FormRequest
{
    public $validator;

    public function attributes()
    {
        return [
            'name' => 'Kolom Nama',
            'description' => 'Description',
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
