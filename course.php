<?php
// This file is part of AGRanking block for Moodle - http://moodle.org/
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
 * @copyright 2019 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');

$id = required_param('id', PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);

require_course_login($course, true);

$context = context_course::instance($course->id);

if (!has_capability('local/modreportproblem:view', $context)) {
    \core\notification::info(get_string('permission', 'local_modreportproblem'));
    redirect($CFG->wwwroot);
}

$title = get_string('reportedproblems', 'local_modreportproblem');
$url = new moodle_url('/local/modreportproblem/course.php', ['id' => $id]);

$PAGE->set_url($url);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');

// Add the page nav to breadcrumb.
$PAGE->navbar->add(get_string('reportedproblems', 'local_modreportproblem'));

$output = $PAGE->get_renderer('local_modreportproblem');

echo $output->header();
echo $output->container_start('modreportproblem-report');

$page_render = new \local_modreportproblem\output\course($context, $page);

echo $output->render($page_render);

$totalproblems = $DB->count_records('modreportproblem');

echo $output->paging_bar($totalproblems, $page, 15, $url);

echo $output->container_end();

echo $OUTPUT->footer();