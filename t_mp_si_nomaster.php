<?php

// id
// grado_4_Si
// grado4_No
// grado_2_Si
// grado_2_No

?>
<?php if ($t_mp_si_no->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_mp_si_no->TableCaption() ?></h4> -->
<table id="tbl_t_mp_si_nomaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_mp_si_no->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_mp_si_no->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $t_mp_si_no->id->FldCaption() ?></td>
			<td<?php echo $t_mp_si_no->id->CellAttributes() ?>>
<span id="el_t_mp_si_no_id">
<span<?php echo $t_mp_si_no->id->ViewAttributes() ?>>
<?php echo $t_mp_si_no->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_mp_si_no->grado_4_Si->Visible) { // grado_4_Si ?>
		<tr id="r_grado_4_Si">
			<td><?php echo $t_mp_si_no->grado_4_Si->FldCaption() ?></td>
			<td<?php echo $t_mp_si_no->grado_4_Si->CellAttributes() ?>>
<span id="el_t_mp_si_no_grado_4_Si">
<span<?php echo $t_mp_si_no->grado_4_Si->ViewAttributes() ?>>
<?php echo $t_mp_si_no->grado_4_Si->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_mp_si_no->grado4_No->Visible) { // grado4_No ?>
		<tr id="r_grado4_No">
			<td><?php echo $t_mp_si_no->grado4_No->FldCaption() ?></td>
			<td<?php echo $t_mp_si_no->grado4_No->CellAttributes() ?>>
<span id="el_t_mp_si_no_grado4_No">
<span<?php echo $t_mp_si_no->grado4_No->ViewAttributes() ?>>
<?php echo $t_mp_si_no->grado4_No->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_mp_si_no->grado_2_Si->Visible) { // grado_2_Si ?>
		<tr id="r_grado_2_Si">
			<td><?php echo $t_mp_si_no->grado_2_Si->FldCaption() ?></td>
			<td<?php echo $t_mp_si_no->grado_2_Si->CellAttributes() ?>>
<span id="el_t_mp_si_no_grado_2_Si">
<span<?php echo $t_mp_si_no->grado_2_Si->ViewAttributes() ?>>
<?php echo $t_mp_si_no->grado_2_Si->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_mp_si_no->grado_2_No->Visible) { // grado_2_No ?>
		<tr id="r_grado_2_No">
			<td><?php echo $t_mp_si_no->grado_2_No->FldCaption() ?></td>
			<td<?php echo $t_mp_si_no->grado_2_No->CellAttributes() ?>>
<span id="el_t_mp_si_no_grado_2_No">
<span<?php echo $t_mp_si_no->grado_2_No->ViewAttributes() ?>>
<?php echo $t_mp_si_no->grado_2_No->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
