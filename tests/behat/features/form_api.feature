@api
Feature: Create a config form to edit the site name.

  Scenario: Check permission.
    And I am logged in as a user with the "administer mymodule" permission
    And I am on "/custom-site-settings"
    Then I should see text matching "Site name"
    Then I should not see text matching "Access denied"
