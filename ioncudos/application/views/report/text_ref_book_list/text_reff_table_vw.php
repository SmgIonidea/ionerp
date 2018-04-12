

<?php $i=0; $j=1;  ?>
	
	<table class="table table-bordered table-hover" id="example1" style="font-size:12px;">	
		<?php if(!empty($data)){ ?>
			<?php foreach($data as $d) {?>	
			
			<tr>
				<?php if($i % 2 == 0)  { ?>
				<td align="right" style="width:50px;" rowspan=2;><?php echo str_repeat("&nbsp;", 5); echo "(".$j .") " ;?></td>
				<td >				
					<?PHP str_repeat("&nbsp;", 0);?><b><font color="blue" size="2px"><?php echo $d['crs_title']."-".$d['crs_code']." :"?></font></b>				
				</td> 
				<?php $j++; } else{?>
				<td class="border_remove" style="width:25px;border-top-color: #f5f3f3;" rowspan=2;></td>
				<?php }++$i; ?>
			</tr>
			<tr>
			<td class="border_remove" style=" border-top-color: #f5f3f3;" >
			<?php if($d['book_type'] == 0){?>					
				 <?php echo str_repeat("&nbsp;", 0);?> <b><font color="black" size="2px">Text Book(s) : </font></b>
						<div style=" padding-left: 60px;"> <?php { echo nl2br($d['book_list']); }?> </div>						
			<?PHP } ?>
	
			<?PHP if($d['book_type'] == 1) {?>
				<?PHP echo str_repeat("&nbsp;", 0);?> <b><font color="black" size="2px">Reference Book(s) : </font></b>
					<div style=" padding-left: 60px;"> <?php { echo nl2br($d['book_list']); }?></div>						
			<?PHP } ?>
			</td>
			</tr>
			<?php } ?>
		<?php } else {?>
		<tr><td><b>No Data To Display.</b></td></tr>
		<?php }?>
	</table>
