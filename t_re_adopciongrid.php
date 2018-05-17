<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_re_adopcion_grid)) $t_re_adopcion_grid = new ct_re_adopcion_grid();

// Page init
$t_re_adopcion_grid->Page_Init();

// Page main
$t_re_adopcion_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_re_adopcion_grid->Page_Render();
?>
<?php if ($t_re_adopcion->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_re_adopciongrid = new ew_Form("ft_re_adopciongrid", "grid");
ft_re_adopciongrid.FormKeyCountName = '<?php echo $t_re_adopcion_grid->FormKeyCountName ?>';

// Validate form
ft_re_adopciongrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_re_adopcion->id->FldCaption(), $t_re_adopcion->id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_re_adopciongrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Parentesco", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_re_adopciongrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_re_adopciongrid.ValidateRequired = true;
<?php } else { ?>
ft_re_adopciongrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_re_adopciongrid.Lists["x_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_adopcion"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_re_adopcion->CurrentAction == "gridadd") {
	if ($t_re_adopcion->CurrentMode == "copy") {
		$bSelectLimit = $t_re_adopcion_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_re_adopcion_grid->TotalRecs = $t_re_adopcion->SelectRecordCount();
			$t_re_adopcion_grid->Recordset = $t_re_adopcion_grid->LoadRecordset($t_re_adopcion_grid->StartRec-1, $t_re_adopcion_grid->DisplayRecs);
		} else {
			if ($t_re_adopcion_grid->Recordset = $t_re_adopcion_grid->LoadRecordset())
				$t_re_adopcion_grid->TotalRecs = $t_re_adopcion_grid->Recordset->RecordCount();
		}
		$t_re_adopcion_grid->StartRec = 1;
		$t_re_adopcion_grid->DisplayRecs = $t_re_adopcion_grid->TotalRecs;
	} else {
		$t_re_adopcion->CurrentFilter = "0=1";
		$t_re_adopcion_grid->StartRec = 1;
		$t_re_adopcion_grid->DisplayRecs = $t_re_adopcion->GridAddRowCount;
	}
	$t_re_adopcion_grid->TotalRecs = $t_re_adopcion_grid->DisplayRecs;
	$t_re_adopcion_grid->StopRec = $t_re_adopcion_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_re_adopcion_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_re_adopcion_grid->TotalRecs <= 0)
			$t_re_adopcion_grid->TotalRecs = $t_re_adopcion->SelectRecordCount();
	} else {
		if (!$t_re_adopcion_grid->Recordset && ($t_re_adopcion_grid->Recordset = $t_re_adopcion_grid->LoadRecordset()))
			$t_re_adopcion_grid->TotalRecs = $t_re_adopcion_grid->Recordset->RecordCount();
	}
	$t_re_adopcion_grid->StartRec = 1;
	$t_re_adopcion_grid->DisplayRecs = $t_re_adopcion_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_re_adopcion_grid->Recordset = $t_re_adopcion_grid->LoadRecordset($t_re_adopcion_grid->StartRec-1, $t_re_adopcion_grid->DisplayRecs);

	// Set no record found message
	if ($t_re_adopcion->CurrentAction == "" && $t_re_adopcion_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_re_adopcion_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_re_adopcion_grid->SearchWhere == "0=101")
			$t_re_adopcion_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_re_adopcion_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_re_adopcion_grid->RenderOtherOptions();
