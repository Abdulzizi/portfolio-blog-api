<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class UserRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    public $validator;

    public function attributes()
    {
        return [
            'password' => 'Kolom Password',
            'username' => 'Kolom Username',
            'email' => 'Kolom Email',
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
            'username' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $this->route('id'),
            'password' => 'required|min:6',
        ];
    }

    private function updateRules(): array
    {
        return [
            'username' => 'sometimes|nullable|max:100',
            'email' => 'sometimes|nullable|email|unique:users,email,' . $this->route('id'),
            'password' => 'sometimes|nullable|min:6',
        ];
    }

    /**
     * inisialisasi key "photo" dengan value base64 sebagai "FILE"
     */
    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'foto-user.jpg',
        ];
    }
}
