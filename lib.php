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

    $contentrenderable = new \local_modreportproblem\output\html($PAGE->context->id, $PAGE->course->id, $PAGE->cm->id, $PAGE->cm->modname);

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

/**
 * Returns report problem form fragment.
 *
 * @param $args
 * @return string
 */
function local_modreportproblem_output_fragment_report_form($args) {
    $args = (object) $args;

    $formdata = [];
    if (!empty($args->jsonformdata)) {
        $serialiseddata = json_decode($args->jsonformdata);
        $formdata = (array)$serialiseddata;
    }

    $mform = new \local_modreportproblem\form\report($formdata, [
        'courseid' => $serialiseddata->courseid,
        'cmid' => $serialiseddata->cmid,
        'module' => $serialiseddata->module,
    ]);

    if (!empty($args->jsonformdata)) {
        // If we were passed non-empty form data we want the mform to call validation functions and show errors.
        $mform->is_validated();
    }

    return $mform->render();
}

/**
 * Returns answer problem form fragment.
 *
 * @param $args
 * @return string
 */
function local_modreportproblem_output_fragment_answer_form($args) {
    $args = (object) $args;
    $o = '';

    $formdata = [];
    if (!empty($args->jsonformdata)) {
        $serialiseddata = json_decode($args->jsonformdata);
        $formdata = (array)$serialiseddata;
    }

    $mform = new \local_modreportproblem\form\answer($formdata, [
        'id' => $serialiseddata->id,
    ]);

    if (!empty($args->jsonformdata)) {
        // If we were passed non-empty form data we want the mform to call validation functions and show errors.
        $mform->is_validated();
    }

    ob_start();
    $mform->display();
    $o .= ob_get_contents();
    ob_end_clean();

    return $o;
}
