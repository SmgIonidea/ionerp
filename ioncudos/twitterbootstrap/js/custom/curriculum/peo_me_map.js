//Program Owner's PO to PEO mapping List View JS functions.
	
	/*You may use scrollspy along with creating and removing elements form DOM. 
	* But if you do so, you have to call the refresh method . 
	* The following code shows how you may do that.
	*/
	var base_url = $('#get_base_url').val();
	
	$('[data-spy="scroll"]').each(function() {
		var $spy = $(this).scrollspy('refresh')
	});

	

    function select_curriculum()  {
        document.getElementById('error').innerHTML = '';
       // document.getElementById('peo_me_note_box_id').innerHTML = '';
       
        var curriculum_id = document.getElementById('curriculum_list').value;
        if (curriculum_id != 'Select Curriculum')   {
            var post_data = {
					'crclm_id': curriculum_id
				}
				
            $.ajax({type: "POST",
				url: base_url+'curriculum/map_peo_me/map_table',
                data: post_data,
                success: function(msg)
                {
                    //document.getElementById('note').style.visibility = 'visible';
                    document.getElementById('mapping_table').innerHTML = msg;
                    
                }
            });
		} 
    }
	
	
	//to display po statement to the user
    function writetext2(me)  {
        document.getElementById('me_display_textbox_id').innerHTML = me;
    }
	
	//
    $('#send_approve_button_id').live('click', function() {
        var flag = 0;
        var tmp = true;
        $('#peomeList tr:not(:nth-child(0))').each(function() {
            $(this).removeAttr("style");
            if ($(this).find('input:checked').length > 0)  {
                tmp = false;
            } else {
                if (flag > 0)
                    $(this).css("background-color", "gray");
                flag++;
            }
        });
		
		$('#peomeList tr:nth-child(1) td').each(function() {
			var td_class = $(this).attr('class');
			$('.' + td_class).removeAttr("style");
			var tmp = true;
			$('.' + td_class + ':not(:nth-child(0))').each(function() {
				if ($(this).children('input:checked').length > 0){
					tmp = false;
				}
			});
			
			if (tmp){
				$('.' + td_class).css("background-color", "gray");
				flag++;
			}
		});
		
		if (flag > 2){
			$('#incomplete_mapping_dialog_id').modal('show');
		} else {
			flag = 0;
			$('#send_mapping_approval_dialog_id').modal('show');
		}
	});

    var globalid;
	
	//
    $('.check').live("click", function() {
        var id = $(this).attr('value');
        globalid = $(this).attr('id');
        window.id = id;
        var curriculum_id = document.getElementById('curriculum_list').value;
        window.curriculum_id = curriculum_id;
        var post_data = {
				'me': id,
				'crclm_id': curriculum_id,
			}
        if ($(this).is(":checked")) {
            $.ajax({
				type: "POST",
				url: base_url+'curriculum/map_peo_me/add_mapping',
                data: post_data,
                success: function(msg) {
                }
            });
        } else {
            $('#uncheck_mapping_dialog_id').modal('show');
        }
    });

	//
    function cancel_uncheck_mapping_dialog() {
        $('#' + globalid).prop('checked', true);
    }

	//from modal2
    function unmapping() {
        var curriculum_id = document.getElementById('curriculum_list').value;
        var post_data = {
				'me': id,
				'crclm_id': curriculum_id,
			}
        $.ajax({
			type: "POST",
			url: base_url+'curriculum/map_peo_me/unmap',
            data: post_data,
            success: function(msg) {
                $('#uncheck_mapping_dialog_id').modal('hide');
            }
        });
    }
	

	//function is to insert curriculum id into the hidden input field
    function submit_mapping_form() {
        document.getElementById('crclm_id').value = document.getElementById('curriculum_list').value;
        $('#frm').submit();
    }

//Static PO to PEO mapping List View JS functions.		
		//select the curriculum from the drop down
	
	function static_select_curriculum()
	{
	//alert();
		document.getElementById('error').innerHTML = '';
		document.getElementById('mapping_table').innerHTML = '';
		
	
		var curriculum_id = document.getElementById('curriculum_list').value;
		if (curriculum_id != 'Select Curriculum')
		{
			var post_data = {
				'crclm_id': curriculum_id
			}

			$.ajax({type: "POST",
				url: base_url+'curriculum/map_peo_me/static_map_table',
				data: post_data,
				success: function(msg)
				{
					document.getElementById('mapping_table').innerHTML = msg;
				}
			});
		} else {
			document.getElementById('error').innerHTML = 'Select Curriculum';
		}
		//Fetching the current State of POs to PEOs Mapping.
		
	}
	
	
	
	