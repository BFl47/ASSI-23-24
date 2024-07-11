Feature: User can add course to favourites courses
 
    Scenario: Add course to favourites
        Given a valid user
        When I am on the login page
        And I fill in "Email" with "test_user@gmail.com"
        And I fill in "Password" with "Test123#"
        And I press "Accedi"
        Then I should be on the home page

        Given a valid course
        When I go to my profile page 
        And I press “Vedi corsi”
        Then I should be on the course page
        And I touch heart 
        Then I should see it filled

