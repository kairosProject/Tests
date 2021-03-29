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
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ConstraintDecorator
 *
 * @category Test
 * @package  KairosProject\Constrait
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ConstraintDecorator extends Constraint
{
    /**
     * Constraint
     *
     * The encapsulated constraint instance
     *
     * @var Constraint
     */
    private $constraint;

    /**
     * ConstraintDecorator constructor.
     *
     * @param Constraint $constraint The constraint to encapsulate
     *
     * @return void
     */
    public function __construct(Constraint $constraint)
    {
        $this->constraint = $constraint;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->constraint->toString();
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param mixed  $other        The other parameter
     * @param string $description  The evaluation description
     * @param bool   $returnResult Define if an exception must be thrown on failure, or if null has to be returned
     *
     * @return bool|null
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        return $this->constraint->evaluate($other, $description, $returnResult);
    }

    /**
     * Counts the number of constraint elements.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->constraint->count();
    }
}
