@api
Feature: On the article page show a block with atricle teasers.
  Filter the articles by author with the logged user.

  Scenario: Create articles by one author. Go to an article and check the teasers block.
    Given users:
      | name     | mail            | status |
      | Joe User | joe@example.com | 1      |
      | Another User | another_user@example.com | 1 |
    And "article" content:
      | title          | author   | body           |
      | ArticleByJoe | Joe User | PLACEHOLDER BODY |
      | Article by another user | Another User | PLACEHOLDER BODY|
      | Article by Joe 1 | Joe User | PLACEHOLDER BODY|
      | Article by another user 1 | Another User | PLACEHOLDER BODY |
      | Article by Joe 2 | Joe User | PLACEHOLDER BODY |
      | Article by Joe 3 | Joe User | PLACEHOLDER BODY |
    And I am logged in as "Joe User"
    And I am on "articlebyjoe"
    Then I should not see text matching "not found"
    Then I should see text matching "Article by Joe"
    Then I should see text matching "Article by Joe 1"
    Then I should see text matching "Article by Joe 2"
    Then I should see text matching "Article by Joe 3"
    Then I should not see text matching "Article by another user"
    Then I should not see text matching "Article by another user 1"