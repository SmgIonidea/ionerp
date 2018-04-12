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
 *
  ---------------------------------------------------------------------------------------------- */
?>


<p><b>Vision : </b></p>
<?php if ($dept_info['vision_mission']) { ?>
    <table class="table table-bordered">
        <tr>
            <td width="800" gridSpan='10' colspan="10" style="width: 40px;">

                <?php echo $dept_info['vision_mission'][0]['dept_vision']; ?>

            </td>
        </tr>
    </table>
<?php } ?>

<p><b>Mission : </b></p> 
<?php
if (!empty($dept_info['vision_mission'])) {
    $vision = $dept_info['vision_mission'][0]["dept_mission"];
    if ($vision) {
        ?>
        <table class="table table-bordered">
            <tr>
                <td width="800" gridSpan='10' colspan="10" style="width: 40px;">
                    <?php echo $dept_info['vision_mission'][0]["dept_mission"]; ?>
                </td>
            </tr>
        </table>
        <?php
    }
}
?>

<p><b>Mission Elements: </b></p>
<?php if ($dept_info['mission']) { ?>
    <table class="table table-bordered">
        <?php foreach ($dept_info['mission'] as $mission) { ?>
            <tr>
                <td width="800" gridSpan='10' colspan="10" style="width: 40px;">
                    <?php echo $mission['dept_me']; ?>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
