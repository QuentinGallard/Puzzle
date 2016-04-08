<?php
/**
 * Created by PhpStorm.
 * User: 205-Quentin
 * Date: 08/04/2016
 * Time: 15:39
 */

namespace App\Model;


class Score
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var int
     */
    private $score;

    /**
     * @var \DateTime
     */
    private $startTimestamp;

    /**
     * @var \DateTime
     */
    private $endTimestamp;

   


    public function startGame(){
        $this->startTimestamp = new \DateTime();
    }

    public function endGame(){
        $this->startTimestamp = new \DateTime();
    }

    public function userGameTime(){
        return startGame().diff($this->endGame());
    }



}