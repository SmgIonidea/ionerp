//Program Owner's PO to PEO mapping List View JS functions.

/*You may use scrollspy along with creating and removing elements form DOM. 
 * But if you do so, you have to call the refresh method . 
 * The following code shows how you may do that.
 */
var base_url = $('#get_base_url').val();

$('[data-spy="scroll"]').each(function () {
    var $spy = $(this).scrollspy('refresh')
});
//set cookie
if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum_list option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_curriculum();
}

$('.show_help').on('click', function () {
    $.ajax({
	url: base_url + 'curriculum/map_po_peo/peo_po_help',
	datatype: "JSON",
	success: function (msg) {
	    document.getElementById('po_peo_help_content_id').innerHTML = msg;
	}
    });
});

function select_curriculum() {

    $.cookie('remember_curriculum', $('#curriculum_list option:selected').val(), {expires: 90, path: '/'});
    document.getElementById('error').innerHTML = '';
    //document.getElementById('po_peo_comment_box_id').innerHTML = '';
    fetch_po_peo_mapping_comment_notes();
    // $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;

    if (curriculum_id != "Select Curriculum") {
//	document.getElementById('side_bar').style.visibility = 'visible';  //Javascript type not working	
	$('#side_bar').show();
    } else {
	document.getElementById('side_bar').style.visibility = 'hidden';  //Javascript type not working	
	document.getElementById('send_remap_div_id').style.visibility = 'hidden'; //Javascript type not working
	$('#side_bar').hide();
    }

    if (curriculum_id != 'Select Curriculum') {

	var post_data = {
	    'crclm_id': curriculum_id
	}

	$.ajax({
	    type: "POST",
	    url: base_url + 'curriculum/map_po_peo/map_table_new',
	    data: post_data,
	    success: function (msg) {
		//document.getElementById('mapping_table').innerHTML = msg; //Javascript type not working
		$('#mapping_table').html(msg);
		$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/fetch_comments',
		    data: post_data,
		    success: function (Comment) {
			//document.getElementById('po_peo_comment_box').innerHTML = Comment;	//Javascript type not working
			$('#po_peo_comment_box').html(Comment);
		    }});
		$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/disable',
		    data: post_data,
		    success: function (msg1) {
			if (msg1 != 1) {			
			    $('#loading').hide();
			    // button is visible	
			    document.getElementById('send_approve_div_id').style.visibility = 'visible';  //Javascript type not working
			    $('#send_approve_div_id').show();
			} else {
			    $('#loading').hide();
			    document.getElementById('send_approve_div_id').style.visibility = 'hidden';   //Javascript type not working
			    $('#send_approve_div_id').hide();
			}

		    }
		});		
		
		$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/disable_remap',
		    data: post_data,
		    success: function (msg1) {
			if (msg1 != 1) {			
			    $('#loading').hide();
			    // button is visible	

			    document.getElementById('send_remap_div_id').style.visibility = 'hidden';    //Javascript type not working
			    $('#send_remap_div_id').hide();
			} else {
			    $('#loading').hide();
			    document.getElementById('send_remap_div_id').style.visibility = 'visible';    //Javascript type not working
			    $('#send_remap_div_id').show();
			}

		    }
		});
	    }
	});
    } else {
	$('#loading').hide();
	document.getElementById('mapping_table').innerHTML = '';
	// button is invisible	
	document.getElementById('send_approve_div_id').style.visibility = 'hidden';
	document.getElementById('send_remap_div_id').style.visibility = 'hidden';
    }
}



