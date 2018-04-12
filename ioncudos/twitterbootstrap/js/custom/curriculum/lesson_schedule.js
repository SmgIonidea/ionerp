
//lesson_schedule.js

var base_url = $('#get_base_url').val();
var view_lesson = 0;
var view_question = 0;

// SLO Lesson Schedule Script functions starts from here //
$(document).ready(function () {
    tiny_init();

    var topic_id = $('#lesson_topic_id').val();
    var topic_hours = $('#topic_hrs').val();
    var curriculumId = $('#lesson_curriculum_id').val();
    var termId = $('#lesson_term_id').val();
    var courseId = $('#lesson_course_id').val();

    defalt_view_lessonSchedule();

    //function to show viewed lession schedule,questions.
    function defalt_view_lessonSchedule() {
        $('#display_portion_wrapper').show();
        $('#view_all').html('<i class="icon-minus icon-white"></i> Hide Lesson schedule</button>');

        var post_data = {'topic_id': topic_id}

        $.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_list/display_schedule',
            data: post_data,
            dataType: 'json',
            success: populate_table
        });

        view_lesson = 1;

        $('#example_wrapper').show();
        $('#view_all_ques').html('<i class="icon-minus icon-white"></i> Hide All questions</button>');
        var insert_path = base_url + 'curriculum/tlo_list/display_questions';

        var post_data = {
            'topic_id': topic_id,
            'curriculum_id': curriculumId,
            'term_id': termId,
            'course_id': courseId, 'question_id': 3
        }

        $.ajax({
            type: "POST",
            url: insert_path,
            data: post_data,
            dataType: 'json',
            success: populateTable
        });

        view_question = 1;
    }

    $('.view_schedule').live('click', function (event) {
        $('#loading').show();
        event.preventDefault();
        $(this).text('Hide Lesson schedule');
        $("i", this).toggleClass("icon-plus icon-minus");
        if (view_lesson % 2 == 0) {
            $('#display_portion_wrapper').show();
            $('#view_all').html('<i class="icon-minus icon-white"></i> Hide Lesson schedule</button>');
        } else {
            $('#display_portion_wrapper').hide();
            $('#view_all').html('<i class="icon-plus icon-white"></i> View Lesson schedule</button>');
        }
        var post_data = {'topic_id': topic_id}
        $.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_list/display_schedule',
            data: post_data,
            dataType: 'json',
            success: populate_table
        });
        view_lesson = view_lesson + 1;
        $('#loading').hide();
    });

    $('.all_questions').live('click', function (event) {
        $('#loading').show();
        event.preventDefault();
        if (view_question % 2 == 0) {
            $('#example_wrapper').show();
            $('#view_all_ques').html('<i class="icon-minus icon-white"></i> Hide All questions</button>');
        } else {
            $('#example_wrapper').hide();
            $('#view_all_ques').html('<i class="icon-plus icon-white"></i>View All questions</button>');
        }
        var insert_path = base_url + 'curriculum/tlo_list/display_questions';
        var post_data = {
            'topic_id': topic_id,
            'curriculum_id': curriculumId,
            'term_id': termId,
            'course_id': courseId, 'question_id': 3
        }
        $.ajax({type: "POST",
            url: insert_path,
            data: post_data,
            dataType: 'json',
            success: populateTable
        });
        view_question = view_question + 1;
        $('#loading').hide();
    });

    $('#review_assignment_form').validate();

    $('.add_details').on('click', function (event) {
        event.preventDefault();
        var flag = $('#review_assignment_form').valid();
        var form_incmplt = false;

        $('.rev_question').each(function () {
            var id = $(this).attr('id');
            text = tinyMCE.get(id).getContent();
            var id_val = id.split("_");

            if (text == "") {
                form_incmplt = true;
                $('.question_num').html('<div class="font_color" id="error_msg">This field is required.</div>');
            } else {
                $('.question_num').html('<div class="font_color" id="error_msg"></div>');
            }
        });

        if (flag == true && form_incmplt == false) {
            $("#loading").show();
            var res = text.replace("<p>", "");
            text = res.replace("</p>", "");
            res = text.replace('alt=""', 'alt="image"');
            text = res;

            var blo_id = $('#bloom_id').val();
            var que_id = $('#question_type').val();
            var tlo_id = $('#tlo_ids').val();
            var pi_id = $('#pi_id').val();
            var insert_path = base_url + 'curriculum/tlo_list/insert_question';

            $('#example_wrapper').show();
            $("#review_assignment_form")[0].reset();

            var post_data = {
                'tl_id': tlo_id,
                'bl_id': blo_id,
                'question': text,
                'question_id': que_id,
                'topic_id': topic_id,
                'curriculum_id': curriculumId,
                'term_id': termId,
                'pi_id': pi_id,
                'course_id': courseId,
            }

            $.ajax({type: "POST",
                url: insert_path,
                data: post_data,
                success: function (count) {
                    if (count > 0) {
                        $("#loading").hide();
                        $('#alert_question').modal('show');
                    } else {
                        $("#loading").hide();
                    }

                    $('#example_wrapper').show();
                    $('#view_all_ques').html('<i class="icon-minus icon-white"></i> Hide All questions</button>');
                    view_question = 1;

                    if (que_id == 2) {
                        $('#type_question_assignment').prop('checked', true);
                    }
                    var post_data = {
                        'topic_id': topic_id,
                        'curriculum_id': curriculumId,
                        'term_id': termId,
                        'course_id': courseId, 'question_id': 3
                    }
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/tlo_list/display_questions',
                        data: post_data,
                        dataType: 'json',
                        success: populateTable
                    });
                }
            });
        }
    });

    //
    $.validator.addMethod("num", function (value, element) {
        var regex = /^([0-9 ]|[0-9 ][- ][0-9 ])+$/; //this is for numeric... you can do any regular expression you like...
        return this.optional(element) || regex.test(value);
    }, "Field must contain only numbers' or dashes. ");

    //
    $('.lesson_schedule').on('click', function (event) {
        event.preventDefault();

        $("#lesson_schedule_add_form").validate({
            rules: {
                lesson_schedule_id: {
                    required: true,
                    num: true
                },
            },
            message: {
                lesson_schedule_id: {
                    required: "This field is required.",
                },
            }
        });

        var flag = $('#lesson_schedule_add_form').valid();

        if (flag == true) {
            $("#loading").show();
            var portion_per_hour = $('#portion_per_hour').val();
            var portion_sl_no = $('#lesson_schedule_id').val();
            var conduction_date = $('#ls_date').val();
            var actual_delivery_date = $('#ls_actual_date').val();
            var lesson_insert_path = base_url + 'curriculum/tlo_list/insert_schedule';

            $('#display_portion_wrapper').show();

            var post_data = {
                'topic_hrs': topic_hours,
                'portion_slNo': portion_sl_no,
                'content': portion_per_hour,
                'conduction_date': conduction_date,
                'actual_delivery_date': actual_delivery_date,
                'topic_id': topic_id,
                'curriculum_id': curriculumId,
                'term_id': termId,
                'course_id': courseId
            }

            $.ajax({type: "POST",
                url: lesson_insert_path,
                data: post_data,
                success: function (count) {
                    if (count > 0) {
                        $("#loading").hide();
                        $('#alert_schedule').modal('show');
                    } else {
                        $("#loading").hide();
                    }

                    $('#display_portion_wrapper').show();
                    $('#view_all').html('<i class="icon-minus icon-white"></i> Hide Lesson schedule</button>');

                    view_lesson = 1;

                    data_post = {
                        'topic_id': topic_id
                    }

                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/tlo_list/display_schedule',
                        data: data_post,
                        dataType: 'json',
                        success: populate_table
                    });
                }
            });
        }
    });

    //
    $(".edit_details_schedule").live('click', function () {
        var portion_content = $(this).data("portion");
        var portion_id = $(this).data("queid");
        var portion_slNO = $(this).data('portion_ref');
        var modal_ls_date = $(this).data('conduction_date');
        var modal_ls_actual_date = $(this).data('actual_delivery_date');

        $("#my_modal_edit_scheule #portion").val(portion_content);
        $("#my_modal_edit_scheule #portion_id").val(portion_id);
        $("#my_modal_edit_scheule #lesson_schedule_id1").val(portion_slNO);
        $("#my_modal_edit_scheule #modal_ls_date").val(modal_ls_date);
        $("#my_modal_edit_scheule #modal_ls_actual_date").val(modal_ls_actual_date);

        $('#my_modal_edit_scheule').modal('show');
    });

    //
    $('#lesson_schedule_edit_form').validate();

    //
    $('.lesson_schedule_edit').live('click', function (event) {
        event.preventDefault();
        var form_incmplt = false;
        var flag = $('#lesson_schedule_edit_form').valid();

        if (flag == false) {
            $('#schedule_update_alert').modal('show');
        } else {
            $("#loading").show();
            var portion_per_hour = $('#portion').val();
            var modal_ls_date = $('#modal_ls_date').val();
            var modal_ls_actual_date = $('#modal_ls_actual_date').val();
            var lesson_insert_path = base_url + 'curriculum/tlo_list/update_lesson_schedule';
            var portion_id = $('#portion_id').val();
            var portion_slNo = $('#lesson_schedule_id1').val();

            var post_data = {
                'portion': portion_per_hour,
                'portion_id': portion_id,
                'portion_slNo': portion_slNo,
                'modal_ls_date': modal_ls_date,
                'modal_ls_actual_date': modal_ls_actual_date,
                'topic_id': topic_id,
                'curriculum_id': curriculumId,
                'term_id': termId,
                'course_id': courseId
            }

            $.ajax({type: "POST",
                url: lesson_insert_path,
                data: post_data,
                dataType: 'json',
                success: function (count) {
                    if (count > 0) {
                        $("#loading").hide();
                        $('#alert_schedule').modal('show');
                    } else {
                        $("#my_modal_edit_scheule").modal('hide');
                        $("#loading").hide();
                    }

                    data_post = {
                        'topic_id': topic_id
                    }

                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/tlo_list/display_schedule',
                        data: data_post,
                        dataType: 'json',
                        success: populate_table
                    });
                }
            });
        }
    });

    //
    $(".delete_details_schedule").live('click', function (event) {
        event.preventDefault();
        var portion_id = $(this).data("queid");

        $("#myModaldelete_1 #portion_delete_id").val(portion_id);
        $('#myModaldelete_1').modal('show');
    });

    //
    $(".delete_portion").live('click', function (event) {
        event.preventDefault();
        $('#loading').show();

        var portion_id = $('#portion_delete_id').val();
        var insert_path = base_url + 'curriculum/tlo_list/delete_lesson_schedule';
        var post_data = {'topic_id': topic_id, 'portion_id': portion_id}

        $.ajax({type: "POST",
            url: insert_path,
            data: post_data,
            success: function () {
                var data_post = {'topic_id': topic_id}
                $.ajax({type: "POST",
                    url: base_url + 'curriculum/tlo_list/display_schedule',
                    data: data_post,
                    dataType: 'json',
                    success: populate_table
                });
            }
        });
        $('#loading').hide();
    });

    //
    $(".edit_question_details").live('click', function (event) {
        event.preventDefault();
        $("#bloom_id_edit").empty();
        $("#pi_id_edit").empty();
        var question = $(this).data("question");
        tinymce.remove();

        var question_id = $(this).data("ques_id");
        var blo_val = $(this).data("blo");
        var tlo_code = $(this).data("tlo");
        var pi_code = $(this).data("pi");
        var bl_level = $(this).data("bl_level");
        var que_num = $(this).data("quenum");
        var que_type =  $(this).attr('data-que_type');
        $('#question_type option[value="'+que_type+'"]').attr("selected", true);
        $('#tlo_ids option[value="' + tlo_code + '"]').attr("selected", true);
        $('#review_question_1').val(question);
        $('#review_question_1').focus();
        var post_data = {
            'tlo_id': tlo_code,
            'bl_id': blo_val,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_list/bl',
            data: post_data,
            success: function (msg) {
                $('#bloom_id').html(msg);
                $('#loading').hide();
            }
        });
        $('#bloom_id option[value="'+blo_val+'"]').attr("selected", true);
        $('.update_question').show();
        $('.cancel_question').show();
        $('.add_details').hide();
        $('#question_id').val(que_num);
        tiny_init();
        var scrollPos =  $("#review_question_1").offset().down;
        $(window).scrollDown(scrollPos);
    });
    
   

    //
    $(".update_question").live('click', function (event) {
        event.preventDefault();
            $("#loading").show();
            //var res = text.replace("<p>", "");
            var text = $.trim(tinymce.get('review_question_1').getContent());//$('#review_question_1').val();
            var que_id = $('input[name="type_question_modal"]:checked', '#question_edit_form').val();
            var tlo_id = $('#tlo_ids').val();
            var pi_id = $('#pi_id').val();
            var questionNo = $("#question_id").val();
            var blo_id = $('#bloom_id').val();
            var question_type = $('#question_type').val();
            var insert_path = base_url + 'curriculum/tlo_list/update_question';
            var post_data = {
                'tl_id': tlo_id,
                'bl_id': blo_id,
                'question': text,
                'question_id': question_type,
                'pi_id': pi_id,
                'id': questionNo,
                'topic_id': topic_id,
                'curriculum_id': curriculumId,
                'term_id': termId,
                'course_id': courseId,
            }

            $.ajax({type: "POST",
                url: insert_path,
                data: post_data,
                success: function (count) {
                    if (count > 0) {
                        $("#loading").hide();
                        $('#alert_question').modal('show');
                    } else {
                       // $("#myModal6").modal('hide');
                       $('#question_type option:first').prop('selected',true);
                       $('#tlo_ids option:first').prop('selected',true);
                       $('#bloom_id option:first').prop('selected',true);
                       tinymce.get('review_question_1').setContent('')
                       $('.add_details').show();
                        $('.update_question').hide();
                        $('.cancel_question').hide();
                        $("#loading").hide();
                    }

                    var data_post = {'topic_id': topic_id,
                        'curriculum_id': curriculumId,
                        'term_id': termId,
                        'course_id': courseId, 'question_id': 3};

                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/tlo_list/display_questions',
                        data: data_post,
                        dataType: 'json',
                        success: populateTable
                    });
                }
            });
        
    });
    
    /*
     * Function to Cancel the update of question.
     * Date: 6/7/2017.
     */
    
    $('.cancel_question').on('click',function(){
        $('#question_type option:first').prop('selected',true);
        $('#tlo_ids option:first').prop('selected',true);
        $('#bloom_id option:first').prop('selected',true);
        tinymce.get('review_question_1').setContent('')
        $('.add_details').show();
        $('.update_question').hide();
        $('.cancel_question').hide();
    })

    //
    $(".delete_question_details").live('click', function (event) {
        event.preventDefault();
        var question_id = $(this).data("queid");
        $("#myModaldelete_2 #question_delete_id").val(question_id);
        $("#myModaldelete_2").modal('show');
    });

    //
    $(".delete_question").live('click', function (event) {
        event.preventDefault();
        var que_num = $('#question_delete_id').val();
        $('#loading').show();
        var insert_path = base_url + 'curriculum/tlo_list/delete_question';

        var post_data = {
            'question_id': que_num
        }

        $.ajax({type: "POST",
            url: insert_path,
            data: post_data,
            success: function () {
                var data_post = {
                    'topic_id': topic_id,
                    'curriculum_id': curriculumId,
                    'term_id': termId,
                    'course_id': courseId, 'question_id': 3
                }

                $.ajax({type: "POST",
                    url: base_url + 'curriculum/tlo_list/display_questions',
                    data: data_post,
                    dataType: 'json',
                    success: populateTable
                });
            }
        });
        $('#loading').hide();
    });

    //
    function populate_table(msg) {
        $('#display_portion').dataTable().fnDestroy();
        $('#display_portion').dataTable(
                {"aoColumns": [
                        {"sTitle": "Lecture No.", "mData": "sl_no"},
                        {"sTitle": "Portion to be covered per hour", "mData": "portion"},
                        {"sTitle": "Planned Delivery Date", "mData": "conduction_date"},
                        {"sTitle": "Actual Delivery Date", "mData": "actual_delivery_date"},
                        {"sTitle": "Edit", "mData": "edit"},
                        {"sTitle": "Delete", "mData": "delete"}
                    ],
                    "sPaginationType": "bootstrap",
                    "aaData": msg,
                    "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                        $('td:eq(0)', nRow).css("text-align", "right");
                        $('td:eq(2)', nRow).css("text-align", "right");
                        $('td:eq(3)', nRow).css("text-align", "right");
                        return nRow;
                    },
                });
        $("#portion_per_hour").val("");
        $('#lesson_schedule_id').val("");
        $('#ls_date').val("");
        $('#ls_actual_date').val("");
    }

    //
    function populateTable(msg) {
        console.log('msg');
        $('#example').dataTable().fnDestroy();
        $('#example').dataTable(
                {"aoColumns": [
                        {"sTitle": "Sl No.", "mData": "sl_no", "sClass": "alignRight"},
                        {"sTitle": "Type", "mData": "type"},
                        {"sTitle": "Question", "mData": "question"},
                        {"sTitle": tlo_entity_lang, "mData": "TLO"},
                        {"sTitle": "Bloom's level", "mData": "Blooms_level"},
                        {"sTitle": "PI Codes", "mData": "PI codes"},
                        {"sTitle": "Edit", "mData": "edit"},
                        {"sTitle": "Delete", "mData": "delete"}
                    ],
                    "sPaginationType": "bootstrap",
                    "aaData": msg
                });
    }
});

