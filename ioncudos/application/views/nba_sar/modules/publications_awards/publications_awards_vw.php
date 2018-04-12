<?php
/**
 * Description	:	Publications and Awards in inter-institute events by students of the 
  programme of study view page
 * Created		:	07 June, 2016
 * Author		:	Arihant Prasad
 * Modification History:-
 *   Date                    Modified By                         	Description
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_6'); ?>
        <div class="span10">
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!-- content goes here -->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo "Publications & Awards in inter-institute events by students list" ?> 
                        </div>
                    </div>							
                    <div class="row-fluid">								
                        <table>
                            <tr>
                                <td>
                                    <label>
                                        Department:<font color='red'>*</font> 
                                        <select id="department" name="department" autofocus = "autofocus" class="input-large" onchange="select_pgm_list();">
                                            <option value="">Select Department</option>
                                            <?php foreach ($dept_result as $listitem) { ?>
                                                <option value="<?php echo htmlentities($listitem['dept_id']); ?>"> <?php echo $listitem['dept_name']; ?></option>
                                            <?php } ?>
                                        </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        Program:<font color='red'>*</font>
                                        <select id="pgm_id" name="pgm_id" class="input-medium" onchange="select_crclm_list();">
                                            <option>Select Program</option>
                                        </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        Curriculum:<font color='red'>*</font> 
                                        <select id="crclm_id" name="crclm_id" autofocus = "autofocus" onchange="select_termlist();" class="input-large" >
                                            <option value="">Select Curriculum</option>
                                        </select> <?php echo str_repeat('&nbsp;', 8); ?>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        Term:<font color='red'>*</font> 
                                        <select id="term" name="term" class="input-medium" onchange="GetSelectedValue();">
                                            <option>Select Term</option>
                                        </select>
                                    </label>
                                </td>
                        <!--	<td> For future use dont remove
                                        <label>
                                                Course:<font color='red'>*</font> 
                                                <select id="course" name="course" class="input-medium" onchange="GetSelectedValue();">
                                                        <option>Select Course</option>
                                                </select>
                                        </label>
                                </td>-->
                            </tr>
                        </table>
                    </div>
                </div>
            </section>

            <section id="contents" 	>
                <div class="bs-docs-example" style=" height:auto; overflow:auto;">  
                    <div class="row-fluid">										  
                        <table class="table table-bordered table-hover" id="example" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl.No</th>
                                    <th>Title</th><th>Participant(s)</th><th>Position</th><th>Venue</th><th>Date </th><th>Upload</th><th>Edit</th><th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section id="contents">
                <div class="bs-docs-example"  style="height:auto; overflow:auto;">
                    <div class="row-fluid" id="">	<br/>									  

                        <form id="publication_awards" name="publication_awards" enctype='multipart/form-data' method="POST" class="form-horizontal">
                            <input type="hidden" id="award_publc_id" name="award_publc_id"/>
                            <input type="hidden" id="per_table_id" name="per_table_id"/>
                            <input type="hidden" id="uload_id" name="uload_id"/>
                            <div class="span6">				
                                <div class="control-group">
                                    <p class="control-label" for="publication_title"> Title : <font color="red"><b>*</b></font></p>
                                    <div class="controls">
                                        <div class="input-append ">
                                            <input placeholder="Enter title" type="text" id="publication_title" name="publication_title" class="required"/>
                                        </div>
                                    </div>
                                </div>																							
                                <div class="control-group">
                                    <p class="control-label" for="participants">Participant(s): <font color="red"><b>*</b></font></p>
                                    <div class="controls">
                                        <div class="input-append ">
                                            <textarea placeholder="Enter participant(s) name" type="text" id="participants" name="participants" class="required"></textarea>
                                        </div>
                                    </div>
                                </div>	

                                <div class="control-group">
                                    <p class="control-label" for="level_of_presentation"> Level : <font color="red"><b>*</b></font></p>
                                    <div class="controls">
                                        <div class="input-append ">																
                                            <select name="level_of_presentation" id="level_of_presentation">		
                                                <?php foreach ($level_of_presentation as $st) { ?>
                                                    <option value="<?php echo $st['mt_details_id']; ?>">
                                                        <?php echo $st['mt_details_name']; ?></option>
                                                <?php } ?>	
                                            </select>
                                        </div>
                                    </div>
                                </div>	

                                <div class="control-group">
                                    <p class="control-label" for="position"> Position : <font color="red"><b></b></font></p>
                                    <div class="controls">
                                        <div class="input-append ">

                                            <input placeholder="Enter position" type="text"  id="position" name="position" class=""/>
                                        </div>
                                    </div>
                                </div>												

                                <div class="control-group">
                                    <p class="control-label" for="paper"> Date : <font color="red"><b>*</b></font></p>
                                    <div class="controls">
                                        <div class="input-append date">
                                            <input placeholder="Select date" readonly  type="text" id="award_publc_date" name="award_publc_date" class="required" style="width:180px;"/>
                                            <span class="add-on" id="award_publc_date_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                            <span id='error_award_publc_date_btn'></span>
                                        </div>					
                                    </div>
                                </div>

                                <div class="control-group">
                                    <p class="control-label" for="Offerprogram">Venue : <font color="red"><b></b></font></p>
                                    <div class="controls">
                                        <div class="input-append ">
                                            <textarea placeholder="Enter venue" type="text" id="award_venue" name="award_venue" class=""></textarea>
                                        </div>
                                    </div>
                                </div>	
                            </div>
                            <div class="span6"> 																	
                                <div class="">	
                                    <b>Abstract:</b>
                                    <textarea placeholder="Enter Abstract " class="question_textarea" name="award_abstract" id="award_abstract" style="" ></textarea>
                                </div>	
                            </div>
                        </form>

                        <div class="form-inline pull-right ">
                            <br/><br/><button value="1" type="button" name="update_award_publc" id="update_award_publc" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
                            <button type="button" name="save_award_publc" id="save_award_publc" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
                            <button type="reset" id="reset_award_publc"  class="clear_all btn btn-info" onclick="reset_publication_awards()" ><i class="icon-refresh icon-white"></i>Reset</button>
                            <br/><br/>
                        </div>				
                    </div>
                </div>
            </section>	


            <div id="Exist" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p>Select all the drop-downs.</p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                </div>
            </div>	
            <form id="myform" name="myform" method="POST" enctype="multipart/form-data" >
                <div class="modal hide fade" id="upload_modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_upload_artifacts">
                                Upload Files
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div id="res_guid_files"></div>
                        <div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
                            <img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="" />
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-danger pull-right close_up_div" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>	

                        <button class="btn btn-primary pull-right" style="margin-right: 3px; margin-left: 3px;" id="save_res_guid_desc" name="save_res_guid_desc" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>

                        <button class="btn btn-success pull-right" style="margin-right: 3px; margin-left: 3px;" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload</button>
                    </div>
                </div>
            </form>
            <div id="delete_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>
                <div class="modal-body">									
                    <p>          Are you sure that you want to delete this record?</p>
                </div>

                <div class="modal-footer">
                        <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                    <a class="btn btn-primary" data-dismiss="modal" id="delete_awards"><i class="icon-ok icon-white"></i> Ok </a> 
                    <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
                </div>
            </div>
            <div id="delete_res_guid_file_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>
                <div class="modal-body">									
                    Do you want to delete this file?
                </div>

                <div class="modal-footer">
                        <!--<a class="btn btn-primary" onClick="javascript:publish();"><i class="icon-ok icon-white"></i> Ok </a> -->
                    <a class="btn btn-primary" data-dismiss="modal" id="delete_uploaded_files"><i class="icon-ok icon-white"></i> Ok </a> 
                    <button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
                </div>
            </div>		

            <!-- Modal to display incorrect file extension status  -->
            <div id="invalid_file_extension" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Invalid file extension
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> Unable to upload the file  because the file extension is invalid.  </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                </div>
            </div>	

            <!--Warning modal for exceeding the upload file size-->
            <div class="modal hide fade" id="larger" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
                <div class="modal-header">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> Uploaded file size is larger than <span class="badge badge-important">10MB </span>.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"style="margin-right: 2px;"><i class="icon-remove icon-white"></i> Close</button>									
                </div>
            </div>
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick_faculty_contribution.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/nba_sar/publications_awards.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
