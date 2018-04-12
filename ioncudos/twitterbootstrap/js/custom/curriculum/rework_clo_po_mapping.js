/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/
var base_url = $('#get_base_url').val();
$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

text_func();

/*Global Variables*/
var unmapping_val;
//display grid on select of term
$(document).ready(function () {
    var data_val3 = $('#term').val();
    var data_val2 = $('#curriculum').val();
    var data_val1 = $('#course').val();
    display_reviewer();
    var post_data = {
        'course_id': data_val1,
        'crclm_id': data_val2,
        'term_id': data_val3,
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/clo_rework_details',
        data: post_data,
        success: function (msg) {
            $('#table1').html(msg);
            //document.getElementById('table1').innerHTML = msg;
        }
    });
});


function display_reviewer()
{
    var data_val1 = $('#course').val();
    var post_data = {
        'course_id': data_val1,
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/clo_reviewer',
        data: post_data,
        success: function (msg) {
            $('#reviewer').html(msg);
        }
    });
    $('#reviewer').hide();
}

//onmouseover
function writetext2(po, clo) {
    $('#text1').html(po);
    $('#text2').html(clo);
}

//on tick
//checkbox
$('.check').live("click", function () {
    var id = $(this).attr('value');
    globalid = $(this).attr('id');
    window.id = id;
    var data_val = $('#curriculum').val();
    var post_data = {
        'po': id,
        'crclm_id': data_val,
    }
    if ($(this).is(":checked"))
    {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/load_pi',
            data: post_data,
            success: function (msg) {
                $('#myModal1').modal('show');
                document.getElementById('comment').innerHTML = msg;
            }
        });
    } else
    {
        $('#myModal2').modal('show');
        $('#comment').html();
    }
});
// On change event to load pi measures

$('.map_level').live('change', function () {

    var id = $(this).attr('value');

    var unmap_id = $(this).find('option:selected').attr('abbr');

    var map_level_data = id.split('|');

    globalid = $(this).attr('id');
    unmapping_val = unmap_id;
    window.id = unmap_id;
    window.id = id;

    var map_po_id = map_level_data[0];
    var map_clo_id = map_level_data[1];

    var data_val = $('#curriculum').val();
    var crs_id = $('#course').val();
    var post_data = {
        'po': id,
        'crs_id': crs_id,
        'crclm_id': data_val
    }
    if ($(this).val() != '')
    {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/load_pi',
            data: post_data,
            success: function (msg) {
                if (msg != "false") {
                    $('#myModal1').modal('show');
                    document.getElementById('comment').innerHTML = msg;
                    $('#map_level_val').val(map_level_data[2]);
                    $('#clo_po_id').val(map_po_id + '|' + map_clo_id);
                } else {
                    // OE & PIs optional
                    // commented as there is no OE & PIs - as requested by AKITC 
                    // $('#oe_pi_optional').modal('show');
                }
            }
        });
    } else
    {
        $('#myModal2').modal('show');
        $('#comment').html();
    }

    /* var id = $(this).attr('value');
     var unmap_id = $(this).find('option:selected').attr('abbr');
     
     var map_level_data = id.split('|');
     globalid = $(this).attr('id');
     window.id = unmap_id;
     
     var curriculum_id = document.getElementById('curriculum').value;
     
     
     var post_data = {
     'po': id,
     'crclm_id': curriculum_id,
     }
     if ($(this).val()!='')
     {
     $.ajax({type: "POST",
     url: base_url + 'curriculum/clo_po_map/load_pi', 
     data: post_data,
     success: function(msg) {
     $('#checkbox_all_checked').modal('show');
     document.getElementById('comment').innerHTML = msg;
     
     $('#map_level_val').val(map_level_data[2]);
     }
     });
     }
     else
     {
     $('#delete_clo_po_maaping').modal('show');
     document.getElementById('comment').innerHTML;
     }*/
});

