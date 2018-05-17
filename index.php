<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_usuarioinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}";

	// Page object name
	var $PageObjName = 'default';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;

		// If session expired, show session expired message
		if (@$_GET["expired"] == "1")
			$this->setFailureMessage($Language->Phrase("SessionExpired"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 't_funcionario'))
		$this->Page_Terminate("t_funcionariolist.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 't_actiividades_remuneradas'))
			$this->Page_Terminate("t_actiividades_remuneradaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_conyugue'))
			$this->Page_Terminate("t_conyuguelist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_pa_afinidad'))
			$this->Page_Terminate("t_pa_afinidadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_pa_consanguinidad'))
			$this->Page_Terminate("t_pa_consanguinidadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_parientes_mp'))
			$this->Page_Terminate("t_parientes_mplist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_re_adopcion'))
			$this->Page_Terminate("t_re_adopcionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_salario'))
			$this->Page_Terminate("t_salariolist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_usuario'))
			$this->Page_Terminate("t_usuariolist.php");
		if ($Security->AllowList(CurrentProjectID() . 'userlevels'))
			$this->Page_Terminate("userlevelslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'audittrail2'))
			$this->Page_Terminate("audittrail2list.php");
		if ($Security->AllowList(CurrentProjectID() . 'consanguineos'))
			$this->Page_Terminate("consanguineoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'afinidad'))
			$this->Page_Terminate("afinidadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'parientes'))
			$this->Page_Terminate("parienteslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_cargos'))
			$this->Page_Terminate("t_cargoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_unidad'))
			$this->Page_Terminate("t_unidadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_unidad_organizacional'))
			$this->Page_Terminate("t_unidad_organizacionallist.php");
		if ($Security->AllowList(CurrentProjectID() . 'conyugue'))
			$this->Page_Terminate("conyuguelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'seleccion_cargos'))
			$this->Page_Terminate("seleccion_cargoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_grados'))
			$this->Page_Terminate("t_gradoslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_parentesco'))
			$this->Page_Terminate("t_parentescolist.php");
		if ($Security->AllowList(CurrentProjectID() . 's_consanguineo'))
			$this->Page_Terminate("s_consanguineolist.php");
		if ($Security->AllowList(CurrentProjectID() . 's_afinidad'))
			$this->Page_Terminate("s_afinidadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 's_adopcion'))
			$this->Page_Terminate("s_adopcionlist.php");
		if ($Security->AllowList(CurrentProjectID() . 's_parentesco_global'))
			$this->Page_Terminate("s_parentesco_globallist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_declaraciones'))
			$this->Page_Terminate("t_declaracioneslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'declaraciones'))
			$this->Page_Terminate("declaracioneslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'Incompatibilidad'))
			$this->Page_Terminate("Incompatibilidadlist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_denuncias'))
			$this->Page_Terminate("t_denunciaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_mp_si_no'))
			$this->Page_Terminate("t_mp_si_nolist.php");
		if ($Security->AllowList(CurrentProjectID() . 't_fiscalias'))
			$this->Page_Terminate("t_fiscaliaslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'consulta_denuncia'))
			$this->Page_Terminate("consulta_denuncialist.php");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage(ew_DeniedMsg() . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
