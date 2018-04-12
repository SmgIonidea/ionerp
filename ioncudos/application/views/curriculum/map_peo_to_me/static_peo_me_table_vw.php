<?php
/**
* Description	:	View Logic for PEOs to MEs Mapping Module.
* Created		:	22-12-2014 
* Modification History:
* Date				Modified By				Description
* 27-12-2014		Jevi V. G.        Added file headers, public function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
?>
<table class="table table-bordered table-hover " id="peomeList" aria-describedby="example_info" style="font-size:12px;">
    <thead align="center">
        <tr>
            <th class="sorting1" rowspan="1" colspan="1" style="width: 30px; color:maroon; white-space:nowrap;"> Program Educational Objectives  (PEOs) /<br> Mission Elements (MEs) </th>
            <?php $cntr=1; foreach ($me_list as $me): ?>
                <th class="sorting1" rowspan="1" colspan="1" style="width: 10px; color:maroon;" onmouseover="writetext2('<?php echo trim ($me['dept_me']); ?>');" id="<?php echo $me['dept_me']; ?>"><?php echo 'ME'.$cntr; ?></th>
            <?php $cntr++; endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($peo_list as $peo): ?>
            <tr id="<?php echo $peo['peo_id']; ?>">
                <td>
                    <p><br><?php echo $peo['peo_statement'] ?></p>
                </td>
                <?php foreach ($me_list as $me): ?>
                    <td id="<?php echo $peo['peo_id']; ?>" 
                        class="mecol<?php echo $me['dept_me_map_id']; ?>">
                        <br>
                        <input
                            type="checkbox" 
                            name='me[]'  
                            value="<?php echo $me['dept_me_map_id'] . '|' . $peo['peo_id'] ?>"  
                            
                            onmouseover="writetext2('<?php echo trim($me['dept_me']); ?>');" 
							onmouseout="textout2();"
                            disabled="disabled"
                            <?php
                            foreach ($mapped_peo_me as $map_list): {
                                    if ($map_list['me_id'] == $me['dept_me_map_id'] && $map_list['peo_id'] == $peo['peo_id']) {
                                        echo 'checked = "checked"';
                                    }
                                }
                            endforeach;
                            ?>																
                            /> 
                    </td>
				<?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
    </tbody>
</table>