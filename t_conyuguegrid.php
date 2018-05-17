<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_conyugue_grid)) $t_conyugue_grid = new ct_conyugue_grid();

// Page init
$t_conyugue_grid->Page_Init();

// Page main
$t_conyugue_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_conyugue_grid->Page_Render();
?>
<?php if ($t_conyugue->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_conyuguegrid = new ew_Form("ft_conyuguegrid", "grid");
ft_conyuguegrid.FormKeyCountName = '<?php echo $t_conyugue_grid->FormKeyCountName ?>';

// Validate form
ft_conyuguegrid.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_conyuguegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "CI_RUN", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Expedido", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Direccion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_conyuguegrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_conyuguegrid.ValidateRequired = true;
<?php } else { ?>
ft_conyuguegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_conyuguegrid.Lists["x_Expedido"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_conyuguegrid.Lists["x_Expedido"].Options = <?php echo json_encode($t_conyugue->Expedido->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($t_conyugue->CurrentAction == "gridadd") {
	if ($t_conyugue->CurrentMode == "copy") {
		$bSelectLimit = $t_conyugue_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_conyugue_grid->TotalRecs = $t_conyugue->SelectRecordCount();
			$t_conyugue_grid->Recordset = $t_conyugue_grid->LoadRecordset($t_conyugue_grid->StartRec-1, $t_conyugue_grid->DisplayRecs);
		} else {
			if ($t_conyugue_grid->Recordset = $t_conyugue_grid->LoadRecordset())
				$t_conyugue_grid->TotalRecs = $t_conyugue_grid->Recordset->RecordCount();
		}
		$t_conyugue_grid->StartRec = 1;
		$t_conyugue_grid->DisplayRecs = $t_conyugue_grid->TotalRecs;
	} else {
		$t_conyugue->CurrentFilter = "0=1";
		$t_conyugue_grid->StartRec = 1;
		$t_conyugue_grid->DisplayRecs = $t_conyugue->GridAddRowCount;
	}
	$t_conyugue_grid->TotalRecs = $t_conyugue_grid->DisplayRecs;
	$t_conyugue_grid->StopRec = $t_conyugue_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_conyugue_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_conyugue_grid->TotalRecs <= 0)
			$t_conyugue_grid->TotalRecs = $t_conyugue->SelectRecordCount();
	} else {
		if (!$t_conyugue_grid->Recordset && ($t_conyugue_grid->Recordset = $t_conyugue_grid->LoadRecordset()))
			$t_conyugue_grid->TotalRecs = $t_conyugue_grid->Recordset->RecordCount();
	}
	$t_conyugue_grid->StartRec = 1;
	$t_conyugue_grid->DisplayRecs = $t_conyugue_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_conyugue_grid->Recordset = $t_conyugue_grid->LoadRecordset($t_conyugue_grid->StartRec-1, $t_conyugue_grid->DisplayRecs);

	// Set no record found message
	if ($t_conyugue->CurrentAction == "" && $t_conyugue_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_conyugue_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_conyugue_grid->SearchWhere == "0=101")
			$t_conyugue_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_conyugue_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_conyugue_grid->RenderOtherOptions();
?>
<?php $t_conyugue_grid->ShowPageHeader(); ?>
<?php
$t_conyugue_grid->ShowMessage();
?>
<?php if ($t_conyugue_grid->TotalRecs > 0 || $t_conyugue->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_conyugue">
<div id="ft_conyuguegrid" class="ewForm form-inline">
<div id="gmp_t_conyugue" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_conyuguegrid" class="table ewTable">
<?php echo $t_conyugue->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_conyugue_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_conyugue_grid->RenderListOptions();

// Render list options (header, left)
$t_conyugue_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_conyugue->CI_RUN->Visible) { // CI_RUN ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->CI_RUN) == "") { ?>
		<th data-name="CI_RUN"><div id="elh_t_conyugue_CI_RUN" class="t_conyugue_CI_RUN"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->CI_RUN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CI_RUN"><div><div id="elh_t_conyugue_CI_RUN" class="t_conyugue_CI_RUN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->CI_RUN->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->CI_RUN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->CI_RUN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_conyugue->Expedido->Visible) { // Expedido ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->Expedido) == "") { ?>
		<th data-name="Expedido"><div id="elh_t_conyugue_Expedido" class="t_conyugue_Expedido"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->Expedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Expedido"><div><div id="elh_t_conyugue_Expedido" class="t_conyugue_Expedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->Expedido->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->Expedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->Expedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_conyugue->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_t_conyugue_Apellido_Paterno" class="t_conyugue_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div><div id="elh_t_conyugue_Apellido_Paterno" class="t_conyugue_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->Apellido_Paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_conyugue->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_t_conyugue_Apellido_Materno" class="t_conyugue_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div><div id="elh_t_conyugue_Apellido_Materno" class="t_conyugue_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->Apellido_Materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_conyugue->Nombres->Visible) { // Nombres ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->Nombres) == "") { ?>
		<th data-name="Nombres"><div id="elh_t_conyugue_Nombres" class="t_conyugue_Nombres"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->Nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres"><div><div id="elh_t_conyugue_Nombres" class="t_conyugue_Nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->Nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_conyugue->Direccion->Visible) { // Direccion ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->Direccion) == "") { ?>
		<th data-name="Direccion"><div id="elh_t_conyugue_Direccion" class="t_conyugue_Direccion"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->Direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Direccion"><div><div id="elh_t_conyugue_Direccion" class="t_conyugue_Direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->Direccion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->Direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->Direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_conyugue->Id->Visible) { // Id ?>
	<?php if ($t_conyugue->SortUrl($t_conyugue->Id) == "") { ?>
		<th data-name="Id"><div id="elh_t_conyugue_Id" class="t_conyugue_Id"><div class="ewTableHeaderCaption"><?php echo $t_conyugue->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div><div id="elh_t_conyugue_Id" class="t_conyugue_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_conyugue->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_conyugue->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_conyugue->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_conyugue_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_conyugue_grid->StartRec = 1;
$t_conyugue_grid->StopRec = $t_conyugue_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_conyugue_grid->FormKeyCountName) && ($t_conyugue->CurrentAction == "gridadd" || $t_conyugue->CurrentAction == "gridedit" || $t_conyugue->CurrentAction == "F")) {
		$t_conyugue_grid->KeyCount = $objForm->GetValue($t_conyugue_grid->FormKeyCountName);
		$t_conyugue_grid->StopRec = $t_conyugue_grid->StartRec + $t_conyugue_grid->KeyCount - 1;
	}
}
$t_conyugue_grid->RecCnt = $t_conyugue_grid->StartRec - 1;
if ($t_conyugue_grid->Recordset && !$t_conyugue_grid->Recordset->EOF) {
	$t_conyugue_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_conyugue_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_conyugue_grid->StartRec > 1)
		$t_conyugue_grid->Recordset->Move($t_conyugue_grid->StartRec - 1);
} elseif (!$t_conyugue->AllowAddDeleteRow && $t_conyugue_grid->StopRec == 0) {
	$t_conyugue_grid->StopRec = $t_conyugue->GridAddRowCount;
}

