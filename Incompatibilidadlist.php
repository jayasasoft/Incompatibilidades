<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "Incompatibilidadinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$Incompatibilidad_list = NULL; // Initialize page object first

class cIncompatibilidad_list extends cIncompatibilidad {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'Incompatibilidad';

	// Page object name
	var $PageObjName = 'Incompatibilidad_list';

	// Grid form hidden field names
	var $FormName = 'fIncompatibilidadlist';
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

		// Table object (Incompatibilidad)
		if (!isset($GLOBALS["Incompatibilidad"]) || get_class($GLOBALS["Incompatibilidad"]) == "cIncompatibilidad") {
			$GLOBALS["Incompatibilidad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Incompatibilidad"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "Incompatibilidadadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "Incompatibilidaddelete.php";
		$this->MultiUpdateUrl = "Incompatibilidadupdate.php";

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Incompatibilidad', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fIncompatibilidadlistsrch";

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->CI_RUN->SetVisibility();
		$this->Expedido->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Nombres->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Unidad->SetVisibility();
		$this->Cargo->SetVisibility();
		$this->Fiscalia_otro->SetVisibility();
		$this->Salario_Mayor_Si->SetVisibility();
		$this->Salario_Mayor_Si->Visible = !$this->IsAddOrEdit();
		$this->Grado_Si->SetVisibility();
		$this->Grado_Si->Visible = !$this->IsAddOrEdit();
		$this->Actividad_Si->SetVisibility();
		$this->Actividad_Si->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $Incompatibilidad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($Incompatibilidad);
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

		// Multi Column
		$this->RecPerRow = 1;
		$this->MultiColumnCnt = 12;
		$this->MultiColumnEditCnt = 12;

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fIncompatibilidadlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->CI_RUN->AdvancedSearch->ToJSON(), ","); // Field CI_RUN
		$sFilterList = ew_Concat($sFilterList, $this->Expedido->AdvancedSearch->ToJSON(), ","); // Field Expedido
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Paterno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Paterno
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Materno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Materno
		$sFilterList = ew_Concat($sFilterList, $this->Nombres->AdvancedSearch->ToJSON(), ","); // Field Nombres
		$sFilterList = ew_Concat($sFilterList, $this->Unidad_Organizacional->AdvancedSearch->ToJSON(), ","); // Field Unidad_Organizacional
		$sFilterList = ew_Concat($sFilterList, $this->Unidad->AdvancedSearch->ToJSON(), ","); // Field Unidad
		$sFilterList = ew_Concat($sFilterList, $this->Cargo->AdvancedSearch->ToJSON(), ","); // Field Cargo
		$sFilterList = ew_Concat($sFilterList, $this->Fiscalia_otro->AdvancedSearch->ToJSON(), ","); // Field Fiscalia_otro
		$sFilterList = ew_Concat($sFilterList, $this->Salario_Mayor_Si->AdvancedSearch->ToJSON(), ","); // Field Salario_Mayor_Si
		$sFilterList = ew_Concat($sFilterList, $this->Grado_Si->AdvancedSearch->ToJSON(), ","); // Field Grado_Si
		$sFilterList = ew_Concat($sFilterList, $this->Actividad_Si->AdvancedSearch->ToJSON(), ","); // Field Actividad_Si
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fIncompatibilidadlistsrch", $filters);

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

		// Field Nombres
		$this->Nombres->AdvancedSearch->SearchValue = @$filter["x_Nombres"];
		$this->Nombres->AdvancedSearch->SearchOperator = @$filter["z_Nombres"];
		$this->Nombres->AdvancedSearch->SearchCondition = @$filter["v_Nombres"];
		$this->Nombres->AdvancedSearch->SearchValue2 = @$filter["y_Nombres"];
		$this->Nombres->AdvancedSearch->SearchOperator2 = @$filter["w_Nombres"];
		$this->Nombres->AdvancedSearch->Save();

		// Field Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = @$filter["x_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = @$filter["z_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchCondition = @$filter["v_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue2 = @$filter["y_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator2 = @$filter["w_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->Save();

		// Field Unidad
		$this->Unidad->AdvancedSearch->SearchValue = @$filter["x_Unidad"];
		$this->Unidad->AdvancedSearch->SearchOperator = @$filter["z_Unidad"];
		$this->Unidad->AdvancedSearch->SearchCondition = @$filter["v_Unidad"];
		$this->Unidad->AdvancedSearch->SearchValue2 = @$filter["y_Unidad"];
		$this->Unidad->AdvancedSearch->SearchOperator2 = @$filter["w_Unidad"];
		$this->Unidad->AdvancedSearch->Save();

		// Field Cargo
		$this->Cargo->AdvancedSearch->SearchValue = @$filter["x_Cargo"];
		$this->Cargo->AdvancedSearch->SearchOperator = @$filter["z_Cargo"];
		$this->Cargo->AdvancedSearch->SearchCondition = @$filter["v_Cargo"];
		$this->Cargo->AdvancedSearch->SearchValue2 = @$filter["y_Cargo"];
		$this->Cargo->AdvancedSearch->SearchOperator2 = @$filter["w_Cargo"];
		$this->Cargo->AdvancedSearch->Save();

		// Field Fiscalia_otro
		$this->Fiscalia_otro->AdvancedSearch->SearchValue = @$filter["x_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator = @$filter["z_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchCondition = @$filter["v_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchValue2 = @$filter["y_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator2 = @$filter["w_Fiscalia_otro"];
		$this->Fiscalia_otro->AdvancedSearch->Save();

		// Field Salario_Mayor_Si
		$this->Salario_Mayor_Si->AdvancedSearch->SearchValue = @$filter["x_Salario_Mayor_Si"];
		$this->Salario_Mayor_Si->AdvancedSearch->SearchOperator = @$filter["z_Salario_Mayor_Si"];
		$this->Salario_Mayor_Si->AdvancedSearch->SearchCondition = @$filter["v_Salario_Mayor_Si"];
		$this->Salario_Mayor_Si->AdvancedSearch->SearchValue2 = @$filter["y_Salario_Mayor_Si"];
		$this->Salario_Mayor_Si->AdvancedSearch->SearchOperator2 = @$filter["w_Salario_Mayor_Si"];
		$this->Salario_Mayor_Si->AdvancedSearch->Save();

		// Field Grado_Si
		$this->Grado_Si->AdvancedSearch->SearchValue = @$filter["x_Grado_Si"];
		$this->Grado_Si->AdvancedSearch->SearchOperator = @$filter["z_Grado_Si"];
		$this->Grado_Si->AdvancedSearch->SearchCondition = @$filter["v_Grado_Si"];
		$this->Grado_Si->AdvancedSearch->SearchValue2 = @$filter["y_Grado_Si"];
		$this->Grado_Si->AdvancedSearch->SearchOperator2 = @$filter["w_Grado_Si"];
		$this->Grado_Si->AdvancedSearch->Save();

		// Field Actividad_Si
		$this->Actividad_Si->AdvancedSearch->SearchValue = @$filter["x_Actividad_Si"];
		$this->Actividad_Si->AdvancedSearch->SearchOperator = @$filter["z_Actividad_Si"];
		$this->Actividad_Si->AdvancedSearch->SearchCondition = @$filter["v_Actividad_Si"];
		$this->Actividad_Si->AdvancedSearch->SearchValue2 = @$filter["y_Actividad_Si"];
		$this->Actividad_Si->AdvancedSearch->SearchOperator2 = @$filter["w_Actividad_Si"];
		$this->Actividad_Si->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->CI_RUN, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Expedido, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Paterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Materno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nombres, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad_Organizacional, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Fiscalia_otro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Salario_Mayor_Si, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Grado_Si, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Actividad_Si, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->CI_RUN); // CI_RUN
			$this->UpdateSort($this->Expedido); // Expedido
			$this->UpdateSort($this->Apellido_Paterno); // Apellido_Paterno
			$this->UpdateSort($this->Apellido_Materno); // Apellido_Materno
			$this->UpdateSort($this->Nombres); // Nombres
			$this->UpdateSort($this->Unidad_Organizacional); // Unidad_Organizacional
			$this->UpdateSort($this->Unidad); // Unidad
			$this->UpdateSort($this->Cargo); // Cargo
			$this->UpdateSort($this->Fiscalia_otro); // Fiscalia_otro
			$this->UpdateSort($this->Salario_Mayor_Si); // Salario_Mayor_Si
			$this->UpdateSort($this->Grado_Si); // Grado_Si
			$this->UpdateSort($this->Actividad_Si); // Actividad_Si
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
				$this->Apellido_Paterno->setSort("");
				$this->Apellido_Materno->setSort("");
				$this->Nombres->setSort("");
				$this->Unidad_Organizacional->setSort("");
				$this->Unidad->setSort("");
				$this->Cargo->setSort("");
				$this->Fiscalia_otro->setSort("");
				$this->Salario_Mayor_Si->setSort("");
				$this->Grado_Si->setSort("");
				$this->Actividad_Si->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fIncompatibilidadlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fIncompatibilidadlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fIncompatibilidadlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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

	// Begin grid
	function MultiColumnBeginGrid() {
		$div = "";

		// Get correct grid count
		if (in_array($this->CurrentAction, array("gridadd", "gridedit"))) { // Grid add/edit
			$cnt = $this->MultiColumnEditCnt;
		} elseif ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline edit row
			$cnt = $this->MultiColumnEditCnt;
		} else {
			$cnt = $this->MultiColumnCnt;
		}
		$this->GridCnt += $cnt;
		$this->ColCnt += 1;
		$this->MultiColumnClass = "col-sm-" . $cnt;

		// Close previous div
		if ($this->GridCnt > 12) {
			$this->GridCnt = $cnt;
			$this->ColCnt = 1;
			$div .= "</div>";
		}

		// Begin new div
		if ($this->ColCnt == 1) {
			$div .= "<div class=\"row ewMultiColumnRow\">";
		}
		return $div;
	}

	// End grid
	function MultiColumnEndGrid() {
		$div = "";

		// Close previous div
		if ($this->GridCnt > 0) {
			$div = "</div>";
		}
		return $div;
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fIncompatibilidadlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		$this->Salario_Mayor_Si->setDbValue($rs->fields('Salario_Mayor_Si'));
		$this->Grado_Si->setDbValue($rs->fields('Grado_Si'));
		$this->Actividad_Si->setDbValue($rs->fields('Actividad_Si'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Expedido->DbValue = $row['Expedido'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
		$this->Unidad->DbValue = $row['Unidad'];
		$this->Cargo->DbValue = $row['Cargo'];
		$this->Fiscalia_otro->DbValue = $row['Fiscalia_otro'];
		$this->Salario_Mayor_Si->DbValue = $row['Salario_Mayor_Si'];
		$this->Grado_Si->DbValue = $row['Grado_Si'];
		$this->Actividad_Si->DbValue = $row['Actividad_Si'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		// Apellido_Paterno
		// Apellido_Materno
		// Nombres
		// Unidad_Organizacional
		// Unidad
		// Cargo
		// Fiscalia_otro
		// Salario_Mayor_Si
		// Grado_Si
		// Actividad_Si

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewCustomAttributes = "";

		// Expedido
		$this->Expedido->ViewValue = $this->Expedido->CurrentValue;
		$this->Expedido->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Unidad
		$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
		$this->Unidad->ViewCustomAttributes = "";

		// Cargo
		$this->Cargo->ViewValue = $this->Cargo->CurrentValue;
		$this->Cargo->ViewCustomAttributes = "";

		// Fiscalia_otro
		$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->ViewCustomAttributes = "";

		// Salario_Mayor_Si
		$this->Salario_Mayor_Si->ViewValue = $this->Salario_Mayor_Si->CurrentValue;
		$this->Salario_Mayor_Si->ViewCustomAttributes = "";

		// Grado_Si
		$this->Grado_Si->ViewValue = $this->Grado_Si->CurrentValue;
		$this->Grado_Si->ViewCustomAttributes = "";

		// Actividad_Si
		$this->Actividad_Si->ViewValue = $this->Actividad_Si->CurrentValue;
		$this->Actividad_Si->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";
			$this->Expedido->TooltipValue = "";

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

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";
			$this->Unidad->TooltipValue = "";

			// Cargo
			$this->Cargo->LinkCustomAttributes = "";
			$this->Cargo->HrefValue = "";
			$this->Cargo->TooltipValue = "";

			// Fiscalia_otro
			$this->Fiscalia_otro->LinkCustomAttributes = "";
			$this->Fiscalia_otro->HrefValue = "";
			$this->Fiscalia_otro->TooltipValue = "";

			// Salario_Mayor_Si
			$this->Salario_Mayor_Si->LinkCustomAttributes = "";
			$this->Salario_Mayor_Si->HrefValue = "";
			$this->Salario_Mayor_Si->TooltipValue = "";

			// Grado_Si
			$this->Grado_Si->LinkCustomAttributes = "";
			$this->Grado_Si->HrefValue = "";
			$this->Grado_Si->TooltipValue = "";

			// Actividad_Si
			$this->Actividad_Si->LinkCustomAttributes = "";
			$this->Actividad_Si->HrefValue = "";
			$this->Actividad_Si->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
if (!isset($Incompatibilidad_list)) $Incompatibilidad_list = new cIncompatibilidad_list();

// Page init
$Incompatibilidad_list->Page_Init();

// Page main
$Incompatibilidad_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Incompatibilidad_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fIncompatibilidadlist = new ew_Form("fIncompatibilidadlist", "list");
fIncompatibilidadlist.FormKeyCountName = '<?php echo $Incompatibilidad_list->FormKeyCountName ?>';

// Form_CustomValidate event
fIncompatibilidadlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fIncompatibilidadlist.ValidateRequired = true;
<?php } else { ?>
fIncompatibilidadlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fIncompatibilidadlistsrch = new ew_Form("fIncompatibilidadlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($Incompatibilidad_list->TotalRecs > 0 && $Incompatibilidad_list->ExportOptions->Visible()) { ?>
<?php $Incompatibilidad_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($Incompatibilidad_list->SearchOptions->Visible()) { ?>
<?php $Incompatibilidad_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($Incompatibilidad_list->FilterOptions->Visible()) { ?>
<?php $Incompatibilidad_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $Incompatibilidad_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($Incompatibilidad_list->TotalRecs <= 0)
			$Incompatibilidad_list->TotalRecs = $Incompatibilidad->SelectRecordCount();
	} else {
		if (!$Incompatibilidad_list->Recordset && ($Incompatibilidad_list->Recordset = $Incompatibilidad_list->LoadRecordset()))
			$Incompatibilidad_list->TotalRecs = $Incompatibilidad_list->Recordset->RecordCount();
	}
	$Incompatibilidad_list->StartRec = 1;
	if ($Incompatibilidad_list->DisplayRecs <= 0 || ($Incompatibilidad->Export <> "" && $Incompatibilidad->ExportAll)) // Display all records
		$Incompatibilidad_list->DisplayRecs = $Incompatibilidad_list->TotalRecs;
	if (!($Incompatibilidad->Export <> "" && $Incompatibilidad->ExportAll))
		$Incompatibilidad_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$Incompatibilidad_list->Recordset = $Incompatibilidad_list->LoadRecordset($Incompatibilidad_list->StartRec-1, $Incompatibilidad_list->DisplayRecs);

	// Set no record found message
	if ($Incompatibilidad->CurrentAction == "" && $Incompatibilidad_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$Incompatibilidad_list->setWarningMessage(ew_DeniedMsg());
		if ($Incompatibilidad_list->SearchWhere == "0=101")
			$Incompatibilidad_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$Incompatibilidad_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$Incompatibilidad_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($Incompatibilidad->Export == "" && $Incompatibilidad->CurrentAction == "") { ?>
<form name="fIncompatibilidadlistsrch" id="fIncompatibilidadlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($Incompatibilidad_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fIncompatibilidadlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="Incompatibilidad">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($Incompatibilidad_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($Incompatibilidad_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $Incompatibilidad_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($Incompatibilidad_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($Incompatibilidad_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($Incompatibilidad_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($Incompatibilidad_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $Incompatibilidad_list->ShowPageHeader(); ?>
<?php
$Incompatibilidad_list->ShowMessage();
?>
<?php if ($Incompatibilidad_list->TotalRecs > 0 || $Incompatibilidad->CurrentAction <> "") { ?>
<div class="ewMultiColumnGrid">
<form name="fIncompatibilidadlist" id="fIncompatibilidadlist" class="form-horizontal ewForm ewListForm ewMultiColumnForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($Incompatibilidad_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $Incompatibilidad_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="Incompatibilidad">
<?php if ($Incompatibilidad_list->TotalRecs > 0 || $Incompatibilidad->CurrentAction == "gridedit") { ?>
<?php
if ($Incompatibilidad->ExportAll && $Incompatibilidad->Export <> "") {
	$Incompatibilidad_list->StopRec = $Incompatibilidad_list->TotalRecs;
} else {

	// Set the last record to display
	if ($Incompatibilidad_list->TotalRecs > $Incompatibilidad_list->StartRec + $Incompatibilidad_list->DisplayRecs - 1)
		$Incompatibilidad_list->StopRec = $Incompatibilidad_list->StartRec + $Incompatibilidad_list->DisplayRecs - 1;
	else
		$Incompatibilidad_list->StopRec = $Incompatibilidad_list->TotalRecs;
}
$Incompatibilidad_list->RecCnt = $Incompatibilidad_list->StartRec - 1;
if ($Incompatibilidad_list->Recordset && !$Incompatibilidad_list->Recordset->EOF) {
	$Incompatibilidad_list->Recordset->MoveFirst();
	$bSelectLimit = $Incompatibilidad_list->UseSelectLimit;
	if (!$bSelectLimit && $Incompatibilidad_list->StartRec > 1)
		$Incompatibilidad_list->Recordset->Move($Incompatibilidad_list->StartRec - 1);
} elseif (!$Incompatibilidad->AllowAddDeleteRow && $Incompatibilidad_list->StopRec == 0) {
	$Incompatibilidad_list->StopRec = $Incompatibilidad->GridAddRowCount;
}
while ($Incompatibilidad_list->RecCnt < $Incompatibilidad_list->StopRec) {
	$Incompatibilidad_list->RecCnt++;
	if (intval($Incompatibilidad_list->RecCnt) >= intval($Incompatibilidad_list->StartRec)) {
		$Incompatibilidad_list->RowCnt++;

		// Set up key count
		$Incompatibilidad_list->KeyCount = $Incompatibilidad_list->RowIndex;

		// Init row class and style
		$Incompatibilidad->ResetAttrs();
		$Incompatibilidad->CssClass = "";
		if ($Incompatibilidad->CurrentAction == "gridadd") {
		} else {
			$Incompatibilidad_list->LoadRowValues($Incompatibilidad_list->Recordset); // Load row values
		}
		$Incompatibilidad->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$Incompatibilidad->RowAttrs = array_merge($Incompatibilidad->RowAttrs, array('data-rowindex'=>$Incompatibilidad_list->RowCnt, 'id'=>'r' . $Incompatibilidad_list->RowCnt . '_Incompatibilidad', 'data-rowtype'=>$Incompatibilidad->RowType));

		// Render row
		$Incompatibilidad_list->RenderRow();

		// Render list options
		$Incompatibilidad_list->RenderListOptions();
?>
<?php echo $Incompatibilidad_list->MultiColumnBeginGrid() ?>
<div class="<?php echo $Incompatibilidad_list->MultiColumnClass ?>"<?php echo $Incompatibilidad->RowAttributes() ?>>
	<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	<table class="table table-bordered table-striped">
	<?php } else { // Add/edit record ?>
	<div>
	<?php } ?>
	<?php if ($Incompatibilidad->CI_RUN->Visible) { // CI_RUN ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_CI_RUN">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->CI_RUN) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->CI_RUN->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->CI_RUN) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->CI_RUN->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->CI_RUN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->CI_RUN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->CI_RUN->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_CI_RUN">
<span<?php echo $Incompatibilidad->CI_RUN->ViewAttributes() ?>>
<?php echo $Incompatibilidad->CI_RUN->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_CI_RUN">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->CI_RUN->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->CI_RUN->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_CI_RUN">
<span<?php echo $Incompatibilidad->CI_RUN->ViewAttributes() ?>>
<?php echo $Incompatibilidad->CI_RUN->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Expedido->Visible) { // Expedido ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Expedido">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Expedido) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Expedido->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Expedido) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Expedido->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Expedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Expedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Expedido->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Expedido">
<span<?php echo $Incompatibilidad->Expedido->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Expedido->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Expedido">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Expedido->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Expedido->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Expedido">
<span<?php echo $Incompatibilidad->Expedido->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Expedido->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Apellido_Paterno">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Apellido_Paterno) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Apellido_Paterno->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Apellido_Paterno) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Apellido_Paterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Apellido_Paterno->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Apellido_Paterno">
<span<?php echo $Incompatibilidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Apellido_Paterno">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Apellido_Paterno->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Apellido_Paterno->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Apellido_Paterno">
<span<?php echo $Incompatibilidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Apellido_Materno">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Apellido_Materno) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Apellido_Materno->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Apellido_Materno) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Apellido_Materno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Apellido_Materno->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Apellido_Materno">
<span<?php echo $Incompatibilidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Apellido_Materno->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Apellido_Materno">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Apellido_Materno->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Apellido_Materno->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Apellido_Materno">
<span<?php echo $Incompatibilidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Apellido_Materno->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Nombres->Visible) { // Nombres ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Nombres">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Nombres) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Nombres->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Nombres) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Nombres->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Nombres->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Nombres">
<span<?php echo $Incompatibilidad->Nombres->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Nombres->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Nombres">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Nombres->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Nombres->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Nombres">
<span<?php echo $Incompatibilidad->Nombres->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Nombres->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Unidad_Organizacional">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Unidad_Organizacional) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Unidad_Organizacional->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Unidad_Organizacional) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Unidad_Organizacional->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Unidad_Organizacional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Unidad_Organizacional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Unidad_Organizacional->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Unidad_Organizacional">
<span<?php echo $Incompatibilidad->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Unidad_Organizacional">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Unidad_Organizacional->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Unidad_Organizacional->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Unidad_Organizacional">
<span<?php echo $Incompatibilidad->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Unidad->Visible) { // Unidad ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Unidad">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Unidad) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Unidad->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Unidad) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Unidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Unidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Unidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Unidad->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Unidad">
<span<?php echo $Incompatibilidad->Unidad->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Unidad->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Unidad">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Unidad->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Unidad->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Unidad">
<span<?php echo $Incompatibilidad->Unidad->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Unidad->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Cargo->Visible) { // Cargo ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Cargo">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Cargo) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Cargo->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Cargo) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Cargo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Cargo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Cargo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Cargo->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Cargo">
<span<?php echo $Incompatibilidad->Cargo->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Cargo->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Cargo">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Cargo->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Cargo->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Cargo">
<span<?php echo $Incompatibilidad->Cargo->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Cargo->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Fiscalia_otro">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Fiscalia_otro) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Fiscalia_otro->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Fiscalia_otro) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Fiscalia_otro->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Fiscalia_otro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Fiscalia_otro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Fiscalia_otro->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Fiscalia_otro">
<span<?php echo $Incompatibilidad->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Fiscalia_otro->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Fiscalia_otro">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Fiscalia_otro->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Fiscalia_otro->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Fiscalia_otro">
<span<?php echo $Incompatibilidad->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Fiscalia_otro->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Salario_Mayor_Si->Visible) { // Salario_Mayor_Si ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Salario_Mayor_Si">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Salario_Mayor_Si) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Salario_Mayor_Si->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Salario_Mayor_Si) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Salario_Mayor_Si->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Salario_Mayor_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Salario_Mayor_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Salario_Mayor_Si->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Salario_Mayor_Si">
<span<?php echo $Incompatibilidad->Salario_Mayor_Si->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Salario_Mayor_Si->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Salario_Mayor_Si">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Salario_Mayor_Si->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Salario_Mayor_Si->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Salario_Mayor_Si">
<span<?php echo $Incompatibilidad->Salario_Mayor_Si->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Salario_Mayor_Si->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Grado_Si->Visible) { // Grado_Si ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Grado_Si">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Grado_Si) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Grado_Si->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Grado_Si) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Grado_Si->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Grado_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Grado_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Grado_Si->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Grado_Si">
<span<?php echo $Incompatibilidad->Grado_Si->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Grado_Si->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Grado_Si">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Grado_Si->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Grado_Si->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Grado_Si">
<span<?php echo $Incompatibilidad->Grado_Si->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Grado_Si->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->Actividad_Si->Visible) { // Actividad_Si ?>
		<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="Incompatibilidad_Actividad_Si">
<?php if ($Incompatibilidad->Export <> "" || $Incompatibilidad->SortUrl($Incompatibilidad->Actividad_Si) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Actividad_Si->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $Incompatibilidad->SortUrl($Incompatibilidad->Actividad_Si) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Incompatibilidad->Actividad_Si->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($Incompatibilidad->Actividad_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Incompatibilidad->Actividad_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $Incompatibilidad->Actividad_Si->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Actividad_Si">
<span<?php echo $Incompatibilidad->Actividad_Si->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Actividad_Si->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group Incompatibilidad_Actividad_Si">
			<label class="col-sm-2 control-label ewLabel"><?php echo $Incompatibilidad->Actividad_Si->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $Incompatibilidad->Actividad_Si->CellAttributes() ?>>
<span id="el<?php echo $Incompatibilidad_list->RowCnt ?>_Incompatibilidad_Actividad_Si">
<span<?php echo $Incompatibilidad->Actividad_Si->ViewAttributes() ?>>
<?php echo $Incompatibilidad->Actividad_Si->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($Incompatibilidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	</table>
	<?php } else { // Add/edit record ?>
	</div>
	<?php } ?>
<div class="ewMultiColumnListOption">
<?php

// Render list options (body, bottom)
$Incompatibilidad_list->ListOptions->Render("body", "", $Incompatibilidad_list->RowCnt);
?>
</div>
<div class="clearfix"></div>
</div>
<?php
	}
	if ($Incompatibilidad->CurrentAction <> "gridadd")
		$Incompatibilidad_list->Recordset->MoveNext();
}
?>
<?php echo $Incompatibilidad_list->MultiColumnEndGrid() ?>
<div class="clearfix"></div>
<?php } ?>
<?php if ($Incompatibilidad->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($Incompatibilidad_list->Recordset)
	$Incompatibilidad_list->Recordset->Close();
?>
<div>
<?php if ($Incompatibilidad->CurrentAction <> "gridadd" && $Incompatibilidad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($Incompatibilidad_list->Pager)) $Incompatibilidad_list->Pager = new cPrevNextPager($Incompatibilidad_list->StartRec, $Incompatibilidad_list->DisplayRecs, $Incompatibilidad_list->TotalRecs) ?>
<?php if ($Incompatibilidad_list->Pager->RecordCount > 0 && $Incompatibilidad_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($Incompatibilidad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $Incompatibilidad_list->PageUrl() ?>start=<?php echo $Incompatibilidad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($Incompatibilidad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $Incompatibilidad_list->PageUrl() ?>start=<?php echo $Incompatibilidad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $Incompatibilidad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($Incompatibilidad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $Incompatibilidad_list->PageUrl() ?>start=<?php echo $Incompatibilidad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($Incompatibilidad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $Incompatibilidad_list->PageUrl() ?>start=<?php echo $Incompatibilidad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $Incompatibilidad_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $Incompatibilidad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $Incompatibilidad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $Incompatibilidad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($Incompatibilidad_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($Incompatibilidad_list->TotalRecs == 0 && $Incompatibilidad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($Incompatibilidad_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fIncompatibilidadlistsrch.FilterList = <?php echo $Incompatibilidad_list->GetFilterList() ?>;
fIncompatibilidadlistsrch.Init();
fIncompatibilidadlist.Init();
</script>
<?php
$Incompatibilidad_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$Incompatibilidad_list->Page_Terminate();
?>
