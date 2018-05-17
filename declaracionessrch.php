<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "declaracionesinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$declaraciones_search = NULL; // Initialize page object first

class cdeclaraciones_search extends cdeclaraciones {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'declaraciones';

	// Page object name
	var $PageObjName = 'declaraciones_search';

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

		// Table object (declaraciones)
		if (!isset($GLOBALS["declaraciones"]) || get_class($GLOBALS["declaraciones"]) == "cdeclaraciones") {
			$GLOBALS["declaraciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["declaraciones"];
		}

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'declaraciones', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("declaracioneslist.php"));
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
		$this->Expedido1->SetVisibility();
		$this->Apellido_Paterno3->SetVisibility();
		$this->Apellido_Materno3->SetVisibility();
		$this->Nombres2->SetVisibility();
		$this->Fecha->SetVisibility();
		$this->Fiscalia_otro->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Unidad->SetVisibility();
		$this->Cargo->SetVisibility();
		$this->Archivo->SetVisibility();

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
		global $EW_EXPORT, $declaraciones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($declaraciones);
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
						$sSrchStr = "declaracioneslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->Expedido1); // Expedido1
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Paterno3); // Apellido_Paterno3
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Materno3); // Apellido_Materno3
		$this->BuildSearchUrl($sSrchUrl, $this->Nombres2); // Nombres2
		$this->BuildSearchUrl($sSrchUrl, $this->Fecha); // Fecha
		$this->BuildSearchUrl($sSrchUrl, $this->Fiscalia_otro); // Fiscalia_otro
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad_Organizacional); // Unidad_Organizacional
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad); // Unidad
		$this->BuildSearchUrl($sSrchUrl, $this->Cargo); // Cargo
		$this->BuildSearchUrl($sSrchUrl, $this->Archivo); // Archivo
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

		// Expedido1
		$this->Expedido1->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Expedido1"));
		$this->Expedido1->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Expedido1");

		// Apellido_Paterno3
		$this->Apellido_Paterno3->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Paterno3"));
		$this->Apellido_Paterno3->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Paterno3");

		// Apellido_Materno3
		$this->Apellido_Materno3->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Materno3"));
		$this->Apellido_Materno3->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Materno3");

		// Nombres2
		$this->Nombres2->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nombres2"));
		$this->Nombres2->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nombres2");

		// Fecha
		$this->Fecha->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Fecha"));
		$this->Fecha->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Fecha");

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

		// Archivo
		$this->Archivo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Archivo"));
		$this->Archivo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Archivo");
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
		// Expedido1
		// Apellido_Paterno3
		// Apellido_Materno3
		// Nombres2
		// Fecha
		// Fiscalia_otro
		// Unidad_Organizacional
		// Unidad
		// Cargo
		// Archivo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CI_RUN
		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewValue = ew_FormatNumber($this->CI_RUN->ViewValue, 0, 0, 0, 0);
		$this->CI_RUN->ViewCustomAttributes = "";

		// Expedido1
		$this->Expedido1->ViewValue = $this->Expedido1->CurrentValue;
		$this->Expedido1->ViewCustomAttributes = "";

		// Apellido_Paterno3
		$this->Apellido_Paterno3->ViewValue = $this->Apellido_Paterno3->CurrentValue;
		$this->Apellido_Paterno3->ViewCustomAttributes = "";

		// Apellido_Materno3
		$this->Apellido_Materno3->ViewValue = $this->Apellido_Materno3->CurrentValue;
		$this->Apellido_Materno3->ViewCustomAttributes = "";

		// Nombres2
		$this->Nombres2->ViewValue = $this->Nombres2->CurrentValue;
		$this->Nombres2->ViewCustomAttributes = "";

		// Fecha
		$this->Fecha->ViewValue = $this->Fecha->CurrentValue;
		$this->Fecha->ViewValue = ew_FormatDateTime($this->Fecha->ViewValue, 0);
		$this->Fecha->ViewCustomAttributes = "";

		// Fiscalia_otro
		if (strval($this->Fiscalia_otro->CurrentValue) <> "") {
			$sFilterWrk = "`denominacion`" . ew_SearchString("=", $this->Fiscalia_otro->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `denominacion`, `Departamento` AS `DispFld`, `denominacion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_fiscalias`";
		$sWhereWrk = "";
		$this->Fiscalia_otro->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Fiscalia_otro, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
			}
		} else {
			$this->Fiscalia_otro->ViewValue = NULL;
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

		// Archivo
		$this->Archivo->ViewValue = $this->Archivo->CurrentValue;
		$this->Archivo->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

			// Expedido1
			$this->Expedido1->LinkCustomAttributes = "";
			$this->Expedido1->HrefValue = "";
			$this->Expedido1->TooltipValue = "";

			// Apellido_Paterno3
			$this->Apellido_Paterno3->LinkCustomAttributes = "";
			$this->Apellido_Paterno3->HrefValue = "";
			$this->Apellido_Paterno3->TooltipValue = "";

			// Apellido_Materno3
			$this->Apellido_Materno3->LinkCustomAttributes = "";
			$this->Apellido_Materno3->HrefValue = "";
			$this->Apellido_Materno3->TooltipValue = "";

			// Nombres2
			$this->Nombres2->LinkCustomAttributes = "";
			$this->Nombres2->HrefValue = "";
			$this->Nombres2->TooltipValue = "";

			// Fecha
			$this->Fecha->LinkCustomAttributes = "";
			$this->Fecha->HrefValue = "";
			$this->Fecha->TooltipValue = "";

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

			// Archivo
			$this->Archivo->LinkCustomAttributes = "";
			if (!ew_Empty($this->Archivo->CurrentValue)) {
				$this->Archivo->HrefValue = ((!empty($this->Archivo->ViewValue)) ? ew_RemoveHtml($this->Archivo->ViewValue) : $this->Archivo->CurrentValue); // Add prefix/suffix
				$this->Archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->Archivo->HrefValue = ew_ConvertFullUrl($this->Archivo->HrefValue);
			} else {
				$this->Archivo->HrefValue = "";
			}
			$this->Archivo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// CI_RUN
			$this->CI_RUN->EditAttrs["class"] = "form-control";
			$this->CI_RUN->EditCustomAttributes = "";
			$this->CI_RUN->EditValue = ew_HtmlEncode($this->CI_RUN->AdvancedSearch->SearchValue);
			$this->CI_RUN->PlaceHolder = ew_RemoveHtml($this->CI_RUN->FldCaption());

			// Expedido1
			$this->Expedido1->EditAttrs["class"] = "form-control";
			$this->Expedido1->EditCustomAttributes = "";
			$this->Expedido1->EditValue = ew_HtmlEncode($this->Expedido1->AdvancedSearch->SearchValue);
			$this->Expedido1->PlaceHolder = ew_RemoveHtml($this->Expedido1->FldCaption());

			// Apellido_Paterno3
			$this->Apellido_Paterno3->EditAttrs["class"] = "form-control";
			$this->Apellido_Paterno3->EditCustomAttributes = "";
			$this->Apellido_Paterno3->EditValue = ew_HtmlEncode($this->Apellido_Paterno3->AdvancedSearch->SearchValue);
			$this->Apellido_Paterno3->PlaceHolder = ew_RemoveHtml($this->Apellido_Paterno3->FldCaption());

			// Apellido_Materno3
			$this->Apellido_Materno3->EditAttrs["class"] = "form-control";
			$this->Apellido_Materno3->EditCustomAttributes = "";
			$this->Apellido_Materno3->EditValue = ew_HtmlEncode($this->Apellido_Materno3->AdvancedSearch->SearchValue);
			$this->Apellido_Materno3->PlaceHolder = ew_RemoveHtml($this->Apellido_Materno3->FldCaption());

			// Nombres2
			$this->Nombres2->EditAttrs["class"] = "form-control";
			$this->Nombres2->EditCustomAttributes = "";
			$this->Nombres2->EditValue = ew_HtmlEncode($this->Nombres2->AdvancedSearch->SearchValue);
			$this->Nombres2->PlaceHolder = ew_RemoveHtml($this->Nombres2->FldCaption());

			// Fecha
			$this->Fecha->EditAttrs["class"] = "form-control";
			$this->Fecha->EditCustomAttributes = "";
			$this->Fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Fecha->AdvancedSearch->SearchValue, 0), 8));
			$this->Fecha->PlaceHolder = ew_RemoveHtml($this->Fecha->FldCaption());

			// Fiscalia_otro
			$this->Fiscalia_otro->EditAttrs["class"] = "form-control";
			$this->Fiscalia_otro->EditCustomAttributes = "";
			if (trim(strval($this->Fiscalia_otro->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`denominacion`" . ew_SearchString("=", $this->Fiscalia_otro->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `denominacion`, `Departamento` AS `DispFld`, `denominacion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_fiscalias`";
			$sWhereWrk = "";
			$this->Fiscalia_otro->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Fiscalia_otro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Fiscalia_otro->EditValue = $arwrk;

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

			// Archivo
			$this->Archivo->EditAttrs["class"] = "form-control";
			$this->Archivo->EditCustomAttributes = "";
			$this->Archivo->EditValue = ew_HtmlEncode($this->Archivo->AdvancedSearch->SearchValue);
			$this->Archivo->PlaceHolder = ew_RemoveHtml($this->Archivo->FldCaption());
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
		if (!ew_CheckDateDef($this->Fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Fecha->FldErrMsg());
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
		$this->Expedido1->AdvancedSearch->Load();
		$this->Apellido_Paterno3->AdvancedSearch->Load();
		$this->Apellido_Materno3->AdvancedSearch->Load();
		$this->Nombres2->AdvancedSearch->Load();
		$this->Fecha->AdvancedSearch->Load();
		$this->Fiscalia_otro->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Unidad->AdvancedSearch->Load();
		$this->Cargo->AdvancedSearch->Load();
		$this->Archivo->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("declaracioneslist.php"), "", $this->TableVar, TRUE);
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
			$sSqlWrk = "SELECT `denominacion` AS `LinkFld`, `Departamento` AS `DispFld`, `denominacion` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_fiscalias`";
			$sWhereWrk = "";
			$this->Fiscalia_otro->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`denominacion` = {filter_value}', "t0" => "200", "fn0" => "");
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
if (!isset($declaraciones_search)) $declaraciones_search = new cdeclaraciones_search();

// Page init
$declaraciones_search->Page_Init();

// Page main
$declaraciones_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$declaraciones_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($declaraciones_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fdeclaracionessearch = new ew_Form("fdeclaracionessearch", "search");
<?php } else { ?>
var CurrentForm = fdeclaracionessearch = new ew_Form("fdeclaracionessearch", "search");
<?php } ?>

// Form_CustomValidate event
fdeclaracionessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdeclaracionessearch.ValidateRequired = true;
<?php } else { ?>
fdeclaracionessearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdeclaracionessearch.Lists["x_Fiscalia_otro"] = {"LinkField":"x_denominacion","Ajax":true,"AutoFill":false,"DisplayFields":["x_Departamento","x_denominacion","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_fiscalias"};

// Form object for search
// Validate function for search

fdeclaracionessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_CI_RUN");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($declaraciones->CI_RUN->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_Fecha");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($declaraciones->Fecha->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$declaraciones_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $declaraciones_search->ShowPageHeader(); ?>
<?php
$declaraciones_search->ShowMessage();
?>
<form name="fdeclaracionessearch" id="fdeclaracionessearch" class="<?php echo $declaraciones_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($declaraciones_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $declaraciones_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="declaraciones">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($declaraciones_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($declaraciones->CI_RUN->Visible) { // CI_RUN ?>
	<div id="r_CI_RUN" class="form-group">
		<label for="x_CI_RUN" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_CI_RUN"><?php echo $declaraciones->CI_RUN->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CI_RUN" id="z_CI_RUN" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->CI_RUN->CellAttributes() ?>>
			<span id="el_declaraciones_CI_RUN">
<input type="text" data-table="declaraciones" data-field="x_CI_RUN" name="x_CI_RUN" id="x_CI_RUN" size="30" maxlength="8" placeholder="<?php echo ew_HtmlEncode($declaraciones->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $declaraciones->CI_RUN->EditValue ?>"<?php echo $declaraciones->CI_RUN->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Expedido1->Visible) { // Expedido1 ?>
	<div id="r_Expedido1" class="form-group">
		<label for="x_Expedido1" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Expedido1"><?php echo $declaraciones->Expedido1->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Expedido1" id="z_Expedido1" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Expedido1->CellAttributes() ?>>
			<span id="el_declaraciones_Expedido1">
<input type="text" data-table="declaraciones" data-field="x_Expedido1" name="x_Expedido1" id="x_Expedido1" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($declaraciones->Expedido1->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Expedido1->EditValue ?>"<?php echo $declaraciones->Expedido1->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Apellido_Paterno3->Visible) { // Apellido_Paterno3 ?>
	<div id="r_Apellido_Paterno3" class="form-group">
		<label for="x_Apellido_Paterno3" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Apellido_Paterno3"><?php echo $declaraciones->Apellido_Paterno3->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Paterno3" id="z_Apellido_Paterno3" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Apellido_Paterno3->CellAttributes() ?>>
			<span id="el_declaraciones_Apellido_Paterno3">
<input type="text" data-table="declaraciones" data-field="x_Apellido_Paterno3" name="x_Apellido_Paterno3" id="x_Apellido_Paterno3" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($declaraciones->Apellido_Paterno3->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Apellido_Paterno3->EditValue ?>"<?php echo $declaraciones->Apellido_Paterno3->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Apellido_Materno3->Visible) { // Apellido_Materno3 ?>
	<div id="r_Apellido_Materno3" class="form-group">
		<label for="x_Apellido_Materno3" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Apellido_Materno3"><?php echo $declaraciones->Apellido_Materno3->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Materno3" id="z_Apellido_Materno3" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Apellido_Materno3->CellAttributes() ?>>
			<span id="el_declaraciones_Apellido_Materno3">
<input type="text" data-table="declaraciones" data-field="x_Apellido_Materno3" name="x_Apellido_Materno3" id="x_Apellido_Materno3" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($declaraciones->Apellido_Materno3->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Apellido_Materno3->EditValue ?>"<?php echo $declaraciones->Apellido_Materno3->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Nombres2->Visible) { // Nombres2 ?>
	<div id="r_Nombres2" class="form-group">
		<label for="x_Nombres2" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Nombres2"><?php echo $declaraciones->Nombres2->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nombres2" id="z_Nombres2" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Nombres2->CellAttributes() ?>>
			<span id="el_declaraciones_Nombres2">
<input type="text" data-table="declaraciones" data-field="x_Nombres2" name="x_Nombres2" id="x_Nombres2" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($declaraciones->Nombres2->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Nombres2->EditValue ?>"<?php echo $declaraciones->Nombres2->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Fecha->Visible) { // Fecha ?>
	<div id="r_Fecha" class="form-group">
		<label for="x_Fecha" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Fecha"><?php echo $declaraciones->Fecha->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Fecha" id="z_Fecha" value="="></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Fecha->CellAttributes() ?>>
			<span id="el_declaraciones_Fecha">
<input type="text" data-table="declaraciones" data-field="x_Fecha" name="x_Fecha" id="x_Fecha" placeholder="<?php echo ew_HtmlEncode($declaraciones->Fecha->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Fecha->EditValue ?>"<?php echo $declaraciones->Fecha->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Fiscalia_otro->Visible) { // Fiscalia_otro ?>
	<div id="r_Fiscalia_otro" class="form-group">
		<label for="x_Fiscalia_otro" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Fiscalia_otro"><?php echo $declaraciones->Fiscalia_otro->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Fiscalia_otro" id="z_Fiscalia_otro" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Fiscalia_otro->CellAttributes() ?>>
			<span id="el_declaraciones_Fiscalia_otro">
<select data-table="declaraciones" data-field="x_Fiscalia_otro" data-value-separator="<?php echo $declaraciones->Fiscalia_otro->DisplayValueSeparatorAttribute() ?>" id="x_Fiscalia_otro" name="x_Fiscalia_otro"<?php echo $declaraciones->Fiscalia_otro->EditAttributes() ?>>
<?php echo $declaraciones->Fiscalia_otro->SelectOptionListHtml("x_Fiscalia_otro") ?>
</select>
<input type="hidden" name="s_x_Fiscalia_otro" id="s_x_Fiscalia_otro" value="<?php echo $declaraciones->Fiscalia_otro->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label for="x_Unidad_Organizacional" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Unidad_Organizacional"><?php echo $declaraciones->Unidad_Organizacional->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad_Organizacional" id="z_Unidad_Organizacional" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Unidad_Organizacional->CellAttributes() ?>>
			<span id="el_declaraciones_Unidad_Organizacional">
<input type="text" data-table="declaraciones" data-field="x_Unidad_Organizacional" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($declaraciones->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Unidad_Organizacional->EditValue ?>"<?php echo $declaraciones->Unidad_Organizacional->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Unidad->Visible) { // Unidad ?>
	<div id="r_Unidad" class="form-group">
		<label for="x_Unidad" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Unidad"><?php echo $declaraciones->Unidad->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad" id="z_Unidad" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Unidad->CellAttributes() ?>>
			<span id="el_declaraciones_Unidad">
<input type="text" data-table="declaraciones" data-field="x_Unidad" name="x_Unidad" id="x_Unidad" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($declaraciones->Unidad->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Unidad->EditValue ?>"<?php echo $declaraciones->Unidad->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Cargo->Visible) { // Cargo ?>
	<div id="r_Cargo" class="form-group">
		<label for="x_Cargo" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Cargo"><?php echo $declaraciones->Cargo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Cargo" id="z_Cargo" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Cargo->CellAttributes() ?>>
			<span id="el_declaraciones_Cargo">
<input type="text" data-table="declaraciones" data-field="x_Cargo" name="x_Cargo" id="x_Cargo" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($declaraciones->Cargo->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Cargo->EditValue ?>"<?php echo $declaraciones->Cargo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($declaraciones->Archivo->Visible) { // Archivo ?>
	<div id="r_Archivo" class="form-group">
		<label for="x_Archivo" class="<?php echo $declaraciones_search->SearchLabelClass ?>"><span id="elh_declaraciones_Archivo"><?php echo $declaraciones->Archivo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Archivo" id="z_Archivo" value="LIKE"></p>
		</label>
		<div class="<?php echo $declaraciones_search->SearchRightColumnClass ?>"><div<?php echo $declaraciones->Archivo->CellAttributes() ?>>
			<span id="el_declaraciones_Archivo">
<input type="text" data-table="declaraciones" data-field="x_Archivo" name="x_Archivo" id="x_Archivo" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($declaraciones->Archivo->getPlaceHolder()) ?>" value="<?php echo $declaraciones->Archivo->EditValue ?>"<?php echo $declaraciones->Archivo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$declaraciones_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fdeclaracionessearch.Init();
</script>
<?php
$declaraciones_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$declaraciones_search->Page_Terminate();
?>