//
$('.select_bl_edit').on('change', function () {
    $('#loading').show();
    var tlo_id = $(this).val();
    var bl_drop_down_fill_path = base_url + 'curriculum/tlo_list/select_bl';

    var post_data = {
        'tlo_id': tlo_id
    }

    $.ajax({type: "POST",
        url: bl_drop_down_fill_path,
        data: post_data,
        success: function (msg) {
            $('#bloom_id_edit').html(msg);
            $('#loading').hide();
        }
    });
});

//
function select_pi_code_edit() {
    var tlo_id = $('#tlo_ids').val();
    var course_id = $('#lesson_course_id').val();
    $('#loading').show();
    var bloom_id = $('#bloom_id_edit').val();
    var pi_drop_down_fill_path = base_url + 'curriculum/tlo_list/select_pi_code';

    var post_data = {
        'tlo_id': tlo_id,
        'crs_id': course_id,
        'bloom_id': bloom_id,
    }

    $.ajax({type: "POST",
        url: pi_drop_down_fill_path,
        data: post_data,
        success: function (msg) {
            $('#pi_id_edit').html(msg);
            $('#loading').hide();
        }
    });
}

//Tiny MCE script
//tinymce.PluginManager.load('equationeditor', base_url+'twitterbootstrap/tinymce/plugins/tinymce_equation_editor/plugin.min.js');
function tiny_init() {
    tinymce.init({
        selector: "textarea",
        theme: 'modern',
        relative_urls: false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages",
        ],
        //content_css: base_url+'twitterbootstrap/tinymce/plugins/tinymce_equation_editor/mathquill.css',
        paste_data_images: true,
        setup: function (ed) {
            ed.on('change', function (e) {
                var id = $(this).attr('id');
                var id_val = id.split("_");
            });
        },
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | bold italic underline | bullist numlist | subscript superscript "
    });
}

