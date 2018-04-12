	
	//criterion 7 js
	
	// criterion 7. section 7.4 continuous improvement  - JS.
	$('#view').on('change','#c7_4_crclm_list', function() {
		var base_url = $('#get_base_url').val();
		var name = $(this).attr('name'); 

		//obtained from nba_sar_view
		var name_value = name.replace('curriculum_list__', '');
		var curriculum = $(this).val();	
		
		var post_data = {
			'curriculum' : curriculum,
			'view_nba_id' : name_value,
			'view_form' : $('#view_form .filter').serializeArray(),
		}
		
		$.ajax({type:"POST",
				url: base_url+'nba_sar/t1ug_c7_continuous_improvement/fetch_quality_of_students',
				async:false,
				data: post_data,
				success: function(msg) {
					$('#enrolment_ratio').empty();
					$('#enrolment_ratio').html(msg);
				}
		});
	});