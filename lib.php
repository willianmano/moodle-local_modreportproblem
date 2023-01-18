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
