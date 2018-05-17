<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_salario_grid)) $t_salario_grid = new ct_salario_grid();

// Page init
$t_salario_grid->Page_Init();

// Page main
$t_salario_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_salario_grid->Page_Render();
?>
<?php if ($t_salario->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_salariogrid = new ew_Form("ft_salariogrid", "grid");
ft_salariogrid.FormKeyCountName = '<?php echo $t_salario_grid->FormKeyCountName ?>';

// Validate form
ft_salariogrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_salario->Id->FldCaption(), $t_salario->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_salario->Id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_salariogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Salario_Mayor_Si[]", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Salario_Mayor_No[]", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_salariogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_salariogrid.ValidateRequired = true;
<?php } else { ?>
ft_salariogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_salariogrid.Lists["x_Salario_Mayor_Si[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_salariogrid.Lists["x_Salario_Mayor_Si[]"].Options = <?php echo json_encode($t_salario->Salario_Mayor_Si->Options()) ?>;
ft_salariogrid.Lists["x_Salario_Mayor_No[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_salariogrid.Lists["x_Salario_Mayor_No[]"].Options = <?php echo json_encode($t_salario->Salario_Mayor_No->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($t_salario->CurrentAction == "gridadd") {
	if ($t_salario->CurrentMode == "copy") {
		$bSelectLimit = $t_salario_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_salario_grid->TotalRecs = $t_salario->SelectRecordCount();
			$t_salario_grid->Recordset = $t_salario_grid->LoadRecordset($t_salario_grid->StartRec-1, $t_salario_grid->DisplayRecs);
		} else {
			if ($t_salario_grid->Recordset = $t_salario_grid->LoadRecordset())
				$t_salario_grid->TotalRecs = $t_salario_grid->Recordset->RecordCount();
		}
		$t_salario_grid->StartRec = 1;
		$t_salario_grid->DisplayRecs = $t_salario_grid->TotalRecs;
	} else {
		$t_salario->CurrentFilter = "0=1";
		$t_salario_grid->StartRec = 1;
		$t_salario_grid->DisplayRecs = $t_salario->GridAddRowCount;
	}
	$t_salario_grid->TotalRecs = $t_salario_grid->DisplayRecs;
	$t_salario_grid->StopRec = $t_salario_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_salario_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_salario_grid->TotalRecs <= 0)
			$t_salario_grid->TotalRecs = $t_salario->SelectRecordCount();
	} else {
		if (!$t_salario_grid->Recordset && ($t_salario_grid->Recordset = $t_salario_grid->LoadRecordset()))
			$t_salario_grid->TotalRecs = $t_salario_grid->Recordset->RecordCount();
	}
	$t_salario_grid->StartRec = 1;
	$t_salario_grid->DisplayRecs = $t_salario_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_salario_grid->Recordset = $t_salario_grid->LoadRecordset($t_salario_grid->StartRec-1, $t_salario_grid->DisplayRecs);

	// Set no record found message
	if ($t_salario->CurrentAction == "" && $t_salario_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_salario_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_salario_grid->SearchWhere == "0=101")
			$t_salario_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_salario_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_salario_grid->RenderOtherOptions();