// Form validation rules are defined & checked before form is submitted to controller.
$("#lesson_schedule_edit_form").validate({
    rules: {
        lesson_schedule_id1: {
            required: true,
            num: true
        },
    },
    message: {
        lesson_schedule_id1: {
            required: "This field is required",
        },
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('sucess');
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//
$("#question_edit_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    },
    rules: {
        type_question_modal: "required"
    }
});

$("#review_assignment_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    },
    rules: {
        type_question: "required"
    }
});

//
function safe_tags(str) {
    return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
}

window.onload = function () {
    //register_button('');
};

//
$('.select_bl').on('change', function () {
    $('#loading').show();
    var tlo_id = $(this).val();
    var bl_drop_down_fill_path = base_url + 'curriculum/tlo_list/select_bl';

    var post_data = {
        'tlo_id': tlo_id
    }

    $.ajax({type: "POST",
        url: bl_drop_down_fill_path,
        data: post_data,
        success: function (msg) {
            $('#bloom_id').html(msg);
            $('#loading').hide();
        }
    });
});

//
function select_pi_code() {
    var tlo_id = $('#tlo_ids').val();
    var course_id = $('#lesson_course_id').val();
    var bloom_id = $('#bloom_id').val();
    $('#loading').show();
    var pi_drop_down_fill_path = base_url + 'curriculum/tlo_list/select_pi_code';

    var post_data = {
        'tlo_id': tlo_id,
        'crs_id': course_id,
        'bloom_id': bloom_id,
    }

    $.ajax({type: "POST",
        url: pi_drop_down_fill_path,
        data: post_data,
        success: function (msg) {
            $('#pi_id').html(msg);
            $('#loading').hide();
        }
    });
}

/*$(function () {
 $('.datepicker').datepicker({
 dateFormat: 'yy-mm-dd',
 startDate: date,
 showOn: "button",
 buttonImage: base_url + "/twitterbootstrap/img/calendar.gif",
 buttonImageOnly: true
 });
 });*/

//while displaying set the format - yyyy-mm-dd
var date = new Date('');
date.setDate(date.getDate() - 1);


//function for calendar
$("#ls_date").datepicker({
    format: "dd-mm-yyyy",
    startDate: date
}).on('changeDate', function (ev) {

    startDate = new Date(ev.date.valueOf());
    startDate.setDate(startDate.getDate(new Date(ev.date.valueOf())));
    $('#ls_actual_date').datepicker('setStartDate', startDate);
});

$("#ls_actual_date").datepicker({
    format: "dd-mm-yyyy",
    // startDate: date
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

//function for calendar icon
$("#modal_ls_date").datepicker({
    format: "dd-mm-yyyy",
    startDate: date
}).on('changeDate', function (ev) {

    startDate = new Date(ev.date.valueOf());
    startDate.setDate(startDate.getDate(new Date(ev.date.valueOf())));
    $('#modal_ls_actual_date').datepicker('setStartDate', startDate);
});

$("#modal_ls_actual_date").datepicker({
    format: "dd-mm-yyyy",
    // startDate: date
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

$('#btn').click(function () {
    $(document).ready(function () {
        $("#ls_date").datepicker().focus();
    });
});

$('#btn_actual_date').click(function () {
    $(document).ready(function () {
        $("#ls_actual_date").datepicker().focus();
    });
});

$('#date_btn').click(function () {
    $(document).ready(function () {
        $("#modal_ls_date").datepicker().focus();
    });
});

$('#date_btn_date').click(function () {
    $(document).ready(function () {
        $("#modal_ls_actual_date").datepicker().focus();
    });
});
