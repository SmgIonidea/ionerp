<?php
/**
 * Description	:	To display, textbook and reference book 

 * Created		:	 August 19th, 2016

 * Author		:	 Bhgayalaxmi S S

 * Modification History:
 *   Date                Modified By                         Description
   
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<style>
     .noborder
      {
        border:none;
      }
    </style>
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
				<form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/text_reference_book_list/export_pdf'); ?>">					
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_pgm_artmtrx_rprt">
                                Text Book / Reference Book List
                            </div>
                        </div>						
						<table style="width:100%;">
							<tr>
								<!--<td>Curriculum : </td><td><select><option>a</option></select></td>-->
								<td style="width:40%;" ><span data-key="lg_crclm">Curriculum:<font color="red"> * </font></span>
									<select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();
										">
									<option value="" selected data-key="lg_sel_crclm">Select Curriculum</option>
									<?php foreach ($results as $listitem): ?>
										<option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
									<?php endforeach; ?>
									</select> 
                                </td>
								 <td style="width:40%;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 <span data-key="lg_term">Term:<font color="red"> * </font></span>
									<select size="1" id="term" name="term" aria-controls="example" onChange = "fetch_syllabus()">
										<option>Select Term</option>
									</select> 
								</td>
								<td style="width:20%;">
								<div class="pull-right">
										<a id="export_top" href="#" onclick="generate_pdf()" class="btn btn-success"><i class= "icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span></a> 
								</div>
								</td>
							</tr>
						</table><br/>
						
						<!--<div class="span12" id="details"><?PHP echo str_repeat("&nbsp;", 10);?><div id="crclm_name" class="span4"></div><div  id="term_name" class="span6"></div></div>-->
						<div id="syllabus_list"></div>
						<br/><div class="pull-right">
							<a id="export_bottom" href="#" onclick="generate_pdf()"  class="btn btn-success"><i class= "icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span></a> 
                        </div><br/>
						
						<input type="hidden" id="pdf" name="pdf"/>
						<input type="hidden" id="crclm_name" name="crclm_name"/>
						<input type="hidden" id="term_name" name="term_name"/>
					</form>
				</div>
				
			</section>
		</div>
	</div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->

<?php $this->load->view('includes/js'); ?>

<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/report/text_reference_book_list.js'); ?>" ></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

