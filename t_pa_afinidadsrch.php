<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_pa_afinidadinfo.php" ?>
<?php include_once "t_funcionarioinfo.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_pa_afinidad_search = NULL; // Initialize page object first

class ct_pa_afinidad_search extends ct_pa_afinidad {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_pa_afinidad';

	// Page object name
	var $PageObjName = 't_pa_afinidad_search';

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

		// Table object (t_pa_afinidad)
		if (!isset($GLOBALS["t_pa_afinidad"]) || get_class($GLOBALS["t_pa_afinidad"]) == "ct_pa_afinidad") {
			$GLOBALS["t_pa_afinidad"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_pa_afinidad"];
		}

		// Table object (t_funcionario)
		if (!isset($GLOBALS['t_funcionario'])) $GLOBALS['t_funcionario'] = new ct_funcionario();

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_pa_afinidad', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("t_pa_afinidadlist.php"));
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
		$this->Id->SetVisibility();
		$this->Nombre->SetVisibility();
		$this->Apellido_Paterno->SetVisibility();
		$this->Apellido_Materno->SetVisibility();
		$this->Grado_Parentesco->SetVisibility();

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
		global $EW_EXPORT, $t_pa_afinidad;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_pa_afinidad);
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
						$sSrchStr = "t_pa_afinidadlist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->Id); // Id
		$this->BuildSearchUrl($sSrchUrl, $this->Nombre); // Nombre
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Paterno); // Apellido_Paterno
		$this->BuildSearchUrl($sSrchUrl, $this->Apellido_Materno); // Apellido_Materno
		$this->BuildSearchUrl($sSrchUrl, $this->Grado_Parentesco); // Grado_Parentesco
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
		// Id

		$this->Id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Id"));
		$this->Id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Id");

		// Nombre
		$this->Nombre->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nombre"));
		$this->Nombre->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nombre");

		// Apellido_Paterno
		$this->Apellido_Paterno->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Paterno"));
		$this->Apellido_Paterno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Paterno");

		// Apellido_Materno
		$this->Apellido_Materno->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Apellido_Materno"));
		$this->Apellido_Materno->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Apellido_Materno");

		// Grado_Parentesco
		$this->Grado_Parentesco->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Grado_Parentesco"));
		$this->Grado_Parentesco->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Grado_Parentesco");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// Id
		// Nombre
		// Apellido_Paterno
		// Apellido_Materno
		// Grado_Parentesco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

		// Nombre
		$this->Nombre->ViewValue = $this->Nombre->CurrentValue;
		$this->Nombre->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Grado_Parentesco
		if (strval($this->Grado_Parentesco->CurrentValue) <> "") {
			$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Grado_Parentesco->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_afinidad`";
		$sWhereWrk = "";
		$this->Grado_Parentesco->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Grado_Parentesco->ViewValue = $this->Grado_Parentesco->CurrentValue;
			}
		} else {
			$this->Grado_Parentesco->ViewValue = NULL;
		}
		$this->Grado_Parentesco->ViewCustomAttributes = "";

			// Id
			$this->Id->LinkCustomAttributes = "";
			$this->Id->HrefValue = "";
			$this->Id->TooltipValue = "";

			// Nombre
			$this->Nombre->LinkCustomAttributes = "";
			$this->Nombre->HrefValue = "";
			$this->Nombre->TooltipValue = "";

			// Apellido_Paterno
			$this->Apellido_Paterno->LinkCustomAttributes = "";
			$this->Apellido_Paterno->HrefValue = "";
			$this->Apellido_Paterno->TooltipValue = "";

			// Apellido_Materno
			$this->Apellido_Materno->LinkCustomAttributes = "";
			$this->Apellido_Materno->HrefValue = "";
			$this->Apellido_Materno->TooltipValue = "";

			// Grado_Parentesco
			$this->Grado_Parentesco->LinkCustomAttributes = "";
			$this->Grado_Parentesco->HrefValue = "";
			$this->Grado_Parentesco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// Id
			$this->Id->EditAttrs["class"] = "form-control";
			$this->Id->EditCustomAttributes = "";
			$this->Id->EditValue = ew_HtmlEncode($this->Id->AdvancedSearch->SearchValue);
			$this->Id->PlaceHolder = ew_RemoveHtml($this->Id->FldCaption());

			// Nombre
			$this->Nombre->EditAttrs["class"] = "form-control";
			$this->Nombre->EditCustomAttributes = "";
			$this->Nombre->EditValue = ew_HtmlEncode($this->Nombre->AdvancedSearch->SearchValue);
			$this->Nombre->PlaceHolder = ew_RemoveHtml($this->Nombre->FldCaption());

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

			// Grado_Parentesco
			$this->Grado_Parentesco->EditAttrs["class"] = "form-control";
			$this->Grado_Parentesco->EditCustomAttributes = "";
			if (trim(strval($this->Grado_Parentesco->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`Parentesco`" . ew_SearchString("=", $this->Grado_Parentesco->AdvancedSearch->SearchValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `Parentesco`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `s_afinidad`";
			$sWhereWrk = "";
			$this->Grado_Parentesco->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->Grado_Parentesco->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->Id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Id->FldErrMsg());
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
		$this->Id->AdvancedSearch->Load();
		$this->Nombre->AdvancedSearch->Load();
		$this->Apellido_Paterno->AdvancedSearch->Load();
		$this->Apellido_Materno->AdvancedSearch->Load();
		$this->Grado_Parentesco->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_pa_afinidadlist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_Grado_Parentesco":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `Parentesco` AS `LinkFld`, `Parentesco` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `s_afinidad`";
			$sWhereWrk = "";
			$this->Grado_Parentesco->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`Parentesco` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->Grado_Parentesco, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_pa_afinidad_search)) $t_pa_afinidad_search = new ct_pa_afinidad_search();

// Page init
$t_pa_afinidad_search->Page_Init();

// Page main
$t_pa_afinidad_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pa_afinidad_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($t_pa_afinidad_search->IsModal) { ?>
var CurrentAdvancedSearchForm = ft_pa_afinidadsearch = new ew_Form("ft_pa_afinidadsearch", "search");
<?php } else { ?>
var CurrentForm = ft_pa_afinidadsearch = new ew_Form("ft_pa_afinidadsearch", "search");
<?php } ?>

// Form_CustomValidate event
ft_pa_afinidadsearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pa_afinidadsearch.ValidateRequired = true;
<?php } else { ?>
ft_pa_afinidadsearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pa_afinidadsearch.Lists["x_Grado_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_afinidad"};

// Form object for search
// Validate function for search

ft_pa_afinidadsearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_Id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($t_pa_afinidad->Id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_pa_afinidad_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_pa_afinidad_search->ShowPageHeader(); ?>
<?php
$t_pa_afinidad_search->ShowMessage();
?>
<form name="ft_pa_afinidadsearch" id="ft_pa_afinidadsearch" class="<?php echo $t_pa_afinidad_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_pa_afinidad_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_pa_afinidad_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_pa_afinidad">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($t_pa_afinidad_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_pa_afinidad->Id->Visible) { // Id ?>
	<div id="r_Id" class="form-group">
		<label for="x_Id" class="<?php echo $t_pa_afinidad_search->SearchLabelClass ?>"><span id="elh_t_pa_afinidad_Id"><?php echo $t_pa_afinidad->Id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Id" id="z_Id" value="="></p>
		</label>
		<div class="<?php echo $t_pa_afinidad_search->SearchRightColumnClass ?>"><div<?php echo $t_pa_afinidad->Id->CellAttributes() ?>>
			<span id="el_t_pa_afinidad_Id">
<input type="text" data-table="t_pa_afinidad" data-field="x_Id" name="x_Id" id="x_Id" size="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Id->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Id->EditValue ?>"<?php echo $t_pa_afinidad->Id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
	<div id="r_Nombre" class="form-group">
		<label for="x_Nombre" class="<?php echo $t_pa_afinidad_search->SearchLabelClass ?>"><span id="elh_t_pa_afinidad_Nombre"><?php echo $t_pa_afinidad->Nombre->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nombre" id="z_Nombre" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_pa_afinidad_search->SearchRightColumnClass ?>"><div<?php echo $t_pa_afinidad->Nombre->CellAttributes() ?>>
			<span id="el_t_pa_afinidad_Nombre">
<input type="text" data-table="t_pa_afinidad" data-field="x_Nombre" name="x_Nombre" id="x_Nombre" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Nombre->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Nombre->EditValue ?>"<?php echo $t_pa_afinidad->Nombre->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
	<div id="r_Apellido_Paterno" class="form-group">
		<label for="x_Apellido_Paterno" class="<?php echo $t_pa_afinidad_search->SearchLabelClass ?>"><span id="elh_t_pa_afinidad_Apellido_Paterno"><?php echo $t_pa_afinidad->Apellido_Paterno->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Paterno" id="z_Apellido_Paterno" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_pa_afinidad_search->SearchRightColumnClass ?>"><div<?php echo $t_pa_afinidad->Apellido_Paterno->CellAttributes() ?>>
			<span id="el_t_pa_afinidad_Apellido_Paterno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Paterno" name="x_Apellido_Paterno" id="x_Apellido_Paterno" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Paterno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Paterno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Paterno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
	<div id="r_Apellido_Materno" class="form-group">
		<label for="x_Apellido_Materno" class="<?php echo $t_pa_afinidad_search->SearchLabelClass ?>"><span id="elh_t_pa_afinidad_Apellido_Materno"><?php echo $t_pa_afinidad->Apellido_Materno->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Apellido_Materno" id="z_Apellido_Materno" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_pa_afinidad_search->SearchRightColumnClass ?>"><div<?php echo $t_pa_afinidad->Apellido_Materno->CellAttributes() ?>>
			<span id="el_t_pa_afinidad_Apellido_Materno">
<input type="text" data-table="t_pa_afinidad" data-field="x_Apellido_Materno" name="x_Apellido_Materno" id="x_Apellido_Materno" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_pa_afinidad->Apellido_Materno->getPlaceHolder()) ?>" value="<?php echo $t_pa_afinidad->Apellido_Materno->EditValue ?>"<?php echo $t_pa_afinidad->Apellido_Materno->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
	<div id="r_Grado_Parentesco" class="form-group">
		<label for="x_Grado_Parentesco" class="<?php echo $t_pa_afinidad_search->SearchLabelClass ?>"><span id="elh_t_pa_afinidad_Grado_Parentesco"><?php echo $t_pa_afinidad->Grado_Parentesco->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Grado_Parentesco" id="z_Grado_Parentesco" value="LIKE"></p>
		</label>
		<div class="<?php echo $t_pa_afinidad_search->SearchRightColumnClass ?>"><div<?php echo $t_pa_afinidad->Grado_Parentesco->CellAttributes() ?>>
			<span id="el_t_pa_afinidad_Grado_Parentesco">
<select data-table="t_pa_afinidad" data-field="x_Grado_Parentesco" data-value-separator="<?php echo $t_pa_afinidad->Grado_Parentesco->DisplayValueSeparatorAttribute() ?>" id="x_Grado_Parentesco" name="x_Grado_Parentesco"<?php echo $t_pa_afinidad->Grado_Parentesco->EditAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->SelectOptionListHtml("x_Grado_Parentesco") ?>
</select>
<input type="hidden" name="s_x_Grado_Parentesco" id="s_x_Grado_Parentesco" value="<?php echo $t_pa_afinidad->Grado_Parentesco->LookupFilterQuery() ?>">
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_pa_afinidad_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_pa_afinidadsearch.Init();
</script>
<?php
$t_pa_afinidad_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_pa_afinidad_search->Page_Terminate();
?>
