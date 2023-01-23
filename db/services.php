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