function map_insert()
{
    var data_val1 = $('#course').val();
    var data_val2 = $('#curriculum').val();
    var data_val3 = $('#term').val();
    var data_val4 = $('#po_id').val();
    var data_val5 = $('#clo_id').val();
    var data_val6 = $('#pi').val();
    var map_level = $('#map_level_val').val();
    var pibx = $('input[name="pi[]"]:checked');
    var val = new Array();
    $.each($("input[name='pi[]']:checked"), function () {
        val.push($(this).val());
    });

    var chkB = $('input[name="cbox[]"]:checked');
    var values = new Array();
    $.each($("input[name='cbox[]']:checked"), function () {
        values.push($(this).val());
    });
    var post_data = {
        'course_id': data_val1,
        'crclm_id': data_val2,
        'term_id': data_val3,
        'po_id': data_val4,
        'clo_id': data_val5,
        'pi': val,
        'cbox': values,
        'map_level': map_level,
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/oncheck_save',
        data: post_data,
        success: function (msg) {

            var course_id = $('#course').val();
            var curriculum_id = $('#curriculum').val();
            var term_id = $('#term').val();

            var post_data = {
                'course_id': course_id,
                'crclm_id': curriculum_id,
                'term_id': term_id,
            }

            $.ajax({type: "POST",
                url: base_url + 'curriculum/clo_po_map/clo_details',
                data: post_data,
                success: function (msg) {
                    $('#table1').html(msg);
                }
            });
        }
    });
}

//from modal2 
function unmapping()
{
    var post_data = {
        'po': unmapping_val,
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/unmap',
        data: post_data,
    });
}

//validate pi msr form - whether related checkboxes are selected for that radio button
/* function validateForm() {
 var formValid = false;
 var ckboxLength = $('input[name="cbox[]"]:checked').length;
 var rdbtnLength = $('input[name="pi[]"]:checked').length;
 if (rdbtnLength && ckboxLength)
 formValid = true;
 if (!formValid){
 //alert("Select PI and its Measures!");
 $('#select_pis').modal('show');
 }else{
 map_insert();
 }
 return formValid;
 } */

function validateForm() {
    var formValid = false;
    var ckboxLength = $('input[name="cbox[]"]:checked').length;
    var rdbtnLength = $('input[name="pi[]"]:checked').length;

    if (rdbtnLength && ckboxLength)
        formValid = true;
    if (!formValid) {
        //alert("Select PI and its Measures!");
        $('#select_pis').modal('show');
    } else {
        map_insert();
        $('#myModal1').modal('hide');
    }
    return formValid;
}

//disable other radio buttons and checkboxes when one radio button is selected
$(function () {
    $('#comment').on('change', '.toggle-family', function () {
        if ($(this).attr('checked')) {
            $('.pi_' + $(this).val()).removeClass('hide');
        }
    });
});

//reset when close or cancel button is pressed - on tick
/* function uncheck() {
 $('#' + globalid).prop('checked', false);
 } */

//reset when close or cancel button is pressed - on tick
function uncheck() {
    var clo_po_id = $('#clo_po_id').val();
    var clo_po_id_array = clo_po_id.split('|');
    var po_id = clo_po_id_array[0];
    var clo_id = clo_po_id_array[1];
    var post_data = {
        'clo_id': clo_id,
        'po_id': po_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/clo_po_map/get_map_val',
        data: post_data,
        success: function (msg) {

            if (msg != 0) {
                $('select[id^="' + globalid + '"] option[value="' + po_id + '|' + clo_id + '|' + $.trim(msg) + '"]').attr('selected', 'selected');
            } else {
                $('#' + globalid).find('option:first').attr('selected', 'selected');
            }
        }
    });
    //$('#'+ globalid).find('option:first').attr('selected', 'selected');
}

//reset when close or cancel button is pressed - on untick
function check() {
    $('#' + globalid).prop('checked', true);
}

