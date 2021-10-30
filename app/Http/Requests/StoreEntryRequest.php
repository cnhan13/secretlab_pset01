<?php


namespace App\Http\Requests;


use App\Models\Entry;

class StoreEntryRequest extends ApiRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return Entry::$rules;
    }

    protected function prepareForValidation()
    {
        $input = $this->all();
        $this->replace(empty($input) ? [] : $this->getFormattedData($input));
    }

    private function getFormattedData($input)
    {
        foreach ($input as $key => $value)
            break;
        return [
            'entry_key' => $key,
            'value' => $value
        ];
    }

    public function messages()
    {
        return Entry::$messages;
    }
}
