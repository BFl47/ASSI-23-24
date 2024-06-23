<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

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
}
