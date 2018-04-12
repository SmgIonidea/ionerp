		/* You may use scrollspy along with creating and removing elements form DOM. 
	 * But if you do so, you have to call the refresh method . 
	 * The following code shows how you may do that:
	*/

	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});

	/* Function is used to fetch mapped POs, Compentency & Performance Indicators.
	* @param-
	* @retuns - the table grid view of po, Compentency & Performance Indicators report details.
	*/
	function select_po()
	{

		var base_url = $('#get_base_url').val();
		var data_val1 = document.getElementById('crclm').value;
		if (!data_val1)
			$("a#export").attr("href", "#");
		else{
			$("a#export").attr("onclick", "generate_pdf("+0+");");
			$("a#export_doc").attr("onclick", "generate_pdf("+1+");");
			}
			
		var post_data = {
			'crclm_id': data_val1,
		}

		$.ajax({type: "POST",
			url: base_url+'report/po_report/fetch_po_details',
			data: post_data,
			success: function(msg) {
				document.getElementById('po_report_table_id').innerHTML = msg;
			}
		});
	}

	function generate_pdf(type)
	{
	if (type=='0'){
	$('#doc_type').val('pdf');
	}else{
	$('#doc_type').val('word');}
	 var cloned = $('#po_report_data').clone().html();
	 $('#pdf').val(cloned);
	 $('#form_id').submit();
	}


