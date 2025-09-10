<?php
namespace PhpMvc\Framework\Concerns;

trait MapObjects
{
    /**
     * Convierte un array de datos en objetos an nimos.
     *
     * @param array $items
     * @return object[]
     */
    public function mapToGenericObjects(array $items): array
    {
        return array_map(fn ($item) => (object)$item, $items);
    }

    /**
     * Convierte un array de datos en objetos de la clase indicada.
     *
     * @template T of object
     * @param array $items
     * @param string $className
     * @return T[]
     */
    public function mapToClassObjects(array $items, string $className): array
    {
        return array_map(fn ($item) => new $className($item), $items);
    }
}