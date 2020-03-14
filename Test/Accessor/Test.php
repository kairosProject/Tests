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
namespace KairosProject\Tests\Test\Accessor;

use KairosProject\Tests\AbstractTestClass;
use KairosProject\Tests\Test\Stub\AccessorClass;
use KairosProject\Tests\Test\Stub\IsserClass;
use KairosProject\Tests\Test\Stub\MethodClass;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\MockObject\Matcher;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\MockObject\Rule\MethodName;
use ReflectionException;
use stdClass;

/**
 * This file is part of the Kairos project.
 *
 * As each files provides by the CSCFA, this file is licensed
 * under the MIT license.
 *
 * PHP version 7.2
 *
 * @category Test
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class Test extends AbstractTestClass
{
    /**
     * Test hasSimpleAccessor
     *
     * Validate the assertHasSimpleAccessor method of the tested class
     *
     * @throws ReflectionException
     */
    public function testHasSimpleAccessorWithSame()
    {
        $this->assertHasSimpleAccessor('same:accessorProperty', new stdClass());
    }

    /**
     * Test hasSimpleAccessor
     *
     * Validate the assertHasSimpleAccessor method of the tested class
     *
     * @throws ReflectionException
     */
    public function testHasSimpleAccessorWithFullAccessor()
    {
        $this->assertHasSimpleAccessor('accessorProperty', 'someValue');
    }

    /**
     * Test hasSimpleAccessor with getter only
     *
     * Validate the assertHasSimpleAccessor method of the tested class
     *
     * @throws ReflectionException
     */
    public function testHasSimpleAccessorWithGetterOnly()
    {
        try {
            $this->assertHasSimpleAccessor('getterProperty', 'someValue');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                sprintf(
                    'The class "%s" is expected to store the method "%s"',
                    $this->getTestedClass(),
                    'setGetterProperty'
                ),
                $exception->getMessage()
            );
        }
    }

    /**
     * Test hasSimpleAccessor with setter only
     *
     * Validate the assertHasSimpleAccessor method of the tested class
     *
     * @throws ReflectionException
     */
    public function testHasSimpleAccessorWithSetterOnly()
    {
        try {
            $this->assertHasSimpleAccessor('setterProperty', 'someValue');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals('No getter found for property "setterProperty"', $exception->getMessage());
        }
    }

    /**
     * Test hasSimpleAccessor with boolean
     *
     * Validate the assertHasSimpleAccessor method of the tested class
     *
     * @throws ReflectionException
     */
    public function testHasSimpleAccessorWithBoolean()
    {
        $this->assertHasSimpleAccessor('boolProperty', true);
    }

    /**
     * Test getClassProperty
     *
     * Validate the getClassProperty method of the tested class
     *
     * @throws ReflectionException
     */
    public function testGetClassProperty()
    {
        $propertyReflection = $this->getClassProperty('setterProperty');
        $this->assertEquals('setterProperty', $propertyReflection->getName());

        try {
            $this->getClassProperty('noProperty');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                sprintf(
                    'The class "%s" is expected to store the property "%s"',
                    $this->getTestedClass(),
                    'noProperty'
                ),
                $exception->getMessage()
            );
        }
    }

    /**
     * Test getInstance
     *
     * Validate the getInstance method of the tested class
     *
     * @throws ReflectionException
     */
    public function testGetInstance()
    {
        $instance = $this->getInstance();
        $this->assertNull($instance->accessorProperty);
        $this->assertNull($instance->getterProperty);
        $this->assertNull($instance->setterProperty);
        $this->assertFalse($instance->boolProperty);

        $instance = $this->getInstance(['accessorProperty' => 'someValue']);
        $this->assertEquals('someValue', $instance->accessorProperty);
        $this->assertNull($instance->getterProperty);
        $this->assertNull($instance->setterProperty);
        $this->assertFalse($instance->boolProperty);

        $instance = $this->getInstance(['accessorProperty' => 'someValue', 'getterProperty' => 'getter']);
        $this->assertEquals('someValue', $instance->accessorProperty);
        $this->assertEquals('getter', $instance->getterProperty);
        $this->assertNull($instance->setterProperty);
        $this->assertFalse($instance->boolProperty);

        $instance = $this->getInstance(
            [
                'accessorProperty' => 'someValue',
                'getterProperty' => 'getter',
                'setterProperty' => 'setter'
            ]
        );
        $this->assertEquals('someValue', $instance->accessorProperty);
        $this->assertEquals('getter', $instance->getterProperty);
        $this->assertEquals('setter', $instance->setterProperty);
        $this->assertFalse($instance->boolProperty);

        $instance = $this->getInstance(
            [
                'accessorProperty' => 'someValue',
                'getterProperty' => 'getter',
                'setterProperty' => 'setter',
                'boolProperty' => true
            ]
        );
        $this->assertEquals('someValue', $instance->accessorProperty);
        $this->assertEquals('getter', $instance->getterProperty);
        $this->assertEquals('setter', $instance->setterProperty);
        $this->assertTrue($instance->boolProperty);
    }


    /**
     * Test assertConstructor
     *
     * Validate the assertConstructor method of the tested class
     *
     * @throws ReflectionException
     */
    public function testAssertConstructor()
    {
        $this->assertConstructor(
            ['accessorProperty' => 'accessor', 'getterProperty' => 'getter', 'setterProperty' => 'setter'],
            ['boolProperty' => false]
        );

        $this->assertConstructor(
            ['same:accessorProperty' => new stdClass(), 'getterProperty' => 'getter', 'setterProperty' => 'setter'],
            ['boolProperty' => false]
        );

        try {
            $this->assertConstructor(
                ['accessorProperty' => 'accessor', 'getterProperty' => 'getter', 'setterProperty' => 'setter'],
                ['boolProperty' => false, 'noProperty' => 'a']
            );
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                sprintf(
                    'The class "%s" is expected to store the property "%s"',
                    $this->getTestedClass(),
                    'noProperty'
                ),
                $exception->getMessage()
            );
        }

        try {
            $this->assertConstructor(
                ['accessorProperty' => 'accessor', 'getterProperty' => 'getter', 'setterProperty' => 'setter'],
                ['boolProperty' => true]
            );
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals('Failed asserting that false matches expected true.', $exception->getMessage());
        }

        try {
            $this->assertConstructor(
                [
                    'accessorProperty' => 'accessor',
                    'getterProperty' => 'getter',
                    'setterProperty' => 'setter',
                    'boolProperty' => true
                ]
            );
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals('Failed asserting that false matches expected true.', $exception->getMessage());
        }
    }

    /**
     * Test PublicMethod
     *
     * Validate the assertPublicMethod method of the tested class
     *
     * @throws ReflectionException
     */
    public function testPublicMethod()
    {
        $this->assertPublicMethod('publicMethod');

        try {
            $this->assertPublicMethod('noMethod');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                sprintf(
                    'The class "%s" is expected to store the method "%s"',
                    $this->getTestedClass(),
                    'noMethod'
                ),
                $exception->getMessage()
            );
        }

        try {
            $this->assertPublicMethod('protectedMethod');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                'Failed asserting that false is true.',
                $exception->getMessage()
            );
        }
    }

    /**
     * Test ProtectedMethod
     *
     * Validate the assertProtectedMethod method of the tested class
     *
     * @throws ReflectionException
     */
    public function testProtectedMethod()
    {
        $this->assertProtectedMethod('protectedMethod');

        try {
            $this->assertProtectedMethod('noMethod');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                sprintf(
                    'The class "%s" is expected to store the method "%s"',
                    $this->getTestedClass(),
                    'noMethod'
                ),
                $exception->getMessage()
            );
        }

        try {
            $this->assertProtectedMethod('publicMethod');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                'Failed asserting that false is true.',
                $exception->getMessage()
            );
        }
    }

    /**
     * Test PrivateMethod
     *
     * Validate the assertPrivateMethod method of the tested class
     *
     * @throws ReflectionException
     */
    public function testPrivateMethod()
    {
        $this->assertPrivateMethod('privateMethod');

        try {
            $this->assertPrivateMethod('noMethod');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                sprintf(
                    'The class "%s" is expected to store the method "%s"',
                    $this->getTestedClass(),
                    'noMethod'
                ),
                $exception->getMessage()
            );
        }

        try {
            $this->assertPrivateMethod('publicMethod');
            $this->fail();
        } catch (AssertionFailedError $exception) {
            $this->assertEquals(
                'Failed asserting that false is true.',
                $exception->getMessage()
            );
        }
    }

    /**
     * Test getInvocationBuilder
     *
     * Validate the getInvocationBuilder method of the tested class
     *
     * @throws ReflectionException
     */
    public function testGetInvocationBuilder()
    {
        $mock = $this->createMock(AccessorClass::class);
        $invocation = $this->getInvocationBuilder($mock, $this->once(), 'getAccessorProperty');

        $matcherReflex = $this->createPropertyReflection(get_class($invocation), 'matcher');
        $matcherReflex->setAccessible(true);

        $matcher = $matcherReflex->getValue($invocation);

        $methodNameRuleReflex = $this->createPropertyReflection(get_class($matcher), 'methodNameRule');
        $methodNameRuleReflex->setAccessible(true);
        $methodNameRule = $methodNameRuleReflex->getValue($matcher);
        $this->assertStringContainsString('getAccessorProperty', $methodNameRule->toString());

        $invocationRuleReflex = $this->createPropertyReflection(get_class($matcher), 'invocationRule');
        $invocationRuleReflex->setAccessible(true);
        $invocationRule = $invocationRuleReflex->getValue($matcher);

        $invokedCountReflex = $this->createPropertyReflection(get_class($invocationRule), 'expectedCount');
        $invokedCountReflex->setAccessible(true);
        $this->assertEquals(1, $invokedCountReflex->getValue($invocationRule));

        $mock->getAccessorProperty();
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
        return MethodClass::class;
    }
}
