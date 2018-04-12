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
<!DOCTYPE html>
<html lang="en">
    <!--head here -->
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
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <section id="contents">
                        <div class="bs-docs-example fixed-height" >

                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add / Edit Books for a Course: <?php echo $b_title[0]['crs_title']; ?>
                                </div>
                                <div><font color="red"><?php echo validation_errors(); ?></font></div>
                            </div>	
                            <!--<div class="span12">
                                    <div class="span6">
                                     <label class="radio-inline">
                                            <input type="radio" class="check" onclick="check_ref(this.value)" name="ch" id="reference" value="1">Reference
                                     </label>
                                    </div><div class="span6">
                                     <label class="radio-inline">
                                            <input type="radio" class="check" onclick="check_ref(this.value)" name="ch" id="theory" value="0">Theory
                                     </label></div>
                            </div>-->
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
                                    <thead >
                                        <tr class="" role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Sl No.</th>
                                            <!--<th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Reference </th>-->
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Author </th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Book Title</th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Edition</th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Website</th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Publication </th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Publication Year </th>
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Text Book / Reference Book </th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                            <th class="header" role="columnheader" tabindex="0" aria-controls="example">Delete</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br/><br/>
                            </div>
                            <input type = "hidden" name="book_curriculum_id" id="book_curriculum_id" value ="<?php echo $curriculum_id; ?>"/>
                            <input type = "hidden" name="book_term_id" id="book_term_id" value ="<?php echo $term_id; ?>"/>

                            <input type = "hidden" name="ref_id" id="ref_id" value ="<?php echo $ref_id; ?>"/>

                            <form id="save_books">
                                <div class="form-horizontal" >
                                    <div class="bs-docs-example" id="book_details">
                                        <div class="span3">
                                            <label class="radio-inline">
                                                <input type="radio"  checked class="t" name="ref" id="ref" value="0">Text Book
											</label> 
                                        </div>
										<div class="span4">
                                            <label class="radio-inline control-label">
                                                <input type="radio" class="r" name="ref" id="ref" value="1">Reference Book 
											</label>		
                                        </div>

                                        <div class="row-fluid">
                                            <div class="span6">
                                                <div class="span12"> 
                                                    <div class="control-group span10">
                                                        <p class="control-label" for="book_sl_no">Sl No.:<font color="red"><b>*</b></font></p>
                                                        <div class="controls">
                                                            <?php echo form_input($book_sl_no); ?>
                                                        </div>
                                                    </div>

                                                    <!--  <div class="control-group span6">
                                                          <p class="control-label" for="book_title">Reference:</p>
                                                          <div class="controls">
                                                              <input type="checkbox" name="book_type_1" id="book_type_1" value="1"/>
                                                                                                                      </div>
                                                                                                              </div>-->
                                                </div>


                                                <div class="control-group">
                                                    <p class="control-label" for="book_author">Book Author:<font color="red"><b>*</b></font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($book_author); ?> 
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="book_title">Book Title:<font color="red"><b>*</b></font></p>
                                                    <div class="controls">
                                                        <?php echo form_input($book_title); ?>  
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="book_website">Book Website:</p>
                                                    <div class="controls">
                                                        <?php echo form_input($book_website); ?>  
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="span6">


                                                <div class="control-group">
                                                    <p class="control-label" for="book_edition">Book Edition:</p>
                                                    <div class="controls">
                                                        <?php echo form_input($book_edition); ?> 
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="book_publication">Publication:</p>
                                                    <div class="controls">
                                                        <?php echo form_input($book_publication); ?> 
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <p class="control-label" for="book_publication_year">Publication Year:</p>
                                                    <div class="controls">
                                                        <div class="input-append date">
                                                            <select class="input-medium  book_publication_year" id="book_publication_year_1" name="book_publication_year_1">
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
                                                <a class="btn pull-right btn-primary save_book_details" name="save_book_details" id="save_book_details"><i class="icon-file icon-white"></i> Save</a>
                 <!--   <a id="remove_field1" class="Delete" href="#"><i class="icon-remove pull-right"></i> </a> -->
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <!--<div id="add_before"></div>
                                    <input type="hidden" name="counter" id="counter" value="1"/>-->
                                </div>

                                <br>
                                <!-- <div class="pull-right row">
                                     <a class="btn btn-primary add_book_details" id="add_book_details"><i class="icon-plus icon-white"></i> Add More</a>
                                                                     </div>	-->
                            </form>
                            <br><br>
                            <!-- ends here-->
                            <form  class="form-horizontal" id="book_add_form"  action="<?php echo base_url('curriculum/topic/insert_books_evaluation'); ?>" name="book_add_form"  method="POST" >
                                <div class="navbar">
                                    <input type = "hidden" name="book_course_id" id="book_course_id" value ="<?php echo $course_id; ?>"/>
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('entity_cie'); ?> Evaluation Scheme for Course : <?php echo $b_title[0]['crs_title']; ?>
                                    </div>
                                </div>	
                                <!--row fluid for labels-->
                                <div class="row-fluid">
                                    <div class="span1">
                                        <div class="control-group">
                                            <label class="control-label" for="institute_info"> </label>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <p class="control-label" for="assessment_name">Assessment</p>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <p class="control-label" for="assessment_mode">Assessment Mode </p>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <p class="control-label" for="weightage_in_marks">Weightage in marks</p>
                                        </div>
                                    </div>
                                </div><!--end of row fluid-->		

                                <div id="prgm_curriculum">
                                    <div class="control-group" id="evaluation_details">

                                        <div class="row-fluid">
                                            <div class="span1">
                                                <div class="control-group">

                                                </div>
                                            </div>
                                            <div class="span3">
                                                <div class="control-group">
                                                    <?php echo form_input($assessment_name); ?> 
                                                </div>
                                            </div>
                                            <div class="span3 ">
                                                <div class="control-group">
                                                    <p class="checkbox inline" style="padding-left:90px;">
                                                        <input id="assessment_mode_1" type="checkbox" name="assessment_mode_1" abbr="1" class="assmnt_mode" value="1" />
                                                        Viva&nbsp;/&nbsp;Project
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="span3">
                                                <div class="control-group">
                                                    <?php echo form_input($weightage_in_marks); ?>
                                                </div>
                                            </div>

                                            <div class="span1">

                                                <a id="remove_field1" class="Delete_assessment identify_remove cursor_pointer"><i class="icon-remove"></i> </a> 
                                            </div>

                                        </div><!--end of row fluid-->
                                    </div>

                                    <!-- <div class="pull-right row">
                                        <button type="button" class="btn btn-primary add_evaluation_details" id="add_evaluation_details"><i class="icon-plus-sign icon-white"></i> Add More</button>
                                    </div> -->
                                    <div id="add_more"></div>
                                    <br>

                                    <input type="hidden" name="counter_eval" id="counter_eval" value="1"/>
                                </div>
                                <input type="hidden" name="cie_eval" id="cie_eval" value="1"/>
                                <input type="hidden" name="check_counter" id="check_counter"  value="0"/>
                                <div class="row-fluid">

                                    <div class="span1">
                                        <div class="control-group">
                                            <label class="control-label" for="institute_info"></label>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label class="control-label" for="institute_info"></label>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <p class="control-label" for="institute_info" onchange="total();">Total</p>
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
                                    <button type="button" class="btn btn-primary" id="generate" name="generate" ><i class="icon-file icon-white"></i> Generate Table </button>
                                    <button type="button" class="btn btn-info" id="revert" name="revert"><i class="icon-refresh icon-white"></i> Revert </button>
                                </div>

                                <input type="hidden" name="checked_val" id="checked_val"/>

                                <br><br>
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Course Unitization for <?php echo $this->lang->line('entity_cie'); ?> and <?php echo $this->lang->line('entity_see'); ?>

                                    </div>
                                    <div><font color="red"><?php echo validation_errors(); ?></font></div>
                                </div>
                                <div id="cie_table">
                                </div>								
                                <br>
                                <div class="pull-right row">
                                    <button type="submit" id="update"  class="add_details btn btn-primary" ><i class="icon-file icon-white"></i> Save </button>
                                    <button type="reset" class="btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
                                    <?php if ($ref_id == 'ref_12') { ?>
                                        <!-- redirect to lab experiment page -->
                                        <a href="<?php echo base_url('curriculum/lab_experiment'); ?>" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i> Cancel </a>
                                    <?php } else { ?>
                                        <!-- redirect to topics page -->
                                        <a href="<?php echo base_url('curriculum/topic'); ?>" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i> Cancel </a>
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
                            <p> Are you sure you want to delete?</p>
                        </div>

                        <div class="modal-footer">
                            <button class="delete_dm btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <div id="edit_book_details" class="modal hide fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;left:500px; width:920px;" data-controls-modal="" data-backdrop="static" data-keyboard="true"><br>

                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Edit <?php echo $b_title[0]['crs_title']; ?>  Book List
                            </div>
                        </div>
                        <div class="modal-body">

                            <form id="edit_books">
                                <div class="" >
                                    <div class="bs-docs-example" id="">
                                        <div class="row-fluid">
                                            <div class="span6">


                                                <div class="span12"> 
                                                    <div class="span3">
                                                        <label class="radio-inline">
                                                            <input type="radio"  checked class="t" name="ref_e" id="ref_e" value="0"> - Text Book
                                                        </label>
                                                    </div><div class="span4">
                                                        <label class="radio-inline">
                                                            <input type="radio" class="r" name="ref_e" id="ref_e" value="1"> Reference Book
                                                        </label>
                                                    </div><br/><br/>
                                                    <div class="control-group span6">
                                                      <!--  <p class="control-label" for="">Sl No.:<font color="red"><b>*</b></font></p>-->
                                                        <div class="controls">
                                                            Sl No.: &nbsp;&nbsp;<font color="red"><b>*</b></font>&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="text" name="book_sl_no_e" id="book_sl_no_e" value="" class= "required loginRegex onlyDigit input-mini sl_no_e"/>
                                                            <input type="hidden" id="book_id" name="book_id" value=""/>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="control-group">
                                                    <!--<p class="control-label" for="">Book Author:<font color="red"><b>*</b></font></p>-->
                                                    <div class="controls">
                                                        Book Author:  <font color="red"><b>*</b></font>     <input type="text" name="book_author_e" id="book_author_e" value="" class = 'required noSpecialChars input-xlarge author_e'/>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                  <!--  <p class="control-label" for="">Book Title:<font color="red"><b>*</b></font></p>-->
                                                    <div class="controls">
                                                        Book Title: <font color="red"><b>*</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="book_title_e" id="book_title_e" value="" class = 'required noSpecialChars input-xlarge title_e'/>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                  <!--  <p class="control-label" for="">Book Website:<font color="red"><b>*</b></font></p>-->
                                                    <div class="controls">
                                                        Book Website: <input type="text" name="book_website_e" id="book_website_e" value="" class = ' noSpecialChars valid_url input-xlarge website_e'/>
                                                    </div>
                                                </div>
                                            </div><br/><br/>
                                            <div class="span6">
                                                <div class="control-group">
                                                   <!-- <p class="control-label" for="">Book Edition:</p>-->
                                                    <div class="controls">
                                                        Book Edition: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type="text" name="book_edition_e" id="book_edition_e" value="" class = ' noSpecialChars1 input-xlarge edition_e'/>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                   <!-- <p class="control-label" for="">Publication:</p>-->
                                                    <div class="controls">
                                                        Publication: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <input type="text" name="book_publication_e" id="book_publication_e" value="" class =' noSpecialChars input-xlarge publication_e'> 
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                   <!-- <p class="control-label" for="">Publication Year:</p>-->
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
    </body>
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/book_add.js'); ?> "></script>
</html>	
