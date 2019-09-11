<?php

namespace App\Collector;

use Illuminate\Support\Collection as Collection;

/**
* collector class
*/
class Collector extends Collection
{
    public function __get($name)
    {
        $value = $this->get($name);
        // if array return another collection instance.
        if (is_array($value)) {
            return $this->make($value);
        }
        return $value;
    }
}