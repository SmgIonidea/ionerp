<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: CO to PO Mapping Review View page which provides the facility to review and add comment for the 
 * Mapping of COs (Course Outcome)to particular course with POs (program outcomes) .	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013   Mritunjay B S      Added file headers, function headers & comments.
 * 11-03-2016   Shayista Mulla   Given full point. 
 * 13-07-2016	Bhagyalaxmi.S.S		Handled OE-PIs
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
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">

                                    Mapping between Course Outcomes(COs) and <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Course-wise
                                </div>

                                <div id="loading" class="ui-widget-overlay ui-front">
                                    <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                                </div>
                            </div> 
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="span5">
                                        Curriculum :&nbsp; <b><?php echo $clo_title[0]['crclm_name']; ?></b></div>
                                    <div class="span3">
                                        Term :&nbsp;<b><?php echo $clo_title[0]['term_name']; ?></b></div>
                                    <div class="span4">
                                        Course :&nbsp;<b><?php echo $clo_title[0]['crs_title']; ?></b></div>
                                </div>
                                <div class="span12">
                                    <div class="span5">
                                        <?php echo $this->lang->line('course_owner_full'); ?> :&nbsp; <b><?php echo $owner[0]['title'] . ' ' . $owner[0]['first_name'] . ' ' . $owner[0]['last_name']; ?></b>
                                    </div>
                                    <div class="span3"></div>
                                    <div class="span4"></div>
                                </div>
                                <div class="span12">
                                    <div class="row-fluid">
                                        <?php echo form_input($crclm_id); ?>
                                        <?php echo form_input($term); ?>
                                        <?php echo form_input($course); ?>
                                        <div class="bs-docs-example span12 scrollspy-example" style=" height:100%; overflow:auto;" >
                                            <form id="table1" > 
                                        </div>

                                        <div class="span12">
                                            <div data-spy="scroll" class="bs-docs-example span12" style="width:100%;">
                                                <div  id="" style="span6">
                                                    <label> Overall Justification : </label>
                                                    <textarea id="text3" style="width:98%" rows="3" cols="5" placeholder="Enter text " maxlength="200" disabled> </textarea>
                                                </div>
                                            </div>

                                            <!--span4 ends here-->
                                        </div>
                                    </div>
                                    </form>
                                    </br><br>
                                    <?php if ($state_id == 2) { ?>
                                        <div class="pull-right">
                                            <b id="rework"  class="btn btn-danger" ><i class="icon-repeat icon-white"></i> Rework </b>
                                            <b id="scan_row_col"  class="btn btn-success "  ><i class="icon-ok icon-white"></i> Accept </b>
                                        </div>
                                    <?php } ?>
                                    <div id="reviewer">

                                    </div>
                                    <input type="hidden" id="text1" name="text1"/> <input type="hidden" id="text2" name="text2"/>
                                    <!--Modal to display the message "approved"-->
                                    <div id="myModal2" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal2" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Review the entire mapping between COs and <?php echo $this->lang->line('sos'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p> <b> Current State: </b> Review of mapping between COs and <?php echo $this->lang->line('sos'); ?> has been completed. </p>
                                            <p> <b> Next State: </b> Term-wise review of mapping between COs and <?php echo $this->lang->line('sos'); ?>. </p>
                                            <p> An email will be sent to <?php echo $this->lang->line('program_owner_full'); ?> (Curriculum Owner) - <b id="program_owner_user" style="color:rgb(230, 122, 23);"></b> </p>

                                            <h4><center> Current status of curriculum: <b id="crclm_name_co_po_accept" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                            <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_review.png'); ?>">
                                        </div>

                                        <div class="modal-body">
                                            <p> Are you sure you want to accept the entire mapping between COs and <?php echo $this->lang->line('sos'); ?> and send it for Term-wise approval? </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" onClick="send();
                                                    send_review();"><i class="icon-ok icon-white"></i> Ok </button> 
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                                        </div>
                                    </div>

                                    <!--Modal to display the message "Sent for Approval"-->
                                    <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Review accept Confirmation 
                                                </div>
                                            </div>
                                        </div>	
                                        <div class="modal-body">
                                            <p> The review of entire mapping between COs and <?php echo $this->lang->line('sos'); ?> has been accepted and it is sent for Term-wise approval. An Email is sent to the concerned <?php echo $this->lang->line('program_owner_full'); ?> to initiate approval process.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" id="refresh" onClick="approve_accept();"><i class="icon-ok icon-white"></i> Ok </button> 
                                        </div>
                                    </div>

                                    <!--Modal to display the message "review confirmation"-->
                                    <div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Send for rework of mapping between COs and <?php echo $this->lang->line('sos'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body">
                                            <p> <b> Current State: </b> Review of mapping between COs to <?php echo $this->lang->line('sos'); ?> has been completed. </p>
                                            <p> <b> Next State: </b> Rework on mapping between COs and <?php echo $this->lang->line('sos'); ?>. </p>
                                            <p> An email will be sent to <?php echo $this->lang->line('course_owner_full'); ?> - <b id="course_owner_user" style="color:rgb(230, 122, 23);"></b> </p>

                                            <h4><center> Current status of curriculum: <b id="crclm_name_co_po_rework" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
                                            <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/co_po_review.png'); ?>">
                                        </div>

                                        <div class="modal-body">
                                            <p> Are you sure you want to send the mapping between COs and <?php echo $this->lang->line('sos'); ?> for rework? </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal" id="refresh_hide" onClick="send_reviewrework();
                                                    "><i class="icon-ok icon-white"></i> Ok </button> 
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                                        </div>
                                    </div>

                                    <!-- Modal to display saved outcome elements & performance indicators -->
                                    <div id="myModal_pm" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_pm" data-backdrop="static" data-keyboard="true">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Selected <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body" id="selected_pm_modal">

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>Close</button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Do not place contents below this line -->
                    </section>			
                </div>					
            </div>
        </div>

        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
    </body>
    <script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/review_clo_po_mapping.js'); ?>" ></script>
    <script type="text/javascript">
                                                $.fn.popover.Constructor.prototype.fixTitle = function () {};
    </script>
</html>

