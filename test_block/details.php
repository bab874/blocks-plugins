<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once(__DIR__.'/../../config.php');
require_once("{$CFG->libdir}/completionlib.php");

// Load data.
$id = required_param('course', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);

// Load course.
$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

$modules = $DB->get_record('course_modules', array('id' => $cmid));
$activityid = $DB->get_record('modules', array('id' => $modules->module));
$modulesdata = $DB->get_records($activityid->name, array('course' => $id));
// Load user.
if ($userid) {
    $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
} else {
    $user = $USER;
}

// Check permissions.
require_login();


$PAGE->set_context(context_course::instance($course->id));

// Print header.
$page = get_string('testprogressdetails', 'block_test_block');
$title = format_string($course->fullname) . ': ' . $page;

$PAGE->navbar->add($page);
$PAGE->set_pagelayout('report');
$PAGE->set_url('/blocks/test_block/details.php', array('course' => $course->id, 'user' => $user->id));
$PAGE->set_title(get_string('course') . ': ' . $course->fullname);
$PAGE->set_heading($title);
echo $OUTPUT->header();


// Display  status.
echo html_writer::start_tag('table', array('class' => 'generalbox boxaligncenter'));
echo html_writer::start_tag('tbody');
echo html_writer::start_tag('tr');
echo html_writer::start_tag('td');
echo get_string('srno', 'block_test_block');
echo html_writer::end_tag('td');
echo html_writer::start_tag('td');
echo get_string('name', 'block_test_block');
echo html_writer::end_tag('td');
echo html_writer::end_tag('tr');
$i = 1;
foreach ($modulesdata as $data) {
    echo html_writer::start_tag('tr');
    echo html_writer::start_tag('td');
    echo $i++;
    echo html_writer::end_tag('td');
    echo html_writer::start_tag('td');
    echo $data->name;
    echo html_writer::end_tag('td');
    echo html_writer::end_tag('tr');
}
    echo html_writer::end_tag('tbody');
    echo html_writer::end_tag('table');
$courseurl = new moodle_url("/course/view.php", array('id' => $course->id));
echo html_writer::start_tag('div', array('class' => 'buttons'));
echo $OUTPUT->single_button($courseurl, get_string('returntocourse', 'block_test_block'), 'get');
echo html_writer::end_tag('div');
echo $OUTPUT->footer();
