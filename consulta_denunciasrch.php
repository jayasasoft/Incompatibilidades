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

$consulta_denuncia_search = NULL; // Initialize page object first

class cconsulta_denuncia_search extends cconsulta_denuncia {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 'consulta_denuncia';

	// Page object name
	var $PageObjName = 'consulta_denuncia_search';

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

		// Table object (consulta_denuncia)
		if (!isset($GLOBALS["consulta_denuncia"]) || get_class($GLOBALS["consulta_denuncia"]) == "cconsulta_denuncia") {
			$GLOBALS["consulta_denuncia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["consulta_denuncia"];
		}

		// Table object (t_usuario)
		if (!isset($GLOBALS['t_usuario'])) $GLOBALS['t_usuario'] = new ct_usuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("consulta_denuncialist.php"));
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
		$this->Nombres_Apellidos->SetVisibility();
		$this->Unidad_Organizacional->SetVisibility();
		$this->Fecha_denuncia->SetVisibility();

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
						$sSrchStr = "consulta_denuncialist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->Nombres_Apellidos); // Nombres_Apellidos
		$this->BuildSearchUrl($sSrchUrl, $this->Unidad_Organizacional); // Unidad_Organizacional
		$this->BuildSearchUrl($sSrchUrl, $this->Fecha_denuncia); // Fecha_denuncia
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

		// Nombres_Apellidos
		$this->Nombres_Apellidos->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Nombres_Apellidos"));
		$this->Nombres_Apellidos->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Nombres_Apellidos");

		// Unidad_Organizacional
		$this->Unidad_Organizacional->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Unidad_Organizacional"));
		$this->Unidad_Organizacional->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Unidad_Organizacional");

		// Fecha_denuncia
		$this->Fecha_denuncia->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_Fecha_denuncia"));
		$this->Fecha_denuncia->AdvancedSearch->SearchOperator = $objForm->GetValue("z_Fecha_denuncia");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// CI_RUN
		// Nombres_Apellidos
		// Unidad_Organizacional
		// Detalles
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

		// Fecha_denuncia
		$this->Fecha_denuncia->ViewValue = $this->Fecha_denuncia->CurrentValue;
		$this->Fecha_denuncia->ViewValue = ew_FormatDateTime($this->Fecha_denuncia->ViewValue, 0);
		$this->Fecha_denuncia->ViewCustomAttributes = "";

			// CI_RUN
			$this->CI_RUN->LinkCustomAttributes = "";
			$this->CI_RUN->HrefValue = "";
			$this->CI_RUN->TooltipValue = "";

			// Nombres_Apellidos
			$this->Nombres_Apellidos->LinkCustomAttributes = "";
			$this->Nombres_Apellidos->HrefValue = "";
			$this->Nombres_Apellidos->TooltipValue = "";

			// Unidad_Organizacional
			$this->Unidad_Organizacional->LinkCustomAttributes = "";
			$this->Unidad_Organizacional->HrefValue = "";
			$this->Unidad_Organizacional->TooltipValue = "";

			// Fecha_denuncia
			$this->Fecha_denuncia->LinkCustomAttributes = "";
			$this->Fecha_denuncia->HrefValue = "";
			$this->Fecha_denuncia->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// CI_RUN
			$this->CI_RUN->EditAttrs["class"] = "form-control";
			$this->CI_RUN->EditCustomAttributes = "";
			$this->CI_RUN->EditValue = ew_HtmlEncode($this->CI_RUN->AdvancedSearch->SearchValue);
			$this->CI_RUN->PlaceHolder = ew_RemoveHtml($this->CI_RUN->FldCaption());

			// Nombres_Apellidos
			$this->Nombres_Apellidos->EditAttrs["class"] = "form-control";
			$this->Nombres_Apellidos->EditCustomAttributes = "";
			$this->Nombres_Apellidos->EditValue = ew_HtmlEncode($this->Nombres_Apellidos->AdvancedSearch->SearchValue);
			$this->Nombres_Apellidos->PlaceHolder = ew_RemoveHtml($this->Nombres_Apellidos->FldCaption());

			// Unidad_Organizacional
			$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
			$this->Unidad_Organizacional->EditCustomAttributes = "";
			$this->Unidad_Organizacional->EditValue = ew_HtmlEncode($this->Unidad_Organizacional->AdvancedSearch->SearchValue);
			$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

			// Fecha_denuncia
			$this->Fecha_denuncia->EditAttrs["class"] = "form-control";
			$this->Fecha_denuncia->EditCustomAttributes = "";
			$this->Fecha_denuncia->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->Fecha_denuncia->AdvancedSearch->SearchValue, 0), 8));
			$this->Fecha_denuncia->PlaceHolder = ew_RemoveHtml($this->Fecha_denuncia->FldCaption());
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
		if (!ew_CheckDateDef($this->Fecha_denuncia->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->Fecha_denuncia->FldErrMsg());
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
		$this->Nombres_Apellidos->AdvancedSearch->Load();
		$this->Unidad_Organizacional->AdvancedSearch->Load();
		$this->Fecha_denuncia->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("consulta_denuncialist.php"), "", $this->TableVar, TRUE);
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
if (!isset($consulta_denuncia_search)) $consulta_denuncia_search = new cconsulta_denuncia_search();

// Page init
$consulta_denuncia_search->Page_Init();

// Page main
$consulta_denuncia_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$consulta_denuncia_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($consulta_denuncia_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fconsulta_denunciasearch = new ew_Form("fconsulta_denunciasearch", "search");
<?php } else { ?>
var CurrentForm = fconsulta_denunciasearch = new ew_Form("fconsulta_denunciasearch", "search");
<?php } ?>

