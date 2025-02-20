<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class UserRequest extends FormRequest
{
    use ConvertsBase64ToFiles; // Library untuk convert base64 menjadi File

    public $validator;

    /**
     * Setting custom attribute pesan error yang ditampilkan
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'password' => 'Kolom Password',
        ];
    }

    /**
     * Tampilkan pesan error ketika validasi gagal
     *
     * @return void
     */
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
            'name' => 'required|max:100',
            'photo' => 'nullable|file|image',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone_number' => 'numeric',
            'user_roles_id' => 'required',
        ];
    }

    private function updateRules(): array
    {
        return [
            'name' => 'required|max:100',
            'photo' => 'nullable|file|image',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'phone_number' => 'numeric',
            'user_roles_id' => 'required',
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
