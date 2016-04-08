<?php

if (!function_exists('boolval')) {
    function boolval($val)
    {
        return (bool)$val;
    }
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

define('DEBUG', false);

$level = !empty($_GET['level']) ? intval($_GET['level']) : 1;

$puzzle = new App\Model\Puzzle();
$grid = $puzzle->generate($level)->getGrid();
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
            width: <?= App\Model\Puzzle::CELL_WIDTH?>px;
            height: <?= App\Model\Puzzle::CELL_HEIGHT?>px;
            margin: 0px;
            padding: 0px;
            /*background: no-repeat left top;*/
            /*background-size: 100% 100%:*/
            /*font-size: 10px;*/
            /*padding: 5px;*/
            /*background-color: #FAFAFA;*/
        }

        [class^=line] {
            height: <?= App\Model\Puzzle::CELL_HEIGHT?>px;
            width: <?= $puzzle->gridWidth() ?>px;
        }

        <?php foreach(App\Model\Cell::listValues() as $key => $value): ?>
        .cell<?= $key ?> { }
        <?php endforeach; ?>
    </style>
</head>
<body>


<div class="container">
    <h1>Puzzle</h1>

    <ul class="pagination">
        <?php if( $level > 1): ?>
            <li><a href="?level=<?= $level-1 ?>">&laquo;</a></li>
        <?php endif; ?>
        <li>level <a href="?level=<?= $level ?>"><?= $level ?></a></li>
        <li><a href="?level=<?= $level+1 ?>"> &raquo; </a></li>
    </ul>

    <div id="game">
        <?php foreach ($grid as $l => $line): ?>
            <div class="line-<?= $l ?>"><?php
                foreach ($line as $c => $cell):
                    ?><div class="cell<?= $cell ?>"><img src="svg/<?= $cell ?>.svg" alt="<?= $cell ?>" height="<?= App\Model\Puzzle::CELL_HEIGHT ?>" width="<?= App\Model\Puzzle::CELL_WIDTH ?>"/></div><?php
                endforeach; ?></div>
        <?php endforeach; ?>
    </div>

    <ul class="pagination">
        <?php if( $level > 1): ?>
            <li><a href="?level=<?= $level-1 ?>">&laquo;</a></li>
        <?php endif; ?>
        <li>level <a href="?level=<?= $level ?>"><?= $level ?></a></li>
        <li><a href="?level=<?= $level+1 ?>"> &raquo; </a></li>
    </ul>

    <h2>Avalaible values</h2>
    <ul class="values">
        <?php foreach (App\Model\Cell::listValues() as $key => $value): ?>
            <li class="cell<?= $key ?>" style="display: inline-block; border: 1px solid #F17000; margin: 2px; padding: 2px">
                <img src="svg/<?= $key ?>.svg" alt="<?= $key ?>" height="20" width="20"/></li>
        <?php endforeach; ?>
    </ul>

    <h2>Leaderboard</h2>
    <table id="leaderboard">
        <tr>
            <th>Name</th>
            <th>Score</th>
        </tr>
        <tr>
            <td>Roger Rabbit</td>
            <td>12000</td>
        </tr>
    </table>

</div>
</body>
</html>