require(['jquery', 'core/ajax', 'core/url', 'core/modal_factory', 'core/str'], function ($, ajax, url, modalFactory, str) {
/*    return {
        init: function() {*/

            var translation = str.get_strings([
               {key: 'dailog_title', component: 'local_edwiserbridge'},
               {key: 'site_url', component: 'local_edwiserbridge'},
               {key: 'token', component: 'local_edwiserbridge'},
               {key: 'copy', component: 'local_edwiserbridge'},
               {key: 'copied', component: 'local_edwiserbridge'},
               {key: 'link', component: 'local_edwiserbridge'},
               {key: 'create', component: 'local_edwiserbridge'},
               {key: 'eb_empty_name_err', component: 'local_edwiserbridge'},
               {key: 'eb_empty_user_err', component: 'local_edwiserbridge'},
               {key: 'eb_service_select_err', component: 'local_edwiserbridge'}
               // {key: 'manualsuccessuser', component: 'local_notifications'}
            ]);

            $(document).ready(function () {

                if ($('#admin-ebnewserviceuserselect').length) {
                    if (!$('#eb_create_service').length) {
                        $('#admin-ebnewserviceuserselect').after(
                            '<div class="row eb_create_service_wrap">'
                            +'  <div class="offset-sm-3 col-sm-3">'
                            +'    <button type="submit" id="eb_create_service" class="btn">'+ M.util.get_string('link', 'local_edwiserbridge') +'</button>'
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
                        +'    <span id="eb_common_success" class="btn"></span>'
                        +'  </div>'
                        +'</div>'
                    );
                }


                function link_web_service(service_id, common_errr_fld, common_success_fld)
                {

                    $("body").css("cursor", "progress");
                    var promises = ajax.call([
                        {methodname: 'eb_link_service', args: {service_id: service_id}}
                    ]);

                    promises[0].done(function(response) {
                        $("body").css("cursor", "default");
                        if (response.status) {
                            $(common_success_fld).text(response.msg);
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
                    var error = 0;

                    var web_service_name = $('#admin-ebnewserviceinp input').val();
                    var user_id = $('#admin-ebnewserviceuserselect select').val();

                    var service_id = $('#admin-ebexistingserviceselect select').val();

                    $('.eb_settings_err').remove();
                    $('#eb_common_err').text('');
                    $('#eb_common_success').text('');

                    if (service_id == 'create') {
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
                    } else {

                        if (service_id != '') {
                            link_web_service(service_id, '#eb_common_err', '#eb_common_success');
                        } else {
                            $('#eb_common_err').text(M.util.get_string('eb_service_select_err', 'local_edwiserbridge'))
                        }

                    }

                }); // event end



                $('#id_eb_mform_create_service').click(function(event){
                    event.preventDefault();

                    var error = 0;

                    var web_service_name = $('#id_eb_service_inp').val();
                    var user_id = $('#id_eb_auth_users_list').val();

                    var service_id = $('#id_eb_sevice_list').val();

                    $('.eb_settings_err').remove();
                    $('#eb_common_err').text('');
                    $('#eb_common_success').text('');

                    if (service_id == 'create') {
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
                    } else {
                        if (service_id != '') {
                            link_web_service(service_id, '#eb_common_err', '#eb_common_success');
                        } else {
                            $('#eb_common_err').text(M.util.get_string('eb_service_select_err', 'local_edwiserbridge'))
                        }
                    }

                }); // event end





                function add_new_service_in_select(element, name, id)
                {
                    $(element +"option:selected").removeAttr("selected");
                    $(element).append('<option value="'+id+'" selected> '+ name +' </option>');
                }


                function handlefieldsdisplay(condition, condition_var, element, btn = '')
                {
                    if (condition == condition_var) {
                        $(btn).text(M.util.get_string('create', 'local_edwiserbridge'));
                        $(element).css('display', 'flex');
                    } else {
                        $(btn).text(M.util.get_string('link', 'local_edwiserbridge'));
                        $(element).css('display', 'none');
                    }

                }


                $('#admin-ebexistingserviceselect select').change(function(event){
                    var eb_service_val = $(this).val();
                    handlefieldsdisplay('create', eb_service_val, '#admin-ebnewserviceinp', '#eb_create_service');
                    handlefieldsdisplay('create', eb_service_val, '#admin-ebnewserviceuserselect', '#eb_create_service');
                });


                $('#id_eb_sevice_list').change(function(event){
                    var eb_service_val = $(this).val();
                    handlefieldsdisplay('create', eb_service_val, '.eb_service_field', '#id_eb_mform_create_service');
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

                    var copyText = $(this).html();
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(copyText).select();
                    document.execCommand("copy");
                    $temp.remove();

                    toaster('Title', 200);
                });



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


