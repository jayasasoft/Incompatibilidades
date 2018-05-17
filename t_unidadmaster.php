<?php

// Unidad
?>
<?php if ($t_unidad->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_unidad->TableCaption() ?></h4> -->
<table id="tbl_t_unidadmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_unidad->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_unidad->Unidad->Visible) { // Unidad ?>
		<tr id="r_Unidad">
			<td><?php echo $t_unidad->Unidad->FldCaption() ?></td>
			<td<?php echo $t_unidad->Unidad->CellAttributes() ?>>
<span id="el_t_unidad_Unidad">
<span<?php echo $t_unidad->Unidad->ViewAttributes() ?>>
<?php echo $t_unidad->Unidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