$(document).ready(function () {
    $('.comment').live('click', function () {
	$('a[rel=popover]').not(this).popover('destroy');
	$('a[rel=popover]').popover({
	    html: 'true',
	    placement: 'top'
	})
    });
    $('.close_btn').live('click', function () {
	$('a[rel=popover]').not(this).popover('destroy');
	//$(this).popover('hide');
    });

    $('.comment_just').live('click', function (e) {

	e.preventDefault();
	var comment_map_val = $(this).attr('abbr');
	var comment_array = comment_map_val.split('|');
	var po_id = comment_array[0];
	var peo_id = comment_array[1];
	var pp_id = comment_array[2];
	var crclm_id = comment_array[3];

	var post_data = {'peo_id': peo_id, 'crclm_id': crclm_id, 'po_id': po_id, 'pp_id': pp_id}
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/map_po_peo/fetch_justification',
	    data: post_data,
	    dataType: 'JSON',
	    success: function (msg) {
		//console.log(msg[0].cmt_statement);
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
	$('a[rel=popover]').not(this).popover('destroy');
	$('a[rel=popover]').popover({
	    html: 'true',
	    trigger: 'manual',
	    placement: 'left'
	})
	$(this).popover('show');
	$('.close_btn').live('click', function () {
	    //$('a[rel=popover]').not(this).popover('destroy');
	    //$(this).popover('hide');
	    var crclm_id = comment_array[3];
	    $.ajax({
		type: "POST",
		url: base_url + 'curriculum/map_po_peo/map_table_new',
		data: post_data,
		success: function (msg) {
		    //document.getElementById('mapping_table').innerHTML = msg;		//Javascript is not working
		    $('#mapping_table').html(msg);
		    $.ajax({
			type: "POST",
			url: base_url + 'curriculum/map_po_peo/fetch_comments',
			data: post_data,
			success: function (Comment) {
			    //document.getElementById('po_peo_comment_box').innerHTML = Comment;	    //Javascript is not working
			    $('#po_peo_comment_box').html(Comment)
			}
		    });
		}
	    });
	});



    });


    $('.save_justification').live('click', function () {
	var comment_map_val = $(this).attr('abbr');
	var comment_array = comment_map_val.split('|');
	var po_id = comment_array[0];
	var peo_id = comment_array[1];
	var pp_id = comment_array[2];
	var crclm_id = comment_array[3];
	var justification = $('#justification').val();
	var post_data = {'peo_id': peo_id, 'crclm_id': crclm_id, 'po_id': po_id, 'justification': justification, 'pp_id': pp_id}
	var post_data1 = {'crclm_id': crclm_id}
	$.ajax({type: "POST",
	    url: base_url + 'curriculum/map_po_peo/save_justification',
	    data: post_data,
	    success: function (msg) {
		$('a[rel=popover]').not(this).popover('destroy');
		//location.reload();
		//   $('a[rel=popover]').not(this).popover('toggle');
		$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/map_table_new',
		    data: post_data,
		    success: function (msg) {
			document.getElementById('mapping_table').innerHTML = msg;
			$.ajax({
			    type: "POST",
			    url: base_url + 'curriculum/map_po_peo/fetch_comments',
			    data: post_data,
			    success: function (Comment) {
				document.getElementById('po_peo_comment_box').innerHTML = Comment;
			    }
			});
		    }
		});
	    }
	});
    });


    /*     $('.cmt_submit').live('click', function () {
     $('a[rel=popover]').not(this).popover('hide');
     }); */
});



//Fetching the current State of POs to PEOs Mapping.
function current_state() {
    var curriculum_id = document.getElementById('curriculum_list').value;
    var post_data = {
	'crclm_id': curriculum_id
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_po_peo_map_current_state',
	data: post_data,
	success: function (msg) {
	    document.getElementById('po_peo_map_current_state').innerHTML = msg;
	}
    });
    // call to fetch BoS user
    $.ajax({type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_bos_user',
	data: post_data,
	dataType: 'JSON',
	success: function (result) {
	    $('#bos_username').html(result.bos_user_name);
	    $('#crclm_name').html(result.crclm_name);
	}
    });
}

//to display po statement to the user
function writetext2(po) {
}

