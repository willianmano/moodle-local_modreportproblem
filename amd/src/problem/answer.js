/**
 * Report problem js logic.
 *
 * @copyright   2022 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
/* eslint-disable */
define([
        'jquery',
        'core/config',
        'core/str',
        'core/modal_factory',
        'core/modal_events',
        'core/fragment',
        'core/ajax',
        'local_modreportproblem/sweetalert',
        'core/yui'],
    function($, Config, Str, ModalFactory, ModalEvents, Fragment, Ajax, Swal, Y) {

        var Answer = function(selector, contextid, id) {
            this.contextid = contextid;

            this.id = id;

            this.init(selector);
        };

        Answer.prototype.modal = null;

        Answer.prototype.contextid = -1;

        Answer.prototype.id = -1;

        Answer.prototype.init = function(selector) {
            var triggers = $(selector);

            return Str.get_string('reporttechnicalproblem', 'local_modreportproblem').then(function(title) {
                // Create the modal.
                return ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: title,
                    body: this.getBody({id: this.id})
                }, triggers);
            }.bind(this)).then(function(modal) {
                // Keep a reference to the modal.
                this.modal = modal;

                // We want to reset the form every time it is opened.
                this.modal.getRoot().on(ModalEvents.hidden, function() {
                    this.modal.setBody(this.getBody({id: this.id}));
                }.bind(this));

                // We want to hide the submit buttons every time it is opened.
                this.modal.getRoot().on(ModalEvents.shown, function() {
                    this.modal.getRoot().append('<style>[data-fieldtype=submit] { display: none ! important; }</style>');
                }.bind(this));

                // We catch the modal save event, and use it to submit the form inside the modal.
                // Triggering a form submission will give JS validation scripts a chance to check for errors.
                this.modal.getRoot().on(ModalEvents.save, this.submitForm.bind(this));
                // We also catch the form submit event and use it to submit the form with ajax.
                this.modal.getRoot().on('submit', 'form', this.submitFormAjax.bind(this));

                return this.modal;
            }.bind(this));
        };

        Answer.prototype.getBody = function(formdata) {
            if (typeof formdata === "undefined") {
                formdata = {};
            }

            // Get the content of the modal.
            var params = {jsonformdata: JSON.stringify(formdata)};

            return Fragment.loadFragment('local_modreportproblem', 'answer_form', this.contextid, params);
        };

        Answer.prototype.handleFormSubmissionResponse = function(data) {
            // We could trigger an event instead.
            Y.use('moodle-core-formchangechecker', function() {
                M.core_formchangechecker.reset_form_dirty_state();
            });


            $('#answer-textarea').val(this.modal.getRoot().find('#id_answer').val());

            $('#answer-area').removeClass('d-none');

            $('#btn-answer').remove();

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
                title: data.status
            });

            this.modal.hide();
        };

        Answer.prototype.handleFormSubmissionFailure = function() {
            var data = this.modal.getRoot().find('form').serializeArray();

            // Oh noes! Epic fail :(
            // Ah wait - this is normal. We need to re-display the form with errors!
            this.modal.setBody(this.getBody(JSON.parse(data)));
        };

        Answer.prototype.submitFormAjax = function(e) {
            // We don't want to do a real form submission.
            e.preventDefault();

            var changeEvent = document.createEvent('HTMLEvents');
            changeEvent.initEvent('change', true, true);

            // Prompt all inputs to run their validation functions.
            // Normally this would happen when the form is submitted, but
            // since we aren't submitting the form normally we need to run client side
            // validation.
            this.modal.getRoot().find(':input').each(function(index, element) {
                element.dispatchEvent(changeEvent);
            });

            // Now the change events have run, see if there are any "invalid" form fields.
            var invalid = $.merge(
                this.modal.getRoot().find('[aria-invalid="true"]'),
                this.modal.getRoot().find('.error')
            );

            // If we found invalid fields, focus on the first one and do not submit via ajax.
            if (invalid.length) {
                invalid.first().focus();
                return;
            }

            // Convert all the form elements values to a serialised string.
            var formData = this.modal.getRoot().find('form').serialize();

            // Now we can continue...
            Ajax.call([{
                methodname: 'local_modreportproblem_answer',
                args: {contextid: this.contextid, jsonformdata: JSON.stringify(formData)},
                done: this.handleFormSubmissionResponse.bind(this),
                fail: this.handleFormSubmissionFailure.bind(this)
            }]);
        };

        Answer.prototype.submitForm = function(e) {
            e.preventDefault();

            this.modal.getRoot().find('form').submit();
        };

        return {
            init: function(selector, contextid, id) {
                return new Answer(selector, contextid, id);
            }
        };
    }
);
