<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "parientesinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$parientes_search = NULL; // Initialize page object first

class cparientes_search extends cparientes {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'parientes';

	// Page object name
	var $PageObjName = 'parientes_search';

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

		// Table object (parientes)
		if (!isset($GLOBALS["parientes"]) || get_class($GLOBALS["parientes"]) == "cparientes") {
			$GLOBALS["parientes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parientes"];
		}

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parientes', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("parienteslist.php"));
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
		$this->Nombres1->SetVisibility();
		$this->Apellido_Paterno1->SetVisibility();
		$this->Apellido_Materno1->SetVisibility();
		$this->Grado_Parentesco->SetVisibility();
		$this->Parentesco->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Fiscalia_otro->SetVisibility();
		$this->Unidad_Organizacional1->SetVisibility();
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
		global $EW_EXPORT, $parientes;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($parientes);
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
						$sSrchStr = "parienteslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->Expedido); // Expedido
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Paterno); // Apellido_Paterno
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Materno); // Apellido_Materno
		$this->BuildSearchUrl($sSrchUrl, $this->Nombres); // Nombres
		$this->BuildSearchUrl($sSrchUrl, $this->Nombres1); // Nombres1
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Paterno1); // Apellido_Paterno1
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Materno1); // Apellido_Materno1
		$this->BuildSearchUrl($sSrchUrl, $this->Grado_Parentesco); // Grado_Parentesco
		$this->BuildSearchUrl($sSrchUrl, $this->Parentesco); // Parentesco
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad_Organizacional); // Unidad_Organizacional
		$this->BuildSearchUrl($sSrchUrl, $this->Fiscalia_otro); // Fiscalia_otro
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad_Organizacional1); // Unidad_Organizacional1
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad); // Unidad
		$this->BuildSearchUrl($sSrchUrl, $this->Cargo); // Cargo
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

		// Expedido
		$this->Expedido->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Expedido"));
		$this->Expedido->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Expedido");

		// Apellido_Paterno
		$this->Apellido_Paterno->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Paterno"));
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Paterno");

		// Apellido_Materno
		$this->Apellido_Materno->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Materno"));
		$this->Apellido_Materno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Materno");

		// Nombres
		$this->Nombres->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nombres"));
		$this->Nombres->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nombres");

		// Nombres1
		$this->Nombres1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nombres1"));
		$this->Nombres1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nombres1");

		// Apellido_Paterno1
		$this->Apellido_Paterno1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Paterno1"));
		$this->Apellido_Paterno1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Paterno1");

		// Apellido_Materno1
		$this->Apellido_Materno1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Materno1"));
		$this->Apellido_Materno1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Materno1");

		// Grado_Parentesco
		$this->Grado_Parentesco->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Grado_Parentesco"));
		$this->Grado_Parentesco->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Grado_Parentesco");

		// Parentesco
		$this->Parentesco->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Parentesco"));
		$this->Parentesco->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Parentesco");

		// Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Unidad_Organizacional"));
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Unidad_Organizacional");

		// Fiscalia_otro
		$this->Fiscalia_otro->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Fiscalia_otro"));
		$this->Fiscalia_otro->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Fiscalia_otro");

		// Unidad_Organizacional1
		$this->Unidad_Organizacional1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Unidad_Organizacional1"));
		$this->Unidad_Organizacional1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Unidad_Organizacional1");

		// Unidad
		$this->Unidad->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Unidad"));
		$this->Unidad->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Unidad");

		// Cargo
		$this->Cargo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Cargo"));
		$this->Cargo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Cargo");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// CI_RUN
		// Expedido
		// Apellido_Paterno
		// Apellido_Materno
		// Nombres
		// Nombres1
		// Apellido_Paterno1
		// Apellido_Materno1
		// Grado_Parentesco
		// Parentesco
		// Unidad_Organizacional
		// Fiscalia_otro
		// Unidad_Organizacional1
		// Unidad
		// Cargo

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

		// Nombres1
		$this->Nombres1->ViewValue = $this->Nombres1->CurrentValue;
		$this->Nombres1->ViewCustomAttributes = "";

		// Apellido_Paterno1
		$this->Apellido_Paterno1->ViewValue = $this->Apellido_Paterno1->CurrentValue;
		$this->Apellido_Paterno1->ViewCustomAttributes = "";

		// Apellido_Materno1
		$this->Apellido_Materno1->ViewValue = $this->Apellido_Materno1->CurrentValue;
		$this->Apellido_Materno1->ViewCustomAttributes = "";

		// Grado_Parentesco
		if (strval($this->Grado_Parentesco->CurrentValue) <> "") {
			$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->OptionCaption($this->Grado_Parentesco->CurrentValue);
		} else {
			$this->Grado_Parentesco->ViewValue = NULL;
		}
		$this->Grado_Parentesco->ViewCustomAttributes = "";

		// Parentesco
		$this->Parentesco->ViewValue = $this->Parentesco->CurrentValue;
		$this->Parentesco->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Fiscalia_otro
		$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->ViewCustomAttributes = "";

		// Unidad_Organizacional1
		$this->Unidad_Organizacional1->ViewValue = $this->Unidad_Organizacional1->CurrentValue;
		$this->Unidad_Organizacional1->ViewCustomAttributes = "";

		// Unidad
		$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
		$this->Unidad->ViewCustomAttributes = "";

		// Cargo
		$this->Cargo->ViewValue = $this->Cargo->CurrentValue;
		$this->Cargo->ViewCustomAttributes = "";

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

			// Nombres1
			$this->Nombres1->LinkCustomAttributes = "";
			$this->Nombres1->HrefValue = "";
			$this->Nombres1->TooltipValue = "";

			// Apellido_Paterno1
			$this->Apellido_Paterno1->LinkCustomAttributes = "";
			$this->Apellido_Paterno1->HrefValue = "";
			$this->Apellido_Paterno1->TooltipValue = "";

			// Apellido_Materno1
			$this->Apellido_Materno1->LinkCustomAttributes = "";
			$this->Apellido_Materno1->HrefValue = "";
			$this->Apellido_Materno1->TooltipValue = "";

			// Grado_Parentesco
			$this->Grado_Parentesco->LinkCustomAttributes = "";
			$this->Grado_Parentesco->HrefValue = "";
			$this->Grado_Parentesco->TooltipValue = "";

			// Parentesco
			$this->Parentesco->LinkCustomAttributes = "";
			$this->Parentesco->HrefValue = "";
			$this->Parentesco->TooltipValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";

			// Fiscalia_otro
			$this->Fiscalia_otro->LinkCustomAttributes = "";
			$this->Fiscalia_otro->HrefValue = "";
			$this->Fiscalia_otro->TooltipValue = "";

			// Unidad_Organizacional1
			$this->Unidad_Organizacional1->LinkCustomAttributes = "";
			$this->Unidad_Organizacional1->HrefValue = "";
			$this->Unidad_Organizacional1->TooltipValue = "";

			// Unidad
			$this->Unidad->LinkCustomAttributes = "";
			$this->Unidad->HrefValue = "";
			$this->Unidad->TooltipValue = "";

			// Cargo
			$this->Cargo->LinkCustomAttributes = "";
			$this->Cargo->HrefValue = "";
			$this->Cargo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// CI_RUN
			$this->CI_RUN->EditAttrs["class"] = "form-control";
			$this->CI_RUN->EditCustomAttributes = "";
			$this->CI_RUN->EditValue = ew_HtmlEncode($this->CI_RUN->AdvancedSearch->SearchValue);
			$this->CI_RUN->PlaceHolder = ew_RemoveHtml($this->CI_RUN->FldCaption());

			// Expedido
			$this->Expedido->EditAttrs["class"] = "form-control";
			$this->Expedido->EditCustomAttributes = "";
			$this->Expedido->EditValue = ew_HtmlEncode($this->Expedido->AdvancedSearch->SearchValue);
			$this->Expedido->PlaceHolder = ew_RemoveHtml($this->Expedido->FldCaption());

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

			// Nombres1
			$this->Nombres1->EditAttrs["class"] = "form-control";
			$this->Nombres1->EditCustomAttributes = "";
			$this->Nombres1->EditValue = ew_HtmlEncode($this->Nombres1->AdvancedSearch->SearchValue);
			$this->Nombres1->PlaceHolder = ew_RemoveHtml($this->Nombres1->FldCaption());

			// Apellido_Paterno1
			$this->Apellido_Paterno1->EditAttrs["class"] = "form-control";
			$this->Apellido_Paterno1->EditCustomAttributes = "";
			$this->Apellido_Paterno1->EditValue = ew_HtmlEncode($this->Apellido_Paterno1->AdvancedSearch->SearchValue);
			$this->Apellido_Paterno1->PlaceHolder = ew_RemoveHtml($this->Apellido_Paterno1->FldCaption());

			// Apellido_Materno1
			$this->Apellido_Materno1->EditAttrs["class"] = "form-control";
			$this->Apellido_Materno1->EditCustomAttributes = "";
			$this->Apellido_Materno1->EditValue = ew_HtmlEncode($this->Apellido_Materno1->AdvancedSearch->SearchValue);
			$this->Apellido_Materno1->PlaceHolder = ew_RemoveHtml($this->Apellido_Materno1->FldCaption());

			// Grado_Parentesco
			$this->Grado_Parentesco->EditAttrs["class"] = "form-control";
			$this->Grado_Parentesco->EditCustomAttributes = "";
			$this->Grado_Parentesco->EditValue = $this->Grado_Parentesco->Options(TRUE);

			// Parentesco
			$this->Parentesco->EditAttrs["class"] = "form-control";
			$this->Parentesco->EditCustomAttributes = "";
			$this->Parentesco->EditValue = ew_HtmlEncode($this->Parentesco->AdvancedSearch->SearchValue);
			$this->Parentesco->PlaceHolder = ew_RemoveHtml($this->Parentesco->FldCaption());

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			$this->Unidad_Organizacional->EditValue = ew_HtmlEncode($this->Unidad_Organizacional->AdvancedSearch->SearchValue);
			$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

			// Fiscalia_otro
			$this->Fiscalia_otro->EditAttrs["class"] = "form-control";
			$this->Fiscalia_otro->EditCustomAttributes = "";
			$this->Fiscalia_otro->EditValue = ew_HtmlEncode($this->Fiscalia_otro->AdvancedSearch->SearchValue);
			$this->Fiscalia_otro->PlaceHolder = ew_RemoveHtml($this->Fiscalia_otro->FldCaption());

			// Unidad_Organizacional1
			$this->Unidad_Organizacional1->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional1->EditCustomAttributes = "";
			$this->Unidad_Organizacional1->EditValue = ew_HtmlEncode($this->Unidad_Organizacional1->AdvancedSearch->SearchValue);
			$this->Unidad_Organizacional1->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional1->FldCaption());

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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->CI_RUN->AdvancedSearch->Load();
		$this->Expedido->AdvancedSearch->Load();
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Nombres->AdvancedSearch->Load();
		$this->Nombres1->AdvancedSearch->Load();
		$this->Apellido_Paterno1->AdvancedSearch->Load();
		$this->Apellido_Materno1->AdvancedSearch->Load();
		$this->Grado_Parentesco->AdvancedSearch->Load();
		$this->Parentesco->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Fiscalia_otro->AdvancedSearch->Load();
		$this->Unidad_Organizacional1->AdvancedSearch->Load();
		$this->Unidad->AdvancedSearch->Load();
		$this->Cargo->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("parienteslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($parientes_search)) $parientes_search = new cparientes_search();

// Page init
$parientes_search->Page_Init();

// Page main
$parientes_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parientes_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($parientes_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fparientessearch = new ew_Form("fparientessearch", "search");
<?php } else { ?>
var CurrentForm = fparientessearch = new ew_Form("fparientessearch", "search");
<?php } ?>

// Form_CustomValidate event
fparientessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparientessearch.ValidateRequired = true;
<?php } else { ?>
fparientessearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fparientessearch.Lists["x_Grado_Parentesco"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fparientessearch.Lists["x_Grado_Parentesco"].Options = <?php echo json_encode($parientes->Grado_Parentesco->Options()) ?>;

// Form object for search
// Validate function for search

fparientessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$parientes_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $parientes_search->ShowPageHeader(); ?>
<?php
$parientes_search->ShowMessage();
?>
<form name="fparientessearch" id="fparientessearch" class="<?php echo $parientes_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parientes_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parientes_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parientes">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($parientes_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($parientes->CI_RUN->Visible) { // CI_RUN ?>
	<div id="r_CI_RUN" class="form-group">
		<label for="x_CI_RUN" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_CI_RUN"><?php echo $parientes->CI_RUN->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CI_RUN" id="z_CI_RUN" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->CI_RUN->CellAttributes() ?>>
			<span id="el_parientes_CI_RUN">
<input type="text" data-table="parientes" data-field="x_CI_RUN" name="x_CI_RUN" id="x_CI_RUN" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($parientes->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $parientes->CI_RUN->EditValue ?>"<?php echo $parientes->CI_RUN->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Expedido->Visible) { // Expedido ?>
	<div id="r_Expedido" class="form-group">
		<label for="x_Expedido" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Expedido"><?php echo $parientes->Expedido->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Expedido" id="z_Expedido" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Expedido->CellAttributes() ?>>
			<span id="el_parientes_Expedido">
<input type="text" data-table="parientes" data-field="x_Expedido" name="x_Expedido" id="x_Expedido" size="30" maxlength="4" placeholder="<?php echo ew_HtmlEncode($parientes->Expedido->getPlaceHolder()) ?>" value="<?php echo $parientes->Expedido->EditValue ?>"<?php echo $parientes->Expedido->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label for="x_Apellido_Paterno" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Apellido_Paterno"><?php echo $parientes->Apellido_Paterno->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Paterno" id="z_Apellido_Paterno" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Apellido_Paterno->CellAttributes() ?>>
			<span id="el_parientes_Apellido_Paterno">
<input type="text" data-table="parientes" data-field="x_Apellido_Paterno" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($parientes->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $parientes->Apellido_Paterno->EditValue ?>"<?php echo $parientes->Apellido_Paterno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label for="x_Apellido_Materno" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Apellido_Materno"><?php echo $parientes->Apellido_Materno->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Materno" id="z_Apellido_Materno" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Apellido_Materno->CellAttributes() ?>>
			<span id="el_parientes_Apellido_Materno">
<input type="text" data-table="parientes" data-field="x_Apellido_Materno" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($parientes->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $parientes->Apellido_Materno->EditValue ?>"<?php echo $parientes->Apellido_Materno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Nombres->Visible) { // Nombres ?>
	<div id="r_Nombres" class="form-group">
		<label for="x_Nombres" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Nombres"><?php echo $parientes->Nombres->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nombres" id="z_Nombres" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Nombres->CellAttributes() ?>>
			<span id="el_parientes_Nombres">
<input type="text" data-table="parientes" data-field="x_Nombres" name="x_Nombres" id="x_Nombres" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parientes->Nombres->getPlaceHolder()) ?>" value="<?php echo $parientes->Nombres->EditValue ?>"<?php echo $parientes->Nombres->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Nombres1->Visible) { // Nombres1 ?>
	<div id="r_Nombres1" class="form-group">
		<label for="x_Nombres1" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Nombres1"><?php echo $parientes->Nombres1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nombres1" id="z_Nombres1" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Nombres1->CellAttributes() ?>>
			<span id="el_parientes_Nombres1">
<input type="text" data-table="parientes" data-field="x_Nombres1" name="x_Nombres1" id="x_Nombres1" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parientes->Nombres1->getPlaceHolder()) ?>" value="<?php echo $parientes->Nombres1->EditValue ?>"<?php echo $parientes->Nombres1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Apellido_Paterno1->Visible) { // Apellido_Paterno1 ?>
	<div id="r_Apellido_Paterno1" class="form-group">
		<label for="x_Apellido_Paterno1" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Apellido_Paterno1"><?php echo $parientes->Apellido_Paterno1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Paterno1" id="z_Apellido_Paterno1" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Apellido_Paterno1->CellAttributes() ?>>
			<span id="el_parientes_Apellido_Paterno1">
<input type="text" data-table="parientes" data-field="x_Apellido_Paterno1" name="x_Apellido_Paterno1" id="x_Apellido_Paterno1" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($parientes->Apellido_Paterno1->getPlaceHolder()) ?>" value="<?php echo $parientes->Apellido_Paterno1->EditValue ?>"<?php echo $parientes->Apellido_Paterno1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Apellido_Materno1->Visible) { // Apellido_Materno1 ?>
	<div id="r_Apellido_Materno1" class="form-group">
		<label for="x_Apellido_Materno1" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Apellido_Materno1"><?php echo $parientes->Apellido_Materno1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Materno1" id="z_Apellido_Materno1" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Apellido_Materno1->CellAttributes() ?>>
			<span id="el_parientes_Apellido_Materno1">
<input type="text" data-table="parientes" data-field="x_Apellido_Materno1" name="x_Apellido_Materno1" id="x_Apellido_Materno1" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($parientes->Apellido_Materno1->getPlaceHolder()) ?>" value="<?php echo $parientes->Apellido_Materno1->EditValue ?>"<?php echo $parientes->Apellido_Materno1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
	<div id="r_Grado_Parentesco" class="form-group">
		<label for="x_Grado_Parentesco" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Grado_Parentesco"><?php echo $parientes->Grado_Parentesco->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Grado_Parentesco" id="z_Grado_Parentesco" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Grado_Parentesco->CellAttributes() ?>>
			<span id="el_parientes_Grado_Parentesco">
<select data-table="parientes" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $parientes->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x_Grado_Parentesco" name="x_Grado_Parentesco"<?php echo $parientes->Grado_Parentesco->EditAttributes() ?>>
<?php echo $parientes->Grado_Parentesco->SelectOptionListHtml("x_Grado_Parentesco") ?>
</select>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Parentesco->Visible) { // Parentesco ?>
	<div id="r_Parentesco" class="form-group">
		<label for="x_Parentesco" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Parentesco"><?php echo $parientes->Parentesco->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Parentesco" id="z_Parentesco" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Parentesco->CellAttributes() ?>>
			<span id="el_parientes_Parentesco">
<input type="text" data-table="parientes" data-field="x_Parentesco" name="x_Parentesco" id="x_Parentesco" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($parientes->Parentesco->getPlaceHolder()) ?>" value="<?php echo $parientes->Parentesco->EditValue ?>"<?php echo $parientes->Parentesco->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label for="x_Unidad_Organizacional" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Unidad_Organizacional"><?php echo $parientes->Unidad_Organizacional->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad_Organizacional" id="z_Unidad_Organizacional" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Unidad_Organizacional->CellAttributes() ?>>
			<span id="el_parientes_Unidad_Organizacional">
<input type="text" data-table="parientes" data-field="x_Unidad_Organizacional" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($parientes->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $parientes->Unidad_Organizacional->EditValue ?>"<?php echo $parientes->Unidad_Organizacional->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<div id="r_Fiscalia_otro" class="form-group">
		<label for="x_Fiscalia_otro" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Fiscalia_otro"><?php echo $parientes->Fiscalia_otro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Fiscalia_otro" id="z_Fiscalia_otro" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Fiscalia_otro->CellAttributes() ?>>
			<span id="el_parientes_Fiscalia_otro">
<input type="text" data-table="parientes" data-field="x_Fiscalia_otro" name="x_Fiscalia_otro" id="x_Fiscalia_otro" size="30" maxlength="120" placeholder="<?php echo ew_HtmlEncode($parientes->Fiscalia_otro->getPlaceHolder()) ?>" value="<?php echo $parientes->Fiscalia_otro->EditValue ?>"<?php echo $parientes->Fiscalia_otro->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Unidad_Organizacional1->Visible) { // Unidad_Organizacional1 ?>
	<div id="r_Unidad_Organizacional1" class="form-group">
		<label for="x_Unidad_Organizacional1" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Unidad_Organizacional1"><?php echo $parientes->Unidad_Organizacional1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad_Organizacional1" id="z_Unidad_Organizacional1" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Unidad_Organizacional1->CellAttributes() ?>>
			<span id="el_parientes_Unidad_Organizacional1">
<input type="text" data-table="parientes" data-field="x_Unidad_Organizacional1" name="x_Unidad_Organizacional1" id="x_Unidad_Organizacional1" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($parientes->Unidad_Organizacional1->getPlaceHolder()) ?>" value="<?php echo $parientes->Unidad_Organizacional1->EditValue ?>"<?php echo $parientes->Unidad_Organizacional1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Unidad->Visible) { // Unidad ?>
	<div id="r_Unidad" class="form-group">
		<label for="x_Unidad" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Unidad"><?php echo $parientes->Unidad->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad" id="z_Unidad" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Unidad->CellAttributes() ?>>
			<span id="el_parientes_Unidad">
<input type="text" data-table="parientes" data-field="x_Unidad" name="x_Unidad" id="x_Unidad" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($parientes->Unidad->getPlaceHolder()) ?>" value="<?php echo $parientes->Unidad->EditValue ?>"<?php echo $parientes->Unidad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($parientes->Cargo->Visible) { // Cargo ?>
	<div id="r_Cargo" class="form-group">
		<label for="x_Cargo" class="<?php echo $parientes_search->SearchLabelClass ?>"><span id="elh_parientes_Cargo"><?php echo $parientes->Cargo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Cargo" id="z_Cargo" value="LIKE"></p>
		</label>
		<div class="<?php echo $parientes_search->SearchRightColumnClass ?>"><div<?php echo $parientes->Cargo->CellAttributes() ?>>
			<span id="el_parientes_Cargo">
<input type="text" data-table="parientes" data-field="x_Cargo" name="x_Cargo" id="x_Cargo" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($parientes->Cargo->getPlaceHolder()) ?>" value="<?php echo $parientes->Cargo->EditValue ?>"<?php echo $parientes->Cargo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$parientes_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fparientessearch.Init();
</script>
<?php
$parientes_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parientes_search->Page_Terminate();
?>
