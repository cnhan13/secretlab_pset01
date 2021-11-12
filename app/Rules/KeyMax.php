<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KeyMax implements Rule
{
    private $length;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strlen($attribute) <= $this->length;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Maximum key length is {$this->length} character" . ($this->length > 1 ? "s" : "");
    }
}
