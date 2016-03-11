<?php

if (!function_exists('boolval')) {
    function boolval($val) {
        return (bool) $val;
    }
}

define('DEBUG', false);

class Puzzle
{
    const MIN_X = 5;
    const MAX_X = 70;
    const MIN_Y = 5;
    const MAX_Y = 40;
    const CELL_WIDTH = 20;
    const CELL_HEIGHT = 20;

    /**
     * @var int
     */
    public $x;

    /**
     * @var int
     */
    public $y;

    /**
     * @var array
     */
    public $grid;

    /**
     * @param null $x
     * @param null $y
     */
    public function __construct($x = null, $y = null)
    {
        $this->x = $x;
        $this->y = $y;
    }


    /**
     * @param int $min
     * @param int $max
     */
    public function initRandomX($min = self::MIN_X, $max = self::MAX_X)
    {
        $this->x = mt_rand($min, $max);
    }

    /**
     * @param int $min
     * @param int $max
     */
    public function initRandomY($min = self::MIN_Y, $max = self::MAX_Y)
    {
        $this->y = mt_rand($min, $max);
    }

    /**
     * Initialize the puzzle
     * @param int $minX
     * @param int $maxX
     * @param int $minY
     * @param int $maxY
     */
    public function initRandomSize($minX = self::MIN_X, $maxX = self::MAX_X, $minY = self::MIN_Y, $maxY = self::MAX_Y)
    {
        $this->initRandomX($minX, $maxX);
        $this->initRandomY($minY, $maxY);
    }

    /**
     * @return string
     */
    public function initGrid()
    {

        for ($y = 0; $y < $this->y; $y++) {
            for ($x = 0; $x < $this->x; $x++) {
                $cell = $this->generateCell($x, $y);
                $this->grid[$y][$x] = $cell;
//                $this->grid[$y][$x] = "x$x/y$y"; //debug
            }
        }
        return $this->grid;
    }

    private function generateCell($x, $y){
        $cell = '';

        //up
        if ($y == 0){ //première ligne du tableau
            $up = 0;
        } else {
            $upCell = $this->grid[$y-1][$x];
            $up = $upCell->down;
        }

        //down
        if ($y == $this->y - 1){ //dernière ligne du tableau
            $down = 0;
        } else {
            $down = mt_rand(0,1);
        }

        //left
        if ($x == 0){ //première colonne  du tableau
            $left = 0;
        } else {
            $leftCell = $this->grid[$y][$x-1];
            $left = $leftCell->right;
        }

        //right
        if ($x == $this->x - 1){ //dernière colonne  du tableau
            $right = 0;
        } else {
            $right = mt_rand(0,1);
        }

        $cell = $up.$right.$down.$left;

        return new Cell($cell);
    }

    public function toJson()
    {
        return json_encode($this->grid);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function gridWidth(){
        return $this->x * self::CELL_WIDTH;
    }

    public function gridHeight(){
        return $this->y * self::CELL_HEIGHT;
    }
}

class Cell
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

    public function __toString(){
        return ''
        .($this->up ? 1:0)
        .($this->right ? 1:0)
        .($this->down ? 1:0)
        .($this->left ? 1:0);
    }

    public static function listValues(){
        $values = [];
        for ($i = 0; $i < 16; $i++){
            $value = base_convert($i, 10, 2);
            $key = str_pad($value, 4, '0', STR_PAD_LEFT);
            $value = str_split($key);
            $values[$key] = $value;
        }
        return $values;
    }

}

$puzzle = new Puzzle();
$puzzle->initRandomSize();
$puzzle->initGrid();
//exit($puzzle);
?>
<!DOCTYPE html>
<html>
    <head lang='fr'>
        <title>Puzzle</title>
        <meta http-equiv='X-UA-Compatible' content='IE=Edge,IE=10,IE=9,IE=8'/>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--    <link type="text/css" rel="stylesheet" media="all" href="css/normalize.css">-->
        <!--Import Google Icon Font-->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style type="text/css">
            #game{
                background-color: #EFF;
                border-collapse: collapse;
                border: 1px solid #000;
                width: <?= $puzzle->gridWidth() ?>px;
            }
            [class^=cell]{
                display:inline-block;
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
            [class^=line]{
                height: <?= Puzzle::CELL_HEIGHT?>px;
                width: <?= $puzzle->gridWidth() ?>px;
            }
            <?php foreach(Cell::listValues() as $key => $value): ?>
            .cell<?= $key ?>{
            <?php /*    border-width: <?=$value[0]?>px <?=$value[1]?>px <?=$value[2]?>px <?=$value[3]?>px; */ ?>
    /*            background-image : url("svg/*/<?//=$key?>/*.svg") ;*/
    /*            background-color: #F*/<?//= base_convert($key, 2, 16) ?>/*F*/<?//= base_convert($key, 2, 16) ?>/*F*/<?//= base_convert($key, 2, 16) ?>/*;*/
            }
            <?php endforeach; ?>
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row center-align">
            <h2>Puzzle</h2>
            <div id="game" class="col s12">
                <?php foreach($puzzle->grid as $l => $line): ?>
                    <div class="line-<?= $l ?>"><?php foreach($line as $c => $cell): ?><div class="cell<?= $cell ?>"><img src="svg/<?= $cell ?>.svg" alt="<?= $cell ?>" height="20" width="20"/></div><?php endforeach; ?></div>
                <?php endforeach; ?>
            </div>


            </div>
            <div class="row">
                <h2>Avalaible values</h2>
                <ul>
                    <?php foreach(Cell::listValues() as $key => $value): ?>
                        <li class="cell<?=$key?>" style="display: block"></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>