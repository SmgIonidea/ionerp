
//curriculum.js - 2.1

$(function(){
    //Criterion 2 - Curriculum component section 2.1.3	
    $('#view').on('change','#curriculum_list__2_1_3', function() {
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        var name_value = name.replace('curriculum_list__', '');   
        var curriculum = $('#curriculum_list__2_1_3').val();
                
        var post_data = {
            'curriculum' : curriculum,
            'view_nba_id' : name_value,
            'view_form' : $('#view_form .filter').serializeArray(),
            'nba_sar_id' : $('#nba_sar_id').val()
        };
						
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
                url: base_url+'nba_sar/t1ug_c2_curriculum/display_curriculum_components',
                async:false,
                data: post_data,
                dataType: 'json',
                success: function(msg) {
                    $('#component_vw_id').empty();
                    $('#component_vw_id').html(msg['component_view']);
                }
            });
        }else{
            $('#component_vw_id').empty();
        }
    });
        
    //Criterion 2 - Structure of the Curriculum section 2.1.2
    $('#view').on('change','#curriculum_list_structure', function() {
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $(this).val();	
			
        var post_data = {
            'curriculum' : curriculum,
            'view_nba_id' : name_value,
            'view_form' : $('#view_form .filter').serializeArray(),
            'nba_sar_id' : $('#nba_sar_id').val()
        };
				
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
                url: base_url+'nba_sar/t1ug_c2_curriculum/display_curriculum_structure',
                async:false,
                data: post_data,
                dataType: 'json',
                success: function(msg) {
                    $('#structure_vw_id').empty();
                    $('#structure_vw_id').html(msg['curriculum_structure_view']);						
                }
            });
        }else{
            $('#structure_vw_id').empty();
        }
    });
});

//File ends here.