<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OnboardingSession;
use Illuminate\Validation\Rule;
use App\Models\Tenant;

class Step1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // so for pucblic onboarding no need of authorize
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'full_name' => 'required|string|max:255',
           'email' => [
                'required',
                'email',
                'max:255',
                // Rule::unique('tenants')->ignore($this->route('tenant')), // ignore if updating tenant

                function($attribute, $value, $fail) {
                    $currentToken = session('onboarding_token');
                    $query = OnboardingSession::where('email', $value);
                    if ($currentToken) {
                        $query->where('token', '!=', $currentToken);
                    }
                    if ($query->exists()) {
                        $fail('The email has already been taken.');
                    }
                    // if(Tenant::where('subdomain',$value)->exists()) {
                    //     $fail('The subdomain has already been taken.');
                    // }
                },

            ],
           
        ];
    }
}
