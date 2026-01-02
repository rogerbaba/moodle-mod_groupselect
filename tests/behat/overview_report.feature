@mod @mod_groupselect
Feature: Testing overview integration in groupselect activity
  In order to summarize the groupselect activity
  As a user
  I need to be able to see the groupselect activity overview

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1        | 0        | 1         |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
      | student2 | Student   | 2        | student2@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           | status |
      | teacher1 | C1     | editingteacher | 0      |
      | student1 | C1     | student        | 0      |
      | student2 | C1     | student        | 1      |
    And the following "groups" exist:
      | name    | course | idnumber |
      | Group 1 | C1     | G1       |
    And the following "group members" exist:
      | user     | group |
      | student1 | G1    |
      | student2 | G1    |
    And I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    And I add a groupselect activity to course "Course 1" section "1" and I fill the form with:
      | Name     | Group self-selection |
    And I log out

  @javascript
  Scenario: The Group self-select activity overview report should generate log events
    Given the site is running Moodle version 5.0 or higher
    And I am on the "Course 1" "course > activities > groupselect" page logged in as "teacher1"
    When I am on the "Course 1" "course" page logged in as "teacher1"
    And I navigate to "Reports" in current page administration
    And I click on "Logs" "link"
    And I click on "Get these logs" "button"
    Then I should see "Course activities overview page viewed"
    And I should see "viewed the instance list for the module 'groupselect'"

  @javascript
  Scenario: The Group self-select activity index redirect to the activities overview
    Given the site is running Moodle version 5.0 or higher
    When I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Activities" block
    And I click on "Group self-selections" "link" in the "Activities" "block"
    Then I should see "An overview of all activities in the course"
    And I should see "Name" in the "groupselect_overview_collapsible" "region"
