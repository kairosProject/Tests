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

use KairosProject\Constrait\InjectionConstraint;
use PHPUnit\Framework\Constraint\Constraint;
use ReflectionException;
use stdClass;

/**
 * Class InjectionConstraintTest
 *
 * @category Test
 * @package  KairosProject\Tests\Test\Constraint
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 * @method InjectionConstraint getInstance(array $injection = [])
 * @covers \KairosProject\Constrait\InjectionConstraint
 */
class InjectionConstraintTest extends ConstraintDecoratorTest
{
    /**
     * Test constructor
     *
     * Validate the constructor method of the tested class
     *
     * @return void
     * @throws ReflectionException
     */
    public function testConstructor(): void
    {
        $this->assertConstructor(
            [
                'same:value' => $this->createMock(stdClass::class),
                'same:constraint' => $this->createMock(Constraint::class)
            ]
        );
    }

    /**
     * Test getValue
     *
     * Validate the getValue method of the tested class
     *
     * @throws ReflectionException
     * @return void
     */
    public function testGetValue(): void
    {
        $this->assertIsSimpleGetter('value', 'getValue', 'some_value');
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
        return InjectionConstraint::class;
    }
}