//
$('#send_approve_button_id').live('click', function () {
    $('#loading').show();
    var flag = 0;
    var tmp = true;
    $('#popeoList tr:not(:nth-child(0))').each(function () {
	$(this).removeAttr("style");
	if ($(this).find('option:selected').text().length > 0) {
	    tmp = false;
	} else {
	    if (flag > 0)
		$(this).css("background-color", "gray");
	    flag++;
	}
	$('#loading').hide();
    });

    $('#popeoList tr:nth-child(1) td').each(function () {
	var td_class = $(this).attr('class');
	$('.' + td_class).removeAttr("style");
	var tmp = true;
	$('.' + td_class + ':not(:nth-child(0))').each(function () {
	    if ($(this).find('option:selected').text().length > 0) {
		tmp = false;
	    }
	});

	if (tmp) {
	    $('.' + td_class).css("background-color", "gray");
	    flag++;
	}
    });

    if (flag > 2) {
	$('#loading').hide();
	$('#incomplete_mapping_dialog_id').modal('show');
    } else {
	flag = 0;
	$('#loading').hide();
	$('#send_mapping_approval_dialog_id').modal('show');
    }
});

var globalid;

//
$('.check').live("change", function () {

    var id = $(this).attr('value');
    globalid = $(this).attr('id');
    window.id = id;
    var curriculum_id = document.getElementById('curriculum_list').value;
    window.curriculum_id = curriculum_id;
    var post_data = {
	'po': id,
	'crclm_id': curriculum_id,
    }
    if ($(this).is(":checked")) {
	$.ajax({
	    type: "POST",
	    url: base_url + 'curriculum/map_po_peo/add_mapping',
	    data: post_data,
	    success: function (msg) {
	    }
	});
    } else {
	$('#uncheck_mapping_dialog_id').modal('show');
    }
});

//
function cancel_uncheck_mapping_dialog() {
    $('#' + globalid).prop('checked', true);

}

//from modal2
function unmapping() {
    var curriculum_id = document.getElementById('curriculum_list').value;
    var post_data = {
	'po': id,
	'crclm_id': curriculum_id,
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/unmap',
	data: post_data,
	success: function (msg) {
	    $('#uncheck_mapping_dialog_id').modal('hide');
	}
    });
}

//
function send_mapping_approval_dialog() {
    $('#loading').show();
    $('#sent_for_approval_dialog_id').modal('show');
    $('#loading').hide();
}

//function is to insert curriculum id into the hidden input field
function submit_mapping_form() {
    document.getElementById('crclm_id').value = document.getElementById('curriculum_list').value;
    $('#frm').submit();
}

//function to get the notes (text) entered
$("textarea#po_peo_comment_box_id").bind("keyup", function () {
    var curriculum_id = document.getElementById('curriculum_list').value;
    var text_value = $(this).val();

    var post_data = {
	'crclm_id': curriculum_id,
	'text': text_value,
    }

    $.ajax({
	url: base_url + 'curriculum/map_po_peo/save_txt',
	type: "POST",
	data: post_data,
	success: function (data) {
	    if (!data) {
		alert("unable to save file!");
	    }
	}
    });
});

//function to save the notes (text)

//function to fetch notes (text) related to PO to PEO mapping in a curriculum.
function fetch_po_peo_mapping_comment_notes() {

    var curriculum_id = document.getElementById('curriculum_list').value;
    var post_data = {
	'crclm_id': curriculum_id,
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_txt',
	data: post_data,
	success: function (msg) {

	    if (msg != 1) {
		document.getElementById('po_peo_comment_box_id').innerHTML = msg;
		$('#po_peo_comment_box_id').val(msg);
	    } else if (msg == 1) {
		$('#po_peo_comment_box_id').attr('placeholder', 'Enter text here');
	    }
	}
    });
}

