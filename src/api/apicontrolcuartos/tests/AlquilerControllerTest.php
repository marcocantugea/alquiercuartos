<?php

namespace Tests;

use App\Http\Controllers\AlquilerController;

final class AlquilerControllerTest extends TestCase
{

    private AlquilerController $alquilercontroller;
    
    public function setUp() :void{
        parent::setUp();
        $this->alquilercontroller=new AlquilerController();
    }


    /**
     * @testWith [17, 50]
     *           [21,50]
     *           [23.1,100]
     *           [23.0001,100]
     *           [23.0010,100]
     *           [23.0100,100]
     *           [47,150]
     *           [61,150]
     *           [73,200]
     *           [84,250]
     *           [100,250]
     *           [103.012,300]
     *           [0,50]
     */
    public function test_ShouldCalculate_GetTotalAPagar_Success(float $minutos,int $expected){

        $totalApagar=$this->alquilercontroller->GetTotalAPagar($minutos);
        $this->assertEquals($totalApagar,$expected);
    }

}
