
	//Improvement Plan Report
	
	var base_url = $('#get_base_url').val();

	if ($.cookie('remember_crclm') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_crclm') + '"]').prop('selected', true);
		select_term();
	}
	
	//Function to fetch term details for term dropdown
	function select_term() {
		$.cookie('remember_crclm', $('#crclm option:selected').val(), {expires: 90, path: '/'});
		var crclm_id = document.getElementById('crclm').value;
		$('#loading').show();
	
		var post_data = {
			'crclm_id': crclm_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/improvement_plan/select_term',
			data: post_data,
			success: function(msg) {
				document.getElementById('term').innerHTML = msg;
				if ($.cookie('remember_term') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
					select_course();
				}
				$('#loading').hide();
			}
		});
	}

	//Function to fetch course details for course dropdown
	function select_course() {
		$.cookie('remember_term', $('#term option:selected').val(), {expires: 90, path: '/'});
		var crclm_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		$('#loading').show();

		var post_data = {
			'crclm_id': crclm_id,
			'term_id': term_id
		}

		$.ajax({type: "POST",
			url: base_url + 'report/improvement_plan/select_course',
			data: post_data,
			success: function(msg) {
				document.getElementById('course').innerHTML = msg;
				if ($.cookie('remember_crs') != null) {
					// set the option to selected that corresponds to what the cookie is set to
					$('#course option[value="' + $.cookie('remember_crs') + '"]').prop('selected', true);
					improvement_plan_display();
				}
				$('#loading').hide();
			}
		});
	}
	
	//function to display improvement plan details
	function improvement_plan_display() {
		$.cookie('remember_crs', $('#course option:selected').val(), {expires: 90, path: '/'});
		var crclm_id = document.getElementById('crclm').value;
		var term_id = document.getElementById('term').value;
		var crs_id = document.getElementById('course').value;
		var entity_id = 11; //for CO
		$('#loading').show();
		
		var post_data = {
			'entity_id': entity_id,
			'crclm_id': crclm_id,
			'term_id': term_id,
			'crs_id': crs_id
		}
		
		$.ajax({type: "POST",
			url: base_url + 'report/improvement_plan/improvement_plan_display',
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view').innerHTML = msg;
				$("#check td").attr('class','table-bordered');
				$("#check table").attr('class','table-bordered');
				$('#loading').hide();
			}
		});
	}
	
	//function to generate pdf
	$('.export').click(function(){
		$("table").each(function() {
			// first copy the attributes to remove
			var attributes = $.map(this.attributes, function(item) {
			return item.name;
			});

			// now remove the attributes
			var table = $(this);
				$.each(attributes, function(i, item) {
				table.removeAttr(item);
			});
		});
		$("table").attr('class','table table-bordered table-hover');
		var cloned = $('#table_view').clone().html();
		$("#check table:first").attr('class','table-bordered');
		$('#pdf').val(cloned);
		$('#form_id').submit();
	});
