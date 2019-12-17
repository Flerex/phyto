<?php

namespace App\Traits;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

trait HierarchyNavigators
{

    /**
     * HierarchyNavigators constructor.
     * @param  array  $attributes
     * @throws ReflectionException
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $fields = ['type'];

        $class = new ReflectionClass($this);

        if ($class->hasMethod('children')) {
            $fields[] = 'contains';
        }

        $this->appends = array_merge($this->appends, $fields);
        $this->visible = array_merge($this->visible, $fields);
    }


    /**
     * @return string
     * @throws ReflectionException
     */
    public function getTypeAttribute(): string
    {
        $class = new ReflectionClass($this);
        return strtolower($class->getShortName());
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function getContainsAttribute(): string
    {
        if (!method_exists($this, 'children')) {
            throw new RuntimeException('The object does not have a children method.');
        }

        $class = new ReflectionClass($this->children()->getRelated());

        return strtolower($class->getShortName());
    }
}
