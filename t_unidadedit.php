<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_unidadinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "t_unidad_organizacionalinfo.php" ?>
<?php include_once "t_cargosgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_unidad_edit = NULL; // Initialize page object first

class ct_unidad_edit extends ct_unidad {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_unidad';

	// Page object name
	var $PageObjName = 't_unidad_edit';

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

		// Table object (t_unidad)
		if (!isset($GLOBALS["t_unidad"]) || get_class($GLOBALS["t_unidad"]) == "ct_unidad") {
			$GLOBALS["t_unidad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_unidad"];
		}

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Table object (t_unidad_organizacional)
		if (!isset($GLOBALS['t_unidad_organizacional'])) $GLOBALS['t_unidad_organizacional'] = new ct_unidad_organizacional();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_unidad', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_unidadlist.php"));
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
		$this->Id_Unidad->SetVisibility();
		$this->Id_Unidad->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->Id_Unidad_Org->SetVisibility();
		$this->Unidad->SetVisibility();
		$this->Ons->SetVisibility();

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

			// Process auto fill for detail table 't_cargos'
			if (@$_POST["grid"] == "ft_cargosgrid") {
				if (!isset($GLOBALS["t_cargos_grid"])) $GLOBALS["t_cargos_grid"] = new ct_cargos_grid;
				$GLOBALS["t_cargos_grid"]->Page_Init();
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
		global $EW_EXPORT, $t_unidad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_unidad);
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
		if (@$_GET["Id_Unidad"] <> "") {
			$this->Id_Unidad->setQueryStringValue($_GET["Id_Unidad"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->Id_Unidad->CurrentValue == "") {
			$this->Page_Terminate("t_unidadlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("t_unidadlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_unidadlist.php")
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

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		if (!$this->Id_Unidad->FldIsDetailKey)
			$this->Id_Unidad->setFormValue($objForm->GetValue("x_Id_Unidad"));
		if (!$this->Id_Unidad_Org->FldIsDetailKey) {
			$this->Id_Unidad_Org->setFormValue($objForm->GetValue("x_Id_Unidad_Org"));
		}
		if (!$this->Unidad->FldIsDetailKey) {
			$this->Unidad->setFormValue($objForm->GetValue("x_Unidad"));
		}
		if (!$this->Ons->FldIsDetailKey) {
			$this->Ons->setFormValue($objForm->GetValue("x_Ons"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Id_Unidad->CurrentValue = $this->Id_Unidad->FormValue;
		$this->Id_Unidad_Org->CurrentValue = $this->Id_Unidad_Org->FormValue;
		$this->Unidad->CurrentValue = $this->Unidad->FormValue;
		$this->Ons->CurrentValue = $this->Ons->FormValue;
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
		$this->Id_Unidad->setDbValue($rs->fields('Id_Unidad'));
		$this->Id_Unidad_Org->setDbValue($rs->fields('Id_Unidad_Org'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->Ons->setDbValue($rs->fields('Ons'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id_Unidad->DbValue = $row['Id_Unidad'];
		$this->Id_Unidad_Org->DbValue = $row['Id_Unidad_Org'];
		$this->Unidad->DbValue = $row['Unidad'];
		$this->Ons->DbValue = $row['Ons'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id_Unidad
		// Id_Unidad_Org
		// Unidad
		// Ons

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id_Unidad
		$this->Id_Unidad->ViewValue = $this->Id_Unidad->CurrentValue;
		$this->Id_Unidad->ViewCustomAttributes = "";

		// Id_Unidad_Org
		$this->Id_Unidad_Org->ViewValue = $this->Id_Unidad_Org->CurrentValue;
		$this->Id_Unidad_Org->ViewCustomAttributes = "";

		// Unidad
		$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
		$this->Unidad->ViewCustomAttributes = "";

		// Ons
		$this->Ons->ViewValue = $this->Ons->CurrentValue;
		$this->Ons->ViewCustomAttributes = "";

			// Id_Unidad
			$this->Id_Unidad->LinkCustomAttributes = "";
			$this->Id_Unidad->HrefValue = "";
			$this->Id_Unidad->TooltipValue = "";

			// Id_Unidad_Org
			$this->Id_Unidad_Org->LinkCustomAttributes = "";
			$this->Id_Unidad_Org->HrefValue = "";
			$this->Id_Unidad_Org->TooltipValue = "";

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";
			$this->Unidad->TooltipValue = "";

			// Ons
			$this->Ons->LinkCustomAttributes = "";
			$this->Ons->HrefValue = "";
			$this->Ons->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id_Unidad
			$this->Id_Unidad->EditAttrs["class"] = "form-control";
			$this->Id_Unidad->EditCustomAttributes = "";
			$this->Id_Unidad->EditValue = $this->Id_Unidad->CurrentValue;
			$this->Id_Unidad->ViewCustomAttributes = "";

			// Id_Unidad_Org
			$this->Id_Unidad_Org->EditAttrs["class"] = "form-control";
			$this->Id_Unidad_Org->EditCustomAttributes = "";
			if ($this->Id_Unidad_Org->getSessionValue() <> "") {
				$this->Id_Unidad_Org->CurrentValue = $this->Id_Unidad_Org->getSessionValue();
			$this->Id_Unidad_Org->ViewValue = $this->Id_Unidad_Org->CurrentValue;
			$this->Id_Unidad_Org->ViewCustomAttributes = "";
			} else {
			$this->Id_Unidad_Org->EditValue = ew_HtmlEncode($this->Id_Unidad_Org->CurrentValue);
			$this->Id_Unidad_Org->PlaceHolder = ew_RemoveHtml($this->Id_Unidad_Org->FldCaption());
			}

			// Unidad
			$this->Unidad->EditAttrs["class"] = "form-control";
			$this->Unidad->EditCustomAttributes = "";
			$this->Unidad->EditValue = ew_HtmlEncode($this->Unidad->CurrentValue);
			$this->Unidad->PlaceHolder = ew_RemoveHtml($this->Unidad->FldCaption());

			// Ons
			$this->Ons->EditAttrs["class"] = "form-control";
			$this->Ons->EditCustomAttributes = "";
			$this->Ons->EditValue = ew_HtmlEncode($this->Ons->CurrentValue);
			$this->Ons->PlaceHolder = ew_RemoveHtml($this->Ons->FldCaption());

			// Edit refer script
			// Id_Unidad

			$this->Id_Unidad->LinkCustomAttributes = "";
			$this->Id_Unidad->HrefValue = "";

			// Id_Unidad_Org
			$this->Id_Unidad_Org->LinkCustomAttributes = "";
			$this->Id_Unidad_Org->HrefValue = "";

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";

			// Ons
			$this->Ons->LinkCustomAttributes = "";
			$this->Ons->HrefValue = "";
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
		if (!$this->Id_Unidad_Org->FldIsDetailKey && !is_null($this->Id_Unidad_Org->FormValue) && $this->Id_Unidad_Org->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id_Unidad_Org->FldCaption(), $this->Id_Unidad_Org->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Id_Unidad_Org->FormValue)) {
			ew_AddMessage($gsFormError, $this->Id_Unidad_Org->FldErrMsg());
		}
		if (!$this->Unidad->FldIsDetailKey && !is_null($this->Unidad->FormValue) && $this->Unidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Unidad->FldCaption(), $this->Unidad->ReqErrMsg));
		}
		if (!$this->Ons->FldIsDetailKey && !is_null($this->Ons->FormValue) && $this->Ons->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Ons->FldCaption(), $this->Ons->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_cargos", $DetailTblVar) && $GLOBALS["t_cargos"]->DetailEdit) {
			if (!isset($GLOBALS["t_cargos_grid"])) $GLOBALS["t_cargos_grid"] = new ct_cargos_grid(); // get detail page object
			$GLOBALS["t_cargos_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Id_Unidad_Org
			$this->Id_Unidad_Org->SetDbValueDef($rsnew, $this->Id_Unidad_Org->CurrentValue, 0, $this->Id_Unidad_Org->ReadOnly);

			// Unidad
			$this->Unidad->SetDbValueDef($rsnew, $this->Unidad->CurrentValue, NULL, $this->Unidad->ReadOnly);

			// Ons
			$this->Ons->SetDbValueDef($rsnew, $this->Ons->CurrentValue, "", $this->Ons->ReadOnly);

			// Check referential integrity for master table 't_unidad_organizacional'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_t_unidad_organizacional();
			$KeyValue = isset($rsnew['Id_Unidad_Org']) ? $rsnew['Id_Unidad_Org'] : $rsold['Id_Unidad_Org'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id_Unidad_Org@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["t_unidad_organizacional"])) $GLOBALS["t_unidad_organizacional"] = new ct_unidad_organizacional();
				$rsmaster = $GLOBALS["t_unidad_organizacional"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "t_unidad_organizacional", $Language->Phrase("RelatedRecordRequired"));
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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("t_cargos", $DetailTblVar) && $GLOBALS["t_cargos"]->DetailEdit) {
						if (!isset($GLOBALS["t_cargos_grid"])) $GLOBALS["t_cargos_grid"] = new ct_cargos_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_cargos"); // Load user level of detail table
						$EditRow = $GLOBALS["t_cargos_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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
			if ($sMasterTblVar == "t_unidad_organizacional") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_Id_Unidad_Org"] <> "") {
					$GLOBALS["t_unidad_organizacional"]->Id_Unidad_Org->setQueryStringValue($_GET["fk_Id_Unidad_Org"]);
					$this->Id_Unidad_Org->setQueryStringValue($GLOBALS["t_unidad_organizacional"]->Id_Unidad_Org->QueryStringValue);
					$this->Id_Unidad_Org->setSessionValue($this->Id_Unidad_Org->QueryStringValue);
					if (!is_numeric($GLOBALS["t_unidad_organizacional"]->Id_Unidad_Org->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "t_unidad_organizacional") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_Id_Unidad_Org"] <> "") {
					$GLOBALS["t_unidad_organizacional"]->Id_Unidad_Org->setFormValue($_POST["fk_Id_Unidad_Org"]);
					$this->Id_Unidad_Org->setFormValue($GLOBALS["t_unidad_organizacional"]->Id_Unidad_Org->FormValue);
					$this->Id_Unidad_Org->setSessionValue($this->Id_Unidad_Org->FormValue);
					if (!is_numeric($GLOBALS["t_unidad_organizacional"]->Id_Unidad_Org->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "t_unidad_organizacional") {
				if ($this->Id_Unidad_Org->CurrentValue == "") $this->Id_Unidad_Org->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("t_cargos", $DetailTblVar)) {
				if (!isset($GLOBALS["t_cargos_grid"]))
					$GLOBALS["t_cargos_grid"] = new ct_cargos_grid;
				if ($GLOBALS["t_cargos_grid"]->DetailEdit) {
					$GLOBALS["t_cargos_grid"]->CurrentMode = "edit";
					$GLOBALS["t_cargos_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_cargos_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_cargos_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_cargos_grid"]->Id_Unidad->FldIsDetailKey = TRUE;
					$GLOBALS["t_cargos_grid"]->Id_Unidad->CurrentValue = $this->Id_Unidad->CurrentValue;
					$GLOBALS["t_cargos_grid"]->Id_Unidad->setSessionValue($GLOBALS["t_cargos_grid"]->Id_Unidad->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_unidadlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($t_unidad_edit)) $t_unidad_edit = new ct_unidad_edit();

// Page init
$t_unidad_edit->Page_Init();

// Page main
$t_unidad_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_unidad_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_unidadedit = new ew_Form("ft_unidadedit", "edit");

// Validate form
ft_unidadedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Id_Unidad_Org");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_unidad->Id_Unidad_Org->FldCaption(), $t_unidad->Id_Unidad_Org->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id_Unidad_Org");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_unidad->Id_Unidad_Org->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Unidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_unidad->Unidad->FldCaption(), $t_unidad->Unidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Ons");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_unidad->Ons->FldCaption(), $t_unidad->Ons->ReqErrMsg)) ?>");

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
ft_unidadedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_unidadedit.ValidateRequired = true;
<?php } else { ?>
ft_unidadedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_unidad_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_unidad_edit->ShowPageHeader(); ?>
<?php
$t_unidad_edit->ShowMessage();
?>
<form name="ft_unidadedit" id="ft_unidadedit" class="<?php echo $t_unidad_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_unidad_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_unidad_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_unidad">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_unidad_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_unidad->getCurrentMasterTable() == "t_unidad_organizacional") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_unidad_organizacional">
<input type="hidden" name="fk_Id_Unidad_Org" value="<?php echo $t_unidad->Id_Unidad_Org->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_unidad->Id_Unidad->Visible) { // Id_Unidad ?>
	<div id="r_Id_Unidad" class="form-group">
		<label id="elh_t_unidad_Id_Unidad" class="col-sm-2 control-label ewLabel"><?php echo $t_unidad->Id_Unidad->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_unidad->Id_Unidad->CellAttributes() ?>>
<span id="el_t_unidad_Id_Unidad">
<span<?php echo $t_unidad->Id_Unidad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_unidad->Id_Unidad->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_unidad" data-field="x_Id_Unidad" data-page="1" name="x_Id_Unidad" id="x_Id_Unidad" value="<?php echo ew_HtmlEncode($t_unidad->Id_Unidad->CurrentValue) ?>">
<?php echo $t_unidad->Id_Unidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_unidad->Id_Unidad_Org->Visible) { // Id_Unidad_Org ?>
	<div id="r_Id_Unidad_Org" class="form-group">
		<label id="elh_t_unidad_Id_Unidad_Org" for="x_Id_Unidad_Org" class="col-sm-2 control-label ewLabel"><?php echo $t_unidad->Id_Unidad_Org->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_unidad->Id_Unidad_Org->CellAttributes() ?>>
<?php if ($t_unidad->Id_Unidad_Org->getSessionValue() <> "") { ?>
<span id="el_t_unidad_Id_Unidad_Org">
<span<?php echo $t_unidad->Id_Unidad_Org->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_unidad->Id_Unidad_Org->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_Id_Unidad_Org" name="x_Id_Unidad_Org" value="<?php echo ew_HtmlEncode($t_unidad->Id_Unidad_Org->CurrentValue) ?>">
<?php } else { ?>
<span id="el_t_unidad_Id_Unidad_Org">
<input type="text" data-table="t_unidad" data-field="x_Id_Unidad_Org" data-page="1" name="x_Id_Unidad_Org" id="x_Id_Unidad_Org" size="30" placeholder="<?php echo ew_HtmlEncode($t_unidad->Id_Unidad_Org->getPlaceHolder()) ?>" value="<?php echo $t_unidad->Id_Unidad_Org->EditValue ?>"<?php echo $t_unidad->Id_Unidad_Org->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $t_unidad->Id_Unidad_Org->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_unidad->Unidad->Visible) { // Unidad ?>
	<div id="r_Unidad" class="form-group">
		<label id="elh_t_unidad_Unidad" for="x_Unidad" class="col-sm-2 control-label ewLabel"><?php echo $t_unidad->Unidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_unidad->Unidad->CellAttributes() ?>>
<span id="el_t_unidad_Unidad">
<input type="text" data-table="t_unidad" data-field="x_Unidad" data-page="1" name="x_Unidad" id="x_Unidad" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_unidad->Unidad->getPlaceHolder()) ?>" value="<?php echo $t_unidad->Unidad->EditValue ?>"<?php echo $t_unidad->Unidad->EditAttributes() ?>>
</span>
<?php echo $t_unidad->Unidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_unidad->Ons->Visible) { // Ons ?>
	<div id="r_Ons" class="form-group">
		<label id="elh_t_unidad_Ons" for="x_Ons" class="col-sm-2 control-label ewLabel"><?php echo $t_unidad->Ons->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_unidad->Ons->CellAttributes() ?>>
<span id="el_t_unidad_Ons">
<input type="text" data-table="t_unidad" data-field="x_Ons" data-page="1" name="x_Ons" id="x_Ons" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_unidad->Ons->getPlaceHolder()) ?>" value="<?php echo $t_unidad->Ons->EditValue ?>"<?php echo $t_unidad->Ons->EditAttributes() ?>>
</span>
<?php echo $t_unidad->Ons->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("t_cargos", explode(",", $t_unidad->getCurrentDetailTable())) && $t_cargos->DetailEdit) {
?>
<?php if ($t_unidad->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("t_cargos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "t_cargosgrid.php" ?>
<?php } ?>
<?php if (!$t_unidad_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_unidad_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_unidadedit.Init();
</script>
<?php
$t_unidad_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_unidad_edit->Page_Terminate();
?>
