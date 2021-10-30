<?php

namespace Tests\Feature;

use App\Models\Entry;
use Carbon\Carbon;
use Database\Factories\EntryFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class EntryTest extends TestCase
{
    use DatabaseMigrations;

    public function testDatabaseCreateSimpleEntry()
    {
        $entry = ['entry_key' => 'mykey', 'value' => 'value'];
        Entry::create($entry);
        $this->assertDatabaseHas('entries', $entry);
    }

    public function testGetEntryValueAtTimestampGivenExistingKeyAndEarlierCarbonTimeShouldReturnKeyNotFound()
    {
        $factory = new EntryFactory();

        $carbonTime = Carbon::now();

        sleep(2);
        /** @var Entry $entry */
        $entry = $factory->create();

        $this->assertDatabaseHas('entries', $entry->toArray());

        $actual = Entry::getEntryValueAtTimestamp($entry->entry_key, $carbonTime);
        $actual = is_array($actual) ? $actual : $actual->toArray();

        $this->assertEquals([
            'success' => false,
            'message' => 'Key not found',
        ], $actual);
    }

    public function testGetEntryValueAtTimestampGivenTwoValuesOfExistingKeyAndCarbonTimeFromFirstCreatedAtToBeforeSecondCreatedAtShouldReturnFirstValue()
    {
        $factory = new EntryFactory();

        /** @var Entry $entry */
        $entry = $factory->create();

        $this->assertDatabaseHas('entries', $entry->toArray());

        $carbonTimes = [];
        for ($i = 0; $i < 3; $i++) {
            $carbonTimes[] = Carbon::now();
            sleep(1);
        }

        $entry2 = $factory->create(['entry_key' => $entry->entry_key]);

        $this->assertDatabaseHas('entries', $entry2->toArray());

        sleep(1);

        $expect = ['value' => $entry->value];
        foreach ($carbonTimes as $carbonTime) {
            $actual = Entry::getEntryValueAtTimestamp($entry->entry_key, $carbonTime);
            $actual = is_array($actual) ? $actual : $actual->toArray();

            $this->assertEquals($expect, $actual);
        }
    }

    public function testGetEntryValueAtTimestampGivenTwoValuesOfExistingKeyAndCarbonTimeFromSecondCreatedAtOnwardsShouldReturnSecondValue()
    {
        $factory = new EntryFactory();

        /** @var Entry $entry */
        $entry = $factory->create();

        $this->assertDatabaseHas('entries', $entry->toArray());

        $entry2 = $factory->create(['entry_key' => $entry->entry_key]);

        $this->assertDatabaseHas('entries', $entry2->toArray());

        $carbonTime = Carbon::now();

        $actual = Entry::getEntryValueAtTimestamp($entry->entry_key, $carbonTime);
        $actual = is_array($actual) ? $actual : $actual->toArray();

        $this->assertEquals(['value' => $entry2->value], $actual);
    }

    public function testStoreEmptyJson()
    {
        $response = $this->postJson('/object', []);
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data' => [
                    'entry_key' => [
                        'Key is required'
                    ]
                ]
            ]);
    }

    public function testStoreEmptyKey()
    {
        $response = $this->postJson('/object', ["" => "test value"]);
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data' => [
                    'entry_key' => [
                        'Key is required'
                    ]
                ]
            ]);
    }

    public function testStoreValidKeyEmptyValue()
    {
        $data = ['mykey' => ''];
        $response = $this->postJson('/object', $data);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => 'mykey',
                'value' => $data['mykey'],
            ]);
    }

    public function testStoreValidKeyNullValue()
    {
        $data = ['mykey' => null];
        $response = $this->postJson('/object', $data);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => 'mykey',
                'value' => $data['mykey'],
            ]);
    }

    public function testStoreValidKeyNonEmptyValue()
    {
        $data = ['mykey' => 'value1'];
        $response = $this->postJson('/object', $data);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => 'mykey',
                'value' => $data['mykey'],
            ]);
    }

    public function testStoreKeyLongerThan255Chars()
    {
        $data = [str_repeat('*', 256) => 'value1'];
        $response = $this->postJson('/object', $data);
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data' => [
                    'entry_key' => [
                        'Maximum key length is 255 characters'
                    ]
                ]
            ]);
    }

    public function testStoreValueLongerThan2000Chars()
    {
        $data = ['mykey' => str_repeat('*', 2001)];
        $response = $this->postJson('/object', $data);
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'success' => false,
                'message' => 'Validation errors',
                'data' => [
                    'value' => [
                        'Maximum value length is 2000 characters'
                    ]
                ]
            ]);
    }

    public function testShowValueJustInserted()
    {
        $factory = new EntryFactory();
        /** @var Entry $entry */
        $entry = $factory->make();
        $data = [$entry->entry_key => $entry->value];
        $this->postJson('/object', $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => $entry->entry_key,
                'value' => $entry->value
            ]);

        $this->get("/object/{$entry->entry_key}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'value' => $entry->value
            ]);
    }

    public function testShowNewerValueOfSameKey()
    {
        $factory = new EntryFactory();
        /** @var Entry $entry */
        $entry = $factory->make();
        $data = [$entry->entry_key => $entry->value];
        $this->postJson('/object', $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => $entry->entry_key,
                'value' => $entry->value
            ]);

        sleep(2);

        $entry2 = $factory->make(['entry_key' => $entry->entry_key]);
        $data2 = [$entry2->entry_key => $entry2->value];
        $this->postJson('/object', $data2)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => $entry2->entry_key,
                'value' => $entry2->value
            ]);

        $this->get("/object/{$entry->entry_key}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'value' => $entry2->value
            ]);
    }

    public function testModelGetEntryValueAtTimestamp()
    {
        $factory = new EntryFactory();
        /** @var Entry $entry */
        $entry = $factory->make();
        $data = [$entry->entry_key => $entry->value];
        $this->postJson('/object', $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => $entry->entry_key,
                'value' => $entry->value
            ]);

        sleep(2);
        $timestamp = Carbon::now()->timestamp;
        sleep(2);

        $entry2 = $factory->make(['entry_key' => $entry->entry_key]);
        $data2 = [$entry2->entry_key => $entry2->value];
        $this->postJson('/object', $data2)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'entry_key' => $entry2->entry_key,
                'value' => $entry2->value
            ]);

        $this->get("/object/{$entry->entry_key}?timestamp=$timestamp")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'value' => $entry->value
            ]);
    }

    /**
     * Test get all records
     *
     * @return void
     */
    public function testGetAllRecords()
    {
        $response = $this->get('/object/get_all_records');

        $response->assertStatus(200);
    }
}
