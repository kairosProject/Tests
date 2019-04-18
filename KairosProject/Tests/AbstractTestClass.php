<?php
declare(strict_types=1);
/**
 * This file is part of the Kairos project.
 *
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Test
 * @package  Chronos
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */

namespace KairosProject\Tests;

use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use PHPUnit\Framework\MockObject\Matcher\Invocation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Abstract test class
 *
 * This class is used as placeholder for test implementation
 *
 * @category                                 Test
 * @package                                  Chronos
 * @author                                   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license                                  MIT <https://opensource.org/licenses/MIT>
 * @link                                     http://cscfa.fr
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class AbstractTestClass extends TestCase
{
    /**
     * Class reflection
     *
     * The tested class reflection to be stored and used during test
     *
     * @var ReflectionClass
     */
    protected $classReflection;

    /**
     * Setup
     *
     * This method is called before each test.
     *
     * @return void
     * @throws ReflectionException
     * @see    \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {
        $this->classReflection = new ReflectionClass($this->getTestedClass());
    }

    /**
     * Get tested class
     *
     * Return the tested class name
     *
     * @return string
     */
    abstract protected function getTestedClass(): string;

    /**
     * Get invocation builder
     *
     * Create an invocation builder base on an invocation specification
     *
     * @param MockObject $mock The base mock object
     * @param Invocation $count The invocation count
     * @param string $method The method name
     *
     * @return InvocationMocker
     */
    protected function getInvocationBuilder(MockObject $mock, Invocation $count, string $method): InvocationMocker
    {
        return $mock->expects($count)->method($method);
    }

    /**
     * Assert has simple accessor
     *
     * Validate the getter and setter as simple ones for the given property, with the given value
     *
     * @param string $property The property to validate
     * @param mixed $value The value to use with getter and setter
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertHasSimpleAccessor(string $property, $value): void
    {
        $getter = sprintf('get%s', ucfirst($property));
        $this->assertPublicMethod($getter);
        $this->assertIsSimpleGetter($property, $getter, $value);

        $setter = sprintf('set%s', ucfirst($property));
        $this->assertPublicMethod($setter);
        $this->assertIsSimpleSetter($property, $setter, $value);
    }

    /**
     * Assert public method
     *
     * Assert a method to be public in current tested class
     *
     * @param string $methodName The method name
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertPublicMethod(string $methodName): void
    {
        $this->assertTrue($this->getClassMethod($methodName)->isPublic());
    }

    /**
     * Get class method
     *
     * Return an instance of ReflectionMethod for a given method name
     *
     * @param string $method The method name to reflex
     * @param bool $accessible The accessibility state of the property
     *
     * @return ReflectionMethod|null
     * @throws ReflectionException
     */
    protected function getClassMethod(string $method, bool $accessible = true): ?ReflectionMethod
    {
        $method = $this->createMethodReflection($this->classReflection->getName(), $method);

        if (!$method) {
            $this->fail(
                sprintf(
                    'The class "%s" is expected to store the method "%s"',
                    $this->getTestedClass(),
                    $method
                )
            );
        }
        $method->setAccessible($accessible);

        return $method;
    }

    /**
     * Create method reflection
     *
     * Return a reflection method, according to the instance class name and mathod. Abble to follow the
     * inheritance tree to find the property.
     *
     * @param string $instanceClassName The base instance class name
     * @param string $method The method name to find
     *
     * @return ReflectionMethod|NULL
     * @throws ReflectionException
     */
    private function createMethodReflection(string $instanceClassName, string $method): ?ReflectionMethod
    {
        $reflectionClass = new ReflectionClass($instanceClassName);

        if ($reflectionClass->hasMethod($method)) {
            $methodReflection = $reflectionClass->getMethod($method);
            return $methodReflection;
        }

        $parentClass = $reflectionClass->getParentClass();
        if (!$parentClass) {
            return null;
        }

        return $this->createMethodReflection($parentClass->getName(), $method);
    }

    /**
     * Assert is simple getter
     *
     * Validate the given method is a simple getter method and return the given value from the given property
     *
     * @param string $property The property name
     * @param string $method The getter method
     * @param mixed $value The returned value
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertIsSimpleGetter(string $property, string $method, $value): void
    {
        $this->assertIsGetter($property, $method, $value, $value);

        return;
    }

    /**
     * Assert is getter
     *
     * Validate the given method is a getter method and return the given expected value from the given property when
     * the given value is injected into the property
     *
     * @param string $property The property name
     * @param string $method The getter method
     * @param mixed $value The injected value
     * @param mixed $expected The returned value
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertIsGetter(string $property, string $method, $value, $expected): void
    {
        $isSame = substr($property, 0, strlen('same:')) == 'same:';
        if ($isSame) {
            $property = substr($property, strlen('same:'));
        }

        $propertyReflex = $this->getClassProperty($property);

        $instance = $this->getInstance();
        $propertyReflex->setValue($instance, $value);

        $method = $this->getClassMethod($method, false);
        $this->assertTrue(
            $method->isPublic(),
            sprintf(
                'The method "%s" of class "%s" is expected to be public',
                $method,
                $this->getTestedClass()
            )
        );

        if ($isSame) {
            $this->assertSame($expected, $method->invoke($instance));
            return;
        }
        $this->assertEquals($expected, $method->invoke($instance));
        return;
    }

    /**
     * Get class property
     *
     * Return an instance of ReflectionProperty for a given property name
     *
     * @param string $property The property name to reflex
     * @param bool $accessible The accessibility state of the property
     *
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    protected function getClassProperty(string $property, bool $accessible = true): ?ReflectionProperty
    {
        $property = $this->createPropertyReflection($this->classReflection->getName(), $property);

        if (!$property) {
            $this->fail(
                sprintf(
                    'The class "%s" is expected to store the property "%s"',
                    $this->getTestedClass(),
                    $property
                )
            );
        }
        $property->setAccessible($accessible);

        return $property;
    }

    /**
     * Create property reflection
     *
     * Return a reflection property, according to the instance class name and property. Abble to follow the
     * inheritance tree to find the property.
     *
     * @param string $instanceClassName The base instance class name
     * @param string $property The property name to find
     *
     * @return ReflectionProperty|NULL
     * @throws ReflectionException
     */
    protected function createPropertyReflection(string $instanceClassName, string $property): ?ReflectionProperty
    {
        $reflectionClass = new ReflectionClass($instanceClassName);

        if ($reflectionClass->hasProperty($property)) {
            $propertyReflection = $reflectionClass->getProperty($property);
            return $propertyReflection;
        }

        $parentClass = $reflectionClass->getParentClass();
        if (!$parentClass) {
            return null;
        }

        return $this->createPropertyReflection($parentClass->getName(), $property);
    }

    /**
     * Get instance
     *
     * Return an instance of tested class without constructor call
     *
     * @param array $injection A set of property to be injected after instantiation
     *
     * @return object
     * @throws ReflectionException
     */
    protected function getInstance(array $injection = [])
    {
        $instance = $this->classReflection->newInstanceWithoutConstructor();

        foreach ($injection as $property => $value) {
            $this->getClassProperty($property)->setValue($instance, $value);
        }

        return $instance;
    }

    /**
     * Assert is simple setter
     *
     * Validate the given method is a simple setter method. Assert the returned value of the method is the instance,
     * and the value is injected into the property.
     *
     * @param string $property The property name
     * @param string $method The getter method
     * @param mixed $value The injected value
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertIsSimpleSetter(string $property, string $method, $value): void
    {
        $this->assertIsSetter($property, $method, $value, $value);

        return;
    }

    /**
     * Assert is setter
     *
     * Validate the given method is a setter method. Assert the returned value of the method is the instance,
     * and the value is injected into the property. Allow the injected value to be modifyed during process.
     *
     * @param string $property The property name
     * @param string $method The getter method
     * @param mixed $value The injected value
     * @param mixed $expected The final injected value
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertIsSetter(string $property, string $method, $value, $expected): void
    {
        $isSame = substr($property, 0, strlen('same:')) == 'same:';
        if ($isSame) {
            $property = substr($property, strlen('same:'));
        }

        $propertyReflex = $this->getClassProperty($property);
        $instance = $this->getInstance();

        $method = $this->getClassMethod($method, false);
        $this->assertTrue(
            $method->isPublic(),
            sprintf(
                'The method "%s" of class "%s" is expected to be public',
                $method,
                $this->getTestedClass()
            )
        );
        $this->assertSame($instance, $method->invoke($instance, $value));

        if ($isSame) {
            $this->assertSame($expected, $propertyReflex->getValue($instance));
            return;
        }
        $this->assertEquals($expected, $propertyReflex->getValue($instance));
        return;
    }

    /**
     * Assert constructor
     *
     * Validate the tested instance constructor. If property is prefixed by 'same:', the assert same
     * is used to validate the injected value.
     *
     * @param array $constructorArguments The constructor call arguments
     * @param array $optionals The optionals constructor arguments
     *
     * @return  void
     * @throws  ReflectionException
     * @example <pre>
     *  assertConstructor(['injectedProperty', 'value'], ['injectedProperty', 'optionalValue']);
     *  assertConstructor(['same:property', Mock]);
     * </pre>
     */
    protected function assertConstructor(array $constructorArguments, array $optionals = [])
    {
        $instance = $this->classReflection->newInstanceArgs(array_values($constructorArguments));

        foreach (array_merge($constructorArguments, $optionals) as $property => $value) {
            if (preg_match('/^same:/', $property)) {
                $property = substr($property, strlen('same:'));
                $this->assertSame($value, $this->getClassProperty($property)->getValue($instance));

                continue;
            }

            $this->assertEquals($value, $this->getClassProperty($property)->getValue($instance));
        }
    }

    /**
     * Assert protected method
     *
     * Assert a method to be protected in current tested class
     *
     * @param string $methodName The method name
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertProtectedMethod(string $methodName): void
    {
        $this->assertTrue($this->getClassMethod($methodName)->isProtected());
    }

    /**
     * Assert private method
     *
     * Assert a method to be private in current tested class
     *
     * @param string $methodName The method name
     *
     * @return void
     * @throws ReflectionException
     */
    protected function assertPrivateMethod(string $methodName): void
    {
        $this->assertTrue($this->getClassMethod($methodName)->isPrivate());
    }
}
