<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_unidad_grid)) $t_unidad_grid = new ct_unidad_grid();

// Page init
$t_unidad_grid->Page_Init();

// Page main
$t_unidad_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_unidad_grid->Page_Render();
?>
<?php if ($t_unidad->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_unidadgrid = new ew_Form("ft_unidadgrid", "grid");
ft_unidadgrid.FormKeyCountName = '<?php echo $t_unidad_grid->FormKeyCountName ?>';

// Validate form
ft_unidadgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Unidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_unidad->Unidad->FldCaption(), $t_unidad->Unidad->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_unidadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Unidad", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_unidadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_unidadgrid.ValidateRequired = true;
<?php } else { ?>
ft_unidadgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($t_unidad->CurrentAction == "gridadd") {
	if ($t_unidad->CurrentMode == "copy") {
		$bSelectLimit = $t_unidad_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_unidad_grid->TotalRecs = $t_unidad->SelectRecordCount();
			$t_unidad_grid->Recordset = $t_unidad_grid->LoadRecordset($t_unidad_grid->StartRec-1, $t_unidad_grid->DisplayRecs);
		} else {
			if ($t_unidad_grid->Recordset = $t_unidad_grid->LoadRecordset())
				$t_unidad_grid->TotalRecs = $t_unidad_grid->Recordset->RecordCount();
		}
		$t_unidad_grid->StartRec = 1;
		$t_unidad_grid->DisplayRecs = $t_unidad_grid->TotalRecs;
	} else {
		$t_unidad->CurrentFilter = "0=1";
		$t_unidad_grid->StartRec = 1;
		$t_unidad_grid->DisplayRecs = $t_unidad->GridAddRowCount;
	}
	$t_unidad_grid->TotalRecs = $t_unidad_grid->DisplayRecs;
	$t_unidad_grid->StopRec = $t_unidad_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_unidad_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_unidad_grid->TotalRecs <= 0)
			$t_unidad_grid->TotalRecs = $t_unidad->SelectRecordCount();
	} else {
		if (!$t_unidad_grid->Recordset && ($t_unidad_grid->Recordset = $t_unidad_grid->LoadRecordset()))
			$t_unidad_grid->TotalRecs = $t_unidad_grid->Recordset->RecordCount();
	}
	$t_unidad_grid->StartRec = 1;
	$t_unidad_grid->DisplayRecs = $t_unidad_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_unidad_grid->Recordset = $t_unidad_grid->LoadRecordset($t_unidad_grid->StartRec-1, $t_unidad_grid->DisplayRecs);

	// Set no record found message
	if ($t_unidad->CurrentAction == "" && $t_unidad_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_unidad_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_unidad_grid->SearchWhere == "0=101")
			$t_unidad_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_unidad_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_unidad_grid->RenderOtherOptions();
?>
<?php $t_unidad_grid->ShowPageHeader(); ?>
<?php
$t_unidad_grid->ShowMessage();
?>
<?php if ($t_unidad_grid->TotalRecs > 0 || $t_unidad->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_unidad">
<div id="ft_unidadgrid" class="ewForm form-inline">
<div id="gmp_t_unidad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_unidadgrid" class="table ewTable">
<?php echo $t_unidad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_unidad_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_unidad_grid->RenderListOptions();

// Render list options (header, left)
$t_unidad_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_unidad->Unidad->Visible) { // Unidad ?>
	<?php if ($t_unidad->SortUrl($t_unidad->Unidad) == "") { ?>
		<th data-name="Unidad"><div id="elh_t_unidad_Unidad" class="t_unidad_Unidad"><div class="ewTableHeaderCaption"><?php echo $t_unidad->Unidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Unidad"><div><div id="elh_t_unidad_Unidad" class="t_unidad_Unidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_unidad->Unidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_unidad->Unidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_unidad->Unidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_unidad_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_unidad_grid->StartRec = 1;
$t_unidad_grid->StopRec = $t_unidad_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_unidad_grid->FormKeyCountName) && ($t_unidad->CurrentAction == "gridadd" || $t_unidad->CurrentAction == "gridedit" || $t_unidad->CurrentAction == "F")) {
		$t_unidad_grid->KeyCount = $objForm->GetValue($t_unidad_grid->FormKeyCountName);
		$t_unidad_grid->StopRec = $t_unidad_grid->StartRec + $t_unidad_grid->KeyCount - 1;
	}
}
$t_unidad_grid->RecCnt = $t_unidad_grid->StartRec - 1;
if ($t_unidad_grid->Recordset && !$t_unidad_grid->Recordset->EOF) {
	$t_unidad_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_unidad_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_unidad_grid->StartRec > 1)
		$t_unidad_grid->Recordset->Move($t_unidad_grid->StartRec - 1);
} elseif (!$t_unidad->AllowAddDeleteRow && $t_unidad_grid->StopRec == 0) {
	$t_unidad_grid->StopRec = $t_unidad->GridAddRowCount;
}

