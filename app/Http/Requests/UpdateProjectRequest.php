<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProjectRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('project'));
    }
    
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'notes' => ['nullable'],
        ];
    }
}
