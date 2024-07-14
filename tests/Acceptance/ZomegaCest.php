<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class ZomegaCest
{
    public function Logout(AcceptanceTester $I){

        $I->amOnPage('/formlogin.php');
        
        $I->fillField('emaillog', 'test1@gmail.com');
        
        $I->fillField('passwordlog', 'Test123#');
        
        $I->click('accedi');
        
        $I->see('Sei loggato come test1');
        
        $I->seeCurrentUrlEquals('/app/home.php');

        $I->click(['class' => 'logout']);
        
        $I->seeCurrentUrlEquals('/app/home.php');

        $I->see('Benvenuto su GymGenius!');  
    }
}
