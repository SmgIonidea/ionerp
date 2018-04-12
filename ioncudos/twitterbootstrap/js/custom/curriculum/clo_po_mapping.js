

/* You may use scroll spy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:-   */
var base_url = $('#get_base_url').val();

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");


var crclm_data_val;
$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});

//Function to fetch term details

if ($.cookie('remember_term') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
    select_term();
}


function select_term()
{

    $.cookie('remember_term', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
    crclm_data_val = document.getElementById('curriculum').value;
    var post_data = {
	'crclm_id': crclm_data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/select_term',
	data: post_data,
	success: function (msg) {
	    //console.log(msg);
	    document.getElementById('term').innerHTML = msg;
	    if ($.cookie('remember_course') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#term option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
		select_course();
	    }
	}
    });
}

function select_course()
{


    $.cookie('remember_course', $('#term option:selected').val(), {expires: 90, path: '/'});
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();

    var post_data = {
	'curriculum_id': crclm_id,
	'term_id': term_id
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/select_course',
	data: post_data,
	success: function (msg) {
	    //console.log(msg);
	    document.getElementById('course').innerHTML = msg;
	    if ($.cookie('remember_selected_value') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#course option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
		display_grid();
	    }
	}
    });
}
$('#course').on('change', function () {
    //fetch_po_clo_mapping_comment_notes();
});
//display grid on select of term
function display_grid()
{

    var course_id = document.getElementById('course').value;

    if (course_id == "") {
	document.getElementById('note').style.visibility = "hidden";
    } else {
	document.getElementById('note').style.visibility = "visible";
    }
    $.cookie('remember_selected_value', $('#course option:selected').val(), {expires: 90, path: '/'});
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
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/button_state',
	data: post_data,
	success: function (btn_state) {
	    if (btn_state == 6 || btn_state == 3 || btn_state == 1) {
		$('#scan_row_col').attr("disabled", false);
	    } else {
		$('#scan_row_col').attr("disabled", true);
	    }
	}
    });

    if (course_id != 0 && curriculum_id != 0 && term_id != 0) {
	$('#scan_row_col').attr("disabled", false);
    } else {
	$('#scan_row_col').attr("disabled", true);
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/clo_po_grid_status',
	data: post_data,
	success: function (msg) {
	    document.getElementById('clo_po_map_current_state').innerHTML = msg;
	}
    });

}


//display Reviewer
function display_reviewer()
{
    fetch_po_clo_mapping_comment_notes();
    var data_val1 = document.getElementById('course').value;
    var post_data = {
	'course_id': data_val1,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/clo_reviewer',
	data: post_data,
	success: function (msg) {
	    $('#reviewer_data').html(msg);
	}
    });
}

//onmouseover
function writetext2(po, clo) {

}

$('.check').live("click", function () {
    var id = $(this).attr('value');
    globalid = $(this).attr('id');
    window.id = id;
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;

    var post_data = {
	'po': id,
	'crclm_id': curriculum_id,
	'term_id': term_id,
    }
    if ($(this).is(":checked"))
    {
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clo_po_map/load_pi',
	    data: post_data,
	    success: function (msg) {
		$('#checkbox_all_checked').modal('show');
		document.getElementById('comment').innerHTML = msg;
	    }
	});
    } else
    {
	$('#delete_clo_po_maaping').modal('show');
	document.getElementById('comment').innerHTML;
    }
});

