<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\User;
use App\Role;

class CreateUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'present', 'min:6'],
            'bio' => 'required',
            'twitter' => ['nullable', 'present', 'url'],
            'profession_id' => ['nullable', 'present', Rule::exists('professions', 'id')],
            'role' => ['nullable', Rule::in(Role::getList())],
            'skills' => ['array', Rule::exists('skills', 'id')],
            'state' => ['required', Rule::in(['active', 'inactive'])]
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El campo nombre es obligatorio',
            'last_name.required' => 'El campo apellido es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El campo email debe ser válido',
            'email.unique' => 'El campo email debe ser único',
            'password.required' => 'El campo password debe ser obligatorio',
            'password.min' => 'El campo password debe tener mínimo 6 caracteres',
            'bio.required' => 'El campo bio es obligatorio',
            'profession_id.exists' => 'El campo profesión debe ser válido',
            'profession_id.present' => 'El campo profesión debe estar presente',
            'twitter.url' => 'El campo twitter debe ser una url válida',
            'role.in' => 'El rol debe ser válido',
            'state.in' => 'El estado debe ser válido',
            'state.required' => 'El estado es obligatorio'
        ];
    }

    public function createUser()
    {
        DB::transaction(function () {

            $user = new User();

            $user->forceFill([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role' => $this->role ?? 'user',
                'state' => $this->state
            ]);

            $user->save();
    
            $user->profile()->create([
                'bio' => $this->bio,
                'twitter' => $this->twitter,
                'profession_id' => $this->profession_id ?? null
            ]);

            if(! empty($this->skills)) {
                $user->skills()->attach($this->skills);
            }
        });
    }
}
