<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
	* 
	* Description	:	Graph display for survey dashboard
	* Created on	:	08-01-2015
	* Author		: 	Jyoti
	* Modification History:
	* Date                Modified By           Description

------------------------------------------------------------------------------------------------------------
*/
?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php
        <div class="span10"> 
			<!-- Contents 
            <section id="contents">-->
				<form target="_blank" name="view_survey_dashboard_form" id="view_survey_dashboard_form" method="POST" action="">
				<!--content goes here
					<div class="row-fluid">
						<div class="span12">-->
                                                    <div class="span4">
							<label>
							<b> Year :<font color="red"> * </font> </b>
							<select name="survey_year" id="survey_year">
								<option value="">Select Year:</option>
								<?php foreach($years as $years) { ?>	
								<option value="<?php echo $years['Year'] ?>"><?php echo $years['Year'] ?></option>							
								<?php }	?>
							</select>
							</label>
                                                    </div>
                                                    <div class="span6">
                                                        <label>
                                                            <b>Department Name:<font color="red"> * </font></b>
                                                       
                                                        
                                                            <?php echo form_dropdown('department', @$departments, (set_value('department')) ? set_value('department') : '0', "id='department' class='remove_err'"); ?>
                                                            <span id="errorspan_department" class="error help-inline"></span>
                                                        </label>                           
                                                    </div>
                                <div class="span2"></div>
							<div class="row-fluid">
								<div class="span12">
									<div class="span6">
										<div id="pie_chart"  class="jqplot-target">

										</div>
									</div>
                                                                    <div class="span1"></div>
									<div class="span4">
										<div id="survey_list">
										
										</div>
									</div>
                                                                    <div class="span1"></div>
								</div>
                                                            <div class="span12">
                                                                <div class="span6">
										<div id="survey_list"  class="survey_list1">

										</div>
									</div>
                                                                <div class="span6"></div>
                                                            </div>
							</div>	
							<!--<div class="row-fluid">
								<div class="span12">
									
										<div id="pie_chart"  class="jqplot-target">

										</div>
									
								</div>
							</div>	
							<div class="row-fluid">
								<div class="span10">
									
										<div id="stacked_bar_chart" class="jqplot-target">
										
										</div>
									
								</div>
							</div>	-->
						<!--</div>
					</div>-->
				</form>
			<!--</section>
		</div>-->
	</div>
</div>
     