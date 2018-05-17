<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_parientes_mp_grid)) $t_parientes_mp_grid = new ct_parientes_mp_grid();

// Page init
$t_parientes_mp_grid->Page_Init();

// Page main
$t_parientes_mp_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_parientes_mp_grid->Page_Render();
?>
<?php if ($t_parientes_mp->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_parientes_mpgrid = new ew_Form("ft_parientes_mpgrid", "grid");
ft_parientes_mpgrid.FormKeyCountName = '<?php echo $t_parientes_mp_grid->FormKeyCountName ?>';

// Validate form
ft_parientes_mpgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_parientes_mp->Id->FldCaption(), $t_parientes_mp->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_parientes_mp->Id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_parientes_mpgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Grado_Parentesco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Parentesco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Unidad_Organizacional", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_parientes_mpgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_parientes_mpgrid.ValidateRequired = true;
<?php } else { ?>
ft_parientes_mpgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_parientes_mpgrid.Lists["x_Grado_Parentesco"] = {"LinkField":"x_Grado","Ajax":true,"AutoFill":true,"DisplayFields":["x_Grado","x_Parentesco","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_parentesco_global"};
ft_parientes_mpgrid.Lists["x_Unidad_Organizacional"] = {"LinkField":"x_Unidad_Organizacional","Ajax":true,"AutoFill":false,"DisplayFields":["x_Unidad_Organizacional","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"seleccion_cargos"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_parientes_mp->CurrentAction == "gridadd") {
	if ($t_parientes_mp->CurrentMode == "copy") {
		$bSelectLimit = $t_parientes_mp_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_parientes_mp_grid->TotalRecs = $t_parientes_mp->SelectRecordCount();
			$t_parientes_mp_grid->Recordset = $t_parientes_mp_grid->LoadRecordset($t_parientes_mp_grid->StartRec-1, $t_parientes_mp_grid->DisplayRecs);
		} else {
			if ($t_parientes_mp_grid->Recordset = $t_parientes_mp_grid->LoadRecordset())
				$t_parientes_mp_grid->TotalRecs = $t_parientes_mp_grid->Recordset->RecordCount();
		}
		$t_parientes_mp_grid->StartRec = 1;
		$t_parientes_mp_grid->DisplayRecs = $t_parientes_mp_grid->TotalRecs;
	} else {
		$t_parientes_mp->CurrentFilter = "0=1";
		$t_parientes_mp_grid->StartRec = 1;
		$t_parientes_mp_grid->DisplayRecs = $t_parientes_mp->GridAddRowCount;
	}
	$t_parientes_mp_grid->TotalRecs = $t_parientes_mp_grid->DisplayRecs;
	$t_parientes_mp_grid->StopRec = $t_parientes_mp_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_parientes_mp_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_parientes_mp_grid->TotalRecs <= 0)
			$t_parientes_mp_grid->TotalRecs = $t_parientes_mp->SelectRecordCount();
	} else {
		if (!$t_parientes_mp_grid->Recordset && ($t_parientes_mp_grid->Recordset = $t_parientes_mp_grid->LoadRecordset()))
			$t_parientes_mp_grid->TotalRecs = $t_parientes_mp_grid->Recordset->RecordCount();
	}
	$t_parientes_mp_grid->StartRec = 1;
	$t_parientes_mp_grid->DisplayRecs = $t_parientes_mp_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_parientes_mp_grid->Recordset = $t_parientes_mp_grid->LoadRecordset($t_parientes_mp_grid->StartRec-1, $t_parientes_mp_grid->DisplayRecs);

	// Set no record found message
	if ($t_parientes_mp->CurrentAction == "" && $t_parientes_mp_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_parientes_mp_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_parientes_mp_grid->SearchWhere == "0=101")
			$t_parientes_mp_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_parientes_mp_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_parientes_mp_grid->RenderOtherOptions();
?>
<?php $t_parientes_mp_grid->ShowPageHeader(); ?>
<?php
$t_parientes_mp_grid->ShowMessage();
?>
<?php if ($t_parientes_mp_grid->TotalRecs > 0 || $t_parientes_mp->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_parientes_mp">
<div id="ft_parientes_mpgrid" class="ewForm form-inline">
<div id="gmp_t_parientes_mp" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_parientes_mpgrid" class="table ewTable">
<?php echo $t_parientes_mp->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_parientes_mp_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_parientes_mp_grid->RenderListOptions();

// Render list options (header, left)
$t_parientes_mp_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_parientes_mp->Id->Visible) { // Id ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Id) == "") { ?>
		<th data-name="Id"><div id="elh_t_parientes_mp_Id" class="t_parientes_mp_Id"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div><div id="elh_t_parientes_mp_Id" class="t_parientes_mp_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_parientes_mp->Nombres->Visible) { // Nombres ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Nombres) == "") { ?>
		<th data-name="Nombres"><div id="elh_t_parientes_mp_Nombres" class="t_parientes_mp_Nombres"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres"><div><div id="elh_t_parientes_mp_Nombres" class="t_parientes_mp_Nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_parientes_mp->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_t_parientes_mp_Apellido_Paterno" class="t_parientes_mp_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div><div id="elh_t_parientes_mp_Apellido_Paterno" class="t_parientes_mp_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Apellido_Paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_parientes_mp->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_t_parientes_mp_Apellido_Materno" class="t_parientes_mp_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div><div id="elh_t_parientes_mp_Apellido_Materno" class="t_parientes_mp_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Apellido_Materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_parientes_mp->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Grado_Parentesco) == "") { ?>
		<th data-name="Grado_Parentesco"><div id="elh_t_parientes_mp_Grado_Parentesco" class="t_parientes_mp_Grado_Parentesco"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Grado_Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Grado_Parentesco"><div><div id="elh_t_parientes_mp_Grado_Parentesco" class="t_parientes_mp_Grado_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Grado_Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Grado_Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Grado_Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_parientes_mp->Parentesco->Visible) { // Parentesco ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Parentesco) == "") { ?>
		<th data-name="Parentesco"><div id="elh_t_parientes_mp_Parentesco" class="t_parientes_mp_Parentesco"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Parentesco"><div><div id="elh_t_parientes_mp_Parentesco" class="t_parientes_mp_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_parientes_mp->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<?php if ($t_parientes_mp->SortUrl($t_parientes_mp->Unidad_Organizacional) == "") { ?>
		<th data-name="Unidad_Organizacional"><div id="elh_t_parientes_mp_Unidad_Organizacional" class="t_parientes_mp_Unidad_Organizacional"><div class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Unidad_Organizacional->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Unidad_Organizacional"><div><div id="elh_t_parientes_mp_Unidad_Organizacional" class="t_parientes_mp_Unidad_Organizacional">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parientes_mp->Unidad_Organizacional->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parientes_mp->Unidad_Organizacional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parientes_mp->Unidad_Organizacional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_parientes_mp_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_parientes_mp_grid->StartRec = 1;
$t_parientes_mp_grid->StopRec = $t_parientes_mp_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_parientes_mp_grid->FormKeyCountName) && ($t_parientes_mp->CurrentAction == "gridadd" || $t_parientes_mp->CurrentAction == "gridedit" || $t_parientes_mp->CurrentAction == "F")) {
		$t_parientes_mp_grid->KeyCount = $objForm->GetValue($t_parientes_mp_grid->FormKeyCountName);
		$t_parientes_mp_grid->StopRec = $t_parientes_mp_grid->StartRec + $t_parientes_mp_grid->KeyCount - 1;
	}
}
$t_parientes_mp_grid->RecCnt = $t_parientes_mp_grid->StartRec - 1;
if ($t_parientes_mp_grid->Recordset && !$t_parientes_mp_grid->Recordset->EOF) {
	$t_parientes_mp_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_parientes_mp_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_parientes_mp_grid->StartRec > 1)
		$t_parientes_mp_grid->Recordset->Move($t_parientes_mp_grid->StartRec - 1);
} elseif (!$t_parientes_mp->AllowAddDeleteRow && $t_parientes_mp_grid->StopRec == 0) {
	$t_parientes_mp_grid->StopRec = $t_parientes_mp->GridAddRowCount;
}

