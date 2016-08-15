<?php

namespace stdr\basicPHPClasses\Tests;

use PHPUnit_Framework_TestCase;
use stdr\basicPHPClasses\Objects;
use stdr\basicPHPClasses\Tests\Bar;
use stdr\basicPHPClasses\Tests\Baz;

class ObjectsTest extends PHPUnit_Framework_TestCase
{
    function test_type()
    {
        $this->assertEquals('string', Objects::getType('foo'));
        $this->assertEquals('integer', Objects::getType(1));
        $this->assertEquals(__CLASS__, Objects::getType($this));
    }

    function test_public_property()
    {
        $this->assertSame('publicValue', Objects::getPropertyValue(new Baz(), 'publicValue'));
        $this->assertSame('publicValue', Objects::getPropertyValue(new Baz, 'publicValue', Bar::class));
        $this->assertSame('publicValue', Objects::getPropertyValue(new Baz, 'publicValue', Baz::class));

        $this->assertSame('parentPublicValue', Objects::getPropertyValue(new Bar, 'publicValue'));
        $this->assertSame('parentPublicValue', Objects::getPropertyValue(new Bar, 'publicValue', Bar::class));
        $this->assertSame('parentPublicValue', Objects::getPropertyValue(new Bar, 'publicValue', Baz::class));

        $this->assertNull(Objects::getPropertyValue(new Baz, 'nonDeclaredProperty'));
    }

    function test_static_property()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Access to undeclared static property');
        Objects::getPropertyValue(Bar::class, 'nonExistentProperty');
    }

    function test_private_property_without_specifying_scope()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot access private property');
        Objects::getPropertyValue(new Baz, 'privateValue');
    }

    function test_private_property()
    {
        $this->assertSame('privateValue', Objects::getPropertyValue(new Baz, 'privateValue', Baz::class));
        $this->assertSame('parentPrivateValue', Objects::getPropertyValue(new Baz, 'privateValue', Bar::class));
        $this->assertSame('parentPrivateValue', Objects::getPropertyValue(new Bar, 'privateValue', Bar::class));
    }

    function test_private_static_property_without_specifying_scope()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot access private property');
        Objects::getPropertyValue(Baz::class, 'privateStaticValue');
    }

    function test_public_property_by_reference()
    {
        $object = new Baz;
        $value = &Objects::getPropertyValue($object, 'publicValue');
        $value = 'value';
        $this->assertSame('value', $object->publicValue);
    }

    function test_public_static_property_by_reference()
    {
        $value = &Objects::getPropertyValue(Baz::class, 'publicStaticValue');
        $value = 'value';
        $this->assertSame('value', Baz::$publicStaticValue);
    }

    function test_private_property_by_reference()
    {
        $object = new Baz;
        $value = &Objects::getPropertyValue($object, 'privateValue', Baz::class);
        $value = 'value';
        $this->assertSame('value', $object->privateValue());
    }

    function tes_private_static_property_by_reference()
    {
        $value = &Objects::getPropertyValue(Baz::class, 'privateStaticValue', Baz::class);
        $value = 'value';
        $this->assertSame('value', Baz::privateStaticValue());
    }

    function test_public_property()
    {
        $object = new Baz;
        Objects::setPropertyValue($object, 'publicValue', 'value');
        $this->assertSame('value', $object->publicValue);

        $object = new Baz;
        Objects::setPropertyValue($object, 'publicValue', 'value', Bar::class);
        $this->assertSame('value', $object->publicValue);

        $object = new Baz;
        Objects::setPropertyValue($object, 'publicValue', 'value', Baz::class);
        $this->assertSame('value', $object->publicValue);

        $object = new Baz;
        Objects::setPropertyValue($object, 'nonDeclaredProperty', 'value');
        $this->assertSame('value', $object->{'nonDeclaredProperty'});
    }

    function test_static_property()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Access to undeclared static property');
        Objects::setPropertyValue(Bar::class, 'nonExistentProperty', 'value');
    }

    function test_public_static_property()
    {
        Objects::setPropertyValue(Baz::class, 'publicStaticValue', 'value');
        $this->assertSame('value', Baz::$publicStaticValue);

        Objects::setPropertyValue(Baz::class, 'publicStaticValue', 'value', Bar::class);
        $this->assertSame('value', Baz::$publicStaticValue);

        Objects::setPropertyValue(Baz::class, 'publicStaticValue', 'value', Baz::class);
        $this->assertSame('value', Baz::$publicStaticValue);
    }

    function test_private_property()
    {
        $object = new Baz;
        Objects::setPropertyValue($object, 'privateValue', 'value', Baz::class);
        $this->assertSame('value', $object->privateValue());

        $object = new Baz;
        Objects::setPropertyValue($object, 'privateValue', 'value', Bar::class);
        $this->assertSame('value', Objects::getPropertyValue($object, 'privateValue', Bar::class));
    }

    
}
