<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CLO to PO Mapping Rework View page which facilitates to view the comment for the 
 * Mapping of clo's (Course Learning Outcome)to particular course with po's (program outcomes) and rework on comments .	  
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013      Mritunjay B S     		Added file headers, function headers & comments.
 * 08-05-2015		Abhinay B Angadi		UI and Bug fixes done for Bloom's Level & Delivery methods
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
            <section id="contents">
                <div id="show">
                    <div id="hide">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Mapping between Course Outcomes(COs) and <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>  Course-wise
                                </div>

                                <div id="loading" class="ui-widget-overlay ui-front">
                                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                                </div>
                            </div> 
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="span4">
                                        Curriculum :&nbsp; <b><?php echo $clo_title[0]['crclm_name']; ?></b></div>
                                    <div class="span3">
                                        Term :&nbsp;<b><?php echo $clo_title[0]['term_name']; ?></b></div>
                                    <div class="span4">
                                        Course :&nbsp;<b><?php echo $clo_title[0]['crs_title']; ?></b></div>
                                </div>
                                <div class="span12">
                                    <div class="row-fluid">
                                        <?php echo form_input($crclm_id); ?>
                                        <?php echo form_input($term); ?>
                                        <?php echo form_input($course); ?>
                                        <div class="bs-docs-example span8 scrollspy-example" style="width: 100%; height:auto; overflow:auto;" >
                                            <form id="table1" > 
                                        </div>

                                        <div class="span12">
                                            <div data-spy="scroll" class="bs-docs-example span12" style="width:100%;">
                                                <div  id="" style="span6">
                                                    <label> Overall Justification : </label>
                                                    <textarea id="text3" style="width:98%" rows="3" cols="5" placeholder="Enter text " maxlength="200" enable> </textarea>
                                                </div>
                                            </div>

                                            <input type="hidden" name="map_level_val" id="map_level_val"/>
                                        </div>
                                        </form>
                                        <br><br>
                                        <?php if ($state_id == 6 || $state_id == 3 || $state_id == 1) { ?>
                                            <div class="pull-right">
                                                <button id="scan_row_col" class="btn my-btn" ><i class="icon-user icon-white" ></i> Send for Review </button>
                                            </div>
                                        <?php } ?>
                                        <div id="reviewer">
                                        </div>

                                        <!--Checkbox Modal-->
                                        <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 	aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                            </br>
                                            <div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> Select <?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicators </div> </div> </div>
                                            <div class="modal-body" id="comment">
                                            </div>
                                            <input type="hidden" name="clo_po_id" id="clo_po_id" />
                                            <div class="modal-footer">
                                                <button  id="update" onclick="return validateForm();" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </button>
                                                <button onclick="uncheck();" class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Cancel </button>  
                                            </div>
                                        </div>	
                                        <!--Modal to show OE & PIs are made optional-->
                                        <div id="oe_pi_optional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="oe_pi_optional" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"><br>
                                            <div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> <?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicators </div> </div> </div>
                                            <div class="modal-body">
                                                <p> There are no <?php echo $this->lang->line('outcome_element_short'); ?> and Performance Indicators(PIs) defined for this <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> . Hence, there is no mapping of <?php echo $this->lang->line('outcome_element_short'); ?> & PIs. </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" data-dismiss="modal"> <i class="icon-ok icon-white"></i> Ok </button>  
                                            </div>
                                        </div>
                                        <!--Modal to confirm before deleting -->
                                        <div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal2" data-backdrop="static" data-keyboard="false">
                                            <br>
                                            <div class="container-fluid"> <div class="navbar"> <div class="navbar-inner-custom"> Uncheck Mapping between COs and <?php echo $this->lang->line('sos'); ?> </div> </div> </div>
                                            <div class="modal-body">
                                                <p> Are you sure that you want to uncheck the mapping between COs and <?php echo $this->lang->line('sos'); ?>? </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onclick="unmapping();">  <i class="icon-ok icon-white"></i> Ok </button>
                                                <button type="" class="cancel btn btn-danger" data-dismiss="modal" onClick="check();"> <i class="icon-remove icon-white"> </i> Cancel </button>
                                            </div>
                                        </div>

                                        <!--Modal to display the message "All are checked"-->
                                        <div id="myModal3" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal3" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Send mapping between COs and <?php echo $this->lang->line('sos'); ?> for review
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-body">
                                                <p> <b> Current State: </b> Mapping between COs to <?php echo $this->lang->line('sos'); ?> has been completed. </p>
                                                <p> <b> Next State: </b> Review of mapping between COs and <?php echo $this->lang->line('sos'); ?>. </p>
                                                <p> An email will be sent to Course Reviewer - <b id="course_reviewer_user" style="color:rgb(230, 122, 23);"></b> </p>

                                                <h4><center> Current status of Curriculum: <b id="crclm_name_co_po_review" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                                <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_crswise.png'); ?>">
                                            </div>

                                            <div class="modal-body">
                                                <p> Are you sure you want to send entire mapping between COs and <?php echo $this->lang->line('sos'); ?> for review? </p>
                                            </div>


                                            <?php if ($review_flag[0]['skip_review'] == 1) { ?>
                                                <div class="modal-footer">
                                                    <button class="btn my-btn" data-dismiss="modal" onClick="send_review();"><i class="icon-user icon-white"></i> Send for Review </button>
                                                    <button class="btn btn-success" data-dismiss="modal" onClick="skip_review();"><i class="icon-ok icon-white"></i> Skip Review </button>
                                                    <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel</button> 
                                                </div>
                                            <?php } else { ?>
                                                <div class="modal-footer">
                                                    <button class="btn my-btn" data-dismiss="modal" onClick="send_review();"><i class="icon-user icon-white"></i> Send for Review </button>

                                                    <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel</button> 
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <!--Modal to display the message "Sent for Approval"-->
                                        <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Mapping between COs and <?php echo $this->lang->line('sos'); ?> Status
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">												
                                                <h4><center> Current status of curriculum: <b id="crclm_name_review" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                            </div>

                                            <div class="modal-body">
                                                <p> The entire mapping between COs and <?php echo $this->lang->line('sos'); ?> has been sent for review to the concerned Course Reviewer.</p>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-primary" data-dismiss="modal" id="refresh" ><i class="icon-ok icon-white"></i> Ok </button> 
                                            </div>
                                        </div>

                                        <!--Modal to display the message "Rows marked grey needs your attention"-->
                                        <div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal5" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Mapping between COs and <?php echo $this->lang->line('sos'); ?> Status
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <p> The entire mapping between COs and <?php echo $this->lang->line('sos'); ?> has to be completed before sending it for review.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </button> 
                                            </div>
                                        </div>

                                        <!-- Modal to display saved outcome elements & performance indicators -->
                                        <div id="myModal_pm" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pm" data-backdrop="static" data-keyboard="true"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Selected <?php echo $this->lang->line('outcome_element_short'); ?> & Performance Indicators
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-body" id="selected_pm_modal">

                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button> 
                                            </div>
                                        </div>

                                        <!--Modal to display the message "Select PI's"-->
                                        <div id="select_pis" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal5" data-backdrop="static" data-keyboard="false"></br>
                                            <div class="container-fluid">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Warning 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-body">
                                                <p> Select Performance Indicators(PIs). </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" data-dismiss="modal" ><i class="icon-ok icon-white"></i> Ok </button> 
                                            </div>
                                        </div>

                                        <!--Modal to Add more Course Outcome -->
                                        <div id="add_more_co_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_add_more_co_statement" data-backdrop="static" data-keyboard="true">
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Add Course Outcome(CO)
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="add_co_statement_view_form">
                                                <div class="modal-body" id="add_co_statement_view">

                                                </div>
                                            </form><br/><br/>
                                            <div class="modal-footer">
                                                <button class="save_co_btn btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span> Save </button>
                                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
                                            </div>
                                        </div>

                                        <!--Modal to edit Course Outcome -->
                                        <div id="edit_clo_statement" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_edit_clo_statement" data-backdrop="static" data-keyboard="true">
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Edit Course Outcome(CO) Statement
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="edit_clo_statement_view_form">
                                                <div class="modal-body" id="edit_clo_statement_view">
                                                </div>
                                            </form>
                                            <div class="modal-footer">
                                                <button id="update_clo_statement_btn" class="btn btn-primary update_clo_statement_btn"><i class="icon-ok icon-white"></i>Update</button>
                                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
                                            </div>
                                        </div>

                                        <!--Modal to delete course outcome statement-->
                                        <div id="delete_clo_div" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" >
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Delete Course Outcome
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body" id="comment">
                                                <p> Are you sure that you want to delete this Course Outcome ? </p>
                                                <input type="hidden" name="clo_id_val" id="clo_id_val" />
                                            </div>
                                            <div class="modal-footer">
                                                <button  class="btn btn-primary delete_clo_btn" data-dismiss="modal" aria-hidden="true" ><i class="icon-ok icon-white"></i> Ok </button>
                                                <button  type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Do not place contents below this line-->
                        </section>
                    </div>
                </div>					
        </div>
    </div>

    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/rework_clo_po_mapping.js'); ?>" ></script>
