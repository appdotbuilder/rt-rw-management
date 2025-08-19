<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHouseholdRequest extends FormRequest
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
            'house_number' => 'required|string|max:20',
            'rt_number' => 'required|string|max:5',
            'rw_number' => 'required|string|max:5',
            'head_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
            'status' => 'required|in:active,inactive,moved',
            'resident_count' => 'required|integer|min:1',
            'monthly_contribution' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'house_number.required' => 'House number is required.',
            'rt_number.required' => 'RT number is required.',
            'rw_number.required' => 'RW number is required.',
            'head_name.required' => 'Household head name is required.',
            'address.required' => 'Address is required.',
            'resident_count.required' => 'Number of residents is required.',
            'resident_count.min' => 'At least 1 resident is required.',
            'monthly_contribution.required' => 'Monthly contribution amount is required.',
            'monthly_contribution.min' => 'Monthly contribution cannot be negative.',
        ];
    }
}