/**
 * Delete skill js logic.
 *
 * @copyright   2022 Willian Mano {@link https://conecti.me}
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

define(['jquery', 'core/ajax', 'core/str', 'local_gamechanger/sweetalert'], function($, Ajax, Str, Swal) {
    var STRINGS = {
        CONFIRM_TITLE: 'Are you sure?',
        CONFIRM_MSG: 'Once deleted, the item cannot be recovered!',
        CONFIRM_YES: 'Yes, delete it!',
        CONFIRM_NO: 'Cancel',
        SUCCESS: 'Chapter successfully deleted.'
    };

    var componentStrings = [
        {
            key: 'deleteitem_confirm_title',
            component: 'local_gamechanger'
        },
        {
            key: 'deleteitem_confirm_msg',
            component: 'local_gamechanger'
        },
        {
            key: 'deleteitem_confirm_yes',
            component: 'local_gamechanger'
        },
        {
            key: 'deleteitem_confirm_no',
            component: 'local_gamechanger'
        },
        {
            key: 'deleteskill_success',
            component: 'local_gamechanger'
        },
    ];

    var DeleteSkill = function() {
        this.getStrings();

        this.registerEventListeners();
    };

    DeleteSkill.prototype.getStrings = function() {
        var stringsPromise = Str.get_strings(componentStrings);

        $.when(stringsPromise).done(function(strings) {
            STRINGS.CONFIRM_TITLE = strings[0];
            STRINGS.CONFIRM_MSG = strings[1];
            STRINGS.CONFIRM_YES = strings[2];
            STRINGS.CONFIRM_NO = strings[3];
            STRINGS.SUCCESS = strings[4];
        });
    };

    DeleteSkill.prototype.registerEventListeners = function() {
        $("body").on("click", ".delete-gamechanger-skill", function(event) {
            event.preventDefault();

            var eventTarget = $(event.currentTarget);

            Swal.fire({
                title: STRINGS.CONFIRM_TITLE,
                text: STRINGS.CONFIRM_MSG,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: STRINGS.CONFIRM_YES,
                cancelButtonText: STRINGS.CONFIRM_NO
            }).then(function(result) {
                if (result.value) {
                    this.deleteItemRow(eventTarget);
                }
            }.bind(this));
        }.bind(this));
    };

    DeleteSkill.prototype.deleteItemRow = function(eventTarget) {
        var request = Ajax.call([{
            methodname: 'local_gamechanger_deleteskill',
            args: {
                id: eventTarget.data('id')
            }
        }]);

        request[0].done(function() {
            this.removeLine(eventTarget);
        }.bind(this)).fail(function(error) {
            var message = error.message;

            if (!message) {
                message = error.error;
            }
            this.showToast('error', message);
        }.bind(this));
    };

    DeleteSkill.prototype.removeLine = function(eventTarget) {
        var tableLine = eventTarget.closest('tr');

        tableLine.fadeOut("normal", function() {
            $(this).remove();
        });

        this.showToast('success', STRINGS.SUCCESS);
    };

    DeleteSkill.prototype.showToast = function(type, message) {
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
            icon: type,
            title: message
        });
    };

    return {
        'init': function() {
            return new DeleteSkill();
        }
    };
});