// On change event to load pi measures
$('.map_level').live('change', function () {
    var id = $(this).attr('value');
    var unmap_id = $(this).find('option:selected').attr('abbr');
    $('#loading').show();
    var map_level_data = id.split('|');
    if (map_level_data == "") {
	check = $(this).find('option:selected').attr('abbr');
	$('#clo_po_id').val(check);
	map_level_data = check.split('|');
    }
    globalid = $(this).attr('id');
    window.id = unmap_id;
    var map_po_id = map_level_data[0];
    var map_clo_id = map_level_data[1];
    var curriculum_id = document.getElementById('curriculum').value;
    var course_id = document.getElementById('course').value;
    var term_id = document.getElementById('term').value;

    var post_data = {
	'term_id': term_id,
	'po': id,
	'crs_id': course_id,
	'crclm_id': curriculum_id
    }
    if ($(this).val() != '')
    {
	
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clo_po_map/load_pi',
	    data: post_data,
	    success: function (msg) {	
display_grid();		
		if (msg != 0) {
		    $('#checkbox_all_checked').modal('show');
		    document.getElementById('comment').innerHTML = msg;
		    $('#map_level_val').val(map_level_data[2]);
		    $('#clo_po_id').val(map_po_id + '|' + map_clo_id);
		    $('#loading').hide();
		} else {
		    $('#loading').hide();
		}
	    }
	});
    } else
    {
	$('#delete_clo_po_maaping').modal('show');
	document.getElementById('comment').innerHTML;
	$('#loading').hide();
    }
});

function map_insert()
{
    var crs_id = $('#course').val();
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var po_id = $('#po_id').val();
    var clo_id = $('#clo_id').val();
    var pi_id = $('#pi').val();
    var pibx = $('input[name="pi[]"]:checked');
    var map_level = $('#map_level_val').val();

    var val = new Array();
    $.each($("input[name='pi[]']:checked"), function () {
	val.push($(this).val());
    });

    var chkB = $('input[name="cbox[]"]:checked');
    var array_values = new Array();
    $.each($("input[name='cbox[]']:checked"), function () {
	array_values.push($(this).val());
    });
    var post_data = {
	'course_id': crs_id,
	'crclm_id': crclm_id,
	'term_id': term_id,
	'po_id': po_id,
	'clo_id': clo_id,
	'pi': val,
	'cbox': array_values,
	'map_level': map_level,
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/oncheck_save',
	data: post_data,
	success: function (msg) {
	    $('#checkbox_all_checked').modal('hide');
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
$('#remap_clo_po_mapping').live('click', function () {
    $('#remap_confirmation').modal('show');
});

$('#cannot_remap').live('click' , function(){
	$('#cannot_remap_modal').modal('show');
});

$('#remap_ok').live('click', function () {
    var course_id = $('#course').val();
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();

    var post_data = {'crs_id': course_id, 'crclm_id': curriculum_id, 'term_id': term_id, }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/remap_co_po',
	data: post_data,
	success: function (msg) {
	    location.reload();
	}
    });
});
//from modal2 
function unmapping()
{
    //console.log(window.id);
    var post_data = {
	'po': id,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/unmap',
	data: post_data,
	success : function(msg){
		display_grid();
	}
    });
	
}

//validate pi msr form - whether related check boxes are selected for that radio button
function validateForm() {
    $('#loading').show();
    var formValid = false;
    var ckboxLength = $('input[name="cbox[]"]:checked').length;
    var rdbtnLength = $('input[name="pi[]"]:checked').length;

    if (rdbtnLength && ckboxLength)
	formValid = true;
    if (!formValid) {
	$('#select_pis').modal('show');
    } else {
	map_insert();
    }
    $('#loading').hide();
    return formValid;
}


$(function () {
    $('#comment').on('change', '.toggle-family', function () {
	if ($(this).attr('checked')) {
	    $('.pi_' + $(this).val()).removeClass('hide');
	} else {
	    $('.pi_' + $(this).val()).addClass('hide');
	}
    });



});

//reset when close or cancel button is pressed - on tick
function uncheck() {
    $('#loading').show();
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
    $('#loading').hide();
}

//reset when close or cancel button is pressed - on untick
function check() {
    $('#' + globalid).prop('selected', true);
}

