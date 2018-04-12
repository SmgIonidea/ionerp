<?php
/**
 * Description	:	List View for Course Stream Report Module. 
 * Created on	:	03-05-2013
 * Modification History:
 * Date                Modified By           		Description
 * 12-09-2013		Abhinay B.Angadi        Added file headers, indentations & Code cleaning.
 * 23-12-2015		Shayista Mulla		Added loading symbol and cookies.
  -----------------------------------------------------------------------------------------------
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
	<?php $this->load->view('includes/sidenav_3'); ?>
	<div class="span10">
	    <!-- Contents-->
	    <div id="loading" class="ui-widget-overlay ui-front">
		<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
	    </div>
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->
		    <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/crs_domain_report/export_pdf'); ?>">
			<div class="navbar">
			    <div class="navbar-inner-custom" data-key="lg_crs_strm_rprt">
				Course Stream (Domain) Report
			    </div>
			    <div class="pull-right">
				<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span> </a>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<div class="row-fluid">
				    <p class="control-label" for="pgm_title"><span data-key="lg_department">Department</span>: <font color='red'>*</font>
					<select size="1" id="dept" name="dept" autofocus = "autofocus" aria-controls="example" onChange = "fetch_curriculum();">
					    <option value="Curriculum" selected data-key="lg_sel_dept">Select Department</option>
					    <?php foreach ($results as $listitem): ?>
    					    <option value="<?php echo $listitem['dept_id']; ?>"> <?php echo $listitem['dept_name']; ?> </option>
					    <?php endforeach; ?>
					</select> 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<span data-key="lg_crclm">Curriculum</span>: <font color='red'>*</font>
					<select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "display_table(); fetch_crclm();">
					</select> 
				    </p>
				    <div class="bs-docs-example">
					<div id="crs_stream_report_table_id" style="overflow:auto;">
					</div>
				    </div>
				    <input type="hidden" name="pdf" id="pdf" />
				    <input type="hidden" name="curr" id="curr"/>
				    <br>	
				    <div class="pull-right">
					<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span> </a>
				    </div>
				    </form>
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
    <script src="<?php echo base_url('twitterbootstrap/js/custom/report/crs_domain_report.js'); ?>" type="text/javascript"></script>
