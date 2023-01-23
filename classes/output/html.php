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

namespace local_modreportproblem\output;

use renderable;
use templatable;
use renderer_base;

/**
 * Report problem HTML renderable class.
 *
 * @package     local_modreportproblem
 * @copyright   2022 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class html implements renderable, templatable {
    protected $contextid;
    protected $courseid;
    protected $cmid;
    protected $module;

    public function __construct($contextid, $courseid, $cmid, $module) {
        $this->contextid = $contextid;
        $this->courseid = $courseid;
        $this->cmid = $cmid;
        $this->module = $module;
    }

    public function export_for_template(renderer_base $output) {

        $config = get_config('local_modreportproblem');

        $options = [];
        if (isset($config->options)) {
            $options = explode("\r\n", $config->options);
        }

        return [
            'contextid' => $this->contextid,
            'courseid' => $this->courseid,
            'cmid' => $this->cmid,
            'module' => $this->module,
            'options' => json_encode($options)
        ];
    }
}