// scan row for check
$('#scan_row_col').click(function () {
    var sected_count = new Array();
    var data_val = document.getElementById('course').value;
    var curriculum_id = document.getElementById('curriculum').value;
    $('#loading').show();
    if (data_val) {
	var all_checked = true;
	var cbox_len = $(".select_verify").length;

	if (cbox_len == 0)
	    all_checked = false;
	$('#map_table tr:not(:nth-child(1), .one)').each(function () {
	    sected_count = [];
	    $(this).removeAttr("style");
	    $(this).children('td:not(:first-child)').each(function () {

		if (!$(this).children("select", "option:selected").val() == "") {
		    sected_count.push($(this).children("select", "option:selected").val());
		}
	    });
	    if (!sected_count.length > 0) {
		$(this).css("background-color", "grey");
		all_checked = false;
	    }
	});

	if (all_checked == true) {
	    //mapping process complete
	    var post_data = {
		'crclm_id': curriculum_id,
		'crs_id': data_val
	    }

	    $.ajax({type: "POST",
		url: base_url + 'curriculum/clo_po_map/fetch_course_reviewer',
		data: post_data,
		dataType: "JSON",
		success: function (msg) {
		    $('#reviewer_user').html(msg.course_viewer_name);
		    $('#crclm_name_co_po').html(msg.crclm_name);
		    $('#send_review').modal('show');
		}
	    });
	} else {
	    //mapping incomplete
	    $('#myModal5').modal('show');
	    all_checked = true;
	}
    }
    $('#loading').hide();
});

function send_review() {
    var course = document.getElementById('course').value;
    var curriculum = document.getElementById('curriculum').value;
    var term = document.getElementById('term').value;
    $('#loading').show();
    var post_data = {
	'course_id': course,
	'crclm_id': curriculum,
	'term_id': term
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/dashboard_data',
	data: post_data,
	success: function (msg) {
	    $('#loading').hide();
	}
    });

}

function select_state() {
    var course = document.getElementById('course').value;
    var curriculum = document.getElementById('curriculum').value;

    var post_data = {
	'course_id': course,
	'crclm_id': curriculum
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/select_data',
	data: post_data,
	success: function (msg) {
	    document.getElementById('table1').innerHTML = msg;
	}
    });
}

function approve_review() {
    var curriculum_id = document.getElementById('curriculum').value;
    var crs_id = document.getElementById('course').value;
    $('#loading').show();
    var post_data = {
	'crclm_id': curriculum_id,
	'crs_id': crs_id
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/fetch_course_reviewer',
	data: post_data,
	dataType: "JSON",
	success: function (msg) {
	    $('#crclm_co_po').html(msg.crclm_name);
	    $('#sent_review_conformation').modal('show');
	    $('#loading').hide();
	}
    });
}

$(document).ready(function () {


    var crclm_id = $("#crclm_id").val();
    var term_id = $("#term_id_hidden").val();
    var course_id = $("#course_id_hidden").val();
    $("#curriculum").val(crclm_id).attr('selected', true);
    var post_data_crclm = {
	'crclm_id': crclm_id
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/select_term',
	data: post_data_crclm,
	success: function (msg) {
	    document.getElementById('term').innerHTML = msg;
	    $("#term").val(term_id).attr('selected', true);
	}
    });

    var post_data_term = {
	'curriculum_id': crclm_id,
	'term_id': term_id
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/select_course',
	data: post_data_term,
	success: function (msg) {
	    document.getElementById('course').innerHTML = msg;
	    $("#course").val(course_id).attr('selected', true);
	    display_grid();
	}

    });
    var data_val = document.getElementById('term_id_hidden').value;
    var data_val1 = document.getElementById('crclm_id').value;
    var data_val2 = document.getElementById('course_id_hidden').value;

    var post_data = {
	'term_id': data_val,
	'crclm_id': data_val1,
	'crs_id': data_val2,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/clo_details_url',
	data: post_data,
	success: function (msg) {
	    document.getElementById('table1').innerHTML = msg;
	    if ($('#checkdisabled').length > 0)
		$('#test123').attr('disabled', 'disabled').addClass('disabled');
	}
    });
    var data_val1 = document.getElementById('course_id_hidden').value;
    var post_data = {
	'course_id': data_val1,
    }

});
$('#refresh').live('click', function () {
    location.reload();
});

