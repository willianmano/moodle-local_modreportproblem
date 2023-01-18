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
define([
        'jquery',
        'core/notification',
        'core/custom_interaction_events',
        'core/modal',
        'core/modal_registry',
        'local_modreportproblem/repository',
        'local_modreportproblem/sweetalert'
    ], function($, Notification, CustomEvents, Modal, ModalRegistry, Repository, Swal) {

    var registered = false;
    var SELECTORS = {
        SAVE_BUTTON: '[data-action="save"]',
        CANCEL_BUTTON: '[data-action="cancel"]'
    };

    /**
     * Constructor for the Modal.
     *
     * @param {object} root The root jQuery element for the modal
     */
    var ModalReportProblem = function(root) {
        Modal.call(this, root);
    };

    ModalReportProblem.TYPE = 'local_modreportproblem-modal';
    ModalReportProblem.prototype = Object.create(Modal.prototype);
    ModalReportProblem.prototype.constructor = ModalReportProblem;

    /**
     * Set up all of the event handling for the modal.
     *
     * @method registerEventListeners
     */
    ModalReportProblem.prototype.registerEventListeners = function() {
        // Apply parent event listeners.
        Modal.prototype.registerEventListeners.call(this);

        this.getModal().on(CustomEvents.events.activate, SELECTORS.SAVE_BUTTON, function() {
            Repository.create(this.getFormData())
                .then(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 8000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Problema reportado com sucesso.<br> Obrigado por nos ajudar a evoluir nossa plataforma.'
                    });
                })
                .catch(Notification.exception);

            this.hide();

            this.destroy();
        }.bind(this));

        this.getModal().on(CustomEvents.events.activate, SELECTORS.CANCEL_BUTTON, function() {
            this.hide();
            this.destroy();
        }.bind(this));
    };

    /**
     * Get the serialised form data.
     *
     * @method getFormData
     * @return {string} serialised form data
     */
    ModalReportProblem.prototype.getFormData = function() {
        return this.getForm().serialize();
    };

    /**
     * Get the form element from the modal.
     *
     * @method getForm
     * @return {object}
     */
    ModalReportProblem.prototype.getForm = function() {
        return this.getBody().find('form');
    };

    // Automatically register with the modal registry the first time this module is imported so that you can create modals
    // of this type using the modal factory.
    if (!registered) {
        ModalRegistry.register(ModalReportProblem.TYPE, ModalReportProblem, 'local_modreportproblem/modal');
        registered = true;
    }

    return ModalReportProblem;
});