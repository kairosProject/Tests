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
 * Class AccessorClass
 *
 * @category Test
 * @package  KairosProject\Tests\Test\Stub
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class AccessorClass
{
    /**
     * Accessor property
     *
     * The accessible property
     *
     * @var mixed
     */
    public $accessorProperty;

    /**
     * Getter property
     *
     * The getter property
     *
     * @var mixed
     */
    public $getterProperty;

    /**
     * Setter property
     *
     * The setter property
     *
     * @var mixed
     */
    public $setterProperty;

    /**
     * AccessorClass constructor.
     *
     * @param mixed $accessorProperty The accessor property value
     * @param mixed $getterProperty   The getter property value
     * @param mixed $setterProperty   The setter property value
     *
     * @return void
     */
    public function __construct($accessorProperty, $getterProperty, $setterProperty)
    {
        $this->accessorProperty = $accessorProperty;
        $this->getterProperty = $getterProperty;
        $this->setterProperty = $setterProperty;
    }


    /**
     * Get public property
     *
     * Return the public property value
     *
     * @return mixed
     */
    public function getAccessorProperty()
    {
        return $this->accessorProperty;
    }

    /**
     * Set public property
     *
     * Set up the public property
     *
     * @param mixed $accessorProperty The public property value
     *
     * @return AccessorClass
     */
    public function setAccessorProperty($accessorProperty)
    {
        $this->accessorProperty = $accessorProperty;

        return $this;
    }

    /**
     * Get protected property
     *
     * Return the protected property value
     *
     * @return mixed
     */
    public function getGetterProperty()
    {
        return $this->getterProperty;
    }

    /**
     * Set private property
     *
     * Set up the private property
     *
     * @param mixed $setterProperty The private property value
     *
     * @return AccessorClass
     */
    public function setSetterProperty($setterProperty)
    {
        $this->setterProperty = $setterProperty;

        return $this;
    }
}
