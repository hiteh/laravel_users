<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\UsersRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
    public function rules( UsersRepositoryInterface $users, Rule $rule, Auth $auth )
    {
        return [
            'name'     => ['sometimes','required', 'string', 'max:255'],
            'email'    => ['sometimes','required', 'string', 'email', 'max:255', 'unique:users,email,'. $auth->user()->id ],
            'password' => ['sometimes','required', 'string', 'min:8', 'confirmed'],
            'role'     => ['sometimes','required', 'string', $rule->in( $users->roles()->pluck('name') ) ],
            'avatar'   => ['sometimes', 'required', 'image'],
        ];
    }
}
