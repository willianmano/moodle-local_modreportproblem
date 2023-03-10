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

namespace local_modreportproblem\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * The mform class for creating a badge
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class report extends \moodleform {
    /**
     * Class constructor.
     *
     * @param array $formdata
     * @param array $customdata
     */
    public function __construct($formdata, $customdata = null) {
        parent::__construct(null, $customdata, 'post',  '', ['class' => 'modreportproblem-report-form'], true, $formdata);

        $this->set_display_vertical();
    }

    /**
     * The form definition.
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function definition() {
        $mform = $this->_form;

        $courseid = !(empty($this->_customdata['courseid'])) ? $this->_customdata['courseid'] : null;
        $cmid = !(empty($this->_customdata['cmid'])) ? $this->_customdata['cmid'] : null;
        $module = !(empty($this->_customdata['module'])) ? $this->_customdata['module'] : null;

        $mform->addElement('hidden', 'courseid');
        if (!empty($courseid)) {
            $mform->setDefault('courseid', $courseid);
        }

        $mform->addElement('hidden', 'cmid');
        if (!empty($cmid)) {
            $mform->setDefault('cmid', $cmid);
        }

        $mform->addElement('hidden', 'module');
        if (!empty($module)) {
            $mform->setDefault('module', $module);
        }

        $formdescription = '<div class="alert alert-info mb-0">'. get_string('reporttitle', 'local_modreportproblem') .'</div>';
        $mform->addElement('html', $formdescription);

        $mform->addElement('select', 'type', get_string('type', 'local_modreportproblem'), $this->get_problem_types());
        $mform->setType('type', PARAM_RAW);
        $mform->addRule('type', get_string('required'), 'required', null, 'client');

        $mform->addElement(
            'textarea',
            'details',
            get_string('details', 'local_modreportproblem'),
            'wrap="virtual" rows="4" cols="70"'
        );
        $mform->setType('details', PARAM_NOTAGS);
        $mform->addRule('details', get_string('required'), 'required', null, 'client');
    }

    /**
     * A bit of custom validation for this form
     *
     * @param array $data An assoc array of field=>value
     * @param array $files An array of files
     *
     * @return array
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        $type = $data['type'] ?? null;
        $details = $data['details'] ?? null;

        if ($this->is_submitted() && (empty($type) || strlen($type) < 3)) {
            $errors['type'] = get_string('required');
        }

        if ($this->is_submitted() && (empty($details) || strlen($details) < 3)) {
            $errors['details'] = get_string('required');
        }

        return $errors;
    }

    private function get_problem_types() {
        $config = get_config('local_modreportproblem');

        if (!isset($config->options)) {
            return [];
        }

        $options = explode("\r\n", $config->options);

        $data[null] = get_string('selectoption', 'local_modreportproblem');
        foreach ($options as $option) {
            $data[$option] = $option;
        }

        return $data;
    }
}
