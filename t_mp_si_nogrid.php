<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_mp_si_no_grid)) $t_mp_si_no_grid = new ct_mp_si_no_grid();

// Page init
$t_mp_si_no_grid->Page_Init();

// Page main
$t_mp_si_no_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_mp_si_no_grid->Page_Render();
?>
<?php if ($t_mp_si_no->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_mp_si_nogrid = new ew_Form("ft_mp_si_nogrid", "grid");
ft_mp_si_nogrid.FormKeyCountName = '<?php echo $t_mp_si_no_grid->FormKeyCountName ?>';

// Validate form
ft_mp_si_nogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Grado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_mp_si_no->Grado->FldCaption(), $t_mp_si_no->Grado->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_mp_si_nogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Grado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Grado_Si[]", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Grado_No[]", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_mp_si_nogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_mp_si_nogrid.ValidateRequired = true;
<?php } else { ?>
ft_mp_si_nogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_mp_si_nogrid.Lists["x_Grado_Si[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_mp_si_nogrid.Lists["x_Grado_Si[]"].Options = <?php echo json_encode($t_mp_si_no->Grado_Si->Options()) ?>;
ft_mp_si_nogrid.Lists["x_Grado_No[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_mp_si_nogrid.Lists["x_Grado_No[]"].Options = <?php echo json_encode($t_mp_si_no->Grado_No->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($t_mp_si_no->CurrentAction == "gridadd") {
	if ($t_mp_si_no->CurrentMode == "copy") {
		$bSelectLimit = $t_mp_si_no_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_mp_si_no_grid->TotalRecs = $t_mp_si_no->SelectRecordCount();
			$t_mp_si_no_grid->Recordset = $t_mp_si_no_grid->LoadRecordset($t_mp_si_no_grid->StartRec-1, $t_mp_si_no_grid->DisplayRecs);
		} else {
			if ($t_mp_si_no_grid->Recordset = $t_mp_si_no_grid->LoadRecordset())
				$t_mp_si_no_grid->TotalRecs = $t_mp_si_no_grid->Recordset->RecordCount();
		}
		$t_mp_si_no_grid->StartRec = 1;
		$t_mp_si_no_grid->DisplayRecs = $t_mp_si_no_grid->TotalRecs;
	} else {
		$t_mp_si_no->CurrentFilter = "0=1";
		$t_mp_si_no_grid->StartRec = 1;
		$t_mp_si_no_grid->DisplayRecs = $t_mp_si_no->GridAddRowCount;
	}
	$t_mp_si_no_grid->TotalRecs = $t_mp_si_no_grid->DisplayRecs;
	$t_mp_si_no_grid->StopRec = $t_mp_si_no_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_mp_si_no_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_mp_si_no_grid->TotalRecs <= 0)
			$t_mp_si_no_grid->TotalRecs = $t_mp_si_no->SelectRecordCount();
	} else {
		if (!$t_mp_si_no_grid->Recordset && ($t_mp_si_no_grid->Recordset = $t_mp_si_no_grid->LoadRecordset()))
			$t_mp_si_no_grid->TotalRecs = $t_mp_si_no_grid->Recordset->RecordCount();
	}
	$t_mp_si_no_grid->StartRec = 1;
	$t_mp_si_no_grid->DisplayRecs = $t_mp_si_no_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_mp_si_no_grid->Recordset = $t_mp_si_no_grid->LoadRecordset($t_mp_si_no_grid->StartRec-1, $t_mp_si_no_grid->DisplayRecs);

	// Set no record found message
	if ($t_mp_si_no->CurrentAction == "" && $t_mp_si_no_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_mp_si_no_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_mp_si_no_grid->SearchWhere == "0=101")
			$t_mp_si_no_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_mp_si_no_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_mp_si_no_grid->RenderOtherOptions();
