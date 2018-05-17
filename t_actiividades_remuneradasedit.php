<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_actiividades_remuneradasinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_actiividades_remuneradas_edit = NULL; // Initialize page object first

class ct_actiividades_remuneradas_edit extends ct_actiividades_remuneradas {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_actiividades_remuneradas';

	// Page object name
	var $PageObjName = 't_actiividades_remuneradas_edit';

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

		// Table object (t_actiividades_remuneradas)
		if (!isset($GLOBALS["t_actiividades_remuneradas"]) || get_class($GLOBALS["t_actiividades_remuneradas"]) == "ct_actiividades_remuneradas") {
			$GLOBALS["t_actiividades_remuneradas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_actiividades_remuneradas"];
		}

		// Table object (t_funcionario)
		if (!isset($GLOBALS['t_funcionario'])) $GLOBALS['t_funcionario'] = new ct_funcionario();

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_actiividades_remuneradas', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_actiividades_remuneradaslist.php"));
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
		$this->Tipo_Actividad->SetVisibility();
		$this->Actividad_Si->SetVisibility();
		$this->Actividad_No->SetVisibility();
		$this->Entidad->SetVisibility();
		$this->Sector->SetVisibility();
		$this->Remunerada->SetVisibility();

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
		global $EW_EXPORT, $t_actiividades_remuneradas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_actiividades_remuneradas);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

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

		// Load key from QueryString
		if (@$_GET["Id"] <> "") {
			$this->Id->setQueryStringValue($_GET["Id"]);
		}
		if (@$_GET["Tipo_Actividad"] <> "") {
			$this->Tipo_Actividad->setQueryStringValue($_GET["Tipo_Actividad"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->Id->CurrentValue == "") {
			$this->Page_Terminate("t_actiividades_remuneradaslist.php"); // Invalid key, return to list
		}
		if ($this->Tipo_Actividad->CurrentValue == "") {
			$this->Page_Terminate("t_actiividades_remuneradaslist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_actiividades_remuneradaslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_actiividades_remuneradaslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		if (!$this->Tipo_Actividad->FldIsDetailKey) {
			$this->Tipo_Actividad->setFormValue($objForm->GetValue("x_Tipo_Actividad"));
		}
		if (!$this->Actividad_Si->FldIsDetailKey) {
			$this->Actividad_Si->setFormValue($objForm->GetValue("x_Actividad_Si"));
		}
		if (!$this->Actividad_No->FldIsDetailKey) {
			$this->Actividad_No->setFormValue($objForm->GetValue("x_Actividad_No"));
		}
		if (!$this->Entidad->FldIsDetailKey) {
			$this->Entidad->setFormValue($objForm->GetValue("x_Entidad"));
		}
		if (!$this->Sector->FldIsDetailKey) {
			$this->Sector->setFormValue($objForm->GetValue("x_Sector"));
		}
		if (!$this->Remunerada->FldIsDetailKey) {
			$this->Remunerada->setFormValue($objForm->GetValue("x_Remunerada"));
		}
		if (!$this->Id->FldIsDetailKey)
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->Tipo_Actividad->CurrentValue = $this->Tipo_Actividad->FormValue;
		$this->Actividad_Si->CurrentValue = $this->Actividad_Si->FormValue;
		$this->Actividad_No->CurrentValue = $this->Actividad_No->FormValue;
		$this->Entidad->CurrentValue = $this->Entidad->FormValue;
		$this->Sector->CurrentValue = $this->Sector->FormValue;
		$this->Remunerada->CurrentValue = $this->Remunerada->FormValue;
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->Tipo_Actividad->setDbValue($rs->fields('Tipo_Actividad'));
		$this->Actividad_Si->setDbValue($rs->fields('Actividad_Si'));
		$this->Actividad_No->setDbValue($rs->fields('Actividad_No'));
		$this->Entidad->setDbValue($rs->fields('Entidad'));
		$this->Sector->setDbValue($rs->fields('Sector'));
		$this->Remunerada->setDbValue($rs->fields('Remunerada'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->Tipo_Actividad->DbValue = $row['Tipo_Actividad'];
		$this->Actividad_Si->DbValue = $row['Actividad_Si'];
		$this->Actividad_No->DbValue = $row['Actividad_No'];
		$this->Entidad->DbValue = $row['Entidad'];
		$this->Sector->DbValue = $row['Sector'];
		$this->Remunerada->DbValue = $row['Remunerada'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// Tipo_Actividad
		// Actividad_Si
		// Actividad_No
		// Entidad
		// Sector
		// Remunerada

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Tipo_Actividad
		$this->Tipo_Actividad->ViewValue = $this->Tipo_Actividad->CurrentValue;
		$this->Tipo_Actividad->ViewCustomAttributes = "";

		// Actividad_Si
		if (strval($this->Actividad_Si->CurrentValue) <> "") {
			$this->Actividad_Si->ViewValue = "";
			$arwrk = explode(",", strval($this->Actividad_Si->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->Actividad_Si->ViewValue .= $this->Actividad_Si->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->Actividad_Si->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->Actividad_Si->ViewValue = NULL;
		}
		$this->Actividad_Si->ViewCustomAttributes = "";

		// Actividad_No
		if (strval($this->Actividad_No->CurrentValue) <> "") {
			$this->Actividad_No->ViewValue = "";
			$arwrk = explode(",", strval($this->Actividad_No->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->Actividad_No->ViewValue .= $this->Actividad_No->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->Actividad_No->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->Actividad_No->ViewValue = NULL;
		}
		$this->Actividad_No->ViewCustomAttributes = "";

		// Entidad
		$this->Entidad->ViewValue = $this->Entidad->CurrentValue;
		$this->Entidad->ViewCustomAttributes = "";

		// Sector
		if (strval($this->Sector->CurrentValue) <> "") {
			$this->Sector->ViewValue = $this->Sector->OptionCaption($this->Sector->CurrentValue);
		} else {
			$this->Sector->ViewValue = NULL;
		}
		$this->Sector->ViewCustomAttributes = "";

		// Remunerada
		if (strval($this->Remunerada->CurrentValue) <> "") {
			$this->Remunerada->ViewValue = $this->Remunerada->OptionCaption($this->Remunerada->CurrentValue);
		} else {
			$this->Remunerada->ViewValue = NULL;
		}
		$this->Remunerada->ViewCustomAttributes = "";

			// Tipo_Actividad
			$this->Tipo_Actividad->LinkCustomAttributes = "";
			$this->Tipo_Actividad->HrefValue = "";
			$this->Tipo_Actividad->TooltipValue = "";

			// Actividad_Si
			$this->Actividad_Si->LinkCustomAttributes = "";
			$this->Actividad_Si->HrefValue = "";
			$this->Actividad_Si->TooltipValue = "";

			// Actividad_No
			$this->Actividad_No->LinkCustomAttributes = "";
			$this->Actividad_No->HrefValue = "";
			$this->Actividad_No->TooltipValue = "";

			// Entidad
			$this->Entidad->LinkCustomAttributes = "";
			$this->Entidad->HrefValue = "";
			$this->Entidad->TooltipValue = "";

			// Sector
			$this->Sector->LinkCustomAttributes = "";
			$this->Sector->HrefValue = "";
			$this->Sector->TooltipValue = "";

			// Remunerada
			$this->Remunerada->LinkCustomAttributes = "";
			$this->Remunerada->HrefValue = "";
			$this->Remunerada->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Tipo_Actividad
			$this->Tipo_Actividad->EditAttrs["class"] = "form-control";
			$this->Tipo_Actividad->EditCustomAttributes = "";
			$this->Tipo_Actividad->EditValue = $this->Tipo_Actividad->CurrentValue;
			$this->Tipo_Actividad->ViewCustomAttributes = "";

			// Actividad_Si
			$this->Actividad_Si->EditCustomAttributes = "";
			$this->Actividad_Si->EditValue = $this->Actividad_Si->Options(FALSE);

			// Actividad_No
			$this->Actividad_No->EditCustomAttributes = "";
			$this->Actividad_No->EditValue = $this->Actividad_No->Options(FALSE);

			// Entidad
			$this->Entidad->EditAttrs["class"] = "form-control";
			$this->Entidad->EditCustomAttributes = "";
			$this->Entidad->EditValue = ew_HtmlEncode($this->Entidad->CurrentValue);
			$this->Entidad->PlaceHolder = ew_RemoveHtml($this->Entidad->FldCaption());

			// Sector
			$this->Sector->EditAttrs["class"] = "form-control";
			$this->Sector->EditCustomAttributes = "";
			$this->Sector->EditValue = $this->Sector->Options(TRUE);

			// Remunerada
			$this->Remunerada->EditAttrs["class"] = "form-control";
			$this->Remunerada->EditCustomAttributes = "";
			$this->Remunerada->EditValue = $this->Remunerada->Options(TRUE);

			// Edit refer script
			// Tipo_Actividad

			$this->Tipo_Actividad->LinkCustomAttributes = "";
			$this->Tipo_Actividad->HrefValue = "";

			// Actividad_Si
			$this->Actividad_Si->LinkCustomAttributes = "";
			$this->Actividad_Si->HrefValue = "";

			// Actividad_No
			$this->Actividad_No->LinkCustomAttributes = "";
			$this->Actividad_No->HrefValue = "";

			// Entidad
			$this->Entidad->LinkCustomAttributes = "";
			$this->Entidad->HrefValue = "";

			// Sector
			$this->Sector->LinkCustomAttributes = "";
			$this->Sector->HrefValue = "";

			// Remunerada
			$this->Remunerada->LinkCustomAttributes = "";
			$this->Remunerada->HrefValue = "";
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

			// Tipo_Actividad
			// Actividad_Si

			$this->Actividad_Si->SetDbValueDef($rsnew, $this->Actividad_Si->CurrentValue, NULL, $this->Actividad_Si->ReadOnly);

			// Actividad_No
			$this->Actividad_No->SetDbValueDef($rsnew, $this->Actividad_No->CurrentValue, NULL, $this->Actividad_No->ReadOnly);

			// Entidad
			$this->Entidad->SetDbValueDef($rsnew, $this->Entidad->CurrentValue, NULL, $this->Entidad->ReadOnly);

			// Sector
			$this->Sector->SetDbValueDef($rsnew, $this->Sector->CurrentValue, NULL, $this->Sector->ReadOnly);

			// Remunerada
			$this->Remunerada->SetDbValueDef($rsnew, $this->Remunerada->CurrentValue, NULL, $this->Remunerada->ReadOnly);

			// Check referential integrity for master table 't_funcionario'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_t_funcionario();
			$KeyValue = isset($rsnew['Id']) ? $rsnew['Id'] : $rsold['Id'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["t_funcionario"])) $GLOBALS["t_funcionario"] = new ct_funcionario();
				$rsmaster = $GLOBALS["t_funcionario"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "t_funcionario", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_funcionario") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id"] <> "") {
					$GLOBALS["t_funcionario"]->Id->setQueryStringValue($_GET["fk_Id"]);
					$this->Id->setQueryStringValue($GLOBALS["t_funcionario"]->Id->QueryStringValue);
					$this->Id->setSessionValue($this->Id->QueryStringValue);
					if (!is_numeric($GLOBALS["t_funcionario"]->Id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_funcionario") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id"] <> "") {
					$GLOBALS["t_funcionario"]->Id->setFormValue($_POST["fk_Id"]);
					$this->Id->setFormValue($GLOBALS["t_funcionario"]->Id->FormValue);
					$this->Id->setSessionValue($this->Id->FormValue);
					if (!is_numeric($GLOBALS["t_funcionario"]->Id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "t_funcionario") {
				if ($this->Id->CurrentValue == "") $this->Id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_actiividades_remuneradaslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($t_actiividades_remuneradas_edit)) $t_actiividades_remuneradas_edit = new ct_actiividades_remuneradas_edit();

// Page init
$t_actiividades_remuneradas_edit->Page_Init();

// Page main
$t_actiividades_remuneradas_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_actiividades_remuneradas_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_actiividades_remuneradasedit = new ew_Form("ft_actiividades_remuneradasedit", "edit");

// Validate form
ft_actiividades_remuneradasedit.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_actiividades_remuneradasedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_actiividades_remuneradasedit.ValidateRequired = true;
<?php } else { ?>
ft_actiividades_remuneradasedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_actiividades_remuneradasedit.Lists["x_Actividad_Si[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasedit.Lists["x_Actividad_Si[]"].Options = <?php echo json_encode($t_actiividades_remuneradas->Actividad_Si->Options()) ?>;
ft_actiividades_remuneradasedit.Lists["x_Actividad_No[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasedit.Lists["x_Actividad_No[]"].Options = <?php echo json_encode($t_actiividades_remuneradas->Actividad_No->Options()) ?>;
ft_actiividades_remuneradasedit.Lists["x_Sector"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasedit.Lists["x_Sector"].Options = <?php echo json_encode($t_actiividades_remuneradas->Sector->Options()) ?>;
ft_actiividades_remuneradasedit.Lists["x_Remunerada"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradasedit.Lists["x_Remunerada"].Options = <?php echo json_encode($t_actiividades_remuneradas->Remunerada->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_actiividades_remuneradas_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_actiividades_remuneradas_edit->ShowPageHeader(); ?>
<?php
$t_actiividades_remuneradas_edit->ShowMessage();
?>
<form name="ft_actiividades_remuneradasedit" id="ft_actiividades_remuneradasedit" class="<?php echo $t_actiividades_remuneradas_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_actiividades_remuneradas_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_actiividades_remuneradas_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_actiividades_remuneradas">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_actiividades_remuneradas_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_actiividades_remuneradas->getCurrentMasterTable() == "t_funcionario") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_funcionario">
<input type="hidden" name="fk_Id" value="<?php echo $t_actiividades_remuneradas->Id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_actiividades_remuneradas->Tipo_Actividad->Visible) { // Tipo_Actividad ?>
	<div id="r_Tipo_Actividad" class="form-group">
		<label id="elh_t_actiividades_remuneradas_Tipo_Actividad" for="x_Tipo_Actividad" class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Tipo_Actividad->CellAttributes() ?>>
<span id="el_t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" data-page="1" name="x_Tipo_Actividad" id="x_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->CurrentValue) ?>">
<?php echo $t_actiividades_remuneradas->Tipo_Actividad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->Actividad_Si->Visible) { // Actividad_Si ?>
	<div id="r_Actividad_Si" class="form-group">
		<label id="elh_t_actiividades_remuneradas_Actividad_Si" class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Actividad_Si->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Actividad_Si->CellAttributes() ?>>
<span id="el_t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-page="1" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x_Actividad_Si[]" id="x_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x_Actividad_Si[]", 1) ?>
</div></div>
</span>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->Actividad_No->Visible) { // Actividad_No ?>
	<div id="r_Actividad_No" class="form-group">
		<label id="elh_t_actiividades_remuneradas_Actividad_No" class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Actividad_No->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Actividad_No->CellAttributes() ?>>
<span id="el_t_actiividades_remuneradas_Actividad_No">
<div id="tp_x_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-page="1" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x_Actividad_No[]" id="x_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x_Actividad_No[]", 1) ?>
</div></div>
</span>
<?php echo $t_actiividades_remuneradas->Actividad_No->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->Entidad->Visible) { // Entidad ?>
	<div id="r_Entidad" class="form-group">
		<label id="elh_t_actiividades_remuneradas_Entidad" for="x_Entidad" class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Entidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Entidad->CellAttributes() ?>>
<span id="el_t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" data-page="1" name="x_Entidad" id="x_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<?php echo $t_actiividades_remuneradas->Entidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->Sector->Visible) { // Sector ?>
	<div id="r_Sector" class="form-group">
		<label id="elh_t_actiividades_remuneradas_Sector" for="x_Sector" class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Sector->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Sector->CellAttributes() ?>>
<span id="el_t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-page="1" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x_Sector" name="x_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x_Sector") ?>
</select>
</span>
<?php echo $t_actiividades_remuneradas->Sector->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->Remunerada->Visible) { // Remunerada ?>
	<div id="r_Remunerada" class="form-group">
		<label id="elh_t_actiividades_remuneradas_Remunerada" for="x_Remunerada" class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Remunerada->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Remunerada->CellAttributes() ?>>
<span id="el_t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-page="1" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x_Remunerada" name="x_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x_Remunerada") ?>
</select>
</span>
<?php echo $t_actiividades_remuneradas->Remunerada->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php if (!$t_actiividades_remuneradas_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_actiividades_remuneradas_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_actiividades_remuneradasedit.Init();
</script>
<?php
$t_actiividades_remuneradas_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_actiividades_remuneradas_edit->Page_Terminate();
?>