function onchange_grid()
{
    if ($('.check').attr('disabled')) {
	$('#scan_row_col').attr("disabled", true);
    } else {
	$('#scan_row_col').attr("disabled", false);
    }
}

//Function to fetch selected PI and Measures
$('#table1').on('click', '.pm', function () {
    var id_name = $(this).attr('class').split(' ')[0];
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

//comments
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
        
	$('a[rel=popover]').not(this).popover('destroy');
	$('a[rel=popover]').popover({
	    html: 'true',
	    placement: 'top'
	})
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
	var term_id = comment_array[4];
	var crs_id = comment_array[5];

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
	    /*var crclm_id = comment_array[2];
	     var term_id = comment_array[4];
	     var crs_id = comment_array[5];		
	     
	     var post_data = {
	     'course_id': crs_id,
	     'crclm_id': crclm_id,
	     'term_id': term_id,
	     }
	     
	     $.ajax({type: "POST",
	     url: base_url + 'curriculum/clo_po_map/clo_details',
	     data: post_data,
	     success: function (msg) {
	     $('#table1').html(msg);
	     }
	     });*/
	    $('a[rel=popover]').not(this).popover('destroy');
	});
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
	var crs_id = comment_array[4];
	var term_id = comment_array[5];
	var justification = $('#justification').val();

	var post_data = {
	    'po_id': po_id,
	    'clo_id': clo_id,
	    'crclm_id': crclm_id,
	    'justification': justification,
	}

	$.ajax({
	    type: "POST",
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
		    //url: base_url + 'curriculum/clo_po_map/clo_details',
		    url: base_url + 'curriculum/clopomap_review/clo_details_rework',
		    data: post_data,
		    success: function (msg) {
			$('#table1').html(msg);
		    }
		});
	    }
	});
    });

});


function skip_review() {

    var data_val1 = document.getElementById('course').value;
    var data_val2 = document.getElementById('term').value;
    var data_val3 = document.getElementById('curriculum').value;
    $('#loading').show();
    var post_data = {
	'course_id': data_val1,
	'term_id': data_val2,
	'crclm_id': data_val3,
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/dashboard_data',
	data: post_data,
	success: function (msg) {
	    //Added by Bhagya S S
	    $.ajax({type: "POST",
		url: base_url + 'curriculum/clopomap_review/fetch_course_mode',
		data: post_data,
		dataType: "JSON",
		success: function (msg) {
		    if (msg == 0 || msg == 2) {
			window.location = base_url + 'curriculum/topic/';
			$('#loading').hide();
		    } else if (msg == 1) {
			window.location = base_url + 'curriculum/lab_experiment';
			$('#loading').hide();
		    } else {
			alert("error");
		    }
		}
	    });
	}
    });
}
//Function to fetch and display help details related to course learning objective to program outcomes
$('.show_help').live('click', function () {
    $.ajax({
	url: base_url + 'curriculum/clo_po_map/clo_po_help',
	datatype: "JSON",
	success: function (msg) {
	    document.getElementById('help_view').innerHTML = msg;
	}
    });
});

