<?php
class Arr{
    public static function flat($inputArray)
    {
        $outputArray = array();
        foreach ($inputArray as $i) {
            foreach ($i as $j) {
                array_push($outputArray, $j);
            }
        }
        return $outputArray;
    }
}