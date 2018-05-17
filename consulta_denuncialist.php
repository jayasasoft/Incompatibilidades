<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "consulta_denunciainfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$consulta_denuncia_list = NULL; // Initialize page object first

class cconsulta_denuncia_list extends cconsulta_denuncia {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'consulta_denuncia';

	// Page object name
	var $PageObjName = 'consulta_denuncia_list';

	// Grid form hidden field names
	var $FormName = 'fconsulta_denuncialist';
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

		// Table object (consulta_denuncia)
		if (!isset($GLOBALS["consulta_denuncia"]) || get_class($GLOBALS["consulta_denuncia"]) == "cconsulta_denuncia") {
			$GLOBALS["consulta_denuncia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["consulta_denuncia"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "consulta_denunciaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "consulta_denunciadelete.php";
		$this->MultiUpdateUrl = "consulta_denunciaupdate.php";

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'consulta_denuncia', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fconsulta_denuncialistsrch";

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
		$this->CI_RUN->Visible = !$this->IsAddOrEdit();
		$this->Nombres_Apellidos->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Detalles->SetVisibility();
		$this->Fecha_denuncia->SetVisibility();
		$this->Fecha_denuncia->Visible = !$this->IsAddOrEdit();

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
		global $EW_EXPORT, $consulta_denuncia;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($consulta_denuncia);
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
			$this->CI_RUN->setFormValue($arrKeyFlds[0]);
			$this->Nombres_Apellidos->setFormValue($arrKeyFlds[1]);
			$this->Fecha_denuncia->setFormValue($arrKeyFlds[2]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fconsulta_denuncialistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->CI_RUN->AdvancedSearch->ToJSON(), ","); // Field CI_RUN
		$sFilterList = ew_Concat($sFilterList, $this->Nombres_Apellidos->AdvancedSearch->ToJSON(), ","); // Field Nombres_Apellidos
		$sFilterList = ew_Concat($sFilterList, $this->Unidad_Organizacional->AdvancedSearch->ToJSON(), ","); // Field Unidad_Organizacional
		$sFilterList = ew_Concat($sFilterList, $this->Fecha_denuncia->AdvancedSearch->ToJSON(), ","); // Field Fecha_denuncia
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fconsulta_denuncialistsrch", $filters);

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

		// Field Nombres_Apellidos
		$this->Nombres_Apellidos->AdvancedSearch->SearchValue = @$filter["x_Nombres_Apellidos"];
		$this->Nombres_Apellidos->AdvancedSearch->SearchOperator = @$filter["z_Nombres_Apellidos"];
		$this->Nombres_Apellidos->AdvancedSearch->SearchCondition = @$filter["v_Nombres_Apellidos"];
		$this->Nombres_Apellidos->AdvancedSearch->SearchValue2 = @$filter["y_Nombres_Apellidos"];
		$this->Nombres_Apellidos->AdvancedSearch->SearchOperator2 = @$filter["w_Nombres_Apellidos"];
		$this->Nombres_Apellidos->AdvancedSearch->Save();

		// Field Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = @$filter["x_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = @$filter["z_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchCondition = @$filter["v_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue2 = @$filter["y_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator2 = @$filter["w_Unidad_Organizacional"];
		$this->Unidad_Organizacional->AdvancedSearch->Save();

		// Field Fecha_denuncia
		$this->Fecha_denuncia->AdvancedSearch->SearchValue = @$filter["x_Fecha_denuncia"];
		$this->Fecha_denuncia->AdvancedSearch->SearchOperator = @$filter["z_Fecha_denuncia"];
		$this->Fecha_denuncia->AdvancedSearch->SearchCondition = @$filter["v_Fecha_denuncia"];
		$this->Fecha_denuncia->AdvancedSearch->SearchValue2 = @$filter["y_Fecha_denuncia"];
		$this->Fecha_denuncia->AdvancedSearch->SearchOperator2 = @$filter["w_Fecha_denuncia"];
		$this->Fecha_denuncia->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->CI_RUN, $Default, FALSE); // CI_RUN
		$this->BuildSearchSql($sWhere, $this->Nombres_Apellidos, $Default, FALSE); // Nombres_Apellidos
		$this->BuildSearchSql($sWhere, $this->Unidad_Organizacional, $Default, FALSE); // Unidad_Organizacional
		$this->BuildSearchSql($sWhere, $this->Fecha_denuncia, $Default, FALSE); // Fecha_denuncia

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->CI_RUN->AdvancedSearch->Save(); // CI_RUN
			$this->Nombres_Apellidos->AdvancedSearch->Save(); // Nombres_Apellidos
			$this->Unidad_Organizacional->AdvancedSearch->Save(); // Unidad_Organizacional
			$this->Fecha_denuncia->AdvancedSearch->Save(); // Fecha_denuncia
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
		$this->BuildBasicSearchSQL($sWhere, $this->Nombres_Apellidos, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad_Organizacional, $arKeywords, $type);
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
		if ($this->Nombres_Apellidos->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Unidad_Organizacional->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fecha_denuncia->AdvancedSearch->IssetSession())
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
		$this->Nombres_Apellidos->AdvancedSearch->UnsetSession();
		$this->Unidad_Organizacional->AdvancedSearch->UnsetSession();
		$this->Fecha_denuncia->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->CI_RUN->AdvancedSearch->Load();
		$this->Nombres_Apellidos->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Fecha_denuncia->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->CI_RUN); // CI_RUN
			$this->UpdateSort($this->Nombres_Apellidos); // Nombres_Apellidos
			$this->UpdateSort($this->Unidad_Organizacional); // Unidad_Organizacional
			$this->UpdateSort($this->Detalles); // Detalles
			$this->UpdateSort($this->Fecha_denuncia); // Fecha_denuncia
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
				$this->Nombres_Apellidos->setSort("");
				$this->Unidad_Organizacional->setSort("");
				$this->Detalles->setSort("");
				$this->Fecha_denuncia->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->CI_RUN->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Nombres_Apellidos->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Fecha_denuncia->CurrentValue) . "\">";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fconsulta_denuncialistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fconsulta_denuncialistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fconsulta_denuncialist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fconsulta_denuncialistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"consulta_denunciasrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"consulta_denuncia\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,url:'consulta_denunciasrch.php',caption:'" . $Language->Phrase("Search") . "'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"fconsulta_denuncialistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
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

		// Nombres_Apellidos
		$this->Nombres_Apellidos->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nombres_Apellidos"]);
		if ($this->Nombres_Apellidos->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nombres_Apellidos->AdvancedSearch->SearchOperator = @$_GET["z_Nombres_Apellidos"];

		// Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Unidad_Organizacional"]);
		if ($this->Unidad_Organizacional->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = @$_GET["z_Unidad_Organizacional"];

		// Fecha_denuncia
		$this->Fecha_denuncia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fecha_denuncia"]);
		if ($this->Fecha_denuncia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fecha_denuncia->AdvancedSearch->SearchOperator = @$_GET["z_Fecha_denuncia"];
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
		$this->Nombres_Apellidos->setDbValue($rs->fields('Nombres_Apellidos'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Detalles->setDbValue($rs->fields('Detalles'));
		$this->Fecha_denuncia->setDbValue($rs->fields('Fecha_denuncia'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Nombres_Apellidos->DbValue = $row['Nombres_Apellidos'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
		$this->Detalles->DbValue = $row['Detalles'];
		$this->Fecha_denuncia->DbValue = $row['Fecha_denuncia'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("CI_RUN")) <> "")
			$this->CI_RUN->CurrentValue = $this->getKey("CI_RUN"); // CI_RUN
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Nombres_Apellidos")) <> "")
			$this->Nombres_Apellidos->CurrentValue = $this->getKey("Nombres_Apellidos"); // Nombres_Apellidos
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Fecha_denuncia")) <> "")
			$this->Fecha_denuncia->CurrentValue = $this->getKey("Fecha_denuncia"); // Fecha_denuncia
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
		// Nombres_Apellidos

		$this->Nombres_Apellidos->CellCssStyle = "white-space: nowrap;";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->CellCssStyle = "white-space: nowrap;";

		// Detalles
		$this->Detalles->CellCssStyle = "white-space: nowrap;";

		// Fecha_denuncia
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewCustomAttributes = "";

		// Nombres_Apellidos
		$this->Nombres_Apellidos->ViewValue = $this->Nombres_Apellidos->CurrentValue;
		$this->Nombres_Apellidos->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Detalles
		$this->Detalles->ViewValue = $this->Detalles->CurrentValue;
		$this->Detalles->ViewCustomAttributes = "";

		// Fecha_denuncia
		$this->Fecha_denuncia->ViewValue = $this->Fecha_denuncia->CurrentValue;
		$this->Fecha_denuncia->ViewValue = ew_FormatDateTime($this->Fecha_denuncia->ViewValue, 0);
		$this->Fecha_denuncia->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";
			if ($this->Export == "")
				$this->CI_RUN->ViewValue = ew_Highlight($this->HighlightName(), $this->CI_RUN->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->CI_RUN->AdvancedSearch->getValue("x"), "");

			// Nombres_Apellidos
			$this->Nombres_Apellidos->LinkCustomAttributes = "";
			$this->Nombres_Apellidos->HrefValue = "";
			$this->Nombres_Apellidos->TooltipValue = "";
			if ($this->Export == "")
				$this->Nombres_Apellidos->ViewValue = ew_Highlight($this->HighlightName(), $this->Nombres_Apellidos->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Nombres_Apellidos->AdvancedSearch->getValue("x"), "");

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";
			if ($this->Export == "")
				$this->Unidad_Organizacional->ViewValue = ew_Highlight($this->HighlightName(), $this->Unidad_Organizacional->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Unidad_Organizacional->AdvancedSearch->getValue("x"), "");

			// Detalles
			$this->Detalles->LinkCustomAttributes = "";
			$this->Detalles->HrefValue = "";
			$this->Detalles->TooltipValue = "";

			// Fecha_denuncia
			$this->Fecha_denuncia->LinkCustomAttributes = "";
			$this->Fecha_denuncia->HrefValue = "";
			$this->Fecha_denuncia->TooltipValue = "";
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
		$this->Nombres_Apellidos->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Fecha_denuncia->AdvancedSearch->Load();
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
if (!isset($consulta_denuncia_list)) $consulta_denuncia_list = new cconsulta_denuncia_list();

// Page init
$consulta_denuncia_list->Page_Init();

// Page main
$consulta_denuncia_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$consulta_denuncia_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fconsulta_denuncialist = new ew_Form("fconsulta_denuncialist", "list");
fconsulta_denuncialist.FormKeyCountName = '<?php echo $consulta_denuncia_list->FormKeyCountName ?>';

// Form_CustomValidate event
fconsulta_denuncialist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconsulta_denuncialist.ValidateRequired = true;
<?php } else { ?>
fconsulta_denuncialist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fconsulta_denuncialistsrch = new ew_Form("fconsulta_denuncialistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($consulta_denuncia_list->TotalRecs > 0 && $consulta_denuncia_list->ExportOptions->Visible()) { ?>
<?php $consulta_denuncia_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($consulta_denuncia_list->SearchOptions->Visible()) { ?>
<?php $consulta_denuncia_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($consulta_denuncia_list->FilterOptions->Visible()) { ?>
<?php $consulta_denuncia_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $consulta_denuncia_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($consulta_denuncia_list->TotalRecs <= 0)
			$consulta_denuncia_list->TotalRecs = $consulta_denuncia->SelectRecordCount();
	} else {
		if (!$consulta_denuncia_list->Recordset && ($consulta_denuncia_list->Recordset = $consulta_denuncia_list->LoadRecordset()))
			$consulta_denuncia_list->TotalRecs = $consulta_denuncia_list->Recordset->RecordCount();
	}
	$consulta_denuncia_list->StartRec = 1;
	if ($consulta_denuncia_list->DisplayRecs <= 0 || ($consulta_denuncia->Export <> "" && $consulta_denuncia->ExportAll)) // Display all records
		$consulta_denuncia_list->DisplayRecs = $consulta_denuncia_list->TotalRecs;
	if (!($consulta_denuncia->Export <> "" && $consulta_denuncia->ExportAll))
		$consulta_denuncia_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$consulta_denuncia_list->Recordset = $consulta_denuncia_list->LoadRecordset($consulta_denuncia_list->StartRec-1, $consulta_denuncia_list->DisplayRecs);

	// Set no record found message
	if ($consulta_denuncia->CurrentAction == "" && $consulta_denuncia_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$consulta_denuncia_list->setWarningMessage(ew_DeniedMsg());
		if ($consulta_denuncia_list->SearchWhere == "0=101")
			$consulta_denuncia_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$consulta_denuncia_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$consulta_denuncia_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($consulta_denuncia->Export == "" && $consulta_denuncia->CurrentAction == "") { ?>
<form name="fconsulta_denuncialistsrch" id="fconsulta_denuncialistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($consulta_denuncia_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fconsulta_denuncialistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="consulta_denuncia">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($consulta_denuncia_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($consulta_denuncia_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $consulta_denuncia_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($consulta_denuncia_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($consulta_denuncia_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($consulta_denuncia_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($consulta_denuncia_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $consulta_denuncia_list->ShowPageHeader(); ?>
<?php
$consulta_denuncia_list->ShowMessage();
?>
<?php if ($consulta_denuncia_list->TotalRecs > 0 || $consulta_denuncia->CurrentAction <> "") { ?>
<div class="ewMultiColumnGrid">
<form name="fconsulta_denuncialist" id="fconsulta_denuncialist" class="form-horizontal ewForm ewListForm ewMultiColumnForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($consulta_denuncia_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $consulta_denuncia_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="consulta_denuncia">
<?php if ($consulta_denuncia_list->TotalRecs > 0 || $consulta_denuncia->CurrentAction == "gridedit") { ?>
<?php
if ($consulta_denuncia->ExportAll && $consulta_denuncia->Export <> "") {
	$consulta_denuncia_list->StopRec = $consulta_denuncia_list->TotalRecs;
} else {

	// Set the last record to display
	if ($consulta_denuncia_list->TotalRecs > $consulta_denuncia_list->StartRec + $consulta_denuncia_list->DisplayRecs - 1)
		$consulta_denuncia_list->StopRec = $consulta_denuncia_list->StartRec + $consulta_denuncia_list->DisplayRecs - 1;
	else
		$consulta_denuncia_list->StopRec = $consulta_denuncia_list->TotalRecs;
}
$consulta_denuncia_list->RecCnt = $consulta_denuncia_list->StartRec - 1;
if ($consulta_denuncia_list->Recordset && !$consulta_denuncia_list->Recordset->EOF) {
	$consulta_denuncia_list->Recordset->MoveFirst();
	$bSelectLimit = $consulta_denuncia_list->UseSelectLimit;
	if (!$bSelectLimit && $consulta_denuncia_list->StartRec > 1)
		$consulta_denuncia_list->Recordset->Move($consulta_denuncia_list->StartRec - 1);
} elseif (!$consulta_denuncia->AllowAddDeleteRow && $consulta_denuncia_list->StopRec == 0) {
	$consulta_denuncia_list->StopRec = $consulta_denuncia->GridAddRowCount;
}
while ($consulta_denuncia_list->RecCnt < $consulta_denuncia_list->StopRec) {
	$consulta_denuncia_list->RecCnt++;
	if (intval($consulta_denuncia_list->RecCnt) >= intval($consulta_denuncia_list->StartRec)) {
		$consulta_denuncia_list->RowCnt++;

		// Set up key count
		$consulta_denuncia_list->KeyCount = $consulta_denuncia_list->RowIndex;

		// Init row class and style
		$consulta_denuncia->ResetAttrs();
		$consulta_denuncia->CssClass = "";
		if ($consulta_denuncia->CurrentAction == "gridadd") {
		} else {
			$consulta_denuncia_list->LoadRowValues($consulta_denuncia_list->Recordset); // Load row values
		}
		$consulta_denuncia->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$consulta_denuncia->RowAttrs = array_merge($consulta_denuncia->RowAttrs, array('data-rowindex'=>$consulta_denuncia_list->RowCnt, 'id'=>'r' . $consulta_denuncia_list->RowCnt . '_consulta_denuncia', 'data-rowtype'=>$consulta_denuncia->RowType));

		// Render row
		$consulta_denuncia_list->RenderRow();

		// Render list options
		$consulta_denuncia_list->RenderListOptions();
?>
<?php echo $consulta_denuncia_list->MultiColumnBeginGrid() ?>
<div class="<?php echo $consulta_denuncia_list->MultiColumnClass ?>"<?php echo $consulta_denuncia->RowAttributes() ?>>
	<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	<table class="table table-bordered table-striped">
	<?php } else { // Add/edit record ?>
	<div>
	<?php } ?>
	<?php if ($consulta_denuncia->CI_RUN->Visible) { // CI_RUN ?>
		<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="consulta_denuncia_CI_RUN">
<?php if ($consulta_denuncia->Export <> "" || $consulta_denuncia->SortUrl($consulta_denuncia->CI_RUN) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $consulta_denuncia->CI_RUN->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consulta_denuncia->SortUrl($consulta_denuncia->CI_RUN) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta_denuncia->CI_RUN->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consulta_denuncia->CI_RUN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta_denuncia->CI_RUN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $consulta_denuncia->CI_RUN->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_CI_RUN">
<span<?php echo $consulta_denuncia->CI_RUN->ViewAttributes() ?>>
<?php echo $consulta_denuncia->CI_RUN->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group consulta_denuncia_CI_RUN">
			<label class="col-sm-2 control-label ewLabel"><?php echo $consulta_denuncia->CI_RUN->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $consulta_denuncia->CI_RUN->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_CI_RUN">
<span<?php echo $consulta_denuncia->CI_RUN->ViewAttributes() ?>>
<?php echo $consulta_denuncia->CI_RUN->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($consulta_denuncia->Nombres_Apellidos->Visible) { // Nombres_Apellidos ?>
		<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="consulta_denuncia_Nombres_Apellidos">
<?php if ($consulta_denuncia->Export <> "" || $consulta_denuncia->SortUrl($consulta_denuncia->Nombres_Apellidos) == "") { ?>
				<div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $consulta_denuncia->Nombres_Apellidos->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consulta_denuncia->SortUrl($consulta_denuncia->Nombres_Apellidos) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta_denuncia->Nombres_Apellidos->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consulta_denuncia->Nombres_Apellidos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta_denuncia->Nombres_Apellidos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $consulta_denuncia->Nombres_Apellidos->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Nombres_Apellidos">
<span<?php echo $consulta_denuncia->Nombres_Apellidos->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Nombres_Apellidos->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group consulta_denuncia_Nombres_Apellidos">
			<label class="col-sm-2 control-label ewLabel"><?php echo $consulta_denuncia->Nombres_Apellidos->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $consulta_denuncia->Nombres_Apellidos->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Nombres_Apellidos">
<span<?php echo $consulta_denuncia->Nombres_Apellidos->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Nombres_Apellidos->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($consulta_denuncia->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="consulta_denuncia_Unidad_Organizacional">
<?php if ($consulta_denuncia->Export <> "" || $consulta_denuncia->SortUrl($consulta_denuncia->Unidad_Organizacional) == "") { ?>
				<div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $consulta_denuncia->Unidad_Organizacional->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consulta_denuncia->SortUrl($consulta_denuncia->Unidad_Organizacional) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta_denuncia->Unidad_Organizacional->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($consulta_denuncia->Unidad_Organizacional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta_denuncia->Unidad_Organizacional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $consulta_denuncia->Unidad_Organizacional->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Unidad_Organizacional">
<span<?php echo $consulta_denuncia->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group consulta_denuncia_Unidad_Organizacional">
			<label class="col-sm-2 control-label ewLabel"><?php echo $consulta_denuncia->Unidad_Organizacional->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $consulta_denuncia->Unidad_Organizacional->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Unidad_Organizacional">
<span<?php echo $consulta_denuncia->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($consulta_denuncia->Detalles->Visible) { // Detalles ?>
		<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="consulta_denuncia_Detalles">
<?php if ($consulta_denuncia->Export <> "" || $consulta_denuncia->SortUrl($consulta_denuncia->Detalles) == "") { ?>
				<div class="ewTableHeaderCaption" style="white-space: nowrap;"><?php echo $consulta_denuncia->Detalles->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consulta_denuncia->SortUrl($consulta_denuncia->Detalles) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta_denuncia->Detalles->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta_denuncia->Detalles->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta_denuncia->Detalles->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $consulta_denuncia->Detalles->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Detalles">
<span<?php echo $consulta_denuncia->Detalles->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Detalles->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group consulta_denuncia_Detalles">
			<label class="col-sm-2 control-label ewLabel"><?php echo $consulta_denuncia->Detalles->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $consulta_denuncia->Detalles->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Detalles">
<span<?php echo $consulta_denuncia->Detalles->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Detalles->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($consulta_denuncia->Fecha_denuncia->Visible) { // Fecha_denuncia ?>
		<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="consulta_denuncia_Fecha_denuncia">
<?php if ($consulta_denuncia->Export <> "" || $consulta_denuncia->SortUrl($consulta_denuncia->Fecha_denuncia) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $consulta_denuncia->Fecha_denuncia->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $consulta_denuncia->SortUrl($consulta_denuncia->Fecha_denuncia) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $consulta_denuncia->Fecha_denuncia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($consulta_denuncia->Fecha_denuncia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($consulta_denuncia->Fecha_denuncia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $consulta_denuncia->Fecha_denuncia->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Fecha_denuncia">
<span<?php echo $consulta_denuncia->Fecha_denuncia->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Fecha_denuncia->ListViewValue() ?></span>
</span>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group consulta_denuncia_Fecha_denuncia">
			<label class="col-sm-2 control-label ewLabel"><?php echo $consulta_denuncia->Fecha_denuncia->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $consulta_denuncia->Fecha_denuncia->CellAttributes() ?>>
<span id="el<?php echo $consulta_denuncia_list->RowCnt ?>_consulta_denuncia_Fecha_denuncia">
<span<?php echo $consulta_denuncia->Fecha_denuncia->ViewAttributes() ?>>
<?php echo $consulta_denuncia->Fecha_denuncia->ListViewValue() ?></span>
</span>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($consulta_denuncia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	</table>
	<?php } else { // Add/edit record ?>
	</div>
	<?php } ?>
<div class="ewMultiColumnListOption">
<?php

// Render list options (body, bottom)
$consulta_denuncia_list->ListOptions->Render("body", "", $consulta_denuncia_list->RowCnt);
?>
</div>
<div class="clearfix"></div>
</div>
<?php
	}
	if ($consulta_denuncia->CurrentAction <> "gridadd")
		$consulta_denuncia_list->Recordset->MoveNext();
}
?>
<?php echo $consulta_denuncia_list->MultiColumnEndGrid() ?>
<div class="clearfix"></div>
<?php } ?>
<?php if ($consulta_denuncia->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($consulta_denuncia_list->Recordset)
	$consulta_denuncia_list->Recordset->Close();
?>
<div>
<?php if ($consulta_denuncia->CurrentAction <> "gridadd" && $consulta_denuncia->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($consulta_denuncia_list->Pager)) $consulta_denuncia_list->Pager = new cPrevNextPager($consulta_denuncia_list->StartRec, $consulta_denuncia_list->DisplayRecs, $consulta_denuncia_list->TotalRecs) ?>
<?php if ($consulta_denuncia_list->Pager->RecordCount > 0 && $consulta_denuncia_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($consulta_denuncia_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $consulta_denuncia_list->PageUrl() ?>start=<?php echo $consulta_denuncia_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($consulta_denuncia_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $consulta_denuncia_list->PageUrl() ?>start=<?php echo $consulta_denuncia_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $consulta_denuncia_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($consulta_denuncia_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $consulta_denuncia_list->PageUrl() ?>start=<?php echo $consulta_denuncia_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($consulta_denuncia_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $consulta_denuncia_list->PageUrl() ?>start=<?php echo $consulta_denuncia_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $consulta_denuncia_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $consulta_denuncia_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $consulta_denuncia_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $consulta_denuncia_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($consulta_denuncia_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($consulta_denuncia_list->TotalRecs == 0 && $consulta_denuncia->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($consulta_denuncia_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fconsulta_denuncialistsrch.FilterList = <?php echo $consulta_denuncia_list->GetFilterList() ?>;
fconsulta_denuncialistsrch.Init();
fconsulta_denuncialist.Init();
</script>
<?php
$consulta_denuncia_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$consulta_denuncia_list->Page_Terminate();
?>
