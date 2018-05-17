<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_denunciasinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_denuncias_add = NULL; // Initialize page object first

class ct_denuncias_add extends ct_denuncias {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_denuncias';

	// Page object name
	var $PageObjName = 't_denuncias_add';

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

		// Table object (t_denuncias)
		if (!isset($GLOBALS["t_denuncias"]) || get_class($GLOBALS["t_denuncias"]) == "ct_denuncias") {
			$GLOBALS["t_denuncias"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_denuncias"];
		}

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_denuncias', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_denunciaslist.php"));
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
		$this->Fecha_denuncia->SetVisibility();
		$this->Nombres_Apellidos->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Detalles->SetVisibility();

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
		global $EW_EXPORT, $t_denuncias;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_denuncias);
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
			if (@$_GET["CI_RUN"] != "") {
				$this->CI_RUN->setQueryStringValue($_GET["CI_RUN"]);
				$this->setKey("CI_RUN", $this->CI_RUN->CurrentValue); // Set up key
			} else {
				$this->setKey("CI_RUN", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["Fecha_denuncia"] != "") {
				$this->Fecha_denuncia->setQueryStringValue($_GET["Fecha_denuncia"]);
				$this->setKey("Fecha_denuncia", $this->Fecha_denuncia->CurrentValue); // Set up key
			} else {
				$this->setKey("Fecha_denuncia", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["Nombres_Apellidos"] != "") {
				$this->Nombres_Apellidos->setQueryStringValue($_GET["Nombres_Apellidos"]);
				$this->setKey("Nombres_Apellidos", $this->Nombres_Apellidos->CurrentValue); // Set up key
			} else {
				$this->setKey("Nombres_Apellidos", ""); // Clear key
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
					$this->Page_Terminate("t_denunciaslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_denunciaslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_denunciasview.php")
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
		$this->CI_RUN->CurrentValue = NULL;
		$this->CI_RUN->OldValue = $this->CI_RUN->CurrentValue;
		$this->Fecha_denuncia->CurrentValue = NULL;
		$this->Fecha_denuncia->OldValue = $this->Fecha_denuncia->CurrentValue;
		$this->Nombres_Apellidos->CurrentValue = NULL;
		$this->Nombres_Apellidos->OldValue = $this->Nombres_Apellidos->CurrentValue;
		$this->Unidad_Organizacional->CurrentValue = NULL;
		$this->Unidad_Organizacional->OldValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Detalles->CurrentValue = NULL;
		$this->Detalles->OldValue = $this->Detalles->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->CI_RUN->FldIsDetailKey) {
			$this->CI_RUN->setFormValue($objForm->GetValue("x_CI_RUN"));
		}
		if (!$this->Fecha_denuncia->FldIsDetailKey) {
			$this->Fecha_denuncia->setFormValue($objForm->GetValue("x_Fecha_denuncia"));
			$this->Fecha_denuncia->CurrentValue = ew_UnFormatDateTime($this->Fecha_denuncia->CurrentValue, 0);
		}
		if (!$this->Nombres_Apellidos->FldIsDetailKey) {
			$this->Nombres_Apellidos->setFormValue($objForm->GetValue("x_Nombres_Apellidos"));
		}
		if (!$this->Unidad_Organizacional->FldIsDetailKey) {
			$this->Unidad_Organizacional->setFormValue($objForm->GetValue("x_Unidad_Organizacional"));
		}
		if (!$this->Detalles->FldIsDetailKey) {
			$this->Detalles->setFormValue($objForm->GetValue("x_Detalles"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->CI_RUN->CurrentValue = $this->CI_RUN->FormValue;
		$this->Fecha_denuncia->CurrentValue = $this->Fecha_denuncia->FormValue;
		$this->Fecha_denuncia->CurrentValue = ew_UnFormatDateTime($this->Fecha_denuncia->CurrentValue, 0);
		$this->Nombres_Apellidos->CurrentValue = $this->Nombres_Apellidos->FormValue;
		$this->Unidad_Organizacional->CurrentValue = $this->Unidad_Organizacional->FormValue;
		$this->Detalles->CurrentValue = $this->Detalles->FormValue;
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
		$this->Fecha_denuncia->setDbValue($rs->fields('Fecha_denuncia'));
		$this->Nombres_Apellidos->setDbValue($rs->fields('Nombres_Apellidos'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Detalles->setDbValue($rs->fields('Detalles'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Fecha_denuncia->DbValue = $row['Fecha_denuncia'];
		$this->Nombres_Apellidos->DbValue = $row['Nombres_Apellidos'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
		$this->Detalles->DbValue = $row['Detalles'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("CI_RUN")) <> "")
			$this->CI_RUN->CurrentValue = $this->getKey("CI_RUN"); // CI_RUN
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Fecha_denuncia")) <> "")
			$this->Fecha_denuncia->CurrentValue = $this->getKey("Fecha_denuncia"); // Fecha_denuncia
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Nombres_Apellidos")) <> "")
			$this->Nombres_Apellidos->CurrentValue = $this->getKey("Nombres_Apellidos"); // Nombres_Apellidos
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
		// CI_RUN
		// Fecha_denuncia
		// Nombres_Apellidos
		// Unidad_Organizacional
		// Detalles

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewCustomAttributes = "";

		// Fecha_denuncia
		$this->Fecha_denuncia->ViewValue = $this->Fecha_denuncia->CurrentValue;
		$this->Fecha_denuncia->ViewValue = ew_FormatDateTime($this->Fecha_denuncia->ViewValue, 0);
		$this->Fecha_denuncia->ViewCustomAttributes = "";

		// Nombres_Apellidos
		$this->Nombres_Apellidos->ViewValue = $this->Nombres_Apellidos->CurrentValue;
		$this->Nombres_Apellidos->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Detalles
		$this->Detalles->ViewValue = $this->Detalles->CurrentValue;
		$this->Detalles->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

			// Fecha_denuncia
			$this->Fecha_denuncia->LinkCustomAttributes = "";
			$this->Fecha_denuncia->HrefValue = "";
			$this->Fecha_denuncia->TooltipValue = "";

			// Nombres_Apellidos
			$this->Nombres_Apellidos->LinkCustomAttributes = "";
			$this->Nombres_Apellidos->HrefValue = "";
			$this->Nombres_Apellidos->TooltipValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";

			// Detalles
			$this->Detalles->LinkCustomAttributes = "";
			$this->Detalles->HrefValue = "";
			$this->Detalles->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// CI_RUN
			// Fecha_denuncia
			// Nombres_Apellidos

			$this->Nombres_Apellidos->EditAttrs["class"] = "form-control";
			$this->Nombres_Apellidos->EditCustomAttributes = "";
			$this->Nombres_Apellidos->EditValue = ew_HtmlEncode($this->Nombres_Apellidos->CurrentValue);
			$this->Nombres_Apellidos->PlaceHolder = ew_RemoveHtml($this->Nombres_Apellidos->FldCaption());

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			$this->Unidad_Organizacional->EditValue = ew_HtmlEncode($this->Unidad_Organizacional->CurrentValue);
			$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

			// Detalles
			$this->Detalles->EditAttrs["class"] = "form-control";
			$this->Detalles->EditCustomAttributes = "";
			$this->Detalles->EditValue = ew_HtmlEncode($this->Detalles->CurrentValue);
			$this->Detalles->PlaceHolder = ew_RemoveHtml($this->Detalles->FldCaption());

			// Add refer script
			// CI_RUN

			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";

			// Fecha_denuncia
			$this->Fecha_denuncia->LinkCustomAttributes = "";
			$this->Fecha_denuncia->HrefValue = "";

			// Nombres_Apellidos
			$this->Nombres_Apellidos->LinkCustomAttributes = "";
			$this->Nombres_Apellidos->HrefValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";

			// Detalles
			$this->Detalles->LinkCustomAttributes = "";
			$this->Detalles->HrefValue = "";
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
		if (!$this->Nombres_Apellidos->FldIsDetailKey && !is_null($this->Nombres_Apellidos->FormValue) && $this->Nombres_Apellidos->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nombres_Apellidos->FldCaption(), $this->Nombres_Apellidos->ReqErrMsg));
		}
		if (!$this->Detalles->FldIsDetailKey && !is_null($this->Detalles->FormValue) && $this->Detalles->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Detalles->FldCaption(), $this->Detalles->ReqErrMsg));
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
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// CI_RUN
		$this->CI_RUN->SetDbValueDef($rsnew, CurrentUserID(), "");
		$rsnew['CI_RUN'] = &$this->CI_RUN->DbValue;

		// Fecha_denuncia
		$this->Fecha_denuncia->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['Fecha_denuncia'] = &$this->Fecha_denuncia->DbValue;

		// Nombres_Apellidos
		$this->Nombres_Apellidos->SetDbValueDef($rsnew, $this->Nombres_Apellidos->CurrentValue, "", FALSE);

		// Unidad_Organizacional
		$this->Unidad_Organizacional->SetDbValueDef($rsnew, $this->Unidad_Organizacional->CurrentValue, NULL, FALSE);

		// Detalles
		$this->Detalles->SetDbValueDef($rsnew, $this->Detalles->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['CI_RUN']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Fecha_denuncia']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Nombres_Apellidos']) == "") {
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
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_denunciaslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($t_denuncias_add)) $t_denuncias_add = new ct_denuncias_add();

// Page init
$t_denuncias_add->Page_Init();

// Page main
$t_denuncias_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_denuncias_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_denunciasadd = new ew_Form("ft_denunciasadd", "add");

// Validate form
ft_denunciasadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Nombres_Apellidos");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_denuncias->Nombres_Apellidos->FldCaption(), $t_denuncias->Nombres_Apellidos->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Detalles");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_denuncias->Detalles->FldCaption(), $t_denuncias->Detalles->ReqErrMsg)) ?>");

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
ft_denunciasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_denunciasadd.ValidateRequired = true;
<?php } else { ?>
ft_denunciasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_denuncias_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_denuncias_add->ShowPageHeader(); ?>
<?php
$t_denuncias_add->ShowMessage();
?>
<form name="ft_denunciasadd" id="ft_denunciasadd" class="<?php echo $t_denuncias_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_denuncias_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_denuncias_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_denuncias">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_denuncias_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_denuncias->Nombres_Apellidos->Visible) { // Nombres_Apellidos ?>
	<div id="r_Nombres_Apellidos" class="form-group">
		<label id="elh_t_denuncias_Nombres_Apellidos" for="x_Nombres_Apellidos" class="col-sm-2 control-label ewLabel"><?php echo $t_denuncias->Nombres_Apellidos->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_denuncias->Nombres_Apellidos->CellAttributes() ?>>
<span id="el_t_denuncias_Nombres_Apellidos">
<input type="text" data-table="t_denuncias" data-field="x_Nombres_Apellidos" name="x_Nombres_Apellidos" id="x_Nombres_Apellidos" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($t_denuncias->Nombres_Apellidos->getPlaceHolder()) ?>" value="<?php echo $t_denuncias->Nombres_Apellidos->EditValue ?>"<?php echo $t_denuncias->Nombres_Apellidos->EditAttributes() ?>>
</span>
<?php echo $t_denuncias->Nombres_Apellidos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_denuncias->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label id="elh_t_denuncias_Unidad_Organizacional" for="x_Unidad_Organizacional" class="col-sm-2 control-label ewLabel"><?php echo $t_denuncias->Unidad_Organizacional->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_denuncias->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_denuncias_Unidad_Organizacional">
<input type="text" data-table="t_denuncias" data-field="x_Unidad_Organizacional" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_denuncias->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $t_denuncias->Unidad_Organizacional->EditValue ?>"<?php echo $t_denuncias->Unidad_Organizacional->EditAttributes() ?>>
</span>
<?php echo $t_denuncias->Unidad_Organizacional->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_denuncias->Detalles->Visible) { // Detalles ?>
	<div id="r_Detalles" class="form-group">
		<label id="elh_t_denuncias_Detalles" for="x_Detalles" class="col-sm-2 control-label ewLabel"><?php echo $t_denuncias->Detalles->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_denuncias->Detalles->CellAttributes() ?>>
<span id="el_t_denuncias_Detalles">
<textarea data-table="t_denuncias" data-field="x_Detalles" name="x_Detalles" id="x_Detalles" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($t_denuncias->Detalles->getPlaceHolder()) ?>"<?php echo $t_denuncias->Detalles->EditAttributes() ?>><?php echo $t_denuncias->Detalles->EditValue ?></textarea>
</span>
<?php echo $t_denuncias->Detalles->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_denuncias_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_denuncias_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_denunciasadd.Init();
</script>
<?php
$t_denuncias_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_denuncias_add->Page_Terminate();
?>
