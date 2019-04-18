# Tests
A test helper library

This library is a simple helper for tests of PHP application, it provide functions that aim to be involved in 
constructor testing, getters and setters, etc...

## Basic usage

```php
class MyClassTest extends AbstractTestClass
{
    /**
     * Test constructor
     *
     * This method validate the constructor of the MyClass class.
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertConstructor(
            [
                'theFirstArgument' => 'theArgumentValue',
                'same:reference' => $this->createMock(\stdClass::class),
                'same:otherReference' => $this->createMock(\stdClass::class)
            ]
        );
        
        $this->assertConstructor(
            [
                'theFirstArgument' => 'theArgumentValue',
                'same:reference' => $this->createMock(\stdClass::class)
            ],
            [
                'otherReference' => null
            ]
        );
    }
    
    /**
     * Test method
     *
     * This method validate the methodName method of the MyClass class.
     *
     * @return void
     */
    public function testMethodName()
    {
        $instance = $this->getInstance();
        $this->assertProtectedMethod('methodName');
        $method = $this->getClassMethod('methodName', true);

        $result = $method->invoke($instance);

        $this->assertEquals('The expected result', $result);
    }

    /**
     * Test getProperty.
     *
     * This method validate the getProperty method of the MyClass class.
     *
     * @return void
     */
    public function testGetProcessEvent()
    {
        $propertyContent = $this->createMock(\stdClass::class);
        $this->assertIsSimpleGetter(
            'property',
            'getProperty',
            $propertyContent
        );
    }

    /**
     * Test for property accessor.
     *
     * Validate the getProperty and setProperty methods.
     *
     * @return void
     */
    public function testPropertyAccessor() : void
    {
        $this->assertHasSimpleAccessor('property', $this->createMock(\stdClass::class));
    }

    /**
     * Test for process.
     *
     * Validate the process methods of the MyClass class.
     *
     * @return void
     */
    public function testProcess()
    {
        $instance = $this->getInstance(
            [
                'logger' => $this->createMock(LoggerInterface::class),
                'eventDispatcher' => $this->createMock(EventDispatcher::class)
            ]
        );

        $this->assertEquals(42, $instance->process());
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
        return ConfigurationDefinition::class;
    }
}
```

## Get invocation builder to configure mock calls

```php
class MyClassTest extends AbstractTestClass
{
    /**
     * Test for routine.
     *
     * Validate the routine methods of the MyClass class.
     *
     * @return void
     */
    public function testRoutine()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $this->getInvocationBuilder($logger, $this->once(), 'debug')
                ->withConsecutive(
                    [
                        $this->equalTo('Start routine')
                    ],
                    [
                        $this->equalTo('End routine')
                    ]
                );
        
        $this->getInstance(['logger' => $logger])->routine();
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
        return ConfigurationDefinition::class;
    }
}
```
