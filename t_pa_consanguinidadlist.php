<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_pa_consanguinidadinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_pa_consanguinidad_list = NULL; // Initialize page object first

class ct_pa_consanguinidad_list extends ct_pa_consanguinidad {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_pa_consanguinidad';

	// Page object name
	var $PageObjName = 't_pa_consanguinidad_list';

	// Grid form hidden field names
	var $FormName = 'ft_pa_consanguinidadlist';
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

		// Table object (t_pa_consanguinidad)
		if (!isset($GLOBALS["t_pa_consanguinidad"]) || get_class($GLOBALS["t_pa_consanguinidad"]) == "ct_pa_consanguinidad") {
			$GLOBALS["t_pa_consanguinidad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_pa_consanguinidad"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_pa_consanguinidadadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_pa_consanguinidaddelete.php";
		$this->MultiUpdateUrl = "t_pa_consanguinidadupdate.php";

		// Table object (t_funcionario)
		if (!isset($GLOBALS['t_funcionario'])) $GLOBALS['t_funcionario'] = new ct_funcionario();

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_pa_consanguinidad', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ft_pa_consanguinidadlistsrch";

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->Id->SetVisibility();
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

		// Set up master detail parameters
		$this->SetUpMasterParms();

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
		global $EW_EXPORT, $t_pa_consanguinidad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_pa_consanguinidad);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "t_funcionario") {
			global $t_funcionario;
			$rsmaster = $t_funcionario->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("t_funcionariolist.php"); // Return to master page
			} else {
				$t_funcionario->LoadListRowValues($rsmaster);
				$t_funcionario->RowType = EW_ROWTYPE_MASTER; // Master row
				$t_funcionario->RenderListRow();
				$rsmaster->Close();
			}
		}

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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("Nombres", ""); // Clear inline edit key
		$this->setKey("Apellido_Paterno", ""); // Clear inline edit key
		$this->setKey("Apellido_Materno", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["Nombres"] <> "") {
			$this->Nombres->setQueryStringValue($_GET["Nombres"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if (@$_GET["Apellido_Paterno"] <> "") {
			$this->Apellido_Paterno->setQueryStringValue($_GET["Apellido_Paterno"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if (@$_GET["Apellido_Materno"] <> "") {
			$this->Apellido_Materno->setQueryStringValue($_GET["Apellido_Materno"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("Nombres", $this->Nombres->CurrentValue); // Set up inline edit key
				$this->setKey("Apellido_Paterno", $this->Apellido_Paterno->CurrentValue); // Set up inline edit key
				$this->setKey("Apellido_Materno", $this->Apellido_Materno->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("Nombres")) <> strval($this->Nombres->CurrentValue))
			return FALSE;
		if (strval($this->getKey("Apellido_Paterno")) <> strval($this->Apellido_Paterno->CurrentValue))
			return FALSE;
		if (strval($this->getKey("Apellido_Materno")) <> strval($this->Apellido_Materno->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		$this->CurrentAction = "add";
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->Nombres->CurrentValue;
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->Apellido_Paterno->CurrentValue;
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->Apellido_Materno->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_Id") && $objForm->HasValue("o_Id") && $this->Id->CurrentValue <> $this->Id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Nombres") && $objForm->HasValue("o_Nombres") && $this->Nombres->CurrentValue <> $this->Nombres->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Apellido_Paterno") && $objForm->HasValue("o_Apellido_Paterno") && $this->Apellido_Paterno->CurrentValue <> $this->Apellido_Paterno->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Apellido_Materno") && $objForm->HasValue("o_Apellido_Materno") && $this->Apellido_Materno->CurrentValue <> $this->Apellido_Materno->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Parentesco") && $objForm->HasValue("o_Parentesco") && $this->Parentesco->CurrentValue <> $this->Parentesco->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "ft_pa_consanguinidadlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->Id->AdvancedSearch->ToJSON(), ","); // Field Id
		$sFilterList = ew_Concat($sFilterList, $this->Nombres->AdvancedSearch->ToJSON(), ","); // Field Nombres
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Paterno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Paterno
		$sFilterList = ew_Concat($sFilterList, $this->Apellido_Materno->AdvancedSearch->ToJSON(), ","); // Field Apellido_Materno
		$sFilterList = ew_Concat($sFilterList, $this->Parentesco->AdvancedSearch->ToJSON(), ","); // Field Parentesco
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ft_pa_consanguinidadlistsrch", $filters);

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

		// Field Id
		$this->Id->AdvancedSearch->SearchValue = @$filter["x_Id"];
		$this->Id->AdvancedSearch->SearchOperator = @$filter["z_Id"];
		$this->Id->AdvancedSearch->SearchCondition = @$filter["v_Id"];
		$this->Id->AdvancedSearch->SearchValue2 = @$filter["y_Id"];
		$this->Id->AdvancedSearch->SearchOperator2 = @$filter["w_Id"];
		$this->Id->AdvancedSearch->Save();

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
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->Id, $Default, FALSE); // Id
		$this->BuildSearchSql($sWhere, $this->Nombres, $Default, FALSE); // Nombres
		$this->BuildSearchSql($sWhere, $this->Apellido_Paterno, $Default, FALSE); // Apellido_Paterno
		$this->BuildSearchSql($sWhere, $this->Apellido_Materno, $Default, FALSE); // Apellido_Materno
		$this->BuildSearchSql($sWhere, $this->Parentesco, $Default, FALSE); // Parentesco

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->Id->AdvancedSearch->Save(); // Id
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

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->Id->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->Id->AdvancedSearch->UnsetSession();
		$this->Nombres->AdvancedSearch->UnsetSession();
		$this->Apellido_Paterno->AdvancedSearch->UnsetSession();
		$this->Apellido_Materno->AdvancedSearch->UnsetSession();
		$this->Parentesco->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->Id->AdvancedSearch->Load();
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
			$this->UpdateSort($this->Id); // Id
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->Id->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->Id->setSort("");
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

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd() && ($this->CurrentAction == "add");
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
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
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->Nombres->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Paterno->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Materno->CurrentValue) . "\">";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Nombres->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Paterno->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Materno->CurrentValue) . "\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->Nombres->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Paterno->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Apellido_Materno->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi update
		$item = &$option->Add("multiupdate");
		$item->Body = "<a class=\"ewAction ewMultiUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" data-table=\"t_pa_consanguinidad\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.ft_pa_consanguinidadlist,url:'" . $this->MultiUpdateUrl . "'});return false;\">" . $Language->Phrase("UpdateSelectedLink") . "</a>";
		$item->Visible = ($Security->CanEdit());

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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ft_pa_consanguinidadlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ft_pa_consanguinidadlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ft_pa_consanguinidadlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"t_pa_consanguinidadsrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		$item->Visible = TRUE;

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

	// Load default values
	function LoadDefaultValues() {
		$this->Id->CurrentValue = NULL;
		$this->Id->OldValue = $this->Id->CurrentValue;
		$this->Nombres->CurrentValue = NULL;
		$this->Nombres->OldValue = $this->Nombres->CurrentValue;
		$this->Apellido_Paterno->CurrentValue = NULL;
		$this->Apellido_Paterno->OldValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Materno->CurrentValue = NULL;
		$this->Apellido_Materno->OldValue = $this->Apellido_Materno->CurrentValue;
		$this->Parentesco->CurrentValue = NULL;
		$this->Parentesco->OldValue = $this->Parentesco->CurrentValue;
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// Id

		$this->Id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Id"]);
		if ($this->Id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Id->AdvancedSearch->SearchOperator = @$_GET["z_Id"];

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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Id->FldIsDetailKey) {
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		}
		$this->Id->setOldValue($objForm->GetValue("o_Id"));
		if (!$this->Nombres->FldIsDetailKey) {
			$this->Nombres->setFormValue($objForm->GetValue("x_Nombres"));
		}
		$this->Nombres->setOldValue($objForm->GetValue("o_Nombres"));
		if (!$this->Apellido_Paterno->FldIsDetailKey) {
			$this->Apellido_Paterno->setFormValue($objForm->GetValue("x_Apellido_Paterno"));
		}
		$this->Apellido_Paterno->setOldValue($objForm->GetValue("o_Apellido_Paterno"));
		if (!$this->Apellido_Materno->FldIsDetailKey) {
			$this->Apellido_Materno->setFormValue($objForm->GetValue("x_Apellido_Materno"));
		}
		$this->Apellido_Materno->setOldValue($objForm->GetValue("o_Apellido_Materno"));
		if (!$this->Parentesco->FldIsDetailKey) {
			$this->Parentesco->setFormValue($objForm->GetValue("x_Parentesco"));
		}
		$this->Parentesco->setOldValue($objForm->GetValue("o_Parentesco"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Parentesco->CurrentValue = $this->Parentesco->FormValue;
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Parentesco->setDbValue($rs->fields('Parentesco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
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
		// Id
		// Nombres
		// Apellido_Paterno
		// Apellido_Materno
		// Parentesco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

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
			$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_consanguineo`";
		$sWhereWrk = "";
		$this->Parentesco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Parentesco, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Parentesco->ViewValue = $this->Parentesco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Parentesco->ViewValue = $this->Parentesco->CurrentValue;
			}
		} else {
			$this->Parentesco->ViewValue = NULL;
		}
		$this->Parentesco->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

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

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";
			$this->Parentesco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			if ($this->Id->getSessionValue() <> "") {
				$this->Id->CurrentValue = $this->Id->getSessionValue();
				$this->Id->OldValue = $this->Id->CurrentValue;
			$this->Id->ViewValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";
			} else {
			$this->Id->EditValue = ew_HtmlEncode($this->Id->CurrentValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());
			}

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

			// Parentesco
			$this->Parentesco->EditAttrs["class"] = "form-control";
			$this->Parentesco->EditCustomAttributes = "";
			if (trim(strval($this->Parentesco->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `s_consanguineo`";
			$sWhereWrk = "";
			$this->Parentesco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Parentesco, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Parentesco->EditValue = $arwrk;

			// Add refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			if ($this->Id->getSessionValue() <> "") {
				$this->Id->CurrentValue = $this->Id->getSessionValue();
				$this->Id->OldValue = $this->Id->CurrentValue;
			$this->Id->ViewValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";
			} else {
			$this->Id->EditValue = ew_HtmlEncode($this->Id->CurrentValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());
			}

			// Nombres
			$this->Nombres->EditAttrs["class"] = "form-control";
			$this->Nombres->EditCustomAttributes = "";
			$this->Nombres->EditValue = $this->Nombres->CurrentValue;
			$this->Nombres->ViewCustomAttributes = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->EditAttrs["class"] = "form-control";
			$this->Apellido_Paterno->EditCustomAttributes = "";
			$this->Apellido_Paterno->EditValue = $this->Apellido_Paterno->CurrentValue;
			$this->Apellido_Paterno->ViewCustomAttributes = "";

			// Apellido_Materno
			$this->Apellido_Materno->EditAttrs["class"] = "form-control";
			$this->Apellido_Materno->EditCustomAttributes = "";
			$this->Apellido_Materno->EditValue = $this->Apellido_Materno->CurrentValue;
			$this->Apellido_Materno->ViewCustomAttributes = "";

			// Parentesco
			$this->Parentesco->EditAttrs["class"] = "form-control";
			$this->Parentesco->EditCustomAttributes = "";
			if (trim(strval($this->Parentesco->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `s_consanguineo`";
			$sWhereWrk = "";
			$this->Parentesco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Parentesco, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Parentesco->EditValue = $arwrk;

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->Id->FldIsDetailKey && !is_null($this->Id->FormValue) && $this->Id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id->FldCaption(), $this->Id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Id->FormValue)) {
			ew_AddMessage($gsFormError, $this->Id->FldErrMsg());
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Nombres'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Apellido_Paterno'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Apellido_Materno'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// Id
			$this->Id->SetDbValueDef($rsnew, $this->Id->CurrentValue, 0, $this->Id->ReadOnly);

			// Nombres
			// Apellido_Paterno
			// Apellido_Materno
			// Parentesco

			$this->Parentesco->SetDbValueDef($rsnew, $this->Parentesco->CurrentValue, "", $this->Parentesco->ReadOnly);

			// Check referential integrity for master table 't_funcionario'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_t_funcionario();
			$KeyValue = isset($rsnew['Id']) ? $rsnew['Id'] : $rsold['Id'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@Id@", ew_AdjustSql($KeyValue), $sMasterFilter);
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;

		// Check referential integrity for master table 't_funcionario'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_t_funcionario();
		if (strval($this->Id->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@Id@", ew_AdjustSql($this->Id->CurrentValue, "DB"), $sMasterFilter);
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

		// Id
		$this->Id->SetDbValueDef($rsnew, $this->Id->CurrentValue, 0, FALSE);

		// Nombres
		$this->Nombres->SetDbValueDef($rsnew, $this->Nombres->CurrentValue, "", FALSE);

		// Apellido_Paterno
		$this->Apellido_Paterno->SetDbValueDef($rsnew, $this->Apellido_Paterno->CurrentValue, "", FALSE);

		// Apellido_Materno
		$this->Apellido_Materno->SetDbValueDef($rsnew, $this->Apellido_Materno->CurrentValue, "", FALSE);

		// Parentesco
		$this->Parentesco->SetDbValueDef($rsnew, $this->Parentesco->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Nombres']) == "") {
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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->Id->AdvancedSearch->Load();
		$this->Nombres->AdvancedSearch->Load();
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Parentesco->AdvancedSearch->Load();
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

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Parentesco":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Parentesco` AS `LinkFld`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_consanguineo`";
			$sWhereWrk = "";
			$this->Parentesco->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Parentesco` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Parentesco, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_pa_consanguinidad_list)) $t_pa_consanguinidad_list = new ct_pa_consanguinidad_list();

// Page init
$t_pa_consanguinidad_list->Page_Init();

// Page main
$t_pa_consanguinidad_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pa_consanguinidad_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ft_pa_consanguinidadlist = new ew_Form("ft_pa_consanguinidadlist", "list");
ft_pa_consanguinidadlist.FormKeyCountName = '<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>';

// Validate form
ft_pa_consanguinidadlist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Id->FldCaption(), $t_pa_consanguinidad->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_pa_consanguinidad->Id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Nombres->FldCaption(), $t_pa_consanguinidad->Nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Paterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Apellido_Paterno->FldCaption(), $t_pa_consanguinidad->Apellido_Paterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Materno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_pa_consanguinidad->Apellido_Materno->FldCaption(), $t_pa_consanguinidad->Apellido_Materno->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
ft_pa_consanguinidadlist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "Id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Nombres", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Paterno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Apellido_Materno", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Parentesco", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_pa_consanguinidadlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pa_consanguinidadlist.ValidateRequired = true;
<?php } else { ?>
ft_pa_consanguinidadlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pa_consanguinidadlist.Lists["x_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_consanguineo"};

// Form object for search
var CurrentSearchForm = ft_pa_consanguinidadlistsrch = new ew_Form("ft_pa_consanguinidadlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($t_pa_consanguinidad_list->TotalRecs > 0 && $t_pa_consanguinidad_list->ExportOptions->Visible()) { ?>
<?php $t_pa_consanguinidad_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($t_pa_consanguinidad_list->SearchOptions->Visible()) { ?>
<?php $t_pa_consanguinidad_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($t_pa_consanguinidad_list->FilterOptions->Visible()) { ?>
<?php $t_pa_consanguinidad_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($t_pa_consanguinidad->Export == "") || (EW_EXPORT_MASTER_RECORD && $t_pa_consanguinidad->Export == "print")) { ?>
<?php
if ($t_pa_consanguinidad_list->DbMasterFilter <> "" && $t_pa_consanguinidad->getCurrentMasterTable() == "t_funcionario") {
	if ($t_pa_consanguinidad_list->MasterRecordExists) {
?>
<?php include_once "t_funcionariomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
if ($t_pa_consanguinidad->CurrentAction == "gridadd") {
	$t_pa_consanguinidad->CurrentFilter = "0=1";
	$t_pa_consanguinidad_list->StartRec = 1;
	$t_pa_consanguinidad_list->DisplayRecs = $t_pa_consanguinidad->GridAddRowCount;
	$t_pa_consanguinidad_list->TotalRecs = $t_pa_consanguinidad_list->DisplayRecs;
	$t_pa_consanguinidad_list->StopRec = $t_pa_consanguinidad_list->DisplayRecs;
} else {
	$bSelectLimit = $t_pa_consanguinidad_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_pa_consanguinidad_list->TotalRecs <= 0)
			$t_pa_consanguinidad_list->TotalRecs = $t_pa_consanguinidad->SelectRecordCount();
	} else {
		if (!$t_pa_consanguinidad_list->Recordset && ($t_pa_consanguinidad_list->Recordset = $t_pa_consanguinidad_list->LoadRecordset()))
			$t_pa_consanguinidad_list->TotalRecs = $t_pa_consanguinidad_list->Recordset->RecordCount();
	}
	$t_pa_consanguinidad_list->StartRec = 1;
	if ($t_pa_consanguinidad_list->DisplayRecs <= 0 || ($t_pa_consanguinidad->Export <> "" && $t_pa_consanguinidad->ExportAll)) // Display all records
		$t_pa_consanguinidad_list->DisplayRecs = $t_pa_consanguinidad_list->TotalRecs;
	if (!($t_pa_consanguinidad->Export <> "" && $t_pa_consanguinidad->ExportAll))
		$t_pa_consanguinidad_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_pa_consanguinidad_list->Recordset = $t_pa_consanguinidad_list->LoadRecordset($t_pa_consanguinidad_list->StartRec-1, $t_pa_consanguinidad_list->DisplayRecs);

	// Set no record found message
	if ($t_pa_consanguinidad->CurrentAction == "" && $t_pa_consanguinidad_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_pa_consanguinidad_list->setWarningMessage(ew_DeniedMsg());
		if ($t_pa_consanguinidad_list->SearchWhere == "0=101")
			$t_pa_consanguinidad_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_pa_consanguinidad_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_pa_consanguinidad_list->RenderOtherOptions();
?>
<?php $t_pa_consanguinidad_list->ShowPageHeader(); ?>
<?php
$t_pa_consanguinidad_list->ShowMessage();
?>
<?php if ($t_pa_consanguinidad_list->TotalRecs > 0 || $t_pa_consanguinidad->CurrentAction <> "") { ?>
<div class="ewMultiColumnGrid">
<form name="ft_pa_consanguinidadlist" id="ft_pa_consanguinidadlist" class="form-horizontal ewForm ewListForm ewMultiColumnForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_pa_consanguinidad_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_pa_consanguinidad_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_pa_consanguinidad">
<?php if ($t_pa_consanguinidad->getCurrentMasterTable() == "t_funcionario" && $t_pa_consanguinidad->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_funcionario">
<input type="hidden" name="fk_Id" value="<?php echo $t_pa_consanguinidad->Id->getSessionValue() ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad_list->TotalRecs > 0 || $t_pa_consanguinidad->CurrentAction == "add" || $t_pa_consanguinidad->CurrentAction == "copy" || $t_pa_consanguinidad->CurrentAction == "gridedit") { ?>
<?php
	if ($t_pa_consanguinidad->CurrentAction == "add" || $t_pa_consanguinidad->CurrentAction == "copy") {
		$t_pa_consanguinidad_list->RowIndex = 0;
		$t_pa_consanguinidad_list->KeyCount = $t_pa_consanguinidad_list->RowIndex;
		if ($t_pa_consanguinidad->CurrentAction == "add")
			$t_pa_consanguinidad_list->LoadDefaultValues();
		if ($t_pa_consanguinidad->EventCancelled) // Insert failed
			$t_pa_consanguinidad_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_pa_consanguinidad->ResetAttrs();
		$t_pa_consanguinidad->RowAttrs = array_merge($t_pa_consanguinidad->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_pa_consanguinidad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_pa_consanguinidad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_pa_consanguinidad_list->RenderRow();

		// Render list options
		$t_pa_consanguinidad_list->RenderListOptions();
		$t_pa_consanguinidad_list->StartRowCnt = 0;
?>
<?php $t_pa_consanguinidad_list->ColCnt = 0 ?>
<div class="row ewMultiColumnRow">
<div class="<?php echo $t_pa_consanguinidad_list->MultiColumnEditClass ?>"<?php echo $t_pa_consanguinidad->RowAttributes() ?>>
	<div>
	<?php if ($t_pa_consanguinidad->Id->Visible) { // Id ?>
		<div class="form-group t_pa_consanguinidad_Id">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Id->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Id->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
</div></div>
		</div>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Nombres->Visible) { // Nombres ?>
		<div class="form-group t_pa_consanguinidad_Nombres">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Nombres->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Nombres->CellAttributes() ?>>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Nombres->EditValue ?>"<?php echo $t_pa_consanguinidad->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
</div></div>
		</div>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<div class="form-group t_pa_consanguinidad_Apellido_Paterno">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Apellido_Paterno->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Apellido_Paterno->CellAttributes() ?>>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
</div></div>
		</div>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<div class="form-group t_pa_consanguinidad_Apellido_Materno">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Apellido_Materno->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Apellido_Materno->CellAttributes() ?>>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
</div></div>
		</div>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Parentesco->Visible) { // Parentesco ?>
		<div class="form-group t_pa_consanguinidad_Parentesco">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Parentesco->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Parentesco->CellAttributes() ?>>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
</div></div>
		</div>
	<?php } ?>
	</div>
<div class="ewMultiColumnListOption">
<?php

// Render list options (body, bottom)
$t_pa_consanguinidad_list->ListOptions->Render("body", "bottom", $t_pa_consanguinidad_list->RowCnt);
?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
ft_pa_consanguinidadlist.UpdateOpts(<?php echo $t_pa_consanguinidad_list->RowIndex ?>);
</script>
</div>
</div>
<?php
}
?>
<?php
if ($t_pa_consanguinidad->ExportAll && $t_pa_consanguinidad->Export <> "") {
	$t_pa_consanguinidad_list->StopRec = $t_pa_consanguinidad_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_pa_consanguinidad_list->TotalRecs > $t_pa_consanguinidad_list->StartRec + $t_pa_consanguinidad_list->DisplayRecs - 1)
		$t_pa_consanguinidad_list->StopRec = $t_pa_consanguinidad_list->StartRec + $t_pa_consanguinidad_list->DisplayRecs - 1;
	else
		$t_pa_consanguinidad_list->StopRec = $t_pa_consanguinidad_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_pa_consanguinidad_list->FormKeyCountName) && ($t_pa_consanguinidad->CurrentAction == "gridadd" || $t_pa_consanguinidad->CurrentAction == "gridedit" || $t_pa_consanguinidad->CurrentAction == "F")) {
		$t_pa_consanguinidad_list->KeyCount = $objForm->GetValue($t_pa_consanguinidad_list->FormKeyCountName);
		$t_pa_consanguinidad_list->StopRec = $t_pa_consanguinidad_list->StartRec + $t_pa_consanguinidad_list->KeyCount - 1;
	}
}
$t_pa_consanguinidad_list->RecCnt = $t_pa_consanguinidad_list->StartRec - 1;
if ($t_pa_consanguinidad_list->Recordset && !$t_pa_consanguinidad_list->Recordset->EOF) {
	$t_pa_consanguinidad_list->Recordset->MoveFirst();
	$bSelectLimit = $t_pa_consanguinidad_list->UseSelectLimit;
	if (!$bSelectLimit && $t_pa_consanguinidad_list->StartRec > 1)
		$t_pa_consanguinidad_list->Recordset->Move($t_pa_consanguinidad_list->StartRec - 1);
} elseif (!$t_pa_consanguinidad->AllowAddDeleteRow && $t_pa_consanguinidad_list->StopRec == 0) {
	$t_pa_consanguinidad_list->StopRec = $t_pa_consanguinidad->GridAddRowCount;
}
$t_pa_consanguinidad_list->EditRowCnt = 0;
if ($t_pa_consanguinidad->CurrentAction == "edit")
	$t_pa_consanguinidad_list->RowIndex = 1;
if ($t_pa_consanguinidad->CurrentAction == "gridadd")
	$t_pa_consanguinidad_list->RowIndex = 0;
if ($t_pa_consanguinidad->CurrentAction == "gridedit")
	$t_pa_consanguinidad_list->RowIndex = 0;
while ($t_pa_consanguinidad_list->RecCnt < $t_pa_consanguinidad_list->StopRec) {
	$t_pa_consanguinidad_list->RecCnt++;
	if (intval($t_pa_consanguinidad_list->RecCnt) >= intval($t_pa_consanguinidad_list->StartRec)) {
		$t_pa_consanguinidad_list->RowCnt++;
		if ($t_pa_consanguinidad->CurrentAction == "gridadd" || $t_pa_consanguinidad->CurrentAction == "gridedit" || $t_pa_consanguinidad->CurrentAction == "F") {
			$t_pa_consanguinidad_list->RowIndex++;
			$objForm->Index = $t_pa_consanguinidad_list->RowIndex;
			if ($objForm->HasValue($t_pa_consanguinidad_list->FormActionName))
				$t_pa_consanguinidad_list->RowAction = strval($objForm->GetValue($t_pa_consanguinidad_list->FormActionName));
			elseif ($t_pa_consanguinidad->CurrentAction == "gridadd")
				$t_pa_consanguinidad_list->RowAction = "insert";
			else
				$t_pa_consanguinidad_list->RowAction = "";
		}

		// Set up key count
		$t_pa_consanguinidad_list->KeyCount = $t_pa_consanguinidad_list->RowIndex;

		// Init row class and style
		$t_pa_consanguinidad->ResetAttrs();
		$t_pa_consanguinidad->CssClass = "";
		if ($t_pa_consanguinidad->CurrentAction == "gridadd") {
			$t_pa_consanguinidad_list->LoadDefaultValues(); // Load default values
		} else {
			$t_pa_consanguinidad_list->LoadRowValues($t_pa_consanguinidad_list->Recordset); // Load row values
		}
		$t_pa_consanguinidad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_pa_consanguinidad->CurrentAction == "gridadd") // Grid add
			$t_pa_consanguinidad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_pa_consanguinidad->CurrentAction == "gridadd" && $t_pa_consanguinidad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_pa_consanguinidad_list->RestoreCurrentRowFormValues($t_pa_consanguinidad_list->RowIndex); // Restore form values
		if ($t_pa_consanguinidad->CurrentAction == "edit") {
			if ($t_pa_consanguinidad_list->CheckInlineEditKey() && $t_pa_consanguinidad_list->EditRowCnt == 0) { // Inline edit
				$t_pa_consanguinidad->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_pa_consanguinidad->CurrentAction == "gridedit") { // Grid edit
			if ($t_pa_consanguinidad->EventCancelled) {
				$t_pa_consanguinidad_list->RestoreCurrentRowFormValues($t_pa_consanguinidad_list->RowIndex); // Restore form values
			}
			if ($t_pa_consanguinidad_list->RowAction == "insert")
				$t_pa_consanguinidad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_pa_consanguinidad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_pa_consanguinidad->CurrentAction == "edit" && $t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT && $t_pa_consanguinidad->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_pa_consanguinidad_list->RestoreFormValues(); // Restore form values
		}
		if ($t_pa_consanguinidad->CurrentAction == "gridedit" && ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT || $t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) && $t_pa_consanguinidad->EventCancelled) // Update failed
			$t_pa_consanguinidad_list->RestoreCurrentRowFormValues($t_pa_consanguinidad_list->RowIndex); // Restore form values
		if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_pa_consanguinidad_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_pa_consanguinidad->RowAttrs = array_merge($t_pa_consanguinidad->RowAttrs, array('data-rowindex'=>$t_pa_consanguinidad_list->RowCnt, 'id'=>'r' . $t_pa_consanguinidad_list->RowCnt . '_t_pa_consanguinidad', 'data-rowtype'=>$t_pa_consanguinidad->RowType));

		// Render row
		$t_pa_consanguinidad_list->RenderRow();

		// Render list options
		$t_pa_consanguinidad_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_pa_consanguinidad_list->RowAction <> "delete" && $t_pa_consanguinidad_list->RowAction <> "insertdelete" && !($t_pa_consanguinidad_list->RowAction == "insert" && $t_pa_consanguinidad->CurrentAction == "F" && $t_pa_consanguinidad_list->EmptyRow())) {
?>
<?php echo $t_pa_consanguinidad_list->MultiColumnBeginGrid() ?>
<div class="<?php echo $t_pa_consanguinidad_list->MultiColumnClass ?>"<?php echo $t_pa_consanguinidad->RowAttributes() ?>>
	<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	<table class="table table-bordered table-striped">
	<?php } else { // Add/edit record ?>
	<div>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Id->Visible) { // Id ?>
		<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_pa_consanguinidad_Id">
<?php if ($t_pa_consanguinidad->Export <> "" || $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Id) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Id->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Id) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_pa_consanguinidad->Id->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_pa_consanguinidad_Id">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Id->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Id->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Id" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_pa_consanguinidad->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Id" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Id->EditValue ?>"<?php echo $t_pa_consanguinidad->Id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Id">
<span<?php echo $t_pa_consanguinidad->Id->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Id->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Nombres->Visible) { // Nombres ?>
		<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_pa_consanguinidad_Nombres">
<?php if ($t_pa_consanguinidad->Export <> "" || $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Nombres) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Nombres->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Nombres) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Nombres->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Nombres->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Nombres->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_pa_consanguinidad->Nombres->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Nombres->EditValue ?>"<?php echo $t_pa_consanguinidad->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Nombres->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Nombres->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_pa_consanguinidad_Nombres">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Nombres->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Nombres->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Nombres->EditValue ?>"<?php echo $t_pa_consanguinidad->Nombres->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Nombres->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Nombres" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Nombres" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Nombres->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Nombres">
<span<?php echo $t_pa_consanguinidad->Nombres->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Nombres->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_pa_consanguinidad_Apellido_Paterno">
<?php if ($t_pa_consanguinidad->Export <> "" || $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Apellido_Paterno) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Paterno->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Apellido_Paterno) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Paterno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Apellido_Paterno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Apellido_Paterno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_pa_consanguinidad->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_pa_consanguinidad_Apellido_Paterno">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Apellido_Paterno->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Apellido_Paterno->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Paterno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Paterno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Paterno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Paterno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Paterno">
<span<?php echo $t_pa_consanguinidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_pa_consanguinidad_Apellido_Materno">
<?php if ($t_pa_consanguinidad->Export <> "" || $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Apellido_Materno) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Materno->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Apellido_Materno) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Apellido_Materno->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Apellido_Materno->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Apellido_Materno->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_pa_consanguinidad->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_pa_consanguinidad_Apellido_Materno">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Apellido_Materno->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Apellido_Materno->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<input type="text" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_consanguinidad->Apellido_Materno->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_pa_consanguinidad->Apellido_Materno->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Apellido_Materno" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Apellido_Materno" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Apellido_Materno->CurrentValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Apellido_Materno">
<span<?php echo $t_pa_consanguinidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Apellido_Materno->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->Parentesco->Visible) { // Parentesco ?>
		<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_pa_consanguinidad_Parentesco">
<?php if ($t_pa_consanguinidad->Export <> "" || $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Parentesco) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Parentesco->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_pa_consanguinidad->SortUrl($t_pa_consanguinidad->Parentesco) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_pa_consanguinidad->Parentesco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_pa_consanguinidad->Parentesco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_pa_consanguinidad->Parentesco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_pa_consanguinidad->Parentesco->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<span<?php echo $t_pa_consanguinidad->Parentesco->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_pa_consanguinidad_Parentesco">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_pa_consanguinidad->Parentesco->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_pa_consanguinidad->Parentesco->CellAttributes() ?>>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_pa_consanguinidad" data-field="x_Parentesco" name="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="o<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo ew_HtmlEncode($t_pa_consanguinidad->Parentesco->OldValue) ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<select data-table="t_pa_consanguinidad" data-field="x_Parentesco" data-value-separator="<?php echo $t_pa_consanguinidad->Parentesco->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" name="x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco"<?php echo $t_pa_consanguinidad->Parentesco->EditAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->SelectOptionListHtml("x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco") ?>
</select>
<input type="hidden" name="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" id="s_x<?php echo $t_pa_consanguinidad_list->RowIndex ?>_Parentesco" value="<?php echo $t_pa_consanguinidad->Parentesco->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_pa_consanguinidad_list->RowCnt ?>_t_pa_consanguinidad_Parentesco">
<span<?php echo $t_pa_consanguinidad->Parentesco->ViewAttributes() ?>>
<?php echo $t_pa_consanguinidad->Parentesco->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	</table>
	<?php } else { // Add/edit record ?>
	</div>
	<?php } ?>
<div class="ewMultiColumnListOption">
<?php

// Render list options (body, bottom)
$t_pa_consanguinidad_list->ListOptions->Render("body", "", $t_pa_consanguinidad_list->RowCnt);
?>
</div>
<div class="clearfix"></div>
</div>
<?php if ($t_pa_consanguinidad->RowType == EW_ROWTYPE_ADD || $t_pa_consanguinidad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_pa_consanguinidadlist.UpdateOpts(<?php echo $t_pa_consanguinidad_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_pa_consanguinidad->CurrentAction <> "gridadd")
		if (!$t_pa_consanguinidad_list->Recordset->EOF) $t_pa_consanguinidad_list->Recordset->MoveNext();
}
?>
<?php echo $t_pa_consanguinidad_list->MultiColumnEndGrid() ?>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentAction == "add" || $t_pa_consanguinidad->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" id="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" value="<?php echo $t_pa_consanguinidad_list->KeyCount ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" id="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" value="<?php echo $t_pa_consanguinidad_list->KeyCount ?>">
<?php echo $t_pa_consanguinidad_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" id="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" value="<?php echo $t_pa_consanguinidad_list->KeyCount ?>">
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" id="<?php echo $t_pa_consanguinidad_list->FormKeyCountName ?>" value="<?php echo $t_pa_consanguinidad_list->KeyCount ?>">
<?php echo $t_pa_consanguinidad_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_pa_consanguinidad->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($t_pa_consanguinidad_list->Recordset)
	$t_pa_consanguinidad_list->Recordset->Close();
?>
<div>
<?php if ($t_pa_consanguinidad->CurrentAction <> "gridadd" && $t_pa_consanguinidad->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_pa_consanguinidad_list->Pager)) $t_pa_consanguinidad_list->Pager = new cPrevNextPager($t_pa_consanguinidad_list->StartRec, $t_pa_consanguinidad_list->DisplayRecs, $t_pa_consanguinidad_list->TotalRecs) ?>
<?php if ($t_pa_consanguinidad_list->Pager->RecordCount > 0 && $t_pa_consanguinidad_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_pa_consanguinidad_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_pa_consanguinidad_list->PageUrl() ?>start=<?php echo $t_pa_consanguinidad_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_pa_consanguinidad_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_pa_consanguinidad_list->PageUrl() ?>start=<?php echo $t_pa_consanguinidad_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_pa_consanguinidad_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_pa_consanguinidad_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_pa_consanguinidad_list->PageUrl() ?>start=<?php echo $t_pa_consanguinidad_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_pa_consanguinidad_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_pa_consanguinidad_list->PageUrl() ?>start=<?php echo $t_pa_consanguinidad_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_pa_consanguinidad_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_pa_consanguinidad_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_pa_consanguinidad_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_pa_consanguinidad_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_pa_consanguinidad_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($t_pa_consanguinidad_list->TotalRecs == 0 && $t_pa_consanguinidad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_pa_consanguinidad_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ft_pa_consanguinidadlistsrch.FilterList = <?php echo $t_pa_consanguinidad_list->GetFilterList() ?>;
ft_pa_consanguinidadlistsrch.Init();
ft_pa_consanguinidadlist.Init();
</script>
<?php
$t_pa_consanguinidad_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_pa_consanguinidad_list->Page_Terminate();
?>
