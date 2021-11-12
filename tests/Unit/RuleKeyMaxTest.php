<?php


namespace Tests\Unit;


use App\Rules\KeyMax;
use PHPUnit\Framework\TestCase;

class RuleKeyMaxTest extends TestCase
{
    public function testKeyMaxLengthPasses()
    {
        $key = "test";
        $rule = new KeyMax(strlen($key));
        $this->assertTrue($rule->passes($key, ''));
    }

    public function testKeyLengthExceedMaxIsInvalid()
    {
        $key = "test";
        $rule = new KeyMax(strlen($key) - 1);
        $this->assertFalse($rule->passes($key, ''));
    }
}