// Initialize aggregate
$t_parientes_mp->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_parientes_mp->ResetAttrs();
$t_parientes_mp_grid->RenderRow();
if ($t_parientes_mp->CurrentAction == "gridadd")
	$t_parientes_mp_grid->RowIndex = 0;
if ($t_parientes_mp->CurrentAction == "gridedit")
	$t_parientes_mp_grid->RowIndex = 0;
while ($t_parientes_mp_grid->RecCnt < $t_parientes_mp_grid->StopRec) {
	$t_parientes_mp_grid->RecCnt++;
	if (intval($t_parientes_mp_grid->RecCnt) >= intval($t_parientes_mp_grid->StartRec)) {
		$t_parientes_mp_grid->RowCnt++;
		if ($t_parientes_mp->CurrentAction == "gridadd" || $t_parientes_mp->CurrentAction == "gridedit" || $t_parientes_mp->CurrentAction == "F") {
			$t_parientes_mp_grid->RowIndex++;
			$objForm->Index = $t_parientes_mp_grid->RowIndex;
			if ($objForm->HasValue($t_parientes_mp_grid->FormActionName))
				$t_parientes_mp_grid->RowAction = strval($objForm->GetValue($t_parientes_mp_grid->FormActionName));
			elseif ($t_parientes_mp->CurrentAction == "gridadd")
				$t_parientes_mp_grid->RowAction = "insert";
			else
				$t_parientes_mp_grid->RowAction = "";
		}

		// Set up key count
		$t_parientes_mp_grid->KeyCount = $t_parientes_mp_grid->RowIndex;

		// Init row class and style
		$t_parientes_mp->ResetAttrs();
		$t_parientes_mp->CssClass = "";
		if ($t_parientes_mp->CurrentAction == "gridadd") {
			if ($t_parientes_mp->CurrentMode == "copy") {
				$t_parientes_mp_grid->LoadRowValues($t_parientes_mp_grid->Recordset); // Load row values
				$t_parientes_mp_grid->SetRecordKey($t_parientes_mp_grid->RowOldKey, $t_parientes_mp_grid->Recordset); // Set old record key
			} else {
				$t_parientes_mp_grid->LoadDefaultValues(); // Load default values
				$t_parientes_mp_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_parientes_mp_grid->LoadRowValues($t_parientes_mp_grid->Recordset); // Load row values
		}
		$t_parientes_mp->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_parientes_mp->CurrentAction == "gridadd") // Grid add
			$t_parientes_mp->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_parientes_mp->CurrentAction == "gridadd" && $t_parientes_mp->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_parientes_mp_grid->RestoreCurrentRowFormValues($t_parientes_mp_grid->RowIndex); // Restore form values
		if ($t_parientes_mp->CurrentAction == "gridedit") { // Grid edit
			if ($t_parientes_mp->EventCancelled) {
				$t_parientes_mp_grid->RestoreCurrentRowFormValues($t_parientes_mp_grid->RowIndex); // Restore form values
			}
			if ($t_parientes_mp_grid->RowAction == "insert")
				$t_parientes_mp->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_parientes_mp->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_parientes_mp->CurrentAction == "gridedit" && ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT || $t_parientes_mp->RowType == EW_ROWTYPE_ADD) && $t_parientes_mp->EventCancelled) // Update failed
			$t_parientes_mp_grid->RestoreCurrentRowFormValues($t_parientes_mp_grid->RowIndex); // Restore form values
		if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_parientes_mp_grid->EditRowCnt++;
		if ($t_parientes_mp->CurrentAction == "F") // Confirm row
			$t_parientes_mp_grid->RestoreCurrentRowFormValues($t_parientes_mp_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_parientes_mp->RowAttrs = array_merge($t_parientes_mp->RowAttrs, array('data-rowindex'=>$t_parientes_mp_grid->RowCnt, 'id'=>'r' . $t_parientes_mp_grid->RowCnt . '_t_parientes_mp', 'data-rowtype'=>$t_parientes_mp->RowType));

		// Render row
		$t_parientes_mp_grid->RenderRow();

		// Render list options
		$t_parientes_mp_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_parientes_mp_grid->RowAction <> "delete" && $t_parientes_mp_grid->RowAction <> "insertdelete" && !($t_parientes_mp_grid->RowAction == "insert" && $t_parientes_mp->CurrentAction == "F" && $t_parientes_mp_grid->EmptyRow())) {
?>
	<tr<?php echo $t_parientes_mp->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_parientes_mp_grid->ListOptions->Render("body", "left", $t_parientes_mp_grid->RowCnt);
?>
	<?php if ($t_parientes_mp->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $t_parientes_mp->Id->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_parientes_mp->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<span<?php echo $t_parientes_mp->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<input type="text" data-table="t_parientes_mp" data-field="x_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Id->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Id->EditValue ?>"<?php echo $t_parientes_mp->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_parientes_mp->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<span<?php echo $t_parientes_mp->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<input type="text" data-table="t_parientes_mp" data-field="x_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Id->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Id->EditValue ?>"<?php echo $t_parientes_mp->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Id" class="t_parientes_mp_Id">
<span<?php echo $t_parientes_mp->Id->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Id->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_parientes_mp_grid->PageObjName . "_row_" . $t_parientes_mp_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_parientes_mp->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres"<?php echo $t_parientes_mp->Nombres->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Nombres" class="form-group t_parientes_mp_Nombres">
<input type="text" data-table="t_parientes_mp" data-field="x_Nombres" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Nombres->EditValue ?>"<?php echo $t_parientes_mp->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Nombres" class="form-group t_parientes_mp_Nombres">
<span<?php echo $t_parientes_mp->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Nombres->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->CurrentValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Nombres" class="t_parientes_mp_Nombres">
<span<?php echo $t_parientes_mp->Nombres->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Nombres->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $t_parientes_mp->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Apellido_Paterno" class="form-group t_parientes_mp_Apellido_Paterno">
<input type="text" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Apellido_Paterno->EditValue ?>"<?php echo $t_parientes_mp->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Apellido_Paterno" class="form-group t_parientes_mp_Apellido_Paterno">
<span<?php echo $t_parientes_mp->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Apellido_Paterno" class="t_parientes_mp_Apellido_Paterno">
<span<?php echo $t_parientes_mp->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $t_parientes_mp->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Apellido_Materno" class="form-group t_parientes_mp_Apellido_Materno">
<input type="text" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Apellido_Materno->EditValue ?>"<?php echo $t_parientes_mp->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Apellido_Materno" class="form-group t_parientes_mp_Apellido_Materno">
<span<?php echo $t_parientes_mp->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Apellido_Materno" class="t_parientes_mp_Apellido_Materno">
<span<?php echo $t_parientes_mp->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
		<td data-name="Grado_Parentesco"<?php echo $t_parientes_mp->Grado_Parentesco->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Grado_Parentesco" class="form-group t_parientes_mp_Grado_Parentesco">
<?php $t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"]; ?>
<select data-table="t_parientes_mp" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_parientes_mp->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco"<?php echo $t_parientes_mp->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_parientes_mp->Grado_Parentesco->SelectOptionListHtml("x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo $t_parientes_mp->Grado_Parentesco->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="ln_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco">
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Grado_Parentesco" class="form-group t_parientes_mp_Grado_Parentesco">
<?php $t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"]; ?>
<select data-table="t_parientes_mp" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_parientes_mp->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco"<?php echo $t_parientes_mp->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_parientes_mp->Grado_Parentesco->SelectOptionListHtml("x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo $t_parientes_mp->Grado_Parentesco->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="ln_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco">
</span>
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Grado_Parentesco" class="t_parientes_mp_Grado_Parentesco">
<span<?php echo $t_parientes_mp->Grado_Parentesco->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Grado_Parentesco->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco"<?php echo $t_parientes_mp->Parentesco->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Parentesco" class="form-group t_parientes_mp_Parentesco">
<input type="text" data-table="t_parientes_mp" data-field="x_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Parentesco->EditValue ?>"<?php echo $t_parientes_mp->Parentesco->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Parentesco" class="form-group t_parientes_mp_Parentesco">
<input type="text" data-table="t_parientes_mp" data-field="x_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Parentesco->EditValue ?>"<?php echo $t_parientes_mp->Parentesco->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Parentesco" class="t_parientes_mp_Parentesco">
<span<?php echo $t_parientes_mp->Parentesco->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Parentesco->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<td data-name="Unidad_Organizacional"<?php echo $t_parientes_mp->Unidad_Organizacional->CellAttributes() ?>>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Unidad_Organizacional" class="form-group t_parientes_mp_Unidad_Organizacional">
<select data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" data-value-separator="<?php echo $t_parientes_mp->Unidad_Organizacional->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional"<?php echo $t_parientes_mp->Unidad_Organizacional->EditAttributes() ?>>
<?php echo $t_parientes_mp->Unidad_Organizacional->SelectOptionListHtml("x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo $t_parientes_mp->Unidad_Organizacional->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->OldValue) ?>">
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Unidad_Organizacional" class="form-group t_parientes_mp_Unidad_Organizacional">
<select data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" data-value-separator="<?php echo $t_parientes_mp->Unidad_Organizacional->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional"<?php echo $t_parientes_mp->Unidad_Organizacional->EditAttributes() ?>>
<?php echo $t_parientes_mp->Unidad_Organizacional->SelectOptionListHtml("x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo $t_parientes_mp->Unidad_Organizacional->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parientes_mp_grid->RowCnt ?>_t_parientes_mp_Unidad_Organizacional" class="t_parientes_mp_Unidad_Organizacional">
<span<?php echo $t_parientes_mp->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $t_parientes_mp->Unidad_Organizacional->ListViewValue() ?></span>
</span>
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="ft_parientes_mpgrid$x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->FormValue) ?>">
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="ft_parientes_mpgrid$o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_parientes_mp_grid->ListOptions->Render("body", "right", $t_parientes_mp_grid->RowCnt);
?>
	</tr>
<?php if ($t_parientes_mp->RowType == EW_ROWTYPE_ADD || $t_parientes_mp->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_parientes_mpgrid.UpdateOpts(<?php echo $t_parientes_mp_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_parientes_mp->CurrentAction <> "gridadd" || $t_parientes_mp->CurrentMode == "copy")
		if (!$t_parientes_mp_grid->Recordset->EOF) $t_parientes_mp_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_parientes_mp->CurrentMode == "add" || $t_parientes_mp->CurrentMode == "copy" || $t_parientes_mp->CurrentMode == "edit") {
		$t_parientes_mp_grid->RowIndex = '$rowindex$';
		$t_parientes_mp_grid->LoadDefaultValues();

		// Set row properties
		$t_parientes_mp->ResetAttrs();
		$t_parientes_mp->RowAttrs = array_merge($t_parientes_mp->RowAttrs, array('data-rowindex'=>$t_parientes_mp_grid->RowIndex, 'id'=>'r0_t_parientes_mp', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_parientes_mp->RowAttrs["class"], "ewTemplate");
		$t_parientes_mp->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_parientes_mp_grid->RenderRow();

		// Render list options
		$t_parientes_mp_grid->RenderListOptions();
		$t_parientes_mp_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_parientes_mp->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_parientes_mp_grid->ListOptions->Render("body", "left", $t_parientes_mp_grid->RowIndex);
?>
	<?php if ($t_parientes_mp->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<?php if ($t_parientes_mp->Id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<span<?php echo $t_parientes_mp->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<input type="text" data-table="t_parientes_mp" data-field="x_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Id->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Id->EditValue ?>"<?php echo $t_parientes_mp->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Id" class="form-group t_parientes_mp_Id">
<span<?php echo $t_parientes_mp->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Id" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parientes_mp_Nombres" class="form-group t_parientes_mp_Nombres">
<input type="text" data-table="t_parientes_mp" data-field="x_Nombres" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Nombres->EditValue ?>"<?php echo $t_parientes_mp->Nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Nombres" class="form-group t_parientes_mp_Nombres">
<span<?php echo $t_parientes_mp->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parientes_mp_Apellido_Paterno" class="form-group t_parientes_mp_Apellido_Paterno">
<input type="text" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Apellido_Paterno->EditValue ?>"<?php echo $t_parientes_mp->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Apellido_Paterno" class="form-group t_parientes_mp_Apellido_Paterno">
<span<?php echo $t_parientes_mp->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parientes_mp_Apellido_Materno" class="form-group t_parientes_mp_Apellido_Materno">
<input type="text" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Apellido_Materno->EditValue ?>"<?php echo $t_parientes_mp->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Apellido_Materno" class="form-group t_parientes_mp_Apellido_Materno">
<span<?php echo $t_parientes_mp->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
		<td data-name="Grado_Parentesco">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parientes_mp_Grado_Parentesco" class="form-group t_parientes_mp_Grado_Parentesco">
<?php $t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"]; ?>
<select data-table="t_parientes_mp" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_parientes_mp->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco"<?php echo $t_parientes_mp->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_parientes_mp->Grado_Parentesco->SelectOptionListHtml("x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo $t_parientes_mp->Grado_Parentesco->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="ln_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Grado_Parentesco" class="form-group t_parientes_mp_Grado_Parentesco">
<span<?php echo $t_parientes_mp->Grado_Parentesco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Grado_Parentesco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Grado_Parentesco" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Grado_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Grado_Parentesco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parientes_mp_Parentesco" class="form-group t_parientes_mp_Parentesco">
<input type="text" data-table="t_parientes_mp" data-field="x_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Parentesco->EditValue ?>"<?php echo $t_parientes_mp->Parentesco->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Parentesco" class="form-group t_parientes_mp_Parentesco">
<span<?php echo $t_parientes_mp->Parentesco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Parentesco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Parentesco" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_parientes_mp->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<td data-name="Unidad_Organizacional">
<?php if ($t_parientes_mp->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parientes_mp_Unidad_Organizacional" class="form-group t_parientes_mp_Unidad_Organizacional">
<select data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" data-value-separator="<?php echo $t_parientes_mp->Unidad_Organizacional->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional"<?php echo $t_parientes_mp->Unidad_Organizacional->EditAttributes() ?>>
<?php echo $t_parientes_mp->Unidad_Organizacional->SelectOptionListHtml("x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="s_x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo $t_parientes_mp->Unidad_Organizacional->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parientes_mp_Unidad_Organizacional" class="form-group t_parientes_mp_Unidad_Organizacional">
<span<?php echo $t_parientes_mp->Unidad_Organizacional->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Unidad_Organizacional->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="x<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" name="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" id="o<?php echo $t_parientes_mp_grid->RowIndex ?>_Unidad_Organizacional" value="<?php echo ew_HtmlEncode($t_parientes_mp->Unidad_Organizacional->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_parientes_mp_grid->ListOptions->Render("body", "right", $t_parientes_mp_grid->RowCnt);
?>
<script type="text/javascript">
ft_parientes_mpgrid.UpdateOpts(<?php echo $t_parientes_mp_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_parientes_mp->CurrentMode == "add" || $t_parientes_mp->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_parientes_mp_grid->FormKeyCountName ?>" id="<?php echo $t_parientes_mp_grid->FormKeyCountName ?>" value="<?php echo $t_parientes_mp_grid->KeyCount ?>">
<?php echo $t_parientes_mp_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_parientes_mp->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_parientes_mp_grid->FormKeyCountName ?>" id="<?php echo $t_parientes_mp_grid->FormKeyCountName ?>" value="<?php echo $t_parientes_mp_grid->KeyCount ?>">
<?php echo $t_parientes_mp_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_parientes_mp->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_parientes_mpgrid">
</div>
<?php

// Close recordset
if ($t_parientes_mp_grid->Recordset)
	$t_parientes_mp_grid->Recordset->Close();
?>
<?php if ($t_parientes_mp_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_parientes_mp_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_parientes_mp_grid->TotalRecs == 0 && $t_parientes_mp->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_parientes_mp_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_parientes_mp->Export == "") { ?>
<script type="text/javascript">
ft_parientes_mpgrid.Init();
</script>
<?php } ?>
<?php
$t_parientes_mp_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_parientes_mp_grid->Page_Terminate();
?>
