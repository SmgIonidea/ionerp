<!DOCTYPE html>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_4'); ?>
        <div class="span10">
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>

            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Upload <?php echo $qp_type; ?> Question Paper
                        </div>
                    </div>
                    <div class="span12">
                        <div class="span4">
                            <b>Curriculum: <font color="blue"><?php echo $crclm_name; ?></font></b>
                        </div>
                        <div class="span4">
                            <b>Term: <font color="blue"><?php echo $term_name; ?></font></b>
                        </div>
                        <div class="span4">
                            <b>Course: <font color="blue"><?php echo $crs_name .'['.$crs_code.']'; ?></font></b>
                        </div>
                        
                    </div>
                    <div>      
						<table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><b>Steps:</b></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="hidden" id="url_address" name="url_address" value="<?php echo base_url('survey/import_student_data_excel/download_excel'); ?>">
                                                1) Click here to 
                                                <a href="<?php echo base_url('uploads/question_paper_template/TEE_Question_Paper_Template.xls'); ?>" title="Select Section before downloading Template." target="" id="download_file" 0="rel=" facebox""=""><b>Download Question Paper Template</b></a><input type="hidden" name="import_type" id="import_type" value="excel"><font color="#8E2727"> (File name: TEE_Question_Paper_Template.xls).</font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2) Enter the <font color="#8E2727"><b>Course Code, Exam Type(TEE, MTE),Questions and Corresponding Mapping to COs, Bloom Level and Topic(optional)</b></font> and Click on <font color="#8E2727"><b>"Choose File"</b></font> button to upload the .xls file. Make sure that the <font color="#8E2727"><b>Course Code</b></font> and <font color="#8E2727"><b>Exam Type</b></font> are entered properly.<br>
                                                (Note: <font color="#8E2727"><b>Discard previous downloaded file</b></font> before downloading new file)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3) Click on <font color="#8E2727"><b>"Upload"</b></font> button to upload the Questions Paper. 
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                        <?php
                        $form_attribute = array(
                            "name" => "upload_form",
                            "id" => "upload_form",
                            "method" => "POST",
                            "enctype" => "multipart/form-data",
                            "class" => "form-inline"
                        );
                        $form_action = "assessment_attainment/upload_question_papers/index/".$pgm_id."/".$crclm_id."/".$crclm_term_id."/".$crs_id."/".$qn_type;
                        echo form_open($form_action, $form_attribute);
                        ?>
                        <br>
                        <div class="container_one">
                            <div class="row">
                                <label class="" style="line-height: 2.5em;">Question Paper</label>
                                <div class=""> 
                                    <!-- image-preview-filename input [CUT FROM HERE]-->
                                    <div class="input-group image-preview">
                                        <input type="text" class="form-control image-preview-filename" style="width: 646px;" disabled="disabled"> <!-- don't give a name === doesn't send on POST/GET -->
                                        <span class="input-group-btn">
                                            <!-- image-preview-clear button -->
                                            <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                <span class="icon-remove"></span> Clear
                                            </button>
                                            <!-- image-preview-input -->
                                            <div class="btn btn-default image-preview-input">
                                                <span class="icon-folder-open"></span>
                                                <span class="image-preview-input-title">Choose File</span>
                                                <?php
                                                    $attribute = array(
                                                        "name" => "upload_file",
                                                        "id" => "upload_file",
                                                        "class" => "",
                                                        "title" => "upload file",
                                                        "required" => "",
                                                        "type" => "file",
                                                        "value" => (isset($data["upload_file"])) ? $data["upload_file"] : "",
                                                    );
                                                    echo form_error("upload_file");
                                                    echo form_input($attribute);
                                                    ?>
                                                    <input type="hidden" name="form_flag" value="1">
                                            </div>
                                            <button type="submit" id="submit" class="btn btn-success"><i class="icon-upload icon-white"></i> Upload</input>
                                        </span>
                                    </div><!-- /input-group image-preview [TO HERE]--> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="pull-right" style="padding-top: 90px;">
                            <?php if($qn_type == 5) {?>
                            <a type="button" class="btn btn-danger" href="<?php echo base_url('question_paper/tee_qp_list'); ?>"><i class="icon-remove icon-white"></i> Close</a>
                            <?php }else{ ?>
                            <a type="button" class="btn btn-danger" href="<?php echo base_url('question_paper/manage_mte_qp'); ?>"><i class="icon-remove icon-white"></i> Close</a>
                            <?php } ?>
                                
                        </div>
                    </div>
                    </div>      
                    </div>                        
                        <?= form_close() ?>
                    </div>
                </div>
                <div class="span12">
                       
                </div>
        </div>
    </div>
</div>
    <div id="modal_msg" class="modal hide fade mapping_weightage" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="true"></br>
        <div class="container-fluid">
            <div class="navbar">
                <div class="navbar-inner-custom" id="navbar">

                </div>
            </div>
        </div>
        <div class="modal-body">
            <p id="message"></p>
        </div>
        <div class="modal-footer">
            <button type="button" id="cancel_weight" data-dismiss="modal" class="cancel_weight btn btn-danger"><i class="icon-white icon-remove"></i> Close</button>
        </div>
    </div>
<!--</div>-->
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

<script type="text/javascript">
    (function ($) {
        $('#submit').on('click',function(){
		if($('.image-preview-filename').val() != ''){
		$('#loading').show();
		}
		});
                
        var res ='<?= $result ?>';
        if (res=='1') {
            var data_options = '{"text":"Question paper successfully uploaded.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
            show_noty(data_options);
            $('#loading').hide();
            console.log('success');
        } else if(res){
            var data_options = '{"text":"'+res+'.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
            show_noty(data_options);
             $('#upload_form')[0].reset();
             $('#loading').hide();
        }else {
            console.log('error');
            $('#loading').hide();
        }

        function show_noty($option) {
            if ($option) {
                $option = $.parseJSON($option);
                noty($option);
            }
        }
    })(jQuery);
    
   $(document).on('click', '#close-preview', function(){ 
    $('.image-preview').popover('hide');
    // Hover befor close the preview    
});

$(function() {
    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");

    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("Browse"); 
    }); 
    // Create the preview image
    $(".image-preview-input input:file").change(function (){     
        var img = $('<img/>', {
            id: 'dynamic',
            width:250,
            height:200
        });      
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Change");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
        }        
        reader.readAsDataURL(file);
    });  
});
</script>


