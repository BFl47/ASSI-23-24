<?php

use PHPUnit\Framework\TestCase;
require_once './app/Corso.class.php';
require_once './app/Utente.class.php';
require_once './app/google_calendar_add_event/GoogleCalendarApi.class.php';

class CorsoTest extends TestCase
{
    public function testCostruttore() {
        $corso = new Corso(1, "Pilates", "Un corso bello", "Aula 108", "2024-07-15", "09:00", "17:00", "Mario Rossi", "RRULE:FREQ=WEEKLY;BYDAY=TU,TH;", "abc123def456");

        // happy path c:
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

    public function testSetters() {
        $corso = new Corso(1, "Pilates", "Un corso bello", "Aula 108", "2024-07-15", "09:00", "17:00", "Mario Rossi", "RRULE:FREQ=WEEKLY;BYDAY=TU,TH;", "abc123def456");

        $corso->setIdGoogle("newGoogleId789");
        $this->assertEquals("newGoogleId789", $corso->getIdGoogle());
    }

    public function testAdminCreation()
    {
        $admin = new Admin(3, 'Admin', 'admin', 'admin@admin.it', 'PASSword123#', "/app/assets/profile.jpg");

        $this->assertInstanceOf(Admin::class, $admin);

        return $admin;
    }

    /**
     * @depends testAdminCreation
     */
    public function testCreaCorso($admin) {
        
        $postData = [
            'nomeevento' => 'Pilates',
            'descrizioneevento' => 'Un corso molto bello',
            'luogoevento' => 'Aula 105',
            'trainerevento' => '1',
            'dataevento' => '2024-07-11', 
            'terminadopo' => '6',
            'days' => ['TU', 'TH'],
            'time_from' => '09:00',
            'time_to' => '10:00'
        ];

        $_POST = $postData;

        require_once __DIR__ . '/../app/google_calendar_add_event/index.php';

        $id = 1;
        $rrule = "RRULE:FREQ=WEEKLY;BYDAY=" . implode(',', $_POST['days']) . ";COUNT=" . intval($_POST['terminadopo']);

        $corso = $admin->creaCorso($id, $_POST['nomeevento'], $_POST['descrizioneevento'], $_POST['luogoevento'], $_POST['dataevento'],  $_POST['time_from'],$_POST['time_to'], $_POST['trainerevento'], $rrule);
        
        $this->assertNotNull($corso);

        $this->assertInstanceOf(Corso::class, $corso);
        $this->assertEquals($id, $corso->id);
        $this->assertEquals($postData['nomeevento'], $corso->getNome());
        $this->assertEquals($postData['descrizioneevento'], $corso->getDescrizione());
        $this->assertEquals($postData['luogoevento'], $corso->getLuogo());
        $this->assertEquals($postData['dataevento'], $corso->getData());
        $this->assertEquals($postData['time_from'], $corso->getTimeFrom());
        $this->assertEquals($postData['time_to'], $corso->getTimeTo());
        $this->assertEquals($postData['trainerevento'], $corso->getTrainer());
        $this->assertEquals($rrule, $corso->getRrule());
        $this->assertEquals(null, $corso->getIdGoogle());

        $googleCalendarApiStub = $this->createStub(GoogleCalendarApi::class);

        $googleEventId = "123";
        $access_token = "access_token";
        $calendar_id = "calendar_id";
        $event_datetime = "2024-07-11T09:00:00";
        $event_timezone = "Europe/Rome";

        $googleCalendarApiStub->method('CreateCalendarEvent')
            ->willReturn($googleEventId);
        
        $corso->setIdGoogle($googleCalendarApiStub->CreateCalendarEvent($access_token, $calendar_id, $corso->getData(), 0, $event_datetime, $event_timezone));

        $this->assertEquals($googleEventId, $corso->getIdGoogle());
   }

}
?>
