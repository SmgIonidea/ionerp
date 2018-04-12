//Course Owner's SLO to CLO mapping list view JS functions.


	
	$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
	}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");
	
	$.validator.addMethod("sel", function(value, element) {
	    return !(this.optional(element) || /[0]/i.test(value));
	}, "This field is required.");
	
	function select_term() {
		document.getElementById('comment_notes_id').innerHTML = '';		
		var data_val = document.getElementById('crclm').value;
		var post_data = {
			'crclm_id': data_val
		}
		
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_term',
			data: post_data,
			success: function(msg) {
					
				document.getElementById('term').innerHTML = msg;
			
				var term=$('#term').val();
			
				if(term==''){var course='<option value="">Select Course</option>';document.getElementById('course').innerHTML = course;
				document.getElementById('topic').innerHTML = course;}
				var topic=$('#topic').val();if(topic==""){func_grid();}
			}
		});
	}

	function select_course()
	{
		document.getElementById('comment_notes_id').innerHTML = '';
		var data_val1 = document.getElementById('term').value;
		var post_data = {
			'term_id': data_val1
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_course',
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
			}
		});
	}

	function select_topic()
	{
		document.getElementById('comment_notes_id').innerHTML = '';
		var data_val = document.getElementById('course').value;
		var post_data = {
			'crs_id': data_val
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_topic',
			data: post_data,
			success: function(msg) {
				document.getElementById('topic').innerHTML = msg;
			}
		});
	}

	function func_grid()
	{

		fetch_comment_notes();
		var term_val = document.getElementById('term').value;
		var crclm_val = document.getElementById('crclm').value;
		var course_val = document.getElementById('course').value;
		var topic_val = document.getElementById('topic').value;
		
				if(topic_val==""){  document.getElementById('justification').style.visibility = 'hidden';}else{
				document.getElementById('justification').style.visibility = 'visible';
				}
		var post_data = {
			'crclm_term_id': term_val,
			'crclm_id': crclm_val,
			'crs_id': course_val,
			'topic_id': topic_val
		}


		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/tlo_details',
			data: post_data,
			success: function(msg) {		
				document.getElementById('tlo_clo_mapping_table_grid').innerHTML = msg;
				onchange_grid();
				display_reviewer();
		
			}
		});
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
	
		$('.save_justification').live('click', function (e) {
			 e.preventDefault();
	 var comment_map_val = $(this).attr('abbr');
	 var comment_array = comment_map_val.split('|'); 
		var crclm_id = $('#crclm_id').val(); 
		var tlo_map_id = $('#tlo_map_id').val(); 
			
		var justification =$('#justification').val(); 
		var post_data ={'tlo_map_id':tlo_map_id , 'crclm_id':crclm_id , 'justification':justification}
		    $.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_clo_map/save_justification',
            data: post_data,
            success: function (msg) {
               $('a[rel=popover]').not(this).popover('destroy');
			//   $('a[rel=popover]').not(this).popover('toggle');
            }
        });
	});
	
   	 $('.comment_just').live('click', function(e) {
	 e.preventDefault();
	 var comment_map_val = $(this).attr('abbr'); 
	 var comment_array = comment_map_val.split('|'); 
	 var tlo_id = comment_array[0];
	 var clo_id = comment_array[1];
	 var curriculum_id = comment_array[2];
	 var tlo_map_id = comment_array[3];
	 
	  var post_data = {
            'tlo_id': tlo_id,
            'clo_id': clo_id,
            'curriculum_id': curriculum_id,
			'tlo_map_id':tlo_map_id,
            }	
			$.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_clo_map/tlo_co_mapping_justification',
            data: post_data,
			dataType: 'JSON',
            success: function(msg) {
			if(msg.length > 0){
				if(msg[0].justification == null){$('#justification').text("");}else{$('#justification').text(msg[0].justification);}
			}else{
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
        $('.close_btn').live('click', function() {
            $('a[rel=popover]').not(this).popover('destroy');
        });
    });	
	
		
$('.save_justification').live('click', function () {
		
		var comment_map_val = $(this).attr('abbr'); 
		var comment_array = comment_map_val.split('|'); 
		var po_id = comment_array[0];
		var clo_id = comment_array[1];
		var crclm_id = comment_array[2];
		var tlo_map_id = comment_array[3];
		var justification =$('#justification').val(); 
	 
		var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
			'tlo_map_id':tlo_map_id,
			'justification':justification,
            }

		    $.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_clo_map/save_justification',
            data: post_data,
            success: function (msg) {
               $('a[rel=popover]').not(this).popover('destroy');
			//   $('a[rel=popover]').not(this).popover('toggle');
            }
        }); 
	});
});

