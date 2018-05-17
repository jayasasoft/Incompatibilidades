<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_pa_afinidad_grid)) $t_pa_afinidad_grid = new ct_pa_afinidad_grid();

// Page init
$t_pa_afinidad_grid->Page_Init();

// Page main
$t_pa_afinidad_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pa_afinidad_grid->Page_Render();
?>
<?php if ($t_pa_afinidad->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_pa_afinidadgrid = new ew_Form("ft_pa_afinidadgrid", "grid");
ft_pa_afinidadgrid.FormKeyCountName = '<?php echo $t_pa_afinidad_grid->FormKeyCountName ?>';

// Validate form
ft_pa_afinidadgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_afinidad->Id->FldCaption(), $t_pa_afinidad->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pa_afinidad->Id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_pa_afinidadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Grado_Parentesco", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_pa_afinidadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pa_afinidadgrid.ValidateRequired = true;
<?php } else { ?>
ft_pa_afinidadgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pa_afinidadgrid.Lists["x_Grado_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_afinidad"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_pa_afinidad->CurrentAction == "gridadd") {
	if ($t_pa_afinidad->CurrentMode == "copy") {
		$bSelectLimit = $t_pa_afinidad_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_pa_afinidad_grid->TotalRecs = $t_pa_afinidad->SelectRecordCount();
			$t_pa_afinidad_grid->Recordset = $t_pa_afinidad_grid->LoadRecordset($t_pa_afinidad_grid->StartRec-1, $t_pa_afinidad_grid->DisplayRecs);
		} else {
			if ($t_pa_afinidad_grid->Recordset = $t_pa_afinidad_grid->LoadRecordset())
				$t_pa_afinidad_grid->TotalRecs = $t_pa_afinidad_grid->Recordset->RecordCount();
		}
		$t_pa_afinidad_grid->StartRec = 1;
		$t_pa_afinidad_grid->DisplayRecs = $t_pa_afinidad_grid->TotalRecs;
	} else {
		$t_pa_afinidad->CurrentFilter = "0=1";
		$t_pa_afinidad_grid->StartRec = 1;
		$t_pa_afinidad_grid->DisplayRecs = $t_pa_afinidad->GridAddRowCount;
	}
	$t_pa_afinidad_grid->TotalRecs = $t_pa_afinidad_grid->DisplayRecs;
	$t_pa_afinidad_grid->StopRec = $t_pa_afinidad_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_pa_afinidad_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_pa_afinidad_grid->TotalRecs <= 0)
			$t_pa_afinidad_grid->TotalRecs = $t_pa_afinidad->SelectRecordCount();
	} else {
		if (!$t_pa_afinidad_grid->Recordset && ($t_pa_afinidad_grid->Recordset = $t_pa_afinidad_grid->LoadRecordset()))
			$t_pa_afinidad_grid->TotalRecs = $t_pa_afinidad_grid->Recordset->RecordCount();
	}
	$t_pa_afinidad_grid->StartRec = 1;
	$t_pa_afinidad_grid->DisplayRecs = $t_pa_afinidad_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_pa_afinidad_grid->Recordset = $t_pa_afinidad_grid->LoadRecordset($t_pa_afinidad_grid->StartRec-1, $t_pa_afinidad_grid->DisplayRecs);

	// Set no record found message
	if ($t_pa_afinidad->CurrentAction == "" && $t_pa_afinidad_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_pa_afinidad_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_pa_afinidad_grid->SearchWhere == "0=101")
			$t_pa_afinidad_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_pa_afinidad_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_pa_afinidad_grid->RenderOtherOptions();
