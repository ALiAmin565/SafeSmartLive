<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAffiliateCreationRequest extends FormRequest
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
        // protected $fillable = ['affiliate_code', 'affiliate_link', 'name', 'status'];
        return [
            'affiliate_code' => 'required | string | max:255 | unique:affiliate_creations',
            'affiliate_link' => 'required | string | max:255 | unique:affiliate_creations',
            'name' => 'required | string | max:255',
            'status' => 'nullable   | boolean',

        ];
    }
}
