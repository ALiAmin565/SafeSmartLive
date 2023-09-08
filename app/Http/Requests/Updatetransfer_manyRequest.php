<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
 use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class Updatetransfer_manyRequest extends FormRequest
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
            'transaction_id'=>'required|exists:transfer_manies,transaction_id',
             
            'status'=>'required|in:success,pending,declined'
        ];
    }


    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([

            'success'   => false,

            'message'   => 'Validation errors',

            'data'      => $validator->errors()

        ]));

    }
}
