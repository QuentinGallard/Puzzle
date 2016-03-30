<?php

if (!function_exists('boolval')) {
    function boolval($val)
    {
        return (bool)$val;
    }
}

define('DEBUG', false);

interface GridInterface
{
    public function getCell($x, $y);

    public function setCell($x, $y, CellInterface $value);

    public function getGrid();

    public function toJson();
}

/**
 * Class Grid
 */
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
     * @throws Exception
     */
    public function getCell($x, $y)
    {
        if (is_null($x) || is_null($y)) {
            throw new Exception('Grid.getCell : Coordonnée vide. x=' . $x . ' , y=' . $y);
        }
        return $this->grid[$y][$x];
    }

    /**
     * @param int $x
     * @param int $y
     * @param CellInterface $value
     * @throws Exception
     */
    public function setCell($x, $y, CellInterface $value)
    {
        if (is_null($x) || is_null($y)) {
            throw new Exception('Grid.setCell : Coordonnée vide. x=' . $x . ' , y=' . $y);
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

interface CellInterface
{
    public function toHex();

    public function toDec();

    public function symetryX();

    public function symetryY();

    public function symetryXY();

    public static function listValues();
}

/**
 * Class Cell
 */
class Cell implements CellInterface
{
    public $up;
    public $right;
    public $down;
    public $left;

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


}

class Puzzle
{
    //Width
    const MIN_WIDTH = 20;
    const MAX_WIDTH = 30;

    //Height
    const MIN_HEIGHT = 20;
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
            $cell    = $this->grid->getCell($this->width - 1 - $x, $this->height - 1 - $y);
            $newCell = clone $cell;
            $newCell->symetryXY();
            return $newCell;
        }

        if ($symetryX) {
            $cell    = $this->grid->getCell($this->width - 1 - $x, $y);
            $newCell = clone $cell;
            $newCell->symetryX();
            return $newCell;
        }

        if ($symetryY) {
            $cell    = $this->grid->getCell($x, $this->height - 1 - $y);
            $newCell = clone $cell;
            $newCell->symetryY();
            return $newCell;
        }

        $cell = '';

        //up
        if ($y == 0) { //première ligne du tableau
            $up = 0;
        } else {
            $upCell = $this->grid->getCell($x, $y - 1);
            $up     = $upCell->down;
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
            $left     = $leftCell->right;
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

$level = !empty($_GET['level']) ? intval($_GET['level']) : 1;

$puzzle = new Puzzle();
$puzzle->seed($level);
$puzzle->initGridSize(5, 70, 5, 30);
$puzzle->generatePuzzle();

$grid = $puzzle->getGrid()->getGrid();

//exit($puzzle);
?>
<!DOCTYPE html>
<html>
<head lang='fr'>
    <title>Puzzle</title>
    <meta http-equiv='X-UA-Compatible' content='IE=Edge,IE=10,IE=9,IE=8'/>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" media="all" href="css/normalize.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style type="text/css">
        .container{
            min-width:  550px;
            max-width: <?= $puzzle->gridWidth() < 550 ? 550 : $puzzle->gridWidth()+100 ?>px;
            margin: 20px auto;
        }

        h1{
            text-align: center;
        }

        #game {
            background-color: #DFA;
            border-collapse: collapse;
            border: 1px solid #000;
            width: <?= $puzzle->gridWidth() ?>px;
            margin: 20px auto;
        }

        .pagination{
            list-style-type: none;
            margin: 10px auto;
            padding: 0;
            text-align: center;
        }

        .pagination li{
            display: inline-block;
            padding: 10px;
        }

        .values{
            list-style-type: none;
            margin: 10px auto;
            padding: 0;
            text-align: center;
        }

        [class^=cell] {
            display: inline-block;
            border: 0px solid black;
            width: <?= Puzzle::CELL_WIDTH?>px;
            height: <?= Puzzle::CELL_HEIGHT?>px;
            margin: 0px;
            padding: 0px;
            /*background: no-repeat left top;*/
            /*background-size: 100% 100%:*/
            /*font-size: 10px;*/
            /*padding: 5px;*/
            /*background-color: #FAFAFA;*/
        }

        [class^=line] {
            height: <?= Puzzle::CELL_HEIGHT?>px;
            width: <?= $puzzle->gridWidth() ?>px;
        }

        <?php foreach(Cell::listValues() as $key => $value): ?>
        .cell<?= $key ?> { }
        <?php endforeach; ?>
    </style>
</head>
<body>


<div class="container">
    <h1>Puzzle</h1>
    <ul class="pagination">
        <?php if( $level > 1): ?>
            <li><a href="/?level=<?= $level-1 ?>">&laquo;</a></li>
        <?php endif; ?>
        <li>level <a href="/?level=<?= $level ?>"><?= $level ?></a></li>
        <li><a href="/?level=<?= $level+1 ?>"> &raquo; </a></li>
    </ul>
    <div id="game">

        <?php foreach ($grid as $l => $line): ?>
            <div class="line-<?= $l ?>"><?php
                foreach ($line as $c => $cell):
                    ?><div class="cell<?= $cell ?>"><img src="svg/<?= $cell ?>.svg" alt="<?= $cell ?>" height="<?= Puzzle::CELL_HEIGHT ?>" width="<?= Puzzle::CELL_WIDTH ?>"/></div><?php
                endforeach; ?></div>
        <?php endforeach; ?>

    </div>
    <ul class="pagination">
        <?php if( $level > 1): ?>
            <li><a href="/?level=<?= $level-1 ?>">&laquo;</a></li>
        <?php endif; ?>
        <li>level <a href="/?level=<?= $level ?>"><?= $level ?></a></li>
        <li><a href="/?level=<?= $level+1 ?>"> &raquo; </a></li>
    </ul>


    <h2>Avalaible values</h2>
    <ul class="values">
        <?php foreach (Cell::listValues() as $key => $value): ?>
            <li class="cell<?= $key ?>" style="display: inline-block; border: 1px solid #F17000; margin: 2px; padding: 2px">
                <img src="svg/<?= $key ?>.svg" alt="<?= $key ?>" height="20" width="20"/></li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>