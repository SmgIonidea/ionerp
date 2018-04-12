<!-----------------------------------------------------------------
/*
* Description: Curriculum Details.
* Created: 01/04/2013
* Author: Pavan D M
* Modification History:
* Date                Modified By                Description

Licence: Open Source :-P
-------------------------------------------------------------------->
<?php
// var_dump($this->data['po_details']);
// exit();
?>

<!DOCTYPE html>
<html lang="en">
	<!--head here -->
<?php  
		$this->load->view('includes/head'); 
	?>

	<body data-spy="scroll" data-target=".bs-docs-sidebar">
		<!--branding here-->
		<?php  
			$this->load->view('includes/branding'); 
		?>
		
		<!-- Navbar here -->
		
		<br>
		<div class="container-fluid">
			<div class="row-fluid">
				<!--sidenav.php-->
				
				<div class="span12">
					<!-- Contents
				================================================== -->
					<section id="contents">
					
					<div class="bs-docs-example fixed-height" >
					<!--content goes here-->
						<div class="navbar">
			 <div class="navbar-inner-custom">
				Curriculum Details
			 </div>
			</div>
			
			<div class="pull-right">
						<a href="<?php echo base_url('curriculum/curriculum/curriculum_static_index');?>" style="margin-left: 10px;" class="btn btn-danger dropdown-toggle pull-right"><i class="icon-remove icon-white"></i><span></span>Close</a>		
	        </div>

			
						<div class="control-group">
						
	          <h4><font color="blue"><label class="control-label" for="curriculum">Name Of the Curriculum : <u><b><?php
             foreach($curr_details as $row)
             {
               print $row->crclm_name ;
 
               }
             ?></u></b></label></font></h4>

			 <?php
				$attributes = array('class' => 'form-horizontal', 'method'=> 'POST', 'id' => 'curriculum_details');
				echo form_open("configuration/curriculum/curriculum_details",$attributes);?>				
				
				<div class="controls">
				</div>
             </div>
			 <div class="bs-docs-example">
			 <div class="pull-left container-fluid">
				<table class="table table-bordered ">
						<thead>
							<tr>
							
								<th>Total Credits</th>
								<th>Total Terms</th>
								<th>Minimum Duration(Years)</th>
								<th>Maximum Duration(Years)</th>
								<th>Curriculum Owner</th>
								
							</tr>
						</thead>	
							<tbody>
							
							<tr>
								<td>
									<center>
										<?php foreach($owner as $row)
										{
											echo $row->total_credits;
										}
										 ?>
										</center>
								 </td>
								 <td>
									<center>
										<?php foreach($owner as $row)
										{
											echo $row->total_terms;
										}
										 ?>
										</center>
								 </td>
								<td>
									<center>
										<?php foreach($owner as $row)
										{
											echo $row->pgm_min_duration;
										}
										 ?>
										</center>
								 </td>
								<td>
									<center>
										<?php foreach($owner as $row)
										{
											echo $row->pgm_max_duration;
										}
										 ?>
										</center>
								 </td>
								
								<td>
									<center>
										<?php foreach($owner as $row)
										{
											echo $row->first_name." ".$row->last_name;
										}
										?>
									</center>
								</td>
							</tr>
						</tbody>
				</table>
			</div>
		
			
			 <div class="pull-left container-fluid">
			 <table class="table table-bordered">
			   <thead>
			 <tr><td colspan="6">
				 <h4><label class="control-label" for="curriculum"><font color="blue"><b>Curriculum Term Details</b></font></h4>
				</td></tr>
					<tr>
						<th>Sl No. </th>
						<th> Name </th>
						<th> Duration (Weeks) </th>
						<th>Credits </th>
						<th>Theory Courses</th>
						<th>Practical Courses</th>
						
						</tr>
					  </thead>
					  <tbody id="s">
				<?php $i=1;
				foreach($term_details as $row): ?>
				<tr>
					<td> <?php echo ($i)?> </td>
					<td> <?php echo $row['term_name']; ?></td>
					<td> <?php echo $row['term_duration']; ?></td>
					<td> <?php echo $row['term_credits']; ?></td>
					<td> <?php echo $row['total_theory_courses']; ?></td>
					<td> <?php echo $row['total_practical_courses']; $i++;?></td>
				</tr>

              	<?php endforeach; ?>
						
						
						</tbody>
				
				 
					</table>
          
			
			  
			</div>
			
			<table class="table table-bordered" >
			
              <thead>
			  
                <tr>
				<tr><td colspan="3">
				 <h4><label class="control-label" for="curriculum"><font color="blue"><b>Curriculum Approval Details</b></font></h4>
				</td></tr>
                  <th></th>
                <th><center>PO & PEO Mapping</center></th>
                  <th><center>CO & PO Mapping</center></th>
                 
                </tr>
              </thead>
              <tbody>
             
				<tr>
                  <td><center><b>BOS Department</b></center></td>
				  <td><center><?php foreach($curriculum_peo_approver as $row)
             {
               
			   echo $row[0]['dept_name'];
 
             }
             ?></center></td>
			     <td><center>
			<?php foreach($clo_po_approver as $row)
             {
               echo $row[0]['dept_name'];
 
             }
             ?></center></td>
			  
				  
                </tr>
				<tr>
                  <td><center><b>Approver</b></center></td>
				  <td><center>  <?php foreach($curriculum_peo_approver as $row)
             {
              echo $row[0]['first_name']." ".$row[0]['last_name'];
 
             }
             ?></center></td>
			<td><center> <?php foreach($clo_po_approver as $row)
             {
              echo $row[0]['first_name']." ".$row[0]['last_name'];
 
             }
             ?></center></td>
			
                </tr>
              </tbody>
			  
            </table>
			
              
			<div class="pull-right">
						<a href="<?php echo base_url('curriculum/curriculum/curriculum_static_index');?>" style="margin-left: 10px;" class="btn btn-danger dropdown-toggle pull-right"><i class="icon-remove icon-white"></i><span></span>Close</a>		
	        </div>
			<br>
				
				</div>
				
			<?php echo form_close();?>
		     
				</div>
				
				</div>
           </div>
           </div>
            <?php  $this->load->view('includes/footer'); ?> 		   
         </body>
         </html>		 