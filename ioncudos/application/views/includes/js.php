<?php
/**
* Description	:	Generic include View file of the application contains all JS files
*					
*Created		:	25-03-2013. 
*		  
* Modification History:
* Date				Modified By				Description
*
* 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.   
----------------------------------------------------------------------------------------
*/
?>
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	
	<script src="<?php echo base_url('twitterbootstrap/js/setup.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/widgets.js');?>"></script>
	<?php if(empty($nba_sar)) {
			echo '<script src="'.base_url("twitterbootstrap/js/jquery.js").'"></script>';
		}
	?>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-transition.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/bootstrap-alert.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-modal.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-dropdown.js'); ?>"></script>	
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-scrollspy.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-tab.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-tooltip.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-popover.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-button.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-collapse.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-carousel.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-typeahead.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap-affix.js'); ?>"></script>
    <!--<script src="<?php echo base_url('twitterbootstrap/js/bootstrap-holder.js'); ?>"></script> -->
	<script src="<?php echo base_url('twitterbootstrap/js/jquery.resizeimagetoparent.js'); ?>"></script>
	
	
	<!--Datepicker-->
	<script src="<?php echo base_url('twitterbootstrap/js/bootstrap-datepicker.js'); ?>"></script>
	
	<!--<script src="<?php echo base_url('twitterbootstrap/js/bootstrap.min.js'); ?>"></script>
	<!-- Ends-->
	<script src="<?php echo base_url('twitterbootstrap/js/holder/holder.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.js'); ?>"></script>
    <script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/grid.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/jquery.validate.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/additional-methods.js'); ?>"></script>	
	<!--<script src="<?php echo base_url('twitterbootstrap/js/jquery.min.js'); ?>"></script>-->
	<!--<script src="<?php echo base_url('twitterbootstrap/js/jquery-ui.js'); ?>"></script>-->
	<script src="<?php echo base_url('twitterbootstrap/js/jquery.cookie.js'); ?>"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/generic.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/jquery_data_tables_numeric-comma.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('twitterbootstrap/js/jquery_data_tables_natural.js'); ?>" type="text/javascript"></script>
	
	<!--localization-->
	<script src="<?php echo base_url('twitterbootstrap/js/custom/locale/locale_lang.js'); ?>" type="text/javascript"></script>