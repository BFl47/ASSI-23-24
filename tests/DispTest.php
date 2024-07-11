<?php

use PHPUnit\Framework\TestCase;
require_once './app/Disp.class.php';
require_once './app/Utente.class.php';

class DispTest extends TestCase {

    public function testTrainerCreation(){
        $trainer = new Trainer(3, 'Trainer', 'Giorgio', 'giorgio@gmail.com', 'PASSword123#', "/app/assets/profile.jpg");
        $this->assertInstanceOf(Trainer::class, $trainer);
        return $trainer;
    }

    /**
     * @depends testTrainerCreation
     */
    public function testCreaDisp($trainer){
        $postData = [
            'data_nuovad' => '2124-08-13'
        ];
        $_POST = $postData;
        $id_trainer = $trainer->getId();

        $today = date("Y-m-d H:i:s");
        $this->assertGreaterThanOrEqual($today, $_POST['data_nuovad']);

        $disp = $trainer->creaDisp($_POST['data_nuovad']);
        $this->assertNotNull($disp);
        //test sui valori dei campi dell'oggetto disp
        $this->assertInstanceOf(Disp::class, $disp);
        $this->assertEquals($id_trainer, $disp->getIdTrainer());
        $this->assertEquals($postData['data_nuovad'], $disp->getData());
        $this->assertEquals(true, $disp->getFlag());

        return $disp;
   }
   /**
     * @depends testCreaDisp
     */
   public function testToggleFlag($disp){
        $flagOld=$disp->getFlag();
        $disp->toggleFlag();
        if($flagOld==true){
            $this->assertEquals(false,$disp->getFlag());
        }else{
            $this->assertEquals(true,$disp->getFlag());
        }
   }
}
?>