// Initialize aggregate
$t_unidad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_unidad->ResetAttrs();
$t_unidad_grid->RenderRow();
if ($t_unidad->CurrentAction == "gridadd")
	$t_unidad_grid->RowIndex = 0;
if ($t_unidad->CurrentAction == "gridedit")
	$t_unidad_grid->RowIndex = 0;
while ($t_unidad_grid->RecCnt < $t_unidad_grid->StopRec) {
	$t_unidad_grid->RecCnt++;
	if (intval($t_unidad_grid->RecCnt) >= intval($t_unidad_grid->StartRec)) {
		$t_unidad_grid->RowCnt++;
		if ($t_unidad->CurrentAction == "gridadd" || $t_unidad->CurrentAction == "gridedit" || $t_unidad->CurrentAction == "F") {
			$t_unidad_grid->RowIndex++;
			$objForm->Index = $t_unidad_grid->RowIndex;
			if ($objForm->HasValue($t_unidad_grid->FormActionName))
				$t_unidad_grid->RowAction = strval($objForm->GetValue($t_unidad_grid->FormActionName));
			elseif ($t_unidad->CurrentAction == "gridadd")
				$t_unidad_grid->RowAction = "insert";
			else
				$t_unidad_grid->RowAction = "";
		}

		// Set up key count
		$t_unidad_grid->KeyCount = $t_unidad_grid->RowIndex;

		// Init row class and style
		$t_unidad->ResetAttrs();
		$t_unidad->CssClass = "";
		if ($t_unidad->CurrentAction == "gridadd") {
			if ($t_unidad->CurrentMode == "copy") {
				$t_unidad_grid->LoadRowValues($t_unidad_grid->Recordset); // Load row values
				$t_unidad_grid->SetRecordKey($t_unidad_grid->RowOldKey, $t_unidad_grid->Recordset); // Set old record key
			} else {
				$t_unidad_grid->LoadDefaultValues(); // Load default values
				$t_unidad_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_unidad_grid->LoadRowValues($t_unidad_grid->Recordset); // Load row values
		}
		$t_unidad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_unidad->CurrentAction == "gridadd") // Grid add
			$t_unidad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_unidad->CurrentAction == "gridadd" && $t_unidad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_unidad_grid->RestoreCurrentRowFormValues($t_unidad_grid->RowIndex); // Restore form values
		if ($t_unidad->CurrentAction == "gridedit") { // Grid edit
			if ($t_unidad->EventCancelled) {
				$t_unidad_grid->RestoreCurrentRowFormValues($t_unidad_grid->RowIndex); // Restore form values
			}
			if ($t_unidad_grid->RowAction == "insert")
				$t_unidad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_unidad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_unidad->CurrentAction == "gridedit" && ($t_unidad->RowType == EW_ROWTYPE_EDIT || $t_unidad->RowType == EW_ROWTYPE_ADD) && $t_unidad->EventCancelled) // Update failed
			$t_unidad_grid->RestoreCurrentRowFormValues($t_unidad_grid->RowIndex); // Restore form values
		if ($t_unidad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_unidad_grid->EditRowCnt++;
		if ($t_unidad->CurrentAction == "F") // Confirm row
			$t_unidad_grid->RestoreCurrentRowFormValues($t_unidad_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_unidad->RowAttrs = array_merge($t_unidad->RowAttrs, array('data-rowindex'=>$t_unidad_grid->RowCnt, 'id'=>'r' . $t_unidad_grid->RowCnt . '_t_unidad', 'data-rowtype'=>$t_unidad->RowType));

		// Render row
		$t_unidad_grid->RenderRow();

		// Render list options
		$t_unidad_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_unidad_grid->RowAction <> "delete" && $t_unidad_grid->RowAction <> "insertdelete" && !($t_unidad_grid->RowAction == "insert" && $t_unidad->CurrentAction == "F" && $t_unidad_grid->EmptyRow())) {
?>
	<tr<?php echo $t_unidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_unidad_grid->ListOptions->Render("body", "left", $t_unidad_grid->RowCnt);
?>
	<?php if ($t_unidad->Unidad->Visible) { // Unidad ?>
		<td data-name="Unidad"<?php echo $t_unidad->Unidad->CellAttributes() ?>>
<?php if ($t_unidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_unidad_grid->RowCnt ?>_t_unidad_Unidad" class="form-group t_unidad_Unidad">
<input type="text" data-table="t_unidad" data-field="x_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_unidad->Unidad->getPlaceHolder()) ?>" value="<?php echo $t_unidad->Unidad->EditValue ?>"<?php echo $t_unidad->Unidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->OldValue) ?>">
<?php } ?>
<?php if ($t_unidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_unidad_grid->RowCnt ?>_t_unidad_Unidad" class="form-group t_unidad_Unidad">
<input type="text" data-table="t_unidad" data-field="x_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_unidad->Unidad->getPlaceHolder()) ?>" value="<?php echo $t_unidad->Unidad->EditValue ?>"<?php echo $t_unidad->Unidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_unidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_unidad_grid->RowCnt ?>_t_unidad_Unidad" class="t_unidad_Unidad">
<span<?php echo $t_unidad->Unidad->ViewAttributes() ?>>
<?php echo $t_unidad->Unidad->ListViewValue() ?></span>
</span>
<?php if ($t_unidad->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->FormValue) ?>">
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="ft_unidadgrid$x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="ft_unidadgrid$x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->FormValue) ?>">
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="ft_unidadgrid$o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="ft_unidadgrid$o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_unidad_grid->PageObjName . "_row_" . $t_unidad_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_unidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_unidad" data-field="x_Id_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Id_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Id_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Id_Unidad->CurrentValue) ?>">
<input type="hidden" data-table="t_unidad" data-field="x_Id_Unidad" name="o<?php echo $t_unidad_grid->RowIndex ?>_Id_Unidad" id="o<?php echo $t_unidad_grid->RowIndex ?>_Id_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Id_Unidad->OldValue) ?>">
<?php } ?>
<?php if ($t_unidad->RowType == EW_ROWTYPE_EDIT || $t_unidad->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_unidad" data-field="x_Id_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Id_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Id_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Id_Unidad->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$t_unidad_grid->ListOptions->Render("body", "right", $t_unidad_grid->RowCnt);
?>
	</tr>
<?php if ($t_unidad->RowType == EW_ROWTYPE_ADD || $t_unidad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_unidadgrid.UpdateOpts(<?php echo $t_unidad_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_unidad->CurrentAction <> "gridadd" || $t_unidad->CurrentMode == "copy")
		if (!$t_unidad_grid->Recordset->EOF) $t_unidad_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_unidad->CurrentMode == "add" || $t_unidad->CurrentMode == "copy" || $t_unidad->CurrentMode == "edit") {
		$t_unidad_grid->RowIndex = '$rowindex$';
		$t_unidad_grid->LoadDefaultValues();

		// Set row properties
		$t_unidad->ResetAttrs();
		$t_unidad->RowAttrs = array_merge($t_unidad->RowAttrs, array('data-rowindex'=>$t_unidad_grid->RowIndex, 'id'=>'r0_t_unidad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_unidad->RowAttrs["class"], "ewTemplate");
		$t_unidad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_unidad_grid->RenderRow();

		// Render list options
		$t_unidad_grid->RenderListOptions();
		$t_unidad_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_unidad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_unidad_grid->ListOptions->Render("body", "left", $t_unidad_grid->RowIndex);
?>
	<?php if ($t_unidad->Unidad->Visible) { // Unidad ?>
		<td data-name="Unidad">
<?php if ($t_unidad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_unidad_Unidad" class="form-group t_unidad_Unidad">
<input type="text" data-table="t_unidad" data-field="x_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_unidad->Unidad->getPlaceHolder()) ?>" value="<?php echo $t_unidad->Unidad->EditValue ?>"<?php echo $t_unidad->Unidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_unidad_Unidad" class="form-group t_unidad_Unidad">
<span<?php echo $t_unidad->Unidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_unidad->Unidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="x<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_unidad" data-field="x_Unidad" name="o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" id="o<?php echo $t_unidad_grid->RowIndex ?>_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Unidad->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_unidad_grid->ListOptions->Render("body", "right", $t_unidad_grid->RowCnt);
?>
<script type="text/javascript">
ft_unidadgrid.UpdateOpts(<?php echo $t_unidad_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_unidad->CurrentMode == "add" || $t_unidad->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_unidad_grid->FormKeyCountName ?>" id="<?php echo $t_unidad_grid->FormKeyCountName ?>" value="<?php echo $t_unidad_grid->KeyCount ?>">
<?php echo $t_unidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_unidad->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_unidad_grid->FormKeyCountName ?>" id="<?php echo $t_unidad_grid->FormKeyCountName ?>" value="<?php echo $t_unidad_grid->KeyCount ?>">
<?php echo $t_unidad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_unidad->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_unidadgrid">
</div>
<?php

// Close recordset
if ($t_unidad_grid->Recordset)
	$t_unidad_grid->Recordset->Close();
?>
<?php if ($t_unidad_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_unidad_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_unidad_grid->TotalRecs == 0 && $t_unidad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_unidad_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_unidad->Export == "") { ?>
<script type="text/javascript">
ft_unidadgrid.Init();
</script>
<?php } ?>
<?php
$t_unidad_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_unidad_grid->Page_Terminate();
?>
