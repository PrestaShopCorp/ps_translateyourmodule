/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

function loadDropZone()
{
    Dropzone.options.importTranslationFile = {
        acceptedFiles: mimetype_xlsx,
        maxFiles: 1,
        maxFilesize: 5, // File size in Mb
        dictDefaultMessage: '',
        hiddenInputContainer: '#importTranslationFile',
        url: ajax_controller_url,
        init: function() {
            this
                .on("addedfile", function(file) {
                    $('.import-start').hide();
                    $('.import-failure-details').html('Something wrong happened');
                })
                .on("sending", function(file, xhr, formData){
                    formData.append("controller", ajax_controller_name);
                    formData.append("action", "UploadTranslation");
                    formData.append("ajax", true);               
                });
        },
        sending: function sending() {
            $('.modal .loader').show();
            $('.import-start').hide();
            $('.import-failure').hide();
            $('.import-success').hide();
        },
        success: function(file, response){
            let treatment = JSON.parse(response);

            $('.modal .loader').hide();
            $('.modal .import-failure-details').hide();
            
            switch (treatment.state) {
                case 0:
                    $('.import-failure').show();
                    $('.import-failure-details').html('<p>In folder\'s /translations/</p>');
                    $('.import-failure-details').append('<ul>');
                    $.each(treatment.errors, function(index, value) {
                        $('.import-failure-details').append('<li>Permission denied on '+index+'.php</li>');
                    });
                    $('.import-failure-details').append('</ul>');
                break;
                case 1:
                    $('.no_avatar_uploaded').hide();
                    $('.import-success').show();
                    $('.import-success-msg').html('Translations have been imported.');
                break;
            }

            this.removeAllFiles();
        },
        error: function(file, response){
            $('.modal .loader').hide();
            $('.import-failure').show();
        }
    };
}

function importManual(event) {
    event.preventDefault();
    $('#importTranslationFile').trigger("click");
}

function failureDetails(event) {
    event.preventDefault();
    $('.import-failure-details').slideDown();
}

function retryUpload(event) {
    event.preventDefault();
    $('.import-start').show();
    $('.import-failure').hide();
}

function resetModalView() {
    $('.import-start').show();
    $('.import-failure').hide();
    $('.import-success').hide();
}




document.addEventListener('DOMContentLoaded', () => {
    $('.import-start-select-manual').on('click', importManual);
    $('.import-failure-details-action').on('click', failureDetails)
    $('.import-failure-retry').on('click', retryUpload)
    $('.modal-header button').on('click', resetModalView);
    $("#upload-child-modal").on("hidden.bs.modal", resetModalView);

    loadDropZone();
});