// scan row for check
$('#scan_row_col').live('click', function () {
    var sected_count = new Array();
    var data_val = $('#course').val();
    var curriculum_id = document.getElementById('curriculum').value;

    if (data_val) {
        var all_checked = true;
        var cbox_len = $(".select_verify").length;

        if (cbox_len == 0)
            all_checked = false;

        $('#table1 tr:not(:nth-child(1), .one)').each(function () {
            sected_count = [];
            $(this).removeAttr("style");
            $(this).children('td:not(:first-child)').each(function () {

                if (!$(this).children("select", "option:selected").val() == "") {
                    sected_count.push($(this).children("select", "option:selected").val());
                }

            });
            if (!sected_count.length > 0)
            {
                $(this).css("background-color", "grey");
                all_checked = false;
            }

            /*alert($(this).find('.select_verify','option:selected').val());
             if (!$(this).find('option:selected').length > 0)
             {
             $(this).css("background-color", "grey");
             all_checked = false;
             }
             */
        });

        /*$('#table1 tr:not(:nth-child(1), .one)').each(function() {
         $(this).removeAttr("style");
         if (!$(this).find('input:checked').length > 0)
         {
         $(this).css("background-color", "grey");
         all_checked = false;
         }
         });*/
        if (all_checked == true) {
            //mapping process has completed
            var post_data = {
                'crclm_id': curriculum_id,
                'crs_id': data_val
            }

            $.ajax({type: "POST",
                url: base_url + 'curriculum/clo_po_map/fetch_course_reviewer',
                data: post_data,
                dataType: "JSON",
                success: function (msg) {
                    $('#course_reviewer_user').html(msg.course_viewer_name);
                    $('#crclm_name_co_po_review').html(msg.crclm_name);
                    $('#myModal3').modal('show');
                }
            });
        } else {
            //mapping incompelte
            $('#myModal5').modal('show');
            all_checked = true;
        }
    }
});

//comments
$(document).ready(function () {
    $('.comment').live('click', function () {

        $('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            trigger: 'manual',
            placement: 'left'
        })
        $(this).popover('show');
        $('.close_btn').live('click', function () {
            $('a[rel=popover]').not(this).popover('destroy');
        });

    });
    $('.cmt_submit').live('click', function () {
        $('a[rel=popover]').not(this).popover('hide');
        var po_id = $('#po_id').val();
        var clo_id = $('#clo_id').val();
        var crclm_id = $('#crclm_id').val();
        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
            'status': 0
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/clo_po_cmt_update',
            data: post_data,
            success: function (msg) {

            }
        });
    });

});

function send_review() {
    $('#loading').show();
    var data_val1 = $('#course').val();
    var data_val2 = $('#reviewer_id').val();
    var data_val3 = $('#curriculum').val();
    var data_val4 = $('#term').val();

    var post_data = {
        'course_id': data_val1,
        'receiver_id': data_val2,
        'crclm_id': data_val3,
        'term_id': data_val4,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/rework_data',
        data: post_data,
        success: function (msg) {
            location.reload();
            // alert();
            // approve_review();

        }
    });
}


//on completion of sending mail & update the database
function approve_review() {
    alert('hi');
    var curriculum_id = document.getElementById('curriculum').value;

    var post_data = {
        'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo_po_map/fetch_course_reviewer',
        data: post_data,
        dataType: "JSON",
        success: function (msg) {
            alert('bye');
            $('#crclm_name_review').html(msg.crclm_name);
            $('#myModal4').modal('show');

        }
    });
}

$('#refresh').live('click', function () {
    $('#scan_row_col').hide();
    location.reload();
});

//Function to fetch selected PI and Measures
$('#table1').on('click', '.pm', function () {
    var id_name = $(this).attr('class').split(' ')[0];
    //var id_value = $('#'+id_name).val();
    var arr = id_name.split("|");

    var clo_id = arr[1];
    var po_id = arr[0];

    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;

    var post_data_pm = {
        'curriculum_id': curriculum_id,
        'term_id': term_id,
        'course_id': course_id,
        'clo_id': clo_id,
        'po_id': po_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo_po_map/clo_po_modal_display_pm',
        data: post_data_pm,
        success: function (msg) {
            document.getElementById('selected_pm_modal').innerHTML = msg;
        }
    });
    $('#myModal_pm').modal('show');
});


function skip_review() {

    var data_val1 = document.getElementById('course').value;
    var data_val2 = document.getElementById('term').value;
    var data_val3 = document.getElementById('curriculum').value;

    var post_data = {
        'course_id': data_val1,
        'term_id': data_val2,
        'crclm_id': data_val3,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/dashboard_data',
        data: post_data,
        success: function (msg) {
        }
    });
    location.reload();
    //$('#loading').show();
}

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

$('#table1').on('click', '.edit_clo_statement', function () {
    var clo_id = $(this).attr('id');
    var clo_statement = $(this).attr('value');
    $('#edit_clo_statement_view').html('<label>Course Outcome Statement <font color="red">*</font>: </label><textarea style="width:100%;" name="updated_clo_statement" id="' + clo_id + '" class="required updated_clo_statement" value="' + clo_statement + '">' + clo_statement + '</textarea>');
    $('#edit_clo_statement').modal('show');
});

