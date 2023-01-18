<?php

namespace local_modreportproblem\output;

defined('MOODLE_INTERNAL') || die();

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
    protected $courseid;
    protected $cmid;
    protected $modname;

    public function __construct($courseid, $cmid, $modname) {
        $this->courseid = $courseid;
        $this->cmid = $cmid;
        $this->modname = $modname;
    }

    public function export_for_template(renderer_base $output) {
        return [
            'courseid' => $this->courseid,
            'cmid' => $this->cmid,
            'modname' => $this->modname
        ];
    }
}
