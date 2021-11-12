<?php


namespace Tests\Unit;


use App\Rules\KeyMax;
use App\Rules\KeyRequired;
use PHPUnit\Framework\TestCase;

class RuleKeyRequiredTest extends TestCase
{
    public function testKeyExistsPasses()
    {
        $key = "test";
        $rule = new KeyRequired();
        $this->assertTrue($rule->passes($key, ''));
    }

    public function testKeyEmptyIsInvalid()
    {
        $key = "";
        $rule = new KeyRequired();
        $this->assertFalse($rule->passes($key, ''));
    }
}