?>
<?php $t_re_adopcion_grid->ShowPageHeader(); ?>
<?php
$t_re_adopcion_grid->ShowMessage();
?>
<?php if ($t_re_adopcion_grid->TotalRecs > 0 || $t_re_adopcion->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_re_adopcion">
<div id="ft_re_adopciongrid" class="ewForm form-inline">
<div id="gmp_t_re_adopcion" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_re_adopciongrid" class="table ewTable">
<?php echo $t_re_adopcion->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_re_adopcion_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_re_adopcion_grid->RenderListOptions();

// Render list options (header, left)
$t_re_adopcion_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_re_adopcion->id->Visible) { // id ?>
	<?php if ($t_re_adopcion->SortUrl($t_re_adopcion->id) == "") { ?>
		<th data-name="id"><div id="elh_t_re_adopcion_id" class="t_re_adopcion_id"><div class="ewTableHeaderCaption"><?php echo $t_re_adopcion->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_t_re_adopcion_id" class="t_re_adopcion_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_re_adopcion->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_re_adopcion->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_re_adopcion->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_re_adopcion->Nombres->Visible) { // Nombres ?>
	<?php if ($t_re_adopcion->SortUrl($t_re_adopcion->Nombres) == "") { ?>
		<th data-name="Nombres"><div id="elh_t_re_adopcion_Nombres" class="t_re_adopcion_Nombres"><div class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres"><div><div id="elh_t_re_adopcion_Nombres" class="t_re_adopcion_Nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_re_adopcion->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_re_adopcion->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_re_adopcion->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($t_re_adopcion->SortUrl($t_re_adopcion->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_t_re_adopcion_Apellido_Paterno" class="t_re_adopcion_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div><div id="elh_t_re_adopcion_Apellido_Paterno" class="t_re_adopcion_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Apellido_Paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_re_adopcion->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_re_adopcion->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_re_adopcion->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($t_re_adopcion->SortUrl($t_re_adopcion->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_t_re_adopcion_Apellido_Materno" class="t_re_adopcion_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div><div id="elh_t_re_adopcion_Apellido_Materno" class="t_re_adopcion_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Apellido_Materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_re_adopcion->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_re_adopcion->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_re_adopcion->Parentesco->Visible) { // Parentesco ?>
	<?php if ($t_re_adopcion->SortUrl($t_re_adopcion->Parentesco) == "") { ?>
		<th data-name="Parentesco"><div id="elh_t_re_adopcion_Parentesco" class="t_re_adopcion_Parentesco"><div class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Parentesco"><div><div id="elh_t_re_adopcion_Parentesco" class="t_re_adopcion_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_re_adopcion->Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_re_adopcion->Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_re_adopcion->Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_re_adopcion_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_re_adopcion_grid->StartRec = 1;
$t_re_adopcion_grid->StopRec = $t_re_adopcion_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_re_adopcion_grid->FormKeyCountName) && ($t_re_adopcion->CurrentAction == "gridadd" || $t_re_adopcion->CurrentAction == "gridedit" || $t_re_adopcion->CurrentAction == "F")) {
		$t_re_adopcion_grid->KeyCount = $objForm->GetValue($t_re_adopcion_grid->FormKeyCountName);
		$t_re_adopcion_grid->StopRec = $t_re_adopcion_grid->StartRec + $t_re_adopcion_grid->KeyCount - 1;
	}
}
$t_re_adopcion_grid->RecCnt = $t_re_adopcion_grid->StartRec - 1;
if ($t_re_adopcion_grid->Recordset && !$t_re_adopcion_grid->Recordset->EOF) {
	$t_re_adopcion_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_re_adopcion_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_re_adopcion_grid->StartRec > 1)
		$t_re_adopcion_grid->Recordset->Move($t_re_adopcion_grid->StartRec - 1);
} elseif (!$t_re_adopcion->AllowAddDeleteRow && $t_re_adopcion_grid->StopRec == 0) {
	$t_re_adopcion_grid->StopRec = $t_re_adopcion->GridAddRowCount;
}

// Initialize aggregate
$t_re_adopcion->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_re_adopcion->ResetAttrs();
$t_re_adopcion_grid->RenderRow();
if ($t_re_adopcion->CurrentAction == "gridadd")
	$t_re_adopcion_grid->RowIndex = 0;
if ($t_re_adopcion->CurrentAction == "gridedit")
	$t_re_adopcion_grid->RowIndex = 0;
while ($t_re_adopcion_grid->RecCnt < $t_re_adopcion_grid->StopRec) {
	$t_re_adopcion_grid->RecCnt++;
	if (intval($t_re_adopcion_grid->RecCnt) >= intval($t_re_adopcion_grid->StartRec)) {
		$t_re_adopcion_grid->RowCnt++;
		if ($t_re_adopcion->CurrentAction == "gridadd" || $t_re_adopcion->CurrentAction == "gridedit" || $t_re_adopcion->CurrentAction == "F") {
			$t_re_adopcion_grid->RowIndex++;
			$objForm->Index = $t_re_adopcion_grid->RowIndex;
			if ($objForm->HasValue($t_re_adopcion_grid->FormActionName))
				$t_re_adopcion_grid->RowAction = strval($objForm->GetValue($t_re_adopcion_grid->FormActionName));
			elseif ($t_re_adopcion->CurrentAction == "gridadd")
				$t_re_adopcion_grid->RowAction = "insert";
			else
				$t_re_adopcion_grid->RowAction = "";
		}

		// Set up key count
		$t_re_adopcion_grid->KeyCount = $t_re_adopcion_grid->RowIndex;

		// Init row class and style
		$t_re_adopcion->ResetAttrs();
		$t_re_adopcion->CssClass = "";
		if ($t_re_adopcion->CurrentAction == "gridadd") {
			if ($t_re_adopcion->CurrentMode == "copy") {
				$t_re_adopcion_grid->LoadRowValues($t_re_adopcion_grid->Recordset); // Load row values
				$t_re_adopcion_grid->SetRecordKey($t_re_adopcion_grid->RowOldKey, $t_re_adopcion_grid->Recordset); // Set old record key
			} else {
				$t_re_adopcion_grid->LoadDefaultValues(); // Load default values
				$t_re_adopcion_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_re_adopcion_grid->LoadRowValues($t_re_adopcion_grid->Recordset); // Load row values
		}
		$t_re_adopcion->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_re_adopcion->CurrentAction == "gridadd") // Grid add
			$t_re_adopcion->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_re_adopcion->CurrentAction == "gridadd" && $t_re_adopcion->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_re_adopcion_grid->RestoreCurrentRowFormValues($t_re_adopcion_grid->RowIndex); // Restore form values
		if ($t_re_adopcion->CurrentAction == "gridedit") { // Grid edit
			if ($t_re_adopcion->EventCancelled) {
				$t_re_adopcion_grid->RestoreCurrentRowFormValues($t_re_adopcion_grid->RowIndex); // Restore form values
			}
			if ($t_re_adopcion_grid->RowAction == "insert")
				$t_re_adopcion->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_re_adopcion->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_re_adopcion->CurrentAction == "gridedit" && ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT || $t_re_adopcion->RowType == EW_ROWTYPE_ADD) && $t_re_adopcion->EventCancelled) // Update failed
			$t_re_adopcion_grid->RestoreCurrentRowFormValues($t_re_adopcion_grid->RowIndex); // Restore form values
		if ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_re_adopcion_grid->EditRowCnt++;
		if ($t_re_adopcion->CurrentAction == "F") // Confirm row
			$t_re_adopcion_grid->RestoreCurrentRowFormValues($t_re_adopcion_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_re_adopcion->RowAttrs = array_merge($t_re_adopcion->RowAttrs, array('data-rowindex'=>$t_re_adopcion_grid->RowCnt, 'id'=>'r' . $t_re_adopcion_grid->RowCnt . '_t_re_adopcion', 'data-rowtype'=>$t_re_adopcion->RowType));

		// Render row
		$t_re_adopcion_grid->RenderRow();

		// Render list options
		$t_re_adopcion_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_re_adopcion_grid->RowAction <> "delete" && $t_re_adopcion_grid->RowAction <> "insertdelete" && !($t_re_adopcion_grid->RowAction == "insert" && $t_re_adopcion->CurrentAction == "F" && $t_re_adopcion_grid->EmptyRow())) {
?>
	<tr<?php echo $t_re_adopcion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_re_adopcion_grid->ListOptions->Render("body", "left", $t_re_adopcion_grid->RowCnt);
?>
	<?php if ($t_re_adopcion->id->Visible) { // id ?>
		<td data-name="id"<?php echo $t_re_adopcion->id->CellAttributes() ?>>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_re_adopcion->id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<span<?php echo $t_re_adopcion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<input type="text" data-table="t_re_adopcion" data-field="x_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->id->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->id->EditValue ?>"<?php echo $t_re_adopcion->id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->OldValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_re_adopcion->id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<span<?php echo $t_re_adopcion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<input type="text" data-table="t_re_adopcion" data-field="x_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->id->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->id->EditValue ?>"<?php echo $t_re_adopcion->id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_id" class="t_re_adopcion_id">
<span<?php echo $t_re_adopcion->id->ViewAttributes() ?>>
<?php echo $t_re_adopcion->id->ListViewValue() ?></span>
</span>
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_re_adopcion_grid->PageObjName . "_row_" . $t_re_adopcion_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_re_adopcion->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres"<?php echo $t_re_adopcion->Nombres->CellAttributes() ?>>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Nombres" class="form-group t_re_adopcion_Nombres">
<input type="text" data-table="t_re_adopcion" data-field="x_Nombres" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->Nombres->EditValue ?>"<?php echo $t_re_adopcion->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->OldValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Nombres" class="form-group t_re_adopcion_Nombres">
<span<?php echo $t_re_adopcion->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Nombres->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->CurrentValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Nombres" class="t_re_adopcion_Nombres">
<span<?php echo $t_re_adopcion->Nombres->ViewAttributes() ?>>
<?php echo $t_re_adopcion->Nombres->ListViewValue() ?></span>
</span>
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $t_re_adopcion->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Apellido_Paterno" class="form-group t_re_adopcion_Apellido_Paterno">
<input type="text" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->Apellido_Paterno->EditValue ?>"<?php echo $t_re_adopcion->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Apellido_Paterno" class="form-group t_re_adopcion_Apellido_Paterno">
<span<?php echo $t_re_adopcion->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Apellido_Paterno" class="t_re_adopcion_Apellido_Paterno">
<span<?php echo $t_re_adopcion->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_re_adopcion->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $t_re_adopcion->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Apellido_Materno" class="form-group t_re_adopcion_Apellido_Materno">
<input type="text" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->Apellido_Materno->EditValue ?>"<?php echo $t_re_adopcion->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Apellido_Materno" class="form-group t_re_adopcion_Apellido_Materno">
<span<?php echo $t_re_adopcion->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Apellido_Materno" class="t_re_adopcion_Apellido_Materno">
<span<?php echo $t_re_adopcion->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_re_adopcion->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco"<?php echo $t_re_adopcion->Parentesco->CellAttributes() ?>>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Parentesco" class="form-group t_re_adopcion_Parentesco">
<select data-table="t_re_adopcion" data-field="x_Parentesco" data-value-separator="<?php echo $t_re_adopcion->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco"<?php echo $t_re_adopcion->Parentesco->EditAttributes() ?>>
<?php echo $t_re_adopcion->Parentesco->SelectOptionListHtml("x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="s_x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo $t_re_adopcion->Parentesco->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Parentesco" class="form-group t_re_adopcion_Parentesco">
<select data-table="t_re_adopcion" data-field="x_Parentesco" data-value-separator="<?php echo $t_re_adopcion->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco"<?php echo $t_re_adopcion->Parentesco->EditAttributes() ?>>
<?php echo $t_re_adopcion->Parentesco->SelectOptionListHtml("x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="s_x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo $t_re_adopcion->Parentesco->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_re_adopcion_grid->RowCnt ?>_t_re_adopcion_Parentesco" class="t_re_adopcion_Parentesco">
<span<?php echo $t_re_adopcion->Parentesco->ViewAttributes() ?>>
<?php echo $t_re_adopcion->Parentesco->ListViewValue() ?></span>
</span>
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="ft_re_adopciongrid$x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="ft_re_adopciongrid$o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_re_adopcion_grid->ListOptions->Render("body", "right", $t_re_adopcion_grid->RowCnt);
?>
	</tr>
<?php if ($t_re_adopcion->RowType == EW_ROWTYPE_ADD || $t_re_adopcion->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_re_adopciongrid.UpdateOpts(<?php echo $t_re_adopcion_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_re_adopcion->CurrentAction <> "gridadd" || $t_re_adopcion->CurrentMode == "copy")
		if (!$t_re_adopcion_grid->Recordset->EOF) $t_re_adopcion_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_re_adopcion->CurrentMode == "add" || $t_re_adopcion->CurrentMode == "copy" || $t_re_adopcion->CurrentMode == "edit") {
		$t_re_adopcion_grid->RowIndex = '$rowindex$';
		$t_re_adopcion_grid->LoadDefaultValues();

		// Set row properties
		$t_re_adopcion->ResetAttrs();
		$t_re_adopcion->RowAttrs = array_merge($t_re_adopcion->RowAttrs, array('data-rowindex'=>$t_re_adopcion_grid->RowIndex, 'id'=>'r0_t_re_adopcion', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_re_adopcion->RowAttrs["class"], "ewTemplate");
		$t_re_adopcion->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_re_adopcion_grid->RenderRow();

		// Render list options
		$t_re_adopcion_grid->RenderListOptions();
		$t_re_adopcion_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_re_adopcion->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_re_adopcion_grid->ListOptions->Render("body", "left", $t_re_adopcion_grid->RowIndex);
?>
	<?php if ($t_re_adopcion->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<?php if ($t_re_adopcion->id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<span<?php echo $t_re_adopcion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<input type="text" data-table="t_re_adopcion" data-field="x_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->id->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->id->EditValue ?>"<?php echo $t_re_adopcion->id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_re_adopcion_id" class="form-group t_re_adopcion_id">
<span<?php echo $t_re_adopcion->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_id" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($t_re_adopcion->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres">
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_re_adopcion_Nombres" class="form-group t_re_adopcion_Nombres">
<input type="text" data-table="t_re_adopcion" data-field="x_Nombres" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->Nombres->EditValue ?>"<?php echo $t_re_adopcion->Nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_re_adopcion_Nombres" class="form-group t_re_adopcion_Nombres">
<span<?php echo $t_re_adopcion->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Nombres" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_re_adopcion->Nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno">
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_re_adopcion_Apellido_Paterno" class="form-group t_re_adopcion_Apellido_Paterno">
<input type="text" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->Apellido_Paterno->EditValue ?>"<?php echo $t_re_adopcion->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_re_adopcion_Apellido_Paterno" class="form-group t_re_adopcion_Apellido_Paterno">
<span<?php echo $t_re_adopcion->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Paterno" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno">
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_re_adopcion_Apellido_Materno" class="form-group t_re_adopcion_Apellido_Materno">
<input type="text" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_re_adopcion->Apellido_Materno->EditValue ?>"<?php echo $t_re_adopcion->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_re_adopcion_Apellido_Materno" class="form-group t_re_adopcion_Apellido_Materno">
<span<?php echo $t_re_adopcion->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Apellido_Materno" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_re_adopcion->Apellido_Materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_re_adopcion->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco">
<?php if ($t_re_adopcion->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_re_adopcion_Parentesco" class="form-group t_re_adopcion_Parentesco">
<select data-table="t_re_adopcion" data-field="x_Parentesco" data-value-separator="<?php echo $t_re_adopcion->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco"<?php echo $t_re_adopcion->Parentesco->EditAttributes() ?>>
<?php echo $t_re_adopcion->Parentesco->SelectOptionListHtml("x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="s_x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo $t_re_adopcion->Parentesco->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_re_adopcion_Parentesco" class="form-group t_re_adopcion_Parentesco">
<span<?php echo $t_re_adopcion->Parentesco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_re_adopcion->Parentesco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_re_adopcion" data-field="x_Parentesco" name="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_re_adopcion_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_re_adopcion->Parentesco->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_re_adopcion_grid->ListOptions->Render("body", "right", $t_re_adopcion_grid->RowCnt);
?>
<script type="text/javascript">
ft_re_adopciongrid.UpdateOpts(<?php echo $t_re_adopcion_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_re_adopcion->CurrentMode == "add" || $t_re_adopcion->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_re_adopcion_grid->FormKeyCountName ?>" id="<?php echo $t_re_adopcion_grid->FormKeyCountName ?>" value="<?php echo $t_re_adopcion_grid->KeyCount ?>">
<?php echo $t_re_adopcion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_re_adopcion->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_re_adopcion_grid->FormKeyCountName ?>" id="<?php echo $t_re_adopcion_grid->FormKeyCountName ?>" value="<?php echo $t_re_adopcion_grid->KeyCount ?>">
<?php echo $t_re_adopcion_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_re_adopcion->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_re_adopciongrid">
</div>
<?php

// Close recordset
if ($t_re_adopcion_grid->Recordset)
	$t_re_adopcion_grid->Recordset->Close();
?>
<?php if ($t_re_adopcion_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_re_adopcion_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_re_adopcion_grid->TotalRecs == 0 && $t_re_adopcion->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_re_adopcion_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_re_adopcion->Export == "") { ?>
<script type="text/javascript">
ft_re_adopciongrid.Init();
</script>
<?php } ?>
<?php
$t_re_adopcion_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_re_adopcion_grid->Page_Terminate();
?>
