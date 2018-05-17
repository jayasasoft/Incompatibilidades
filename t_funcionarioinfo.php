<?php

// Global variable for table object
$t_funcionario = NULL;

//
// Table class for t_funcionario
//
class ct_funcionario extends cTable {
	var $Id;
	var $CI_RUN;
	var $Expedido;
	var $Apellido_Paterno;
	var $Apellido_Materno;
	var $Nombres;
	var $Fecha_Nacimiento;
	var $Estado_Civil;
	var $Direccion;
	var $Telefono;
	var $Celular;
	var $Fiscalia_otro;
	var $Unidad_Organizacional;
	var $Unidad;
	var $Cargo;
	var $Fecha_registro;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_funcionario';
		$this->TableName = 't_funcionario';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_funcionario`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = TRUE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// Id
		$this->Id = new cField('t_funcionario', 't_funcionario', 'x_Id', 'Id', '`Id`', '`Id`', 3, -1, FALSE, '`Id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->Id->Sortable = FALSE; // Allow sort
		$this->Id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Id'] = &$this->Id;

		// CI_RUN
		$this->CI_RUN = new cField('t_funcionario', 't_funcionario', 'x_CI_RUN', 'CI_RUN', '`CI_RUN`', '`CI_RUN`', 200, -1, FALSE, '`CI_RUN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CI_RUN->Sortable = TRUE; // Allow sort
		$this->CI_RUN->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CI_RUN'] = &$this->CI_RUN;

		// Expedido
		$this->Expedido = new cField('t_funcionario', 't_funcionario', 'x_Expedido', 'Expedido', '`Expedido`', '`Expedido`', 200, -1, FALSE, '`Expedido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Expedido->Sortable = TRUE; // Allow sort
		$this->fields['Expedido'] = &$this->Expedido;

		// Apellido_Paterno
		$this->Apellido_Paterno = new cField('t_funcionario', 't_funcionario', 'x_Apellido_Paterno', 'Apellido_Paterno', '`Apellido_Paterno`', '`Apellido_Paterno`', 200, -1, FALSE, '`Apellido_Paterno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Paterno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Paterno'] = &$this->Apellido_Paterno;

		// Apellido_Materno
		$this->Apellido_Materno = new cField('t_funcionario', 't_funcionario', 'x_Apellido_Materno', 'Apellido_Materno', '`Apellido_Materno`', '`Apellido_Materno`', 200, -1, FALSE, '`Apellido_Materno`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Apellido_Materno->Sortable = TRUE; // Allow sort
		$this->fields['Apellido_Materno'] = &$this->Apellido_Materno;

		// Nombres
		$this->Nombres = new cField('t_funcionario', 't_funcionario', 'x_Nombres', 'Nombres', '`Nombres`', '`Nombres`', 200, -1, FALSE, '`Nombres`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres->Sortable = TRUE; // Allow sort
		$this->fields['Nombres'] = &$this->Nombres;

		// Fecha_Nacimiento
		$this->Fecha_Nacimiento = new cField('t_funcionario', 't_funcionario', 'x_Fecha_Nacimiento', 'Fecha_Nacimiento', '`Fecha_Nacimiento`', ew_CastDateFieldForLike('`Fecha_Nacimiento`', 13, "DB"), 133, 13, FALSE, '`Fecha_Nacimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Fecha_Nacimiento->Sortable = TRUE; // Allow sort
		$this->Fecha_Nacimiento->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectShortDateMDY"));
		$this->fields['Fecha_Nacimiento'] = &$this->Fecha_Nacimiento;

		// Estado_Civil
		$this->Estado_Civil = new cField('t_funcionario', 't_funcionario', 'x_Estado_Civil', 'Estado_Civil', '`Estado_Civil`', '`Estado_Civil`', 200, -1, FALSE, '`Estado_Civil`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->Estado_Civil->Sortable = TRUE; // Allow sort
		$this->Estado_Civil->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Estado_Civil->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->Estado_Civil->OptionCount = 5;
		$this->fields['Estado_Civil'] = &$this->Estado_Civil;

		// Direccion
		$this->Direccion = new cField('t_funcionario', 't_funcionario', 'x_Direccion', 'Direccion', '`Direccion`', '`Direccion`', 200, -1, FALSE, '`Direccion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Direccion->Sortable = TRUE; // Allow sort
		$this->fields['Direccion'] = &$this->Direccion;

		// Telefono
		$this->Telefono = new cField('t_funcionario', 't_funcionario', 'x_Telefono', 'Telefono', '`Telefono`', '`Telefono`', 200, -1, FALSE, '`Telefono`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Telefono->Sortable = TRUE; // Allow sort
		$this->Telefono->FldDefaultErrMsg = str_replace(array("%1", "%2"), array("10000000", "80000000"), $Language->Phrase("IncorrectRange"));
		$this->fields['Telefono'] = &$this->Telefono;

		// Celular
		$this->Celular = new cField('t_funcionario', 't_funcionario', 'x_Celular', 'Celular', '`Celular`', '`Celular`', 200, -1, FALSE, '`Celular`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Celular->Sortable = TRUE; // Allow sort
		$this->Celular->FldDefaultErrMsg = str_replace(array("%1", "%2"), array("10000000", "80000000"), $Language->Phrase("IncorrectRange"));
		$this->fields['Celular'] = &$this->Celular;

		// Fiscalia_otro
		$this->Fiscalia_otro = new cField('t_funcionario', 't_funcionario', 'x_Fiscalia_otro', 'Fiscalia_otro', '`Fiscalia_otro`', '`Fiscalia_otro`', 200, -1, FALSE, '`EV__Fiscalia_otro`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->Fiscalia_otro->Sortable = TRUE; // Allow sort
		$this->Fiscalia_otro->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->Fiscalia_otro->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['Fiscalia_otro'] = &$this->Fiscalia_otro;

		// Unidad_Organizacional
		$this->Unidad_Organizacional = new cField('t_funcionario', 't_funcionario', 'x_Unidad_Organizacional', 'Unidad_Organizacional', '`Unidad_Organizacional`', '`Unidad_Organizacional`', 200, -1, FALSE, '`Unidad_Organizacional`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Unidad_Organizacional->Sortable = TRUE; // Allow sort
		$this->fields['Unidad_Organizacional'] = &$this->Unidad_Organizacional;

		// Unidad
		$this->Unidad = new cField('t_funcionario', 't_funcionario', 'x_Unidad', 'Unidad', '`Unidad`', '`Unidad`', 200, -1, FALSE, '`Unidad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Unidad->Sortable = TRUE; // Allow sort
		$this->fields['Unidad'] = &$this->Unidad;

		// Cargo
		$this->Cargo = new cField('t_funcionario', 't_funcionario', 'x_Cargo', 'Cargo', '`Cargo`', '`Cargo`', 200, -1, FALSE, '`Cargo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Cargo->Sortable = TRUE; // Allow sort
		$this->fields['Cargo'] = &$this->Cargo;

		// Fecha_registro
		$this->Fecha_registro = new cField('t_funcionario', 't_funcionario', 'x_Fecha_registro', 'Fecha_registro', '`Fecha_registro`', ew_CastDateFieldForLike('`Fecha_registro`', 0, "DB"), 133, 0, FALSE, '`Fecha_registro`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Fecha_registro->Sortable = FALSE; // Allow sort
		$this->Fecha_registro->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['Fecha_registro'] = &$this->Fecha_registro;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "t_conyugue") {
			$sDetailUrl = $GLOBALS["t_conyugue"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_pa_consanguinidad") {
			$sDetailUrl = $GLOBALS["t_pa_consanguinidad"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_pa_afinidad") {
			$sDetailUrl = $GLOBALS["t_pa_afinidad"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_re_adopcion") {
			$sDetailUrl = $GLOBALS["t_re_adopcion"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_mp_si_no") {
			$sDetailUrl = $GLOBALS["t_mp_si_no"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_parientes_mp") {
			$sDetailUrl = $GLOBALS["t_parientes_mp"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_actiividades_remuneradas") {
			$sDetailUrl = $GLOBALS["t_actiividades_remuneradas"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($this->getCurrentDetailTable() == "t_salario") {
			$sDetailUrl = $GLOBALS["t_salario"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_Id=" . urlencode($this->Id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "t_funcionariolist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_funcionario`";
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
			"SELECT *, (SELECT CONCAT(`Fiscalia`,'" . ew_ValueSeparator(1, $this->Fiscalia_otro) . "',`Unidad_Organizacional`,'" . ew_ValueSeparator(2, $this->Fiscalia_otro) . "',`Unidad`,'" . ew_ValueSeparator(3, $this->Fiscalia_otro) . "',`Cargo`) FROM `seleccion_cargos` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`Fiscalia` = `t_funcionario`.`Fiscalia_otro` LIMIT 1) AS `EV__Fiscalia_otro` FROM `t_funcionario`" .
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
		$Lista = CurrentUserID();
        $dato = CurrentUserLevel() ;
        $sWhere='';
        if ($dato !=  '-1') 
        {        
        $this->TableFilter = "CI_RUN = $Lista ";
        ew_AddFilter($sWhere, $this->TableFilter);
		}
      return $sWhere ;
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
		if ($this->Fiscalia_otro->AdvancedSearch->SearchValue <> "" ||
			$this->Fiscalia_otro->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->Fiscalia_otro->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->Fiscalia_otro->FldVirtualExpression . " ") !== FALSE)
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

		// Cascade Update detail table 't_pa_consanguinidad'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'Id'
			$bCascadeUpdate = TRUE;
			$rscascade['Id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_pa_consanguinidad"])) $GLOBALS["t_pa_consanguinidad"] = new ct_pa_consanguinidad();
			$rswrk = $GLOBALS["t_pa_consanguinidad"]->LoadRs("`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_pa_consanguinidad"]->Update($rscascade, "`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 't_pa_afinidad'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'Id'
			$bCascadeUpdate = TRUE;
			$rscascade['Id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_pa_afinidad"])) $GLOBALS["t_pa_afinidad"] = new ct_pa_afinidad();
			$rswrk = $GLOBALS["t_pa_afinidad"]->LoadRs("`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_pa_afinidad"]->Update($rscascade, "`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 't_re_adopcion'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'id'
			$bCascadeUpdate = TRUE;
			$rscascade['id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_re_adopcion"])) $GLOBALS["t_re_adopcion"] = new ct_re_adopcion();
			$rswrk = $GLOBALS["t_re_adopcion"]->LoadRs("`id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_re_adopcion"]->Update($rscascade, "`id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 't_mp_si_no'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'Id'
			$bCascadeUpdate = TRUE;
			$rscascade['Id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_mp_si_no"])) $GLOBALS["t_mp_si_no"] = new ct_mp_si_no();
			$rswrk = $GLOBALS["t_mp_si_no"]->LoadRs("`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_mp_si_no"]->Update($rscascade, "`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 't_parientes_mp'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'Id'
			$bCascadeUpdate = TRUE;
			$rscascade['Id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_parientes_mp"])) $GLOBALS["t_parientes_mp"] = new ct_parientes_mp();
			$rswrk = $GLOBALS["t_parientes_mp"]->LoadRs("`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_parientes_mp"]->Update($rscascade, "`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 't_actiividades_remuneradas'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'Id'
			$bCascadeUpdate = TRUE;
			$rscascade['Id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_actiividades_remuneradas"])) $GLOBALS["t_actiividades_remuneradas"] = new ct_actiividades_remuneradas();
			$rswrk = $GLOBALS["t_actiividades_remuneradas"]->LoadRs("`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_actiividades_remuneradas"]->Update($rscascade, "`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}

		// Cascade Update detail table 't_salario'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['Id']) && $rsold['Id'] <> $rs['Id'])) { // Update detail field 'Id'
			$bCascadeUpdate = TRUE;
			$rscascade['Id'] = $rs['Id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["t_salario"])) $GLOBALS["t_salario"] = new ct_salario();
			$rswrk = $GLOBALS["t_salario"]->LoadRs("`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$GLOBALS["t_salario"]->Update($rscascade, "`Id` = " . ew_QuotedValue($rsold['Id'], EW_DATATYPE_NUMBER, 'DB'), $rswrk->fields);
				$rswrk->MoveNext();
			}
		}
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('Id', $rs))
				ew_AddFilter($where, ew_QuotedName('Id', $this->DBID) . '=' . ew_QuotedValue($rs['Id'], $this->Id->FldDataType, $this->DBID));
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

		// Cascade delete detail table 't_pa_consanguinidad'
		if (!isset($GLOBALS["t_pa_consanguinidad"])) $GLOBALS["t_pa_consanguinidad"] = new ct_pa_consanguinidad();
		$rscascade = $GLOBALS["t_pa_consanguinidad"]->LoadRs("`Id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_pa_consanguinidad"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 't_pa_afinidad'
		if (!isset($GLOBALS["t_pa_afinidad"])) $GLOBALS["t_pa_afinidad"] = new ct_pa_afinidad();
		$rscascade = $GLOBALS["t_pa_afinidad"]->LoadRs("`Id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_pa_afinidad"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 't_re_adopcion'
		if (!isset($GLOBALS["t_re_adopcion"])) $GLOBALS["t_re_adopcion"] = new ct_re_adopcion();
		$rscascade = $GLOBALS["t_re_adopcion"]->LoadRs("`id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_re_adopcion"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 't_mp_si_no'
		if (!isset($GLOBALS["t_mp_si_no"])) $GLOBALS["t_mp_si_no"] = new ct_mp_si_no();
		$rscascade = $GLOBALS["t_mp_si_no"]->LoadRs("`Id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_mp_si_no"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 't_parientes_mp'
		if (!isset($GLOBALS["t_parientes_mp"])) $GLOBALS["t_parientes_mp"] = new ct_parientes_mp();
		$rscascade = $GLOBALS["t_parientes_mp"]->LoadRs("`Id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_parientes_mp"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 't_actiividades_remuneradas'
		if (!isset($GLOBALS["t_actiividades_remuneradas"])) $GLOBALS["t_actiividades_remuneradas"] = new ct_actiividades_remuneradas();
		$rscascade = $GLOBALS["t_actiividades_remuneradas"]->LoadRs("`Id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_actiividades_remuneradas"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}

		// Cascade delete detail table 't_salario'
		if (!isset($GLOBALS["t_salario"])) $GLOBALS["t_salario"] = new ct_salario();
		$rscascade = $GLOBALS["t_salario"]->LoadRs("`Id` = " . ew_QuotedValue($rs['Id'], EW_DATATYPE_NUMBER, "DB")); 
		while ($rscascade && !$rscascade->EOF) {
			$GLOBALS["t_salario"]->Delete($rscascade->fields);
			$rscascade->MoveNext();
		}
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`Id` = @Id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->Id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@Id@", ew_AdjustSql($this->Id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "t_funcionariolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_funcionariolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_funcionarioview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_funcionarioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_funcionarioadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_funcionarioadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_funcionarioedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_funcionarioedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_funcionarioadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_funcionarioadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_funcionariodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "Id:" . ew_VarToJson($this->Id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->Id->CurrentValue)) {
			$sUrl .= "Id=" . urlencode($this->Id->CurrentValue);
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
			if ($isPost && isset($_POST["Id"]))
				$arKeys[] = ew_StripSlashes($_POST["Id"]);
			elseif (isset($_GET["Id"]))
				$arKeys[] = ew_StripSlashes($_GET["Id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
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
			$this->Id->CurrentValue = $key;
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
		$this->Id->setDbValue($rs->fields('Id'));
		$this->CI_RUN->setDbValue($rs->fields('CI_RUN'));
		$this->Expedido->setDbValue($rs->fields('Expedido'));
		$this->Apellido_Paterno->setDbValue($rs->fields('Apellido_Paterno'));
		$this->Apellido_Materno->setDbValue($rs->fields('Apellido_Materno'));
		$this->Nombres->setDbValue($rs->fields('Nombres'));
		$this->Fecha_Nacimiento->setDbValue($rs->fields('Fecha_Nacimiento'));
		$this->Estado_Civil->setDbValue($rs->fields('Estado_Civil'));
		$this->Direccion->setDbValue($rs->fields('Direccion'));
		$this->Telefono->setDbValue($rs->fields('Telefono'));
		$this->Celular->setDbValue($rs->fields('Celular'));
		$this->Fiscalia_otro->setDbValue($rs->fields('Fiscalia_otro'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Unidad->setDbValue($rs->fields('Unidad'));
		$this->Cargo->setDbValue($rs->fields('Cargo'));
		$this->Fecha_registro->setDbValue($rs->fields('Fecha_registro'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// Id

		$this->Id->CellCssStyle = "white-space: nowrap;";

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

		$this->Fecha_registro->CellCssStyle = "white-space: nowrap;";

		// Id
		$this->Id->ViewValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

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

		// Id
		$this->Id->LinkCustomAttributes = "";
		$this->Id->HrefValue = "";
		$this->Id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// Id
		$this->Id->EditAttrs["class"] = "form-control";
		$this->Id->EditCustomAttributes = "";
		$this->Id->EditValue = $this->Id->CurrentValue;
		$this->Id->ViewCustomAttributes = "";

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

		// Fecha_Nacimiento
		$this->Fecha_Nacimiento->EditAttrs["class"] = "form-control";
		$this->Fecha_Nacimiento->EditCustomAttributes = "";
		$this->Fecha_Nacimiento->EditValue = ew_FormatDateTime($this->Fecha_Nacimiento->CurrentValue, 13);
		$this->Fecha_Nacimiento->PlaceHolder = ew_RemoveHtml($this->Fecha_Nacimiento->FldCaption());

		// Estado_Civil
		$this->Estado_Civil->EditAttrs["class"] = "form-control";
		$this->Estado_Civil->EditCustomAttributes = "";
		$this->Estado_Civil->EditValue = $this->Estado_Civil->Options(TRUE);

		// Direccion
		$this->Direccion->EditAttrs["class"] = "form-control";
		$this->Direccion->EditCustomAttributes = "";
		$this->Direccion->EditValue = $this->Direccion->CurrentValue;
		$this->Direccion->PlaceHolder = ew_RemoveHtml($this->Direccion->FldCaption());

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

		// Fiscalia_otro
		$this->Fiscalia_otro->EditAttrs["class"] = "form-control";
		$this->Fiscalia_otro->EditCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
		$this->Unidad_Organizacional->EditCustomAttributes = "";
		$this->Unidad_Organizacional->EditValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

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

		// Fecha_registro
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
					if ($this->Fecha_Nacimiento->Exportable) $Doc->ExportCaption($this->Fecha_Nacimiento);
					if ($this->Estado_Civil->Exportable) $Doc->ExportCaption($this->Estado_Civil);
					if ($this->Direccion->Exportable) $Doc->ExportCaption($this->Direccion);
					if ($this->Telefono->Exportable) $Doc->ExportCaption($this->Telefono);
					if ($this->Celular->Exportable) $Doc->ExportCaption($this->Celular);
					if ($this->Fiscalia_otro->Exportable) $Doc->ExportCaption($this->Fiscalia_otro);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Unidad->Exportable) $Doc->ExportCaption($this->Unidad);
					if ($this->Cargo->Exportable) $Doc->ExportCaption($this->Cargo);
					if ($this->Fecha_registro->Exportable) $Doc->ExportCaption($this->Fecha_registro);
				} else {
					if ($this->CI_RUN->Exportable) $Doc->ExportCaption($this->CI_RUN);
					if ($this->Expedido->Exportable) $Doc->ExportCaption($this->Expedido);
					if ($this->Apellido_Paterno->Exportable) $Doc->ExportCaption($this->Apellido_Paterno);
					if ($this->Apellido_Materno->Exportable) $Doc->ExportCaption($this->Apellido_Materno);
					if ($this->Nombres->Exportable) $Doc->ExportCaption($this->Nombres);
					if ($this->Fecha_Nacimiento->Exportable) $Doc->ExportCaption($this->Fecha_Nacimiento);
					if ($this->Estado_Civil->Exportable) $Doc->ExportCaption($this->Estado_Civil);
					if ($this->Direccion->Exportable) $Doc->ExportCaption($this->Direccion);
					if ($this->Telefono->Exportable) $Doc->ExportCaption($this->Telefono);
					if ($this->Celular->Exportable) $Doc->ExportCaption($this->Celular);
					if ($this->Fiscalia_otro->Exportable) $Doc->ExportCaption($this->Fiscalia_otro);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Unidad->Exportable) $Doc->ExportCaption($this->Unidad);
					if ($this->Cargo->Exportable) $Doc->ExportCaption($this->Cargo);
					if ($this->Fecha_registro->Exportable) $Doc->ExportCaption($this->Fecha_registro);
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
						if ($this->Fecha_Nacimiento->Exportable) $Doc->ExportField($this->Fecha_Nacimiento);
						if ($this->Estado_Civil->Exportable) $Doc->ExportField($this->Estado_Civil);
						if ($this->Direccion->Exportable) $Doc->ExportField($this->Direccion);
						if ($this->Telefono->Exportable) $Doc->ExportField($this->Telefono);
						if ($this->Celular->Exportable) $Doc->ExportField($this->Celular);
						if ($this->Fiscalia_otro->Exportable) $Doc->ExportField($this->Fiscalia_otro);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Unidad->Exportable) $Doc->ExportField($this->Unidad);
						if ($this->Cargo->Exportable) $Doc->ExportField($this->Cargo);
						if ($this->Fecha_registro->Exportable) $Doc->ExportField($this->Fecha_registro);
					} else {
						if ($this->CI_RUN->Exportable) $Doc->ExportField($this->CI_RUN);
						if ($this->Expedido->Exportable) $Doc->ExportField($this->Expedido);
						if ($this->Apellido_Paterno->Exportable) $Doc->ExportField($this->Apellido_Paterno);
						if ($this->Apellido_Materno->Exportable) $Doc->ExportField($this->Apellido_Materno);
						if ($this->Nombres->Exportable) $Doc->ExportField($this->Nombres);
						if ($this->Fecha_Nacimiento->Exportable) $Doc->ExportField($this->Fecha_Nacimiento);
						if ($this->Estado_Civil->Exportable) $Doc->ExportField($this->Estado_Civil);
						if ($this->Direccion->Exportable) $Doc->ExportField($this->Direccion);
						if ($this->Telefono->Exportable) $Doc->ExportField($this->Telefono);
						if ($this->Celular->Exportable) $Doc->ExportField($this->Celular);
						if ($this->Fiscalia_otro->Exportable) $Doc->ExportField($this->Fiscalia_otro);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Unidad->Exportable) $Doc->ExportField($this->Unidad);
						if ($this->Cargo->Exportable) $Doc->ExportField($this->Cargo);
						if ($this->Fecha_registro->Exportable) $Doc->ExportField($this->Fecha_registro);
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
		if (preg_match('/^x(\d)*_Fiscalia_otro$/', $id)) {
			$conn = &$this->Connection();
			$sSqlWrk = "SELECT `Unidad_Organizacional` AS FIELD0, `Unidad` AS FIELD1, `Cargo` AS FIELD2 FROM `seleccion_cargos`";
			$sWhereWrk = "(`Fiscalia` = " . ew_QuotedValue($val, EW_DATATYPE_STRING, $this->DBID) . ")";
			$this->Fiscalia_otro->LookupFilters = array("dx1" => '`Fiscalia`', "dx2" => '`Unidad_Organizacional`', "dx3" => '`Unidad`', "dx4" => '`Cargo`');
			$this->Lookup_Selecting($this->Fiscalia_otro, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($rs = ew_LoadRecordset($sSqlWrk, $conn)) {
				while ($rs && !$rs->EOF) {
					$ar = array();
					$this->Unidad_Organizacional->setDbValue($rs->fields[0]);
					$this->Unidad->setDbValue($rs->fields[1]);
					$this->Cargo->setDbValue($rs->fields[2]);
					$this->RowType == EW_ROWTYPE_EDIT;
					$this->RenderEditRow();
					$ar[] = ($this->Unidad_Organizacional->AutoFillOriginalValue) ? $this->Unidad_Organizacional->CurrentValue : $this->Unidad_Organizacional->EditValue;
					$ar[] = ($this->Unidad->AutoFillOriginalValue) ? $this->Unidad->CurrentValue : $this->Unidad->EditValue;
					$ar[] = ($this->Cargo->AutoFillOriginalValue) ? $this->Cargo->CurrentValue : $this->Cargo->EditValue;
					$rowcnt += 1;
					$rsarr[] = $ar;
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}

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
