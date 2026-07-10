<?php

namespace App\Http\Requests\Startup;

use Illuminate\Foundation\Http\FormRequest;

class StoreStartupMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $startup = $this->route('organization')?->startup;

        return $startup && ($this->user()?->can('manageMedia', $startup) ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'media' => ['required', 'image', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:255'],
        ];
    }
}
