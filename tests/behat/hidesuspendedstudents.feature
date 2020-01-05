@mod @mod_groupselect
Feature: Setting to enable hiding of suspended students.

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
    And the following "course enrolments" exist:
      | user | course | role | status |
      | teacher1 | C1 | editingteacher | 0 |
      | student1 | C1 | student | 0 |
      | student2 | C1 | student | 1 |
    And the following "groups" exist:
      | name | course | idnumber |
      | Group 1 | C1 | G1 |
    And the following "group members" exist:
      | user | group |
      | student1 | G1 |
      | student2 | G1 |
    And I log in as "admin"
    And I am on site homepage

  Scenario: Setting is off, suspended students will be visible.
    Given I follow "Course 1"
    And I turn editing mode on
    And I add a "Group self-selection" to section "1" and I fill the form with:
      | Name        | Group self-selection       |
    And I follow "Group self-selection"
    Then I should see "Student 2"

  Scenario: Turning the setting on, suspended students will be hidden.
    Given I navigate to "Plugins > Activity modules > Group self-selection" in site administration
    And I set the field "Hide suspended students" to "1"
    And I press "Save changes"
    And I am on site homepage
    Then I follow "Course 1"
    And I turn editing mode on
    And I add a "Group self-selection" to section "1" and I fill the form with:
      | Name        | Group self-selection       |
    And I follow "Group self-selection"
    Then I should not see "Student 2"