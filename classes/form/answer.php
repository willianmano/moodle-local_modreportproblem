<?php

namespace local_modreportproblem\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * The mform class for creating a badge
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class answer extends \moodleform {
    /**
     * Class constructor.
     *
     * @param array $formdata
     * @param array $customdata
     */
    public function __construct($formdata, $customdata = null) {
        parent::__construct(null, $customdata, 'post',  '', ['class' => 'modreportproblem-answer-form'], true, $formdata);

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

        $mform->addElement('hidden', 'id');
        $mform->setDefault('id', $this->_customdata['id']);

        $mform->addElement('textarea', 'answer', get_string('answer', 'local_modreportproblem'), 'wrap="virtual" rows="4" cols="70"');
        $mform->setType('answer', PARAM_NOTAGS);
        $mform->addRule('answer', get_string('required'), 'required', null, 'client');
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

        $answer = $data['answer'] ?? null;

        if ($this->is_submitted() && (empty($answer) || strlen($answer) < 3)) {
            $errors['answer'] = get_string('required');
        }

        return $errors;
    }
}
