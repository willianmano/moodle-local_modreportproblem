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
 * A javascript module to handle report problem ajax actions.
 *
 * @module     local_modreportproblem/repository
 * @class      repository
 * @copyright  2023 Willian Mano - http://conecti.me
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax'], function($, Ajax) {

    /**
     * Report a new problem.
     *
     * @param {string} formdata The form data
     *
     * @return {promise}
     */
    var create = function(formdata) {
        var request = {
            methodname: 'local_modreportproblem_create',
            args: {
                formdata: formdata
            }
        };

        return Ajax.call([request])[0];
    };

    return {
        create: create
    };
});