// Form_CustomValidate event
fconsulta_denunciasearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconsulta_denunciasearch.ValidateRequired = true;
<?php } else { ?>
fconsulta_denunciasearch.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search
// Validate function for search

fconsulta_denunciasearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_Fecha_denuncia");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($consulta_denuncia->Fecha_denuncia->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$consulta_denuncia_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $consulta_denuncia_search->ShowPageHeader(); ?>
<?php
$consulta_denuncia_search->ShowMessage();
?>
<form name="fconsulta_denunciasearch" id="fconsulta_denunciasearch" class="<?php echo $consulta_denuncia_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($consulta_denuncia_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $consulta_denuncia_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="consulta_denuncia">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($consulta_denuncia_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($consulta_denuncia->CI_RUN->Visible) { // CI_RUN ?>
	<div id="r_CI_RUN" class="form-group">
		<label for="x_CI_RUN" class="<?php echo $consulta_denuncia_search->SearchLabelClass ?>"><span id="elh_consulta_denuncia_CI_RUN"><?php echo $consulta_denuncia->CI_RUN->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_CI_RUN" id="z_CI_RUN" value="LIKE"></p>
		</label>
		<div class="<?php echo $consulta_denuncia_search->SearchRightColumnClass ?>"><div<?php echo $consulta_denuncia->CI_RUN->CellAttributes() ?>>
			<span id="el_consulta_denuncia_CI_RUN">
<input type="text" data-table="consulta_denuncia" data-field="x_CI_RUN" name="x_CI_RUN" id="x_CI_RUN" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($consulta_denuncia->CI_RUN->getPlaceHolder()) ?>" value="<?php echo $consulta_denuncia->CI_RUN->EditValue ?>"<?php echo $consulta_denuncia->CI_RUN->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($consulta_denuncia->Nombres_Apellidos->Visible) { // Nombres_Apellidos ?>
	<div id="r_Nombres_Apellidos" class="form-group">
		<label for="x_Nombres_Apellidos" class="<?php echo $consulta_denuncia_search->SearchLabelClass ?>"><span id="elh_consulta_denuncia_Nombres_Apellidos"><?php echo $consulta_denuncia->Nombres_Apellidos->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Nombres_Apellidos" id="z_Nombres_Apellidos" value="LIKE"></p>
		</label>
		<div class="<?php echo $consulta_denuncia_search->SearchRightColumnClass ?>"><div<?php echo $consulta_denuncia->Nombres_Apellidos->CellAttributes() ?>>
			<span id="el_consulta_denuncia_Nombres_Apellidos">
<input type="text" data-table="consulta_denuncia" data-field="x_Nombres_Apellidos" name="x_Nombres_Apellidos" id="x_Nombres_Apellidos" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($consulta_denuncia->Nombres_Apellidos->getPlaceHolder()) ?>" value="<?php echo $consulta_denuncia->Nombres_Apellidos->EditValue ?>"<?php echo $consulta_denuncia->Nombres_Apellidos->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($consulta_denuncia->Unidad_Organizacional->Visible) { // Unidad_Organizacional ?>
	<div id="r_Unidad_Organizacional" class="form-group">
		<label for="x_Unidad_Organizacional" class="<?php echo $consulta_denuncia_search->SearchLabelClass ?>"><span id="elh_consulta_denuncia_Unidad_Organizacional"><?php echo $consulta_denuncia->Unidad_Organizacional->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_Unidad_Organizacional" id="z_Unidad_Organizacional" value="LIKE"></p>
		</label>
		<div class="<?php echo $consulta_denuncia_search->SearchRightColumnClass ?>"><div<?php echo $consulta_denuncia->Unidad_Organizacional->CellAttributes() ?>>
			<span id="el_consulta_denuncia_Unidad_Organizacional">
<input type="text" data-table="consulta_denuncia" data-field="x_Unidad_Organizacional" name="x_Unidad_Organizacional" id="x_Unidad_Organizacional" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($consulta_denuncia->Unidad_Organizacional->getPlaceHolder()) ?>" value="<?php echo $consulta_denuncia->Unidad_Organizacional->EditValue ?>"<?php echo $consulta_denuncia->Unidad_Organizacional->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($consulta_denuncia->Fecha_denuncia->Visible) { // Fecha_denuncia ?>
	<div id="r_Fecha_denuncia" class="form-group">
		<label for="x_Fecha_denuncia" class="<?php echo $consulta_denuncia_search->SearchLabelClass ?>"><span id="elh_consulta_denuncia_Fecha_denuncia"><?php echo $consulta_denuncia->Fecha_denuncia->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Fecha_denuncia" id="z_Fecha_denuncia" value="="></p>
		</label>
		<div class="<?php echo $consulta_denuncia_search->SearchRightColumnClass ?>"><div<?php echo $consulta_denuncia->Fecha_denuncia->CellAttributes() ?>>
			<span id="el_consulta_denuncia_Fecha_denuncia">
<input type="text" data-table="consulta_denuncia" data-field="x_Fecha_denuncia" name="x_Fecha_denuncia" id="x_Fecha_denuncia" placeholder="<?php echo ew_HtmlEncode($consulta_denuncia->Fecha_denuncia->getPlaceHolder()) ?>" value="<?php echo $consulta_denuncia->Fecha_denuncia->EditValue ?>"<?php echo $consulta_denuncia->Fecha_denuncia->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$consulta_denuncia_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fconsulta_denunciasearch.Init();
</script>
<?php
$consulta_denuncia_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$consulta_denuncia_search->Page_Terminate();
?>
