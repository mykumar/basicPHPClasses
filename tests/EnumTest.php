<?php

namespace stdr\basicPHPClasses\Tests;

use ErrorException;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use stdr\basicPHPClasses\Enum;

class Foo extends Enum
{
    const BAR = 'FooBar';
    const BAZ = ['FooBaz'];
    const QUX = ['FooQux1', 'FooQux2'];

    public $value;
    public $secondValue;

    protected function __construct(string $value, string $secondValue = null)
    {
        parent::__construct();
        $this->value = $value;
        $this->secondValue = $secondValue;
    }
}

class EnumTest extends PHPUnit_Framework_TestCase
{
    function test_value_by_its_name()
    {
        $this->assertInstanceOf(Foo::class, Foo::BAR());
    }

    function test_instance_name()
    {
        $this->assertSame(Foo::BAR(), Foo::BAR());
    }

    function test_for_different_name_a_different_instance_is_returned()
    {
        $this->assertNotSame(Foo::BAR(), Foo::BAZ());
    }

    function test_instantiated_defined_values()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Enum stdrbasicPHPClassesTests\Foo does not contain value named BOGUS.');

        Foo::BOGUS();
    }

    function test_enum_constructor()
    {
        $this->assertSame(Foo::BAR, Foo::BAR()->value);
        $this->assertSame(Foo::BAZ, [Foo::BAZ()->value]);
        $this->assertSame(Foo::QUX, [Foo::QUX()->value, Foo::QUX()->secondValue]);
    }

    function test_all_available_values_can_be_obtained()
    {
        $this->assertSame([Foo::BAR(), Foo::BAZ(), Foo::QUX()], iterator_to_array(Foo::values()));
    }

    function test_enum_values_have_correct_names()
    {
        $this->assertSame('BAR', Foo::BAR()->name());
        $this->assertSame('BAZ', Foo::BAZ()->name());
    }

    function test_valueOf()
    {
        $this->assertSame(Foo::BAR(), Foo::valueOf('BAR'));
    }

    function test_caste_to_strings()
    {
        $this->assertSame('BAR', (string) Foo::BAR());
        $this->assertSame('BAZ', (string) Foo::BAZ());
    }

    function test_clone()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('There can be only one instance for each enummeration value.');

        clone Foo::BAR();
    }

    function test_enum_unboxed()
    {
        $this->assertSame(Foo::BAR, Foo::BAR()->unbox());
    }

    function test_boxed()
    {
        $this->assertSame(Foo::BAR(), Foo::box(Foo::BAR));
        $this->assertSame(Foo::QUX(), Foo::box(Foo::QUX));
    }

    function test_values_not_within_enumeration_cannot_be_boxed()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value 123 is not within stdrbasicPHPClassesTests\Foo enumeration.');

        Foo::box(123);
    }

    function test_enum_serialized()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Enumeration values cannot be serialized.');

        serialize(Foo::BAR());
    }

    function test_enum_deserialized()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Enumeration values cannot be deserialized.');

        unserialize('O:22:"stdrbasicPHPClassesTests\Foo":1:{s:4:"name";s:3:"BAR";}');
    }
}
