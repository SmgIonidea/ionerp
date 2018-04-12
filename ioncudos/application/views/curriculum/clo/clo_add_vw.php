<?php
/**
 * Description          :	Course Learning Outcome grid provides the list of course learning
  Outcome statements along with edit and delete options

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:-
 *   Date                Modified By                         Description
 * 15-09-2013		Arihant Prasad			File header, function headers, indentation and comments.
 * 08-05-2015		Abhinay B Angadi		UI and Bug fixes done for Bloom's Level & Delivery methods
 * 28-12-2015		Bhagyalaxmi S S			UI(added dataTable grid with delete and edit option) 
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
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
                <!--content goes here-->	
                <div class="bs-docs-example" >
                    <!--content goes here-->
                    <div class="row-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Course Outcome (CO)
                            </div>
                        </div> 

                        <fieldset>
                            <div class="control-group span12" ><br>
                                <div class="span4">											
                                    &nbsp;&nbsp;&nbsp;Curriculum : <b><font color="blue"><?php echo $curriculum_name_result[0]['crclm_name']; ?></font></b>
                                    <input type="hidden" name="crclm_id" id = "crclm_id" value = "<?php echo $curriculum_name_result[0]['crclm_id']; ?>" />
                                </div>
                                <div class="span4">
                                    Term : <b><font color="blue"><?php echo $curriculum_name_result[0]['term_name']; ?></font></b>
                                    <input type="hidden" name="term_id" id = "term_id" value = "<?php echo $curriculum_name_result[0]['crclm_term_id']; ?>" />
                                </div>
                                <div class="span4">

                                    Course : <b><font color="blue"><?php echo $curriculum_name_result[0]['crs_title']; ?> (<?php echo $curriculum_name_result[0]['crs_code']; ?>)</font></b>
                                    <input type="hidden" name="crs_id" id = "crs_id" value = "<?php echo $curriculum_name_result[0]['crs_id']; ?>" />
                                </div>
                            </div>
                        </fieldset>
                        <input type="hidden" value="<?php echo $curriculum_name_result[0]['crclm_id']; ?>" id="curriculum_add"/>
                        <input type="hidden" value="<?php echo $curriculum_name_result[0]['crclm_term_id']; ?>" id="term_add"/>
                        <input type="hidden" value="<?php echo $curriculum_name_result[0]['crs_id']; ?>" id="course_add"/>
                        <!--To differenciate between list page and add page -->
                        <input type="hidden" id="page_diff" value="1" />
                        <table class="table table-bordered table-hover" id="example_add" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header headerSortDown" style="width: 60px;" tabindex="0" aria-controls="example" aria-sort="ascending"> CO Code </th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending"> Course Outcome (CO) </th>
                                    <th class="header" tabindex="0" aria-controls="example"> Bloom's Level</th>
                                    <th class="header" tabindex="0" aria-controls="example"> Delivery Methods</th>
                                    <th class="header" style="width: 40px;" tabindex="0" aria-controls="example"> Edit </th>
                                    <th class="header" style="width: 50px;"tabindex="0" aria-controls="example"> Delete </th>
                                </tr>
                            </thead>
                            <tbody id ="co_table_body_id" aria-live="polite" aria-relevant="all">
                            </tbody>
                        </table>
                        <form class="form-inline" method="post" name="clo_form" id="clo_form">
                            <div class="span12">
                                <div class="row-fluid">
                                    <div id="table_view">
                                        <div id="add_clo"> 
                                            <div id="add_me">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Add Course Outcome (CO)
                                                    </div>
                                                </div> 
                                                <div class="span10">
                                                    <div class="control-group" style="width:100%">
                                                        <div class="span3">
                                                            <label class="control-label" for="clo_statement_1"> Course  Outcome (CO) Statement: <font color="red"> * &nbsp;&nbsp;</font>   </label>
                                                        </div>
                                                        <div class="controls span9">

                                                            <textarea name="clo_statement_1" class="clo_stmt required char-counter" maxlength="2000" autofocus="autofocus" cols="100"  id="clo_statement_1" rows="3" style="margin-left: 0px; margin-right: 0px; width: 80%;"></textarea>
                                                            <br/><span id='char_span_support' class='margin-left5'>0 of 2000. </span>  
                                                        </div>
                                                        <font color="red"><span></span></font>
                                                    </div>	
                                                </div>
                                                <br/><br/><br/>
                                                <input type="hidden" id ="clo_bl_flag" name="clo_bl_flag" value="<?php echo $clo_bl_flag; ?>"/>
                                                <div class="span12">
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
                                                                            <select class="bloom_list_data example-getting-started required" name="bloom_level_<?php echo ($i + 1); ?>[]" id="bloom_level_<?php echo ($i + 1); ?>" multiple="multiple"  ><?php echo $bloom_level_options[$i]; ?>
                                                                            </select>
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
                                                            <td style="width:500px;">
                                                                <p>Delivery Method   : 
                                                                    <select class="example-getting-started required delivery_method_class" name="delivery_method_1[]" id="delivery_method_1" multiple="multiple"  ><?php echo $delivery_method_options; ?>
                                                                    </select>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>                                               
                                                    <tr>
                                                        <td>
                                                            <div class="span7" id="bloom_level_1_actionverbs"></div>
                                                        </td>
                                                        <td>
                                                            <div class="span7" id="bloom_level_2_actionverbs"></div>
                                                        </td>
                                                        <td>
                                                            <div class="span7" id="bloom_level_3_actionverbs"></div>
                                                        </td>
                                                    </tr>                             
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div id="add_aft">
                                </div>
                            </div>
                    </div>
                    <div class="">
                        <div class="control-group pull-right">

                            <label class="control-label"></label>
                            <div class="pull-right">
                                <button type="button" id="update" class="submit btn btn-primary"><i class="icon-file icon-white"></i> Save</button>
                                <a class="btn btn-danger" id="cancel" href="<?php echo base_url('curriculum/clo') ?>"> <i class="icon-remove icon-white"></i> Close </a>
                            </div>
                        </div><br/><br/>
                        </form>
                        <!-- Modal to display delete confirmation message -->
                        <div id="myModal_delete" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Delete Confirmation 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> Are you sure you want to Delete? </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_clo1();"> <i class="icon-ok icon-white"></i> Ok </button>
                                <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel </button>
                            </div>
                        </div>
                        <div id="myModal_edit_clo"  style="width:750px;left:550px;" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_edit_clo" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Edit Course Outcome (CO)
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div id="edit_Co" ></div>
                            </div>
                            <div class="modal-footer">
                                <button  type="button" class="btn btn-primary"   id="update_edit"> <i class="icon-file icon-white"></i> Update </button>

                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel </button>
                            </div>
                        </div>
                        <div id="myModal_Warning"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                CO Statement already exists.          
                            </div>

                            <div class="modal-footer">                         
                                <button type="reset" class=" btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"> </i> Ok </button>
                            </div>
                        </div>
                        <div id="co_data_import" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="help_content">
                                <p>Course Outcomes for this course are used in the assessment planning.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                            </div>	
                        </div>
                        <div id="edit_checking" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="select_crclm_modal" data-backdrop="static" data-keyboard="false">
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <p> CO Statement already exists.  </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </button>  
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!--Do not place contents below this line-->
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script>
    //var bloom_level_options = "<?php echo $bloom_level_options[0]; ?>";
    var delivery_method_options = "<?php echo $delivery_method_options; ?>";
    $('form[name="clo_form"]').ready(function () {
        $('#bloom_level_1').multiselect({
            //includeSelectAllOption: true,
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
                    //action_verb_data.push('<b>' + bloom_level + ' -</b>' + action_verbs + '<br>');
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
            //includeSelectAllOption: true,
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
            //includeSelectAllOption: true,
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
            //includeSelectAllOption: true,
            maxHeight: 200,
            buttonWidth: 160,
            numberDisplayed: 5,
            nSelectedText: 'selected',
            nonSelectedText: "Select Bloom's Level"
        });
        $('#delivery_method_1').multiselect({
            //includeSelectAllOption: true,
            buttonWidth: 170,
            nSelectedText: 'selected',
            nonSelectedText: 'Select Delivery Method'
        });
    });
</script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<!-- End of file clo_add_vw.php 
                        Location: .curriculum/clo/clo_add_vw.php -->