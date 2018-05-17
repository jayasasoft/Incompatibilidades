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

$register = NULL; // Initialize page object first

class cregister extends ct_usuario {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Page object name
	var $PageObjName = 'register';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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
		if (!isset($GLOBALS["t_usuario"])) $GLOBALS["t_usuario"] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("register", "RegisterPage", $url, "", "", TRUE);
		$bUserExists = FALSE;
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->Usuario->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in t_usuario class, t_usuarioinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						$Email = $this->PrepareRegisterEmail();

						// Get new recordset
						$this->CurrentFilter = $this->KeyFilter();
						$sSql = $this->SQL();
						$rsnew = $UserTableConn->Execute($sSql);
						$row = $rsnew->fields;
						$Args = array();
						$Args["rs"] = $row;
						$bEmailSent = FALSE;
						if ($this->Email_Sending($Email, $Args))
							$bEmailSent = $Email->Send();

						// Send email failed
						if (!$bEmailSent)
							$this->setFailureMessage($Email->SendErrDescription);
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success

						// Auto login user
						if ($Security->ValidateUser($this->Usuario->CurrentValue, $this->Clave->FormValue, TRUE)) {

							// Nothing to do
						}
						$this->Page_Terminate("index.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add
		}
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
		$this->Usuario->CurrentValue = NULL;
		$this->Usuario->OldValue = $this->Usuario->CurrentValue;
		$this->Clave->CurrentValue = NULL;
		$this->Clave->OldValue = $this->Clave->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
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
		$this->Clave->ConfirmValue = $objForm->GetValue("c_Clave");
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Codigo_usuario->CurrentValue = $this->Codigo_usuario->FormValue;
		$this->Exp->CurrentValue = $this->Exp->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Usuario->CurrentValue = $this->Usuario->FormValue;
		$this->Clave->CurrentValue = $this->Clave->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
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

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Codigo_usuario
			$this->Codigo_usuario->EditAttrs["class"] = "form-control";
			$this->Codigo_usuario->EditCustomAttributes = "";
			$this->Codigo_usuario->EditValue = ew_HtmlEncode($this->Codigo_usuario->CurrentValue);
			$this->Codigo_usuario->PlaceHolder = ew_RemoveHtml($this->Codigo_usuario->FldCaption());

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

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

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

			// Usuario
			$this->Usuario->LinkCustomAttributes = "";
			$this->Usuario->HrefValue = "";

