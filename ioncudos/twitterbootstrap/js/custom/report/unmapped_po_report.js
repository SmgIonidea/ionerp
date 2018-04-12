
	//Unmapped Program Outcome Report

	var base_url = $('#get_base_url').val();

	//Unmapped Program Outcome Report View and Static View Page
	/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
	 The following code shows how you may do that:*/

	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});
	
	//set cookie
    	if($.cookie('remember_curriculum') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
		select_pos();
    	}

	//Function to fetch term details and display the unmapped grid
	function select_pos() {
		$.cookie('remember_curriculum', $('#crclm option:selected').val(), { expires: 90, path: '/'});
		var curriculum_id = document.getElementById('crclm').value;
		$('#loading').show();
		if (!curriculum_id)
			$("a#export").attr("href", "#");
		else
			$("a#export").attr("onclick", "generate_pdf();");

		var post_data = {
			'curriculum_id': curriculum_id,
		}

		$.ajax({type: "POST",
			url: base_url + 'report/unmapped_po_report/fetch_unmapped_po',
			data: post_data,
			success: function(msg) {
				document.getElementById('table_view').innerHTML = msg;
				$('#loading').hide();
			}
		});
	}

	//Function to insert curriculum id into the hidden field
	function insert_curriculum_id_into_hidden_field() {
		var curriculum_select = document.getElementById("crclm");
		var selected_curriculum = curriculum_select.options[curriculum_select.selectedIndex].text
		
		document.getElementById("curr").value = selected_curriculum;
	}

	//Function to generate .pdf
	function generate_pdf() {
		var cloned = $('#table_view').clone().html();
		
		$('#pdf').val(cloned);
		$('#form_export_pdf').submit();
	}
