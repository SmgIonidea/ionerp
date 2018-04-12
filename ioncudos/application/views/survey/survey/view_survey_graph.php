<?php
/**
 * Description	:	Graph display for survey
 * Created on	:	24-12-2014
 * Author		: 	Jyoti
 * Modification History:
 * Date                Modified By           Description

  ------------------------------------------------------------------------------------------------------------
 */
?>
<div class="container-fluid">
<!--<style>
progress,          /* All HTML5 progress enabled browsers */
progress[role]     /* polyfill */
{

        /* Turns off styling - not usually needed, but good to know. */
        appearance: none;
        -moz-appearance: none;
        -webkit-appearance: none;

        /* gets rid of default border in Firefox and Opera. */ 
        border: none;

        /* Needs to be in here for Safari polyfill so background images work as expected. */
        background-size: auto;
        
        /* Dimensions */
        width: 400px;
        height: 60px;
        
}

/* Polyfill */
progress[role]:after {
        background-image: none; /* removes default background from polyfill */
}

/* Ensure fallback text doesn't appear in polyfill */
progress[role] strong {
        display: none;
}
progress,                          /* Firefox  */ 
progress[role][aria-valuenow] {    /* Polyfill */
   background: #white !important; /* !important is needed by the polyfill */
}

/* Chrome */
progress::-webkit-progress-bar {
    background: white;
}
/* IE10 */
progress {
    color: #60AFFE;
}

/* Firefox */
progress::-moz-progress-bar { 
    background:  #60AFFE;	
}

/* Chrome */
progress::-webkit-progress-value {
    background:  #60AFFE;
}

/* Polyfill */
progress[aria-valuenow]:before  {
    background:  #60AFFE;
}
</style>-->
    <div class="row-fluid">
        <!--sidenav.php-->
        <div class="span12"> 
            <!-- Contents -->
            <section id="contents">
                <form target="_blank" name="view_survey_graph_form" id="view_survey_graph_form" method="POST" action="<?php echo base_url('survey/surveys/export_reports_pdf'); ?>">
                    <!--content goes here-->
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <?php
                                    $graphTitle = $survey_name." - ".$survey_for." Analysis";
                                    echo "<input type='hidden' id='graphTitle' value='".$graphTitle."' />";
                                ?>
                                <!-- Span6 starts here-->
                                <div class="control-group" id="survey_information">
                                    <h5><center>Survey Details</center></h5>
                                     <div class="bs-docs-example">
                                         <table style="width: 100%;text-align: center;" class="table table-bordered">
                                        <tr>
                                            <td><b style="color:blue;"> Survey: </b><?php echo $survey_name; ?></td>
                                            <td><b style="color:blue;"> Survey Type: </b><?php echo $survey_type_name; ?></td>
                                            <td><b style="color:blue;"> Survey For: </b><?php echo $survey_for; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b style="color:blue;"> Department: </b><?php echo $dept; ?></td>
                                            <td><b style="color:blue;"> Program: </b><?php echo $pgm; ?></td>
                                            <td><b style="color:blue;"> Target Value: </b><?php echo $threshold_value; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><b style="color:blue;">Report Summary:</b>
                                                Number of people Responded / Total number of  Responders: <b><?php echo $responded." / "; ?><?php echo $totalResp; ?></b> People
                                            </td>
                                        </tr>
                                    </table>
                                     </div>
                                </div>
                                <br />
                                <div id="attainment_report_types">
                                    <b  style="color:blue;">Report Type:<font color="red"> * </font></b> 
                                    <?php if ($survey_type=='16'){ ?>
                                    <input type="radio" name="report_type" id="report_type" value="3" class="report_type_class">&nbsp;&nbsp; Attainment Report
                                    <?php } ?>&nbsp;&nbsp;
                                    <input type="radio" name="report_type" id="report_type" value="1" class="report_type_class" >&nbsp;&nbsp; Quantitative Reports &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                                    <input type="radio" name="report_type" id="report_type" value="2" class="report_type_class">&nbsp;&nbsp; Detailed Reports
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    
                                </div><br /><br />
                                <div id="actual_data" style="height:auto;">

                                </div>
                                <div id="graph_val">

                                </div>
                            </div>
                            <input type="hidden" name="survey_id" id="survey_id" value="<?php echo $survey_id; ?>" />
                            <input type="hidden" name="su_for" id="su_for" value="<?php echo $su_for; ?>" />
                            <input type="hidden" name="survey_report_hidden" id="survey_report_hidden" value="" />
                        </div>

                        <div class="pull-right">
                            <br>
                            <a id="export" class="btn btn-success"><i class="icon-book icon-white" ></i>Export </a>
                            <a href="<?php echo base_url('survey/reports/hostedSurvey'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i><span></span> Back </a>
						</div>
						<div id="graph_detail_report" style="display:none;">
						</div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
