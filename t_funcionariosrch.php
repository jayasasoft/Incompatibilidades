<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_funcionario_search = NULL; // Initialize page object first

class ct_funcionario_search extends ct_funcionario {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_funcionario';

	// Page object name
	var $PageObjName = 't_funcionario_search';

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

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "t_funcionariolist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->CI_RUN); // CI_RUN
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Paterno); // Apellido_Paterno
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Materno); // Apellido_Materno
		$this->BuildSearchUrl($sSrchUrl, $this->Nombres); // Nombres
		$this->BuildSearchUrl($sSrchUrl, $this->Fecha_Nacimiento); // Fecha_Nacimiento
		$this->BuildSearchUrl($sSrchUrl, $this->Estado_Civil); // Estado_Civil
		$this->BuildSearchUrl($sSrchUrl, $this->Direccion); // Direccion
		$this->BuildSearchUrl($sSrchUrl, $this->Telefono); // Telefono
		$this->BuildSearchUrl($sSrchUrl, $this->Celular); // Celular
		$this->BuildSearchUrl($sSrchUrl, $this->Fiscalia_otro); // Fiscalia_otro
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad_Organizacional); // Unidad_Organizacional
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad); // Unidad
		$this->BuildSearchUrl($sSrchUrl, $this->Cargo); // Cargo
		$this->BuildSearchUrl($sSrchUrl, $this->Fecha_registro); // Fecha_registro
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// CI_RUN

		$this->CI_RUN->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_CI_RUN"));
		$this->CI_RUN->AdvancedSearch->SearchOperator = $objForm->GetValue("z_CI_RUN");

		// Apellido_Paterno
		$this->Apellido_Paterno->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Paterno"));
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Paterno");

		// Apellido_Materno
		$this->Apellido_Materno->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Materno"));
		$this->Apellido_Materno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Materno");

		// Nombres
		$this->Nombres->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nombres"));
		$this->Nombres->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nombres");

		// Fecha_Nacimiento
		$this->Fecha_Nacimiento->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Fecha_Nacimiento"));
		$this->Fecha_Nacimiento->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Fecha_Nacimiento");

		// Estado_Civil
		$this->Estado_Civil->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Estado_Civil"));
		$this->Estado_Civil->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Estado_Civil");

		// Direccion
		$this->Direccion->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Direccion"));
		$this->Direccion->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Direccion");

		// Telefono
		$this->Telefono->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Telefono"));
		$this->Telefono->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Telefono");

		// Celular
		$this->Celular->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Celular"));
		$this->Celular->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Celular");

		// Fiscalia_otro
		$this->Fiscalia_otro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Fiscalia_otro"));
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Fiscalia_otro");

		// Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Unidad_Organizacional"));
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Unidad_Organizacional");

		// Unidad
		$this->Unidad->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Unidad"));
		$this->Unidad->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Unidad");

		// Cargo
		$this->Cargo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Cargo"));
		$this->Cargo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Cargo");

		// Fecha_registro
		$this->Fecha_registro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Fecha_registro"));
		$this->Fecha_registro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Fecha_registro");
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// CI_RUN
			$this->CI_RUN->EditAttrs["class"] = "form-control";
			$this->CI_RUN->EditCustomAttributes = "";
			$this->CI_RUN->EditValue = ew_HtmlEncode($this->CI_RUN->AdvancedSearch->SearchValue);
			$this->CI_RUN->PlaceHolder = ew_RemoveHtml($this->CI_RUN->FldCaption());

			// Apellido_Paterno
			$this->Apellido_Paterno->EditAttrs["class"] = "form-control";
			$this->Apellido_Paterno->EditCustomAttributes = "";
			$this->Apellido_Paterno->EditValue = ew_HtmlEncode($this->Apellido_Paterno->AdvancedSearch->SearchValue);
			$this->Apellido_Paterno->PlaceHolder = ew_RemoveHtml($this->Apellido_Paterno->FldCaption());

			// Apellido_Materno
			$this->Apellido_Materno->EditAttrs["class"] = "form-control";
			$this->Apellido_Materno->EditCustomAttributes = "";
			$this->Apellido_Materno->EditValue = ew_HtmlEncode($this->Apellido_Materno->AdvancedSearch->SearchValue);
			$this->Apellido_Materno->PlaceHolder = ew_RemoveHtml($this->Apellido_Materno->FldCaption());

			// Nombres
			$this->Nombres->EditAttrs["class"] = "form-control";
			$this->Nombres->EditCustomAttributes = "";
			$this->Nombres->EditValue = ew_HtmlEncode($this->Nombres->AdvancedSearch->SearchValue);
			$this->Nombres->PlaceHolder = ew_RemoveHtml($this->Nombres->FldCaption());

			// Fecha_Nacimiento
			$this->Fecha_Nacimiento->EditAttrs["class"] = "form-control";
			$this->Fecha_Nacimiento->EditCustomAttributes = "";
			$this->Fecha_Nacimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Fecha_Nacimiento->AdvancedSearch->SearchValue, 13), 13));
			$this->Fecha_Nacimiento->PlaceHolder = ew_RemoveHtml($this->Fecha_Nacimiento->FldCaption());

			// Estado_Civil
			$this->Estado_Civil->EditAttrs["class"] = "form-control";
			$this->Estado_Civil->EditCustomAttributes = "";
			$this->Estado_Civil->EditValue = $this->Estado_Civil->Options(TRUE);

			// Direccion
			$this->Direccion->EditAttrs["class"] = "form-control";
			$this->Direccion->EditCustomAttributes = "";
			$this->Direccion->EditValue = ew_HtmlEncode($this->Direccion->AdvancedSearch->SearchValue);
			$this->Direccion->PlaceHolder = ew_RemoveHtml($this->Direccion->FldCaption());

			// Telefono
			$this->Telefono->EditAttrs["class"] = "form-control";
			$this->Telefono->EditCustomAttributes = "";
			$this->Telefono->EditValue = ew_HtmlEncode($this->Telefono->AdvancedSearch->SearchValue);
			$this->Telefono->PlaceHolder = ew_RemoveHtml($this->Telefono->FldCaption());

			// Celular
			$this->Celular->EditAttrs["class"] = "form-control";
			$this->Celular->EditCustomAttributes = "";
			$this->Celular->EditValue = ew_HtmlEncode($this->Celular->AdvancedSearch->SearchValue);
			$this->Celular->PlaceHolder = ew_RemoveHtml($this->Celular->FldCaption());

			// Fiscalia_otro
			$this->Fiscalia_otro->EditAttrs["class"] = "form-control";
			$this->Fiscalia_otro->EditCustomAttributes = "";
			$this->Fiscalia_otro->EditValue = ew_HtmlEncode($this->Fiscalia_otro->AdvancedSearch->SearchValue);
			$this->Fiscalia_otro->PlaceHolder = ew_RemoveHtml($this->Fiscalia_otro->FldCaption());

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			$this->Unidad_Organizacional->EditValue = ew_HtmlEncode($this->Unidad_Organizacional->AdvancedSearch->SearchValue);
			$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

			// Unidad
			$this->Unidad->EditAttrs["class"] = "form-control";
			$this->Unidad->EditCustomAttributes = "";
			$this->Unidad->EditValue = ew_HtmlEncode($this->Unidad->AdvancedSearch->SearchValue);
			$this->Unidad->PlaceHolder = ew_RemoveHtml($this->Unidad->FldCaption());

			// Cargo
			$this->Cargo->EditAttrs["class"] = "form-control";
			$this->Cargo->EditCustomAttributes = "";
			$this->Cargo->EditValue = ew_HtmlEncode($this->Cargo->AdvancedSearch->SearchValue);
			$this->Cargo->PlaceHolder = ew_RemoveHtml($this->Cargo->FldCaption());

			// Fecha_registro
			$this->Fecha_registro->EditAttrs["class"] = "form-control";
			$this->Fecha_registro->EditCustomAttributes = "";
			$this->Fecha_registro->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Fecha_registro->AdvancedSearch->SearchValue, 0), 8));
			$this->Fecha_registro->PlaceHolder = ew_RemoveHtml($this->Fecha_registro->FldCaption());
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
		if (!ew_CheckInteger($this->CI_RUN->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->CI_RUN->FldErrMsg());
		}
		if (!ew_CheckShortUSDate($this->Fecha_Nacimiento->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Fecha_Nacimiento->FldErrMsg());
		}
		if (!ew_CheckRange($this->Telefono->AdvancedSearch->SearchValue, 10000000, 80000000)) {
			ew_AddMessage($gsSearchError, $this->Telefono->FldErrMsg());
		}
		if (!ew_CheckRange($this->Celular->AdvancedSearch->SearchValue, 10000000, 80000000)) {
			ew_AddMessage($gsSearchError, $this->Celular->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->Fecha_registro->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Fecha_registro->FldErrMsg());
		}

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_funcionariolist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($t_funcionario_search)) $t_funcionario_search = new ct_funcionario_search();

