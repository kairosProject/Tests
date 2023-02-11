<?php
/**
 * This file is part of the Kairos project.
 *
 * As each file provided by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Test
 * @package  KairosProject\Tests\Test\Constraint
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
declare(strict_types=1);

namespace KairosProject\Tests\Test\StaticOverride;

use KairosProject\Tests\AbstractTestClass;
use KairosProject\Tests\Test\StaticOverride\Fixture\ClassWithStaticProperty;
use ReflectionException;
use ReflectionProperty;
use stdClass;

/**
 * This file is part of the Kairos project.
 *
 * As each file provided by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Test
 * @package  KairosProject\Tests\Test\Constraint
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ContextualStaticOverrideTest extends AbstractTestClass
{
    /**
     * This method is called before the first test of this test class is run. It allows to setup a property content.
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $propertyReflection = new ReflectionProperty(ClassWithStaticProperty::class, 'property');
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue(static::class);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOverrideTheProperty(): void
    {
        $this->getClassProperty('property', true, ClassWithStaticProperty::class)->setValue('test');

        $propertyReflection = new ReflectionProperty(ClassWithStaticProperty::class, 'property');
        $propertyReflection->setAccessible(true);

        static::assertEquals('test', $propertyReflection->getValue());
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testOverrideThePropertyWithFullArguments(): void
    {
        $instance = new ClassWithStaticProperty();
        $this->getClassProperty('property', true, ClassWithStaticProperty::class)->setValue($instance, 'test');

        $propertyReflection = new ReflectionProperty(ClassWithStaticProperty::class, 'property');
        $propertyReflection->setAccessible(true);

        static::assertEquals('test', $propertyReflection->getValue($instance));
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testPropertyIsSetBackToPreviousValue(): void
    {
        static::assertEquals(
            static::class,
            $this->getClassProperty('property', true, ClassWithStaticProperty::class)->getValue()
        );
    }

    /**
     * Get tested class
     *
     * Return the tested class name
     *
     * @return string
     */
    protected function getTestedClass(): string
    {
        return stdClass::class;
    }
}