$("#test img").attr("height", "");
	function onmouseover_write_clo_textarea(clo, tlo) {
		$('#clo_statement_id').html(clo);
	} 

	// SLO to CLO mapping check-box save operation 
	$( '.check').live('click', function() {
		$('#loading').show();
		var id = $(this).attr('value');
			
		var cross_id = $(this).attr('id'); 
		var tlo_clo_id = id.split('|');
		var clo_id = tlo_clo_id[0];
		var tlo_id = tlo_clo_id[1];
		var crclm_id = $('#crclm').val();
		
		var term_id = $('#term').val();
		var course_id = $('#course').val();
		var topic_id = $('#topic').val();
		
		var post_data = {
        	'clo_id': clo_id,
        	'tlo_id': tlo_id,
			'term_id' : term_id,
			'course_id' : course_id,
        	'crclm_id': crclm_id,
        	'topic_id': topic_id,
		} 
		if(crclm_id != '' && term_id !='' && course_id !='' && topic_id !=''){
		$.ajax({type: "POST",
            url: base_url + 'curriculum/tlo_clo_map/load_oe_pi', 
            data: post_data,
            success: function(msg) {
				if (msg != 0) {
						document.getElementById('OE_PI').innerHTML = msg;
						$('#checkbox_all_checked').modal('show');
						$('#cross_sec_val').val(cross_id);
						$('#tlo_id_val').val(tlo_id);
						document.getElementById('comment_popup').style.visibility = 'visible';
						$('#loading').hide();	
					} else {
						var term_val = document.getElementById('term').value;
						var crclm_val = document.getElementById('crclm').value;
						var course_val = document.getElementById('course').value;
						var topic_val = document.getElementById('topic').value;
							var post_data = {
							'crclm_term_id': term_val,
							'crclm_id': crclm_val,
							'crs_id': course_val,
							'topic_id': topic_val
						}
						$('#loading').hide();

						$.ajax({type: "POST",
							url: base_url+'curriculum/tlo_clo_map/tlo_details',
							data: post_data,
							success: function(msg) {			
								document.getElementById('tlo_clo_mapping_table_grid').innerHTML = msg;
								onchange_grid();
								display_reviewer();
						
							}
						});
					}
            }
        });
		
		}else{
		
			$('#error_dialog_window_for_mapping').modal('show');
			$('#error_dialog').val(cross_id);
			$('#loading').hide();
		}
		 $('#loading').hide();
		
	});
	
	$(function() {
		$('#OE_PI').on('change', '.toggle-family', function() {
			var oeboxLength = $('input[name="oebox[]"]:checked').length;
			var oebox_val = $(this).val();
			$('#check_box_val').val(oebox_val);
				if(oeboxLength > 1)
				{
				$('#oe_modal_warning').modal('show');
				}else{
			if ($(this).attr('checked')) {
				$('.pi_' + $(this).val()).removeClass('hide');
			} else {
				$('.pi_' + $(this).val()).addClass('hide');
			}
			}
		});
	});
	$('#oe_warning').on('click', '.btn_ok', function() {
			var checked_val = $('#check_box_val').val();
			$('.pi_' + checked_val).removeClass('hide');
		});
	$('#oe_warning').on('click', '.btn_close', function() {
			var checked_val = $('#check_box_val').val();
			$('.oe_map_' + checked_val).prop('checked', false);
		});
	function delete_modal(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

function warning_modal(msg) {
    var data_options = '{"text":"Select Outcome Element and its PIs!","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
	//validate pi msr form - whether related check boxes are selected for that radio button
function validateForm() {

    var formValid = false;
    var piboxLength = $('input[name="pibox[]"]:checked').length;
    var oeboxLength = $('input[name="oebox[]"]:checked').length;
    
	if (oeboxLength && piboxLength)
        formValid = true;
    if (!formValid){
        //alert("Select Outcome Element and its PIs!");
		warning_modal();
	} else {

        map_insert();
			
	}
    return formValid;
}

function map_insert()
{
    var clo_id 		= $('#clo_id').val();
    var tlo_id 		= $('#tlo_id').val();
    var crclm_id 	= $('#crclm').val();
    var course_id 	= $('#course').val();
    var topic_id 	= $('#topic').val();
    var oe_id 		= $('#oebox').val();
    var oebx 		= $('input[name="oebox[]"]:checked');
	
    var oe_val = new Array();
    $.each($("input[name='oebox[]']:checked"), function() {
        oe_val.push($(this).val()); 
		});
   
    var pibox = $('input[name="pibox[]"]:checked');
    var array_values = new Array();
    $.each($("input[name='pibox[]']:checked"), function() {
        array_values.push($(this).val());
    });
	
    var post_data = {
        'course_id': course_id,
        'crclm_id': crclm_id,
        'topic_id': topic_id,
        'tlo_id': tlo_id,
        'clo_id': clo_id,
        'oebox': oe_val,
        'pibox': array_values
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/tlo_clo_map/oe_pi_oncheck_save', 
        data: post_data,
        success: function(msg) {		
		$('#checkbox_all_checked').modal('hide');
		
		var term_val = document.getElementById('term').value;
		var crclm_val = document.getElementById('crclm').value;
		var course_val = document.getElementById('course').value;
		var topic_val = document.getElementById('topic').value;
			var post_data = {
			'crclm_term_id': term_val,
			'crclm_id': crclm_val,
			'crs_id': course_val,
			'topic_id': topic_val
		}

		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/tlo_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('tlo_clo_mapping_table_grid').innerHTML = msg;
				onchange_grid();
				display_reviewer();
		
			}
		});
		
		
		
		
		
		
        }
    });
	
	
	
	
	
	
}
	function uncheck() {
		var cross_sec_val = $('#cross_sec_val').val();
		var tlo_id = $('#tlo_id_val').val();
		var post_data = {
				'tlo_id': tlo_id,
			}
			$.ajax({type: "POST",
					url: base_url+'curriculum/tlo_clo_map/get_clo',
					data: post_data,
					success: function(msg) {
					console.log(msg);
					var string_val = $.trim(msg)+$.trim(tlo_id);
					var cross_id = $.trim('#'+string_val);
					console.log(string_val);
					console.log(cross_id);
					if(msg != 0){
						$(cross_id).prop('checked', true);
					}else{
						$('#'+cross_sec_val).prop('checked', false);
					} 
					}
				});
	}

	function check() {
	}

	// scan row for check
	$('#send_review_button').live('click', function() {

		var data_val = document.getElementById('topic').value;
		var crs_id = document.getElementById('course').value;
		var data_val2 = document.getElementById('crclm').value;
		var topic_id=$('#topic').val();
		$('#loading').show();
		var post_data = {
			'crclm_id': data_val2,
			'crs_id': crs_id
				}
		if (data_val2) {
			var all_checked = true;
			var cbox_len = $(":radio").length;
			
			if (cbox_len == 0)
				all_checked = false;
				
			$('#tlo_clo_mapping_table_grid tr:not(:nth-child(1), .one)').each(function() {
				$(this).removeAttr("style");
				if (!$(this).find('input:checked').length > 0)
				{
					$(this).css("background-color", "grey");
					all_checked = false;
				}
			});
			
			if (all_checked == true) {
				//entire mapping are checked, send for approval, fetch curriculum & course reviewer details 
				
				$.ajax({type: "POST",
					url: base_url+'curriculum/tlo_clo_map/fetch_course_reviewer',
					data: post_data,
					dataType: 'JSON',
					success: function(result){
						$('#course_viewer_name').html(result.course_viewer_name);
						$('#curriculum_name').html(result.crclm_name);
						$('#confirmation_mapping_send_review_dialog_window').modal('show');
						$('#loading').hide();
					}
				});	
				$.ajax({type: "POST",
					url: base_url+'curriculum/tlo_clo_map/update_topic',
					data: {'topic_id':topic_id},
					dataType: 'JSON',
					success: function(result){					
					}
				});
				
			} else {
				//mapping incomplete
				$('#failure_mapping_send_review_dialog_window').modal('show');
				all_checked = true;
				$('#loading').hide();
			}
		} else {
			$('#error_dialog_window').modal('show');
			$('#loading').hide();
		}
	});

	function unmapping() {
		var post_data = {
			'tlo': id
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/unmap',
			data: post_data,
		});
	}
	
	function error_uncheck(){
		var check_val = $('#error_dialog').val();
		$('#'+check_val).prop('checked', false);
	}

	//display Reviewer
	function display_reviewer()
	{
		var data_val1 = $('#course').val();
		var post_data = {
			'course_id': data_val1
		}
		
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/tlo_reviewer',
			data: post_data,
			success: function(msg) {
				document.getElementById('reviewer').innerHTML = msg;
			}
		});

		$('#reviewer').hide();
	}

	function send_review()
	{
		var data_val1 = $('#topic').val();
		var data_val2 = $('#reviewer_id').val();
		var data_val3 = $('#crclm').val();
		var data_val4 = $('#course').val();
		var data_val5 = $('#term').val();
		var post_data = {
			'topic_id': data_val1,
			'receiver_id': data_val2,
			'crclm_id': data_val3,
			'crs_id': data_val4,
			'term_id': data_val5
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tloclo_map_review/dashboard_data',
			data: post_data,
			success: function(msg) {
			}
		});
	}

	//when you click send for apporval status information is stored (for view progress)
	function review()
	{
		var data_val = document.getElementById('crclm').value;
		var crs_id = document.getElementById('course').value;
		var post_data = {
					'crclm_id': data_val,
					'crs_id': crs_id
				}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/review_details',
			data: post_data,
			dataType : "JSON",
			success: function(msg) {
				console.log(msg.course_reviewer_name);
				$('#course_reviewer_name').html(msg.course_reviewer_name);
				$('#curriculum_name_review').html(msg.crclm_name);
			}
		});
		$('#sent_review_dialog_window').modal('show');
	}

	//auto save textarea content
	$("textarea#comment_notes_id").bind("keyup", function() {
		myAjaxFunction(this.value) //the same as myAjaxFunction($("textarea#comment_notes_id").val())
	});

	function myAjaxFunction(value) {
		var data_val1 = document.getElementById('crclm').value;
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
			url: base_url+'curriculum/tlo_clo_map/save_txt',
			data: post_data,
			success: function(data) {
				if (!data) {
					alert("Error !!! Unable to Save file");
				}
				else
				{
					fetch_comment_notes();
				}
			}
		});
	}

	function fetch_comment_notes()
	{
		var data_val1 = document.getElementById('crclm').value;
		var data_val2 =$('#term').val();
		var data_val3 = document.getElementById('topic').value;
	
		var post_data = {
			'crclm_id': data_val1,
			'term_id': data_val2,
			'topic_id': data_val3
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/fetch_txt',
			data: post_data,
			success: function(msg) {
				document.getElementById('comment_notes_id').innerHTML = msg;
				$('#comment_notes_id').val(msg);
			}
		});
	}

	$(document).ready(function() {
		//Function to fetch selected PI and Measures
		$('#tlo_clo_mapping_table_grid').on('click','.pm',function() {
			var id_name = $(this).attr('class').split(' ')[0];
			var arr = id_name.split("|");
			
			var clo_id = arr[0];
			var tlo_id = arr[1];			
			
			var curriculum_id = document.getElementById('crclm').value;
			var term_id = document.getElementById('term').value;
			var course_id = document.getElementById('course').value;
			var topic_id = document.getElementById('topic').value;
			$('#loading').show();
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
				$('#loading').hide();
			} else {
				$('#error_dialog_window_for_mapping').modal('show');
				$('#loading').hide();
			}
		});
	
		var crclm_id = $("#crclm_id").val();
		var term_id = $("#term_id_hidden").val();
		var course_id = $("#course_id_hidden").val();
		var topic_id = $("#topic_id_hidden").val();
		
		$("#crclm").val(crclm_id).attr('selected', true);
		var post_data_crclm = {
				'crclm_id': crclm_id
			}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_term',
			data: post_data_crclm,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
				$("#term").val(term_id).attr('selected', true);
			}
		});

		var post_data_term = {
				'term_id': term_id
			}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_course',
			data: post_data_term,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
				$("#course").val(course_id).attr('selected', true);
			}
		});
		var post_data_course = {
				'crs_id': course_id
			}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_topic',
			data: post_data_course,
			success: function(msg) {
				document.getElementById('topic').innerHTML = msg;
				$("#topic").val(topic_id).attr('selected', true);
			}
		});
		
		//	fetch_comment_notes();
		var data_val = document.getElementById('term_id_hidden').value;
		var data_val1 = document.getElementById('crclm_id').value;
		var data_val2 = document.getElementById('course_id_hidden').value;
		var data_val3 = document.getElementById('topic_id_hidden').value;
		var post_data = {
				'crclm_term_id': data_val,
				'crclm_id': data_val1,
				'crs_id': data_val2,
				'topic_id': data_val3
			}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/tlo_details_url',
			data: post_data,
			success: function(msg) {
				document.getElementById('tlo_clo_mapping_table_grid').innerHTML = msg;
			}
		});
		var data_val1 = document.getElementById('course_id_hidden').value;
		var post_data = {
			'course_id': data_val1
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/tlo_reviewer',
			data: post_data,
			success: function(msg) {
				document.getElementById('reviewer').innerHTML = msg;
			}
		});
		$('#reviewer').hide();
	});
 
	function onchange_grid()
	{
/* 		if ($('.check').attr('disabled')) {
			$('#send_review_button').attr("disabled", true);
		}
		else {
			$('#send_review_button').attr("disabled", false);
		} */
	}

	$('#refresh').live('click', function() {
			window.location = base_url + 'curriculum/tlo_clo_map/map_tlo_clo';
		$('#send_review_button').hide();
	});

	
