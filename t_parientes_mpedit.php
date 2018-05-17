<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_parientes_mpinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_parientes_mp_edit = NULL; // Initialize page object first

class ct_parientes_mp_edit extends ct_parientes_mp {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_parientes_mp';

	// Page object name
	var $PageObjName = 't_parientes_mp_edit';

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

		// Table object (t_parientes_mp)
		if (!isset($GLOBALS["t_parientes_mp"]) || get_class($GLOBALS["t_parientes_mp"]) == "ct_parientes_mp") {
			$GLOBALS["t_parientes_mp"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_parientes_mp"];
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
			define("EW_TABLE_NAME", 't_parientes_mp', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_parientes_mplist.php"));
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
		$this->Id->SetVisibility();
		$this->Nombres->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Grado_Parentesco->SetVisibility();
		$this->Parentesco->SetVisibility();
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
		global $EW_EXPORT, $t_parientes_mp;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_parientes_mp);
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
		if (@$_GET["Nombres"] <> "") {
			$this->Nombres->setQueryStringValue($_GET["Nombres"]);
		}
		if (@$_GET["Apellido_Paterno"] <> "") {
			$this->Apellido_Paterno->setQueryStringValue($_GET["Apellido_Paterno"]);
		}
		if (@$_GET["Apellido_Materno"] <> "") {
			$this->Apellido_Materno->setQueryStringValue($_GET["Apellido_Materno"]);
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
		if ($this->Nombres->CurrentValue == "") {
			$this->Page_Terminate("t_parientes_mplist.php"); // Invalid key, return to list
		}
		if ($this->Apellido_Paterno->CurrentValue == "") {
			$this->Page_Terminate("t_parientes_mplist.php"); // Invalid key, return to list
		}
		if ($this->Apellido_Materno->CurrentValue == "") {
			$this->Page_Terminate("t_parientes_mplist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("t_parientes_mplist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_parientes_mplist.php")
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
		if (!$this->Id->FldIsDetailKey) {
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		}
		if (!$this->Nombres->FldIsDetailKey) {
			$this->Nombres->setFormValue($objForm->GetValue("x_Nombres"));
		}
		if (!$this->Apellido_Paterno->FldIsDetailKey) {
			$this->Apellido_Paterno->setFormValue($objForm->GetValue("x_Apellido_Paterno"));
		}
		if (!$this->Apellido_Materno->FldIsDetailKey) {
			$this->Apellido_Materno->setFormValue($objForm->GetValue("x_Apellido_Materno"));
		}
		if (!$this->Grado_Parentesco->FldIsDetailKey) {
			$this->Grado_Parentesco->setFormValue($objForm->GetValue("x_Grado_Parentesco"));
		}
		if (!$this->Parentesco->FldIsDetailKey) {
			$this->Parentesco->setFormValue($objForm->GetValue("x_Parentesco"));
		}
		if (!$this->Unidad_Organizacional->FldIsDetailKey) {
			$this->Unidad_Organizacional->setFormValue($objForm->GetValue("x_Unidad_Organizacional"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Grado_Parentesco->CurrentValue = $this->Grado_Parentesco->FormValue;
		$this->Parentesco->CurrentValue = $this->Parentesco->FormValue;
		$this->Unidad_Organizacional->CurrentValue = $this->Unidad_Organizacional->FormValue;
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
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Grado_Parentesco->setDbValue($rs->fields('Grado_Parentesco'));
		if (array_key_exists('EV__Grado_Parentesco', $rs->fields)) {
			$this->Grado_Parentesco->VirtualValue = $rs->fields('EV__Grado_Parentesco'); // Set up virtual field value
		} else {
			$this->Grado_Parentesco->VirtualValue = ""; // Clear value
		}
		$this->Parentesco->setDbValue($rs->fields('Parentesco'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Grado_Parentesco->DbValue = $row['Grado_Parentesco'];
		$this->Parentesco->DbValue = $row['Parentesco'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// Nombres
		// Apellido_Paterno
		// Apellido_Materno
		// Grado_Parentesco
		// Parentesco
		// Unidad_Organizacional

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Grado_Parentesco
		if ($this->Grado_Parentesco->VirtualValue <> "") {
			$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->VirtualValue;
		} else {
		if (strval($this->Grado_Parentesco->CurrentValue) <> "") {
			$sFilterWrk = "`Grado`" . ew_SearchString("=", $this->Grado_Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Grado`, `Grado` AS `DispFld`, `Parentesco` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_parentesco_global`";
		$sWhereWrk = "";
		$this->Grado_Parentesco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->CurrentValue;
			}
		} else {
			$this->Grado_Parentesco->ViewValue = NULL;
		}
		}
		$this->Grado_Parentesco->ViewCustomAttributes = "";

		// Parentesco
		$this->Parentesco->ViewValue = $this->Parentesco->CurrentValue;
		$this->Parentesco->ViewCustomAttributes = "";

		// Unidad_Organizacional
		if (strval($this->Unidad_Organizacional->CurrentValue) <> "") {
			$sFilterWrk = "`Unidad_Organizacional`" . ew_SearchString("=", $this->Unidad_Organizacional->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Unidad_Organizacional`, `Unidad_Organizacional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `seleccion_cargos`";
		$sWhereWrk = "";
		$this->Unidad_Organizacional->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Unidad_Organizacional, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
			}
		} else {
			$this->Unidad_Organizacional->ViewValue = NULL;
		}
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";
			$this->Nombres->TooltipValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";
			$this->Apellido_Paterno->TooltipValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";
			$this->Apellido_Materno->TooltipValue = "";

			// Grado_Parentesco
			$this->Grado_Parentesco->LinkCustomAttributes = "";
			$this->Grado_Parentesco->HrefValue = "";
			$this->Grado_Parentesco->TooltipValue = "";

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";
			$this->Parentesco->TooltipValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			if ($this->Id->getSessionValue() <> "") {
				$this->Id->CurrentValue = $this->Id->getSessionValue();
			$this->Id->ViewValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";
			} else {
			$this->Id->EditValue = ew_HtmlEncode($this->Id->CurrentValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());
			}

			// Nombres
			$this->Nombres->EditAttrs["class"] = "form-control";
			$this->Nombres->EditCustomAttributes = "";
			$this->Nombres->EditValue = $this->Nombres->CurrentValue;
			$this->Nombres->ViewCustomAttributes = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->EditAttrs["class"] = "form-control";
			$this->Apellido_Paterno->EditCustomAttributes = "";
			$this->Apellido_Paterno->EditValue = $this->Apellido_Paterno->CurrentValue;
			$this->Apellido_Paterno->ViewCustomAttributes = "";

			// Apellido_Materno
			$this->Apellido_Materno->EditAttrs["class"] = "form-control";
			$this->Apellido_Materno->EditCustomAttributes = "";
			$this->Apellido_Materno->EditValue = $this->Apellido_Materno->CurrentValue;
			$this->Apellido_Materno->ViewCustomAttributes = "";

			// Grado_Parentesco
			$this->Grado_Parentesco->EditAttrs["class"] = "form-control";
			$this->Grado_Parentesco->EditCustomAttributes = "";
			if (trim(strval($this->Grado_Parentesco->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Grado`" . ew_SearchString("=", $this->Grado_Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Grado`, `Grado` AS `DispFld`, `Parentesco` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `s_parentesco_global`";
			$sWhereWrk = "";
			$this->Grado_Parentesco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Grado_Parentesco->EditValue = $arwrk;

			// Parentesco
			$this->Parentesco->EditAttrs["class"] = "form-control";
			$this->Parentesco->EditCustomAttributes = "";
			$this->Parentesco->EditValue = ew_HtmlEncode($this->Parentesco->CurrentValue);
			$this->Parentesco->PlaceHolder = ew_RemoveHtml($this->Parentesco->FldCaption());

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			if (trim(strval($this->Unidad_Organizacional->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Unidad_Organizacional`" . ew_SearchString("=", $this->Unidad_Organizacional->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Unidad_Organizacional`, `Unidad_Organizacional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `seleccion_cargos`";
			$sWhereWrk = "";
			$this->Unidad_Organizacional->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Unidad_Organizacional, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Unidad_Organizacional->EditValue = $arwrk;

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Grado_Parentesco
			$this->Grado_Parentesco->LinkCustomAttributes = "";
			$this->Grado_Parentesco->HrefValue = "";

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";

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

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Id->FldIsDetailKey && !is_null($this->Id->FormValue) && $this->Id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id->FldCaption(), $this->Id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Id->FormValue)) {
			ew_AddMessage($gsFormError, $this->Id->FldErrMsg());
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

			// Id
			$this->Id->SetDbValueDef($rsnew, $this->Id->CurrentValue, 0, $this->Id->ReadOnly);

			// Nombres
			// Apellido_Paterno
			// Apellido_Materno
			// Grado_Parentesco

			$this->Grado_Parentesco->SetDbValueDef($rsnew, $this->Grado_Parentesco->CurrentValue, "", $this->Grado_Parentesco->ReadOnly);

			// Parentesco
			$this->Parentesco->SetDbValueDef($rsnew, $this->Parentesco->CurrentValue, "", $this->Parentesco->ReadOnly);

			// Unidad_Organizacional
			$this->Unidad_Organizacional->SetDbValueDef($rsnew, $this->Unidad_Organizacional->CurrentValue, "", $this->Unidad_Organizacional->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_parientes_mplist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Grado_Parentesco":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Grado` AS `LinkFld`, `Grado` AS `DispFld`, `Parentesco` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_parentesco_global`";
			$sWhereWrk = "";
			$this->Grado_Parentesco->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Grado` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_Unidad_Organizacional":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Unidad_Organizacional` AS `LinkFld`, `Unidad_Organizacional` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `seleccion_cargos`";
			$sWhereWrk = "";
			$this->Unidad_Organizacional->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Unidad_Organizacional` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Unidad_Organizacional, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_parientes_mp_edit)) $t_parientes_mp_edit = new ct_parientes_mp_edit();

// Page init
$t_parientes_mp_edit->Page_Init();

// Page main
$t_parientes_mp_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_parientes_mp_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_parientes_mpedit = new ew_Form("ft_parientes_mpedit", "edit");

// Validate form
ft_parientes_mpedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_parientes_mp->Id->FldCaption(), $t_parientes_mp->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_parientes_mp->Id->FldErrMsg()) ?>");

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
ft_parientes_mpedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_parientes_mpedit.ValidateRequired = true;
<?php } else { ?>
ft_parientes_mpedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_parientes_mpedit.Lists["x_Grado_Parentesco"] = {"LinkField":"x_Grado","Ajax":true,"AutoFill":true,"DisplayFields":["x_Grado","x_Parentesco","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_parentesco_global"};
ft_parientes_mpedit.Lists["x_Unidad_Organizacional"] = {"LinkField":"x_Unidad_Organizacional","Ajax":true,"AutoFill":false,"DisplayFields":["x_Unidad_Organizacional","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"seleccion_cargos"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_parientes_mp_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_parientes_mp_edit->ShowPageHeader(); ?>
<?php
$t_parientes_mp_edit->ShowMessage();
?>
<form name="ft_parientes_mpedit" id="ft_parientes_mpedit" class="<?php echo $t_parientes_mp_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_parientes_mp_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_parientes_mp_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_parientes_mp">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_parientes_mp_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_parientes_mp->getCurrentMasterTable() == "t_funcionario") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_funcionario">
<input type="hidden" name="fk_Id" value="<?php echo $t_parientes_mp->Id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_parientes_mp->Id->Visible) { // Id ?>
	<div id="r_Id" class="form-group">
		<label id="elh_t_parientes_mp_Id" for="x_Id" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Id->CellAttributes() ?>>
<?php if ($t_parientes_mp->Id->getSessionValue() <> "") { ?>
<span id="el_t_parientes_mp_Id">
<span<?php echo $t_parientes_mp->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_Id" name="x_Id" value="<?php echo ew_HtmlEncode($t_parientes_mp->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_t_parientes_mp_Id">
<input type="text" data-table="t_parientes_mp" data-field="x_Id" data-page="1" name="x_Id" id="x_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Id->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Id->EditValue ?>"<?php echo $t_parientes_mp->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $t_parientes_mp->Id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_parientes_mp->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label id="elh_t_parientes_mp_Nombres" for="x_Nombres" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Nombres->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Nombres->CellAttributes() ?>>
<span id="el_t_parientes_mp_Nombres">
<span<?php echo $t_parientes_mp->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Nombres->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Nombres" data-page="1" name="x_Nombres" id="x_Nombres" value="<?php echo ew_HtmlEncode($t_parientes_mp->Nombres->CurrentValue) ?>">
<?php echo $t_parientes_mp->Nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_parientes_mp->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label id="elh_t_parientes_mp_Apellido_Paterno" for="x_Apellido_Paterno" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Apellido_Paterno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Apellido_Paterno->CellAttributes() ?>>
<span id="el_t_parientes_mp_Apellido_Paterno">
<span<?php echo $t_parientes_mp->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Paterno" data-page="1" name="x_Apellido_Paterno" id="x_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Paterno->CurrentValue) ?>">
<?php echo $t_parientes_mp->Apellido_Paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_parientes_mp->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label id="elh_t_parientes_mp_Apellido_Materno" for="x_Apellido_Materno" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Apellido_Materno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Apellido_Materno->CellAttributes() ?>>
<span id="el_t_parientes_mp_Apellido_Materno">
<span<?php echo $t_parientes_mp->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_parientes_mp->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_parientes_mp" data-field="x_Apellido_Materno" data-page="1" name="x_Apellido_Materno" id="x_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_parientes_mp->Apellido_Materno->CurrentValue) ?>">
<?php echo $t_parientes_mp->Apellido_Materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_parientes_mp->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
	<div id="r_Grado_Parentesco" class="form-group">
		<label id="elh_t_parientes_mp_Grado_Parentesco" for="x_Grado_Parentesco" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Grado_Parentesco->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Grado_Parentesco->CellAttributes() ?>>
<span id="el_t_parientes_mp_Grado_Parentesco">
<?php $t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$t_parientes_mp->Grado_Parentesco->EditAttrs["onchange"]; ?>
<select data-table="t_parientes_mp" data-field="x_Grado_Parentesco" data-page="1" data-value-separator="<?php echo $t_parientes_mp->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x_Grado_Parentesco" name="x_Grado_Parentesco"<?php echo $t_parientes_mp->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_parientes_mp->Grado_Parentesco->SelectOptionListHtml("x_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x_Grado_Parentesco" id="s_x_Grado_Parentesco" value="<?php echo $t_parientes_mp->Grado_Parentesco->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Grado_Parentesco" id="ln_x_Grado_Parentesco" value="x_Parentesco">
</span>
<?php echo $t_parientes_mp->Grado_Parentesco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_parientes_mp->Parentesco->Visible) { // Parentesco ?>
	<div id="r_Parentesco" class="form-group">
		<label id="elh_t_parientes_mp_Parentesco" for="x_Parentesco" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Parentesco->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Parentesco->CellAttributes() ?>>
<span id="el_t_parientes_mp_Parentesco">
<input type="text" data-table="t_parientes_mp" data-field="x_Parentesco" data-page="1" name="x_Parentesco" id="x_Parentesco" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_parientes_mp->Parentesco->getPlaceHolder()) ?>" value="<?php echo $t_parientes_mp->Parentesco->EditValue ?>"<?php echo $t_parientes_mp->Parentesco->EditAttributes() ?>>
</span>
<?php echo $t_parientes_mp->Parentesco->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_parientes_mp->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label id="elh_t_parientes_mp_Unidad_Organizacional" for="x_Unidad_Organizacional" class="col-sm-2 control-label ewLabel"><?php echo $t_parientes_mp->Unidad_Organizacional->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_parientes_mp->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_parientes_mp_Unidad_Organizacional">
<select data-table="t_parientes_mp" data-field="x_Unidad_Organizacional" data-page="1" data-value-separator="<?php echo $t_parientes_mp->Unidad_Organizacional->DisplayValueSeparatorAttribute() ?>" id="x_Unidad_Organizacional" name="x_Unidad_Organizacional"<?php echo $t_parientes_mp->Unidad_Organizacional->EditAttributes() ?>>
<?php echo $t_parientes_mp->Unidad_Organizacional->SelectOptionListHtml("x_Unidad_Organizacional") ?>
</select>
<input type="hidden" name="s_x_Unidad_Organizacional" id="s_x_Unidad_Organizacional" value="<?php echo $t_parientes_mp->Unidad_Organizacional->LookupFilterQuery() ?>">
</span>
<?php echo $t_parientes_mp->Unidad_Organizacional->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_parientes_mp_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_parientes_mp_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_parientes_mpedit.Init();
</script>
<?php
$t_parientes_mp_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_parientes_mp_edit->Page_Terminate();
?>
