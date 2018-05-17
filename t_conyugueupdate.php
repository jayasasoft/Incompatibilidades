<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_conyugueinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_conyugue_update = NULL; // Initialize page object first

class ct_conyugue_update extends ct_conyugue {

	// Page ID
	var $PageID = 'update';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_conyugue';

	// Page object name
	var $PageObjName = 't_conyugue_update';

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

		// Table object (t_conyugue)
		if (!isset($GLOBALS["t_conyugue"]) || get_class($GLOBALS["t_conyugue"]) == "ct_conyugue") {
			$GLOBALS["t_conyugue"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_conyugue"];
		}

		// Table object (t_funcionario)
		if (!isset($GLOBALS['t_funcionario'])) $GLOBALS['t_funcionario'] = new ct_funcionario();

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'update', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_conyugue', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_conyuguelist.php"));
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
		$this->CI_RUN->SetVisibility();
		$this->Expedido->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Nombres->SetVisibility();
		$this->Direccion->SetVisibility();
		$this->Id->SetVisibility();

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
		global $EW_EXPORT, $t_conyugue;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_conyugue);
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
			$this->Page_Terminate("t_conyuguelist.php"); // No records selected, return to list
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
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
			$this->Disabled = " disabled";
		} else {
			$this->RowType = EW_ROWTYPE_EDIT; // Render edit
			$this->Disabled = "";
		}
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
					$this->CI_RUN->setDbValue($this->Recordset->fields('CI_RUN'));
					$this->Expedido->setDbValue($this->Recordset->fields('Expedido'));
					$this->Apellido_Paterno->setDbValue($this->Recordset->fields('Apellido_Paterno'));
					$this->Apellido_Materno->setDbValue($this->Recordset->fields('Apellido_Materno'));
					$this->Nombres->setDbValue($this->Recordset->fields('Nombres'));
					$this->Direccion->setDbValue($this->Recordset->fields('Direccion'));
					$this->Id->setDbValue($this->Recordset->fields('Id'));
				} else {
					if (!ew_CompareValue($this->CI_RUN->DbValue, $this->Recordset->fields('CI_RUN')))
						$this->CI_RUN->CurrentValue = NULL;
					if (!ew_CompareValue($this->Expedido->DbValue, $this->Recordset->fields('Expedido')))
						$this->Expedido->CurrentValue = NULL;
					if (!ew_CompareValue($this->Apellido_Paterno->DbValue, $this->Recordset->fields('Apellido_Paterno')))
						$this->Apellido_Paterno->CurrentValue = NULL;
					if (!ew_CompareValue($this->Apellido_Materno->DbValue, $this->Recordset->fields('Apellido_Materno')))
						$this->Apellido_Materno->CurrentValue = NULL;
					if (!ew_CompareValue($this->Nombres->DbValue, $this->Recordset->fields('Nombres')))
						$this->Nombres->CurrentValue = NULL;
					if (!ew_CompareValue($this->Direccion->DbValue, $this->Recordset->fields('Direccion')))
						$this->Direccion->CurrentValue = NULL;
					if (!ew_CompareValue($this->Id->DbValue, $this->Recordset->fields('Id')))
						$this->Id->CurrentValue = NULL;
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
		$this->CI_RUN->CurrentValue = $sKeyFld;
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
		if (!$this->CI_RUN->FldIsDetailKey) {
			$this->CI_RUN->setFormValue($objForm->GetValue("x_CI_RUN"));
		}
		$this->CI_RUN->MultiUpdate = $objForm->GetValue("u_CI_RUN");
		if (!$this->Expedido->FldIsDetailKey) {
			$this->Expedido->setFormValue($objForm->GetValue("x_Expedido"));
		}
		$this->Expedido->MultiUpdate = $objForm->GetValue("u_Expedido");
		if (!$this->Apellido_Paterno->FldIsDetailKey) {
			$this->Apellido_Paterno->setFormValue($objForm->GetValue("x_Apellido_Paterno"));
		}
		$this->Apellido_Paterno->MultiUpdate = $objForm->GetValue("u_Apellido_Paterno");
		if (!$this->Apellido_Materno->FldIsDetailKey) {
			$this->Apellido_Materno->setFormValue($objForm->GetValue("x_Apellido_Materno"));
		}
		$this->Apellido_Materno->MultiUpdate = $objForm->GetValue("u_Apellido_Materno");
		if (!$this->Nombres->FldIsDetailKey) {
			$this->Nombres->setFormValue($objForm->GetValue("x_Nombres"));
		}
		$this->Nombres->MultiUpdate = $objForm->GetValue("u_Nombres");
		if (!$this->Direccion->FldIsDetailKey) {
			$this->Direccion->setFormValue($objForm->GetValue("x_Direccion"));
		}
		$this->Direccion->MultiUpdate = $objForm->GetValue("u_Direccion");
		if (!$this->Id->FldIsDetailKey) {
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		}
		$this->Id->MultiUpdate = $objForm->GetValue("u_Id");
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->CI_RUN->CurrentValue = $this->CI_RUN->FormValue;
		$this->Expedido->CurrentValue = $this->Expedido->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Direccion->CurrentValue = $this->Direccion->FormValue;
		$this->Id->CurrentValue = $this->Id->FormValue;
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->CI_RUN->setDbValue($rs->fields('CI_RUN'));
		$this->Expedido->setDbValue($rs->fields('Expedido'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Direccion->setDbValue($rs->fields('Direccion'));
		$this->Id->setDbValue($rs->fields('Id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Expedido->DbValue = $row['Expedido'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Direccion->DbValue = $row['Direccion'];
		$this->Id->DbValue = $row['Id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// CI_RUN
		// Expedido
		// Apellido_Paterno
		// Apellido_Materno
		// Nombres
		// Direccion
		// Id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewCustomAttributes = "";

		// Expedido
		if (strval($this->Expedido->CurrentValue) <> "") {
			$this->Expedido->ViewValue = $this->Expedido->OptionCaption($this->Expedido->CurrentValue);
		} else {
			$this->Expedido->ViewValue = NULL;
		}
		$this->Expedido->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Direccion
		$this->Direccion->ViewValue = $this->Direccion->CurrentValue;
		$this->Direccion->ViewCustomAttributes = "";

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";
			$this->Expedido->TooltipValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";
			$this->Apellido_Paterno->TooltipValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";
			$this->Apellido_Materno->TooltipValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";
			$this->Nombres->TooltipValue = "";

			// Direccion
			$this->Direccion->LinkCustomAttributes = "";
			$this->Direccion->HrefValue = "";
			$this->Direccion->TooltipValue = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// CI_RUN
			$this->CI_RUN->EditAttrs["class"] = "form-control";
			$this->CI_RUN->EditCustomAttributes = "";
			$this->CI_RUN->EditValue = $this->CI_RUN->CurrentValue;
			$this->CI_RUN->ViewCustomAttributes = "";

			// Expedido
			$this->Expedido->EditAttrs["class"] = "form-control";
			$this->Expedido->EditCustomAttributes = "";
			$this->Expedido->EditValue = $this->Expedido->Options(TRUE);

			// Apellido_Paterno
			$this->Apellido_Paterno->EditAttrs["class"] = "form-control";
			$this->Apellido_Paterno->EditCustomAttributes = "";
			$this->Apellido_Paterno->EditValue = ew_HtmlEncode($this->Apellido_Paterno->CurrentValue);
			$this->Apellido_Paterno->PlaceHolder = ew_RemoveHtml($this->Apellido_Paterno->FldCaption());

			// Apellido_Materno
			$this->Apellido_Materno->EditAttrs["class"] = "form-control";
			$this->Apellido_Materno->EditCustomAttributes = "";
			$this->Apellido_Materno->EditValue = ew_HtmlEncode($this->Apellido_Materno->CurrentValue);
			$this->Apellido_Materno->PlaceHolder = ew_RemoveHtml($this->Apellido_Materno->FldCaption());

			// Nombres
			$this->Nombres->EditAttrs["class"] = "form-control";
			$this->Nombres->EditCustomAttributes = "";
			$this->Nombres->EditValue = ew_HtmlEncode($this->Nombres->CurrentValue);
			$this->Nombres->PlaceHolder = ew_RemoveHtml($this->Nombres->FldCaption());

			// Direccion
			$this->Direccion->EditAttrs["class"] = "form-control";
			$this->Direccion->EditCustomAttributes = "";
			$this->Direccion->EditValue = ew_HtmlEncode($this->Direccion->CurrentValue);
			$this->Direccion->PlaceHolder = ew_RemoveHtml($this->Direccion->FldCaption());

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// Edit refer script
			// CI_RUN

			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";

			// Direccion
			$this->Direccion->LinkCustomAttributes = "";
			$this->Direccion->HrefValue = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";
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
		if ($this->CI_RUN->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Expedido->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Apellido_Paterno->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Apellido_Materno->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Nombres->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Direccion->MultiUpdate == "1") $lUpdateCnt++;
		if ($this->Id->MultiUpdate == "1") $lUpdateCnt++;
		if ($lUpdateCnt == 0) {
			$gsFormError = $Language->Phrase("NoFieldSelected");
			return FALSE;
		}

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

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

			// CI_RUN
			// Expedido

			$this->Expedido->SetDbValueDef($rsnew, $this->Expedido->CurrentValue, "", $this->Expedido->ReadOnly || $this->Expedido->MultiUpdate <> "1");

			// Apellido_Paterno
			$this->Apellido_Paterno->SetDbValueDef($rsnew, $this->Apellido_Paterno->CurrentValue, "", $this->Apellido_Paterno->ReadOnly || $this->Apellido_Paterno->MultiUpdate <> "1");

			// Apellido_Materno
			$this->Apellido_Materno->SetDbValueDef($rsnew, $this->Apellido_Materno->CurrentValue, "", $this->Apellido_Materno->ReadOnly || $this->Apellido_Materno->MultiUpdate <> "1");

			// Nombres
			$this->Nombres->SetDbValueDef($rsnew, $this->Nombres->CurrentValue, "", $this->Nombres->ReadOnly || $this->Nombres->MultiUpdate <> "1");

			// Direccion
			$this->Direccion->SetDbValueDef($rsnew, $this->Direccion->CurrentValue, "", $this->Direccion->ReadOnly || $this->Direccion->MultiUpdate <> "1");

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_conyuguelist.php"), "", $this->TableVar, TRUE);
		$PageId = "update";
		$Breadcrumb->Add("update", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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

		//echo "AAAAAAAAAAAA";
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
if (!isset($t_conyugue_update)) $t_conyugue_update = new ct_conyugue_update();

// Page init
$t_conyugue_update->Page_Init();

// Page main
$t_conyugue_update->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_conyugue_update->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "update";
var CurrentForm = ft_conyugueupdate = new ew_Form("ft_conyugueupdate", "update");

// Validate form
ft_conyugueupdate.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_conyugueupdate.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_conyugueupdate.ValidateRequired = true;
<?php } else { ?>
ft_conyugueupdate.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_conyugueupdate.Lists["x_Expedido"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_conyugueupdate.Lists["x_Expedido"].Options = <?php echo json_encode($t_conyugue->Expedido->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags;
</script>
<?php if (!$t_conyugue_update->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_conyugue_update->ShowPageHeader(); ?>
<?php
$t_conyugue_update->ShowMessage();
?>
<form name="ft_conyugueupdate" id="ft_conyugueupdate" class="<?php echo $t_conyugue_update->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_conyugue_update->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_conyugue_update->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_conyugue">
<?php if ($t_conyugue->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_update" id="a_update" value="U">
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } else { ?>
<input type="hidden" name="a_update" id="a_update" value="F">
<?php } ?>
<?php if ($t_conyugue_update->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php foreach ($t_conyugue_update->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_t_conyugueupdate">
	<div class="checkbox">
		<label><input type="checkbox" name="u" id="u" onclick="ew_SelectAll(this);"<?php echo $t_conyugue_update->Disabled ?>> <?php echo $Language->Phrase("UpdateSelectAll") ?></label>
	</div>
<?php if ($t_conyugue->Expedido->Visible) { // Expedido ?>
	<div id="r_Expedido" class="form-group">
		<label for="x_Expedido" class="col-sm-2 control-label">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_Expedido" id="u_Expedido" value="1"<?php echo ($t_conyugue->Expedido->MultiUpdate == "1") ? " checked" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled<?php echo ($t_conyugue->Expedido->MultiUpdate == "1") ? " checked" : "" ?>>
<input type="hidden" name="u_Expedido" id="u_Expedido" value="<?php echo $t_conyugue->Expedido->MultiUpdate ?>">
<?php } ?>
 <?php echo $t_conyugue->Expedido->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_conyugue->Expedido->CellAttributes() ?>>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el_t_conyugue_Expedido">
<select data-table="t_conyugue" data-field="x_Expedido" data-value-separator="<?php echo $t_conyugue->Expedido->DisplayValueSeparatorAttribute() ?>" id="x_Expedido" name="x_Expedido"<?php echo $t_conyugue->Expedido->EditAttributes() ?>>
<?php echo $t_conyugue->Expedido->SelectOptionListHtml("x_Expedido") ?>
</select>
</span>
<?php } else { ?>
<span id="el_t_conyugue_Expedido">
<span<?php echo $t_conyugue->Expedido->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Expedido->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Expedido" name="x_Expedido" id="x_Expedido" value="<?php echo ew_HtmlEncode($t_conyugue->Expedido->FormValue) ?>">
<?php } ?>
<?php echo $t_conyugue->Expedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_conyugue->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label for="x_Apellido_Paterno" class="col-sm-2 control-label">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_Apellido_Paterno" id="u_Apellido_Paterno" value="1"<?php echo ($t_conyugue->Apellido_Paterno->MultiUpdate == "1") ? " checked" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled<?php echo ($t_conyugue->Apellido_Paterno->MultiUpdate == "1") ? " checked" : "" ?>>
<input type="hidden" name="u_Apellido_Paterno" id="u_Apellido_Paterno" value="<?php echo $t_conyugue->Apellido_Paterno->MultiUpdate ?>">
<?php } ?>
 <?php echo $t_conyugue->Apellido_Paterno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_conyugue->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el_t_conyugue_Apellido_Paterno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Paterno->EditValue ?>"<?php echo $t_conyugue->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_conyugue_Apellido_Paterno">
<span<?php echo $t_conyugue->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Paterno" name="x_Apellido_Paterno" id="x_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<?php echo $t_conyugue->Apellido_Paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_conyugue->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label for="x_Apellido_Materno" class="col-sm-2 control-label">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_Apellido_Materno" id="u_Apellido_Materno" value="1"<?php echo ($t_conyugue->Apellido_Materno->MultiUpdate == "1") ? " checked" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled<?php echo ($t_conyugue->Apellido_Materno->MultiUpdate == "1") ? " checked" : "" ?>>
<input type="hidden" name="u_Apellido_Materno" id="u_Apellido_Materno" value="<?php echo $t_conyugue->Apellido_Materno->MultiUpdate ?>">
<?php } ?>
 <?php echo $t_conyugue->Apellido_Materno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_conyugue->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el_t_conyugue_Apellido_Materno">
<input type="text" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Apellido_Materno->EditValue ?>"<?php echo $t_conyugue->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_conyugue_Apellido_Materno">
<span<?php echo $t_conyugue->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Apellido_Materno" name="x_Apellido_Materno" id="x_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_conyugue->Apellido_Materno->FormValue) ?>">
<?php } ?>
<?php echo $t_conyugue->Apellido_Materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_conyugue->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label for="x_Nombres" class="col-sm-2 control-label">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_Nombres" id="u_Nombres" value="1"<?php echo ($t_conyugue->Nombres->MultiUpdate == "1") ? " checked" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled<?php echo ($t_conyugue->Nombres->MultiUpdate == "1") ? " checked" : "" ?>>
<input type="hidden" name="u_Nombres" id="u_Nombres" value="<?php echo $t_conyugue->Nombres->MultiUpdate ?>">
<?php } ?>
 <?php echo $t_conyugue->Nombres->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_conyugue->Nombres->CellAttributes() ?>>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el_t_conyugue_Nombres">
<input type="text" data-table="t_conyugue" data-field="x_Nombres" name="x_Nombres" id="x_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Nombres->EditValue ?>"<?php echo $t_conyugue->Nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_conyugue_Nombres">
<span<?php echo $t_conyugue->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Nombres" name="x_Nombres" id="x_Nombres" value="<?php echo ew_HtmlEncode($t_conyugue->Nombres->FormValue) ?>">
<?php } ?>
<?php echo $t_conyugue->Nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_conyugue->Direccion->Visible) { // Direccion ?>
	<div id="r_Direccion" class="form-group">
		<label for="x_Direccion" class="col-sm-2 control-label">
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<input type="checkbox" name="u_Direccion" id="u_Direccion" value="1"<?php echo ($t_conyugue->Direccion->MultiUpdate == "1") ? " checked" : "" ?>>
<?php } else { ?>
<input type="checkbox" disabled<?php echo ($t_conyugue->Direccion->MultiUpdate == "1") ? " checked" : "" ?>>
<input type="hidden" name="u_Direccion" id="u_Direccion" value="<?php echo $t_conyugue->Direccion->MultiUpdate ?>">
<?php } ?>
 <?php echo $t_conyugue->Direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_conyugue->Direccion->CellAttributes() ?>>
<?php if ($t_conyugue->CurrentAction <> "F") { ?>
<span id="el_t_conyugue_Direccion">
<input type="text" data-table="t_conyugue" data-field="x_Direccion" name="x_Direccion" id="x_Direccion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_conyugue->Direccion->getPlaceHolder()) ?>" value="<?php echo $t_conyugue->Direccion->EditValue ?>"<?php echo $t_conyugue->Direccion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_conyugue_Direccion">
<span<?php echo $t_conyugue->Direccion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_conyugue->Direccion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_conyugue" data-field="x_Direccion" name="x_Direccion" id="x_Direccion" value="<?php echo ew_HtmlEncode($t_conyugue->Direccion->FormValue) ?>">
<?php } ?>
<?php echo $t_conyugue->Direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if (!$t_conyugue_update->IsModal) { ?>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
<?php if ($t_conyugue->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_update.value='F';"><?php echo $Language->Phrase("UpdateBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_conyugue_update->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_update.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
		</div>
	</div>
<?php } ?>
</div>
</form>
<script type="text/javascript">
ft_conyugueupdate.Init();
</script>
<?php
$t_conyugue_update->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_conyugue_update->Page_Terminate();
?>
