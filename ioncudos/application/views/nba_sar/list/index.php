<?php
/**
 * Description          :	View for NBA SAR Report.
 * Created              :	
 * Author               :
 * Modification History :
 * Date	                        Modified by                      Description
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div id="loading" class="ui-widget-overlay ui-front">
    <img src="<?php echo base_url('twitterbootstrap/img/loadinggraphic.gif'); ?>" alt="loading" />
</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/nba_css/library/jqueryui/jquery-ui-1.10.2.custom.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/nba_css/nba_css.css'); ?>" />
<div class="row-fluid">
    <form id="description_form" method="post" action="<?php echo base_url(); ?>nba_sar/nba_sar/export" >
        <input type="hidden" name="node_info" id="node_info" value=""/>
        <input type="hidden" name="having_data" id="having_data" value=""/>
        <input type="hidden" name="export_details" id="export_details" value=""/>
        <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $dept ?>" />
        <input type="hidden" name="pgm_id" id="pgm_id"  value="<?php echo $pgm ?>" />
        <input type="hidden" name="nba_report_id" value="<?php echo $node_id ?>" id="node_id" />
        <input type="hidden" name="nba_report_tier_id" value="<?php echo $tier_id ?>" id="tier_id" />
        <input type="hidden" name="co_graph" value="" id="co_graph" />
        <input type="hidden" name="bloom_graph" value="" id="bloom_graph" />
    </form>
    <div class="span12 pull-right" style="margin-bottom:10px">	
        <a href="<?php echo base_url('nba_sar/nba_list'); ?>"><button class="btn-fix btn btn-primary pull-right"><i class="icon-arrow-left"></i></button></a>
        <button type='button' disabled='disabled' class='description_save btn-fix btn btn-primary pull-right'><i class="icon-file icon-white"></i> Save</button>
        <button type="submit" disabled='disabled' id="export_node" class="export btn-fix btn pull-right btn-success" abbr="0"><i class="icon-book icon-white"></i> Export .doc</button>
        <!--<input type="submit" class="export btn-fix btn pull-right" abbr="1" value="Export All" />-->
    </div>
    <div class="span12 pull-right">			
        <div class="span3 well" style="position:relative; left:5px; height: 500px; overflow-y: auto; overflow-x: hidden;">
            <input type="text" value="" style="box-shadow:inset 0 0 4px #eee;margin: 0 5px 15px; padding:6px 45px 6px 12px; border-radius:4px; border:1px solid silver; font-size:1.1em;" id="search_query" placeholder="Search">
            <div id="nba_sar"></div>
        </div>
        <div class="span9 well view_div" style="display:none">
            <form id="view_form" method="post" action="<?php echo base_url(); ?>nba_sar/nba_sar/export" >
                <div id="view"></div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="nba_sar_id" />
<input type="hidden" id="get_baseurl" value="<?php echo base_url(); ?>"/>

<div id="nba_modal_1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="" data-backdrop="static" data-keyboard="true">
    <div class="modal-header">
        <div class="navbar">
            <div class="navbar-inner-custom">
                Edit Guideline
            </div>
        </div>
    </div>
    <div class="modal-body">
        <!--<input type="hidden" id="nba_input_data" name="nba_input_data" value="" />
        <!--<textarea name="nba_standard_content" class="standard_content_input" ></textarea>remove -->
        <div id="data-container"></div>
    </div>
    <div id ="modal_footer" class="modal-footer">
        <!--<input type='button' attr="1" class='standard_content_save btn-fix btn btn-primary' value='Save as NBA_SAR Description' />-->
        <?php if ($this->ion_auth->is_admin()) { ?>
            <button type='button' attr="2" class='standard_content_save btn-fix btn btn-primary'><i class="icon-file icon-white"></i> Save Guideline</button>
        <?php } ?>
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i>  Close </button>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
<?php $this->load->view('partials/footer'); ?>
<?php
$data['nba_sar'] = 1;
$this->load->view('includes/js', $data);
?>
<script type="text/javascript" src="<?php echo base_url('assets/nba_js/library/jquery-ui-1.10.2.custom.min.js') ?>"></script>	 
<script type="text/javascript" src="<?php echo base_url('assets/nba_js/nba_js.js') ?>"></script>	
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.jqplot.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jqplot.pieRenderer.js') ?>"></script>
<?php echo empty($include_js) ? '' : $include_js; ?>
<!-- End of file index.php 
        Location: .nba_sar/list/index.php -->