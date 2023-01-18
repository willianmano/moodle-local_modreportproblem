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
 * Contain the logic for a drawer.
 *
 * @copyright  2023 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/log', 'core/modal_factory', 'local_modreportproblem/modal'],
    function($, Log, ModalFactory, ModalReportProblem) {

    var SELECTORS = {
        TOGGLE_REGION: '.modreportproblem',
        CMID_INPUT: '#cmid',
        COURSEID_INPUT: '#courseid',
        MODULE_INPUT: '#module'
    };

    var courseid = null;
    var cmid = null;
    var modulename = null;

    var Reportproblem = function(courseId, cmId, moduleName) {

        if (!$(SELECTORS.TOGGLE_REGION).length) {
            Log.debug('Page is missing the report problem trigger button');
        }

        courseid = courseId;
        cmid = cmId;
        modulename = moduleName;

        this.registerEventListeners();
    };

    /**
     * Open / close the blocks drawer.
     *
     * @method toggleReportproblem
     */
    Reportproblem.prototype.openReportproblem = function() {
        ModalFactory.create({
            type: ModalReportProblem.TYPE
        }).then(function(modal) {
            modal.show();

            $(SELECTORS.CMID_INPUT).val(cmid);
            $(SELECTORS.COURSEID_INPUT).val(courseid);
            $(SELECTORS.MODULE_INPUT).val(modulename);
        }.bind(this));
    };

    /**
     * Set up all of the event handling for the modal.
     *
     * @method registerEventListeners
     */
    Reportproblem.prototype.registerEventListeners = function() {
        $(SELECTORS.TOGGLE_REGION).click(function(e) {
            this.openReportproblem(e);
            e.preventDefault();
        }.bind(this));
    };

    return {
        'init': function(courseid, cmid, modulename) {
            return new Reportproblem(courseid, cmid, modulename);
        }
    };
});
