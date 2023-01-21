<?php

/**
 * Problem answered message class.
 *
 * @package   local_modreportproblem
 * @copyright 2023 Willian Mano - http://conecti.me
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_modreportproblem\notification;

defined('MOODLE_INTERNAL') || die();

use core\message\message;
use moodle_url;

/**
 * Comment mention notification class
 *
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class problemanswered {
    /** @var \context Course context. */
    public $context;
    /** @var \stdClass The problem. */
    public $problem;

    /**
     * Constructor.
     *
     * @param \context $context
     * @param string $problemid
     */
    public function __construct($context, $problem) {
        $this->context = $context;
        $this->problem = $problem;
    }

    /**
     * Get the notification message data
     *
     * @return message
     *
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function send() {
        global $USER;

        $yourpeoblemwasanswered = get_string('yourpeoblemwasanswered', 'local_modreportproblem');

        $clicktoaccess = get_string('yourpeoblemwasanswered_button', 'local_modreportproblem');

        $urlparams = ['id' => $this->problem->id];

        $url = new moodle_url("/local/modreportproblem/details.php", $urlparams);

        $message = new message();
        $message->component = 'local_modreportproblem';
        $message->name = 'problemanswered';
        $message->userfrom = $USER;
        $message->userto = $this->problem->userid;
        $message->subject = $yourpeoblemwasanswered;
        $message->fullmessage = $yourpeoblemwasanswered;
        $message->fullmessageformat = FORMAT_PLAIN;
        $message->fullmessagehtml = '<p>'.$yourpeoblemwasanswered.'</p>';
        $message->fullmessagehtml .= '<p><a class="btn btn-primary" href="'.$url.'">'.$clicktoaccess.'</a></p>';
        $message->smallmessage = $yourpeoblemwasanswered;
        $message->contexturl = $url;
        $message->contexturlname = get_string('yourpeoblemwasanswered_contextname', 'local_modreportproblem');
        $message->courseid = $this->problem->courseid;
        $message->notification = 1;

        return message_send($message);
    }
}
