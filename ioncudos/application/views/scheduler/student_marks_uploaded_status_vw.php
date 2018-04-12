<!--head here -->
<?php //$this->load->view('includes/head'); ?>
<!--branding here-->
<?php //$this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php //$this->load->view('includes/navbar'); ?>

<style type="text/css">
    .large_modal{
        width:70%;
        margin-left: -35%; 
    }
</style>
<div class="container-fluid">
    <div class="row-fluid">
        <?php //$this->load->view('includes/sidenav_5'); ?> 
				<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
        <div class="span12">
            <!-- Contents -->
            <section id="contents" >
                <div class="">
                    <input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>"/>
                    <!--content goes here-->	
                    <div id="loading" class="ui-widget-overlay ui-front">
                        <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                    </div>
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Uploaded Student Marks Status
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="pull-right">
                            <!--<a href="<?php echo base_url('scheduler/student_marks_upload'); ?>" class="btn btn-danger"><i class="icon icon-arrow-left icon-white"></i> Back</a>-->
                        </div>
                        <div class="span12" id="tabs">
                            <ul id="myTab" class="nav nav-tabs">
                                <li class="active"><a href="#processed_files" role="tab" data-toggle="tab"> Processed Files </a></li>
                                <li><a href="#rejected_files" role="tab" data-toggle="tab"> Rejected Files </a></li>
                                <li><a href="#pending_files" role="tab" data-toggle="tab"> Pending Files </a></li>
                                <li><a href="#invalid_files" role="tab" data-toggle="tab"> Invalid Files </a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <!--Tab one starts here-->
                                <div class="tab-pane fade in active" id="processed_files">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" style="width:370px;">
                                            Processed Files List
                                        </div>
                                    </div>
                                    <?php
                                    if (sizeof($accepted_files) > 2) {
                                        echo "<table class='table table-bordered table-stripped'>";
                                        echo "<thead><tr><th><center>Sl No.</></th><th><center>Subject Code</center></th><th><center>Branch</center></th><th><center>Year</center></th><th><center>File</center></th><!--<th><center>Status</center></th>--></tr></thead>";
                                        $i = 0;
                                        foreach ($accepted_files as $file) {
                                            if ($file != '.' && $file != '..') {
                                                $i++;
                                                $file_name = current(explode('.', $file));
                                                $course_data = explode('_', $file_name);
                                                $crs_code = $course_data[5];
                                                $branch_code = $course_data[2];
                                                $end_year = $course_data[1];
                                                echo "<tr>";
                                                echo "<td><center>" . $i . "</center></td>";
                                                echo "<td><center>" . $crs_code . "</center></td>";
                                                echo "<td><center>" . $branch_code . "</center></td>";
                                                echo "<td><center>" . $end_year . "</center></td>";
                                                echo "<td><center>" . $file . "</center></td>";
                                                //echo "<td><center><button class='btn btn-success'>Processed</button></center></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "<h4 class='err_msg'>No files found</h4>";
                                    }
                                    ?>
                                    <div class="well" style="color: green;">Note: These files are processed successfully.
                                    </div>
                                </div>
                                <!--Tab one ends here-->
                                <!--Tab two starts here-->
                                <div class="tab-pane fade" id="rejected_files">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" style="width:370px;">
                                            Rejected Files List
                                        </div>
                                    </div>
                                    <?php
								//	$rejected_files_new = array_slice($rejected_files,0,-1);
									$rejected_files_new = $rejected_files;
                                    if (sizeof($rejected_files_new) > 2) {
                                        echo "<table class='table table-bordered table-stripped' id='rejected_file_table'>";
                                        echo "<thead><tr><th><center>Sl No.</></th><th><center>Subject Code</center></th><th><center>Branch</center></th><th><center>Year</center></th><th><center>File</center></th><th><center>Remarks</center></th><th><center>Upload</center></th></tr></thead>";
                                        $i = 0;
										
										
                                        foreach ($rejected_files_new as $file) {
                                            if ($file != '.' && $file != '..') {
                                                $i++;
                                                $file_name = current(explode('.', $file));
                                                $course_data = explode('_', $file_name);
                                                $crs_code = $course_data[5];
                                                $branch_code = $course_data[2];
                                                $end_year = $course_data[1];
                                                echo "<tr>";
                                                echo "<td><center>" . $i . "</center></td>";
                                                echo "<td><center>" . $crs_code . "</center></td>";
                                                echo "<td><center>" . $branch_code . "</center></td>";
                                                echo "<td><center>" . $end_year . "</center></td>";
                                                echo "<td><center>" . $file . "</center></td>";
                                                echo "<td><center><a href='#' class='get_tab_name show_remarks' temp_tab='" . $file_name . "' id='show_remarks'><button class='btn btn-danger'><i class='icon icon-white icon-eye-open'></i> Remarks</button></a></center></td>";
                                                echo "<td><center><a href='#' id='reupload_file'  temp_tab='" . $file_name . "' class='get_tab_name reupload_file btn btn-success'><i class='icon icon-upload icon-white'></i> Upload</a></center></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "<h4 class='err_msg'>No files found</h4>";
                                    }
                                    ?>
                                    <div id="rejected_files_modal" class="modal hide fade large_modal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                   View Remarks
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div id="file_name"></div>
											<div id="remark_mismatch"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                                        </div>
                                    </div>
                                    <div id="error_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_dialog_window" data-backdrop="static" data-keyboard="true"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Warning!
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div id="err_status"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
                                        </div>
                                    </div>
                                    <!--Form to re-upload file-->
                                    <form name="student_data_upload_form" id="student_data_upload_form" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="rej_file_name" id="rej_file_name"/>
                                        <input name="upload_file" id="upload_file" type="file" size="1" style="opacity:0" />
                                    </form>
                                    <!--re-upload form ends-->
                                    <div class="well" style="color: #a9302a;">Note: Rejected files will be having one of the below reasons:
                                        <ol>
                                            <li>Academic year may not exists for selected course.</li>
                                            <li>Incorrect number of questions</li>
                                        </ol>
                                    </div>
                                </div>
                                <!--Tab two ends here-->
                                <!--Tab three starts here-->
                                <div class="tab-pane fade" id="pending_files">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" style="width:370px;">
                                            Pending Files List
                                        </div>
                                    </div>
										<div class="span2 process_all_files pull-right">										
											<a href="<?php echo base_url('scheduler/student_marks_upload/dir'); ?>"class="btn btn-primary" target="_blank">Process Pending Files</a>										
										</div>
									<br/>
                                    <?php
                                    if (sizeof($pending_files) > 2) {
                                        echo "<table class='table table-bordered table-stripped'>";
                                        echo "<thead><tr><th><center>Sl No.</></th><th><center>Subject Code</center></th><th><center>Branch</center></th><th><center>Year</center></th><th><center>File</center></th><th><center>Status</center></th></tr></thead>";
                                        $i = 0;
                                        foreach ($pending_files as $file) {
                                            if ($file != '.' && $file != '..') {
                                                $i++;
                                                $file_name = current(explode('.', $file));
                                                $course_data = explode('_', $file_name);
                                                $crs_code = $course_data[5];
                                                $branch_code = $course_data[2];
                                                $end_year = $course_data[1];
                                                echo "<tr>";
                                                echo "<td><center>" . $i . "</center></td>";
                                                echo "<td><center>" . $crs_code . "</center></td>";
                                                echo "<td><center>" . $branch_code . "</center></td>";
                                                echo "<td><center>" . $end_year . "</center></td>";
                                                echo "<td><center>" . $file . "</center></td>";
                                                echo "<td><center><button class='btn btn-success process_file' file_name='" . $file . "'>Process now</button></center></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "<h4 class='err_msg'>Queue is empty. No files pending</h4>";
                                    }
                                    ?>
                                    <div class="well"><b>Note : </b> These files in the queue will be processed in next Scheduled time.</div>
                                </div>
                                <!--Tab three ends here-->
                                <div class="tab-pane fade" id="invalid_files">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom" style="width:370px;">
                                            Invalid Files List
                                        </div>
                                    </div>
                                    <div class="pull-right" style="margin-right:5%;marigin-bottom:2%;">
                                        <a id="delete_all_files" class="btn btn-danger">Delete All</a>
                                    </div>
                                    <br/><br/>
                                    <?php
                                    if (sizeof($invalid_files) > 2) {
                                        echo "<table class='table table-bordered table-stripped'>";
                                        echo "<thead><tr><th style='width:2%;'><center>Sl  No.</></th><th style='width:5%;'><center>File</center></th><th style='width:5%;'><center>Delete</center></th></tr></thead>";
                                        $i = 0;
                                        foreach ($invalid_files as $file) {
                                            if ($file != '.' && $file != '..') {
                                                $i++;
                                                echo "<tr>";
                                                echo "<td><center>" . $i . "</center></td>";
                                                echo "<td>" . $file . "</td>";
                                                echo "<td><center><button class='btn btn-danger del_invalid_file' file_name='" . $file . "'>Delete</button></center></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        echo "</table>";
                                    } else {
                                        echo "<h4 class='err_msg'>Queue is empty.</h4>";
                                    }
                                    ?>
                                </div>
                            </div><!-- end of tab-content-->
                        </div>

                    </div>
                    <br/><br/><br/>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php //$this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/js_v3'); ?>
<script src="<?php echo base_url(); ?>twitterbootstrap/js/custom/student_marks_upload.js" type="text/javascript"></script>	
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>	
