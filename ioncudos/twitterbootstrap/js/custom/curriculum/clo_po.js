
	//Course Learning Objectives (CLOs) to Program Outcomes (POs) Mapping (Termwise)

	var base_url = $('#get_base_url').val();
	
	//scrolling
	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});
	
	//double horizontal scroll bar (sync)
	$(function(){
		$(".wrapper1").scroll(function(){
			$(".wrapper2")
				.scrollLeft($(".wrapper1").scrollLeft());
		});
		$(".wrapper2").scroll(function(){
			$(".wrapper1")
				.scrollLeft($(".wrapper2").scrollLeft());
		});
	});
	
	//CLO to PO static page - for Member
	//display grid on select of term
	function static_func_grid() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/static_clo_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view').innerHTML = msg;
			}
		});
	}
	
	//Function to fetch and display help details related to course learning objective to program outcomes
	function show_help() {
		$.ajax({
			url: base_url + 'curriculum/clo_po/clo_po_help',
			datatype: "JSON",
			success: function(msg) {
				document.getElementById('help_view').innerHTML = msg;
			}
		});
	}

	if($.cookie('remember_term') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
		select_term();
	}
	
	//Function to fetch term details for term dropdown
	function select_term() {
		$.cookie('remember_term', $('#crclm option:selected').val(), { expires: 90, path: '/'});
		
		var curriculum_id = document.getElementById('crclm').value;

		var post_data = {
			'curriculum_id': curriculum_id
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
				if($.cookie('remember_selected_value') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#term option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
					func_grid();
			}
			}
		});
	}

	//Function to display grid containing course and related course learning objectives and program outcomes
	function func_grid() {
		$.cookie('remember_selected_value', $('#term option:selected').val(), { expires: 90, path: '/'});
		
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		if (curriculum_id && term_id) {
			var post_data = {
				'curriculum_id': curriculum_id,
				'term_id': term_id
			}

			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/clo_details',
				data: post_data,
				success: function(msg) {
					document.getElementById('table_view').innerHTML = msg;
					text_func(); 
					display_approver();
				}
			});
		}
	}

	//Function to display textarea content for a particular curriculum and its corresponding term
	function text_func() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id
		}
	}

	//Function to display program outcome and course learning objective statement in the textarea when mouse is placed on checkbox
	function write_to_textarea(po, clo) {
		document.getElementById('text_po_view').innerHTML = po;
		document.getElementById('text_clo_view').innerHTML = clo;
	}
	
	// On change event to load pi measures
	// Author 	: Mritunjay
	// Date 	: 3/4/2014
	$('.map_level').live('change',function() {
		var id = $(this).attr('value');
		var unmap_id = $(this).find('option:selected').attr('abbr');
		var map_level_data = id.split('|');
		globalid = $(this).attr('id');
		window.id = unmap_id;

		var curriculum_id = document.getElementById('crclmid').value;
		window.curriculum_id = curriculum_id;

		var post_data = {
			'po': id,
			'curriculum_id': curriculum_id
		}

		if ($(this).val()!='') {
			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/load_pi',
				data: post_data,
				success: function(msg) {
					if (msg != 0) {
						$('#myModal_indicator_measure').modal('show');
						document.getElementById('comments').innerHTML = msg;
						$('#map_level_val').val(map_level_data[2]);
						$('#crs_id').val(map_level_data[3]);
						$('#clo_po_id').val(unmap_id);
					} else {
					// OE & PIs optional
					// commented as there is no OE & PIs - as requested by AKITC 
						// $('#oe_pi_optional').modal('show');
					}
				}
			});
		} else {
			$('#clo_po_id').val(unmap_id);
			$('#myModal_unmap').modal('show');
			document.getElementById('comments').innerHTML;
		}
});
	
	//Function to unmap course learning objective to program outcome
	function unmapping() {
		var post_data = {
			'po': id,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/unmap',
			data: post_data,
			success: function(msg) {
				$('#myModal_unmap').modal('hide');
			}
		});
	}

	/*
	 * validate performance indicator and measure form - check whether at least one checkbox of performance indicator 
	   and corresponding measure has been selected 
	 */
	function validateForm() {
		var formValid = false;
		var ckboxLength = $('input[name="cbox[]"]:checked').length;
		var rdbtnLength = $('input[name="pi[]"]:checked').length;

		if (rdbtnLength && ckboxLength) {
			formValid = true;
		}

		if (!formValid) {
			alert("Select Outcome Element(s) and Performance Indicator(s)!");
		} else {
			oncheck_save();
		}
		
		return formValid;
	}

	//On selecting performance indicator unhide the corresponding checkbox of the measure
	$(function() {
		$('#comments').on('change', '.toggle-family', function() {
			if ($(this).attr('checked')) {
				$('.pi_' + $(this).val()).removeClass('hide');
			} else {
				$('.pi_' + $(this).val()).addClass('hide');
			}
		});
	});

	//return to current state when close or cancel button is pressed - on tick
	function uncheck() {
		$('#'+ globalid).find('option:first').attr('selected', 'selected');
	}

	//return to current state when close or cancel button is pressed - on untick
	function check() {
		$('#' + globalid).prop('checked', true);
	}

	//after checking PI & its corresponding Measure(s) save the data
	function oncheck_save() {
		var curriculum_id = document.getElementById('crclm').value;
		var po_id = document.getElementById('po_id').value;
		var clo_id = document.getElementById('clo_id').value;
		var course_id = document.getElementById('crs_id').value;
		var map_level = $('#map_level_val').val();

		//performance indicator
		var vals = new Array();
		$.each($("input[name='pi[]']:checked"), function() {
			vals.push($(this).val());
		});
			
		//measure
		var values = new Array();
		$.each($("input[name='cbox[]']:checked"), function() {
			values.push($(this).val());
		});
		var post_data = {
			'curriculum_id': curriculum_id,
			'po_id': po_id,
			'clo_id': clo_id,
			'pi': vals,
			'course_id': course_id,
			'cbox': values,
			'map_level' : map_level,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/oncheck_save',
			data: post_data,
			success: function(msg) {
				//hide modal
				$('#myModal_indicator_measure').modal('hide');
			}
		});
	}

	// scan rows, to make sure that each course learning objective has at least one program outcome
	$('#scan_row_col').live('click', function(e) {
		//to prevent modal from disappearing even before any option is selected
		e.preventDefault();
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		var sected_count = new Array();
		
		//only after selecting term "send for approval" button can be accessed
		if (term_id) {
			var all_checked = true;
			var cbox_len = $('.select_verify').length;

			//redundant lines of code
			if (cbox_len == 0) {
				all_checked = false;
			}
			
			$('#table_view tr:not(:nth-child(1), .one)').each(function() {
				sected_count = [];
				$(this).removeAttr("style");
				$(this).children('td:not(:first-child)').each(function (){
				
				if(!$(this).children("select","option:selected").val() ==""){
					sected_count.push($(this).children("select","option:selected").val());
					}
					
				});
				
				if (!sected_count.length > 0) {
					$(this).css("background-color", "grey");
					all_checked = false;
				}
			});

			var post_data = {
				'curriculum_id': curriculum_id,
				'term_id': term_id
			}
			
			if (all_checked) {
				//all checked. send for approval
				var post_data = {
					'curriculum_id': curriculum_id,
				}

				$.ajax({type: "POST",
					url: base_url + 'curriculum/clo_po/bos_user_name',
					data: post_data,
					dataType: "JSON",
					success: function(msg) {
						$('#bos_user').html(msg.bos_user_name);
						$('#crclm_name_termwise').html(msg.crclm_name);
						$('#myModal_approval_confirm').modal('show');
					}
				});
			} else {
				//mapping incomplete
				$('#myModal_incomplete_mapping').modal('show');
				all_checked = true;
			}
		}
	});

	//when "send for approval" is clicked , status information is updated (for view progress)
	function approve() {
		var curriculum_id = document.getElementById('crclm').value;

		var post_data = {
			'curriculum_id': curriculum_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/approve_details',
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
						$('#crclm_name_termwise_send').html(msg.crclm_name);
						$('#myModal_approval_status').modal('show');
					}
				});
			}
		});
	}
	
	//on click of approve button, mapping complete send for approval by updating the dashboard
	function approver_dashboard_update() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;

		if (curriculum_id && term_id) {
			var post_data = {
				'curriculum_id': curriculum_id
			}

			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/bos_user_name',
				data: post_data,
				dataType: "JSON",
				success: function(msg) {
					$('#bos_user_crs_owner').html(msg.bos_user_name);
					$('#crclm_name_skip').html(msg.crclm_name);
					$('#myModal_approval_approved').modal('show');
				}
			});
		}
	}
	
	//dashboard update and finalize
	function dashboard_update_finalize() {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		$('#loading').show();
		
		if (curriculum_id && term_id) {
			var post_data = {
				'curriculum_id': curriculum_id,
				'term_id': term_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/clo_po/skip_bos_approval_accept',
				data: post_data,
				success: function(msg) {
					
					$('#myModal_approval_confirmation').modal('show');
				}
			});
		}
		location.reload();
	}

	//Function to save text
	function myAjaxFunction(value) {
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		var text = value;

		var post_data = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'text': text,
		}

		$.ajax({
			url: base_url + 'curriculum/clo_po/save_txt',
			type: "POST",
			data: post_data,
			success: function(data) {
				if (!data) {
					alert("unable to save file!");
				}
			}
		});
	}

	//Function to update dashboard once mapping process has been completed
	function dashboard_update() {
		var term_id = document.getElementById('term').value;
		var curriculum_id = document.getElementById('crclm').value;
		
		$('#loading').show();
		
		var post_data = {
			'term_id': term_id,
			'curriculum_id': curriculum_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/dashboard_data_send_approval',
			data: post_data,
			success: function(msg) {

			}
		});
		
		$('#scan_row_col').attr("disabled", true);
		window.location.href = base_url + 'curriculum/clo_po/index';
	}


	//Function to fetch details about the approver for the course learning objectives to program outcome mapping
	function display_approver() {
		var curriculum_id = document.getElementById('crclm').value;

		var post_data = {
			'curriculum_id': curriculum_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/clo_po_approver',
			data: post_data,
			success: function(msg) {
				document.getElementById('approver').innerHTML = msg;
			}
		});
		$('#approver').hide();
	}
	
	//Function to fetch selected PI and Measures
	$('#table_view').on('click','.pm',function() {
		var id_name = $(this).attr('class').split(' ')[0];
		var id_value = $('#'+id_name).val();
		var arr = id_value.split("|");
		var course_id = arr[2];
		var clo_id = arr[1];
		var po_id = arr[0];
		
		var curriculum_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		
		var post_data_pm = {
			'curriculum_id': curriculum_id,
			'term_id': term_id,
			'clo_id': clo_id,
			'po_id': po_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'curriculum/clo_po/modal_display_pm',
			data: post_data_pm,
			success: function(msg) {
				document.getElementById('selected_pm_modal').innerHTML = msg;
			}
		});
		$('#myModal_pm').modal('show');
	});

	//Function to display the grid along with curriculum id and term id
	$(document).ready(function() {
		var curriculum_id = $("#crclm_id").val();
		var term_id = $("#term_id_hidden").val();

		if (curriculum_id && term_id) {
			$("#crclm").val(curriculum_id).attr('selected', true);

			var post_data_crclm = {
				'curriculum_id': curriculum_id
			}

			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/select_term',
				data: post_data_crclm,
				success: function(msg) {
					document.getElementById('term').innerHTML = msg;
					$("#term").val(term_id).attr('selected', true);
				}
			});

			var term_id = document.getElementById('term_id_hidden').value;
			var curriculum_id = document.getElementById('crclm_id').value;

			var post_data = {
				'term_id': term_id,
				'curriculum_id': curriculum_id,
			}

			//curriculum and term with its grid
			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/clo_details',
				data: post_data,
				success: function(msg) {
					document.getElementById('table_view').innerHTML = msg;
				}
			});

			var term_id = document.getElementById('term_id_hidden').value;
			var curriculum_id = document.getElementById('crclm_id').value;

			var post_data = {
				'term_id': term_id,
				'curriculum_id': curriculum_id,
			}

			//select an approver
			$.ajax({type: "POST",
				url: base_url + 'curriculum/clo_po/clo_po_approver',
				data: post_data,
				success: function(msg) {
					document.getElementById('approver').innerHTML = msg;
				}
			});
			$('#approver').hide();

			//for fetching notes
			var post_data = {
				'term_id': term_id,
				'curriculum_id': curriculum_id,
			}
			
			if (curriculum_id && !term_id) {
				$('#scan_row_col').attr("disabled", true);
			}
		}
	});
	
	//reset when close or cancel button is pressed - on tick
	function uncheck() {
		var clo_po_id = $('#clo_po_id').val();
		var clo_po_id_array = clo_po_id.split('|');
		var po_id = clo_po_id_array[0];
		var clo_id = clo_po_id_array[1];
		var crs_id = clo_po_id_array[2];
		
		var post_data = {
			'clo_id' : clo_id,
			'po_id'  : po_id
		}
		
		$.ajax({
			type: "POST",
			url: base_url + 'curriculum/clo_po/get_map_val', 
			data: post_data,
			success: function(msg) {
				
				if(msg != 0) {
					$('select[id^="'+globalid+'"] option[value="'+po_id+'|'+clo_id+'|'+$.trim(msg)+'|'+crs_id+'"]').attr('selected', 'selected');
				} else {
					$('#'+ globalid).find('option:first').attr('selected', 'selected');
				}
			}
		});
		//$('#'+ globalid).find('option:first').attr('selected', 'selected');
	}


	//CLO to PO Script Ends Here