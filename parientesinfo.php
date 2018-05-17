<?php

// Global variable for table object
$parientes = NULL;

//
// Table class for parientes
//
class cparientes extends cTable {
	var $CI_RUN;
	var $Expedido;
	var $Apellido_Paterno;
	var $Apellido_Materno;
	var $Nombres;
	var $Nombres1;
	var $Apellido_Paterno1;
	var $Apellido_Materno1;
	var $Grado_Parentesco;
	var $Parentesco;
	var $Unidad_Organizacional;
	var $Fiscalia_otro;
	var $Unidad_Organizacional1;
	var $Unidad;
	var $Cargo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'parientes';
		$this->TableName = 'parientes';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`parientes`";
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

		// CI_RUN
		$this->CI_RUN = new cField('parientes', 'parientes', 'x_CI_RUN', 'CI_RUN', '`CI_RUN`', '`CI_RUN`', 200, -1, FALSE, '`CI_RUN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CI_RUN->Sortable = TRUE; // Allow sort
		$this->fields['CI_RUN'] = &$this->CI_RUN;

		// Expedido
		$this->Expedido = new cField('parientes', 'parientes', 'x_Expedido', 'Expedido', '`Expedido`', '`Expedido`', 200, -1, FALSE, '`Expedido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Expedido->Sortable = TRUE; // Allow sort
		$this->fields['Expedido'] = &$this->Expedido;

		// Apellido_Paterno
		$this->Apellido_Paterno = new cField('parientes', 'parientes', 'x_Apellido_Paterno', 'Apellido_Paterno', '`Apellido_Paterno`', '`Apellido_Paterno`', 200, -1, FALSE, '`Apellido_Paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Paterno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Paterno'] = &$this->Apellido_Paterno;

		// Apellido_Materno
		$this->Apellido_Materno = new cField('parientes', 'parientes', 'x_Apellido_Materno', 'Apellido_Materno', '`Apellido_Materno`', '`Apellido_Materno`', 200, -1, FALSE, '`Apellido_Materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Materno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Materno'] = &$this->Apellido_Materno;

		// Nombres
		$this->Nombres = new cField('parientes', 'parientes', 'x_Nombres', 'Nombres', '`Nombres`', '`Nombres`', 200, -1, FALSE, '`Nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres->Sortable = TRUE; // Allow sort
		$this->fields['Nombres'] = &$this->Nombres;

		// Nombres1
		$this->Nombres1 = new cField('parientes', 'parientes', 'x_Nombres1', 'Nombres1', '`Nombres1`', '`Nombres1`', 200, -1, FALSE, '`Nombres1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres1->Sortable = TRUE; // Allow sort
		$this->fields['Nombres1'] = &$this->Nombres1;

		// Apellido_Paterno1
		$this->Apellido_Paterno1 = new cField('parientes', 'parientes', 'x_Apellido_Paterno1', 'Apellido_Paterno1', '`Apellido_Paterno1`', '`Apellido_Paterno1`', 200, -1, FALSE, '`Apellido_Paterno1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Paterno1->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Paterno1'] = &$this->Apellido_Paterno1;

		// Apellido_Materno1
		$this->Apellido_Materno1 = new cField('parientes', 'parientes', 'x_Apellido_Materno1', 'Apellido_Materno1', '`Apellido_Materno1`', '`Apellido_Materno1`', 200, -1, FALSE, '`Apellido_Materno1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Materno1->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Materno1'] = &$this->Apellido_Materno1;

		// Grado_Parentesco
		$this->Grado_Parentesco = new cField('parientes', 'parientes', 'x_Grado_Parentesco', 'Grado_Parentesco', '`Grado_Parentesco`', '`Grado_Parentesco`', 200, -1, FALSE, '`Grado_Parentesco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Grado_Parentesco->Sortable = TRUE; // Allow sort
		$this->Grado_Parentesco->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Grado_Parentesco->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->Grado_Parentesco->OptionCount = 2;
		$this->fields['Grado_Parentesco'] = &$this->Grado_Parentesco;

		// Parentesco
		$this->Parentesco = new cField('parientes', 'parientes', 'x_Parentesco', 'Parentesco', '`Parentesco`', '`Parentesco`', 200, -1, FALSE, '`Parentesco`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Parentesco->Sortable = TRUE; // Allow sort
		$this->fields['Parentesco'] = &$this->Parentesco;

		// Unidad_Organizacional
		$this->Unidad_Organizacional = new cField('parientes', 'parientes', 'x_Unidad_Organizacional', 'Unidad_Organizacional', '`Unidad_Organizacional`', '`Unidad_Organizacional`', 200, -1, FALSE, '`Unidad_Organizacional`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Unidad_Organizacional->Sortable = TRUE; // Allow sort
		$this->fields['Unidad_Organizacional'] = &$this->Unidad_Organizacional;

		// Fiscalia_otro
		$this->Fiscalia_otro = new cField('parientes', 'parientes', 'x_Fiscalia_otro', 'Fiscalia_otro', '`Fiscalia_otro`', '`Fiscalia_otro`', 200, -1, FALSE, '`Fiscalia_otro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Fiscalia_otro->Sortable = TRUE; // Allow sort
		$this->fields['Fiscalia_otro'] = &$this->Fiscalia_otro;

		// Unidad_Organizacional1
		$this->Unidad_Organizacional1 = new cField('parientes', 'parientes', 'x_Unidad_Organizacional1', 'Unidad_Organizacional1', '`Unidad_Organizacional1`', '`Unidad_Organizacional1`', 200, -1, FALSE, '`Unidad_Organizacional1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Unidad_Organizacional1->Sortable = TRUE; // Allow sort
		$this->fields['Unidad_Organizacional1'] = &$this->Unidad_Organizacional1;

		// Unidad
		$this->Unidad = new cField('parientes', 'parientes', 'x_Unidad', 'Unidad', '`Unidad`', '`Unidad`', 200, -1, FALSE, '`Unidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Unidad->Sortable = TRUE; // Allow sort
		$this->fields['Unidad'] = &$this->Unidad;

		// Cargo
		$this->Cargo = new cField('parientes', 'parientes', 'x_Cargo', 'Cargo', '`Cargo`', '`Cargo`', 200, -1, FALSE, '`Cargo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Cargo->Sortable = TRUE; // Allow sort
		$this->fields['Cargo'] = &$this->Cargo;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`parientes`";
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
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
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
			if (array_key_exists('Nombres1', $rs))
				ew_AddFilter($where, ew_QuotedName('Nombres1', $this->DBID) . '=' . ew_QuotedValue($rs['Nombres1'], $this->Nombres1->FldDataType, $this->DBID));
			if (array_key_exists('Apellido_Paterno1', $rs))
				ew_AddFilter($where, ew_QuotedName('Apellido_Paterno1', $this->DBID) . '=' . ew_QuotedValue($rs['Apellido_Paterno1'], $this->Apellido_Paterno1->FldDataType, $this->DBID));
			if (array_key_exists('Apellido_Materno1', $rs))
				ew_AddFilter($where, ew_QuotedName('Apellido_Materno1', $this->DBID) . '=' . ew_QuotedValue($rs['Apellido_Materno1'], $this->Apellido_Materno1->FldDataType, $this->DBID));
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
		return "`Nombres1` = '@Nombres1@' AND `Apellido_Paterno1` = '@Apellido_Paterno1@' AND `Apellido_Materno1` = '@Apellido_Materno1@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@Nombres1@", ew_AdjustSql($this->Nombres1->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@Apellido_Paterno1@", ew_AdjustSql($this->Apellido_Paterno1->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@Apellido_Materno1@", ew_AdjustSql($this->Apellido_Materno1->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "parienteslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "parienteslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("parientesview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("parientesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "parientesadd.php?" . $this->UrlParm($parm);
		else
			$url = "parientesadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("parientesedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("parientesadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("parientesdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "Nombres1:" . ew_VarToJson($this->Nombres1->CurrentValue, "string", "'");
		$json .= ",Apellido_Paterno1:" . ew_VarToJson($this->Apellido_Paterno1->CurrentValue, "string", "'");
		$json .= ",Apellido_Materno1:" . ew_VarToJson($this->Apellido_Materno1->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Nombres1->CurrentValue)) {
			$sUrl .= "Nombres1=" . urlencode($this->Nombres1->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->Apellido_Paterno1->CurrentValue)) {
			$sUrl .= "&Apellido_Paterno1=" . urlencode($this->Apellido_Paterno1->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->Apellido_Materno1->CurrentValue)) {
			$sUrl .= "&Apellido_Materno1=" . urlencode($this->Apellido_Materno1->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["Nombres1"]))
				$arKey[] = ew_StripSlashes($_POST["Nombres1"]);
			elseif (isset($_GET["Nombres1"]))
				$arKey[] = ew_StripSlashes($_GET["Nombres1"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["Apellido_Paterno1"]))
				$arKey[] = ew_StripSlashes($_POST["Apellido_Paterno1"]);
			elseif (isset($_GET["Apellido_Paterno1"]))
				$arKey[] = ew_StripSlashes($_GET["Apellido_Paterno1"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["Apellido_Materno1"]))
				$arKey[] = ew_StripSlashes($_POST["Apellido_Materno1"]);
			elseif (isset($_GET["Apellido_Materno1"]))
				$arKey[] = ew_StripSlashes($_GET["Apellido_Materno1"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 3)
					continue; // Just skip so other keys will still work
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
			$this->Nombres1->CurrentValue = $key[0];
			$this->Apellido_Paterno1->CurrentValue = $key[1];
			$this->Apellido_Materno1->CurrentValue = $key[2];
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
		$this->CI_RUN->setDbValue($rs->fields('CI_RUN'));
		$this->Expedido->setDbValue($rs->fields('Expedido'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Nombres1->setDbValue($rs->fields('Nombres1'));
		$this->Apellido_Paterno1->setDbValue($rs->fields('Apellido_Paterno1'));
		$this->Apellido_Materno1->setDbValue($rs->fields('Apellido_Materno1'));
		$this->Grado_Parentesco->setDbValue($rs->fields('Grado_Parentesco'));
		$this->Parentesco->setDbValue($rs->fields('Parentesco'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		$this->Unidad_Organizacional1->setDbValue($rs->fields('Unidad_Organizacional1'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// CI_RUN
		$this->CI_RUN->EditAttrs["class"] = "form-control";
		$this->CI_RUN->EditCustomAttributes = "";
		$this->CI_RUN->EditValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->PlaceHolder = ew_RemoveHtml($this->CI_RUN->FldCaption());

		// Expedido
		$this->Expedido->EditAttrs["class"] = "form-control";
		$this->Expedido->EditCustomAttributes = "";
		$this->Expedido->EditValue = $this->Expedido->CurrentValue;
		$this->Expedido->PlaceHolder = ew_RemoveHtml($this->Expedido->FldCaption());

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

		// Nombres
		$this->Nombres->EditAttrs["class"] = "form-control";
		$this->Nombres->EditCustomAttributes = "";
		$this->Nombres->EditValue = $this->Nombres->CurrentValue;
		$this->Nombres->PlaceHolder = ew_RemoveHtml($this->Nombres->FldCaption());

		// Nombres1
		$this->Nombres1->EditAttrs["class"] = "form-control";
		$this->Nombres1->EditCustomAttributes = "";
		$this->Nombres1->EditValue = $this->Nombres1->CurrentValue;
		$this->Nombres1->ViewCustomAttributes = "";

		// Apellido_Paterno1
		$this->Apellido_Paterno1->EditAttrs["class"] = "form-control";
		$this->Apellido_Paterno1->EditCustomAttributes = "";
		$this->Apellido_Paterno1->EditValue = $this->Apellido_Paterno1->CurrentValue;
		$this->Apellido_Paterno1->ViewCustomAttributes = "";

		// Apellido_Materno1
		$this->Apellido_Materno1->EditAttrs["class"] = "form-control";
		$this->Apellido_Materno1->EditCustomAttributes = "";
		$this->Apellido_Materno1->EditValue = $this->Apellido_Materno1->CurrentValue;
		$this->Apellido_Materno1->ViewCustomAttributes = "";

		// Grado_Parentesco
		$this->Grado_Parentesco->EditAttrs["class"] = "form-control";
		$this->Grado_Parentesco->EditCustomAttributes = "";
		$this->Grado_Parentesco->EditValue = $this->Grado_Parentesco->Options(TRUE);

		// Parentesco
		$this->Parentesco->EditAttrs["class"] = "form-control";
		$this->Parentesco->EditCustomAttributes = "";
		$this->Parentesco->EditValue = $this->Parentesco->CurrentValue;
		$this->Parentesco->PlaceHolder = ew_RemoveHtml($this->Parentesco->FldCaption());

		// Unidad_Organizacional
		$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
		$this->Unidad_Organizacional->EditCustomAttributes = "";
		$this->Unidad_Organizacional->EditValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

		// Fiscalia_otro
		$this->Fiscalia_otro->EditAttrs["class"] = "form-control";
		$this->Fiscalia_otro->EditCustomAttributes = "";
		$this->Fiscalia_otro->EditValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->PlaceHolder = ew_RemoveHtml($this->Fiscalia_otro->FldCaption());

		// Unidad_Organizacional1
		$this->Unidad_Organizacional1->EditAttrs["class"] = "form-control";
		$this->Unidad_Organizacional1->EditCustomAttributes = "";
		$this->Unidad_Organizacional1->EditValue = $this->Unidad_Organizacional1->CurrentValue;
		$this->Unidad_Organizacional1->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional1->FldCaption());

		// Unidad
		$this->Unidad->EditAttrs["class"] = "form-control";
		$this->Unidad->EditCustomAttributes = "";
		$this->Unidad->EditValue = $this->Unidad->CurrentValue;
		$this->Unidad->PlaceHolder = ew_RemoveHtml($this->Unidad->FldCaption());

		// Cargo
		$this->Cargo->EditAttrs["class"] = "form-control";
		$this->Cargo->EditCustomAttributes = "";
		$this->Cargo->EditValue = $this->Cargo->CurrentValue;
		$this->Cargo->PlaceHolder = ew_RemoveHtml($this->Cargo->FldCaption());

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
					if ($this->CI_RUN->Exportable) $Doc->ExportCaption($this->CI_RUN);
					if ($this->Expedido->Exportable) $Doc->ExportCaption($this->Expedido);
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Nombres1->Exportable) $Doc->ExportCaption($this->Nombres1);
					if ($this->Apellido_Paterno1->Exportable) $Doc->ExportCaption($this->Apellido_Paterno1);
					if ($this->Apellido_Materno1->Exportable) $Doc->ExportCaption($this->Apellido_Materno1);
					if ($this->Grado_Parentesco->Exportable) $Doc->ExportCaption($this->Grado_Parentesco);
					if ($this->Parentesco->Exportable) $Doc->ExportCaption($this->Parentesco);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Fiscalia_otro->Exportable) $Doc->ExportCaption($this->Fiscalia_otro);
					if ($this->Unidad_Organizacional1->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional1);
					if ($this->Unidad->Exportable) $Doc->ExportCaption($this->Unidad);
					if ($this->Cargo->Exportable) $Doc->ExportCaption($this->Cargo);
				} else {
					if ($this->CI_RUN->Exportable) $Doc->ExportCaption($this->CI_RUN);
					if ($this->Expedido->Exportable) $Doc->ExportCaption($this->Expedido);
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Nombres1->Exportable) $Doc->ExportCaption($this->Nombres1);
					if ($this->Apellido_Paterno1->Exportable) $Doc->ExportCaption($this->Apellido_Paterno1);
					if ($this->Apellido_Materno1->Exportable) $Doc->ExportCaption($this->Apellido_Materno1);
					if ($this->Grado_Parentesco->Exportable) $Doc->ExportCaption($this->Grado_Parentesco);
					if ($this->Parentesco->Exportable) $Doc->ExportCaption($this->Parentesco);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Fiscalia_otro->Exportable) $Doc->ExportCaption($this->Fiscalia_otro);
					if ($this->Unidad_Organizacional1->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional1);
					if ($this->Unidad->Exportable) $Doc->ExportCaption($this->Unidad);
					if ($this->Cargo->Exportable) $Doc->ExportCaption($this->Cargo);
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
						if ($this->CI_RUN->Exportable) $Doc->ExportField($this->CI_RUN);
						if ($this->Expedido->Exportable) $Doc->ExportField($this->Expedido);
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Nombres1->Exportable) $Doc->ExportField($this->Nombres1);
						if ($this->Apellido_Paterno1->Exportable) $Doc->ExportField($this->Apellido_Paterno1);
						if ($this->Apellido_Materno1->Exportable) $Doc->ExportField($this->Apellido_Materno1);
						if ($this->Grado_Parentesco->Exportable) $Doc->ExportField($this->Grado_Parentesco);
						if ($this->Parentesco->Exportable) $Doc->ExportField($this->Parentesco);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Fiscalia_otro->Exportable) $Doc->ExportField($this->Fiscalia_otro);
						if ($this->Unidad_Organizacional1->Exportable) $Doc->ExportField($this->Unidad_Organizacional1);
						if ($this->Unidad->Exportable) $Doc->ExportField($this->Unidad);
						if ($this->Cargo->Exportable) $Doc->ExportField($this->Cargo);
					} else {
						if ($this->CI_RUN->Exportable) $Doc->ExportField($this->CI_RUN);
						if ($this->Expedido->Exportable) $Doc->ExportField($this->Expedido);
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Nombres1->Exportable) $Doc->ExportField($this->Nombres1);
						if ($this->Apellido_Paterno1->Exportable) $Doc->ExportField($this->Apellido_Paterno1);
						if ($this->Apellido_Materno1->Exportable) $Doc->ExportField($this->Apellido_Materno1);
						if ($this->Grado_Parentesco->Exportable) $Doc->ExportField($this->Grado_Parentesco);
						if ($this->Parentesco->Exportable) $Doc->ExportField($this->Parentesco);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Fiscalia_otro->Exportable) $Doc->ExportField($this->Fiscalia_otro);
						if ($this->Unidad_Organizacional1->Exportable) $Doc->ExportField($this->Unidad_Organizacional1);
						if ($this->Unidad->Exportable) $Doc->ExportField($this->Unidad);
						if ($this->Cargo->Exportable) $Doc->ExportField($this->Cargo);
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
