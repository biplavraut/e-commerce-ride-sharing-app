<?php

namespace App\Http\Requests\Api;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class SendItemsRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|min:3,max:50|unique:send_items,name',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->fails() && $this->expectsJson()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            throw new HttpResponseException(
                response()->json(['status' => false, 'message' => $errors, 'statusCode' => 422], 422)
            );
        }
        parent::failedValidation($validator);
    }
}