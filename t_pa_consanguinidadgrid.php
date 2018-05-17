<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_pa_consanguinidad_grid)) $t_pa_consanguinidad_grid = new ct_pa_consanguinidad_grid();

// Page init
$t_pa_consanguinidad_grid->Page_Init();

// Page main
$t_pa_consanguinidad_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pa_consanguinidad_grid->Page_Render();
?>
<?php if ($t_pa_consanguinidad->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_pa_consanguinidadgrid = new ew_Form("ft_pa_consanguinidadgrid", "grid");
ft_pa_consanguinidadgrid.FormKeyCountName = '<?php echo $t_pa_consanguinidad_grid->FormKeyCountName ?>';

// Validate form
ft_pa_consanguinidadgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Id->FldCaption(), $t_pa_consanguinidad->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pa_consanguinidad->Id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Nombres->FldCaption(), $t_pa_consanguinidad->Nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Paterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Apellido_Paterno->FldCaption(), $t_pa_consanguinidad->Apellido_Paterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Materno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Apellido_Materno->FldCaption(), $t_pa_consanguinidad->Apellido_Materno->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_pa_consanguinidadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Parentesco", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_pa_consanguinidadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pa_consanguinidadgrid.ValidateRequired = true;
<?php } else { ?>
ft_pa_consanguinidadgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pa_consanguinidadgrid.Lists["x_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_consanguineo"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_pa_consanguinidad->CurrentAction == "gridadd") {
	if ($t_pa_consanguinidad->CurrentMode == "copy") {
		$bSelectLimit = $t_pa_consanguinidad_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_pa_consanguinidad_grid->TotalRecs = $t_pa_consanguinidad->SelectRecordCount();
			$t_pa_consanguinidad_grid->Recordset = $t_pa_consanguinidad_grid->LoadRecordset($t_pa_consanguinidad_grid->StartRec-1, $t_pa_consanguinidad_grid->DisplayRecs);
		} else {
			if ($t_pa_consanguinidad_grid->Recordset = $t_pa_consanguinidad_grid->LoadRecordset())
				$t_pa_consanguinidad_grid->TotalRecs = $t_pa_consanguinidad_grid->Recordset->RecordCount();
		}
		$t_pa_consanguinidad_grid->StartRec = 1;
		$t_pa_consanguinidad_grid->DisplayRecs = $t_pa_consanguinidad_grid->TotalRecs;
	} else {
		$t_pa_consanguinidad->CurrentFilter = "0=1";
		$t_pa_consanguinidad_grid->StartRec = 1;
		$t_pa_consanguinidad_grid->DisplayRecs = $t_pa_consanguinidad->GridAddRowCount;
	}
	$t_pa_consanguinidad_grid->TotalRecs = $t_pa_consanguinidad_grid->DisplayRecs;
	$t_pa_consanguinidad_grid->StopRec = $t_pa_consanguinidad_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_pa_consanguinidad_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_pa_consanguinidad_grid->TotalRecs <= 0)
			$t_pa_consanguinidad_grid->TotalRecs = $t_pa_consanguinidad->SelectRecordCount();
	} else {
		if (!$t_pa_consanguinidad_grid->Recordset && ($t_pa_consanguinidad_grid->Recordset = $t_pa_consanguinidad_grid->LoadRecordset()))
			$t_pa_consanguinidad_grid->TotalRecs = $t_pa_consanguinidad_grid->Recordset->RecordCount();
	}
	$t_pa_consanguinidad_grid->StartRec = 1;
	$t_pa_consanguinidad_grid->DisplayRecs = $t_pa_consanguinidad_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_pa_consanguinidad_grid->Recordset = $t_pa_consanguinidad_grid->LoadRecordset($t_pa_consanguinidad_grid->StartRec-1, $t_pa_consanguinidad_grid->DisplayRecs);

	// Set no record found message
	if ($t_pa_consanguinidad->CurrentAction == "" && $t_pa_consanguinidad_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_pa_consanguinidad_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_pa_consanguinidad_grid->SearchWhere == "0=101")
			$t_pa_consanguinidad_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_pa_consanguinidad_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_pa_consanguinidad_grid->RenderOtherOptions();
