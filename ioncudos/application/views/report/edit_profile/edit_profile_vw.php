<?php
/**
* Description	:	Generate or edit map_level_weightage of Faculty

* Created		:	01/09/2015

* Author		:	Bhagyalaxmi Shivapuji

* Modification History:
*   Date                Modified By                         Description
* 
------------------------------------------------------------------------------------------ */
?> 

<!DOCTYPE html>
<html lang="en">
<!--head here -->
<?php $this->load->view('includes/head'); //var_dump($dept);?>
<style>
.ui-datepicker-calendar {
    display: none;
 }
 .alignright { text-align: right; }
 input::-moz-placeholder {
        text-align: left;
		  -webkit-transition: opacity 0.3s linear; color: gray;
    }
    input::-webkit-input-placeholder {
        text-align: left;
    }
.num_valid::-webkit-input-placeholder {
    color: red
}
.num_valid::-moz-placeholder {
    color: red
}
.circle:hover
{
        border-radius:50%;
}

</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/user_profile_style.css'); ?>" media="screen" />

<?php  $mode =  $mode_of_view[0]['faculty_display_mode_flag'];?>

<body data-spy="scroll" data-target=".bs-docs-sidebar">

<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 

<div class="container-fluid">
	<div class="row-fluid">
	<!--sidenav.php-->
	<?php //$this->load->view('includes/sidenav_3'); ?>
	<div class="span12">
	<!-- Contents -->
		<section id="contents">
				<div id="loading" class="ui-widget-overlay ui-front">
					<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
				</div>
			<div class="bs-docs-example fixed-height">
				<div class="row-fluid">
				<!--content goes here-->	
					<div class="navbar">
						<div class="navbar-inner-custom">
							Faculty Contribution<?php ?><br/>
						</div>
					</div>

					<div class="tabbable" id="tabs"> <!-- Only required for left/right tabs -->
						<ul class="nav nav-tabs">                                
					<!---		<li  class="active"><a href="#tab1" data-toggle="tab">  Faculty Profile</a></li>
							<li ><a href="#tab2" data-toggle="tab"> Teaching Workload</a></li>
							<li class=""><a href="#tab3" data-toggle="tab"> Research Publication </a></li>
							<!--<li><a href="#tab6" data-toggle="tab"> &nbsp;Consultancy / Testing Projects</a></li>						
							<!--<li><a href="#tab5" data-toggle="tab">Innovation</a></li>	-->
							<!--<li><a href="#tab7" data-toggle="tab">Sponsored Projects</a></li>	
							<li><a href="#tab8" data-toggle="tab">   Award & Honors </a></li>	
							<li><a href="#tab9" data-toggle="tab"> Patent & Innovation </a></li>
							<li><a href="#tab10" data-toggle="tab">Fellowship / Scholarship </a></li>	
							<li><a href="#tab11" data-toggle="tab">Paper Presentation </a></li>
							<li><a href="#tab12" data-toggle="tab">Book Published</a></li>	
							<li><a href="#tab13" data-toggle="tab">Conference / Seminar / Training / Workshop Organised</a></li>		
							<li><a href="#tab14" data-toggle="tab">Conference / Seminar / Training / Workshop Attented</a></li>-->
												
				
							<li  class="active"><a href="#tab1" data-toggle="tab"><img style="width:20px;" src="<?php echo base_url('twitterbootstrap/img/user.png');?>" alt="" />  Faculty Profile</a></li>
							<li ><a title='Teaching Workload' href="#tab2" data-toggle="tab"><img style="width:15px;"  src="<?php echo base_url('twitterbootstrap/img/workload.jpg');?>" alt="" />  Teaching Workload</a></li>
							<li class=""><a title = 'Research Publication' href="#tab3" data-toggle="tab"> <img style="width:20px;"  src="<?php echo base_url('twitterbootstrap/img/publication.png');?>" alt="" />  Research Publication </a></li>
							<!--<li><a href="#tab4" data-toggle="tab">Journal Publication</a></li>-->
							<li><a href="#tab6" title ='Consultancy / Testing Projects' data-toggle="tab"> <img style="width:25px;"  src="<?php echo base_url('twitterbootstrap/img/consult.png');?>" alt="" /> Consultancy / Testing Projects</a></li>						
							<!--<li><a href="#tab5" data-toggle="tab">Innovation</a></li>	-->
							<li><a  title="Sponsored Projects" href="#tab7" data-toggle="tab"> <img style="width:20px;"  src="<?php echo base_url('twitterbootstrap/img/sponsored.png');?>" alt="" />  Sponsored Projects</a></li>	
							<li><a title="Award & Honors" href="#tab8" data-toggle="tab">  <img style="width:15px;"  src="<?php echo base_url('twitterbootstrap/img/award.png');?>" alt="" />  Award & Honors </a></li>	
							<li><a title="Patent & Innovation" href="#tab9" data-toggle="tab">  <img style="width:20px;"  src="<?php echo base_url('twitterbootstrap/img/patent8.png');?>" alt="" />  Patent & Innovation </a></li>
							<li><a title="Book Published" href="#tab12" data-toggle="tab"> <img style="width:18px;"  src="<?php echo base_url('twitterbootstrap/img/book_publish.png');?>" alt="" /> &nbsp;Book Published</a></li>	
							<li><a title="Fellowship / Scholarship" href="#tab10" data-toggle="tab">  <img style="width:18px;"  src="<?php echo base_url('twitterbootstrap/img/fellow.png');?>" alt="" />&nbsp;Fellowship / Scholarship </a></li>	
							<!--<li><a href="#tab11" data-toggle="tab"> <img style="width:18px;"  src="<?php echo base_url('twitterbootstrap/img/pp5.png');?>" alt="" /> Paper Presentation </a></li>-->
							
							<li><a title='Conference / Seminar / Training / Workshop / Development Organized' href="#tab13" data-toggle="tab"> <img style="width:18px;"  src="<?php echo base_url('twitterbootstrap/img/conference3.png');?>" alt="" /> Conference / Seminar / Training / Development / Workshop  Organized</a></li>		
							<li><a title= 'Conference / Seminar / Training / Workshop / Development Attended' href="#tab14" data-toggle="tab"> <img style="width:18px;"  src="<?php echo base_url('twitterbootstrap/img/conference3.png');?>" alt="" /> Conference / Seminar / Training  / Development / Workshop Attended</a></li>				
							<li><a title="Research Projects" href="#tab15" data-toggle="tab"> <img style="width:17px;"  src="<?php echo base_url('twitterbootstrap/img/research.png');?>" alt="" /> Research Projects</a></li>
						</ul>
						<input type="hidden" id="upload_id" name="upload_id" value=""/>	
						<input type="hidden" id="per_table_id" name="per_table_id" value=""/>
						<input type="hidden" id="workload_switch"  name="workload_switch" value ="1"/>

			<!--<form id="add_form_id"> -->
		<div class="tab-content">
			<div class="tab-pane active check_tab" id="tab1" ><!-- Tab-1 Contents-->    
		
				<div class="span12">
					<div class="span6" style="padding-left:35px;">
						
					<b >Upload your profile photo:</b>	
						<div style="background-color:#D8D3D3;width:120px;height:115px;" class="circle">
							<div id="imgContainer" >	
							
								<form enctype="multipart/form-data" action="<?php echo base_url('report/edit_profile/image_upload_demo_submit'); ?> " method="POST" name="image_upload_form" id="image_upload_form">
								
									<div id="imgArea"  style="width:80px;height:80px;align:center;top:17px;left:20px;" class="">
										 <div class="box-hover"></div>
										<?php if($user_photo == ""){?>
											<div id="img_val" class="img_val">
											<img src="<?php echo base_url('twitterbootstrap/img/default.jpg');?>" id="upload_img" alt="" />
											</div>
											<?php }else{ ?>
											<div id="img_val_1" class="img_val">
												<img src="<?php echo base_url('uploads/user_profile_photo/medium/'.$user_photo);?>" id="upload_img" alt="" />
											</div>	
												<?php } ?>
																
											<div class="progressBar">
												<div class="bar"></div>
												<div class="percent">0%</div>
											</div>
											<div id="imgChange" class="cursor_pointer" style="font-size:10px;">
												<span style="color:#0088cc" class="cursor_pointer"  ><b>Change profile </b></span>					 
											   <input type="file" accept="image/*"  class="cursor_pointer"  name="image_upload_file" id="image_upload_file">
											   <input type="hidden" id="user_id_for_profile_pic" name="user_id_for_profile_pic" value="<?php echo $user_id;?>" />
											</div>									
									</div>
								</form>
							</div>	
					</div>
					</div>
					<div class="span6"></br></br>
							<div class="pull-right">
							<?php echo str_repeat("&nbsp;", 15); ?><b>Log History : </b><font color="red"><b> * </b></font><div class="btn-group" id="toggle_event_editing"><?php echo str_repeat("&nbsp;", 5); ?>
											<button type="button" id="locked_active" name="locked_active" class="btn btn-success locked_active" title="Show log history"  >Enable</button>
											<button type="button" id="unlocked_inactive" name="unlocked_inactive" class="btn btn-default unlocked_inactive " title="Prevent to show log history">Disable</button>
						</div>
					</div>
					<br/><br/>
					<div class="control-group pull-right" style="left:400px;">						
						<div class="controls">
							 <b>Reset Password :</b>
								 <input type="hidden" name="password_user_id" id="password_user_id" value="<?php echo $user_id; ?>"/>
								 <input placeholder="Enter Password"  type="password" id="reset_password" name="reset_password" style="margin-top:10px;" />	
								 <a class="btn btn-primary" name="reset_password_btn" id="reset_password_btn" ><i class="icon-ok icon-white"></i> Update</a>								
						</div>								
					</div>
					</div>
					
				</div>
		
		
		
				<div class="span12" style=" padding:0 0 0 20px;">
				

		<?php    $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'edit_my_profile');     
		echo form_open(current_url(), $attributes);?>
			
				<input type="hidden" id="log_history_ds" name="log_history_ds" value=" <?php echo $prevent_log_history; ?> "/>
			       
	    <div class="accordion-group">
			<a class="brand-custom cursor_pointer " data-toggle="collapse" data-target="#emp_details"style="text-decoration:none;">
			   <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
				<?php echo "Employee Details";?></b></h5>
			</a>
		</div>	
		<div id="emp_details" class="cursor_pointer accordion-group panel-collapse collapse accordion-body " style="padding:0px 0px 0px 0px;height:auto; overflow:auto;"><br/>
		<div>
		<!--<div style="left:85px;background-color:#e0e0d1;top:200px;border:1px solid #C4C0BE;width:150px;height:30px;z-index:2;border-radius: 20px;position: absolute;" class="cursor_pointer"><h4><b><font color="#800000" size="2px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Personal Details: </font></b></h4/></div>-->
			<div  id="demo"  style="z-index:1;position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#f5f3f3;-webkit-box-shadow: #C4C4C4 100px 100px 100px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
				<div style="padding:0px 0px 0px 50px;left: 50px;top: 0px;z-index: -1;";><h4><b><font color="#800000" size="2px"> 
				Personal Details:</font></b></h4/></div><br/>
					<section id="contents">
						<div class="row-fluid cursor_pointer"  >
							<div class="span12">
								<div class="span4">
									<div class="control-group">
										<label class="control-label" for="user_title"> Title: <font color='red'> * </font></label>
											<div class="controls">
												<select id="user_title" name="user_title" class="input-small required" align="center">
													<option value="">Select </option>
													<option value="Mr." <?php if($selected_title =='Mr.'){echo 'selected="selected"';}?>>Mr.</option>
													<option value="Mrs."<?php if($selected_title =='Mrs.'){echo 'selected="selected"';}?>>Mrs.</option>
													<option value="Ms." <?php if($selected_title =='Ms.'){echo 'selected="selected"';}?>>Ms.</option>
													<option value="Miss." <?php if($selected_title =='Miss.'){echo 'selected="selected"';}?>>Miss.</option>
													<option value="Prof." <?php if($selected_title =='Prof.'){echo 'selected="selected"';}?>>Prof.</option>
													<option value="Dr."  <?php if($selected_title =='Dr.'){echo 'selected="selected"';}?>>Dr.</option>
												</select>
											</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="email"> Email Id: <font color='red'> * </font></label>
											<div class="controls">
												<input placeholder="Enter Email-Id" type="text" id="email_id" name="email_id" value="<?php echo $email;?>" class="required" readonly/>									
											</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="user_website"> Website: <font color='red'></font></label>
											<div class="controls">
												<input type="text" placeholder="Enter Website" id="user_website" name="user_website" value="<?php echo $user_website;?>" class=""/>
											</div>
									</div>									
									<div class="control-group">
										<p class="control-label" for="Offerprogram">Present Address:<font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append date">
												<textarea placeholder="Enter Present Address" class="required" value="" id="present_adr" name="present_adr" ><?php echo $present_addr;?></textarea>
											</div>
										</div>
									</div>									
								</div>
								<div class="span4">								
									<div class="control-group">
										<label class="control-label" for="first_name"> First Name: <font color='red'> * </font></label>
											<div class="controls">
												<input placeholder="Enter First Name" type="text" id="first_name" name="first_name" value="<?php echo  htmlspecialchars($first_name) ;?>" class=" required fname"/>		
											</div>
									</div>

									<div class="control-group">
										<label class="control-label" for="email"> Alternative Email Id: <font color='red'>  </font></label>
											<div class="controls">
												<input placeholder="Enter Email-Id" type="text" id="email_id_alt" name="email_id_alt" value="<?php echo $alertnative_email;?>" class="" />									
											</div>
									</div>									
									<div class="control-group">
										<label class="control-label" for="country_code">Contact Number: <font color='red'></font></label>
											<div class="controls">
												<input id="mobile-number" style="width:220px;" type="tel" value="<?php echo $contact;?>" name="mobile-number" class=" phoneNumber ">
												<span id="valid-msg" class="hide" style="color:green"></span>
												<span id="error-msg" class="hide" style="color:red">Invalid number</span>								
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="Offerprogram">Permanent  Address:<font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append date">
												<textarea placeholder="Enter Permanent Address" class="required" value="" id="permanent_adr" name="permanent_adr" ><?php echo $permanent_addr;?></textarea/>
											</div>
										</div>
									</div>	
								</div>
								<div class="span4">	
									<div class="control-group">
										<label class="control-label" for="last_name"> Last Name: <font color='red'> * </font></label>
											<div class="controls">
												<input style="width:200px;" placeholder="Enter Last Name" type="text" id="last_name" name="last_name" value="<?php echo $last_name;?>" class=" required lname"/>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="Offerprogram">Date of Birth:<font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append date">
												
												
												<?php  if($dob != "0000-00-00"){?>
												<input placeholder="Select date" type="text"  style="width:190px;" class="text-right span12 datepicker" value="<?php echo $dob;?>" id="dob" name="dob"  readonly>
												<span class="add-on" id="dob_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php } else {?>
												<input placeholder="Select date" type="text"  style="width:190px;" class="text-right span12 datepicker"  id="dob" name="dob"  readonly>
												<span class="add-on" id="dob_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span> <?php } ?>
												
											</div>
										</div>
									</div>
							<div class="control-group">
								<label class="control-label" for="last_name"> Blood Group: <font color='red'></font></label>
										<div class="controls" style="width:200px;">
											<select name="Mem_BloodGr" id="Mem_BloodGr" >
												<option value="0" <?php if($blood_group =='0'){echo 'selected="selected"';}?>>Select Blood Group</option>
												<option value="A+" <?php if($blood_group =='A+'){echo 'selected="selected"';}?>>A+</option>
												<option value="A-" <?php if($blood_group =='A-'){echo 'selected="selected"';}?>>A-</option>
												<option value="B+" <?php if($blood_group =='B+'){echo 'selected="selected"';}?>>B+</option>
												<option value="B-" <?php if($blood_group =='B-'){echo 'selected="selected"';}?>>B-</option>
												<option value="O+" <?php if($blood_group =='O+'){echo 'selected="selected"';}?>>O+</option>
												<option value="O-" <?php if($blood_group =='O-'){echo 'selected="selected"';}?>>O-</option>
												<option value="AB+" <?php if($blood_group =='AB+'){echo 'selected="selected"';}?>>AB+</option>
												<option value="AB-" <?php if($blood_group =='AB-'){echo 'selected="selected"';}?>>AB-</option>
											</select>
										</div>
								</div>

								</div> 					
							</div>	
						 </div>	
					</section>
			</div>
		</div>
			<br/>
	
			<div style=" position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
				<div style="padding:0 0 0 50px";><h4><b><font color="#800000" size="2px">Professional  Details: </font></b></h4/></div>
					<section id="contents">
						<div class="">						
							<div class="row-fluid form-horizontal">
								<div class="span12" >
									<div class="span4">							
										<div class="control-group">
											<label class="control-label" for="emp_no"> Employee No: <font color='red'></font></label>
											<div class="controls">
												<input placeholder="Enter Employee No." type="text" id="emp_no" name="emp_no" value="<?php echo htmlspecialchars($emp_no);?>" class=""/>
											</div>
										</div>	
										<div class="control-group">
											<p class="control-label" for="Offerprogram">Date of Joining:<font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append date">
												<?php  if($yoj != "0000-00-00"){?>
													<input placeholder="Select date" type="text" style="width:193px;" class="text-right span12 datepicker required" id="dp2" name="dp2" value="<?php echo $yoj;?>"  readonly>
													<span class="add-on" id="btn1" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php } else {?>
													<input placeholder="Select date" type="text" style="width:193px;" class="text-right span12 datepicker required" id="dp2" name="dp2"   readonly>
													<span class="add-on" id="btn1" style="cursor:pointer;"><i class="icon-calendar"></i></span> <?php } ?>
													
												</div>
											</div>
											<div class="controls">
													<span id='error_placeholder_yoj'></span>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="industrial_experience"> Teaching Experience(In Years): <font color='red'>  </font></label>
											<div class="controls">	
												<input placeholder="Enter Teaching Experience" type = "text" id="teach_experiance" name="teach_experiance" value="<?php echo $teach_experiance;?>" class=" text-right allownumericwithoutdecimal"/>									
											</div>
										</div>	
										
												
										<div class="control-group">
											<label class="control-label" for=""> Faculty Serving: <font color='red'>*</font></label>									
											<div class="controls">
												<select name="faculty_serving" id="faculty_serving">		
													<option value="0">Select </option>
												<?php //selected_faculty_serving
												foreach ($faculty_serving as $list_faculty) {
												 ?><option value="<?php echo $list_faculty['mt_details_id']; ?>" <?php if( $list_faculty['mt_details_id'] ==$selected_faculty_serving){echo 'selected="selected"';} ?>><?php echo  $list_faculty['mt_details_name'];  ?></option><?php
												} ?>
												
												</select>
											</div>
										</div>
										<div class="control-group">
											<p class="control-label" for="Offerprogram">Remarks:<font color="red"><b></b></font></p>
											<div class="controls">
												<textarea class="" placeholder="Enter Remarks" value="" id="remark" name="remark" ><?php echo $remarks;?></textarea>
											</div>
										</div>
									</div>
									<div class="span4">		
										<div class="control-group">
											<label class="control-label" for="qualification"> Faculty Type: <font color='red'> * </font></label>
											<div class="controls">								
												<select style="width:215px;" id="faculty_type" name="faculty_type" class="required" align="center">
												<option value="0" >Select</option>
												<?php foreach($faculty_type as $row){?>
													<option value="<?php echo $row['mt_details_id'];?>" <?php if( $row['mt_details_id'] ==$selected_faculty_type){echo 'selected="selected"';} ?>><?php echo $row['mt_details_name']?></option><?php }?>									
												</select>
											</div>
										</div>
										<div class="control-group">
											<p class="control-label" for="Offerprogram">Relieving  Date:<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append date">
												<?php  if($resign_date != "0000-00-00"){?>
													<input placeholder="Select date" type="text" style="width:188px;" class="text-right span12 datepicker " id="resigning_date" name="resigning_date" value="<?php echo $resign_date;?>"  readonly>
													<span class="add-on" id="btn_resign" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php } else {?>
													<input placeholder="Select date" type="text" style="width:188px;" class="text-right span12 datepicker" id="resigning_date" name="resigning_date"   readonly>
													<span class="add-on" id="btn_resign" style="cursor:pointer;"><i class="icon-calendar"></i></span> <?php } ?>
													
												</div>
											</div>
											<div class="controls">
													<span id='error_placeholder_resign'></span>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="industrial_experience"> Industrial Experience(In Years): <font color='red'> </font></label>
											<div class="controls">	
												<input style="width:200px;"  placeholder="Enter Industrial Experience" type = "text" id="indus_experiance" name="indus_experiance" value="<?php echo $indurtrial_experiance; ?>" class="allownumericwithoutdecimal text-right"/>									
											</div>
										</div>	
										<div class="control-group">
											<p class="control-label" for="Offerprogram">Last Promotion:<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append date">
													<?php  echo $last_promotion ; if($last_promotion != "0000-00-00"){?>
													<input style="width:192px;" placeholder="Select date" type="text" class="text-right span12 datepicker " id="last_promotion" name="last_promotion"  value="<?php echo $last_promotion;?>" readonly>
													<span class="add-on" id="btn_last_promotion" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php } else {?>
													<input style="width:192px;"placeholder="Select date" type="text" class="text-right span12 datepicker " id="last_promotion" name="last_promotion"  readonly>
													<span class="add-on" id="btn_last_promotion" style="cursor:pointer;"><i class="icon-calendar"></i></span> <?php } ?>
													
												</div>
											</div>
										</div>
										
										<div class="control-group">
											<p class="control-label" for="responsibilities">Responsibilities:<font color="red"><b></b></font></p>
											<div class="controls">
												<textarea class="" placeholder="Enter Responsibilities"  value="" id="responsibilities" name="responsibilities" ><?php echo $responsibilities;?></textarea>
											</div>
										</div>
										
									</div>
									<div class="span4">	
										<div class="control-group">
											<label class="control-label" for="Designationdd"> Current Designation: <font color='red'>*</font></label>									
											<div class="controls">
												<select style="width:215px;" name="designation" id="designation">		
													<option value="0">Select </option>
												<?php
												foreach ($designation as $list_item_designation) {
												 ?><option value="<?php echo $list_item_designation['designation_id']; ?>" <?php if($list_item_designation['designation_id'] == $selected_designation){echo 'selected="selected"';}?>><?php echo  $list_item_designation['designation_name'];  ?></option><?php
												}?>
												
												</select>
											<br/>
												<a href="#manage_designation_list_modal" data-toggle="modal" data-target="#manage_designation_list_modal"> Manage Designation List </a>
											</div>
											
										</div>
										
										<div class="control-group">
										<p class="control-label" for="Offerprogram">Retirement  Date:<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append date">
													<?php  if($retirement_date != "0000-00-00"){?>
													<input placeholder="Select date" type="text" style="width:188px;" class="text-right span12 datepicker " id="retirement_date" name="retirement_date"  value="<?php echo $retirement_date;?>" readonly>
													<span class="add-on" id="btn_retirement" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php } else {?>
													<input placeholder="Select date"  type="text" style="width:188px;" class="text-right span12 datepicker " id="retirement_date" name="retirement_date"  readonly>
													<span class="add-on" id="btn_retirement" style="cursor:pointer;"><i class="icon-calendar"></i></span> <?php } ?>
													
												</div>
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="experience"> Total Experience: <font color='red'> * </font></label>
											<div class="controls">	
												<input placeholder="Total Experience"  style="width:160px;" readonly type = "text" id="experiance" name="experiance" value="<?php echo $experiance;?>" class="text-right required"/>	
												<?php if($admin_auth == "admin_chairman") {?>
												<input type = "checkbox" title="An employee on contract for a period of not less than two years and drawing consolidated salary not less than applicable gross salary shall only be counted as a regular employee." value="<?php echo $faculty_mode; ?>" name="faculty_mode" id="faculty_mode" <?php if($faculty_mode == 1) {?> checked <?php } ?>/>
												<?php } ?>
												
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="last_name"> Salary Pay(in <img style="width:10px;height:10px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): <font color='red'></font></label>
											<div class="controls">
												<input style="width:200px;" placeholder="Enter Salary Pay"  type="text" id="salary_pay" name="salary_pay" value="<?php echo $salary_pay;?>" class=" text-right allownumericwithdecimal"/>
											</div>
										</div>	
										<div class="control-group">
											<label class="control-label" for="professional_bodies"> Professional Bodies <font color='red'></font></label>
											<div class="controls">
												<textarea style="width:200px;" placeholder="Enter professional_bodies"  type="text" id="professional_bodies" name="professional_bodies" value="" class=""><?php echo $professional_bodies;?></textarea>
											</div>
										</div>	
										
									</div>
								</div>
							</div>	
						</div>												
					</section>
			</div><br/>
					<!--<div style="left:85px;background-color:#e0e0d1;top:422px;border:1px solid #C4C0BE;width:170px;height:30px;z-index:2;border-radius: 20px;position: absolute;" class="cursor_pointer"><h4><b><font color="#800000" size="2px">&nbsp;&nbsp;&nbsp;&nbsp;Qualification  Details: </font></b></h4/></div>-->
			<div style="  position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
			<div style="padding:0 0 0 50px";><h4><b><font color="#800000" size="2px"> Qualification  Details:  </font></b></h4/></div><br/>
					<section id="contents">
						<div class="row-fluid form-horizontal">
							<div class="span12" >
								<div class="span4">									
									<div class="control-group">
										<label class="control-label" for="qualification"> Highest Qualification: <font color='red'> * </font></label>
										<div class="controls">								
											<select id="heighest_qualification" name="heighest_qualification" class="required" align="center">
											<option value="0" >Select</option>
											<?php foreach($qualification as $row){?>
												<option value="<?php echo $row['mt_details_id'];?>" <?php if( $row['mt_details_id'] ==$selected_qualificaton_id){echo 'selected="selected"';} ?>><?php echo $row['mt_details_name']?></option><?php }?>
											</select>
										</div>
									</div>	
									<div class="control-group">
										<label class="control-label" for="user_specialization"> Specialization: <font color='red'>  </font></label>
										<div class="controls">								
											<textarea placeholder="Enter Specialization"  id="user_specialization" name="user_specialization" ><?php echo $user_specialization; ?></textarea>
										</div>
									</div>									
								</div>
								<div class="span4">
								
									<div class="control-group">
										<label class="control-label" for="research_interest"> Research Interest: <font color='red'>  </font></label>
										<div class="controls">								
											<textarea placeholder="Enter Research Interest"  id="research_interest" name="research_interest"><?php echo $research_interest;?></textarea>
										</div>
									</div>		
										
								</div>								
								<div class="span4">																
									<div class="control-group">
										<label class="control-label" for="qualification"> Skills: <font color='red'>  </font></label>
										<div class="controls">								
											<textarea  placeholder="Enter Skills"  id="skills" name="skills"><?php echo $skills;?></textarea>
										</div>
									</div>	

								</div>
							</div>
						</div>
					</section>
			</div>	<br/>		
			
			<div style="  position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
			<div style="padding:0 0 0 50px";><h4><b><font color="#800000" size="2px"> Ph.D  Details:  </font></b></h4/></div><br/>
					<section id="contents">
						<div class="row-fluid form-horizontal">
							<div class="span12" >
								<div class="span6">									

									<div class="control-group">
										<label class="control-label" for="phd_from"> Ph.D from: <font color='red'>  </font></label>
										<div class="controls">								
											<input  placeholder="Enter Ph.D from"  style="width:250px;" type="text" id="phd_from" name="phd_from" value="<?php echo htmlspecialchars($phd_from); ?>"/>
										</div>
									</div>	
									<div class="control-group">
										<label class="control-label" for="qualification"> Supervisor(s): <font color='red'>  </font></label>
										<div class="controls">								
											<input  placeholder="Enter Supervisor(s)"  style="width:250px;" type="text" id="superviser" name="superviser" value="<?php echo htmlspecialchars($superviser); ?>"/>
										</div>
									</div>		
									<div class="control-group">
										<label class="control-label" for="qualification"> URL: <font color='red'>  </font></label>
										<div class="controls">								
											<!-- <input  placeholder="Enter Supervisor(s)"  style="width:250px;" type="text" id="superviser" name="superviser" value="<?php echo htmlspecialchars($superviser); ?>"/> -->
											<input type="text" class = "url_valid" id="phd_url" name="phd_url" value ="<?php echo  htmlspecialchars($phd_url);?>" />
										</div>
									</div>	
									
								</div>
								<div class="span6">
									<div class="control-group">
										<label class="control-label" for="phd_status"> Ph.D Status: <font color='red'>   </font></label>
										<div class="controls">																			 
										<select name="phd_status" id="phd_status">	
												<option value="" >Select Type</option>
												<?php foreach ($status_phd as $st) {
													?><option value="<?php echo $st['mt_details_id']; ?>" <?php if( $st['mt_details_id'] ==$phd_status){echo 'selected="selected"';}?>>
												<?php echo  $st['mt_details_name_data'];  ?></option><?php
											}?>
				
											</select>
											<!--<input  style="width:250px;" type="text"  name="phd_status" id="phd_status" value= "<?php echo htmlspecialchars($phd_status); ?>" />-->
											
										<!--<select name="phd_status" id="phd_status" >
												<option value="" <?php if($phd_status ==''){echo 'selected="selected"';}?>>Select Status</option>
												<option value="57" <?php if($phd_status =='59'){echo 'selected="selected"';}?>>On Going</option>
												<option value="58" <?php if($phd_status =='60'){echo 'selected="selected"';}?>>Completed</option>
											</select>-->											
										</div>
									</div>
									<div class="control-group">
											<p class="control-label" for="Offerprogram">Ph.D during assessment year:<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append date">
													<?php  echo $phd_assessment_year ; if($phd_assessment_year != "0000"){?>
													<input placeholder="Select year"  type="text" class="text-right span12 datepicker " id="phd_assessment_year" name="phd_assessment_year"  value="<?php echo $phd_assessment_year;?>" readonly>
													<span class="add-on" id="btn_phd_assessment_year" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php } else {?>
													<input placeholder="Select year"  type="text" class="text-right span12 datepicker " id="phd_assessment_year" name="phd_assessment_year"  readonly>
													<span class="add-on" id="btn_phd_assessment_year" style="cursor:pointer;"><i class="icon-calendar"></i></span> <?php } ?>
													
												</div>
											</div>
										</div>									
								</div>								
							</div>
						<!--<div style="padding:0 0 0 50px";></div><br/>	=--->
							<div class="span12">	
								<div class="span2">	
									<div class="control-group pull-right">										
											<h4><b><font color="" size="2px"> Ph.D Guidance  Details:  </font></b></h4/>
									</div>	
								</div>
								<div class="span3">	
									<div class="control-group">
										<label class="control-label" for="guidance_within_org"> Candidate(s) within organization : <font color='red'>  </font></label>
										<div class="controls">								
											<input type="text" class="text-right input-mini allownumericwithoutdecimal " id="guidance_within_org" name="guidance_within_org" value="<?php echo $guidance_within_org;?>"/>
										</div>
									</div>	
								</div>								
								<div class="span4">	
									<div class="control-group">
										<label class="control-label" for="guidance_outside_org"> Candidate(s) outside organization : <font color='red'>  </font></label>
										<div class="controls">								
											<input type="text" class="text-right input-mini allownumericwithoutdecimal " id="guidance_outside_org" name="guidance_outside_org" value="<?php echo $guidance_outside_org;?>"/>
										</div>
									</div>	
								</div>
							</div>
						</div>
					</section>
			</div>
			
			<br/>
			<?php echo form_close(); ?>
				<div class="row-fluid">
					<div class="span12" style="width:1220px;">
						<div class="form-inline pull-right" >
							<button type="button" class="btn btn-primary" id="my_profile"><i class="icon-file icon-white"></i> Update </button>    
							<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>
						</div>
					</div>
				</div><br/>
		</div>

		<div class="accordion">
         <div class="accordion-group">
			<a class="brand-custom cursor_pointer" data-toggle="collapse" href="#collapse2" style="text-decoration:none;">
			   <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
				<?php echo "Qualification Details";?></b></h5>
			</a>
		</div>
		<div id="collapse2"  class="accordion-body">
			<section id="contents">
			<!-- bs-docs-example --->
				<div class="bs-docs-example">
					<div class="row-fluid">
						<table class="table table-bordered table-hover" id="my_qualification_tbl" style="font-size:12px;" >
							<thead>
								<tr>
									<th>Sl.No</th><th>Highest Qualification:</th><th>University</th>
									<th>Year of Graduation</th><th>Upload</th><th>Edit</th><th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>No Data Available</td><td></td><td></td><td></td><td></td><td></td><td></td>
								</tr>
							</tbody>
						</table><br/><br/>
						<input type="hidden" id="my_qua_id" name="my_qua_id"/>
					<form id="my_qualification" name="my_qualification" class = 'form-horizontal'>
					<div class="span12">
						<div class="span3">
							<div class="control-group">
							 <label>Degree: <font color='red'> * </font></label>
									<select id="degree" name="degree" class="required" align="center">
										<option value="0" >Select</option><?php foreach($qualification as $row){?>
										<option value="<?php echo $row['mt_details_id'];?>"><?php echo $row['mt_details_name']?></option>
												<?php }?>
									</select>
							
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
							 <label>Specialization: <font color='red'> * </font></label>
							 <input type="text" placeholder="Enter Specialization "  name="dept_id" id="dept_id" />
							</div>
						</div>
						<div class="span3">
							<div class="control-group">
								<div class="">	
									 <label>University:<font color='red'> * </font>  </label>
									 <input placeholder="Enter University"  type="text" id="my_university" value="<?php ?>" name="my_university" class="required input-large" style="width:200px;" rows=3/>
								</div>
							</div>	
						</div>
						<div class="span3">
							<div class="control-group">								
								
								<label>Year of Graduation:<font color="red"><b>*</b></font> </label>
									<div class="input-append date">
										<input placeholder="Select date"  type="text" class="text-right span12 datepicker" value="" id="start_date" name="start_date" readonly>
										<span class="add-on" id="start_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
									</div>
									
									<div class="controls">
										<span id='error_placeholder_start_date'></span>
									</div>
							</div>
					   </div>
					</div>
				</form>
					<div class="row-fluid">
						<div class="span12">
							<div class="form-inline pull-right ">
								<br/><br/>
								<button type="button" name="my_qualification_update" id="my_qualification_update" onclick="" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
								<button type="button" name="my_qualification_save" id="my_qualification_save" onclick="my_qualification_save" class="btn btn-primary"   ><i class="icon-file icon-white"></i><span></span> Save</button>
								<button type="reset_my_qualification" id="reset_my_qualification"  class="clear_all btn btn-info" onclick="reset_my_qualification()" ><i class="icon-refresh icon-white"></i>Reset</button>
								<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>								<br/><br/>																
							</div>
						</div>
					</div>
					</div>
				</div>
			</section>
		</div>
		</div>	
		
		
			</div>
		</div> <!-- Tab-1 Ends Here-->					

	<div class="tab-pane " id="tab2">  <!-- Tab-2 Starts Here-->	
		
		<div class="span12">
	
		<div class="menu">
		<div class="accordion">
         <div class="accordion-group">
		 <a class="brand-custom cursor_pointer " data-toggle="collapse" href="#collapse1" style="text-decoration:none;">
          <h5><b>&nbsp;<i class="icon-chevron-down icon-black"  data-toggle="collapse" > </i>
           <?php echo "Teaching Workload ";?></b></h5>
		  </a>
			</div>
			<!-- Contents  panel-collapse collapse   white-space: nowrap;-->
		<div id="collapse1" class=" accordion-body">
			<section id="contents">
				<div class="bs-docs-example"  style="height:auto; overflow:auto;">			
					<div class="row-fluid"><br>																	  
						<table class="table table-bordered table-hover " id="example2" style="font-size:12px;text-align:right" >
							<thead>
								<tr>
									<th style="width:50px;">Sl.No</th><th>Department</th><th>Program Type</th><th>Program</th><th style="width:70px;">Program Category</th><th>Work Type</th>
									<th  style="width:50px;">Workload Distribution(In Year)</th><th>Accademic Year</th><th>Workload</th><th>Edit</th><th>Delete</th>
									<!-- <th>Type of work</th>-->
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><!--<td></td>-->
								</tr>
							</tbody>
						</table><br><hr>	
					</div>
					<?php												
					$attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'my_teaching_pro');
					echo form_open(current_url(), $attributes);
					?>
				<div class="row-fluid" class=""><br>
				
					
				<div class="form_container FM tab2">
					<table id="work_load_table" cellspacing="0" cellpadding="0" class="table table-bordered table-hover" style="font-size:12px;" >
						<thead>
							<tr>
								<th>Department <font color='red'> * </font></th><th>Program Type <font color='red'> * </font></th>
								<th>Program <font color='red'> * </font></th>
								<th>Program Category <font color='red'> * </font></th><th>Workload Distribution<br/>(in years) <font color='red'> * </font></th>								
								<th>Academic Year <font color='red'> * </font></th><th>Type of work<font color='red'> * </font></th>
								<th>Workload(%)<font color='red'>*</font></th></tr>
					   </thead>
						<tbody>
							<tr>
								<td><select style="width:150px;"  id="dept" name="dept">
										<!--<option value="0">Select </option>-->
										<?php foreach($dept as $de){?>
										<!-- title="<?php echo $de['dept_description'];  ?>"  -->
										<option value="<?php echo $de['dept_id'];?>" ><?php echo $de['dept_name']?></option>
										<?php } ?>
									</select >
								</td><td>
								<div id="program_type">
									<select id="program_type_id" name="program_type_id" class="required ui"  >
										<!--<option value="0">Select </option>-->
										
									</select>
									</div>
								</td><td>
								<div id="program">
									<select id="program_id" name="program_id" class="required ui" >
										<!--<option value="0">Select </option>-->
										
									</select>
									</div>
								</td><td>
								<div id="progra_cat">
									<select id="pgm_category" name="pgm_category" class="required ui" style="width:150px;" >
										<!--<option value="0">Select </option>-->
										
									</select>
									</div>								
								</td>
							
								<td><div id="terms">
								<select id="year_sem" name="year_sem" class="input-mini">
								<!--<option value="0">Select </option><option>1</option>-->
								</select></div></td><td>
								<div id="">
										<div class="input-append date">											
											<input placeholder="Select date" type="text" style="width:100px;" class="text-right datepicker required" id="accademic" name="accademic" readonly>
											<span class="add-on" id="btn_accademic" style="cursor:pointer;"><i class="icon-calendar"></i></span><?php ?>
										</div>
										<span id='accademic_error'></span>
								</div>
								</td>
								<td>
									<!--<select id="type_of_work" name="type_of_work"  style="width:150px;">
										<option value="0">Select Type of work</option>
										<option value="1">Theory teaching</option>
										<option value="2">Laboratory work</option>
										<option value="3">Project Mentoring</option>
										<option value="4">Others (specify)</option>
									</select>-->
									<select id="type_of_work" name="type_of_work"  style="width:150px;">
										<option value="0">Select Type of work</option>
										<?php foreach($type_of_work as $type) {?>
												<option value="<?php echo $type['mt_details_id']; ?>"><?php echo $type['mt_details_name'];?></option>	
										<?php } ?>
									</select>
									<br/><br/>
									<input type="text" id="type_of_work_others" name="type_of_work_others" style="display:none;width:150px;"/>
								</td>
								<td>
									<input type="text" placeholder="workload"  id="work_load" name="work_load" max = "100" class="allownumericwithdecimal input-mini text-right"/>
								</td>
								</tr>
						</tbody>
					</table>		
					<input type="hidden" value="<?php echo sizeOf($program_workload);?>" id="wkl"/>
					<input type="hidden" id="my_wid" name="my_wid" value=""/>
					<input type="hidden" id="my_teaching_edit_val" name="my_teaching_edit_val" value="0"/>
					</div> 
			</div>
			<div class="pull-right">
			<br/><br/>
					<button type="button" class="btn btn-primary" style="margin-right: 3px; margin-left: 3px;" id="my_teaching_update" name="my_teaching_update"><i class="icon-file icon-white"></i> Update </button>	
					<button type="button" class="btn btn-primary" style="margin-right: 3px; margin-left: 3px;" id="my_teaching" name="my_teaching"><i class="icon-file icon-white"></i> Save </button>
					<button type="reset" id="reset_teaching"  class="clear_all btn btn-info" onclick="reset_my_teaching()" ><i class="icon-refresh icon-white"></i>Reset</button>
					
					<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger "><i class="icon-remove-sign icon-white"></i><span></span> Close </span></a> <?php } ?>	
			</div><br/><br/>
			<?php echo form_close(); ?>
					<!--Do not place contents below this line-->	
					<br/><br/>
				</div>
			</section>
			</div>
		</div>
		</div>
		</div>																		
	</div>   <!-- Tab-2 Ends Here-->
	
	
	   
	<div class="tab-pane  hide fade in" id="tab3">   <!-- Tab-3 Starts Here-->                          		
	<div class="span12">
		<!-- Contents -->
		<section id="contents">
			<div class="bs-docs-example" >
			<div class="navbar" id="fc"  style="display:none;">
				<div class="navbar-inner-custom" >Faculty Contribution<?php ?><br/></div>
			</div>
			<div class="control-group">			
					<div class="controls">
						<b>Type : </b><select id ="type_publication_filter" name="type_publication_filter" > <?php foreach($research_type as $rt) {?>
								<option value="<?php echo $rt['mt_details_id'];?>"> <?php echo $rt['mt_details_name'];?></option>
								<?php } ?>
						</select>
					</div>
				</div>
			<div class="row-fluid" id="tab3_h"  style="height:auto; overflow:auto;"><br>	
				<table class="table table-bordered table-hover display nowrap" id="example3" cellspacing="0" width="100%"aria-describedby="example_info" style="font-size:12px;">
					<thead>
						<tr>
						<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tbody>
				</table>
			</div>
	
			<div  class="row-fluid tab3" >
			<div>
				<?php												
			$attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'research_publication', 'enctype'=>'multipart/form-data');
			echo form_open(current_url(), $attributes);
					?>
				<div class="span12 accordion-group"><br/>
				<div class="span4">				
				<div class="control-group">
					<p class="control-label" for="Offerprogram"> Title of Paper : <font color="red"><b>*</b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Project Title"  type="text" id="title_res_research" name="title_res_research" class="required"/>
							</div>
						</div>
				 </div>				
				 <div class="control-group">
					<p class="control-label" for="Offerprogram"> Level / Status  : <font color="red"><b>*</b></font></p>
						<div class="controls">
							<div class="input-append ">
								<select id="publisher_status" name="publisher_status"> <?php foreach($level_of_presentation as $rt) {?>
									<option value="<?php echo $rt['mt_details_id'];?>"> <?php echo $rt['mt_details_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
				 </div>
			    <div class="control-group">
					<p class="control-label" for="author">Co - Author(s) : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
								<textarea placeholder="Enter Co- Author(s)"  id="author_research" name="author_research" class=""></textarea>
							</div>
						</div>
				 </div>				    
				<div class="control-group">
					<p class="control-label" for="publisher_research">Publisher : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">										
								<textarea placeholder="Enter Publisher's detail"  id="publisher_research" name="publisher_research" class=""></textarea>
							</div>
						</div>
				</div>
			<!--	<div class="control-group">
					<p class="control-label" for=""> Amount (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
								<input placeholder="Enter Amount"  type="text" id="amount_res_research" name="amount_res_research" class="allownumericwithdecimal text-right" value="0.0000"/>										
							</div>
						</div>
				 </div>	-->			 

				<?php if($mode == 1){ ?>	
				<div class="control-group">
					<p class="control-label" for="res_pages_research">Page(s) : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter page(s)" type="text" id="pages_research" name="pages_research" class="text-right"/>
							</div>
						</div>
				</div>
				<?php } ?>
				<!--<div class="control-group">
					<p class="control-label" for="Offerprogram">Publication Date:<font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input type="text" placeholder="Select date" class="text-right datepicker " id="dp4_research" name="dp4_research"  style="width:180px;" readonly>
								<span class="add-on" id="research_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>

						</div>					
					</div>
				</div>				
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Current version Date:<font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input type="text" placeholder="Select date"  class="text-right span12 datepicker " id="dp4_end_research" name="dp4_end_research"  style="width:195px;"readonly>
							<span class="add-on required" id="dp4_end_research_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>		
						</div>	<br/>						
							<span style="display: inline-block; " id='eror_research'></span><span style="display: inline-block; " id='end_error_placeholder_dp4'></span>
					</div>
				</div>	-->
				<div class="control-group">
					<p class="control-label" for="citation_research">Citation  Index<font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Citation Index"  type="text" id="citation_research" name="citation_research" class=" text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				 </div>					 
				<?php if($mode == 1){ ?>
				<div class="control-group">
					<p class="control-label" for="hindex_research">h-Index<font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter h-Index"  type="text" id="hindex_research" name="hindex_research" class="text-right  allownumericwithoutdecimal "/>
							</div>
						</div>
				</div>
				<?php } ?>
				<?php if($mode == 1){ ?>
				<div class="control-group">
					<p class="control-label" for="i10_index_research">i10-Index<font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter i10-Index" type="text" id="i10_index_research" name="i10_index_research" class="text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				 </div>
				<?php } ?>	
				<div class="control-group">					
						<div class="">
						<b>Any awards it has won :</b>
							<div class="input-append ">
								&nbsp;&nbsp;<input type ="checkbox"  id="publication_award_won" name="publication_award_won"/>								
							</div>
						</div>
				 </div>
				 <div class="control-group publication_won" >					
						<div class="controls">
						<p class="control-label" for="i10_index_research"> Specify : <font color="red"><b>*</b></font></p>
							<div class="input-append ">							
								<input type ="text" id="publication_award_won_text" name="publication_award_won_text"/>
							</div>
						</div>
				 </div>
					
				</div>
				<div class="span4">
				<div class="control-group">
					<p class="control-label" title="Digital Object Identifier" for="doi_research"> Type : <font color="red"><b>*</b></font></p>
					<div class="controls">
						<select id ="type_publication" name="type_publication" > <?php foreach($research_type as $rt) {?>
								<option value="<?php echo $rt['mt_details_id'];?>"> <?php echo $rt['mt_details_name'];?></option>
								<?php } ?>
						</select>
					</div>
				</div>				
				<div class="control-group">
					<p class="control-label" title="Digital Object Identifier" for="">Publication Title: <font color="red"><b>*</b></font></p>
					<div class="controls">
						<div class="input-append ">
						<input type="text" id="publication_title" name ="publication_title" class="required" />
						</div>
					</div>
				</div>
				<div>
					
				</div>
				<?php if($mode == 1){ ?>				   
				<div class="control-group">
					<p class="control-label" title="Digital Object Identifier" for="doi_research">DOI : </p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Digital Object Identifier" type="text" id="doi_research" name="doi_research" class=""/>
							</div>
						</div>
				</div>
				<?php } ?>
				
				<div class="control-group">
					<p class="control-label" title="Internation Standard Serial Number" for="issn_research">ISSN / ISBN : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input  placeholder="International Standard Serial Number" type="text" id="issn_research" name="issn_research" class=""/>
							</div>
						</div>
				</div>
				<?php if($mode == 1){ ?>				
				<div class="control-group">
					<p class="control-label" for="publish_online_research">Published URL : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Published URL " type="text" id="publish_online_research" name="publish_online_research" class=""/>
							</div>
						</div>
				</div>
				<?php } ?>
				<?php if($mode == 1){ ?>
				<div class="control-group">
					<p class="control-label" for="index_terms_research">Index Term(s)  : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
								<textarea  placeholder="Enter Index Term(s) " id="index_terms_research" name="index_terms_research" class=""></textarea>
							</div>
						</div>
				</div>
				<?php } ?>
				<div class="control-group">
					<p class="control-label" for="status_research">Status : <font color="red"><b>*</b></font></p>
						<div class="controls">
							<div class="input-append ">
								<select name="status_research" id="status_research">		
									<?php foreach ($status as $st) {
										?><option value="<?php echo $st['mt_details_id']; ?>">
									<?php echo  $st['mt_details_name'];  ?></option><?php
									}?>
				
								</select>
							</div>
						</div>
				</div>	
				<!--<div class="control-group">
					<p class="control-label" for=""> Sponsored by : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">									
									<textarea placeholder="Enter Sponsored by " id="contribution_res_guid_research" name="contribution_res_guid_research" class=""></textarea>
							</div>
						</div>
				</div>
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Issue Date : <font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input type="text" placeholder="Select date " class="text-right span12 datepicker " id="issue_date_research" name="issue_date_research"  style="width:200px;"readonly>
							<span class="add-on required" id="issue_date_btn_research" style="cursor:pointer;"><i class="icon-calendar"></i></span>		
						</div>	<br/>						
							<span style="display: inline-block; " id='eror_issue_date_research'></span><span style="display: inline-block; " id='end_error_placeholder_dp4'></span>
					</div>
				</div>
				<div class="control-group">
					<p class="control-label" for="issue_no_research">Issue No. : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input type="text" placeholder="Enter Issue No. " id="issue_no_research" name="issue_no_research" class=" text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				</div> -->

				<div class="control-group">
					<p class="control-label" for="vol_no_research">Volume No. : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input type="text" placeholder="Enter Volume No." id="vol_no_research" name="vol_no_research" class=" text-right allownumericwithoutdecimal"/>
							</div>
						</div>
				</div>				
 
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Publication Date:<font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input type="text" placeholder="Select date" class="text-right datepicker " id="dp4_research" name="dp4_research"  style="width:180px;" readonly>
								<span class="add-on" id="research_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>

						</div>					
					</div>
				</div>

				</div>
				<div  class="span4" style="height:500px;">
				Abstract : 
						<textarea class="question_textarea" name="abstract_data_research" id="abstract_data_research" style="margin: 10px; width: 150px; height: 10px;" ></textarea>		
				</div>
			</div>
			
 				
				<input type="hidden" id="research_id" name="research_id"/>
				<input type="hidden" id="user_id_research" name="user_id_research" value="<?php echo $user_id;?>"/>
				<?php echo form_close(); ?>	
				
				<div class="form-inline pull-right ">
					<br/><br/><button type="button" name="my_research_peper_update" id="my_research_peper_update" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
					<button type="button" name="my_research_pape_save" id="my_research_pape_save" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
					<button type="reset" id="reset"  class="clear_all btn btn-info" onclick="reset_reaearch_papers()" ><i class="icon-refresh icon-white"></i>Reset</button>
					<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
					<br/><br/>
				</div>	
					</div>
			</div>	
					
			</div>
		</section>
	</div>
</div> <!-- Tab-3 Ends Here-->
<!-- Tab - 6 starts here -->

	<div class="tab-pane" id="tab6">
		<div class="span12">				
				<section id="contents">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="tab6_h">										  
							<table class="table table-bordered table-hover" id="example6" style="width:100%">
								<thead>
									<tr>
										<th style="width:50px;"> Sl.No </th> 
										<th style="width:150px;"> Project Code </th>
										<th style="width:200px;"> Project Title </th> 
										<th  style="width:250px;"> Client </th> 
										<th  style="width:50px;"> Role </th>
										<th style="width:50px;">Year</th>
										<th style="width:50px;">status</th>
										<th style="width:50px;">Upload</th>
										<th style="width:50px;">Edit</th>
										<th style="width:50px;">Delete</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</section>
				
				<section id="contents" class="tab6">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="">	<br/>									  
							<?php $attributes = array('class' => 'form-horizontal_new ', 'method' => 'POST', 'id' => 'consultancy_projects', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
								<div class="span4">				
									<div class="control-group">
										<p class="control-label" for="project_code"> Project Code : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input placeholder="Enter Project Code " type="text" id="project_code" name="project_code" class="required"/>
												</div>
											</div>
									</div>									
									<div class="control-group">
										<p class="control-label" for="project_title"> Project Title : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea  placeholder="Enter Project Title " type="text" id="project_title" name="project_title" class="required"></textarea>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="client"> Client : </p>
											<div class="controls">
												<div class="input-append ">
													<textarea placeholder="Enter Client's name "type="text" id="client" name="client" class=""></textarea>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="amount">Revenue earned(in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): </p>
											<div class="controls">
												<div class="input-append ">
													<input placeholder="Enter Amount" type="text" id="amount_consult" name="amount_consult" class="text-right allownumericwithdecimal"/>
												</div>
											</div>
									</div>									
									
								</div>
								<div class="span4"> 

									<div class="control-group">
										<p class="control-label" for="year">Commencement Date: <font color="red"><b>*</b></font></p>		
										<div class="input-append date">
											<input placeholder="Select year" readonly  type="text" id="year_consult" name="year_consult" class="text-right" style="width:180px;"/>
											<span class="add-on" id="year_consult_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<span id='error_year_consult_btn'></span>
										</div>										
									</div><br/>									
									<div class="control-group">
										<p class="control-label" for="status">Status : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<select name="consult_status" id="consult_status">		
															<!--<option value="0">Select </option>-->
														<?php foreach ($status as $st) {
															?><option value="<?php echo $st['mt_details_id']; ?>" <?php //if($st['mt_detaild_id'] == $state_selected){echo 'selected="selected"';}?>>
														<?php echo  $st['mt_details_name'];  ?></option><?php
														}?>
									
													</select>
												</div>
											</div>
									</div>
									
									<div class="control-group">
										<p class="control-label" for="consultant"> Your Role : </p>
											<div class="controls">
												<div class="input-append ">
													<!--<input placeholder="Enter consultant's name " type="text" id="consultant" name="consultant" class=""/>-->
													<select id="consult_role" name="consult_role" class="required"><?php foreach($consultant_role as $consult_role){ ?>
														<option value="<?php echo $consult_role['mt_details_id'];?>"> <?php echo $consult_role['mt_details_name']; ?> </option>
													<?php } ?>
													</select>														
												</div>
											</div>
									</div>
									<div class="control-group" id="consultant_role">
										<p class="control-label" for="Specify">Specify<font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input placeholder="Specify User Role " type="text" id="consultant" name="consultant"/>
											</div>
										</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="co_consultant">Co-consultant(s): <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea placeholder="Enter consultant's name " type="text" id="co_consultant" name="co_consultant"></textarea>
												</div>
											</div>
									</div>									
								</div>
								<div class="span4"> 																	
									<div class="">
									<b>Abstract</b>
									<textarea placeholder="Enter Abstract " class="question_textarea" name="abstract_consult" id="abstract_consult" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
									</div>	
								</div>
						<input type="hidden" value="<?php echo $user_id;?>" id="consult_user_id" name="consult_user_id"/>
						<input type="hidden" value="" id="c_id" name="c_id"/>

					<?php echo form_close(); ?>	
					<div class="form-inline pull-right ">
					<br/><br/><button value="1" type="button" name="update_consult_project" id="update_consult_project" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
					<button type="button" name="save_consult_project" id="save_consult_project" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
					<button type="reset" id="reset_consult_project"  class="clear_all btn btn-info" onclick="reset_consult_project()" ><i class="icon-refresh icon-white"></i>Reset</button>
					<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
					<br/><br/>
				</div>							
				
						</div>
					</div>
				</section>
		</div>
	 </div>
<!--- Tab - 6 ends here --->
<!-- Tab - 7 starts here -->

	<div class="tab-pane" id="tab7">
		<div class="span12">

				<section id="contents">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="tab6_h">										  
							<table class="table table-bordered table-hover" id="example7" style="width:100px">
								<thead>
									<tr>
										<th> Sl.No </th><!--<th> Project Code </th> --><th> Project Title </th> <th> Principal Investigator </th> <th> Co - Principal Investigator </th> <th> Sponsored Organization</th><th>Year</th><th>status</th><th>Upload</th><th>Edit</th><th>Delete</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><!--<td></td>-->
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</section>
								
				<section id="contents" class="tab7">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="">	<br/>									  
							<?php $attributes = array('class' => 'form-horizontal_new ', 'method' => 'POST', 'id' => 'sponsored_projects', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
								<div class="span4">				
								<!-- 	<div class="control-group">
										<p class="control-label" for="spo_project_code"> Project Code : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Project Code" type="text" value='' id="spo_project_code" name="spo_project_code" class="required"/>
												</div>
											</div>
									</div>	 -->					
									<div class="control-group">
										<p class="control-label" for="spo_project_title"> Project Title : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Project Title" type="text" id="spo_project_title" name="spo_project_title" class="required"/>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="spo_year">Date of sanction:<font color="red"><b>*</b></font></p>		
										<div class="input-append date">
											<input readonly   placeholder="Select year" type="text" id="spo_year" name="spo_year" class="text-right required" style="width:180px;"/>
											<span class="add-on" id="spo_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<span id='error_spo_year_btn'></span>
										</div>										
									</div>
									
									<div class="control-group">
										<p class="control-label" for="spo_investigator">Principal Investigator :<font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input  type="text" placeholder="Enter Principal Investigator" id="spo_investigator" name="spo_investigator" class="required"/>
											</div>
										</div>
									</div><br/>
									<div class="control-group">	
										<p class="control-label" for=""> Collaborating Organization :</p>
										<div class="controls">
											<div class="input-append ">
												<textarea  rows="3" placeholder="Enter Collaborating Organization" id="collaborating_organization" name="collaborating_organization" class=""></textarea>
											</div>
										</div>
									</div>	

								</div>
								
								<div class="span4"> 
									<div class="control-group">
										<p class="control-label" for="spo_amount">Amount (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Amount" type="text" id="spo_amount" name="spo_amount" class="text-right allownumericwithdecimal"/>
												</div>
											</div>
									</div>	
									<div class="control-group">
										<p class="control-label" for="spo_status">Status : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<select name="spo_status" id="spo_status">		
															<!--<option value="0">Select </option>-->
														<?php foreach ($status as $st) {
															?><option value="<?php echo $st['mt_details_id']; ?>">
														<?php echo  $st['mt_details_name'];  ?></option><?php
														}?>
									
													</select>
												</div>
											</div>
									</div>	
									<div class="control-group">
										<p class="control-label" for="spo_duration">Duration: <font color="red"><b>*</b></font></p>		
										<div class="input-append date">
											<input  placeholder="Enter duration in month(s)" type="text" id="spo_duration" name="spo_duration" class="text-right required allownumericwithoutdecimal" style="width:205px;"/>
											
												<span id='error_spo_year_btn'></span>
										</div>										
									</div>									
									<div class="control-group">
									<p class="control-label" for="co_spo_investigator">Co-Investigator:</p>
										<div class="controls">
											<div class="input-append ">													
												<textarea placeholder="Enter Co-Principal Investigator(s)" id="co_spo_investigator" name="co_spo_investigator" class=""></textarea>
											</div>
										</div>
									</div>																		
									<div class="control-group">
										<p class="control-label" for="spo_oganization"> Sponsoring Organization : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea   placeholder="Enter Sponsoring Organization" id="spo_oganization" name="spo_oganization" class=""></textarea>
												</div>
											</div>
									</div>									
								</div>
								<div class="span4"> 																	
									<div class="">
											<b>Description of the project :</b>
											<textarea placeholder="Enter Description of the project " class="question_textarea" name="abstract_spo" id="abstract_spo" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
									</div>	
								</div>
									<input type="hidden" value="<?php echo $user_id;?>" id="spo_user_id" name="spo_user_id"/>
									<input type="hidden" value="" id="s_id" name="s_id"/>

							<?php echo form_close(); ?>	
							<div class="form-inline pull-right ">
								<br/><br/><button value="1" type="button" name="update_spo_project" id="update_spo_project" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
								<button type="button" name="save_spo_project" id="save_spo_project" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
								<button type="reset" id="reset_spo_project"  class="clear_all btn btn-info" onclick="reset_spo_project()" ><i class="icon-refresh icon-white"></i>Reset</button>
								<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
								<br/><br/>
							</div>							
				
						</div>
					</div>
				</section>
		</div>
	 </div>
<!--- Tab - 8 ends here --->
	<div class="tab-pane" id="tab8">
		<div class="span12">					
				<section id="contents">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="tab8_h">										  
							<table class="table table-bordered table-hover" id="example8" style="width:100%">
								<thead>
									<tr>
										<th style="width:50px;"> Sl.No </th> <th style="width:250px;"> Award Name </th><th>Award for</th> <th style="width:150px;"> Sponsored Organization</th><th style="width:100px;"> Awarded Year </th><th style="width:100px;"  >Remarks</th><th style="width:70px;" >Upload</th><th style="width:30px;">Edit</th><th style="width:50px;">Delete</th>
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
								
				<section id="contents" class="tab8">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="">	<br/>									  
							<?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'award_honours', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
								<div class="span6">				
									<div class="control-group">
										<p class="control-label" for="award_name"> Awarded Name : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Awarded Name" type="text" id="award_name" name="award_name" class="required"/>
												</div>
											</div>
									</div>									
									<div class="control-group">
										<p class="control-label" for="award_org"> Awarded Organization : </p>
											<div class="controls">
												<div class="input-append ">
													<textarea  placeholder="Enter Awarded Organization" id="award_org" name="award_org" class=""></textarea>
												</div>
											</div>
									</div>									
									<div class="control-group">
										<p class="control-label" for="award_for">Awarded for :<font color="red"><b> * </b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea  placeholder="Enter Awarded for"  id="award_for" name="award_for" class="required"></textarea>
												</div>
											</div>
									</div>
									<!--<div class="control-group">
										<p class="control-label" for="cash_award"> Cash Awarded(in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ):<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Cash Award" type="text" id="cash_award" name="cash_award" class=" text-right allownumericwithdecimal"/>
												</div>
											</div>
									</div>	-->
									
															
									
								</div>
								<div class="span6"> 
								<div class="control-group">
										<p class="control-label" for="awarded_year">Awarded Year : <font color="red"><b>*</b></font></p>
										<div class="controls">
										<div class="input-append date">
											<input readonly   placeholder="Select year" type="text" id="awarded_year" name="awarded_year" class="text-right required" style="width:180px;"/>
											<span class="add-on" id="awarded_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<span id='error_awarded_year_btn'></span>
										</div>					
										</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="award_venue"> Venue : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea   placeholder="Enter Venue" id="award_venue" name="award_venue" class=""></textarea>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="award_remarks">Any other detail about award : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													
													<textarea placeholder="Enter other details" rows="4" cols="6" id="award_remarks" name="award_remarks" class=""></textarea>
												</div>
											</div>
									</div>	
								</div>
									<input type="hidden" value="<?php echo $user_id;?>" id="award_user_id" name="award_user_id"/>
									<input type="hidden" value="" id="award_id" name="award_id"/>

							<?php echo form_close(); ?>	
							<div class="form-inline pull-right ">
								<br/><br/><button value="1" type="button" name="update_award_honour" id="update_award_honour" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
								<button type="button" name="save_award_honour" id="save_award_honour" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
								<button type="reset" id="reset_award_honour"  class="clear_all btn btn-info" onclick="reset_award_honour()" ><i class="icon-refresh icon-white"></i>Reset</button>
								<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
								<br/><br/>
							</div>							
				
						</div>
					</div>
				</section>
		</div>
	</div>
<!--- Tab-8 Ends  Here --->
<!--- Tab - 9 ends here --->
	<div class="tab-pane" id="tab9">
		<div class="span12">
			<section id="contents">
				<div class="bs-docs-example"  style="height:auto; overflow:auto;">
					<div class="row-fluid" id="tab9_h">										  
						<table class="table table-bordered table-hover" id="example9" style="width:100%">
							<thead>
								<tr>
									<th> Sl.No </th> <th> Title </th><th> Inventor(s) </th> <th> Patent No. </th> <th> Year </th><th> Status</th><th>Upload</th><th>Edit</th><th>Delete</th>
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
			<section id="contents" class="tab9">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="">	<br/>									  
							<?php $attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'patent', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
								<div class="span4">				
									<div class="control-group">
										<p class="control-label" for="patent_title"> Title : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input placeholder="Enter Patent title"  type="text" id="patent_title" name="patent_title" class="required"/>
												</div>
											</div>
									</div>																							
									<div class="control-group">
										<p class="control-label" for="patent_no">Patent No. : </p>
											<div class="controls">
												<div class="input-append ">
													<input type="text" id="patent_no" name="patent_no" placeholder="Enter Patent no. " class=""/>
												</div>
											</div>
									</div>	

								<!--	<div class="control-group">
										<p class="control-label" for="inventors"> Inventor(s) : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													
													<textarea   placeholder="Enter inventor's name" id="inventors" name="inventors" class=""></textarea>
												</div>
											</div>
									</div>	-->											
								</div>
								
							<div class="span4"> 
								<div class="control-group">
										<p class="control-label" for="paper"> Year : <font color="red"><b>*</b></font></p>
										<div class="controls">
										<div class="input-append date">
											<input readonly placeholder="Select year "  type="text" id="patent_year" name="patent_year" class="text-right required" style="width:180px;"/>
											<span class="add-on" id="patent_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<span id='error_patent_year_btn'></span>
										</div>					
										</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="Offerprogram"> Status : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">																
												<select name="patent_status" id="patent_status">		
													<?php
													foreach ($status as $st) {
													 ?><option value="<?php echo $st['mt_details_id']; ?>">
													 <?php echo  $st['mt_details_name'];  ?></option><?php
													}?>
													
													</select>
											</div>
										</div>
									</div>
									<!--<div class="control-group">
										<p class="control-label" for="Offerprogram">Link : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
															<input placeholder="Enter Link " type="text" id="innovation_link" name="innovation_link" class=""/>
												</div>
											</div>
									 </div>	-->
								</div>
									<div class="span4"> 																	
									<div class="">	
											<b>Abstract:</b>
											<textarea placeholder="Enter Abstract " class="question_textarea" name="patent_abstract" id="patent_abstract" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
									</div>	
								</div>
									<input type="hidden" value="<?php echo $user_id;?>" id="patent_user_id" name="patent_user_id"/>
									<input type="hidden" value="" id="patent_id" name="patent_id"/>

							<?php echo form_close(); ?>	
							<div class="form-inline pull-right ">
								<br/><br/><button value="1" type="button" name="update_patent" id="update_patent" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
								<button type="button" name="save_patent" id="save_patent" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
								<button type="reset" id="reset_patent"  class="clear_all btn btn-info" onclick="reset_patent()" ><i class="icon-refresh icon-white"></i>Reset</button>
								<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
								<br/><br/>
							</div>							
				
						</div>
					</div>
				</section>

			
		</div>
	</div>
	<!-- Tab-9 Ends Here-->  
	
	 <!-- Tab-10 Starts Here--> 
	<div class="tab-pane" id="tab10">
		<div class="span12">

			<section id="contents">
				<div class="bs-docs-example"  style="height:auto; overflow:auto;">
					<div class="row-fluid" id="tab10_h">										  
						<table class="table table-bordered table-hover" id="example10" style="width:100%">
							<thead>
								<tr>
								<!-- <th> Abstract </th>-->
									<th> Sl.No </th> <th> Fellowship / Scholarship </th><th> Awarded by </th> <th> Year</th><th> Type </th><th>Upload</th><th>Edit</th><th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</section>
			<section id="contents" class="tab10">
				<div class="bs-docs-example"  style="height:auto; overflow:auto;">
					<div class="row-fluid" id="">	<br/>									  
						<?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'sholarship', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
							<div class="span6">				
								<div class="control-group">
									<p class="control-label" for="sholarship_for">Fellowship / Scholarship for:<font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input placeholder="Enter Fellowship / Scholarship for " type="text" id="sholarship_for" name="sholarship_for" class="required"/>
											</div>
										</div>
								</div>									
								<div class="control-group">
									<p class="control-label" for="awarded_by"> Awarded by : </p>
										<div class="controls">
											<div class="input-append ">
												<textarea placeholder="Enter Awarded by" id="awarded_by" name="awarded_by" class="" ></textarea>
											</div>
										</div>
								</div>													
								<div class="control-group">
									<p class="control-label" for="scholar_year"> Start - End Year : <font color="red"><b>*</b></font></p>
									<div class="controls">
									<div class="input-append date">
									
									<input readonly placeholder="Select date" type="text" id="scholar_year" name="scholar_year" class="text-right required" style="width:85px;" value=""/>
									<span class="add-on" id="scholar_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
									<input  placeholder="Select Date"  type="text" class="text-right span12 datepicker" id="scholar_end_year" name="scholar_end_year"  style="width:85px;" readonly />
									<span class="add-on required" id="scholar_end_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
									<span id='error_scholar_year_btn'></span>
									</div>	

									
									</div>
								</div>
								<div class="control-group">
								
									<p class="control-label" for="cash_award"> Amount (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ):<font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input type="text" placeholder="Enter Amount" id="fellow_amount" name="fellow_amount" class="text-right allownumericwithdecimal"/ >
											</div>
										</div>
								</div>								
								<div class="control-group">
									<p class="control-label" for="scholar_type"> Type : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="scholar_type" id="scholar_type">		
												<?php
												foreach ($faculty_fellow_type as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
								</div>
								
							</div>
								<div class="span6"> 																	
								<div class="">	
										<b>Abstract :</b>
										<textarea placeholder="Enter Abstract " class="question_textarea" name="scholar_abstract" id="scholar_abstract" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
								</div>	
							</div>
								<input type="hidden" value="<?php echo $user_id;?>" id="scholar_user_id" name="scholar_user_id"/>
								<input type="hidden" value="" id="scholar_id" name="scholar_id"/>

						<?php echo form_close(); ?>	
						<div class="form-inline pull-right ">
							<br/><br/><button value="1" type="button" name="update_scholar" id="update_scholar" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
							<button type="button" name="save_scholar" id="save_scholar" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
							<button type="reset" id="reset_scholar"  class="clear_all btn btn-info" onclick="reset_scholar()" ><i class="icon-refresh icon-white"></i>Reset</button>
							<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
							<br/><br/>
						</div>							
			
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- Tab-10 Ends Here-->  	
	 
	 <!-- Tab-11 Starts Here--> 
	<div class="tab-pane" id="tab11">
		<div class="span12">
		<div class="control-group">
	
			<div class="controls">														
				 Select Level : <font color="red"><b>*</b></font>	
				 <select name="select_level_present" id="select_level_present">		
				 <option value="-1">All</option>
						<?php
						foreach ($level_of_presentation as $st) {
						 ?><option value="<?php echo $st['mt_details_id']; ?>">
						 <?php echo  $st['mt_details_name'];  ?></option><?php
						}?>
						
						</select>
			</div>
		</div>		
		<section id="contents">
				<div class="bs-docs-example" style="height:auto; overflow:auto;">
					<div class="row-fluid" id="tab11_h">										  
						<table class="table table-bordered table-hover" id="example11" style="width:100%;">
							<thead>
								<tr>
									 <th> Title </th><th> Venue </th> <th> Presentation Type </th><th style="width:150px;"> Date </th><th> Presentation role </th> <th>Upload</th><th>Edit</th><th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
		</section>
			
			<section id="contents" class="tab11">
				<div class="bs-docs-example" style="height:auto; overflow:auto;">
					<div class="row-fluid" id="">	<br/>									  
						<?php $attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'paper_present', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
							<div class="span4">				
								<div class="control-group">
									<p class="control-label" for="paper_present_title"> Title : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input   placeholder="Enter paper presentation title" type="text" id="paper_present_title" name="paper_present_title" class="required"/>
											</div>
										</div>
								</div>																						
								<div class="control-group">
									<p class="control-label" for="paper_present_year"> Date : <font color="red"><b>*</b></font></p>
									<div class="controls">
									<div class="input-append date">
										<input readonly    placeholder="Select date" type="text" id="paper_present_year" name="paper_present_year" class="required text-right" style="width:180px;"/>
										<span class="add-on" id="paper_present_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											<span id='error_paper_present_year_btn'></span>
									</div>					
									</div>
								</div>
								<div class="control-group">
									<p class="control-label" for="paper_present_venue"> Venue : <font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append ">
												<textarea  placeholder="Enter Venue" id="paper_present_venue" name="paper_present_venue" class=""></textarea>
											</div>
										</div>
								</div>								
							</div>
							<div class="span4">
								
								<div class="control-group">
									<p class="control-label" for="level_of_presentation"> Level : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="level_of_presentation" id="level_of_presentation">		
												<?php
												foreach ($level_of_presentation as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
								</div>								
							
								<div class="control-group">
									<p class="control-label" for="presentation_type"> Type : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="presentation_type" id="presentation_type">		
												<?php
												foreach ($presentation_type as $st) {
												 ?><option  title="<?php echo $st['mt_details_name_desc']; ?>" value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
								</div>								
							<div class="control-group">
									<p class="control-label" for="presentation_role"> Role : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="presentation_role" id="presentation_role">		
												<?php
												foreach ($presentation_role as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
								</div>
							</div>
							<div class="span4"> 																	
								<div class="">	
										<b>Abstract:</b>
										<textarea placeholder="Enter Abstract " class="question_textarea" name="paper_present_abstract" id="paper_present_abstract" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
								</div>	
							</div>
								<input type="hidden" value="<?php echo $user_id;?>" id="paper_present_user_id" name="paper_present_user_id"/>
								<input type="hidden" value="" id="paper_present_id" name="paper_present_id"/>

						<?php echo form_close(); ?>	
						<div class="form-inline pull-right ">
							<br/><br/><button value="1" type="button" name="update_paper_present" id="update_paper_present" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
							<button type="button" name="save_paper_present" id="save_paper_present" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
							<button type="reset" id="reset_paper_present"  class="clear_all btn btn-info" onclick="reset_paper_present()" ><i class="icon-refresh icon-white"></i>Reset</button>
							<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
							<br/><br/>
						</div>							
			
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- Tab-11 Ends Here-->  	
	<!-- Tab-12 Starts Here--> 
	<div class="tab-pane" id="tab12">
		<div class="span12">				
		<section id="contents">
				<div class="bs-docs-example" style="height:auto; overflow:auto;">
					<div class="row-fluid" id="tab12_h">										  
						<table class="table table-bordered table-hover" id="example12" style="width:100%">
							<thead>
								<tr>
									<th> Sl.No </th> <th> Book Title </th><th> Book.No  </th><th> Co - Author </th><th> ISBN No </th> <th> Copyright Year </th><th>  Year of publication </th> <th>Upload</th><th>Edit</th><th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		
			<section id="contents" class="tab12">
				<div class="bs-docs-example" style="height:auto; overflow:auto;">
					<div class="row-fluid" id="">	<br/>									  
						<?php $attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'text_reference_book', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
							<div class="span4">													
								<div class="control-group">
									<p class="control-label" for="book_title"> Book Title : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input placeholder="Enter Book title" type="text" id="book_title" name="book_title" class="required"/>
											</div>
										</div>
								</div>
							<div class="control-group">
									<p class="control-label" for="year_of_publication">Published Year: <font color="red"><b>*</b></font></p>
									<div class="controls">
									<div class="input-append date">
										<input readonly  placeholder="Select year" type="text" id="year_of_publication" name="year_of_publication" class="text-right required" style="width:180px;"/>
										<span class="add-on" id="year_of_publication_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											<span id='error_year_of_publication_btn'></span>
									</div>					
									</div>
							</div>																
								<?php if($mode == 1){ ?>
								<div class="control-group">
									<p class="control-label" for="book_no"> Book No : </p>
										<div class="controls">
											<div class="input-append ">
												<input placeholder="Enter Book no. "  type="text" id="book_no" name="book_no" class=""/>
											</div>
										</div>
								</div>	
								
							<div class="control-group">
									<p class="control-label" for="copyright_year">Copyright  Year: </p>
									<div class="controls">
									<div class="input-append date">
										<input placeholder="Select year" readonly  type="text" id="copyright_year" name="copyright_year" class="text-right " style="width:180px;"/>
										<span class="add-on" id="year_copyright_year_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											<span id='error_copyright_year_btn'></span>
									</div>					
									</div>
							</div>
							
							<div class="control-group">
									<p class="control-label" for="no_of_chapters"> No. of  Chapters : <font color="red"><b></b></font></p>
									<div class="controls">
										<input type="text" placeholder="Enter No. of  Chapters" id="no_of_chapters" name="no_of_chapters" class="text-right allownumericwithoutdecimal"/>
									</div>
							</div>	
							<?php } ?>							
								<div class="control-group">
									<p class="control-label" for="co_author"> Co - Author(s)  : <font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append ">		
												<textarea placeholder="Enter Co-Author(s) detail"  id="co_author" name="co_author" class="" ></textarea>
											</div>
										</div>
								</div>	
							
								<div class="control-group">
									<p class="control-label" for="published_by"> Publisher Name : </p>
									<div class="controls">
										<textarea placeholder="Enter publisher's detail" id="published_by" name="published_by" class=""></textarea>
									</div>
								</div>									
							</div>
							<div class="span4">
							<div class="control-group">
									<p class="control-label" for="book_type"> Type : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="book_type" id="book_type">		
												<?php
												foreach ($book_type as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
							</div>								
								<div class="control-group book_type_name" >
                                                                    <p class="control-label" for="book_type_name"> Book Type : <font color="red">*</font></p>
									<div class="controls">
										<input placeholder="Enter book type" type="text" id="book_type_name" name="book_type_name" class=""/>
									</div>
								</div>	
								<div class="control-group">
									<p class="control-label" for="isbn_no"> ISBN No.: </p>
									<div class="controls">
										<input placeholder="International Standard Book Number" type="text" id="isbn_no" name="isbn_no" class=""/>
									</div>
								</div>							

							<!--<div class="control-group">
									<p class="control-label" for="chapters"> Chapter(s) : <font color="red"><b></b></font></p>
									<div class="controls">
										<textarea rows="2" placeholder="Enter Chapter(s)" id="chapters" name="chapters" class=""></textarea>
									</div>
							</div>-->
								<div class="control-group">
									<p class="control-label" for="book_language"> Language(s) : </p>
									<div class="controls">
										<textarea placeholder="Enter language(s) detail" id="language_used" name="language_used" class=""></textarea>
									</div>
								</div>								
							<?php if($mode == 1){ ?>
							<div class="control-group">
									<p class="control-label" for="printed_at"> Published in : </p>
									<div class="controls">
										<textarea placeholder="Enter Published in" rows="2" id="printed_at" name="printed_at" class=""></textarea>
									</div>
							</div>
						<?php } ?>
							</div>
							<div class="span4"> 																	
								<div class="">	
											<b>About the book:</b>
										<textarea placeholder="Enter Abstract " class="question_textarea" name="about_book" id="about_book" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
								</div>	
							</div>
								<input type="hidden" value="<?php echo $user_id;?>" id="book_user_id" name="book_user_id"/>
								<input type="hidden" value="" id="text_ref_id" name="text_ref_id"/>

						<?php echo form_close(); ?>	
						<div class="form-inline pull-right ">
							<br/><br/><button value="1" type="button" name="update_text_reference_book" id="update_text_reference_book" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
							<button type="button" name="save_text_reference_book" id="save_text_reference_book" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
							<button type="reset" id="reset_text_reference_book_present"  class="clear_all btn btn-info" onclick="reset_text_reference_book()" ><i class="icon-refresh icon-white"></i>Reset</button>
							<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
							<br/><br/>
						</div>							
			
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- Tab-12 Ends Here-->  

	<!-- Tab-13 Starts Here--> 
	<div class="tab-pane" id="tab13">
		<div class="span12">
			<!--<div class="control-group">
				<div class="controls">
				Select Type : <font color="red"><b>*</b></font>															
						  <select name="select_training_type" id="select_training_type">	
							 <option value="0"> Select Type</option>
						  <option value="-1"> All</option>
							<?php
						
							foreach ($training_type as $st) {
							 ?><option value="<?php echo $st['mt_details_id']; ?>">
							 <?php echo  $st['mt_details_name'];  ?></option><?php
							}?>
							
							</select>
				</div>
			</div>	-->
		<section id="contents" 	>
				<div class="bs-docs-example" style=" height:auto; overflow:auto;">  
					<div class="row-fluid" id="tab13_h">										  
						<table class="table table-bordered table-hover dataTable" id="example13" style="width:100%;">
							<thead>
								<tr>
									<th> Program Title </th><th>Level</th><th> Co-ordinator(s)</th> <th>Duration</th> <!--<th> From date </th>--> <th> To dates </th><th> Sponsored By</th> <th> Sponsored By</th> <th>Upload</th><th>Edit</th><th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><!--<td></td>-->
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		
			<section id="contents" class="tab13">
				<div class="bs-docs-example" style="height:auto; overflow:auto;">
					<div class="row-fluid" id="">	<br/>									  
						<?php $attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'training_workshop_conference', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
							<div class="span4">	
								<div class="info-tab note-icon"></div>
								<div class="control-group">
									<p class="control-label" for="program_title"> Program Title : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input placeholder="Enter Title" type="text" id="program_title" name="program_title" class="required"/>
											</div>
										</div>
								</div>	

								<div class="control-group">
										<p class="control-label" for="training_type"> Type : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">																
												<select name="training_type" id="training_type">		
													<?php
													foreach ($training_type as $st) {
													 ?><option value="<?php echo $st['mt_details_id']; ?>">
													 <?php echo  $st['mt_details_name'];  ?></option><?php
													}?>
													
													</select>
											</div>
										</div>
								</div>								
								<div class="control-group">
									<p class="control-label" for="event organizer">Event Organizer: <font color="red"><b></b></font></p>
									<div class="controls">
										<div class="input-append ">
											<input type="text"  placeholder="Enter Event Organizer" id="event_organizer" name="event_organizer" class=""/>
										</div>
									</div>
								</div>

								<div class="control-group">
									<p class="control-label" for="program_fees">Participants : </p>
									<div class="controls">
										<input type="text"  placeholder="Enter no. of participants" id="no_of_participants" name="no_of_participants" class="text-right allownumericwithoutdecimal"/>
									</div>
								</div>
								<!--<div class="control-group">
									<p class="control-label" for="program_fees">Fees (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): <font color="red"><b></b></font></p>
									<div class="controls">
										<input type="text"  placeholder="Enter Fees" id="program_fees" name="program_fees" class="text-right allownumericwithdecimal"/>
									</div>
								</div>-->									
								
								<!--<div class="control-group">
									<p class="control-label" for="coordinators"> Co-ordinator(s) : <font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append ">
												<textarea  placeholder="Enter Co-ordinator(s) name" rows="4" type="text" id="coordinators" name="coordinators" class=""></textarea>
											</div>
										</div>
								</div>--->	
								<div class="control-group">
									<p class="control-label" for="training_venue"> Venue : <font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append ">
												<textarea placeholder="Enter Venue" rows="4" id="training_venue" name="training_venue" class=""></textarea>
											</div>
										</div>
								</div>									
							
							</div>
							<div class="span4">
								
								<div class="control-group">
									<p class="control-label" for="duration"> Duration : <font color="red"><b>*</b></font></p>
									<div class="controls ">
										<div class="input-append ">	
										<input type="text" placeholder="Enter hours" id="duration_hours" name="duration_hours" class=" text-right allownumericwithoutdecimal required input-mini"/> <b>:</b>
										<input type="text" placeholder="Enter minutes" id="duration_minutes" name="duration_minutes" class=" text-right allownumericwithoutdecimal required input-mini"/>
										<span style="display: inline-block; " id='error_to_date'></span><span style="display: inline-block; " id='error_hour_minute'></span>
										</div>
									</div>
								</div>	
								<div class="control-group">
								<p class="control-label" for="duration">Select Level : <font color="red"><b>*</b></font>	</p>
									<div class="controls">														
										 
										 <select name="select_level_conference" id="select_level_conference">												
												<?php
												foreach ($level_of_presentation as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
									</div>
								</div>		
								<div class="control-group">
									<p class="control-label" for="event organizer"> Collaboration : <font color="red"><b></b></font></p>
									<div class="controls">	
										<div class="input-append ">
										<input type="text"  placeholder="Enter Collabration details" id="collaboration" name="collaboration" class=""/>
										</div>
									</div>
								</div>								
								<div class="control-group">
									<p class="control-label" for="training_role"> Your Role : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">	
												<input type="text"  name="training_role" id="training_role" class="required"/>
											<!--<select name="training_role" id="training_role">		
												<?php
												foreach ($presentation_role as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>-->
										</div>
									</div>
								</div>								
								<div class="control-group">
										<p class="control-label" for="Offerprogram"> Date <font color="red"><b>*</b></font> </p>
										<div class="controls">
											<div class="input-append ">									
												<input  placeholder="Select Date"  type="text" class="text-right span12 datepicker required" id="from_date" name="from_date"  style="width:85px;" readonly />
												<span class="add-on required" id="btn_from_date" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<input  placeholder="Select Date"  type="text" class="text-right span12 datepicker required" id="to_date" name="to_date"  style="width:85px;" readonly />
												<span class="add-on required" id="to_date_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											</div>
											<div class="input-append text-right">										
												<span style="display: inline-block; " id='error_to_date'></span>
												<span style="display: inline-block; " id='error_from_to_date'></span>
											</div>	
										</div>
								</div>	

								<div class="control-group">
									<p class="control-label" for="training_sposored_by"> Sponsored by : <font color="red"><b></b></font></p>
									<div class="controls">
									<div class="input-append ">
										<textarea placeholder="Enter Venue" id="training_sposored_by" name="training_sposored_by"  class=""> </textarea>
										</div>
									</div>
								</div>								
								<!--<div class="control-group">
									<p class="control-label" for="pedagogy"> Pedagogy : <font color="red"><b></b></font></p>
									<div class="controls">
									<div class="input-append ">
										<textarea placeholder="Enter Venue" id="pedagogy" name="pedagogy" rows="4" class="text-"> </textarea>
										</div>
									</div>
								</div>-->								
							</div>
							<div class="span4"> 																	
								<div class="">	
									<b>Highlights:</b>								
										<textarea placeholder="Enter Objective " class="question_textarea" name="trarinin_objectives" id="trarinin_objectives" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
								</div>	
							</div>
								<input type="hidden" value="<?php echo $user_id;?>" id="twc_user_id" name="twc_user_id"/>
								<input type="hidden" value="" id="twc_id" name="twc_id"/>
								<input type="hidden" value="" id="ttr" name="ttr"/>

						<?php echo form_close(); ?>	
						<div class="form-inline pull-right ">
							<br/><br/><button value="1" type="button" name="update_training_workshop_conference" id="update_training_workshop_conference" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
							<button type="button" name="save_training_workshop_conference" id="save_training_workshop_conference" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
							<button type="reset" id="reset_training_workshop_conference"  class="clear_all btn btn-info" onclick="reset_training_workshop_conference()" ><i class="icon-refresh icon-white"></i>Reset</button>
							<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
							<br/><br/>
						</div>							
			
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- Tab-13 Ends Here-->  	
	<!-- Tab-14 Starts Here--> 
	<div class="tab-pane" id="tab14">
		<div class="span12">
			<!--<div class="control-group">
				<div class="controls">
				Select Type : <font color="red"><b>*</b></font>															
						  <select name="select_training_type" id="select_training_type">	
							 <option value="0"> Select Type</option>
						  <option value="-1"> All</option>
							<?php
						
							foreach ($training_type as $st) {
							 ?><option value="<?php echo $st['mt_details_id']; ?>">
							 <?php echo  $st['mt_details_name'];  ?></option><?php
							}?>
							
							</select>
				</div>
			</div>	-->
		<section id="contents" 	>
				<div class="bs-docs-example" style=" height:auto; overflow:auto;">  
					<div class="row-fluid" id="tab13_h">										  
						<table class="table table-bordered table-hover dataTable" id="example14" style="width:100%;">
							<thead>
								<tr>
									<th> Program Title </th><th>Level</th> <th>Duration</th> <th> From date </th> <!--<th> To dates </th>--><th></th><th></th><th></th><th>Edit</th><th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><!--<td></td>-->
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		
			<section id="contents" class="tab14">
				<div class="bs-docs-example" style="height:auto; overflow:auto;">
					<div class="row-fluid" id="">	<br/>									  
						<?php $attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'training_workshop_conference_attended', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
							<div class="span4">	
								<div class="info-tab note-icon"></div>
								<div class="control-group">
									<p class="control-label" for="program_title"> Program Title : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">
												<input placeholder="Enter Title" type="text" id="program_title_attended" name="program_title_attended" class="required"/>
											</div>
										</div>
								</div>	

								<div class="control-group">
										<p class="control-label" for="training_type"> Type : <font color="red"><b>*</b></font></p>
										<div class="controls">
											<div class="input-append ">																
												<select name="training_type_attended" id="training_type_attended">		
													<?php
													foreach ($training_type as $st) {
													 ?><option value="<?php echo $st['mt_details_id']; ?>">
													 <?php echo  $st['mt_details_name'];  ?></option><?php
													}?>
													
													</select>
											</div>
										</div>
								</div>	
								<div class="control-group">
									<p class="control-label" for="event organizer">Event Organizer: <font color="red"><b></b></font></p>
									<div class="controls">
									<div class="input-append ">
										<input type="text"  placeholder="Enter Event Organizer" id="event_organizer_attended" name="event_organizer_attended" class=""/>
									</div>
									</div>
								</div>								
								<!--<div class="control-group">
									<p class="control-label" for="program_fees">Fees (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): <font color="red"><b></b></font></p>
									<div class="controls">
										<input type="text"  placeholder="Enter Fees" id="program_fees_attended" name="program_fees_attended" class="text-right allownumericwithdecimal"/>
									</div>
								</div>-->									
									
								<div class="control-group">
									<p class="control-label" for="training_venue"> Venue : <font color="red"><b></b></font></p>
										<div class="controls">
											<div class="input-append ">
												<textarea placeholder="Enter Venue" rows="4" id="training_venue_attended" name="training_venue_attended" class=""></textarea>
											</div>
										</div>
								</div>									
							
							</div>
							<div class="span4">
								<div class="control-group">
								<p class="control-label" for="duration">Select Level : <font color="red"><b>*</b></font>	</p>
									<div class="controls">														
										 
										<select name="select_level_conference_attended" id="select_level_conference_attended">	  
										      <?php
												foreach ($level_of_presentation as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
										</select>
									</div>
								</div>		
								<div class="control-group">
									<p class="control-label" for="training_role"> Your Role : <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="training_role_attended" id="training_role_attended">		
												<?php
												foreach ($conference_role as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
								</div>
								<div class="control-group" id="attended_specify_div">
									<p class="control-label" for=""> Specify: <font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">	
											<input type="text" name="training_role_attended_specify" id="training_role_attended_specify" class="required"/>
										</div>
									</div>
								</div>									
								<div class="control-group">
									<p class="control-label" for="training_role">Invited/Deputed:<font color="red"><b>*</b></font></p>
									<div class="controls">
										<div class="input-append ">																
											<select name="delegates_attended" id="delegates_attended">		
												<?php
												foreach ($delegates as $st) {
												 ?><option value="<?php echo $st['mt_details_id']; ?>">
												 <?php echo  $st['mt_details_name'];  ?></option><?php
												}?>
												
												</select>
										</div>
									</div>
								</div>								
								<div class="control-group">
										<p class="control-label" for="Offerprogram"> Date <font color="red"><b>*</b></font> </p>
										<div class="controls">
											<div class="input-append ">									
												<input  placeholder="Select Date"  type="text" class="text-right span12 datepicker required" id="from_date_attended" name="from_date_attended"  style="width:85px;" readonly />
												<span class="add-on required" id="btn_from_date_attended" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											<input  placeholder="Select Date"  type="text" class="text-right span12 datepicker required" id="to_date_attended" name="to_date_attended"  style="width:85px;" readonly />
												<span class="add-on required" id="to_date_btn_attended" style="cursor:pointer;"><i class="icon-calendar"></i></span>
											</div>
												<span style="display: inline-block; " id='error_to_date'></span><span style="display: inline-block; " id='error_from_to_date_attended'></span>
										</div>
								</div>								
							</div>
							<div class="span4"> 																	
								<div class="">	
									<b>Highlights:</b>								
										<textarea placeholder="Enter Objective " class="question_textarea" name="trarinin_objectives_attended" id="trarinin_objectives_attended" style="margin: 10px; width: 100px; height: 5px;" ></textarea>
								</div>	
							</div>
								<input type="hidden" value="<?php echo $user_id;?>" id="twca_user_id" name="twca_user_id"/>
								<input type="hidden" value="" id="twca_id" name="twca_id"/>
								<!--<input type="hidden" value="" id="ttr" name="ttr"/>-->

						<?php echo form_close(); ?>	
						<div class="form-inline pull-right ">
							<br/><br/><button value="1" type="button" name="update_training_workshop_conference_attended" id="update_training_workshop_conference_attended" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
							<button type="button" name="save_training_workshop_conference_attended" id="save_training_workshop_conference_attended" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
							<button type="reset" id="reset_training_workshop_conference_attended"  class="clear_all btn btn-info" onclick="reset_training_workshop_conference_attended()" ><i class="icon-refresh icon-white"></i>Reset</button>
							<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
							<br/><br/>
						</div>							
			
					</div>
				</div>
			</section>
		</div>
	</div>
	<!-- Tab-14 Ends Here-->  
	
	
<!--- Tab - 8 ends here --->
	<div class="tab-pane" id="tab15">
		<div class="span12">					
				<section id="contents">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="tab8_h">										  
							<table class="table table-bordered table-hover" id="example15" style="width:100%">
								<thead>
									<tr>
										<th style="width:50px;"> Sl.No </th> <th style="width:250px;"> Award Name </th><th>Award for</th> <th style="width:150px;"> Sponsored Organization</th><th style="width:100px;"> Awarded Year </th><th style="width:100px;"  >Remarks</th><th style="width:70px;" >Upload</th><th style="width:30px;">Edit</th><th style="width:50px;">Delete</th>
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
								
				<section id="contents" class="tab15">
					<div class="bs-docs-example"  style="height:auto; overflow:auto;">
						<div class="row-fluid" id="">	<br/>									  
							<?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'research_project', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
								<div class="span5">				
									<div class="control-group">
										<p class="control-label" for="award_name">Project Title : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Project Title" type="text" id="research_project_title" name="research_project_title" class="required"/>
												</div>
											</div>
									</div>									
									<div class="control-group">
										<p class="control-label" for="award_org"> Role : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">											
													<select id="research_project_user_role" name="research_project_user_role" >		
														<?php foreach ($investigator as $st) {
															?><option value="<?php echo $st['mt_details_id']; ?>">
														<?php echo  $st['mt_details_name'];  ?></option><?php
													}?>
									
													</select>
												</div>
											</div>
									</div>									
									<div class="control-group">
										<p class="control-label" for="research project"> Team member(s):<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea  placeholder="Enter Team member(s)"  id="research_project_team_member" name="research_project_team_member" class=""></textarea>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="status_jourrnal">Status : <font color="red"><b>*</b></font></p>
											<div class="controls">
												<div class="input-append ">
													<select name="research_project_status" id="research_project_status">		
														<?php foreach ($status as $st) {
															?><option value="<?php echo $st['mt_details_id']; ?>">
														<?php echo  $st['mt_details_name'];  ?></option><?php
														}?>
									
													</select>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="research project"> Collaboration:<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<textarea  placeholder="Enter Collaboration"  id="research_project_collabration" name="research_project_collabration" class=""></textarea>
												</div>
											</div>
									</div>									
								</div>
								<div class="span6"> 
									<div class="control-group">
										<p class="control-label" for="awarded_year"> Sanctioned Date : <font color="red"><b>*</b></font></p>
										<div class="controls">
										<div class="input-append date">
											<input readonly   placeholder="Select year" type="text" id="research_project_sactioned_date" name="research_project_sactioned_date" class="text-right required" style="width:180px;"/>
											<span class="add-on" id="research_project_scanctioned_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
												<span id='error_research_project_title_btn'></span>
										</div>					
										</div>
									</div>
									<div class="control-group">
										<p style="width:175px;" class="control-label" for="cash_award"> Quantum of Amount Sanctioned (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ):<font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input  placeholder="Enter Amount" type="text" id="research_project_amount" name="research_project_amount" class=" text-right allownumericwithdecimal"/>
												</div>
											</div>
									</div>	
									<div class="control-group">
										<p class="control-label" for="award_venue"> Duration( in months ) : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													<input type="text"   placeholder="Enter Duration" id="research_project_duration" name="research_project_duration" class=" text-right allownumericwithoutdecimal "/>
												</div>
											</div>
									</div>
									<div class="control-group">
										<p class="control-label" for="award_remarks">Funding Agency : <font color="red"><b></b></font></p>
											<div class="controls">
												<div class="input-append ">
													
													<textarea placeholder="Enter Funding Agency" rows="4" cols="6" id="research_project_funding_agency" name="research_project_funding_agency" class=""></textarea>
												</div>
											</div>
									</div>	
								</div>
									<input type="hidden" value="<?php echo $user_id;?>" id="research_user_id" name="research_user_id"/>
									<input type="hidden" value="" id="research_project_id" name="research_project_id"/>

							<?php echo form_close(); ?>	
							<div class="form-inline pull-right ">
								<br/><br/><button value="1" type="button" name="update_research_project" id="update_research_project" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
								<button type="button" name="save_research_project" id="save_research_project" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
								<button type="reset" id="reset_research_project"  class="clear_all btn btn-info" onclick="reset_research_project()" ><i class="icon-refresh icon-white"></i>Reset</button>
								<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
								<br/><br/>
							</div>							
				
						</div>
					</div>
				</section>
		</div>
	</div>
<!--- Tab-15 Ends  Here --->
	
	
											
	<div class="tab-pane  hide fade in" id="tab4">   <!-- Tab-4 Starts Here-->
		<div class="span12">
		<!-- Contents -->
			<section id="contents">
				<div class="bs-docs-example"  style="height:auto; overflow:auto;">
					<div class="row-fluid" id="tab4_h">										  
					<table class=" table table-bordered table-hover display nowrap" id="example1" cellspacing="0" width="100%"aria-describedby="example_info" style="font-size:12px;">
					<thead>
						<tr>
						<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
					</tbody>
				</table>
					</div>
				</div>
			</section>
			
		<section id="contents" class="tab4">
			<div class="bs-docs-example" style="height:auto; overflow:auto;">
			<div class="navbar" id="fc"  style="display:none;">
				<div class="navbar-inner-custom" >Faculty Contribution<?php ?><br/></div>
			</div>

	
			<div  class="row-fluid tab1" >
				<?php												
			$attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'journal_publication', 'enctype'=>'multipart/form-data');
			echo form_open(current_url(), $attributes);
					?>
			<div class="span12 accordion-group"><br/>
				<div class="span4">				
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Project Title : <font color="red"><b>*</b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Title" type="text" id="title_res_jourrnal" name="title_res_jourrnal" class="required"/>
							</div>
						</div>
				 </div>
			    <div class="control-group">
					<p class="control-label" for="author">Co - Author(s) : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
								<textarea placeholder="Enter Co - Author(s) " id="author_jourrnal" name="author_jourrnal" class=""></textarea>
							</div>
						</div>
				 </div>				    
				<div class="control-group">
					<p class="control-label" for="publisher_jourrnal">Publisher : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">										
								<textarea placeholder="Enter Publisher's detail" id="publisher_jourrnal" name="publisher_jourrnal" class=""></textarea>
							</div>
						</div>
				</div>
				
				<!--<div class="control-group">
						<div class="controls">

									Citation Count : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="citation_jourrnal" name="citation_jourrnal" class="input-mini allownumericwithoutdecimal "/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									H-index : <input type="text" id="hindex_jourrnal" name="hindex_jourrnal" class="input-mini allownumericwithoutdecimal "/><br/>
									i10-index :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="i10_index_jourrnal" name="i10_index_jourrnal" class="input-mini allownumericwithoutdecimal "/>
						</div>
				</div>-->
								 <div class="control-group">
					<p class="control-label" for=""> Amount (in <img style="width:8px;height:8px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> ): <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Amount" type="text" id="amount_res_jourrnal" name="amount_res_jourrnal" class="text-right allownumericwithdecimal" value="0.0000"/>
										
							</div>
						</div>
				 </div>				 

					
				<div class="control-group">
					<p class="control-label" for="res_pages_jourrnal">Page(s) : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter page(s)" type="text" id="pages_jourrnal" name="pages_jourrnal" class="text-right"/>
							</div>
						</div>
				</div>
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Publication Date:<font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input placeholder="Select date" type="text" class="text-right datepicker " id="dp4_jourrnal" name="dp4_jourrnal"  style="width:180px;" readonly>
								<span class="add-on" id="btn2" style="cursor:pointer;"><i class="icon-calendar"></i></span>

						</div>					
					</div>
				</div>				
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Current version Date:<font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input placeholder="Select date" type="text" class="text-right span12 datepicker " id="dp4_end_jourrnal" name="dp4_end_jourrnal"  style="width:200px;"readonly>
							<span class="add-on required" id="dp4_end_btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>		
						</div>	<br/>						
							<span style="display: inline-block; " id='eror_jourrnal'></span><span style="display: inline-block; " id='end_error_placeholder_dp4'></span>
					</div>
				</div>	
				<div class="control-group">
					<p class="control-label" for="citation_jourrnal">Citation  Index<font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Citation Index" type="text" id="citation_jourrnal" name="citation_jourrnal" class="text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				 </div>					 
				 <div class="control-group">
					<p class="control-label" for="hindex_jourrnal">h-Index<font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter h-Index" type="text" id="hindex_jourrnal" name="hindex_jourrnal" class=" text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				</div>
				<div class="control-group">
					<p class="control-label" for="i10_index_jourrnal">i10-Index<font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input type="text" placeholder="Enter i10-Index" id="i10_index_jourrnal" name="i10_index_jourrnal" class="text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				 </div>	 				
				</div>
				<div class="span4">
			
								   
				<div class="control-group">
					<p class="control-label" title="Digital Object Identifier" for="doi_jourrnal">DOI : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Digital Object Identifier " type="text" id="doi_jourrnal" name="doi_jourrnal" class=""/>
							</div>
						</div>
				</div>
				<div class="control-group">
					<p class="control-label" title="Internation Standard Serial Number" for="issn_jourrnal">ISSN : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Internation Standard Serial Number" type="text" id="issn_jourrnal" name="issn_jourrnal" class=""/>
							</div>
						</div>
				</div>	
				<div class="control-group">
					<p class="control-label" for="publish_online_jourrnal">Published URL : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input type="text" placeholder="Enter Published URL" id="publish_online_jourrnal" name="publish_online_jourrnal" class=""/>
							</div>
						</div>
				</div>

	
				<div class="control-group">
					<p class="control-label" for="index_terms_jourrnal">Index Term(s)  : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
								<textarea  placeholder="Enter Index Term(s)" id="index_terms_jourrnal" name="index_terms_jourrnal" class=""></textarea>
							</div>
						</div>
				</div>
				
				<div class="control-group">
					<p class="control-label" for="status_jourrnal">Status : <font color="red"><b>*</b></font></p>
						<div class="controls">
							<div class="input-append ">
								<select name="status_jourrnal" id="status_jourrnal">		
									<?php foreach ($status as $st) {
										?><option value="<?php echo $st['mt_details_id']; ?>">
									<?php echo  $st['mt_details_name'];  ?></option><?php
									}?>
				
								</select>
							</div>
						</div>
				</div>	
				<div class="control-group">
					<p class="control-label" for=""> Sponsored by : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">									
									<textarea placeholder="Enter Sponsor's detail" id="contribution_res_guid_jourrnal" name="contribution_res_guid_jourrnal" class=""></textarea>
							</div>
						</div>
				</div>
				<div class="control-group">
					<p class="control-label" for="Offerprogram">Issue Date : <font color="red"><b></b></font> </p>
					<div class="controls">
						<div class="input-append ">									
							<input placeholder="Select date" type="text" class="text-right span12 datepicker " id="issue_date_jourrnal" name="issue_date_jourrnal"  style="width:200px;"readonly>
							<span class="add-on required" id="issue_date_btn_jourrnal" style="cursor:pointer;"><i class="icon-calendar"></i></span>		
						</div>	<br/>						
							<span style="display: inline-block; " id='eror_issue_date_jourrnal'></span><span style="display: inline-block; " id='end_error_placeholder_dp4'></span>
					</div>
				</div>
			    <div class="control-group">
					<p class="control-label" for="issue_no_jourrnal">Issue No. : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Issue No." type="text" id="issue_no_jourrnal" name="issue_no_jourrnal" class="text-right allownumericwithoutdecimal "/>
							</div>
						</div>
				</div>
				<div class="control-group">
					<p class="control-label" for="vol_no_jourrnal">Volume No. : <font color="red"><b></b></font></p>
						<div class="controls">
							<div class="input-append ">
										<input placeholder="Enter Volume No." type="text" id="vol_no_jourrnal" name="vol_no_jourrnal" class="text-right allownumericwithoutdecimal"/>
							</div>
						</div>
				</div>				
				


				</div>
				<div  class="span4" style="height:500px;">
				Abstract : 
						<textarea class="question_textarea" name="abstract_data_jourrnal" id="abstract_data_jourrnal" style="margin: 10px; width: 150px; height: 10px;" ></textarea>		
				</div>
			</div>
				
			
 				
				<input type="hidden" id="journal_id" name="journal_id"/>
				<input type="hidden" id="user_id_journal" name="user_id_journal" value="<?php echo $user_id;?>"/>
			<!--	<input type="hidden" id="my_res_guid_id_update_val" name="my_res_guid_id_update_val" value="0"/>-->
				<?php echo form_close(); ?>	
				
				<div class="form-inline pull-right ">
					<br/><br/><button type="button" name="jourrnal_update" id="jourrnal_update" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
					<button type="button" name="jourrnal_save" id="jourrnal_save" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
					<button type="reset" id="reset"  class="clear_all btn btn-info" onclick="reset_jourrnal()" ><i class="icon-refresh icon-white"></i>Reset</button>
					<?php if($close_btn == "close_btn"){ ?><a class="brand-custom" href= "<?php echo base_url('curriculum/dept_users'); ?>" id="cancel_button"><span class="btn btn-danger pull-right"><i class="icon-remove-sign icon-white"></i><span></span> Close</span></a> <?php } ?>	
					<br/><br/>
				</div>		
			</div>	
					
			</div>
		</section>
		</div>
	</div><!-- Tab-4 Ends Here -->
			<input type="hidden" value="<?php echo $user_id;?>" id="user_id" name="user_id"/>
			<input type="hidden" value="" id="user_id_data" name="user_id_data"/>
			<input type="hidden" value="" id="my_achive_id" name="my_achive_id"/>
			<input type="hidden" value="" id="my_taining_id" name="my_training_id"/>
			<input type="hidden" value="" id="my_twid" name="my_twid"/>
	</div>
	</section>
	</div></div></div>

			
	<div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="btnYes"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>		
	
	<div id="delete_confirm_consult" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_consult"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	
	<div id="delete_confirm_text_reff_book" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_text_ref"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	
	<div id="delete_confirm_training_conferrence" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_traning"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	
	<div id="delete_confirm_training_conferrence_attended" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_traning_attended"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	
	<div id="delete_research_projects" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_research_projects_data"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>

	<div id="delete_confirm_sponsored" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_sponsored"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	<div id="delete_award_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_award"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>
	<div id="delete_patent_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_patent"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	
	<div id="delete_scholor_confirm" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_scholor"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>	
	
	

	<div id="myModal5" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="btnYest"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>

	<div id="myModal6" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="btnDelWork"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>
	
		<div id="delete_my_qualification" class="modal hide fade"  role="dialog" aria-labelledby="delete_my_qualification" aria-hidden="true" style="display: none;" data-controls-modal="delete_my_qualification" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_my_qua_btn"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>		
	<div id="paper_presentation_modal" class="modal hide fade"  role="dialog" aria-labelledby="my_innovation_warning" aria-hidden="true" style="display: none;" data-controls-modal="my_innovation_warning" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_paper_presentation"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
	</div>
	<div id="incorrect_file_header" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="incorrect_file_header" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					Invalid file header
				</div>
			</div>
		</div>

		<div class="modal-body">
			<p> Unable to display the file details because the file headers are invalid or might be trying to upload wrong file.  </p>
		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
		</div>
	</div>	
	<div id="invalid_file" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="incorrect_file_header" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					Invalid file header
				</div>
			</div>
		</div>

		<div class="modal-body">
			<p> Unable to upload the file  because the file headers are invalid or might be trying to upload wrong file.  </p>
		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
		</div>
	</div>
						
	<!-- Modal to display file is empty status  -->
	<div id="csv_file_empty" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="csv_file_empty" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					Warning
				</div>
			</div>
		</div>

		<div class="modal-body">
			<p> Student template is empty.  </p>
		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" onClick="window.location.reload()" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
		</div>
	</div>
						

						
	<div id="File_exist" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					File Already Exist
				</div>
			</div>
		</div>

		<div class="modal-body">
			<p>Sorry , cant upload file. File already exist.  </p>
		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
		</div>
	</div>
	

	
	<form id="myform" name="myform" method="POST" enctype="multipart/form-data" >
			<div  class="modal hide fade" id="upload_modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; width:750px;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="false" >
				<div class="modal-header">
						<div class="navbar">
						<div class="navbar-inner-custom" data-key="lg_upload_artifacts">
							Uploaded Files
						</div>
						</div>
				</div>

				<div class="modal-body">
						<div id="res_guid_files"></div>
					<div id="loading_edit" class="text-center ui-widget-overlay ui-front" style = "visibility: hidden">
						<img style="width:75px;height:75px" vspace="250px" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="" />
					</div>
				</div>

				<div class="modal-footer">
					
					<button class="btn btn-danger pull-right close_up_div" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>	
					
					<button class="btn btn-primary pull-right"  style="margin-right: 3px; margin-left: 3px;" id="save_res_guid_desc" name="save_res_guid_desc" value=""><i class="icon-file icon-white"></i> <span data-key="lg_save">Save</span></button>
					
					<button class="btn btn-success pull-right" style="margin-right: 3px; margin-left: 3px;" id="uploaded_file" name="uploaded_file" value=""><i class="icon-upload icon-white"></i> Upload more</button>
				</div>
			</div>
	</form>
			<input type="hidden" id="my_id" name="my_id" value=""/>
			
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_res_guid_file"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
			</div>
		</div>	
		
		<div id="delete_res_deatil_file_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_res_detail_file"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
			</div>
		</div>
		
				<!--Error Modal for file name size exceed-->
		<div class="modal hide fade" id="file_name_size_exc" name="file_name_size_exc" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
				<div class="modal-header">
				<div class="navbar">
						<div class="navbar-inner-custom" data-key="lg_err_msg">
						Error Message
						</div>
				</div>
				</div>

				<div class="modal-body" data-key="lg_file_name_too_long">
				File name is too long.
				</div>

				<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>									
				</div>
		</div>
							<!--Warning modal for exceeding the upload file size-->
		<div class="modal hide fade" id="larger" name="larger" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true" >
				<div class="modal-header">
				<div class="navbar">
						<div class="navbar-inner-custom">
						Warning
						</div>
				</div>
				</div>

				<div class="modal-body" data-key="lg_file_name_too_long">
				<p> Uploaded file size is larger than <span class="badge badge-important">10MB </span>.</p>
				</div>

				<div class="modal-footer">
				<button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>									
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
	<div id="manage_designation_list_modal"  class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; margin-left: -450px; width:900px;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					User Designation List
				</div>
			</div>
		</div>

		<div class="modal-body">
			<b>Current Designation : <font color="#2C5E77" ><span id="current_designation" ></span></font></b>
		</div>

		<div style=" padding: 10px;">										  
			<table class="table table-bordered table-hover" id="example_designation" style="">
				<thead>
					<tr>
					<th> Sl No. </th><th>Department</th><th>Designation </th> <th>Year</th><th>Edit</th><th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td> No Data to Display </td><td></td><td></td><td></td><td></td><td></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/><br/>
	<?php $attributes = array('class' => 'form-horizontal_new', 'method' => 'POST', 'id' => 'save_user_designations', 'enctype'=>'multipart/form-data'); echo form_open(current_url(), $attributes); ?>
		<div style=" padding: 15px;" class="accordion-group ">
			<div class=" ">
				<table style=" padding: 15px;width:800px;" >
					<tr>
						<td><b> Department : </b></td>
						<td>
							<select style="width:150px;"  id="dept_user" name="dept_user">
										<!--<option value="0">Select </option>-->
										<?php foreach($dept as $de){?>
										<!-- title="<?php echo $de['dept_description'];  ?>"  -->
										<option value="<?php echo $de['dept_id'];?>"  <?php if($de['dept_id'] == $base_dept_id){echo 'selected="selected"';}?> ><?php echo $de['dept_name']?></option>
										<?php } ?>
									</select >
						
						</td>
						<td> <b>Designation : <font color="red"> * </font></b></td>
						<td>
							<select name="designation_list" id="designation_list">		
								<option value="0">Select </option>
									<?php
									foreach ($designation as $list_item_designation) {
									 ?><option value="<?php echo $list_item_designation['designation_id']; ?>"><?php echo  $list_item_designation['designation_name'];  ?></option><?php
									}?>
							</select>
						</td>
						<td> <b>Year : <font color="red"> * </font> </b></td>
						<td>
							<div class="control-group">							
								<div class="controls">
									<div class="input-append date">
										
										<input placeholder="Select date" style="width:150px;" type="text" class="text-right datepicker " id="designation_date" name="designation_date"  value="" readonly>
										<span class="add-on" id="btn_designation_date" style="cursor:pointer;"><i class="icon-calendar"></i></span>
										<span id='error_designation_date'></span>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	<input type="hidden" id="design_user_id" name="design_user_id" value="<?php echo $user_id; ?>" /> 
	<input type="hidden" id="user_usd_id" name="user_usd_id" value="" /> 
	
		<?php echo form_close(); ?>	
		<div class="modal-footer">			
				<div class="form-inline pull-right ">
					<button value="1" type="button" name="update_save_user_designations" id="update_save_user_designations" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Update</button>
					<button type="button" name="save_save_user_designations" id="save_save_user_designations" class="btn btn-primary"><i class="icon-file icon-white"></i><span></span> Save</button>
					<button type="reset" id="reset_save_user_designations"  class="clear_all btn btn-info" onclick="reset_save_user_designations()" ><i class="icon-refresh icon-white"></i>Reset</button>
					<button type="button" class="btn btn-danger btn-sm" style="margin-right: 2px;" data-dismiss="modal" ><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button>	
				</div>
		</div>
	</div>
	<div id="Exist" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="invalid_file_extension" data-backdrop="static" data-keyboard="false"></br>
		<div class="container-fluid">
			<div class="navbar">
				<div class="navbar-inner-custom">
					Warning
				</div>
			</div>
		</div>

		<div class="modal-body">
			<p>Data Already Exist</p>
		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
		</div>
	</div>	
	
	<div id="delete_user_designation" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false"></br>
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
				 <a class="btn btn-primary" data-dismiss="modal" id="delete_user_designation_btn"><i class="icon-ok icon-white"></i> Ok </a> 
				<button class="btn btn-danger" data-dismiss="modal" ><i class="icon-remove icon-white"></i> Close </button> 
			</div>
		</div>	
	
</div> 
</div>
</div>
<!--Do not place contents below this line-->


<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('/twitterbootstrap/js/upclick_faculty_contribution.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.form.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/edit_profile.js'); ?>" type="text/javascript"> </script>
<link href="<?php echo base_url('twitterbootstrap/css/intlTelInput.css'); ?>" rel="stylesheet">	
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/intlTelInput.js'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/notify.js'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/notify.min.js'); ?>"></script>
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>



<script>
// Dont remove comment 
$("#mobile-number").intlTelInput({
//autoFormat: false,
//autoHideDialCode: false,
//defaultCountry: "jp",
//nationalMode: true,
//numberType: "MOBILE",
//onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
//preferredCountries: ['cn', 'jp'],
//responsiveDropdown: true,
utilsScript: "<?php echo base_url('twitterbootstrap/js/utils.js'); ?>"
});
</script>

<script>

</script>
<!-- End of file edit_user.php 
Location: .configuration/users/edit_user.php -->