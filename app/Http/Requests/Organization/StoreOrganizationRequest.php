<?php

namespace App\Http\Requests\Organization;

use App\Enums\EmployeeCount;
use App\Enums\OrganizationType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('organizations.create') ?? false;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return $this->organizationRules();
    }

    /** @return array<string, mixed> */
    protected function organizationRules(): array
    {
        return [
            'type' => ['required', Rule::enum(OrganizationType::class)],
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'state_id' => ['nullable', 'exists:states,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'founded_year' => ['nullable', 'integer', 'min:1800', 'max:'.date('Y')],
            'employee_count' => ['nullable', Rule::enum(EmployeeCount::class)],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
