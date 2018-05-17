<?php

// Global variable for table object
$conyugue = NULL;

//
// Table class for conyugue
//
class cconyugue extends cTable {
	var $CI_RUN;
	var $Apellido_Paterno;
	var $Apellido_Materno;
	var $Nombres;
	var $Telefono;
	var $Celular;
	var $Direccion;
	var $Fiscalia_otro;
	var $Unidad_Organizacional;
	var $Cargo;
	var $Unidad;
	var $CI_RUN1;
	var $Expedido;
	var $Apellido_Paterno1;
	var $Apellido_Materno1;
	var $Nombres1;
	var $Direccion1;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'conyugue';
		$this->TableName = 'conyugue';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`conyugue`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
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
		$this->BasicSearch->TypeDefault = "AND";

		// CI_RUN
		$this->CI_RUN = new cField('conyugue', 'conyugue', 'x_CI_RUN', 'CI_RUN', '`CI_RUN`', '`CI_RUN`', 200, -1, FALSE, '`CI_RUN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CI_RUN->Sortable = TRUE; // Allow sort
		$this->CI_RUN->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CI_RUN'] = &$this->CI_RUN;

		// Apellido_Paterno
		$this->Apellido_Paterno = new cField('conyugue', 'conyugue', 'x_Apellido_Paterno', 'Apellido_Paterno', '`Apellido_Paterno`', '`Apellido_Paterno`', 200, -1, FALSE, '`Apellido_Paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Paterno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Paterno'] = &$this->Apellido_Paterno;

		// Apellido_Materno
		$this->Apellido_Materno = new cField('conyugue', 'conyugue', 'x_Apellido_Materno', 'Apellido_Materno', '`Apellido_Materno`', '`Apellido_Materno`', 200, -1, FALSE, '`Apellido_Materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Materno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Materno'] = &$this->Apellido_Materno;

		// Nombres
		$this->Nombres = new cField('conyugue', 'conyugue', 'x_Nombres', 'Nombres', '`Nombres`', '`Nombres`', 200, -1, FALSE, '`Nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres->Sortable = TRUE; // Allow sort
		$this->fields['Nombres'] = &$this->Nombres;

		// Telefono
		$this->Telefono = new cField('conyugue', 'conyugue', 'x_Telefono', 'Telefono', '`Telefono`', '`Telefono`', 200, -1, FALSE, '`Telefono`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Telefono->Sortable = TRUE; // Allow sort
		$this->Telefono->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Telefono'] = &$this->Telefono;

		// Celular
		$this->Celular = new cField('conyugue', 'conyugue', 'x_Celular', 'Celular', '`Celular`', '`Celular`', 200, -1, FALSE, '`Celular`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Celular->Sortable = TRUE; // Allow sort
		$this->Celular->FldDefaultErrMsg = $Language->Phrase("IncorrectField");
		$this->fields['Celular'] = &$this->Celular;

		// Direccion
		$this->Direccion = new cField('conyugue', 'conyugue', 'x_Direccion', 'Direccion', '`Direccion`', '`Direccion`', 200, -1, FALSE, '`Direccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Direccion->Sortable = TRUE; // Allow sort
		$this->fields['Direccion'] = &$this->Direccion;

		// Fiscalia_otro
		$this->Fiscalia_otro = new cField('conyugue', 'conyugue', 'x_Fiscalia_otro', 'Fiscalia_otro', '`Fiscalia_otro`', '`Fiscalia_otro`', 200, -1, FALSE, '`Fiscalia_otro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Fiscalia_otro->Sortable = TRUE; // Allow sort
		$this->fields['Fiscalia_otro'] = &$this->Fiscalia_otro;

		// Unidad_Organizacional
		$this->Unidad_Organizacional = new cField('conyugue', 'conyugue', 'x_Unidad_Organizacional', 'Unidad_Organizacional', '`Unidad_Organizacional`', '`Unidad_Organizacional`', 200, -1, FALSE, '`Unidad_Organizacional`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Unidad_Organizacional->Sortable = TRUE; // Allow sort
		$this->Unidad_Organizacional->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Unidad_Organizacional->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['Unidad_Organizacional'] = &$this->Unidad_Organizacional;

		// Cargo
		$this->Cargo = new cField('conyugue', 'conyugue', 'x_Cargo', 'Cargo', '`Cargo`', '`Cargo`', 200, -1, FALSE, '`EV__Cargo`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->Cargo->Sortable = TRUE; // Allow sort
		$this->Cargo->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Cargo->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['Cargo'] = &$this->Cargo;

		// Unidad
		$this->Unidad = new cField('conyugue', 'conyugue', 'x_Unidad', 'Unidad', '`Unidad`', '`Unidad`', 200, -1, FALSE, '`EV__Unidad`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->Unidad->Sortable = TRUE; // Allow sort
		$this->Unidad->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Unidad->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['Unidad'] = &$this->Unidad;

		// CI_RUN1
		$this->CI_RUN1 = new cField('conyugue', 'conyugue', 'x_CI_RUN1', 'CI_RUN1', '`CI_RUN1`', '`CI_RUN1`', 200, -1, FALSE, '`CI_RUN1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CI_RUN1->Sortable = TRUE; // Allow sort
		$this->CI_RUN1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CI_RUN1'] = &$this->CI_RUN1;

		// Expedido
		$this->Expedido = new cField('conyugue', 'conyugue', 'x_Expedido', 'Expedido', '`Expedido`', '`Expedido`', 200, -1, FALSE, '`Expedido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Expedido->Sortable = TRUE; // Allow sort
		$this->Expedido->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Expedido->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->Expedido->OptionCount = 9;
		$this->fields['Expedido'] = &$this->Expedido;

		// Apellido_Paterno1
		$this->Apellido_Paterno1 = new cField('conyugue', 'conyugue', 'x_Apellido_Paterno1', 'Apellido_Paterno1', '`Apellido_Paterno1`', '`Apellido_Paterno1`', 200, -1, FALSE, '`Apellido_Paterno1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Paterno1->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Paterno1'] = &$this->Apellido_Paterno1;

		// Apellido_Materno1
		$this->Apellido_Materno1 = new cField('conyugue', 'conyugue', 'x_Apellido_Materno1', 'Apellido_Materno1', '`Apellido_Materno1`', '`Apellido_Materno1`', 200, -1, FALSE, '`Apellido_Materno1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Materno1->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Materno1'] = &$this->Apellido_Materno1;

		// Nombres1
		$this->Nombres1 = new cField('conyugue', 'conyugue', 'x_Nombres1', 'Nombres1', '`Nombres1`', '`Nombres1`', 200, -1, FALSE, '`Nombres1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres1->Sortable = TRUE; // Allow sort
		$this->fields['Nombres1'] = &$this->Nombres1;

		// Direccion1
		$this->Direccion1 = new cField('conyugue', 'conyugue', 'x_Direccion1', 'Direccion1', '`Direccion1`', '`Direccion1`', 200, -1, FALSE, '`Direccion1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Direccion1->Sortable = TRUE; // Allow sort
		$this->fields['Direccion1'] = &$this->Direccion1;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`conyugue`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT `Cargo` FROM `t_cargos` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`Cargo` = `conyugue`.`Cargo` LIMIT 1) AS `EV__Cargo`, (SELECT `Unidad` FROM `t_unidad` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`Unidad` = `conyugue`.`Unidad` LIMIT 1) AS `EV__Unidad` FROM `conyugue`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`Apellido_Paterno1` ASC,`Apellido_Materno1` ASC,`Nombres1` ASC";
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->BasicSearch->getKeyword() <> "")
			return TRUE;
		if ($this->Cargo->AdvancedSearch->SearchValue <> "" ||
			$this->Cargo->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Cargo->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Cargo->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->Unidad->AdvancedSearch->SearchValue <> "" ||
			$this->Unidad->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Unidad->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Unidad->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
			if (array_key_exists('CI_RUN1', $rs))
				ew_AddFilter($where, ew_QuotedName('CI_RUN1', $this->DBID) . '=' . ew_QuotedValue($rs['CI_RUN1'], $this->CI_RUN1->FldDataType, $this->DBID));
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
		return "`CI_RUN1` = '@CI_RUN1@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@CI_RUN1@", ew_AdjustSql($this->CI_RUN1->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "conyuguelist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "conyuguelist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("conyugueview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("conyugueview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "conyugueadd.php?" . $this->UrlParm($parm);
		else
			$url = "conyugueadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("conyugueedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("conyugueadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("conyuguedelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "CI_RUN1:" . ew_VarToJson($this->CI_RUN1->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->CI_RUN1->CurrentValue)) {
			$sUrl .= "CI_RUN1=" . urlencode($this->CI_RUN1->CurrentValue);
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
			if ($isPost && isset($_POST["CI_RUN1"]))
				$arKeys[] = ew_StripSlashes($_POST["CI_RUN1"]);
			elseif (isset($_GET["CI_RUN1"]))
				$arKeys[] = ew_StripSlashes($_GET["CI_RUN1"]);
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
			$this->CI_RUN1->CurrentValue = $key;
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
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Telefono->setDbValue($rs->fields('Telefono'));
		$this->Celular->setDbValue($rs->fields('Celular'));
		$this->Direccion->setDbValue($rs->fields('Direccion'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->CI_RUN1->setDbValue($rs->fields('CI_RUN1'));
		$this->Expedido->setDbValue($rs->fields('Expedido'));
		$this->Apellido_Paterno1->setDbValue($rs->fields('Apellido_Paterno1'));
		$this->Apellido_Materno1->setDbValue($rs->fields('Apellido_Materno1'));
		$this->Nombres1->setDbValue($rs->fields('Nombres1'));
		$this->Direccion1->setDbValue($rs->fields('Direccion1'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// CI_RUN
		// Apellido_Paterno
		// Apellido_Materno
		// Nombres
		// Telefono
		// Celular
		// Direccion
		// Fiscalia_otro
		// Unidad_Organizacional
		// Cargo
		// Unidad
		// CI_RUN1
		// Expedido
		// Apellido_Paterno1
		// Apellido_Materno1
		// Nombres1
		// Direccion1
		// CI_RUN

		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewValue = ew_FormatNumber($this->CI_RUN->ViewValue, 0, 0, 0, 0);
		$this->CI_RUN->ViewCustomAttributes = "";

		// Apellido_Paterno
		$this->Apellido_Paterno->ViewValue = $this->Apellido_Paterno->CurrentValue;
		$this->Apellido_Paterno->ViewCustomAttributes = "";

		// Apellido_Materno
		$this->Apellido_Materno->ViewValue = $this->Apellido_Materno->CurrentValue;
		$this->Apellido_Materno->ViewCustomAttributes = "";

		// Nombres
		$this->Nombres->ViewValue = $this->Nombres->CurrentValue;
		$this->Nombres->ViewCustomAttributes = "";

		// Telefono
		$this->Telefono->ViewValue = $this->Telefono->CurrentValue;
		$this->Telefono->ViewCustomAttributes = "";

		// Celular
		$this->Celular->ViewValue = $this->Celular->CurrentValue;
		$this->Celular->ViewCustomAttributes = "";

		// Direccion
		$this->Direccion->ViewValue = $this->Direccion->CurrentValue;
		$this->Direccion->ViewCustomAttributes = "";

		// Fiscalia_otro
		$this->Fiscalia_otro->ViewValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Cargo
		if ($this->Cargo->VirtualValue <> "") {
			$this->Cargo->ViewValue = $this->Cargo->VirtualValue;
		} else {
		if (strval($this->Cargo->CurrentValue) <> "") {
			$sFilterWrk = "`Cargo`" . ew_SearchString("=", $this->Cargo->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Cargo`, `Cargo` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_cargos`";
		$sWhereWrk = "";
		$this->Cargo->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Cargo, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Cargo->ViewValue = $this->Cargo->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Cargo->ViewValue = $this->Cargo->CurrentValue;
			}
		} else {
			$this->Cargo->ViewValue = NULL;
		}
		}
		$this->Cargo->ViewCustomAttributes = "";

		// Unidad
		if ($this->Unidad->VirtualValue <> "") {
			$this->Unidad->ViewValue = $this->Unidad->VirtualValue;
		} else {
		if (strval($this->Unidad->CurrentValue) <> "") {
			$sFilterWrk = "`Unidad`" . ew_SearchString("=", $this->Unidad->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `Unidad`, `Unidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_unidad`";
		$sWhereWrk = "";
		$this->Unidad->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Unidad, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Unidad->ViewValue = $this->Unidad->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Unidad->ViewValue = $this->Unidad->CurrentValue;
			}
		} else {
			$this->Unidad->ViewValue = NULL;
		}
		}
		$this->Unidad->ViewCustomAttributes = "";

		// CI_RUN1
		$this->CI_RUN1->ViewValue = $this->CI_RUN1->CurrentValue;
		$this->CI_RUN1->ViewCustomAttributes = "";

		// Expedido
		if (strval($this->Expedido->CurrentValue) <> "") {
			$this->Expedido->ViewValue = $this->Expedido->OptionCaption($this->Expedido->CurrentValue);
		} else {
			$this->Expedido->ViewValue = NULL;
		}
		$this->Expedido->ViewCustomAttributes = "";

		// Apellido_Paterno1
		$this->Apellido_Paterno1->ViewValue = $this->Apellido_Paterno1->CurrentValue;
		$this->Apellido_Paterno1->ViewCustomAttributes = "";

		// Apellido_Materno1
		$this->Apellido_Materno1->ViewValue = $this->Apellido_Materno1->CurrentValue;
		$this->Apellido_Materno1->ViewCustomAttributes = "";

		// Nombres1
		$this->Nombres1->ViewValue = $this->Nombres1->CurrentValue;
		$this->Nombres1->ViewCustomAttributes = "";

		// Direccion1
		$this->Direccion1->ViewValue = $this->Direccion1->CurrentValue;
		$this->Direccion1->ViewCustomAttributes = "";

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

		// Telefono
		$this->Telefono->LinkCustomAttributes = "";
		$this->Telefono->HrefValue = "";
		$this->Telefono->TooltipValue = "";

		// Celular
		$this->Celular->LinkCustomAttributes = "";
		$this->Celular->HrefValue = "";
		$this->Celular->TooltipValue = "";

		// Direccion
		$this->Direccion->LinkCustomAttributes = "";
		$this->Direccion->HrefValue = "";
		$this->Direccion->TooltipValue = "";

		// Fiscalia_otro
		$this->Fiscalia_otro->LinkCustomAttributes = "";
		$this->Fiscalia_otro->HrefValue = "";
		$this->Fiscalia_otro->TooltipValue = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->LinkCustomAttributes = "";
		$this->Unidad_Organizacional->HrefValue = "";
		$this->Unidad_Organizacional->TooltipValue = "";

		// Cargo
		$this->Cargo->LinkCustomAttributes = "";
		$this->Cargo->HrefValue = "";
		$this->Cargo->TooltipValue = "";

		// Unidad
		$this->Unidad->LinkCustomAttributes = "";
		$this->Unidad->HrefValue = "";
		$this->Unidad->TooltipValue = "";

		// CI_RUN1
		$this->CI_RUN1->LinkCustomAttributes = "";
		$this->CI_RUN1->HrefValue = "";
		$this->CI_RUN1->TooltipValue = "";

		// Expedido
		$this->Expedido->LinkCustomAttributes = "";
		$this->Expedido->HrefValue = "";
		$this->Expedido->TooltipValue = "";

		// Apellido_Paterno1
		$this->Apellido_Paterno1->LinkCustomAttributes = "";
		$this->Apellido_Paterno1->HrefValue = "";
		$this->Apellido_Paterno1->TooltipValue = "";

		// Apellido_Materno1
		$this->Apellido_Materno1->LinkCustomAttributes = "";
		$this->Apellido_Materno1->HrefValue = "";
		$this->Apellido_Materno1->TooltipValue = "";

		// Nombres1
		$this->Nombres1->LinkCustomAttributes = "";
		$this->Nombres1->HrefValue = "";
		$this->Nombres1->TooltipValue = "";

		// Direccion1
		$this->Direccion1->LinkCustomAttributes = "";
		$this->Direccion1->HrefValue = "";
		$this->Direccion1->TooltipValue = "";

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

		// Telefono
		$this->Telefono->EditAttrs["class"] = "form-control";
		$this->Telefono->EditCustomAttributes = "";
		$this->Telefono->EditValue = $this->Telefono->CurrentValue;
		$this->Telefono->PlaceHolder = ew_RemoveHtml($this->Telefono->FldCaption());

		// Celular
		$this->Celular->EditAttrs["class"] = "form-control";
		$this->Celular->EditCustomAttributes = "";
		$this->Celular->EditValue = $this->Celular->CurrentValue;
		$this->Celular->PlaceHolder = ew_RemoveHtml($this->Celular->FldCaption());

		// Direccion
		$this->Direccion->EditAttrs["class"] = "form-control";
		$this->Direccion->EditCustomAttributes = "";
		$this->Direccion->EditValue = $this->Direccion->CurrentValue;
		$this->Direccion->PlaceHolder = ew_RemoveHtml($this->Direccion->FldCaption());

		// Fiscalia_otro
		$this->Fiscalia_otro->EditAttrs["class"] = "form-control";
		$this->Fiscalia_otro->EditCustomAttributes = "";
		$this->Fiscalia_otro->EditValue = $this->Fiscalia_otro->CurrentValue;
		$this->Fiscalia_otro->PlaceHolder = ew_RemoveHtml($this->Fiscalia_otro->FldCaption());

		// Unidad_Organizacional
		$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
		$this->Unidad_Organizacional->EditCustomAttributes = "";

		// Cargo
		$this->Cargo->EditAttrs["class"] = "form-control";
		$this->Cargo->EditCustomAttributes = "";

		// Unidad
		$this->Unidad->EditAttrs["class"] = "form-control";
		$this->Unidad->EditCustomAttributes = "";

		// CI_RUN1
		$this->CI_RUN1->EditAttrs["class"] = "form-control";
		$this->CI_RUN1->EditCustomAttributes = "";
		$this->CI_RUN1->EditValue = $this->CI_RUN1->CurrentValue;
		$this->CI_RUN1->ViewCustomAttributes = "";

		// Expedido
		$this->Expedido->EditAttrs["class"] = "form-control";
		$this->Expedido->EditCustomAttributes = "";
		$this->Expedido->EditValue = $this->Expedido->Options(TRUE);

		// Apellido_Paterno1
		$this->Apellido_Paterno1->EditAttrs["class"] = "form-control";
		$this->Apellido_Paterno1->EditCustomAttributes = "";
		$this->Apellido_Paterno1->EditValue = $this->Apellido_Paterno1->CurrentValue;
		$this->Apellido_Paterno1->PlaceHolder = ew_RemoveHtml($this->Apellido_Paterno1->FldCaption());

		// Apellido_Materno1
		$this->Apellido_Materno1->EditAttrs["class"] = "form-control";
		$this->Apellido_Materno1->EditCustomAttributes = "";
		$this->Apellido_Materno1->EditValue = $this->Apellido_Materno1->CurrentValue;
		$this->Apellido_Materno1->PlaceHolder = ew_RemoveHtml($this->Apellido_Materno1->FldCaption());

		// Nombres1
		$this->Nombres1->EditAttrs["class"] = "form-control";
		$this->Nombres1->EditCustomAttributes = "";
		$this->Nombres1->EditValue = $this->Nombres1->CurrentValue;
		$this->Nombres1->PlaceHolder = ew_RemoveHtml($this->Nombres1->FldCaption());

		// Direccion1
		$this->Direccion1->EditAttrs["class"] = "form-control";
		$this->Direccion1->EditCustomAttributes = "";
		$this->Direccion1->EditValue = $this->Direccion1->CurrentValue;
		$this->Direccion1->PlaceHolder = ew_RemoveHtml($this->Direccion1->FldCaption());

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
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Telefono->Exportable) $Doc->ExportCaption($this->Telefono);
					if ($this->Celular->Exportable) $Doc->ExportCaption($this->Celular);
					if ($this->Direccion->Exportable) $Doc->ExportCaption($this->Direccion);
					if ($this->Fiscalia_otro->Exportable) $Doc->ExportCaption($this->Fiscalia_otro);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Cargo->Exportable) $Doc->ExportCaption($this->Cargo);
					if ($this->Unidad->Exportable) $Doc->ExportCaption($this->Unidad);
					if ($this->CI_RUN1->Exportable) $Doc->ExportCaption($this->CI_RUN1);
					if ($this->Expedido->Exportable) $Doc->ExportCaption($this->Expedido);
					if ($this->Apellido_Paterno1->Exportable) $Doc->ExportCaption($this->Apellido_Paterno1);
					if ($this->Apellido_Materno1->Exportable) $Doc->ExportCaption($this->Apellido_Materno1);
					if ($this->Nombres1->Exportable) $Doc->ExportCaption($this->Nombres1);
					if ($this->Direccion1->Exportable) $Doc->ExportCaption($this->Direccion1);
				} else {
					if ($this->CI_RUN->Exportable) $Doc->ExportCaption($this->CI_RUN);
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Telefono->Exportable) $Doc->ExportCaption($this->Telefono);
					if ($this->Celular->Exportable) $Doc->ExportCaption($this->Celular);
					if ($this->Direccion->Exportable) $Doc->ExportCaption($this->Direccion);
					if ($this->Fiscalia_otro->Exportable) $Doc->ExportCaption($this->Fiscalia_otro);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Cargo->Exportable) $Doc->ExportCaption($this->Cargo);
					if ($this->Unidad->Exportable) $Doc->ExportCaption($this->Unidad);
					if ($this->CI_RUN1->Exportable) $Doc->ExportCaption($this->CI_RUN1);
					if ($this->Expedido->Exportable) $Doc->ExportCaption($this->Expedido);
					if ($this->Apellido_Paterno1->Exportable) $Doc->ExportCaption($this->Apellido_Paterno1);
					if ($this->Apellido_Materno1->Exportable) $Doc->ExportCaption($this->Apellido_Materno1);
					if ($this->Nombres1->Exportable) $Doc->ExportCaption($this->Nombres1);
					if ($this->Direccion1->Exportable) $Doc->ExportCaption($this->Direccion1);
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
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Telefono->Exportable) $Doc->ExportField($this->Telefono);
						if ($this->Celular->Exportable) $Doc->ExportField($this->Celular);
						if ($this->Direccion->Exportable) $Doc->ExportField($this->Direccion);
						if ($this->Fiscalia_otro->Exportable) $Doc->ExportField($this->Fiscalia_otro);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Cargo->Exportable) $Doc->ExportField($this->Cargo);
						if ($this->Unidad->Exportable) $Doc->ExportField($this->Unidad);
						if ($this->CI_RUN1->Exportable) $Doc->ExportField($this->CI_RUN1);
						if ($this->Expedido->Exportable) $Doc->ExportField($this->Expedido);
						if ($this->Apellido_Paterno1->Exportable) $Doc->ExportField($this->Apellido_Paterno1);
						if ($this->Apellido_Materno1->Exportable) $Doc->ExportField($this->Apellido_Materno1);
						if ($this->Nombres1->Exportable) $Doc->ExportField($this->Nombres1);
						if ($this->Direccion1->Exportable) $Doc->ExportField($this->Direccion1);
					} else {
						if ($this->CI_RUN->Exportable) $Doc->ExportField($this->CI_RUN);
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Telefono->Exportable) $Doc->ExportField($this->Telefono);
						if ($this->Celular->Exportable) $Doc->ExportField($this->Celular);
						if ($this->Direccion->Exportable) $Doc->ExportField($this->Direccion);
						if ($this->Fiscalia_otro->Exportable) $Doc->ExportField($this->Fiscalia_otro);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Cargo->Exportable) $Doc->ExportField($this->Cargo);
						if ($this->Unidad->Exportable) $Doc->ExportField($this->Unidad);
						if ($this->CI_RUN1->Exportable) $Doc->ExportField($this->CI_RUN1);
						if ($this->Expedido->Exportable) $Doc->ExportField($this->Expedido);
						if ($this->Apellido_Paterno1->Exportable) $Doc->ExportField($this->Apellido_Paterno1);
						if ($this->Apellido_Materno1->Exportable) $Doc->ExportField($this->Apellido_Materno1);
						if ($this->Nombres1->Exportable) $Doc->ExportField($this->Nombres1);
						if ($this->Direccion1->Exportable) $Doc->ExportField($this->Direccion1);
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