$('#table1').on('click', '.edit_clo_statement', function () {
    var clo_id = $(this).attr('id');
    var curriculum_id = $('#curriculum').val();
    var clo_statement = $(this).attr('value');
    $('#loading').show();
    var post_data = {
	'clo_id': clo_id,
	'crclm_id': curriculum_id
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/getCloBloomDeliveryInfo',
	data: post_data,
	datatype: "JSON",
	success: function (msg) {
	    var dropdown = JSON.parse(msg);
	    $('#edit_clo_statement_view').html('Course Outcome Statement <font color="red">*</font>: <textarea style="width:55%;" name="updated_clo_statement" id="' + clo_id + '" class="required updated_clo_statement" value="' + clo_statement + '">' + clo_statement + '</textarea><div class="span12"><div class="span3" style="text-align: center;"> Bloom\'s Level  : </div><div class="span4">' + dropdown.bloom_level_dropdown + '</div><div class="span4" id="bloom_level_actionverbs">' + dropdown.bloom_level_action_verb + '</div><br><br><br></div><div class="span12"><div class="span3" style="text-align: center;"><span data-key="lg_delvry_mthd">Delivery Method</span>  : </div><div class="span4">' + dropdown.delivery_method_dropdown + '</div><div class="span4"></div></div>');

	    $('#bloom_level').multiselect({
		onChange: function (option, checked) {
		    var selections = [];
		    var action_verb_data = [];
		    $("#bloom_level option:selected").each(function () {
			var bloom_level_id = $(this).val();
			var bloom_level = $(this).text();
			var action_verbs = $(this).attr('title');
			selections.push(bloom_level_id);
			action_verb_data.push('<b>' + bloom_level + '-</b>' + action_verbs + '<br>');
		    });
		    var action_verb = action_verb_data.join("");
		    $('#bloom_level_actionverbs').html(action_verb.toString());
		},
		maxHeight: 400,
		buttonWidth: 160,
		numberDisplayed: 5,
		nSelectedText: 'selected',
		nonSelectedText: 'Select Blooms Level'
	    });
	    $('#delivery_method').multiselect({
		maxHeight: 200,
		numberDisplayed: 5,
		nSelectedText: 'selected',
		nonSelectedText: 'Select Delivery Level'
	    });
	}
    });

    $('#edit_clo_statement').modal('show');
    $('#loading').hide();
});

$("#edit_clo_statement_view_form").validate({
    ignore: '*:not([name])',
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
    $('#loading').show();
    if (flag) {
	var updated_clo_statement = $('.updated_clo_statement').val();
	var clo_id = $('.updated_clo_statement').attr('id');
	var course = $('#course').val()
	var bloom_level = $('#bloom_level').val();
	var delivery_method = $('#delivery_method').val();
	var update_data = {
	    'clo_id': clo_id,
	    'clo_statement': updated_clo_statement,
	    'course_id': course,
	    'bloom_level': bloom_level,
	    'delivery_method': delivery_method
	}
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clo_po_map/update_clo_statement',
	    data: update_data,
	    datatype: "JSON",
	    success: function (msg) {
		$('#edit_clo_statement').modal('hide');
		display_grid();
		$('#loading').hide();
	    }
	});
    } else {
	$('#loading').hide();
    }
});

$(document).on('click', '.add_more_co_btn', function () {
    $('#add_co_statement_view').html('Course Outcome Statement <font color="red">*</font>: <textarea style="width:55%;" name="add_co_statement" id="add_co_statement" class="required add_co_statement" value=""></textarea><div class="span12"><div class="span3" style="text-align: center;"> Blooms Level  : </div><div class="span4"><select class="example-getting-started" name="bloom_level_1[]" id="bloom_level_1" multiple="multiple">' + bloom_level_options + '</select></div><div class="span4" id="bloom_level_1_actionverbs"></div><br><br><br></div><div class="span12"><div class="span3" style="text-align: center;">Delivery Method  : </div><div class="span4"><select class="example-getting-started" name="delivery_method_1[]" id="delivery_method_1" multiple="multiple"  >' + delivery_method_options + '</select> </div><div class="span4"></div></div>');

    $('#loading').show();
    $('#bloom_level_1').multiselect({
	onChange: function (option, checked) {
	    var selections = [];
	    var action_verb_data = [];
	    $("#bloom_level_1 option:selected").each(function () {
		var bloom_level_id = $(this).val();
		var bloom_level = $(this).text();
		var action_verbs = $(this).attr('title');
		selections.push(bloom_level_id);
		action_verb_data.push('<b>' + bloom_level + '-</b>' + action_verbs + '<br>');
	    });
	    var joined = action_verb_data.join("");
	    $('#bloom_level_1_actionverbs').html(joined.toString());
	},
	maxHeight: 200,
	buttonWidth: 160,
	numberDisplayed: 5,
	nSelectedText: 'selected',
	nonSelectedText: 'Select Blooms Level'
    });
    $('#delivery_method_1').multiselect({
	maxHeight: 200,
	numberDisplayed: 5,
	nSelectedText: 'selected',
	nonSelectedText: 'Select Delivery Method'
    });

    //delivery method dropdown generation
    crclm_id = document.getElementById('curriculum').value;
    var post_data = {
	'crclm_id': crclm_id
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/fetch_delivery_method',
	data: post_data,
	success: function (msg) {

	    console.log(msg.length);
	    $('#delivery_method_1').append(msg);
	    $('#delivery_method_1').multiselect('rebuild');

	}
    });


    $('#loading').hide();
    $('#add_more_co_div').modal('show');
});

