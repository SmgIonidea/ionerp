<?php
/**
 * Description	:	Display list of curriculum and view the curriculum vision,mission,peo an s po
 * 					
 * Created		:	29-06-2015
 *
 * Author		:	Jyoti
 * 		  
 * Modification History:
 *    Date               Modified By                			Description
 * 23-12-2015		Shayista Mulla 				Added loading symbol and cookie.
  ---------------------------------------------------------------------------------------------- */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
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
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <form class="form-horizontal" method="POST" target="_blank" id="list_curriculum_details" name="list_curriculum_details" action="<?php echo base_url('curriculum/curriculum_details/export'); ?>">
                        <input type="hidden" name="doc_type" id="doc_type">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Department / Curriculum (Regulation) Details List
                            </div>
                            <div class="pull-right">
                                <a id="export" href="#" class="btn btn-success export_doc"><i class="icon-book icon-white"></i> Export .pdf </a>
                            </div>

                            <div class="pull-right">
                                <a id="export_doc" href="#" class="btn btn-success export_doc" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                            </div>
                        </div>
                        <div>
                            <label>
                                Curriculum:<font color="red"> * </font>
                                <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange="curriculum_details();">
                                    <option value="" selected> Select the Curriculum </option>
                                    <?php foreach ($results as $list_item): ?>
                                        <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                    <?php endforeach; ?>
                                </select>

                            </label>
                        </div>

                        <div id="curriculum_details_div"></div><br />
                        <div class="pull-right">
                            <a id="export" href="#" class="btn btn-success export_doc"><i class="icon-book icon-white"></i> Export .pdf </a>
                        </div>

                        <div class="pull-right">
                            <a id="export_doc" href="#" class="btn btn-success export_doc" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                        </div> <br/>
                        <input type="hidden" name="curriculum_details_div_hidden" id="curriculum_details_div_hidden" />
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum_details.js'); ?>" type="text/javascript"></script>

<!-- End of file list_peo_vw.php 
Location: .curriculum/peo/list_peo_vw.php -->
