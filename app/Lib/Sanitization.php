<?php

namespace App\Lib;

class Sanitization
{
    public function getPostVarsTrimmed($postVarsArray)
    {
        return array_map([$this, "trimArray"], $postVarsArray);
    }

    public function trimArray($item)
    {
        return trim($item);
    }


}