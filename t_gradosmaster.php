<?php

// Grado
?>
<?php if ($t_grados->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_grados->TableCaption() ?></h4> -->
<table id="tbl_t_gradosmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_grados->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_grados->Grado->Visible) { // Grado ?>
		<tr id="r_Grado">
			<td><?php echo $t_grados->Grado->FldCaption() ?></td>
			<td<?php echo $t_grados->Grado->CellAttributes() ?>>
<span id="el_t_grados_Grado">
<span<?php echo $t_grados->Grado->ViewAttributes() ?>>
<?php echo $t_grados->Grado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
