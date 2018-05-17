<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "t_conyuguegridcls.php" ?>
<?php include_once "t_pa_consanguinidadgridcls.php" ?>
<?php include_once "t_pa_afinidadgridcls.php" ?>
<?php include_once "t_re_adopciongridcls.php" ?>
<?php include_once "t_mp_si_nogridcls.php" ?>
<?php include_once "t_parientes_mpgridcls.php" ?>
<?php include_once "t_actiividades_remuneradasgridcls.php" ?>
<?php include_once "t_salariogridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_funcionario_list = NULL; // Initialize page object first

class ct_funcionario_list extends ct_funcionario {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_funcionario';

	// Page object name
	var $PageObjName = 't_funcionario_list';

	// Grid form hidden field names
	var $FormName = 'ft_funcionariolist';
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
	var $AuditTrailOnAdd = FALSE;
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

		// Table object (t_funcionario)
		if (!isset($GLOBALS["t_funcionario"]) || get_class($GLOBALS["t_funcionario"]) == "ct_funcionario") {
			$GLOBALS["t_funcionario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_funcionario"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_funcionarioadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_funcionariodelete.php";
		$this->MultiUpdateUrl = "t_funcionarioupdate.php";

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_funcionario', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ft_funcionariolistsrch";

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
		$this->Fecha_Nacimiento->SetVisibility();
		$this->Estado_Civil->SetVisibility();
		$this->Direccion->SetVisibility();
		$this->Telefono->SetVisibility();
		$this->Celular->SetVisibility();
		$this->Fiscalia_otro->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Unidad->SetVisibility();
		$this->Cargo->SetVisibility();

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

			// Process auto fill for detail table 't_conyugue'
			if (@$_POST["grid"] == "ft_conyuguegrid") {
				if (!isset($GLOBALS["t_conyugue_grid"])) $GLOBALS["t_conyugue_grid"] = new ct_conyugue_grid;
				$GLOBALS["t_conyugue_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_pa_consanguinidad'
			if (@$_POST["grid"] == "ft_pa_consanguinidadgrid") {
				if (!isset($GLOBALS["t_pa_consanguinidad_grid"])) $GLOBALS["t_pa_consanguinidad_grid"] = new ct_pa_consanguinidad_grid;
				$GLOBALS["t_pa_consanguinidad_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_pa_afinidad'
			if (@$_POST["grid"] == "ft_pa_afinidadgrid") {
				if (!isset($GLOBALS["t_pa_afinidad_grid"])) $GLOBALS["t_pa_afinidad_grid"] = new ct_pa_afinidad_grid;
				$GLOBALS["t_pa_afinidad_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_re_adopcion'
			if (@$_POST["grid"] == "ft_re_adopciongrid") {
				if (!isset($GLOBALS["t_re_adopcion_grid"])) $GLOBALS["t_re_adopcion_grid"] = new ct_re_adopcion_grid;
				$GLOBALS["t_re_adopcion_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_mp_si_no'
			if (@$_POST["grid"] == "ft_mp_si_nogrid") {
				if (!isset($GLOBALS["t_mp_si_no_grid"])) $GLOBALS["t_mp_si_no_grid"] = new ct_mp_si_no_grid;
				$GLOBALS["t_mp_si_no_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_parientes_mp'
			if (@$_POST["grid"] == "ft_parientes_mpgrid") {
				if (!isset($GLOBALS["t_parientes_mp_grid"])) $GLOBALS["t_parientes_mp_grid"] = new ct_parientes_mp_grid;
				$GLOBALS["t_parientes_mp_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_actiividades_remuneradas'
			if (@$_POST["grid"] == "ft_actiividades_remuneradasgrid") {
				if (!isset($GLOBALS["t_actiividades_remuneradas_grid"])) $GLOBALS["t_actiividades_remuneradas_grid"] = new ct_actiividades_remuneradas_grid;
				$GLOBALS["t_actiividades_remuneradas_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}

			// Process auto fill for detail table 't_salario'
			if (@$_POST["grid"] == "ft_salariogrid") {
				if (!isset($GLOBALS["t_salario_grid"])) $GLOBALS["t_salario_grid"] = new ct_salario_grid;
				$GLOBALS["t_salario_grid"]->Page_Init();
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
		global $EW_EXPORT, $t_funcionario;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_funcionario);
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
	var $DisplayRecs = 25;
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
			$this->DisplayRecs = 25; // Load default
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
		if (count($arrKeyFlds) >= 1) {
			$this->Id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "ft_funcionariolistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->CI_RUN->AdvancedSearch->ToJSON(), ","); // Field CI_RUN
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Paterno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Paterno
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Materno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Materno
		$sFilterList = ew_Concat($sFilterList, $this->Nombres->AdvancedSearch->ToJSON(), ","); // Field Nombres
		$sFilterList = ew_Concat($sFilterList, $this->Fecha_Nacimiento->AdvancedSearch->ToJSON(), ","); // Field Fecha_Nacimiento
		$sFilterList = ew_Concat($sFilterList, $this->Estado_Civil->AdvancedSearch->ToJSON(), ","); // Field Estado_Civil
		$sFilterList = ew_Concat($sFilterList, $this->Direccion->AdvancedSearch->ToJSON(), ","); // Field Direccion
		$sFilterList = ew_Concat($sFilterList, $this->Telefono->AdvancedSearch->ToJSON(), ","); // Field Telefono
		$sFilterList = ew_Concat($sFilterList, $this->Celular->AdvancedSearch->ToJSON(), ","); // Field Celular
		$sFilterList = ew_Concat($sFilterList, $this->Fiscalia_otro->AdvancedSearch->ToJSON(), ","); // Field Fiscalia_otro
		$sFilterList = ew_Concat($sFilterList, $this->Unidad_Organizacional->AdvancedSearch->ToJSON(), ","); // Field Unidad_Organizacional
		$sFilterList = ew_Concat($sFilterList, $this->Unidad->AdvancedSearch->ToJSON(), ","); // Field Unidad
		$sFilterList = ew_Concat($sFilterList, $this->Cargo->AdvancedSearch->ToJSON(), ","); // Field Cargo
		$sFilterList = ew_Concat($sFilterList, $this->Fecha_registro->AdvancedSearch->ToJSON(), ","); // Field Fecha_registro
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ft_funcionariolistsrch", $filters);

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

		// Field Fecha_Nacimiento
		$this->Fecha_Nacimiento->AdvancedSearch->SearchValue = @$filter["x_Fecha_Nacimiento"];
		$this->Fecha_Nacimiento->AdvancedSearch->SearchOperator = @$filter["z_Fecha_Nacimiento"];
		$this->Fecha_Nacimiento->AdvancedSearch->SearchCondition = @$filter["v_Fecha_Nacimiento"];
		$this->Fecha_Nacimiento->AdvancedSearch->SearchValue2 = @$filter["y_Fecha_Nacimiento"];
		$this->Fecha_Nacimiento->AdvancedSearch->SearchOperator2 = @$filter["w_Fecha_Nacimiento"];
		$this->Fecha_Nacimiento->AdvancedSearch->Save();

		// Field Estado_Civil
		$this->Estado_Civil->AdvancedSearch->SearchValue = @$filter["x_Estado_Civil"];
		$this->Estado_Civil->AdvancedSearch->SearchOperator = @$filter["z_Estado_Civil"];
		$this->Estado_Civil->AdvancedSearch->SearchCondition = @$filter["v_Estado_Civil"];
		$this->Estado_Civil->AdvancedSearch->SearchValue2 = @$filter["y_Estado_Civil"];
		$this->Estado_Civil->AdvancedSearch->SearchOperator2 = @$filter["w_Estado_Civil"];
		$this->Estado_Civil->AdvancedSearch->Save();

		// Field Direccion
		$this->Direccion->AdvancedSearch->SearchValue = @$filter["x_Direccion"];
		$this->Direccion->AdvancedSearch->SearchOperator = @$filter["z_Direccion"];
		$this->Direccion->AdvancedSearch->SearchCondition = @$filter["v_Direccion"];
		$this->Direccion->AdvancedSearch->SearchValue2 = @$filter["y_Direccion"];
		$this->Direccion->AdvancedSearch->SearchOperator2 = @$filter["w_Direccion"];
		$this->Direccion->AdvancedSearch->Save();

		// Field Telefono
		$this->Telefono->AdvancedSearch->SearchValue = @$filter["x_Telefono"];
		$this->Telefono->AdvancedSearch->SearchOperator = @$filter["z_Telefono"];
		$this->Telefono->AdvancedSearch->SearchCondition = @$filter["v_Telefono"];
		$this->Telefono->AdvancedSearch->SearchValue2 = @$filter["y_Telefono"];
		$this->Telefono->AdvancedSearch->SearchOperator2 = @$filter["w_Telefono"];
		$this->Telefono->AdvancedSearch->Save();

		// Field Celular
		$this->Celular->AdvancedSearch->SearchValue = @$filter["x_Celular"];
		$this->Celular->AdvancedSearch->SearchOperator = @$filter["z_Celular"];
		$this->Celular->AdvancedSearch->SearchCondition = @$filter["v_Celular"];
		$this->Celular->AdvancedSearch->SearchValue2 = @$filter["y_Celular"];
		$this->Celular->AdvancedSearch->SearchOperator2 = @$filter["w_Celular"];
		$this->Celular->AdvancedSearch->Save();

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

		// Field Fecha_registro
		$this->Fecha_registro->AdvancedSearch->SearchValue = @$filter["x_Fecha_registro"];
		$this->Fecha_registro->AdvancedSearch->SearchOperator = @$filter["z_Fecha_registro"];
		$this->Fecha_registro->AdvancedSearch->SearchCondition = @$filter["v_Fecha_registro"];
		$this->Fecha_registro->AdvancedSearch->SearchValue2 = @$filter["y_Fecha_registro"];
		$this->Fecha_registro->AdvancedSearch->SearchOperator2 = @$filter["w_Fecha_registro"];
		$this->Fecha_registro->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->CI_RUN, $Default, FALSE); // CI_RUN
		$this->BuildSearchSql($sWhere, $this->Apellido_Paterno, $Default, FALSE); // Apellido_Paterno
		$this->BuildSearchSql($sWhere, $this->Apellido_Materno, $Default, FALSE); // Apellido_Materno
		$this->BuildSearchSql($sWhere, $this->Nombres, $Default, FALSE); // Nombres
		$this->BuildSearchSql($sWhere, $this->Fecha_Nacimiento, $Default, FALSE); // Fecha_Nacimiento
		$this->BuildSearchSql($sWhere, $this->Estado_Civil, $Default, FALSE); // Estado_Civil
		$this->BuildSearchSql($sWhere, $this->Direccion, $Default, FALSE); // Direccion
		$this->BuildSearchSql($sWhere, $this->Telefono, $Default, FALSE); // Telefono
		$this->BuildSearchSql($sWhere, $this->Celular, $Default, FALSE); // Celular
		$this->BuildSearchSql($sWhere, $this->Fiscalia_otro, $Default, FALSE); // Fiscalia_otro
		$this->BuildSearchSql($sWhere, $this->Unidad_Organizacional, $Default, FALSE); // Unidad_Organizacional
		$this->BuildSearchSql($sWhere, $this->Unidad, $Default, FALSE); // Unidad
		$this->BuildSearchSql($sWhere, $this->Cargo, $Default, FALSE); // Cargo
		$this->BuildSearchSql($sWhere, $this->Fecha_registro, $Default, FALSE); // Fecha_registro

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->CI_RUN->AdvancedSearch->Save(); // CI_RUN
			$this->Apellido_Paterno->AdvancedSearch->Save(); // Apellido_Paterno
			$this->Apellido_Materno->AdvancedSearch->Save(); // Apellido_Materno
			$this->Nombres->AdvancedSearch->Save(); // Nombres
			$this->Fecha_Nacimiento->AdvancedSearch->Save(); // Fecha_Nacimiento
			$this->Estado_Civil->AdvancedSearch->Save(); // Estado_Civil
			$this->Direccion->AdvancedSearch->Save(); // Direccion
			$this->Telefono->AdvancedSearch->Save(); // Telefono
			$this->Celular->AdvancedSearch->Save(); // Celular
			$this->Fiscalia_otro->AdvancedSearch->Save(); // Fiscalia_otro
			$this->Unidad_Organizacional->AdvancedSearch->Save(); // Unidad_Organizacional
			$this->Unidad->AdvancedSearch->Save(); // Unidad
			$this->Cargo->AdvancedSearch->Save(); // Cargo
			$this->Fecha_registro->AdvancedSearch->Save(); // Fecha_registro
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
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Paterno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Apellido_Materno, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Nombres, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Estado_Civil, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Direccion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Telefono, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Celular, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Fiscalia_otro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad_Organizacional, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Unidad, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Cargo, $arKeywords, $type);
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
		if ($this->Apellido_Paterno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Apellido_Materno->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Nombres->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fecha_Nacimiento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Estado_Civil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Direccion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Telefono->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Celular->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fiscalia_otro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Unidad_Organizacional->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Unidad->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Cargo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Fecha_registro->AdvancedSearch->IssetSession())
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
		$this->Apellido_Paterno->AdvancedSearch->UnsetSession();
		$this->Apellido_Materno->AdvancedSearch->UnsetSession();
		$this->Nombres->AdvancedSearch->UnsetSession();
		$this->Fecha_Nacimiento->AdvancedSearch->UnsetSession();
		$this->Estado_Civil->AdvancedSearch->UnsetSession();
		$this->Direccion->AdvancedSearch->UnsetSession();
		$this->Telefono->AdvancedSearch->UnsetSession();
		$this->Celular->AdvancedSearch->UnsetSession();
		$this->Fiscalia_otro->AdvancedSearch->UnsetSession();
		$this->Unidad_Organizacional->AdvancedSearch->UnsetSession();
		$this->Unidad->AdvancedSearch->UnsetSession();
		$this->Cargo->AdvancedSearch->UnsetSession();
		$this->Fecha_registro->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->CI_RUN->AdvancedSearch->Load();
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Nombres->AdvancedSearch->Load();
		$this->Fecha_Nacimiento->AdvancedSearch->Load();
		$this->Estado_Civil->AdvancedSearch->Load();
		$this->Direccion->AdvancedSearch->Load();
		$this->Telefono->AdvancedSearch->Load();
		$this->Celular->AdvancedSearch->Load();
		$this->Fiscalia_otro->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Unidad->AdvancedSearch->Load();
		$this->Cargo->AdvancedSearch->Load();
		$this->Fecha_registro->AdvancedSearch->Load();
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
			$this->UpdateSort($this->Fecha_Nacimiento); // Fecha_Nacimiento
			$this->UpdateSort($this->Estado_Civil); // Estado_Civil
			$this->UpdateSort($this->Direccion); // Direccion
			$this->UpdateSort($this->Telefono); // Telefono
			$this->UpdateSort($this->Celular); // Celular
			$this->UpdateSort($this->Fiscalia_otro); // Fiscalia_otro
			$this->UpdateSort($this->Unidad_Organizacional); // Unidad_Organizacional
			$this->UpdateSort($this->Unidad); // Unidad
			$this->UpdateSort($this->Cargo); // Cargo
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
				$this->setSessionOrderByList($sOrderBy);
				$this->CI_RUN->setSort("");
				$this->Expedido->setSort("");
				$this->Apellido_Paterno->setSort("");
				$this->Apellido_Materno->setSort("");
				$this->Nombres->setSort("");
				$this->Fecha_Nacimiento->setSort("");
				$this->Estado_Civil->setSort("");
				$this->Direccion->setSort("");
				$this->Telefono->setSort("");
				$this->Celular->setSort("");
				$this->Fiscalia_otro->setSort("");
				$this->Unidad_Organizacional->setSort("");
				$this->Unidad->setSort("");
				$this->Cargo->setSort("");
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

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "detail_t_conyugue"
		$item = &$this->ListOptions->Add("detail_t_conyugue");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_conyugue') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_conyugue_grid"])) $GLOBALS["t_conyugue_grid"] = new ct_conyugue_grid;

		// "detail_t_pa_consanguinidad"
		$item = &$this->ListOptions->Add("detail_t_pa_consanguinidad");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_pa_consanguinidad') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_pa_consanguinidad_grid"])) $GLOBALS["t_pa_consanguinidad_grid"] = new ct_pa_consanguinidad_grid;

		// "detail_t_pa_afinidad"
		$item = &$this->ListOptions->Add("detail_t_pa_afinidad");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_pa_afinidad') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_pa_afinidad_grid"])) $GLOBALS["t_pa_afinidad_grid"] = new ct_pa_afinidad_grid;

		// "detail_t_re_adopcion"
		$item = &$this->ListOptions->Add("detail_t_re_adopcion");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_re_adopcion') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_re_adopcion_grid"])) $GLOBALS["t_re_adopcion_grid"] = new ct_re_adopcion_grid;

		// "detail_t_mp_si_no"
		$item = &$this->ListOptions->Add("detail_t_mp_si_no");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_mp_si_no') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_mp_si_no_grid"])) $GLOBALS["t_mp_si_no_grid"] = new ct_mp_si_no_grid;

		// "detail_t_parientes_mp"
		$item = &$this->ListOptions->Add("detail_t_parientes_mp");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_parientes_mp') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_parientes_mp_grid"])) $GLOBALS["t_parientes_mp_grid"] = new ct_parientes_mp_grid;

		// "detail_t_actiividades_remuneradas"
		$item = &$this->ListOptions->Add("detail_t_actiividades_remuneradas");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_actiividades_remuneradas') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_actiividades_remuneradas_grid"])) $GLOBALS["t_actiividades_remuneradas_grid"] = new ct_actiividades_remuneradas_grid;

		// "detail_t_salario"
		$item = &$this->ListOptions->Add("detail_t_salario");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_salario') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_salario_grid"])) $GLOBALS["t_salario_grid"] = new ct_salario_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("t_conyugue");
		$pages->Add("t_pa_consanguinidad");
		$pages->Add("t_pa_afinidad");
		$pages->Add("t_re_adopcion");
		$pages->Add("t_mp_si_no");
		$pages->Add("t_parientes_mp");
		$pages->Add("t_actiividades_remuneradas");
		$pages->Add("t_salario");
		$this->DetailPages = $pages;

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

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_t_conyugue"
		$oListOpt = &$this->ListOptions->Items["detail_t_conyugue"];
		if ($Security->AllowList(CurrentProjectID() . 't_conyugue')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_conyugue", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_conyuguelist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_conyugue_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_conyugue')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_conyugue")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_conyugue";
			}
			if ($GLOBALS["t_conyugue_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_conyugue')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_conyugue")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_conyugue";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_pa_consanguinidad"
		$oListOpt = &$this->ListOptions->Items["detail_t_pa_consanguinidad"];
		if ($Security->AllowList(CurrentProjectID() . 't_pa_consanguinidad')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_pa_consanguinidad", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_pa_consanguinidadlist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_pa_consanguinidad_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_pa_consanguinidad')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_consanguinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_pa_consanguinidad";
			}
			if ($GLOBALS["t_pa_consanguinidad_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_pa_consanguinidad')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_consanguinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_pa_consanguinidad";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_pa_afinidad"
		$oListOpt = &$this->ListOptions->Items["detail_t_pa_afinidad"];
		if ($Security->AllowList(CurrentProjectID() . 't_pa_afinidad')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_pa_afinidad", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_pa_afinidadlist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_pa_afinidad_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_pa_afinidad')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_afinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_pa_afinidad";
			}
			if ($GLOBALS["t_pa_afinidad_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_pa_afinidad')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_afinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_pa_afinidad";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_re_adopcion"
		$oListOpt = &$this->ListOptions->Items["detail_t_re_adopcion"];
		if ($Security->AllowList(CurrentProjectID() . 't_re_adopcion')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_re_adopcion", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_re_adopcionlist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_re_adopcion_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_re_adopcion')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_re_adopcion")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_re_adopcion";
			}
			if ($GLOBALS["t_re_adopcion_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_re_adopcion')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_re_adopcion")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_re_adopcion";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_mp_si_no"
		$oListOpt = &$this->ListOptions->Items["detail_t_mp_si_no"];
		if ($Security->AllowList(CurrentProjectID() . 't_mp_si_no')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_mp_si_no", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_mp_si_nolist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_mp_si_no_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_mp_si_no')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_mp_si_no")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_mp_si_no";
			}
			if ($GLOBALS["t_mp_si_no_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_mp_si_no')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_mp_si_no")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_mp_si_no";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_parientes_mp"
		$oListOpt = &$this->ListOptions->Items["detail_t_parientes_mp"];
		if ($Security->AllowList(CurrentProjectID() . 't_parientes_mp')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_parientes_mp", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_parientes_mplist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_parientes_mp_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_parientes_mp')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_parientes_mp")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_parientes_mp";
			}
			if ($GLOBALS["t_parientes_mp_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_parientes_mp')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_parientes_mp")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_parientes_mp";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_actiividades_remuneradas"
		$oListOpt = &$this->ListOptions->Items["detail_t_actiividades_remuneradas"];
		if ($Security->AllowList(CurrentProjectID() . 't_actiividades_remuneradas')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_actiividades_remuneradas", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_actiividades_remuneradaslist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_actiividades_remuneradas_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_actiividades_remuneradas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_actiividades_remuneradas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_actiividades_remuneradas";
			}
			if ($GLOBALS["t_actiividades_remuneradas_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_actiividades_remuneradas')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_actiividades_remuneradas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_actiividades_remuneradas";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_t_salario"
		$oListOpt = &$this->ListOptions->Items["detail_t_salario"];
		if ($Security->AllowList(CurrentProjectID() . 't_salario')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_salario", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_salariolist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_salario_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_salario')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_salario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_salario";
			}
			if ($GLOBALS["t_salario_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_salario')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_salario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_salario";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ft_funcionariolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ft_funcionariolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ft_funcionariolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ft_funcionariolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"t_funcionariosrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"t_funcionario\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,url:'t_funcionariosrch.php',caption:'" . $Language->Phrase("Search") . "'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Search highlight button
		$item = &$this->SearchOptions->Add("searchhighlight");
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewHighlight active\" title=\"" . $Language->Phrase("Highlight") . "\" data-caption=\"" . $Language->Phrase("Highlight") . "\" data-toggle=\"button\" data-form=\"ft_funcionariolistsrch\" data-name=\"" . $this->HighlightName() . "\">" . $Language->Phrase("HighlightBtn") . "</button>";
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

		// Apellido_Paterno
		$this->Apellido_Paterno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Apellido_Paterno"]);
		if ($this->Apellido_Paterno->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator = @$_GET["z_Apellido_Paterno"];

		// Apellido_Materno
		$this->Apellido_Materno->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Apellido_Materno"]);
		if ($this->Apellido_Materno->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Apellido_Materno->AdvancedSearch->SearchOperator = @$_GET["z_Apellido_Materno"];

		// Nombres
		$this->Nombres->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Nombres"]);
		if ($this->Nombres->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Nombres->AdvancedSearch->SearchOperator = @$_GET["z_Nombres"];

		// Fecha_Nacimiento
		$this->Fecha_Nacimiento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fecha_Nacimiento"]);
		if ($this->Fecha_Nacimiento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fecha_Nacimiento->AdvancedSearch->SearchOperator = @$_GET["z_Fecha_Nacimiento"];

		// Estado_Civil
		$this->Estado_Civil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Estado_Civil"]);
		if ($this->Estado_Civil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Estado_Civil->AdvancedSearch->SearchOperator = @$_GET["z_Estado_Civil"];

		// Direccion
		$this->Direccion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Direccion"]);
		if ($this->Direccion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Direccion->AdvancedSearch->SearchOperator = @$_GET["z_Direccion"];

		// Telefono
		$this->Telefono->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Telefono"]);
		if ($this->Telefono->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Telefono->AdvancedSearch->SearchOperator = @$_GET["z_Telefono"];

		// Celular
		$this->Celular->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Celular"]);
		if ($this->Celular->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Celular->AdvancedSearch->SearchOperator = @$_GET["z_Celular"];

		// Fiscalia_otro
		$this->Fiscalia_otro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fiscalia_otro"]);
		if ($this->Fiscalia_otro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator = @$_GET["z_Fiscalia_otro"];

		// Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Unidad_Organizacional"]);
		if ($this->Unidad_Organizacional->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = @$_GET["z_Unidad_Organizacional"];

		// Unidad
		$this->Unidad->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Unidad"]);
		if ($this->Unidad->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Unidad->AdvancedSearch->SearchOperator = @$_GET["z_Unidad"];

		// Cargo
		$this->Cargo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Cargo"]);
		if ($this->Cargo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Cargo->AdvancedSearch->SearchOperator = @$_GET["z_Cargo"];

		// Fecha_registro
		$this->Fecha_registro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Fecha_registro"]);
		if ($this->Fecha_registro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Fecha_registro->AdvancedSearch->SearchOperator = @$_GET["z_Fecha_registro"];
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->CI_RUN->setDbValue($rs->fields('CI_RUN'));
		$this->Expedido->setDbValue($rs->fields('Expedido'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Fecha_Nacimiento->setDbValue($rs->fields('Fecha_Nacimiento'));
		$this->Estado_Civil->setDbValue($rs->fields('Estado_Civil'));
		$this->Direccion->setDbValue($rs->fields('Direccion'));
		$this->Telefono->setDbValue($rs->fields('Telefono'));
		$this->Celular->setDbValue($rs->fields('Celular'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		if (array_key_exists('EV__Fiscalia_otro', $rs->fields)) {
			$this->Fiscalia_otro->VirtualValue = $rs->fields('EV__Fiscalia_otro'); // Set up virtual field value
		} else {
			$this->Fiscalia_otro->VirtualValue = ""; // Clear value
		}
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
		$this->Fecha_registro->setDbValue($rs->fields('Fecha_registro'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->CI_RUN->DbValue = $row['CI_RUN'];
		$this->Expedido->DbValue = $row['Expedido'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Nombres->DbValue = $row['Nombres'];
		$this->Fecha_Nacimiento->DbValue = $row['Fecha_Nacimiento'];
		$this->Estado_Civil->DbValue = $row['Estado_Civil'];
		$this->Direccion->DbValue = $row['Direccion'];
		$this->Telefono->DbValue = $row['Telefono'];
		$this->Celular->DbValue = $row['Celular'];
		$this->Fiscalia_otro->DbValue = $row['Fiscalia_otro'];
		$this->Unidad_Organizacional->DbValue = $row['Unidad_Organizacional'];
		$this->Unidad->DbValue = $row['Unidad'];
		$this->Cargo->DbValue = $row['Cargo'];
		$this->Fecha_registro->DbValue = $row['Fecha_registro'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
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
		// Id

		$this->Id->CellCssStyle = "white-space: nowrap;";

		// CI_RUN
		// Expedido
		// Apellido_Paterno
		// Apellido_Materno
		// Nombres
		// Fecha_Nacimiento
		// Estado_Civil
		// Direccion
		// Telefono
		// Celular
		// Fiscalia_otro
		// Unidad_Organizacional
		// Unidad
		// Cargo
		// Fecha_registro

		$this->Fecha_registro->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewValue = ew_FormatNumber($this->CI_RUN->ViewValue, 0, 0, 0, 0);
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

		// Fecha_Nacimiento
		$this->Fecha_Nacimiento->ViewValue = $this->Fecha_Nacimiento->CurrentValue;
		$this->Fecha_Nacimiento->ViewValue = ew_FormatDateTime($this->Fecha_Nacimiento->ViewValue, 13);
		$this->Fecha_Nacimiento->ViewCustomAttributes = "";

		// Estado_Civil
		if (strval($this->Estado_Civil->CurrentValue) <> "") {
			$this->Estado_Civil->ViewValue = $this->Estado_Civil->OptionCaption($this->Estado_Civil->CurrentValue);
		} else {
			$this->Estado_Civil->ViewValue = NULL;
		}
		$this->Estado_Civil->ViewCustomAttributes = "";

		// Direccion
		$this->Direccion->ViewValue = $this->Direccion->CurrentValue;
		$this->Direccion->ViewCustomAttributes = "";

		// Telefono
		$this->Telefono->ViewValue = $this->Telefono->CurrentValue;
		$this->Telefono->ViewCustomAttributes = "";

		// Celular
		$this->Celular->ViewValue = $this->Celular->CurrentValue;
		$this->Celular->ViewCustomAttributes = "";

		// Fiscalia_otro
		if ($this->Fiscalia_otro->VirtualValue <> "") {
			$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->VirtualValue;
		} else {
		if (strval($this->Fiscalia_otro->CurrentValue) <> "") {
			$sFilterWrk = "`Fiscalia`" . ew_SearchString("=", $this->Fiscalia_otro->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Fiscalia`, `Fiscalia` AS `DispFld`, `Unidad_Organizacional` AS `Disp2Fld`, `Unidad` AS `Disp3Fld`, `Cargo` AS `Disp4Fld` FROM `seleccion_cargos`";
		$sWhereWrk = "";
		$this->Fiscalia_otro->LookupFilters = array("dx1" => '`Fiscalia`', "dx2" => '`Unidad_Organizacional`', "dx3" => '`Unidad`', "dx4" => '`Cargo`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Fiscalia_otro, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$arwrk[4] = $rswrk->fields('Disp4Fld');
				$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
			}
		} else {
			$this->Fiscalia_otro->ViewValue = NULL;
		}
		}
		$this->Fiscalia_otro->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Unidad
		$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
		$this->Unidad->ViewCustomAttributes = "";

		// Cargo
		$this->Cargo->ViewValue = $this->Cargo->CurrentValue;
		$this->Cargo->ViewCustomAttributes = "";

		// Fecha_registro
		$this->Fecha_registro->ViewValue = $this->Fecha_registro->CurrentValue;
		$this->Fecha_registro->ViewValue = ew_FormatDateTime($this->Fecha_registro->ViewValue, 0);
		$this->Fecha_registro->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";
			$this->Expedido->TooltipValue = "";
			if ($this->Export == "")
				$this->Expedido->ViewValue = ew_Highlight($this->HighlightName(), $this->Expedido->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), "", "");

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

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";
			$this->Nombres->TooltipValue = "";
			if ($this->Export == "")
				$this->Nombres->ViewValue = ew_Highlight($this->HighlightName(), $this->Nombres->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Nombres->AdvancedSearch->getValue("x"), "");

			// Fecha_Nacimiento
			$this->Fecha_Nacimiento->LinkCustomAttributes = "";
			$this->Fecha_Nacimiento->HrefValue = "";
			$this->Fecha_Nacimiento->TooltipValue = "";

			// Estado_Civil
			$this->Estado_Civil->LinkCustomAttributes = "";
			$this->Estado_Civil->HrefValue = "";
			$this->Estado_Civil->TooltipValue = "";

			// Direccion
			$this->Direccion->LinkCustomAttributes = "";
			$this->Direccion->HrefValue = "";
			$this->Direccion->TooltipValue = "";
			if ($this->Export == "")
				$this->Direccion->ViewValue = ew_Highlight($this->HighlightName(), $this->Direccion->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Direccion->AdvancedSearch->getValue("x"), "");

			// Telefono
			$this->Telefono->LinkCustomAttributes = "";
			$this->Telefono->HrefValue = "";
			$this->Telefono->TooltipValue = "";

			// Celular
			$this->Celular->LinkCustomAttributes = "";
			$this->Celular->HrefValue = "";
			$this->Celular->TooltipValue = "";
			if ($this->Export == "")
				$this->Celular->ViewValue = ew_Highlight($this->HighlightName(), $this->Celular->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Celular->AdvancedSearch->getValue("x"), "");

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

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";
			$this->Unidad->TooltipValue = "";
			if ($this->Export == "")
				$this->Unidad->ViewValue = ew_Highlight($this->HighlightName(), $this->Unidad->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Unidad->AdvancedSearch->getValue("x"), "");

			// Cargo
			$this->Cargo->LinkCustomAttributes = "";
			$this->Cargo->HrefValue = "";
			$this->Cargo->TooltipValue = "";
			if ($this->Export == "")
				$this->Cargo->ViewValue = ew_Highlight($this->HighlightName(), $this->Cargo->ViewValue, $this->BasicSearch->getKeyword(), $this->BasicSearch->getType(), $this->Cargo->AdvancedSearch->getValue("x"), "");
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
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Nombres->AdvancedSearch->Load();
		$this->Fecha_Nacimiento->AdvancedSearch->Load();
		$this->Estado_Civil->AdvancedSearch->Load();
		$this->Direccion->AdvancedSearch->Load();
		$this->Telefono->AdvancedSearch->Load();
		$this->Celular->AdvancedSearch->Load();
		$this->Fiscalia_otro->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Unidad->AdvancedSearch->Load();
		$this->Cargo->AdvancedSearch->Load();
		$this->Fecha_registro->AdvancedSearch->Load();
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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_funcionario';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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
if (!isset($t_funcionario_list)) $t_funcionario_list = new ct_funcionario_list();

// Page init
$t_funcionario_list->Page_Init();

// Page main
$t_funcionario_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_funcionario_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ft_funcionariolist = new ew_Form("ft_funcionariolist", "list");
ft_funcionariolist.FormKeyCountName = '<?php echo $t_funcionario_list->FormKeyCountName ?>';

// Form_CustomValidate event
ft_funcionariolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_funcionariolist.ValidateRequired = true;
<?php } else { ?>
ft_funcionariolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_funcionariolist.Lists["x_Estado_Civil"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_funcionariolist.Lists["x_Estado_Civil"].Options = <?php echo json_encode($t_funcionario->Estado_Civil->Options()) ?>;
ft_funcionariolist.Lists["x_Fiscalia_otro"] = {"LinkField":"x_Fiscalia","Ajax":true,"AutoFill":false,"DisplayFields":["x_Fiscalia","x_Unidad_Organizacional","x_Unidad","x_Cargo"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"seleccion_cargos"};

// Form object for search
var CurrentSearchForm = ft_funcionariolistsrch = new ew_Form("ft_funcionariolistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($t_funcionario_list->TotalRecs > 0 && $t_funcionario_list->ExportOptions->Visible()) { ?>
<?php $t_funcionario_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($t_funcionario_list->SearchOptions->Visible()) { ?>
<?php $t_funcionario_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($t_funcionario_list->FilterOptions->Visible()) { ?>
<?php $t_funcionario_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $t_funcionario_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_funcionario_list->TotalRecs <= 0)
			$t_funcionario_list->TotalRecs = $t_funcionario->SelectRecordCount();
	} else {
		if (!$t_funcionario_list->Recordset && ($t_funcionario_list->Recordset = $t_funcionario_list->LoadRecordset()))
			$t_funcionario_list->TotalRecs = $t_funcionario_list->Recordset->RecordCount();
	}
	$t_funcionario_list->StartRec = 1;
	if ($t_funcionario_list->DisplayRecs <= 0 || ($t_funcionario->Export <> "" && $t_funcionario->ExportAll)) // Display all records
		$t_funcionario_list->DisplayRecs = $t_funcionario_list->TotalRecs;
	if (!($t_funcionario->Export <> "" && $t_funcionario->ExportAll))
		$t_funcionario_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_funcionario_list->Recordset = $t_funcionario_list->LoadRecordset($t_funcionario_list->StartRec-1, $t_funcionario_list->DisplayRecs);

	// Set no record found message
	if ($t_funcionario->CurrentAction == "" && $t_funcionario_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_funcionario_list->setWarningMessage(ew_DeniedMsg());
		if ($t_funcionario_list->SearchWhere == "0=101")
			$t_funcionario_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_funcionario_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($t_funcionario_list->AuditTrailOnSearch && $t_funcionario_list->Command == "search" && !$t_funcionario_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $t_funcionario_list->getSessionWhere();
		$t_funcionario_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$t_funcionario_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($t_funcionario->Export == "" && $t_funcionario->CurrentAction == "") { ?>
<form name="ft_funcionariolistsrch" id="ft_funcionariolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($t_funcionario_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ft_funcionariolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="t_funcionario">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($t_funcionario_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($t_funcionario_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $t_funcionario_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($t_funcionario_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($t_funcionario_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($t_funcionario_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($t_funcionario_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $t_funcionario_list->ShowPageHeader(); ?>
<?php
$t_funcionario_list->ShowMessage();
?>
<?php if ($t_funcionario_list->TotalRecs > 0 || $t_funcionario->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_funcionario">
<form name="ft_funcionariolist" id="ft_funcionariolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_funcionario_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_funcionario_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_funcionario">
<div id="gmp_t_funcionario" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($t_funcionario_list->TotalRecs > 0 || $t_funcionario->CurrentAction == "gridedit") { ?>
<table id="tbl_t_funcionariolist" class="table ewTable">
<?php echo $t_funcionario->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_funcionario_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_funcionario_list->RenderListOptions();

// Render list options (header, left)
$t_funcionario_list->ListOptions->Render("header", "left");
?>
<?php if ($t_funcionario->CI_RUN->Visible) { // CI_RUN ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->CI_RUN) == "") { ?>
		<th data-name="CI_RUN"><div id="elh_t_funcionario_CI_RUN" class="t_funcionario_CI_RUN"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->CI_RUN->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="CI_RUN"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->CI_RUN) ?>',1);"><div id="elh_t_funcionario_CI_RUN" class="t_funcionario_CI_RUN">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->CI_RUN->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->CI_RUN->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->CI_RUN->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Expedido->Visible) { // Expedido ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Expedido) == "") { ?>
		<th data-name="Expedido"><div id="elh_t_funcionario_Expedido" class="t_funcionario_Expedido"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Expedido->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Expedido"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Expedido) ?>',1);"><div id="elh_t_funcionario_Expedido" class="t_funcionario_Expedido">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Expedido->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Expedido->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Expedido->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Apellido_Paterno) == "") { ?>
		<th data-name="Apellido_Paterno"><div id="elh_t_funcionario_Apellido_Paterno" class="t_funcionario_Apellido_Paterno"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Apellido_Paterno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Paterno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Apellido_Paterno) ?>',1);"><div id="elh_t_funcionario_Apellido_Paterno" class="t_funcionario_Apellido_Paterno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Apellido_Paterno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Apellido_Materno) == "") { ?>
		<th data-name="Apellido_Materno"><div id="elh_t_funcionario_Apellido_Materno" class="t_funcionario_Apellido_Materno"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Apellido_Materno->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Apellido_Materno"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Apellido_Materno) ?>',1);"><div id="elh_t_funcionario_Apellido_Materno" class="t_funcionario_Apellido_Materno">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Apellido_Materno->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Nombres->Visible) { // Nombres ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Nombres) == "") { ?>
		<th data-name="Nombres"><div id="elh_t_funcionario_Nombres" class="t_funcionario_Nombres"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Nombres->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Nombres"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Nombres) ?>',1);"><div id="elh_t_funcionario_Nombres" class="t_funcionario_Nombres">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Nombres->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Fecha_Nacimiento->Visible) { // Fecha_Nacimiento ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Fecha_Nacimiento) == "") { ?>
		<th data-name="Fecha_Nacimiento"><div id="elh_t_funcionario_Fecha_Nacimiento" class="t_funcionario_Fecha_Nacimiento"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Fecha_Nacimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fecha_Nacimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Fecha_Nacimiento) ?>',1);"><div id="elh_t_funcionario_Fecha_Nacimiento" class="t_funcionario_Fecha_Nacimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Fecha_Nacimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Fecha_Nacimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Fecha_Nacimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Estado_Civil->Visible) { // Estado_Civil ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Estado_Civil) == "") { ?>
		<th data-name="Estado_Civil"><div id="elh_t_funcionario_Estado_Civil" class="t_funcionario_Estado_Civil"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Estado_Civil->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Estado_Civil"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Estado_Civil) ?>',1);"><div id="elh_t_funcionario_Estado_Civil" class="t_funcionario_Estado_Civil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Estado_Civil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Estado_Civil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Estado_Civil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Direccion->Visible) { // Direccion ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Direccion) == "") { ?>
		<th data-name="Direccion"><div id="elh_t_funcionario_Direccion" class="t_funcionario_Direccion"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Direccion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Direccion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Direccion) ?>',1);"><div id="elh_t_funcionario_Direccion" class="t_funcionario_Direccion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Direccion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Direccion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Direccion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Telefono->Visible) { // Telefono ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Telefono) == "") { ?>
		<th data-name="Telefono"><div id="elh_t_funcionario_Telefono" class="t_funcionario_Telefono"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Telefono->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Telefono"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Telefono) ?>',1);"><div id="elh_t_funcionario_Telefono" class="t_funcionario_Telefono">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Telefono->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Telefono->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Telefono->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Celular->Visible) { // Celular ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Celular) == "") { ?>
		<th data-name="Celular"><div id="elh_t_funcionario_Celular" class="t_funcionario_Celular"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Celular->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Celular"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Celular) ?>',1);"><div id="elh_t_funcionario_Celular" class="t_funcionario_Celular">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Celular->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Celular->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Celular->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Fiscalia_otro) == "") { ?>
		<th data-name="Fiscalia_otro"><div id="elh_t_funcionario_Fiscalia_otro" class="t_funcionario_Fiscalia_otro"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Fiscalia_otro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Fiscalia_otro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Fiscalia_otro) ?>',1);"><div id="elh_t_funcionario_Fiscalia_otro" class="t_funcionario_Fiscalia_otro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Fiscalia_otro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Fiscalia_otro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Fiscalia_otro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Unidad_Organizacional) == "") { ?>
		<th data-name="Unidad_Organizacional"><div id="elh_t_funcionario_Unidad_Organizacional" class="t_funcionario_Unidad_Organizacional"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Unidad_Organizacional->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Unidad_Organizacional"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Unidad_Organizacional) ?>',1);"><div id="elh_t_funcionario_Unidad_Organizacional" class="t_funcionario_Unidad_Organizacional">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Unidad_Organizacional->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Unidad_Organizacional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Unidad_Organizacional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Unidad->Visible) { // Unidad ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Unidad) == "") { ?>
		<th data-name="Unidad"><div id="elh_t_funcionario_Unidad" class="t_funcionario_Unidad"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Unidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Unidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Unidad) ?>',1);"><div id="elh_t_funcionario_Unidad" class="t_funcionario_Unidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Unidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Unidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Unidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_funcionario->Cargo->Visible) { // Cargo ?>
	<?php if ($t_funcionario->SortUrl($t_funcionario->Cargo) == "") { ?>
		<th data-name="Cargo"><div id="elh_t_funcionario_Cargo" class="t_funcionario_Cargo"><div class="ewTableHeaderCaption"><?php echo $t_funcionario->Cargo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Cargo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_funcionario->SortUrl($t_funcionario->Cargo) ?>',1);"><div id="elh_t_funcionario_Cargo" class="t_funcionario_Cargo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_funcionario->Cargo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_funcionario->Cargo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_funcionario->Cargo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_funcionario_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($t_funcionario->ExportAll && $t_funcionario->Export <> "") {
	$t_funcionario_list->StopRec = $t_funcionario_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_funcionario_list->TotalRecs > $t_funcionario_list->StartRec + $t_funcionario_list->DisplayRecs - 1)
		$t_funcionario_list->StopRec = $t_funcionario_list->StartRec + $t_funcionario_list->DisplayRecs - 1;
	else
		$t_funcionario_list->StopRec = $t_funcionario_list->TotalRecs;
}
$t_funcionario_list->RecCnt = $t_funcionario_list->StartRec - 1;
if ($t_funcionario_list->Recordset && !$t_funcionario_list->Recordset->EOF) {
	$t_funcionario_list->Recordset->MoveFirst();
	$bSelectLimit = $t_funcionario_list->UseSelectLimit;
	if (!$bSelectLimit && $t_funcionario_list->StartRec > 1)
		$t_funcionario_list->Recordset->Move($t_funcionario_list->StartRec - 1);
} elseif (!$t_funcionario->AllowAddDeleteRow && $t_funcionario_list->StopRec == 0) {
	$t_funcionario_list->StopRec = $t_funcionario->GridAddRowCount;
}

// Initialize aggregate
$t_funcionario->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_funcionario->ResetAttrs();
$t_funcionario_list->RenderRow();
while ($t_funcionario_list->RecCnt < $t_funcionario_list->StopRec) {
	$t_funcionario_list->RecCnt++;
	if (intval($t_funcionario_list->RecCnt) >= intval($t_funcionario_list->StartRec)) {
		$t_funcionario_list->RowCnt++;

		// Set up key count
		$t_funcionario_list->KeyCount = $t_funcionario_list->RowIndex;

		// Init row class and style
		$t_funcionario->ResetAttrs();
		$t_funcionario->CssClass = "";
		if ($t_funcionario->CurrentAction == "gridadd") {
		} else {
			$t_funcionario_list->LoadRowValues($t_funcionario_list->Recordset); // Load row values
		}
		$t_funcionario->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$t_funcionario->RowAttrs = array_merge($t_funcionario->RowAttrs, array('data-rowindex'=>$t_funcionario_list->RowCnt, 'id'=>'r' . $t_funcionario_list->RowCnt . '_t_funcionario', 'data-rowtype'=>$t_funcionario->RowType));

		// Render row
		$t_funcionario_list->RenderRow();

		// Render list options
		$t_funcionario_list->RenderListOptions();
?>
	<tr<?php echo $t_funcionario->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_funcionario_list->ListOptions->Render("body", "left", $t_funcionario_list->RowCnt);
?>
	<?php if ($t_funcionario->CI_RUN->Visible) { // CI_RUN ?>
		<td data-name="CI_RUN"<?php echo $t_funcionario->CI_RUN->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_CI_RUN" class="t_funcionario_CI_RUN">
<span<?php echo $t_funcionario->CI_RUN->ViewAttributes() ?>>
<?php echo $t_funcionario->CI_RUN->ListViewValue() ?></span>
</span>
<a id="<?php echo $t_funcionario_list->PageObjName . "_row_" . $t_funcionario_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($t_funcionario->Expedido->Visible) { // Expedido ?>
		<td data-name="Expedido"<?php echo $t_funcionario->Expedido->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Expedido" class="t_funcionario_Expedido">
<span<?php echo $t_funcionario->Expedido->ViewAttributes() ?>>
<?php echo $t_funcionario->Expedido->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td data-name="Apellido_Paterno"<?php echo $t_funcionario->Apellido_Paterno->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Apellido_Paterno" class="t_funcionario_Apellido_Paterno">
<span<?php echo $t_funcionario->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_funcionario->Apellido_Paterno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td data-name="Apellido_Materno"<?php echo $t_funcionario->Apellido_Materno->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Apellido_Materno" class="t_funcionario_Apellido_Materno">
<span<?php echo $t_funcionario->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_funcionario->Apellido_Materno->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Nombres->Visible) { // Nombres ?>
		<td data-name="Nombres"<?php echo $t_funcionario->Nombres->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Nombres" class="t_funcionario_Nombres">
<span<?php echo $t_funcionario->Nombres->ViewAttributes() ?>>
<?php echo $t_funcionario->Nombres->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Fecha_Nacimiento->Visible) { // Fecha_Nacimiento ?>
		<td data-name="Fecha_Nacimiento"<?php echo $t_funcionario->Fecha_Nacimiento->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Fecha_Nacimiento" class="t_funcionario_Fecha_Nacimiento">
<span<?php echo $t_funcionario->Fecha_Nacimiento->ViewAttributes() ?>>
<?php echo $t_funcionario->Fecha_Nacimiento->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Estado_Civil->Visible) { // Estado_Civil ?>
		<td data-name="Estado_Civil"<?php echo $t_funcionario->Estado_Civil->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Estado_Civil" class="t_funcionario_Estado_Civil">
<span<?php echo $t_funcionario->Estado_Civil->ViewAttributes() ?>>
<?php echo $t_funcionario->Estado_Civil->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Direccion->Visible) { // Direccion ?>
		<td data-name="Direccion"<?php echo $t_funcionario->Direccion->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Direccion" class="t_funcionario_Direccion">
<span<?php echo $t_funcionario->Direccion->ViewAttributes() ?>>
<?php echo $t_funcionario->Direccion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Telefono->Visible) { // Telefono ?>
		<td data-name="Telefono"<?php echo $t_funcionario->Telefono->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Telefono" class="t_funcionario_Telefono">
<span<?php echo $t_funcionario->Telefono->ViewAttributes() ?>>
<?php echo $t_funcionario->Telefono->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Celular->Visible) { // Celular ?>
		<td data-name="Celular"<?php echo $t_funcionario->Celular->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Celular" class="t_funcionario_Celular">
<span<?php echo $t_funcionario->Celular->ViewAttributes() ?>>
<?php echo $t_funcionario->Celular->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
		<td data-name="Fiscalia_otro"<?php echo $t_funcionario->Fiscalia_otro->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Fiscalia_otro" class="t_funcionario_Fiscalia_otro">
<span<?php echo $t_funcionario->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $t_funcionario->Fiscalia_otro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
		<td data-name="Unidad_Organizacional"<?php echo $t_funcionario->Unidad_Organizacional->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Unidad_Organizacional" class="t_funcionario_Unidad_Organizacional">
<span<?php echo $t_funcionario->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $t_funcionario->Unidad_Organizacional->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Unidad->Visible) { // Unidad ?>
		<td data-name="Unidad"<?php echo $t_funcionario->Unidad->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Unidad" class="t_funcionario_Unidad">
<span<?php echo $t_funcionario->Unidad->ViewAttributes() ?>>
<?php echo $t_funcionario->Unidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($t_funcionario->Cargo->Visible) { // Cargo ?>
		<td data-name="Cargo"<?php echo $t_funcionario->Cargo->CellAttributes() ?>>
<span id="el<?php echo $t_funcionario_list->RowCnt ?>_t_funcionario_Cargo" class="t_funcionario_Cargo">
<span<?php echo $t_funcionario->Cargo->ViewAttributes() ?>>
<?php echo $t_funcionario->Cargo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_funcionario_list->ListOptions->Render("body", "right", $t_funcionario_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($t_funcionario->CurrentAction <> "gridadd")
		$t_funcionario_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_funcionario->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_funcionario_list->Recordset)
	$t_funcionario_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($t_funcionario->CurrentAction <> "gridadd" && $t_funcionario->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_funcionario_list->Pager)) $t_funcionario_list->Pager = new cPrevNextPager($t_funcionario_list->StartRec, $t_funcionario_list->DisplayRecs, $t_funcionario_list->TotalRecs) ?>
<?php if ($t_funcionario_list->Pager->RecordCount > 0 && $t_funcionario_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_funcionario_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_funcionario_list->PageUrl() ?>start=<?php echo $t_funcionario_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_funcionario_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_funcionario_list->PageUrl() ?>start=<?php echo $t_funcionario_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_funcionario_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_funcionario_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_funcionario_list->PageUrl() ?>start=<?php echo $t_funcionario_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_funcionario_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_funcionario_list->PageUrl() ?>start=<?php echo $t_funcionario_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_funcionario_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_funcionario_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_funcionario_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_funcionario_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_funcionario_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($t_funcionario_list->TotalRecs == 0 && $t_funcionario->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_funcionario_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ft_funcionariolistsrch.FilterList = <?php echo $t_funcionario_list->GetFilterList() ?>;
ft_funcionariolistsrch.Init();
ft_funcionariolist.Init();
</script>
<?php
$t_funcionario_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_funcionario_list->Page_Terminate();
?>
