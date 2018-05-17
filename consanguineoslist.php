<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "consanguineosinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$consanguineos_list = NULL; // Initialize page object first

class cconsanguineos_list extends cconsanguineos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'consanguineos';

	// Page object name
	var $PageObjName = 'consanguineos_list';

	// Grid form hidden field names
	var $FormName = 'fconsanguineoslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (consanguineos)
		if (!isset($GLOBALS["consanguineos"]) || get_class($GLOBALS["consanguineos"]) == "cconsanguineos") {
			$GLOBALS["consanguineos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["consanguineos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "consanguineosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "consanguineosdelete.php";
		$this->MultiUpdateUrl = "consanguineosupdate.php";

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'consanguineos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_usuario)
		if (!isset($UserTable)) {
			$UserTable = new ct_usuario();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fconsanguineoslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->CI_RUN->SetVisibility();
		$this->Expedido->SetVisibility();
		$this->Nombres1->SetVisibility();
		$this->Apellido_Paterno1->SetVisibility();
		$this->Apellido_Materno1->SetVisibility();
		$this->Fiscalia_otro->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Cargo->SetVisibility();
		$this->Unidad->SetVisibility();
		$this->Nombres->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Parentesco->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $consanguineos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($consanguineos);
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
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 3) {
			$this->Nombres->setFormValue($arrKeyFlds[0]);
			$this->Apellido_Paterno->setFormValue($arrKeyFlds[1]);
			$this->Apellido_Materno->setFormValue($arrKeyFlds[2]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fconsanguineoslistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->CI_RUN->AdvancedSearch->ToJSON(), ","); // Field CI_RUN
		$sFilterList = ew_Concat($sFilterList, $this->Expedido->AdvancedSearch->ToJSON(), ","); // Field Expedido
		$sFilterList = ew_Concat($sFilterList, $this->Nombres1->AdvancedSearch->ToJSON(), ","); // Field Nombres1
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Paterno1->AdvancedSearch->ToJSON(), ","); // Field Apellido_Paterno1
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Materno1->AdvancedSearch->ToJSON(), ","); // Field Apellido_Materno1
		$sFilterList = ew_Concat($sFilterList, $this->Fiscalia_otro->AdvancedSearch->ToJSON(), ","); // Field Fiscalia_otro
		$sFilterList = ew_Concat($sFilterList, $this->Unidad_Organizacional->AdvancedSearch->ToJSON(), ","); // Field Unidad_Organizacional
		$sFilterList = ew_Concat($sFilterList, $this->Cargo->AdvancedSearch->ToJSON(), ","); // Field Cargo
		$sFilterList = ew_Concat($sFilterList, $this->Unidad->AdvancedSearch->ToJSON(), ","); // Field Unidad
		$sFilterList = ew_Concat($sFilterList, $this->Nombres->AdvancedSearch->ToJSON(), ","); // Field Nombres
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Paterno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Paterno
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Materno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Materno
		$sFilterList = ew_Concat($sFilterList, $this->Parentesco->AdvancedSearch->ToJSON(), ","); // Field Parentesco
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fconsanguineoslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field CI_RUN
		$this->CI_RUN->AdvancedSearch->SearchValue = @$filter["x_CI_RUN"];
		$this->CI_RUN->AdvancedSearch->SearchOperator = @$filter["z_CI_RUN"];
		$this->CI_RUN->AdvancedSearch->SearchCondition = @$filter["v_CI_RUN"];
		$this->CI_RUN->AdvancedSearch->SearchValue2 = @$filter["y_CI_RUN"];
		$this->CI_RUN->AdvancedSearch->SearchOperator2 = @$filter["w_CI_RUN"];
		$this->CI_RUN->AdvancedSearch->Save();

		// Field Expedido
		$this->Expedido->AdvancedSearch->SearchValue = @$filter["x_Expedido"];
		$this->Expedido->AdvancedSearch->SearchOperator = @$filter["z_Expedido"];
		$this->Expedido->AdvancedSearch->SearchCondition = @$filter["v_Expedido"];
		$this->Expedido->AdvancedSearch->SearchValue2 = @$filter["y_Expedido"];
		$this->Expedido->AdvancedSearch->SearchOperator2 = @$filter["w_Expedido"];
		$this->Expedido->AdvancedSearch->Save();

		// Field Nombres1
		$this->Nombres1->AdvancedSearch->SearchValue = @$filter["x_Nombres1"];
		$this->Nombres1->AdvancedSearch->SearchOperator = @$filter["z_Nombres1"];
		$this->Nombres1->AdvancedSearch->SearchCondition = @$filter["v_Nombres1"];
		$this->Nombres1->AdvancedSearch->SearchValue2 = @$filter["y_Nombres1"];
		$this->Nombres1->AdvancedSearch->SearchOperator2 = @$filter["w_Nombres1"];
		$this->Nombres1->AdvancedSearch->Save();

		// Field Apellido_Paterno1
		$this->Apellido_Paterno1->AdvancedSearch->SearchValue = @$filter["x_Apellido_Paterno1"];
		$this->Apellido_Paterno1->AdvancedSearch->SearchOperator = @$filter["z_Apellido_Paterno1"];
		$this->Apellido_Paterno1->AdvancedSearch->SearchCondition = @$filter["v_Apellido_Paterno1"];
		$this->Apellido_Paterno1->AdvancedSearch->SearchValue2 = @$filter["y_Apellido_Paterno1"];
		$this->Apellido_Paterno1->AdvancedSearch->SearchOperator2 = @$filter["w_Apellido_Paterno1"];
		$this->Apellido_Paterno1->AdvancedSearch->Save();

		// Field Apellido_Materno1
		$this->Apellido_Materno1->AdvancedSearch->SearchValue = @$filter["x_Apellido_Materno1"];
		$this->Apellido_Materno1->AdvancedSearch->SearchOperator = @$filter["z_Apellido_Materno1"];
		$this->Apellido_Materno1->AdvancedSearch->SearchCondition = @$filter["v_Apellido_Materno1"];
		$this->Apellido_Materno1->AdvancedSearch->SearchValue2 = @$filter["y_Apellido_Materno1"];
		$this->Apellido_Materno1->AdvancedSearch->SearchOperator2 = @$filter["w_Apellido_Materno1"];
		$this->Apellido_Materno1->AdvancedSearch->Save();

		// Field Fiscalia_otro
		$this->Fiscalia_otro->AdvancedSearch->SearchValue = @$filter["x_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator = @$filter["z_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchCondition = @$filter["v_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchValue2 = @$filter["y_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator2 = @$filter["w_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->Save();

		// Field Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = @$filter["x_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = @$filter["z_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchCondition = @$filter["v_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue2 = @$filter["y_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator2 = @$filter["w_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->Save();

		// Field Cargo
		$this->Cargo->AdvancedSearch->SearchValue = @$filter["x_Cargo"];
		$this->Cargo->AdvancedSearch->SearchOperator = @$filter["z_Cargo"];
		$this->Cargo->AdvancedSearch->SearchCondition = @$filter["v_Cargo"];
		$this->Cargo->AdvancedSearch->SearchValue2 = @$filter["y_Cargo"];
		$this->Cargo->AdvancedSearch->SearchOperator2 = @$filter["w_Cargo"];
		$this->Cargo->AdvancedSearch->Save();

		// Field Unidad
		$this->Unidad->AdvancedSearch->SearchValue = @$filter["x_Unidad"];
		$this->Unidad->AdvancedSearch->SearchOperator = @$filter["z_Unidad"];
		$this->Unidad->AdvancedSearch->SearchCondition = @$filter["v_Unidad"];
		$this->Unidad->AdvancedSearch->SearchValue2 = @$filter["y_Unidad"];
		$this->Unidad->AdvancedSearch->SearchOperator2 = @$filter["w_Unidad"];
		$this->Unidad->AdvancedSearch->Save();

		// Field Nombres
		$this->Nombres->AdvancedSearch->SearchValue = @$filter["x_Nombres"];
		$this->Nombres->AdvancedSearch->SearchOperator = @$filter["z_Nombres"];
		$this->Nombres->AdvancedSearch->SearchCondition = @$filter["v_Nombres"];
		$this->Nombres->AdvancedSearch->SearchValue2 = @$filter["y_Nombres"];
		$this->Nombres->AdvancedSearch->SearchOperator2 = @$filter["w_Nombres"];
		$this->Nombres->AdvancedSearch->Save();

		// Field Apellido_Paterno
		$this->Apellido_Paterno->AdvancedSearch->SearchValue = @$filter["x_Apellido_Paterno"];
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator = @$filter["z_Apellido_Paterno"];
		$this->Apellido_Paterno->AdvancedSearch->SearchCondition = @$filter["v_Apellido_Paterno"];
		$this->Apellido_Paterno->AdvancedSearch->SearchValue2 = @$filter["y_Apellido_Paterno"];
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator2 = @$filter["w_Apellido_Paterno"];
		$this->Apellido_Paterno->AdvancedSearch->Save();

		// Field Apellido_Materno
		$this->Apellido_Materno->AdvancedSearch->SearchValue = @$filter["x_Apellido_Materno"];
		$this->Apellido_Materno->AdvancedSearch->SearchOperator = @$filter["z_Apellido_Materno"];
		$this->Apellido_Materno->AdvancedSearch->SearchCondition = @$filter["v_Apellido_Materno"];
		$this->Apellido_Materno->AdvancedSearch->SearchValue2 = @$filter["y_Apellido_Materno"];
		$this->Apellido_Materno->AdvancedSearch->SearchOperator2 = @$filter["w_Apellido_Materno"];
		$this->Apellido_Materno->AdvancedSearch->Save();

		// Field Parentesco
		$this->Parentesco->AdvancedSearch->SearchValue = @$filter["x_Parentesco"];
		$this->Parentesco->AdvancedSearch->SearchOperator = @$filter["z_Parentesco"];
		$this->Parentesco->AdvancedSearch->SearchCondition = @$filter["v_Parentesco"];
		$this->Parentesco->AdvancedSearch->SearchValue2 = @$filter["y_Parentesco"];
		$this->Parentesco->AdvancedSearch->SearchOperator2 = @$filter["w_Parentesco"];
		$this->Parentesco->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->CI_RUN, $Default, FALSE); // CI_RUN
		$this->BuildSearchSql($sWhere, $this->Expedido, $Default, FALSE); // Expedido
		$this->BuildSearchSql($sWhere, $this->Nombres1, $Default, FALSE); // Nombres1
		$this->BuildSearchSql($sWhere, $this->Apellido_Paterno1, $Default, FALSE); // Apellido_Paterno1
		$this->BuildSearchSql($sWhere, $this->Apellido_Materno1, $Default, FALSE); // Apellido_Materno1
		$this->BuildSearchSql($sWhere, $this->Fiscalia_otro, $Default, FALSE); // Fiscalia_otro
		$this->BuildSearchSql($sWhere, $this->Unidad_Organizacional, $Default, FALSE); // Unidad_Organizacional
		$this->BuildSearchSql($sWhere, $this->Cargo, $Default, FALSE); // Cargo
		$this->BuildSearchSql($sWhere, $this->Unidad, $Default, FALSE); // Unidad
		$this->BuildSearchSql($sWhere, $this->Nombres, $Default, FALSE); // Nombres
		$this->BuildSearchSql($sWhere, $this->Apellido_Paterno, $Default, FALSE); // Apellido_Paterno
		$this->BuildSearchSql($sWhere, $this->Apellido_Materno, $Default, FALSE); // Apellido_Materno
		$this->BuildSearchSql($sWhere, $this->Parentesco, $Default, FALSE); // Parentesco

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->CI_RUN->AdvancedSearch->Save(); // CI_RUN
			$this->Expedido->AdvancedSearch->Save(); // Expedido
			$this->Nombres1->AdvancedSearch->Save(); // Nombres1
			$this->Apellido_Paterno1->AdvancedSearch->Save(); // Apellido_Paterno1
			$this->Apellido_Materno1->AdvancedSearch->Save(); // Apellido_Materno1
			$this->Fiscalia_otro->AdvancedSearch->Save(); // Fiscalia_otro
			$this->Unidad_Organizacional->AdvancedSearch->Save(); // Unidad_Organizacional
			$this->Cargo->AdvancedSearch->Save(); // Cargo
			$this->Unidad->AdvancedSearch->Save(); // Unidad
			$this->Nombres->AdvancedSearch->Save(); // Nombres
			$this->Apellido_Paterno->AdvancedSearch->Save(); // Apellido_Paterno
			$this->Apellido_Materno->AdvancedSearch->Save(); // Apellido_Materno
			$this->Parentesco->AdvancedSearch->Save(); // Parentesco
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->CI_RUN, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Expedido, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nombres1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Paterno1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Materno1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Fiscalia_otro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad_Organizacional, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nombres, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Paterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Materno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Parentesco, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->CI_RUN->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Expedido->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nombres1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Apellido_Paterno1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Apellido_Materno1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fiscalia_otro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Unidad_Organizacional->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cargo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Unidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nombres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Apellido_Paterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Apellido_Materno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Parentesco->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->CI_RUN->AdvancedSearch->UnsetSession();
		$this->Expedido->AdvancedSearch->UnsetSession();
		$this->Nombres1->AdvancedSearch->UnsetSession();
		$this->Apellido_Paterno1->AdvancedSearch->UnsetSession();
		$this->Apellido_Materno1->AdvancedSearch->UnsetSession();
		$this->Fiscalia_otro->AdvancedSearch->UnsetSession();
		$this->Unidad_Organizacional->AdvancedSearch->UnsetSession();
		$this->Cargo->AdvancedSearch->UnsetSession();
		$this->Unidad->AdvancedSearch->UnsetSession();
		$this->Nombres->AdvancedSearch->UnsetSession();
		$this->Apellido_Paterno->AdvancedSearch->UnsetSession();
		$this->Apellido_Materno->AdvancedSearch->UnsetSession();
		$this->Parentesco->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->CI_RUN->AdvancedSearch->Load();
		$this->Expedido->AdvancedSearch->Load();
		$this->Nombres1->AdvancedSearch->Load();
		$this->Apellido_Paterno1->AdvancedSearch->Load();
		$this->Apellido_Materno1->AdvancedSearch->Load();
		$this->Fiscalia_otro->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Cargo->AdvancedSearch->Load();
		$this->Unidad->AdvancedSearch->Load();
		$this->Nombres->AdvancedSearch->Load();
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Parentesco->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->CI_RUN); // CI_RUN
			$this->UpdateSort($this->Expedido); // Expedido
			$this->UpdateSort($this->Nombres1); // Nombres1
			$this->UpdateSort($this->Apellido_Paterno1); // Apellido_Paterno1
			$this->UpdateSort($this->Apellido_Materno1); // Apellido_Materno1
			$this->UpdateSort($this->Fiscalia_otro); // Fiscalia_otro
			$this->UpdateSort($this->Unidad_Organizacional); // Unidad_Organizacional
			$this->UpdateSort($this->Cargo); // Cargo
			$this->UpdateSort($this->Unidad); // Unidad
			$this->UpdateSort($this->Nombres); // Nombres
			$this->UpdateSort($this->Apellido_Paterno); // Apellido_Paterno
			$this->UpdateSort($this->Apellido_Materno); // Apellido_Materno
			$this->UpdateSort($this->Parentesco); // Parentesco
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->CI_RUN->setSort("");
				$this->Expedido->setSort("");
				$this->Nombres1->setSort("");
				$this->Apellido_Paterno1->setSort("");
				$this->Apellido_Materno1->setSort("");
				$this->Fiscalia_otro->setSort("");
				$this->Unidad_Organizacional->setSort("");
				$this->Cargo->setSort("");
				$this->Unidad->setSort("");
				$this->Nombres->setSort("");
				$this->Apellido_Paterno->setSort("");
				$this->Apellido_Materno->setSort("");
				$this->Parentesco->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Nombres->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Paterno->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Materno->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fconsanguineoslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fconsanguineoslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fconsanguineoslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fconsanguineoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"consanguineossrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"consanguineos\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,url:'consanguineossrch.php',caption:'" . $Language->Phrase("Search") . "'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fconsanguineoslistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
		$item->Visible = ($this->SearchWhere <> "" && $this->TotalRecs > 0);

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// CI_RUN

		$this->CI_RUN->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_CI_RUN"]);
		if ($this->CI_RUN->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->CI_RUN->AdvancedSearch->SearchOperator = @$_GET["z_CI_RUN"];

		// Expedido
		$this->Expedido->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Expedido"]);
		if ($this->Expedido->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Expedido->AdvancedSearch->SearchOperator = @$_GET["z_Expedido"];

		// Nombres1
		$this->Nombres1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nombres1"]);
		if ($this->Nombres1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nombres1->AdvancedSearch->SearchOperator = @$_GET["z_Nombres1"];

		// Apellido_Paterno1
		$this->Apellido_Paterno1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Apellido_Paterno1"]);
		if ($this->Apellido_Paterno1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Apellido_Paterno1->AdvancedSearch->SearchOperator = @$_GET["z_Apellido_Paterno1"];

		// Apellido_Materno1
		$this->Apellido_Materno1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Apellido_Materno1"]);
		if ($this->Apellido_Materno1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Apellido_Materno1->AdvancedSearch->SearchOperator = @$_GET["z_Apellido_Materno1"];

		// Fiscalia_otro
		$this->Fiscalia_otro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fiscalia_otro"]);
		if ($this->Fiscalia_otro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator = @$_GET["z_Fiscalia_otro"];

		// Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Unidad_Organizacional"]);
		if ($this->Unidad_Organizacional->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = @$_GET["z_Unidad_Organizacional"];

		// Cargo
		$this->Cargo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cargo"]);
		if ($this->Cargo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cargo->AdvancedSearch->SearchOperator = @$_GET["z_Cargo"];

		// Unidad
		$this->Unidad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Unidad"]);
		if ($this->Unidad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Unidad->AdvancedSearch->SearchOperator = @$_GET["z_Unidad"];

		// Nombres
		$this->Nombres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nombres"]);
		if ($this->Nombres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nombres->AdvancedSearch->SearchOperator = @$_GET["z_Nombres"];

		// Apellido_Paterno
		$this->Apellido_Paterno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Apellido_Paterno"]);
		if ($this->Apellido_Paterno->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator = @$_GET["z_Apellido_Paterno"];

		// Apellido_Materno
		$this->Apellido_Materno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Apellido_Materno"]);
		if ($this->Apellido_Materno->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Apellido_Materno->AdvancedSearch->SearchOperator = @$_GET["z_Apellido_Materno"];

		// Parentesco
		$this->Parentesco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Parentesco"]);
		if ($this->Parentesco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Parentesco->AdvancedSearch->SearchOperator = @$_GET["z_Parentesco"];
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
		$this->Nombres1->setDbValue($rs->fields('Nombres1'));
		$this->Apellido_Paterno1->setDbValue($rs->fields('Apellido_Paterno1'));
		$this->Apellido_Materno1->setDbValue($rs->fields('Apellido_Materno1'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Parentesco->setDbValue($rs->fields('Parentesco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Expedido->DbValue = $row['Expedido'];
		$this->Nombres1->DbValue = $row['Nombres1'];
		$this->Apellido_Paterno1->DbValue = $row['Apellido_Paterno1'];
		$this->Apellido_Materno1->DbValue = $row['Apellido_Materno1'];
		$this->Fiscalia_otro->DbValue = $row['Fiscalia_otro'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
		$this->Cargo->DbValue = $row['Cargo'];
		$this->Unidad->DbValue = $row['Unidad'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Parentesco->DbValue = $row['Parentesco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Nombres")) <> "")
			$this->Nombres->CurrentValue = $this->getKey("Nombres"); // Nombres
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// CI_RUN
		// Expedido
		// Nombres1
		// Apellido_Paterno1
		// Apellido_Materno1
		// Fiscalia_otro
		// Unidad_Organizacional
		// Cargo
		// Unidad
		// Nombres
		// Apellido_Paterno
		// Apellido_Materno
		// Parentesco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewCustomAttributes = "";

		// Expedido
		$this->Expedido->ViewValue = $this->Expedido->CurrentValue;
		$this->Expedido->ViewCustomAttributes = "";

		// Nombres1
		$this->Nombres1->ViewValue = $this->Nombres1->CurrentValue;
		$this->Nombres1->ViewCustomAttributes = "";

		// Apellido_Paterno1
		$this->Apellido_Paterno1->ViewValue = $this->Apellido_Paterno1->CurrentValue;
		$this->Apellido_Paterno1->ViewCustomAttributes = "";

		// Apellido_Materno1
		$this->Apellido_Materno1->ViewValue = $this->Apellido_Materno1->CurrentValue;
		$this->Apellido_Materno1->ViewCustomAttributes = "";

		// Fiscalia_otro
		$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Cargo
		$this->Cargo->ViewValue = $this->Cargo->CurrentValue;
		$this->Cargo->ViewCustomAttributes = "";

		// Unidad
		$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
		$this->Unidad->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Parentesco
		if (strval($this->Parentesco->CurrentValue) <> "") {
			$this->Parentesco->ViewValue = $this->Parentesco->OptionCaption($this->Parentesco->CurrentValue);
		} else {
			$this->Parentesco->ViewValue = NULL;
		}
		$this->Parentesco->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";
			if ($this->Export == "")
				$this->CI_RUN->ViewValue = ew_Highlight($this->HighlightName(), $this->CI_RUN->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->CI_RUN->AdvancedSearch->getValue("x"), "");

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";
			$this->Expedido->TooltipValue = "";
			if ($this->Export == "")
				$this->Expedido->ViewValue = ew_Highlight($this->HighlightName(), $this->Expedido->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Expedido->AdvancedSearch->getValue("x"), "");

			// Nombres1
			$this->Nombres1->LinkCustomAttributes = "";
			$this->Nombres1->HrefValue = "";
			$this->Nombres1->TooltipValue = "";
			if ($this->Export == "")
				$this->Nombres1->ViewValue = ew_Highlight($this->HighlightName(), $this->Nombres1->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Nombres1->AdvancedSearch->getValue("x"), "");

			// Apellido_Paterno1
			$this->Apellido_Paterno1->LinkCustomAttributes = "";
			$this->Apellido_Paterno1->HrefValue = "";
			$this->Apellido_Paterno1->TooltipValue = "";
			if ($this->Export == "")
				$this->Apellido_Paterno1->ViewValue = ew_Highlight($this->HighlightName(), $this->Apellido_Paterno1->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Apellido_Paterno1->AdvancedSearch->getValue("x"), "");

			// Apellido_Materno1
			$this->Apellido_Materno1->LinkCustomAttributes = "";
			$this->Apellido_Materno1->HrefValue = "";
			$this->Apellido_Materno1->TooltipValue = "";
			if ($this->Export == "")
				$this->Apellido_Materno1->ViewValue = ew_Highlight($this->HighlightName(), $this->Apellido_Materno1->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Apellido_Materno1->AdvancedSearch->getValue("x"), "");

			// Fiscalia_otro
			$this->Fiscalia_otro->LinkCustomAttributes = "";
			$this->Fiscalia_otro->HrefValue = "";
			$this->Fiscalia_otro->TooltipValue = "";
			if ($this->Export == "")
				$this->Fiscalia_otro->ViewValue = ew_Highlight($this->HighlightName(), $this->Fiscalia_otro->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Fiscalia_otro->AdvancedSearch->getValue("x"), "");

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";
			if ($this->Export == "")
				$this->Unidad_Organizacional->ViewValue = ew_Highlight($this->HighlightName(), $this->Unidad_Organizacional->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Unidad_Organizacional->AdvancedSearch->getValue("x"), "");

			// Cargo
			$this->Cargo->LinkCustomAttributes = "";
			$this->Cargo->HrefValue = "";
			$this->Cargo->TooltipValue = "";
			if ($this->Export == "")
				$this->Cargo->ViewValue = ew_Highlight($this->HighlightName(), $this->Cargo->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Cargo->AdvancedSearch->getValue("x"), "");

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";
			$this->Unidad->TooltipValue = "";
			if ($this->Export == "")
				$this->Unidad->ViewValue = ew_Highlight($this->HighlightName(), $this->Unidad->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Unidad->AdvancedSearch->getValue("x"), "");

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";
			$this->Nombres->TooltipValue = "";
			if ($this->Export == "")
				$this->Nombres->ViewValue = ew_Highlight($this->HighlightName(), $this->Nombres->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Nombres->AdvancedSearch->getValue("x"), "");

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";
			$this->Apellido_Paterno->TooltipValue = "";
			if ($this->Export == "")
				$this->Apellido_Paterno->ViewValue = ew_Highlight($this->HighlightName(), $this->Apellido_Paterno->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Apellido_Paterno->AdvancedSearch->getValue("x"), "");

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";
			$this->Apellido_Materno->TooltipValue = "";
			if ($this->Export == "")
				$this->Apellido_Materno->ViewValue = ew_Highlight($this->HighlightName(), $this->Apellido_Materno->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Apellido_Materno->AdvancedSearch->getValue("x"), "");

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";
			$this->Parentesco->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->CI_RUN->AdvancedSearch->Load();
		$this->Expedido->AdvancedSearch->Load();
		$this->Nombres1->AdvancedSearch->Load();
		$this->Apellido_Paterno1->AdvancedSearch->Load();
		$this->Apellido_Materno1->AdvancedSearch->Load();
		$this->Fiscalia_otro->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Cargo->AdvancedSearch->Load();
		$this->Unidad->AdvancedSearch->Load();
		$this->Nombres->AdvancedSearch->Load();
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Parentesco->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_consanguineos\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_consanguineos',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fconsanguineoslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

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

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($consanguineos_list)) $consanguineos_list = new cconsanguineos_list();

// Page init
$consanguineos_list->Page_Init();

// Page main
$consanguineos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$consanguineos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($consanguineos->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fconsanguineoslist = new ew_Form("fconsanguineoslist", "list");
fconsanguineoslist.FormKeyCountName = '<?php echo $consanguineos_list->FormKeyCountName ?>';

// Form_CustomValidate event
fconsanguineoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconsanguineoslist.ValidateRequired = true;
<?php } else { ?>
fconsanguineoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fconsanguineoslist.Lists["x_Parentesco"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fconsanguineoslist.Lists["x_Parentesco"].Options = <?php echo json_encode($consanguineos->Parentesco->Options()) ?>;

// Form object for search
var CurrentSearchForm = fconsanguineoslistsrch = new ew_Form("fconsanguineoslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($consanguineos->Export == "") { ?>
<div class="ewToolbar">
<?php if ($consanguineos->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($consanguineos_list->TotalRecs > 0 && $consanguineos_list->ExportOptions->Visible()) { ?>
<?php $consanguineos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($consanguineos_list->SearchOptions->Visible()) { ?>
<?php $consanguineos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($consanguineos_list->FilterOptions->Visible()) { ?>
<?php $consanguineos_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($consanguineos->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $consanguineos_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($consanguineos_list->TotalRecs <= 0)
			$consanguineos_list->TotalRecs = $consanguineos->SelectRecordCount();
	} else {
		if (!$consanguineos_list->Recordset && ($consanguineos_list->Recordset = $consanguineos_list->LoadRecordset()))
			$consanguineos_list->TotalRecs = $consanguineos_list->Recordset->RecordCount();
	}
	$consanguineos_list->StartRec = 1;
	if ($consanguineos_list->DisplayRecs <= 0 || ($consanguineos->Export <> "" && $consanguineos->ExportAll)) // Display all records
		$consanguineos_list->DisplayRecs = $consanguineos_list->TotalRecs;
	if (!($consanguineos->Export <> "" && $consanguineos->ExportAll))
		$consanguineos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$consanguineos_list->Recordset = $consanguineos_list->LoadRecordset($consanguineos_list->StartRec-1, $consanguineos_list->DisplayRecs);

	// Set no record found message
	if ($consanguineos->CurrentAction == "" && $consanguineos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$consanguineos_list->setWarningMessage(ew_DeniedMsg());
		if ($consanguineos_list->SearchWhere == "0=101")
			$consanguineos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$consanguineos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$consanguineos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($consanguineos->Export == "" && $consanguineos->CurrentAction == "") { ?>
<form name="fconsanguineoslistsrch" id="fconsanguineoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($consanguineos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fconsanguineoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="consanguineos">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($consanguineos_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($consanguineos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $consanguineos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($consanguineos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($consanguineos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($consanguineos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($consanguineos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $consanguineos_list->ShowPageHeader(); ?>
<?php
$consanguineos_list->ShowMessage();
?>
<?php if ($consanguineos_list->TotalRecs > 0 || $consanguineos->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid consanguineos">
<form name="fconsanguineoslist" id="fconsanguineoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($consanguineos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $consanguineos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="consanguineos">
<div id="gmp_consanguineos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($consanguineos_list->TotalRecs > 0 || $consanguineos->CurrentAction == "gridedit") { ?>
<table id="tbl_consanguineoslist" class="table ewTable">
<?php echo $consanguineos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$consanguineos_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$consanguineos_list->RenderListOptions();

// Render list options (header, left)
$consanguineos_list->ListOptions->Render("header", "left");
?>
<?php if ($consanguineos->CI_RUN->Visible) { // CI_RUN ?>
	<?php if ($consanguineos->SortUrl($consanguineos->CI_RUN) == "") { ?>
		<th data-name="CI_RUN"><div id="elh_consanguineos_CI_RUN" class="consanguineos_CI_RUN"><div class="ewTableHeaderCaption"><?php echo $consanguineos->CI_RUN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CI_RUN"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->CI_RUN) ?>',1);"><div id="elh_consanguineos_CI_RUN" class="consanguineos_CI_RUN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->CI_RUN->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->CI_RUN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->CI_RUN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Expedido->Visible) { // Expedido ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Expedido) == "") { ?>
		<th data-name="Expedido"><div id="elh_consanguineos_Expedido" class="consanguineos_Expedido"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Expedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Expedido"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Expedido) ?>',1);"><div id="elh_consanguineos_Expedido" class="consanguineos_Expedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Expedido->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Expedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Expedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Nombres1->Visible) { // Nombres1 ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Nombres1) == "") { ?>
		<th data-name="Nombres1"><div id="elh_consanguineos_Nombres1" class="consanguineos_Nombres1"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Nombres1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Nombres1) ?>',1);"><div id="elh_consanguineos_Nombres1" class="consanguineos_Nombres1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Nombres1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Nombres1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Nombres1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Apellido_Paterno1->Visible) { // Apellido_Paterno1 ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Apellido_Paterno1) == "") { ?>
		<th data-name="Apellido_Paterno1"><div id="elh_consanguineos_Apellido_Paterno1" class="consanguineos_Apellido_Paterno1"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Paterno1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Apellido_Paterno1) ?>',1);"><div id="elh_consanguineos_Apellido_Paterno1" class="consanguineos_Apellido_Paterno1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Paterno1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Apellido_Paterno1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Apellido_Paterno1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Apellido_Materno1->Visible) { // Apellido_Materno1 ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Apellido_Materno1) == "") { ?>
		<th data-name="Apellido_Materno1"><div id="elh_consanguineos_Apellido_Materno1" class="consanguineos_Apellido_Materno1"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Materno1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Apellido_Materno1) ?>',1);"><div id="elh_consanguineos_Apellido_Materno1" class="consanguineos_Apellido_Materno1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Materno1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Apellido_Materno1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Apellido_Materno1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Fiscalia_otro) == "") { ?>
		<th data-name="Fiscalia_otro"><div id="elh_consanguineos_Fiscalia_otro" class="consanguineos_Fiscalia_otro"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Fiscalia_otro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fiscalia_otro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Fiscalia_otro) ?>',1);"><div id="elh_consanguineos_Fiscalia_otro" class="consanguineos_Fiscalia_otro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Fiscalia_otro->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Fiscalia_otro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Fiscalia_otro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Unidad_Organizacional) == "") { ?>
		<th data-name="Unidad_Organizacional"><div id="elh_consanguineos_Unidad_Organizacional" class="consanguineos_Unidad_Organizacional"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Unidad_Organizacional->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Unidad_Organizacional"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Unidad_Organizacional) ?>',1);"><div id="elh_consanguineos_Unidad_Organizacional" class="consanguineos_Unidad_Organizacional">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Unidad_Organizacional->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Unidad_Organizacional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Unidad_Organizacional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Cargo->Visible) { // Cargo ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Cargo) == "") { ?>
		<th data-name="Cargo"><div id="elh_consanguineos_Cargo" class="consanguineos_Cargo"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Cargo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Cargo) ?>',1);"><div id="elh_consanguineos_Cargo" class="consanguineos_Cargo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Cargo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Cargo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Cargo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Unidad->Visible) { // Unidad ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Unidad) == "") { ?>
		<th data-name="Unidad"><div id="elh_consanguineos_Unidad" class="consanguineos_Unidad"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Unidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Unidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Unidad) ?>',1);"><div id="elh_consanguineos_Unidad" class="consanguineos_Unidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Unidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Unidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Unidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Nombres->Visible) { // Nombres ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Nombres) == "") { ?>
		<th data-name="Nombres"><div id="elh_consanguineos_Nombres" class="consanguineos_Nombres"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Nombres) ?>',1);"><div id="elh_consanguineos_Nombres" class="consanguineos_Nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Nombres->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_consanguineos_Apellido_Paterno" class="consanguineos_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Apellido_Paterno) ?>',1);"><div id="elh_consanguineos_Apellido_Paterno" class="consanguineos_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Paterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_consanguineos_Apellido_Materno" class="consanguineos_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Apellido_Materno) ?>',1);"><div id="elh_consanguineos_Apellido_Materno" class="consanguineos_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Apellido_Materno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($consanguineos->Parentesco->Visible) { // Parentesco ?>
	<?php if ($consanguineos->SortUrl($consanguineos->Parentesco) == "") { ?>
		<th data-name="Parentesco"><div id="elh_consanguineos_Parentesco" class="consanguineos_Parentesco"><div class="ewTableHeaderCaption"><?php echo $consanguineos->Parentesco->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Parentesco"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consanguineos->SortUrl($consanguineos->Parentesco) ?>',1);"><div id="elh_consanguineos_Parentesco" class="consanguineos_Parentesco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consanguineos->Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consanguineos->Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consanguineos->Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$consanguineos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($consanguineos->ExportAll && $consanguineos->Export <> "") {
	$consanguineos_list->StopRec = $consanguineos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($consanguineos_list->TotalRecs > $consanguineos_list->StartRec + $consanguineos_list->DisplayRecs - 1)
		$consanguineos_list->StopRec = $consanguineos_list->StartRec + $consanguineos_list->DisplayRecs - 1;
	else
		$consanguineos_list->StopRec = $consanguineos_list->TotalRecs;
}
$consanguineos_list->RecCnt = $consanguineos_list->StartRec - 1;
if ($consanguineos_list->Recordset && !$consanguineos_list->Recordset->EOF) {
	$consanguineos_list->Recordset->MoveFirst();
	$bSelectLimit = $consanguineos_list->UseSelectLimit;
	if (!$bSelectLimit && $consanguineos_list->StartRec > 1)
		$consanguineos_list->Recordset->Move($consanguineos_list->StartRec - 1);
} elseif (!$consanguineos->AllowAddDeleteRow && $consanguineos_list->StopRec == 0) {
	$consanguineos_list->StopRec = $consanguineos->GridAddRowCount;
}

// Initialize aggregate
$consanguineos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$consanguineos->ResetAttrs();
$consanguineos_list->RenderRow();
while ($consanguineos_list->RecCnt < $consanguineos_list->StopRec) {
	$consanguineos_list->RecCnt++;
	if (intval($consanguineos_list->RecCnt) >= intval($consanguineos_list->StartRec)) {
		$consanguineos_list->RowCnt++;

		// Set up key count
		$consanguineos_list->KeyCount = $consanguineos_list->RowIndex;

		// Init row class and style
		$consanguineos->ResetAttrs();
		$consanguineos->CssClass = "";
		if ($consanguineos->CurrentAction == "gridadd") {
		} else {
			$consanguineos_list->LoadRowValues($consanguineos_list->Recordset); // Load row values
		}
		$consanguineos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$consanguineos->RowAttrs = array_merge($consanguineos->RowAttrs, array('data-rowindex'=>$consanguineos_list->RowCnt, 'id'=>'r' . $consanguineos_list->RowCnt . '_consanguineos', 'data-rowtype'=>$consanguineos->RowType));

		// Render row
		$consanguineos_list->RenderRow();

		// Render list options
		$consanguineos_list->RenderListOptions();
?>
	<tr<?php echo $consanguineos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$consanguineos_list->ListOptions->Render("body", "left", $consanguineos_list->RowCnt);
?>
	<?php if ($consanguineos->CI_RUN->Visible) { // CI_RUN ?>
		<td data-name="CI_RUN"<?php echo $consanguineos->CI_RUN->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_CI_RUN" class="consanguineos_CI_RUN">
<span<?php echo $consanguineos->CI_RUN->ViewAttributes() ?>>
<?php echo $consanguineos->CI_RUN->ListViewValue() ?></span>
</span>
<a id="<?php echo $consanguineos_list->PageObjName . "_row_" . $consanguineos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($consanguineos->Expedido->Visible) { // Expedido ?>
		<td data-name="Expedido"<?php echo $consanguineos->Expedido->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Expedido" class="consanguineos_Expedido">
<span<?php echo $consanguineos->Expedido->ViewAttributes() ?>>
<?php echo $consanguineos->Expedido->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Nombres1->Visible) { // Nombres1 ?>
		<td data-name="Nombres1"<?php echo $consanguineos->Nombres1->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Nombres1" class="consanguineos_Nombres1">
<span<?php echo $consanguineos->Nombres1->ViewAttributes() ?>>
<?php echo $consanguineos->Nombres1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Apellido_Paterno1->Visible) { // Apellido_Paterno1 ?>
		<td data-name="Apellido_Paterno1"<?php echo $consanguineos->Apellido_Paterno1->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Apellido_Paterno1" class="consanguineos_Apellido_Paterno1">
<span<?php echo $consanguineos->Apellido_Paterno1->ViewAttributes() ?>>
<?php echo $consanguineos->Apellido_Paterno1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Apellido_Materno1->Visible) { // Apellido_Materno1 ?>
		<td data-name="Apellido_Materno1"<?php echo $consanguineos->Apellido_Materno1->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Apellido_Materno1" class="consanguineos_Apellido_Materno1">
<span<?php echo $consanguineos->Apellido_Materno1->ViewAttributes() ?>>
<?php echo $consanguineos->Apellido_Materno1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
		<td data-name="Fiscalia_otro"<?php echo $consanguineos->Fiscalia_otro->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Fiscalia_otro" class="consanguineos_Fiscalia_otro">
<span<?php echo $consanguineos->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $consanguineos->Fiscalia_otro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<td data-name="Unidad_Organizacional"<?php echo $consanguineos->Unidad_Organizacional->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Unidad_Organizacional" class="consanguineos_Unidad_Organizacional">
<span<?php echo $consanguineos->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $consanguineos->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Cargo->Visible) { // Cargo ?>
		<td data-name="Cargo"<?php echo $consanguineos->Cargo->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Cargo" class="consanguineos_Cargo">
<span<?php echo $consanguineos->Cargo->ViewAttributes() ?>>
<?php echo $consanguineos->Cargo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Unidad->Visible) { // Unidad ?>
		<td data-name="Unidad"<?php echo $consanguineos->Unidad->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Unidad" class="consanguineos_Unidad">
<span<?php echo $consanguineos->Unidad->ViewAttributes() ?>>
<?php echo $consanguineos->Unidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres"<?php echo $consanguineos->Nombres->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Nombres" class="consanguineos_Nombres">
<span<?php echo $consanguineos->Nombres->ViewAttributes() ?>>
<?php echo $consanguineos->Nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $consanguineos->Apellido_Paterno->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Apellido_Paterno" class="consanguineos_Apellido_Paterno">
<span<?php echo $consanguineos->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $consanguineos->Apellido_Paterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $consanguineos->Apellido_Materno->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Apellido_Materno" class="consanguineos_Apellido_Materno">
<span<?php echo $consanguineos->Apellido_Materno->ViewAttributes() ?>>
<?php echo $consanguineos->Apellido_Materno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($consanguineos->Parentesco->Visible) { // Parentesco ?>
		<td data-name="Parentesco"<?php echo $consanguineos->Parentesco->CellAttributes() ?>>
<span id="el<?php echo $consanguineos_list->RowCnt ?>_consanguineos_Parentesco" class="consanguineos_Parentesco">
<span<?php echo $consanguineos->Parentesco->ViewAttributes() ?>>
<?php echo $consanguineos->Parentesco->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$consanguineos_list->ListOptions->Render("body", "right", $consanguineos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($consanguineos->CurrentAction <> "gridadd")
		$consanguineos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($consanguineos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($consanguineos_list->Recordset)
	$consanguineos_list->Recordset->Close();
?>
<?php if ($consanguineos->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($consanguineos->CurrentAction <> "gridadd" && $consanguineos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($consanguineos_list->Pager)) $consanguineos_list->Pager = new cPrevNextPager($consanguineos_list->StartRec, $consanguineos_list->DisplayRecs, $consanguineos_list->TotalRecs) ?>
<?php if ($consanguineos_list->Pager->RecordCount > 0 && $consanguineos_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($consanguineos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $consanguineos_list->PageUrl() ?>start=<?php echo $consanguineos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($consanguineos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $consanguineos_list->PageUrl() ?>start=<?php echo $consanguineos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $consanguineos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($consanguineos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $consanguineos_list->PageUrl() ?>start=<?php echo $consanguineos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($consanguineos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $consanguineos_list->PageUrl() ?>start=<?php echo $consanguineos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $consanguineos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $consanguineos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $consanguineos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $consanguineos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($consanguineos_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($consanguineos_list->TotalRecs == 0 && $consanguineos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($consanguineos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($consanguineos->Export == "") { ?>
<script type="text/javascript">
fconsanguineoslistsrch.FilterList = <?php echo $consanguineos_list->GetFilterList() ?>;
fconsanguineoslistsrch.Init();
fconsanguineoslist.Init();
</script>
<?php } ?>
<?php
$consanguineos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($consanguineos->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$consanguineos_list->Page_Terminate();
?>
