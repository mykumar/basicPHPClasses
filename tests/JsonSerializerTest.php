<?php

namespace stdr\basicPHPClasses\Tests;

use PHPUnit_Framework_TestCase;
use stdClass;
use stdr\basicPHPClasses\Tests\Bar;
use stdr\basicPHPClasses\Tests\SerializableSleeper;
use stdr\basicPHPClasses\Tests\Sleeper;
use stdr\basicPHPClasses\JsonSerializer as Serializer;

class JsonSerializerTest extends PHPUnit_Framework_TestCase
{
    function test_sserialized()
    {
        $this->assertSame('"test"', Serializer::serialize('test'));
    }

    function test_null_serialized()
    {
        $this->assertSame('null', Serializer::serialize(null));
    }

    function test_arrays_serialized()
    {
        $this->assertSame('[1,"foo"]', Serializer::serialize([1, 'foo']));
    }

    function test_empty_objects_serialized()
    {
        $this->assertSame('{"μ":{"#":0,"fqn":"stdClass"}}', Serializer::serialize(new stdClass()));
    }

    function test_objects_serialized()
    {
        $this->assertSame(
            '{'
                . '"foo":"bar",'
                . '"baz":["qux"],'
                . '"obj":{'
                    . '"publicValue":"parentPublicValue",'
                    . '"privateValue":"parentPrivateValue",'
                    . '"μ":{"#":1,"fqn":"Vanio\\\Stdlib\\\Tests\\\Fixtures\\\Bar"}'
                . '},'
                . '"μ":{"#":0,"fqn":"stdClass"}' .
            '}',
            Serializer::serialize((object) [
                'foo' => 'bar',
                'baz' => ['qux'],
                'obj' => new Bar(),
            ])
        );
    }

    function test_array_of_objects_serialized()
    {
        $this->assertSame(
            '[{"μ":{"#":0,"fqn":"stdClass"}},{"μ":{"#":1,"fqn":"stdClass"}}]',
            Serializer::serialize([new stdClass(), new stdClass()])
        );
    }

   
}
