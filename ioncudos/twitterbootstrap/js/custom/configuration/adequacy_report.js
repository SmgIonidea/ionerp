
var base_url = $('#get_base_url').val();
 /* Class is used to display the emails by fetching the in-putted live string from email tab.
	* @param- live data (live search data)
	* @returns - an object.
	*/	
	$(function() {
		$('#fetch_email_ids_to').typeahead({
			source: function(typeahead, query) {
				$.ajax({
					url: base_url+'configuration/adequacy_report/email_ids_list_to',
					type: "POST",
					data: "query=test",
					dataType: "JSON",
					async: false,
					success: function(data) {
					
						typeahead.process(data);
					}
				});
			},
			property: 'email',
			items: 8,
			onselect: function(obj) {
			}
		});
		$('#fetch_email_ids_cc').typeahead({
			source: function(typeahead, query) {
				$.ajax({
					url: base_url+'configuration/adequacy_report/email_ids_list_cc',
					type: "POST",
					data: "query=test",
					dataType: "JSON",
					async: false,
					success: function(data) {
					
						typeahead.process(data);
					}
				});
			},
			property: 'email',
			items: 8,
			onselect: function(obj) {
			}
		});
	});
	
	$('#generate').on('click',function(){
	
	$.ajax({type: "POST",
			url: base_url+'configuration/adequacy_report/generate_csv',
				//data: post_data,
				success: function(msg){
				$("#generate").attr("disabled", "disabled"); 
				console.log(msg);
				$('#csv_table').html(msg);
				
				}
			});
	
	});
	
	$('#submit_add').on('click',function(){
	
		var hiddentags_to = $("input[name=hidden-tags_to]").val();
		if(hiddentags_to == '')
		{
			$('#myModal_emptyto').modal('show');
			return false;
		
		}
		//alert(hidden-tags_to);
		});