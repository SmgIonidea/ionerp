<?php
/**
 * Description	:	Course Learning Outcome grid provides the list of course learning
					Outcome statements along with edit and delete options

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 15-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
 ----------------------------------------------------------------------------------------------*/
?>

<div id="table_view" class>
	<div id="add_clo"> 
		<div id="add_me" class="bs-docs-example">
			<div class="control-group input-append">
				<label class="control-label" for="clo_statement_1"> Course Outcome (CO) Statement: <font color="red"> * </font></label>
				<div class="controls ">
					 <textarea name="clo_statement_1" class="clo_stmt required" autofocus="autofocus" cols="10"  id="clo_statement_1" rows="3" type="text" style="margin-left: 0px; margin-right: 0px; width: 834px;"></textarea>
					 <button id="clo_btn_1" class="btn btn-primary add_clo" type="button"><i class="icon-plus-sign icon-white"></i>  Add More COs</button><br><span for="clo_btn_1"></span>
				</div>
			</div>	
		</div>						
	</div>
</div>

<div id="add_aft">
</div>
<!--<input type="hidden" name="counter" id="counter" value="1">-->			
<!-- End of file clo_table_vw.php 
                        Location: .curriculum/clo/clo_table_vw.php -->