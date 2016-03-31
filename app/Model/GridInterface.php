<?php

namespace App\Model;

interface GridInterface
{
    public function getCell($x, $y);

    public function setCell($x, $y, CellInterface $value);

    public function getGrid();

    public function toJson();
}