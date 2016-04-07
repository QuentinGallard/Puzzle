<?php

namespace App\Model;

class Puzzle
{
//Width
    const MIN_WIDTH = 5;
    const MAX_WIDTH = 30;

    //Height
    const MIN_HEIGHT = 5;
    const MAX_HEIGHT = 30;

    const CELL_WIDTH = 20;
    const CELL_HEIGHT = 20;


    /**
     * @var int width of the grid
     */
    public $width;

    /**
     * @var int height of the grid
     */
    public $height;

    /**
     * @var int
     */
    public $cellWidth;

    /**
     * @var int
     */
    public $cellHeight;

    /**
     * @var float
     */
    public $chanceSymetry;

    /**
     * @var GridInterface
     */
    public $grid;


    /**
     * @param GridInterface|null $grid
     */
    public function __construct(GridInterface $grid = null)
    {
        $this->grid = $grid ? $grid : new Grid();
    }

    public function seed($seed = 0)
    {
        mt_srand($seed);
        return $seed;
    }

    /**
     * @return Grid|GridInterface
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param int $min
     * @param int $max
     */
    public function initWidth($min = null, $max = null)
    {
        $min         = !empty($min) ? $min : self::MIN_WIDTH;
        $max         = !empty($max) ? $max : self::MAX_WIDTH;
        $this->width = $min === $max ? $min : mt_rand($min, $max);
    }

    /**
     * @param int $min
     * @param int $max
     */
    public function initHeight($min = null, $max = null)
    {
        $min          = !empty($min) ? $min : self::MIN_HEIGHT;
        $max          = !empty($max) ? $max : self::MAX_HEIGHT;
        $this->height = $min === $max ? $min : mt_rand($min, $max);
    }

    /**
     * Initialize the puzzle width and height with random value.
     * @param null $minWidth
     * @param null $maxWidth
     * @param null $minHeight
     * @param null $maxHeight
     */
    public function initGridSize($minWidth = null, $maxWidth = null, $minHeight = null, $maxHeight = null)
    {
        $this->initWidth($minWidth, $maxWidth);
        $this->initHeight($minHeight, $maxHeight);
    }

    /**
     * @return Grid|GridInterface
     */
    public function generatePuzzle()
    {
        $symetry = $this->hasSymetry();

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {

                $symetryX = ($symetry['x'] && $x >= ($this->width / 2));
                $symetryY = ($symetry['y'] && $y >= ($this->height / 2));

                $cell = $this->generateCell($x, $y, $symetryX, $symetryY);
                $this->grid->setCell($x, $y, $cell);
//                $this->grid->setCell($x, $y, "x$x/y$y"); //debug
            }
        }
        return $this->grid;
    }


    /**
     * @param $level
     * @return Grid|GridInterface
     */
    public function generate($level)
    {
        $this->chanceSymetry = 0.5;
        $this->seed($level);
        $this->initGridSize();
        return $this->generatePuzzle();
    }

    /**
     * @return int
     */
    public function gridWidth()
    {
        return $this->width * self::CELL_WIDTH;
    }

    /**
     * @return int
     */
    public function gridHeight()
    {
        return $this->height * self::CELL_HEIGHT;
    }

    /**
     * @param $x
     * @param $y
     * @param bool|false $symetryX
     * @param bool|false $symetryY
     * @return Cell
     */
    private function generateCell($x, $y, $symetryX = false, $symetryY = false)
    {

        if ($symetryX && $symetryY) {
            return $this->generateCellSymetryXY($x, $y);
        }

        if ($symetryX) {
            return $this->generateCellSymetryX($x, $y);
        }

        if ($symetryY) {
            return $this->generateCellSymetryY($x, $y);
        }

        $cell = '';

        //up
        if ($y == 0) { //première ligne du tableau
            $up = 0;
        } else {
            $upCell = $this->grid->getCell($x, $y - 1);
            $up     = $upCell->getDown();
        }

        //down
        if ($y == $this->height - 1) { //dernière ligne du tableau
            $down = 0;
        } else {
            $down = mt_rand(0, 1);
        }

        //left
        if ($x == 0) { //première colonne  du tableau
            $left = 0;
        } else {
            $leftCell = $this->grid->getCell($x - 1, $y);
            $left     = $leftCell->getRight();
        }

        //right
        if ($x == $this->width - 1) { //dernière colonne  du tableau
            $right = 0;
        } else {
            $right = mt_rand(0, 1);
        }

        $cell = $up . $right . $down . $left;

        return new Cell($cell);
    }

    /**
     * @param int $x
     * @param int $y
     * @return CellInterface
     */
    private function generateCellSymetryX($x, $y){
        $cell    = $this->grid->getCell($this->width - 1 - $x, $y);
        $newCell = clone $cell;
        $newCell->symetryX();
        return $newCell;
    }

    /**
     * @param int $x
     * @param int $y
     * @return CellInterface
     */
    private function generateCellSymetryY($x, $y){
        $cell    = $this->grid->getCell($x, $this->height - 1 - $y);
        $newCell = clone $cell;
        $newCell->symetryY();
        return $newCell;
    }

    /**
     * @param int $x
     * @param int $y
     * @return CellInterface
     */
    private function generateCellSymetryXY($x, $y){
        $cell    = $this->grid->getCell($this->width - 1 - $x, $this->height - 1 - $y);
        $newCell = clone $cell;
        $newCell->symetryXY();
        return $newCell;
    }

    /**
     * @return array
     */
    private function hasSymetry()
    {
        return [
            'x' => $this->width % 2 == 0 && mt_rand(1, 100) <= $this->chanceSymetry * 100,
            'y' => $this->height % 2 == 0 && mt_rand(1, 100) <= $this->chanceSymetry * 100
        ];
    }
}