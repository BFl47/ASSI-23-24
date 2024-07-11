<?php
class Disp {
    public $id_trainer;
    public $data_d;
    public $free;
    
    public function __construct($id_trainer, $data_d) {
        $this->id_trainer = $id_trainer;
        $this->data_d = $data_d;
        $this->free = true;
    }

    public function getIdTrainer() {
        return $this->id_trainer;
    }

    public function getData() {
        return $this->data_d;
    }

    public function getFlag() {
        return $this->free;
    }

    public function toggleFlag(){
        if($this->free==true){
            $this->free=false;
        }else{
            $this->free=true;
        }
    }
}
?>