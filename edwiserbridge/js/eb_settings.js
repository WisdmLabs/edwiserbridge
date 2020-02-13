define('local_edwiserbridge/eb_settings', ['jquery', 'core/ajax', 'core/url', 'core/modal_factory'], function ($, ajax, url, modalFactory) {
    return {
        init: function() {
            $(document).ready(function () {
                // alert('AAA');
            

                /*var trigger = $('#adminsettings');
                modalFactory.create({
                    title: 'test title',
                    body: '<p>test body content</p>',
                    footer: 'test footer content',
                }, trigger)
                  .done(function(modal) {
                    // Do what you want with your new modal.
                });*/

                if ($('#admin-newserviceuserselect').length) {

                    $('#admin-newserviceuserselect').after('<button id="eb_create_service"> Test </button>');

                }

                $('#eb_create_service').click(function(event){

                    event.preventDefault();
                    
                    var web_service_name = $('#id_s_local_edwiserbridge_newserviceinp').val();
                    var user_id = $('#id_s_local_edwiserbridge_newserviceuserselect').val();

                    var promises = ajax.call([

                        {methodname: 'eb_create_service', args: {web_service_name: web_service_name, user_id: user_id}}

                    ]);

                    promises[0].done(function(response) {

                        // swal(response.success);
                        /*jQuery("body").css("cursor", "auto");

                        if (response.success) {
                            swal("success !", response.message, "success");
                        } else {
                            swal("error !", response.message, "error");
                        }*/


                    }).fail(function(response) {
                        /*jQuery("body").css("cursor", "auto");
                        swal("error !", response.success, "error");*/

                    });


                });



                /*$(document).on('click', sendSessionEmail, function(e) {
                    var trigger = $(sendSessionEmail);
                    var servicename = 'block_f2fsessions_get_profile_fields';

                    var getProfilefields = ajax.call([{
                       methodname: servicename,
                           args: {
                           }
                    }]);

                    // Create modal
                    modalFactory.create({
                       title: 'Send notifications to users',
                       // Can include JS which is run when modal is attached to DOM.
                       // body: Templates.render('core/modal_test_3', {}),
                       // body: templates.render('block_f2fsessions/usersfilter', {
                       // }),
                    }, trigger).done(function(modal) {
                       var root = modal.getRoot();

                       // Destroy modal on hide
                       root.on(modalEvents.hidden, function(e) {
                           e.preventDefault();
                           modal.destroy();
                       });

                       modal.show();
                    });
                });
*/






            });
        }
    }
});



/*(function ($) {
    
    $(document).ready(function () {

        alert('AAA');

    });

})(jQuery);*/