$("#add_co_statement_view_form").validate({
    ignore: '*:not([name])',
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
    $('#loading').show();
    if (flag) {
	var co_stmt = $('#add_co_statement').val();
	var curriculum_id = $('#curriculum').val();
	var term_id = $('#term').val();
	var course_id = $('#course').val();
	var bloom_level = $('#bloom_level_1').val();
	var delivery_method = $('#delivery_method_1').val();
	var add_co_data = {
	    'curriculum_id': curriculum_id,
	    'term_id': term_id,
	    'course_id': course_id,
	    'co_stmt': co_stmt,
	    'bloom_level': bloom_level,
	    'delivery_method': delivery_method
	}
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clo_po_map/add_more_co_statement',
	    data: add_co_data,
	    datatype: "JSON",
	    success: function (msg) {
		console.log(msg);
		$('#add_more_co_div').modal('hide');
		display_grid();
		$('#loading').hide();
	    }
	});
    } else {
	$('#loading').hide();
    }
});

$(document).on('click', '.delete_clo_statement', function () {
    $('#loading').show();
    var clo_id = $(this).attr('value');
    $('#clo_id_val').val(clo_id);
    $('#delete_clo_div').modal('show');
    $('#loading').hide();
});

$(document).on('click', '.delete_clo_btn', function () {
    var clo_id = $('#clo_id_val').val();
    $('#loading').show();
    var delete_co_data = {
	'clo_id': clo_id
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/delete_clo_statement',
	data: delete_co_data,
	datatype: "JSON",
	success: function (msg) {
	    display_grid();
	    $('#loading').hide();
	}
    });
});


//function to get the notes (text) entered
$("textarea#clo_po_comment_box_id").bind("blur", function () {
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
    var text_value = $(this).val();

    var post_data = {
	'crclm_id': curriculum_id,
	'term_id': term_id,
	'text': text_value,
	'course_id': course_id
    }

    $.ajax({
	url: base_url + 'curriculum/clo_po_map/save_txt',
	type: "POST",
	data: post_data,
	success: function (data) {
	    if (!data) {
		alert("unable to save file!");
	    }
	}
    });
});


//function to fetch notes (text) related to PO to CLO mapping in a curriculum.
function fetch_po_clo_mapping_comment_notes() {

    var curriculum_id = document.getElementById('curriculum').value;
    var course_id = document.getElementById('course').value;
    var term = document.getElementById('term').value;
    var post_data = {
	'crclm_id': curriculum_id,
	'course_id': course_id,
	'term_id': term
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/clo_po_map/fetch_txt',
	data: post_data,
	success: function (msg) {
	    document.getElementById('clo_po_comment_box_id').innerHTML = msg;
	    $("textarea").val(msg);
	}
    });
}

/**********artifacts***********/

//displaying the modal
$('#artifacts_modal').click(function (e) {
    e.preventDefault();
    display_artifact();
});

