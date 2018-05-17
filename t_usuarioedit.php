<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_usuario_edit = NULL; // Initialize page object first

class ct_usuario_edit extends ct_usuario {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_usuario';

	// Page object name
	var $PageObjName = 't_usuario_edit';

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
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = FALSE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

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

		// Table object (t_usuario)
		if (!isset($GLOBALS["t_usuario"]) || get_class($GLOBALS["t_usuario"]) == "ct_usuario") {
			$GLOBALS["t_usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_usuario"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_usuario', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_usuariolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("t_usuariolist.php"));
			}
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->Codigo_usuario->SetVisibility();
		$this->Exp->SetVisibility();
		$this->Nombres->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Usuario->SetVisibility();
		$this->Clave->SetVisibility();
		$this->Nivel_id->SetVisibility();
		$this->_Email->SetVisibility();
		$this->Activado->SetVisibility();

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
		global $EW_EXPORT, $t_usuario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_usuario);
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
		if (@$_GET["Codigo_usuario"] <> "") {
			$this->Codigo_usuario->setQueryStringValue($_GET["Codigo_usuario"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->Codigo_usuario->CurrentValue == "") {
			$this->Page_Terminate("t_usuariolist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("t_usuariolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_usuariolist.php")
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
		if (!$this->Codigo_usuario->FldIsDetailKey) {
			$this->Codigo_usuario->setFormValue($objForm->GetValue("x_Codigo_usuario"));
		}
		if (!$this->Exp->FldIsDetailKey) {
			$this->Exp->setFormValue($objForm->GetValue("x_Exp"));
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
		if (!$this->Usuario->FldIsDetailKey) {
			$this->Usuario->setFormValue($objForm->GetValue("x_Usuario"));
		}
		if (!$this->Clave->FldIsDetailKey) {
			$this->Clave->setFormValue($objForm->GetValue("x_Clave"));
		}
		if (!$this->Nivel_id->FldIsDetailKey) {
			$this->Nivel_id->setFormValue($objForm->GetValue("x_Nivel_id"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->Activado->FldIsDetailKey) {
			$this->Activado->setFormValue($objForm->GetValue("x_Activado"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Codigo_usuario->CurrentValue = $this->Codigo_usuario->FormValue;
		$this->Exp->CurrentValue = $this->Exp->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Usuario->CurrentValue = $this->Usuario->FormValue;
		$this->Clave->CurrentValue = $this->Clave->FormValue;
		$this->Nivel_id->CurrentValue = $this->Nivel_id->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->Activado->CurrentValue = $this->Activado->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('edit');
			if (!$res) {
				$sUserIdMsg = ew_DeniedMsg();
				$this->setFailureMessage($sUserIdMsg);
			}
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->Codigo_usuario->setDbValue($rs->fields('Codigo_usuario'));
		$this->Exp->setDbValue($rs->fields('Exp'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Usuario->setDbValue($rs->fields('Usuario'));
		$this->Clave->setDbValue($rs->fields('Clave'));
		$this->Nivel_id->setDbValue($rs->fields('Nivel_id'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->Activado->setDbValue($rs->fields('Activado'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Codigo_usuario->DbValue = $row['Codigo_usuario'];
		$this->Exp->DbValue = $row['Exp'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Usuario->DbValue = $row['Usuario'];
		$this->Clave->DbValue = $row['Clave'];
		$this->Nivel_id->DbValue = $row['Nivel_id'];
		$this->_Email->DbValue = $row['Email'];
		$this->Activado->DbValue = $row['Activado'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Codigo_usuario
		// Exp
		// Nombres
		// Apellido_Paterno
		// Apellido_Materno
		// Usuario
		// Clave
		// Nivel_id
		// Email
		// Activado

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Codigo_usuario
		$this->Codigo_usuario->ViewValue = $this->Codigo_usuario->CurrentValue;
		$this->Codigo_usuario->ViewCustomAttributes = "";

		// Exp
		if (strval($this->Exp->CurrentValue) <> "") {
			$this->Exp->ViewValue = $this->Exp->OptionCaption($this->Exp->CurrentValue);
		} else {
			$this->Exp->ViewValue = NULL;
		}
		$this->Exp->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Usuario
		$this->Usuario->ViewValue = $this->Usuario->CurrentValue;
		$this->Usuario->ViewCustomAttributes = "";

		// Clave
		$this->Clave->ViewValue = $this->Clave->CurrentValue;
		$this->Clave->ViewCustomAttributes = "";

		// Nivel_id
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->Nivel_id->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->Nivel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->Nivel_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Nivel_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Nivel_id->ViewValue = $this->Nivel_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Nivel_id->ViewValue = $this->Nivel_id->CurrentValue;
			}
		} else {
			$this->Nivel_id->ViewValue = NULL;
		}
		} else {
			$this->Nivel_id->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->Nivel_id->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// Activado
		$this->Activado->ViewValue = $this->Activado->CurrentValue;
		$this->Activado->ViewCustomAttributes = "";

			// Codigo_usuario
			$this->Codigo_usuario->LinkCustomAttributes = "";
			$this->Codigo_usuario->HrefValue = "";
			$this->Codigo_usuario->TooltipValue = "";

			// Exp
			$this->Exp->LinkCustomAttributes = "";
			$this->Exp->HrefValue = "";
			$this->Exp->TooltipValue = "";

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

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";
			$this->Usuario->TooltipValue = "";

			// Clave
			$this->Clave->LinkCustomAttributes = "";
			$this->Clave->HrefValue = "";
			$this->Clave->TooltipValue = "";

			// Nivel_id
			$this->Nivel_id->LinkCustomAttributes = "";
			$this->Nivel_id->HrefValue = "";
			$this->Nivel_id->TooltipValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";

			// Activado
			$this->Activado->LinkCustomAttributes = "";
			$this->Activado->HrefValue = "";
			$this->Activado->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Codigo_usuario
			$this->Codigo_usuario->EditAttrs["class"] = "form-control";
			$this->Codigo_usuario->EditCustomAttributes = "";
			$this->Codigo_usuario->EditValue = $this->Codigo_usuario->CurrentValue;
			$this->Codigo_usuario->ViewCustomAttributes = "";

			// Exp
			$this->Exp->EditAttrs["class"] = "form-control";
			$this->Exp->EditCustomAttributes = "";
			$this->Exp->EditValue = $this->Exp->Options(TRUE);

			// Nombres
			$this->Nombres->EditAttrs["class"] = "form-control";
			$this->Nombres->EditCustomAttributes = "";
			$this->Nombres->EditValue = ew_HtmlEncode($this->Nombres->CurrentValue);
			$this->Nombres->PlaceHolder = ew_RemoveHtml($this->Nombres->FldCaption());

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

			// Usuario
			$this->Usuario->EditAttrs["class"] = "form-control";
			$this->Usuario->EditCustomAttributes = "";
			$this->Usuario->EditValue = ew_HtmlEncode($this->Usuario->CurrentValue);
			$this->Usuario->PlaceHolder = ew_RemoveHtml($this->Usuario->FldCaption());

			// Clave
			$this->Clave->EditAttrs["class"] = "form-control";
			$this->Clave->EditCustomAttributes = "";
			$this->Clave->EditValue = ew_HtmlEncode($this->Clave->CurrentValue);
			$this->Clave->PlaceHolder = ew_RemoveHtml($this->Clave->FldCaption());

			// Nivel_id
			$this->Nivel_id->EditAttrs["class"] = "form-control";
			$this->Nivel_id->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->Nivel_id->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->Nivel_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->Nivel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->Nivel_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Nivel_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Nivel_id->EditValue = $arwrk;
			}

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

			// Activado
			$this->Activado->EditAttrs["class"] = "form-control";
			$this->Activado->EditCustomAttributes = "";
			$this->Activado->EditValue = ew_HtmlEncode($this->Activado->CurrentValue);
			$this->Activado->PlaceHolder = ew_RemoveHtml($this->Activado->FldCaption());

			// Edit refer script
			// Codigo_usuario

			$this->Codigo_usuario->LinkCustomAttributes = "";
			$this->Codigo_usuario->HrefValue = "";

			// Exp
			$this->Exp->LinkCustomAttributes = "";
			$this->Exp->HrefValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";

			// Clave
			$this->Clave->LinkCustomAttributes = "";
			$this->Clave->HrefValue = "";

			// Nivel_id
			$this->Nivel_id->LinkCustomAttributes = "";
			$this->Nivel_id->HrefValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";

			// Activado
			$this->Activado->LinkCustomAttributes = "";
			$this->Activado->HrefValue = "";
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
		if (!$this->Codigo_usuario->FldIsDetailKey && !is_null($this->Codigo_usuario->FormValue) && $this->Codigo_usuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Codigo_usuario->FldCaption(), $this->Codigo_usuario->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Codigo_usuario->FormValue)) {
			ew_AddMessage($gsFormError, $this->Codigo_usuario->FldErrMsg());
		}
		if (!$this->Exp->FldIsDetailKey && !is_null($this->Exp->FormValue) && $this->Exp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Exp->FldCaption(), $this->Exp->ReqErrMsg));
		}
		if (!$this->Nombres->FldIsDetailKey && !is_null($this->Nombres->FormValue) && $this->Nombres->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nombres->FldCaption(), $this->Nombres->ReqErrMsg));
		}
		if (!$this->Apellido_Paterno->FldIsDetailKey && !is_null($this->Apellido_Paterno->FormValue) && $this->Apellido_Paterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Apellido_Paterno->FldCaption(), $this->Apellido_Paterno->ReqErrMsg));
		}
		if (!$this->Apellido_Materno->FldIsDetailKey && !is_null($this->Apellido_Materno->FormValue) && $this->Apellido_Materno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Apellido_Materno->FldCaption(), $this->Apellido_Materno->ReqErrMsg));
		}
		if (!$this->Usuario->FldIsDetailKey && !is_null($this->Usuario->FormValue) && $this->Usuario->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Usuario->FldCaption(), $this->Usuario->ReqErrMsg));
		}
		if (!$this->Clave->FldIsDetailKey && !is_null($this->Clave->FormValue) && $this->Clave->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Clave->FldCaption(), $this->Clave->ReqErrMsg));
		}
		if (!$this->Nivel_id->FldIsDetailKey && !is_null($this->Nivel_id->FormValue) && $this->Nivel_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nivel_id->FldCaption(), $this->Nivel_id->ReqErrMsg));
		}
		if (!$this->_Email->FldIsDetailKey && !is_null($this->_Email->FormValue) && $this->_Email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_Email->FldCaption(), $this->_Email->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->_Email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_Email->FldErrMsg());
		}
		if (!$this->Activado->FldIsDetailKey && !is_null($this->Activado->FormValue) && $this->Activado->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Activado->FldCaption(), $this->Activado->ReqErrMsg));
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

			// Codigo_usuario
			// Exp

			$this->Exp->SetDbValueDef($rsnew, $this->Exp->CurrentValue, "", $this->Exp->ReadOnly);

			// Nombres
			$this->Nombres->SetDbValueDef($rsnew, $this->Nombres->CurrentValue, "", $this->Nombres->ReadOnly);

			// Apellido_Paterno
			$this->Apellido_Paterno->SetDbValueDef($rsnew, $this->Apellido_Paterno->CurrentValue, "", $this->Apellido_Paterno->ReadOnly);

			// Apellido_Materno
			$this->Apellido_Materno->SetDbValueDef($rsnew, $this->Apellido_Materno->CurrentValue, "", $this->Apellido_Materno->ReadOnly);

			// Usuario
			$this->Usuario->SetDbValueDef($rsnew, $this->Usuario->CurrentValue, "", $this->Usuario->ReadOnly);

			// Clave
			$this->Clave->SetDbValueDef($rsnew, $this->Clave->CurrentValue, "", $this->Clave->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('Clave') == $this->Clave->CurrentValue));

			// Nivel_id
			if ($Security->CanAdmin()) { // System admin
			$this->Nivel_id->SetDbValueDef($rsnew, $this->Nivel_id->CurrentValue, 0, $this->Nivel_id->ReadOnly);
			}

			// Email
			$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, "", $this->_Email->ReadOnly);

			// Activado
			$this->Activado->SetDbValueDef($rsnew, $this->Activado->CurrentValue, 0, $this->Activado->ReadOnly);

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
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
			if ($this->SendEmail)
				$this->SendEmailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->Codigo_usuario->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_usuariolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Nivel_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$this->Nivel_id->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`userlevelid` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Nivel_id, $sWhereWrk); // Call Lookup selecting
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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_usuario';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 't_usuario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['Codigo_usuario'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					if ($fldname == 'Clave') {
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Send email after update success
	function SendEmailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		$sTable = 't_usuario';
		$sSubject = $sTable . " ". $Language->Phrase("RecordUpdated");
		$sAction = $Language->Phrase("ActionUpdated");

		// Get key value
		$sKey = "";
		if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$sKey .= $rsold['Codigo_usuario'];
		$Email = new cEmail();
		$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
		$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
		$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
		$Email->ReplaceSubject($sSubject); // Replace Subject
		$Email->ReplaceContent("<!--table-->", $sTable);
		$Email->ReplaceContent("<!--key-->", $sKey);
		$Email->ReplaceContent("<!--action-->", $sAction);
		$Args = array();
		$Args["rsold"] = &$rsold;
		$Args["rsnew"] = &$rsnew;
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $Args))
			$bEmailSent = $Email->Send();

		// Send email failed
		if (!$bEmailSent)
			$this->setFailureMessage($Email->SendErrDescription);
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
if (!isset($t_usuario_edit)) $t_usuario_edit = new ct_usuario_edit();

