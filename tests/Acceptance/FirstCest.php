<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class FirstCest
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/home.php');
        $I->see('Benvenuto su GymGenius!');
    }

    public function login(AcceptanceTester $I)
    {
        $I->amOnPage('/formlogin.php');
        $I->fillField('emaillog', 't3@suca.com');
        $I->fillField('passwordlog', 'Qwertyuiop1!');
        $I->click('accedi');
        $I->see('Sei loggato come t3');

    }

    
}
