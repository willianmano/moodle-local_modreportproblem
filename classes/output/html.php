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
