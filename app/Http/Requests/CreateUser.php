<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Interfaces\RolesRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;

class CreateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules( RolesRepositoryInterface $roles )
    {
        return [
            'name'     => ['sometimes','required', 'string', 'max:255'],
            'email'    => ['sometimes','required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['sometimes','required', 'string', 'min:8', 'confirmed'],
            'role'     => ['sometimes','required', 'string', Rule::in( $roles->getAvailableRolesList() ) ],
            'avatar'   => ['sometimes', 'required', 'image'],
        ];
    }
}
