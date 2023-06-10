<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('project'));
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email']
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'The user you are inviting must have a Birdboard account.'
        ];
    }
}
