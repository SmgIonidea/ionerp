<?php
/**
 * Description	:	List View for Program Outcomes(POs) Type Module.
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 03-09-2013		Abhinay B.Angadi        Added file headers, indentations.
 * 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  --------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<?php
$this->load->view('includes/branding');
?>
<!-- Navbar here -->
<?php
$this->load->view('includes/navbar');
?> 
<div class="container-fluid">
    <div class="row-fluid">
	<!--sidenav.php-->
	<?php $this->load->view('includes/sidenav_1'); ?>
	<div class="span10">
	    <!-- Contents
================================================== -->
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->	
		    <div class="navbar">
			<div class="navbar-inner-custom">
			    Graduate Attributes (GAs) List
			</div>
		    </div>	
		    <div class="row pull-right">   
			<a href="<?php echo base_url('configuration/graduate_attributes/ga_add_record'); ?>" align="right">
			    <button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> 
				</i> Add</button>
			</a>
		    </div>
		    <br><br>
		    <div id="example_wrapper" class="dataTables_wrapper" role="grid">
			<table class="table table-bordered table-hover " id="example" aria-describedby="example_info">
			    <thead >
				<tr class="gradeU even" role="row">
				    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Sl No.</th>
				    <th class="header headerSortDown span3" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Graduate Attribute Statement</th>
				    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending">Graduate Attribute Description</th>

				    <th class="header span1" role="columnheader" tabindex="0" aria-controls="example">Edit
				    </th>
				    <th class="header span1" role="columnheader" tabindex="0" aria-controls="example">Delete
				    </th>
				</tr>
			    </thead>
			    <tbody role="alert" aria-live="polite" aria-relevant="all">
				<?php foreach ($records as $records): ?>
    				<tr>
    				    <td class="sorting_1 table-left-align"><?php echo $records['ga_reference']; ?>
    				    </td> 
    				    <td class="sorting_1 table-left-align"><?php echo $records['ga_statement']; ?>
    				    </td>  
    				    <td class="sorting_1 table-left-align"><?php echo $records['ga_description']; ?></td>
    				    <td> <a class="" href="<?php echo base_url('configuration/graduate_attributes/ga_edit_record') . '/' . $records['ga_id']; ?>">
    					    <i class="icon-pencil icon-black"> </i></a></td>

					<?php
					if ($records['is_ga'] == 0) {
					    ?>
					    <td>
						<div id="hint">
						    <center>
							<a href = "#myModaldelete" id="<?php echo $records['ga_id']; ?>" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" 
							   title="Delete" 
							   value="<?php echo $records['ga_id']; ?>">
							</a>
						    </center>
						</div>
					    </td>
					    <?php
					} else {
					    ?>

					    <td>
						<div id="hint">
						    <center>
							<a href = "#cant_delete" class=" get_id icon-remove" data-toggle="modal" data-original-title="Delete" rel="tooltip" title="Delete">
							</a>
						    </center>
						</div>
					    </td>
					<?php } ?>

    				</tr>
				<?php endforeach; ?>	
			    </tbody>
			</table>
			<br><br><br>
			<div class="pull-right">   
			    <a href="<?php echo base_url('configuration/graduate_attributes/ga_add_record'); ?>" >
				<button type="button" class="btn btn-primary"><i class="icon-plus-sign icon-white"> 
				    </i> Add</button>
			    </a>
			</div><br>	
		    </div>	
		    <!-- Modal -->
		    <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
			<div class="modal-header">
			    <div class="navbar-inner-custom">
				Delete Confirmation 
			    </div>
			    <div class="modal-body">
				<p>Are you sure you want to Delete?</p>
			    </div>
			    <div class="modal-footer">
				<button class="delete_ga btn btn-primary "  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
				<button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
			    </div>
			</div>		
		    </div>

		    <div id="ga_name_exists" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
			<div class="modal-header">
			    <div class="navbar-inner-custom">
				Warning
			    </div>
			</div>
			<div class="modal-body">
			    <p>The Graduate Attribute Name already exists. </p>
			</div>
			<div class="modal-footer">
			    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
			</div>
		    </div>

		    <div id="cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
			<div class="modal-header">
			    <div class="navbar-inner-custom">
				Warning 
			    </div>
			</div>
			<div class="modal-body">
			    <p>You cannot delete this Graduate Attribute, as it has been assigned to a <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>. </p>
			</div>
			<div class="modal-footer">
			    <button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
			</div>
		    </div>  
		    <br>			


		    <!--Do not place contents below this line-->	
		    </section>	
		</div>
	</div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/ga.js'); ?>" type="text/javascript"></script>