<?php

namespace PhpMvc\Framework\Concerns;

/**
 * Trait HasProperty
 *
 * Provee métodos
 * para verificar si una propiedad existe y está establecida en el modelo.
 * Permite verificar propiedades en múltiples contenedores (arreglos u objetos).
 */
trait HasProperty
{
    /**
     * @var array Contenedores de propiedades.
     * Puede contener arreglos u objetos donde se buscarán las propiedades.
     */
    private array $propertyContainers = [];

    /**
     * Establece los contenedores de propiedades.
     *
     * @param array $properties Un array contenedores de propiedades (arrays u objetos).
     */
    public function setPropertyContainers(array $properties): void
    {
        $this->propertyContainers = $properties;
    }

    /**
     * Verifica si una propiedad existe en los contenedores de propiedades.
     *
     * @param string $property El nombre de la propiedad a verificar.
     * @return bool Retorna true si la propiedad existe, false en caso contrario.
     * @example
     * ```php
     * if ($model->hasProperty('name')) {
     *     // La propiedad 'name' existe en el modelo.
     * }
     * ```
     */
    public function hasProperty(string $property): bool
    {
        $i = 0;
        $found = false;
        
        while (!$found && $i < count($this->propertyContainers)) {
            $container = $this->propertyContainers[$i];

            if (is_array($container) && array_key_exists($property, $container)) {
                $found = true;
            } elseif (is_object($container) && property_exists($container, $property)) {
                $found = true;
            }

            $i++;
        }

        return $found;
    }

    /**
     * Obtiene el valor de una propiedad en los contenedores de propiedades.
     *
     * @param string $property El nombre de la propiedad a obtener.
     * @return mixed El valor de la propiedad si existe, null en caso contrario.
     * @example
     * ```php
     * $value = $model->propertyValue('name');
     * if ($value !== null) {
     *     // La propiedad 'name' tiene un valor.
     * }
     * ```
     */
    public function propertyValue(string $property)
    {
        $i = 0;
        $found = false;
        $value = null;

        while (!$found && $i < count($this->propertyContainers)) {
            $container = $this->propertyContainers[$i];

            if (is_array($container) && array_key_exists($property, $container)) {
                $value = $container[$property];
                $found = true;
            } elseif (is_object($container) && property_exists($container, $property)) {
                $value = $container->$property;
                $found = true;
            }

            $i++;
        }

        return $value;
    }

    /**
     * Verifica si una propiedad está vacía en el modelo.
     *
     * @param string $property El nombre de la propiedad a verificar.
     * @return bool Retorna true si la propiedad está vacía, false en caso contrario.
     * @example
     * ```php
     * if ($model->isPropertyEmpty('name')) {
     *     // Handle empty property case
     * }
     * ```
     */
    public function isPropertyEmpty(string $property): bool
    {
        $propertyValue = $this->propertyValue($property);
        return empty($propertyValue);
    }

    /**
     * Check if a property is set in the model.
     *
     * @param string $property The name of the property to check.
     * @return bool Returns true if the property is set, false otherwise.
     * @example
     * ```php
     * if ($model->isPropertySet('name')) {
     *     // Do something with $model->name
     * }
     * ```
     */
    public function isPropertySet(string $property): bool
    {
        return $this->hasProperty($property) && !$this->isPropertyEmpty($property);
    }
}
