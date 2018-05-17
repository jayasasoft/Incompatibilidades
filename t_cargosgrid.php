<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_cargos_grid)) $t_cargos_grid = new ct_cargos_grid();

// Page init
$t_cargos_grid->Page_Init();

// Page main
$t_cargos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_cargos_grid->Page_Render();
?>
<?php if ($t_cargos->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_cargosgrid = new ew_Form("ft_cargosgrid", "grid");
ft_cargosgrid.FormKeyCountName = '<?php echo $t_cargos_grid->FormKeyCountName ?>';

// Validate form
ft_cargosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Cargo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_cargos->Cargo->FldCaption(), $t_cargos->Cargo->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_cargosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Cargo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Obs", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_cargosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_cargosgrid.ValidateRequired = true;
<?php } else { ?>
ft_cargosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($t_cargos->CurrentAction == "gridadd") {
	if ($t_cargos->CurrentMode == "copy") {
		$bSelectLimit = $t_cargos_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_cargos_grid->TotalRecs = $t_cargos->SelectRecordCount();
			$t_cargos_grid->Recordset = $t_cargos_grid->LoadRecordset($t_cargos_grid->StartRec-1, $t_cargos_grid->DisplayRecs);
		} else {
			if ($t_cargos_grid->Recordset = $t_cargos_grid->LoadRecordset())
				$t_cargos_grid->TotalRecs = $t_cargos_grid->Recordset->RecordCount();
		}
		$t_cargos_grid->StartRec = 1;
		$t_cargos_grid->DisplayRecs = $t_cargos_grid->TotalRecs;
	} else {
		$t_cargos->CurrentFilter = "0=1";
		$t_cargos_grid->StartRec = 1;
		$t_cargos_grid->DisplayRecs = $t_cargos->GridAddRowCount;
	}
	$t_cargos_grid->TotalRecs = $t_cargos_grid->DisplayRecs;
	$t_cargos_grid->StopRec = $t_cargos_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_cargos_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_cargos_grid->TotalRecs <= 0)
			$t_cargos_grid->TotalRecs = $t_cargos->SelectRecordCount();
	} else {
		if (!$t_cargos_grid->Recordset && ($t_cargos_grid->Recordset = $t_cargos_grid->LoadRecordset()))
			$t_cargos_grid->TotalRecs = $t_cargos_grid->Recordset->RecordCount();
	}
	$t_cargos_grid->StartRec = 1;
	$t_cargos_grid->DisplayRecs = $t_cargos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_cargos_grid->Recordset = $t_cargos_grid->LoadRecordset($t_cargos_grid->StartRec-1, $t_cargos_grid->DisplayRecs);

	// Set no record found message
	if ($t_cargos->CurrentAction == "" && $t_cargos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_cargos_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_cargos_grid->SearchWhere == "0=101")
			$t_cargos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_cargos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_cargos_grid->RenderOtherOptions();
?>
<?php $t_cargos_grid->ShowPageHeader(); ?>
<?php
$t_cargos_grid->ShowMessage();
?>
<?php if ($t_cargos_grid->TotalRecs > 0 || $t_cargos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_cargos">
<div id="ft_cargosgrid" class="ewForm form-inline">
<div id="gmp_t_cargos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_cargosgrid" class="table ewTable">
<?php echo $t_cargos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_cargos_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_cargos_grid->RenderListOptions();

// Render list options (header, left)
$t_cargos_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_cargos->Cargo->Visible) { // Cargo ?>
	<?php if ($t_cargos->SortUrl($t_cargos->Cargo) == "") { ?>
		<th data-name="Cargo"><div id="elh_t_cargos_Cargo" class="t_cargos_Cargo"><div class="ewTableHeaderCaption"><?php echo $t_cargos->Cargo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo"><div><div id="elh_t_cargos_Cargo" class="t_cargos_Cargo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_cargos->Cargo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_cargos->Cargo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_cargos->Cargo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_cargos->Obs->Visible) { // Obs ?>
	<?php if ($t_cargos->SortUrl($t_cargos->Obs) == "") { ?>
		<th data-name="Obs"><div id="elh_t_cargos_Obs" class="t_cargos_Obs"><div class="ewTableHeaderCaption"><?php echo $t_cargos->Obs->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Obs"><div><div id="elh_t_cargos_Obs" class="t_cargos_Obs">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_cargos->Obs->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_cargos->Obs->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_cargos->Obs->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_cargos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_cargos_grid->StartRec = 1;
$t_cargos_grid->StopRec = $t_cargos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_cargos_grid->FormKeyCountName) && ($t_cargos->CurrentAction == "gridadd" || $t_cargos->CurrentAction == "gridedit" || $t_cargos->CurrentAction == "F")) {
		$t_cargos_grid->KeyCount = $objForm->GetValue($t_cargos_grid->FormKeyCountName);
		$t_cargos_grid->StopRec = $t_cargos_grid->StartRec + $t_cargos_grid->KeyCount - 1;
	}
}
$t_cargos_grid->RecCnt = $t_cargos_grid->StartRec - 1;
if ($t_cargos_grid->Recordset && !$t_cargos_grid->Recordset->EOF) {
	$t_cargos_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_cargos_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_cargos_grid->StartRec > 1)
		$t_cargos_grid->Recordset->Move($t_cargos_grid->StartRec - 1);
} elseif (!$t_cargos->AllowAddDeleteRow && $t_cargos_grid->StopRec == 0) {
	$t_cargos_grid->StopRec = $t_cargos->GridAddRowCount;
}