function textout2() {
    document.getElementById('po_display_textbox_id').innerHTML = '';
}
$(document).ready(function () {
    $('.comment').live('click', function () {
	$('a[rel=popover]').not(this).popover('destroy');
	$('a[rel=popover]').popover({
	    html: 'true',
	    placement: 'top'
	})
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

//Approver's PO to PEO mapping List View JS functions.	

//to display po statement to the user
function write_po_statement(po) {
    document.getElementById('po_display_textbox_id').innerHTML = po;
    //approver_fetch_po_peo_mapping_comment_notes();
}
function erase_po_statement() {
    document.getElementById('po_display_textbox_id').innerHTML = '';
}

$('#rework').click(function () {
    document.getElementById('state').value = 6;

    var curriculum_id = document.getElementById('curriculum_list').value;
    var post_data = {
	'crclm_id': curriculum_id
    }
    // call to fetch BoS user
    $.ajax({type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_chairman_user',
	data: post_data,
	dataType: 'JSON',
	success: function (result) {
	    $('#chairman_username_review').html(result.chairman_user_name);
	    $('#crclm_name_review').html(result.crclm_name);
	}
    });
    $('#myModal_rework').modal('show');

});

$('.ok_rework').on('click', function () {
    document.getElementById('crclm_id').value = document.getElementById('crclm_id').value;
    $('#po_peo_frm').submit();
    $('#loading').show();
});

$('#approved').click(function () {
    document.getElementById('state').value = 7;
    var curriculum_id = document.getElementById('curriculum_list').value;
    var post_data = {
	'crclm_id': curriculum_id
    }
    // call to fetch BoS user
    $.ajax({type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_programowner_user',
	data: post_data,
	dataType: 'JSON',
	success: function (result) {
	    $('#programowner_username').html(result.programowner_user_name);
	    $('#crclm_name').html(result.crclm_name);
	}
    });
    $('#myModal_approve').modal('show');

});

$('.ok_approve').on('click', function () {
    document.getElementById('crclm_id').value = document.getElementById('crclm_id').value;
    $('#po_peo_frm').submit();
    $('#loading').show();
});

//Skip Approval script 

function skip_approve_button_id() {
    var curriculum_id = document.getElementById('curriculum_list').value;
    $('#loading').show();
    var post_data = {
	'crclm_id': curriculum_id
    }
    // call to fetch BoS user
    $.ajax({type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_programowner_user',
	data: post_data,
	dataType: 'JSON',
	success: function (result) {
	    $('#programowner_username').html(result.programowner_user_name);
	    $('#crclm_name_skip').html(result.crclm_name);
	    $('#loading').hide();
	}
    });
    $('#loading').hide();
    $('#skip_mapping_approval_dialog_id').modal('show');
}

$('.bos_skip_approval').on('click', function () {
    $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;
    var state_id = document.getElementById('state').value = 7;
    var post_data = {
	'state': state_id,
	'crclm_id': curriculum_id
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/bos_skip_approval',
	data: post_data,
	dataType: 'JSON',
	success: function (msg) {
	    //added by bhagya S S 
	    if ((msg['chairman'] == true) && (msg['po_exist'] == false) && (msg['admin'] == false)) {
		window.location.reload();
	    } else {
		if (msg['data']['oe_pi_flag'] == 1) {
		    $(location).attr('href', base_url + 'curriculum/pi_and_measures/pi_and_measures_index/' + curriculum_id);
		} else if (msg['data']['oe_pi_flag'] == 0) {
		    $(location).attr('href', base_url + 'curriculum/course/course_index/' + curriculum_id);
		}
	    }
	}
    });
});

$("textarea#approver_po_peo_comment_box_id").bind("keyup", function () {
    po_peo_mapping_comment_notes_insert(this.value);
});

//Static PO to PEO mapping List View JS functions.		
//select the curriculum from the drop down

function static_select_curriculum() {
    document.getElementById('error').innerHTML = '';
    document.getElementById('mapping_table').innerHTML = '';
    static_fetch_po_peo_mapping_comment_notes();

    var curriculum_id = document.getElementById('curriculum_list').value;
    if (curriculum_id != 'Select Curriculum') {
	var post_data = {
	    'crclm_id': curriculum_id
	}

	$.ajax({type: "POST",
	    url: base_url + 'curriculum/map_po_peo/static_map_table',
	    data: post_data,
	    success: function (msg)
	    {
		document.getElementById('mapping_table').innerHTML = msg;
	    }
	});
    } else {
	document.getElementById('error').innerHTML = 'Select Curriculum';
    }
    //Fetching the current State of POs to PEOs Mapping.
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_po_peo_map_current_state',
	data: post_data,
	success: function (msg) {

	    document.getElementById('po_peo_map_current_state').innerHTML = msg;
	}
    });
}


function static_fetch_po_peo_mapping_comment_notes()
{
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
	'crclm_id': curriculum_id,
    }

    $.ajax({type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_txt',
	data: post_data,
	success: function (msg) {
	    if (msg != 1) {
		document.getElementById('po_peo_comment_box_id').innerHTML = msg;
	    } else if (msg == 1) {
		$('#po_peo_comment_box_id').attr('placeholder', 'Enter text here');
	    }
	}
    });
}

