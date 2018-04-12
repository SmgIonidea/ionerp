/*You may use scrollspy along with creating and removing elements form DOM. But if you do so, you have to call the refresh method . 
         The following code shows how you may do that:*/

        $('[data-spy="scroll"]').each(function() {
            var $spy = $(this).scrollspy('refresh')
        });

	//set cookie
    	if($.cookie('remember_curriculum') != null) {
		// set the option to selected that corresponds to what the cookie is set to
		$('#crclm option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
		select_term();
    	}
	
        //second dropdown - term
        function select_term()
        {
	    $.cookie('remember_curriculum', $('#crclm option:selected').val(), { expires: 90, path: '/'});
            var data_val = document.getElementById('crclm').value;
	    var select_term_path = 'tlo_clo_map_report/select_term';
	    $('#loading').show();
            var post_data = {
                'crclm_id': data_val
            }

            $.ajax({type: "POST",
                url: select_term_path,
                data: post_data,
                success: function(msg) {
                    document.getElementById('term').innerHTML = msg;
		    if($.cookie('remember_term') != null) {
			$('#term option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
			select_course();
		    }
		    $('#loading').hide();
                }
            });
        }

	//Third dropdown course
        function select_course()
        {
	    $.cookie('remember_term', $('#term option:selected').val(), { expires: 90, path: '/'});
            var data_val1 = document.getElementById('term').value;
	    var term_course_path = 'tlo_clo_map_report/term_course_details';
	    $('#loading').show();
            var post_data = {
                'term_id': data_val1
            }

            $.ajax({type: "POST",
                url: term_course_path, 
                data: post_data,
                success: function(msg) {
                    document.getElementById('course').innerHTML = msg;
		    if($.cookie('remember_course') != null) {
			$('#course option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
			select_topic();
		    }
		    $('#loading').hide();

                }
            });
        }

	//Four dropdown topic
        function select_topic()
        {
	    $.cookie('remember_course', $('#course option:selected').val(), { expires: 90, path: '/'});
            var data_val = document.getElementById('course').value;
            var data_val1 = document.getElementById('term').value;
	    var course_topic_path = 'tlo_clo_map_report/course_topic_details' ;
	    $('#loading').show();
            var post_data = {
                'crs_id': data_val,
                'term_id': data_val1,
            }


            $.ajax({type: "POST",
                url: course_topic_path,
                data: post_data,
                success: function(msg) {
                    document.getElementById('topic').innerHTML = msg;
		    if($.cookie('remember_topic') != null) {
			$('#topic option[value="' + $.cookie('remember_topic') + '"]').prop('selected', true);
			func_grid();
		    }
		    $('#loading').hide();
                }
            });
        }


        //display grid on select of term
        function func_grid() {
			$.cookie('remember_topic', $('#topic option:selected').val(), { expires: 90, path: '/'});
			var data_val = document.getElementById('term').value;
			var data_val1 = document.getElementById('crclm').value;
			var data_val2 = document.getElementById('course').value;
			var data_val3 = document.getElementById('topic').value;

			$('#loading').show();
            
			if (!data_val3) {
                $("a#export").attr("href", "#");
            } else {
				$("a#export").attr("onclick", "generate_pdf("+0+");");
				$("a#export_doc").attr("onclick", "generate_pdf("+1+");");
			}
			
			var tlo_details_path = 'tlo_clo_map_report/tlo_details';
            var post_data = {
                'term_id': data_val,
                'crclm_id': data_val1,
                'crs_id': data_val2,
                'topic_id': data_val3,
            }

            $.ajax({type: "POST",
                url: tlo_details_path,
                data: post_data,
                success: function(msg) {
                    document.getElementById('tlo_clo_mapping').innerHTML = msg;
					$('#loading').hide();
                }
            });
        }

    function generate_pdf(type) {
		if (type == '0') {
			$('#doc_type').val('pdf');
		} else {
			$('#doc_type').val('word');
		}

		var cloned = $('#tlo_clo_mapping').clone().html();
		$('#pdf').val(cloned);
		$('#form1').submit();
	}