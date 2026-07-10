<?php

namespace App\Http\Requests\Organization;

use App\Enums\OrganizationMemberRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manageMembers', $this->route('organization')) ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'role' => ['required', Rule::enum(OrganizationMemberRole::class)->except([OrganizationMemberRole::Owner])],
            'title' => ['nullable', 'string', 'max:100'],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'email.exists' => 'No CyraConnect account found with that email address.',
        ];
    }
}
