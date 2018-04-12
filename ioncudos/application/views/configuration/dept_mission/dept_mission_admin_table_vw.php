<?php
/**
 * Description           :	View for Department Mission Module.
 * Created		 :	27-12-2014 
 * Modification History:
 * Date			Modified By				Description
 * 27-12-2014		Jevi V. G.                  Added file headers, public function headers, indentations & comments.
  ---------------------------------------------------------------------------------------- */
?>

<div id="dept_mission_vw_id" name="dept_mission_vw_id" class="table table-bordered" style="width:100%">
    <section id="contents">
        <div class="bs-docs-example">
            <!--content goes here-->			
            <form class=" form-horizontal" method="POST" id="add_form" name="add_form">				
                <div class="control-group">
                    <p class="control-label" for="vision"><b>Organization Vision: &nbsp;&nbsp;&nbsp;</b>
                        <!--<div class="controls">-->
                        <textarea style="width: 85%; rows:2; cols:100;" readonly><?php echo $vision['value']; ?></textarea> </p>
                    <!--</div>-->
                </div>

                <div class="control-group">
                    <p class="control-label" for="mission"><b>Organization Mission: &nbsp;</b>
                        <!--<div class="controls">-->  
                        <textarea style="width: 85%; rows:2; cols:100;" readonly><?php echo $mission['value']; ?></textarea> </p>
                    <!--</div>-->
                </div>

                <div class="control-group">
                    <p class="control-label" for="dept_vision"><b>Department Vision: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                        <!--<div class="controls">-->
                        <textarea style="width: 85%; rows:2; cols:100;" readonly><?php echo $dept_vision['value']; ?></textarea> </p>
                    <!--</div>-->
                </div>

                <div class="control-group">
                    <p class="control-label" for="dept_mission"><b>Department Mission: &nbsp;&nbsp;&nbsp;</b>
                        <!--<div class="controls">-->
                        <textarea style="width: 85%; rows:2; cols:100;" readonly><?php echo $dept_mission['value']; ?></textarea> </p>
                    <!--</div>-->
                </div>

                <?php
                $mission_counter = 1;
                $count = 0;
                ?>
                <p class="control-label" for="dept_mission_element"><b>Mission Elements: </b></p>
                <?php
                foreach ($missions as $me) {
                    $missions['value'] = $me['dept_me'];
                    ?>	

                    <div class="control-group ">
                        <div class="controls">
                            <?php if ($me['dept_me'] != '0') { ?>
                                <div name = "mission_element_<?php echo $mission_counter; ?>" id = "mission_element_<?php echo $mission_counter; ?>" class=	"noSpecialChars" readonly>ME - <?php echo $me['dept_me']; ?></div>
                            <?php } else { ?>
                                <div name = "mission_element_<?php echo $mission_counter; ?>" id = "mission_element_<?php echo $mission_counter; ?>" class="noSpecialChars"></div>
                            <?php } ?>
                        </div>
                    </div>

                    <?php
                    $mission_counter++;
                    $count++;
                }
                ?>
                <br>
            </form>
            <div id="error_message" style="color:red">
            </div>
            <!--Do not place contents below this line-->
        </div>

</div>