<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_parentesco_grid)) $t_parentesco_grid = new ct_parentesco_grid();

// Page init
$t_parentesco_grid->Page_Init();

// Page main
$t_parentesco_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_parentesco_grid->Page_Render();
?>
<?php if ($t_parentesco->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_parentescogrid = new ew_Form("ft_parentescogrid", "grid");
ft_parentescogrid.FormKeyCountName = '<?php echo $t_parentesco_grid->FormKeyCountName ?>';

// Validate form
ft_parentescogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Parentesco");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_parentesco->Parentesco->FldCaption(), $t_parentesco->Parentesco->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_parentescogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Parentesco", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_parentescogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_parentescogrid.ValidateRequired = true;
<?php } else { ?>
ft_parentescogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($t_parentesco->CurrentAction == "gridadd") {
	if ($t_parentesco->CurrentMode == "copy") {
		$bSelectLimit = $t_parentesco_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_parentesco_grid->TotalRecs = $t_parentesco->SelectRecordCount();
			$t_parentesco_grid->Recordset = $t_parentesco_grid->LoadRecordset($t_parentesco_grid->StartRec-1, $t_parentesco_grid->DisplayRecs);
		} else {
			if ($t_parentesco_grid->Recordset = $t_parentesco_grid->LoadRecordset())
				$t_parentesco_grid->TotalRecs = $t_parentesco_grid->Recordset->RecordCount();
		}
		$t_parentesco_grid->StartRec = 1;
		$t_parentesco_grid->DisplayRecs = $t_parentesco_grid->TotalRecs;
	} else {
		$t_parentesco->CurrentFilter = "0=1";
		$t_parentesco_grid->StartRec = 1;
		$t_parentesco_grid->DisplayRecs = $t_parentesco->GridAddRowCount;
	}
	$t_parentesco_grid->TotalRecs = $t_parentesco_grid->DisplayRecs;
	$t_parentesco_grid->StopRec = $t_parentesco_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_parentesco_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_parentesco_grid->TotalRecs <= 0)
			$t_parentesco_grid->TotalRecs = $t_parentesco->SelectRecordCount();
	} else {
		if (!$t_parentesco_grid->Recordset && ($t_parentesco_grid->Recordset = $t_parentesco_grid->LoadRecordset()))
			$t_parentesco_grid->TotalRecs = $t_parentesco_grid->Recordset->RecordCount();
	}
	$t_parentesco_grid->StartRec = 1;
	$t_parentesco_grid->DisplayRecs = $t_parentesco_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_parentesco_grid->Recordset = $t_parentesco_grid->LoadRecordset($t_parentesco_grid->StartRec-1, $t_parentesco_grid->DisplayRecs);

	// Set no record found message
	if ($t_parentesco->CurrentAction == "" && $t_parentesco_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_parentesco_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_parentesco_grid->SearchWhere == "0=101")
			$t_parentesco_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_parentesco_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_parentesco_grid->RenderOtherOptions();
?>
<?php $t_parentesco_grid->ShowPageHeader(); ?>
<?php
$t_parentesco_grid->ShowMessage();
?>
<?php if ($t_parentesco_grid->TotalRecs > 0 || $t_parentesco->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_parentesco">
<div id="ft_parentescogrid" class="ewForm form-inline">
<div id="gmp_t_parentesco" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_parentescogrid" class="table ewTable">
<?php echo $t_parentesco->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_parentesco_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_parentesco_grid->RenderListOptions();

// Render list options (header, left)
$t_parentesco_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_parentesco->Parentesco->Visible) { // Parentesco ?>
	<?php if ($t_parentesco->SortUrl($t_parentesco->Parentesco) == "") { ?>
		<th data-name="Parentesco"><div id="elh_t_parentesco_Parentesco" class="t_parentesco_Parentesco"><div class="ewTableHeaderCaption"><?php echo $t_parentesco->Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Parentesco"><div><div id="elh_t_parentesco_Parentesco" class="t_parentesco_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_parentesco->Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_parentesco->Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_parentesco->Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_parentesco_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_parentesco_grid->StartRec = 1;
$t_parentesco_grid->StopRec = $t_parentesco_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_parentesco_grid->FormKeyCountName) && ($t_parentesco->CurrentAction == "gridadd" || $t_parentesco->CurrentAction == "gridedit" || $t_parentesco->CurrentAction == "F")) {
		$t_parentesco_grid->KeyCount = $objForm->GetValue($t_parentesco_grid->FormKeyCountName);
		$t_parentesco_grid->StopRec = $t_parentesco_grid->StartRec + $t_parentesco_grid->KeyCount - 1;
	}
}
$t_parentesco_grid->RecCnt = $t_parentesco_grid->StartRec - 1;
if ($t_parentesco_grid->Recordset && !$t_parentesco_grid->Recordset->EOF) {
	$t_parentesco_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_parentesco_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_parentesco_grid->StartRec > 1)
		$t_parentesco_grid->Recordset->Move($t_parentesco_grid->StartRec - 1);
} elseif (!$t_parentesco->AllowAddDeleteRow && $t_parentesco_grid->StopRec == 0) {
	$t_parentesco_grid->StopRec = $t_parentesco->GridAddRowCount;
}

