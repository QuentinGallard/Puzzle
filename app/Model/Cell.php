<?php

namespace App\Model;


class Cell implements CellInterface
{
    private $up;
    private $right;
    private $down;
    private $left;

    public function __construct($params = null)
    {
        if (is_string($params)) {
            $params = str_split($params);
        }
        if (is_array($params)) {
            $this->up    = boolval($params[0]) ? 1 : 0;
            $this->right = boolval($params[1]) ? 1 : 0;
            $this->down  = boolval($params[2]) ? 1 : 0;
            $this->left  = boolval($params[3]) ? 1 : 0;
        }
    }

    public function __toString()
    {
        return ''
        . ($this->up ? 1 : 0)
        . ($this->right ? 1 : 0)
        . ($this->down ? 1 : 0)
        . ($this->left ? 1 : 0);
    }

    public function toHex()
    {
        return base_convert($this->__toString(), 2, 16);
    }

    public function toDec()
    {
        return base_convert($this->__toString(), 2, 10);
    }

    public function symetryX()
    {
        $right       = $this->left;
        $this->left  = $this->right;
        $this->right = $right;
    }

    public function symetryY()
    {
        $down       = $this->up;
        $this->up   = $this->down;
        $this->down = $down;
    }

    public function symetryXY()
    {
        $this->symetryX();
        $this->symetryY();
    }

    public static function listValues()
    {
        $values = [];
        for ($i = 0; $i < 16; $i++) {
            $value        = base_convert($i, 10, 2);
            $key          = str_pad($value, 4, '0', STR_PAD_LEFT);
            $value        = str_split($key);
            $values[$key] = $value;
        }
        return $values;
    }

    public function getUp()
    {
        return $this->up;
    }

    public function getDown()
    {
        return $this->down;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function getRight()
    {
        return $this->right;
    }


}