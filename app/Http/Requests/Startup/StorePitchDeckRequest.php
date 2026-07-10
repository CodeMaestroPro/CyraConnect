<?php

namespace App\Http\Requests\Startup;

use Illuminate\Foundation\Http\FormRequest;

class StorePitchDeckRequest extends FormRequest
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
            'pitch_deck' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