$("#edit_clo_statement_view_form").validate({
    rules: {
        updated_clo_statement: {
            loginRegex: true
        },
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().removeClass('error');
        $(element).parent().addClass('success');
    }
});

$(document).on('click', '.update_clo_statement_btn', function (e) {
    e.preventDefault();
    var flag = $('#edit_clo_statement_view_form').valid();
    if (flag) {
        var updated_clo_statement = $('.updated_clo_statement').val();
        var clo_id = $('.updated_clo_statement').attr('id');
        var update_data = {
            'clo_id': clo_id,
            'clo_statement': updated_clo_statement
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clo_po_map/update_clo_statement',
            data: update_data,
            datatype: "JSON",
            success: function (msg) {
                $('#edit_clo_statement').modal('hide');
                location.reload();
            }
        });
    }
});


$(document).on('click', '.add_more_co_btn', function () {
    $('#add_co_statement_view').html('<label>Course Outcome Statement <font color="red">*</font>: </label><textarea style="width:100%;" name="add_co_statement" id="add_co_statement" class="required add_co_statement" value=""></textarea>');
    $('#add_more_co_div').modal('show');
});

$("#add_co_statement_view_form").validate({
    rules: {
        add_co_statement: {
            loginRegex: true
        },
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().removeClass('error');
        $(element).parent().addClass('success');
    }
});

$(document).on('click', '.save_co_btn', function (e) {
    e.preventDefault();
    var flag = $('#add_co_statement_view_form').valid();
    if (flag) {
        var co_stmt = $('#add_co_statement').val();
        var curriculum_id = $('#curriculum').val();
        var term_id = $('#term').val();
        var course_id = $('#course').val();
        var add_co_data = {
            'curriculum_id': curriculum_id,
            'term_id': term_id,
            'course_id': course_id,
            'co_stmt': co_stmt
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clo_po_map/add_more_co_statement',
            data: add_co_data,
            datatype: "JSON",
            success: function (msg) {
                $('#add_more_co_div').modal('hide');
                location.reload();
            }
        });
    }
});


$(document).on('click', '.delete_clo_statement', function () {
    var clo_id = $(this).attr('value');
    $('#clo_id_val').val(clo_id);
    $('#delete_clo_div').modal('show');
});

$(document).on('click', '.delete_clo_btn', function () {
    var clo_id = $('#clo_id_val').val();
    var delete_co_data = {
        'clo_id': clo_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clo_po_map/delete_clo_statement',
        data: delete_co_data,
        datatype: "JSON",
        success: function (msg) {
            location.reload();
        }
    });
});

//display textarea content
function text_func()
{
    var data_val1 = document.getElementById('curriculum').value;
    var data_val2 = document.getElementById('term').value;
    var data_val3 = document.getElementById('course').value;
    var post_data = {
        'crclm_id': data_val1,
        'term_id': data_val2,
        'course_id': data_val3,
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/fetch_txt',
        data: post_data,
        success: function (msg) {
            document.getElementById('text3').innerHTML = msg;
        }
    });
}

//auto save textarea content
$("textarea#text3").bind("keyup", function () {
    myAjaxFunction(this.value) //the same as myAjaxFunction($("textarea#text3").val())
});

function myAjaxFunction(value) {
    var data_val1 = document.getElementById('curriculum').value;
    var data_val2 = document.getElementById('term').value;
    var data_val3 = document.getElementById('course').value;
    var data_val4 = document.getElementById('text3').value;
    var post_data = {
        'crclm_id': data_val1,
        'term_id': data_val2,
        'course_id': data_val3,
        'text': data_val4,
    }
    $.ajax({
        url: base_url + 'curriculum/clopomap_review/save_txt',
        type: "POST",
        data: post_data,
        success: function (data) {
            if (!data) {
                alert("unable to save file!");
            } else
            {
                text_func();
            }
        }
    });
}

