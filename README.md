# Tests
A test helper library

This library is a simple helper for tests of PHP application, it provide functions that aim to be involved in 
constructor testing, getters and setters, etc...

## Basic usage

The test helper library is designed to automate the tests for basic logic elements. These basics elements will be the
simple constructor testing, the getter/setter or more abstractly accessor tests, and finally the instance construction
without requirements.

To be used, the test case has to extends the `AbstractTestClass` class, and define the tested class. 

```php
class MyClassTest extends AbstractTestClass
{
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

### Constructor test

First of all is the constructor test. To validate the construction of an instance, its necessary to test multiples
cases:
 * The parameter assignation
 * The parameter default value

To do this, the `assertConstructor()` method allow two arguments as array. The first array will contain the name of
the property expected to be assigned as key and the value to assign as value, representing the given arguments in the
same order as the call. The second array will have the same structure and represent the default value of the
properties.

To test the value assignment as reference (useful for object), the the property have to be prefixed by the `same:`
keyword.

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
    
    [...]
}
```

### Access a protected or private method

To access a private or protected method, the `getClassMethod('name')` can be used. It will return an instance of 
ReflectionMethod configured to be accessible by default. 

```php
class MyClassTest extends AbstractTestClass
{
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
        $method = $this->getClassMethod('methodName');

        $result = $method->invoke($instance);

        $this->assertEquals('The expected result', $result);
    }
    
    [...]
}
```

### Test a simple getter or a simple setter

The simple getters and setters are a common practice to access private or protected properties in an object. To 
validate that a property is correctly assigned or returned, then the 
`assertIsSimpleGetter('property', 'getterMethodName', 'value')` or the 
`assertIsSimpleSetter('property', 'getterMethodName', 'value')` methods can be used.

If you want to test both at the same time, then it is possible to use the 
`assertHasSimpleAccessor('property', 'value')` method.

```php
class MyClassTest extends AbstractTestClass
{
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
    
    [...]
}
```

### Get a fresh instance without calling the constructor

As we don't want to test the constructor for each tests, it is possible to get a fresh instance by calling the 
`getInstance()` method. The argument of this method is optional and can be used to set the dependencies directly
inside the properties, using the ReflectionProperty instead of the constructor or the setters.

```php
class MyClassTest extends AbstractTestClass
{
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
    
    [...]
}
```

## Get invocation builder to configure mock calls

To create a new InvocationBuilder instance, the `getInvocationBuilder($mock, new Invocation(), 'methodName')` can be
used. This method is nothing more than a helper for `$mock->expect($count)->method('methodName')`; 

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
    
    [...]
}
```

## Bulk properties content validation

To validate the value stored by a set of property, use the `assertPropertiesSame` and `assertPropertiesEqual` methods.

```php
class MyClassTest extends AbstractTestClass
{
    /**
     * Test set data
     *
     * Validate the data setter of some class
     *
     * @return void
     */
    public function testContent(): void
    {
        $instance = $this->getInstance();
        $date = new \DateTime();
        
        $instance->setSubject('Test subject');
        $instance->setEmail('matthieu.vallance@exemple.org');
        $instance->setDate($date);

        $this->assertPropertiesSame($instance, ['date' => $date]);
        $this->assertPropertiesEquals(
            $instance, 
            ['subject' => 'Test subject', 'email' => 'matthieu.vallance@exemple.org']
        );
    }
    
    [...]
}
```
