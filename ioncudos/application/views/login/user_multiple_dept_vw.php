<?php
/**
 * Description	:	View file for Login Page of the application.
 *
 * Created		:	25-03-2013.
 *
 * Modification History:
 * Date				Modified By				Description
 * 19-08-2013		Abhinay B.Angadi        Added file headers, indentations.
 * 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
 * 											Code cleaning.
  --------------------------------------------------------------------------------
 */
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <title> <?php echo $title . ' | '; ?> IonCUDOS </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <?php $this->load->view('includes/head'); ?>
        <!-- Le styles -->
        <link href="<?php echo base_url('twitterbootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/css/docs.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/css/custom.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.css'); ?>" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="twitterbootstrap/js/html5shiv.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack();" onpageshow="if (event.persisted) noBack();">
        <input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
	
        <!-- Subhead
        ================================================== -->
        <header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62);">
            <!--<div class="container">-->
            <div class="container-fluid">
                <img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 227px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 5px;">
                <p><?php echo str_repeat('&nbsp;', 34); ?>
                    
                    
            </div>
        </header>

        <div class="container-fliud fixed-height"><br>






            <!-- Modal to display Confirmation message before sending for Approval -->
            <div id="multi_dept_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="request_password_dialog_id" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Switch Department
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                        <div class="row-fluid ">
                            <div class="span8">
                                <div class="control-group">
                                    <div class="controls" id="place_multi_dept_modal">
                                        <label class="control-label" for="email"> Department: <font color='red'> * </font></label>
                                        <select name="department_list" id="department_list" class="required">
                                            <option value="">Select Department</option>
                                            <?php foreach ($dept_data as $dept) { ?>
                                                <option value="<?php echo $dept['assigned_dept_id']; ?> "><?php echo $dept['dept_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                        <span id="err_msg"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="span4 pull-right">
                                <div class="control-group">
                                    <div class="controls pull-right">
                                        <label>Home Department</label>
                                        <button type="button" id="log_in_my_dept" base_dept_id = "<?php echo $this->ion_auth->user()->row()->base_dept_id; ?>" user_id="<?php echo $this->ion_auth->user()->row()->id; ?>"class="btn btn-success"><i class="icon-home icon-white"></i> Log-in</button>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div>
						<p><b>Note:</b> As you are Associated with more than one department, select the department and click on log-in under which you would like to work.</p>
						</div>
                        </div>
                    <div class="modal-footer">
                        <div class="pull-left">
                        <button type="button" id="submit_button_id" class="btn btn-success"><i class="icon-user icon-white"></i> Log-in </button>
                        <a href="<?php echo base_url('logout'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i>Cancel </a>
                        </div>
                    </div>
                </div>
                
                <!-- Modal to display the Approval status message -->
                <div id="sent_email_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="sent_email_dialog_id" data-backdrop="static" data-keyboard="true"></br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Request Confirmation
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p> Request has been sent successfully. An email is sent to your Email Id</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" onclick="submit_success();"><i class="icon-ok icon-white"></i> Ok </button>
                    </div>
                </div>
                <!-- Modal to display the Error message for un registered email_id -->
                <div id="error_in_email_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_in_email_id" data-backdrop="static" data-keyboard="true"></br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Request Failed
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p> The Email Id provided is either INCORRECT or NOT REGISTERED with this Application. Kindly verify & provide valid Email Id.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" id="close" class=" btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                    </div>
                </div>
            </div>
            <?php $this->load->view('includes/js'); ?>
            <?php $this->load->view('includes/footer'); ?>
            <script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/login/multi_dept.js'); ?>"></script>