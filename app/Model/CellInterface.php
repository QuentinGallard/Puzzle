<?php

namespace App\Model;


interface CellInterface
{
    public function toHex();

    public function toDec();

    public function symetryX();

    public function symetryY();

    public function symetryXY();

    public static function listValues();
}