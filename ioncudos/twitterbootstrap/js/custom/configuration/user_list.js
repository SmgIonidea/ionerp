
	//Course Learning Objectives (CLOs) to Program Outcomes (POs) Mapping (Termwise)
	
	var base_url = $('#get_base_url').val();
	var user_id;

    $(document).on('click','.status',function(){
        user_id = $(this).attr("id");
    });

	//this function will activate the deactivated users
    $(document).ready(function() {
        $('.enable-ok').click(function(e) {
            e.preventDefault();
                       
            $.ajax({type: "POST",
				url: base_url + 'configuration/users/activate/' + user_id,
                success: function(msg) {        
                    location.reload();
                }
            });
        });

		//this function will deactivate the active users
        $('.disable-ok').click(function(e) {
            e.preventDefault();
            
            var post_data ={ 'user_id' : user_id}
            $.ajax({
                type: "POST",
                url: base_url + 'configuration/users/check_user',
                data: post_data,
                datatype: "json",
                success: function(data_1){
                    if(data_1 == 0){
                        $.ajax({type: "POST",
				url: base_url + 'configuration/users/deactivate/' + user_id,
                        success: function(msg) {
                            location.reload();
                        }
                    
                    });
                        
                    }else{
                        $('#cannot_disable').modal('show');
                    }
                }
            });
            
        });
    });

	if ($.cookie('remember_dept') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept_id option[value="' + $.cookie('remember_dept') + '"]').prop('selected', true);
    select_dept();
}
	
	//Function to display list in the List View of Assessment Method for a Program selected.
	function select_dept(){
		$.cookie('remember_dept', $('#dept_id option:selected').val(), {expires: 90, path: '/'});
		//$('.add_ao_mtd').prop('disabled',false);
		$('#dept_id_h').val($('#dept_id').val());
		grid();
	}
	
	/* Function to fetch the assessment methods for the selected program
	 * @param - program id
	 * @returns - List of assessment method along with descriptions in List View Page.
	 */
	function grid() {
        var dept_id = $('#dept_id').val();
        var post_data = {
            'dept_id': dept_id,
        }
        $.ajax({type: "POST",
			url: base_url + 'configuration/users/user_list',
            data: post_data,
            dataType: 'json',
            success: populate_table
        });
    }
	
	function populate_table(msg) {
		$('#example').dataTable().fnDestroy();
        $('#example').dataTable({
			 "sPaginationType": "full_numbers",
			 "iDisplayLength": 20,
			 "aoColumns": [
							{"sTitle": "First Name", "mData": "first_name"},
							{"sTitle": "Last Name", "mData": "last_name"},
							{"sTitle": "Department", "mData": "dept_name"},
							{"sTitle": "User Group", "mData": "group_name"},
							{"sTitle": "Designation", "mData": "designation"},
							{"sTitle": "Email", "mData": "email"},
							{"sTitle": "Edit", "mData": "user_edit"},
							{"sTitle": "Status", "mData": "user_status"}
							
						 ], "aaData": msg["user_list"] ,
						 "sPaginationType": "bootstrap"
		});
		
	}
	//List User Script Ends Here