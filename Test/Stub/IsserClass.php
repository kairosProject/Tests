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
 * @package  KairosProject\Tests\Test\Stub
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
namespace KairosProject\Tests\Test\Stub;

/**
 * Class IsserClass
 *
 * @category Test
 * @package  KairosProject\Tests\Test\Stub
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class IsserClass extends AccessorClass
{
    /**
     * Bool property
     *
     * The boolean property
     *
     * @var bool
     */
    public $boolProperty = false;

    /**
     * Is bool property
     *
     * Return the bool property value
     *
     * @return bool
     */
    public function isBoolProperty(): bool
    {
        return $this->boolProperty;
    }

    /**
     * Set bool property
     *
     * Set up the bool property
     *
     * @param bool $boolProperty The bool property
     *
     * @return AccessorClass
     */
    public function setBoolProperty(bool $boolProperty): AccessorClass
    {
        $this->boolProperty = $boolProperty;

        return $this;
    }
}