//function to get the comments (text) entered
$("#comment_notes_textarea").on("keyup", "#peo_po_comment_add", function () {
    var crclm_id = $('#crclm_id').val();
    var po_peo_comment = $(this).val();
    var post_data = {
	'crclm_id': crclm_id,
	'peo_po_cmt': po_peo_comment
    }

    $.ajax({
	url: base_url + 'curriculum/map_po_peo/save_peo_po_comment',
	type: "POST",
	data: post_data,
	async: true,
	success: function (data) {
	    if (!data) {
		alert("unable to save file!");
	    }
	}
    });

});

$('#send_remap_button_id').live('click', function () {
    $('#send_mapping_remap_dialog_id').modal('show');
});

function revert_mapping() {
    $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;
    var state_id = document.getElementById('state').value = 6;

    var post_data = {
	'state': state_id,
	'crclm_id': curriculum_id
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/remap',
	data: post_data,
	success: function (msg) {
	    $('#loading').hide();
	    window.location.reload();
	}
    });

}

/**********************************************************************************************************************************/


$('#uncheck_data').on('click', function () {

});
$('.check_map').live("change", function () {
    $('#loading').show();
    var id = $(this).attr('value');
    globalid = $(this).attr('id');
    window.id = id;
    var curriculum_id = document.getElementById('curriculum_list').value;
    window.curriculum_id = curriculum_id;
    var map_level_data = id.split('|');
    var map_level = map_level_data.length;
    var post_data = {
	'po': id,
	'crclm_id': curriculum_id,
    }
    if (map_level == 3) {
	$.ajax({
	    type: "POST",
	    url: base_url + 'curriculum/map_po_peo/add_mapping_new',
	    data: post_data,
	    success: function (msg) {
		$('#loading').hide();					//success_modal_update();
		var curriculum_id = document.getElementById('curriculum_list').value;
		var post_data = {
		    'crclm_id': curriculum_id
		}

		$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/map_table_new',
		    data: post_data,
		    success: function (msg) {
			document.getElementById('mapping_table').innerHTML = msg;
			$.ajax({
			    type: "POST",
			    url: base_url + 'curriculum/map_po_peo/fetch_comments',
			    data: post_data,
			    success: function (Comment) {
				document.getElementById('po_peo_comment_box').innerHTML = Comment;
			    }});
			$.ajax({
			    type: "POST",
			    url: base_url + 'curriculum/map_po_peo/disable',
			    data: post_data,
			    success: function (msg1) {
				if (msg1 != 1) {
				    $('#loading').hide();
				    // button is visible	
				    document.getElementById('send_approve_div_id').style.visibility = 'visible';
				    
				} else {
				    $('#loading').hide();
				    document.getElementById('send_approve_div_id').style.visibility = 'hidden';
				    
				}

			    }
			});
			
			$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/disable_remap',
		    data: post_data,
		    success: function (msg1) {
			if (msg1 != 1) {			
			    $('#loading').hide();
			    // button is visible	

			    document.getElementById('send_remap_div_id').style.visibility = 'hidden';    //Javascript type not working
			    $('#send_remap_div_id').hide();
			} else {
			    $('#loading').hide();
			    document.getElementById('send_remap_div_id').style.visibility = 'visible';    //Javascript type not working
			    $('#send_remap_div_id').show();
			}

		    }
		});
			
		    }
		});
	    }
	});
    } else {
	$('#loading').hide();
	$('#uncheck_mapping_dialog_id').modal('show');
    }
});
$('[data-toggle="popover"]').popover();
// Function to 	
function cancel_uncheck_mapping_dialog_new() {
    var post_data = {
	'globalid': id
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/fetch_map_data',
	data: post_data,
	success: function (msg) {
	    $('#test').val(msg);
	    //success_modal_update();
	}
    });

}

