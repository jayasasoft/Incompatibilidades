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

$t_funcionario_edit = NULL; // Initialize page object first

class ct_funcionario_edit extends ct_funcionario {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_funcionario';

	// Page object name
	var $PageObjName = 't_funcionario_edit';

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
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = TRUE;
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

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_funcionariolist.php"));
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DetailPages; // Detail pages object

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
		if (@$_GET["Id"] <> "") {
			$this->Id->setQueryStringValue($_GET["Id"]);
		}

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
		if ($this->Id->CurrentValue == "") {
			$this->Page_Terminate("t_funcionariolist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("t_funcionariolist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_funcionariolist.php")
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
		if (!$this->CI_RUN->FldIsDetailKey) {
			$this->CI_RUN->setFormValue($objForm->GetValue("x_CI_RUN"));
		}
		if (!$this->Expedido->FldIsDetailKey) {
			$this->Expedido->setFormValue($objForm->GetValue("x_Expedido"));
		}
		if (!$this->Apellido_Paterno->FldIsDetailKey) {
			$this->Apellido_Paterno->setFormValue($objForm->GetValue("x_Apellido_Paterno"));
		}
		if (!$this->Apellido_Materno->FldIsDetailKey) {
			$this->Apellido_Materno->setFormValue($objForm->GetValue("x_Apellido_Materno"));
		}
		if (!$this->Nombres->FldIsDetailKey) {
			$this->Nombres->setFormValue($objForm->GetValue("x_Nombres"));
		}
		if (!$this->Fecha_Nacimiento->FldIsDetailKey) {
			$this->Fecha_Nacimiento->setFormValue($objForm->GetValue("x_Fecha_Nacimiento"));
			$this->Fecha_Nacimiento->CurrentValue = ew_UnFormatDateTime($this->Fecha_Nacimiento->CurrentValue, 13);
		}
		if (!$this->Estado_Civil->FldIsDetailKey) {
			$this->Estado_Civil->setFormValue($objForm->GetValue("x_Estado_Civil"));
		}
		if (!$this->Direccion->FldIsDetailKey) {
			$this->Direccion->setFormValue($objForm->GetValue("x_Direccion"));
		}
		if (!$this->Telefono->FldIsDetailKey) {
			$this->Telefono->setFormValue($objForm->GetValue("x_Telefono"));
		}
		if (!$this->Celular->FldIsDetailKey) {
			$this->Celular->setFormValue($objForm->GetValue("x_Celular"));
		}
		if (!$this->Fiscalia_otro->FldIsDetailKey) {
			$this->Fiscalia_otro->setFormValue($objForm->GetValue("x_Fiscalia_otro"));
		}
		if (!$this->Unidad_Organizacional->FldIsDetailKey) {
			$this->Unidad_Organizacional->setFormValue($objForm->GetValue("x_Unidad_Organizacional"));
		}
		if (!$this->Unidad->FldIsDetailKey) {
			$this->Unidad->setFormValue($objForm->GetValue("x_Unidad"));
		}
		if (!$this->Cargo->FldIsDetailKey) {
			$this->Cargo->setFormValue($objForm->GetValue("x_Cargo"));
		}
		if (!$this->Fecha_registro->FldIsDetailKey) {
			$this->Fecha_registro->setFormValue($objForm->GetValue("x_Fecha_registro"));
			$this->Fecha_registro->CurrentValue = ew_UnFormatDateTime($this->Fecha_registro->CurrentValue, 0);
		}
		if (!$this->Id->FldIsDetailKey)
			$this->Id->setFormValue($objForm->GetValue("x_Id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->Id->CurrentValue = $this->Id->FormValue;
		$this->CI_RUN->CurrentValue = $this->CI_RUN->FormValue;
		$this->Expedido->CurrentValue = $this->Expedido->FormValue;
		$this->Apellido_Paterno->CurrentValue = $this->Apellido_Paterno->FormValue;
		$this->Apellido_Materno->CurrentValue = $this->Apellido_Materno->FormValue;
		$this->Nombres->CurrentValue = $this->Nombres->FormValue;
		$this->Fecha_Nacimiento->CurrentValue = $this->Fecha_Nacimiento->FormValue;
		$this->Fecha_Nacimiento->CurrentValue = ew_UnFormatDateTime($this->Fecha_Nacimiento->CurrentValue, 13);
		$this->Estado_Civil->CurrentValue = $this->Estado_Civil->FormValue;
		$this->Direccion->CurrentValue = $this->Direccion->FormValue;
		$this->Telefono->CurrentValue = $this->Telefono->FormValue;
		$this->Celular->CurrentValue = $this->Celular->FormValue;
		$this->Fiscalia_otro->CurrentValue = $this->Fiscalia_otro->FormValue;
		$this->Unidad_Organizacional->CurrentValue = $this->Unidad_Organizacional->FormValue;
		$this->Unidad->CurrentValue = $this->Unidad->FormValue;
		$this->Cargo->CurrentValue = $this->Cargo->FormValue;
		$this->Fecha_registro->CurrentValue = $this->Fecha_registro->FormValue;
		$this->Fecha_registro->CurrentValue = ew_UnFormatDateTime($this->Fecha_registro->CurrentValue, 0);
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// CI_RUN
			$this->CI_RUN->EditAttrs["class"] = "form-control";
			$this->CI_RUN->EditCustomAttributes = "";
			$this->CI_RUN->EditValue = ew_HtmlEncode($this->CI_RUN->CurrentValue);
			$this->CI_RUN->PlaceHolder = ew_RemoveHtml($this->CI_RUN->FldCaption());

			// Expedido
			$this->Expedido->EditAttrs["class"] = "form-control";
			$this->Expedido->EditCustomAttributes = "";
			$this->Expedido->EditValue = ew_HtmlEncode($this->Expedido->CurrentValue);
			$this->Expedido->PlaceHolder = ew_RemoveHtml($this->Expedido->FldCaption());

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

			// Nombres
			$this->Nombres->EditAttrs["class"] = "form-control";
			$this->Nombres->EditCustomAttributes = "";
			$this->Nombres->EditValue = ew_HtmlEncode($this->Nombres->CurrentValue);
			$this->Nombres->PlaceHolder = ew_RemoveHtml($this->Nombres->FldCaption());

			// Fecha_Nacimiento
			$this->Fecha_Nacimiento->EditAttrs["class"] = "form-control";
			$this->Fecha_Nacimiento->EditCustomAttributes = "";
			$this->Fecha_Nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->Fecha_Nacimiento->CurrentValue, 13));
			$this->Fecha_Nacimiento->PlaceHolder = ew_RemoveHtml($this->Fecha_Nacimiento->FldCaption());

			// Estado_Civil
			$this->Estado_Civil->EditAttrs["class"] = "form-control";
			$this->Estado_Civil->EditCustomAttributes = "";
			$this->Estado_Civil->EditValue = $this->Estado_Civil->Options(TRUE);

			// Direccion
			$this->Direccion->EditAttrs["class"] = "form-control";
			$this->Direccion->EditCustomAttributes = "";
			$this->Direccion->EditValue = ew_HtmlEncode($this->Direccion->CurrentValue);
			$this->Direccion->PlaceHolder = ew_RemoveHtml($this->Direccion->FldCaption());

			// Telefono
			$this->Telefono->EditAttrs["class"] = "form-control";
			$this->Telefono->EditCustomAttributes = "";
			$this->Telefono->EditValue = ew_HtmlEncode($this->Telefono->CurrentValue);
			$this->Telefono->PlaceHolder = ew_RemoveHtml($this->Telefono->FldCaption());

			// Celular
			$this->Celular->EditAttrs["class"] = "form-control";
			$this->Celular->EditCustomAttributes = "";
			$this->Celular->EditValue = ew_HtmlEncode($this->Celular->CurrentValue);
			$this->Celular->PlaceHolder = ew_RemoveHtml($this->Celular->FldCaption());

			// Fiscalia_otro
			$this->Fiscalia_otro->EditCustomAttributes = "";
			if (trim(strval($this->Fiscalia_otro->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Fiscalia`" . ew_SearchString("=", $this->Fiscalia_otro->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Fiscalia`, `Fiscalia` AS `DispFld`, `Unidad_Organizacional` AS `Disp2Fld`, `Unidad` AS `Disp3Fld`, `Cargo` AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `seleccion_cargos`";
			$sWhereWrk = "";
			$this->Fiscalia_otro->LookupFilters = array("dx1" => '`Fiscalia`', "dx2" => '`Unidad_Organizacional`', "dx3" => '`Unidad`', "dx4" => '`Cargo`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Fiscalia_otro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$arwrk[4] = ew_HtmlEncode($rswrk->fields('Disp4Fld'));
				$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->DisplayValue($arwrk);
			} else {
				$this->Fiscalia_otro->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Fiscalia_otro->EditValue = $arwrk;

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			$this->Unidad_Organizacional->EditValue = ew_HtmlEncode($this->Unidad_Organizacional->CurrentValue);
			$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

			// Unidad
			$this->Unidad->EditAttrs["class"] = "form-control";
			$this->Unidad->EditCustomAttributes = "";
			$this->Unidad->EditValue = ew_HtmlEncode($this->Unidad->CurrentValue);
			$this->Unidad->PlaceHolder = ew_RemoveHtml($this->Unidad->FldCaption());

			// Cargo
			$this->Cargo->EditAttrs["class"] = "form-control";
			$this->Cargo->EditCustomAttributes = "";
			$this->Cargo->EditValue = ew_HtmlEncode($this->Cargo->CurrentValue);
			$this->Cargo->PlaceHolder = ew_RemoveHtml($this->Cargo->FldCaption());

			// Fecha_registro
			// Edit refer script
			// CI_RUN

			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";

			// Expedido
			$this->Expedido->LinkCustomAttributes = "";
			$this->Expedido->HrefValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";

			// Nombres
			$this->Nombres->LinkCustomAttributes = "";
			$this->Nombres->HrefValue = "";

			// Fecha_Nacimiento
			$this->Fecha_Nacimiento->LinkCustomAttributes = "";
			$this->Fecha_Nacimiento->HrefValue = "";

			// Estado_Civil
			$this->Estado_Civil->LinkCustomAttributes = "";
			$this->Estado_Civil->HrefValue = "";

			// Direccion
			$this->Direccion->LinkCustomAttributes = "";
			$this->Direccion->HrefValue = "";

			// Telefono
			$this->Telefono->LinkCustomAttributes = "";
			$this->Telefono->HrefValue = "";

			// Celular
			$this->Celular->LinkCustomAttributes = "";
			$this->Celular->HrefValue = "";

			// Fiscalia_otro
			$this->Fiscalia_otro->LinkCustomAttributes = "";
			$this->Fiscalia_otro->HrefValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";

			// Cargo
			$this->Cargo->LinkCustomAttributes = "";
			$this->Cargo->HrefValue = "";

			// Fecha_registro
			$this->Fecha_registro->LinkCustomAttributes = "";
			$this->Fecha_registro->HrefValue = "";
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
		if (!$this->CI_RUN->FldIsDetailKey && !is_null($this->CI_RUN->FormValue) && $this->CI_RUN->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CI_RUN->FldCaption(), $this->CI_RUN->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->CI_RUN->FormValue)) {
			ew_AddMessage($gsFormError, $this->CI_RUN->FldErrMsg());
		}
		if (!$this->Expedido->FldIsDetailKey && !is_null($this->Expedido->FormValue) && $this->Expedido->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Expedido->FldCaption(), $this->Expedido->ReqErrMsg));
		}
		if (!$this->Apellido_Paterno->FldIsDetailKey && !is_null($this->Apellido_Paterno->FormValue) && $this->Apellido_Paterno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Apellido_Paterno->FldCaption(), $this->Apellido_Paterno->ReqErrMsg));
		}
		if (!$this->Apellido_Materno->FldIsDetailKey && !is_null($this->Apellido_Materno->FormValue) && $this->Apellido_Materno->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Apellido_Materno->FldCaption(), $this->Apellido_Materno->ReqErrMsg));
		}
		if (!$this->Nombres->FldIsDetailKey && !is_null($this->Nombres->FormValue) && $this->Nombres->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nombres->FldCaption(), $this->Nombres->ReqErrMsg));
		}
		if (!$this->Fecha_Nacimiento->FldIsDetailKey && !is_null($this->Fecha_Nacimiento->FormValue) && $this->Fecha_Nacimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Fecha_Nacimiento->FldCaption(), $this->Fecha_Nacimiento->ReqErrMsg));
		}
		if (!ew_CheckShortUSDate($this->Fecha_Nacimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->Fecha_Nacimiento->FldErrMsg());
		}
		if (!$this->Estado_Civil->FldIsDetailKey && !is_null($this->Estado_Civil->FormValue) && $this->Estado_Civil->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Estado_Civil->FldCaption(), $this->Estado_Civil->ReqErrMsg));
		}
		if (!$this->Direccion->FldIsDetailKey && !is_null($this->Direccion->FormValue) && $this->Direccion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Direccion->FldCaption(), $this->Direccion->ReqErrMsg));
		}
		if (!ew_CheckRange($this->Telefono->FormValue, 10000000, 80000000)) {
			ew_AddMessage($gsFormError, $this->Telefono->FldErrMsg());
		}
		if (!ew_CheckRange($this->Celular->FormValue, 10000000, 80000000)) {
			ew_AddMessage($gsFormError, $this->Celular->FldErrMsg());
		}
		if (!$this->Fiscalia_otro->FldIsDetailKey && !is_null($this->Fiscalia_otro->FormValue) && $this->Fiscalia_otro->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Fiscalia_otro->FldCaption(), $this->Fiscalia_otro->ReqErrMsg));
		}
		if (!$this->Unidad_Organizacional->FldIsDetailKey && !is_null($this->Unidad_Organizacional->FormValue) && $this->Unidad_Organizacional->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Unidad_Organizacional->FldCaption(), $this->Unidad_Organizacional->ReqErrMsg));
		}
		if (!$this->Unidad->FldIsDetailKey && !is_null($this->Unidad->FormValue) && $this->Unidad->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Unidad->FldCaption(), $this->Unidad->ReqErrMsg));
		}
		if (!$this->Cargo->FldIsDetailKey && !is_null($this->Cargo->FormValue) && $this->Cargo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Cargo->FldCaption(), $this->Cargo->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_conyugue", $DetailTblVar) && $GLOBALS["t_conyugue"]->DetailEdit) {
			if (!isset($GLOBALS["t_conyugue_grid"])) $GLOBALS["t_conyugue_grid"] = new ct_conyugue_grid(); // get detail page object
			$GLOBALS["t_conyugue_grid"]->ValidateGridForm();
		}
		if (in_array("t_pa_consanguinidad", $DetailTblVar) && $GLOBALS["t_pa_consanguinidad"]->DetailEdit) {
			if (!isset($GLOBALS["t_pa_consanguinidad_grid"])) $GLOBALS["t_pa_consanguinidad_grid"] = new ct_pa_consanguinidad_grid(); // get detail page object
			$GLOBALS["t_pa_consanguinidad_grid"]->ValidateGridForm();
		}
		if (in_array("t_pa_afinidad", $DetailTblVar) && $GLOBALS["t_pa_afinidad"]->DetailEdit) {
			if (!isset($GLOBALS["t_pa_afinidad_grid"])) $GLOBALS["t_pa_afinidad_grid"] = new ct_pa_afinidad_grid(); // get detail page object
			$GLOBALS["t_pa_afinidad_grid"]->ValidateGridForm();
		}
		if (in_array("t_re_adopcion", $DetailTblVar) && $GLOBALS["t_re_adopcion"]->DetailEdit) {
			if (!isset($GLOBALS["t_re_adopcion_grid"])) $GLOBALS["t_re_adopcion_grid"] = new ct_re_adopcion_grid(); // get detail page object
			$GLOBALS["t_re_adopcion_grid"]->ValidateGridForm();
		}
		if (in_array("t_mp_si_no", $DetailTblVar) && $GLOBALS["t_mp_si_no"]->DetailEdit) {
			if (!isset($GLOBALS["t_mp_si_no_grid"])) $GLOBALS["t_mp_si_no_grid"] = new ct_mp_si_no_grid(); // get detail page object
			$GLOBALS["t_mp_si_no_grid"]->ValidateGridForm();
		}
		if (in_array("t_parientes_mp", $DetailTblVar) && $GLOBALS["t_parientes_mp"]->DetailEdit) {
			if (!isset($GLOBALS["t_parientes_mp_grid"])) $GLOBALS["t_parientes_mp_grid"] = new ct_parientes_mp_grid(); // get detail page object
			$GLOBALS["t_parientes_mp_grid"]->ValidateGridForm();
		}
		if (in_array("t_actiividades_remuneradas", $DetailTblVar) && $GLOBALS["t_actiividades_remuneradas"]->DetailEdit) {
			if (!isset($GLOBALS["t_actiividades_remuneradas_grid"])) $GLOBALS["t_actiividades_remuneradas_grid"] = new ct_actiividades_remuneradas_grid(); // get detail page object
			$GLOBALS["t_actiividades_remuneradas_grid"]->ValidateGridForm();
		}
		if (in_array("t_salario", $DetailTblVar) && $GLOBALS["t_salario"]->DetailEdit) {
			if (!isset($GLOBALS["t_salario_grid"])) $GLOBALS["t_salario_grid"] = new ct_salario_grid(); // get detail page object
			$GLOBALS["t_salario_grid"]->ValidateGridForm();
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
		if ($this->CI_RUN->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`CI_RUN` = '" . ew_AdjustSql($this->CI_RUN->CurrentValue, $this->DBID) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->CI_RUN->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->CI_RUN->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
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

			// CI_RUN
			$this->CI_RUN->SetDbValueDef($rsnew, $this->CI_RUN->CurrentValue, "", $this->CI_RUN->ReadOnly);

			// Expedido
			$this->Expedido->SetDbValueDef($rsnew, $this->Expedido->CurrentValue, "", $this->Expedido->ReadOnly);

			// Apellido_Paterno
			$this->Apellido_Paterno->SetDbValueDef($rsnew, $this->Apellido_Paterno->CurrentValue, "", $this->Apellido_Paterno->ReadOnly);

			// Apellido_Materno
			$this->Apellido_Materno->SetDbValueDef($rsnew, $this->Apellido_Materno->CurrentValue, "", $this->Apellido_Materno->ReadOnly);

			// Nombres
			$this->Nombres->SetDbValueDef($rsnew, $this->Nombres->CurrentValue, "", $this->Nombres->ReadOnly);

			// Fecha_Nacimiento
			$this->Fecha_Nacimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->Fecha_Nacimiento->CurrentValue, 13), ew_CurrentDate(), $this->Fecha_Nacimiento->ReadOnly);

			// Estado_Civil
			$this->Estado_Civil->SetDbValueDef($rsnew, $this->Estado_Civil->CurrentValue, "", $this->Estado_Civil->ReadOnly);

			// Direccion
			$this->Direccion->SetDbValueDef($rsnew, $this->Direccion->CurrentValue, "", $this->Direccion->ReadOnly);

			// Telefono
			$this->Telefono->SetDbValueDef($rsnew, $this->Telefono->CurrentValue, NULL, $this->Telefono->ReadOnly);

			// Celular
			$this->Celular->SetDbValueDef($rsnew, $this->Celular->CurrentValue, NULL, $this->Celular->ReadOnly);

			// Fiscalia_otro
			$this->Fiscalia_otro->SetDbValueDef($rsnew, $this->Fiscalia_otro->CurrentValue, "", $this->Fiscalia_otro->ReadOnly);

			// Unidad_Organizacional
			$this->Unidad_Organizacional->SetDbValueDef($rsnew, $this->Unidad_Organizacional->CurrentValue, "", $this->Unidad_Organizacional->ReadOnly);

			// Unidad
			$this->Unidad->SetDbValueDef($rsnew, $this->Unidad->CurrentValue, "", $this->Unidad->ReadOnly);

			// Cargo
			$this->Cargo->SetDbValueDef($rsnew, $this->Cargo->CurrentValue, "", $this->Cargo->ReadOnly);

			// Fecha_registro
			$this->Fecha_registro->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
			$rsnew['Fecha_registro'] = &$this->Fecha_registro->DbValue;

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
					if (in_array("t_conyugue", $DetailTblVar) && $GLOBALS["t_conyugue"]->DetailEdit) {
						if (!isset($GLOBALS["t_conyugue_grid"])) $GLOBALS["t_conyugue_grid"] = new ct_conyugue_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_conyugue"); // Load user level of detail table
						$EditRow = $GLOBALS["t_conyugue_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_pa_consanguinidad", $DetailTblVar) && $GLOBALS["t_pa_consanguinidad"]->DetailEdit) {
						if (!isset($GLOBALS["t_pa_consanguinidad_grid"])) $GLOBALS["t_pa_consanguinidad_grid"] = new ct_pa_consanguinidad_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_pa_consanguinidad"); // Load user level of detail table
						$EditRow = $GLOBALS["t_pa_consanguinidad_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_pa_afinidad", $DetailTblVar) && $GLOBALS["t_pa_afinidad"]->DetailEdit) {
						if (!isset($GLOBALS["t_pa_afinidad_grid"])) $GLOBALS["t_pa_afinidad_grid"] = new ct_pa_afinidad_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_pa_afinidad"); // Load user level of detail table
						$EditRow = $GLOBALS["t_pa_afinidad_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_re_adopcion", $DetailTblVar) && $GLOBALS["t_re_adopcion"]->DetailEdit) {
						if (!isset($GLOBALS["t_re_adopcion_grid"])) $GLOBALS["t_re_adopcion_grid"] = new ct_re_adopcion_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_re_adopcion"); // Load user level of detail table
						$EditRow = $GLOBALS["t_re_adopcion_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_mp_si_no", $DetailTblVar) && $GLOBALS["t_mp_si_no"]->DetailEdit) {
						if (!isset($GLOBALS["t_mp_si_no_grid"])) $GLOBALS["t_mp_si_no_grid"] = new ct_mp_si_no_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_mp_si_no"); // Load user level of detail table
						$EditRow = $GLOBALS["t_mp_si_no_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_parientes_mp", $DetailTblVar) && $GLOBALS["t_parientes_mp"]->DetailEdit) {
						if (!isset($GLOBALS["t_parientes_mp_grid"])) $GLOBALS["t_parientes_mp_grid"] = new ct_parientes_mp_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_parientes_mp"); // Load user level of detail table
						$EditRow = $GLOBALS["t_parientes_mp_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_actiividades_remuneradas", $DetailTblVar) && $GLOBALS["t_actiividades_remuneradas"]->DetailEdit) {
						if (!isset($GLOBALS["t_actiividades_remuneradas_grid"])) $GLOBALS["t_actiividades_remuneradas_grid"] = new ct_actiividades_remuneradas_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_actiividades_remuneradas"); // Load user level of detail table
						$EditRow = $GLOBALS["t_actiividades_remuneradas_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}
				if ($EditRow) {
					if (in_array("t_salario", $DetailTblVar) && $GLOBALS["t_salario"]->DetailEdit) {
						if (!isset($GLOBALS["t_salario_grid"])) $GLOBALS["t_salario_grid"] = new ct_salario_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_salario"); // Load user level of detail table
						$EditRow = $GLOBALS["t_salario_grid"]->GridUpdate();
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
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
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
				if ($GLOBALS["t_conyugue_grid"]->DetailEdit) {
					$GLOBALS["t_conyugue_grid"]->CurrentMode = "edit";
					$GLOBALS["t_conyugue_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_pa_consanguinidad_grid"]->DetailEdit) {
					$GLOBALS["t_pa_consanguinidad_grid"]->CurrentMode = "edit";
					$GLOBALS["t_pa_consanguinidad_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_pa_afinidad_grid"]->DetailEdit) {
					$GLOBALS["t_pa_afinidad_grid"]->CurrentMode = "edit";
					$GLOBALS["t_pa_afinidad_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_re_adopcion_grid"]->DetailEdit) {
					$GLOBALS["t_re_adopcion_grid"]->CurrentMode = "edit";
					$GLOBALS["t_re_adopcion_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_mp_si_no_grid"]->DetailEdit) {
					$GLOBALS["t_mp_si_no_grid"]->CurrentMode = "edit";
					$GLOBALS["t_mp_si_no_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_parientes_mp_grid"]->DetailEdit) {
					$GLOBALS["t_parientes_mp_grid"]->CurrentMode = "edit";
					$GLOBALS["t_parientes_mp_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_actiividades_remuneradas_grid"]->DetailEdit) {
					$GLOBALS["t_actiividades_remuneradas_grid"]->CurrentMode = "edit";
					$GLOBALS["t_actiividades_remuneradas_grid"]->CurrentAction = "gridedit";

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
				if ($GLOBALS["t_salario_grid"]->DetailEdit) {
					$GLOBALS["t_salario_grid"]->CurrentMode = "edit";
					$GLOBALS["t_salario_grid"]->CurrentAction = "gridedit";

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
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
		case "x_Fiscalia_otro":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Fiscalia` AS `LinkFld`, `Fiscalia` AS `DispFld`, `Unidad_Organizacional` AS `Disp2Fld`, `Unidad` AS `Disp3Fld`, `Cargo` AS `Disp4Fld` FROM `seleccion_cargos`";
			$sWhereWrk = "{filter}";
			$this->Fiscalia_otro->LookupFilters = array("dx1" => '`Fiscalia`', "dx2" => '`Unidad_Organizacional`', "dx3" => '`Unidad`', "dx4" => '`Cargo`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Fiscalia` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Fiscalia_otro, $sWhereWrk); // Call Lookup selecting
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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_funcionario';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 't_funcionario';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['Id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
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
if (!isset($t_funcionario_edit)) $t_funcionario_edit = new ct_funcionario_edit();

// Page init
$t_funcionario_edit->Page_Init();

// Page main
$t_funcionario_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_funcionario_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_funcionarioedit = new ew_Form("ft_funcionarioedit", "edit");

// Validate form
ft_funcionarioedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_CI_RUN");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->CI_RUN->FldCaption(), $t_funcionario->CI_RUN->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CI_RUN");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->CI_RUN->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Expedido");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Expedido->FldCaption(), $t_funcionario->Expedido->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Paterno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Apellido_Paterno->FldCaption(), $t_funcionario->Apellido_Paterno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Apellido_Materno");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Apellido_Materno->FldCaption(), $t_funcionario->Apellido_Materno->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Nombres");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Nombres->FldCaption(), $t_funcionario->Nombres->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Fecha_Nacimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Fecha_Nacimiento->FldCaption(), $t_funcionario->Fecha_Nacimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Fecha_Nacimiento");
			if (elm && !ew_CheckShortUSDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Fecha_Nacimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Estado_Civil");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Estado_Civil->FldCaption(), $t_funcionario->Estado_Civil->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Direccion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Direccion->FldCaption(), $t_funcionario->Direccion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Telefono");
			if (elm && !ew_CheckRange(elm.value, 10000000, 80000000))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Telefono->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Celular");
			if (elm && !ew_CheckRange(elm.value, 10000000, 80000000))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Celular->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Fiscalia_otro");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Fiscalia_otro->FldCaption(), $t_funcionario->Fiscalia_otro->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Unidad_Organizacional");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Unidad_Organizacional->FldCaption(), $t_funcionario->Unidad_Organizacional->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Unidad");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Unidad->FldCaption(), $t_funcionario->Unidad->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Cargo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_funcionario->Cargo->FldCaption(), $t_funcionario->Cargo->ReqErrMsg)) ?>");

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
ft_funcionarioedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_funcionarioedit.ValidateRequired = true;
<?php } else { ?>
ft_funcionarioedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_funcionarioedit.Lists["x_Estado_Civil"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_funcionarioedit.Lists["x_Estado_Civil"].Options = <?php echo json_encode($t_funcionario->Estado_Civil->Options()) ?>;
ft_funcionarioedit.Lists["x_Fiscalia_otro"] = {"LinkField":"x_Fiscalia","Ajax":true,"AutoFill":true,"DisplayFields":["x_Fiscalia","x_Unidad_Organizacional","x_Unidad","x_Cargo"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"seleccion_cargos"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_funcionario_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_funcionario_edit->ShowPageHeader(); ?>
<?php
$t_funcionario_edit->ShowMessage();
?>
<form name="ft_funcionarioedit" id="ft_funcionarioedit" class="<?php echo $t_funcionario_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_funcionario_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_funcionario_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_funcionario">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_funcionario_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_funcionario->CI_RUN->Visible) { // CI_RUN ?>
	<div id="r_CI_RUN" class="form-group">
		<label id="elh_t_funcionario_CI_RUN" for="x_CI_RUN" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->CI_RUN->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->CI_RUN->CellAttributes() ?>>
<span id="el_t_funcionario_CI_RUN">
<input type="text" data-table="t_funcionario" data-field="x_CI_RUN" data-page="1" name="x_CI_RUN" id="x_CI_RUN" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_funcionario->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->CI_RUN->EditValue ?>"<?php echo $t_funcionario->CI_RUN->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->CI_RUN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Expedido->Visible) { // Expedido ?>
	<div id="r_Expedido" class="form-group">
		<label id="elh_t_funcionario_Expedido" for="x_Expedido" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Expedido->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Expedido->CellAttributes() ?>>
<span id="el_t_funcionario_Expedido">
<input type="text" data-table="t_funcionario" data-field="x_Expedido" data-page="1" name="x_Expedido" id="x_Expedido" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Expedido->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Expedido->EditValue ?>"<?php echo $t_funcionario->Expedido->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Expedido->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label id="elh_t_funcionario_Apellido_Paterno" for="x_Apellido_Paterno" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Apellido_Paterno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Apellido_Paterno->CellAttributes() ?>>
<span id="el_t_funcionario_Apellido_Paterno">
<input type="text" data-table="t_funcionario" data-field="x_Apellido_Paterno" data-page="1" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Apellido_Paterno->EditValue ?>"<?php echo $t_funcionario->Apellido_Paterno->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Apellido_Paterno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label id="elh_t_funcionario_Apellido_Materno" for="x_Apellido_Materno" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Apellido_Materno->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Apellido_Materno->CellAttributes() ?>>
<span id="el_t_funcionario_Apellido_Materno">
<input type="text" data-table="t_funcionario" data-field="x_Apellido_Materno" data-page="1" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Apellido_Materno->EditValue ?>"<?php echo $t_funcionario->Apellido_Materno->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Apellido_Materno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label id="elh_t_funcionario_Nombres" for="x_Nombres" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Nombres->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Nombres->CellAttributes() ?>>
<span id="el_t_funcionario_Nombres">
<input type="text" data-table="t_funcionario" data-field="x_Nombres" data-page="1" name="x_Nombres" id="x_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Nombres->EditValue ?>"<?php echo $t_funcionario->Nombres->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Nombres->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Fecha_Nacimiento->Visible) { // Fecha_Nacimiento ?>
	<div id="r_Fecha_Nacimiento" class="form-group">
		<label id="elh_t_funcionario_Fecha_Nacimiento" for="x_Fecha_Nacimiento" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Fecha_Nacimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Fecha_Nacimiento->CellAttributes() ?>>
<span id="el_t_funcionario_Fecha_Nacimiento">
<input type="text" data-table="t_funcionario" data-field="x_Fecha_Nacimiento" data-page="1" data-format="13" name="x_Fecha_Nacimiento" id="x_Fecha_Nacimiento" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Fecha_Nacimiento->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Fecha_Nacimiento->EditValue ?>"<?php echo $t_funcionario->Fecha_Nacimiento->EditAttributes() ?>>
<?php if (!$t_funcionario->Fecha_Nacimiento->ReadOnly && !$t_funcionario->Fecha_Nacimiento->Disabled && !isset($t_funcionario->Fecha_Nacimiento->EditAttrs["readonly"]) && !isset($t_funcionario->Fecha_Nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_funcionarioedit", "x_Fecha_Nacimiento", 13);
</script>
<?php } ?>
</span>
<?php echo $t_funcionario->Fecha_Nacimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Estado_Civil->Visible) { // Estado_Civil ?>
	<div id="r_Estado_Civil" class="form-group">
		<label id="elh_t_funcionario_Estado_Civil" for="x_Estado_Civil" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Estado_Civil->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Estado_Civil->CellAttributes() ?>>
<span id="el_t_funcionario_Estado_Civil">
<select data-table="t_funcionario" data-field="x_Estado_Civil" data-page="1" data-value-separator="<?php echo $t_funcionario->Estado_Civil->DisplayValueSeparatorAttribute() ?>" id="x_Estado_Civil" name="x_Estado_Civil"<?php echo $t_funcionario->Estado_Civil->EditAttributes() ?>>
<?php echo $t_funcionario->Estado_Civil->SelectOptionListHtml("x_Estado_Civil") ?>
</select>
</span>
<?php echo $t_funcionario->Estado_Civil->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Direccion->Visible) { // Direccion ?>
	<div id="r_Direccion" class="form-group">
		<label id="elh_t_funcionario_Direccion" for="x_Direccion" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Direccion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Direccion->CellAttributes() ?>>
<span id="el_t_funcionario_Direccion">
<input type="text" data-table="t_funcionario" data-field="x_Direccion" data-page="1" name="x_Direccion" id="x_Direccion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Direccion->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Direccion->EditValue ?>"<?php echo $t_funcionario->Direccion->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Telefono->Visible) { // Telefono ?>
	<div id="r_Telefono" class="form-group">
		<label id="elh_t_funcionario_Telefono" for="x_Telefono" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Telefono->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Telefono->CellAttributes() ?>>
<span id="el_t_funcionario_Telefono">
<input type="text" data-table="t_funcionario" data-field="x_Telefono" data-page="1" name="x_Telefono" id="x_Telefono" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Telefono->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Telefono->EditValue ?>"<?php echo $t_funcionario->Telefono->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Telefono->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Celular->Visible) { // Celular ?>
	<div id="r_Celular" class="form-group">
		<label id="elh_t_funcionario_Celular" for="x_Celular" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Celular->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Celular->CellAttributes() ?>>
<span id="el_t_funcionario_Celular">
<input type="text" data-table="t_funcionario" data-field="x_Celular" data-page="1" name="x_Celular" id="x_Celular" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Celular->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Celular->EditValue ?>"<?php echo $t_funcionario->Celular->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Celular->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<div id="r_Fiscalia_otro" class="form-group">
		<label id="elh_t_funcionario_Fiscalia_otro" for="x_Fiscalia_otro" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Fiscalia_otro->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Fiscalia_otro->CellAttributes() ?>>
<span id="el_t_funcionario_Fiscalia_otro">
<?php $t_funcionario->Fiscalia_otro->EditAttrs["onchange"] = "ew_AutoFill(this); " . @$t_funcionario->Fiscalia_otro->EditAttrs["onchange"]; ?>
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_Fiscalia_otro"><?php echo (strval($t_funcionario->Fiscalia_otro->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_funcionario->Fiscalia_otro->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_funcionario->Fiscalia_otro->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_Fiscalia_otro',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_funcionario" data-field="x_Fiscalia_otro" data-page="1" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_funcionario->Fiscalia_otro->DisplayValueSeparatorAttribute() ?>" name="x_Fiscalia_otro" id="x_Fiscalia_otro" value="<?php echo $t_funcionario->Fiscalia_otro->CurrentValue ?>"<?php echo $t_funcionario->Fiscalia_otro->EditAttributes() ?>>
<input type="hidden" name="s_x_Fiscalia_otro" id="s_x_Fiscalia_otro" value="<?php echo $t_funcionario->Fiscalia_otro->LookupFilterQuery() ?>">
<input type="hidden" name="ln_x_Fiscalia_otro" id="ln_x_Fiscalia_otro" value="x_Unidad_Organizacional,x_Unidad,x_Cargo">
</span>
<?php echo $t_funcionario->Fiscalia_otro->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label id="elh_t_funcionario_Unidad_Organizacional" for="x_Unidad_Organizacional" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Unidad_Organizacional->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Unidad_Organizacional->CellAttributes() ?>>
<span id="el_t_funcionario_Unidad_Organizacional">
<input type="text" data-table="t_funcionario" data-field="x_Unidad_Organizacional" data-page="1" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Unidad_Organizacional->EditValue ?>"<?php echo $t_funcionario->Unidad_Organizacional->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Unidad_Organizacional->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Unidad->Visible) { // Unidad ?>
	<div id="r_Unidad" class="form-group">
		<label id="elh_t_funcionario_Unidad" for="x_Unidad" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Unidad->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Unidad->CellAttributes() ?>>
<span id="el_t_funcionario_Unidad">
<input type="text" data-table="t_funcionario" data-field="x_Unidad" data-page="1" name="x_Unidad" id="x_Unidad" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Unidad->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Unidad->EditValue ?>"<?php echo $t_funcionario->Unidad->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Unidad->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Cargo->Visible) { // Cargo ?>
	<div id="r_Cargo" class="form-group">
		<label id="elh_t_funcionario_Cargo" for="x_Cargo" class="col-sm-2 control-label ewLabel"><?php echo $t_funcionario->Cargo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_funcionario->Cargo->CellAttributes() ?>>
<span id="el_t_funcionario_Cargo">
<input type="text" data-table="t_funcionario" data-field="x_Cargo" data-page="1" name="x_Cargo" id="x_Cargo" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Cargo->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Cargo->EditValue ?>"<?php echo $t_funcionario->Cargo->EditAttributes() ?>>
</span>
<?php echo $t_funcionario->Cargo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_funcionario" data-field="x_Id" name="x_Id" id="x_Id" value="<?php echo ew_HtmlEncode($t_funcionario->Id->CurrentValue) ?>">
<?php if ($t_funcionario->getCurrentDetailTable() <> "") { ?>
<?php
	$t_funcionario_edit->DetailPages->ValidKeys = explode(",", $t_funcionario->getCurrentDetailTable());
	$FirstActiveDetailTable = $t_funcionario_edit->DetailPages->ActivePageIndex();
?>
<div class="ewDetailPages">
<div class="panel-group" id="t_funcionario_edit_details">
<?php
	if (in_array("t_conyugue", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_conyugue->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_conyugue") {
			$FirstActiveDetailTable = "t_conyugue";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_conyugue") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_conyugue"><?php echo $Language->TablePhrase("t_conyugue", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_conyugue") ?>" id="tab_t_conyugue">
			<div class="panel-body">
<?php include_once "t_conyuguegrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_pa_consanguinidad", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_pa_consanguinidad->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_pa_consanguinidad") {
			$FirstActiveDetailTable = "t_pa_consanguinidad";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_pa_consanguinidad") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_pa_consanguinidad"><?php echo $Language->TablePhrase("t_pa_consanguinidad", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_pa_consanguinidad") ?>" id="tab_t_pa_consanguinidad">
			<div class="panel-body">
<?php include_once "t_pa_consanguinidadgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_pa_afinidad", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_pa_afinidad->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_pa_afinidad") {
			$FirstActiveDetailTable = "t_pa_afinidad";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_pa_afinidad") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_pa_afinidad"><?php echo $Language->TablePhrase("t_pa_afinidad", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_pa_afinidad") ?>" id="tab_t_pa_afinidad">
			<div class="panel-body">
<?php include_once "t_pa_afinidadgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_re_adopcion", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_re_adopcion->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_re_adopcion") {
			$FirstActiveDetailTable = "t_re_adopcion";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_re_adopcion") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_re_adopcion"><?php echo $Language->TablePhrase("t_re_adopcion", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_re_adopcion") ?>" id="tab_t_re_adopcion">
			<div class="panel-body">
<?php include_once "t_re_adopciongrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_mp_si_no", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_mp_si_no->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_mp_si_no") {
			$FirstActiveDetailTable = "t_mp_si_no";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_mp_si_no") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_mp_si_no"><?php echo $Language->TablePhrase("t_mp_si_no", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_mp_si_no") ?>" id="tab_t_mp_si_no">
			<div class="panel-body">
<?php include_once "t_mp_si_nogrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_parientes_mp", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_parientes_mp->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_parientes_mp") {
			$FirstActiveDetailTable = "t_parientes_mp";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_parientes_mp") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_parientes_mp"><?php echo $Language->TablePhrase("t_parientes_mp", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_parientes_mp") ?>" id="tab_t_parientes_mp">
			<div class="panel-body">
<?php include_once "t_parientes_mpgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_actiividades_remuneradas", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_actiividades_remuneradas->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_actiividades_remuneradas") {
			$FirstActiveDetailTable = "t_actiividades_remuneradas";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_actiividades_remuneradas") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_actiividades_remuneradas"><?php echo $Language->TablePhrase("t_actiividades_remuneradas", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_actiividades_remuneradas") ?>" id="tab_t_actiividades_remuneradas">
			<div class="panel-body">
<?php include_once "t_actiividades_remuneradasgrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
<?php
	if (in_array("t_salario", explode(",", $t_funcionario->getCurrentDetailTable())) && $t_salario->DetailEdit) {
		if ($FirstActiveDetailTable == "" || $FirstActiveDetailTable == "t_salario") {
			$FirstActiveDetailTable = "t_salario";
		}
?>
	<div class="panel panel-default<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_salario") ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a class="panel-toggle" data-toggle="collapse" data-parent="#t_funcionario_edit_details" href="#tab_t_salario"><?php echo $Language->TablePhrase("t_salario", "TblCaption") ?></a>
			</h4>
		</div>
		<div class="panel-collapse collapse<?php echo $t_funcionario_edit->DetailPages->PageStyle("t_salario") ?>" id="tab_t_salario">
			<div class="panel-body">
<?php include_once "t_salariogrid.php" ?>
			</div>
		</div>
	</div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if (!$t_funcionario_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_funcionario_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_funcionarioedit.Init();
</script>
<?php
$t_funcionario_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_funcionario_edit->Page_Terminate();
?>
