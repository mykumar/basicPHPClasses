<?php

namespace stdr\basicPHPClasses\Tests;

use PHPUnit_Framework_TestCase;
use stdr\basicPHPClasses\HashMap;

class HashMapTest extends PHPUnit_Framework_TestCase
{
    /** @var LinkedHashMap */
    private $map;

    function setUp()
    {
        $this->map = new LinkedHashMap();
    }

    function test_string_key_can_be_retrieved_by_its_key()
    {
        $this->map['test'] = 'Hello!';

        $this->assertSame('Hello!', $this->map['test']);
    }

    function test_numeric_key_can_be_retrieved_by_its_key()
    {
        $this->map[0] = 'First';
        $this->map[1.02] = 'Second';

        $this->assertSame('First', $this->map[0]);
        $this->assertSame('Second', $this->map[1.02]);
    }

    function test_object_key_can_be_retrieved_by_its_key()
    {
        $key = (object) ['name' => 'John'];
        $this->map[$key] = 'Hello John!';

        $this->assertSame('Hello John!', $this->map[$key]);
    }

    function test_array_key_can_be_retrieved_by_its_key()
    {
        $key = [
            'name' => 'John',
            'surname' => 'Doe',
            'attrs' => ['gender' => 'male'],
            'info' => (object) ['desc' => 'none'],
        ];
        $this->map[$key] = 'Hello John!';

        $this->assertSame('Hello John!', $this->map[$key]);
    }

    function test_map()
    {
        $this->map['test'] = 'Hello!';

        $this->assertFalse(isset($this->map['bogus']));
        $this->assertTrue(isset($this->map['test']));
    }

    function test_null()
    {
        $this->assertNull($this->map['bogus']);
    }

    function test_remove_map_using_key()
    {
        $this->map['test'] = 'Hello!';
        unset($this->map['test']);

        $this->assertFalse(isset($this->map['test']));
    }

    function test_map_size_zero()
    {
        $this->assertSame(0, count($this->map));
    }

    function test_map_size()
    {
        $this->map[0] = 'Hello!';
        $this->assertSame(1, count($this->map));
    }

    function test_map_iterated()
    {
        $objectKey = (object) ['data' => ['something']];
        $expected = [
            [0, 'test0'],
            [1, 'test1'],
            [1.01, 'test1.01'],
            ['foo', 'bar'],
            [['name' => 'John', 'surname' => 'Doe'], 'John Doe'],
            [$objectKey, 'Object'],
        ];

        foreach ($expected as list($key, $value)) {
            $this->map[$key] = $value;
        }

        $index = 0;
        foreach ($this->map as $key => $value) {
            $this->assertSame($expected[$index][0], $key);
            $this->assertSame($expected[$index++][1], $value);
        }
    }

    function test_map_keys_retrieved()
    {
        $keys = [0, 1, 1.01, 'foo', ['name' => 'John', 'surname' => 'Doe'], (object) ['data' => ['something']]];

        foreach ($keys as $i => $key) {
            $this->map[$key] = $i;
        }

        $this->assertSame($keys, $this->map->keys());
    }

    
    function test_map_values_retrieved()
    {
        $values = [0, 1, 1.01, 'foo', ['name' => 'John', 'surname' => 'Doe'], (object) ['data' => ['something']]];

        foreach ($values as $value) {
            $this->map[] = $value;
        }

        $this->assertSame($values, $this->map->values());
    }

    function test_map__initialization_after_deserialization()
    {
        $objectKey = (object) ['data' => ['something']];
        $expected = [
            [0, 'test0'],
            [1, 'test1'],
            [1.01, 'test1.01'],
            ['foo', 'bar'],
            [['name' => 'John', 'surname' => 'Doe'], 'John Doe'],
            [$objectKey, 'Object'],
        ];

        foreach ($expected as list($key, $value)) {
            $this->map[$key] = $value;
        }

        $this->map = unserialize(serialize($this->map));

        $index = 0;
        foreach ($this->map->keys() as $key) {
            $this->assertEquals($expected[$index][0], $key);
            $this->assertSame($expected[$index++][1], $this->map[$key]);
        }
    }
}