// Initialize aggregate
$t_conyugue->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_conyugue->ResetAttrs();
$t_conyugue_grid->RenderRow();
if ($t_conyugue->CurrentAction == "gridadd")
	$t_conyugue_grid->RowIndex = 0;
if ($t_conyugue->CurrentAction == "gridedit")
	$t_conyugue_grid->RowIndex = 0;
while ($t_conyugue_grid->RecCnt < $t_conyugue_grid->StopRec) {
	$t_conyugue_grid->RecCnt++;
	if (intval($t_conyugue_grid->RecCnt) >= intval($t_conyugue_grid->StartRec)) {
		$t_conyugue_grid->RowCnt++;
		if ($t_conyugue->CurrentAction == "gridadd" || $t_conyugue->CurrentAction == "gridedit" || $t_conyugue->CurrentAction == "F") {
			$t_conyugue_grid->RowIndex++;
			$objForm->Index = $t_conyugue_grid->RowIndex;
			if ($objForm->HasValue($t_conyugue_grid->FormActionName))
				$t_conyugue_grid->RowAction = strval($objForm->GetValue($t_conyugue_grid->FormActionName));
			elseif ($t_conyugue->CurrentAction == "gridadd")
				$t_conyugue_grid->RowAction = "insert";
			else
				$t_conyugue_grid->RowAction = "";
		}

		// Set up key count
		$t_conyugue_grid->KeyCount = $t_conyugue_grid->RowIndex;

		// Init row class and style
		$t_conyugue->ResetAttrs();
		$t_conyugue->CssClass = "";
		if ($t_conyugue->CurrentAction == "gridadd") {
			if ($t_conyugue->CurrentMode == "copy") {
				$t_conyugue_grid->LoadRowValues($t_conyugue_grid->Recordset); // Load row values
				$t_conyugue_grid->SetRecordKey($t_conyugue_grid->RowOldKey, $t_conyugue_grid->Recordset); // Set old record key
			} else {
				$t_conyugue_grid->LoadDefaultValues(); // Load default values
				$t_conyugue_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_conyugue_grid->LoadRowValues($t_conyugue_grid->Recordset); // Load row values
		}
		$t_conyugue->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_conyugue->CurrentAction == "gridadd") // Grid add
			$t_conyugue->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_conyugue->CurrentAction == "gridadd" && $t_conyugue->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_conyugue_grid->RestoreCurrentRowFormValues($t_conyugue_grid->RowIndex); // Restore form values
		if ($t_conyugue->CurrentAction == "gridedit") { // Grid edit
			if ($t_conyugue->EventCancelled) {
				$t_conyugue_grid->RestoreCurrentRowFormValues($t_conyugue_grid->RowIndex); // Restore form values
			}
			if ($t_conyugue_grid->RowAction == "insert")
				$t_conyugue->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_conyugue->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_conyugue->CurrentAction == "gridedit" && ($t_conyugue->RowType == EW_ROWTYPE_EDIT || $t_conyugue->RowType == EW_ROWTYPE_ADD) && $t_conyugue->EventCancelled) // Update failed
			$t_conyugue_grid->RestoreCurrentRowFormValues($t_conyugue_grid->RowIndex); // Restore form values
		if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_conyugue_grid->EditRowCnt++;
		if ($t_conyugue->CurrentAction == "F") // Confirm row
			$t_conyugue_grid->RestoreCurrentRowFormValues($t_conyugue_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_conyugue->RowAttrs = array_merge($t_conyugue->RowAttrs, array('data-rowindex'=>$t_conyugue_grid->RowCnt, 'id'=>'r' . $t_conyugue_grid->RowCnt . '_t_conyugue', 'data-rowtype'=>$t_conyugue->RowType));

		// Render row
		$t_conyugue_grid->RenderRow();

		// Render list options
		$t_conyugue_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_conyugue_grid->RowAction <> "delete" && $t_conyugue_grid->RowAction <> "insertdelete" && !($t_conyugue_grid->RowAction == "insert" && $t_conyugue->CurrentAction == "F" && $t_conyugue_grid->EmptyRow())) {
?>
	<tr<?php echo $t_conyugue->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_conyugue_grid->ListOptions->Render("body", "left", $t_conyugue_grid->RowCnt);
?>
	<?php if ($t_conyugue->CI_RUN->Visible) { // CI_RUN ?>
		<td data-name="CI_RUN"<?php echo $t_conyugue->CI_RUN->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_CI_RUN" class="form-group t_conyugue_CI_RUN">
<input type="text" data-table="t_conyugue" data-field="x_CI_RUN" name="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->CI_RUN->EditValue ?>"<?php echo $t_conyugue->CI_RUN->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_CI_RUN" class="form-group t_conyugue_CI_RUN">
<span<?php echo $t_conyugue->CI_RUN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->CI_RUN->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->CurrentValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_CI_RUN" class="t_conyugue_CI_RUN">
<span<?php echo $t_conyugue->CI_RUN->ViewAttributes() ?>>
<?php echo $t_conyugue->CI_RUN->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_conyugue_grid->PageObjName . "_row_" . $t_conyugue_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_conyugue->Expedido->Visible) { // Expedido ?>
		<td data-name="Expedido"<?php echo $t_conyugue->Expedido->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Expedido" class="form-group t_conyugue_Expedido">
<select data-table="t_conyugue" data-field="x_Expedido" data-value-separator="<?php echo $t_conyugue->Expedido->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido"<?php echo $t_conyugue->Expedido->EditAttributes() ?>>
<?php echo $t_conyugue->Expedido->SelectOptionListHtml("x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido") ?>
</select>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Expedido" class="form-group t_conyugue_Expedido">
<select data-table="t_conyugue" data-field="x_Expedido" data-value-separator="<?php echo $t_conyugue->Expedido->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido"<?php echo $t_conyugue->Expedido->EditAttributes() ?>>
<?php echo $t_conyugue->Expedido->SelectOptionListHtml("x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido") ?>
</select>
</span>
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Expedido" class="t_conyugue_Expedido">
<span<?php echo $t_conyugue->Expedido->ViewAttributes() ?>>
<?php echo $t_conyugue->Expedido->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_conyugue->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $t_conyugue->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Apellido_Paterno" class="form-group t_conyugue_Apellido_Paterno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Paterno->EditValue ?>"<?php echo $t_conyugue->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Apellido_Paterno" class="form-group t_conyugue_Apellido_Paterno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Paterno->EditValue ?>"<?php echo $t_conyugue->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Apellido_Paterno" class="t_conyugue_Apellido_Paterno">
<span<?php echo $t_conyugue->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_conyugue->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_conyugue->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $t_conyugue->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Apellido_Materno" class="form-group t_conyugue_Apellido_Materno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Materno->EditValue ?>"<?php echo $t_conyugue->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Apellido_Materno" class="form-group t_conyugue_Apellido_Materno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Materno->EditValue ?>"<?php echo $t_conyugue->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Apellido_Materno" class="t_conyugue_Apellido_Materno">
<span<?php echo $t_conyugue->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_conyugue->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_conyugue->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres"<?php echo $t_conyugue->Nombres->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Nombres" class="form-group t_conyugue_Nombres">
<input type="text" data-table="t_conyugue" data-field="x_Nombres" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Nombres->EditValue ?>"<?php echo $t_conyugue->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Nombres" class="form-group t_conyugue_Nombres">
<input type="text" data-table="t_conyugue" data-field="x_Nombres" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Nombres->EditValue ?>"<?php echo $t_conyugue->Nombres->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Nombres" class="t_conyugue_Nombres">
<span<?php echo $t_conyugue->Nombres->ViewAttributes() ?>>
<?php echo $t_conyugue->Nombres->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_conyugue->Direccion->Visible) { // Direccion ?>
		<td data-name="Direccion"<?php echo $t_conyugue->Direccion->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Direccion" class="form-group t_conyugue_Direccion">
<input type="text" data-table="t_conyugue" data-field="x_Direccion" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Direccion->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Direccion->EditValue ?>"<?php echo $t_conyugue->Direccion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Direccion" class="form-group t_conyugue_Direccion">
<input type="text" data-table="t_conyugue" data-field="x_Direccion" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Direccion->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Direccion->EditValue ?>"<?php echo $t_conyugue->Direccion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Direccion" class="t_conyugue_Direccion">
<span<?php echo $t_conyugue->Direccion->ViewAttributes() ?>>
<?php echo $t_conyugue->Direccion->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_conyugue->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $t_conyugue->Id->CellAttributes() ?>>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_conyugue->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Id" class="form-group t_conyugue_Id">
<span<?php echo $t_conyugue->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Id" class="form-group t_conyugue_Id">
<input type="text" data-table="t_conyugue" data-field="x_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Id->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Id->EditValue ?>"<?php echo $t_conyugue->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Id" class="form-group t_conyugue_Id">
<span<?php echo $t_conyugue->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_conyugue_grid->RowCnt ?>_t_conyugue_Id" class="t_conyugue_Id">
<span<?php echo $t_conyugue->Id->ViewAttributes() ?>>
<?php echo $t_conyugue->Id->ListViewValue() ?></span>
</span>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="ft_conyuguegrid$x<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->FormValue) ?>">
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="ft_conyuguegrid$o<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_conyugue_grid->ListOptions->Render("body", "right", $t_conyugue_grid->RowCnt);
?>
	</tr>
<?php if ($t_conyugue->RowType == EW_ROWTYPE_ADD || $t_conyugue->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_conyuguegrid.UpdateOpts(<?php echo $t_conyugue_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_conyugue->CurrentAction <> "gridadd" || $t_conyugue->CurrentMode == "copy")
		if (!$t_conyugue_grid->Recordset->EOF) $t_conyugue_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_conyugue->CurrentMode == "add" || $t_conyugue->CurrentMode == "copy" || $t_conyugue->CurrentMode == "edit") {
		$t_conyugue_grid->RowIndex = '$rowindex$';
		$t_conyugue_grid->LoadDefaultValues();

		// Set row properties
		$t_conyugue->ResetAttrs();
		$t_conyugue->RowAttrs = array_merge($t_conyugue->RowAttrs, array('data-rowindex'=>$t_conyugue_grid->RowIndex, 'id'=>'r0_t_conyugue', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_conyugue->RowAttrs["class"], "ewTemplate");
		$t_conyugue->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_conyugue_grid->RenderRow();

		// Render list options
		$t_conyugue_grid->RenderListOptions();
		$t_conyugue_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_conyugue->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_conyugue_grid->ListOptions->Render("body", "left", $t_conyugue_grid->RowIndex);
?>
	<?php if ($t_conyugue->CI_RUN->Visible) { // CI_RUN ?>
		<td data-name="CI_RUN">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_conyugue_CI_RUN" class="form-group t_conyugue_CI_RUN">
<input type="text" data-table="t_conyugue" data-field="x_CI_RUN" name="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->CI_RUN->EditValue ?>"<?php echo $t_conyugue->CI_RUN->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_CI_RUN" class="form-group t_conyugue_CI_RUN">
<span<?php echo $t_conyugue->CI_RUN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->CI_RUN->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="x<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_CI_RUN" name="o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" id="o<?php echo $t_conyugue_grid->RowIndex ?>_CI_RUN" value="<?php echo ew_HtmlEncode($t_conyugue->CI_RUN->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_conyugue->Expedido->Visible) { // Expedido ?>
		<td data-name="Expedido">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_conyugue_Expedido" class="form-group t_conyugue_Expedido">
<select data-table="t_conyugue" data-field="x_Expedido" data-value-separator="<?php echo $t_conyugue->Expedido->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido"<?php echo $t_conyugue->Expedido->EditAttributes() ?>>
<?php echo $t_conyugue->Expedido->SelectOptionListHtml("x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Expedido" class="form-group t_conyugue_Expedido">
<span<?php echo $t_conyugue->Expedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Expedido->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_conyugue->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_conyugue_Apellido_Paterno" class="form-group t_conyugue_Apellido_Paterno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Paterno->EditValue ?>"<?php echo $t_conyugue->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Apellido_Paterno" class="form-group t_conyugue_Apellido_Paterno">
<span<?php echo $t_conyugue->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_conyugue->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_conyugue_Apellido_Materno" class="form-group t_conyugue_Apellido_Materno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Materno->EditValue ?>"<?php echo $t_conyugue->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Apellido_Materno" class="form-group t_conyugue_Apellido_Materno">
<span<?php echo $t_conyugue->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_conyugue->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_conyugue_Nombres" class="form-group t_conyugue_Nombres">
<input type="text" data-table="t_conyugue" data-field="x_Nombres" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Nombres->EditValue ?>"<?php echo $t_conyugue->Nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Nombres" class="form-group t_conyugue_Nombres">
<span<?php echo $t_conyugue->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_conyugue->Direccion->Visible) { // Direccion ?>
		<td data-name="Direccion">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_conyugue_Direccion" class="form-group t_conyugue_Direccion">
<input type="text" data-table="t_conyugue" data-field="x_Direccion" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Direccion->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Direccion->EditValue ?>"<?php echo $t_conyugue->Direccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Direccion" class="form-group t_conyugue_Direccion">
<span<?php echo $t_conyugue->Direccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Direccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_conyugue->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<?php if ($t_conyugue->Id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_conyugue_Id" class="form-group t_conyugue_Id">
<span<?php echo $t_conyugue->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Id" class="form-group t_conyugue_Id">
<input type="text" data-table="t_conyugue" data-field="x_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Id->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Id->EditValue ?>"<?php echo $t_conyugue->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_conyugue_Id" class="form-group t_conyugue_Id">
<span<?php echo $t_conyugue->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="x<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_conyugue" data-field="x_Id" name="o<?php echo $t_conyugue_grid->RowIndex ?>_Id" id="o<?php echo $t_conyugue_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_conyugue->Id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_conyugue_grid->ListOptions->Render("body", "right", $t_conyugue_grid->RowCnt);
?>
<script type="text/javascript">
ft_conyuguegrid.UpdateOpts(<?php echo $t_conyugue_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_conyugue->CurrentMode == "add" || $t_conyugue->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_conyugue_grid->FormKeyCountName ?>" id="<?php echo $t_conyugue_grid->FormKeyCountName ?>" value="<?php echo $t_conyugue_grid->KeyCount ?>">
<?php echo $t_conyugue_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_conyugue->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_conyugue_grid->FormKeyCountName ?>" id="<?php echo $t_conyugue_grid->FormKeyCountName ?>" value="<?php echo $t_conyugue_grid->KeyCount ?>">
<?php echo $t_conyugue_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_conyugue->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_conyuguegrid">
</div>
<?php

// Close recordset
if ($t_conyugue_grid->Recordset)
	$t_conyugue_grid->Recordset->Close();
?>
<?php if ($t_conyugue_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_conyugue_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_conyugue_grid->TotalRecs == 0 && $t_conyugue->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_conyugue_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_conyugue->Export == "") { ?>
<script type="text/javascript">
ft_conyuguegrid.Init();
</script>
<?php } ?>
<?php
$t_conyugue_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_conyugue_grid->Page_Terminate();
?>
