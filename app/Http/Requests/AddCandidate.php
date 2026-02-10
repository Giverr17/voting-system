<?php

namespace App\Http\Requests;

use App\Enums\CandidatePosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AddCandidate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'position_applied' => ['required', new Enum(CandidatePosition::class)],
            'department' => ['required', 'string'],
            'mat_no' => [
                'required',
                'size:10',
                Rule::unique('candidates', 'mat_no')->ignore($this->route('id')),
            ],
            'level' => ['required'],
            'slogan' => ['required', 'string'],
            'image' => $this->isMethod('put') || $this->isMethod('patch')
                ? 'nullable|image|mimes:jpeg,png,jpg|max:2048'
                : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