?>
<?php $t_salario_grid->ShowPageHeader(); ?>
<?php
$t_salario_grid->ShowMessage();
?>
<?php if ($t_salario_grid->TotalRecs > 0 || $t_salario->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_salario">
<div id="ft_salariogrid" class="ewForm form-inline">
<div id="gmp_t_salario" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_salariogrid" class="table ewTable">
<?php echo $t_salario->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_salario_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_salario_grid->RenderListOptions();

// Render list options (header, left)
$t_salario_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_salario->Id->Visible) { // Id ?>
	<?php if ($t_salario->SortUrl($t_salario->Id) == "") { ?>
		<th data-name="Id"><div id="elh_t_salario_Id" class="t_salario_Id"><div class="ewTableHeaderCaption"><?php echo $t_salario->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div><div id="elh_t_salario_Id" class="t_salario_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_salario->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_salario->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_salario->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_salario->Salario_Mayor_Si->Visible) { // Salario_Mayor_Si ?>
	<?php if ($t_salario->SortUrl($t_salario->Salario_Mayor_Si) == "") { ?>
		<th data-name="Salario_Mayor_Si"><div id="elh_t_salario_Salario_Mayor_Si" class="t_salario_Salario_Mayor_Si"><div class="ewTableHeaderCaption"><?php echo $t_salario->Salario_Mayor_Si->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Salario_Mayor_Si"><div><div id="elh_t_salario_Salario_Mayor_Si" class="t_salario_Salario_Mayor_Si">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_salario->Salario_Mayor_Si->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_salario->Salario_Mayor_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_salario->Salario_Mayor_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_salario->Salario_Mayor_No->Visible) { // Salario_Mayor_No ?>
	<?php if ($t_salario->SortUrl($t_salario->Salario_Mayor_No) == "") { ?>
		<th data-name="Salario_Mayor_No"><div id="elh_t_salario_Salario_Mayor_No" class="t_salario_Salario_Mayor_No"><div class="ewTableHeaderCaption"><?php echo $t_salario->Salario_Mayor_No->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Salario_Mayor_No"><div><div id="elh_t_salario_Salario_Mayor_No" class="t_salario_Salario_Mayor_No">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_salario->Salario_Mayor_No->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_salario->Salario_Mayor_No->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_salario->Salario_Mayor_No->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_salario_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_salario_grid->StartRec = 1;
$t_salario_grid->StopRec = $t_salario_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_salario_grid->FormKeyCountName) && ($t_salario->CurrentAction == "gridadd" || $t_salario->CurrentAction == "gridedit" || $t_salario->CurrentAction == "F")) {
		$t_salario_grid->KeyCount = $objForm->GetValue($t_salario_grid->FormKeyCountName);
		$t_salario_grid->StopRec = $t_salario_grid->StartRec + $t_salario_grid->KeyCount - 1;
	}
}
$t_salario_grid->RecCnt = $t_salario_grid->StartRec - 1;
if ($t_salario_grid->Recordset && !$t_salario_grid->Recordset->EOF) {
	$t_salario_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_salario_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_salario_grid->StartRec > 1)
		$t_salario_grid->Recordset->Move($t_salario_grid->StartRec - 1);
} elseif (!$t_salario->AllowAddDeleteRow && $t_salario_grid->StopRec == 0) {
	$t_salario_grid->StopRec = $t_salario->GridAddRowCount;
}

// Initialize aggregate
$t_salario->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_salario->ResetAttrs();
$t_salario_grid->RenderRow();
if ($t_salario->CurrentAction == "gridadd")
	$t_salario_grid->RowIndex = 0;
if ($t_salario->CurrentAction == "gridedit")
	$t_salario_grid->RowIndex = 0;
