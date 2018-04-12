
	//Course Learning Objectives (CLOs) to Program Outcomes (POs) Mapping (BOS - Board of Study Member)

	var base_url = $('#get_base_url').val();

	/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
	 The following code shows how you may do that:*/

	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});

	//Function to fetch term details for term dropdown
	function select_term() {
		var curriculum_id = document.getElementById('crclm').value;
		
		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url+'curriculum/clo_po_approve_comment/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
			}
		});
	}

	//Function to display grid containing course and related course learning objectives and program outcomes
	function func_grid() {
		var term_id = document.getElementById('term').value;
		var curriculum_id = document.getElementById('crclm').value;

		var post_data = {
			'term_id': term_id,
			'curriculum_id': curriculum_id,
		}

		$.ajax({type: "POST",
			url: base_url+'curriculum/clo_po_approve_comment/clo_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view').innerHTML = msg;
			}
		});
	}

	//Function to display program outcome and course learning objective statement in the textarea when mouse is placed on checkbox
	function write_to_textarea(po, clo, cmnt) {
		document.getElementById('text_po_view').innerHTML = po;
		document.getElementById('text_clo_view').innerHTML = clo;
	}

	//Function to fetch comments
	function comments(po, clo) {
		var i = 0;
		var post_data = {
			'po_id': po,
			'clo_id': clo,
		}

		$.ajax({type: "POST",
			url: base_url+'curriculum/clo_po_approve_comment/clo_po_comments',
			data: post_data,
			success: function(msg) {
				if (msg != 0) {
					$('#write_comment').html(msg);
				} else {
					$('#write_comment').html("No Comments");
				}

			}
		});
	}

	//Onclick of each checkbox in the grid a pop up appears containing performance indicators and corresponding measures
	$('.check').live("click", function() {
		var po_id = $(this).attr('value');
		var curriculum_id = document.getElementById('crclmid').value;
		
		var post_data = {
			'po': po_id,
			'curriculum_id': curriculum_id,
		}

		if ($(this).is(":checked")) {
			$.ajax({type: "POST",
				url: base_url+'curriculum/clo_po_approve_comment/load_pi',
				data: post_data,
				success: function(msg) {

				}
			});
		}
	});

	//Function to display the grid along with curriculum id and term id
	$(document).ready(function() {
		//to add comments
		$('.comment').live('click', function() {

			var comment_map_val = $(this).attr('abbr');
			var comment_array = comment_map_val.split('|');
			var po_id = comment_array[0];
			var clo_id = comment_array[1];
			var crclm_id = comment_array[2];
			var co_po_id = comment_array[3];
			var clo_po_id = $('#clo_po_id').val();
			var post_data = {
				'po_id': po_id,
				'clo_id': clo_id,
				'crclm_id': crclm_id,
				'clo_po_id':co_po_id
			}

			$.ajax({type: "POST",
				url: base_url + 'curriculum/clopomap_review/co_po_mapping_comment',
				data: post_data,
				dataType: 'JSON',
				success: function(msg) {
					if(msg.length > 0) {
						$('#clo_po_cmt').text(msg[0].cmt_statement);
					} else {
						$('#clo_po_cmt').text('');
					}
				}
			});
			
			$('a[rel=popover]').not(this).popover('destroy');
			$('a[rel=popover]').popover({
				html: 'true',
				trigger: 'manual',
				placement: 'left'
			});
		
			$(this).popover('show');
			
			$('.close_btn').live('click', function() {
				$('a[rel=popover]').not(this).popover('destroy');
			});
		});
		

		

		
		
		
		

		//to insert & submit comment
		$('.cmt_submit').live('click', function() {
			$('a[rel=popover]').not(this).popover('hide');
			var po_id = document.getElementById('po_id').value;
			var clo_id = document.getElementById('clo_id').value;
			var curriculum_id = document.getElementById('crclm_id').value;
			var clo_po_comment = document.getElementById('clo_po_cmt').value;
			
			var post_data = {
				'po_id': po_id,
				'clo_id': clo_id,
				'curriculum_id': curriculum_id,
				'clo_po_comment': clo_po_comment,
			}
			
			if(clo_po_comment != ''){
				$.ajax({type: "POST",
					url: base_url+'curriculum/clo_po_approve_comment/clo_po_comment_insert',
					data: post_data,
					success: function(msg) {
					}
				});
			}
		});
	});

	//on click of approve button, mapping complete send for approval by updating the dashboard
	$('#bos_approval_accept').live('click', function(e) {
		e.preventDefault();
		
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		if (curriculum_id && term_id) {
			var post_data = {
				'curriculum_id': curriculum_id,
			}

			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/bos_user_name',
				data: post_data,
				dataType: "JSON",
				success: function(msg) {
					$('#bos_user_approve').html(msg.bos_user_name);
					$('#crclm_name_co_po_approve').html(msg.crclm_name);
					$('#myModal_approval_approved').modal('show');
				}
			});
		}
	});

	//dashboard update and finalize
	function dashboard_update_finalize() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		$('#loading').show();
		
		if (term_id) {
			var post_data = {
				'curriculum_id': curriculum_id,
				'term_id': term_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/clo_po_approve_comment/bos_approval_accept',
				data: post_data,
				success: function(msg) {
					var post_data = {
						'curriculum_id': curriculum_id,
					}

					$.ajax({type: "POST",
						url: base_url + 'curriculum/clo_po/bos_user_name',
						data: post_data,
						dataType: "JSON",
						success: function(msg) {
							$('#bos_user_approve').html(msg.bos_user_name);
							$('#crclm_name_co_po_approve').html(msg.crclm_name);
							$('#myModal_approval_confirmation').modal('show');
						}
					});
				}
			});
		}
		
		//location.reload();
	}
	
	$('.ok_approve').on('click',function() {
		window.location.replace(base_url+'dashboard/dashboard');
	});

	//function to show rework modal
	var crs_id;
	$("#table_view").on('click','.rework_btn', function(e) {
		e.preventDefault();
		crs_id = $(this).parent().siblings('input[type=hidden]').val();
		
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;

		if (term_id) {
			var post_data = {
				'curriculum_id': curriculum_id,
				'crs_id': crs_id
			}

			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po_approve_comment/crs_owner_details',
				data: post_data,
				dataType: "JSON",
				success: function(msg) {
					$('#crs_owner_user').html(msg.crs_owner_user_name);
					$('#crclm_name_co_po').html(msg.crclm_name);
					$('#myModal_rework').modal('show');
				}
			});
		}
	});

	//function to update dashboard on rework
	$('#finalize_rework').on('click', function(e) {
		e.preventDefault();
		
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;	
		
		$('#loading').show();
		
		if (curriculum_id && term_id && crs_id) {
			var post_data = {
				'curriculum_id': curriculum_id,
				'term_id': term_id,
				'crs_id': crs_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/clo_po_approve_comment/dashboard_rework',
				data: post_data,
				success: function(msg) {
					location.reload();
				}
			});
		}
	});
	
	//Function to fetch selected PI and Measures
	$('#table_view').on('click','.pm',function() {
		var id_name = $(this).attr('class').split(' ')[0];
		var arr = id_name.split("|");
		var course_id = arr[2];
		var clo_id = arr[1];
		var po_id = arr[0];
		
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		var post_data_pm = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'course_id': course_id,
			'clo_id': clo_id,
			'po_id': po_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po_approve_comment/modal_display_pm',
			data: post_data_pm,
			success: function(msg) {
				document.getElementById('selected_pm_modal').innerHTML = msg;
			}
		});
		$('#myModal_pm').modal('show');
	});
	
	//Function to display the grid along with curriculum id and term id
	$(document).ready(function() {
		var curriculum_id = $("#curriculum_id").val();
		var term_id = $("#term_id_hidden").val();

		if (curriculum_id && term_id) {
			$("#crclm").val(curriculum_id).attr('selected', true);

			var post_data_crclm = {
				'curriculum_id': curriculum_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/clo_po_approve_comment/select_term',
				data: post_data_crclm,
				success: function(msg) {
					document.getElementById('term').innerHTML = msg;
					$("#term").val(term_id).attr('selected', true);
				}
			});

			var term_id = document.getElementById('term_id_hidden').value;
			var curriculum_id = document.getElementById('curriculum_id').value;

			var post_data = {
				'term_id': term_id,
				'curriculum_id': curriculum_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/clo_po_approve_comment/clo_details',
				data: post_data,
				success: function(msg) {
					document.getElementById('table_view').innerHTML = msg;
				}
			});
		}
	});