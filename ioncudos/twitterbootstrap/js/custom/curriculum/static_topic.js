					var base_url = $('#get_base_url').val();
						if($.cookie('remember_term') != null) {

							// set the option to selected that corresponds to what the cookie is set to
							$('#curriculum option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
							select_term();
	
							} 

                            function select_term()
                            {
								$.cookie('remember_term', $('#curriculum option:selected').val(), { expires: 90, path: '/'});
                                var data_val = document.getElementById('curriculum').value;
                                var post_data = {
                                    'crclm_id': data_val
                                }
                                $.ajax({type: "POST",
                                    url: base_url+'curriculum/topic/select_term',
                                    data: post_data,
                                    success: function(msg) {
                                        document.getElementById('term').innerHTML = msg;
										 if($.cookie('remember_course') != null) {
									// set the option to selected that corresponds to what the cookie is set to
										$('#term option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
										select_course();
                                    } 
                                }
                            });
							}

                            function select_course()
                            {
								$.cookie('remember_course', $('#term option:selected').val(), {expires: 90, path: '/'});
								var select_course_path = base_url+'curriculum/topic/select_course';
                                var data_val = document.getElementById('term').value;
                                var post_data = {
                                    'term_id': data_val
                                }
                                $.ajax({type: "POST",
                                    url: select_course_path, 
                                    data: post_data,
                                    success: function(msg) {
                                        document.getElementById('course').innerHTML = msg;
										 if($.cookie('remember_selected_value') != null) {
										// set the option to selected that corresponds to what the cookie is set to
										$('#course option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
										GetSelectedValue();
                                    } 
									}
                                });
                            }
							
							//Function to fetch the grid details
                            function GetSelectedValue()
                            {
								$.cookie('remember_selected_value', $('#course option:selected').val(), { expires: 90, path: '/'});
                                var curriculum_id = document.getElementById('curriculum').value;
                                var term_id = document.getElementById('term').value;
                                var course_id = document.getElementById('course').value;
								var show_topic_path = base_url+'curriculum/topic/static_show_topic';
                                var post_data = {
                                    'crclm_id': curriculum_id,
                                    'term_id': term_id,
                                    'course_id': course_id,
                                };
                                $.ajax(
                                        {type: "POST",
                                            url: show_topic_path, 
                                            data: post_data,
                                            dataType: 'json',
                                            success: populate_table
                                        });
                            }
                            
                            function populate_table(msg) {
                                $('#example').dataTable().fnDestroy();
                                $('#example').dataTable(
                                        {
                                            "aoColumns": [
                                                {"sTitle": "Topic(s)", "sClass": "", "mData": "topic_title"},
                                                {"sTitle": "Topic Content", "sClass": "", "mData": "topic_content"}
                                                                   ], "aaData": msg});
                            }
                         
