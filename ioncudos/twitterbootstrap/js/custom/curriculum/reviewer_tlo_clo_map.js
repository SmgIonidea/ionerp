//Course Reviewer's SLO to CLO mapping list view JS functions.

	var base_url = $('#get_base_url').val();

	//on tick of checkbox
	$('.check').live("click", function() {
	if ($(this).is(':checked')) {
		document.getElementById(this.id).checked = false;
	}
	else {
		document.getElementById(this.id).checked = true;
	}
	});

	$('#accept_review_button').live('click', function() {
		//all checked. send for approval
		var disabled1 = false;
		var classList = $('#accept_review_button').attr('class').split(/\s+/);
		$.each(classList, function(index, item) {
			if (item === 'disabled') {
				var disabled1 = true;
				return false
			}
		});
		if (disabled1 == false) {
			$('#confirmation_review_accept_dialog_window').modal('show');
		}
		//do nothing
	});

	function send()
	{
		$('#review_accept_dialog_window').modal('show');
	}

	$('#send_rework_button').live('click', function() {
		//all checked. send for approval
		var disabled = false;
		var classList = $('#send_rework_button').attr('class').split(/\s+/);
		$.each(classList, function(index, item) {
			if (item === 'disabled') {
				var disabled = true;
			}
		});
		if (disabled == false)
			$('#confirmation_mapping_send_rework_dialog_window').modal('show');
	});

	////////////////////////////////Review Accept////////////////////////////////
	function review_accept()
	{
		var data_val = document.getElementById('crclm_id').value;
		var post_data = {
			'crclm_id': data_val
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/review_accept_details',
			data: post_data,
			success: function(msg) {
			}
		});
	}

	function dashboard_update_accept()
	{
		var data_val1 = document.getElementById('topic').value;
		var data_val2 = document.getElementById('approver').value;
		var data_val3 = document.getElementById('crclm_id').value;
		var data_val4 = document.getElementById('course').value;
		var data_val5 = document.getElementById('term').value;
		var post_data = {
			'topic': data_val1,
			'approver_id': data_val2,
			'crs_id': data_val4,
			'crclm_id': data_val3,
			'term_id': data_val5
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/dashboard_data_for_review_accept',
			data: post_data,
			success: function(msg) {
			document.location = base_url + 'dashboard/dashboard';
			}
		});
	}

	///////////////////////////Review Rework////////////////////////////
	function send_rework()
	{
		var data_val = document.getElementById('crclm_id').value;
		var post_data = {
			'crclm_id': data_val,
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/rework_details',
			data: post_data,
			success: function(msg) {
				$('#confirmation_mapping_send_rework_dialog_window').modal('show');
			}
		});
	}

	function dashboard_update_rework()
	{
		var data_val1 = document.getElementById('topic').value;
		var data_val2 = document.getElementById('approver').value;
		var data_val3 = document.getElementById('crclm_id').value;
		var data_val4 = document.getElementById('course').value;
		var data_val5 = document.getElementById('term').value;
		var post_data = {
			'topic_id': data_val1,
			'receiver_id': data_val2,
			'crs_id': data_val4,
			'crclm_id': data_val3,
			'term_id': data_val5
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/dashboard_data_for_review_rework',
			data: post_data,
			success: function(msg) {
				$('#sent_rework_dialog_window').modal('show');
			}
		});
	}

	$("textarea#comment_notes_id").bind("keydown", function() {
		review_myAjaxFunction(this.value) 
	});

	function review_myAjaxFunction(value) {
		var data_val1 = document.getElementById('crclm_id').value;
		var data_val2 = document.getElementById('topic').value;
		var data_val3 = document.getElementById('term').value;
		var data_val4 = document.getElementById('comment_notes_id').value;
		var post_data = {
			'crclm_id': data_val1,
			'topic_id': data_val2,
			'term_id': data_val3,
			'text': data_val4
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tloclo_map_review/save_txt',
			data: post_data,
			success: function(data) {
				if (!data) {
					alert("unable to save file!");
				}
			}
		});
	}

	function review_fetch_comment_notes()
	{
		var data_val1 = document.getElementById('crclm_id').value;
		var data_val2 = document.getElementById('term').value;
		var data_val3 = document.getElementById('topic').value;
		var post_data = {
			'crclm_id': data_val1,
			'term_id': data_val2,
			'topic_id': data_val3
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tloclo_map_review/fetch_txt',
			data: post_data,
			success: function(msg) {

				document.getElementById('comment_notes_id').innerHTML = msg;
			}
		});
	}

	function auto_load_table_grid() {
		review_fetch_comment_notes();
		var data_val = document.getElementById('term').value;
		var data_val1 = document.getElementById('crclm_id').value;
		var data_val2 = document.getElementById('course').value;
		var data_val3 = document.getElementById('topic').value;
		var post_data = {
			'crclm_term_id': data_val,
			'crclm_id': data_val1,
			'crs_id': data_val2,
			'topic_id': data_val3
			}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tloclo_map_review/tlo_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('tlo_clo_mapping_table_grid').innerHTML = msg;
			}
		});
	}

	function onmouseover_write_clo_textarea(clo, tlo) {
		document.getElementById('clo_statement_id').innerHTML = clo;
	}
	
	$('#review_refresh_all_buttons').live('click', function() {
		$('#accept_review_button').hide();
		$('#send_rework_button').hide();
	});
	
	$('#refresh_hide').live('click', function() {
		$('#accept_review_button').hide();
		$('#send_rework_button').hide();
		document.location.href = base_url + 'dashboard/dashboard';
	});
	
	
	$(document).ready(function() {
		//Function to fetch selected PI and Measures
		$('#tlo_clo_mapping_table_grid').on('click','.pm',function() {
			var id_name = $(this).attr('class').split(' ')[0];
			var arr = id_name.split("|");
			
			var clo_id = arr[0];
			var tlo_id = arr[1];			
			
			var curriculum_id = $("#crclm_id").val();
			var term_id = $("#term").val();
			var course_id = $("#course").val();
			var topic_id = $("#topic").val();
			
			/* var curriculum_id = document.getElementById('crclm_id').value;
			var term_id = document.getElementById('term').value;
			var course_id = document.getElementById('course').value;
			var topic_id = document.getElementById('topic').value; */
			
			var post_data_pm = {
				'curriculum_id': curriculum_id,
				'term_id': term_id,
				'course_id': course_id,
				'topic_id': topic_id,
				'clo_id': clo_id,
				'tlo_id': tlo_id
			}
			
			if(curriculum_id && term_id && course_id && topic_id) {
				$.ajax({type: "POST",
					url: base_url + 'curriculum/tlo_clo_map/modal_display_pm',
					data: post_data_pm,
					success: function(msg) {
						document.getElementById('selected_pm_modal').innerHTML = msg;
					}
				});
				$('#myModal_pm').modal('show');
			} else {
				$('#error_dialog_window_for_mapping').modal('show');
			}
		});
	});
		