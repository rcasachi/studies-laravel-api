<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JSONAPIRelationshipRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'data' => 'present|array|nullable',

            'data.id' => [Rule::requiredIf($this->has('data.type')), 'string'],
            'data.type' => [Rule::requiredIf($this->has('data.id')), Rule::in(array_keys(config('jsonapi.resources')))],

            'data.*.id' => [Rule::requiredIf($this->has('data.0')), 'string'],
            'data.*.type' => [Rule::requiredIf($this->has('data.0')), Rule::in(array_keys(config('jsonapi.resources')))],
        ];
    }
}
