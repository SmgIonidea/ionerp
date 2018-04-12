/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    /**Prototype Js here for question crud operation**/

    var base_url = $('#get_base_url').val();
    var mcq = {
        maxQstn: 0,
        curQstn: 0,
        appendQstnDiv: 'question_holder_div',
        getMaxQstn: function () {
            console.log('get max qstn =', this.maxQstn);
            return this.maxQstn;
        },
        setMaxQstn: function () {
            this.maxQstn = parseInt($('#total_qstn').val());
            //console.log('get max qstn =', this.maxQstn);
        },
        modifyMaxQstn: function () {
            $('#total_qstn').val(this.maxQstn - 1);
            this.maxQstn--;
        },
        createQstnOrder: function () {
            var selectList = "<select name='question_" + this.curQstn + "_order' id='question_" + this.curQstn + "_order' class='input qstn_order_set'>";
            for (var x = 1; x <= this.maxQstn; x++) {
                selectList += "<option>" + x + "</option>";
            }
            selectList += "</select>";            
            return selectList;
        },
        modifyQstnOrder: function () {

        },
        createQstn: function () {
            var qstnHtml = qstnTemplate();
            $('#' + this.appendQstnDiv).append(qstnHtml);
            tiny_init('#question_'+this.curQstn);
        },
        allQstn: function () {
            $('#' + this.appendQstnDiv).html('');
            for (var x = 0; x < this.maxQstn; x++) {
                this.curQstn=x;
                this.createQstn();
            }           
        },
        deleteQstn: function () {
        },
    };

$(function(){
    tiny_init('.mcqTinyMce');
});
function ajaxTiny(){
    $(function(){
        tiny_init('.mcqTinyMce');
    });
}
$(function () {
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOn: "button",
        buttonImage: base_url + "/twitterbootstrap/img/calendar.gif",
        buttonImageOnly: true
    });
});


//    var max = 0;
//    $('.btn_question_resp').each(function(){
//        var num = parseInt($(this).attr('question'), 10);
//        if (num > max) { max = num; }
//    });
//    //alert(max);
//    $('#maxQustnId').val(max);

