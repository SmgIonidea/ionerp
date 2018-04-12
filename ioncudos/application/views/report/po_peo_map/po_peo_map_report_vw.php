<?php
/**
 * Description	:	List View for PO to PEO Mapped Report Module. Select Curriculum then 
 * 					its related POs and PEOs and their corresponding mapping
 * 					is displayed in a report format which can be exported as a pdf file.
 * Created on	:	03-05-2013
 * Modification History:
 * Date              Modified By           		Description
 * 09-09-2013		Abhinay B.Angadi        Added file headers, indentations & comments.
 * 23-12-2015		Shayista Mulla		Added loading symbol and set cookie.
 * 25-02-2016 		Shayisat Mulla		Added justification data and included justification data pdf
  -------------------------------------------------------------------------------------------
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
	    <!-- Contents -->
	    <div id="loading" class="ui-widget-overlay ui-front">
		<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
	    </div>
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->
		    <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/po_peo_map_report/export'); ?>">
			<input type="hidden" name="doc_type" id="doc_type">
			<div class="navbar">
			    <div class="navbar-inner-custom">
				<?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> to Program Educational Objectives (PEOs) Mapped Report
			    </div>
			    <div class="pull-right">
				<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
			    </div>

			    <div class="pull-right">
				<a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
			    </div>
			</div>
			<div class="row-fluid">
			    <div class="span12">
				<div class="row-fluid">
				    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Curriculum <font color="red"> * </font>
					<select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_mapped_po_peo();">
					    <option value="" selected>Select Curriculum</option>
					    <?php foreach ($results as $listitem): ?>
    					    <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
					    <?php endforeach; ?>
					</select> 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
				    </label>
				    <div id="pdf_data">
					<div class="bs-docs-example">
					    <div id="po_peo_mapped_report_table_id" style="overflow:auto;">
					    </div>
					</div>
					<br>
					<div id="justification_view" style="overflow:auto;">
					</div>
					<br>
				    </div>

				    <div id="pdf_data">
					<!-- <div class="bs-docs-example">
					     <div id="po_peo_mapped_report_table_id" style="overflow:auto;">
					     </div>
					 </div><br>-->

					<div id="individual_justification_view" style="overflow:auto;">
					</div>
					<br>
				    </div>

				    <input type="hidden" name="pdf" id="pdf" />									
				    <br>	
				    <div class="pull-right">
					<a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                                    </div>

				    <div class="pull-right">
					<a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
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
    <script src="<?php echo base_url('twitterbootstrap/js/custom/report/po_peo_map_report.js'); ?>" type="text/javascript"></script>
