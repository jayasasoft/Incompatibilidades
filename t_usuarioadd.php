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

$t_usuario_add = NULL; // Initialize page object first

class ct_usuario_add extends ct_usuario {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_usuario';

	// Page object name
	var $PageObjName = 't_usuario_add';

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
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = FALSE;
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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Codigo_usuario"] != "") {
				$this->Codigo_usuario->setQueryStringValue($_GET["Codigo_usuario"]);
				$this->setKey("Codigo_usuario", $this->Codigo_usuario->CurrentValue); // Set up key
			} else {
				$this->setKey("Codigo_usuario", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_usuariolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_usuariolist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_usuarioview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Codigo_usuario->CurrentValue = NULL;
		$this->Codigo_usuario->OldValue = $this->Codigo_usuario->CurrentValue;
		$this->Exp->CurrentValue = NULL;
		$this->Exp->OldValue = $this->Exp->CurrentValue;
		$this->Nombres->CurrentValue = NULL;
		$this->Nombres->OldValue = $this->Nombres->CurrentValue;
		$this->Apellido_Paterno->CurrentValue = NULL;
		$this->Apellido_Paterno->OldValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Materno->CurrentValue = NULL;
		$this->Apellido_Materno->OldValue = $this->Apellido_Materno->CurrentValue;
		$this->Nivel_id->CurrentValue = NULL;
		$this->Nivel_id->OldValue = $this->Nivel_id->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
		$this->Activado->CurrentValue = NULL;
		$this->Activado->OldValue = $this->Activado->CurrentValue;
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
		$this->LoadOldRecord();
		$this->Codigo_usuario->CurrentValue = $this->Codigo_usuario->FormValue;
		$this->Exp->CurrentValue = $this->Exp->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
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
			$res = $this->ShowOptionLink('add');
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Codigo_usuario")) <> "")
			$this->Codigo_usuario->CurrentValue = $this->getKey("Codigo_usuario"); // Codigo_usuario
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Codigo_usuario
			$this->Codigo_usuario->EditAttrs["class"] = "form-control";
			$this->Codigo_usuario->EditCustomAttributes = "";
			if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			} elseif (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$this->UserIDAllow("add")) { // Non system admin
			} else {
			$this->Codigo_usuario->EditValue = ew_HtmlEncode($this->Codigo_usuario->CurrentValue);
			$this->Codigo_usuario->PlaceHolder = ew_RemoveHtml($this->Codigo_usuario->FldCaption());
			}

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

			// Add refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->Codigo_usuario->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->Codigo_usuario->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->Codigo_usuario->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}

		// Check if valid parent user id
		$bValidParentUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->Codigo_usuario->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidParentUser = $Security->IsValidUserID($this->Codigo_usuario->CurrentValue);
			if (!$bValidParentUser) {
				$sParentUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedParentUserID"));
				$sParentUserIdMsg = str_replace("%p", $this->Codigo_usuario->CurrentValue, $sParentUserIdMsg);
				$this->setFailureMessage($sParentUserIdMsg);
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Codigo_usuario
		$this->Codigo_usuario->SetDbValueDef($rsnew, $this->Codigo_usuario->CurrentValue, "", FALSE);

		// Exp
		$this->Exp->SetDbValueDef($rsnew, $this->Exp->CurrentValue, "", FALSE);

		// Nombres
		$this->Nombres->SetDbValueDef($rsnew, $this->Nombres->CurrentValue, "", FALSE);

		// Apellido_Paterno
		$this->Apellido_Paterno->SetDbValueDef($rsnew, $this->Apellido_Paterno->CurrentValue, "", FALSE);

		// Apellido_Materno
		$this->Apellido_Materno->SetDbValueDef($rsnew, $this->Apellido_Materno->CurrentValue, "", FALSE);

		// Nivel_id
		if ($Security->CanAdmin()) { // System admin
		$this->Nivel_id->SetDbValueDef($rsnew, $this->Nivel_id->CurrentValue, 0, FALSE);
		}

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, "", FALSE);

		// Activado
		$this->Activado->SetDbValueDef($rsnew, $this->Activado->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Codigo_usuario']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
			if ($this->SendEmail)
				$this->SendEmailOnAdd($rsnew);
		}
		return $AddRow;
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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 't_usuario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Codigo_usuario'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				if ($fldname == 'Clave')
					$newvalue = $Language->Phrase("PasswordMask");
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Send email after add success
	function SendEmailOnAdd(&$rs) {
		global $Language;
		$sTable = 't_usuario';
		$sSubject = $sTable . " " . $Language->Phrase("RecordInserted");
		$sAction = $Language->Phrase("ActionInserted");

		// Get key value
		$sKey = "";
		if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$sKey .= $rs['Codigo_usuario'];
		$Email = new cEmail();
		$Email->Load(EW_EMAIL_NOTIFY_TEMPLATE);
		$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
		$Email->ReplaceRecipient(EW_RECIPIENT_EMAIL); // Replace Recipient
		$Email->ReplaceSubject($sSubject); // Replace Subject
		$Email->ReplaceContent("<!--table-->", $sTable);
		$Email->ReplaceContent("<!--key-->", $sKey);
		$Email->ReplaceContent("<!--action-->", $sAction);
		$Args = array("rsnew" => $rs);
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
if (!isset($t_usuario_add)) $t_usuario_add = new ct_usuario_add();

// Page init
$t_usuario_add->Page_Init();

// Page main
$t_usuario_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_usuario_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_usuarioadd = new ew_Form("ft_usuarioadd", "add");

// Validate form
ft_usuarioadd.Validate = function() {
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
ft_usuarioadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_usuarioadd.ValidateRequired = true;
<?php } else { ?>
ft_usuarioadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_usuarioadd.Lists["x_Exp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_usuarioadd.Lists["x_Exp"].Options = <?php echo json_encode($t_usuario->Exp->Options()) ?>;
ft_usuarioadd.Lists["x_Nivel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_usuario_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_usuario_add->ShowPageHeader(); ?>
<?php
$t_usuario_add->ShowMessage();
?>
<form name="ft_usuarioadd" id="ft_usuarioadd" class="<?php echo $t_usuario_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_usuario_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_usuario_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_usuario">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_usuario_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_usuario->Codigo_usuario->Visible) { // Codigo_usuario ?>
	<div id="r_Codigo_usuario" class="form-group">
		<label id="elh_t_usuario_Codigo_usuario" for="x_Codigo_usuario" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Codigo_usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Codigo_usuario->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_t_usuario_Codigo_usuario">
<select data-table="t_usuario" data-field="x_Codigo_usuario" data-value-separator="<?php echo $t_usuario->Codigo_usuario->DisplayValueSeparatorAttribute() ?>" id="x_Codigo_usuario" name="x_Codigo_usuario"<?php echo $t_usuario->Codigo_usuario->EditAttributes() ?>>
<?php echo $t_usuario->Codigo_usuario->SelectOptionListHtml("x_Codigo_usuario") ?>
</select>
</span>
<?php } elseif (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$t_usuario->UserIDAllow("add")) { // Non system admin ?>
<span id="el_t_usuario_Codigo_usuario">
<select data-table="t_usuario" data-field="x_Codigo_usuario" data-value-separator="<?php echo $t_usuario->Codigo_usuario->DisplayValueSeparatorAttribute() ?>" id="x_Codigo_usuario" name="x_Codigo_usuario"<?php echo $t_usuario->Codigo_usuario->EditAttributes() ?>>
<?php echo $t_usuario->Codigo_usuario->SelectOptionListHtml("x_Codigo_usuario") ?>
</select>
</span>
<?php } else { ?>
<span id="el_t_usuario_Codigo_usuario">
<input type="text" data-table="t_usuario" data-field="x_Codigo_usuario" name="x_Codigo_usuario" id="x_Codigo_usuario" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_usuario->Codigo_usuario->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Codigo_usuario->EditValue ?>"<?php echo $t_usuario->Codigo_usuario->EditAttributes() ?>>
</span>
<?php } ?>
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
<?php if (!$t_usuario_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_usuario_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_usuarioadd.Init();
</script>
<?php
$t_usuario_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_usuario_add->Page_Terminate();
?>
