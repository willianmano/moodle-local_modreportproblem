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
class course implements renderable, templatable {

    protected $context;
    protected $page;

    public function __construct($context, $page = null) {
        $this->context = $context;
        $this->page = $page;
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

        $sql = "SELECT rp.*, c.fullname
                FROM {modreportproblem} rp
                INNER JOIN {course} c ON c.id = rp.courseid
                ORDER BY rp.answer is not null, rp.id  asc";

        $records = $DB->get_records_sql($sql, null, ($this->page) * 15 , 15);

        foreach ($records as $key => $record) {
            $records[$key]->date = date ( 'd/m/Y', $record->timecreated);
            $records[$key]->hour = date ( 'H:i', $record->timecreated );
        }

        $records = array_values($records);

        return ['reportedproblems' => $records, 'permission' => has_capability('local/modreportproblem:view', $this->context)];
    }
}
