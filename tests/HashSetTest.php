<?php

namespace stdr\basicPHPClasses\Tests;

use PHPUnit_Framework_TestCase;
use stdr\basicPHPClasses\HashSet;

class HashSetTest extends PHPUnit_Framework_TestCase
{
    /** @var LinkedHashSet */
    private $set;

    function setUp()
    {
        $this->set = new LinkedHashSet();
    }

    function test_set_empty()
    {
        $this->assertCount(0, $this->set);
    }

    
    function test_set_presence_value()
    {
        $this->set->add('test');

        $this->assertTrue($this->set->contains('test'));
        $this->assertFalse($this->set->contains('bogus'));
    }

    function test_set_value_removed()
    {
        $this->set->add('test');

        $this->assertTrue($this->set->remove('test'));
        $this->assertFalse($this->set->contains('test'));
        $this->assertCount(0, $this->set);
        $this->assertFalse($this->set->remove('test'));
    }

    function test_set_values_retrieved()
    {
        $values = [0, 1, 1.01, 'foo', ['name' => 'John', 'surname' => 'Doe'], (object) ['data' => ['something']]];

        foreach ($values as $value) {
            $this->set->add($value);
        }

        $this->assertSame($values, $this->set->values());
    }

    function test_set_iteration()
    {
        $object = (object) ['data' => ['something']];
        $expected = [0, 1.01, 'foo', ['name' => 'John', 'surname' => 'Doe'], $object];

        foreach ($expected as $value) {
            $this->set->add($value);
        }

        $index = 0;
        foreach ($this->set as $value) {
            $this->assertSame($expected[$index++], $value);
        }
    }
}