$('.datepicker').datepicker({
    format: 'mm/dd/yyyy'

});
$('.show-datepicker').click(function () {
    $('.datepicker').datepicker('show', {
        format: 'mm/dd/yyyy'
    });
});
    
    $('.total_qstn_generate').live('blur', function () {
        mcq.setMaxQstn();
        //mcq.createQstnOrder();
        mcq.allQstn();
    });
    function qstnTemplate() {
        //var qstnOrder=mcq.createQstnOrder();
        var qstn = '<div class="question_panel bs-docs-example">'
                /*+ '<div class="control-group">'
                + '    <label class="control-label" for="question_1_order">Question Order:<font color="red"> * </font></label>'
                + '    <div id="question_1_order_div" class="controls">'
                +          qstnOrder
                +       '</div>'
                + '</div>'*/
                + '<div class="span2">'
                + '    <label class="" for="qualification">Question:<font color="red"> * </font></label>'
                + '</div>'
                + '<div class="control-group">'
                + '    <div class="span12">'
                + '       <textarea name="question_'+mcq.curQstn+'" id="question_'+mcq.curQstn+'" class="mcqTinyMce remove_err"></textarea>'
                + '    </div>'
                + '</div>'
                + '<h4> Answer Section</h4>'
                + '<p class="margin-top10"></p>'
                + '<div class="row-fluid standard_option_div_'+mcq.curQstn+'" id="standard_option_div_'+mcq.curQstn+'"></div>'
                + '<div class="custom_option_div_'+mcq.curQstn+'" id="custom_option_div_'+mcq.curQstn+'">'
                + '    <div class="option-div_1">'
                + '        <div class="span1" style="width:3px; margin-top: 5px">'
                + '            <font color="red">*</font>'
                + '        </div>'
                + '        <div class="row-fluid">'
                + '            <div class="span1 questionChoiceBox_1" style="width:24px; padding-left: 11px">'
                + '                <input type="radio" name="inpRadio" disabled="disabled" class="option-type-box-1">&nbsp;&nbsp;</div>'
                + '            <div class="span10">'
                + '                <input type="text" name="qstn_'+mcq.curQstn+'_option_input_box_1" value="" id="qstn_'+mcq.curQstn+'_option_input_box_1" maxlength="100" class="input option-input-box char-counter remove_err"><a href="http://localhost/ioncudos_v2/#" class="Delete mcq_delete_custom_option" id="delete_me_'+mcq.curQstn+'_1"><img src="twitterbootstrap/css/images/remove_ico.png"></a> '
                + '                <span id="char_span_qstn_'+mcq.curQstn+'_option_input_box_1">0 of 100.</span>'
                + '            </div>'
                + '            <br>'
                + '            <span id="errorspan_qstn_'+mcq.curQstn+'_option_input_box_1" class="error help-inline"></span>'
                + '        </div>'
                + '    </div>'
                + '    <div class="option-div_1">'
                + '        <div class="span1" style="width:3px; margin-top: 5px">'
                + '            <font color="red">*</font>'
                + '        </div>'
                + '        <div class="row-fluid">'
                + '            <div class="span1 questionChoiceBox_1" style="width:24px; padding-left: 11px">'
                + '                <input type="radio" name="inpRadio" disabled="disabled" class="option-type-box-1">&nbsp;&nbsp;</div>'
                + '            <div class="span10">'
                + '                <input type="text" name="qstn_'+mcq.curQstn+'_option_input_box_2" value="" id="qstn_'+mcq.curQstn+'_option_input_box_2" maxlength="100" class="input option-input-box char-counter remove_err"><a href="http://localhost/ioncudos_v2/#" class="Delete mcq_delete_custom_option" id="delete_me_'+mcq.curQstn+'_2"><img src="twitterbootstrap/css/images/remove_ico.png"></a> '
                + '                <span id="char_span_qstn_'+mcq.curQstn+'_option_input_box_2">0 of 100.</span>'
                + '            </div>'
                + '            <br>'
                + '            <span id="errorspan_qstn_'+mcq.curQstn+'_option_input_box_2" class="error help-inline"></span>'
                + '        </div>'
                + '    </div>'
                + '    <div class="option-div_1">'
                + '        <div class="span1" style="width:3px; margin-top: 5px">'
                + '            <font color="red">*</font>'
                + '        </div>'
                + '        <div class="row-fluid">'
                + '            <div class="span1 questionChoiceBox_1" style="width:24px; padding-left: 11px">'
                + '                <input type="radio" name="inpRadio" disabled="disabled" class="option-type-box-1">&nbsp;&nbsp;</div>'
                + '            <div class="span11">'
                + '                <input type="text" name="qstn_'+mcq.curQstn+'_option_input_box_3" value="" id="qstn_'+mcq.curQstn+'_option_input_box_3" maxlength="100" class="input option-input-box char-counter remove_err"><a href="http://localhost/ioncudos_v2/#" class="Delete mcq_delete_custom_option" id="delete_me_'+mcq.curQstn+'_3"><img src="twitterbootstrap/css/images/remove_ico.png"></a> '
                + '                <span id="char_span_qstn_'+mcq.curQstn+'_option_input_box_3">0 of 100.</span>'
                + '            </div>'
                + '            <br>'
                + '            <span id="errorspan_qstn_'+mcq.curQstn+'_option_input_box_3" class="error help-inline"></span>'
                + '        </div>'
                + '    </div>'
                + '    <div class="option-div_1">'
                + '        <div class="span1" style="width:3px; margin-top: 5px">'
                + '            <font color="red">*</font>'
                + '        </div>'
                + '        <div class="row-fluid">'
                + '            <div class="span1 questionChoiceBox_1" style="width:24px; padding-left: 11px">'
                + '                <input type="radio" name="inpRadio" disabled="disabled" class="option-type-box-1">&nbsp;&nbsp;</div>'
                + '            <div class="span11">'
                + '                <input type="text" name="qstn_'+mcq.curQstn+'_option_input_box_4" value="" id="qstn_'+mcq.curQstn+'_option_input_box_4" maxlength="100" class="input option-input-box char-counter remove_err"><a href="http://localhost/ioncudos_v2/#" class="Delete mcq_delete_custom_option" id="delete_me_'+mcq.curQstn+'_4"><img src="twitterbootstrap/css/images/remove_ico.png"></a>'
                + '                <span id="char_span_qstn_'+mcq.curQstn+'_option_input_box_4">0 of 100.</span>'
                + '            </div>'
                + '            <br>'
                + '            <span id="errorspan_qstn_'+mcq.curQstn+'_option_input_box_4" class="error help-inline"></span>'
                + '        </div>'
                + '    </div>'
                + '    <div class="row-fluid">'
                + '        <div class="span12 pull-right">'
                + '            <a href="http://localhost/ioncudos_v2/#" class="btn btn-primary pull-right add_custom_options add_custom_options_'+mcq.curQstn+'" qstn="'+mcq.curQstn+'" option_count="5"><i class="icon-plus-sign icon-white"></i>Add Options</a></div>'
                + '    </div>'
                + '</div>'
                + '</div>';
        return qstn;
    }


    function tiny_init(selectorId) {
        
         //tinyMCE.execCommand('mceRemoveControl', true, selectorId);
         //tinyMCEkilled = true;
        tinymce.init({
            selector: selectorId,
            //plugins: "paste",
             plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table contextmenu paste jbimages"
  ],
            paste_data_images: true,
            setup: function (ed) {
                ed.on('change', function (e) {
                    var id = $(this).attr('id');
                    var id_val = id.split("_");
                    $('#error_msg_' + id_val[2]).empty();
                });
            },
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
			relative_urls: false
                    //height : 300;			
        });
        //tinymce.execCommand('mceAddControl',true, selectorId);
    }

    /***** DECLARE COMMON VARIABLES ******/

    var param = controller = method = post_data = '';
    var base_url = $('#get_base_url').val();
    var sub_path = 'mcq/';
    var form_method = 'POST';
    var data_type = 'JSON';
    var reloadMe = 1;


    $('.mcq_program_list_by_dept').change(function () {
        var dept_id = $(this).val();
        controller = 'mcqs/';
        method = 'create_mcq';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'dept_id': dept_id,
            'flag': 'program'
        }
        genericAjax('program_type');
    });
    $('.mcq_crclm_list_by_prgm').change(function () {
        var program_id = $(this).val();
        controller = 'mcqs/';
        method = 'create_mcq';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'program_id': program_id,
            'flag': 'curriculum_list'
        }
        genericAjax('curriculum');
    });
    $('.mcq_course_list_by_crclm').change(function () {
        var program_id = $(this).val();
        var dept_id = $('.mcq_program_list_by_dept').val();
        controller = 'mcqs/';
        method = 'create_mcq';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'program_id': program_id,
            'dept_id': dept_id,
            'flag': 'course'
        }
        genericAjax('course');
    });

    var optionNo = 4;
    var optionLimit = 5;
