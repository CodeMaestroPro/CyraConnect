<?php

namespace App\Http\Requests\Organization;

class UpdateOrganizationRequest extends StoreOrganizationRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('organization')) ?? false;
    }
}
