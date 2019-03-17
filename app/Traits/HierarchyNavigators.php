<?php

namespace App\Traits;

trait HierarchyNavigators
{

    public function __construct()
    {
        parent::__construct();


        $fields = ['type'];

        $class = new \ReflectionClass($this);

        if ($class->hasMethod('children')) {
            $fields[] = 'contains';
        }


        $this->appends = array_merge($this->appends, $fields);
        $this->visible = array_merge($this->visible, $fields);
    }


    public function getTypeAttribute()
    {
        $class = new \ReflectionClass($this);
        return strtolower($class->getShortName());
    }

    public function getContainsAttribute()
    {
        $class = new \ReflectionClass($this->children()->getRelated());
        return strtolower($class->getShortName());
    }
}