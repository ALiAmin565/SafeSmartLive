<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAffiliateCreationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'affiliate_code' => 'string | max:255 | unique:affiliate_creations',
            // 'affiliate_link' => 'string | max:255 | unique:affiliate_creations',
            // 'name' => 'string | max:255',
            // 'status' => 'nullable | boolean',
        ];
    }
}