?>
<?php $t_mp_si_no_grid->ShowPageHeader(); ?>
<?php
$t_mp_si_no_grid->ShowMessage();
?>
<?php if ($t_mp_si_no_grid->TotalRecs > 0 || $t_mp_si_no->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_mp_si_no">
<div id="ft_mp_si_nogrid" class="ewForm form-inline">
<div id="gmp_t_mp_si_no" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_mp_si_nogrid" class="table ewTable">
<?php echo $t_mp_si_no->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_mp_si_no_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_mp_si_no_grid->RenderListOptions();

// Render list options (header, left)
$t_mp_si_no_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_mp_si_no->Grado->Visible) { // Grado ?>
	<?php if ($t_mp_si_no->SortUrl($t_mp_si_no->Grado) == "") { ?>
		<th data-name="Grado"><div id="elh_t_mp_si_no_Grado" class="t_mp_si_no_Grado"><div class="ewTableHeaderCaption"><?php echo $t_mp_si_no->Grado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Grado"><div><div id="elh_t_mp_si_no_Grado" class="t_mp_si_no_Grado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_mp_si_no->Grado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_mp_si_no->Grado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_mp_si_no->Grado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_mp_si_no->Grado_Si->Visible) { // Grado_Si ?>
	<?php if ($t_mp_si_no->SortUrl($t_mp_si_no->Grado_Si) == "") { ?>
		<th data-name="Grado_Si"><div id="elh_t_mp_si_no_Grado_Si" class="t_mp_si_no_Grado_Si"><div class="ewTableHeaderCaption"><?php echo $t_mp_si_no->Grado_Si->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Grado_Si"><div><div id="elh_t_mp_si_no_Grado_Si" class="t_mp_si_no_Grado_Si">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_mp_si_no->Grado_Si->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_mp_si_no->Grado_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_mp_si_no->Grado_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_mp_si_no->Grado_No->Visible) { // Grado_No ?>
	<?php if ($t_mp_si_no->SortUrl($t_mp_si_no->Grado_No) == "") { ?>
		<th data-name="Grado_No"><div id="elh_t_mp_si_no_Grado_No" class="t_mp_si_no_Grado_No"><div class="ewTableHeaderCaption"><?php echo $t_mp_si_no->Grado_No->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Grado_No"><div><div id="elh_t_mp_si_no_Grado_No" class="t_mp_si_no_Grado_No">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_mp_si_no->Grado_No->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_mp_si_no->Grado_No->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_mp_si_no->Grado_No->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_mp_si_no_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_mp_si_no_grid->StartRec = 1;
$t_mp_si_no_grid->StopRec = $t_mp_si_no_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_mp_si_no_grid->FormKeyCountName) && ($t_mp_si_no->CurrentAction == "gridadd" || $t_mp_si_no->CurrentAction == "gridedit" || $t_mp_si_no->CurrentAction == "F")) {
		$t_mp_si_no_grid->KeyCount = $objForm->GetValue($t_mp_si_no_grid->FormKeyCountName);
		$t_mp_si_no_grid->StopRec = $t_mp_si_no_grid->StartRec + $t_mp_si_no_grid->KeyCount - 1;
	}
}
$t_mp_si_no_grid->RecCnt = $t_mp_si_no_grid->StartRec - 1;
if ($t_mp_si_no_grid->Recordset && !$t_mp_si_no_grid->Recordset->EOF) {
	$t_mp_si_no_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_mp_si_no_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_mp_si_no_grid->StartRec > 1)
		$t_mp_si_no_grid->Recordset->Move($t_mp_si_no_grid->StartRec - 1);
} elseif (!$t_mp_si_no->AllowAddDeleteRow && $t_mp_si_no_grid->StopRec == 0) {
	$t_mp_si_no_grid->StopRec = $t_mp_si_no->GridAddRowCount;
}

// Initialize aggregate
$t_mp_si_no->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_mp_si_no->ResetAttrs();
$t_mp_si_no_grid->RenderRow();
if ($t_mp_si_no->CurrentAction == "gridadd")
	$t_mp_si_no_grid->RowIndex = 0;
if ($t_mp_si_no->CurrentAction == "gridedit")
	$t_mp_si_no_grid->RowIndex = 0;
