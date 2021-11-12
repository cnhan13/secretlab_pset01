<?php


namespace App\Http\Requests;


use App\Models\Entry;
use App\Rules\KeyMax;
use App\Rules\KeyRequired;
use App\Rules\ValueMax;

class StoreEntryRequest extends ApiRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            '*' => [new KeyRequired(), new KeyMax(255), 'sometimes', new ValueMax(2000)],
        ];
    }

    public function messages()
    {
        return Entry::$messages;
    }
}
