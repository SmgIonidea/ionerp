	<section  id="contents" class="">
		<div class="form-horizontal_new bs-docs-example" style="width:auto; overflow:auto;" >
			<div class="span12">
				<div class="control-group">
				
					<table style="width:1030px;">
						<tr><th rowspan="6">
						
						<div id="imgArea"  style="width:180px;height:180px;align:center;top:17px;left:20px;" class="">
						<?php if($user->user_pic == ""){?>
								<div id="img_val" class="img_val">
								<img src="<?php echo base_url('twitterbootstrap/img/default.jpg');?>" id="upload_img" alt="" />
								</div>
						<?php }else{ ?>
								<div id="img_val_1" class="img_val">
									<img src="<?php echo base_url('uploads/user_profile_photo/medium/'.$user->user_pic);?>" id="upload_img" alt=""/>
								</div>	
						<?php } ?>
					</div>
						</th><th style="width:150px;text-align: right;"><b>Name :</b></th><th style="width:350px;text-align: left;"><b><?php echo str_repeat("&nbsp;", 10); ?><?php echo " ". $user->title ." ". $user->first_name ." ". $user->last_name;?></b></th>
							 <th rowspan="" style="width:150px;text-align: right;"><b>Present Address :</b></th><th  rowspan="" style="width:400px;"><b><?php if(!empty($user->present_address)){echo " ".$user->present_address;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></b></th>
						</tr>
						<tr  style="font-size:12px"><th style="width:150px;text-align: right;"><b>Contact :</b></th><th style="text-align: left;"><b><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->contact)){echo " ".$user->contact;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></b></th></tr>
						<tr  style="font-size:12px"><th  style="width:150px;text-align: right;"><b>Email :</b></th ><th colspan="2"; style="text-align: left;" ><b><?php echo str_repeat("&nbsp;", 10); ?><?php echo " ".$user->email;?></b></th></tr>
						<tr  style="font-size:12px"><th style="width:150px;text-align: right;"><b>Website :</b></th><th style="width:450px; text-align: left;"><b><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->user_website)){echo " ".$user->user_website;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></b></th></tr>
						<tr  style="font-size:12px"> <th style="width:150px;text-align: right;"><b>Date of Birth : </b></th><th style="text-align: left;"><b><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->DOB)){echo " ".$user->DOB;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></b></th></tr>
						<tr  style="font-size:12px"> <th style="width:150px;text-align: right;"><b>Blood Group :</b></th><th style="text-align: left;"><b><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->blood_group)){echo " ".$user->blood_group;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></b></th></tr>
					</table>				
				</div>			
				
			</div>
			<div id="add_brk"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></div>
			<div  id="div1" class="add_attr_style1" style="page-break-inside: avoid;position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
				<div style="padding:0 0 0 10px";><h4><b><font color="#800000" size="2px">Professional  Details: </font></b></h4/></div>
				<table class="table_border" style="width:1000px;font-size:12px">
					<tr><th style="width:150px;text-align: right;">Employee No :</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->emp_no)){echo " ".$user->emp_no;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Faculty Type:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($faculty_type)){echo " ".$faculty_type;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Current Designation:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($current_designation)){echo " ".$current_designation;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					</tr>
					<tr><th style="width:150px;text-align: right;">Date of Joining :</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->year_of_joining)){ echo "".date("d-m-Y",strtotime($user->year_of_joining));	} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Relieving Date:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(($user->resign_date)!="0000-00-00"){echo "".date("d-m-Y",strtotime($user->resign_date));} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Retirement Date:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(($user->retirement_date)!="0000-00-00"){echo " ". date("d-m-Y",strtotime($user->retirement_date));} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					</tr>						
					<tr><th style="width:150px;text-align: right;">Teaching Experience :</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->teach_experiance)){echo "".$user->teach_experiance;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Industrial Experience:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->indurtrial_experiance)){echo "".$user->indurtrial_experiance;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Total Experience:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(($user->teach_experiance + $user->indurtrial_experiance) != 0){echo "". $user->teach_experiance +  $user->indurtrial_experiance;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					</tr>					
					<tr><th style="width:150px;text-align: right;">Faculty Serving :</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($faculty_serving)){echo " ".$faculty_serving;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Last Promotion:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(($user->last_promotion)!="0000-00-00"){echo " ". date("d-m-Y",strtotime($user->last_promotion)); } else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Salary Pay:</th><th style="text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(($user->salary_pay) != 0.00){?>  <img style="width:10px;height:10px;position:relative;left:2%;"  src="<?php echo base_url('twitterbootstrap/img/rupee.png');?>" alt="" /> <?php echo " ".$user->salary_pay;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					</tr>					
					<tr><!--<th style="width:150px;text-align: right;">Remark(s):</th><th  style="width:250px;" ><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->remarks)){echo " ".$user->remarks;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Responsibility(s):</th><th style="width:250px;" ><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->responsibilities)){echo " ".$user->responsibilities;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>-->
					
						<th style="width:150px;text-align: right;">Professional bodies:</th><th style="width:250px;text-align: left;" colspan=5 ><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->professional_bodies)){?> <br/><?php echo " ". $user->professional_bodies;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
							<th></th><th></th><th></th><th></th> 
					</tr>
				</table>							
					
			</div><br/>			
			
			<div class="add_attr_style1" style="page-break-inside: avoid; position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
				<div style="padding:0 0 0 10px";><h4><b><font color="#800000" size="2px">Qualification  Details: </font></b></h4/></div>
				<table style="width:1000px;font-size:12px">
					<tr><th style="width:150px;text-align: right;">Highest Qualification :</th><th style="text-align: left;" ><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->user_qualification)){echo " ".$user->user_qualification;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:150px;text-align: right;">Specialization :</th><th style="width:150px;text-align: left;" ><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->user_specialization)){echo " ".$user->user_specialization;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>						
					</tr>
					<tr>
					<th style="width:150px;text-align: right;">Research Interest :</th><th style="width:500px;text-align: left;"><?php echo str_repeat("&nbsp;", 1); ?><?php if(!empty($user->research_interrest)){echo " ".$user->research_interrest;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					<th style="width:150px;text-align: right;">Skills :</th><th style="width:250px;text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->skills)){echo "".$user->skills;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					</tr>						

				</table>							
					
			</div><br/>		

			<div class="add_attr_style1" style="page-break-inside: avoid;position: relative;-webkit-border-radius: 25px;-moz-border-radius: 25px;border-radius: 0px;border:1px solid #C4C0BE;background-color:#F7F7F7;-webkit-box-shadow: #C4C4C4 10px 10px 10px;-moz-box-shadow: #C4C4C4 10px 10px 10px; box-shadow: #C4C4C4 10px 10px 10px;">
				<div style="padding:0 0 0 10px";><h4><b><font color="#800000" size="2px">Ph.D  Details: </font></b></h4/></div>
				<table style="width:1000px;font-size:12px">
					<tr><th style="width:170px;text-align: right;">Ph.D From :</th><th style="width:450px;text-align: left;"><?php echo str_repeat("&nbsp;", 0); ?><?php if(!empty($user->phd_from)){echo " ".$user->phd_from;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
						<th style="width:170px;text-align: right;">Ph.D Status :</th><th style="width:250px;text-align: left;"><?php echo str_repeat("&nbsp;", 0); ?><?php if(!empty($user->phd_status_data)){echo " ".$user->phd_status_data;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>						
					</tr>
					<tr><th style="width:170px;text-align: right;">Supervisor(s) :</th><th style="width:450px;text-align: left;"><?php echo str_repeat("&nbsp;", 0); ?><?php if(!empty($user->superviser)){echo " ".$user->superviser;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>						
					<th style="width:170px;text-align: right;">Ph.D Assessment Year :</th><th  style="width:250px;text-align: left;"><?php echo str_repeat("&nbsp;", 05); ?><?php if(($user->phd_assessment_year)!="0000"){echo " ".$user->phd_assessment_year;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>
					</tr>
					<tr><th style="width:170px;text-align: right;"><font color="#800000"></br><br/>Ph.D Guidance Detail(s) :</font></th></tr>
					<tr>		
					<th style="width:170px;text-align: right;"><br/>Candidate(s) within organization :</th><th style="width:450px;text-align: left;"><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->guidance_within_org)){echo " ".$user->guidance_within_org;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>						
					<th style="width:170px;text-align: right;"><br/>Candidate(s) outside organization:</th><th style="text-align: left;" ><?php echo str_repeat("&nbsp;", 10); ?><?php if(!empty($user->guidance_outside_org)){echo " ".$user->guidance_outside_org;} else { echo str_repeat("&nbsp;", 10); echo "--";} ?></th>											
					</tr>						

				</table>							
					
			</div><br/>
		</div>
	</section>