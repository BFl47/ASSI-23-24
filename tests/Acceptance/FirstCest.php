<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class FirstCest
{
    // public function frontpageWorks(AcceptanceTester $I)
    // {
    //     $I->amOnPage('/home.php');
    //     $I->see('Benvenuto su GymGenius!');
    // }

    public function AddAvailability(AcceptanceTester $I)
    {
        $I->amOnPage('/formlogin.php');

        $I->fillField('emaillog', 't3@gmail.com');

        $I->fillField('passwordlog', 'Qwertyuiop1!');

        $I->click('accedi');

        $I->seeCurrentUrlEquals('/app/home.php');

        $I->see('Sei loggato come t3');

        $I->click(['class' => 'bottone_profilo']);
        
        $I->seeCurrentUrlEquals('/app/profilo.php');

        $I->click(['class' => 'bottone_gestisci']);

        $I->seeCurrentUrlEquals('/app/appdisp_trainer.php');

        $I->fillField('data_nuovad', '2054-07-03');

        $I->click(['class' => 'bottone_add_disp']);

        $I->seeCurrentUrlEquals('/app/appdisp_trainer.php');

        $I->see('2054-07-03');

        
        

    }

    
}

/*Aggiungi disponibilità appuntamento 
Come utente registrato con ruolo ‘trainer’
In modo da aggiornare la mia lista delle disponibilità
Voglio aggiungere una data

Feature: Trainer can add appointment availability

    Scenario: Add appointment availability
        
        Given a valid trainer
        When I am on the login page 
        And I fill in "Email" with "test_trainer@gmail.com" 
        And I fill in "Password" with "Test123#" 
        And I press "Accedi" 
        Then I should be on trainer home page

        Given I am on trainer home page
        And I press "Gestisci" 
        Then I should be on the appointments page
        Given a valid date
        When I fill in "Data Nuova Disp" with "2034-07-03"
        And I press “Aggiungi disponibilità”
        Then I should see a confirmation alert 
        And I should see the new availability
*/     

