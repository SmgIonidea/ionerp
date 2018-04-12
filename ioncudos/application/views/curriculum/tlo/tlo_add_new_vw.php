<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description          : TLO Add view page, provides the fecility to add the details of TLO's.
 * Modification History :
 * Date                     Modified By				Description
 * 05-09-2013           Mritunjay B S           Added file headers, function headers & comments. 
 * 05-10-2015			Bhgayalaxmi S S			Added DataTable grid the edit page placed in modal
 * 10-1-2016			Bhagyalaxmi S S 		Removed reloading of pages 
 * 05-10-2015			Bhgayalaxmi S S
 * 05-01-2016			Shayista Mulla          Added loading image

  ---------------------------------------------------------------------------------------------------------------------------------

 */
?>
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add / Edit  <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo_singular'); ?>)
                                    <a href="#help" class="pull-right" data-toggle="modal" onclick="show_help();" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign"></i></a>
                                </div>
                            </div>	
                            <div class="span12">
                                <div class="span3">
                                    Curriculum : <span style="color:black;font-size:12px;font-weight:bold;"><?php echo $crclm; ?></span>
                                </div>
                                <div class="span2">
                                    Term : <span style="color:clack;font-size:12px;font-weight:bold;"><?php echo $term_name; ?></span>
                                </div>
                                <div class="span4">
                                    Course : <span style="color:black;font-size:12px;font-weight:bold;"><?php echo $course_name ; ?> (<?php echo $course_code; ?>)</span>
                                </div>
                                <div class="span3">
                                    Topic : <span style="color:black;font-size:12px;font-weight:bold;"><?php echo $topic_name; ?></span>
                                </div>
                            </div></br></br>

                            <table class="table table-bordered table-hover" id="example_view" aria-describedby="example_info" style="fonsize:12px;">
                                <thead>
                                    <tr role="row">
                                        <th style = "width: 90px;" ><?php echo $this->lang->line('entity_tlo_singular'); ?> Code</th>
                                        <th><?php echo $this->lang->line('entity_tlo_full'); ?></th>
                                        <th>Bloom's Level</th>
                                        <th>Delivery Method</th>
                                        <th>Delivery Approach</th>
                                        <th style = "width: 40px;">Edit</th>
                                        <th style = "width: 50px;">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/><br/>
                            <form class="form-horizontal" method="POST" id="tlo_add_form"  data-toggle="validator" name="courseform" action="" >                                                                                                                                                                                                                                                                      
                                <div id="loading" class="ui-widget-overlay ui-front">
                                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                                </div>
                                 <input type="hidden" id ="clo_bl_flag" name="clo_bl_flag" value="<?php echo $clo_bl_flag; ?>"/>
                                <div id="add_tlo">
                                    <div id="remove" class="tlo">
                                        <div id="tlo_statement"  data-spy="scroll" class="bs-docs-example" style="width:auto; height:520px; padding-top:30px;">
                                            <div class="span12">											
                                                <div class="row-fluid">

                                                    <div class="span6">
                                                        <a href="http://math.typeit.org/" target="_blank" class="pull-right" style="text-decoration:none; font-size:12px; color:FFF;"> On-line Mathematical Editor </a>
                                                        <div class="control-group">
                                                            <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) Statement: <font color='red'>*</font>														
                                                            <div class="">
                                                                <br/>
                                                                <label id="tiny_mce" style="color:red"></label>
                                                                <?php echo form_textarea($tlo_name); ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="span6">                                                      
                                                        <div class="control-group">
                                                            <label class="control-label" for="delivery_methods">Delivery  Method: <font color='red'></font></label>
                                                            <div class="controls">
                                                                <select id="delivery_methods1" name="delivery_methods1" class="input-large">
                                                                    <option value="" selected>Select Delivery Method</option>
                                                                    <?php foreach ($delivery_method as $method) { ?>
                                                                        <option value="<?php echo $method['crclm_dm_id']; ?> " title = "<?php echo $method['delivery_mtd_name']; ?>"><?php echo $method['delivery_mtd_name']; ?> </option>
                                                                    <?php } ?>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="delivery_approach">Delivery Approach: </label>
                                                            <div class="controls">
                                                                <textarea class="" style="width:100%; height:125px;"cols="40" rows="4" name="delivery_approach1" id="delivery_approach"></textarea>
                                                            </div>
                                                        </div></div>

                                                </div><div class="span12">
                                                    <table style="width:90%">
                                                        <tr>
                                                            <?php
                                                            $i = 0;
                                                            foreach ($bloom_domain as $domain) {
                                                                if ($bld_active[$i] == 1 && $domain['status'] == 1) {
                                                                    ?>
                                                                    <td>
                                                                        <p>
                                                                            <?php echo $domain['bld_name']; ?> : <?php if ($clo_bl_flag == 1) { ?> <font color="red"> * &nbsp;&nbsp;</font><?php } ?>
                                                                            <select class="example-getting-started required" name="bloom_level_<?php echo ($i + 1); ?>[]" id="bloom_level_<?php echo ($i + 1); ?>" multiple="multiple"  ><?php echo $bloom_level_options[$i]; ?>
                                                                        </p>
                                                                         <input type="hidden" id="bloom_filed_id" name = "bloom_filed_id[]" value="bloom_level_<?php echo ($i + 1); ?>"/>
                                                                         </br><span class= "error_placeholder_bl" id="error_placeholder_bl<?php echo ($i + 1); ?>"> </span>
                                                                    </td>
                                                                    <?php
                                                                }
                                                                $i++;
                                                                echo '<input type="hidden" name="bld_id_' . $i . '" id="bld_id_' . $i . '" value="' . $domain['bld_id'] . '">';
                                                            }
                                                            ?>
                                                                    
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="span7" id="bloom_level_1_actionverbs"></div>
                                                            </td>
                                                            <td>
                                                                <div class="span7" id="bloom_level_2_actionverbs"></div></td>
                                                            <td>
                                                                <div class="span7" id="bloom_level_3_actionverbs"></div></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- tlo_statement-->
                            </form>
                        </div><!-- remove-->
                        <div class="control-group pull-right"><br/>
                            <button  class="save btn btn-primary" type="button" id="save" onclick=""><i class="icon-file icon-white"></i> Save</button>
                            <a href= "<?php echo base_url('curriculum/topic'); ?>" class="btn btn-danger" ><i class="icon-remove icon-white"></i> Close </a>
                        </div><br/><br/><br/>
                </div><!-- add_tlo-->
                <div id="insert_before">
                </div>
                <input type="hidden" name="counter" id="counter" value="1">								                           
                <br>
				
                <!--Modal to display help content-->
                <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                <?php echo $this->lang->line('entity_tlo_singular'); ?> and Bloom's Taxonomy guideline
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" id="help1">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                    </div>
                </div>
				
                <div id="error" style="color:red">
                </div>
				
                <div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Delete Confirmation
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">									
                        Are you sure you want to Delete?
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-primary" data-dismiss="modal" id="btnYest"><i class="icon-ok icon-white"></i> Ok </a> 
                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                    </div>
                </div>
                <div id="myModal_edit"  role="dialog" class="modal hide fade"  aria-labelledby="" aria-hidden="true" style="width:70%; left:37%; display:none;" data-controls-modal="" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Edit <?php echo $this->lang->line('entity_tlo_singular'); ?> Statement
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">									
                        <table id="edit_tlo">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="span3">
                                            <label class="control-label" ><?php echo $this->lang->line('entity_tlo_singular'); ?> Code: <font color='red'></font></label>
                                            <div class="span1">
                                                <input type="text" id="tlo_code_edit" class="input-small"/>
                                            </div>
                                        </div>
                                        <div class="span9">
                                            <label class="control-label" ><?php echo $this->lang->line('entity_tlo_singular'); ?> Statement: <font color='red'>*</font></label>
                                            <div class="controls">
                                                <label id="tiny_mce_edit" style="color:red"></label>
                                                <textarea class="tlo" id="tlo_stmt_edit"></textarea>	
                                            </div><br/>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="span12">
                            <table style="width:100%">
                                <tr>
                                    <?php
                                    $i = 0;
                                    foreach ($bloom_domain as $domain) {
                                        if ($bld_active[$i] == 1 && $domain['status'] == 1) {
                                            ?>
                                            <td>
                                                <p>
                                                    <?php echo $domain['bld_name']; ?> : <?php if ($clo_bl_flag == 1) { ?> <font color="red"> * &nbsp;&nbsp;</font><?php } ?>
                                                    <select class="example-getting-started required" name="edit_bloom_level_<?php echo ($i + 1); ?>[]" id="edit_bloom_level_<?php echo ($i + 1); ?>" multiple="multiple"  ><?php echo $bloom_level_options[$i]; ?>
                                                </p>
                                                  <input type="hidden" id="bloom_filed_edit_id" name = "bloom_filed_edit_id[]" value="edit_bloom_level_<?php echo ($i + 1); ?>"/>
                                                  </br><span class= "error_placeholder_edit_bl" id="error_placeholder_edit_bl<?php echo ($i + 1); ?>"> </span>
                                            </td>
                                            <?php
                                        }
                                        $i++;
                                        echo '<input type="hidden" name="bld_id_' . $i . '" id="bld_id_' . $i . '" value="' . $domain['bld_id'] . '">';
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="span7" id="edit_bloom_level_1_actionverbs"></div>
                                    </td>
                                    <td>
                                        <div class="span7" id="edit_bloom_level_2_actionverbs"></div></td>
                                    <td>
                                        <div class="span7" id="edit_bloom_level_3_actionverbs"></div></td>
                                </tr>
                            </table>
                            <div class="control-group span6">
                                <label class="control-label span4" > Delivery Method:<font color='red'></font></label>
                                <div class="controls span8">
                                    <select id="dlm" name="dlm" class="input-large  fetch_action_verbs">
                                        <option value="" selected>Select Delivery Method</option>
                                        <?php foreach ($delivery_method as $method) { ?>
                                            <option value="<?php echo $method['crclm_dm_id']; ?>" title = "<?php echo $method['delivery_mtd_name']; ?>"><?php echo $method['delivery_mtd_name']; ?> </option>
                                        <?php } ?>

                                    </select><br/>
                                    <label  class="span2" id="bloom_error_edit" style="color:red;white-space: nowrap"></label>
                                </div>
                            </div>
                            <div class="control-group span6">
                                <label class="control-label span4" >Delivery Approach: <font color='red'></font></label>
                                <div class="controls span8">
                                    <textarea class="" style="width:90%; height:80px;" rows="2" name="delivery_approach_edit" id="delivery_approach_edit"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary save_edit_tlo"><i class="icon-file icon-white"></i> Update </a> 
                        <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                    </div>
                </div>
            </div>
        </div>	
    </div>	
    <?php echo form_input($crclm_id); ?>
    <?php echo form_input($curriculum); ?>
    <?php echo form_input($term_id); ?>
    <?php echo form_input($course_id); ?>
    <?php echo form_input($topic_id); ?>
</form>
</section>
</div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/tlo.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script>
                                        var entity_tlo_singular = "<?php echo $this->lang->line('entity_tlo_singular'); ?>";
                                        var entity_tlo_full = "<?php echo $this->lang->line('entity_tlo_full'); ?>";
                                        $('#bloom_level_1').multiselect({
                                            onChange: function (option, checked) {
                                                var selectedOptions = $('#bloom_level_1 option:selected');
                                                if (selectedOptions.length >= 4) {
                                                    // Disable all other checkboxes.
                                                    var nonSelectedOptions = $('#bloom_level_1 option').filter(function () {
                                                        return !$(this).is(':selected');
                                                    });

                                                    var dropdown = $('#bloom_level_1').siblings('.multiselect-container');
                                                    nonSelectedOptions.each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', true);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                } else {
                                                    // Enable all checkboxes.
                                                    var dropdown = $('#bloom_level_1').siblings('.multiselect-container');
                                                    $('#bloom_level_1 option').each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', false);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                }
                                                var selections = [];
                                                var action_verb_data = [];
                                                $("#bloom_level_1 option:selected").each(function () {
                                                    var bloom_level_id = $(this).val();
                                                    var bloom_level = $(this).text();
                                                    var action_verbs = '';
                                                    var action_verbs = $(this).attr('title');
                                                    selections.push(bloom_level_id);
                                                    action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
                                                });
                                                var action_verb = action_verb_data.join("<b></b>");
                                                $('#bloom_level_1_actionverbs').html(action_verb.toString());

                                            },
                                            //includeSelectAllOption: true,
                                            maxHeight: 200,
                                            buttonWidth: 160,
                                            numberDisplayed: 5,
                                            nSelectedText: 'selected',
                                            nonSelectedText: "Select Bloom's Level"
                                        });
                                        $('#bloom_level_2').multiselect({
                                            onChange: function (option, checked) {
                                                var selectedOptions = $('#bloom_level_2 option:selected');
                                                if (selectedOptions.length >= 4) {
                                                    // Disable all other checkboxes.
                                                    var nonSelectedOptions = $('#bloom_level_2 option').filter(function () {
                                                        return !$(this).is(':selected');
                                                    });

                                                    var dropdown = $('#bloom_level_2').siblings('.multiselect-container');
                                                    nonSelectedOptions.each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', true);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                } else {
                                                    // Enable all checkboxes.
                                                    var dropdown = $('#bloom_level_2').siblings('.multiselect-container');
                                                    $('#bloom_level_2 option').each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', false);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                }
                                                var selections = [];
                                                var action_verb_data = [];
                                                $("#bloom_level_2 option:selected").each(function () {
                                                    var bloom_level_id = $(this).val();
                                                    var bloom_level = $(this).text();
                                                    var action_verbs = '';
                                                    var action_verbs = $(this).attr('title');
                                                    selections.push(bloom_level_id);
                                                    action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
                                                });
                                                var action_verb = action_verb_data.join("<b></b>");
                                                $('#bloom_level_2_actionverbs').html(action_verb.toString());

                                            },
                                            //includeSelectAllOption: true,
                                            maxHeight: 200,
                                            buttonWidth: 160,
                                            numberDisplayed: 5,
                                            nSelectedText: 'selected',
                                            nonSelectedText: "Select Bloom's Level"
                                        });
                                        $('#bloom_level_3').multiselect({
                                            onChange: function (option, checked) {
                                                var selectedOptions = $('#bloom_level_3 option:selected');
                                                if (selectedOptions.length >= 4) {
                                                    // Disable all other checkboxes.
                                                    var nonSelectedOptions = $('#bloom_level_3 option').filter(function () {
                                                        return !$(this).is(':selected');
                                                    });

                                                    var dropdown = $('#bloom_level_3').siblings('.multiselect-container');
                                                    nonSelectedOptions.each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', true);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                } else {
                                                    // Enable all checkboxes.
                                                    var dropdown = $('#bloom_level_3').siblings('.multiselect-container');
                                                    $('#bloom_level_3 option').each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', false);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                }
                                                var selections = [];
                                                var action_verb_data = [];
                                                $("#bloom_level_3 option:selected").each(function () {
                                                    var bloom_level_id = $(this).val();
                                                    var bloom_level = $(this).text();
                                                    var action_verbs = '';
                                                    var action_verbs = $(this).attr('title');
                                                    selections.push(bloom_level_id);
                                                    action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
                                                });
                                                var action_verb = action_verb_data.join("<b></b>");
                                                $('#bloom_level_3_actionverbs').html(action_verb.toString());

                                            },
                                            maxHeight: 200,
                                            buttonWidth: 160,
                                            numberDisplayed: 5,
                                            nSelectedText: 'selected',
                                            nonSelectedText: "Select Bloom's Level"
                                        });
                                        $('#delivery_method_1').multiselect({
                                            nSelectedText: 'selected',
                                            nonSelectedText: 'Select Delivery Method'
                                        });
                                        $('#edit_bloom_level_1').multiselect({
                                            onChange: function (option, checked) {
                                                var selectedOptions = $('#edit_bloom_level_1 option:selected');
                                                if (selectedOptions.length >= 4) {
                                                    // Disable all other checkboxes.
                                                    var nonSelectedOptions = $('#edit_bloom_level_1 option').filter(function () {
                                                        return !$(this).is(':selected');
                                                    });

                                                    var dropdown = $('#edit_bloom_level_1').siblings('.multiselect-container');
                                                    nonSelectedOptions.each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', true);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                } else {
                                                    // Enable all checkboxes.
                                                    var dropdown = $('#edit_bloom_level_1').siblings('.multiselect-container');
                                                    $('#bloom_level_1 option').each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', false);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                }
                                                var selections = [];
                                                var action_verb_data = [];
                                                $("#edit_bloom_level_1 option:selected").each(function () {
                                                    var bloom_level_id = $(this).val();
                                                    var bloom_level = $(this).text();
                                                    var action_verbs = '';
                                                    var action_verbs = $(this).attr('title');
                                                    selections.push(bloom_level_id);
                                                    action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
                                                });
                                                var action_verb = action_verb_data.join("<b></b>");
                                                $('#edit_bloom_level_1_actionverbs').html(action_verb.toString());

                                            },
                                            maxHeight: 200,
                                            buttonWidth: 160,
                                            numberDisplayed: 5,
                                            nSelectedText: 'selected',
                                            nonSelectedText: "Select Bloom's Level"
                                        });
                                        $('#edit_bloom_level_2').multiselect({
                                            onChange: function (option, checked) {
                                                var selectedOptions = $('#edit_bloom_level_2 option:selected');
                                                if (selectedOptions.length >= 4) {
                                                    // Disable all other checkboxes.
                                                    var nonSelectedOptions = $('#edit_bloom_level_2 option').filter(function () {
                                                        return !$(this).is(':selected');
                                                    });

                                                    var dropdown = $('#edit_bloom_level_2').siblings('.multiselect-container');
                                                    nonSelectedOptions.each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', true);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                } else {
                                                    // Enable all checkboxes.
                                                    var dropdown = $('#bloom_level_2').siblings('.multiselect-container');
                                                    $('#edit_bloom_level_2 option').each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', false);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                }
                                                var selections = [];
                                                var action_verb_data = [];
                                                $("#edit_bloom_level_2 option:selected").each(function () {
                                                    var bloom_level_id = $(this).val();
                                                    var bloom_level = $(this).text();
                                                    var action_verbs = '';
                                                    var action_verbs = $(this).attr('title');
                                                    selections.push(bloom_level_id);
                                                    action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
                                                });
                                                var action_verb = action_verb_data.join("<b></b>");
                                                $('#edit_bloom_level_2_actionverbs').html(action_verb.toString());

                                            },
                                            maxHeight: 200,
                                            buttonWidth: 160,
                                            numberDisplayed: 5,
                                            nSelectedText: 'selected',
                                            nonSelectedText: "Select Bloom's Level"
                                        });
                                        $('#edit_bloom_level_3').multiselect({
                                            onChange: function (option, checked) {
                                                var selectedOptions = $('#edit_bloom_level_3 option:selected');
                                                if (selectedOptions.length >= 4) {
                                                    // Disable all other checkboxes.
                                                    var nonSelectedOptions = $('#edit_bloom_level_3 option').filter(function () {
                                                        return !$(this).is(':selected');
                                                    });

                                                    var dropdown = $('#edit_bloom_level_3').siblings('.multiselect-container');
                                                    nonSelectedOptions.each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', true);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                } else {
                                                    // Enable all checkboxes.
                                                    var dropdown = $('#edit_bloom_level_3').siblings('.multiselect-container');
                                                    $('#edit_bloom_level_3 option').each(function () {
                                                        var input = $('input[value="' + $(this).val() + '"]');
                                                        input.prop('disabled', false);
                                                        input.parent('li').addClass('disabled');
                                                    });
                                                }
                                                var selections = [];
                                                var action_verb_data = [];
                                                $("#edit_bloom_level_3 option:selected").each(function () {
                                                    var bloom_level_id = $(this).val();
                                                    var bloom_level = $(this).text();
                                                    var action_verbs = '';
                                                    var action_verbs = $(this).attr('title');
                                                    selections.push(bloom_level_id);
                                                    action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
                                                });
                                                var action_verb = action_verb_data.join("<b></b>");
                                                $('#edit_bloom_level_3_actionverbs').html(action_verb.toString());

                                            },
                                            maxHeight: 200,
                                            buttonWidth: 160,
                                            numberDisplayed: 5,
                                            nSelectedText: 'selected',
                                            nonSelectedText: "Select Bloom's Level"
                                        });
</script>
</body>
</html>