//Add custom options
    $('.add_custom_options').live('click', function (e) {
        e.preventDefault();

        var option_count = parseInt($(this).attr('option_count'));
        qstnAttrNo = $(this).attr('qstn');
        optionNo = option_count;

        var rad = "<input type='radio' title='Select if this is correct answer' name='correct_answer' value='1'>";
        var eleBox = rad;

        if (optionNo <= optionLimit) {
            
            var ele = "<div class='option-div_" + qstnAttrNo + "'>"
                    + "   <div class='span1' style='width:3px; margin-top: 5px'></div>"
                    + "   <div class='row-fluid'>"
                    + "       <div style='width:75px; padding-left:11px' class='span2 questionChoiceBox_"+optionNo+"' >" + eleBox + " Option: <font color='red'>*</font></div>"
                    + "       <div class='span10'>"
                    + "           <input type='text' class='input option-input-box char-counter remove_err' maxlength='100' id='qstn_option_input_box_" + optionNo + "' value='' name='qstn_option_input_box_" + optionNo + "'>"
                    + "           <a id='delete_me_" + optionNo + "' class='Delete mcq_delete_custom_option' href='#'>"
                    + "           <img src='twitterbootstrap/css/images/remove_ico.png'></a>"
                    + "           <span id='char_span_qstn_option_input_box_" + optionNo + "'>0 of 100.</span></div><br>"
                    + "           <span class='error help-inline' id='errorspan_qstn_option_input_box_" + optionNo + "'></span>"
                    + "   </div>"
                    + "</div>";
            $(ele).insertBefore(this);
            optionNo++;
            $(this).attr('option_count', optionNo);
        } else {
            $('#warning_message').text('Maximum 5 options are allowed here !');
            $('.mcq_warning_dialog').trigger('click');
        }

    });

