<?php

// Fiscalia
// Unidad_Organizacional

?>
<?php if ($t_unidad_organizacional->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_unidad_organizacional->TableCaption() ?></h4> -->
<table id="tbl_t_unidad_organizacionalmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_unidad_organizacional->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_unidad_organizacional->Fiscalia->Visible) { // Fiscalia ?>
		<tr id="r_Fiscalia">
			<td><?php echo $t_unidad_organizacional->Fiscalia->FldCaption() ?></td>
			<td<?php echo $t_unidad_organizacional->Fiscalia->CellAttributes() ?>>
<span id="el_t_unidad_organizacional_Fiscalia">
<span<?php echo $t_unidad_organizacional->Fiscalia->ViewAttributes() ?>>
<?php echo $t_unidad_organizacional->Fiscalia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_unidad_organizacional->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<tr id="r_Unidad_Organizacional">
			<td><?php echo $t_unidad_organizacional->Unidad_Organizacional->FldCaption() ?></td>
			<td<?php echo $t_unidad_organizacional->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_unidad_organizacional_Unidad_Organizacional">
<span<?php echo $t_unidad_organizacional->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $t_unidad_organizacional->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
