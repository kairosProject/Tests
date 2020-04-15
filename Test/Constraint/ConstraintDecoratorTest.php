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
 * @package  KairosProject\Tests\Test\Constraint
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\Tests\Test\Constraint;

use KairosProject\Constrait\ConstraintDecorator;
use KairosProject\Tests\AbstractTestClass;
use PHPUnit\Framework\Constraint\Constraint;
use ReflectionException;

/**
 * Class ConstraintDecoratorTest
 *
 * @category Test
 * @package  KairosProject\Tests\Test\Constraint
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 * @method ConstraintDecorator getInstance(array $injection = [])
 * @covers \KairosProject\Constrait\ConstraintDecorator
 */
class ConstraintDecoratorTest extends AbstractTestClass
{
    /**
     * Test constructor
     *
     * Validate the constructor method of the tested class
     *
     * @throws ReflectionException
     * @return void
     */
    public function testConstructor(): void
    {
        $this->assertConstructor(
            ['same:constraint' => $this->createMock(Constraint::class)]
        );
    }

    /**
     * Test toString
     *
     * Validate the toString method of the tested class
     *
     * @throws ReflectionException
     * @return void
     */
    public function testToString(): void
    {
        $constraint = $this->createMock(Constraint::class);
        $instance = $this->getInstance(compact('constraint'));

        $constraint->expects($this->once())
            ->method('toString')
            ->willReturn('2837');

        $this->assertEquals('2837', $instance->toString());
    }

    /**
     * Test evaluate
     *
     * Validate the evaluate method of the tested class
     *
     * @throws ReflectionException
     * @return void
     */
    public function testEvaluate(): void
    {
        $constraint = $this->createMock(Constraint::class);
        $instance = $this->getInstance(compact('constraint'));

        $constraint->expects($this->exactly(2))
            ->method('evaluate')
            ->withConsecutive(
                [$this->equalTo('other'), $this->equalTo('description'), $this->isFalse()],
                [$this->equalTo('some_other'), $this->equalTo('some_description'), $this->isTrue()]
            )
            ->willReturnOnConsecutiveCalls(true, false);

        $this->assertTrue($instance->evaluate('other', 'description', false));
        $this->assertFalse($instance->evaluate('some_other', 'some_description', true));
    }

    /**
     * Test count
     *
     * Validate the count method of the tested class
     *
     * @throws ReflectionException
     * @return void
     */
    public function testCount(): void
    {
        $constraint = $this->createMock(Constraint::class);
        $instance = $this->getInstance(compact('constraint'));

        $constraint->expects($this->once())
            ->method('count')
            ->willReturn(5);

        $this->assertCount(5, $instance);
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
        return ConstraintDecorator::class;
    }
}
