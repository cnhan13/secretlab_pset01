<?php


namespace App\Http\Requests;


class ShowEntryRequest extends ApiRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'timestamp' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'timestamp.integer' => 'Timestamp must be a valid number'
        ];
    }
}
