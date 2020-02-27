@mod @mod_groupselect
Feature: Setting to enable hiding of group members students.
  In order enrol to a group
  As a student
  I need to see other group members

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | c1 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
      | student3 | Student | 3 | student1@example.com |
      | student4 | Student | 4 | student2@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | c1     | editingteacher |
      | student1 | c1     | student        |
      | student2 | c1     | student        |
      | student3 | c1     | student        |
      | student4 | c1     | student        |
    And the following "groups" exist:
      | name | course | idnumber |
      | Group 1 | c1 | G1 |
    And the following "group members" exist:
      | user | group |
      | student1 | G1 |

  Scenario: Students see group members when choosing and hidegroupmembers is off.
    Given I log in as "admin"
    And I set the following system permissions of "Student" role:
      | moodle/course:viewparticipants | Allow |
    And I am on site homepage
    And I follow "Course 1"
    And I turn editing mode on
    And I add a "Group self-selection" to section "1" and I fill the form with:
      | Name        | Group self-selection       |
      | Hide group members for students | 0      |
    And I log out
    And I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Group self-selection"
    And I should not see "Member list not available"
    Then I should see "Student 1"

  Scenario: Students do not see group members when choosing and hidegroupmembers is off but capability is off.
    Given I log in as "admin"
    And I set the following system permissions of "Student" role:
      | moodle/course:viewparticipants | Prevent |
    And I am on site homepage
    And I follow "Course 1"
    And I turn editing mode on
    And I add a "Group self-selection" to section "1" and I fill the form with:
      | Name        | Group self-selection       |
      | Hide group members for students | 0      |
    And I log out
    And I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Group self-selection"
    And I should not see "Student 1"
    Then I should see "Member list not available"

  Scenario: Students do not see group members when choosing and hidegroupmembers is on.
    Given I log in as "admin"
    And I set the following system permissions of "Student" role:
      | moodle/course:viewparticipants | Allow |
    And I am on site homepage
    And I follow "Course 1"
    And I turn editing mode on
    And I add a "Group self-selection" to section "1" and I fill the form with:
      | Name        | Group self-selection       |
      | Hide group members for students | 1      |
    And I log out
    And I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Group self-selection"
    And I should see "Student 2"
    And I should not see "Student 1"
    Then I should see "Member list not available"
