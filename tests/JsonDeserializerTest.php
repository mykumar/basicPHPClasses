<?php

namespace stdr\basicPHPClasses\Tests;

use PHPUnit_Framework_TestCase;
use stdClass;
use stdr\basicPHPClassesTests\Bar;
use stdr\basicPHPClasses\JsonDeserializer as Deserializer;

class JsonDeserializerTest extends PHPUnit_Framework_TestCase
{
    function test_deserialized_correctly()
    {
        $this->assertSame('test', Deserializer::deserialize('"test"'));
    }

    function test_null_deserialized()
    {
        $this->assertNull(Deserializer::deserialize('null'));
    }

    function test_arrays_deserialized()
    {
        $this->assertSame([1, 'foo'], Deserializer::deserialize('[1,"foo"]'));
    }

    function test_empty_objects_deserialized()
    {
        $object = Deserializer::deserialize('{"μ":{"#":0,"fqn":"stdClass"}}');

        $this->assertInstanceOf(stdClass::class, $object);
        $this->assertCount(0, (array) $object);
    }

    function test_objects_deserialized()
    {
        $json = '{'
            . '"foo":"bar",'
            . '"baz":["qux"],'
            . '"obj":{'
                . '"publicValue":"parentPublicValue",'
                . '"privateValue":"parentPrivateValue",'
                . '"μ":{"#":1,"fqn":"Vanio\\\Stdlib\\\Tests\\\Fixtures\\\Bar"}'
            . '},'
            . '"μ":{"#":0,"fqn":"stdClass"}' .
        '}';

        $object = Deserializer::deserialize($json);

        $this->assertInstanceOf(stdClass::class, $object);
        $this->assertCount(3, (array) $object);
        $this->assertSame('bar', $object->foo);
        $this->assertSame(['qux'], $object->baz);
        $this->assertInstanceOf(Bar::class, $object->obj);
        $this->assertSame('parentPublicValue', $object->obj->publicValue);
        $this->assertSame('parentPrivateValue', $object->obj->privateValue());
    }

    function test_array_of_objects_deserialized()
    {
        $array = Deserializer::deserialize('[{"μ":{"#":0,"fqn":"stdClass"}},{"μ":{"#":1,"fqn":"stdClass"}}]');

        $this->assertCount(2, $array);
        $this->assertContainsOnlyInstancesOf(stdClass::class, $array);
    }

    function test_object_references_deserialized()
    {
        $json = '[{"μ":{"#":0,"fqn":"stdClass"}},{"foo":{"μ":{"#":0,"fqn":"stdClass"}},"μ":{"#":1,"fqn":"stdClass"}}]';
        $objects = Deserializer::deserialize($json);

        $this->assertSame($objects[0], $objects[1]->foo);
    }

    
}
