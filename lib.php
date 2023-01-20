<?php

/**
 * Plugin lib.
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

defined('MOODLE_INTERNAL') || die();

function local_modreportproblem_moove_module_footer() {
    global $PAGE;

    if (isguestuser() || !isloggedin() || !$PAGE->cm) {
        return false;
    }

    $renderer = $PAGE->get_renderer('local_modreportproblem');

    $contentrenderable = new \local_modreportproblem\output\html($PAGE->course->id, $PAGE->cm->id, $PAGE->cm->modname);

    return $renderer->render($contentrenderable);
}

/**
 * This function extends the navigation with the report items
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param stdClass $course The course to object for the report
 * @param context $context The context of the course
 */
function local_modreportproblem_extend_navigation_course($navigation, $course, $context) {
    if (has_capability('local/modreportproblem:view', $context)) {
        $url = new moodle_url('/local/modreportproblem/course.php', ['id' => $course->id]);
        $navigation->find('coursereports', navigation_node::TYPE_CONTAINER)->add(get_string('reportedproblems', 'local_modreportproblem'), $url, navigation_node::TYPE_SETTING, null, null, new pix_icon('i/report', ''));
    }
}
