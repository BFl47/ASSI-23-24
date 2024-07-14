<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class AlfaCest
{
    public function Signup(AcceptanceTester $I) {

        $I->amOnPage('/home.php');
        
        $I->click('Signup');

        $I->seeCurrentUrlEquals('/app/formsignup.php');

        $I->fillField('nome', 'test11');

        $I->fillField('email', 'test11@gmail.com');
        
        $I->fillField('password', 'Qwertyuiop1!');

        $I->fillField('confermapassword', 'Qwertyuiop1!');

        $I->selectOption('Ruolo','User');

        $I->click('signup');

        $I->seeCurrentUrlEquals('/app/home.php');

    }
}
