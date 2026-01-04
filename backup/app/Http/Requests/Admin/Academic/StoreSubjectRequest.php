<?php

namespace App\Http\Requests\Admin\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:academic_subjects,code,NULL,id,school_id,' . (auth()->user()->school_id ?? 'NULL')],
            'type' => ['required', 'string', 'in:theory,practical,lab'],
            'credit_hours' => ['nullable', 'integer', 'min:0', 'max:10'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}


