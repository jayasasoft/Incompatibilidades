<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "conyugueinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$conyugue_view = NULL; // Initialize page object first

class cconyugue_view extends cconyugue {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'conyugue';

	// Page object name
	var $PageObjName = 'conyugue_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (conyugue)
		if (!isset($GLOBALS["conyugue"]) || get_class($GLOBALS["conyugue"]) == "cconyugue") {
			$GLOBALS["conyugue"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["conyugue"];
		}
		$KeyUrl = "";
		if (@$_GET["CI_RUN1"] <> "") {
			$this->RecKey["CI_RUN1"] = $_GET["CI_RUN1"];
			$KeyUrl .= "&amp;CI_RUN1=" . urlencode($this->RecKey["CI_RUN1"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'conyugue', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_usuario)
		if (!isset($UserTable)) {
			$UserTable = new ct_usuario();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("conyuguelist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["CI_RUN1"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["CI_RUN1"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
		$this->CI_RUN->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Nombres->SetVisibility();
		$this->Telefono->SetVisibility();
		$this->Celular->SetVisibility();
		$this->Direccion->SetVisibility();
		$this->Fiscalia_otro->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Cargo->SetVisibility();
		$this->Unidad->SetVisibility();
		$this->CI_RUN1->SetVisibility();
		$this->Expedido->SetVisibility();
		$this->Apellido_Paterno1->SetVisibility();
		$this->Apellido_Materno1->SetVisibility();
		$this->Nombres1->SetVisibility();
		$this->Direccion1->SetVisibility();

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
		global $EW_EXPORT, $conyugue;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($conyugue);
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["CI_RUN1"] <> "") {
				$this->CI_RUN1->setQueryStringValue($_GET["CI_RUN1"]);
				$this->RecKey["CI_RUN1"] = $this->CI_RUN1->QueryStringValue;
			} elseif (@$_POST["CI_RUN1"] <> "") {
				$this->CI_RUN1->setFormValue($_POST["CI_RUN1"]);
				$this->RecKey["CI_RUN1"] = $this->CI_RUN1->FormValue;
			} else {
				$sReturnUrl = "conyuguelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "conyuguelist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "conyuguelist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->CI_RUN->setDbValue($rs->fields('CI_RUN'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Telefono->setDbValue($rs->fields('Telefono'));
		$this->Celular->setDbValue($rs->fields('Celular'));
		$this->Direccion->setDbValue($rs->fields('Direccion'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
		if (array_key_exists('EV__Cargo', $rs->fields)) {
			$this->Cargo->VirtualValue = $rs->fields('EV__Cargo'); // Set up virtual field value
		} else {
			$this->Cargo->VirtualValue = ""; // Clear value
		}
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		if (array_key_exists('EV__Unidad', $rs->fields)) {
			$this->Unidad->VirtualValue = $rs->fields('EV__Unidad'); // Set up virtual field value
		} else {
			$this->Unidad->VirtualValue = ""; // Clear value
		}
		$this->CI_RUN1->setDbValue($rs->fields('CI_RUN1'));
		$this->Expedido->setDbValue($rs->fields('Expedido'));
		$this->Apellido_Paterno1->setDbValue($rs->fields('Apellido_Paterno1'));
		$this->Apellido_Materno1->setDbValue($rs->fields('Apellido_Materno1'));
		$this->Nombres1->setDbValue($rs->fields('Nombres1'));
		$this->Direccion1->setDbValue($rs->fields('Direccion1'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Telefono->DbValue = $row['Telefono'];
		$this->Celular->DbValue = $row['Celular'];
		$this->Direccion->DbValue = $row['Direccion'];
		$this->Fiscalia_otro->DbValue = $row['Fiscalia_otro'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
		$this->Cargo->DbValue = $row['Cargo'];
		$this->Unidad->DbValue = $row['Unidad'];
		$this->CI_RUN1->DbValue = $row['CI_RUN1'];
		$this->Expedido->DbValue = $row['Expedido'];
		$this->Apellido_Paterno1->DbValue = $row['Apellido_Paterno1'];
		$this->Apellido_Materno1->DbValue = $row['Apellido_Materno1'];
		$this->Nombres1->DbValue = $row['Nombres1'];
		$this->Direccion1->DbValue = $row['Direccion1'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// CI_RUN
		// Apellido_Paterno
		// Apellido_Materno
		// Nombres
		// Telefono
		// Celular
		// Direccion
		// Fiscalia_otro
		// Unidad_Organizacional
		// Cargo
		// Unidad
		// CI_RUN1
		// Expedido
		// Apellido_Paterno1
		// Apellido_Materno1
		// Nombres1
		// Direccion1

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewValue = ew_FormatNumber($this->CI_RUN->ViewValue, 0, 0, 0, 0);
		$this->CI_RUN->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Telefono
		$this->Telefono->ViewValue = $this->Telefono->CurrentValue;
		$this->Telefono->ViewCustomAttributes = "";

		// Celular
		$this->Celular->ViewValue = $this->Celular->CurrentValue;
		$this->Celular->ViewCustomAttributes = "";

		// Direccion
		$this->Direccion->ViewValue = $this->Direccion->CurrentValue;
		$this->Direccion->ViewCustomAttributes = "";

		// Fiscalia_otro
		$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Cargo
		if ($this->Cargo->VirtualValue <> "") {
			$this->Cargo->ViewValue = $this->Cargo->VirtualValue;
		} else {
		if (strval($this->Cargo->CurrentValue) <> "") {
			$sFilterWrk = "`Cargo`" . ew_SearchString("=", $this->Cargo->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Cargo`, `Cargo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_cargos`";
		$sWhereWrk = "";
		$this->Cargo->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Cargo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Cargo->ViewValue = $this->Cargo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Cargo->ViewValue = $this->Cargo->CurrentValue;
			}
		} else {
			$this->Cargo->ViewValue = NULL;
		}
		}
		$this->Cargo->ViewCustomAttributes = "";

		// Unidad
		if ($this->Unidad->VirtualValue <> "") {
			$this->Unidad->ViewValue = $this->Unidad->VirtualValue;
		} else {
		if (strval($this->Unidad->CurrentValue) <> "") {
			$sFilterWrk = "`Unidad`" . ew_SearchString("=", $this->Unidad->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Unidad`, `Unidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_unidad`";
		$sWhereWrk = "";
		$this->Unidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Unidad, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Unidad->ViewValue = $this->Unidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
			}
		} else {
			$this->Unidad->ViewValue = NULL;
		}
		}
		$this->Unidad->ViewCustomAttributes = "";

		// CI_RUN1
		$this->CI_RUN1->ViewValue = $this->CI_RUN1->CurrentValue;
		$this->CI_RUN1->ViewCustomAttributes = "";

		// Expedido
		if (strval($this->Expedido->CurrentValue) <> "") {
			$this->Expedido->ViewValue = $this->Expedido->OptionCaption($this->Expedido->CurrentValue);
		} else {
			$this->Expedido->ViewValue = NULL;
		}
		$this->Expedido->ViewCustomAttributes = "";

		// Apellido_Paterno1
		$this->Apellido_Paterno1->ViewValue = $this->Apellido_Paterno1->CurrentValue;
		$this->Apellido_Paterno1->ViewCustomAttributes = "";

		// Apellido_Materno1
		$this->Apellido_Materno1->ViewValue = $this->Apellido_Materno1->CurrentValue;
		$this->Apellido_Materno1->ViewCustomAttributes = "";

		// Nombres1
		$this->Nombres1->ViewValue = $this->Nombres1->CurrentValue;
		$this->Nombres1->ViewCustomAttributes = "";

		// Direccion1
		$this->Direccion1->ViewValue = $this->Direccion1->CurrentValue;
		$this->Direccion1->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

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

			// Telefono
			$this->Telefono->LinkCustomAttributes = "";
			$this->Telefono->HrefValue = "";
			$this->Telefono->TooltipValue = "";

			// Celular
			$this->Celular->LinkCustomAttributes = "";
			$this->Celular->HrefValue = "";
			$this->Celular->TooltipValue = "";

			// Direccion
			$this->Direccion->LinkCustomAttributes = "";
			$this->Direccion->HrefValue = "";
			$this->Direccion->TooltipValue = "";

			// Fiscalia_otro
			$this->Fiscalia_otro->LinkCustomAttributes = "";
			$this->Fiscalia_otro->HrefValue = "";
			$this->Fiscalia_otro->TooltipValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";

			// Cargo
			$this->Cargo->LinkCustomAttributes = "";
			$this->Cargo->HrefValue = "";
			$this->Cargo->TooltipValue = "";

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";
			$this->Unidad->TooltipValue = "";

			// CI_RUN1
			$this->CI_RUN1->LinkCustomAttributes = "";
			$this->CI_RUN1->HrefValue = "";
			$this->CI_RUN1->TooltipValue = "";

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";
			$this->Expedido->TooltipValue = "";

			// Apellido_Paterno1
			$this->Apellido_Paterno1->LinkCustomAttributes = "";
			$this->Apellido_Paterno1->HrefValue = "";
			$this->Apellido_Paterno1->TooltipValue = "";

			// Apellido_Materno1
			$this->Apellido_Materno1->LinkCustomAttributes = "";
			$this->Apellido_Materno1->HrefValue = "";
			$this->Apellido_Materno1->TooltipValue = "";

			// Nombres1
			$this->Nombres1->LinkCustomAttributes = "";
			$this->Nombres1->HrefValue = "";
			$this->Nombres1->TooltipValue = "";

			// Direccion1
			$this->Direccion1->LinkCustomAttributes = "";
			$this->Direccion1->HrefValue = "";
			$this->Direccion1->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_conyugue\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_conyugue',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fconyugueview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("conyuguelist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($conyugue_view)) $conyugue_view = new cconyugue_view();

// Page init
$conyugue_view->Page_Init();

// Page main
$conyugue_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$conyugue_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($conyugue->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fconyugueview = new ew_Form("fconyugueview", "view");

// Form_CustomValidate event
fconyugueview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconyugueview.ValidateRequired = true;
<?php } else { ?>
fconyugueview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fconyugueview.Lists["x_Cargo"] = {"LinkField":"x_Cargo","Ajax":true,"AutoFill":false,"DisplayFields":["x_Cargo","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_cargos"};
fconyugueview.Lists["x_Unidad"] = {"LinkField":"x_Unidad","Ajax":true,"AutoFill":false,"DisplayFields":["x_Unidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_unidad"};
fconyugueview.Lists["x_Expedido"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fconyugueview.Lists["x_Expedido"].Options = <?php echo json_encode($conyugue->Expedido->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($conyugue->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$conyugue_view->IsModal) { ?>
<?php if ($conyugue->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $conyugue_view->ExportOptions->Render("body") ?>
<?php
	foreach ($conyugue_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$conyugue_view->IsModal) { ?>
<?php if ($conyugue->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $conyugue_view->ShowPageHeader(); ?>
<?php
$conyugue_view->ShowMessage();
?>
<form name="fconyugueview" id="fconyugueview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($conyugue_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $conyugue_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="conyugue">
<?php if ($conyugue_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($conyugue->CI_RUN->Visible) { // CI_RUN ?>
	<tr id="r_CI_RUN">
		<td><span id="elh_conyugue_CI_RUN"><?php echo $conyugue->CI_RUN->FldCaption() ?></span></td>
		<td data-name="CI_RUN"<?php echo $conyugue->CI_RUN->CellAttributes() ?>>
<span id="el_conyugue_CI_RUN">
<span<?php echo $conyugue->CI_RUN->ViewAttributes() ?>>
<?php echo $conyugue->CI_RUN->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<tr id="r_Apellido_Paterno">
		<td><span id="elh_conyugue_Apellido_Paterno"><?php echo $conyugue->Apellido_Paterno->FldCaption() ?></span></td>
		<td data-name="Apellido_Paterno"<?php echo $conyugue->Apellido_Paterno->CellAttributes() ?>>
<span id="el_conyugue_Apellido_Paterno">
<span<?php echo $conyugue->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $conyugue->Apellido_Paterno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<tr id="r_Apellido_Materno">
		<td><span id="elh_conyugue_Apellido_Materno"><?php echo $conyugue->Apellido_Materno->FldCaption() ?></span></td>
		<td data-name="Apellido_Materno"<?php echo $conyugue->Apellido_Materno->CellAttributes() ?>>
<span id="el_conyugue_Apellido_Materno">
<span<?php echo $conyugue->Apellido_Materno->ViewAttributes() ?>>
<?php echo $conyugue->Apellido_Materno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Nombres->Visible) { // Nombres ?>
	<tr id="r_Nombres">
		<td><span id="elh_conyugue_Nombres"><?php echo $conyugue->Nombres->FldCaption() ?></span></td>
		<td data-name="Nombres"<?php echo $conyugue->Nombres->CellAttributes() ?>>
<span id="el_conyugue_Nombres">
<span<?php echo $conyugue->Nombres->ViewAttributes() ?>>
<?php echo $conyugue->Nombres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Telefono->Visible) { // Telefono ?>
	<tr id="r_Telefono">
		<td><span id="elh_conyugue_Telefono"><?php echo $conyugue->Telefono->FldCaption() ?></span></td>
		<td data-name="Telefono"<?php echo $conyugue->Telefono->CellAttributes() ?>>
<span id="el_conyugue_Telefono">
<span<?php echo $conyugue->Telefono->ViewAttributes() ?>>
<?php echo $conyugue->Telefono->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Celular->Visible) { // Celular ?>
	<tr id="r_Celular">
		<td><span id="elh_conyugue_Celular"><?php echo $conyugue->Celular->FldCaption() ?></span></td>
		<td data-name="Celular"<?php echo $conyugue->Celular->CellAttributes() ?>>
<span id="el_conyugue_Celular">
<span<?php echo $conyugue->Celular->ViewAttributes() ?>>
<?php echo $conyugue->Celular->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Direccion->Visible) { // Direccion ?>
	<tr id="r_Direccion">
		<td><span id="elh_conyugue_Direccion"><?php echo $conyugue->Direccion->FldCaption() ?></span></td>
		<td data-name="Direccion"<?php echo $conyugue->Direccion->CellAttributes() ?>>
<span id="el_conyugue_Direccion">
<span<?php echo $conyugue->Direccion->ViewAttributes() ?>>
<?php echo $conyugue->Direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<tr id="r_Fiscalia_otro">
		<td><span id="elh_conyugue_Fiscalia_otro"><?php echo $conyugue->Fiscalia_otro->FldCaption() ?></span></td>
		<td data-name="Fiscalia_otro"<?php echo $conyugue->Fiscalia_otro->CellAttributes() ?>>
<span id="el_conyugue_Fiscalia_otro">
<span<?php echo $conyugue->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $conyugue->Fiscalia_otro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<tr id="r_Unidad_Organizacional">
		<td><span id="elh_conyugue_Unidad_Organizacional"><?php echo $conyugue->Unidad_Organizacional->FldCaption() ?></span></td>
		<td data-name="Unidad_Organizacional"<?php echo $conyugue->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_conyugue_Unidad_Organizacional">
<span<?php echo $conyugue->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $conyugue->Unidad_Organizacional->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Cargo->Visible) { // Cargo ?>
	<tr id="r_Cargo">
		<td><span id="elh_conyugue_Cargo"><?php echo $conyugue->Cargo->FldCaption() ?></span></td>
		<td data-name="Cargo"<?php echo $conyugue->Cargo->CellAttributes() ?>>
<span id="el_conyugue_Cargo">
<span<?php echo $conyugue->Cargo->ViewAttributes() ?>>
<?php echo $conyugue->Cargo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Unidad->Visible) { // Unidad ?>
	<tr id="r_Unidad">
		<td><span id="elh_conyugue_Unidad"><?php echo $conyugue->Unidad->FldCaption() ?></span></td>
		<td data-name="Unidad"<?php echo $conyugue->Unidad->CellAttributes() ?>>
<span id="el_conyugue_Unidad">
<span<?php echo $conyugue->Unidad->ViewAttributes() ?>>
<?php echo $conyugue->Unidad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->CI_RUN1->Visible) { // CI_RUN1 ?>
	<tr id="r_CI_RUN1">
		<td><span id="elh_conyugue_CI_RUN1"><?php echo $conyugue->CI_RUN1->FldCaption() ?></span></td>
		<td data-name="CI_RUN1"<?php echo $conyugue->CI_RUN1->CellAttributes() ?>>
<span id="el_conyugue_CI_RUN1">
<span<?php echo $conyugue->CI_RUN1->ViewAttributes() ?>>
<?php echo $conyugue->CI_RUN1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Expedido->Visible) { // Expedido ?>
	<tr id="r_Expedido">
		<td><span id="elh_conyugue_Expedido"><?php echo $conyugue->Expedido->FldCaption() ?></span></td>
		<td data-name="Expedido"<?php echo $conyugue->Expedido->CellAttributes() ?>>
<span id="el_conyugue_Expedido">
<span<?php echo $conyugue->Expedido->ViewAttributes() ?>>
<?php echo $conyugue->Expedido->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Apellido_Paterno1->Visible) { // Apellido_Paterno1 ?>
	<tr id="r_Apellido_Paterno1">
		<td><span id="elh_conyugue_Apellido_Paterno1"><?php echo $conyugue->Apellido_Paterno1->FldCaption() ?></span></td>
		<td data-name="Apellido_Paterno1"<?php echo $conyugue->Apellido_Paterno1->CellAttributes() ?>>
<span id="el_conyugue_Apellido_Paterno1">
<span<?php echo $conyugue->Apellido_Paterno1->ViewAttributes() ?>>
<?php echo $conyugue->Apellido_Paterno1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Apellido_Materno1->Visible) { // Apellido_Materno1 ?>
	<tr id="r_Apellido_Materno1">
		<td><span id="elh_conyugue_Apellido_Materno1"><?php echo $conyugue->Apellido_Materno1->FldCaption() ?></span></td>
		<td data-name="Apellido_Materno1"<?php echo $conyugue->Apellido_Materno1->CellAttributes() ?>>
<span id="el_conyugue_Apellido_Materno1">
<span<?php echo $conyugue->Apellido_Materno1->ViewAttributes() ?>>
<?php echo $conyugue->Apellido_Materno1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Nombres1->Visible) { // Nombres1 ?>
	<tr id="r_Nombres1">
		<td><span id="elh_conyugue_Nombres1"><?php echo $conyugue->Nombres1->FldCaption() ?></span></td>
		<td data-name="Nombres1"<?php echo $conyugue->Nombres1->CellAttributes() ?>>
<span id="el_conyugue_Nombres1">
<span<?php echo $conyugue->Nombres1->ViewAttributes() ?>>
<?php echo $conyugue->Nombres1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($conyugue->Direccion1->Visible) { // Direccion1 ?>
	<tr id="r_Direccion1">
		<td><span id="elh_conyugue_Direccion1"><?php echo $conyugue->Direccion1->FldCaption() ?></span></td>
		<td data-name="Direccion1"<?php echo $conyugue->Direccion1->CellAttributes() ?>>
<span id="el_conyugue_Direccion1">
<span<?php echo $conyugue->Direccion1->ViewAttributes() ?>>
<?php echo $conyugue->Direccion1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<?php if ($conyugue->Export == "") { ?>
<script type="text/javascript">
fconyugueview.Init();
</script>
<?php } ?>
<?php
$conyugue_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($conyugue->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$conyugue_view->Page_Terminate();
?>
