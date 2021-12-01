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


defined('MOODLE_INTERNAL') || die();

require_once("{$CFG->libdir}/completionlib.php");


class block_test_block extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_test_block');
    }

    public function applicable_formats() {
        return array('course' => true);
    }

    public function get_content() {
        global $USER , $DB;

        $rows = array();
        $srows = array();
        $prows = array();
        if ($this->content !== null) {
            return $this->content;
        }
        $course = $this->page->course;
        $context = context_course::instance($course->id);
        // Create empty content.
        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';
        $info = new completion_info($course);
        $modules = $DB->get_records('course_modules', array('course' => $course->id));
        $completions = $info->get_completions($USER->id);
        foreach ($modules as $moduleid) {
            $textht = "";
            $activity = $DB->get_record('modules', array('id' => $moduleid->module));
            $modules = $DB->get_record($activity->name, array('id' => $moduleid->instance));
            $modulescompl = $DB->get_record('course_modules_completion',
            array('coursemoduleid' => $moduleid->id, 'userid' => $USER->id));
            if (!empty($modulescompl)) {
                $completion = "-Completed";
            } else {
                $completion = "1";
            }
            $textht .= html_writer::start_tag('p');
            $textht .= html_writer::start_span().''. $moduleid->id.''. html_writer::end_span();
            $textht .= html_writer::start_span().'-'. $modules->name.''. html_writer::end_span();
            $textht .= html_writer::start_span().'-'. date('d-M-Y', $modules->timecreated).''. html_writer::end_span();
            $textht .= html_writer::start_span().''. $completion.''. html_writer::end_span();
            $textht .= html_writer::end_tag('p');
            $url = new moodle_url('/blocks/test_block/details.php', array('course' => $course->id, 'cmid' => $moduleid->id));
            $this->content->text .= html_writer::link($url, $textht);
        }
        return $this->content;
    }
}
