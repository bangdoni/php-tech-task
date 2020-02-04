<?php

namespace App\Tests\Util;

use App\Util\Lunch;
use PHPUnit\Framework\TestCase;

class LunchTest extends TestCase
{
    public function testDate()
    {
        $dateLunch = new Lunch();

        $result = $dateLunch->date('2019-03-23');
        
        $responseJson = '[{"title":"Hotdog","ingredients":["Hotdog Bun","Sausage","Ketchup","Mustard"]}]';
        
        $this->assertEquals($responseJson, $result);
    }
}