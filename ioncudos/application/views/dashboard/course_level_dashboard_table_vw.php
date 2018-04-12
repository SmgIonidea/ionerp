<br>			
<div class="navbar">
    <div class="navbar-inner-custom">
        Curriculum Course Level Summary
    </div>
</div>	
<table id="table1" name="table1" class="table table-bordered" style="width:100%">
    <thead>
		<tr>
			<center>
			<h4><font color="#5E676B" >Course Level State Table</font></h4>
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
                    <label><b><font color="blue"><?php echo $term['term_name']; ?></font></b></label> 
            </td></b>
        </tr>
        <?php foreach ($course_list as $crs): ?>
            <tr>
                <?php
                if ($term['crclm_term_id'] == $crs['crclm_term_id']) {
                    ?>
                    <td style=" vertical-align:justify; relative;">
                        <?php echo $i . ". " . $crs['crs_title'] . " (" . $crs['crs_code'] . ") "; ?>
                    </td>
                    <td  style="vertical-align:justify; relative;">
                        <?php
                        echo $crs['description'];
                        ?> 
                    </td>
                    <td  style="vertical-align:justify; relative;">
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