//Remove custom options and rearrange option nos.
    $('.mcq_delete_custom_option').live('click', function (e) {
        e.preventDefault();
        var parents = $(this).parent().attr('class');
        var optionId = $(this).attr('id');
        var optionIdArr = optionId.split('_');
        var delOptionNo = parseInt(optionIdArr[(optionIdArr.length - 1)]);
        var qstnAttrNo = $(this).attr('qstn');
        var optionNo = parseInt($(this).attr('option_count'));

        if (delOptionNo <= 2) {
            $('#warning_message').text('At least 2 options required !');
            $('.mcq_warning_dialog').trigger('click');
            return false;
        }
        $(this).parent().parent().parent().remove();
        optionNo--;
        $('.add_custom_options_' + qstnAttrNo).attr('option_count', optionNo);
    });

    $('.char-counter').each(function () {
        var len = $(this).val().length;
        var max = parseInt($(this).attr('maxlength'));
        var spanId = 'char_span_' + $(this).attr('id');
        $('#' + spanId).text(len + ' of ' + max + '.');
    });
    $('.char-counter').live('keyup', function () {

        var max = parseInt($(this).attr('maxlength'));
        var len = $(this).val().length;
        var spanId = 'char_span_' + $(this).attr('id');
        if (len >= max) {
            $('#' + spanId).css('color', 'red');
            $('#' + spanId).text(' you have reached the limit');
        } else {
            $('#' + spanId).css('color', '');
            $('#' + spanId).text(len + ' of ' + max + '.');
        }
    });
    $('.char-counter').live('blur', function () {
        var max = parseInt($(this).attr('maxlength'));
        var len = $(this).val().length;
        var spanId = 'char_span_' + $(this).attr('id');
        if (len >= max) {
            $('#' + spanId).css('color', 'red');
            $('#' + spanId).text(' you have reached the limit');
        } else {
            $('#' + spanId).text(len + ' of ' + max + '.');
            $('#' + spanId).css('color', '');
        }
        $(this).text($(this).val());
    });

	$('#view_mcq_stats_plot_form').on('change', '.report_type_class', function () {
		var report_type_val = $('[class="report_type_class"]:checked').val();
		if(report_type_val == 1){
			$('#mcq_report_export_div').css({"display":"inline"});
		}else{
			$('#mcq_report_export_div').css({"display":"none"});
		}
		var mcq_id = $('#mcq_id').val();
		controller = 'mcqs/';
        method = 'get_mcq_stats';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'report_type_val': report_type_val,
            'mcq_id': mcq_id
        }
        genericAjax('mcq_stats_data');
	});
	
	$('#view_mcq_stats_plot_form').on('click','#mcq_report_export',function(){
		var survey_information_val = $('#survey_information').clone().html();
		var actual_data_val = $('#export_data_div').clone().html();
		$('#mcq_report_hidden').val(survey_information_val+'<br />'+actual_data_val);
		$('#view_mcq_stats_plot_form').submit();
	});
	
	$('.cookie_department_deptid').change(function () {
		var dept_id = $(this).val();
		$.cookie('cookie_department_deptid', dept_id, {expires: 90, path: '/'});
		
		controller = 'mcqs/';
		method = 'getProgramList';
		data_type = 'HTML';
		reloadMe = 0;
		post_data = {
			'dept_id': dept_id,
			'flag': 'program'
		}
		genericAjax('program_type');
	});
	
	$('.cookie_program_pgm_id').change(function () {
		var program_id = $(this).val();
		$.cookie('cookie_program_pgm_id', program_id, {expires: 90, path: '/'});
		
		controller = 'mcqs/';
		method = 'getCurriculumList';
		data_type = 'HTML';
		reloadMe = 0;
		post_data = {
			'program_id': program_id,
			'flag': 'curriculum'
		}
		genericAjax('curriculum');
	});
	
	$('.cookie_curriculum_curr_id').change(function () {
		form_method = 'POST';
		var dept_id = $('#department').val();
		var program_id = $('#program_type').val();
		var curr_id = $('#curriculum').val();
		$.cookie('cookie_curriculum_curr_id', curr_id, {expires: 90, path: '/'});
       
		if(curr_id != '0'){
		controller = 'mcqs/';
		method = 'getMCQList';
		data_type = 'HTML';
		reloadMe = 0;
		post_data = {
			'dept_id': dept_id,
            'prgm_id': program_id,
            'crclm_id': curr_id,
            'flag': 'filter_mcq_list'
		}
		dataTableParam=[];
		dataTableParam['columns']=[
            {"sTitle": "MCQ Title", "mData": "mcq_title"},
            {"sTitle": "Course Code", "mData": "crs_code"},
            {"sTitle": "Course Title", "mData": "crs_title"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "Department", "mData": "dept_acronym"},
            {"sTitle": "Conducted By", "mData": "user"},
            {"sTitle": "Assign", "mData": "assign"}
            ];
		dataTableAjax(post_data,dataTableParam,setAjaxSelectBox,param);
		dataTableParam=null;
		}
	});
        
        $('.cookie_curriculum_id').change(function () {
		form_method = 'POST';
		var dept_id = $('#department').val();
		var program_id = $('#program_type').val();
		var curr_id = $('#curriculum').val();
		$.cookie('cookie_curriculum_curr_id', curr_id, {expires: 90, path: '/'});
		
		controller = 'mcqs/';
		method = 'getMCQRepotsList';
		data_type = 'HTML';
		reloadMe = 0;
		post_data = {
			'dept_id': dept_id,
            'prgm_id': program_id,
            'crclm_id': curr_id,
            'flag': 'filter_mcq_list'
		}
		dataTableParam=[];
		dataTableParam['columns']=[
            {"sTitle": "MCQ Title", "mData": "mcq_title"},
            {"sTitle": "Course Code", "mData": "crs_code"},
            {"sTitle": "Course Title", "mData": "crs_title"},
            {"sTitle": "Start Date", "mData": "start_date"},
            {"sTitle": "Department", "mData": "dept_acronym"},
            {"sTitle": "Conducted By", "mData": "user"},
            ];
		dataTableAjax(post_data,dataTableParam,setAjaxSelectBox,param);
		dataTableParam=null;
	});
		
	$('.assign_mcq').live('click',function () {
		var mcq_id = $(this).attr('id');		
		controller = 'mcqs/';
		method = 'getMCQData';
		data_type = 'HTML';
		reloadMe = 0;
		post_data = {
			'mcq_id': mcq_id,
            'flag': 'mcq_data'
		}
		genericAjax('mcq_data_section');
		$('#assignMCQModal').modal('show');
	});
	
	$('#mcq_data_section').on('change','#department_mcq',function(){
		var dept_id = $(this).val();		
		controller = 'mcqs/';
		method = 'getDeptUsers';
		data_type = 'HTML';
		reloadMe = 0;
		post_data = {
			'dept_id': dept_id,
            'flag': 'dept_user'
		}
		genericAjax('mcq_user_section');
	});
	
	$('.assign-mcq-ok').click(function(){
            var mcq_id = $('#mcq_id').val();
            var department_mcq = $('#department_mcq').val();
            var dept_user_mcq = $('#dept_user_mcq').val();
            controller = 'mcqs/';
            method = 'saveAssignedMCQ';
            reloadMe = 1;
            post_data = {
                'mcq_id': mcq_id,
                'department_mcq': department_mcq,
                'dept_user_mcq': dept_user_mcq
            }
            genericAjax();
	});
        
        $('.mapping_op').change(function(){
            var mapping_op = $(this).val();
            //alert(mapping_op);
            controller = 'mcqs/';
            method = 'mapping_op';
            data_type = 'HTML';
            reloadMe = 0;
            post_data = {
                'curr_id': 5
            };
            genericAjax('mapping_table');
        });
        
        $('.dlt_mcq_questn').live('click', function(){
            var qId = $(this).val();
            event.preventDefault();
            controller = 'mcqs/';
            method = 'delete_mcq_question';
            data_type = 'HTML';
            reloadMe = 1;
            post_data = {
                'qId': qId
            };
            genericAjax(null,callbk_delete_mcq_qustn);
        });
        function callbk_delete_mcq_qustn(){
            //alert('hi');
            
        }
	
    function genericAjax(divTargetId, callBack, callBackparam, loader) {
        if (loader) {
            $('#loading').show();
        }
        if (!callBack) {
            callBack = unDefinedCallBack;
        }
        if (!callBackparam) {
            callBackparam = 0;
        }
        $.ajax({
            type: form_method,
            url: base_url + sub_path + controller + method + '/' + param,
            data: post_data,
            datatype: data_type,
            success: function (result) {
                if (reloadMe) {
                    location.reload();
                }
                if (divTargetId) {
                    $('#' + divTargetId).html(result);
                }
                if (loader) {
                    $('#loading').hide();
                }
                //tinyMCE.execCommand('mceAddControl', false, 'mcq_question');
            },
            failure: function (msg) {
                if (divTargetId) {
                    $('#' + divTargetId).html(msg);
                }
                if (loader) {
                    $('#loading').hide();
                }
            }
        }).done(function () {
            callBack(callBackparam);
            if (loader) {
                $('#loading').hide();
            }
        });

    }
    function dataTableAjax(post_data, dataTableParam, callBk, callBkParam) {
        $.ajax({type: "POST",
            url: base_url + sub_path + controller + method + '/' + param,
            data: post_data,
            dataType: 'json',
            success: function (result) {
                populateDataTable(result, dataTableParam);
            }
        }).done(function () {
            if (callBk) {
                callBk(callBkParam);
            }
        });
    }

    function populateDataTable(data, dataTableParam) {

        if (!dataTableParam['paginationType']) {
            dataTableParam['paginationType'] = "full_numbers";
        }
        if (!dataTableParam['displayLength']) {
            dataTableParam['displayLength'] = 20;
        }
		var visibleRow = [];
		var isVisible = true;
		if($('#role_id').val()=='1') { 
			visibleRow = [4,5,6];
			isVisible = false;
		}
        $('#example').dataTable().fnDestroy();
        $('#example').dataTable({
            "sPaginationType": dataTableParam['paginationType'],
            "iDisplayLength": dataTableParam['displayLength'],
            "aoColumns": dataTableParam['columns'],
            "aaData": data,
			"aoColumnDefs": [
                { "bVisible": isVisible, "aTargets":visibleRow}
				
			], 
            "sPaginationType": "bootstrap"
        });
    }
    function setAjaxSelectBox(param) {
        var selected = param['selected'];
        var eleId = param['ele_id'];
        $('#' + eleId + ' option[value="' + selected + '"]').attr('selected', 'selected');
    }
    /*
     * Don not remove below function
     * its a default callback function for genericAjax()
     */
    function unDefinedCallBack() {

    }
    
    function ajax_tiny(){
        tinyMCE.init({ 
            mode : "exact", 
            elements : "mcq_question",
            plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table contextmenu paste jbimages"
  ],
            paste_data_images: true,
            setup: function (ed) {
                ed.on('change', function (e) {
                    var id = $(this).attr('id');
                    var id_val = id.split("_");
                    $('#error_msg_' + id_val[2]).empty();
                });
            },
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
			relative_urls: false
                    //height : 300;
        }); 
        tinyMCE.execCommand('mceAddControl', false, "mcq_question");
    }

    $('.btn_question_no').live('click', function () {
        var questionNo = $(this).attr('question');
        $('.btn_question_no').each(function(){ $(this).removeClass('activeQustn');});
        $(this).addClass('activeQustn');
        tinyMCE.execCommand('mceFocus', false, 'mcq_question');                    
        tinyMCE.execCommand('mceRemoveControl', false, 'mcq_question');
        controller = 'mcqs/';
        method = 'edit_question';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'question_id': questionNo
        };
        genericAjax('comp_question_panel',ajax_tiny);
    });
    
    $('.btn_question_resp').live('click',function(questionNo){
        questionNo = $(this).attr('question'); 
        $('.btn_question_resp').each(function(){ $(this).removeClass('activeQustn');});
        $(this).addClass('activeQustn');
        
        controller = 'response/';
        method = 'resp_question';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'question_id': questionNo
        };
        genericAjax('resp_question_panel');
    });
    
    $('#response_next').live('click', function(){
        var qId = $('#question_id').val();
        $("button[question='"+qId+"']").addClass('answeredQustn');
        var answerVal = "";
        var selected = $("input[type='radio'][name='correct_answer']:checked");
        if (selected.length > 0) {
            answerVal = selected.val();
        }
        var studId = $('#student_id').val();
        var mcqId = $('#mcqId').val();
        var sub = $(this).val();
        var nextQuestion = $('#nextQuestion').val();
        
        var respId = $('#respId').val();
        controller = 'response/';
        method = 'save_response';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'mcq_question_id': qId,
            'mcq_answer_id': answerVal,
            'student_id': studId,
            'mcq_id': mcqId,
            'mcq_response_id': respId
        };
        
        if(typeof nextQuestion === 'undefined'){
            genericAjax('loadRespId');
        }else{
           genericAjax('',respCallBack,nextQuestion); 
        }
    });
    
    $('#response').live('click', function(){
        var qId = $('#question_id').val();
        $("button[question='"+qId+"']").addClass('answeredQustn');
        var answerVal = "";
        var selected = $("input[type='radio'][name='correct_answer']:checked");
        if (selected.length > 0) {
            answerVal = selected.val();
        }
        var studId = $('#student_id').val();
        var mcqId = $('#mcqId').val();
        
        var respId = $('#respId').val();
        controller = 'response/';
        method = 'save_response';
        data_type = 'HTML';
        reloadMe = 0;
        post_data = {
            'mcq_question_id': qId,
            'mcq_answer_id': answerVal,
            'student_id': studId,
            'mcq_id': mcqId,
            'mcq_response_id': respId
        };
        genericAjax('loadRespId'); 
    });
    
    function respCallBack(id){
        
        $('.btn_question_resp').each(function(){ $(this).removeClass('activeQustn');});
        $("button[question='"+id+"']").addClass('activeQustn');
                
        if(id>0){
            questionNo = id;
            controller = 'response/';
            method = 'resp_question';
            data_type = 'HTML';
            reloadMe = 0;
            post_data = {
                'question_id': questionNo
            };
            genericAjax('resp_question_panel');
        }else{
            exit;
        }
    }
  
});//end ready function


