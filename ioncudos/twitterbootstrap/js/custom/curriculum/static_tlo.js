 // SLO Static List Script Starts From Here.
 
 var base_url = $('#get_base_url').val();
 
 // SLO Static List page script starts from here.
	//function to fetch term
	function static_select_term() {
        var curriculum_id = document.getElementById('curriculum').value;
		var term_drop_down_fill_path = base_url+'curriculum/tlo_list/select_term';
        var post_data = {
            'crclm_id': curriculum_id
        }

        $.ajax({type: "POST",
            url: term_drop_down_fill_path, 
            data: post_data,
            success: function(msg) {
                document.getElementById('term').innerHTML = msg;
            }
        });
    }

	//function to fetch course
    function static_select_course() {
        var term_id = document.getElementById('term').value;
		var course_drop_down_fill = base_url + 'curriculum/tlo_list/select_course';
        var post_data = {
            'term_id': term_id
        }
        $.ajax({type: "POST",
            url: course_drop_down_fill, 
            data: post_data,
            success: function(msg) {
                document.getElementById('course').innerHTML = msg;
            }
        });
    }

	//function to fetch topic
    function static_select_topic()
    {
        var curriculum_id = document.getElementById('curriculum').value;
        var term_id = document.getElementById('term').value;
        var course_id = document.getElementById('course').value;
		var topic_drop_down_fill = base_url + 'curriculum/tlo_list/select_topic';
        var post_data = {
            'crclm_id': curriculum_id,
            'term_id': term_id,
            'crs_id': course_id,
        }

        $.ajax({type: "POST",
            url: topic_drop_down_fill, 
            data: post_data,
            success: function(msg) {
                document.getElementById('topic').innerHTML = msg;
            }
        });
    }
	
	function static_GetSelectedValue()
    {
        var curriculum_id = document.getElementById('curriculum').value;
        var term_id = document.getElementById('term').value;
        var course_id = document.getElementById('course').value;
        var topic_id = document.getElementById('topic').value;
		var edit_tlo_path = base_url + 'curriculum/tlo/edit_tlo';
		var show_topic_path = base_url + 'curriculum/tlo_list/show_topic';
       
        var post_data = {
            'crclm_id': curriculum_id,
            'term_id': term_id,
            'course_id': course_id,
            'topic_id': topic_id,
        }
		
        $.ajax({type: "POST",
            url: show_topic_path, 
            data: post_data,
            dataType: 'json',
            success: static_populate_table
        });
    }
	
	//function to populate static table
    function static_populate_table(msg) {
        var m = 'd';
        $('#example').dataTable().fnDestroy();
		
        $('#example').dataTable(
			{"aoColumns": [
					{"sTitle": "SLOs", "mData": "tlo_statement"},
					
			], "aaData": msg["tlo_list"]});
        $('#publish').attr("disabled", false);
	}
//SLO Static List page script Ends Here.