?>
<?php $t_pa_consanguinidad_grid->ShowPageHeader(); ?>
<?php
$t_pa_consanguinidad_grid->ShowMessage();
?>
<?php if ($t_pa_consanguinidad_grid->TotalRecs > 0 || $t_pa_consanguinidad->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_pa_consanguinidad">
<div id="ft_pa_consanguinidadgrid" class="ewForm form-inline">
<div id="gmp_t_pa_consanguinidad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_pa_consanguinidadgrid" class="table ewTable">
<?php echo $t_pa_consanguinidad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_pa_consanguinidad_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_pa_consanguinidad_grid->RenderListOptions();

// Render list options (header, left)
$t_pa_consanguinidad_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_pa_consanguinidad->Id->Visible) { // Id ?>
	<?php if ($t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Id) == "") { ?>
		<th data-name="Id"><div id="elh_t_pa_consanguinidad_Id" class="t_pa_consanguinidad_Id"><div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div><div id="elh_t_pa_consanguinidad_Id" class="t_pa_consanguinidad_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_consanguinidad->Nombres->Visible) { // Nombres ?>
	<?php if ($t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Nombres) == "") { ?>
		<th data-name="Nombres"><div id="elh_t_pa_consanguinidad_Nombres" class="t_pa_consanguinidad_Nombres"><div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres"><div><div id="elh_t_pa_consanguinidad_Nombres" class="t_pa_consanguinidad_Nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_consanguinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_t_pa_consanguinidad_Apellido_Paterno" class="t_pa_consanguinidad_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div><div id="elh_t_pa_consanguinidad_Apellido_Paterno" class="t_pa_consanguinidad_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_consanguinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_t_pa_consanguinidad_Apellido_Materno" class="t_pa_consanguinidad_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div><div id="elh_t_pa_consanguinidad_Apellido_Materno" class="t_pa_consanguinidad_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_pa_consanguinidad->Parentesco->Visible) { // Parentesco ?>
	<?php if ($t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Parentesco) == "") { ?>
		<th data-name="Parentesco"><div id="elh_t_pa_consanguinidad_Parentesco" class="t_pa_consanguinidad_Parentesco"><div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Parentesco"><div><div id="elh_t_pa_consanguinidad_Parentesco" class="t_pa_consanguinidad_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_pa_consanguinidad_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_pa_consanguinidad_grid->StartRec = 1;
$t_pa_consanguinidad_grid->StopRec = $t_pa_consanguinidad_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_pa_consanguinidad_grid->FormKeyCountName) && ($t_pa_consanguinidad->CurrentAction == "gridadd" || $t_pa_consanguinidad->CurrentAction == "gridedit" || $t_pa_consanguinidad->CurrentAction == "F")) {
		$t_pa_consanguinidad_grid->KeyCount = $objForm->GetValue($t_pa_consanguinidad_grid->FormKeyCountName);
		$t_pa_consanguinidad_grid->StopRec = $t_pa_consanguinidad_grid->StartRec + $t_pa_consanguinidad_grid->KeyCount - 1;
	}
}
$t_pa_consanguinidad_grid->RecCnt = $t_pa_consanguinidad_grid->StartRec - 1;
if ($t_pa_consanguinidad_grid->Recordset && !$t_pa_consanguinidad_grid->Recordset->EOF) {
	$t_pa_consanguinidad_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_pa_consanguinidad_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_pa_consanguinidad_grid->StartRec > 1)
		$t_pa_consanguinidad_grid->Recordset->Move($t_pa_consanguinidad_grid->StartRec - 1);
} elseif (!$t_pa_consanguinidad->AllowAddDeleteRow && $t_pa_consanguinidad_grid->StopRec == 0) {
	$t_pa_consanguinidad_grid->StopRec = $t_pa_consanguinidad->GridAddRowCount;
}

// Initialize aggregate
$t_pa_consanguinidad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_pa_consanguinidad->ResetAttrs();
$t_pa_consanguinidad_grid->RenderRow();
if ($t_pa_consanguinidad->CurrentAction == "gridadd")
	$t_pa_consanguinidad_grid->RowIndex = 0;
if ($t_pa_consanguinidad->CurrentAction == "gridedit")
	$t_pa_consanguinidad_grid->RowIndex = 0;
