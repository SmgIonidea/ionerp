//examination.js

$(function(){
    //Function is empty div
    function clear_all() {
        $('#nba_qp_div').html('');
        $('#co_outcome_chart').html('')
        $('#coplannedcoveragesdistribution > thead').html('');
        $('#coplannedcoveragesdistribution > tbody').html('');
        $('#bloom_level_marks_chart').html('');
        $('#bloomslevelplannedmarksdistribution > thead').html('');
        $('#bloomslevelplannedmarksdistribution > tbody').html('');
    }
    
    //Function is to fetch curriclum courses.  - section 2.2.2
    $('#view').on('change','#curriculum_2_2_2', function() {
        clear_all();
        var base_url = $('#get_base_url').val();
        var curriculum = $(this).val();	
        
        var post_data = {
            'curriculum' : curriculum
        }

        if(curriculum){
            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/display_curriculum_courses',
                async:false,
                data: post_data,
                success: function(courses) {
                    $('#curriculum_course_examination').html(courses);
                }
            });
        }else{
            $('#curriculum_course_examination').html("<option value=''>Select Course</option>");
            $('#course_qp_examination').html("<option value=''>Select Question Paper</option>");
        }
    });
        
    //Function is to fetch course question paper. - section 2.2.2
    $('#view').on('change','#curriculum_course_examination', function() {
        clear_all(); 
        var base_url = $('#get_base_url').val();
        var curriculum = $('#curriculum_2_2_2').val();    
        var selected_course = $('#curriculum_course_examination').val(); 
        
        if(selected_course){
            var element = $("option:selected", this);
            var course_data = (element.attr('attr')).split('/');
            var course = course_data[0];
        
            var post_data = {
                'curriculum' : curriculum,
                'course' : course
            }

            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/display_course_qp',
                async:false,
                data: post_data,
                success: function(question_paper) {
                    $('#course_qp_examination').html(question_paper);
                }
            });
        }else{
            $('#course_qp_examination').html("<option value=''>Select Question Paper</option>");
        }
    });
        
    //Function is to display question paper.  - section 2.2.2
    $('#view').on('click','#generate_2_2_2_report', function() {
        clear_all();
        var base_url = $('#get_base_url').val();
        var curriculum = $('#curriculum_2_2_2').val();
        var program = $('#pgm_id').val();
        var selected_qp = $('#course_qp_examination').val();
        var name = $('#curriculum_course_examination').attr('name');   
        var name_value = name.replace('curriculum_list__', '');
        var post_data = {
            'curriculum' : curriculum,
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
        
        if(selected_qp) {
            var crs_element = $("option:selected", $('#curriculum_course_examination'));
            var course_data = crs_element.attr('attr').split('/');
            var course = course_data[0];
            var term = course_data[1];
            var qp_element = $("option:selected", $('#course_qp_examination'));
            var qp_data = qp_element.attr('attr').split('/');
            var qpd_id = qp_data[0];
            var qp_type = qp_data[1];
            var post_data = {
                'pgmtype' : program,
                'crclm_id' : curriculum,
                'crs_id' : course,
                'term_id' : term,
                'qp_type' : qp_type,
                'qpd_id' : qpd_id,
                'nba_flag' : 1
            }
        
            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/generate_model_qp_modal_tee',
                async:false,
                data: post_data,
                success: function(question_paper) {
                    tiny_init();
                    $('#question_paper_display_div').html(question_paper);
                    $('#qp_table_data').dataTable().fnDestroy();

                    $('#qp_table_data').dataTable({
                        "fnDrawCallback" : function () {
                            $('.group').parent().css({
                                'background-color' : '#C7C5C5'
                            });
                        },
                        "bPaginate" : false,
                        "bFilter" : false,
                        "bInfo" : false,
                        "aaSorting" : [[1, 'asc']]
                    }).rowGrouping({
                        iGroupingColumnIndex : 0,
                        bHideGroupingColumn : true
                    });
                }
            });
            
            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/course_co_planned_coverage',
                async:false,
                data: post_data,
                success: function(crs_outcome) {
                    var data = $.parseJSON(crs_outcome);
                    var clo_code = data['clo_code'].split(",");
                    var clo_total_marks_dist = data['total_marks'].split(",");
                    var clo_percentage_dist = data['percentage'].split(",");
                    var clo_statement_dist = data['clo_stmt'].split(",");
                    actual_data = new Array();
                    var i = 0;
                    var j = 0;
                    $.each(clo_code, function () {
                        var clo_code_data = clo_code[i];
                        var clo_percentage_dist_data = clo_percentage_dist[i];
                        data = new Array();
                        data.push(clo_code_data, Number(clo_percentage_dist_data));
                        i++;
                        actual_data[j++] = data;
                    });
                    jQuery.jqplot('co_outcome_chart', [actual_data], {
                        title : {
                            text : '', 
                            show : true
                        },
                        seriesDefaults : {
                            renderer : jQuery.jqplot.PieRenderer,
                            rendererOptions : {
                                fill : true,
                                showDataLabels : true,
                                sliceMargin : 4,
                                lineWidth : 5,
                                dataLabelFormatString : '%.2f%'
                            }
                        },
                        legend : {
                            show : true,
                            location : 'ne'
                        },
                        highlighter : {
                            show : true,
                            tooltipLocation : 's',
                            tooltipAxes : 'y',
                            useAxesFormatters : false,
                            tooltipFormatString : '%s',
                            tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
                                return clo_statement_dist[pointIndex];
                            }
                        }
                    });
                    var imgData = $('#co_outcome_chart').jqplotToImageStr({}); // given the div id of your plot, get the img data
                    $('#co_graph').val(imgData);// append the img to the DOM

                    $('#coplannedcoveragesdistribution > thead:first').html('<tr><td class="orange"><center><b>COs Level</b></center></td><td class="orange"><center><b>Planned Marks</b></center></td><td class="orange"><center><b>Planned Distribution</b></center></td></tr>');
                    var m = 0;
                    $.each(clo_code, function () {
                        $('#coplannedcoveragesdistribution > tbody:last').append('<tr><td><center>' + clo_code[m] + '</center></td><td><center>' + clo_total_marks_dist[m] + '</center></td><td><center>' + clo_percentage_dist[m] + ' %</center></td></tr>');
                        m++;
                    });
                }
            });
            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/course_bloom_level_marks_distribution',
                async:false,
                data: post_data,
                success: function(bloom_level) {
                    var data = $.parseJSON(bloom_level);
                    var blooms_level = data['blooms_level_marks_dist'].split(",");
                    var total_marks_dist = data['total_marks_marks_dist'].split(",");
                    var percentage_dist = data['percentage_distribution_marks_dist'].split(",");
                    var bloom_lvl_marks_desc = data['bloom_level_marks_description'].split(",");
                    var actual_data = new Array();
                    var i = 0;
                    var j = 0;
                    $.each(blooms_level, function () {
                        var bloom_lvl = blooms_level[i];
                        var percent_distr = percentage_dist[i];
                        data = new Array();
                        data.push(bloom_lvl, Number(percent_distr));
                        i++;
                        actual_data[j++] = data;
                    });
                    jQuery.jqplot('bloom_level_marks_chart', [actual_data], {
                        title : {
                            text : '', //Blooms Level Planned Marks Distribution',
                            show : true
                        },
                        seriesDefaults : {
                            renderer : jQuery.jqplot.PieRenderer,
                            rendererOptions : {
                                fill : true,
                                showDataLabels : true,
                                sliceMargin : 4,
                                lineWidth : 5,
                                dataLabelFormatString : '%.2f%'
                            }
                        },
                        legend : {
                            show : true,
                            location : 'ne'
                        },
                        highlighter : {
                            show : true,
                            tooltipLocation : 's',
                            tooltipAxes : 'y',
                            useAxesFormatters : false,
                            tooltipFormatString : '%s',
                            tooltipContentEditor : function (str, seriesIndex, pointIndex, plot) {
                                return bloom_lvl_marks_desc[pointIndex];
                            }
                        }
                    });
                    var imgData = $('#bloom_level_marks_chart').jqplotToImageStr({}); // given the div id of your plot, get the img data
                    $('#bloom_graph').val(imgData);// append the img to the DOM
                    
                    $('#bloomslevelplannedmarksdistribution > thead:first').html('<tr><td class="orange"><center><b>Blooms Level</b></center></td><td class="orange"><center><b>Marks Distribution</b></center></td><td class="orange"><center><b>% Distribution</b></center></td></tr>');
                    var l = 0;
                    $.each(blooms_level, function () {
                        $('#bloomslevelplannedmarksdistribution > tbody:last').append('<tr><td><center>' + blooms_level[l] + '</center></td><td><center>' + total_marks_dist[l] + '</center></td><td><center>' + percentage_dist[l] + ' %</center></td></tr>');
                        l++;
                    });
                }
            });
            post_data = {
                'co_graph':$('#co_graph').val(),
                'bloom_graph':$('#bloom_graph').val(),
                'nba_sar_id':$('#nba_sar_id').val(),
                'nba_report_id':$("#node_id").val()
            }
            $.ajax({
                type:"POST",
                url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/create_graph_image',
                async:false,
                data: post_data,
                success: function(data) {
                    
                }
            });
        }
    });
    
    //Report 2.2.3 
    //Function is to fetch term list.- section 2.2.3
    $('#view').on('change','#curriculum_2_2_3_list', function() { 
        $("#student_quality_project_div").html('');
        var base_url = $('#get_base_url').val();
        var name = $(this).attr('name'); 
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $(this).val();	

        var post_data = {
            'curriculum' : curriculum,
            'view_nba_id' : name_value,
            'view_form' : $('#view_form .filter').serializeArray()
        }

        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/get_term_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#term_2_2_3_list').empty();
                $('#term_2_2_3_list').html(msg);
            }
        });
    });
		
    //Function is to fetch course list.- section 2.2.3
    $('#view').on('change','#term_2_2_3_list', function() {
        $("#student_quality_project_div").html('');
        var base_url = $('#get_base_url').val();
        var crclm_id = $('#curriculum_2_2_3_list').val();
        var term_id = $(this).val();
        var post_data = {
            'term_id' : term_id,
            'crclm_id' : crclm_id
        }
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/get_course_list',
            async:false,
            data: post_data,
            success: function(msg) {
                $('#course_2_2_3_list').html(msg);
            }
        });
    });		
    
    //Function is to display quality of student project.- section 2.2.3
    $('#view').on('click','#generate_2_2_3_report', function() {
        $('#student_quality_project_div').empty();
        var base_url = $('#get_base_url').val();
        var name = $('#curriculum_2_2_3_list').attr('name'); 
        var name_value = name.replace('curriculum_list__', '');
        var curriculum = $('#curriculum_2_2_3_list').val();
        var course_id = $('#course_2_2_3_list').val();
        var crclm_id = $('#curriculum_2_2_3_list').val();
        var term_id = $('#term_2_2_3_list').val();

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
        var post_data = {
            'course_id' : course_id,
            'crclm_id' : crclm_id,
            'term_id' : term_id
        }
        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/get_quality_student_project',
            async:false,
            data: post_data,
            dataType: 'json',
            success: function(student_project_quality) {
                $('#student_quality_project_div').html(student_project_quality);
            }
        });
        
    });
             
    //Function is to display industry internship details - section 2.2.5
    $('#view').on('change','#curriculum_2_2_5', function() {
        $('#industry_intership_div').empty();
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

        $.ajax({
            type:"POST",
            url: base_url+'nba_sar/t2pharm_c2_teaching_learning_process/display_industry_internship',
            async:false,
            data: post_data,
            dataType: 'json',
            success: function(msg) {
                $('#industry_intership_div').html(msg['industry_internship']);
            }
        });
    });
});

//function initialize tinymce.
function tiny_init(){
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
//File ends here.