// Initialize aggregate
$t_parentesco->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_parentesco->ResetAttrs();
$t_parentesco_grid->RenderRow();
if ($t_parentesco->CurrentAction == "gridadd")
	$t_parentesco_grid->RowIndex = 0;
if ($t_parentesco->CurrentAction == "gridedit")
	$t_parentesco_grid->RowIndex = 0;
while ($t_parentesco_grid->RecCnt < $t_parentesco_grid->StopRec) {
	$t_parentesco_grid->RecCnt++;
	if (intval($t_parentesco_grid->RecCnt) >= intval($t_parentesco_grid->StartRec)) {
		$t_parentesco_grid->RowCnt++;
		if ($t_parentesco->CurrentAction == "gridadd" || $t_parentesco->CurrentAction == "gridedit" || $t_parentesco->CurrentAction == "F") {
			$t_parentesco_grid->RowIndex++;
			$objForm->Index = $t_parentesco_grid->RowIndex;
			if ($objForm->HasValue($t_parentesco_grid->FormActionName))
				$t_parentesco_grid->RowAction = strval($objForm->GetValue($t_parentesco_grid->FormActionName));
			elseif ($t_parentesco->CurrentAction == "gridadd")
				$t_parentesco_grid->RowAction = "insert";
			else
				$t_parentesco_grid->RowAction = "";
		}

		// Set up key count
		$t_parentesco_grid->KeyCount = $t_parentesco_grid->RowIndex;

		// Init row class and style
		$t_parentesco->ResetAttrs();
		$t_parentesco->CssClass = "";
		if ($t_parentesco->CurrentAction == "gridadd") {
			if ($t_parentesco->CurrentMode == "copy") {
				$t_parentesco_grid->LoadRowValues($t_parentesco_grid->Recordset); // Load row values
				$t_parentesco_grid->SetRecordKey($t_parentesco_grid->RowOldKey, $t_parentesco_grid->Recordset); // Set old record key
			} else {
				$t_parentesco_grid->LoadDefaultValues(); // Load default values
				$t_parentesco_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_parentesco_grid->LoadRowValues($t_parentesco_grid->Recordset); // Load row values
		}
		$t_parentesco->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_parentesco->CurrentAction == "gridadd") // Grid add
			$t_parentesco->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_parentesco->CurrentAction == "gridadd" && $t_parentesco->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_parentesco_grid->RestoreCurrentRowFormValues($t_parentesco_grid->RowIndex); // Restore form values
		if ($t_parentesco->CurrentAction == "gridedit") { // Grid edit
			if ($t_parentesco->EventCancelled) {
				$t_parentesco_grid->RestoreCurrentRowFormValues($t_parentesco_grid->RowIndex); // Restore form values
			}
			if ($t_parentesco_grid->RowAction == "insert")
				$t_parentesco->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_parentesco->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_parentesco->CurrentAction == "gridedit" && ($t_parentesco->RowType == EW_ROWTYPE_EDIT || $t_parentesco->RowType == EW_ROWTYPE_ADD) && $t_parentesco->EventCancelled) // Update failed
			$t_parentesco_grid->RestoreCurrentRowFormValues($t_parentesco_grid->RowIndex); // Restore form values
		if ($t_parentesco->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_parentesco_grid->EditRowCnt++;
		if ($t_parentesco->CurrentAction == "F") // Confirm row
			$t_parentesco_grid->RestoreCurrentRowFormValues($t_parentesco_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_parentesco->RowAttrs = array_merge($t_parentesco->RowAttrs, array('data-rowindex'=>$t_parentesco_grid->RowCnt, 'id'=>'r' . $t_parentesco_grid->RowCnt . '_t_parentesco', 'data-rowtype'=>$t_parentesco->RowType));

		// Render row
		$t_parentesco_grid->RenderRow();

		// Render list options
		$t_parentesco_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_parentesco_grid->RowAction <> "delete" && $t_parentesco_grid->RowAction <> "insertdelete" && !($t_parentesco_grid->RowAction == "insert" && $t_parentesco->CurrentAction == "F" && $t_parentesco_grid->EmptyRow())) {
?>
	<tr<?php echo $t_parentesco->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_parentesco_grid->ListOptions->Render("body", "left", $t_parentesco_grid->RowCnt);
?>
	<?php if ($t_parentesco->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco"<?php echo $t_parentesco->Parentesco->CellAttributes() ?>>
<?php if ($t_parentesco->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_parentesco_grid->RowCnt ?>_t_parentesco_Parentesco" class="form-group t_parentesco_Parentesco">
<input type="text" data-table="t_parentesco" data-field="x_Parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parentesco->Parentesco->EditValue ?>"<?php echo $t_parentesco->Parentesco->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_parentesco->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_parentesco_grid->RowCnt ?>_t_parentesco_Parentesco" class="form-group t_parentesco_Parentesco">
<input type="text" data-table="t_parentesco" data-field="x_Parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parentesco->Parentesco->EditValue ?>"<?php echo $t_parentesco->Parentesco->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_parentesco->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_parentesco_grid->RowCnt ?>_t_parentesco_Parentesco" class="t_parentesco_Parentesco">
<span<?php echo $t_parentesco->Parentesco->ViewAttributes() ?>>
<?php echo $t_parentesco->Parentesco->ListViewValue() ?></span>
</span>
<?php if ($t_parentesco->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="ft_parentescogrid$x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="ft_parentescogrid$x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->FormValue) ?>">
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="ft_parentescogrid$o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="ft_parentescogrid$o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_parentesco_grid->PageObjName . "_row_" . $t_parentesco_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_parentesco->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_parentesco" data-field="x_Id_parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Id_parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Id_parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Id_parentesco->CurrentValue) ?>">
<input type="hidden" data-table="t_parentesco" data-field="x_Id_parentesco" name="o<?php echo $t_parentesco_grid->RowIndex ?>_Id_parentesco" id="o<?php echo $t_parentesco_grid->RowIndex ?>_Id_parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Id_parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_parentesco->RowType == EW_ROWTYPE_EDIT || $t_parentesco->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_parentesco" data-field="x_Id_parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Id_parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Id_parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Id_parentesco->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$t_parentesco_grid->ListOptions->Render("body", "right", $t_parentesco_grid->RowCnt);
?>
	</tr>
<?php if ($t_parentesco->RowType == EW_ROWTYPE_ADD || $t_parentesco->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_parentescogrid.UpdateOpts(<?php echo $t_parentesco_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_parentesco->CurrentAction <> "gridadd" || $t_parentesco->CurrentMode == "copy")
		if (!$t_parentesco_grid->Recordset->EOF) $t_parentesco_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_parentesco->CurrentMode == "add" || $t_parentesco->CurrentMode == "copy" || $t_parentesco->CurrentMode == "edit") {
		$t_parentesco_grid->RowIndex = '$rowindex$';
		$t_parentesco_grid->LoadDefaultValues();

		// Set row properties
		$t_parentesco->ResetAttrs();
		$t_parentesco->RowAttrs = array_merge($t_parentesco->RowAttrs, array('data-rowindex'=>$t_parentesco_grid->RowIndex, 'id'=>'r0_t_parentesco', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_parentesco->RowAttrs["class"], "ewTemplate");
		$t_parentesco->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_parentesco_grid->RenderRow();

		// Render list options
		$t_parentesco_grid->RenderListOptions();
		$t_parentesco_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_parentesco->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_parentesco_grid->ListOptions->Render("body", "left", $t_parentesco_grid->RowIndex);
?>
	<?php if ($t_parentesco->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco">
<?php if ($t_parentesco->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_parentesco_Parentesco" class="form-group t_parentesco_Parentesco">
<input type="text" data-table="t_parentesco" data-field="x_Parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parentesco->Parentesco->EditValue ?>"<?php echo $t_parentesco->Parentesco->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_parentesco_Parentesco" class="form-group t_parentesco_Parentesco">
<span<?php echo $t_parentesco->Parentesco->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parentesco->Parentesco->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="x<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_parentesco" data-field="x_Parentesco" name="o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" id="o<?php echo $t_parentesco_grid->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_parentesco->Parentesco->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_parentesco_grid->ListOptions->Render("body", "right", $t_parentesco_grid->RowCnt);
?>
<script type="text/javascript">
ft_parentescogrid.UpdateOpts(<?php echo $t_parentesco_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_parentesco->CurrentMode == "add" || $t_parentesco->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_parentesco_grid->FormKeyCountName ?>" id="<?php echo $t_parentesco_grid->FormKeyCountName ?>" value="<?php echo $t_parentesco_grid->KeyCount ?>">
<?php echo $t_parentesco_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_parentesco->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_parentesco_grid->FormKeyCountName ?>" id="<?php echo $t_parentesco_grid->FormKeyCountName ?>" value="<?php echo $t_parentesco_grid->KeyCount ?>">
<?php echo $t_parentesco_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_parentesco->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_parentescogrid">
</div>
<?php

// Close recordset
if ($t_parentesco_grid->Recordset)
	$t_parentesco_grid->Recordset->Close();
?>
<?php if ($t_parentesco_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_parentesco_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_parentesco_grid->TotalRecs == 0 && $t_parentesco->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_parentesco_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_parentesco->Export == "") { ?>
<script type="text/javascript">
ft_parentescogrid.Init();
</script>
<?php } ?>
<?php
$t_parentesco_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_parentesco_grid->Page_Terminate();
?>
