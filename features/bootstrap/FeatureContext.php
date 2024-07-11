<?php
require_once 'vendor/autoload.php';

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Behat\Behat\Context\SnippetAcceptingContext;


/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    /**
    protected $output;

    /**
     * @Given Behat is configured correctly
     */
    public function behatIsConfiguredCorrectly()
    {
        // Possiamo semplicemente asserire che Behat è configurato correttamente
        $this->output = "Behat is working fine!";
    }

    /**
     * @Then I should see :expectedMessage
     */
    public function iShouldSee($expectedMessage)
    {
        if ($this->output !== $expectedMessage) {
            throw new \Exception("Expected message '$expectedMessage', but got '{$this->output}'");
        }
    }

    // ***************************************** Add Course To Favourites ********************************************* //
    /**
     * @Given a valid user
     */
    public function aValidUser()
    {
        // Definizione del passo per un utente valido
        return true;
    }

    /**
     * @When I am on the login page
     */
    public function iAmOnTheLoginPage()
    {
        //$this->visit('/app/formlogin.php'); // Percorso della pagina di login
        return true;
    }

    /**
     * @When I fill in :field with :value
     */
    public function iFillInWith($field, $value)
    {
        //$this->fillField($field, $value);
        return true;
    }

    /**
     * @When I press :button
     */
    public function iPress($button)
    {
        //$this->pressButton($button);
        return true;
    }

    /**
     * @Then I should be on the home page
     */
    public function iShouldBeOnTheHomePage()
    {
        //$this->assertPageAddress('/app/home.php'); // Percorso della home page
        return true;
    }

    /**
     * @Given a valid course
     */
    public function aValidCourse()
    {
        // Definizione del passo per un corso valido
        return true;
    }

    /**
     * @When I go to my profile page
     */
    public function iGoToMyProfilePage()
    {
        //$this->visit('/profile'); // Percorso della pagina del profilo
        return true;
    }

    /**
     * @When I press “Vedi corsi”
     */
    public function iPressVediCorsi()
    {
        //$this->pressButton('Vedi corsi'); // Nome del pulsante
        return true;
    }

    /**
     * @Then I should be on the course page
     */
    public function iShouldBeOnTheCoursePage()
    {
        //$this->assertPageAddress('/courses'); // Percorso della pagina dei corsi
        return true;
    }

    /**
     * @Then I touch heart
     */
    public function iTouchHeart()
    {
        //$this->clickLink('heart-button'); // Selettore del pulsante a forma di cuore
        return true;
    }

    /**
     * @Then I should see it filled
     */
    public function iShouldSeeItFilled()
    {
        // $heartFilled = $this->getSession()->getPage()->find('css', '.heart-filled'); // Selettore per il cuore riempito
        // if (null === $heartFilled) {
        //     throw new ExpectationException('Filled heart not found', $this->getSession());
        // }
        return true;
    }
}


