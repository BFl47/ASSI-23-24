<?php
// File: tests/CorsoTest.php

use PHPUnit\Framework\TestCase;
require_once './app/Corso.class.php';

class CorsoTest extends TestCase
{
    public function testCostruttore()
    {
        $corso = new Corso(1, "Pilates", "Un corso bello", "Aula 108", "2024-07-15", "09:00", "17:00", "Mario Rossi", "RRULE:FREQ=WEEKLY;BYDAY=TU,TH;", "abc123def456");

        // happy path
        $this->assertEquals(1, $corso->getId());
        $this->assertEquals("Pilates", $corso->getNome());
        $this->assertEquals("Un corso bello", $corso->getDescrizione());
        $this->assertEquals("Aula 108", $corso->getLuogo());
        $this->assertEquals("2024-07-15", $corso->getData());
        $this->assertEquals("09:00", $corso->getTimeFrom());
        $this->assertEquals("17:00", $corso->getTimeTo());
        $this->assertEquals("Mario Rossi", $corso->getTrainer());
        $this->assertEquals("RRULE:FREQ=WEEKLY;BYDAY=TU,TH;", $corso->getRrule());
        $this->assertEquals("abc123def456", $corso->getIdGoogle());
    }

    public function testSetters()
    {
        $corso = new Corso(1, "Pilates", "Un corso bello", "Aula 108", "2024-07-15", "09:00", "17:00", "Mario Rossi", "RRULE:FREQ=WEEKLY;BYDAY=TU,TH;", "abc123def456");

        $corso->setIdGoogle("newGoogleId789");
        $this->assertEquals("newGoogleId789", $corso->getIdGoogle());
    }
}
?>