?>
<?php $t_pa_afinidad_grid->ShowPageHeader(); ?>
<?php
$t_pa_afinidad_grid->ShowMessage();
?>
<?php if ($t_pa_afinidad_grid->TotalRecs > 0 || $t_pa_afinidad->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_pa_afinidad">
<div id="ft_pa_afinidadgrid" class="ewForm form-inline">
<div id="gmp_t_pa_afinidad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_pa_afinidadgrid" class="table ewTable">
<?php echo $t_pa_afinidad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_pa_afinidad_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_pa_afinidad_grid->RenderListOptions();

// Render list options (header, left)
$t_pa_afinidad_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_pa_afinidad->Id->Visible) { // Id ?>
	<?php if ($t_pa_afinidad->SortUrl($t_pa_afinidad->Id) == "") { ?>
		<th data-name="Id"><div id="elh_t_pa_afinidad_Id" class="t_pa_afinidad_Id"><div class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div><div id="elh_t_pa_afinidad_Id" class="t_pa_afinidad_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_afinidad->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_afinidad->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
	<?php if ($t_pa_afinidad->SortUrl($t_pa_afinidad->Nombre) == "") { ?>
		<th data-name="Nombre"><div id="elh_t_pa_afinidad_Nombre" class="t_pa_afinidad_Nombre"><div class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombre"><div><div id="elh_t_pa_afinidad_Nombre" class="t_pa_afinidad_Nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_afinidad->Nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_afinidad->Nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($t_pa_afinidad->SortUrl($t_pa_afinidad->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_t_pa_afinidad_Apellido_Paterno" class="t_pa_afinidad_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div><div id="elh_t_pa_afinidad_Apellido_Paterno" class="t_pa_afinidad_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Apellido_Paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_afinidad->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_afinidad->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($t_pa_afinidad->SortUrl($t_pa_afinidad->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_t_pa_afinidad_Apellido_Materno" class="t_pa_afinidad_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div><div id="elh_t_pa_afinidad_Apellido_Materno" class="t_pa_afinidad_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Apellido_Materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_afinidad->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_afinidad->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
	<?php if ($t_pa_afinidad->SortUrl($t_pa_afinidad->Grado_Parentesco) == "") { ?>
		<th data-name="Grado_Parentesco"><div id="elh_t_pa_afinidad_Grado_Parentesco" class="t_pa_afinidad_Grado_Parentesco"><div class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Grado_Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Grado_Parentesco"><div><div id="elh_t_pa_afinidad_Grado_Parentesco" class="t_pa_afinidad_Grado_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_afinidad->Grado_Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_afinidad->Grado_Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_afinidad->Grado_Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_pa_afinidad_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_pa_afinidad_grid->StartRec = 1;
$t_pa_afinidad_grid->StopRec = $t_pa_afinidad_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_pa_afinidad_grid->FormKeyCountName) && ($t_pa_afinidad->CurrentAction == "gridadd" || $t_pa_afinidad->CurrentAction == "gridedit" || $t_pa_afinidad->CurrentAction == "F")) {
		$t_pa_afinidad_grid->KeyCount = $objForm->GetValue($t_pa_afinidad_grid->FormKeyCountName);
		$t_pa_afinidad_grid->StopRec = $t_pa_afinidad_grid->StartRec + $t_pa_afinidad_grid->KeyCount - 1;
	}
}
$t_pa_afinidad_grid->RecCnt = $t_pa_afinidad_grid->StartRec - 1;
if ($t_pa_afinidad_grid->Recordset && !$t_pa_afinidad_grid->Recordset->EOF) {
	$t_pa_afinidad_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_pa_afinidad_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_pa_afinidad_grid->StartRec > 1)
		$t_pa_afinidad_grid->Recordset->Move($t_pa_afinidad_grid->StartRec - 1);
} elseif (!$t_pa_afinidad->AllowAddDeleteRow && $t_pa_afinidad_grid->StopRec == 0) {
	$t_pa_afinidad_grid->StopRec = $t_pa_afinidad->GridAddRowCount;
}

// Initialize aggregate
$t_pa_afinidad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_pa_afinidad->ResetAttrs();
$t_pa_afinidad_grid->RenderRow();
if ($t_pa_afinidad->CurrentAction == "gridadd")
	$t_pa_afinidad_grid->RowIndex = 0;
if ($t_pa_afinidad->CurrentAction == "gridedit")
	$t_pa_afinidad_grid->RowIndex = 0;
while ($t_pa_afinidad_grid->RecCnt < $t_pa_afinidad_grid->StopRec) {
	$t_pa_afinidad_grid->RecCnt++;
	if (intval($t_pa_afinidad_grid->RecCnt) >= intval($t_pa_afinidad_grid->StartRec)) {
		$t_pa_afinidad_grid->RowCnt++;
		if ($t_pa_afinidad->CurrentAction == "gridadd" || $t_pa_afinidad->CurrentAction == "gridedit" || $t_pa_afinidad->CurrentAction == "F") {
			$t_pa_afinidad_grid->RowIndex++;
			$objForm->Index = $t_pa_afinidad_grid->RowIndex;
			if ($objForm->HasValue($t_pa_afinidad_grid->FormActionName))
				$t_pa_afinidad_grid->RowAction = strval($objForm->GetValue($t_pa_afinidad_grid->FormActionName));
			elseif ($t_pa_afinidad->CurrentAction == "gridadd")
				$t_pa_afinidad_grid->RowAction = "insert";
			else
				$t_pa_afinidad_grid->RowAction = "";
		}

		// Set up key count
		$t_pa_afinidad_grid->KeyCount = $t_pa_afinidad_grid->RowIndex;

		// Init row class and style
		$t_pa_afinidad->ResetAttrs();
		$t_pa_afinidad->CssClass = "";
		if ($t_pa_afinidad->CurrentAction == "gridadd") {
			if ($t_pa_afinidad->CurrentMode == "copy") {
				$t_pa_afinidad_grid->LoadRowValues($t_pa_afinidad_grid->Recordset); // Load row values
				$t_pa_afinidad_grid->SetRecordKey($t_pa_afinidad_grid->RowOldKey, $t_pa_afinidad_grid->Recordset); // Set old record key
			} else {
				$t_pa_afinidad_grid->LoadDefaultValues(); // Load default values
				$t_pa_afinidad_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_pa_afinidad_grid->LoadRowValues($t_pa_afinidad_grid->Recordset); // Load row values
		}
		$t_pa_afinidad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_pa_afinidad->CurrentAction == "gridadd") // Grid add
			$t_pa_afinidad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_pa_afinidad->CurrentAction == "gridadd" && $t_pa_afinidad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_pa_afinidad_grid->RestoreCurrentRowFormValues($t_pa_afinidad_grid->RowIndex); // Restore form values
		if ($t_pa_afinidad->CurrentAction == "gridedit") { // Grid edit
			if ($t_pa_afinidad->EventCancelled) {
				$t_pa_afinidad_grid->RestoreCurrentRowFormValues($t_pa_afinidad_grid->RowIndex); // Restore form values
			}
			if ($t_pa_afinidad_grid->RowAction == "insert")
				$t_pa_afinidad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_pa_afinidad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_pa_afinidad->CurrentAction == "gridedit" && ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT || $t_pa_afinidad->RowType == EW_ROWTYPE_ADD) && $t_pa_afinidad->EventCancelled) // Update failed
			$t_pa_afinidad_grid->RestoreCurrentRowFormValues($t_pa_afinidad_grid->RowIndex); // Restore form values
		if ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_pa_afinidad_grid->EditRowCnt++;
		if ($t_pa_afinidad->CurrentAction == "F") // Confirm row
			$t_pa_afinidad_grid->RestoreCurrentRowFormValues($t_pa_afinidad_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_pa_afinidad->RowAttrs = array_merge($t_pa_afinidad->RowAttrs, array('data-rowindex'=>$t_pa_afinidad_grid->RowCnt, 'id'=>'r' . $t_pa_afinidad_grid->RowCnt . '_t_pa_afinidad', 'data-rowtype'=>$t_pa_afinidad->RowType));

		// Render row
		$t_pa_afinidad_grid->RenderRow();

		// Render list options
		$t_pa_afinidad_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_pa_afinidad_grid->RowAction <> "delete" && $t_pa_afinidad_grid->RowAction <> "insertdelete" && !($t_pa_afinidad_grid->RowAction == "insert" && $t_pa_afinidad->CurrentAction == "F" && $t_pa_afinidad_grid->EmptyRow())) {
?>
	<tr<?php echo $t_pa_afinidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_pa_afinidad_grid->ListOptions->Render("body", "left", $t_pa_afinidad_grid->RowCnt);
?>
	<?php if ($t_pa_afinidad->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $t_pa_afinidad->Id->CellAttributes() ?>>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_pa_afinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<span<?php echo $t_pa_afinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<input type="text" data-table="t_pa_afinidad" data-field="x_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Id->EditValue ?>"<?php echo $t_pa_afinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_pa_afinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<span<?php echo $t_pa_afinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<input type="text" data-table="t_pa_afinidad" data-field="x_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Id->EditValue ?>"<?php echo $t_pa_afinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Id" class="t_pa_afinidad_Id">
<span<?php echo $t_pa_afinidad->Id->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Id->ListViewValue() ?></span>
</span>
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_pa_afinidad_grid->PageObjName . "_row_" . $t_pa_afinidad_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
		<td data-name="Nombre"<?php echo $t_pa_afinidad->Nombre->CellAttributes() ?>>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Nombre" class="form-group t_pa_afinidad_Nombre">
<input type="text" data-table="t_pa_afinidad" data-field="x_Nombre" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Nombre->EditValue ?>"<?php echo $t_pa_afinidad->Nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Nombre" class="form-group t_pa_afinidad_Nombre">
<span<?php echo $t_pa_afinidad->Nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Nombre->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Nombre" class="t_pa_afinidad_Nombre">
<span<?php echo $t_pa_afinidad->Nombre->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Nombre->ListViewValue() ?></span>
</span>
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $t_pa_afinidad->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Apellido_Paterno" class="form-group t_pa_afinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Apellido_Paterno" class="form-group t_pa_afinidad_Apellido_Paterno">
<span<?php echo $t_pa_afinidad->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Apellido_Paterno" class="t_pa_afinidad_Apellido_Paterno">
<span<?php echo $t_pa_afinidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $t_pa_afinidad->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Apellido_Materno" class="form-group t_pa_afinidad_Apellido_Materno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Apellido_Materno" class="form-group t_pa_afinidad_Apellido_Materno">
<span<?php echo $t_pa_afinidad->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Apellido_Materno" class="t_pa_afinidad_Apellido_Materno">
<span<?php echo $t_pa_afinidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
		<td data-name="Grado_Parentesco"<?php echo $t_pa_afinidad->Grado_Parentesco->CellAttributes() ?>>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Grado_Parentesco" class="form-group t_pa_afinidad_Grado_Parentesco">
<select data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_pa_afinidad->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco"<?php echo $t_pa_afinidad->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->SelectOptionListHtml("x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="s_x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo $t_pa_afinidad->Grado_Parentesco->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Grado_Parentesco" class="form-group t_pa_afinidad_Grado_Parentesco">
<select data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_pa_afinidad->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco"<?php echo $t_pa_afinidad->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->SelectOptionListHtml("x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="s_x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo $t_pa_afinidad->Grado_Parentesco->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_afinidad_grid->RowCnt ?>_t_pa_afinidad_Grado_Parentesco" class="t_pa_afinidad_Grado_Parentesco">
<span<?php echo $t_pa_afinidad->Grado_Parentesco->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->ListViewValue() ?></span>
</span>
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="ft_pa_afinidadgrid$x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="ft_pa_afinidadgrid$o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_pa_afinidad_grid->ListOptions->Render("body", "right", $t_pa_afinidad_grid->RowCnt);
?>
	</tr>
<?php if ($t_pa_afinidad->RowType == EW_ROWTYPE_ADD || $t_pa_afinidad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_pa_afinidadgrid.UpdateOpts(<?php echo $t_pa_afinidad_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_pa_afinidad->CurrentAction <> "gridadd" || $t_pa_afinidad->CurrentMode == "copy")
		if (!$t_pa_afinidad_grid->Recordset->EOF) $t_pa_afinidad_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_pa_afinidad->CurrentMode == "add" || $t_pa_afinidad->CurrentMode == "copy" || $t_pa_afinidad->CurrentMode == "edit") {
		$t_pa_afinidad_grid->RowIndex = '$rowindex$';
		$t_pa_afinidad_grid->LoadDefaultValues();

		// Set row properties
		$t_pa_afinidad->ResetAttrs();
		$t_pa_afinidad->RowAttrs = array_merge($t_pa_afinidad->RowAttrs, array('data-rowindex'=>$t_pa_afinidad_grid->RowIndex, 'id'=>'r0_t_pa_afinidad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_pa_afinidad->RowAttrs["class"], "ewTemplate");
		$t_pa_afinidad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_pa_afinidad_grid->RenderRow();

		// Render list options
		$t_pa_afinidad_grid->RenderListOptions();
		$t_pa_afinidad_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_pa_afinidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_pa_afinidad_grid->ListOptions->Render("body", "left", $t_pa_afinidad_grid->RowIndex);
?>
	<?php if ($t_pa_afinidad->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<?php if ($t_pa_afinidad->Id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<span<?php echo $t_pa_afinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<input type="text" data-table="t_pa_afinidad" data-field="x_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Id->EditValue ?>"<?php echo $t_pa_afinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_pa_afinidad_Id" class="form-group t_pa_afinidad_Id">
<span<?php echo $t_pa_afinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Id" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
		<td data-name="Nombre">
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_afinidad_Nombre" class="form-group t_pa_afinidad_Nombre">
<input type="text" data-table="t_pa_afinidad" data-field="x_Nombre" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Nombre->EditValue ?>"<?php echo $t_pa_afinidad->Nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_afinidad_Nombre" class="form-group t_pa_afinidad_Nombre">
<span<?php echo $t_pa_afinidad->Nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Nombre" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Nombre" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno">
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_afinidad_Apellido_Paterno" class="form-group t_pa_afinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_afinidad_Apellido_Paterno" class="form-group t_pa_afinidad_Apellido_Paterno">
<span<?php echo $t_pa_afinidad->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno">
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_afinidad_Apellido_Materno" class="form-group t_pa_afinidad_Apellido_Materno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_afinidad_Apellido_Materno" class="form-group t_pa_afinidad_Apellido_Materno">
<span<?php echo $t_pa_afinidad->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
		<td data-name="Grado_Parentesco">
<?php if ($t_pa_afinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_afinidad_Grado_Parentesco" class="form-group t_pa_afinidad_Grado_Parentesco">
<select data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_pa_afinidad->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco"<?php echo $t_pa_afinidad->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->SelectOptionListHtml("x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="s_x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo $t_pa_afinidad->Grado_Parentesco->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_afinidad_Grado_Parentesco" class="form-group t_pa_afinidad_Grado_Parentesco">
<span<?php echo $t_pa_afinidad->Grado_Parentesco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_afinidad->Grado_Parentesco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="x<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" name="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" id="o<?php echo $t_pa_afinidad_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_afinidad->Grado_Parentesco->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_pa_afinidad_grid->ListOptions->Render("body", "right", $t_pa_afinidad_grid->RowCnt);
?>
<script type="text/javascript">
ft_pa_afinidadgrid.UpdateOpts(<?php echo $t_pa_afinidad_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_pa_afinidad->CurrentMode == "add" || $t_pa_afinidad->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_pa_afinidad_grid->FormKeyCountName ?>" id="<?php echo $t_pa_afinidad_grid->FormKeyCountName ?>" value="<?php echo $t_pa_afinidad_grid->KeyCount ?>">
<?php echo $t_pa_afinidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pa_afinidad->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_pa_afinidad_grid->FormKeyCountName ?>" id="<?php echo $t_pa_afinidad_grid->FormKeyCountName ?>" value="<?php echo $t_pa_afinidad_grid->KeyCount ?>">
<?php echo $t_pa_afinidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pa_afinidad->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_pa_afinidadgrid">
</div>
<?php

// Close recordset
if ($t_pa_afinidad_grid->Recordset)
	$t_pa_afinidad_grid->Recordset->Close();
?>
<?php if ($t_pa_afinidad_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_pa_afinidad_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_pa_afinidad_grid->TotalRecs == 0 && $t_pa_afinidad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_pa_afinidad_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_pa_afinidad->Export == "") { ?>
<script type="text/javascript">
ft_pa_afinidadgrid.Init();
</script>
<?php } ?>
<?php
$t_pa_afinidad_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_pa_afinidad_grid->Page_Terminate();
?>
