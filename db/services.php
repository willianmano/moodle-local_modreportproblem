<?php

/**
 * Report problem services definition
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_modreportproblem_create' => array(
        'classname' => 'local_modreportproblem\external\problem',
        'classpath' => 'local/modreportproblem/classes/external/problem.php',
        'methodname' => 'create',
        'description' => 'Report a new problem.',
        'type' => 'write',
        'ajax' => true
    ),
    'local_modreportproblem_answer' => array(
        'classname' => 'local_modreportproblem\external\problem',
        'classpath' => 'local/modreportproblem/classes/external/problem.php',
        'methodname' => 'answer',
        'description' => 'Answer a problem.',
        'type' => 'write',
        'ajax' => true
    )
];
