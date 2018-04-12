	
	//Course Delivery Report

	var base_url = $('#get_base_url').val();

	//set cookie
    	if($.cookie('remember_curriculum') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
		fetch_term();
    	}
	
	//Function to fetch term details for term dropdown
	function fetch_term() {
		$.cookie('remember_curriculum', $('#crclm option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('crclm').value;

		$('#loading').show();
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lab_course_delivery_report/fetch_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
				
				if($.cookie('remember_term') != null) {
						$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
						fetch_lab_course();
				}
				$('#loading').hide();
			}
		});
	}
	
	//Function to fetch course details
	function fetch_lab_course() {
		$.cookie('remember_term', $('#term option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		$('#loading').show();
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lab_course_delivery_report/fetch_lab_course',
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
				if($.cookie('remember_lab_course') != null) {
					$('#course option[value="' + $.cookie('remember_lab_course') + '"]').prop('selected', true);
					fetch_all_details();
				}
				$('#loading').hide();
			}
		});
	}
	
	//Function to fetch grid and all other details on selecting course dropdown
	function fetch_all_details() {
		$.cookie('remember_lab_course', $('#course option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		var course_id = document.getElementById('course').value;
		
		if (!course_id) {
			$("a#export").attr("href", "#");
		} else {
			$("a#export").attr("onclick", "generate_pdf();");
		}

		$('#loading').show();
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}
		if (course_id) {
			$.ajax({type: "POST",
				url: base_url + 'report/lab_course_delivery_report/fetch_lab_course_plan',
				data: post_data,
				dataType: 'json',
				success: function(msg) {
					//to display data in the course delivery lesson plan grid
					$('.expt_table').html(msg['d1']);
					$('#loading').hide();
				}
			});
		}else{
			$('.expt_table').html("");
			$('#loading').hide();
		}
	}
	
	//generate pdf
	function generate_pdf() {
		var cloned = $('#gene_table').clone().html();
		$('#pdf').val(cloned);
		$('#lab_form').submit();
	}
