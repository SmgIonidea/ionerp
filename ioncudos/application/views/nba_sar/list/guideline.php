    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>

        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/nba_css/nba_css.css'); ?>" />
<div class="row-fluid">
<div class="span3">
</div>
<div class="span6">
	<?php 
		$guide_line = !empty($guide_line)? $guide_line: '';
		$edit_mode = !empty($edit_mode)? $edit_mode: '';
		echo '<input type="hidden" id="is_edit" value="'.$edit_mode.'" />';
		echo form_textarea('guideline',$guide_line,'id="guideline"');
	?>
	<input type='button' class='guideline_save btn-fix btn btn-primary' style="position:relative;top:10px" value='Add' />
</div>
</div>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>	 
<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" src="<?php echo base_url('assets/nba_js/nba_guideline_js.js') ?>"></script>	