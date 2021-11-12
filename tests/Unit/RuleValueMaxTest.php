<?php


namespace Tests\Unit;


use App\Rules\ValueMax;
use PHPUnit\Framework\TestCase;

class RuleValueMaxTest extends TestCase
{
    public function testValueMaxLengthPasses()
    {
        $value = "test";
        $rule = new ValueMax(strlen($value));
        $this->assertTrue($rule->passes('aKey', $value));
    }

    public function testValueLengthExceedMaxIsInvalid()
    {
        $value = "test";
        $rule = new ValueMax(strlen($value) - 1);
        $this->assertFalse($rule->passes('aKey', $value));
    }
}