while ($t_mp_si_no_grid->RecCnt < $t_mp_si_no_grid->StopRec) {
	$t_mp_si_no_grid->RecCnt++;
	if (intval($t_mp_si_no_grid->RecCnt) >= intval($t_mp_si_no_grid->StartRec)) {
		$t_mp_si_no_grid->RowCnt++;
		if ($t_mp_si_no->CurrentAction == "gridadd" || $t_mp_si_no->CurrentAction == "gridedit" || $t_mp_si_no->CurrentAction == "F") {
			$t_mp_si_no_grid->RowIndex++;
			$objForm->Index = $t_mp_si_no_grid->RowIndex;
			if ($objForm->HasValue($t_mp_si_no_grid->FormActionName))
				$t_mp_si_no_grid->RowAction = strval($objForm->GetValue($t_mp_si_no_grid->FormActionName));
			elseif ($t_mp_si_no->CurrentAction == "gridadd")
				$t_mp_si_no_grid->RowAction = "insert";
			else
				$t_mp_si_no_grid->RowAction = "";
		}

		// Set up key count
		$t_mp_si_no_grid->KeyCount = $t_mp_si_no_grid->RowIndex;

		// Init row class and style
		$t_mp_si_no->ResetAttrs();
		$t_mp_si_no->CssClass = "";
		if ($t_mp_si_no->CurrentAction == "gridadd") {
			if ($t_mp_si_no->CurrentMode == "copy") {
				$t_mp_si_no_grid->LoadRowValues($t_mp_si_no_grid->Recordset); // Load row values
				$t_mp_si_no_grid->SetRecordKey($t_mp_si_no_grid->RowOldKey, $t_mp_si_no_grid->Recordset); // Set old record key
			} else {
				$t_mp_si_no_grid->LoadDefaultValues(); // Load default values
				$t_mp_si_no_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_mp_si_no_grid->LoadRowValues($t_mp_si_no_grid->Recordset); // Load row values
		}
		$t_mp_si_no->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_mp_si_no->CurrentAction == "gridadd") // Grid add
			$t_mp_si_no->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_mp_si_no->CurrentAction == "gridadd" && $t_mp_si_no->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_mp_si_no_grid->RestoreCurrentRowFormValues($t_mp_si_no_grid->RowIndex); // Restore form values
		if ($t_mp_si_no->CurrentAction == "gridedit") { // Grid edit
			if ($t_mp_si_no->EventCancelled) {
				$t_mp_si_no_grid->RestoreCurrentRowFormValues($t_mp_si_no_grid->RowIndex); // Restore form values
			}
			if ($t_mp_si_no_grid->RowAction == "insert")
				$t_mp_si_no->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_mp_si_no->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_mp_si_no->CurrentAction == "gridedit" && ($t_mp_si_no->RowType == EW_ROWTYPE_EDIT || $t_mp_si_no->RowType == EW_ROWTYPE_ADD) && $t_mp_si_no->EventCancelled) // Update failed
			$t_mp_si_no_grid->RestoreCurrentRowFormValues($t_mp_si_no_grid->RowIndex); // Restore form values
		if ($t_mp_si_no->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_mp_si_no_grid->EditRowCnt++;
		if ($t_mp_si_no->CurrentAction == "F") // Confirm row
			$t_mp_si_no_grid->RestoreCurrentRowFormValues($t_mp_si_no_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_mp_si_no->RowAttrs = array_merge($t_mp_si_no->RowAttrs, array('data-rowindex'=>$t_mp_si_no_grid->RowCnt, 'id'=>'r' . $t_mp_si_no_grid->RowCnt . '_t_mp_si_no', 'data-rowtype'=>$t_mp_si_no->RowType));

		// Render row
		$t_mp_si_no_grid->RenderRow();

		// Render list options
		$t_mp_si_no_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_mp_si_no_grid->RowAction <> "delete" && $t_mp_si_no_grid->RowAction <> "insertdelete" && !($t_mp_si_no_grid->RowAction == "insert" && $t_mp_si_no->CurrentAction == "F" && $t_mp_si_no_grid->EmptyRow())) {
?>
	<tr<?php echo $t_mp_si_no->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_mp_si_no_grid->ListOptions->Render("body", "left", $t_mp_si_no_grid->RowCnt);
?>
	<?php if ($t_mp_si_no->Grado->Visible) { // Grado ?>
		<td data-name="Grado"<?php echo $t_mp_si_no->Grado->CellAttributes() ?>>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado" class="form-group t_mp_si_no_Grado">
<input type="text" data-table="t_mp_si_no" data-field="x_Grado" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->getPlaceHolder()) ?>" value="<?php echo $t_mp_si_no->Grado->EditValue ?>"<?php echo $t_mp_si_no->Grado->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->OldValue) ?>">
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado" class="form-group t_mp_si_no_Grado">
<span<?php echo $t_mp_si_no->Grado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_mp_si_no->Grado->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->CurrentValue) ?>">
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado" class="t_mp_si_no_Grado">
<span<?php echo $t_mp_si_no->Grado->ViewAttributes() ?>>
<?php echo $t_mp_si_no->Grado->ListViewValue() ?></span>
</span>
<?php if ($t_mp_si_no->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->FormValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="ft_mp_si_nogrid$x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="ft_mp_si_nogrid$x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->FormValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="ft_mp_si_nogrid$o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="ft_mp_si_nogrid$o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_mp_si_no_grid->PageObjName . "_row_" . $t_mp_si_no_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Id" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Id" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_mp_si_no->Id->CurrentValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Id" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Id" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_mp_si_no->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_EDIT || $t_mp_si_no->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Id" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Id" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_mp_si_no->Id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_mp_si_no->Grado_Si->Visible) { // Grado_Si ?>
		<td data-name="Grado_Si"<?php echo $t_mp_si_no->Grado_Si->CellAttributes() ?>>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado_Si" class="form-group t_mp_si_no_Grado_Si">
<div id="tp_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" class="ewTemplate"><input type="checkbox" data-table="t_mp_si_no" data-field="x_Grado_Si" data-value-separator="<?php echo $t_mp_si_no->Grado_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="{value}"<?php echo $t_mp_si_no->Grado_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_mp_si_no->Grado_Si->CheckBoxListHtml(FALSE, "x{$t_mp_si_no_grid->RowIndex}_Grado_Si[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->OldValue) ?>">
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado_Si" class="form-group t_mp_si_no_Grado_Si">
<div id="tp_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" class="ewTemplate"><input type="checkbox" data-table="t_mp_si_no" data-field="x_Grado_Si" data-value-separator="<?php echo $t_mp_si_no->Grado_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="{value}"<?php echo $t_mp_si_no->Grado_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_mp_si_no->Grado_Si->CheckBoxListHtml(FALSE, "x{$t_mp_si_no_grid->RowIndex}_Grado_Si[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado_Si" class="t_mp_si_no_Grado_Si">
<span<?php echo $t_mp_si_no->Grado_Si->ViewAttributes() ?>>
<?php echo $t_mp_si_no->Grado_Si->ListViewValue() ?></span>
</span>
<?php if ($t_mp_si_no->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->FormValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="ft_mp_si_nogrid$x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" id="ft_mp_si_nogrid$x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->FormValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="ft_mp_si_nogrid$o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="ft_mp_si_nogrid$o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_mp_si_no->Grado_No->Visible) { // Grado_No ?>
		<td data-name="Grado_No"<?php echo $t_mp_si_no->Grado_No->CellAttributes() ?>>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado_No" class="form-group t_mp_si_no_Grado_No">
<div id="tp_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" class="ewTemplate"><input type="checkbox" data-table="t_mp_si_no" data-field="x_Grado_No" data-value-separator="<?php echo $t_mp_si_no->Grado_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="{value}"<?php echo $t_mp_si_no->Grado_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_mp_si_no->Grado_No->CheckBoxListHtml(FALSE, "x{$t_mp_si_no_grid->RowIndex}_Grado_No[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->OldValue) ?>">
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado_No" class="form-group t_mp_si_no_Grado_No">
<div id="tp_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" class="ewTemplate"><input type="checkbox" data-table="t_mp_si_no" data-field="x_Grado_No" data-value-separator="<?php echo $t_mp_si_no->Grado_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="{value}"<?php echo $t_mp_si_no->Grado_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_mp_si_no->Grado_No->CheckBoxListHtml(FALSE, "x{$t_mp_si_no_grid->RowIndex}_Grado_No[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_mp_si_no_grid->RowCnt ?>_t_mp_si_no_Grado_No" class="t_mp_si_no_Grado_No">
<span<?php echo $t_mp_si_no->Grado_No->ViewAttributes() ?>>
<?php echo $t_mp_si_no->Grado_No->ListViewValue() ?></span>
</span>
<?php if ($t_mp_si_no->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->FormValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="ft_mp_si_nogrid$x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" id="ft_mp_si_nogrid$x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->FormValue) ?>">
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="ft_mp_si_nogrid$o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="ft_mp_si_nogrid$o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_mp_si_no_grid->ListOptions->Render("body", "right", $t_mp_si_no_grid->RowCnt);
?>
	</tr>
<?php if ($t_mp_si_no->RowType == EW_ROWTYPE_ADD || $t_mp_si_no->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_mp_si_nogrid.UpdateOpts(<?php echo $t_mp_si_no_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_mp_si_no->CurrentAction <> "gridadd" || $t_mp_si_no->CurrentMode == "copy")
		if (!$t_mp_si_no_grid->Recordset->EOF) $t_mp_si_no_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_mp_si_no->CurrentMode == "add" || $t_mp_si_no->CurrentMode == "copy" || $t_mp_si_no->CurrentMode == "edit") {
		$t_mp_si_no_grid->RowIndex = '$rowindex$';
		$t_mp_si_no_grid->LoadDefaultValues();

		// Set row properties
		$t_mp_si_no->ResetAttrs();
		$t_mp_si_no->RowAttrs = array_merge($t_mp_si_no->RowAttrs, array('data-rowindex'=>$t_mp_si_no_grid->RowIndex, 'id'=>'r0_t_mp_si_no', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_mp_si_no->RowAttrs["class"], "ewTemplate");
		$t_mp_si_no->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_mp_si_no_grid->RenderRow();

		// Render list options
		$t_mp_si_no_grid->RenderListOptions();
		$t_mp_si_no_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_mp_si_no->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_mp_si_no_grid->ListOptions->Render("body", "left", $t_mp_si_no_grid->RowIndex);
?>
	<?php if ($t_mp_si_no->Grado->Visible) { // Grado ?>
		<td data-name="Grado">
<?php if ($t_mp_si_no->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_mp_si_no_Grado" class="form-group t_mp_si_no_Grado">
<input type="text" data-table="t_mp_si_no" data-field="x_Grado" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->getPlaceHolder()) ?>" value="<?php echo $t_mp_si_no->Grado->EditValue ?>"<?php echo $t_mp_si_no->Grado->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_mp_si_no_Grado" class="form-group t_mp_si_no_Grado">
<span<?php echo $t_mp_si_no->Grado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_mp_si_no->Grado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_mp_si_no->Grado_Si->Visible) { // Grado_Si ?>
		<td data-name="Grado_Si">
<?php if ($t_mp_si_no->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_mp_si_no_Grado_Si" class="form-group t_mp_si_no_Grado_Si">
<div id="tp_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" class="ewTemplate"><input type="checkbox" data-table="t_mp_si_no" data-field="x_Grado_Si" data-value-separator="<?php echo $t_mp_si_no->Grado_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="{value}"<?php echo $t_mp_si_no->Grado_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_mp_si_no->Grado_Si->CheckBoxListHtml(FALSE, "x{$t_mp_si_no_grid->RowIndex}_Grado_Si[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_mp_si_no_Grado_Si" class="form-group t_mp_si_no_Grado_Si">
<span<?php echo $t_mp_si_no->Grado_Si->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_mp_si_no->Grado_Si->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_Si" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_Si[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_Si->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_mp_si_no->Grado_No->Visible) { // Grado_No ?>
		<td data-name="Grado_No">
<?php if ($t_mp_si_no->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_mp_si_no_Grado_No" class="form-group t_mp_si_no_Grado_No">
<div id="tp_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" class="ewTemplate"><input type="checkbox" data-table="t_mp_si_no" data-field="x_Grado_No" data-value-separator="<?php echo $t_mp_si_no->Grado_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="{value}"<?php echo $t_mp_si_no->Grado_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_mp_si_no->Grado_No->CheckBoxListHtml(FALSE, "x{$t_mp_si_no_grid->RowIndex}_Grado_No[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_mp_si_no_Grado_No" class="form-group t_mp_si_no_Grado_No">
<span<?php echo $t_mp_si_no->Grado_No->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_mp_si_no->Grado_No->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" id="x<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_mp_si_no" data-field="x_Grado_No" name="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" id="o<?php echo $t_mp_si_no_grid->RowIndex ?>_Grado_No[]" value="<?php echo ew_HtmlEncode($t_mp_si_no->Grado_No->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_mp_si_no_grid->ListOptions->Render("body", "right", $t_mp_si_no_grid->RowCnt);
?>
<script type="text/javascript">
ft_mp_si_nogrid.UpdateOpts(<?php echo $t_mp_si_no_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_mp_si_no->CurrentMode == "add" || $t_mp_si_no->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_mp_si_no_grid->FormKeyCountName ?>" id="<?php echo $t_mp_si_no_grid->FormKeyCountName ?>" value="<?php echo $t_mp_si_no_grid->KeyCount ?>">
<?php echo $t_mp_si_no_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_mp_si_no->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_mp_si_no_grid->FormKeyCountName ?>" id="<?php echo $t_mp_si_no_grid->FormKeyCountName ?>" value="<?php echo $t_mp_si_no_grid->KeyCount ?>">
<?php echo $t_mp_si_no_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_mp_si_no->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_mp_si_nogrid">
</div>
<?php

// Close recordset
if ($t_mp_si_no_grid->Recordset)
	$t_mp_si_no_grid->Recordset->Close();
?>
<?php if ($t_mp_si_no_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_mp_si_no_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_mp_si_no_grid->TotalRecs == 0 && $t_mp_si_no->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_mp_si_no_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_mp_si_no->Export == "") { ?>
<script type="text/javascript">
ft_mp_si_nogrid.Init();
</script>
<?php } ?>
<?php
$t_mp_si_no_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_mp_si_no_grid->Page_Terminate();
?>