function unmapping_new() {      
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
	'po': id,
	'crclm_id': curriculum_id,
    }
    $.ajax({
	type: "POST",
	url: base_url + 'curriculum/map_po_peo/unmap_new',
	data: post_data,
	success: function (msg) {
	    $('#uncheck_mapping_dialog_id').modal('hide');//success_modal_update();

	    var curriculum_id = document.getElementById('curriculum_list').value;
	    var post_data = {
		'crclm_id': curriculum_id
	    }

	    $.ajax({
		type: "POST",
		url: base_url + 'curriculum/map_po_peo/map_table_new',
		data: post_data,
		success: function (msg) {
		    document.getElementById('mapping_table').innerHTML = msg;
		    $.ajax({
			type: "POST",
			url: base_url + 'curriculum/map_po_peo/fetch_comments',
			data: post_data,
			success: function (Comment) {
			    document.getElementById('po_peo_comment_box').innerHTML = Comment;
			}});
		    $.ajax({
			type: "POST",
			url: base_url + 'curriculum/map_po_peo/disable',
			data: post_data,
			success: function (msg1) {
			    if (msg1 != 1) {
				$('#loading').hide();
				// button is visible	
				document.getElementById('send_approve_div_id').style.visibility = 'visible';
				//document.getElementById('send_remap_div_id').style.visibility = 'hidden';
			    } else {
				$('#loading').hide();
				document.getElementById('send_approve_div_id').style.visibility = 'hidden';
				//document.getElementById('send_remap_div_id').style.visibility = 'visible';
			    }

			}
		    });
			
				$.ajax({
		    type: "POST",
		    url: base_url + 'curriculum/map_po_peo/disable_remap',
		    data: post_data,
		    success: function (msg1) {
			if (msg1 != 1) {			
			    $('#loading').hide();
			    // button is visible	

			    document.getElementById('send_remap_div_id').style.visibility = 'hidden';    //Javascript type not working
			    $('#send_remap_div_id').hide();
			} else {
			    $('#loading').hide();
			    document.getElementById('send_remap_div_id').style.visibility = 'visible';    //Javascript type not working
			    $('#send_remap_div_id').show();
			}

		    }
		});	
			
		}
	    });
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
    var artifact_value = $('#art_val').val();
    var crclm_id = $('#curriculum_list').val();
    $('#loading').show();
    if (crclm_id != 'Select Curriculum') {
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
		$('#loading').hide();
		$('#mymodal').modal('show');
	    }
	});
    } else {
	$('#loading').hide();
	$('#select_crclm').modal('show');
    }
}

//uploading the file 
$('.art_facts,#curriculum_list').on('click change', function (e) {
    var uploader = document.getElementById('uploaded_file');
    var crclm_id = $('#curriculum_list').val();
    var art = $('#art_val').val();
    var val = $(this).attr('uploaded-file');
    var folder_name = $('#curriculum_list option:selected').val();
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
		$('#loading').hide();
		display_artifact();
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