// Page init
$t_funcionario_search->Page_Init();

// Page main
$t_funcionario_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_funcionario_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($t_funcionario_search->IsModal) { ?>
var CurrentAdvancedSearchForm = ft_funcionariosearch = new ew_Form("ft_funcionariosearch", "search");
<?php } else { ?>
var CurrentForm = ft_funcionariosearch = new ew_Form("ft_funcionariosearch", "search");
<?php } ?>

// Form_CustomValidate event
ft_funcionariosearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_funcionariosearch.ValidateRequired = true;
<?php } else { ?>
ft_funcionariosearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_funcionariosearch.Lists["x_Estado_Civil"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_funcionariosearch.Lists["x_Estado_Civil"].Options = <?php echo json_encode($t_funcionario->Estado_Civil->Options()) ?>;
ft_funcionariosearch.Lists["x_Fiscalia_otro"] = {"LinkField":"x_Fiscalia","Ajax":true,"AutoFill":false,"DisplayFields":["x_Fiscalia","x_Unidad_Organizacional","x_Unidad","x_Cargo"],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"seleccion_cargos"};

// Form object for search
// Validate function for search

ft_funcionariosearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_CI_RUN");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->CI_RUN->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Fecha_Nacimiento");
	if (elm && !ew_CheckShortUSDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Fecha_Nacimiento->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Telefono");
	if (elm && !ew_CheckRange(elm.value, 10000000, 80000000))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Telefono->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Celular");
	if (elm && !ew_CheckRange(elm.value, 10000000, 80000000))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Celular->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Fecha_registro");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_funcionario->Fecha_registro->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_funcionario_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_funcionario_search->ShowPageHeader(); ?>
<?php
$t_funcionario_search->ShowMessage();
?>
<form name="ft_funcionariosearch" id="ft_funcionariosearch" class="<?php echo $t_funcionario_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_funcionario_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_funcionario_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_funcionario">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($t_funcionario_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_funcionario->CI_RUN->Visible) { // CI_RUN ?>
	<div id="r_CI_RUN" class="form-group">
		<label for="x_CI_RUN" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_CI_RUN"><?php echo $t_funcionario->CI_RUN->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CI_RUN" id="z_CI_RUN" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->CI_RUN->CellAttributes() ?>>
			<span id="el_t_funcionario_CI_RUN">
<input type="text" data-table="t_funcionario" data-field="x_CI_RUN" data-page="1" name="x_CI_RUN" id="x_CI_RUN" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_funcionario->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->CI_RUN->EditValue ?>"<?php echo $t_funcionario->CI_RUN->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label for="x_Apellido_Paterno" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Apellido_Paterno"><?php echo $t_funcionario->Apellido_Paterno->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Paterno" id="z_Apellido_Paterno" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Apellido_Paterno->CellAttributes() ?>>
			<span id="el_t_funcionario_Apellido_Paterno">
<input type="text" data-table="t_funcionario" data-field="x_Apellido_Paterno" data-page="1" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Apellido_Paterno->EditValue ?>"<?php echo $t_funcionario->Apellido_Paterno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label for="x_Apellido_Materno" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Apellido_Materno"><?php echo $t_funcionario->Apellido_Materno->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Materno" id="z_Apellido_Materno" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Apellido_Materno->CellAttributes() ?>>
			<span id="el_t_funcionario_Apellido_Materno">
<input type="text" data-table="t_funcionario" data-field="x_Apellido_Materno" data-page="1" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Apellido_Materno->EditValue ?>"<?php echo $t_funcionario->Apellido_Materno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label for="x_Nombres" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Nombres"><?php echo $t_funcionario->Nombres->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nombres" id="z_Nombres" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Nombres->CellAttributes() ?>>
			<span id="el_t_funcionario_Nombres">
<input type="text" data-table="t_funcionario" data-field="x_Nombres" data-page="1" name="x_Nombres" id="x_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Nombres->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Nombres->EditValue ?>"<?php echo $t_funcionario->Nombres->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Fecha_Nacimiento->Visible) { // Fecha_Nacimiento ?>
	<div id="r_Fecha_Nacimiento" class="form-group">
		<label for="x_Fecha_Nacimiento" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Fecha_Nacimiento"><?php echo $t_funcionario->Fecha_Nacimiento->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Fecha_Nacimiento" id="z_Fecha_Nacimiento" value="="></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Fecha_Nacimiento->CellAttributes() ?>>
			<span id="el_t_funcionario_Fecha_Nacimiento">
<input type="text" data-table="t_funcionario" data-field="x_Fecha_Nacimiento" data-page="1" data-format="13" name="x_Fecha_Nacimiento" id="x_Fecha_Nacimiento" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Fecha_Nacimiento->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Fecha_Nacimiento->EditValue ?>"<?php echo $t_funcionario->Fecha_Nacimiento->EditAttributes() ?>>
<?php if (!$t_funcionario->Fecha_Nacimiento->ReadOnly && !$t_funcionario->Fecha_Nacimiento->Disabled && !isset($t_funcionario->Fecha_Nacimiento->EditAttrs["readonly"]) && !isset($t_funcionario->Fecha_Nacimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_funcionariosearch", "x_Fecha_Nacimiento", 13);
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Estado_Civil->Visible) { // Estado_Civil ?>
	<div id="r_Estado_Civil" class="form-group">
		<label for="x_Estado_Civil" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Estado_Civil"><?php echo $t_funcionario->Estado_Civil->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Estado_Civil" id="z_Estado_Civil" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Estado_Civil->CellAttributes() ?>>
			<span id="el_t_funcionario_Estado_Civil">
<select data-table="t_funcionario" data-field="x_Estado_Civil" data-page="1" data-value-separator="<?php echo $t_funcionario->Estado_Civil->DisplayValueSeparatorAttribute() ?>" id="x_Estado_Civil" name="x_Estado_Civil"<?php echo $t_funcionario->Estado_Civil->EditAttributes() ?>>
<?php echo $t_funcionario->Estado_Civil->SelectOptionListHtml("x_Estado_Civil") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Direccion->Visible) { // Direccion ?>
	<div id="r_Direccion" class="form-group">
		<label for="x_Direccion" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Direccion"><?php echo $t_funcionario->Direccion->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Direccion" id="z_Direccion" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Direccion->CellAttributes() ?>>
			<span id="el_t_funcionario_Direccion">
<input type="text" data-table="t_funcionario" data-field="x_Direccion" data-page="1" name="x_Direccion" id="x_Direccion" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Direccion->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Direccion->EditValue ?>"<?php echo $t_funcionario->Direccion->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Telefono->Visible) { // Telefono ?>
	<div id="r_Telefono" class="form-group">
		<label for="x_Telefono" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Telefono"><?php echo $t_funcionario->Telefono->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Telefono" id="z_Telefono" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Telefono->CellAttributes() ?>>
			<span id="el_t_funcionario_Telefono">
<input type="text" data-table="t_funcionario" data-field="x_Telefono" data-page="1" name="x_Telefono" id="x_Telefono" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Telefono->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Telefono->EditValue ?>"<?php echo $t_funcionario->Telefono->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Celular->Visible) { // Celular ?>
	<div id="r_Celular" class="form-group">
		<label for="x_Celular" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Celular"><?php echo $t_funcionario->Celular->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Celular" id="z_Celular" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Celular->CellAttributes() ?>>
			<span id="el_t_funcionario_Celular">
<input type="text" data-table="t_funcionario" data-field="x_Celular" data-page="1" name="x_Celular" id="x_Celular" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Celular->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Celular->EditValue ?>"<?php echo $t_funcionario->Celular->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<div id="r_Fiscalia_otro" class="form-group">
		<label for="x_Fiscalia_otro" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Fiscalia_otro"><?php echo $t_funcionario->Fiscalia_otro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Fiscalia_otro" id="z_Fiscalia_otro" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Fiscalia_otro->CellAttributes() ?>>
			<span id="el_t_funcionario_Fiscalia_otro">
<input type="text" data-table="t_funcionario" data-field="x_Fiscalia_otro" data-page="1" name="x_Fiscalia_otro" id="x_Fiscalia_otro" size="30" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Fiscalia_otro->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Fiscalia_otro->EditValue ?>"<?php echo $t_funcionario->Fiscalia_otro->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label for="x_Unidad_Organizacional" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Unidad_Organizacional"><?php echo $t_funcionario->Unidad_Organizacional->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad_Organizacional" id="z_Unidad_Organizacional" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Unidad_Organizacional->CellAttributes() ?>>
			<span id="el_t_funcionario_Unidad_Organizacional">
<input type="text" data-table="t_funcionario" data-field="x_Unidad_Organizacional" data-page="1" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Unidad_Organizacional->EditValue ?>"<?php echo $t_funcionario->Unidad_Organizacional->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Unidad->Visible) { // Unidad ?>
	<div id="r_Unidad" class="form-group">
		<label for="x_Unidad" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Unidad"><?php echo $t_funcionario->Unidad->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad" id="z_Unidad" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Unidad->CellAttributes() ?>>
			<span id="el_t_funcionario_Unidad">
<input type="text" data-table="t_funcionario" data-field="x_Unidad" data-page="1" name="x_Unidad" id="x_Unidad" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Unidad->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Unidad->EditValue ?>"<?php echo $t_funcionario->Unidad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Cargo->Visible) { // Cargo ?>
	<div id="r_Cargo" class="form-group">
		<label for="x_Cargo" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Cargo"><?php echo $t_funcionario->Cargo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Cargo" id="z_Cargo" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Cargo->CellAttributes() ?>>
			<span id="el_t_funcionario_Cargo">
<input type="text" data-table="t_funcionario" data-field="x_Cargo" data-page="1" name="x_Cargo" id="x_Cargo" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Cargo->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Cargo->EditValue ?>"<?php echo $t_funcionario->Cargo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_funcionario->Fecha_registro->Visible) { // Fecha_registro ?>
	<div id="r_Fecha_registro" class="form-group">
		<label for="x_Fecha_registro" class="<?php echo $t_funcionario_search->SearchLabelClass ?>"><span id="elh_t_funcionario_Fecha_registro"><?php echo $t_funcionario->Fecha_registro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Fecha_registro" id="z_Fecha_registro" value="="></p>
		</label>
		<div class="<?php echo $t_funcionario_search->SearchRightColumnClass ?>"><div<?php echo $t_funcionario->Fecha_registro->CellAttributes() ?>>
			<span id="el_t_funcionario_Fecha_registro">
<input type="text" data-table="t_funcionario" data-field="x_Fecha_registro" data-page="1" name="x_Fecha_registro" id="x_Fecha_registro" placeholder="<?php echo ew_HtmlEncode($t_funcionario->Fecha_registro->getPlaceHolder()) ?>" value="<?php echo $t_funcionario->Fecha_registro->EditValue ?>"<?php echo $t_funcionario->Fecha_registro->EditAttributes() ?>>
<?php if (!$t_funcionario->Fecha_registro->ReadOnly && !$t_funcionario->Fecha_registro->Disabled && !isset($t_funcionario->Fecha_registro->EditAttrs["readonly"]) && !isset($t_funcionario->Fecha_registro->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_funcionariosearch", "x_Fecha_registro", 0);
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_funcionario_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_funcionariosearch.Init();
</script>
<?php
$t_funcionario_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_funcionario_search->Page_Terminate();
?>