$('.save_justification').live('click', function () {

    var comment_map_val = $(this).attr('abbr');
    var comment_array = comment_map_val.split('|');
    var po_id = comment_array[0];
    var clo_id = comment_array[1];
    var crclm_id = comment_array[2];
    var crs_id = comment_array[4];
    var term_id = comment_array[5];
    var justification = $('#justification').val();

    var post_data = {
        'po_id': po_id,
        'clo_id': clo_id,
        'crclm_id': crclm_id,
        'justification': justification,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/clopomap_review/save_justification',
        data: post_data,
        success: function (msg) {
            $('a[rel=popover]').not(this).popover('destroy');
            //   $('a[rel=popover]').not(this).popover('toggle');
            var post_data = {
                'course_id': crs_id,
                'crclm_id': crclm_id,
                'term_id': term_id,
            }
            $.ajax({
                type: "POST",
                url: base_url + 'curriculum/clopomap_review/clo_rework_details',
                data: post_data,
                success: function (msg) {
                    $('#table1').html(msg);
                    //document.getElementById('table1').innerHTML = msg;
                }
            });
        }
    });
});


$(document).ready(function () {
    $('.comment_just').live('click', function () {
        $('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            placement: 'top'
        })
    });
    $('.close_btn').live('click', function () {
        $('a[rel=popover]').not(this).popover('destroy');
    });



    $('.cmt_submit').live('click', function () {
        $('a[rel=popover]').not(this).popover('hide');
        var po_id = $('#po_id').val();
        var clo_id = $('#clo_id').val();
        var crclm_id = $('#crclmid').val();

        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
            'status': 0,
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/clo_po_cmt_update',
            data: post_data,
            success: function (msg) {

            }
        });
    });

    $('.save_justification').live('click', function () {

        var comment_map_val = $(this).attr('abbr');
        var comment_array = comment_map_val.split('|');
        var po_id = comment_array[0];
        var clo_id = comment_array[1];
        var crclm_id = comment_array[2];
        var justification = $('#justification').val();

        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
            'justification': justification,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/save_justification',
            data: post_data,
            success: function (msg) {
                $('a[rel=popover]').not(this).popover('destroy');
                //   $('a[rel=popover]').not(this).popover('toggle');
            }
        });
    });

});


$(document).ready(function () {

    $('.comment').live('click', function (e) {
        e.preventDefault();
        var comment_map_val = $(this).attr('abbr');
        var comment_array = comment_map_val.split('|');
        var clo_id = comment_array[0];
        var po_id = comment_array[1];
        var crclm_id = comment_array[2];

        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/co_po_mapping_comment',
            data: post_data,
            dataType: 'JSON',
            success: function (msg) {
//			console.log(msg[0].cmt_statement);
                if (msg.length > 0) {
                    $('#clo_po_cmt').text(msg[0].cmt_statement);
                } else {
                    $('#clo_po_cmt').text('');
                }
            }
        });

        //$(this).attr('data-content').popover('show');
        $('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            trigger: 'manual',
            placement: 'left'
        })
        $(this).popover('show');
        $('.close_btn').live('click', function () {
            $('a[rel=popover]').not(this).popover('destroy');
        });
    });
    $('.comment_just').live('click', function (e) {
        e.preventDefault();
        var comment_map_val = $(this).attr('abbr');
        var comment_array = comment_map_val.split('|');
        var po_id = comment_array[0];
        var clo_id = comment_array[1];
        var crclm_id = comment_array[2];
        var clo_po_id = comment_array[3];

        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
            'clo_po_id': clo_po_id,
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/co_po_mapping_justification',
            data: post_data,
            dataType: 'JSON',
            success: function (msg) {
//			console.log(msg[0].cmt_statement);
                if (msg.length > 0) {
                    if (msg[0].justification == null) {
                        $('#justification').text("");
                    } else {
                        $('#justification').text(msg[0].justification);
                    }
                } else {
                    $('#justification').text('');
                }
            }
        });

        //$(this).attr('data-content').popover('show');
        $('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            trigger: 'manual',
            placement: 'left'
        })
        $(this).popover('show');
        $('.close_btn').live('click', function () {
            $('a[rel=popover]').not(this).popover('destroy');
        });
    });
    $('.cmt_submit').live('click', function () {
        $('a[rel=popover]').not(this).popover('hide');
        var po_id = document.getElementById('po_id').value;
        var clo_id = document.getElementById('clo_id').value;
        var crclm_id = document.getElementById('crclm_id').value;
        var clo_po_cmt = document.getElementById('clo_po_cmt').value;
        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
            'clo_po_cmt': clo_po_cmt,
        }
        if (clo_po_cmt != '') {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/clopomap_review/clo_po_cmt_insert',
                data: post_data,
                success: function (msg) {
                }
            });
        }
    });
});