			// Clave
			$this->Clave->LinkCustomAttributes = "";
			$this->Clave->HrefValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
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
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!$this->Clave->FldIsDetailKey && !is_null($this->Clave->FormValue) && $this->Clave->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
		}
		if ($this->Clave->ConfirmValue <> $this->Clave->FormValue) {
			ew_AddMessage($gsFormError, $Language->Phrase("MismatchPassword"));
		}
		if (!$this->_Email->FldIsDetailKey && !is_null($this->_Email->FormValue) && $this->_Email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_Email->FldCaption(), $this->_Email->ReqErrMsg));
		}
		if (!ew_CheckEmail($this->_Email->FormValue)) {
			ew_AddMessage($gsFormError, $this->_Email->FldErrMsg());
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

		// Usuario
		$this->Usuario->SetDbValueDef($rsnew, $this->Usuario->CurrentValue, "", FALSE);

		// Clave
		$this->Clave->SetDbValueDef($rsnew, $this->Clave->CurrentValue, "", FALSE);

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, "", FALSE);

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

			// Call User Registered event
			$this->User_Registered($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

	  //echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

	  //echo "User_Activated";
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$register->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "register";
var CurrentForm = fregister = new ew_Form("fregister", "register");

// Validate form
fregister.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterUserName"));
			elm = this.GetElements("x" + infix + "_Clave");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterPassword"));
			if (fobj.c_Clave.value != fobj.x_Clave.value)
				return this.OnError(fobj.c_Clave, ewLanguage.Phrase("MismatchPassword"));
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_usuario->_Email->FldCaption(), $t_usuario->_Email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_CheckEmail(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_usuario->_Email->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregister.ValidateRequired = true;
<?php } else { ?>
fregister.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fregister.Lists["x_Exp"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fregister.Lists["x_Exp"].Options = <?php echo json_encode($t_usuario->Exp->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="<?php echo $register->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($register->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $register->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_usuario">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<?php if ($t_usuario->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<div>
<?php if ($t_usuario->Codigo_usuario->Visible) { // Codigo_usuario ?>
	<div id="r_Codigo_usuario" class="form-group">
		<label id="elh_t_usuario_Codigo_usuario" for="x_Codigo_usuario" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Codigo_usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Codigo_usuario->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Codigo_usuario">
<input type="text" data-table="t_usuario" data-field="x_Codigo_usuario" data-page="1" name="x_Codigo_usuario" id="x_Codigo_usuario" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($t_usuario->Codigo_usuario->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Codigo_usuario->EditValue ?>"<?php echo $t_usuario->Codigo_usuario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario_Codigo_usuario">
<span<?php echo $t_usuario->Codigo_usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Codigo_usuario->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Codigo_usuario" data-page="1" name="x_Codigo_usuario" id="x_Codigo_usuario" value="<?php echo ew_HtmlEncode($t_usuario->Codigo_usuario->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Codigo_usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Exp->Visible) { // Exp ?>
	<div id="r_Exp" class="form-group">
		<label id="elh_t_usuario_Exp" for="x_Exp" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Exp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Exp->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Exp">
<select data-table="t_usuario" data-field="x_Exp" data-page="1" data-value-separator="<?php echo $t_usuario->Exp->DisplayValueSeparatorAttribute() ?>" id="x_Exp" name="x_Exp"<?php echo $t_usuario->Exp->EditAttributes() ?>>
<?php echo $t_usuario->Exp->SelectOptionListHtml("x_Exp") ?>
</select>
</span>
<?php } else { ?>
<span id="el_t_usuario_Exp">
<span<?php echo $t_usuario->Exp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Exp->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Exp" data-page="1" name="x_Exp" id="x_Exp" value="<?php echo ew_HtmlEncode($t_usuario->Exp->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Exp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label id="elh_t_usuario_Nombres" for="x_Nombres" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Nombres->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Nombres">
<input type="text" data-table="t_usuario" data-field="x_Nombres" data-page="1" name="x_Nombres" id="x_Nombres" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_usuario->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Nombres->EditValue ?>"<?php echo $t_usuario->Nombres->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario_Nombres">
<span<?php echo $t_usuario->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Nombres->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Nombres" data-page="1" name="x_Nombres" id="x_Nombres" value="<?php echo ew_HtmlEncode($t_usuario->Nombres->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label id="elh_t_usuario_Apellido_Paterno" for="x_Apellido_Paterno" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Apellido_Paterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Apellido_Paterno">
<input type="text" data-table="t_usuario" data-field="x_Apellido_Paterno" data-page="1" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_usuario->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Apellido_Paterno->EditValue ?>"<?php echo $t_usuario->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario_Apellido_Paterno">
<span<?php echo $t_usuario->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Apellido_Paterno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Apellido_Paterno" data-page="1" name="x_Apellido_Paterno" id="x_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_usuario->Apellido_Paterno->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Apellido_Paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label id="elh_t_usuario_Apellido_Materno" for="x_Apellido_Materno" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Apellido_Materno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Apellido_Materno">
<input type="text" data-table="t_usuario" data-field="x_Apellido_Materno" data-page="1" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" placeholder="<?php echo ew_HtmlEncode($t_usuario->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Apellido_Materno->EditValue ?>"<?php echo $t_usuario->Apellido_Materno->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario_Apellido_Materno">
<span<?php echo $t_usuario->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Apellido_Materno->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Apellido_Materno" data-page="1" name="x_Apellido_Materno" id="x_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_usuario->Apellido_Materno->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Apellido_Materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Usuario->Visible) { // Usuario ?>
	<div id="r_Usuario" class="form-group">
		<label id="elh_t_usuario_Usuario" for="x_Usuario" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Usuario->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Usuario->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Usuario">
<input type="text" data-table="t_usuario" data-field="x_Usuario" data-page="1" name="x_Usuario" id="x_Usuario" size="30" maxlength="12" placeholder="<?php echo ew_HtmlEncode($t_usuario->Usuario->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Usuario->EditValue ?>"<?php echo $t_usuario->Usuario->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario_Usuario">
<span<?php echo $t_usuario->Usuario->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Usuario->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Usuario" data-page="1" name="x_Usuario" id="x_Usuario" value="<?php echo ew_HtmlEncode($t_usuario->Usuario->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Clave->Visible) { // Clave ?>
	<div id="r_Clave" class="form-group">
		<label id="elh_t_usuario_Clave" for="x_Clave" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->Clave->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Clave->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario_Clave">
<input type="text" data-table="t_usuario" data-field="x_Clave" data-page="1" name="x_Clave" id="x_Clave" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_usuario->Clave->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Clave->EditValue ?>"<?php echo $t_usuario->Clave->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario_Clave">
<span<?php echo $t_usuario->Clave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Clave->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x_Clave" data-page="1" name="x_Clave" id="x_Clave" value="<?php echo ew_HtmlEncode($t_usuario->Clave->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->Clave->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->Clave->Visible) { // Clave ?>
	<div id="r_c_Clave" class="form-group">
		<label id="elh_c_t_usuario_Clave" for="c_Clave" class="col-sm-2 control-label ewLabel"><?php echo $Language->Phrase("Confirm") ?> <?php echo $t_usuario->Clave->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->Clave->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_c_t_usuario_Clave">
<input type="text" data-table="t_usuario" data-field="c_Clave" data-page="1" name="c_Clave" id="c_Clave" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_usuario->Clave->getPlaceHolder()) ?>" value="<?php echo $t_usuario->Clave->EditValue ?>"<?php echo $t_usuario->Clave->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_c_t_usuario_Clave">
<span<?php echo $t_usuario->Clave->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->Clave->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="c_Clave" data-page="1" name="c_Clave" id="c_Clave" value="<?php echo ew_HtmlEncode($t_usuario->Clave->FormValue) ?>">
<?php } ?>
</div></div>
	</div>
<?php } ?>
<?php if ($t_usuario->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_t_usuario__Email" for="x__Email" class="col-sm-2 control-label ewLabel"><?php echo $t_usuario->_Email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_usuario->_Email->CellAttributes() ?>>
<?php if ($t_usuario->CurrentAction <> "F") { ?>
<span id="el_t_usuario__Email">
<input type="text" data-table="t_usuario" data-field="x__Email" data-page="1" name="x__Email" id="x__Email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_usuario->_Email->getPlaceHolder()) ?>" value="<?php echo $t_usuario->_Email->EditValue ?>"<?php echo $t_usuario->_Email->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_t_usuario__Email">
<span<?php echo $t_usuario->_Email->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_usuario->_Email->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_usuario" data-field="x__Email" data-page="1" name="x__Email" id="x__Email" value="<?php echo ew_HtmlEncode($t_usuario->_Email->FormValue) ?>">
<?php } ?>
<?php echo $t_usuario->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<?php if ($t_usuario->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_register.value='F';"><?php echo $Language->Phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_register.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div>
</div>
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">
var txt1 = "REGISTRARSE:";
var txt11 = "Registre los datos solicitados en el formulario";
var txt2 = "USUARIO Y CLAVE: ";
var txt22 = "Proporcione un nombre de usuario: Ejm. juan.perez y una clave Ejm. Perez@2018";
var txt3 = "MENSAJE: ";
var txt33 = "Una vez enviada su solicitud de registro, recibirá en su correo (e-mail) un mensaje de confirmación de su registro";
document.write("<hr/>");
document.write('<img src="phpimages/Ayuda.jpg"/>');
document.write("<p>"+ txt1.bold().fontcolor("green").fontsize(3)+txt11.fontcolor("black").fontsize(3)+"</p>");
document.write("<p>"+ txt2.bold().fontcolor("green").fontsize(3)+txt22.fontcolor("black").fontsize(3)+"</p>");
document.write("<p>"+ txt3.bold().fontcolor("green").fontsize(3)+txt33.fontcolor("black").fontsize(3)+"</p>");
document.write("<hr/>");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
