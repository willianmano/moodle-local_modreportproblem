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
 * Class containing data for local report problema report page.
 *
 * @package   local_modreportproblem
 * @copyright 2023 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_modreportproblem\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

/**
 * Class containing data for local report problema report page.
 *
 * @package   local_modreportproblem
 * @copyright 2019 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class details implements renderable, templatable {

    protected $course;
    protected $coursemodule;
    protected $problem;

    public function __construct($course, $coursemodule, $problem) {
        $problem->timecrated = userdate($problem->timecreated);

        $this->course = $course;
        $this->coursemodule = $coursemodule;
        $this->problem = $problem;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     *
     * @return array Context variables for the template
     *
     * @throws \dml_exception
     */
    public function export_for_template(renderer_base $output) {
        global $DB;

        $user = $DB->get_record('user', ['id' => $this->problem->userid]);

        $this->problem->coursename = $this->course->fullname;
        $this->problem->userfullname = fullname($user);
        $this->problem->modulename = $this->coursemodule->name;
        $this->problem->date = date ( 'd/m/Y', $this->problem->timecreated);
        $this->problem->hour = date ( 'h:i', $this->problem->timecreated );

        return $this->problem;
    }
}