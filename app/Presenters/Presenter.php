<?php

namespace App\Presenters;

abstract class Presenter
{
    /**
     * @param array $items
     *
     * @return array
     */
    public function collections(array $items)
    {
        $items['data'] = array_map([$this, 'transform'], $items['data']);

        return $items;
    }

    /**
     * @param $item
     *
     * @return mixed
     */
    abstract public function transform($item);
}
