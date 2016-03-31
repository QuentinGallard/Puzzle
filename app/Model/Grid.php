<?php

namespace App\Model;


class Grid implements GridInterface
{
    /**
     * @var array
     */
    private $grid;

    public function __construct()
    {
        $this->grid = [];
    }

    /**
     * @param $x
     * @param $y
     * @return CellInterface
     * @throws \Exception
     */
    public function getCell($x, $y)
    {
        if (is_null($x) || is_null($y)) {
            throw new \Exception('Grid.getCell : Coordonnée vide. x=' . $x . ' , y=' . $y);
        }
        return $this->grid[$y][$x];
    }

    /**
     * @param int $x
     * @param int $y
     * @param CellInterface $value
     * @throws \Exception
     */
    public function setCell($x, $y, CellInterface $value)
    {
        if (is_null($x) || is_null($y)) {
            throw new \Exception('Grid.setCell : Coordonnée vide. x=' . $x . ' , y=' . $y);
        }
        $this->grid[$y][$x] = $value;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->grid);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
    }

//    /**
//     * get Grid Line
//     * @param int $line
//     * @return mixed
//     * @throws Exception
//     */
//    public function getLine($line = 0){
//        if (!isset($this->grid[$line])){
//            throw new Exception('Grid.getLine : Ligne inexistante. line=' . $line);
//        }
//        return $this->grid[$line];
//    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return count($this->grid);
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        if ($this->getHeight() > 0) {
            return count($this->grid[0]);
        }
        return 0;
    }
}