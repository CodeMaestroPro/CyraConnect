<?php

namespace App\Http\Requests\Profile;

use App\Enums\SkillProficiency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'skill_id' => ['required', 'exists:skills,id'],
            'proficiency' => ['required', Rule::enum(SkillProficiency::class)],
            'years' => ['nullable', 'integer', 'min:0', 'max:50'],
        ];
    }
}