while ($t_pa_consanguinidad_grid->RecCnt < $t_pa_consanguinidad_grid->StopRec) {
	$t_pa_consanguinidad_grid->RecCnt++;
	if (intval($t_pa_consanguinidad_grid->RecCnt) >= intval($t_pa_consanguinidad_grid->StartRec)) {
		$t_pa_consanguinidad_grid->RowCnt++;
		if ($t_pa_consanguinidad->CurrentAction == "gridadd" || $t_pa_consanguinidad->CurrentAction == "gridedit" || $t_pa_consanguinidad->CurrentAction == "F") {
			$t_pa_consanguinidad_grid->RowIndex++;
			$objForm->Index = $t_pa_consanguinidad_grid->RowIndex;
			if ($objForm->HasValue($t_pa_consanguinidad_grid->FormActionName))
				$t_pa_consanguinidad_grid->RowAction = strval($objForm->GetValue($t_pa_consanguinidad_grid->FormActionName));
			elseif ($t_pa_consanguinidad->CurrentAction == "gridadd")
				$t_pa_consanguinidad_grid->RowAction = "insert";
			else
				$t_pa_consanguinidad_grid->RowAction = "";
		}

		// Set up key count
		$t_pa_consanguinidad_grid->KeyCount = $t_pa_consanguinidad_grid->RowIndex;

		// Init row class and style
		$t_pa_consanguinidad->ResetAttrs();
		$t_pa_consanguinidad->CssClass = "";
		if ($t_pa_consanguinidad->CurrentAction == "gridadd") {
			if ($t_pa_consanguinidad->CurrentMode == "copy") {
				$t_pa_consanguinidad_grid->LoadRowValues($t_pa_consanguinidad_grid->Recordset); // Load row values
				$t_pa_consanguinidad_grid->SetRecordKey($t_pa_consanguinidad_grid->RowOldKey, $t_pa_consanguinidad_grid->Recordset); // Set old record key
			} else {
				$t_pa_consanguinidad_grid->LoadDefaultValues(); // Load default values
				$t_pa_consanguinidad_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_pa_consanguinidad_grid->LoadRowValues($t_pa_consanguinidad_grid->Recordset); // Load row values
		}
		$t_pa_consanguinidad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_pa_consanguinidad->CurrentAction == "gridadd") // Grid add
			$t_pa_consanguinidad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_pa_consanguinidad->CurrentAction == "gridadd" && $t_pa_consanguinidad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_pa_consanguinidad_grid->RestoreCurrentRowFormValues($t_pa_consanguinidad_grid->RowIndex); // Restore form values
		if ($t_pa_consanguinidad->CurrentAction == "gridedit") { // Grid edit
			if ($t_pa_consanguinidad->EventCancelled) {
				$t_pa_consanguinidad_grid->RestoreCurrentRowFormValues($t_pa_consanguinidad_grid->RowIndex); // Restore form values
			}
			if ($t_pa_consanguinidad_grid->RowAction == "insert")
				$t_pa_consanguinidad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_pa_consanguinidad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_pa_consanguinidad->CurrentAction == "gridedit" && ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT || $t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) && $t_pa_consanguinidad->EventCancelled) // Update failed
			$t_pa_consanguinidad_grid->RestoreCurrentRowFormValues($t_pa_consanguinidad_grid->RowIndex); // Restore form values
		if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_pa_consanguinidad_grid->EditRowCnt++;
		if ($t_pa_consanguinidad->CurrentAction == "F") // Confirm row
			$t_pa_consanguinidad_grid->RestoreCurrentRowFormValues($t_pa_consanguinidad_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_pa_consanguinidad->RowAttrs = array_merge($t_pa_consanguinidad->RowAttrs, array('data-rowindex'=>$t_pa_consanguinidad_grid->RowCnt, 'id'=>'r' . $t_pa_consanguinidad_grid->RowCnt . '_t_pa_consanguinidad', 'data-rowtype'=>$t_pa_consanguinidad->RowType));

		// Render row
		$t_pa_consanguinidad_grid->RenderRow();

		// Render list options
		$t_pa_consanguinidad_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_pa_consanguinidad_grid->RowAction <> "delete" && $t_pa_consanguinidad_grid->RowAction <> "insertdelete" && !($t_pa_consanguinidad_grid->RowAction == "insert" && $t_pa_consanguinidad->CurrentAction == "F" && $t_pa_consanguinidad_grid->EmptyRow())) {
?>
	<tr<?php echo $t_pa_consanguinidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_pa_consanguinidad_grid->ListOptions->Render("body", "left", $t_pa_consanguinidad_grid->RowCnt);
?>
	<?php if ($t_pa_consanguinidad->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $t_pa_consanguinidad->Id->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Id" class="t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Id->ListViewValue() ?></span>
</span>
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_pa_consanguinidad_grid->PageObjName . "_row_" . $t_pa_consanguinidad_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres"<?php echo $t_pa_consanguinidad->Nombres->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Nombres" class="form-group t_pa_consanguinidad_Nombres">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Nombres->EditValue ?>"<?php echo $t_pa_consanguinidad->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Nombres" class="form-group t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Nombres->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Nombres" class="t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Nombres->ListViewValue() ?></span>
</span>
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $t_pa_consanguinidad->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno" class="form-group t_pa_consanguinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno" class="form-group t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno" class="t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $t_pa_consanguinidad->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno" class="form-group t_pa_consanguinidad_Apellido_Materno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno" class="form-group t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno" class="t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Parentesco" class="form-group t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Parentesco" class="form-group t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_grid->RowCnt ?>_t_pa_consanguinidad_Parentesco" class="t_pa_consanguinidad_Parentesco">
<span<?php echo $t_pa_consanguinidad->Parentesco->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->ListViewValue() ?></span>
</span>
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="ft_pa_consanguinidadgrid$x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="ft_pa_consanguinidadgrid$o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_pa_consanguinidad_grid->ListOptions->Render("body", "right", $t_pa_consanguinidad_grid->RowCnt);
?>
	</tr>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD || $t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_pa_consanguinidadgrid.UpdateOpts(<?php echo $t_pa_consanguinidad_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_pa_consanguinidad->CurrentAction <> "gridadd" || $t_pa_consanguinidad->CurrentMode == "copy")
		if (!$t_pa_consanguinidad_grid->Recordset->EOF) $t_pa_consanguinidad_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_pa_consanguinidad->CurrentMode == "add" || $t_pa_consanguinidad->CurrentMode == "copy" || $t_pa_consanguinidad->CurrentMode == "edit") {
		$t_pa_consanguinidad_grid->RowIndex = '$rowindex$';
		$t_pa_consanguinidad_grid->LoadDefaultValues();

		// Set row properties
		$t_pa_consanguinidad->ResetAttrs();
		$t_pa_consanguinidad->RowAttrs = array_merge($t_pa_consanguinidad->RowAttrs, array('data-rowindex'=>$t_pa_consanguinidad_grid->RowIndex, 'id'=>'r0_t_pa_consanguinidad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_pa_consanguinidad->RowAttrs["class"], "ewTemplate");
		$t_pa_consanguinidad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_pa_consanguinidad_grid->RenderRow();

		// Render list options
		$t_pa_consanguinidad_grid->RenderListOptions();
		$t_pa_consanguinidad_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_pa_consanguinidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_pa_consanguinidad_grid->ListOptions->Render("body", "left", $t_pa_consanguinidad_grid->RowIndex);
?>
	<?php if ($t_pa_consanguinidad->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Id" class="form-group t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres">
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Nombres" class="form-group t_pa_consanguinidad_Nombres">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Nombres->EditValue ?>"<?php echo $t_pa_consanguinidad->Nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Nombres" class="form-group t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno">
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Apellido_Paterno" class="form-group t_pa_consanguinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Apellido_Paterno" class="form-group t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno">
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Apellido_Materno" class="form-group t_pa_consanguinidad_Apellido_Materno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Apellido_Materno" class="form-group t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco">
<?php if ($t_pa_consanguinidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Parentesco" class="form-group t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_pa_consanguinidad_Parentesco" class="form-group t_pa_consanguinidad_Parentesco">
<span<?php echo $t_pa_consanguinidad->Parentesco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Parentesco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_pa_consanguinidad_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_pa_consanguinidad_grid->ListOptions->Render("body", "right", $t_pa_consanguinidad_grid->RowCnt);
?>
<script type="text/javascript">
ft_pa_consanguinidadgrid.UpdateOpts(<?php echo $t_pa_consanguinidad_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_pa_consanguinidad->CurrentMode == "add" || $t_pa_consanguinidad->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_pa_consanguinidad_grid->FormKeyCountName ?>" id="<?php echo $t_pa_consanguinidad_grid->FormKeyCountName ?>" value="<?php echo $t_pa_consanguinidad_grid->KeyCount ?>">
<?php echo $t_pa_consanguinidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_pa_consanguinidad_grid->FormKeyCountName ?>" id="<?php echo $t_pa_consanguinidad_grid->FormKeyCountName ?>" value="<?php echo $t_pa_consanguinidad_grid->KeyCount ?>">
<?php echo $t_pa_consanguinidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_pa_consanguinidadgrid">
</div>
<?php

// Close recordset
if ($t_pa_consanguinidad_grid->Recordset)
	$t_pa_consanguinidad_grid->Recordset->Close();
?>
<?php if ($t_pa_consanguinidad_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_pa_consanguinidad_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_pa_consanguinidad_grid->TotalRecs == 0 && $t_pa_consanguinidad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_pa_consanguinidad_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_pa_consanguinidad->Export == "") { ?>
<script type="text/javascript">
ft_pa_consanguinidadgrid.Init();
</script>
<?php } ?>
<?php
$t_pa_consanguinidad_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_pa_consanguinidad_grid->Page_Terminate();
?>
