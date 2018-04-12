<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic Add view page, provides the fecility to Add the Topic Contents.
 * Modification History:
 * Date							Modified By								Description
 * 30-04-2014                   Jevi V G                           Added file headers, function headers & comments. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
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
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Books for Course : <?php echo $b_title[0]['crs_title']; ?>
                        </div>
                        <div><font color="red"><?php echo validation_errors(); ?></font></div>
                    </div>	
                    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                        <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                            <thead >
                                <tr class="" role="row">
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Sl.No</th>                                                                               
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Author </th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Title</th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Edition</th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Website</th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Publication </th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Publication Year </th>
                                    <th class="header headerSortDown" tabindex="0" aria-controls="example" aria-sort="ascending">Theory/Reference</th>
                                    <th class="header" tabindex="0" aria-controls="example">Edit</th>
                                    <th class="header" tabindex="0" aria-controls="example">Delete</th>
                                </tr>
                            </thead>
                        </table>
                        <br/><br/>
                    </div>
                    <form id="save_books" name="save_books">
                        <div class="form-horizontal">
                            <div class="control-group  bs-docs-example" id="book_details">
                                <div class="span3">
                                    <label class="radio-inline">
                                        <input type="radio"  checked class="t" name="ref" id="ref" value="0">Text Book</label>

                                </div><div class="span4">
                                    <label class="radio-inline control-label">
                                        <input type="radio" class="r" name="ref" id="ref" value="1">Reference Book </label>
                                </div>                              
                                <?php echo form_input($book_id); ?>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <div class="span12">												
                                            <div class="control-group span10">
                                                <p class="control-label">Sl No.:<font color="red"><b>*</b></font></p>
                                                <div class="controls">

                                                    <?php echo form_input($book_sl_no); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label">Book Author:<font color="red"><b>*</b></font></p>
                                            <div class="controls">
                                                <?php echo form_input($book_author); ?> 
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label">Book Title:<font color="red"><b>*</b></font></p>
                                            <div class="controls">
                                                <?php echo form_input($book_title); ?>  
                                            </div>
                                        </div>
					<div class="control-group">
                                            <p class="control-label">Book Website:</p>
                                            <div class="controls">
                                                <?php echo form_input($book_website); ?>  
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <p class="control-label">Book Edition:</p>
                                            <div class="controls">
                                                <?php echo form_input($book_edition); ?> 
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label">Publication:</p>
                                            <div class="controls">
                                                <?php echo form_input($book_publication); ?> 
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label">Publication Year:</p>
                                            <div class="controls">
                                                <div class="input-append date">                                                 
                                                    <select class="input-medium  publication_year" id="book_publication_year" name="book_publication_year">
                                                        <option value="">Select Year</option>
                                                        <?php
                                                        $years = range(date("Y"), date("Y", strtotime("now - 60 years")));
                                                        foreach ($years as $year) {
                                                            echo'<option value="' . $year . '">' . $year . '</option>';
                                                        }
                                                        ?> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                       
                                    </div>
                                    <a class="btn pull-right btn-primary save_book_details" name="save_book_details" id="save_book_details"><i class="icon-file icon-white"></i> Save</a>
                                </div>
                            </div>	
                        </div>    
                    </form>
                    <!-- here-->
                    <form  class="form-horizontal" id="book_add_form"  action="<?php echo base_url('curriculum/topic/update_books_evaluation'); ?>" name="book_add_form"  method="POST" >
                        <input type = "hidden" name="book_curriculum_id" id="book_curriculum_id" value ="<?php echo $curriculum_id; ?>"/>
                        <input type = "hidden" name="book_term_id" id="book_term_id" value ="<?php echo $term_id; ?>"/>
                        <input type = "hidden" name="book_course_id" id="book_course_id" value ="<?php echo $course_id; ?>"/>
                        <input type = "hidden" name="ref_id" id="ref_id" value ="<?php echo $ref_id; ?>"/>
                        <input type="hidden" id="book_id1" name="book_id1" value=""/>                        
                        <br>
                        <br>
                        <!-- ends here-->
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                <?php echo $this->lang->line('entity_cie'); ?> Evaluation Scheme for Course : <?php echo $b_title[0]['crs_title']; ?>
                            </div>
                        </div>	
                        <!--row fluid for labels-->
                        <div class="row-fluid">
                            <div class="span1">
                                <div class="control-group">
                                    <p class="control-label"> </p>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <p class="control-label">Assessment </p>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <p class="control-label">Assessment Mode </p>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <p class="control-label">Weightage in marks</p>
                                </div>
                            </div>
                        </div><!--end of row fluid-->		
                        <?php
                        $cieCntr = 1;
                        foreach ($cie_scheme as $cie):
                            $assessment_id['value'] = $cie['assessment_id'];
                            $assessment_id['id'] = 'assessment_id' . $cieCntr;
                            $assessment_id['name'] = 'assessment_id' . $cieCntr;

                            $assessment_name['value'] = $cie['assessment_name'];
                            $assessment_name['id'] = 'assessment_name' . $cieCntr;
                            $assessment_name['name'] = 'assessment_name' . $cieCntr;

                            $weightage_in_marks['value'] = $cie['weightage_in_marks'];
                            $weightage_in_marks['id'] = 'weightage_in_marks' . $cieCntr;
                            $weightage_in_marks['name'] = 'weightage_in_marks' . $cieCntr;
                            ?> 
                            <div id="prgm_curriculum">
                                <div class="control-group" id="evaluation_details">
                                    <div class="row-fluid">
                                        <div class="span1">
                                            <div class="control-group">
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="control-group">
                                                <?php echo form_input($assessment_id); ?> 
                                                <?php echo form_input($assessment_name); ?> 
                                            </div>
                                        </div>
                                        <div class="span3 ">
                                            <div class="control-group">
                                                <p class="checkbox inline" style="padding-left:90px;">
                                                    <input id="assessment_mode<?php echo $cieCntr; ?>" type="checkbox" name="assessment_mode<?php echo $cieCntr; ?>" abbr="<?php echo $cieCntr; ?>" class="assmnt_mode" value="1" <?php
                                                    if ($cie['assessment_mode'] == 1) {
                                                        echo 'checked="checked"';
                                                    }
                                                    ?> />
                                                    Viva
                                                </p>
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="control-group">
                                                <?php echo form_input($weightage_in_marks); ?>
                                            </div>
                                        </div>
                                        <div class="span1">
                                            <a id="remove_field<?php echo $cieCntr; ?>" class="Delete_assessment identify_remove cursor_pointer"><i class="icon-remove"></i> </a> 
                                        </div>
                                    </div><!--end of row fluid-->
                                </div>
                                <?php
                                $array[] = $cieCntr;
                                $cieCntr++;
                            endforeach;
                            $array_val = implode(",", $array);
                            ?>
                            <!--<div class="pull-right row">
                                <button type="button" class="btn btn-primary add_evaluation_details" id="add_evaluation_details"><i class="icon-plus-sign icon-white"></i> Add More</button>
                            </div>-->
                            <div id="add_more"></div>
                            <br>
                            <input type="hidden" name="counter_eval" id="counter_eval" value="<?php echo $array_val; ?>"/>
                        </div>
                        <input type="hidden" name="cie_eval" id="cie_eval" value="<?php echo $cieCntr - 1; ?>"/>
                        <input type="hidden" name="check_counter" id="check_counter"  value="0"/>
                        <div class="row-fluid">
                            <div class="span1">
                                <div class="control-group">
                                    <p class="control-label"></p>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <p class="control-label"></p>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <p class="control-label" onchange="total();">Total</p>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <input class="span12" name="total" id="total" type="text" placeholder="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right row">
                            <button type="button" class="btn btn-primary add_evaluation_details" id="add_evaluation_details"><i class="icon-plus-sign icon-white"></i> Add More</button>
                            <button type="button" class="btn btn-primary" id="generate" name="generate" disabled="disabled" ><i class="icon-file icon-white"></i> Generate Table </button>
                            <button type="button" class="btn btn-info" id="revert" name="revert"><i class="icon-refresh icon-white"></i> Revert </button>
                        </div>
                        <input type="hidden" name="checked_val" id="checked_val"/>
                        <br>
                        <br>
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Course Unitization for Minor Exams and Semester End Examination
                            </div>
                            <div><font color="red"><?php echo validation_errors(); ?></font></div>
                        </div>
                        <div id="cie_table">
                            <table class=" table table-bordered">
                                <tr>
                                    <th><?php echo $this->lang->line('entity_topic'); ?>/Chapters</th>
                                    <th>Teaching Hours</th>
                                    <?php
                                    $column_size = sizeof($crs_unitization);
                                    $colspan = 2 + $column_size;
                                    if (!empty($crs_unitization)) {

                                        $k = 0;

                                        for ($i = 1; $i <= $column_size; $i++) {
                                            ?>
                                            <th>No. of Questions for - <?php
                                                if (!empty($crs_unitization[$i - 1])) {
                                                    echo $crs_unitization[$i - 1][$k]['assessment_name'];
                                                }
                                                ?>
                                                <?php if (!empty($crs_unitization[$i - 1])) { ?>
                                                    <input type="hidden" name="assess_id<?php echo $i; ?>[]" id="assess_id<?php echo $i; ?>" class="input-mini" value = "<?php echo $crs_unitization[$i - 1][$k]['assessment_id']; ?>"/>
                                                <?php }
                                                ?>
                                            </th>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php
                                $temp = 0;
                                $counter = 0;
                                $count = 1;
                                foreach ($topic_details as $topic_data) {

                                    if ($temp != $topic_data['t_unit_id']) {
                                        $temp = $topic_data['t_unit_id']
                                        ?>
                                        <tr><td colspan="<?php echo $colspan; ?>"><center><b><?php echo $topic_data['t_unit_name']; ?></b></center></td></tr>
                                    <?php } ?>

                                    <tr>
                                        <td>
                                            <?php echo $topic_data['topic_title']; ?><input type="hidden" name="topic_id[]" id="topic_id" class="input-small" value = "<?php echo $topic_data['topic_id']; ?>"/>
                                        </td>
                                        <td><?php echo $topic_data['topic_hrs']; ?></td>

                                        <?php
                                        $count = 1;
                                        if (!empty($crs_unitization)) {
                                            foreach ($crs_unitization as $crs_unit) {
                                                if (!empty($crs_unit[$counter])) {
                                                    ?>
                                                    <td>
                                                        <input type="text" name="no_of_questions<?php echo $count; ?>[]" id="no_of_questions<?php echo $count; ?>" class="input-small" value = "<?php echo $crs_unit[$counter]['no_of_questions']; ?>"/>
                                                    </td>
                                                    <?php
                                                } else {
                                                    echo '<td>0.00</td>';
                                                }

                                                $count++;
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    $counter++;
                                }
                                ?>
                            </table>
                        </div>	
                        <input type="hidden" name="table_counter" id="table_counter" class="mini" value = "<?php echo $count - 1; ?>"/>
                        <input type="hidden" name="assess_del_val" id="assess_del_val" class="mini" value = ""/>
                        <br>
                        <div class="pull-right row">
                            <button type="submit" id="update"  class="add_details btn btn-primary" ><i class="icon-file icon-white"></i>Update</button>
                            <?php if ($ref_id == "ref_12") { ?>
                                <!-- redirect to lab experiment page -->
                                <a href="<?php echo base_url('curriculum/lab_experiment'); ?>" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i>Cancel</a>
                            <?php } else { ?>
                                <!-- redirect to topics page -->
                                <a href="<?php echo base_url('curriculum/topic'); ?>" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i>Cancel</a>
                            <?php } ?>
                        </div>
                        <br />
                    </form>
            </section>
            <div id="myModaldelete" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModaldelete" data-backdrop="static" data-keyboard="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Delete Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Do you want to delete this record? </p>
                </div>
                <div class="modal-footer">
                    <button class="delete_dm btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
            <div id="edit_book_details" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; left:550px;width:920px;" data-controls-modal="" data-backdrop="static" data-keyboard="true"><br>
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Edit <?php echo $b_title[0]['crs_title']; ?> Book List
                    </div>
                </div>
                <div class="modal-body">
                    <form id="edit_books">
                        <div class="" >
                            <div class="bs-docs-example">
                                <div class="row-fluid">
                                    <div class="span6">
                                        <div class="span12">                                           
                                            <div class="span3">
                                                <label class="radio-inline">
                                                    <input type="radio"  class="t" name="ref_e" id="ref_e" value="0">Text Book
                                                </label>

                                            </div>
                                            <div class="span4">
                                                <label class="radio-inline">
                                                    <input type="radio" class="r" name="ref_e" id="ref_e" value="1">Reference Boook 
                                                </label>
                                            </div>                                          
                                            <br/><br/>
                                            <div class="control-group span6">                                           
                                                <div class="controls">
                                                    Sl No.: &nbsp;&nbsp;<font color="red"><b>*</b></font>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="book_sl_no_e" id="book_sl_no_e" value="" class= "required loginRegex onlyDigit input-mini sl_no_e"/>														   
                                                </div>
                                            </div>
                                        </div>

                                        <div class="control-group">                                         
                                            <div class="controls">
                                                Book Author:  <font color="red"><b>*</b></font> <input type="text" name="book_author_e" id="book_author_e" value="" class = 'required noSpecialChars input-xlarge author_e'/>
                                            </div>
                                        </div>
                                        <div class="control-group">                                          
                                            <div class="controls">
                                                Book Title: <font color="red"><b>*</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="book_title_e" id="book_title_e" value="" class = 'required noSpecialChars input-xlarge title_e'/>
                                            </div>
                                        </div>
					<div class="control-group">                                          
                                            <div class="controls">
                                                Book Website: <input type="text" name="book_website_e" id="book_website_e" value="" class = ' noSpecialChars input-xlarge website_e'/>
                                            </div>
                                        </div>
                                    </div><br/><br/>
                                    <div class="span6">
                                        <div class="control-group">
                                            <div class="controls">
                                                Book Edition: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="book_edition_e" id="book_edition_e" value="" class = ' noSpecialChars1 input-xlarge edition_e'/>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div class="controls">
                                                Publication: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="text" name="book_publication_e" id="book_publication_e" value="" class =' noSpecialChars input-xlarge publication_e'> 
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div class="controls">
                                                Publication Year:  <div class="input-append date">
                                                    <select class="input-medium  book_publication_year_e" id="book_publication_year_e" name="book_publication_year_e">
                                                        <option value="">Select Year</option>
                                                        <?php
                                                        $years = range(date("Y"), date("Y", strtotime("now - 60 years")));
                                                        foreach ($years as $year) {
                                                            echo'<option value="' . $year . '">' . $year . '</option>';
                                                        }
                                                        ?> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <input type="hidden" id="book_id" name="book_id" value=""/>
                <div class="modal-footer">
                    <a class="btn btn-primary update_book_details" name="update_book_details" id="update_book_details"><i class="icon-file icon-white"></i> Update</a>
                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/book_edit.js'); ?> "></script>
