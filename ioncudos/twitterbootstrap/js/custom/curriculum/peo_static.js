
	//Program Educational Objective Page

	var base_url = $('#get_base_url').val();
	//display grid on select of curriculum
    function static_grid() {
        var curriculum_id = document.getElementById('crclm').value;

        var post_data = {
            'curriculum_id': curriculum_id,
        }

        $.ajax({type: "POST",
			url: base_url + 'curriculum/peo/peo_list',
            data: post_data,
            dataType: 'json',
            success: static_populate_table
        });
    }

	
    
	
	//generates a grid on select of curriculum from the dropdown
	function static_populate_table(msg) {		
        $('#example').dataTable().fnDestroy();
        $('#example').dataTable(
                {"aoColumns": [
                        {"sTitle": "Program Educational Objective(PEO) Statements", "mData": "peo_statement"},
                    ], "aaData": msg["peo_list"]});
    }

	
	
	//to set the color of textarea border meant for writing program educational objective statements - add page
   