/****** DEFINE VALIDATION RULE ******/

$.validator.addMethod("alpha", function (value, element) {
    return this.optional(element) || /^[a-zA-Z ]+$/i.test(value);
}, "This field must contain only letters and space.");

$.validator.addMethod("alpha_numeric", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9 ]+$/i.test(value);
}, "This field must contain only letters numbers and space.");


$.validator.addMethod("noSpecialChars", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\_\.\-\s\'\ / \ + \&\,]+$/i.test(value);
}, "This field must contain only letters space, dots and underscore.");

$.validator.addMethod("noSpecialChars2", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\,\.\-\ \/\_]+$/i.test(value);
}, "This field must contain only letters, numbers, spaces and dashes.");

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");

$.validator.addMethod("selectOption", function (value, element) {
    return this.optional(element) || /^[1-9]+[0-9]*$/i.test(value);
}, "Please select an option.");

$.validator.setDefaults({
    submitHandler: function() { 
        mcq_question = tinyMCE.get('mcq_question').getContent();
        if(mcq_question == ''){
            var error = '<span style="color:red;" for="mcq_question" generated="true" class="help-inline">Please enter question</span>';
            $(error).insertAfter('#mcq_question');
        }else{
            //$('#add_questions').submit();
            form.submit();
        }
    }
});

