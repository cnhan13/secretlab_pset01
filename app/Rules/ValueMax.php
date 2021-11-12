<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValueMax implements Rule
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
        return strlen($value) <= $this->length;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Maximum value length is {$this->length} character" . ($this->length > 1 ? "s" : "");
    }
}
