<?php include_once "t_usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($t_actiividades_remuneradas_grid)) $t_actiividades_remuneradas_grid = new ct_actiividades_remuneradas_grid();

// Page init
$t_actiividades_remuneradas_grid->Page_Init();

// Page main
$t_actiividades_remuneradas_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_actiividades_remuneradas_grid->Page_Render();
?>
<?php if ($t_actiividades_remuneradas->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_actiividades_remuneradasgrid = new ew_Form("ft_actiividades_remuneradasgrid", "grid");
ft_actiividades_remuneradasgrid.FormKeyCountName = '<?php echo $t_actiividades_remuneradas_grid->FormKeyCountName ?>';

// Validate form
ft_actiividades_remuneradasgrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_actiividades_remuneradas->Id->FldCaption(), $t_actiividades_remuneradas->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_actiividades_remuneradas->Id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_actiividades_remuneradasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Tipo_Actividad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Actividad_Si[]", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Actividad_No[]", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Entidad", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Sector", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Remunerada", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_actiividades_remuneradasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_actiividades_remuneradasgrid.ValidateRequired = true;
<?php } else { ?>
ft_actiividades_remuneradasgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_actiividades_remuneradasgrid.Lists["x_Actividad_Si[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasgrid.Lists["x_Actividad_Si[]"].Options = <?php echo json_encode($t_actiividades_remuneradas->Actividad_Si->Options()) ?>;
ft_actiividades_remuneradasgrid.Lists["x_Actividad_No[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasgrid.Lists["x_Actividad_No[]"].Options = <?php echo json_encode($t_actiividades_remuneradas->Actividad_No->Options()) ?>;
ft_actiividades_remuneradasgrid.Lists["x_Sector"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasgrid.Lists["x_Sector"].Options = <?php echo json_encode($t_actiividades_remuneradas->Sector->Options()) ?>;
ft_actiividades_remuneradasgrid.Lists["x_Remunerada"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasgrid.Lists["x_Remunerada"].Options = <?php echo json_encode($t_actiividades_remuneradas->Remunerada->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($t_actiividades_remuneradas->CurrentAction == "gridadd") {
	if ($t_actiividades_remuneradas->CurrentMode == "copy") {
		$bSelectLimit = $t_actiividades_remuneradas_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_actiividades_remuneradas_grid->TotalRecs = $t_actiividades_remuneradas->SelectRecordCount();
			$t_actiividades_remuneradas_grid->Recordset = $t_actiividades_remuneradas_grid->LoadRecordset($t_actiividades_remuneradas_grid->StartRec-1, $t_actiividades_remuneradas_grid->DisplayRecs);
		} else {
			if ($t_actiividades_remuneradas_grid->Recordset = $t_actiividades_remuneradas_grid->LoadRecordset())
				$t_actiividades_remuneradas_grid->TotalRecs = $t_actiividades_remuneradas_grid->Recordset->RecordCount();
		}
		$t_actiividades_remuneradas_grid->StartRec = 1;
		$t_actiividades_remuneradas_grid->DisplayRecs = $t_actiividades_remuneradas_grid->TotalRecs;
	} else {
		$t_actiividades_remuneradas->CurrentFilter = "0=1";
		$t_actiividades_remuneradas_grid->StartRec = 1;
		$t_actiividades_remuneradas_grid->DisplayRecs = $t_actiividades_remuneradas->GridAddRowCount;
	}
	$t_actiividades_remuneradas_grid->TotalRecs = $t_actiividades_remuneradas_grid->DisplayRecs;
	$t_actiividades_remuneradas_grid->StopRec = $t_actiividades_remuneradas_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_actiividades_remuneradas_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_actiividades_remuneradas_grid->TotalRecs <= 0)
			$t_actiividades_remuneradas_grid->TotalRecs = $t_actiividades_remuneradas->SelectRecordCount();
	} else {
		if (!$t_actiividades_remuneradas_grid->Recordset && ($t_actiividades_remuneradas_grid->Recordset = $t_actiividades_remuneradas_grid->LoadRecordset()))
			$t_actiividades_remuneradas_grid->TotalRecs = $t_actiividades_remuneradas_grid->Recordset->RecordCount();
	}
	$t_actiividades_remuneradas_grid->StartRec = 1;
	$t_actiividades_remuneradas_grid->DisplayRecs = $t_actiividades_remuneradas_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_actiividades_remuneradas_grid->Recordset = $t_actiividades_remuneradas_grid->LoadRecordset($t_actiividades_remuneradas_grid->StartRec-1, $t_actiividades_remuneradas_grid->DisplayRecs);

	// Set no record found message
	if ($t_actiividades_remuneradas->CurrentAction == "" && $t_actiividades_remuneradas_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_actiividades_remuneradas_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_actiividades_remuneradas_grid->SearchWhere == "0=101")
			$t_actiividades_remuneradas_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_actiividades_remuneradas_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_actiividades_remuneradas_grid->RenderOtherOptions();
?>
<?php $t_actiividades_remuneradas_grid->ShowPageHeader(); ?>
<?php
$t_actiividades_remuneradas_grid->ShowMessage();
?>
<?php if ($t_actiividades_remuneradas_grid->TotalRecs > 0 || $t_actiividades_remuneradas->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_actiividades_remuneradas">
<div id="ft_actiividades_remuneradasgrid" class="ewForm form-inline">
<div id="gmp_t_actiividades_remuneradas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_actiividades_remuneradasgrid" class="table ewTable">
<?php echo $t_actiividades_remuneradas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_actiividades_remuneradas_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_actiividades_remuneradas_grid->RenderListOptions();

// Render list options (header, left)
$t_actiividades_remuneradas_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_actiividades_remuneradas->Id->Visible) { // Id ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Id) == "") { ?>
		<th data-name="Id"><div id="elh_t_actiividades_remuneradas_Id" class="t_actiividades_remuneradas_Id"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Id"><div><div id="elh_t_actiividades_remuneradas_Id" class="t_actiividades_remuneradas_Id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_actiividades_remuneradas->Tipo_Actividad->Visible) { // Tipo_Actividad ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Tipo_Actividad) == "") { ?>
		<th data-name="Tipo_Actividad"><div id="elh_t_actiividades_remuneradas_Tipo_Actividad" class="t_actiividades_remuneradas_Tipo_Actividad"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tipo_Actividad"><div><div id="elh_t_actiividades_remuneradas_Tipo_Actividad" class="t_actiividades_remuneradas_Tipo_Actividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Tipo_Actividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Tipo_Actividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_actiividades_remuneradas->Actividad_Si->Visible) { // Actividad_Si ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Actividad_Si) == "") { ?>
		<th data-name="Actividad_Si"><div id="elh_t_actiividades_remuneradas_Actividad_Si" class="t_actiividades_remuneradas_Actividad_Si"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_Si->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Actividad_Si"><div><div id="elh_t_actiividades_remuneradas_Actividad_Si" class="t_actiividades_remuneradas_Actividad_Si">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_Si->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Actividad_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Actividad_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_actiividades_remuneradas->Actividad_No->Visible) { // Actividad_No ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Actividad_No) == "") { ?>
		<th data-name="Actividad_No"><div id="elh_t_actiividades_remuneradas_Actividad_No" class="t_actiividades_remuneradas_Actividad_No"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_No->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Actividad_No"><div><div id="elh_t_actiividades_remuneradas_Actividad_No" class="t_actiividades_remuneradas_Actividad_No">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_No->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Actividad_No->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Actividad_No->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_actiividades_remuneradas->Entidad->Visible) { // Entidad ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Entidad) == "") { ?>
		<th data-name="Entidad"><div id="elh_t_actiividades_remuneradas_Entidad" class="t_actiividades_remuneradas_Entidad"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Entidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Entidad"><div><div id="elh_t_actiividades_remuneradas_Entidad" class="t_actiividades_remuneradas_Entidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Entidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Entidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Entidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_actiividades_remuneradas->Sector->Visible) { // Sector ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Sector) == "") { ?>
		<th data-name="Sector"><div id="elh_t_actiividades_remuneradas_Sector" class="t_actiividades_remuneradas_Sector"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Sector->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Sector"><div><div id="elh_t_actiividades_remuneradas_Sector" class="t_actiividades_remuneradas_Sector">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_actiividades_remuneradas->Remunerada->Visible) { // Remunerada ?>
	<?php if ($t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Remunerada) == "") { ?>
		<th data-name="Remunerada"><div id="elh_t_actiividades_remuneradas_Remunerada" class="t_actiividades_remuneradas_Remunerada"><div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Remunerada->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Remunerada"><div><div id="elh_t_actiividades_remuneradas_Remunerada" class="t_actiividades_remuneradas_Remunerada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Remunerada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Remunerada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Remunerada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_actiividades_remuneradas_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_actiividades_remuneradas_grid->StartRec = 1;
$t_actiividades_remuneradas_grid->StopRec = $t_actiividades_remuneradas_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_actiividades_remuneradas_grid->FormKeyCountName) && ($t_actiividades_remuneradas->CurrentAction == "gridadd" || $t_actiividades_remuneradas->CurrentAction == "gridedit" || $t_actiividades_remuneradas->CurrentAction == "F")) {
		$t_actiividades_remuneradas_grid->KeyCount = $objForm->GetValue($t_actiividades_remuneradas_grid->FormKeyCountName);
		$t_actiividades_remuneradas_grid->StopRec = $t_actiividades_remuneradas_grid->StartRec + $t_actiividades_remuneradas_grid->KeyCount - 1;
	}
}
$t_actiividades_remuneradas_grid->RecCnt = $t_actiividades_remuneradas_grid->StartRec - 1;
if ($t_actiividades_remuneradas_grid->Recordset && !$t_actiividades_remuneradas_grid->Recordset->EOF) {
	$t_actiividades_remuneradas_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_actiividades_remuneradas_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_actiividades_remuneradas_grid->StartRec > 1)
		$t_actiividades_remuneradas_grid->Recordset->Move($t_actiividades_remuneradas_grid->StartRec - 1);
} elseif (!$t_actiividades_remuneradas->AllowAddDeleteRow && $t_actiividades_remuneradas_grid->StopRec == 0) {
	$t_actiividades_remuneradas_grid->StopRec = $t_actiividades_remuneradas->GridAddRowCount;
}

// Initialize aggregate
$t_actiividades_remuneradas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_actiividades_remuneradas->ResetAttrs();
$t_actiividades_remuneradas_grid->RenderRow();
if ($t_actiividades_remuneradas->CurrentAction == "gridadd")
	$t_actiividades_remuneradas_grid->RowIndex = 0;
if ($t_actiividades_remuneradas->CurrentAction == "gridedit")
	$t_actiividades_remuneradas_grid->RowIndex = 0;
while ($t_actiividades_remuneradas_grid->RecCnt < $t_actiividades_remuneradas_grid->StopRec) {
	$t_actiividades_remuneradas_grid->RecCnt++;
	if (intval($t_actiividades_remuneradas_grid->RecCnt) >= intval($t_actiividades_remuneradas_grid->StartRec)) {
		$t_actiividades_remuneradas_grid->RowCnt++;
		if ($t_actiividades_remuneradas->CurrentAction == "gridadd" || $t_actiividades_remuneradas->CurrentAction == "gridedit" || $t_actiividades_remuneradas->CurrentAction == "F") {
			$t_actiividades_remuneradas_grid->RowIndex++;
			$objForm->Index = $t_actiividades_remuneradas_grid->RowIndex;
			if ($objForm->HasValue($t_actiividades_remuneradas_grid->FormActionName))
				$t_actiividades_remuneradas_grid->RowAction = strval($objForm->GetValue($t_actiividades_remuneradas_grid->FormActionName));
			elseif ($t_actiividades_remuneradas->CurrentAction == "gridadd")
				$t_actiividades_remuneradas_grid->RowAction = "insert";
			else
				$t_actiividades_remuneradas_grid->RowAction = "";
		}

		// Set up key count
		$t_actiividades_remuneradas_grid->KeyCount = $t_actiividades_remuneradas_grid->RowIndex;

		// Init row class and style
		$t_actiividades_remuneradas->ResetAttrs();
		$t_actiividades_remuneradas->CssClass = "";
		if ($t_actiividades_remuneradas->CurrentAction == "gridadd") {
			if ($t_actiividades_remuneradas->CurrentMode == "copy") {
				$t_actiividades_remuneradas_grid->LoadRowValues($t_actiividades_remuneradas_grid->Recordset); // Load row values
				$t_actiividades_remuneradas_grid->SetRecordKey($t_actiividades_remuneradas_grid->RowOldKey, $t_actiividades_remuneradas_grid->Recordset); // Set old record key
			} else {
				$t_actiividades_remuneradas_grid->LoadDefaultValues(); // Load default values
				$t_actiividades_remuneradas_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_actiividades_remuneradas_grid->LoadRowValues($t_actiividades_remuneradas_grid->Recordset); // Load row values
		}
		$t_actiividades_remuneradas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_actiividades_remuneradas->CurrentAction == "gridadd") // Grid add
			$t_actiividades_remuneradas->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_actiividades_remuneradas->CurrentAction == "gridadd" && $t_actiividades_remuneradas->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_actiividades_remuneradas_grid->RestoreCurrentRowFormValues($t_actiividades_remuneradas_grid->RowIndex); // Restore form values
		if ($t_actiividades_remuneradas->CurrentAction == "gridedit") { // Grid edit
			if ($t_actiividades_remuneradas->EventCancelled) {
				$t_actiividades_remuneradas_grid->RestoreCurrentRowFormValues($t_actiividades_remuneradas_grid->RowIndex); // Restore form values
			}
			if ($t_actiividades_remuneradas_grid->RowAction == "insert")
				$t_actiividades_remuneradas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_actiividades_remuneradas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_actiividades_remuneradas->CurrentAction == "gridedit" && ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT || $t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) && $t_actiividades_remuneradas->EventCancelled) // Update failed
			$t_actiividades_remuneradas_grid->RestoreCurrentRowFormValues($t_actiividades_remuneradas_grid->RowIndex); // Restore form values
		if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_actiividades_remuneradas_grid->EditRowCnt++;
		if ($t_actiividades_remuneradas->CurrentAction == "F") // Confirm row
			$t_actiividades_remuneradas_grid->RestoreCurrentRowFormValues($t_actiividades_remuneradas_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_actiividades_remuneradas->RowAttrs = array_merge($t_actiividades_remuneradas->RowAttrs, array('data-rowindex'=>$t_actiividades_remuneradas_grid->RowCnt, 'id'=>'r' . $t_actiividades_remuneradas_grid->RowCnt . '_t_actiividades_remuneradas', 'data-rowtype'=>$t_actiividades_remuneradas->RowType));

		// Render row
		$t_actiividades_remuneradas_grid->RenderRow();

		// Render list options
		$t_actiividades_remuneradas_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_actiividades_remuneradas_grid->RowAction <> "delete" && $t_actiividades_remuneradas_grid->RowAction <> "insertdelete" && !($t_actiividades_remuneradas_grid->RowAction == "insert" && $t_actiividades_remuneradas->CurrentAction == "F" && $t_actiividades_remuneradas_grid->EmptyRow())) {
?>
	<tr<?php echo $t_actiividades_remuneradas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_actiividades_remuneradas_grid->ListOptions->Render("body", "left", $t_actiividades_remuneradas_grid->RowCnt);
?>
	<?php if ($t_actiividades_remuneradas->Id->Visible) { // Id ?>
		<td data-name="Id"<?php echo $t_actiividades_remuneradas->Id->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_actiividades_remuneradas->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Id" class="form-group t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Id" class="form-group t_actiividades_remuneradas_Id">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Id->EditValue ?>"<?php echo $t_actiividades_remuneradas->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Id" class="form-group t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Id" class="t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Id->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_actiividades_remuneradas_grid->PageObjName . "_row_" . $t_actiividades_remuneradas_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Tipo_Actividad->Visible) { // Tipo_Actividad ?>
		<td data-name="Tipo_Actividad"<?php echo $t_actiividades_remuneradas->Tipo_Actividad->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad" class="form-group t_actiividades_remuneradas_Tipo_Actividad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad" class="form-group t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->CurrentValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad" class="t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Actividad_Si->Visible) { // Actividad_Si ?>
		<td data-name="Actividad_Si"<?php echo $t_actiividades_remuneradas->Actividad_Si->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si" class="form-group t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_grid->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si" class="form-group t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_grid->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si" class="t_actiividades_remuneradas_Actividad_Si">
<span<?php echo $t_actiividades_remuneradas->Actividad_Si->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Actividad_Si->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Actividad_No->Visible) { // Actividad_No ?>
		<td data-name="Actividad_No"<?php echo $t_actiividades_remuneradas->Actividad_No->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Actividad_No" class="form-group t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_grid->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Actividad_No" class="form-group t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_grid->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Actividad_No" class="t_actiividades_remuneradas_Actividad_No">
<span<?php echo $t_actiividades_remuneradas->Actividad_No->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Actividad_No->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Entidad->Visible) { // Entidad ?>
		<td data-name="Entidad"<?php echo $t_actiividades_remuneradas->Entidad->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Entidad" class="form-group t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Entidad" class="form-group t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Entidad" class="t_actiividades_remuneradas_Entidad">
<span<?php echo $t_actiividades_remuneradas->Entidad->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Entidad->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Sector->Visible) { // Sector ?>
		<td data-name="Sector"<?php echo $t_actiividades_remuneradas->Sector->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Sector" class="form-group t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector") ?>
</select>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Sector" class="form-group t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector") ?>
</select>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Sector" class="t_actiividades_remuneradas_Sector">
<span<?php echo $t_actiividades_remuneradas->Sector->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Remunerada->Visible) { // Remunerada ?>
		<td data-name="Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Remunerada" class="form-group t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada") ?>
</select>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Remunerada" class="form-group t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada") ?>
</select>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_grid->RowCnt ?>_t_actiividades_remuneradas_Remunerada" class="t_actiividades_remuneradas_Remunerada">
<span<?php echo $t_actiividades_remuneradas->Remunerada->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->ListViewValue() ?></span>
</span>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="ft_actiividades_remuneradasgrid$x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->FormValue) ?>">
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="ft_actiividades_remuneradasgrid$o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_actiividades_remuneradas_grid->ListOptions->Render("body", "right", $t_actiividades_remuneradas_grid->RowCnt);
?>
	</tr>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD || $t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_actiividades_remuneradasgrid.UpdateOpts(<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_actiividades_remuneradas->CurrentAction <> "gridadd" || $t_actiividades_remuneradas->CurrentMode == "copy")
		if (!$t_actiividades_remuneradas_grid->Recordset->EOF) $t_actiividades_remuneradas_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_actiividades_remuneradas->CurrentMode == "add" || $t_actiividades_remuneradas->CurrentMode == "copy" || $t_actiividades_remuneradas->CurrentMode == "edit") {
		$t_actiividades_remuneradas_grid->RowIndex = '$rowindex$';
		$t_actiividades_remuneradas_grid->LoadDefaultValues();

		// Set row properties
		$t_actiividades_remuneradas->ResetAttrs();
		$t_actiividades_remuneradas->RowAttrs = array_merge($t_actiividades_remuneradas->RowAttrs, array('data-rowindex'=>$t_actiividades_remuneradas_grid->RowIndex, 'id'=>'r0_t_actiividades_remuneradas', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_actiividades_remuneradas->RowAttrs["class"], "ewTemplate");
		$t_actiividades_remuneradas->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_actiividades_remuneradas_grid->RenderRow();

		// Render list options
		$t_actiividades_remuneradas_grid->RenderListOptions();
		$t_actiividades_remuneradas_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_actiividades_remuneradas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_actiividades_remuneradas_grid->ListOptions->Render("body", "left", $t_actiividades_remuneradas_grid->RowIndex);
?>
	<?php if ($t_actiividades_remuneradas->Id->Visible) { // Id ?>
		<td data-name="Id">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<?php if ($t_actiividades_remuneradas->Id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Id" class="form-group t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Id" class="form-group t_actiividades_remuneradas_Id">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Id->EditValue ?>"<?php echo $t_actiividades_remuneradas->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Id" class="form-group t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Tipo_Actividad->Visible) { // Tipo_Actividad ?>
		<td data-name="Tipo_Actividad">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Tipo_Actividad" class="form-group t_actiividades_remuneradas_Tipo_Actividad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Tipo_Actividad" class="form-group t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Actividad_Si->Visible) { // Actividad_Si ?>
		<td data-name="Actividad_Si">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Actividad_Si" class="form-group t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_grid->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Actividad_Si" class="form-group t_actiividades_remuneradas_Actividad_Si">
<span<?php echo $t_actiividades_remuneradas->Actividad_Si->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Actividad_Si->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_Si[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Actividad_No->Visible) { // Actividad_No ?>
		<td data-name="Actividad_No">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Actividad_No" class="form-group t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_grid->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Actividad_No" class="form-group t_actiividades_remuneradas_Actividad_No">
<span<?php echo $t_actiividades_remuneradas->Actividad_No->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Actividad_No->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Actividad_No[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Entidad->Visible) { // Entidad ?>
		<td data-name="Entidad">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Entidad" class="form-group t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Entidad" class="form-group t_actiividades_remuneradas_Entidad">
<span<?php echo $t_actiividades_remuneradas->Entidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Entidad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Sector->Visible) { // Sector ?>
		<td data-name="Sector">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Sector" class="form-group t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Sector" class="form-group t_actiividades_remuneradas_Sector">
<span<?php echo $t_actiividades_remuneradas->Sector->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Sector->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Remunerada->Visible) { // Remunerada ?>
		<td data-name="Remunerada">
<?php if ($t_actiividades_remuneradas->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Remunerada" class="form-group t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada") ?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_actiividades_remuneradas_Remunerada" class="form-group t_actiividades_remuneradas_Remunerada">
<span<?php echo $t_actiividades_remuneradas->Remunerada->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Remunerada->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="x<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" id="o<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_actiividades_remuneradas_grid->ListOptions->Render("body", "right", $t_actiividades_remuneradas_grid->RowCnt);
?>
<script type="text/javascript">
ft_actiividades_remuneradasgrid.UpdateOpts(<?php echo $t_actiividades_remuneradas_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_actiividades_remuneradas->CurrentMode == "add" || $t_actiividades_remuneradas->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_actiividades_remuneradas_grid->FormKeyCountName ?>" id="<?php echo $t_actiividades_remuneradas_grid->FormKeyCountName ?>" value="<?php echo $t_actiividades_remuneradas_grid->KeyCount ?>">
<?php echo $t_actiividades_remuneradas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_actiividades_remuneradas->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_actiividades_remuneradas_grid->FormKeyCountName ?>" id="<?php echo $t_actiividades_remuneradas_grid->FormKeyCountName ?>" value="<?php echo $t_actiividades_remuneradas_grid->KeyCount ?>">
<?php echo $t_actiividades_remuneradas_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_actiividades_remuneradas->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_actiividades_remuneradasgrid">
</div>
<?php

// Close recordset
if ($t_actiividades_remuneradas_grid->Recordset)
	$t_actiividades_remuneradas_grid->Recordset->Close();
?>
<?php if ($t_actiividades_remuneradas_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_actiividades_remuneradas_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas_grid->TotalRecs == 0 && $t_actiividades_remuneradas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_actiividades_remuneradas_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->Export == "") { ?>
<script type="text/javascript">
ft_actiividades_remuneradasgrid.Init();
</script>
<?php } ?>
<?php
$t_actiividades_remuneradas_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_actiividades_remuneradas_grid->Page_Terminate();
?>
