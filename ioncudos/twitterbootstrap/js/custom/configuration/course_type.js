
	//Course Type List Page

	var base_url = $('#get_base_url').val();

	//tool tip
    $("#hint a").tooltip();

    $(document).ready(function() {
        var table_row;
        var course_type_id;

        $('.get_id').live('click', function(e)
        {
            course_type_id = $(this).attr('id');
            table_row = $(this).closest("tr").get(0);
        });

		//to delete an existing course type from the list
        $('.delete_course').click(function(e) {
            e.preventDefault();
            var post_data = {
                'course_type_id': course_type_id,
            }

            $.ajax({type: "POST",
				url: base_url + 'configuration/course_type/course_delete',
                data: post_data,
                datatype: "JSON",
                success: function(msg)
                {
                    var oTable = $('#example').dataTable();
                    oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
                }
            });
        });
    });
	
	//Course Type List Page Script Ends Here