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

$t_funcionario_view = NULL; // Initialize page object first

class ct_funcionario_view extends ct_funcionario {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_funcionario';

	// Page object name
	var $PageObjName = 't_funcionario_view';

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
	var $AuditTrailOnView = TRUE;
	var $AuditTrailOnViewData = TRUE;
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
		echo "<p>" . "Imprimir Reporte del Formulario..." . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . "............" . "</p>";
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
		$KeyUrl = "";
		if (@$_GET["Id"] <> "") {
			$this->RecKey["Id"] = $_GET["Id"];
			$KeyUrl .= "&amp;Id=" . urlencode($this->RecKey["Id"]);
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
				$this->Page_Terminate(ew_GetUrl("t_funcionariolist.php"));
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
		if (@$_GET["Id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["Id"]);
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
		$this->Fecha_registro->SetVisibility();

		// Set up detail page object
		$this->SetupDetailPages();

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
	var $DetailPages; // Detail pages object

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
			if (@$_GET["Id"] <> "") {
				$this->Id->setQueryStringValue($_GET["Id"]);
				$this->RecKey["Id"] = $this->Id->QueryStringValue;
			} elseif (@$_POST["Id"] <> "") {
				$this->Id->setFormValue($_POST["Id"]);
				$this->RecKey["Id"] = $this->Id->FormValue;
			} else {
				$sReturnUrl = "t_funcionariolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "t_funcionariolist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "t_funcionariolist.php"; // Not page request, return to list
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

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_t_conyugue"
		$item = &$option->Add("detail_t_conyugue");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_conyugue", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_conyuguelist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_conyugue_grid"] && $GLOBALS["t_conyugue_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_conyugue')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_conyugue")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_conyugue";
		}
		if ($GLOBALS["t_conyugue_grid"] && $GLOBALS["t_conyugue_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_conyugue')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_conyugue")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_conyugue";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_conyugue');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_conyugue";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_pa_consanguinidad"
		$item = &$option->Add("detail_t_pa_consanguinidad");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_pa_consanguinidad", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_pa_consanguinidadlist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_pa_consanguinidad_grid"] && $GLOBALS["t_pa_consanguinidad_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_pa_consanguinidad')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_consanguinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_pa_consanguinidad";
		}
		if ($GLOBALS["t_pa_consanguinidad_grid"] && $GLOBALS["t_pa_consanguinidad_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_pa_consanguinidad')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_consanguinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_pa_consanguinidad";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_pa_consanguinidad');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_pa_consanguinidad";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_pa_afinidad"
		$item = &$option->Add("detail_t_pa_afinidad");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_pa_afinidad", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_pa_afinidadlist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_pa_afinidad_grid"] && $GLOBALS["t_pa_afinidad_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_pa_afinidad')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_afinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_pa_afinidad";
		}
		if ($GLOBALS["t_pa_afinidad_grid"] && $GLOBALS["t_pa_afinidad_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_pa_afinidad')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_pa_afinidad")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_pa_afinidad";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_pa_afinidad');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_pa_afinidad";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_re_adopcion"
		$item = &$option->Add("detail_t_re_adopcion");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_re_adopcion", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_re_adopcionlist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_re_adopcion_grid"] && $GLOBALS["t_re_adopcion_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_re_adopcion')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_re_adopcion")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_re_adopcion";
		}
		if ($GLOBALS["t_re_adopcion_grid"] && $GLOBALS["t_re_adopcion_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_re_adopcion')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_re_adopcion")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_re_adopcion";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_re_adopcion');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_re_adopcion";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_mp_si_no"
		$item = &$option->Add("detail_t_mp_si_no");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_mp_si_no", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_mp_si_nolist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_mp_si_no_grid"] && $GLOBALS["t_mp_si_no_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_mp_si_no')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_mp_si_no")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_mp_si_no";
		}
		if ($GLOBALS["t_mp_si_no_grid"] && $GLOBALS["t_mp_si_no_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_mp_si_no')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_mp_si_no")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_mp_si_no";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_mp_si_no');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_mp_si_no";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_parientes_mp"
		$item = &$option->Add("detail_t_parientes_mp");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_parientes_mp", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_parientes_mplist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_parientes_mp_grid"] && $GLOBALS["t_parientes_mp_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_parientes_mp')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_parientes_mp")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_parientes_mp";
		}
		if ($GLOBALS["t_parientes_mp_grid"] && $GLOBALS["t_parientes_mp_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_parientes_mp')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_parientes_mp")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_parientes_mp";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_parientes_mp');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_parientes_mp";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_actiividades_remuneradas"
		$item = &$option->Add("detail_t_actiividades_remuneradas");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_actiividades_remuneradas", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_actiividades_remuneradaslist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_actiividades_remuneradas_grid"] && $GLOBALS["t_actiividades_remuneradas_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_actiividades_remuneradas')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_actiividades_remuneradas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_actiividades_remuneradas";
		}
		if ($GLOBALS["t_actiividades_remuneradas_grid"] && $GLOBALS["t_actiividades_remuneradas_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_actiividades_remuneradas')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_actiividades_remuneradas")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_actiividades_remuneradas";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_actiividades_remuneradas');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_actiividades_remuneradas";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// "detail_t_salario"
		$item = &$option->Add("detail_t_salario");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("t_salario", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_salariolist.php?" . EW_TABLE_SHOW_MASTER . "=t_funcionario&fk_Id=" . urlencode(strval($this->Id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["t_salario_grid"] && $GLOBALS["t_salario_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_salario')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_salario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "t_salario";
		}
		if ($GLOBALS["t_salario_grid"] && $GLOBALS["t_salario_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_salario')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_salario")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "t_salario";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_salario');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_salario";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
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
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
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
		// Id
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

			// Telefono
			$this->Telefono->LinkCustomAttributes = "";
			$this->Telefono->HrefValue = "";
			$this->Telefono->TooltipValue = "";

			// Celular
			$this->Celular->LinkCustomAttributes = "";
			$this->Celular->HrefValue = "";
			$this->Celular->TooltipValue = "";

			// Fiscalia_otro
			$this->Fiscalia_otro->LinkCustomAttributes = "";
			$this->Fiscalia_otro->HrefValue = "";
			$this->Fiscalia_otro->TooltipValue = "";

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

			// Fecha_registro
			$this->Fecha_registro->LinkCustomAttributes = "";
			$this->Fecha_registro->HrefValue = "";
			$this->Fecha_registro->TooltipValue = "";
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
		$item->Visible = FALSE;

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
		$item->Body = "<button id=\"emf_t_funcionario\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_funcionario',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ft_funcionarioview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
		$mysqli = new mysqli("localhost", "root", "", "bd_incompatibilidades");
        $parametro = $this->Id->CurrentValue; 
        
        //CONYUGUE
        $result3= $mysqli->query("SELECT id FROM t_conyugue where id = ".$parametro." "); 
        $row_cnt3 = $result3->num_rows;
        if ($row_cnt3 == 0) 
        {
        $fila_conyugue = $mysqli->query("INSERT INTO t_conyugue  VALUES ('".$parametro."','---','---','---','---','---','---')");
        }
        //mysqli_free_result($fila_conyugue); 
        //mysqli_free_result($result3);     
        //ADOPCION
        $result1 = $mysqli->query("SELECT Id FROM t_re_adopcion where id = ".$parametro.""); 
        $row_cnt1 = $result1->num_rows;
        if ($row_cnt1 == 0) 
        {
        $fila_adopcion = $mysqli->query("INSERT INTO t_re_adopcion  VALUES ('".$parametro."','---','---','---','---')");
        }
        //mysqli_free_result($fila_adopcion); 
        //mysqli_free_result($result1);
        //PARIENTES
        $result2= $mysqli->query("SELECT Id FROM t_parientes_mp where id = ".$parametro.""); 
        $row_cnt2 = $result2->num_rows;
        if ($row_cnt2 == 0) 
        {
        $fila_parientes = $mysqli->query("INSERT INTO t_parientes_mp  VALUES ('".$parametro."','---','---','---','---','---','---')");
        }
        
       
         // CONSANGUINEOS
        $result4= $mysqli->query("SELECT Id FROM t_pa_consanguinidad where id = ".$parametro.""); 
        $row_cnt4 = $result4->num_rows;
        if ($row_cnt4 == 0) 
        {
        $fila_consanguineo1 = $mysqli->query("INSERT INTO t_pa_consanguinidad  VALUES ('".$parametro."','---','---','---','---')");
        }
        
        // AFINIDAD
        $result5= $mysqli->query("SELECT Id FROM t_pa_afinidad where id = ".$parametro.""); 
        $row_cnt5 = $result5->num_rows;
        if ($row_cnt5 == 0) 
        {
        $fila_afinidad = $mysqli->query("INSERT INTO t_pa_afinidad  VALUES ('".$parametro."','---','---','---','---')");
        }
       mysqli_free_result(); 
      
       
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
        // Export detail records (t_conyugue)
		$Doc->Text .='<div class="caja" style="page-break-after:always">"Parte II : DATOS DEL CONYUGUE O CONVIVIENTE <br> </div>';
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_conyugue", explode(",", $this->getCurrentDetailTable()))) {
			global $t_conyugue;
			if (!isset($t_conyugue)) $t_conyugue = new ct_conyugue;
			$rsdetail = $t_conyugue->LoadRs($t_conyugue->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_conyugue->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
        
      	// Export detail records (t_pa_consanguinidad)
		$Doc->Text .='<div class="caja" style="page-break-before:always">"Parte III :RELACIONES DE PARENTESCO POR CONSANGUINIDAD <br> HIJOS,PADRES,HERMANOS y ABUELOS (Registrar a los parientes con vida)</div>';
	    if (EW_EXPORT_DETAIL_RECORDS && in_array("t_pa_consanguinidad", explode(",", $this->getCurrentDetailTable()))) {
			global $t_pa_consanguinidad;
			if (!isset($t_pa_consanguinidad)) $t_pa_consanguinidad = new ct_pa_consanguinidad;
			$rsdetail = $t_pa_consanguinidad->LoadRs($t_pa_consanguinidad->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_pa_consanguinidad->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
		// Export detail records (t_pa_afinidad)
		$Doc->Text .='<div class="caja" style="page-break-before:always">"Parte IV :RELACIONES DE PARENTESCO POR AFINIDAD <br> SUEGROS,CUÑADOS,HIJOS POLÍTICOS (Registrar a los parientes con vida)</div>';
        if (EW_EXPORT_DETAIL_RECORDS && in_array("t_pa_afinidad", explode(",", $this->getCurrentDetailTable()))) {
			global $t_pa_afinidad;
			if (!isset($t_pa_afinidad)) $t_pa_afinidad = new ct_pa_afinidad;
			$rsdetail = $t_pa_afinidad->LoadRs($t_pa_afinidad->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_pa_afinidad->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
        
		// Export detail records (t_re_adopcion)
        $Doc->Text .='<div class="caja" style="page-break-before:always">"Parte V : RELACIONES POR ADOPCIÓN <br> HIJOS EN ADOPCIÓN, PADRES ADOPTANTES PADRASTRIOS </div>';
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_re_adopcion", explode(",", $this->getCurrentDetailTable()))) {
			global $t_re_adopcion;
			if (!isset($t_re_adopcion)) $t_re_adopcion = new ct_re_adopcion;
			$rsdetail = $t_re_adopcion->LoadRs($t_re_adopcion->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_re_adopcion->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
            $Doc->Text .='<br> <br> <br> <br> <br> <br> <br> <br> <br> <hr> ';
		
		}
		// Export detail records (t_mp_si_no)
 
         $Doc->Text .='<div class="caja" style="page-break-before:always">"Parte VI : PARIENTES QUE TRABAJAN EN EL MINISTERIO PÚBLICO</div>';
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_mp_si_no", explode(",", $this->getCurrentDetailTable()))) {
			global $t_mp_si_no;
			if (!isset($t_mp_si_no)) $t_mp_si_no = new ct_mp_si_no;
			$rsdetail = $t_mp_si_no->LoadRs($t_mp_si_no->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_mp_si_no->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
   
			}
		}

		// Export detail records (t_parientes_mp)
		$Doc->Text .='<div class="caja" style="page-break-before:always">"Detallar los parientes hasta 4° grado de consanguinidad y 2° grado de afinidad que trabajan en el  Ministerio Público</div>';
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_parientes_mp", explode(",", $this->getCurrentDetailTable()))) {
			global $t_parientes_mp;
			if (!isset($t_parientes_mp)) $t_parientes_mp = new ct_parientes_mp;
			$rsdetail = $t_parientes_mp->LoadRs($t_parientes_mp->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_parientes_mp->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (t_actiividades_remuneradas)
		$Doc->Text .='<div class="caja" style="page-break-before:always">"Parte VII ACTIVIDADES Y PERCEPCIONES ECONÓMICAS</div>';
        if (EW_EXPORT_DETAIL_RECORDS && in_array("t_actiividades_remuneradas", explode(",", $this->getCurrentDetailTable()))) {
			global $t_actiividades_remuneradas;
			if (!isset($t_actiividades_remuneradas)) $t_actiividades_remuneradas = new ct_actiividades_remuneradas;
			$rsdetail = $t_actiividades_remuneradas->LoadRs($t_actiividades_remuneradas->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_actiividades_remuneradas->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}

		// Export detail records (t_salario)
        $Doc->Text .='<div class="caja" style="page-break-before:always">"Declare si el salario mensual que UD. percibe, supera al salario establecido para el Presidente del Estado Plurinacional de Bolivia <br> Tomar en ciuenta el salario que percibe en el Ministerio Público y fuera de el </div>';
		if (EW_EXPORT_DETAIL_RECORDS && in_array("t_salario", explode(",", $this->getCurrentDetailTable()))) {
			global $t_salario;
			if (!isset($t_salario)) $t_salario = new ct_salario;
			$rsdetail = $t_salario->LoadRs($t_salario->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$t_salario->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
		$Doc->Text .='<br> <br> <hr> ';
		$Doc->Text .='<div class="caja" style="page-break-after:always"> <br> "Juro la exavtitud y veracidad de los datos declarados en el presente documento ala fecha de su presentaci{on <br>
        Asimismo autorizo expresamente a los funcionarios del Ministerio Público a verigicar la información proporcionada <br>
        comprmentiéndome a presentar la documentación que sustente lo declarado en caso de ser requerido
        
        </div>';
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
        $parametro = $this->CI_RUN->CurrentValue;
        $nombre_archivo = trim('t_funcionario_'.$this->Id->CurrentValue.'.pdf');
        $fecha= date('Y-m-d');
        $consulta = $mysqli->query("INSERT INTO t_declaraciones  VALUES ('".$parametro."','".$fecha."','".	$nombre_archivo."')");
        
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
			if (in_array("t_conyugue", $DetailTblVar)) {
				if (!isset($GLOBALS["t_conyugue_grid"]))
					$GLOBALS["t_conyugue_grid"] = new ct_conyugue_grid;
				if ($GLOBALS["t_conyugue_grid"]->DetailView) {
					$GLOBALS["t_conyugue_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_conyugue_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_conyugue_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_conyugue_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_conyugue_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_conyugue_grid"]->Id->setSessionValue($GLOBALS["t_conyugue_grid"]->Id->CurrentValue);
				}
			}
			if (in_array("t_pa_consanguinidad", $DetailTblVar)) {
				if (!isset($GLOBALS["t_pa_consanguinidad_grid"]))
					$GLOBALS["t_pa_consanguinidad_grid"] = new ct_pa_consanguinidad_grid;
				if ($GLOBALS["t_pa_consanguinidad_grid"]->DetailView) {
					$GLOBALS["t_pa_consanguinidad_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_pa_consanguinidad_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_pa_consanguinidad_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_pa_consanguinidad_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_pa_consanguinidad_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_pa_consanguinidad_grid"]->Id->setSessionValue($GLOBALS["t_pa_consanguinidad_grid"]->Id->CurrentValue);
				}
			}
			if (in_array("t_pa_afinidad", $DetailTblVar)) {
				if (!isset($GLOBALS["t_pa_afinidad_grid"]))
					$GLOBALS["t_pa_afinidad_grid"] = new ct_pa_afinidad_grid;
				if ($GLOBALS["t_pa_afinidad_grid"]->DetailView) {
					$GLOBALS["t_pa_afinidad_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_pa_afinidad_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_pa_afinidad_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_pa_afinidad_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_pa_afinidad_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_pa_afinidad_grid"]->Id->setSessionValue($GLOBALS["t_pa_afinidad_grid"]->Id->CurrentValue);
				}
			}
			if (in_array("t_re_adopcion", $DetailTblVar)) {
				if (!isset($GLOBALS["t_re_adopcion_grid"]))
					$GLOBALS["t_re_adopcion_grid"] = new ct_re_adopcion_grid;
				if ($GLOBALS["t_re_adopcion_grid"]->DetailView) {
					$GLOBALS["t_re_adopcion_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_re_adopcion_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_re_adopcion_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_re_adopcion_grid"]->id->FldIsDetailKey = TRUE;
					$GLOBALS["t_re_adopcion_grid"]->id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_re_adopcion_grid"]->id->setSessionValue($GLOBALS["t_re_adopcion_grid"]->id->CurrentValue);
				}
			}
			if (in_array("t_mp_si_no", $DetailTblVar)) {
				if (!isset($GLOBALS["t_mp_si_no_grid"]))
					$GLOBALS["t_mp_si_no_grid"] = new ct_mp_si_no_grid;
				if ($GLOBALS["t_mp_si_no_grid"]->DetailView) {
					$GLOBALS["t_mp_si_no_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_mp_si_no_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_mp_si_no_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_mp_si_no_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_mp_si_no_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_mp_si_no_grid"]->Id->setSessionValue($GLOBALS["t_mp_si_no_grid"]->Id->CurrentValue);
				}
			}
			if (in_array("t_parientes_mp", $DetailTblVar)) {
				if (!isset($GLOBALS["t_parientes_mp_grid"]))
					$GLOBALS["t_parientes_mp_grid"] = new ct_parientes_mp_grid;
				if ($GLOBALS["t_parientes_mp_grid"]->DetailView) {
					$GLOBALS["t_parientes_mp_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_parientes_mp_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_parientes_mp_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_parientes_mp_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_parientes_mp_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_parientes_mp_grid"]->Id->setSessionValue($GLOBALS["t_parientes_mp_grid"]->Id->CurrentValue);
				}
			}
			if (in_array("t_actiividades_remuneradas", $DetailTblVar)) {
				if (!isset($GLOBALS["t_actiividades_remuneradas_grid"]))
					$GLOBALS["t_actiividades_remuneradas_grid"] = new ct_actiividades_remuneradas_grid;
				if ($GLOBALS["t_actiividades_remuneradas_grid"]->DetailView) {
					$GLOBALS["t_actiividades_remuneradas_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_actiividades_remuneradas_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_actiividades_remuneradas_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_actiividades_remuneradas_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_actiividades_remuneradas_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_actiividades_remuneradas_grid"]->Id->setSessionValue($GLOBALS["t_actiividades_remuneradas_grid"]->Id->CurrentValue);
				}
			}
			if (in_array("t_salario", $DetailTblVar)) {
				if (!isset($GLOBALS["t_salario_grid"]))
					$GLOBALS["t_salario_grid"] = new ct_salario_grid;
				if ($GLOBALS["t_salario_grid"]->DetailView) {
					$GLOBALS["t_salario_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["t_salario_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_salario_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_salario_grid"]->Id->FldIsDetailKey = TRUE;
					$GLOBALS["t_salario_grid"]->Id->CurrentValue = $this->Id->CurrentValue;
					$GLOBALS["t_salario_grid"]->Id->setSessionValue($GLOBALS["t_salario_grid"]->Id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_funcionariolist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
	}

	// Set up detail pages
	function SetupDetailPages() {
		$pages = new cSubPages();
		$pages->Add('t_conyugue');
		$pages->Add('t_pa_consanguinidad');
		$pages->Add('t_pa_afinidad');
		$pages->Add('t_re_adopcion');
		$pages->Add('t_mp_si_no');
		$pages->Add('t_parientes_mp');
		$pages->Add('t_actiividades_remuneradas');
		$pages->Add('t_salario');
		$this->DetailPages = $pages;
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

	// Write Audit Trail (view page)
	function WriteAuditTrailOnView(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnView) return;
		$table = 't_funcionario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['Id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		if ($this->AuditTrailOnViewData) { // Write all data
			foreach (array_keys($rs) as $fldname) {
				if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
						$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
						if (EW_AUDIT_TRAIL_TO_DATABASE)
							$oldvalue = $rs[$fldname];
						else
							$oldvalue = "[MEMO]"; // Memo Field
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
						$oldvalue = "[XML]"; // XML Field
					} else {
						$oldvalue = $rs[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "V", $table, $fldname, $key, $oldvalue, "");
				}
			}
		} else { // Write record id only
			ew_WriteAuditTrail("log", $dt, $id, $usr, "V", $table, "", $key, "", "");
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
    $logo= ('<img src="phpimages/logo.jpg" style="float:left;clear:left;" "/>');
    $Escudo= ('<img src="phpimages/escudo.jpg" style="float:right;clear:right;" " />');
    $Leyenda = "<br>"."DECLARACION JURADA DE INEXISTENCIA DE INCOMPATIBILIDADES ESTABLECIDAS POR LEY <br>"."UNIDAD DE RECURSOS HUMANOS <br>"."GESTION 2018";
    //$tabla_funcionario ="<br>"."PARTE I : DATOS DEL FUNCIONARIO <br>" ;
    $tabla_funcionario ='<br>'.'<div class="caja" style="page-break-after:always">" Parte I : DATOS DEL FUNCIONARIO  </div>';
    $header = $Escudo.$logo.$Leyenda.$tabla_funcionario;
//$this->ExportDoc->Text .= "my footer"; // Export footer
//echo $this->ExportDoc->Text;

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {
	 $fecha = ew_StdCurrentDateTime();
     $blanco = "<br>";
     $linea = '<hr>';
     $footer = $blanco.$blanco.$linea.$blanco.$blanco."Firma del Funcionario <br>".$fecha;
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
    
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($t_funcionario_view)) $t_funcionario_view = new ct_funcionario_view();

// Page init
$t_funcionario_view->Page_Init();

// Page main
$t_funcionario_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_funcionario_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_funcionario->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ft_funcionarioview = new ew_Form("ft_funcionarioview", "view");

// Form_CustomValidate event
ft_funcionarioview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_funcionarioview.ValidateRequired = true;
<?php } else { ?>
ft_funcionarioview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_funcionarioview.Lists["x_Estado_Civil"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_funcionarioview.Lists["x_Estado_Civil"].Options = <?php echo json_encode($t_funcionario->Estado_Civil->Options()) ?>;
ft_funcionarioview.Lists["x_Fiscalia_otro"] = {"LinkField":"x_Fiscalia","Ajax":true,"AutoFill":false,"DisplayFields":["x_Fiscalia","x_Unidad_Organizacional","x_Unidad","x_Cargo"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"seleccion_cargos"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_funcionario->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$t_funcionario_view->IsModal) { ?>
<?php if ($t_funcionario->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $t_funcionario_view->ExportOptions->Render("body") ?>
<?php
	foreach ($t_funcionario_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$t_funcionario_view->IsModal) { ?>
<?php if ($t_funcionario->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_funcionario_view->ShowPageHeader(); ?>
<?php
$t_funcionario_view->ShowMessage();
?>
<form name="ft_funcionarioview" id="ft_funcionarioview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_funcionario_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_funcionario_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_funcionario">
<?php if ($t_funcionario_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($t_funcionario->CI_RUN->Visible) { // CI_RUN ?>
	<tr id="r_CI_RUN">
		<td><span id="elh_t_funcionario_CI_RUN"><?php echo $t_funcionario->CI_RUN->FldCaption() ?></span></td>
		<td data-name="CI_RUN"<?php echo $t_funcionario->CI_RUN->CellAttributes() ?>>
<span id="el_t_funcionario_CI_RUN" data-page="1">
<span<?php echo $t_funcionario->CI_RUN->ViewAttributes() ?>>
<?php echo $t_funcionario->CI_RUN->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Expedido->Visible) { // Expedido ?>
	<tr id="r_Expedido">
		<td><span id="elh_t_funcionario_Expedido"><?php echo $t_funcionario->Expedido->FldCaption() ?></span></td>
		<td data-name="Expedido"<?php echo $t_funcionario->Expedido->CellAttributes() ?>>
<span id="el_t_funcionario_Expedido" data-page="1">
<span<?php echo $t_funcionario->Expedido->ViewAttributes() ?>>
<?php echo $t_funcionario->Expedido->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<tr id="r_Apellido_Paterno">
		<td><span id="elh_t_funcionario_Apellido_Paterno"><?php echo $t_funcionario->Apellido_Paterno->FldCaption() ?></span></td>
		<td data-name="Apellido_Paterno"<?php echo $t_funcionario->Apellido_Paterno->CellAttributes() ?>>
<span id="el_t_funcionario_Apellido_Paterno" data-page="1">
<span<?php echo $t_funcionario->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_funcionario->Apellido_Paterno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<tr id="r_Apellido_Materno">
		<td><span id="elh_t_funcionario_Apellido_Materno"><?php echo $t_funcionario->Apellido_Materno->FldCaption() ?></span></td>
		<td data-name="Apellido_Materno"<?php echo $t_funcionario->Apellido_Materno->CellAttributes() ?>>
<span id="el_t_funcionario_Apellido_Materno" data-page="1">
<span<?php echo $t_funcionario->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_funcionario->Apellido_Materno->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Nombres->Visible) { // Nombres ?>
	<tr id="r_Nombres">
		<td><span id="elh_t_funcionario_Nombres"><?php echo $t_funcionario->Nombres->FldCaption() ?></span></td>
		<td data-name="Nombres"<?php echo $t_funcionario->Nombres->CellAttributes() ?>>
<span id="el_t_funcionario_Nombres" data-page="1">
<span<?php echo $t_funcionario->Nombres->ViewAttributes() ?>>
<?php echo $t_funcionario->Nombres->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Fecha_Nacimiento->Visible) { // Fecha_Nacimiento ?>
	<tr id="r_Fecha_Nacimiento">
		<td><span id="elh_t_funcionario_Fecha_Nacimiento"><?php echo $t_funcionario->Fecha_Nacimiento->FldCaption() ?></span></td>
		<td data-name="Fecha_Nacimiento"<?php echo $t_funcionario->Fecha_Nacimiento->CellAttributes() ?>>
<span id="el_t_funcionario_Fecha_Nacimiento" data-page="1">
<span<?php echo $t_funcionario->Fecha_Nacimiento->ViewAttributes() ?>>
<?php echo $t_funcionario->Fecha_Nacimiento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Estado_Civil->Visible) { // Estado_Civil ?>
	<tr id="r_Estado_Civil">
		<td><span id="elh_t_funcionario_Estado_Civil"><?php echo $t_funcionario->Estado_Civil->FldCaption() ?></span></td>
		<td data-name="Estado_Civil"<?php echo $t_funcionario->Estado_Civil->CellAttributes() ?>>
<span id="el_t_funcionario_Estado_Civil" data-page="1">
<span<?php echo $t_funcionario->Estado_Civil->ViewAttributes() ?>>
<?php echo $t_funcionario->Estado_Civil->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Direccion->Visible) { // Direccion ?>
	<tr id="r_Direccion">
		<td><span id="elh_t_funcionario_Direccion"><?php echo $t_funcionario->Direccion->FldCaption() ?></span></td>
		<td data-name="Direccion"<?php echo $t_funcionario->Direccion->CellAttributes() ?>>
<span id="el_t_funcionario_Direccion" data-page="1">
<span<?php echo $t_funcionario->Direccion->ViewAttributes() ?>>
<?php echo $t_funcionario->Direccion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Telefono->Visible) { // Telefono ?>
	<tr id="r_Telefono">
		<td><span id="elh_t_funcionario_Telefono"><?php echo $t_funcionario->Telefono->FldCaption() ?></span></td>
		<td data-name="Telefono"<?php echo $t_funcionario->Telefono->CellAttributes() ?>>
<span id="el_t_funcionario_Telefono" data-page="1">
<span<?php echo $t_funcionario->Telefono->ViewAttributes() ?>>
<?php echo $t_funcionario->Telefono->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Celular->Visible) { // Celular ?>
	<tr id="r_Celular">
		<td><span id="elh_t_funcionario_Celular"><?php echo $t_funcionario->Celular->FldCaption() ?></span></td>
		<td data-name="Celular"<?php echo $t_funcionario->Celular->CellAttributes() ?>>
<span id="el_t_funcionario_Celular" data-page="1">
<span<?php echo $t_funcionario->Celular->ViewAttributes() ?>>
<?php echo $t_funcionario->Celular->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<tr id="r_Fiscalia_otro">
		<td><span id="elh_t_funcionario_Fiscalia_otro"><?php echo $t_funcionario->Fiscalia_otro->FldCaption() ?></span></td>
		<td data-name="Fiscalia_otro"<?php echo $t_funcionario->Fiscalia_otro->CellAttributes() ?>>
<span id="el_t_funcionario_Fiscalia_otro" data-page="1">
<span<?php echo $t_funcionario->Fiscalia_otro->ViewAttributes() ?>>
<?php echo $t_funcionario->Fiscalia_otro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<tr id="r_Unidad_Organizacional">
		<td><span id="elh_t_funcionario_Unidad_Organizacional"><?php echo $t_funcionario->Unidad_Organizacional->FldCaption() ?></span></td>
		<td data-name="Unidad_Organizacional"<?php echo $t_funcionario->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_funcionario_Unidad_Organizacional" data-page="1">
<span<?php echo $t_funcionario->Unidad_Organizacional->ViewAttributes() ?>>
<?php echo $t_funcionario->Unidad_Organizacional->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Unidad->Visible) { // Unidad ?>
	<tr id="r_Unidad">
		<td><span id="elh_t_funcionario_Unidad"><?php echo $t_funcionario->Unidad->FldCaption() ?></span></td>
		<td data-name="Unidad"<?php echo $t_funcionario->Unidad->CellAttributes() ?>>
<span id="el_t_funcionario_Unidad" data-page="1">
<span<?php echo $t_funcionario->Unidad->ViewAttributes() ?>>
<?php echo $t_funcionario->Unidad->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Cargo->Visible) { // Cargo ?>
	<tr id="r_Cargo">
		<td><span id="elh_t_funcionario_Cargo"><?php echo $t_funcionario->Cargo->FldCaption() ?></span></td>
		<td data-name="Cargo"<?php echo $t_funcionario->Cargo->CellAttributes() ?>>
<span id="el_t_funcionario_Cargo" data-page="1">
<span<?php echo $t_funcionario->Cargo->ViewAttributes() ?>>
<?php echo $t_funcionario->Cargo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($t_funcionario->Fecha_registro->Visible) { // Fecha_registro ?>
	<tr id="r_Fecha_registro">
		<td><span id="elh_t_funcionario_Fecha_registro"><?php echo $t_funcionario->Fecha_registro->FldCaption() ?></span></td>
		<td data-name="Fecha_registro"<?php echo $t_funcionario->Fecha_registro->CellAttributes() ?>>
<span id="el_t_funcionario_Fecha_registro" data-page="1">
<span<?php echo $t_funcionario->Fecha_registro->ViewAttributes() ?>>
<?php echo $t_funcionario->Fecha_registro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($t_funcionario->getCurrentDetailTable() <> "") { ?>
<?php
	$t_funcionario_view->DetailPages->ValidKeys = explode(",", $t_funcionario->getCurrentDetailTable());
	$FirstActiveDetailTable = $t_funcionario_view->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="panel-group" id="t_funcionario_view_details">
<?php
	if (in_array("t_conyugue", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_conyugue->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_conyugue") {
			$FirstActiveDetailTable = "t_conyugue";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_conyugue") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_conyugue"><?php echo $Language->TablePhrase("t_conyugue", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_conyugue") ?>" id="tab_t_conyugue">
			<div class="panel-body">
<?php include_once "t_conyuguegrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_pa_consanguinidad", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_pa_consanguinidad->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_pa_consanguinidad") {
			$FirstActiveDetailTable = "t_pa_consanguinidad";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_pa_consanguinidad") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_pa_consanguinidad"><?php echo $Language->TablePhrase("t_pa_consanguinidad", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_pa_consanguinidad") ?>" id="tab_t_pa_consanguinidad">
			<div class="panel-body">
<?php include_once "t_pa_consanguinidadgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_pa_afinidad", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_pa_afinidad->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_pa_afinidad") {
			$FirstActiveDetailTable = "t_pa_afinidad";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_pa_afinidad") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_pa_afinidad"><?php echo $Language->TablePhrase("t_pa_afinidad", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_pa_afinidad") ?>" id="tab_t_pa_afinidad">
			<div class="panel-body">
<?php include_once "t_pa_afinidadgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_re_adopcion", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_re_adopcion->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_re_adopcion") {
			$FirstActiveDetailTable = "t_re_adopcion";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_re_adopcion") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_re_adopcion"><?php echo $Language->TablePhrase("t_re_adopcion", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_re_adopcion") ?>" id="tab_t_re_adopcion">
			<div class="panel-body">
<?php include_once "t_re_adopciongrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_mp_si_no", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_mp_si_no->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_mp_si_no") {
			$FirstActiveDetailTable = "t_mp_si_no";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_mp_si_no") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_mp_si_no"><?php echo $Language->TablePhrase("t_mp_si_no", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_mp_si_no") ?>" id="tab_t_mp_si_no">
			<div class="panel-body">
<?php include_once "t_mp_si_nogrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_parientes_mp", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_parientes_mp->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_parientes_mp") {
			$FirstActiveDetailTable = "t_parientes_mp";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_parientes_mp") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_parientes_mp"><?php echo $Language->TablePhrase("t_parientes_mp", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_parientes_mp") ?>" id="tab_t_parientes_mp">
			<div class="panel-body">
<?php include_once "t_parientes_mpgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_actiividades_remuneradas", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_actiividades_remuneradas->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_actiividades_remuneradas") {
			$FirstActiveDetailTable = "t_actiividades_remuneradas";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_actiividades_remuneradas") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_actiividades_remuneradas"><?php echo $Language->TablePhrase("t_actiividades_remuneradas", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_actiividades_remuneradas") ?>" id="tab_t_actiividades_remuneradas">
			<div class="panel-body">
<?php include_once "t_actiividades_remuneradasgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_salario", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_salario->DetailView) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_salario") {
			$FirstActiveDetailTable = "t_salario";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_view->DetailPages->PageStyle("t_salario") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_view_details" href="#tab_t_salario"><?php echo $Language->TablePhrase("t_salario", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_view->DetailPages->PageStyle("t_salario") ?>" id="tab_t_salario">
			<div class="panel-body">
<?php include_once "t_salariogrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
</div>
</div>
<?php } ?>
</form>
<?php if ($t_funcionario->Export == "") { ?>
<script type="text/javascript">
ft_funcionarioview.Init();
</script>
<?php } ?>
<?php
$t_funcionario_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_funcionario->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_funcionario_view->Page_Terminate();
?>
