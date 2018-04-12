<?php ?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/nba_css/library/jqueryui/jquery-ui-1.10.2.custom.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/nba_css/nba_css.css'); ?>" />
<div class="container-fluid">
    <div class="row-fluid">
	<!--sidenav.php-->
	<?php $this->load->view('includes/sidenav_3'); ?>
	<div class="span10">
	    <!-- Contents -->
	    <div id="loading" class="ui-widget-overlay ui-front">
		<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
	    </div>
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->
		    <form target="_blank" name="view_form" id="view_form" method="POST" action="<?php echo base_url('report/co_po_matrix/export_to_pdf'); ?>">
			<div class="navbar">
			    <div class="navbar-inner-custom">
				Program Level Course - PO Matrix Report
			    </div>
			</div>

			<div class="pull-right">
			    <a id="export" href="#" class="btn btn-success"><i class= "icon-book icon-white"></i>Export .pdf</a>   
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<div>
				    <div class="row-fluid">
					<table style="width:70%">
					    <tr>
						<td>
						    Curriculum:<font color="red"> * </font><br>
						    <select size="1" id="curriculum_list_cos" name="curriculum_list__38_4" autofocus = "autofocus" aria-controls="example" onChange = "">
							<option value="" selected>Select Curriculum</option>
							<?php foreach ($curriculum_list as $listitem): ?>
    							<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
							<?php endforeach; ?>
						    </select>
						</td>
					    </tr>
					</table>
					<div class="span10">
					    <div id="main_table" class="bs-docs-example">
						<div id="course_articulation_matrix_grid">
						    <div id="view">
							<div class="row-fluid">
							    <div class="span12 cl">
								<b>Course list :-</b>
							    </div>
							</div>
							<div id="co_vw_id">

							</div>
							<input class="generate_report_flag" value="1" type="hidden" />
						    </div>
						    <div style="margin-bottom:50px;">
							<input type="button" id="generate_clo" class="btn btn-success pull-right" value="Generate Report"/>
						    </div>
						    <div id="co_matrix_data"></div>
						</div>
					    </div>
					</div><!-- span8 ends here-->
				    </div>

				    <input type="hidden" name="pdf" id="pdf" />
				    <input type="hidden" name="stmt" id="stmt" />
				    <input type="hidden" name="curr" id="curr"/>
				    <input type="hidden" name="term_name" id="term_name"/>
				    <br>
				    <div class="pull-right">
					<a id="export" href="#" class="btn btn-success"><i class= "icon-book icon-white"></i>Export .pdf</a>
				    </div>
				    </form>
				</div>
			    </div>
			</div>
		</div>
		<!--Do not place contents below this line-->
	    </section>			
	</div>					
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/report/co_po_matrix.js'); ?>" ></script>
