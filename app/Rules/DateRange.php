<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateRange implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($form ,$to)
    {
        $this->start = $form; 
        $this->end = $to;
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
        //
        if($this->start <= $this->end && $this->end >= $this->start)
		{
			return true;
		}
		
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The valid dates intersect each other.';
    }
}
