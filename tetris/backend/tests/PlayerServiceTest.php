<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\PlayerService;
use App\Entity\Board;
use App\Service\GameService;


class PlayerServiceTest extends WebTestCase
{

    private $serv;
    private $serv2;
    private $secret = 'def000004ba8fee5d13ac2b2d8f13d3762bd732df2513df86da00f96da48c36623de3fe1bd45d0e63b82066fe31ccd1f090883d60312c989cd4893797b143c4a33495263';

    public function setUp(){
        $client = static::createClient();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $this->serv = new PlayerService($entityManager, $this->secret);

        $this->serv2 = new GameService($entityManager, $this->secret);
    }

    public function testRegisterPlayer()
    {
        $p = $this->serv->registerPlayer('Ana');
        $this->assertEquals('Ana', $p->getName(), 'right name');
        $this->assertGreaterThan(0, $p->getId(), 'positive id');
    }

    public function testGameVictory() 
    {
        $array = [Board::NOUGHT,Board::NOUGHT,Board::CROSS,
        Board::CROSS,Board::CROSS,Board::NOUGHT,
        Board::CROSS,Board::CROSS,Board::NOUGHT];

        $board = new Board();
        $board->setPositions($array);
        $p = $this->serv2->gameVictory($board);
        $this->assertEquals([2,4,6], $p);


        $rand = rand(0,2);

        // Check that rows match
        $array[$rand*3] = Board::NOUGHT;
        $array[$rand*3+1] = Board::NOUGHT;
        $array[$rand*3+2] = Board::NOUGHT;
        $board->setPositions($array);
        $p = $this->serv2->gameVictory($board);
        $this->assertEquals([$rand*3,$rand*3+1,$rand*3+2], $p);

        
        /*
        $array2 = $array;

        $auxArray = [0,3,6];
        $rand = $auxArray[rand(0,2)];


        // Check that columns match
        $array2[$rand] = Board::NOUGHT;
        $array2[$rand+3] = Board::NOUGHT;
        $array2[$rand+6] = Board::NOUGHT;
        $board->setPositions($array2);
        $p = $this->serv2->gameVictory($board);
        $this->assertEquals([$rand,$rand+3,$rand+6], $p);
        */


    }
}