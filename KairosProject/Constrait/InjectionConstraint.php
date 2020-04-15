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
 * @package  KairosProject\Constrait
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\Constrait;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Class InjectionConstraint
 *
 * @category Test
 * @package  KairosProject\Constrait
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class InjectionConstraint extends ConstraintDecorator
{
    /**
     * Value
     *
     * The value to inject
     *
     * @var mixed
     */
    private $value;

    /**
     * ConstraintDecorator constructor.
     *
     * @param mixed      $value      The value to inject
     * @param Constraint $constraint The constraint to encapsulate
     *
     * @return void
     */
    public function __construct($value, Constraint $constraint)
    {
        $this->value = $value;

        parent::__construct($constraint);
    }

    /**
     * Get value
     *
     * Return the value to inject
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
