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

    public function getUp();

    public function getDown();

    public function getLeft();

    public function getRight();
}