// Page init
$t_usuario_edit->Page_Init();

// Page main
$t_usuario_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_usuario_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_usuarioedit = new ew_Form("ft_usuarioedit", "edit");

// Validate form
ft_usuarioedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Codigo_usuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Codigo_usuario->FldCaption(), $t_usuario->Codigo_usuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Codigo_usuario");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_usuario->Codigo_usuario->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Exp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Exp->FldCaption(), $t_usuario->Exp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Nombres->FldCaption(), $t_usuario->Nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Paterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Apellido_Paterno->FldCaption(), $t_usuario->Apellido_Paterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Materno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Apellido_Materno->FldCaption(), $t_usuario->Apellido_Materno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Usuario");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Usuario->FldCaption(), $t_usuario->Usuario->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Clave");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Clave->FldCaption(), $t_usuario->Clave->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Nivel_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Nivel_id->FldCaption(), $t_usuario->Nivel_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->_Email->FldCaption(), $t_usuario->_Email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_usuario->_Email->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Activado");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->Activado->FldCaption(), $t_usuario->Activado->ReqErrMsg)) ?>");

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
ft_usuarioedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_usuarioedit.ValidateRequired = true;
<?php } else { ?>
ft_usuarioedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_usuarioedit.Lists["x_Exp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_usuarioedit.Lists["x_Exp"].Options = <?php echo json_encode($t_usuario->Exp->Options()) ?>;
ft_usuarioedit.Lists["x_Nivel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_usuario_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_usuario_edit->ShowPageHeader(); ?>
<?php
$t_usuario_edit->ShowMessage();
?>
<form name="ft_usuarioedit" id="ft_usuarioedit" class="<?php echo $t_usuario_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_usuario_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_usuario_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_usuario">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_usuario_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($t_usuario->Codigo_usuario->Visible) { // Codigo_usuario ?>
	<div id="r_Codigo_usuario" class="form-group">
		<label id="elh_t_usuario_Codigo_usuario" for="x_Codigo_usuario" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Codigo_usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Codigo_usuario->CellAttributes() ?>>
<span id="el_t_usuario_Codigo_usuario">
<span<?php echo $t_usuario->Codigo_usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Codigo_usuario->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Codigo_usuario" name="x_Codigo_usuario" id="x_Codigo_usuario" value="<?php echo ew_HtmlEncode($t_usuario->Codigo_usuario->CurrentValue) ?>">
<?php echo $t_usuario->Codigo_usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Exp->Visible) { // Exp ?>
	<div id="r_Exp" class="form-group">
		<label id="elh_t_usuario_Exp" for="x_Exp" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Exp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Exp->CellAttributes() ?>>
<span id="el_t_usuario_Exp">
<select data-table="t_usuario" data-field="x_Exp" data-value-separator="<?php echo $t_usuario->Exp->DisplayValueSeparatorAttribute() ?>" id="x_Exp" name="x_Exp"<?php echo $t_usuario->Exp->EditAttributes() ?>>
<?php echo $t_usuario->Exp->SelectOptionListHtml("x_Exp") ?>
</select>
</span>
<?php echo $t_usuario->Exp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label id="elh_t_usuario_Nombres" for="x_Nombres" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Nombres->CellAttributes() ?>>
<span id="el_t_usuario_Nombres">
<input type="text" data-table="t_usuario" data-field="x_Nombres" name="x_Nombres" id="x_Nombres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_usuario->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Nombres->EditValue ?>"<?php echo $t_usuario->Nombres->EditAttributes() ?>>
</span>
<?php echo $t_usuario->Nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label id="elh_t_usuario_Apellido_Paterno" for="x_Apellido_Paterno" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Apellido_Paterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Apellido_Paterno->CellAttributes() ?>>
<span id="el_t_usuario_Apellido_Paterno">
<input type="text" data-table="t_usuario" data-field="x_Apellido_Paterno" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_usuario->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Apellido_Paterno->EditValue ?>"<?php echo $t_usuario->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php echo $t_usuario->Apellido_Paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label id="elh_t_usuario_Apellido_Materno" for="x_Apellido_Materno" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Apellido_Materno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Apellido_Materno->CellAttributes() ?>>
<span id="el_t_usuario_Apellido_Materno">
<input type="text" data-table="t_usuario" data-field="x_Apellido_Materno" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" placeholder="<?php echo ew_HtmlEncode($t_usuario->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Apellido_Materno->EditValue ?>"<?php echo $t_usuario->Apellido_Materno->EditAttributes() ?>>
</span>
<?php echo $t_usuario->Apellido_Materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Usuario->Visible) { // Usuario ?>
	<div id="r_Usuario" class="form-group">
		<label id="elh_t_usuario_Usuario" for="x_Usuario" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Usuario->CellAttributes() ?>>
<span id="el_t_usuario_Usuario">
<input type="text" data-table="t_usuario" data-field="x_Usuario" name="x_Usuario" id="x_Usuario" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($t_usuario->Usuario->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Usuario->EditValue ?>"<?php echo $t_usuario->Usuario->EditAttributes() ?>>
</span>
<?php echo $t_usuario->Usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Clave->Visible) { // Clave ?>
	<div id="r_Clave" class="form-group">
		<label id="elh_t_usuario_Clave" for="x_Clave" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Clave->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Clave->CellAttributes() ?>>
<span id="el_t_usuario_Clave">
<input type="text" data-table="t_usuario" data-field="x_Clave" name="x_Clave" id="x_Clave" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_usuario->Clave->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Clave->EditValue ?>"<?php echo $t_usuario->Clave->EditAttributes() ?>>
</span>
<?php echo $t_usuario->Clave->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Nivel_id->Visible) { // Nivel_id ?>
	<div id="r_Nivel_id" class="form-group">
		<label id="elh_t_usuario_Nivel_id" for="x_Nivel_id" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Nivel_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Nivel_id->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_t_usuario_Nivel_id">
<p class="form-control-static"><?php echo $t_usuario->Nivel_id->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_t_usuario_Nivel_id">
<select data-table="t_usuario" data-field="x_Nivel_id" data-value-separator="<?php echo $t_usuario->Nivel_id->DisplayValueSeparatorAttribute() ?>" id="x_Nivel_id" name="x_Nivel_id"<?php echo $t_usuario->Nivel_id->EditAttributes() ?>>
<?php echo $t_usuario->Nivel_id->SelectOptionListHtml("x_Nivel_id") ?>
</select>
<input type="hidden" name="s_x_Nivel_id" id="s_x_Nivel_id" value="<?php echo $t_usuario->Nivel_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $t_usuario->Nivel_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_t_usuario__Email" for="x__Email" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->_Email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->_Email->CellAttributes() ?>>
<span id="el_t_usuario__Email">
<input type="text" data-table="t_usuario" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_usuario->_Email->getPlaceHolder()) ?>" value="<?php echo $t_usuario->_Email->EditValue ?>"<?php echo $t_usuario->_Email->EditAttributes() ?>>
</span>
<?php echo $t_usuario->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Activado->Visible) { // Activado ?>
	<div id="r_Activado" class="form-group">
		<label id="elh_t_usuario_Activado" for="x_Activado" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Activado->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Activado->CellAttributes() ?>>
<span id="el_t_usuario_Activado">
<input type="text" data-table="t_usuario" data-field="x_Activado" name="x_Activado" id="x_Activado" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_usuario->Activado->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Activado->EditValue ?>"<?php echo $t_usuario->Activado->EditAttributes() ?>>
</span>
<?php echo $t_usuario->Activado->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_usuario_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_usuario_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_usuarioedit.Init();
</script>
<?php
$t_usuario_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_usuario_edit->Page_Terminate();
?>
