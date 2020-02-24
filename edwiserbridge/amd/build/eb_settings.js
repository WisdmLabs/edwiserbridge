define('local_edwiserbridge/eb_settings', ['jquery', 'core/ajax', 'core/url', 'core/modal_factory', 'core/str'], function ($, ajax, url, modalFactory, str) {
    /*return {
        init: function() {*/

            var translation = str.get_strings([
               {key: 'dailog_title', component: 'local_edwiserbridge'},
               {key: 'site_url', component: 'local_edwiserbridge'},
               {key: 'token', component: 'local_edwiserbridge'},
               {key: 'copy', component: 'local_edwiserbridge'},
               {key: 'copied', component: 'local_edwiserbridge'},
               {key: 'process', component: 'local_edwiserbridge'},
               {key: 'eb_empty_name_err', component: 'local_edwiserbridge'},
               {key: 'eb_empty_user_err', component: 'local_edwiserbridge'}
               // {key: 'manualsuccessuser', component: 'local_notifications'}
            ]);


            $(document).ready(function () {

                if ($('#admin-ebnewserviceuserselect').length) {
                    if (!$('#eb_create_service').length) {
                        $('#admin-ebnewserviceuserselect').after(
                            '<div class="row eb_create_service_wrap">'
                            +'  <div class="offset-sm-3 col-sm-3">'
                            +'    <button type="submit" id="eb_create_service" class="btn">'+ M.util.get_string('process', 'local_edwiserbridge') +'</button>'
                            +'  </div>'
                            +'</div>'
                        );
                    }
                }

                if ($('.eb_create_service_wrap').length) {
                    $('.eb_create_service_wrap').before(
                        '<div class="row eb_common_err_wrap">'
                        +'  <div class="offset-sm-3 col-sm-3">'
                        +'    <span id="eb_common_err" class="btn"></span>'
                        +'  </div>'
                        +'</div>'
                    );
                }


                function create_web_service(web_service_name, user_id, service_select_fld, common_errr_fld, is_mform)
                {
                    $("body").css("cursor", "progress");
                    var promises = ajax.call([

                        {methodname: 'eb_create_service', args: {web_service_name: web_service_name, user_id: user_id}}

                    ]);

                    promises[0].done(function(response) {
                        $("body").css("cursor", "default");
                        if (response.status) {

                            var eb_dialog_content = '<table class="eb_toke_detail_tbl">'
                                                    +'  <tr>'
                                                    +'     <th width="17%">'+ M.util.get_string('site_url', 'local_edwiserbridge') +'</th>'
                                                    +'     <td> : <span class="eb_copy_text">'+ response.site_url +'</span>'
                                                    +'        <span class="eb_copy_btn">'+ M.util.get_string('copy', 'local_edwiserbridge') +'</span></td>'
                                                    +'  </tr>'
                                                    +'  <tr>'
                                                    +'     <th width="17%">'+ M.util.get_string('token', 'local_edwiserbridge') +'</th>'
                                                    +'     <td> : <span class="eb_copy_text">'+ response.token +'</span>'
                                                    +'        <span class="eb_copy_btn">'+ M.util.get_string('copy', 'local_edwiserbridge') +'</span></td>'
                                                    +'  </tr>'
                                                    +'</table>';


                            modalFactory.create({
                                title: M.util.get_string('dailog_title', 'local_edwiserbridge'),
                                body: eb_dialog_content,
                                footer: '',
                                keyboard: false,
                                backdrop: 'static'
                            }).done(function(modal) {
                                // Do what you want with your new modal.
                                modal.show();
                            });

                            add_new_service_in_select(service_select_fld, web_service_name, response.service_id);

                            if (is_mform) {
                                $('#eb_mform_token').text(response.token);
                            }


                        } else {
                            $(common_errr_fld).text(response.msg);
                        }

                        return response;

                    }).fail(function(response) {
                        $("body").css("cursor", "default");
                        /*jQuery("body").css("cursor", "auto");
                        swal("error !", response.success, "error");*/

                        return 0;

                    }); //promise end
                }



                $('#eb_create_service').click(function(event){
                    event.preventDefault();

                    var web_service_name = $('#admin-ebnewserviceinp input').val();
                    var user_id = $('#admin-ebnewserviceuserselect select').val();
                    var error = 0;
                    $('.eb_settings_err').remove();
                    $('#eb_common_err').text('');

                    if (web_service_name == "") {
                        $('#admin-ebnewserviceinp input').after('<span class="eb_settings_err">'+ M.util.get_string('eb_empty_name_err', 'local_edwiserbridge') +'</span>');
                        error = 1;
                    }

                    if (user_id == "") {
                        $('#admin-ebnewserviceuserselect select').after('<span class="eb_settings_err">'+ M.util.get_string('eb_empty_user_err', 'local_edwiserbridge') +'</span>');
                        error = 1;
                    }

                    if (error) {
                        return;
                    }



                    create_web_service(web_service_name, user_id, '#admin-ebexistingserviceselect select', '#eb_common_err', 1);


                    /*$("body").css("cursor", "progress");
                    var promises = ajax.call([

                        {methodname: 'eb_create_service', args: {web_service_name: web_service_name, user_id: user_id}}

                    ]);

                    promises[0].done(function(response) {
                        $("body").css("cursor", "default");
                        if (response.status) {

                            var eb_dialog_content = '<table class="eb_toke_detail_tbl">'
                                                    +'  <tr>'
                                                    +'     <th width="17%">'+ M.util.get_string('site_url', 'local_edwiserbridge') +'</th>'
                                                    +'     <td> : <span class="eb_copy_text">'+ response.site_url +'</span>'
                                                    +'        <span class="eb_copy_btn">'+ M.util.get_string('copy', 'local_edwiserbridge') +'</span></td>'
                                                    +'  </tr>'
                                                    +'  <tr>'
                                                    +'     <th width="17%">'+ M.util.get_string('token', 'local_edwiserbridge') +'</th>'
                                                    +'     <td> : <span class="eb_copy_text">'+ response.token +'</span>'
                                                    +'        <span class="eb_copy_btn">'+ M.util.get_string('copy', 'local_edwiserbridge') +'</span></td>'
                                                    +'  </tr>'
                                                    +'</table>';

                            modalFactory.create({
                                title: M.util.get_string('dailog_title', 'local_edwiserbridge'),
                                body: eb_dialog_content,
                                footer: '',
                                keyboard: false,
                                backdrop: 'static'
                            }).done(function(modal) {
                                modal.show();
                            });

                            add_new_service_in_select('#admin-ebexistingserviceselect select', web_service_name);

                        } else {
                            $('#eb_common_err').text(response.msg);
                        }

                    }).fail(function(response) {
                        $("body").css("cursor", "default");
                        

                    }); //promise end*/

                }); // event end



                $('#id_eb_mform_create_service').click(function(event){
                    event.preventDefault();

                    var web_service_name = $('#id_eb_service_inp').val();
                    var user_id = $('#id_eb_auth_users_list').val();
                    var error = 0;
                    $('.eb_settings_err').remove();
                    $('#eb_common_err').text('');

                    if (web_service_name == "") {
                        $('#id_eb_service_inp').after('<span class="eb_settings_err">'+ M.util.get_string('eb_empty_name_err', 'local_edwiserbridge') +'</span>');
                        error = 1;    
                    }

                    if (user_id == "") {
                        $('#id_eb_auth_users_list').after('<span class="eb_settings_err">'+ M.util.get_string('eb_empty_user_err', 'local_edwiserbridge') +'</span>');
                        error = 1;
                    } 

                    if (error) {
                        return;
                    }

                    create_web_service(web_service_name, user_id, '#id_eb_sevice_list', '#eb_common_err', 1);

                }); // event end





                function add_new_service_in_select(element, name)
                {
                    $(element +"option:selected").removeAttr("selected");
                    $(element).append('<option selected> '+ name +' </option>');
                }







                /*$('body').on('hover', '.eb_copy_text', function(event){

console.log('HOVER');

                    var parent = $(this).parent();
                    parent = parent.children('.eb_copy_btn')
                    parent.css('display', 'block');
                });*/

                

                function handlefieldsdisplay(condition, condition_var, element)
                {
                    if (condition == condition_var) {
                        $(element).css('display', 'flex');
                    } else {
                        $(element).css('display', 'none');
                    }

                }


                $('#admin-ebexistingserviceselect select').change(function(event){
                    var eb_service_val = $(this).val();
                    
                    /*if ('create' == eb_service_val) {
                        $('#admin-ebnewserviceinp').css('display', 'flex');
                        $('#admin-ebnewserviceuserselect').css('display', 'flex');
                    } else {
                        $('#admin-ebnewserviceinp').css('display', 'none');
                        $('#admin-ebnewserviceuserselect').css('display', 'none');
                    }*/
                    handlefieldsdisplay('create', eb_service_val, '#admin-ebnewserviceinp');
                    handlefieldsdisplay('create', eb_service_val, '#admin-ebnewserviceuserselect');
                });


                $('#id_eb_sevice_list').change(function(event){
                    var eb_service_val = $(this).val();
                    handlefieldsdisplay('create', eb_service_val, '.eb_service_field');
                });


                $(document).on("mouseenter", ".eb_copy_text", function() {
                    // hover starts code here
                    var parent = $(this).siblings('.eb_copy_btn');
                    parent.css('visibility', 'visible');
                });


                $(document).on("mouseleave", ".eb_copy_text", function() {
                    // hover ends code here
                    var parent = $(this).siblings('.eb_copy_btn');
                    parent.css('visibility', 'hidden');
                });


                $(document).on('click', '.eb_copy_text', function() {
                    event.preventDefault();

                    /*var copy_btn_div = $(this).parent();
                    copy_btn_div = copy_btn_div.children('.eb_copy_text');
                    copy_btn_div = copy_btn_div.children('span');

console.log(copy_btn_div);
*/
                    var copyText = $(this).html();

                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(copyText).select();
                    document.execCommand("copy");
                    $temp.remove();


                    /*copyText.select();
                    copyText.setSelectionRange(0, 99999);

                    document.execCommand("copy");*/

                    /* Alert the copied text */
                    // alert("Copied the text: " + copyText.value);


                    toaster('Title', 200);
                });


                /*$('.eb_copy_text').on('click', function(event){
                    
                    setTimeout(function(){
                      alert("Boom!");
                    }, 2000);
                });*/



                /*$('#eb_create_service_test').click(function(event){
                    toaster('Title');
                    
                });*/


                function toaster(title, time = 2000) {
                    const id = 'local_edwiserbridge_copy';
                    const toast = $('<div id="' + id + '">'+ M.util.get_string('copied', 'local_edwiserbridge') +'<div>').get(0);
                    document.querySelector('body').appendChild(toast);
                    toast.classList.add('show');
                    setTimeout(function() {
                       toast.classList.add('fade');
                       setTimeout(function() {
                            toast.classList.remove('fade');
                            setTimeout(function() {
                            toast.remove();
                       }, time);
                     }, time);
                   });
                }



                function myFunction() {
                    /* Get the text field */
                    var copyText = document.getElementById("myInput");

                    /* Select the text field */
                    copyText.select();
                    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

                    /* Copy the text inside the text field */
                    document.execCommand("copy");

                    /* Alert the copied text */
                    alert("Copied the text: " + copyText.value);
                }



            });
/*        }
    }*/
});

