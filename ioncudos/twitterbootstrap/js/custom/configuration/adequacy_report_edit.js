
var base_url = $('#get_base_url').val();
 /* Class is used to display the course names by fetching the in-putted live string from prerequisite course tab.
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
	// Class is used to fetch predecessor course ids of deleted predecessor courses.
