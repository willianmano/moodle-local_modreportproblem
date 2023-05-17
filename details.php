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

/**
 * Report problem report page
 *
 * @package   local_modreportproblem
 * @copyright 2023 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');

$id = required_param('id', PARAM_INT);

$problem = $DB->get_record('modreportproblem', ['id' => $id], '*', MUST_EXIST);

list($course, $coursemodule) = get_course_and_cm_from_cmid($problem->cmid, $problem->module);

require_course_login($course, true);

$context = \core\context\course::instance($course->id);

if (!has_capability('local/modreportproblem:answer', $context) && $problem->userid != $USER->id) {
    throw new moodle_exception('Illegal access');
}

$url = new moodle_url('/local/modreportproblem/details.php', ['id' => $id]);

$title = get_string('reportedproblems', 'local_modreportproblem');

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('admin');

$reporturl = new moodle_url('/local/modreportproblem/course.php', ['id' => $course->id]);

// Add the page nav to breadcrumb.
$PAGE->navbar->add(get_string('reportedproblems', 'local_modreportproblem'), $reporturl);
$PAGE->navbar->add(get_string('details', 'local_modreportproblem'));

$output = $PAGE->get_renderer('local_modreportproblem');

echo $output->header();
echo $output->container_start('modreportproblem-details');

$page = new \local_modreportproblem\output\details($context, $course, $coursemodule, $problem);

echo $output->render($page);

echo $output->container_end();

echo $OUTPUT->footer();
