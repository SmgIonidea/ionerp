	//Performance indicator and measures approver list page
	//Function to fetch curriculum details
	
	var base_url = $('#get_base_url').val();
	
	function bos_select_curriculum() {
		var curriculum_id = document.getElementById('curriculum_list').value;

		var post_data = {
			'curriculum_id': curriculum_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'curriculum/pi_and_measures/select_po_curriculum',
			data: post_data,
			dataType: 'json',
			success: bos_populate_table
		});
	}
	
	//Function to populate table containing approved program outcome and its corresponding performance indicator and measures
	function bos_populate_table(msg) {
		var m = 'd';
		
		$('#example').dataTable().fnDestroy();
		$('#example').dataTable(
			{"aoColumns": [
				{"sTitle": "Approved Program Outcomes (PO)", "mData": "po_statement"},
				{"sTitle": "PI & Measures", "mData": "po_id2"},
				{"sTitle": "Comments", "mData": "po_id3"},
			], "aaData": msg}
		);
	}

	//Function to fetch curriculum details and display the grid on load
	$(document).ready(function() {
		var table_row;
		var po_id;

		$('.get_id').live('click', function(e) {
			po_id = $(this).attr('id');
			table_row = $(this).closest("tr").get(0);
		});

		$('.cmt_submit').live('click', function() {
			$('a[rel=popover]').not(this).popover('hide');
			
			var po_id = document.getElementById('po_id').value;
			var curriculum_id = document.getElementById('crclm_id').value;
			var pi_cmt = document.getElementById('pi_cmt').value;
			
			var post_data = {
				'po_id': po_id,
				'curriculum_id': curriculum_id,
				'pi_cmt': pi_cmt,
			}
			
			if(pi_cmt != ''){
				$.ajax({type: "POST",
					url: base_url + 'curriculum/pi_and_measures/pi_comment_insert',
					data: post_data,
					success: function(msg) {
					}
				}); 
			}
		});

		//comment section starts
		$('.comment').live('click', function() {
		
			$('a[rel = popover]').not(this).popover('destroy');
						
			$('a[rel = popover]').popover({
				html: 'true',
				trigger: 'manual',
				placement: 'top'
        })
        $(this).popover('show');
			
			$('.close_btn').live('click', function() {
				$('a[rel = popover]').not(this).popover('destroy');
			});
		});

		//on click of rework button
		$('#rework').live('click', function() {
			var curriculum_id = document.getElementById('curriculum_list').value;
			
			if (curriculum_id) {
				var post_data = {
					'crclm_id': curriculum_id,
				}

				$.ajax({type: "POST",
					url: base_url + 'curriculum/pi_and_measures/prog_user_name',
					data: post_data,
					dataType: "JSON",
					success: function(msg) {
						$('#pgm_owner_user_rework').html(msg.prog_user_name);
						$('#crclm_name_oe_pi_rework').html(msg.crclm_name);
						$('#myModal_rework').modal('show');
					}
				});
			}
		});
		
		//on click of accept button
		$('#accept').live('click', function() {
			var curriculum_id = document.getElementById('curriculum_list').value;
			
			if (curriculum_id) {
				var post_data = {
					'crclm_id': curriculum_id,
				}

				$.ajax({type: "POST",
					url: base_url + 'curriculum/pi_and_measures/prog_user_name',
					data: post_data,
					dataType: "JSON",
					success: function(msg) {
						$('#pgm_owner_user_accept').html(msg.prog_user_name);
						$('#crclm_name_oe_pi_accept').html(msg.crclm_name);
						$('#myModal_accept').modal('show');	
					}
				});
			}
		});
	});

	//Function to fetch related performance indicators and corresponding measures for the program outcomes
	function pi_list(po_id) {
		var post_data = {
			'po_id': po_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'curriculum/pi_and_measures/get_pi',
			data: post_data,
			datatype: "JSON",
			success: function(msg) {
				document.getElementById('list').innerHTML = msg;
			}
		});
	}

	//Function to send program outcomes along with its corresponding performance indicators and measures for rework
	function rework() {
		var curriculum_id = document.getElementById('curriculum_list').value;
		$('#loading').show();

		var post_data = {
			'curriculum_id': curriculum_id,
		}
		
		$.ajax({type: "POST",
			url: base_url + 'curriculum/pi_and_measures/pi_rework',
			data: post_data,
			success: function(msg) {
			document.location.href = base_url + 'dashboard/dashboard';

			}
		});
		$('#loading').show();
	}
	
	//Function to approve program outcomes along with its corresponding performance indicators and measures
	function accept() {

		$('#loading').show();
 		var curriculum_id = document.getElementById('curriculum_list').value;
		
		var post_data = {
			'curriculum_id': curriculum_id,
		}
		
		$.ajax({type: "POST",
			url: base_url + 'curriculum/pi_and_measures/pi_accept',
			data: post_data,
			success: function(msg) {
				//document.location.href = base_url + 'dashboard/dashboard';
				//added by Bhagya S S
				//window.location = base_url +'curriculum/course/';
				$(location).attr('href', base_url +'curriculum/course/course_index/'+curriculum_id); 
			}
		}); 
		$('#loading').show();
	} 
	
	
	//function to get the notes (text) entered
    $(".pi_cmt_add").live("keyup", function() {
	var crclm_id = $('#crclm_id_val').val();
	var po_id = $(this).attr("abbr");
	
	var po_comment = $(this).val();
	
	var post_data = {
	'crclm_id' : crclm_id,
	'po_id':po_id,
	'po_comment' :po_comment
	}
	$.ajax({
			url: base_url+'curriculum/pi_and_measures/save_pi_comment',
            type: "POST",
            data: post_data,
            success: function(data) {
                if (!data) {
                    alert("unable to save file!");
                }
            }
        });
      
    });

	
	//Performance Indicator and its measure(s) Script Ends Here	
