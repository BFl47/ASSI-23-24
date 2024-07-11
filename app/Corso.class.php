<?php
class Corso {
    public $id;
    public $nome;
    public $descrizione;
    public $luogo;
    public $data;
    public $time_from;
    public $time_to;
    public $trainer;
    public $rrule;
    public $id_google;

    // Costruttore
    public function __construct($id, $nome, $descrizione, $luogo, $data, $time_from, $time_to, $trainer, $rrule, $id_google = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descrizione = $descrizione;
        $this->luogo = $luogo;
        $this->data = $data;
        $this->time_from = $time_from;
        $this->time_to = $time_to;
        $this->trainer = $trainer;
        $this->rrule = $rrule;
        $this->id_google = $id_google;
    }

    // Metodi getter
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getDescrizione() {
        return $this->descrizione;
    }

    public function getLuogo() {
        return $this->luogo;
    }

    public function getData() {
        return $this->data;
    }

    public function getTimeFrom() {
        return $this->time_from;
    }

    public function getTimeTo() {
        return $this->time_to;
    }

    public function getTrainer() {
        return $this->trainer;
    }

    public function getRrule() {
        return $this->rrule;
    }

    public function getIdGoogle() {
        return $this->id_google;
    }

    public function setIdGoogle($id_google) {
        $this->id_google = $id_google;
    }
}
?>
