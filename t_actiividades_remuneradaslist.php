<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_actiividades_remuneradasinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_actiividades_remuneradas_list = NULL; // Initialize page object first

class ct_actiividades_remuneradas_list extends ct_actiividades_remuneradas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_actiividades_remuneradas';

	// Page object name
	var $PageObjName = 't_actiividades_remuneradas_list';

	// Grid form hidden field names
	var $FormName = 'ft_actiividades_remuneradaslist';
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

		// Table object (t_actiividades_remuneradas)
		if (!isset($GLOBALS["t_actiividades_remuneradas"]) || get_class($GLOBALS["t_actiividades_remuneradas"]) == "ct_actiividades_remuneradas") {
			$GLOBALS["t_actiividades_remuneradas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_actiividades_remuneradas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_actiividades_remuneradasadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_actiividades_remuneradasdelete.php";
		$this->MultiUpdateUrl = "t_actiividades_remuneradasupdate.php";

		// Table object (t_funcionario)
		if (!isset($GLOBALS['t_funcionario'])) $GLOBALS['t_funcionario'] = new ct_funcionario();

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_actiividades_remuneradas', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ft_actiividades_remuneradaslistsrch";

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
		$this->Tipo_Actividad->SetVisibility();
		$this->Actividad_Si->SetVisibility();
		$this->Actividad_No->SetVisibility();
		$this->Entidad->SetVisibility();
		$this->Sector->SetVisibility();
		$this->Remunerada->SetVisibility();

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
		global $EW_EXPORT, $t_actiividades_remuneradas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_actiividades_remuneradas);
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

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

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
		$this->setKey("Id", ""); // Clear inline edit key
		$this->setKey("Tipo_Actividad", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
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
		if (@$_GET["Id"] <> "") {
			$this->Id->setQueryStringValue($_GET["Id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if (@$_GET["Tipo_Actividad"] <> "") {
			$this->Tipo_Actividad->setQueryStringValue($_GET["Tipo_Actividad"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("Id", $this->Id->CurrentValue); // Set up inline edit key
				$this->setKey("Tipo_Actividad", $this->Tipo_Actividad->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("Id")) <> strval($this->Id->CurrentValue))
			return FALSE;
		if (strval($this->getKey("Tipo_Actividad")) <> strval($this->Tipo_Actividad->CurrentValue))
			return FALSE;
		return TRUE;
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
		if (count($arrKeyFlds) >= 2) {
			$this->Id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->Id->FormValue))
				return FALSE;
			$this->Tipo_Actividad->setFormValue($arrKeyFlds[1]);
		}
		return TRUE;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_Id") && $objForm->HasValue("o_Id") && $this->Id->CurrentValue <> $this->Id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Tipo_Actividad") && $objForm->HasValue("o_Tipo_Actividad") && $this->Tipo_Actividad->CurrentValue <> $this->Tipo_Actividad->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Actividad_Si") && $objForm->HasValue("o_Actividad_Si") && $this->Actividad_Si->CurrentValue <> $this->Actividad_Si->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Actividad_No") && $objForm->HasValue("o_Actividad_No") && $this->Actividad_No->CurrentValue <> $this->Actividad_No->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Entidad") && $objForm->HasValue("o_Entidad") && $this->Entidad->CurrentValue <> $this->Entidad->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Sector") && $objForm->HasValue("o_Sector") && $this->Sector->CurrentValue <> $this->Sector->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Remunerada") && $objForm->HasValue("o_Remunerada") && $this->Remunerada->CurrentValue <> $this->Remunerada->OldValue)
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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->Id); // Id
			$this->UpdateSort($this->Tipo_Actividad); // Tipo_Actividad
			$this->UpdateSort($this->Actividad_Si); // Actividad_Si
			$this->UpdateSort($this->Actividad_No); // Actividad_No
			$this->UpdateSort($this->Entidad); // Entidad
			$this->UpdateSort($this->Sector); // Sector
			$this->UpdateSort($this->Remunerada); // Remunerada
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
				$this->Tipo_Actividad->setSort("");
				$this->Actividad_Si->setSort("");
				$this->Actividad_No->setSort("");
				$this->Entidad->setSort("");
				$this->Sector->setSort("");
				$this->Remunerada->setSort("");
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
				if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->Id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Tipo_Actividad->CurrentValue) . "\">";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->Id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Tipo_Actividad->CurrentValue) . "\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->Id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->Tipo_Actividad->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ft_actiividades_remuneradaslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ft_actiividades_remuneradaslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ft_actiividades_remuneradaslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$this->Tipo_Actividad->CurrentValue = NULL;
		$this->Tipo_Actividad->OldValue = $this->Tipo_Actividad->CurrentValue;
		$this->Actividad_Si->CurrentValue = NULL;
		$this->Actividad_Si->OldValue = $this->Actividad_Si->CurrentValue;
		$this->Actividad_No->CurrentValue = NULL;
		$this->Actividad_No->OldValue = $this->Actividad_No->CurrentValue;
		$this->Entidad->CurrentValue = NULL;
		$this->Entidad->OldValue = $this->Entidad->CurrentValue;
		$this->Sector->CurrentValue = NULL;
		$this->Sector->OldValue = $this->Sector->CurrentValue;
		$this->Remunerada->CurrentValue = NULL;
		$this->Remunerada->OldValue = $this->Remunerada->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Id->FldIsDetailKey) {
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
		}
		if (!$this->Tipo_Actividad->FldIsDetailKey) {
			$this->Tipo_Actividad->setFormValue($objForm->GetValue("x_Tipo_Actividad"));
		}
		if (!$this->Actividad_Si->FldIsDetailKey) {
			$this->Actividad_Si->setFormValue($objForm->GetValue("x_Actividad_Si"));
		}
		if (!$this->Actividad_No->FldIsDetailKey) {
			$this->Actividad_No->setFormValue($objForm->GetValue("x_Actividad_No"));
		}
		if (!$this->Entidad->FldIsDetailKey) {
			$this->Entidad->setFormValue($objForm->GetValue("x_Entidad"));
		}
		if (!$this->Sector->FldIsDetailKey) {
			$this->Sector->setFormValue($objForm->GetValue("x_Sector"));
		}
		if (!$this->Remunerada->FldIsDetailKey) {
			$this->Remunerada->setFormValue($objForm->GetValue("x_Remunerada"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->Tipo_Actividad->CurrentValue = $this->Tipo_Actividad->FormValue;
		$this->Actividad_Si->CurrentValue = $this->Actividad_Si->FormValue;
		$this->Actividad_No->CurrentValue = $this->Actividad_No->FormValue;
		$this->Entidad->CurrentValue = $this->Entidad->FormValue;
		$this->Sector->CurrentValue = $this->Sector->FormValue;
		$this->Remunerada->CurrentValue = $this->Remunerada->FormValue;
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
		$this->Tipo_Actividad->setDbValue($rs->fields('Tipo_Actividad'));
		$this->Actividad_Si->setDbValue($rs->fields('Actividad_Si'));
		$this->Actividad_No->setDbValue($rs->fields('Actividad_No'));
		$this->Entidad->setDbValue($rs->fields('Entidad'));
		$this->Sector->setDbValue($rs->fields('Sector'));
		$this->Remunerada->setDbValue($rs->fields('Remunerada'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->Tipo_Actividad->DbValue = $row['Tipo_Actividad'];
		$this->Actividad_Si->DbValue = $row['Actividad_Si'];
		$this->Actividad_No->DbValue = $row['Actividad_No'];
		$this->Entidad->DbValue = $row['Entidad'];
		$this->Sector->DbValue = $row['Sector'];
		$this->Remunerada->DbValue = $row['Remunerada'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("Id")) <> "")
			$this->Id->CurrentValue = $this->getKey("Id"); // Id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("Tipo_Actividad")) <> "")
			$this->Tipo_Actividad->CurrentValue = $this->getKey("Tipo_Actividad"); // Tipo_Actividad
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
		// Tipo_Actividad
		// Actividad_Si
		// Actividad_No
		// Entidad
		// Sector
		// Remunerada

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// Tipo_Actividad
		$this->Tipo_Actividad->ViewValue = $this->Tipo_Actividad->CurrentValue;
		$this->Tipo_Actividad->ViewCustomAttributes = "";

		// Actividad_Si
		if (strval($this->Actividad_Si->CurrentValue) <> "") {
			$this->Actividad_Si->ViewValue = "";
			$arwrk = explode(",", strval($this->Actividad_Si->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->Actividad_Si->ViewValue .= $this->Actividad_Si->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->Actividad_Si->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->Actividad_Si->ViewValue = NULL;
		}
		$this->Actividad_Si->ViewCustomAttributes = "";

		// Actividad_No
		if (strval($this->Actividad_No->CurrentValue) <> "") {
			$this->Actividad_No->ViewValue = "";
			$arwrk = explode(",", strval($this->Actividad_No->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->Actividad_No->ViewValue .= $this->Actividad_No->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->Actividad_No->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->Actividad_No->ViewValue = NULL;
		}
		$this->Actividad_No->ViewCustomAttributes = "";

		// Entidad
		$this->Entidad->ViewValue = $this->Entidad->CurrentValue;
		$this->Entidad->ViewCustomAttributes = "";

		// Sector
		if (strval($this->Sector->CurrentValue) <> "") {
			$this->Sector->ViewValue = $this->Sector->OptionCaption($this->Sector->CurrentValue);
		} else {
			$this->Sector->ViewValue = NULL;
		}
		$this->Sector->ViewCustomAttributes = "";

		// Remunerada
		if (strval($this->Remunerada->CurrentValue) <> "") {
			$this->Remunerada->ViewValue = $this->Remunerada->OptionCaption($this->Remunerada->CurrentValue);
		} else {
			$this->Remunerada->ViewValue = NULL;
		}
		$this->Remunerada->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// Tipo_Actividad
			$this->Tipo_Actividad->LinkCustomAttributes = "";
			$this->Tipo_Actividad->HrefValue = "";
			$this->Tipo_Actividad->TooltipValue = "";

			// Actividad_Si
			$this->Actividad_Si->LinkCustomAttributes = "";
			$this->Actividad_Si->HrefValue = "";
			$this->Actividad_Si->TooltipValue = "";

			// Actividad_No
			$this->Actividad_No->LinkCustomAttributes = "";
			$this->Actividad_No->HrefValue = "";
			$this->Actividad_No->TooltipValue = "";

			// Entidad
			$this->Entidad->LinkCustomAttributes = "";
			$this->Entidad->HrefValue = "";
			$this->Entidad->TooltipValue = "";

			// Sector
			$this->Sector->LinkCustomAttributes = "";
			$this->Sector->HrefValue = "";
			$this->Sector->TooltipValue = "";

			// Remunerada
			$this->Remunerada->LinkCustomAttributes = "";
			$this->Remunerada->HrefValue = "";
			$this->Remunerada->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			if ($this->Id->getSessionValue() <> "") {
				$this->Id->CurrentValue = $this->Id->getSessionValue();
			$this->Id->ViewValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";
			} else {
			$this->Id->EditValue = ew_HtmlEncode($this->Id->CurrentValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());
			}

			// Tipo_Actividad
			$this->Tipo_Actividad->EditAttrs["class"] = "form-control";
			$this->Tipo_Actividad->EditCustomAttributes = "";
			$this->Tipo_Actividad->EditValue = ew_HtmlEncode($this->Tipo_Actividad->CurrentValue);
			$this->Tipo_Actividad->PlaceHolder = ew_RemoveHtml($this->Tipo_Actividad->FldCaption());

			// Actividad_Si
			$this->Actividad_Si->EditCustomAttributes = "";
			$this->Actividad_Si->EditValue = $this->Actividad_Si->Options(FALSE);

			// Actividad_No
			$this->Actividad_No->EditCustomAttributes = "";
			$this->Actividad_No->EditValue = $this->Actividad_No->Options(FALSE);

			// Entidad
			$this->Entidad->EditAttrs["class"] = "form-control";
			$this->Entidad->EditCustomAttributes = "";
			$this->Entidad->EditValue = ew_HtmlEncode($this->Entidad->CurrentValue);
			$this->Entidad->PlaceHolder = ew_RemoveHtml($this->Entidad->FldCaption());

			// Sector
			$this->Sector->EditAttrs["class"] = "form-control";
			$this->Sector->EditCustomAttributes = "";
			$this->Sector->EditValue = $this->Sector->Options(TRUE);

			// Remunerada
			$this->Remunerada->EditAttrs["class"] = "form-control";
			$this->Remunerada->EditCustomAttributes = "";
			$this->Remunerada->EditValue = $this->Remunerada->Options(TRUE);

			// Add refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// Tipo_Actividad
			$this->Tipo_Actividad->LinkCustomAttributes = "";
			$this->Tipo_Actividad->HrefValue = "";

			// Actividad_Si
			$this->Actividad_Si->LinkCustomAttributes = "";
			$this->Actividad_Si->HrefValue = "";

			// Actividad_No
			$this->Actividad_No->LinkCustomAttributes = "";
			$this->Actividad_No->HrefValue = "";

			// Entidad
			$this->Entidad->LinkCustomAttributes = "";
			$this->Entidad->HrefValue = "";

			// Sector
			$this->Sector->LinkCustomAttributes = "";
			$this->Sector->HrefValue = "";

			// Remunerada
			$this->Remunerada->LinkCustomAttributes = "";
			$this->Remunerada->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = $this->Id->CurrentValue;
			$this->Id->ViewCustomAttributes = "";

			// Tipo_Actividad
			$this->Tipo_Actividad->EditAttrs["class"] = "form-control";
			$this->Tipo_Actividad->EditCustomAttributes = "";
			$this->Tipo_Actividad->EditValue = $this->Tipo_Actividad->CurrentValue;
			$this->Tipo_Actividad->ViewCustomAttributes = "";

			// Actividad_Si
			$this->Actividad_Si->EditCustomAttributes = "";
			$this->Actividad_Si->EditValue = $this->Actividad_Si->Options(FALSE);

			// Actividad_No
			$this->Actividad_No->EditCustomAttributes = "";
			$this->Actividad_No->EditValue = $this->Actividad_No->Options(FALSE);

			// Entidad
			$this->Entidad->EditAttrs["class"] = "form-control";
			$this->Entidad->EditCustomAttributes = "";
			$this->Entidad->EditValue = ew_HtmlEncode($this->Entidad->CurrentValue);
			$this->Entidad->PlaceHolder = ew_RemoveHtml($this->Entidad->FldCaption());

			// Sector
			$this->Sector->EditAttrs["class"] = "form-control";
			$this->Sector->EditCustomAttributes = "";
			$this->Sector->EditValue = $this->Sector->Options(TRUE);

			// Remunerada
			$this->Remunerada->EditAttrs["class"] = "form-control";
			$this->Remunerada->EditCustomAttributes = "";
			$this->Remunerada->EditValue = $this->Remunerada->Options(TRUE);

			// Edit refer script
			// Id

			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";

			// Tipo_Actividad
			$this->Tipo_Actividad->LinkCustomAttributes = "";
			$this->Tipo_Actividad->HrefValue = "";

			// Actividad_Si
			$this->Actividad_Si->LinkCustomAttributes = "";
			$this->Actividad_Si->HrefValue = "";

			// Actividad_No
			$this->Actividad_No->LinkCustomAttributes = "";
			$this->Actividad_No->HrefValue = "";

			// Entidad
			$this->Entidad->LinkCustomAttributes = "";
			$this->Entidad->HrefValue = "";

			// Sector
			$this->Sector->LinkCustomAttributes = "";
			$this->Sector->HrefValue = "";

			// Remunerada
			$this->Remunerada->LinkCustomAttributes = "";
			$this->Remunerada->HrefValue = "";
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
		if (!$this->Id->FldIsDetailKey && !is_null($this->Id->FormValue) && $this->Id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Id->FldCaption(), $this->Id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->Id->FormValue)) {
			ew_AddMessage($gsFormError, $this->Id->FldErrMsg());
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
				$sThisKey .= $row['Id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['Tipo_Actividad'];
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
			// Tipo_Actividad
			// Actividad_Si

			$this->Actividad_Si->SetDbValueDef($rsnew, $this->Actividad_Si->CurrentValue, NULL, $this->Actividad_Si->ReadOnly);

			// Actividad_No
			$this->Actividad_No->SetDbValueDef($rsnew, $this->Actividad_No->CurrentValue, NULL, $this->Actividad_No->ReadOnly);

			// Entidad
			$this->Entidad->SetDbValueDef($rsnew, $this->Entidad->CurrentValue, NULL, $this->Entidad->ReadOnly);

			// Sector
			$this->Sector->SetDbValueDef($rsnew, $this->Sector->CurrentValue, NULL, $this->Sector->ReadOnly);

			// Remunerada
			$this->Remunerada->SetDbValueDef($rsnew, $this->Remunerada->CurrentValue, NULL, $this->Remunerada->ReadOnly);

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

		// Tipo_Actividad
		$this->Tipo_Actividad->SetDbValueDef($rsnew, $this->Tipo_Actividad->CurrentValue, "", FALSE);

		// Actividad_Si
		$this->Actividad_Si->SetDbValueDef($rsnew, $this->Actividad_Si->CurrentValue, NULL, FALSE);

		// Actividad_No
		$this->Actividad_No->SetDbValueDef($rsnew, $this->Actividad_No->CurrentValue, NULL, FALSE);

		// Entidad
		$this->Entidad->SetDbValueDef($rsnew, $this->Entidad->CurrentValue, NULL, FALSE);

		// Sector
		$this->Sector->SetDbValueDef($rsnew, $this->Sector->CurrentValue, NULL, FALSE);

		// Remunerada
		$this->Remunerada->SetDbValueDef($rsnew, $this->Remunerada->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['Tipo_Actividad']) == "") {
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
if (!isset($t_actiividades_remuneradas_list)) $t_actiividades_remuneradas_list = new ct_actiividades_remuneradas_list();

// Page init
$t_actiividades_remuneradas_list->Page_Init();

// Page main
$t_actiividades_remuneradas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_actiividades_remuneradas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ft_actiividades_remuneradaslist = new ew_Form("ft_actiividades_remuneradaslist", "list");
ft_actiividades_remuneradaslist.FormKeyCountName = '<?php echo $t_actiividades_remuneradas_list->FormKeyCountName ?>';

// Validate form
ft_actiividades_remuneradaslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_actiividades_remuneradas->Id->FldCaption(), $t_actiividades_remuneradas->Id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_actiividades_remuneradas->Id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_actiividades_remuneradaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_actiividades_remuneradaslist.ValidateRequired = true;
<?php } else { ?>
ft_actiividades_remuneradaslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_actiividades_remuneradaslist.Lists["x_Actividad_Si[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradaslist.Lists["x_Actividad_Si[]"].Options = <?php echo json_encode($t_actiividades_remuneradas->Actividad_Si->Options()) ?>;
ft_actiividades_remuneradaslist.Lists["x_Actividad_No[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradaslist.Lists["x_Actividad_No[]"].Options = <?php echo json_encode($t_actiividades_remuneradas->Actividad_No->Options()) ?>;
ft_actiividades_remuneradaslist.Lists["x_Sector"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradaslist.Lists["x_Sector"].Options = <?php echo json_encode($t_actiividades_remuneradas->Sector->Options()) ?>;
ft_actiividades_remuneradaslist.Lists["x_Remunerada"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_actiividades_remuneradaslist.Lists["x_Remunerada"].Options = <?php echo json_encode($t_actiividades_remuneradas->Remunerada->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($t_actiividades_remuneradas_list->TotalRecs > 0 && $t_actiividades_remuneradas_list->ExportOptions->Visible()) { ?>
<?php $t_actiividades_remuneradas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($t_actiividades_remuneradas->Export == "") || (EW_EXPORT_MASTER_RECORD && $t_actiividades_remuneradas->Export == "print")) { ?>
<?php
if ($t_actiividades_remuneradas_list->DbMasterFilter <> "" && $t_actiividades_remuneradas->getCurrentMasterTable() == "t_funcionario") {
	if ($t_actiividades_remuneradas_list->MasterRecordExists) {
?>
<?php include_once "t_funcionariomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $t_actiividades_remuneradas_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_actiividades_remuneradas_list->TotalRecs <= 0)
			$t_actiividades_remuneradas_list->TotalRecs = $t_actiividades_remuneradas->SelectRecordCount();
	} else {
		if (!$t_actiividades_remuneradas_list->Recordset && ($t_actiividades_remuneradas_list->Recordset = $t_actiividades_remuneradas_list->LoadRecordset()))
			$t_actiividades_remuneradas_list->TotalRecs = $t_actiividades_remuneradas_list->Recordset->RecordCount();
	}
	$t_actiividades_remuneradas_list->StartRec = 1;
	if ($t_actiividades_remuneradas_list->DisplayRecs <= 0 || ($t_actiividades_remuneradas->Export <> "" && $t_actiividades_remuneradas->ExportAll)) // Display all records
		$t_actiividades_remuneradas_list->DisplayRecs = $t_actiividades_remuneradas_list->TotalRecs;
	if (!($t_actiividades_remuneradas->Export <> "" && $t_actiividades_remuneradas->ExportAll))
		$t_actiividades_remuneradas_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_actiividades_remuneradas_list->Recordset = $t_actiividades_remuneradas_list->LoadRecordset($t_actiividades_remuneradas_list->StartRec-1, $t_actiividades_remuneradas_list->DisplayRecs);

	// Set no record found message
	if ($t_actiividades_remuneradas->CurrentAction == "" && $t_actiividades_remuneradas_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_actiividades_remuneradas_list->setWarningMessage(ew_DeniedMsg());
		if ($t_actiividades_remuneradas_list->SearchWhere == "0=101")
			$t_actiividades_remuneradas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_actiividades_remuneradas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$t_actiividades_remuneradas_list->RenderOtherOptions();
?>
<?php $t_actiividades_remuneradas_list->ShowPageHeader(); ?>
<?php
$t_actiividades_remuneradas_list->ShowMessage();
?>
<?php if ($t_actiividades_remuneradas_list->TotalRecs > 0 || $t_actiividades_remuneradas->CurrentAction <> "") { ?>
<div class="ewMultiColumnGrid">
<form name="ft_actiividades_remuneradaslist" id="ft_actiividades_remuneradaslist" class="form-horizontal ewForm ewListForm ewMultiColumnForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_actiividades_remuneradas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_actiividades_remuneradas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_actiividades_remuneradas">
<?php if ($t_actiividades_remuneradas->getCurrentMasterTable() == "t_funcionario" && $t_actiividades_remuneradas->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_funcionario">
<input type="hidden" name="fk_Id" value="<?php echo $t_actiividades_remuneradas->Id->getSessionValue() ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas_list->TotalRecs > 0 || $t_actiividades_remuneradas->CurrentAction == "gridedit") { ?>
<?php
if ($t_actiividades_remuneradas->ExportAll && $t_actiividades_remuneradas->Export <> "") {
	$t_actiividades_remuneradas_list->StopRec = $t_actiividades_remuneradas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_actiividades_remuneradas_list->TotalRecs > $t_actiividades_remuneradas_list->StartRec + $t_actiividades_remuneradas_list->DisplayRecs - 1)
		$t_actiividades_remuneradas_list->StopRec = $t_actiividades_remuneradas_list->StartRec + $t_actiividades_remuneradas_list->DisplayRecs - 1;
	else
		$t_actiividades_remuneradas_list->StopRec = $t_actiividades_remuneradas_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_actiividades_remuneradas_list->FormKeyCountName) && ($t_actiividades_remuneradas->CurrentAction == "gridadd" || $t_actiividades_remuneradas->CurrentAction == "gridedit" || $t_actiividades_remuneradas->CurrentAction == "F")) {
		$t_actiividades_remuneradas_list->KeyCount = $objForm->GetValue($t_actiividades_remuneradas_list->FormKeyCountName);
		$t_actiividades_remuneradas_list->StopRec = $t_actiividades_remuneradas_list->StartRec + $t_actiividades_remuneradas_list->KeyCount - 1;
	}
}
$t_actiividades_remuneradas_list->RecCnt = $t_actiividades_remuneradas_list->StartRec - 1;
if ($t_actiividades_remuneradas_list->Recordset && !$t_actiividades_remuneradas_list->Recordset->EOF) {
	$t_actiividades_remuneradas_list->Recordset->MoveFirst();
	$bSelectLimit = $t_actiividades_remuneradas_list->UseSelectLimit;
	if (!$bSelectLimit && $t_actiividades_remuneradas_list->StartRec > 1)
		$t_actiividades_remuneradas_list->Recordset->Move($t_actiividades_remuneradas_list->StartRec - 1);
} elseif (!$t_actiividades_remuneradas->AllowAddDeleteRow && $t_actiividades_remuneradas_list->StopRec == 0) {
	$t_actiividades_remuneradas_list->StopRec = $t_actiividades_remuneradas->GridAddRowCount;
}
$t_actiividades_remuneradas_list->EditRowCnt = 0;
if ($t_actiividades_remuneradas->CurrentAction == "edit")
	$t_actiividades_remuneradas_list->RowIndex = 1;
if ($t_actiividades_remuneradas->CurrentAction == "gridedit")
	$t_actiividades_remuneradas_list->RowIndex = 0;
while ($t_actiividades_remuneradas_list->RecCnt < $t_actiividades_remuneradas_list->StopRec) {
	$t_actiividades_remuneradas_list->RecCnt++;
	if (intval($t_actiividades_remuneradas_list->RecCnt) >= intval($t_actiividades_remuneradas_list->StartRec)) {
		$t_actiividades_remuneradas_list->RowCnt++;
		if ($t_actiividades_remuneradas->CurrentAction == "gridadd" || $t_actiividades_remuneradas->CurrentAction == "gridedit" || $t_actiividades_remuneradas->CurrentAction == "F") {
			$t_actiividades_remuneradas_list->RowIndex++;
			$objForm->Index = $t_actiividades_remuneradas_list->RowIndex;
			if ($objForm->HasValue($t_actiividades_remuneradas_list->FormActionName))
				$t_actiividades_remuneradas_list->RowAction = strval($objForm->GetValue($t_actiividades_remuneradas_list->FormActionName));
			elseif ($t_actiividades_remuneradas->CurrentAction == "gridadd")
				$t_actiividades_remuneradas_list->RowAction = "insert";
			else
				$t_actiividades_remuneradas_list->RowAction = "";
		}

		// Set up key count
		$t_actiividades_remuneradas_list->KeyCount = $t_actiividades_remuneradas_list->RowIndex;

		// Init row class and style
		$t_actiividades_remuneradas->ResetAttrs();
		$t_actiividades_remuneradas->CssClass = "";
		if ($t_actiividades_remuneradas->CurrentAction == "gridadd") {
			$t_actiividades_remuneradas_list->LoadDefaultValues(); // Load default values
		} else {
			$t_actiividades_remuneradas_list->LoadRowValues($t_actiividades_remuneradas_list->Recordset); // Load row values
		}
		$t_actiividades_remuneradas->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_actiividades_remuneradas->CurrentAction == "edit") {
			if ($t_actiividades_remuneradas_list->CheckInlineEditKey() && $t_actiividades_remuneradas_list->EditRowCnt == 0) { // Inline edit
				$t_actiividades_remuneradas->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_actiividades_remuneradas->CurrentAction == "gridedit") { // Grid edit
			if ($t_actiividades_remuneradas->EventCancelled) {
				$t_actiividades_remuneradas_list->RestoreCurrentRowFormValues($t_actiividades_remuneradas_list->RowIndex); // Restore form values
			}
			if ($t_actiividades_remuneradas_list->RowAction == "insert")
				$t_actiividades_remuneradas->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_actiividades_remuneradas->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_actiividades_remuneradas->CurrentAction == "edit" && $t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT && $t_actiividades_remuneradas->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_actiividades_remuneradas_list->RestoreFormValues(); // Restore form values
		}
		if ($t_actiividades_remuneradas->CurrentAction == "gridedit" && ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT || $t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) && $t_actiividades_remuneradas->EventCancelled) // Update failed
			$t_actiividades_remuneradas_list->RestoreCurrentRowFormValues($t_actiividades_remuneradas_list->RowIndex); // Restore form values
		if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_actiividades_remuneradas_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_actiividades_remuneradas->RowAttrs = array_merge($t_actiividades_remuneradas->RowAttrs, array('data-rowindex'=>$t_actiividades_remuneradas_list->RowCnt, 'id'=>'r' . $t_actiividades_remuneradas_list->RowCnt . '_t_actiividades_remuneradas', 'data-rowtype'=>$t_actiividades_remuneradas->RowType));

		// Render row
		$t_actiividades_remuneradas_list->RenderRow();

		// Render list options
		$t_actiividades_remuneradas_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_actiividades_remuneradas_list->RowAction <> "delete" && $t_actiividades_remuneradas_list->RowAction <> "insertdelete" && !($t_actiividades_remuneradas_list->RowAction == "insert" && $t_actiividades_remuneradas->CurrentAction == "F" && $t_actiividades_remuneradas_list->EmptyRow())) {
?>
<?php echo $t_actiividades_remuneradas_list->MultiColumnBeginGrid() ?>
<div class="<?php echo $t_actiividades_remuneradas_list->MultiColumnClass ?>"<?php echo $t_actiividades_remuneradas->RowAttributes() ?>>
	<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	<table class="table table-bordered table-striped">
	<?php } else { // Add/edit record ?>
	<div>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Id->Visible) { // Id ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Id">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Id) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Id->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Id) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Id->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_actiividades_remuneradas->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Id->EditValue ?>"<?php echo $t_actiividades_remuneradas->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Id">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Id->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Id->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_actiividades_remuneradas->Id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Id->EditValue ?>"<?php echo $t_actiividades_remuneradas->Id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Id" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Id" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Id->CurrentValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Id">
<span<?php echo $t_actiividades_remuneradas->Id->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Id->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Tipo_Actividad->Visible) { // Tipo_Actividad ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Tipo_Actividad">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Tipo_Actividad) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Tipo_Actividad) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Tipo_Actividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Tipo_Actividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Tipo_Actividad->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->CurrentValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Tipo_Actividad">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Tipo_Actividad->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_actiividades_remuneradas->Tipo_Actividad->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Tipo_Actividad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Tipo_Actividad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Tipo_Actividad->CurrentValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Tipo_Actividad">
<span<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Tipo_Actividad->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Actividad_Si->Visible) { // Actividad_Si ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Actividad_Si">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Actividad_Si) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_Si->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Actividad_Si) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_Si->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Actividad_Si->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Actividad_Si->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Actividad_Si->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si">
<span<?php echo $t_actiividades_remuneradas->Actividad_Si->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Actividad_Si->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Actividad_Si">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Actividad_Si->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Actividad_Si->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_Si->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_Si" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_Si->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_Si->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_Si" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_Si->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_Si[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_Si">
<span<?php echo $t_actiividades_remuneradas->Actividad_Si->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Actividad_Si->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Actividad_No->Visible) { // Actividad_No ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Actividad_No">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Actividad_No) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_No->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Actividad_No) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Actividad_No->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Actividad_No->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Actividad_No->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Actividad_No->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_No">
<span<?php echo $t_actiividades_remuneradas->Actividad_No->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Actividad_No->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Actividad_No">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Actividad_No->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Actividad_No->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Actividad_No->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_No">
<div id="tp_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" class="ewTemplate"><input type="checkbox" data-table="t_actiividades_remuneradas" data-field="x_Actividad_No" data-value-separator="<?php echo $t_actiividades_remuneradas->Actividad_No->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No[]" value="{value}"<?php echo $t_actiividades_remuneradas->Actividad_No->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Actividad_No" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_actiividades_remuneradas->Actividad_No->CheckBoxListHtml(FALSE, "x{$t_actiividades_remuneradas_list->RowIndex}_Actividad_No[]") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Actividad_No">
<span<?php echo $t_actiividades_remuneradas->Actividad_No->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Actividad_No->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Entidad->Visible) { // Entidad ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Entidad">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Entidad) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Entidad->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Entidad) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Entidad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Entidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Entidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Entidad->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Entidad">
<span<?php echo $t_actiividades_remuneradas->Entidad->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Entidad->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Entidad">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Entidad->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Entidad->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Entidad">
<input type="text" data-table="t_actiividades_remuneradas" data-field="x_Entidad" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Entidad" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Entidad->getPlaceHolder()) ?>" value="<?php echo $t_actiividades_remuneradas->Entidad->EditValue ?>"<?php echo $t_actiividades_remuneradas->Entidad->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Entidad">
<span<?php echo $t_actiividades_remuneradas->Entidad->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Entidad->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Sector->Visible) { // Sector ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Sector">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Sector) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Sector->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Sector) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Sector->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Sector->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Sector->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Sector->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector") ?>
</select>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector") ?>
</select>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Sector">
<span<?php echo $t_actiividades_remuneradas->Sector->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Sector">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Sector->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Sector->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector") ?>
</select>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Sector" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Sector->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Sector">
<select data-table="t_actiividades_remuneradas" data-field="x_Sector" data-value-separator="<?php echo $t_actiividades_remuneradas->Sector->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector"<?php echo $t_actiividades_remuneradas->Sector->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Sector") ?>
</select>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Sector">
<span<?php echo $t_actiividades_remuneradas->Sector->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Sector->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->Remunerada->Visible) { // Remunerada ?>
		<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
		<tr>
			<td class="ewTableHeader"><span class="t_actiividades_remuneradas_Remunerada">
<?php if ($t_actiividades_remuneradas->Export <> "" || $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Remunerada) == "") { ?>
				<div class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Remunerada->FldCaption() ?></div>
<?php } else { ?>
				<div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_actiividades_remuneradas->SortUrl($t_actiividades_remuneradas->Remunerada) ?>',1);">
            	<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_actiividades_remuneradas->Remunerada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_actiividades_remuneradas->Remunerada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_actiividades_remuneradas->Remunerada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
				</div>
<?php } ?>
			</span></td>
			<td<?php echo $t_actiividades_remuneradas->Remunerada->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada") ?>
</select>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada") ?>
</select>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Remunerada">
<span<?php echo $t_actiividades_remuneradas->Remunerada->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
		</tr>
		<?php } else { // Add/edit record ?>
		<div class="form-group t_actiividades_remuneradas_Remunerada">
			<label class="col-sm-2 control-label ewLabel"><?php echo $t_actiividades_remuneradas->Remunerada->FldCaption() ?></label>
			<div class="col-sm-10"><div<?php echo $t_actiividades_remuneradas->Remunerada->CellAttributes() ?>>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada") ?>
</select>
</span>
<input type="hidden" data-table="t_actiividades_remuneradas" data-field="x_Remunerada" name="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" id="o<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" value="<?php echo ew_HtmlEncode($t_actiividades_remuneradas->Remunerada->OldValue) ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Remunerada">
<select data-table="t_actiividades_remuneradas" data-field="x_Remunerada" data-value-separator="<?php echo $t_actiividades_remuneradas->Remunerada->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada" name="x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada"<?php echo $t_actiividades_remuneradas->Remunerada->EditAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->SelectOptionListHtml("x<?php echo $t_actiividades_remuneradas_list->RowIndex ?>_Remunerada") ?>
</select>
</span>
<?php } ?>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_actiividades_remuneradas_list->RowCnt ?>_t_actiividades_remuneradas_Remunerada">
<span<?php echo $t_actiividades_remuneradas->Remunerada->ViewAttributes() ?>>
<?php echo $t_actiividades_remuneradas->Remunerada->ListViewValue() ?></span>
</span>
<?php } ?>
</div></div>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_VIEW) { // View record ?>
	</table>
	<?php } else { // Add/edit record ?>
	</div>
	<?php } ?>
<div class="ewMultiColumnListOption">
<?php

// Render list options (body, bottom)
$t_actiividades_remuneradas_list->ListOptions->Render("body", "", $t_actiividades_remuneradas_list->RowCnt);
?>
</div>
<div class="clearfix"></div>
</div>
<?php if ($t_actiividades_remuneradas->RowType == EW_ROWTYPE_ADD || $t_actiividades_remuneradas->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_actiividades_remuneradaslist.UpdateOpts(<?php echo $t_actiividades_remuneradas_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_actiividades_remuneradas->CurrentAction <> "gridadd")
		if (!$t_actiividades_remuneradas_list->Recordset->EOF) $t_actiividades_remuneradas_list->Recordset->MoveNext();
}
?>
<?php echo $t_actiividades_remuneradas_list->MultiColumnEndGrid() ?>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_actiividades_remuneradas->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_actiividades_remuneradas_list->FormKeyCountName ?>" id="<?php echo $t_actiividades_remuneradas_list->FormKeyCountName ?>" value="<?php echo $t_actiividades_remuneradas_list->KeyCount ?>">
<?php } ?>
<?php if ($t_actiividades_remuneradas->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_actiividades_remuneradas_list->FormKeyCountName ?>" id="<?php echo $t_actiividades_remuneradas_list->FormKeyCountName ?>" value="<?php echo $t_actiividades_remuneradas_list->KeyCount ?>">
<?php echo $t_actiividades_remuneradas_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_actiividades_remuneradas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($t_actiividades_remuneradas_list->Recordset)
	$t_actiividades_remuneradas_list->Recordset->Close();
?>
<div>
<?php if ($t_actiividades_remuneradas->CurrentAction <> "gridadd" && $t_actiividades_remuneradas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_actiividades_remuneradas_list->Pager)) $t_actiividades_remuneradas_list->Pager = new cPrevNextPager($t_actiividades_remuneradas_list->StartRec, $t_actiividades_remuneradas_list->DisplayRecs, $t_actiividades_remuneradas_list->TotalRecs) ?>
<?php if ($t_actiividades_remuneradas_list->Pager->RecordCount > 0 && $t_actiividades_remuneradas_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_actiividades_remuneradas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_actiividades_remuneradas_list->PageUrl() ?>start=<?php echo $t_actiividades_remuneradas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_actiividades_remuneradas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_actiividades_remuneradas_list->PageUrl() ?>start=<?php echo $t_actiividades_remuneradas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_actiividades_remuneradas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_actiividades_remuneradas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_actiividades_remuneradas_list->PageUrl() ?>start=<?php echo $t_actiividades_remuneradas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_actiividades_remuneradas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_actiividades_remuneradas_list->PageUrl() ?>start=<?php echo $t_actiividades_remuneradas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_actiividades_remuneradas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_actiividades_remuneradas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_actiividades_remuneradas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_actiividades_remuneradas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_actiividades_remuneradas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($t_actiividades_remuneradas_list->TotalRecs == 0 && $t_actiividades_remuneradas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_actiividades_remuneradas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ft_actiividades_remuneradaslist.Init();
</script>
<?php
$t_actiividades_remuneradas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_actiividades_remuneradas_list->Page_Terminate();
?>
