<?php

namespace local_modreportproblem\external;

use external_api;
use external_value;
use external_single_structure;
use external_function_parameters;
use local_modreportproblem\form\report;

/**
 * Section external api class.
 *
 * @package     local_modreportproblem
 * @copyright   2023 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class problem extends external_api {
    /**
     * Create comment parameters
     *
     * @return external_function_parameters
     */
    public static function create_parameters() {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'The context id for the course module'),
            'jsonformdata' => new external_value(PARAM_RAW, 'The data from the form'),
        ]);
    }


    public static function create($contextid, $jsonformdata) {
        global $DB, $USER;

        $params = self::validate_parameters(self::create_parameters(),
            ['contextid' => $contextid, 'jsonformdata' => $jsonformdata]);

        $context = \context::instance_by_id($contextid, MUST_EXIST);

        self::validate_context($context);

        $serialiseddata = json_decode($params['jsonformdata']);

        $data = [];
        parse_str($serialiseddata, $data);

        $mform = new report($data);

        $validateddata = $mform->get_data();

        if (!$validateddata) {
            throw new \moodle_exception('invalidformdata');
        }

        $recorddata = new \stdClass();
        $recorddata->courseid = $validateddata->courseid;
        $recorddata->userid = $USER->id;
        $recorddata->cmid = $validateddata->cmid;
        $recorddata->module = $validateddata->module;
        $recorddata->type = $validateddata->type;
        $recorddata->details = $validateddata->details;
        $recorddata->timecreated = time();

        $DB->insert_record('modreportproblem', $recorddata);

        return ['status' => get_string('reportsuccess', 'local_modreportproblem')];
    }

    public static function create_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'The transaction status'),
        ]);
    }
}
