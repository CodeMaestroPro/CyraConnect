<?php

namespace App\Http\Requests\Startup;

use App\Enums\BusinessModel;
use App\Enums\FundingStage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStartupProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $startup = $this->route('organization')?->startup;

        return $startup && ($this->user()?->can('update', $startup) ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'funding_stage' => ['nullable', Rule::enum(FundingStage::class)],
            'total_funding' => ['nullable', 'numeric', 'min:0'],
            'last_funding_date' => ['nullable', 'date'],
            'last_funding_amount' => ['nullable', 'numeric', 'min:0'],
            'business_model' => ['nullable', Rule::enum(BusinessModel::class)],
            'revenue_model' => ['nullable', 'string', 'max:100'],
            'monthly_users' => ['nullable', 'integer', 'min:0'],
            'monthly_revenue' => ['nullable', 'integer', 'min:0'],
            'is_hiring' => ['sometimes', 'boolean'],
            'is_raising' => ['sometimes', 'boolean'],
            'target_raise' => ['nullable', 'numeric', 'min:0'],
            'sectors' => ['nullable', 'array'],
            'sectors.*' => ['integer', 'exists:sectors,id'],
        ];
    }
}
