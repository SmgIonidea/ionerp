/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
 The following code shows how you may do that:*/
var base_url = $('#get_base_url').val();
$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});
//second dropdown - term
function select_term()
{
    var data_val = document.getElementById('curriculum').value;
    var post_data = {
	'crclm_id': data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/select_term',
	data: post_data,
	success: function (msg) {
	    document.getElementById('term').innerHTML = msg;
	}
    });
}


function select_course()
{
    var data_val = document.getElementById('term').value;
    var post_data = {
	'term_id': data_val
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/select_course', //"<?php echo base_url(); ?>",
	data: post_data,
	success: function (msg) {
	    document.getElementById('course').innerHTML = msg;
	}
    });
}
//display grid on select of term
$(document).ready(function () {
    display_reviewer();
    text_func();
    var data_val3 = document.getElementById('term').value;
    var data_val2 = document.getElementById('curriculum').value;
    var data_val1 = document.getElementById('course').value;
    var post_data = {
	'course_id': data_val1,
	'crclm_id': data_val2,
	'term_id': data_val3,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/clo_details',
	data: post_data,
	success: function (msg) {
	    document.getElementById('table1').innerHTML = msg;
	}
    });
});

//onmouseover
function writetext2(po, clo) {
    if (po != "" && clo != "") {
	document.getElementById('text1').innerHTML = po;
	document.getElementById('text2').innerHTML = clo;
    }
}

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

//on tick
//checkbox
$('.check').live("click", function () {
    if ($(this).is(':checked')) {
	document.getElementById(this.id).checked = false;
    } else {
	document.getElementById(this.id).checked = true;
    }
});

function send_review() {
    $('#loading').show();
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
	    $('#loading').hide();
	}
    });
}

function send() {
    var curriculum_id = document.getElementById('curriculum').value;

    var post_data = {
	'crclm_id': curriculum_id
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clo_po_map/fetch_programowner_user',
	data: post_data,
	dataType: "JSON",
	success: function (msg) {
	    $('#crclm_co_po').html(msg.crclm_name);
	    $('#myModal4').modal('show');
	}
    });
}

function send_reviewrework() {
    $('#loading').show();
    var data_val1 = document.getElementById('course').value;
    var data_val2 = document.getElementById('term').value;
    var data_val3 = document.getElementById('curriculum').value;
    var data_val4 = document.getElementById('reviewer_id').value;

    var post_data = {
	'course_id': data_val1,
	'term_id': data_val2,
	'crclm_id': data_val3,
	'reviewer_id': data_val4,
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/rework_dashboard_data',
	data: post_data,
	success: function (msg) {
	    approve_rework();
	}
    });
}

$('#scan_row_col').live('click', function () {
    //all checked. send for approval
    var disabled1 = false;
    var classList = $('#scan_row_col').attr('class').split(/\s+/);
    var curriculum_id = document.getElementById('curriculum').value;

    $.each(classList, function (index, item) {
	if (item === 'disabled') {
	    var disabled1 = true;
	    return false
	}
    });

    if (disabled1 == false) {
	var post_data = {
	    'crclm_id': curriculum_id
	}

	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clo_po_map/fetch_programowner_user',
	    data: post_data,
	    dataType: "JSON",
	    success: function (msg) {
		$('#program_owner_user').html(msg.programowner_user_name);
		$('#crclm_name_co_po_accept').html(msg.crclm_name);
		$('#myModal2').modal('show');
		$('#loading').hide();
	    }
	});
    }
});

$('#rework').live('click', function () {
    //all checked. send for approval
    var disabled = false;
    var classList = $('#rework').attr('class').split(/\s+/);
    var curriculum_id = document.getElementById('curriculum').value;
    var crs_id = document.getElementById('course').value;
    $.each(classList, function (index, item) {
	if (item === 'disabled') {
	    var disabled = true;
	}
    });

    if (disabled == false) {
	var post_data = {
	    'crclm_id': curriculum_id,
	    'crs_id': crs_id
	}
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/clo_po_map/fetch_course_owner',
	    data: post_data,
	    dataType: "JSON",
	    success: function (msg) {
		$('#course_owner_user').html(msg.course_owner_name);
		$('#crclm_name_co_po_rework').html(msg.crclm_name);
		$('#myModal5').modal('show');
	    }
	});
    }
});

function approve_accept()
{
    var data_val = document.getElementById('curriculum').value;
    var post_data = {
	'crclm_id': data_val,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/approve_accept_details',
	data: post_data,
	success: function (msg) {
	    document.location.href = base_url + 'dashboard/dashboard';
	}
    });
}

//rework_history
function approve_rework()
{
    var data_val = document.getElementById('curriculum').value;
    var post_data = {
	'crclm_id': data_val,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/update_workflow_history',
	data: post_data,
	success: function (msg) {
	    document.location.href = base_url + 'dashboard/dashboard';
	}
    });
}

//display Reviewer
function display_reviewer()
{
    var data_val1 = document.getElementById('course').value;
    var post_data = {
	'course_id': data_val1,
    }
    $.ajax({type: "POST",
	url: base_url + 'curriculum/clopomap_review/clo_reviewer',
	data: post_data,
	//dataType:'json',			
	success: function (msg) {
	    document.getElementById('reviewer').innerHTML = msg;
	}
    });
    $('#reviewer').hide();
}

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
	var term_id = comment_array[4];
	var crs_id = comment_array[5];
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
		$.ajax({type: "POST",
		    url: base_url + 'curriculum/clopomap_review/clo_details',
		    data: post_data,
		    success: function (msg) {
			$('#table1').html(msg);
			//document.getElementById('table1').innerHTML = msg;
		    }
		});
	    }
	});
    });

});


$(document).ready(function () {

    $('.comment').live('click', function (e) {
	e.preventDefault();
	var comment_map_val = $(this).attr('abbr');
	var comment_array = comment_map_val.split('|');
	var po_id = comment_array[0];
	var clo_id = comment_array[1];
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
	    //$('a[rel=popover]').not(this).popover('destroy');
	    var po_id = comment_array[0];
	    var clo_id = comment_array[1];
	    var crclm_id = comment_array[2];
	    var clo_po_id = comment_array[3];
	    var crs_id = comment_array[4];
	    var term_id = comment_array[4];
	    text_func();
	    var post_data = {
		'course_id': crs_id,
		'crclm_id': crclm_id,
		'term_id': term_id,
	    }
	    $.ajax({type: "POST",
		url: base_url + 'curriculum/clopomap_review/clo_details',
		data: post_data,
		success: function (msg) {
		    //$('#table1').html(msg);
		    document.getElementById('table1').innerHTML = msg;
		}
	    });
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

$('#refresh').live('click', function () {
    $('#scan_row_col').hide();
    $('#rework').hide();

});

$('#refresh_hide').live('click', function () {
    $('#scan_row_col').hide();
    $('#rework').hide();

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