while ($t_salario_grid->RecCnt < $t_salario_grid->StopRec) {
	$t_salario_grid->RecCnt++;
	if (intval($t_salario_grid->RecCnt) >= intval($t_salario_grid->StartRec)) {
		$t_salario_grid->RowCnt++;
		if ($t_salario->CurrentAction == "gridadd" || $t_salario->CurrentAction == "gridedit" || $t_salario->CurrentAction == "F") {
			$t_salario_grid->RowIndex++;
			$objForm->Index = $t_salario_grid->RowIndex;
			if ($objForm->HasValue($t_salario_grid->FormActionName))
				$t_salario_grid->RowAction = strval($objForm->GetValue($t_salario_grid->FormActionName));
			elseif ($t_salario->CurrentAction == "gridadd")
				$t_salario_grid->RowAction = "insert";
			else
				$t_salario_grid->RowAction = "";
		}

		// Set up key count
		$t_salario_grid->KeyCount = $t_salario_grid->RowIndex;

		// Init row class and style
		$t_salario->ResetAttrs();
		$t_salario->CssClass = "";
		if ($t_salario->CurrentAction == "gridadd") {
			if ($t_salario->CurrentMode == "copy") {
				$t_salario_grid->LoadRowValues($t_salario_grid->Recordset); // Load row values
				$t_salario_grid->SetRecordKey($t_salario_grid->RowOldKey, $t_salario_grid->Recordset); // Set old record key
			} else {
				$t_salario_grid->LoadDefaultValues(); // Load default values
				$t_salario_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_salario_grid->LoadRowValues($t_salario_grid->Recordset); // Load row values
		}
		$t_salario->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_salario->CurrentAction == "gridadd") // Grid add
			$t_salario->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_salario->CurrentAction == "gridadd" && $t_salario->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_salario_grid->RestoreCurrentRowFormValues($t_salario_grid->RowIndex); // Restore form values
		if ($t_salario->CurrentAction == "gridedit") { // Grid edit
			if ($t_salario->EventCancelled) {
				$t_salario_grid->RestoreCurrentRowFormValues($t_salario_grid->RowIndex); // Restore form values
			}
			if ($t_salario_grid->RowAction == "insert")
				$t_salario->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_salario->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_salario->CurrentAction == "gridedit" && ($t_salario->RowType == EW_ROWTYPE_EDIT || $t_salario->RowType == EW_ROWTYPE_ADD) && $t_salario->EventCancelled) // Update failed
			$t_salario_grid->RestoreCurrentRowFormValues($t_salario_grid->RowIndex); // Restore form values
		if ($t_salario->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_salario_grid->EditRowCnt++;
		if ($t_salario->CurrentAction == "F") // Confirm row
			$t_salario_grid->RestoreCurrentRowFormValues($t_salario_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_salario->RowAttrs = array_merge($t_salario->RowAttrs, array('data-rowindex'=>$t_salario_grid->RowCnt, 'id'=>'r' . $t_salario_grid->RowCnt . '_t_salario', 'data-rowtype'=>$t_salario->RowType));

		// Render row
		$t_salario_grid->RenderRow();

		// Render list options
		$t_salario_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_salario_grid->RowAction <> "delete" && $t_salario_grid->RowAction <> "insertdelete" && !($t_salario_grid->RowAction == "insert" && $t_salario->CurrentAction == "F" && $t_salario_grid->EmptyRow())) {
?>
	<tr<?php echo $t_salario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_salario_grid->ListOptions->Render("body", "left", $t_salario_grid->RowCnt);
?>
	<?php if ($t_salario->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $t_salario->Id->CellAttributes() ?>>
<?php if ($t_salario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_salario->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Id" class="form-group t_salario_Id">
<span<?php echo $t_salario->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_salario->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Id" class="form-group t_salario_Id">
<input type="text" data-table="t_salario" data-field="x_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_salario->Id->getPlaceHolder()) ?>" value="<?php echo $t_salario->Id->EditValue ?>"<?php echo $t_salario->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_salario" data-field="x_Id" name="o<?php echo $t_salario_grid->RowIndex ?>_Id" id="o<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_salario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Id" class="form-group t_salario_Id">
<span<?php echo $t_salario->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_salario->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_salario" data-field="x_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($t_salario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Id" class="t_salario_Id">
<span<?php echo $t_salario->Id->ViewAttributes() ?>>
<?php echo $t_salario->Id->ListViewValue() ?></span>
</span>
<?php if ($t_salario->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_salario" data-field="x_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->FormValue) ?>">
<input type="hidden" data-table="t_salario" data-field="x_Id" name="o<?php echo $t_salario_grid->RowIndex ?>_Id" id="o<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_salario" data-field="x_Id" name="ft_salariogrid$x<?php echo $t_salario_grid->RowIndex ?>_Id" id="ft_salariogrid$x<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->FormValue) ?>">
<input type="hidden" data-table="t_salario" data-field="x_Id" name="ft_salariogrid$o<?php echo $t_salario_grid->RowIndex ?>_Id" id="ft_salariogrid$o<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_salario_grid->PageObjName . "_row_" . $t_salario_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_salario->Salario_Mayor_Si->Visible) { // Salario_Mayor_Si ?>
		<td data-name="Salario_Mayor_Si"<?php echo $t_salario->Salario_Mayor_Si->CellAttributes() ?>>
<?php if ($t_salario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Salario_Mayor_Si" class="form-group t_salario_Salario_Mayor_Si">
<div id="tp_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" class="ewTemplate"><input type="checkbox" data-table="t_salario" data-field="x_Salario_Mayor_Si" data-value-separator="<?php echo $t_salario->Salario_Mayor_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="{value}"<?php echo $t_salario->Salario_Mayor_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_salario->Salario_Mayor_Si->CheckBoxListHtml(FALSE, "x{$t_salario_grid->RowIndex}_Salario_Mayor_Si[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->OldValue) ?>">
<?php } ?>
<?php if ($t_salario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Salario_Mayor_Si" class="form-group t_salario_Salario_Mayor_Si">
<div id="tp_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" class="ewTemplate"><input type="checkbox" data-table="t_salario" data-field="x_Salario_Mayor_Si" data-value-separator="<?php echo $t_salario->Salario_Mayor_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="{value}"<?php echo $t_salario->Salario_Mayor_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_salario->Salario_Mayor_Si->CheckBoxListHtml(FALSE, "x{$t_salario_grid->RowIndex}_Salario_Mayor_Si[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_salario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Salario_Mayor_Si" class="t_salario_Salario_Mayor_Si">
<span<?php echo $t_salario->Salario_Mayor_Si->ViewAttributes() ?>>
<?php echo $t_salario->Salario_Mayor_Si->ListViewValue() ?></span>
</span>
<?php if ($t_salario->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->FormValue) ?>">
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="ft_salariogrid$x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" id="ft_salariogrid$x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->FormValue) ?>">
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="ft_salariogrid$o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="ft_salariogrid$o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_salario->Salario_Mayor_No->Visible) { // Salario_Mayor_No ?>
		<td data-name="Salario_Mayor_No"<?php echo $t_salario->Salario_Mayor_No->CellAttributes() ?>>
<?php if ($t_salario->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Salario_Mayor_No" class="form-group t_salario_Salario_Mayor_No">
<div id="tp_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" class="ewTemplate"><input type="checkbox" data-table="t_salario" data-field="x_Salario_Mayor_No" data-value-separator="<?php echo $t_salario->Salario_Mayor_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="{value}"<?php echo $t_salario->Salario_Mayor_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_salario->Salario_Mayor_No->CheckBoxListHtml(FALSE, "x{$t_salario_grid->RowIndex}_Salario_Mayor_No[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->OldValue) ?>">
<?php } ?>
<?php if ($t_salario->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Salario_Mayor_No" class="form-group t_salario_Salario_Mayor_No">
<div id="tp_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" class="ewTemplate"><input type="checkbox" data-table="t_salario" data-field="x_Salario_Mayor_No" data-value-separator="<?php echo $t_salario->Salario_Mayor_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="{value}"<?php echo $t_salario->Salario_Mayor_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_salario->Salario_Mayor_No->CheckBoxListHtml(FALSE, "x{$t_salario_grid->RowIndex}_Salario_Mayor_No[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_salario->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_salario_grid->RowCnt ?>_t_salario_Salario_Mayor_No" class="t_salario_Salario_Mayor_No">
<span<?php echo $t_salario->Salario_Mayor_No->ViewAttributes() ?>>
<?php echo $t_salario->Salario_Mayor_No->ListViewValue() ?></span>
</span>
<?php if ($t_salario->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->FormValue) ?>">
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="ft_salariogrid$x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" id="ft_salariogrid$x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->FormValue) ?>">
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="ft_salariogrid$o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="ft_salariogrid$o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_salario_grid->ListOptions->Render("body", "right", $t_salario_grid->RowCnt);
?>
	</tr>
<?php if ($t_salario->RowType == EW_ROWTYPE_ADD || $t_salario->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_salariogrid.UpdateOpts(<?php echo $t_salario_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_salario->CurrentAction <> "gridadd" || $t_salario->CurrentMode == "copy")
		if (!$t_salario_grid->Recordset->EOF) $t_salario_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_salario->CurrentMode == "add" || $t_salario->CurrentMode == "copy" || $t_salario->CurrentMode == "edit") {
		$t_salario_grid->RowIndex = '$rowindex$';
		$t_salario_grid->LoadDefaultValues();

		// Set row properties
		$t_salario->ResetAttrs();
		$t_salario->RowAttrs = array_merge($t_salario->RowAttrs, array('data-rowindex'=>$t_salario_grid->RowIndex, 'id'=>'r0_t_salario', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_salario->RowAttrs["class"], "ewTemplate");
		$t_salario->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_salario_grid->RenderRow();

		// Render list options
		$t_salario_grid->RenderListOptions();
		$t_salario_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_salario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_salario_grid->ListOptions->Render("body", "left", $t_salario_grid->RowIndex);
?>
	<?php if ($t_salario->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($t_salario->CurrentAction <> "F") { ?>
<?php if ($t_salario->Id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_salario_Id" class="form-group t_salario_Id">
<span<?php echo $t_salario->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_salario->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_salario_Id" class="form-group t_salario_Id">
<input type="text" data-table="t_salario" data-field="x_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_salario->Id->getPlaceHolder()) ?>" value="<?php echo $t_salario->Id->EditValue ?>"<?php echo $t_salario->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_salario_Id" class="form-group t_salario_Id">
<span<?php echo $t_salario->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_salario->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_salario" data-field="x_Id" name="x<?php echo $t_salario_grid->RowIndex ?>_Id" id="x<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_salario" data-field="x_Id" name="o<?php echo $t_salario_grid->RowIndex ?>_Id" id="o<?php echo $t_salario_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_salario->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_salario->Salario_Mayor_Si->Visible) { // Salario_Mayor_Si ?>
		<td data-name="Salario_Mayor_Si">
<?php if ($t_salario->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_salario_Salario_Mayor_Si" class="form-group t_salario_Salario_Mayor_Si">
<div id="tp_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" class="ewTemplate"><input type="checkbox" data-table="t_salario" data-field="x_Salario_Mayor_Si" data-value-separator="<?php echo $t_salario->Salario_Mayor_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="{value}"<?php echo $t_salario->Salario_Mayor_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_salario->Salario_Mayor_Si->CheckBoxListHtml(FALSE, "x{$t_salario_grid->RowIndex}_Salario_Mayor_Si[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_salario_Salario_Mayor_Si" class="form-group t_salario_Salario_Mayor_Si">
<span<?php echo $t_salario->Salario_Mayor_Si->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_salario->Salario_Mayor_Si->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_Si" name="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" id="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_Si[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_Si->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_salario->Salario_Mayor_No->Visible) { // Salario_Mayor_No ?>
		<td data-name="Salario_Mayor_No">
<?php if ($t_salario->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_salario_Salario_Mayor_No" class="form-group t_salario_Salario_Mayor_No">
<div id="tp_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" class="ewTemplate"><input type="checkbox" data-table="t_salario" data-field="x_Salario_Mayor_No" data-value-separator="<?php echo $t_salario->Salario_Mayor_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="{value}"<?php echo $t_salario->Salario_Mayor_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_salario->Salario_Mayor_No->CheckBoxListHtml(FALSE, "x{$t_salario_grid->RowIndex}_Salario_Mayor_No[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_salario_Salario_Mayor_No" class="form-group t_salario_Salario_Mayor_No">
<span<?php echo $t_salario->Salario_Mayor_No->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_salario->Salario_Mayor_No->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" id="x<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_salario" data-field="x_Salario_Mayor_No" name="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" id="o<?php echo $t_salario_grid->RowIndex ?>_Salario_Mayor_No[]" value="<?php echo ew_HtmlEncode($t_salario->Salario_Mayor_No->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_salario_grid->ListOptions->Render("body", "right", $t_salario_grid->RowCnt);
?>
<script type="text/javascript">
ft_salariogrid.UpdateOpts(<?php echo $t_salario_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_salario->CurrentMode == "add" || $t_salario->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_salario_grid->FormKeyCountName ?>" id="<?php echo $t_salario_grid->FormKeyCountName ?>" value="<?php echo $t_salario_grid->KeyCount ?>">
<?php echo $t_salario_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_salario->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_salario_grid->FormKeyCountName ?>" id="<?php echo $t_salario_grid->FormKeyCountName ?>" value="<?php echo $t_salario_grid->KeyCount ?>">
<?php echo $t_salario_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_salario->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_salariogrid">
</div>
<?php

// Close recordset
if ($t_salario_grid->Recordset)
	$t_salario_grid->Recordset->Close();
?>
<?php if ($t_salario_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_salario_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_salario_grid->TotalRecs == 0 && $t_salario->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_salario_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_salario->Export == "") { ?>
<script type="text/javascript">
ft_salariogrid.Init();
</script>
<?php } ?>
<?php
$t_salario_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_salario_grid->Page_Terminate();
?>
