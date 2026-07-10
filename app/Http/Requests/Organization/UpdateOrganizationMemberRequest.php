<?php

namespace App\Http\Requests\Organization;

use App\Enums\OrganizationMemberRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manageMembers', $this->route('organization')) ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::enum(OrganizationMemberRole::class)->except([OrganizationMemberRole::Owner])],
            'title' => ['nullable', 'string', 'max:100'],
            'is_public' => ['sometimes', 'boolean'],
        ];
    }
}
