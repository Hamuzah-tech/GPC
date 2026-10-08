<?php

namespace App\Http\Requests;

use App\Models\EventRegistration;
use App\Support\RegistrationNormalizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:2', 'max:255'],
            'registration_number' => ['required', 'string', 'max:100', function (string $attribute, mixed $value, \Closure $fail) {
                if (RegistrationNormalizer::registrationNumber((string) $value) === '') {
                    $fail('Please enter your registration number.');
                }
            }],
            'phone' => ['required', 'string', 'max:50', function (string $attribute, mixed $value, \Closure $fail) {
                $digits = RegistrationNormalizer::phone((string) $value);
                if (strlen($digits) < 9 || strlen($digits) > 15) {
                    $fail('Please enter a valid phone number.');
                }
            }],
            'email' => ['required', 'email', 'max:255'],
            'year_of_study' => ['required', 'in:'.EventRegistration::YEAR_OF_STUDY],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'full_name.min' => 'Please enter your full name.',
            'registration_number.required' => 'Please enter your registration number.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'year_of_study.required' => 'Year of study is required.',
            'year_of_study.in' => 'This event is only open to Year 1 students.',
        ];
    }
}
