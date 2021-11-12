<?php

namespace Tests\Unit;

use App\Models\Entry;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class EntryTest extends TestCase
{
    public function testEntryTable()
    {
        $entry = new Entry();
        $this->assertEquals($entry->getTable(), Entry::table());
    }

    public function testIsValidTimestampGivenValidIntegerTimestampShouldReturnTrue()
    {
        $input = Carbon::now()->timestamp;
        $actual = Entry::isValidTimestamp($input);
        $this->assertTrue($actual);
    }

    public function testIsValidTimestampGivenValidStringTimestampShouldReturnTrue()
    {
        $input = '' . Carbon::now()->timestamp;
        $actual = Entry::isValidTimestamp($input);
        $this->assertTrue($actual);
    }

    public function testIsValidTimestampGivenStringContainingLetterShouldReturnFalse()
    {
        $input = Carbon::now()->timestamp . 'a';
        $actual = Entry::isValidTimestamp($input);
        $this->assertFalse($actual);
    }

    public function testIsValidTimestampGivenStringContainingFloatingPointNumberShouldReturnFalse()
    {
        $input = Carbon::now()->timestamp . '.1';
        $actual = Entry::isValidTimestamp($input);
        $this->assertFalse($actual);
    }

    public function testIsValidTimestampGivenStringContainingZeroShouldReturnTrue()
    {
        $input = '0';
        $actual = Entry::isValidTimestamp($input);
        $this->assertTrue($actual);
    }

    public function testIsValidTimestampGivenStringContainingNegativeNumberShouldReturnTrue()
    {
        $input = '-' . Carbon::now()->timestamp;
        $actual = Entry::isValidTimestamp($input);
        $this->assertTrue($actual);
    }

    public function testGetCarbonTimeFromTimestampOrNowWithNullTimestamp()
    {
        $before = Carbon::now();
        $actual = Entry::getCarbonTimeFromTimestampOrNow();
        $after = Carbon::now();
        $this->assertGreaterThanOrEqual($before, $actual);
        $this->assertLessThanOrEqual($after, $actual);
    }

    public function testGetCarbonTimeFromTimestampOrNowWithValidTimestamp()
    {
        $timestamp = 1635514022;
        $expect = Carbon::createFromTimestamp($timestamp);
        $actual = Entry::getCarbonTimeFromTimestampOrNow($timestamp);
        $this->assertEquals($expect, $actual);
    }

    public function testGetCarbonTimeFromTimestampOrNowWithTimestampZero()
    {
        $timestamp = 0;
        $expect = Carbon::createFromTimestamp($timestamp);
        $actual = Entry::getCarbonTimeFromTimestampOrNow($timestamp);
        $this->assertEquals($expect, $actual);
    }

    public function testGetCarbonTimeFromTimestampOrNowWithTimestampNegative()
    {
        $timestamp = -2;
        $expect = Carbon::createFromTimestamp($timestamp);
        $actual = Entry::getCarbonTimeFromTimestampOrNow($timestamp);
        $this->assertEquals($expect, $actual);
    }
}
