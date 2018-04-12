<table id="generate_table" class="table table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Curriculum Component</th> 
            <th>Course Type<font color=red>*</font>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $cloneCntr = 0; ?>
        <tr><?php
            for ($i = 0; $i < count($course_type_weightage); $i++) {
                $id = $course_type_weightage[$i]['course_type_id'];
                $cloneCntr++;
                ?>
                <td name="crclm_comp<?php echo $i + 1; ?>" id="crclm_comp<?php echo $i + 1; ?>" style="text-align:center"> <?php echo($crclm_comp_name[$i]) ?></td>
                <td>
                    <select  class="crs_type required" id="course_type_value<?php echo $i + 1; ?>" name="course_type_value<?php echo $i + 1; ?>"  onchange="select_details(this.value,<?php echo $i + 1; ?>);">
                        <option value="<?php echo $course_type_weightage[$i]['course_type_id']; ?>" >
                            <?php echo ucfirst($course_type_name[$i][0]['crs_type_name']); ?>	
                        </option>
                        <option value=''>Select Course Type</option>
                        <?php
                        for ($k = 0; $k < count($course_type); $k++) {
                            if ($course_type[$k]['crs_type_id'] != $course_type_weightage[$i]['course_type_id']) {
                                ?>
                                <option value="<?php echo $course_type[$k]['crs_type_id'] ?>">
                                    <?php echo ucfirst($course_type[$k]['crs_type_name']); ?>
                                </option><?php
                            }
                        }
                        ?>	
                    </select>
                    <span style='position: relative;left: 5px; color:red;' id="error_msg<?php echo $i + 1; ?>"></span> 
                </td>
                <td name="crs_type_desc<?php echo $i + 1; ?>" id="crs_type_desc<?php echo $i + 1; ?>"> <?php echo($crs_type_desc[$i]) ?></td>
                <?php if ($i == 0) { ?> <td></td><?php } else { ?>
                    <td>
                        <a id="remove_field<?php echo $i + 1; ?>" class= "Delete" ><i class='icon-remove' id='icon-remove<?php echo $i + 1; ?>'></i></a>
                    </td><?php }
                ?>
            </tr> <?php }
            ?>
    </tbody>
</table>
<input type="hidden" id="course_count" name="course_count" value="<?php echo count($course_type); ?>" > 
<div class="pull-right">
    <input type="hidden" id="counter" name="counter" value="<?php echo $cloneCntr; ?>"/>	
    <?php if (!empty($imp_count)) { ?> 
        <input type="hidden" name="stack_counter" id="stack_counter" value="<?php echo $imp_count; ?>" />
    <?php } else { ?>
        <input type="hidden" name="stack_counter" id="stack_counter" value="1" />
    <?php } ?>
</div><br><br>
<input type="hidden" id="duplicate" name="duplicate" value="1">