$('#add_mcq').validate({
        rules: {
            department: {
                required: true,
                selectOption: true
            },
            program_type: {
                required: true,
                selectOption: true
            },
            curriculum: {
                required: true,
                selectOption: true
            },
            course: {
                required: true,
                selectOption: true
            },
            
            mcq_title: {
                required: true
            },
            total_questions: {
                required: true,
                onlyDigit: true
            },
            total_marks: {
                required: true,
                onlyDigit: true
            },
            allocated_time: {
                required: true
            },
            start_date: {
                required: true
            }
        },
        messages: {
            department: {
                required: "Please select an oiption"  
            },
            program_type: {
                required: "Please select an oiption"  
            },
            curriculum: {
                required: "Please select an oiption"  
            },
            course: {
                required: "Please select an oiption"  
            },
            mcq_title: {
                required: "Title  is required"
            },
            total_questions: {
                required: "Total question field is required",
                onlyDigit: "Only digits are allowed"
            },
            total_marks: {
                required: "Total question field is required",
                onlyDigit: "Only digits are allowed"
            },
            allocated_time: {
                required: "This field is required"
            },
            start_date: {
                required: "Select start date"
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
            //return false;
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        }
    });
    
    $('#add_questions').validate({
        
        rules: {
            mcq_question: {
                required: true,
                minlength: 15
            },
            qstn_option_input_box_1: {
                required: true
            },
            qstn_option_input_box_2: {
                required: true
            },
            qstn_option_input_box_3: {
                required: true
            },
            qstn_option_input_box_4: {
                required: true
            },
            qstn_option_input_box_5: {
                required: true
            }
        },
        messages: {
            mcq_question: {
                 required: "Please fill in your question",
                minlength: "Your question must consist of at least 15 characters"
            },
            qstn_option_input_box_1: {
                required: "Please enter option"
            },
            qstn_option_input_box_2: {
                required: "Please enter option"
            },
            qstn_option_input_box_3: {
                required: "Please enter option"
            },
            qstn_option_input_box_4: {
                required: "Please enter option"
            },
            qstn_option_input_box_5: {
                required: "Please enter option"
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
            //return false;
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        }
    });
    
    $('#student_info').validate({
        rules: {
            fname: {
                required: true,
                alpha_numeric: true
            },
            lname: {
                required: true,
                alpha_numeric: true
            },
            usn: {
                required: true,
                alpha_numeric: true
            }
        },
         messages: {
            fname: {
                required: "Please enter First Name",  
                alpha_numeric: "Must contain only letters and space."
            },
            lname: {
                required: "Please enter Last Name",  
                alpha_numeric: "Must contain only letters and space."
            },
            usn: {
                required: "Please enter USN",  
                alpha_numeric: "Must contain only letters and digits."
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
            //return false;
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        }
    });
	
	$('#mcq_data_section_frm').validate({
        rules: {
            department_mcq: {
                required: true,
                selectOption: true
            },
            dept_user_mcq: {
                required: true,
                selectOption: true
            }
        },
        messages: {
            department_mcq: {
                required: "Please select an oiption"  
            },
            dept_user_mcq: {
                required: "Please select an oiption"  
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('error');
            //return false;
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('error');
            $(element).addClass('success');
        }
    }); 
   
	 
    //to conduct mcq assign students
	$('.get_curr_id').live("click", function () {
            curriculum_id = $(this).attr('id');
            var mcq_id_student = $(this).attr('value');

            var post_data_pm = {
                'curriculum_id': curriculum_id,
                'mcq_id': mcq_id_student
            }

            $.ajax({type: "POST",
                url: base_url + 'mcq/mcqs/student_mcq_list',
                data: post_data_pm,
                success: function(msg) {
                    document.getElementById('student_mcq_modal').innerHTML = msg;
                }
            });
            $('#mcq_id').val(mcq_id_student);
            $('#myModal_students_list').modal('show');
	});	
	
	$('#all_students_id').live('click',function(){
            if($(this).is(':checked')){
                $('.student_name').each(function(){
                    $(this).prop('checked','checked');
                });		
            }else{
                $('.student_name').each(function(){
                    $(this).prop('checked',false);
                });	
            }
	});
	
	$(".student_name").live('click',function(){
            if($(".student_name").length==$(".student_name:checked").length){
                $("#all_students_id").attr("checked","checked");
            }else{
                $("#all_students_id").removeAttr("checked");
            }
	});
	
	$('.assign_student_mcq').click(function(e){
            $('#student_mcq_modal_frm').submit();
	});
	
	// to generate key
	$('.get_key').live("click", function () {
            mcq_id = $(this).attr('id');

            var post_data = {
                'mcq_id': mcq_id
            }
		
            $.ajax({type: "POST",
                url: base_url + 'mcq/mcqs/get_generated_link',
                data: post_data,
                success: function(msg) {
                    var link = base_url+'mcq/response/start/'+msg.trim();
                    var modalBody = "<h4>Please distribute below link to all students to start their MCQ Exam.</h4><b>"+link+"</b><br /><br /><p><b>Please instruct students to follow below steps :</b></p><p>1. Type the above URL in the browser</p><p>2. MCQ Exam details with Student Login details page will appear.</p><p>3. Student need to login with their First Name, Last Name and USN details</p><p>4. On successful validation of the details , Students will be allowed to take their exams</p>";
                    document.getElementById('generated_link').innerHTML = modalBody;
                }
            });

            $('#myModalInitiate').modal('show');
	});

	//verify enable mcq or not
	$('.enable_mcq_verify').on("click", function () {
            var mcq_id = $(this).next().val();
            $('#get_mcq_modal_id').val(mcq_id);

            $('#enable_mcq_verify').modal('show');
	});
	
	//enable mcq
	$('.enable_mcq').on("click", function () {
            var mcq_id = $('#get_mcq_modal_id').val();

            var post_data = {
                'mcq_id': mcq_id
            }

            $.ajax({type: "POST",
                url: base_url + 'mcq/mcqs/enable_mcq',
                data: post_data,
                success: function(msg) {
                    var link = base_url+'mcq/response/start/'+msg.trim();
                    var modalBody = "<h4>Please distribute below link to all students to start their MCQ Exam.</h4><b>"+link+"</b><br /><br /><p><b>Please instruct students to follow below steps :</b></p><p>1. Type the above URL in the browser</p><p>2. MCQ Exam details with Student Login details page will appear.</p><p>3. Student need to login with their First Name, Last Name and USN details</p><p>4. On successful validation of the details , Students will be allowed to take their exams</p>";
                    document.getElementById('generated_link').innerHTML = modalBody;
                    $('#myModalInitiate').modal('show');
                }
            });

            //clear global variable once the status update process is complete
            var mcq_id = $('#get_mcq_modal_id').val('');		
	});
	
	//clear mcq_id on cancel
	$('.clear_on_cancel').on("click", function () {
            var mcq_id = $('#get_mcq_modal_id').val('');
	});

	//on initiate completion reload the page
	function initiate_complete() {
            location.reload();
	}