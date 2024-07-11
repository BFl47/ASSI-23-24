<?php

use PHPUnit\Framework\TestCase;
require_once './app/Utente.class.php';

class UtenteTest extends TestCase {
    public function testUserCreation() {
        $user = new User(1, "User","Mario Rossi","mariorossi@gmail.com","Qwertyuiop1!",$path_img = "/app/assets/profile.jpg");

        $this->assertEquals(1, $user->getId());
        $this->assertEquals("Mario Rossi", $user->getNome());
        $this->assertEquals("User", $user->getRuolo());
        $this->assertEquals("mariorossi@gmail.com", $user->getEmail());
        $this->assertEquals("Qwertyuiop1!", $user->getPassword());
        $this->assertEquals("/app/assets/profile.jpg", $user->getPath_img());
    }

    public function testTrainerCreation() {
        $trainer = new Trainer(2, 'Trainer', 'Alfredo Coppola', 'alfredocoppola@gmail.it', 'PASSword123#', $path_img = "/app/assets/profile.jpg");

        $this->assertEquals(2, $trainer->getId());
        $this->assertEquals("Alfredo Coppola", $trainer->getNome());
        $this->assertEquals("Trainer", $trainer->getRuolo());
        $this->assertEquals("alfredocoppola@gmail.it", $trainer->getEmail());
        $this->assertEquals("PASSword123#", $trainer->getPassword());
        $this->assertEquals("/app/assets/profile.jpg", $trainer->getPath_img());
    }

    public function testAdminCreation() {
        $admin = new Admin(3, 'Admin', 'admin', 'admin@admin.it', 'PASSword123#', $path_img = "/app/assets/profile.jpg");

        $this->assertEquals(3, $admin->getId());
        $this->assertEquals("Admin", $admin->getRuolo());
        $this->assertEquals("admin", $admin->getNome());
        $this->assertEquals("admin@admin.it", $admin->getEmail());
        $this->assertEquals("PASSword123#", $admin->getPassword());
        $this->assertEquals("/app/assets/profile.jpg", $admin->getPath_img());
    }

    public function testRecuperoPsw() {
        $user = new User(1, "User","Mario Rossi","mariorossi@gmail.com","Qwertyuiop1!",$path_img = "/app/assets/profile.jpg");

        $user->setToken("newGoogleId789");
        $this->assertEquals("newGoogleId789", $user->getToken());

        $user->setScadenza("2024-06-25 10:07:23");
        $this->assertEquals("2024-06-25 10:07:23", $user->getScadenza());
    }
}
?>

