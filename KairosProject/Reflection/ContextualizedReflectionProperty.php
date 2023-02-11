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

namespace KairosProject\Reflection;

use ReflectionProperty;

/**
 * Abstract test class
 *
 * This class is used as placeholder for test implementation
 *
 * @category Test
 * @package  Chronos
 * @author   matthieu vallance <matthieu.vallance@cscfa.fr>
 * @license  MIT <https://opensource.org/licenses/MIT>
 * @link     http://cscfa.fr
 */
class ContextualizedReflectionProperty extends ReflectionProperty
{
    /**
     * Store the initialization state of the old value
     *
     * @var bool
     */
    private $initialized = false;

    /**
     * Store the old property value before setting it up
     *
     * @var null
     */
    private $oldValue = null;

    /**
     * Set property value
     *
     * @link https://php.net/manual/en/reflectionproperty.setvalue.php
     * @param mixed $objectOrValue If the property is non-static an object must
     * be provided to change the property on. If the property is static this
     * parameter is left out and only $value needs to be provided.
     * @param mixed $value The new value.
     * @return void No value is returned.
     */
    public function setValue($objectOrValue, $value = null): void
    {
        if (!$this->initialized) {
            $this->initialized = true;
            $this->oldValue = parent::getValue();
        }

        if (is_a($objectOrValue, $this->getDeclaringClass()->getName())) {
            parent::setValue($objectOrValue, $value);
            return;
        }

        parent::setValue($objectOrValue);
    }

    /**
     * Reset the old property value
     *
     * @return void
     */
    public function resetValue()
    {
        if ($this->initialized) {
            parent::setValue($this->oldValue);
        }
    }
}
