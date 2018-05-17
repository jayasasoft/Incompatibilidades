<?php

// Global variable for table object
$t_usuario = NULL;

//
// Table class for t_usuario
//
class ct_usuario extends cTable {
	var $Codigo_usuario;
	var $Exp;
	var $Nombres;
	var $Apellido_Paterno;
	var $Apellido_Materno;
	var $Usuario;
	var $Clave;
	var $Nivel_id;
	var $_Email;
	var $Activado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_usuario';
		$this->TableName = 't_usuario';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_usuario`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Codigo_usuario
		$this->Codigo_usuario = new cField('t_usuario', 't_usuario', 'x_Codigo_usuario', 'Codigo_usuario', '`Codigo_usuario`', '`Codigo_usuario`', 200, -1, FALSE, '`Codigo_usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Codigo_usuario->Sortable = TRUE; // Allow sort
		$this->Codigo_usuario->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Codigo_usuario'] = &$this->Codigo_usuario;

		// Exp
		$this->Exp = new cField('t_usuario', 't_usuario', 'x_Exp', 'Exp', '`Exp`', '`Exp`', 200, -1, FALSE, '`Exp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Exp->Sortable = TRUE; // Allow sort
		$this->Exp->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Exp->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->Exp->OptionCount = 9;
		$this->fields['Exp'] = &$this->Exp;

		// Nombres
		$this->Nombres = new cField('t_usuario', 't_usuario', 'x_Nombres', 'Nombres', '`Nombres`', '`Nombres`', 200, -1, FALSE, '`Nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres->Sortable = TRUE; // Allow sort
		$this->fields['Nombres'] = &$this->Nombres;

		// Apellido_Paterno
		$this->Apellido_Paterno = new cField('t_usuario', 't_usuario', 'x_Apellido_Paterno', 'Apellido_Paterno', '`Apellido_Paterno`', '`Apellido_Paterno`', 200, -1, FALSE, '`Apellido_Paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Paterno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Paterno'] = &$this->Apellido_Paterno;

		// Apellido_Materno
		$this->Apellido_Materno = new cField('t_usuario', 't_usuario', 'x_Apellido_Materno', 'Apellido_Materno', '`Apellido_Materno`', '`Apellido_Materno`', 200, -1, FALSE, '`Apellido_Materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Materno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Materno'] = &$this->Apellido_Materno;

		// Usuario
		$this->Usuario = new cField('t_usuario', 't_usuario', 'x_Usuario', 'Usuario', '`Usuario`', '`Usuario`', 200, -1, FALSE, '`Usuario`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Usuario->Sortable = FALSE; // Allow sort
		$this->fields['Usuario'] = &$this->Usuario;

		// Clave
		$this->Clave = new cField('t_usuario', 't_usuario', 'x_Clave', 'Clave', '`Clave`', '`Clave`', 200, -1, FALSE, '`Clave`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Clave->Sortable = FALSE; // Allow sort
		$this->fields['Clave'] = &$this->Clave;

		// Nivel_id
		$this->Nivel_id = new cField('t_usuario', 't_usuario', 'x_Nivel_id', 'Nivel_id', '`Nivel_id`', '`Nivel_id`', 3, -1, FALSE, '`Nivel_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Nivel_id->Sortable = TRUE; // Allow sort
		$this->Nivel_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Nivel_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->Nivel_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Nivel_id'] = &$this->Nivel_id;

		// Email
		$this->_Email = new cField('t_usuario', 't_usuario', 'x__Email', 'Email', '`Email`', '`Email`', 200, -1, FALSE, '`Email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_Email->Sortable = TRUE; // Allow sort
		$this->_Email->FldDefaultErrMsg = $Language->Phrase("IncorrectEmail");
		$this->fields['Email'] = &$this->_Email;

		// Activado
		$this->Activado = new cField('t_usuario', 't_usuario', 'x_Activado', 'Activado', '`Activado`', '`Activado`', 16, -1, FALSE, '`Activado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Activado->Sortable = TRUE; // Allow sort
		$this->fields['Activado'] = &$this->Activado;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_usuario`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Clave')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Clave') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('Codigo_usuario', $rs))
				ew_AddFilter($where, ew_QuotedName('Codigo_usuario', $this->DBID) . '=' . ew_QuotedValue($rs['Codigo_usuario'], $this->Codigo_usuario->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`Codigo_usuario` = '@Codigo_usuario@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@Codigo_usuario@", ew_AdjustSql($this->Codigo_usuario->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "t_usuariolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_usuariolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_usuarioview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_usuarioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_usuarioadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_usuarioadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("t_usuarioedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("t_usuarioadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_usuariodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "Codigo_usuario:" . ew_VarToJson($this->Codigo_usuario->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Codigo_usuario->CurrentValue)) {
			$sUrl .= "Codigo_usuario=" . urlencode($this->Codigo_usuario->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["Codigo_usuario"]))
				$arKeys[] = ew_StripSlashes($_POST["Codigo_usuario"]);
			elseif (isset($_GET["Codigo_usuario"]))
				$arKeys[] = ew_StripSlashes($_GET["Codigo_usuario"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->Codigo_usuario->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->Codigo_usuario->setDbValue($rs->fields('Codigo_usuario'));
		$this->Exp->setDbValue($rs->fields('Exp'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Usuario->setDbValue($rs->fields('Usuario'));
		$this->Clave->setDbValue($rs->fields('Clave'));
		$this->Nivel_id->setDbValue($rs->fields('Nivel_id'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->Activado->setDbValue($rs->fields('Activado'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Codigo_usuario
		// Exp
		// Nombres
		// Apellido_Paterno
		// Apellido_Materno
		// Usuario
		// Clave
		// Nivel_id
		// Email
		// Activado
		// Codigo_usuario

		$this->Codigo_usuario->ViewValue = $this->Codigo_usuario->CurrentValue;
		$this->Codigo_usuario->ViewCustomAttributes = "";

		// Exp
		if (strval($this->Exp->CurrentValue) <> "") {
			$this->Exp->ViewValue = $this->Exp->OptionCaption($this->Exp->CurrentValue);
		} else {
			$this->Exp->ViewValue = NULL;
		}
		$this->Exp->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Usuario
		$this->Usuario->ViewValue = $this->Usuario->CurrentValue;
		$this->Usuario->ViewCustomAttributes = "";

		// Clave
		$this->Clave->ViewValue = $this->Clave->CurrentValue;
		$this->Clave->ViewCustomAttributes = "";

		// Nivel_id
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->Nivel_id->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->Nivel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->Nivel_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Nivel_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Nivel_id->ViewValue = $this->Nivel_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Nivel_id->ViewValue = $this->Nivel_id->CurrentValue;
			}
		} else {
			$this->Nivel_id->ViewValue = NULL;
		}
		} else {
			$this->Nivel_id->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->Nivel_id->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// Activado
		$this->Activado->ViewValue = $this->Activado->CurrentValue;
		$this->Activado->ViewCustomAttributes = "";

		// Codigo_usuario
		$this->Codigo_usuario->LinkCustomAttributes = "";
		$this->Codigo_usuario->HrefValue = "";
		$this->Codigo_usuario->TooltipValue = "";

		// Exp
		$this->Exp->LinkCustomAttributes = "";
		$this->Exp->HrefValue = "";
		$this->Exp->TooltipValue = "";

		// Nombres
		$this->Nombres->LinkCustomAttributes = "";
		$this->Nombres->HrefValue = "";
		$this->Nombres->TooltipValue = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->LinkCustomAttributes = "";
		$this->Apellido_Paterno->HrefValue = "";
		$this->Apellido_Paterno->TooltipValue = "";

		// Apellido_Materno
		$this->Apellido_Materno->LinkCustomAttributes = "";
		$this->Apellido_Materno->HrefValue = "";
		$this->Apellido_Materno->TooltipValue = "";

		// Usuario
		$this->Usuario->LinkCustomAttributes = "";
		$this->Usuario->HrefValue = "";
		$this->Usuario->TooltipValue = "";

		// Clave
		$this->Clave->LinkCustomAttributes = "";
		$this->Clave->HrefValue = "";
		$this->Clave->TooltipValue = "";

		// Nivel_id
		$this->Nivel_id->LinkCustomAttributes = "";
		$this->Nivel_id->HrefValue = "";
		$this->Nivel_id->TooltipValue = "";

		// Email
		$this->_Email->LinkCustomAttributes = "";
		$this->_Email->HrefValue = "";
		$this->_Email->TooltipValue = "";

		// Activado
		$this->Activado->LinkCustomAttributes = "";
		$this->Activado->HrefValue = "";
		$this->Activado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Codigo_usuario
		$this->Codigo_usuario->EditAttrs["class"] = "form-control";
		$this->Codigo_usuario->EditCustomAttributes = "";
		$this->Codigo_usuario->EditValue = $this->Codigo_usuario->CurrentValue;
		$this->Codigo_usuario->ViewCustomAttributes = "";

		// Exp
		$this->Exp->EditAttrs["class"] = "form-control";
		$this->Exp->EditCustomAttributes = "";
		$this->Exp->EditValue = $this->Exp->Options(TRUE);

		// Nombres
		$this->Nombres->EditAttrs["class"] = "form-control";
		$this->Nombres->EditCustomAttributes = "";
		$this->Nombres->EditValue = $this->Nombres->CurrentValue;
		$this->Nombres->PlaceHolder = ew_RemoveHtml($this->Nombres->FldCaption());

		// Apellido_Paterno
		$this->Apellido_Paterno->EditAttrs["class"] = "form-control";
		$this->Apellido_Paterno->EditCustomAttributes = "";
		$this->Apellido_Paterno->EditValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->PlaceHolder = ew_RemoveHtml($this->Apellido_Paterno->FldCaption());

		// Apellido_Materno
		$this->Apellido_Materno->EditAttrs["class"] = "form-control";
		$this->Apellido_Materno->EditCustomAttributes = "";
		$this->Apellido_Materno->EditValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->PlaceHolder = ew_RemoveHtml($this->Apellido_Materno->FldCaption());

		// Usuario
		$this->Usuario->EditAttrs["class"] = "form-control";
		$this->Usuario->EditCustomAttributes = "";
		$this->Usuario->EditValue = $this->Usuario->CurrentValue;
		$this->Usuario->PlaceHolder = ew_RemoveHtml($this->Usuario->FldCaption());

		// Clave
		$this->Clave->EditAttrs["class"] = "form-control";
		$this->Clave->EditCustomAttributes = "";
		$this->Clave->EditValue = $this->Clave->CurrentValue;
		$this->Clave->PlaceHolder = ew_RemoveHtml($this->Clave->FldCaption());

		// Nivel_id
		$this->Nivel_id->EditAttrs["class"] = "form-control";
		$this->Nivel_id->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->Nivel_id->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

		// Email
		$this->_Email->EditAttrs["class"] = "form-control";
		$this->_Email->EditCustomAttributes = "";
		$this->_Email->EditValue = $this->_Email->CurrentValue;
		$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

		// Activado
		$this->Activado->EditAttrs["class"] = "form-control";
		$this->Activado->EditCustomAttributes = "";
		$this->Activado->EditValue = $this->Activado->CurrentValue;
		$this->Activado->PlaceHolder = ew_RemoveHtml($this->Activado->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->Codigo_usuario->Exportable) $Doc->ExportCaption($this->Codigo_usuario);
					if ($this->Exp->Exportable) $Doc->ExportCaption($this->Exp);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Usuario->Exportable) $Doc->ExportCaption($this->Usuario);
					if ($this->Clave->Exportable) $Doc->ExportCaption($this->Clave);
					if ($this->Nivel_id->Exportable) $Doc->ExportCaption($this->Nivel_id);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->Activado->Exportable) $Doc->ExportCaption($this->Activado);
				} else {
					if ($this->Codigo_usuario->Exportable) $Doc->ExportCaption($this->Codigo_usuario);
					if ($this->Exp->Exportable) $Doc->ExportCaption($this->Exp);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Usuario->Exportable) $Doc->ExportCaption($this->Usuario);
					if ($this->Clave->Exportable) $Doc->ExportCaption($this->Clave);
					if ($this->Nivel_id->Exportable) $Doc->ExportCaption($this->Nivel_id);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->Activado->Exportable) $Doc->ExportCaption($this->Activado);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->Codigo_usuario->Exportable) $Doc->ExportField($this->Codigo_usuario);
						if ($this->Exp->Exportable) $Doc->ExportField($this->Exp);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Usuario->Exportable) $Doc->ExportField($this->Usuario);
						if ($this->Clave->Exportable) $Doc->ExportField($this->Clave);
						if ($this->Nivel_id->Exportable) $Doc->ExportField($this->Nivel_id);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->Activado->Exportable) $Doc->ExportField($this->Activado);
					} else {
						if ($this->Codigo_usuario->Exportable) $Doc->ExportField($this->Codigo_usuario);
						if ($this->Exp->Exportable) $Doc->ExportField($this->Exp);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Usuario->Exportable) $Doc->ExportField($this->Usuario);
						if ($this->Clave->Exportable) $Doc->ExportField($this->Clave);
						if ($this->Nivel_id->Exportable) $Doc->ExportField($this->Nivel_id);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->Activado->Exportable) $Doc->ExportField($this->Activado);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '`Codigo_usuario` = ' . ew_QuotedValue($userid, EW_DATATYPE_STRING, EW_USER_TABLE_DBID);
		$sParentUserIDFilter = '`Codigo_usuario` IN (SELECT `Codigo_usuario` FROM ' . "`t_usuario`" . ' WHERE `Codigo_usuario` = ' . ew_QuotedValue($userid, EW_DATATYPE_STRING, EW_USER_TABLE_DBID) . ')';
		$sUserIDFilter = "($sUserIDFilter) OR ($sParentUserIDFilter)";
		return $sUserIDFilter;
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`Codigo_usuario` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// Add Parent User ID filter
	function AddParentUserIDFilter($sFilter, $userid) {
		global $Security;
		if (!$Security->IsAdmin()) {
			$result = $Security->ParentUserIDList($userid);
			if ($result <> "")
				$result = '`Codigo_usuario` IN (' . $result . ')';
			ew_AddFilter($result, $sFilter);
			return $result;
		} else {
			return $sFilter;
		}
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `t_usuario`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Send register email
	function SendRegisterEmail($row) {
		$Email = $this->PrepareRegisterEmail($row);
		$Args = array();
		$Args["rs"] = $row;
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $Args)) // NOTE: use Email_Sending server event of user table
			$bEmailSent = $Email->Send();
		return $bEmailSent;
	}

	// Prepare register email
	function PrepareRegisterEmail($row = NULL, $langid = "") {
		$Email = new cEmail;
		$Email->Load(EW_EMAIL_REGISTER_TEMPLATE, $langid);
		$sReceiverEmail = ($row == NULL) ? $this->_Email->CurrentValue : $row['Email'];
		if ($sReceiverEmail == "") { // Send to recipient directly
			$sReceiverEmail = EW_RECIPIENT_EMAIL;
			$sBccEmail = "";
		} else { // Bcc recipient
			$sBccEmail = EW_RECIPIENT_EMAIL;
		}
		$Email->ReplaceSender(EW_SENDER_EMAIL); // Replace Sender
		$Email->ReplaceRecipient($sReceiverEmail); // Replace Recipient
		if ($sBccEmail <> "") $Email->AddBcc($sBccEmail); // Add Bcc
		$Email->ReplaceContent('<!--FieldCaption_Codigo_usuario-->', $this->Codigo_usuario->FldCaption());
		$Email->ReplaceContent('<!--Codigo_usuario-->', ($row == NULL) ? strval($this->Codigo_usuario->FormValue) : $row['Codigo_usuario']);
		$Email->ReplaceContent('<!--FieldCaption_Exp-->', $this->Exp->FldCaption());
		$Email->ReplaceContent('<!--Exp-->', ($row == NULL) ? strval($this->Exp->FormValue) : $row['Exp']);
		$Email->ReplaceContent('<!--FieldCaption_Nombres-->', $this->Nombres->FldCaption());
		$Email->ReplaceContent('<!--Nombres-->', ($row == NULL) ? strval($this->Nombres->FormValue) : $row['Nombres']);
		$Email->ReplaceContent('<!--FieldCaption_Apellido_Paterno-->', $this->Apellido_Paterno->FldCaption());
		$Email->ReplaceContent('<!--Apellido_Paterno-->', ($row == NULL) ? strval($this->Apellido_Paterno->FormValue) : $row['Apellido_Paterno']);
		$Email->ReplaceContent('<!--FieldCaption_Apellido_Materno-->', $this->Apellido_Materno->FldCaption());
		$Email->ReplaceContent('<!--Apellido_Materno-->', ($row == NULL) ? strval($this->Apellido_Materno->FormValue) : $row['Apellido_Materno']);
		$Email->ReplaceContent('<!--FieldCaption_Usuario-->', $this->Usuario->FldCaption());
		$Email->ReplaceContent('<!--Usuario-->', ($row == NULL) ? strval($this->Usuario->FormValue) : $row['Usuario']);
		$Email->ReplaceContent('<!--FieldCaption_Clave-->', $this->Clave->FldCaption());
		$Email->ReplaceContent('<!--Clave-->', ($row == NULL) ? strval($this->Clave->FormValue) : $row['Clave']);
		$Email->ReplaceContent('<!--FieldCaption_Email-->', $this->_Email->FldCaption());
		$Email->ReplaceContent('<!--Email-->', ($row == NULL) ? strval($this->_Email->FormValue) : $row['Email']);
		$Email->Content = preg_replace('/<!--\s*register_activate_link_begin[\s\S]*?-->[\s\S]*?<!--\s*register_activate_link_end[\s\S]*?-->/i', '', $Email->Content); // Remove activate link block
		return $Email;
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
