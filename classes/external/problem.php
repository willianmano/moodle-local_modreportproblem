<?php

namespace local_modreportproblem\external;

use external_api;
use external_value;
use external_single_structure;
use external_function_parameters;

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
            'formdata' => new external_value(PARAM_RAW, 'The data from the form'),
        ]);
    }


    public static function create($formdata) {
        global $DB, $USER;

        $params = self::validate_parameters(self::create_parameters(), ['formdata' => $formdata]);

        $data = [];
        parse_str($params['formdata'], $data);

        $transaction = $DB->start_delegated_transaction();

        $sql = 'SELECT cm.id, cm.course, cm.instance, m.name
                FROM {course_modules} cm
                INNER JOIN {modules} m ON cm.module = m.id
                WHERE cm.id = :cmid';
        $coursemodule = $DB->get_record_sql($sql, ['cmid' => $data['cmid']]);

        $recorddata = new \stdClass();
        $recorddata->courseid = $coursemodule->course;
        $recorddata->userid = $USER->id;
        $recorddata->cmid = $coursemodule->id;
        $recorddata->module = $coursemodule->name;
        $recorddata->type = $data['problemtype'];
        $recorddata->details = $data['problemdetails'];
        $recorddata->timecreated = time();

        $DB->insert_record('modreportproblem', $recorddata);

        $transaction->allow_commit();

        return ['status' => true];
    }

    public static function create_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'The transaction status'),
        ]);
    }
}
