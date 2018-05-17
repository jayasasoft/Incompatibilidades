<?php

// CI_RUN
// Expedido
// Apellido_Paterno
// Apellido_Materno
// Nombres
// Fecha_Nacimiento
// Estado_Civil
// Direccion
// Telefono
// Celular
// Fiscalia_otro
// Unidad_Organizacional
// Unidad
// Cargo

?>
<?php if ($t_funcionario->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_funcionario->TableCaption() ?></h4> -->
<table id="tbl_t_funcionariomaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_funcionario->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_funcionario->CI_RUN->Visible) { // CI_RUN ?>
		<tr id="r_CI_RUN">
			<td><?php echo $t_funcionario->CI_RUN->FldCaption() ?></td>
			<td<?php echo $t_funcionario->CI_RUN->CellAttributes() ?>>
<span id="el_t_funcionario_CI_RUN">
<span<?php echo $t_funcionario->CI_RUN->ViewAttributes() ?>>
<?php echo $t_funcionario->CI_RUN->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Expedido->Visible) { // Expedido ?>
		<tr id="r_Expedido">
			<td><?php echo $t_funcionario->Expedido->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Expedido->CellAttributes() ?>>
<span id="el_t_funcionario_Expedido">
<span<?php echo $t_funcionario->Expedido->ViewAttributes() ?>>
<?php echo $t_funcionario->Expedido->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<tr id="r_Apellido_Paterno">
			<td><?php echo $t_funcionario->Apellido_Paterno->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Apellido_Paterno->CellAttributes() ?>>
<span id="el_t_funcionario_Apellido_Paterno">
<span<?php echo $t_funcionario->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_funcionario->Apellido_Paterno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<tr id="r_Apellido_Materno">
			<td><?php echo $t_funcionario->Apellido_Materno->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Apellido_Materno->CellAttributes() ?>>
<span id="el_t_funcionario_Apellido_Materno">
<span<?php echo $t_funcionario->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_funcionario->Apellido_Materno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Nombres->Visible) { // Nombres ?>
		<tr id="r_Nombres">
			<td><?php echo $t_funcionario->Nombres->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Nombres->CellAttributes() ?>>
<span id="el_t_funcionario_Nombres">
<span<?php echo $t_funcionario->Nombres->ViewAttributes() ?>>
<?php echo $t_funcionario->Nombres->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Fecha_Nacimiento->Visible) { // Fecha_Nacimiento ?>
		<tr id="r_Fecha_Nacimiento">
			<td><?php echo $t_funcionario->Fecha_Nacimiento->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Fecha_Nacimiento->CellAttributes() ?>>
<span id="el_t_funcionario_Fecha_Nacimiento">
<span<?php echo $t_funcionario->Fecha_Nacimiento->ViewAttributes() ?>>
<?php echo $t_funcionario->Fecha_Nacimiento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Estado_Civil->Visible) { // Estado_Civil ?>
		<tr id="r_Estado_Civil">
			<td><?php echo $t_funcionario->Estado_Civil->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Estado_Civil->CellAttributes() ?>>
<span id="el_t_funcionario_Estado_Civil">
<span<?php echo $t_funcionario->Estado_Civil->ViewAttributes() ?>>
<?php echo $t_funcionario->Estado_Civil->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Direccion->Visible) { // Direccion ?>
		<tr id="r_Direccion">
			<td><?php echo $t_funcionario->Direccion->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Direccion->CellAttributes() ?>>
<span id="el_t_funcionario_Direccion">
<span<?php echo $t_funcionario->Direccion->ViewAttributes() ?>>
<?php echo $t_funcionario->Direccion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Telefono->Visible) { // Telefono ?>
		<tr id="r_Telefono">
			<td><?php echo $t_funcionario->Telefono->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Telefono->CellAttributes() ?>>
<span id="el_t_funcionario_Telefono">
<span<?php echo $t_funcionario->Telefono->ViewAttributes() ?>>
<?php echo $t_funcionario->Telefono->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Celular->Visible) { // Celular ?>
		<tr id="r_Celular">
			<td><?php echo $t_funcionario->Celular->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Celular->CellAttributes() ?>>
<span id="el_t_funcionario_Celular">
<span<?php echo $t_funcionario->Celular->ViewAttributes() ?>>
<?php echo $t_funcionario->Celular->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
		<tr id="r_Fiscalia_otro">
			<td><?php echo $t_funcionario->Fiscalia_otro->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Fiscalia_otro->CellAttributes() ?>>
<span id="el_t_funcionario_Fiscalia_otro">
<span<?php echo $t_funcionario->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $t_funcionario->Fiscalia_otro->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<tr id="r_Unidad_Organizacional">
			<td><?php echo $t_funcionario->Unidad_Organizacional->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_funcionario_Unidad_Organizacional">
<span<?php echo $t_funcionario->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $t_funcionario->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Unidad->Visible) { // Unidad ?>
		<tr id="r_Unidad">
			<td><?php echo $t_funcionario->Unidad->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Unidad->CellAttributes() ?>>
<span id="el_t_funcionario_Unidad">
<span<?php echo $t_funcionario->Unidad->ViewAttributes() ?>>
<?php echo $t_funcionario->Unidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_funcionario->Cargo->Visible) { // Cargo ?>
		<tr id="r_Cargo">
			<td><?php echo $t_funcionario->Cargo->FldCaption() ?></td>
			<td<?php echo $t_funcionario->Cargo->CellAttributes() ?>>
<span id="el_t_funcionario_Cargo">
<span<?php echo $t_funcionario->Cargo->ViewAttributes() ?>>
<?php echo $t_funcionario->Cargo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