//displaying the modal content
function display_artifact() {
    $('#loading').show();
    var artifact_value = $('#art_val').val();
    var crclm_id = $('#curriculum').val();
    if (crclm_id != '') {
	var post_data = {
	    'art_val': artifact_value,
	    'crclm': crclm_id
	}
	$.ajax({
	    type: "POST",
	    url: base_url + 'upload_artifacts/artifacts/modal_display',
	    data: post_data,
	    async: false,
	    success: function (data) {
		$('#art').html(data);
		$('#mymodal').modal('show');
		$('#loading').hide();
	    }
	});
    } else {
	$('#loading').hide();
	$('#select_crclm').modal('show');
    }
}

//uploading the file 
$('.art_facts,#curriculum').on('click change', function (e) {
    var uploader = document.getElementById('uploaded_file');
    var crclm_id = $('#curriculum').val();
    var art = $('#art_val').val();
    var val = $(this).attr('uploaded-file');
    var folder_name = $('#curriculum option:selected').val();
    var post_data = {
	'crclm': crclm_id,
	'art_val': art,
	'crclm': folder_name
    }
    upclick({
	element: uploader,
	action_params: post_data,
	action: base_url + 'upload_artifacts/artifacts/modal_upload',
	onstart: function (filename) {
	    (document).getElementById('loading_edit').style.visibility = 'visible';
	},
	oncomplete: function (response_data) {
	    if (response_data == "file_name_size_exceeded") {
		$('#file_name_size_exc').modal('show');
	    } else if (response_data == "file_size_exceed") {
		$('#larger').modal('show');
	    }
	    display_artifact();
	    (document).getElementById('loading_edit').style.visibility = 'hidden';
	}
    });
});

//deleting the file
$('#art').on('click', '.artifact_entity', function (e) {
    var del_id = $(this).attr('data-id');

    $('#delete_file').modal('show');
    $('#delete_selected').click(function (e) {
	$('#loading').show();
	$.ajax({
	    type: "POST",
	    url: base_url + 'upload_artifacts/artifacts/modal_delete_file',
	    data: {'artifact_id': del_id},
	    success: function (data) {
		display_artifact();
		$('#loading').hide();
	    }
	});
	$('#delete_file').modal('hide');
    });

});

$('body').on('focus', '.std_date', function () {
    $("#af_actual_date").datepicker({
	format: "yyyy-mm-dd",
	//endDate:'-1d'
    }).on('changeDate', function (ev) {
	$(this).blur();
	$(this).datepicker('hide');
    });
    $(this).datepicker({
	format: "yyyy-mm-dd",
	//endDate:'-1d'
    }).on('changeDate', function (ev) {
	$(this).blur();
	$(this).datepicker('hide');
    });
});

$('#art').on('click', '.std_date_1', function () {
    $(this).siblings('input').focus();
});

//on save artifact description and date
$('#save_artifact').live('click', function (e) {
    e.preventDefault();
    $('#myform').submit();
});

$('#myform').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var form_val = new Array();

    $('.save_form_data').each(function () {
	//values fetched will be inserted into the array
	form_val.push($(this).val());
    });

    //check whether file any file exists or not
    if (form_val.length > 0) {
	//if file exists
	$.ajax({
	    type: "POST",
	    url: base_url + 'upload_artifacts/artifacts/save_artifact',
	    data: form_data,
	    contentType: false,
	    cache: false,
	    processData: false,
	    success: function (msg) {
		if ($.trim(msg) == 1) {
		    //display success message on save
		    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
		    var options = $.parseJSON(data_options);
		    noty(options);
		} else {
		    //display error message - if description and date could not be saved
		    var data_options = '{"text":"Your data could not be saved.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
		    var options = $.parseJSON(data_options);
		    noty(options);
		}
	    }
	});
    } else {
	//display error message if file does not exist and user tries to click save button
	var data_options = '{"text":"File needs to be uploaded.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
	var options = $.parseJSON(data_options);
	noty(options);
    }
});

//message to display animated notification instead of modal
$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
});

