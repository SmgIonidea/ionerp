
//peo.js

//Criterion 1 - Vision Mission section - 1.2
$(function() {
    $('#view').on('change','#curriculum_list_peo',function() {
        var base_url = $('#get_base_url').val();
        var curriculum = $(this).val();	
        var department = $('#dept_id').val();	
        var name = $(this).attr('name'); 
        //obtained from nba_sar_view
        var name_value = name.replace('curriculum_list__', '');
			
        var post_data = {
            'curriculum' : curriculum,
            'department' : department,
            'view_nba_id' : name_value,
            'nba_sar_id' : $('#nba_sar_id').val(),
            'view_form' : $('#view_form .filter').serializeArray()
        }

        $.ajax({
            data:post_data,
            async:false,
            type:'post',
            url: $('#get_baseurl').val()+"nba_sar/nba_sar/generate_report",
            success: function(msg){
            }
        });
	
        if(curriculum){
            $.ajax({
                type:"POST",
                url: base_url + 'nba_sar/T2dip_c1_vision_mission/display_peo',
                async:false,
                data: post_data,
                success: function(msg) {
                    $('#peo_vw_id').empty();
                    $('#peo_vw_id').html(msg);
                }
            });
        }else{
            $('#peo_vw_id').empty();
        }
    });
});

//File ends here.