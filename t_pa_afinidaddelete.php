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

$t_pa_afinidad_delete = NULL; // Initialize page object first

class ct_pa_afinidad_delete extends ct_pa_afinidad {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Table name
	var $TableName = 't_pa_afinidad';

	// Page object name
	var $PageObjName = 't_pa_afinidad_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_pa_afinidadlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_pa_afinidad class, t_pa_afinidadinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("t_pa_afinidadlist.php"); // Return to list
			}
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
		$this->Nombre->setDbValue($rs->fields('Nombre'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Grado_Parentesco->setDbValue($rs->fields('Grado_Parentesco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->Id->DbValue = $row['Id'];
		$this->Nombre->DbValue = $row['Nombre'];
		$this->Apellido_Paterno->DbValue = $row['Apellido_Paterno'];
		$this->Apellido_Materno->DbValue = $row['Apellido_Materno'];
		$this->Grado_Parentesco->DbValue = $row['Grado_Parentesco'];
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$conn->BeginTrans();

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
				$sThisKey .= $row['Nombre'];
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
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_pa_afinidadlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($t_pa_afinidad_delete)) $t_pa_afinidad_delete = new ct_pa_afinidad_delete();

// Page init
$t_pa_afinidad_delete->Page_Init();

// Page main
$t_pa_afinidad_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_pa_afinidad_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_pa_afinidaddelete = new ew_Form("ft_pa_afinidaddelete", "delete");

// Form_CustomValidate event
ft_pa_afinidaddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_pa_afinidaddelete.ValidateRequired = true;
<?php } else { ?>
ft_pa_afinidaddelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_pa_afinidaddelete.Lists["x_Grado_Parentesco"] = {"LinkField":"x_Parentesco","Ajax":true,"AutoFill":false,"DisplayFields":["x_Parentesco","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"s_afinidad"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $t_pa_afinidad_delete->ShowPageHeader(); ?>
<?php
$t_pa_afinidad_delete->ShowMessage();
?>
<form name="ft_pa_afinidaddelete" id="ft_pa_afinidaddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_pa_afinidad_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_pa_afinidad_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_pa_afinidad">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_pa_afinidad_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_pa_afinidad->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_pa_afinidad->Id->Visible) { // Id ?>
		<th><span id="elh_t_pa_afinidad_Id" class="t_pa_afinidad_Id"><?php echo $t_pa_afinidad->Id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
		<th><span id="elh_t_pa_afinidad_Nombre" class="t_pa_afinidad_Nombre"><?php echo $t_pa_afinidad->Nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<th><span id="elh_t_pa_afinidad_Apellido_Paterno" class="t_pa_afinidad_Apellido_Paterno"><?php echo $t_pa_afinidad->Apellido_Paterno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<th><span id="elh_t_pa_afinidad_Apellido_Materno" class="t_pa_afinidad_Apellido_Materno"><?php echo $t_pa_afinidad->Apellido_Materno->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
		<th><span id="elh_t_pa_afinidad_Grado_Parentesco" class="t_pa_afinidad_Grado_Parentesco"><?php echo $t_pa_afinidad->Grado_Parentesco->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_pa_afinidad_delete->RecCnt = 0;
$i = 0;
while (!$t_pa_afinidad_delete->Recordset->EOF) {
	$t_pa_afinidad_delete->RecCnt++;
	$t_pa_afinidad_delete->RowCnt++;

	// Set row properties
	$t_pa_afinidad->ResetAttrs();
	$t_pa_afinidad->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_pa_afinidad_delete->LoadRowValues($t_pa_afinidad_delete->Recordset);

	// Render row
	$t_pa_afinidad_delete->RenderRow();
?>
	<tr<?php echo $t_pa_afinidad->RowAttributes() ?>>
<?php if ($t_pa_afinidad->Id->Visible) { // Id ?>
		<td<?php echo $t_pa_afinidad->Id->CellAttributes() ?>>
<span id="el<?php echo $t_pa_afinidad_delete->RowCnt ?>_t_pa_afinidad_Id" class="t_pa_afinidad_Id">
<span<?php echo $t_pa_afinidad->Id->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_pa_afinidad->Nombre->Visible) { // Nombre ?>
		<td<?php echo $t_pa_afinidad->Nombre->CellAttributes() ?>>
<span id="el<?php echo $t_pa_afinidad_delete->RowCnt ?>_t_pa_afinidad_Nombre" class="t_pa_afinidad_Nombre">
<span<?php echo $t_pa_afinidad->Nombre->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Paterno->Visible) { // Apellido_Paterno ?>
		<td<?php echo $t_pa_afinidad->Apellido_Paterno->CellAttributes() ?>>
<span id="el<?php echo $t_pa_afinidad_delete->RowCnt ?>_t_pa_afinidad_Apellido_Paterno" class="t_pa_afinidad_Apellido_Paterno">
<span<?php echo $t_pa_afinidad->Apellido_Paterno->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Apellido_Paterno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_pa_afinidad->Apellido_Materno->Visible) { // Apellido_Materno ?>
		<td<?php echo $t_pa_afinidad->Apellido_Materno->CellAttributes() ?>>
<span id="el<?php echo $t_pa_afinidad_delete->RowCnt ?>_t_pa_afinidad_Apellido_Materno" class="t_pa_afinidad_Apellido_Materno">
<span<?php echo $t_pa_afinidad->Apellido_Materno->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Apellido_Materno->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_pa_afinidad->Grado_Parentesco->Visible) { // Grado_Parentesco ?>
		<td<?php echo $t_pa_afinidad->Grado_Parentesco->CellAttributes() ?>>
<span id="el<?php echo $t_pa_afinidad_delete->RowCnt ?>_t_pa_afinidad_Grado_Parentesco" class="t_pa_afinidad_Grado_Parentesco">
<span<?php echo $t_pa_afinidad->Grado_Parentesco->ViewAttributes() ?>>
<?php echo $t_pa_afinidad->Grado_Parentesco->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_pa_afinidad_delete->Recordset->MoveNext();
}
$t_pa_afinidad_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_pa_afinidad_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_pa_afinidaddelete.Init();
</script>
<?php
$t_pa_afinidad_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_pa_afinidad_delete->Page_Terminate();
?>
