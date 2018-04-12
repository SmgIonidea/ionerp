<table id="publish_table" name="publish_table" class="table table-bordered" style="width:100%">
    <thead>
		<tr>
			<center>
			<h4><font color="#5E676B" >Termwise Publish Summary</font></h4>
			</center>
		</tr>
		<th> <font color="#8E2727">Term Name / Courses Defined</font> </th>
		<th> <font color="#8E2727">Current Status Description</font> </th>
		<th> <font color="#8E2727">Course Owner</font> </th>
	</thead>
	<tbody>
   <?php foreach ($term_list as $term): $i = 1; ?>				
        <tr>
            <td colspan="9" id="<?php echo $term['crclm_term_id']; ?>"><b>
                    <label class="pull-left"><b><font color="blue"><?php echo $term['term_name']; ?></font></b></label>
			<input name="crclm_term_id[]" class="crclm_term_id" value="<?php echo $term['crclm_term_id']; ?>" type="hidden" />
			<?php if ($term['publish_flag'] == 1) { ?>
				<b style="width: 10px; color: #E67A17"> : Term is Ready for Delivery Planning Publish</b>
				<?php } elseif ($term['publish_flag'] == 2) { 	?>
				<b style="width: 10px; color: #278e27"> : Term is Published for Delivery Planning</b>
			<?php } else { 	?>
				<b style="width: 10px; color: #8E2727"> : Term is Pending Publish for Delivery Planning</b>
			<?php } ?>
            </td></b>
        </tr>
        <?php foreach ($course_list as $crs): ?>
            <tr>
                <?php
                if ($term['crclm_term_id'] == $crs['crclm_term_id']) {
                    ?>
                    <td style=" vertical-align:justify;"  relative;>
                        <?php echo $i . ". " . $crs['crs_title'] . " (" . $crs['crs_code'] . ") "; ?>
                    </td>
                    <td  style="vertical-align:justify;"  relative;>
                        <?php
                        echo $crs['description'];
                        ?> 
                    </td>
                    <td  style="vertical-align:justify; " relative;>
                        <?php
                        echo $crs['first_name'] . " " . $crs['last_name'];
                        ?> 
                    </td>
                    <?php
                    $i++;
                }
                ?>
            </tr>
        <?php endforeach; ?>

    <?php endforeach; ?>	
</tbody>
</table>