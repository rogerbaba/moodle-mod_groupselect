@mod @mod_groupselect
Feature: See the group self-selection activity dates.
  In order to enrol in a group in timely manner
  As a student
  I need to see the group self-selection dates

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | showactivitydates |
      | Course 1 | C1        | 0        | 1                 |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  @javascript
  Scenario: Students see activity dates of group self-selection.
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Group self-selection" to section "1" and I fill the form with:
      | Name                     | Group self-selection |
      | id                       | scheduler1           |
      | id_timeavailable_enabled | 1                    |
      | id_timeavailable_day     | 1                    |
      | id_timeavailable_month   | 1                    |
      | id_timeavailable_year    | 2024                 |
      | id_timedue_enabled       | 1                    |
      | id_timedue_month         | 12                   |
      | id_timedue_day           | 31                   |
      | id_timedue_year          | 2024                 |
    And I am on the "Course 1" Course page logged in as student1
    Then the activity date information in "Group self-selection" should exist
