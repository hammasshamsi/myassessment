<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\OnboardingSession;

class Step3Request extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'subdomain'=>[
                'required',
                'string',
                'max:255',
                'alpha_num',
                'max:20',
                function ($attribute, $value, $fail) {
                    $reserved=['admin','user', 'login','dashboard', 'api', 'auth', 'web', 'app', 'home', 'support', 'contact','tenant','landlord','www','http','https','com','controller','model'];
                    if(in_array(strtolower($value), $reserved)) {
                        $fail("The Subdomain'{$value}' is reserved and cannot be used.");
                    }
                    $token = session('onboarding_token');
                    $query = OnboardingSession::where('subdomain', $value);
                    if ($token) {
                        $query->where('token', '!=', $token);
                    }

                    if ($query->exists()) {
                        $fail("This subdomain is already taken.");
                    }
                }
            ],
        ];
    }
}
