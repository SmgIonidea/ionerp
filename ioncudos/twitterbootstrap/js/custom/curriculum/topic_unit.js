$("#hint a").tooltip();
            var table_row;
            var data_val;
            $('.get_id').live('click', function(e)
            {
                data_val = $(this).attr('id');
                table_row = $(this).closest("tr").get(0);
            });
			
			/* Function is to delete program mode by sending the program mode id to controller.
			* @param - program mode id.
			* @returns- updated list view.
			*/            
			$('.delete_topic_unit').click(function(e) {
				var base_url = $('#get_base_url').val();
                e.preventDefault();
                var post_data = {
                    't_unit_id': data_val,
                }
                $.ajax({
					type: "POST",
					url: base_url+'configuration/topic_unit/topic_unit_delete',
                    data: post_data,
                    datatype: "JSON",
                    success: function(msg)
                    {
                        var oTable = $('#example').dataTable();
                        oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
                    }
                });
            });
        

        var currentID;
        function currentIDSt(id)
        {
            currentID = id;
        }