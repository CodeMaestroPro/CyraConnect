<?php

namespace App\Http\Requests\Student;

use App\Enums\StudentApplicationStatus;
use App\Enums\StudentApplicationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('student');
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(StudentApplicationType::class)],
            'status' => ['nullable', Rule::enum(StudentApplicationStatus::class)],
            'applied_at' => ['nullable', 'date'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
