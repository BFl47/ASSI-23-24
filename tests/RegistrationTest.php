<?php

use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    public function testRegistration()
    {
        // Dati di esempio per la registrazione
        $postData = [
            'nome' => 'Mario Rossi',
            'email' => 'mario@example.com',
            'password' => 'Password123!',
            'confermapassword' => 'Password123!',
            'ruolo' => 'User', // Cambia in 'Trainer' o 'Admin' se vuoi testare altri ruoli
        ];

        // Simula l'invio POST al file formsignup.php
        $_POST = $postData;

        // Include il file che gestisce la registrazione 
        require_once __DIR__ . '/../app/formsignup.php';

        // Verifica che la registrazione sia avvenuta correttamente
        $registeredUser = getUserByEmail($postData['email']);
        $this->assertNotNull($registeredUser);
        $this->assertEquals($postData['email'], $registeredUser['email']);
        $this->assertEquals($postData['ruolo'], $registeredUser['ruolo']);
    }
}

// Funzione di esempio per ottenere l'utente dal database (simulazione)
function getUserByEmail($email)
{
    // Simulazione di una ricerca nel database degli utenti registrati
    $registeredUsers = [
        ['nome' => 'Mario Rossi', 'email' => 'mario@example.com', 'ruolo' => 'User'], // Simulazione di un utente registrato
        // Aggiungi altri utenti registrati se necessario
    ];

    foreach ($registeredUsers as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }

    return null; // Ritorna null se l'utente non è trovato
}
