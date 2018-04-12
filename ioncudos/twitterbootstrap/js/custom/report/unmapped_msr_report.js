
	/* You may use scrollspy along with creating and removing elements form DOM. 
	 * But if you do so, you have to call the refresh method . 
	 * The following code shows how you may do that:
	*/
	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});
	
	//set cookie
    	if($.cookie('remember_curriculum') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
		select_unmapped_measures();
    	}
	/* Function is used to fetch unmapped POs, PIs & Measures.
	* @param-
	* @retuns - the table grid view of unmapped measures report details.
	*/
	function select_unmapped_measures()
	{
		$.cookie('remember_curriculum', $('#crclm option:selected').val(), { expires: 90, path: '/'});
		var base_url = $('#get_base_url').val();
		var data_val1 = document.getElementById('crclm').value;
		$('#loading').show();

		if (!data_val1)
			$("a#export").attr("href", "#");
		else
			$("a#export").attr("onclick", "generate_pdf();");
		var post_data = {
			'crclm_id': data_val1,
		}
		$.ajax({type: "POST",
			url: base_url+'report/unmapped_msr_report/fetch_unmapped',
			data: post_data,
			success: function(msg) {
				document.getElementById('unmapped_measures_table_id').innerHTML = msg;
				$('#loading').hide();
			}
		});
	}

	function fetch_crclm()
	{
		var crclmSelect = document.getElementById("crclm");
		var selectedcrclm = crclmSelect.options[crclmSelect.selectedIndex].text
		document.getElementById("curriculum_id").value = selectedcrclm;
	}

	function generate_pdf()
	{
		var cloned = $('#unmapped_measures_table_id').clone().html();
		$('#pdf').val(cloned);
		$('#form_id').submit();
	}
