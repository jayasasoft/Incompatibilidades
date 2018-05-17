<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_unidad_organizacionalinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "t_unidadgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_unidad_organizacional_update = NULL; // Initialize page object first

class ct_unidad_organizacional_update extends ct_unidad_organizacional {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_unidad_organizacional';

	// Page object name
	var $PageObjName = 't_unidad_organizacional_update';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_unidad_organizacional)
		if (!isset($GLOBALS["t_unidad_organizacional"]) || get_class($GLOBALS["t_unidad_organizacional"]) == "ct_unidad_organizacional") {
			$GLOBALS["t_unidad_organizacional"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_unidad_organizacional"];
		}

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'update', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_unidad_organizacional', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_usuario)
		if (!isset($UserTable)) {
			$UserTable = new ct_usuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_unidad_organizacionallist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Fiscalia->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 't_unidad'
			if (@$_POST["grid"] == "ft_unidadgrid") {
				if (!isset($GLOBALS["t_unidad_grid"])) $GLOBALS["t_unidad_grid"] = new ct_unidad_grid;
				$GLOBALS["t_unidad_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_unidad_organizacional;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_unidad_organizacional);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewUpdateForm";
	var $IsModal = FALSE;
	var $RecKeys;
	var $Disabled;
	var $Recordset;
	var $UpdateCount = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Try to load keys from list form
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		if (@$_POST["a_update"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_update"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->LoadMultiUpdateValues(); // Load initial values to form
		}
		if (count($this->RecKeys) <= 0)
			$this->Page_Terminate("t_unidad_organizacionallist.php"); // No records selected, return to list
		switch ($this->CurrentAction) {
			case "U": // Update
				if ($this->UpdateRows()) { // Update Records based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				} else {
					$this->RestoreFormValues(); // Restore form values
				}
		}

		// Render row
		$this->RowType = EW_ROWTYPE_EDIT; // Render edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Load initial values to form if field values are identical in all selected records
	function LoadMultiUpdateValues() {
		$this->CurrentFilter = $this->GetKeyFilter();

		// Load recordset
		if ($this->Recordset = $this->LoadRecordset()) {
			$i = 1;
			while (!$this->Recordset->EOF) {
				if ($i == 1) {
					$this->Fiscalia->setDbValue($this->Recordset->fields('Fiscalia'));
					$this->Unidad_Organizacional->setDbValue($this->Recordset->fields('Unidad_Organizacional'));
				} else {
					if (!ew_CompareValue($this->Fiscalia->DbValue, $this->Recordset->fields('Fiscalia')))
						$this->Fiscalia->CurrentValue = NULL;
					if (!ew_CompareValue($this->Unidad_Organizacional->DbValue, $this->Recordset->fields('Unidad_Organizacional')))
						$this->Unidad_Organizacional->CurrentValue = NULL;
				}
				$i++;
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
		}
	}

	// Set up key value
	function SetupKeyValues($key) {
		$sKeyFld = $key;
		if (!is_numeric($sKeyFld))
			return FALSE;
		$this->Id_Unidad_Org->CurrentValue = $sKeyFld;
		return TRUE;
	}

	// Update all selected rows
	function UpdateRows() {
		global $Language;
		$conn = &$this->Connection();
		$conn->BeginTrans();

		// Get old recordset
		$this->CurrentFilter = $this->GetKeyFilter();
		$sSql = $this->SQL();
		$rsold = $conn->Execute($sSql);

		// Update all rows
		$sKey = "";
		foreach ($this->RecKeys as $key) {
			if ($this->SetupKeyValues($key)) {
				$sThisKey = $key;
				$this->SendEmail = FALSE; // Do not send email on update success
				$this->UpdateCount += 1; // Update record count for records being updated
				$UpdateRows = $this->EditRow(); // Update this row
			} else {
				$UpdateRows = FALSE;
			}
			if (!$UpdateRows)
				break; // Update failed
			if ($sKey <> "") $sKey .= ", ";
			$sKey .= $sThisKey;
		}

		// Check if all rows updated
		if ($UpdateRows) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$rsnew = $conn->Execute($sSql);
		} else {
			$conn->RollbackTrans(); // Rollback transaction
		}
		return $UpdateRows;
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Fiscalia->FldIsDetailKey) {
			$this->Fiscalia->setFormValue($objForm->GetValue("x_Fiscalia"));
		}
		$this->Fiscalia->MultiUpdate = $objForm->GetValue("u_Fiscalia");
		if (!$this->Unidad_Organizacional->FldIsDetailKey) {
			$this->Unidad_Organizacional->setFormValue($objForm->GetValue("x_Unidad_Organizacional"));
		}
		$this->Unidad_Organizacional->MultiUpdate = $objForm->GetValue("u_Unidad_Organizacional");
		if (!$this->Id_Unidad_Org->FldIsDetailKey)
			$this->Id_Unidad_Org->setFormValue($objForm->GetValue("x_Id_Unidad_Org"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Id_Unidad_Org->CurrentValue = $this->Id_Unidad_Org->FormValue;
		$this->Fiscalia->CurrentValue = $this->Fiscalia->FormValue;
		$this->Unidad_Organizacional->CurrentValue = $this->Unidad_Organizacional->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Id_Unidad_Org->setDbValue($rs->fields('Id_Unidad_Org'));
		$this->Fiscalia->setDbValue($rs->fields('Fiscalia'));
		if (array_key_exists('EV__Fiscalia', $rs->fields)) {
			$this->Fiscalia->VirtualValue = $rs->fields('EV__Fiscalia'); // Set up virtual field value
		} else {
			$this->Fiscalia->VirtualValue = ""; // Clear value
		}
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_Unidad_Org->DbValue = $row['Id_Unidad_Org'];
		$this->Fiscalia->DbValue = $row['Fiscalia'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id_Unidad_Org
		// Fiscalia
		// Unidad_Organizacional

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Fiscalia
		if ($this->Fiscalia->VirtualValue <> "") {
			$this->Fiscalia->ViewValue = $this->Fiscalia->VirtualValue;
		} else {
		if (strval($this->Fiscalia->CurrentValue) <> "") {
			$sFilterWrk = "`denominacion`" . ew_SearchString("=", $this->Fiscalia->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `denominacion`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_fiscalias`";
		$sWhereWrk = "";
		$this->Fiscalia->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Fiscalia, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Fiscalia->ViewValue = $this->Fiscalia->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Fiscalia->ViewValue = $this->Fiscalia->CurrentValue;
			}
		} else {
			$this->Fiscalia->ViewValue = NULL;
		}
		}
		$this->Fiscalia->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

			// Fiscalia
			$this->Fiscalia->LinkCustomAttributes = "";
			$this->Fiscalia->HrefValue = "";
			$this->Fiscalia->TooltipValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Fiscalia
			$this->Fiscalia->EditAttrs["class"] = "form-control";
			$this->Fiscalia->EditCustomAttributes = "";
			if (trim(strval($this->Fiscalia->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`denominacion`" . ew_SearchString("=", $this->Fiscalia->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `denominacion`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_fiscalias`";
			$sWhereWrk = "";
			$this->Fiscalia->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Fiscalia, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Fiscalia->EditValue = $arwrk;

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			$this->Unidad_Organizacional->EditValue = ew_HtmlEncode($this->Unidad_Organizacional->CurrentValue);
			$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

			// Edit refer script
			// Fiscalia

			$this->Fiscalia->LinkCustomAttributes = "";
			$this->Fiscalia->HrefValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";
		$lUpdateCnt = 0;
		if ($this->Fiscalia->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Unidad_Organizacional->MultiUpdate == "1") $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->Fiscalia->MultiUpdate <> "" && !$this->Fiscalia->FldIsDetailKey && !is_null($this->Fiscalia->FormValue) && $this->Fiscalia->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Fiscalia->FldCaption(), $this->Fiscalia->ReqErrMsg));
		}
		if ($this->Unidad_Organizacional->MultiUpdate <> "" && !$this->Unidad_Organizacional->FldIsDetailKey && !is_null($this->Unidad_Organizacional->FormValue) && $this->Unidad_Organizacional->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Unidad_Organizacional->FldCaption(), $this->Unidad_Organizacional->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Fiscalia
			$this->Fiscalia->SetDbValueDef($rsnew, $this->Fiscalia->CurrentValue, "", $this->Fiscalia->ReadOnly || $this->Fiscalia->MultiUpdate <> "1");

			// Unidad_Organizacional
			$this->Unidad_Organizacional->SetDbValueDef($rsnew, $this->Unidad_Organizacional->CurrentValue, "", $this->Unidad_Organizacional->ReadOnly || $this->Unidad_Organizacional->MultiUpdate <> "1");

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_unidad_organizacionallist.php"), "", $this->TableVar, TRUE);
		$PageId = "update";
		$Breadcrumb->Add("update", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Fiscalia":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `denominacion` AS `LinkFld`, `denominacion` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_fiscalias`";
			$sWhereWrk = "";
			$this->Fiscalia->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`denominacion` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Fiscalia, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($t_unidad_organizacional_update)) $t_unidad_organizacional_update = new ct_unidad_organizacional_update();

// Page init
$t_unidad_organizacional_update->Page_Init();

// Page main
$t_unidad_organizacional_update->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_unidad_organizacional_update->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "update";
var CurrentForm = ft_unidad_organizacionalupdate = new ew_Form("ft_unidad_organizacionalupdate", "update");

// Validate form
ft_unidad_organizacionalupdate.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	if (!ew_UpdateSelected(fobj)) {
		ew_Alert(ewLanguage.Phrase("NoFieldSelected"));
		return false;
	}
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_Fiscalia");
			uelm = this.GetElements("u" + infix + "_Fiscalia");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_unidad_organizacional->Fiscalia->FldCaption(), $t_unidad_organizacional->Fiscalia->ReqErrMsg)) ?>");
			}
			elm = this.GetElements("x" + infix + "_Unidad_Organizacional");
			uelm = this.GetElements("u" + infix + "_Unidad_Organizacional");
			if (uelm && uelm.checked) {
				if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
					return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_unidad_organizacional->Unidad_Organizacional->FldCaption(), $t_unidad_organizacional->Unidad_Organizacional->ReqErrMsg)) ?>");
			}

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_unidad_organizacionalupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_unidad_organizacionalupdate.ValidateRequired = true;
<?php } else { ?>
ft_unidad_organizacionalupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_unidad_organizacionalupdate.Lists["x_Fiscalia"] = {"LinkField":"x_denominacion","Ajax":true,"AutoFill":false,"DisplayFields":["x_denominacion","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_fiscalias"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_unidad_organizacional_update->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_unidad_organizacional_update->ShowPageHeader(); ?>
<?php
$t_unidad_organizacional_update->ShowMessage();
?>
<form name="ft_unidad_organizacionalupdate" id="ft_unidad_organizacionalupdate" class="<?php echo $t_unidad_organizacional_update->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_unidad_organizacional_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_unidad_organizacional_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_unidad_organizacional">
<input type="hidden" name="a_update" id="a_update" value="U">
<?php if ($t_unidad_organizacional_update->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php foreach ($t_unidad_organizacional_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_t_unidad_organizacionalupdate">
	<div class="checkbox">
		<label><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"> <?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($t_unidad_organizacional->Fiscalia->Visible) { // Fiscalia ?>
	<div id="r_Fiscalia" class="form-group">
		<label for="x_Fiscalia" class="col-sm-2 control-label">
<input type="checkbox" name="u_Fiscalia" id="u_Fiscalia" value="1"<?php echo ($t_unidad_organizacional->Fiscalia->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $t_unidad_organizacional->Fiscalia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_unidad_organizacional->Fiscalia->CellAttributes() ?>>
<span id="el_t_unidad_organizacional_Fiscalia">
<select data-table="t_unidad_organizacional" data-field="x_Fiscalia" data-value-separator="<?php echo $t_unidad_organizacional->Fiscalia->DisplayValueSeparatorAttribute() ?>" id="x_Fiscalia" name="x_Fiscalia"<?php echo $t_unidad_organizacional->Fiscalia->EditAttributes() ?>>
<?php echo $t_unidad_organizacional->Fiscalia->SelectOptionListHtml("x_Fiscalia") ?>
</select>
<input type="hidden" name="s_x_Fiscalia" id="s_x_Fiscalia" value="<?php echo $t_unidad_organizacional->Fiscalia->LookupFilterQuery() ?>">
</span>
<?php echo $t_unidad_organizacional->Fiscalia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_unidad_organizacional->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label for="x_Unidad_Organizacional" class="col-sm-2 control-label">
<input type="checkbox" name="u_Unidad_Organizacional" id="u_Unidad_Organizacional" value="1"<?php echo ($t_unidad_organizacional->Unidad_Organizacional->MultiUpdate == "1") ? " checked" : "" ?>>
 <?php echo $t_unidad_organizacional->Unidad_Organizacional->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_unidad_organizacional->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_unidad_organizacional_Unidad_Organizacional">
<input type="text" data-table="t_unidad_organizacional" data-field="x_Unidad_Organizacional" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_unidad_organizacional->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $t_unidad_organizacional->Unidad_Organizacional->EditValue ?>"<?php echo $t_unidad_organizacional->Unidad_Organizacional->EditAttributes() ?>>
</span>
<?php echo $t_unidad_organizacional->Unidad_Organizacional->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if (!$t_unidad_organizacional_update->IsModal) { ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("UpdateBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_unidad_organizacional_update->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
		</div>
	</div>
<?php } ?>
</div>
</form>
<script type="text/javascript">
ft_unidad_organizacionalupdate.Init();
</script>
<?php
$t_unidad_organizacional_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_unidad_organizacional_update->Page_Terminate();
?>