// SLO to CLO mapping Static View JS function
	
	
	function static_select_term()
	{
		var data_val = document.getElementById('crclm').value;
		var post_data = {
			'crclm_id': data_val
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
			}
		});
	}

	function static_select_course()
	{
		var data_val1 = document.getElementById('term').value;
		var post_data = {
			'term_id': data_val1
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_course',
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
			}
		});
	}

	function static_select_topic()
	{
		var data_val = document.getElementById('course').value;
		var post_data = {
			'crs_id': data_val
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/select_topic',
			data: post_data,
			success: function(msg) {
				document.getElementById('topic').innerHTML = msg;
			}
		});
	}

	function static_func_grid()
	{
		var data_val = document.getElementById('term').value;
		var data_val1 = document.getElementById('crclm').value;
		var data_val2 = document.getElementById('course').value;
		var data_val3 = document.getElementById('topic').value;
		var post_data = {
			'crclm_term_id': data_val,
			'crclm_id': data_val1,
			'crs_id': data_val2,
			'topic_id': data_val3,
		}
		$.ajax({type: "POST",
			url: base_url+'curriculum/tlo_clo_map/static_tlo_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('table1').innerHTML = msg;
				if ($('#checkdisabled').length > 0)
					$('#test123').attr('disabled', 'disabled').addClass('disabled');
			}
		});
	}
	
	//To fetch help content related to curriculum
   $('.show_help').live('click',function(){
        $.ajax({
			url: base_url+'curriculum/tlo_clo_map/tlo_clo_map_help',
			datatype: "JSON",
			success: function(msg) {
				$('#help_content').html(msg);
			}
		});
    });
	
		function dashboard_update_accept()
	{
		var data_val1 = document.getElementById('topic').value;
		var data_val3 = document.getElementById('crclm').value;
		var data_val4 = document.getElementById('course').value;
		var data_val5 = document.getElementById('term').value;
		var post_data = {
			'topic': data_val1,
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
	
	$('#tlo_clo_mapping_table_grid').on('click','.edit_tlo_statement',function(){
	
		$('#edit_tlo_statement_view').empty();
		var tlo_id = $(this).attr('id');
	
		var tlo_statement = $(this).attr('value');
		var bloom_id = $(this).attr('name');
		$('#bloom_level_val').val(bloom_id);
		$('#loading').show();
		var post_data = {
				'bloom_id' : bloom_id
			}
		$.ajax({type: 'POST',
				url: base_url + 'curriculum/tlo_clo_map/getSelectedBloomsLevel',
				data: post_data,
				success: function(bloom_lvl_data) {	
			
			tinymce.remove();	
				tiny_init();
				tinyMCE.activeEditor.focus();
					tinyMCE.get('updated_tlo_statement').setContent(tlo_statement);
					//$("#blooms_level").select("clearSelection");
					$('#blooms_level').find('option').remove()
					$("#blooms_level").append(bloom_lvl_data);
					$("#tlo_id").val(tlo_id);
					$.ajax({type: 'POST',
							url: base_url + 'curriculum/tlo_clo_map/getBloomsLevelActionVerb',
							data: post_data,
							success: function(action_verb) {
							$('#bloom_actionverbs_edit').html(action_verb);
							/* 					
								$('#edit_tlo_statement_view').append('<div class="row-span"><div class="span12"><div class="span4"></div><div class="span8"><div id="bloom_actionverbs">'+action_verb+'</div></div>');  */
							} 
					});						
				}
		});				
		$('#edit_tlo_statement').modal('show');
		$('#loading').hide();
	});	
	//    $('#test').html('height');	
	$('#edit_tlo_statement_view_form').on('change','#blooms_level',function(){

		var bloom_id = $(this).attr('value');
		$('#bloom_level_val').val(bloom_id);
		var post_data = {
			'bloom_id': bloom_id
		}
		$.ajax({type: 'POST',
				url: base_url + 'curriculum/tlo_clo_map/getBloomsLevelActionVerb',
				data: post_data,
				success: function(action_verb) {
					$('#bloom_actionverbs_edit').html(action_verb); 
				} 
		});
	});
	
	$("#edit_tlo_statement_view_form").validate({
		rules: {
			updated_tlo_statement: {
				loginRegex: true
			},
		},
		errorClass: "help-inline font_color",
		errorElement: "span",
		highlight: function(element, errorClass, validClass) {
			$(element).parent().addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parent().removeClass('error');
			$(element).parent().addClass('success');
		}
    });
	
	$(document).on('click','.update_tlo_statement_btn',function(e){
		e.preventDefault();
		 tinyMCE.triggerSave();
		var flag = $('#edit_tlo_statement_view_form').valid();
		if(flag){
		var dataval = $('.updated_tlo_statement').val();

		var str1=dataval.replace("<p>", " ");
		var str2=str1.replace("</p>"," "); 
		var res= str2.replace('alt=""', 'alt="image"');
		var updated_tlo_statement=res; 
		var tlo_id = $('#tlo_id').val();
		var bloom_id = $('#bloom_level_val').attr('value');	
		
			var update_data = {
				'tlo_id':tlo_id,
				'tlo_statement': updated_tlo_statement,
				'bloom_id': bloom_id
			}	
			$.ajax({type: "POST",
				url: base_url + 'curriculum/tlo_clo_map/update_tlo_statement',
				data: update_data,
				datatype: "JSON",
				success: function(msg) {
				if(msg==1){$('#edit_tlo_statement').modal('hide');success_modal(msg);}
				else{fail_modal(msg);}
					func_grid();
				}
			});
		}
	});
	
	$(document).on('click','.add_more_tlo_btn',function(){
		var crclm = $('#crclm').val();
		var term = $('#term').val();
		var course = $('#course').val();
		var topic = $('#topic').val();
		$('#loading').show();
		if( crclm == '' || term == '' || course == '' || topic == '')	{
			$('#error_dialog_window').modal('show');
			$('#loading').hide();
		} else {
			
			$.ajax({
				url: base_url + 'curriculum/tlo_clo_map/getBloomsLevel',
				success: function(bloom_lvl_data) {					
				tinymce.remove();	
				tiny_init();
				tinyMCE.activeEditor.focus();
				$('#blooms_level_add').find('option').remove()
				tinyMCE.get('add_tlo_statement').setContent('');
				$('#bloom_actionverbs').val(" ");
				$('#blooms_level_add').append(bloom_lvl_data);
			 	 	/*$('#add_tlo_statement_view').html('<div class="row-span"><div class="span12"><div class="span4">'+entity_tlo+' Statement <font color="red">*</font>:</div><div class="span8"> <textarea name="add_tlo_statement" id="add_tlo_statement" style="width:90%;" class="required  add_tlo_statement" value="" autofocus="autofocus"></textarea></div></div></div><br><br><br><div class="row-span"><div class="span12"><div class="span4">Bloom\'s Level <font color="red">*</font>: </div><div class="span8"> <select name="blooms_level" id="blooms_level" class="sel input-medium" >'+bloom_lvl_data+'</select></div></div></div><br><div class="row-span"><div class="span12"><div class="span4"></div><div class="span8"><div id="bloom_actionverbs">Note : Select Bloom\'s Level to view its respective Action Verbs</div></div>');  */				
				} 
			});
			$('#add_more_tlo_div').modal('show');
			$('#loading').hide();
		}
	});

	$('#add_tlo_statement_view').on('change','#blooms_level_add',function(){
		var bloom_id = $(this).attr('value');
		var post_data = {
			'bloom_id': bloom_id
		}
		$.ajax({type: 'POST',
				url: base_url + 'curriculum/tlo_clo_map/getBloomsLevelActionVerb',
				data: post_data,
				success: function(action_verb) {
					$('#bloom_actionverbs').html(action_verb); 
				} 
		});
	});
	
	$("#add_tlo_statement_view_form").validate({
		rules: {
			add_tlo_statement: {
				loginRegex: true
			},
		},
		errorClass: "help-inline font_color",
		errorElement: "span",
		highlight: function(element, errorClass, validClass) {
			$(element).parent().addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parent().removeClass('error');
			$(element).parent().addClass('success');
		}
    });
	
	$(document).on('click','.save_more_tlo_btn',function(e){	
		e.preventDefault();
		var flag = $('#add_tlo_statement_view_form').valid();
		if(flag){
			//var dataval = $('#add_tlo_statement').val();
			var dataval=tinymce.get("add_tlo_statement").getContent();			
			var str1=dataval.replace("<p>", " ");
			var str2=str1.replace("</p>"," "); 
			var res= str2.replace('alt=""', 'alt="image"');
			var tlo_stmt=res; 
			
			var curriculum_id = $('#crclm').val();
			var term_id = $('#term').val();
			var course_id = $('#course').val();
			var topic_id = $('#topic').val();
			var bloom_id = $('#blooms_level_add').val();
			var add_tlo_data = {
				'curriculum_id': curriculum_id,
				'term_id': term_id,
				'course_id': course_id,
				'topic_id': topic_id,
				'tlo_stmt': tlo_stmt,
				'bloom_id': bloom_id
			}	
			$.ajax({type: "POST",
				url: base_url + 'curriculum/tlo_clo_map/add_more_tlo_statement',
				data: add_tlo_data,
				datatype: "JSON",
				success: function(msg) {				
				if(msg==1){				
					$('#add_more_tlo_div').modal('hide');
					success_modal(msg);
					func_grid();
					}else{fail_modal(msg);}
				}
			});
		}
	});
	
	$(document).on('click','.delete_tlo_statement',function(){
		$('#tlo_id_val').val($(this).attr('value'));
		$('#delete_tlo_div').modal('show');		
	});
	
	$(document).on('click','.delete_tlo_btn',function(){
		$('#loading').show();
		var tlo_id = $('#tlo_id_val').attr('value');
		delete_tlo_data = {
			'tlo_id': tlo_id
		}	
		$.ajax({type: "POST",
			url: base_url + 'curriculum/tlo_clo_map/delete_tlo_statement',
			data: delete_tlo_data,
			datatype: "JSON",
			success: function(msg) {
			if(msg==1){
				func_grid();
				$('#loading').hide();
				delete_modal(msg);
				}
			}
		});
	});


			//Tiny MCE script
	 tinymce.init({
		 mode : "specific_textareas",
		editor_selector: "tlo1",
		relative_urls: false,
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
		"searchreplace visualblocks code fullscreen",
		"insertdatetime media table contextmenu paste jbimages",
		
		],
		paste_data_images: true,
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
		
	});
	

 	 function tiny_init(){

	tinymce.init({
    mode : "specific_textareas",
	editor_selector: "tlo1",
	relative_urls: false,
	plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table contextmenu paste jbimages",
	
    ],
	paste_data_images: true,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
});








	} 
		function success_modal(msg) {//$('#myModal_suc').modal('show'); 
				var data_options = '{"text":"Your data has been saved successfully.  ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);
				
		}
		
			function fail_modal(msg){//$('#myModal_fail').modal('show');
				$('#loading').hide();
				var data_options = '{"text":"Not Updated    ","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);
									$('#loading').hide();
		}
					function delete_modal(msg){//$('#myModal_suc').modal('show'); 
				var data_options = '{"text":"Your data has been deleted successfully.  ","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
									var options = $.parseJSON(data_options);
									noty(options);
				
		}