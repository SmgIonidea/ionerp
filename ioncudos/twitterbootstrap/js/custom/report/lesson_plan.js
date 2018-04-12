
	//Lesson Plan Report Page
	
	var base_url = $('#get_base_url').val();
	
	//to change tab - on click
	$('#myTab a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	})
	
	//############################################################################################
			//COURSE PLAN
	//############################################################################################
	//Function to fetch term details for term dropdown - Course Plan
	function select_term() {
		var curriculum_id = document.getElementById('crclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
			}
		});
	}

	//Function to fetch course details for course dropdown - Course Plan
	function select_term_course() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		var post_data = {
			'term_id': term_id,
			'curriculum_id': curriculum_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/term_course_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
			}
		});
	}
	
	//Function to display grid on select of course - Course Plan
	function select_course_course_plan() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		var course_id = document.getElementById('course').value;
	
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/course_plan_details',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view_course_plan').innerHTML = msg;
			}
		});
	}
	
	//Function to display curriculum and year - Course Plan
	function curriculum_year() {
		var curriculum_year = $('#crclm').find(":selected").text();
		document.getElementById('curriculum_year').innerHTML = curriculum_year;
	}
	
	//Function to display semester - Course Plan
	function semester() {
		var semester = $('#term').find(":selected").text();
		document.getElementById('semester').innerHTML = semester;
	}
	
	//Function to fetch prerequisite courses - Course Plan
	function prerequisites() {
		var course_id = document.getElementById('course').value;
		
		var post_data = {
			'course_id': course_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/prerequisites',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('prerequisites').innerHTML = msg;
			}
		});
	}
	
	//Function to fetch course learning objective statements - Course Plan
	function clo_statements() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		var course_id = document.getElementById('course').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/clo_details',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('clo_statements').innerHTML = msg;
			}
		});
	}
	
	//############################################################################################
			//COURSE CONTENT
	//############################################################################################
	//Function to fetch term details for term dropdown - Course Content
	function select_term_cc() {
		var curriculum_id = document.getElementById('cccrclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('ccterm').innerHTML = msg;
			}
		});
	}

	//Function to fetch course details for course dropdown - Course Content
	function select_term_course_cc() {
		var curriculum_id = document.getElementById('cccrclm').value;
		var term_id = document.getElementById('ccterm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/term_course_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('cccourse').innerHTML = msg;
			}
		});
	}
	
	//Function to display course code - Course Content
	function course_code() {
		var course_id = document.getElementById('cccourse').value;
		
		var post_data = {
			'course_id': course_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/course_code',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('course_code').innerHTML = msg;
			}
		});
		
		l_t_p();
	}
	
	//Function to fetch credits for the course (l-t-p => lecture credits, tutorial credits and practical credits) - Course Content
	function l_t_p() {
		var course_id = document.getElementById('cccourse').value;
		
		var post_data = {
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/l_t_p',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('l_t_p').innerHTML = msg;
			}
		});
		
		course_title();
	}
	
	//Function to fetch course title - Course Content
	function course_title() {
		var course_id = document.getElementById('cccourse').value;
		
		var post_data = {
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/course_title',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('course_title').innerHTML = msg;
			}
		});
		
		cie_marks();
	}
	
	//Function to fetch cie (continuous internal evaluation) marks for the course - Course Content
	function cie_marks() {
		var course_id = document.getElementById('cccourse').value;
		
		var post_data = {
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/cie_marks',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('cie_marks').innerHTML = msg;
			}
		});
		
		see_marks();
	}
	
	//Function to fetch see (semester end exam) marks for the course - Course Content
	function see_marks() {
		var course_id = document.getElementById('cccourse').value;
		
		var post_data = {
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/see_marks',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('see_marks').innerHTML = msg;
			}
		});
		
		teaching_hours();
	}
	
	//Function to fetch number of teaching hours for the course - Course Content
	function teaching_hours() {
		var course_id = document.getElementById('cccourse').value;
		
		var post_data = {
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/teaching_hours',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('teaching_hours').innerHTML = msg;
			}
		});
	}
	
	//Function to display grid on select of course for course content - Course Content
	function course_content_details() {
		var curriculum_id = document.getElementById('cccrclm').value;
		var term_id = document.getElementById('ccterm').value;
		var course_id = document.getElementById('cccourse').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/course_content_details',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view_course_content').innerHTML = msg;
			}
		});
	}
	
	//############################################################################################
				//CHAPTER WISE PLAN
	//############################################################################################
	//Function to fetch term details for term dropdown - Chapter wise Plan
	function select_term_cp() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('cpterm').innerHTML = msg;
			}
		});
	}

	//Function to fetch course details for course dropdown - Chapter wise Plan
	function select_term_course_cp() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		var term_id = document.getElementById('cpterm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/term_course_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('cpcourse').innerHTML = msg;
			}
		});
	}
	
	//Function to fetch topic details for topic dropdown - Chapter wise Plan
	function select_topic() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		var term_id = document.getElementById('cpterm').value;
		var course_id = document.getElementById('cpcourse').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/select_topic',
			data: post_data,
			success: function(msg) {
				document.getElementById('cptopic').innerHTML = msg;
			}
		});
	}
	
	//Function to fetch & display chapter wise plan details in the table
	function chapter_wise_plan_content() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		var term_id = document.getElementById('cpterm').value;
		var course_id = document.getElementById('cpcourse').value;
		var topic_id = document.getElementById('cptopic').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
			'topic_id': topic_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/chapter_wise_plan_content',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view_chapter_wise_plan').innerHTML = msg;
			}
		});
	}
	
	//Function to fetch & display chapter wise plan details in the table
	function lesson_schedule_details() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		var term_id = document.getElementById('cpterm').value;
		var course_id = document.getElementById('cpcourse').value;
		var topic_id = document.getElementById('cptopic').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
			'topic_id': topic_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/lesson_schedule_details',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('lesson_plan').innerHTML = msg;
			}
		});
	}
	//Function to fetch topic learning objective details related to each topic - Chapter wise Plan
	function tlo_details() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		var term_id = document.getElementById('cpterm').value;
		var course_id = document.getElementById('cpcourse').value;
		var topic_id = document.getElementById('cptopic').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
			'topic_id': topic_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/tlo_details',
			data: post_data,
			success: function(msg) {
			lesson_schedule_details();
			}
		});
	}
	
	//Function to fetch topic learning objective details related to each topic - Chapter wise Plan
	function review_question() {
		var curriculum_id = document.getElementById('cpcrclm').value;
		var term_id = document.getElementById('cpterm').value;
		var course_id = document.getElementById('cpcourse').value;
		var topic_id = document.getElementById('cptopic').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
			'topic_id': topic_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/review_question',
			data: post_data,
			success: function(msg) {
				$('#review_questions').html(msg);
			}
		});
	}
	
	//Function to export html page to .doc - Course Plan
	$( ".export_course_plan" ).click(function( event ) {
		var curriculum_id = $('#crclm').val();
		var term_id = $('#term').val();
		var course_id = $('#course').val();
		event.preventDefault();
		
		if (curriculum_id && term_id && course_id) {
			$(this).closest('form').submit();
		} else {
			$('#myModal_export_status_one').modal('show');
		}
	});
	
	//Function to export html page to .doc - Course Content
	$( ".export_course_content" ).click(function( event ) {
		var curriculum_id = $('#cccrclm').val();
		var term_id = $('#ccterm').val();
		var course_id = $('#cccourse').val();
		event.preventDefault();
		
		if (curriculum_id && term_id && course_id) {
			$(this).closest('form').submit();
		} else {
			$('#myModal_export_status_one').modal('show');
		}
	});
	
	//Function to export html page to .doc - Chapter wise Plan
	$( ".export_chapter_wise_plan" ).click(function( event ) {
		var curriculum_id = $('#cpcrclm').val();
		var term_id = $('#cpterm').val();
		var course_id = $('#cpcourse').val();
		var topic_id = $('#cptopic').val();
		event.preventDefault();
		
		if (curriculum_id && term_id && course_id && topic_id) {
			$(this).closest('form').submit();
		} else {
			$('#myModal_export_status_one').modal('show');
		}
	});
	
	//############################################################################################
				//Course Unitization
	//############################################################################################
	//Function to fetch term details for term dropdown - Course Unitization
	function select_term_cu() {
		var curriculum_id = document.getElementById('cucrclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('cuterm').innerHTML = msg;
			}
		});
	}

	//Function to fetch course details for course dropdown - Course Unitization
	function select_term_course_cu() {
		var curriculum_id = document.getElementById('cucrclm').value;
		var term_id = document.getElementById('cuterm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/term_course_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('cucourse').innerHTML = msg;
			}
		});
	}
	
	//Function to fetch & display chapter wise plan details in the table
	function course_unitization_content() {
		var curriculum_id = document.getElementById('cucrclm').value;
		var term_id = document.getElementById('cuterm').value;
		var course_id = document.getElementById('cucourse').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/course_unitization_content',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view_cu').innerHTML = msg;
			}
		});
	}
	
	//Function to export html page to .doc - Course Unitization
	$( ".export_course_unitization" ).click(function( event ) {
		var curriculum_id = $('#cucrclm').val();
		var term_id = $('#cuterm').val();
		var course_id = $('#cucourse').val();
		event.preventDefault();
		
		if (curriculum_id && term_id && course_id) {
			$(this).closest('form').submit();
		} else {
			$('#myModal_export_status_one').modal('show');
		}
	});
	
	
	

	//############################################################################################
				//OE and PI
	//############################################################################################
	//Function to fetch term details for term dropdown - Course Unitization
	function select_term_oe_pi_() {
		var curriculum_id = document.getElementById('oe_pi_crclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('oe_pi_term').innerHTML = msg;
			}
		});
	}

	//Function to fetch course details for course dropdown - Course Unitization
	function select_term_course_oe_pi_() {
		var curriculum_id = document.getElementById('oe_pi_crclm').value;
		var term_id = document.getElementById('oe_pi_term').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/term_course_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('oe_pi_course').innerHTML = msg;
			}
		});
	}
	
	
	//Function to fetch & display chapter wise plan details in the table
	function oe_pi_content() {
		var curriculum_id = document.getElementById('oe_pi_crclm').value;
		var term_id = document.getElementById('oe_pi_term').value;
		var course_id = document.getElementById('oe_pi_course').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/lesson_plan/oe_pi_content',
			
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view_oe_pi').innerHTML = msg;
			}
		});
	}
	
	//Function to export html page to .doc - oe & pi
	$( ".export_oe_and_pi" ).click(function( event ) {
		var curriculum_id = $('#oe_pi_crclm').val();
		var term_id = $('#oe_pi_term').val();
		var course_id = $('#oe_pi_course').val();
		event.preventDefault();
		
		if (curriculum_id && term_id && course_id) {
			$(this).closest('form').submit();
		} else {
			$('#myModal_export_status_one').modal('show');
		}
	});