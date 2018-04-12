//Course Learning Objectives (CLOs)
	var clo_id;
	var table_row;
	var cloneCntr = 2;
	var base_url = $('#get_base_url').val();
	var clo_counter = new Array();
		clo_counter.push(1);
	
	//Course Learning Objectives (CLOs) List Page

	$('#publish').live('click', function() {
		var curriculum_id = document.getElementById('curriculum').value;
		var term_id = document.getElementById('term').value;
		var course_id = document.getElementById('course').value;
	
		if (curriculum_id && term_id && course_id) {
			$('#myModal_publish').modal('show');
		}
	});

	//Function to fetch term details
	if($.cookie('remember_term') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
	select_term();
	}

	function select_term() {
	$.cookie('remember_term', $('#curriculum option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('curriculum').value;
		var post_data = {
			'curriculum_id': curriculum_id
		}
		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
				if($.cookie('remember_course') != null) {
				// set the option to selected that corresponds to what the cookie is set to
					$('#term option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
					select_course();
				}
			}
		});
	}

	//Function to fetch course details
	function select_course() {
		$.cookie('remember_course', $('#term option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('curriculum').value;
		var term_id = document.getElementById('term').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}
		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo/select_course',
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
				if($.cookie('remember_selected_value') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#course option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
					static_get_selected_value();
				}
			}
		});
	}

	
	// function to populate static page data.
	function static_get_selected_value() {
		$.cookie('remember_selected_value', $('#course option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('curriculum').value;
		var term_id = document.getElementById('term').value;
		var course_id = document.getElementById('course').value;
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo/static_show_clo',
			data: post_data,
			dataType: 'json',
			success: static_populate_table
		});
	}
/////////////////////////
function static_populate_table(msg) {
		var m = 'd';
		$('#example').dataTable().fnDestroy();
		$('#example').dataTable(
				{"aoColumns": [
						{"sTitle": "Course Learning Outcomes(CLOs)", "mData": "clo_statement"}
					], "aaData": msg});
	}
	//Function to generate data table grid
