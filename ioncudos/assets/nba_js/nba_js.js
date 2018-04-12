//nba_js.js

$(function(){
    //Function is to redirect to nba sar report list when nba sar report id is not there.
    if($('#node_id').val() == ''){
        window.location = $('#get_baseurl').val()+'nba_sar/nba_list';
    }
        
    //Function is to search node from tree. 
    $('#search_query').keyup(function () {
        var search_string = $('#search_query').val();
        $('#nba_sar').jstree(true).search(search_string);
    });
        
    //Function is to populate tree.
    $('#nba_sar').jstree({
        'core' : {
            'data' : {
                "url" : $('#get_baseurl').val()+'nba_sar/nba_sar/get',
                "dataType" : 'json',
                "method" : 'post',
                'li_attr':{
                    'title':'sa'
                },
                "data" : function (node) {
                    var node_value = $('#tier_id').val();
                    var node_value = {
                        "id" : node.id,
                        "node_value" : node_value
                    }
                    return node_value;
                },
                success:function(node_return){
                    if(node_return == null){
                        window.location = $('#get_baseurl').val()+'nba_sar/nba_list';
                    }
                }
            },
            "themes":{
                'name': 'proton',
                'responsive': true,
                'icons':false
            }
        },
        "plugins" : ["search","wholerow" ]
    });
        
    //Function is to show all node with it's children node. 
    $('#nba_sar').on('ready.jstree', function() {
        $("#nba_sar").jstree("open_all");
    });
        
    //Function is to export .docx file.
    $('.export').on('click',function(){
        $('#loading').show();
        $('#export_details').val($(this).attr('abbr'));
        $('#description_form').submit();
        $('#loading').hide();
    /*if($(this).attr('abbr') != '1'){
				$('#description_form').submit();
			}else{
				$.ajax({
					url: $('#get_baseurl').val()+'nba_sar/nba_sar/export',
					async:false,
					method: 'post',
					data:{
						'export_details' : 1,
						'nba_report_id' : $('#node_id').val()
					},
					success:function(view){
					
					}
				});
			}*/
    });
        
    //Function is to load view for selected node
    $('#nba_sar').on("select_node.jstree", function (e, data) {
        $('#loading').show();
        $('#view').empty();
        $('.view_div').hide();
        $('#node_info').val(data.node.id);
        $('#export_node').removeAttr('disabled');
        $.ajax({
            url: $('#get_baseurl').val()+'nba_sar/nba_sar/get_view',
            dataType: 'json',
            type:'post',
            async:false,
            data:{
                'id' : data.node.id,
                'dept_id' : $('#dept_id').val(),
                'pgm_id' : $('#pgm_id').val(),
                'node_value' : $('#node_id').val(),
                'node_info' : $('#node_info').val()
            },
            success:function(view){
                $('#loading').hide();
                if($.trim(view['view_data']) != ''){
                    $('.description_save').removeAttr('disabled');
                    $('.view_div').show();
                    $('#view').html('<b>'+view['label']+' : </b><br><br>'+view['view_data']);
                    /*var flag = $('.generate_report_flag').val();
                                        if(flag == '1'){
                                                $('#generate_report').show();
                                        }else{
                                                $('#generate_report').hide();
                                        }*/
                    $('#nba_sar_id').val(data.node.id);
                    if($.trim(view['show_save']) == '1'){
                        tiny();
                        $('.description_save').removeAttr('disabled');
                    }else{
                        $('.description_save').attr('disabled','disabled');
                    }
                }else{
                    $('.description_save').attr('disabled','disabled');
                }
						
            //var crclm_val = $('#curriculum_list_course_po').attr('name'); // Added by Mritunjay B S- Date:9-5-2016
            //var crclm_name_split = crclm_val.split('__');
            //var node_values = crclm_name_split[1].split('_');
            //$('.cos_checkbox').attr('name','');
            // $('.cos_checkbox').attr('name','cos_checkbox_list__'+node_values[0]+'_'+node_values[1]);// redifining the all checkbox names
            }
        });
    });
        
    //Function is to initialize tiny mce for textarea.
    function tiny(){
        tinymce.editors = [];
        tinymce.init({
            selector: "textarea",
            relative_urls: false,
            plugins: [
            "advlist autolink lists charmap preview ",
            "searchreplace visualblocks fullscreen",
            "table contextmenu paste moxiemanager jbimages"
            ],
            toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages"
        });			
    }
        
    //
    /*$('.view_div').on('click','#generate_report',function(){
                var postdata={
                        'view_form' : $('#view_form .filter').serializeArray(),
                        'nba_sar_id' : $('#nba_sar_id').val(),
                        'nba_id': $('#node_id').val()
                };
                $.ajax({
                        data:postdata,
                        async:false,
                        type:'post',
                        url: $('#get_baseurl').val()+"nba_sar/nba_sar/generate_report",
                        success: function(msg){
                        }
                });
        });*/
        
    //Function is to save description.
    $('.description_save').on('click',function(){
        $('#loading').show();
        tinymce.triggerSave();
        var postdata={
            'view_id' : $('#node_info').val(),
            'nba_id' : $('#node_id').val(),
            'description' : $('#view_form').serializeArray()
        };
        $.ajax({
            data:postdata,
            async:false,
            type:'post',
            url: $('#get_baseurl').val()+"nba_sar/nba_sar/description_update",
            success: function(msg){
                $('#loading').hide();
            }
        });
    });
        
    /* Function is used to fetch dept mission vision for admin.
	* @param-
	* @retuns - the view of department mission vision details.
	*/
	
    /* $('.view_div').on('change','#dept',function(){
                var base_url = $('#get_base_url').val();
                var data_val1 = $('#dept').val();
		
                var post_data = {
                        'dept': data_val1,
                }

                $.ajax({
                        type: "POST",
                        url: base_url+'nba_sar/nba_sar/display_dept_vision_mission',
                        data: post_data,
                        async:false,
                        success: function(msg) {
                                document.getElementById('dept_mission_vw_id').innerHTML = msg;
                        }
                });
		
        });*/
        
       
    var nba_guideline_data = '';
    //Function is to get guideline content.
    $('#view').on('click', '.edit_standard_content', function(e) {
        e.preventDefault();
        var input_id = $(this).attr('id');
        $('#nba_modal_1').modal('show');
        $('#nba_input_data').val(input_id);
        var postdata={
            'input_id' : input_id
        };
        $.ajax({
            data:postdata,
            async:false,
            type:'post',
            url: $('#get_baseurl').val()+"nba_sar/nba_sar/get_standard_content",
            success: function(std_content){
                $('#nba_modal_1 > .modal-body').html('');
                var data = $.parseJSON(std_content);
                nba_guideline_data = data['guideline'];
                $('.modal-body').append('<input type="hidden" id="nba_input_data" name="nba_input_data" value="'+input_id+'" /><textarea name="nba_standard_content" row="10" cols="20" class="standard_content_input_tinymce" >'+nba_guideline_data+'</textarea>');
                tinymce.init({
                    selector: "textarea",
                    relative_urls: false,
                    plugins: [
                    "advlist autolink lists charmap preview ",
                    "searchreplace visualblocks fullscreen",
                    "table contextmenu paste moxiemanager jbimages"
                    ],
                    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
                    setup: function (editor) {
                        editor.on('change', function () {
                            tinymce.triggerSave();
                        });
                    }
                });
            }
        });
    });
        
    //Function is to save guideline content.
    $('#nba_modal_1').on('click', '.standard_content_save', function(e) {
        e.preventDefault();
        var input_id = $('#nba_input_data').val();
        var attr_id = $(this).attr('attr');
        var guideline = tinymce.activeEditor.getContent();
        var postdata={
            'input_id' : input_id,
            'guideline' : guideline,
            'attr_id' : attr_id
        };
        $.ajax({
            data:postdata,
            async:false,
            type:'post',
            url: $('#get_baseurl').val()+"nba_sar/nba_sar/set_standard_content",
            success: function(msg){
                $('#nba_modal_1').modal('toggle');
            }
        });
    });

});
//File ends here.