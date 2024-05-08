<?php

namespace Utils;

class SplStatic2DArray extends \SplFixedArray
{
    public function __construct(int $size = 0, int $depth = 0)
    {
        parent::__construct($size);

        for ($i = 0; $i < $size; $i++) {
            $this[$i] = new \SplFixedArray($depth);
        }
    }
}