// Initialize aggregate
$t_cargos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_cargos->ResetAttrs();
$t_cargos_grid->RenderRow();
if ($t_cargos->CurrentAction == "gridadd")
	$t_cargos_grid->RowIndex = 0;
if ($t_cargos->CurrentAction == "gridedit")
	$t_cargos_grid->RowIndex = 0;
while ($t_cargos_grid->RecCnt < $t_cargos_grid->StopRec) {
	$t_cargos_grid->RecCnt++;
	if (intval($t_cargos_grid->RecCnt) >= intval($t_cargos_grid->StartRec)) {
		$t_cargos_grid->RowCnt++;
		if ($t_cargos->CurrentAction == "gridadd" || $t_cargos->CurrentAction == "gridedit" || $t_cargos->CurrentAction == "F") {
			$t_cargos_grid->RowIndex++;
			$objForm->Index = $t_cargos_grid->RowIndex;
			if ($objForm->HasValue($t_cargos_grid->FormActionName))
				$t_cargos_grid->RowAction = strval($objForm->GetValue($t_cargos_grid->FormActionName));
			elseif ($t_cargos->CurrentAction == "gridadd")
				$t_cargos_grid->RowAction = "insert";
			else
				$t_cargos_grid->RowAction = "";
		}

		// Set up key count
		$t_cargos_grid->KeyCount = $t_cargos_grid->RowIndex;

		// Init row class and style
		$t_cargos->ResetAttrs();
		$t_cargos->CssClass = "";
		if ($t_cargos->CurrentAction == "gridadd") {
			if ($t_cargos->CurrentMode == "copy") {
				$t_cargos_grid->LoadRowValues($t_cargos_grid->Recordset); // Load row values
				$t_cargos_grid->SetRecordKey($t_cargos_grid->RowOldKey, $t_cargos_grid->Recordset); // Set old record key
			} else {
				$t_cargos_grid->LoadDefaultValues(); // Load default values
				$t_cargos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_cargos_grid->LoadRowValues($t_cargos_grid->Recordset); // Load row values
		}
		$t_cargos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_cargos->CurrentAction == "gridadd") // Grid add
			$t_cargos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_cargos->CurrentAction == "gridadd" && $t_cargos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_cargos_grid->RestoreCurrentRowFormValues($t_cargos_grid->RowIndex); // Restore form values
		if ($t_cargos->CurrentAction == "gridedit") { // Grid edit
			if ($t_cargos->EventCancelled) {
				$t_cargos_grid->RestoreCurrentRowFormValues($t_cargos_grid->RowIndex); // Restore form values
			}
			if ($t_cargos_grid->RowAction == "insert")
				$t_cargos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_cargos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_cargos->CurrentAction == "gridedit" && ($t_cargos->RowType == EW_ROWTYPE_EDIT || $t_cargos->RowType == EW_ROWTYPE_ADD) && $t_cargos->EventCancelled) // Update failed
			$t_cargos_grid->RestoreCurrentRowFormValues($t_cargos_grid->RowIndex); // Restore form values
		if ($t_cargos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_cargos_grid->EditRowCnt++;
		if ($t_cargos->CurrentAction == "F") // Confirm row
			$t_cargos_grid->RestoreCurrentRowFormValues($t_cargos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_cargos->RowAttrs = array_merge($t_cargos->RowAttrs, array('data-rowindex'=>$t_cargos_grid->RowCnt, 'id'=>'r' . $t_cargos_grid->RowCnt . '_t_cargos', 'data-rowtype'=>$t_cargos->RowType));

		// Render row
		$t_cargos_grid->RenderRow();

		// Render list options
		$t_cargos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_cargos_grid->RowAction <> "delete" && $t_cargos_grid->RowAction <> "insertdelete" && !($t_cargos_grid->RowAction == "insert" && $t_cargos->CurrentAction == "F" && $t_cargos_grid->EmptyRow())) {
?>
	<tr<?php echo $t_cargos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_cargos_grid->ListOptions->Render("body", "left", $t_cargos_grid->RowCnt);
?>
	<?php if ($t_cargos->Cargo->Visible) { // Cargo ?>
		<td data-name="Cargo"<?php echo $t_cargos->Cargo->CellAttributes() ?>>
<?php if ($t_cargos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_cargos_grid->RowCnt ?>_t_cargos_Cargo" class="form-group t_cargos_Cargo">
<input type="text" data-table="t_cargos" data-field="x_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($t_cargos->Cargo->getPlaceHolder()) ?>" value="<?php echo $t_cargos->Cargo->EditValue ?>"<?php echo $t_cargos->Cargo->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->OldValue) ?>">
<?php } ?>
<?php if ($t_cargos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_cargos_grid->RowCnt ?>_t_cargos_Cargo" class="form-group t_cargos_Cargo">
<input type="text" data-table="t_cargos" data-field="x_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($t_cargos->Cargo->getPlaceHolder()) ?>" value="<?php echo $t_cargos->Cargo->EditValue ?>"<?php echo $t_cargos->Cargo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_cargos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_cargos_grid->RowCnt ?>_t_cargos_Cargo" class="t_cargos_Cargo">
<span<?php echo $t_cargos->Cargo->ViewAttributes() ?>>
<?php echo $t_cargos->Cargo->ListViewValue() ?></span>
</span>
<?php if ($t_cargos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->FormValue) ?>">
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="ft_cargosgrid$x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="ft_cargosgrid$x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->FormValue) ?>">
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="ft_cargosgrid$o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="ft_cargosgrid$o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_cargos_grid->PageObjName . "_row_" . $t_cargos_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_cargos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_cargos" data-field="x_Id_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Id_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Id_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Id_Cargo->CurrentValue) ?>">
<input type="hidden" data-table="t_cargos" data-field="x_Id_Cargo" name="o<?php echo $t_cargos_grid->RowIndex ?>_Id_Cargo" id="o<?php echo $t_cargos_grid->RowIndex ?>_Id_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Id_Cargo->OldValue) ?>">
<?php } ?>
<?php if ($t_cargos->RowType == EW_ROWTYPE_EDIT || $t_cargos->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_cargos" data-field="x_Id_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Id_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Id_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Id_Cargo->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_cargos->Obs->Visible) { // Obs ?>
		<td data-name="Obs"<?php echo $t_cargos->Obs->CellAttributes() ?>>
<?php if ($t_cargos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_cargos_grid->RowCnt ?>_t_cargos_Obs" class="form-group t_cargos_Obs">
<input type="text" data-table="t_cargos" data-field="x_Obs" name="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_cargos->Obs->getPlaceHolder()) ?>" value="<?php echo $t_cargos->Obs->EditValue ?>"<?php echo $t_cargos->Obs->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="o<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="o<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->OldValue) ?>">
<?php } ?>
<?php if ($t_cargos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_cargos_grid->RowCnt ?>_t_cargos_Obs" class="form-group t_cargos_Obs">
<input type="text" data-table="t_cargos" data-field="x_Obs" name="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_cargos->Obs->getPlaceHolder()) ?>" value="<?php echo $t_cargos->Obs->EditValue ?>"<?php echo $t_cargos->Obs->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_cargos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_cargos_grid->RowCnt ?>_t_cargos_Obs" class="t_cargos_Obs">
<span<?php echo $t_cargos->Obs->ViewAttributes() ?>>
<?php echo $t_cargos->Obs->ListViewValue() ?></span>
</span>
<?php if ($t_cargos->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->FormValue) ?>">
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="o<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="o<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="ft_cargosgrid$x<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="ft_cargosgrid$x<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->FormValue) ?>">
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="ft_cargosgrid$o<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="ft_cargosgrid$o<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_cargos_grid->ListOptions->Render("body", "right", $t_cargos_grid->RowCnt);
?>
	</tr>
<?php if ($t_cargos->RowType == EW_ROWTYPE_ADD || $t_cargos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_cargosgrid.UpdateOpts(<?php echo $t_cargos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_cargos->CurrentAction <> "gridadd" || $t_cargos->CurrentMode == "copy")
		if (!$t_cargos_grid->Recordset->EOF) $t_cargos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_cargos->CurrentMode == "add" || $t_cargos->CurrentMode == "copy" || $t_cargos->CurrentMode == "edit") {
		$t_cargos_grid->RowIndex = '$rowindex$';
		$t_cargos_grid->LoadDefaultValues();

		// Set row properties
		$t_cargos->ResetAttrs();
		$t_cargos->RowAttrs = array_merge($t_cargos->RowAttrs, array('data-rowindex'=>$t_cargos_grid->RowIndex, 'id'=>'r0_t_cargos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_cargos->RowAttrs["class"], "ewTemplate");
		$t_cargos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_cargos_grid->RenderRow();

		// Render list options
		$t_cargos_grid->RenderListOptions();
		$t_cargos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_cargos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_cargos_grid->ListOptions->Render("body", "left", $t_cargos_grid->RowIndex);
?>
	<?php if ($t_cargos->Cargo->Visible) { // Cargo ?>
		<td data-name="Cargo">
<?php if ($t_cargos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_cargos_Cargo" class="form-group t_cargos_Cargo">
<input type="text" data-table="t_cargos" data-field="x_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" size="30" maxlength="80" placeholder="<?php echo ew_HtmlEncode($t_cargos->Cargo->getPlaceHolder()) ?>" value="<?php echo $t_cargos->Cargo->EditValue ?>"<?php echo $t_cargos->Cargo->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_cargos_Cargo" class="form-group t_cargos_Cargo">
<span<?php echo $t_cargos->Cargo->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_cargos->Cargo->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="x<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_cargos" data-field="x_Cargo" name="o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" id="o<?php echo $t_cargos_grid->RowIndex ?>_Cargo" value="<?php echo ew_HtmlEncode($t_cargos->Cargo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_cargos->Obs->Visible) { // Obs ?>
		<td data-name="Obs">
<?php if ($t_cargos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_cargos_Obs" class="form-group t_cargos_Obs">
<input type="text" data-table="t_cargos" data-field="x_Obs" name="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_cargos->Obs->getPlaceHolder()) ?>" value="<?php echo $t_cargos->Obs->EditValue ?>"<?php echo $t_cargos->Obs->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_cargos_Obs" class="form-group t_cargos_Obs">
<span<?php echo $t_cargos->Obs->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_cargos->Obs->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="x<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_cargos" data-field="x_Obs" name="o<?php echo $t_cargos_grid->RowIndex ?>_Obs" id="o<?php echo $t_cargos_grid->RowIndex ?>_Obs" value="<?php echo ew_HtmlEncode($t_cargos->Obs->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_cargos_grid->ListOptions->Render("body", "right", $t_cargos_grid->RowCnt);
?>
<script type="text/javascript">
ft_cargosgrid.UpdateOpts(<?php echo $t_cargos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_cargos->CurrentMode == "add" || $t_cargos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_cargos_grid->FormKeyCountName ?>" id="<?php echo $t_cargos_grid->FormKeyCountName ?>" value="<?php echo $t_cargos_grid->KeyCount ?>">
<?php echo $t_cargos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_cargos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_cargos_grid->FormKeyCountName ?>" id="<?php echo $t_cargos_grid->FormKeyCountName ?>" value="<?php echo $t_cargos_grid->KeyCount ?>">
<?php echo $t_cargos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_cargos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_cargosgrid">
</div>
<?php

// Close recordset
if ($t_cargos_grid->Recordset)
	$t_cargos_grid->Recordset->Close();
?>
<?php if ($t_cargos_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_cargos_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_cargos_grid->TotalRecs == 0 && $t_cargos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_cargos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_cargos->Export == "") { ?>
<script type="text/javascript">
ft_cargosgrid.Init();
</script>
<?php } ?>
<?php
$t_cargos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_cargos_grid->Page_Terminate();
?>
