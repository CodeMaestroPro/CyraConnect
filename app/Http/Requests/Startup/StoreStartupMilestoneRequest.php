<?php

namespace App\Http\Requests\Startup;

use Illuminate\Foundation\Http\FormRequest;

class StoreStartupMilestoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        $startup = $this->route('organization')?->startup;

        return $startup && ($this->user()?->can('manageMilestones', $startup) ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'achieved_at' => ['required', 'date'],
        ];
    }
}
