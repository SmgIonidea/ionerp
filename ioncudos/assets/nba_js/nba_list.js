//nba_list.js

//Function is to load nba-sar report for selected nba-sar report.
$('.nba_report').on('click',function(){
        var node_id = $(this).attr('abbr');
        $('#node_id').val(node_id);
        window.location=base_url+"nba_sar/nba_sar/index/"+node_id;
});

//Function is to fetch program for selected department
$('#dept_id').on('change', function(){
        var base_url = $('#get_base_url').val();
        var dept = $('#dept_id').val();
        var post_data = {
                'dept' : dept
        }
        $.ajax({
                type:"POST",
                url: base_url+'nba_sar/nba_list/select_program',
                async: false,
                data: post_data,
                dataType: 'json',
                success: function(msg) {
                        if(msg.program_list.length>0){		
                                $('#program_list').empty();
                                $('#program_list').prepend(msg.program_list);
                        }
                }
        });
});

//Function is to store program type id on select of program
$('#program_list').on('change', function(){
        var element = $(this).find('option:selected'); 
        var program_type_id= element.attr('data-program_type');
        $('#program_type').val(program_type_id);
});

//Function is to define rules to validate form.
$("#add_form").validate({
        rules: {                
                dept_id: {
                        required: true
                },
                program_list: {
                        required: true
                }
        },
        messages: {             
                dept_id: {
                        required: "This field is required."
                },
                program_list: {
                        required: "This field is required."
                }
        },
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function(element, errorClass, validClass) {
                $(element).parent().parent().removeClass('success');
                $(element).addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
                $(element).parent().parent().removeClass('error');
                $(element).addClass('success');
        }
});
		
$("#hint a").tooltip();
var table_row;

//Function is to store nba sar report id to delete
$('.get_id').live('click', function(e){
        nba_id = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
});

//Function is to delete nba sar report 
$('.delete_nba').click(function(e) {
        var base_url = $('#get_base_url').val();
        e.preventDefault();
        var post_data = {
                'nba_id': nba_id
        }
        $.ajax({
                type: "POST",
                url: base_url+'nba_sar/nba_list/nba_delete',
                data: post_data,
                datatype: "JSON",
                success: function(msg)
                {
                        var oTable = $('#example').dataTable();
                        oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
						
                }
        });
}); 
//File ends here.
	
	