<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_pa_afinidadinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_pa_afinidad_add = NULL; // Initialize page object first

class ct_pa_afinidad_add extends ct_pa_afinidad {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_pa_afinidad';

	// Page object name
	var $PageObjName = 't_pa_afinidad_add';

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

		// Table object (t_pa_afinidad)
		if (!isset($GLOBALS["t_pa_afinidad"]) || get_class($GLOBALS["t_pa_afinidad"]) == "ct_pa_afinidad") {
			$GLOBALS["t_pa_afinidad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_pa_afinidad"];
		}

		// Table object (t_funcionario)
		if (!isset($GLOBALS['t_funcionario'])) $GLOBALS['t_funcionario'] = new ct_funcionario();

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_pa_afinidad', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_pa_afinidadlist.php"));
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
		$this->Nombre->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Grado_Parentesco->SetVisibility();

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
		global $EW_EXPORT, $t_pa_afinidad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_pa_afinidad);
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["Nombre"] != "") {
				$this->Nombre->setQueryStringValue($_GET["Nombre"]);
				$this->setKey("Nombre", $this->Nombre->CurrentValue); // Set up key
			} else {
				$this->setKey("Nombre", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["Apellido_Paterno"] != "") {
				$this->Apellido_Paterno->setQueryStringValue($_GET["Apellido_Paterno"]);
				$this->setKey("Apellido_Paterno", $this->Apellido_Paterno->CurrentValue); // Set up key
			} else {
				$this->setKey("Apellido_Paterno", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["Apellido_Materno"] != "") {
				$this->Apellido_Materno->setQueryStringValue($_GET["Apellido_Materno"]);
				$this->setKey("Apellido_Materno", $this->Apellido_Materno->CurrentValue); // Set up key
			} else {
				$this->setKey("Apellido_Materno", ""); // Clear key
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
					$this->Page_Terminate("t_pa_afinidadlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_pa_afinidadlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_pa_afinidadview.php")
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
		$this->Nombre->CurrentValue = NULL;
		$this->Nombre->OldValue = $this->Nombre->CurrentValue;
		$this->Apellido_Paterno->CurrentValue = NULL;
		$this->Apellido_Paterno->OldValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Materno->CurrentValue = NULL;
		$this->Apellido_Materno->OldValue = $this->Apellido_Materno->CurrentValue;
		$this->Grado_Parentesco->CurrentValue = NULL;
		$this->Grado_Parentesco->OldValue = $this->Grado_Parentesco->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Nombre->FldIsDetailKey) {
			$this->Nombre->setFormValue($objForm->GetValue("x_Nombre"));
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Nombre->CurrentValue = $this->Nombre->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Grado_Parentesco->CurrentValue = $this->Grado_Parentesco->FormValue;
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
		$this->Nombre->setDbValue($rs->fields('Nombre'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Grado_Parentesco->setDbValue($rs->fields('Grado_Parentesco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->Nombre->DbValue = $row['Nombre'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Grado_Parentesco->DbValue = $row['Grado_Parentesco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Nombre")) <> "")
			$this->Nombre->CurrentValue = $this->getKey("Nombre"); // Nombre
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Apellido_Paterno")) <> "")
			$this->Apellido_Paterno->CurrentValue = $this->getKey("Apellido_Paterno"); // Apellido_Paterno
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Apellido_Materno")) <> "")
			$this->Apellido_Materno->CurrentValue = $this->getKey("Apellido_Materno"); // Apellido_Materno
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
		// Id
		// Nombre
		// Apellido_Paterno
		// Apellido_Materno
		// Grado_Parentesco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Nombre
		$this->Nombre->ViewValue = $this->Nombre->CurrentValue;
		$this->Nombre->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Grado_Parentesco
		if (strval($this->Grado_Parentesco->CurrentValue) <> "") {
			$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Grado_Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_afinidad`";
		$sWhereWrk = "";
		$this->Grado_Parentesco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->CurrentValue;
			}
		} else {
			$this->Grado_Parentesco->ViewValue = NULL;
		}
		$this->Grado_Parentesco->ViewCustomAttributes = "";

			// Nombre
			$this->Nombre->LinkCustomAttributes = "";
			$this->Nombre->HrefValue = "";
			$this->Nombre->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Nombre
			$this->Nombre->EditAttrs["class"] = "form-control";
			$this->Nombre->EditCustomAttributes = "";
			$this->Nombre->EditValue = ew_HtmlEncode($this->Nombre->CurrentValue);
			$this->Nombre->PlaceHolder = ew_RemoveHtml($this->Nombre->FldCaption());

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

			// Grado_Parentesco
			$this->Grado_Parentesco->EditAttrs["class"] = "form-control";
			$this->Grado_Parentesco->EditCustomAttributes = "";
			if (trim(strval($this->Grado_Parentesco->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Grado_Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `s_afinidad`";
			$sWhereWrk = "";
			$this->Grado_Parentesco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Grado_Parentesco->EditValue = $arwrk;

			// Add refer script
			// Nombre

			$this->Nombre->LinkCustomAttributes = "";
			$this->Nombre->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Grado_Parentesco
			$this->Grado_Parentesco->LinkCustomAttributes = "";
			$this->Grado_Parentesco->HrefValue = "";
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check referential integrity for master table 't_funcionario'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_t_funcionario();
		if ($this->Id->getSessionValue() <> "") {
			$sMasterFilter = str_replace("@Id@", ew_AdjustSql($this->Id->getSessionValue(), "DB"), $sMasterFilter);
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
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Nombre
		$this->Nombre->SetDbValueDef($rsnew, $this->Nombre->CurrentValue, "", FALSE);

		// Apellido_Paterno
		$this->Apellido_Paterno->SetDbValueDef($rsnew, $this->Apellido_Paterno->CurrentValue, "", FALSE);

		// Apellido_Materno
		$this->Apellido_Materno->SetDbValueDef($rsnew, $this->Apellido_Materno->CurrentValue, "", FALSE);

		// Grado_Parentesco
		$this->Grado_Parentesco->SetDbValueDef($rsnew, $this->Grado_Parentesco->CurrentValue, "", FALSE);

		// Id
		if ($this->Id->getSessionValue() <> "") {
			$rsnew['Id'] = $this->Id->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Nombre']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Apellido_Paterno']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Apellido_Materno']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_pa_afinidadlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Grado_Parentesco":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Parentesco` AS `LinkFld`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_afinidad`";
			$sWhereWrk = "";
			$this->Grado_Parentesco->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Parentesco` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_pa_afinidad_add)) $t_pa_afinidad_add = new ct_pa_afinidad_add();

// Page init
$t_pa_afinidad_add->Page_Init();

// Page main
$t_pa_afinidad_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pa_afinidad_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_pa_afinidadadd = new ew_Form("ft_pa_afinidadadd", "add");

// Validate form
ft_pa_afinidadadd.Validate = function() {
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
ft_pa_afinidadadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pa_afinidadadd.ValidateRequired = true;
<?php } else { ?>
ft_pa_afinidadadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pa_afinidadadd.Lists["x_Grado_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_afinidad"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_pa_afinidad_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_pa_afinidad_add->ShowPageHeader(); ?>
<?php
$t_pa_afinidad_add->ShowMessage();
?>
<form name="ft_pa_afinidadadd" id="ft_pa_afinidadadd" class="<?php echo $t_pa_afinidad_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_pa_afinidad_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_pa_afinidad_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_pa_afinidad">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_pa_afinidad_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_pa_afinidad->getCurrentMasterTable() == "t_funcionario") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_funcionario">
<input type="hidden" name="fk_Id" value="<?php echo $t_pa_afinidad->Id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
	<div id="r_Nombre" class="form-group">
		<label id="elh_t_pa_afinidad_Nombre" for="x_Nombre" class="col-sm-2 control-label ewLabel"><?php echo $t_pa_afinidad->Nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pa_afinidad->Nombre->CellAttributes() ?>>
<span id="el_t_pa_afinidad_Nombre">
<input type="text" data-table="t_pa_afinidad" data-field="x_Nombre" data-page="1" name="x_Nombre" id="x_Nombre" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Nombre->EditValue ?>"<?php echo $t_pa_afinidad->Nombre->EditAttributes() ?>>
</span>
<?php echo $t_pa_afinidad->Nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label id="elh_t_pa_afinidad_Apellido_Paterno" for="x_Apellido_Paterno" class="col-sm-2 control-label ewLabel"><?php echo $t_pa_afinidad->Apellido_Paterno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pa_afinidad->Apellido_Paterno->CellAttributes() ?>>
<span id="el_t_pa_afinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" data-page="1" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php echo $t_pa_afinidad->Apellido_Paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label id="elh_t_pa_afinidad_Apellido_Materno" for="x_Apellido_Materno" class="col-sm-2 control-label ewLabel"><?php echo $t_pa_afinidad->Apellido_Materno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pa_afinidad->Apellido_Materno->CellAttributes() ?>>
<span id="el_t_pa_afinidad_Apellido_Materno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" data-page="1" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<?php echo $t_pa_afinidad->Apellido_Materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
	<div id="r_Grado_Parentesco" class="form-group">
		<label id="elh_t_pa_afinidad_Grado_Parentesco" for="x_Grado_Parentesco" class="col-sm-2 control-label ewLabel"><?php echo $t_pa_afinidad->Grado_Parentesco->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_pa_afinidad->Grado_Parentesco->CellAttributes() ?>>
<span id="el_t_pa_afinidad_Grado_Parentesco">
<select data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" data-page="1" data-value-separator="<?php echo $t_pa_afinidad->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x_Grado_Parentesco" name="x_Grado_Parentesco"<?php echo $t_pa_afinidad->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->SelectOptionListHtml("x_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x_Grado_Parentesco" id="s_x_Grado_Parentesco" value="<?php echo $t_pa_afinidad->Grado_Parentesco->LookupFilterQuery() ?>">
</span>
<?php echo $t_pa_afinidad->Grado_Parentesco->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (strval($t_pa_afinidad->Id->getSessionValue()) <> "") { ?>
<input type="hidden" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode(strval($t_pa_afinidad->Id->getSessionValue())) ?>">
<?php } ?>
<?php if (!$t_pa_afinidad_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_pa_afinidad_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_pa_afinidadadd.Init();
</script>
<?php
$t_pa_afinidad_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_pa_afinidad_add->Page_Terminate();
?>
