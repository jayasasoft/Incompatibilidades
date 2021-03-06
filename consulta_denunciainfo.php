<?php

// Global variable for table object
$consulta_denuncia = NULL;

//
// Table class for consulta_denuncia
//
class cconsulta_denuncia extends cTable {
	var $CI_RUN;
	var $Nombres_Apellidos;
	var $Unidad_Organizacional;
	var $Detalles;
	var $Fecha_denuncia;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'consulta_denuncia';
		$this->TableName = 'consulta_denuncia';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`consulta_denuncia`";
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
		$this->BasicSearch->TypeDefault = "OR";

		// CI_RUN
		$this->CI_RUN = new cField('consulta_denuncia', 'consulta_denuncia', 'x_CI_RUN', 'CI_RUN', '`CI_RUN`', '`CI_RUN`', 200, -1, FALSE, '`CI_RUN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CI_RUN->Sortable = TRUE; // Allow sort
		$this->fields['CI_RUN'] = &$this->CI_RUN;

		// Nombres_Apellidos
		$this->Nombres_Apellidos = new cField('consulta_denuncia', 'consulta_denuncia', 'x_Nombres_Apellidos', 'Nombres_Apellidos', '`Nombres_Apellidos`', '`Nombres_Apellidos`', 200, -1, FALSE, '`Nombres_Apellidos`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Nombres_Apellidos->Sortable = FALSE; // Allow sort
		$this->fields['Nombres_Apellidos'] = &$this->Nombres_Apellidos;

		// Unidad_Organizacional
		$this->Unidad_Organizacional = new cField('consulta_denuncia', 'consulta_denuncia', 'x_Unidad_Organizacional', 'Unidad_Organizacional', '`Unidad_Organizacional`', '`Unidad_Organizacional`', 200, -1, FALSE, '`Unidad_Organizacional`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Unidad_Organizacional->Sortable = FALSE; // Allow sort
		$this->fields['Unidad_Organizacional'] = &$this->Unidad_Organizacional;

		// Detalles
		$this->Detalles = new cField('consulta_denuncia', 'consulta_denuncia', 'x_Detalles', 'Detalles', '`Detalles`', '`Detalles`', 201, -1, FALSE, '`Detalles`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->Detalles->Sortable = FALSE; // Allow sort
		$this->fields['Detalles'] = &$this->Detalles;

		// Fecha_denuncia
		$this->Fecha_denuncia = new cField('consulta_denuncia', 'consulta_denuncia', 'x_Fecha_denuncia', 'Fecha_denuncia', '`Fecha_denuncia`', ew_CastDateFieldForLike('`Fecha_denuncia`', 0, "DB"), 133, 0, FALSE, '`Fecha_denuncia`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Fecha_denuncia->Sortable = FALSE; // Allow sort
		$this->Fecha_denuncia->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['Fecha_denuncia'] = &$this->Fecha_denuncia;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`consulta_denuncia`";
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
			if (array_key_exists('CI_RUN', $rs))
				ew_AddFilter($where, ew_QuotedName('CI_RUN', $this->DBID) . '=' . ew_QuotedValue($rs['CI_RUN'], $this->CI_RUN->FldDataType, $this->DBID));
			if (array_key_exists('Nombres_Apellidos', $rs))
				ew_AddFilter($where, ew_QuotedName('Nombres_Apellidos', $this->DBID) . '=' . ew_QuotedValue($rs['Nombres_Apellidos'], $this->Nombres_Apellidos->FldDataType, $this->DBID));
			if (array_key_exists('Fecha_denuncia', $rs))
				ew_AddFilter($where, ew_QuotedName('Fecha_denuncia', $this->DBID) . '=' . ew_QuotedValue($rs['Fecha_denuncia'], $this->Fecha_denuncia->FldDataType, $this->DBID));
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
		return "`CI_RUN` = '@CI_RUN@' AND `Nombres_Apellidos` = '@Nombres_Apellidos@' AND `Fecha_denuncia` = '@Fecha_denuncia@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@CI_RUN@", ew_AdjustSql($this->CI_RUN->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@Nombres_Apellidos@", ew_AdjustSql($this->Nombres_Apellidos->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		$sKeyFilter = str_replace("@Fecha_denuncia@", ew_AdjustSql(ew_UnFormatDateTime($this->Fecha_denuncia->CurrentValue,0), $this->DBID), $sKeyFilter); // Replace key value
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
			return "consulta_denuncialist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "consulta_denuncialist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("consulta_denunciaview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("consulta_denunciaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "consulta_denunciaadd.php?" . $this->UrlParm($parm);
		else
			$url = "consulta_denunciaadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("consulta_denunciaedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("consulta_denunciaadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("consulta_denunciadelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "CI_RUN:" . ew_VarToJson($this->CI_RUN->CurrentValue, "string", "'");
		$json .= ",Nombres_Apellidos:" . ew_VarToJson($this->Nombres_Apellidos->CurrentValue, "string", "'");
		$json .= ",Fecha_denuncia:" . ew_VarToJson($this->Fecha_denuncia->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->CI_RUN->CurrentValue)) {
			$sUrl .= "CI_RUN=" . urlencode($this->CI_RUN->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->Nombres_Apellidos->CurrentValue)) {
			$sUrl .= "&Nombres_Apellidos=" . urlencode($this->Nombres_Apellidos->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->Fecha_denuncia->CurrentValue)) {
			$sUrl .= "&Fecha_denuncia=" . urlencode($this->Fecha_denuncia->CurrentValue);
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
			if ($isPost && isset($_POST["CI_RUN"]))
				$arKey[] = ew_StripSlashes($_POST["CI_RUN"]);
			elseif (isset($_GET["CI_RUN"]))
				$arKey[] = ew_StripSlashes($_GET["CI_RUN"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["Nombres_Apellidos"]))
				$arKey[] = ew_StripSlashes($_POST["Nombres_Apellidos"]);
			elseif (isset($_GET["Nombres_Apellidos"]))
				$arKey[] = ew_StripSlashes($_GET["Nombres_Apellidos"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["Fecha_denuncia"]))
				$arKey[] = ew_StripSlashes($_POST["Fecha_denuncia"]);
			elseif (isset($_GET["Fecha_denuncia"]))
				$arKey[] = ew_StripSlashes($_GET["Fecha_denuncia"]);
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
			$this->CI_RUN->CurrentValue = $key[0];
			$this->Nombres_Apellidos->CurrentValue = $key[1];
			$this->Fecha_denuncia->CurrentValue = $key[2];
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
		$this->Nombres_Apellidos->setDbValue($rs->fields('Nombres_Apellidos'));
		$this->Unidad_Organizacional->setDbValue($rs->fields('Unidad_Organizacional'));
		$this->Detalles->setDbValue($rs->fields('Detalles'));
		$this->Fecha_denuncia->setDbValue($rs->fields('Fecha_denuncia'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// CI_RUN
		// Nombres_Apellidos

		$this->Nombres_Apellidos->CellCssStyle = "white-space: nowrap;";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->CellCssStyle = "white-space: nowrap;";

		// Detalles
		$this->Detalles->CellCssStyle = "white-space: nowrap;";

		// Fecha_denuncia
		// CI_RUN

		$this->CI_RUN->ViewValue = $this->CI_RUN->CurrentValue;
		$this->CI_RUN->ViewCustomAttributes = "";

		// Nombres_Apellidos
		$this->Nombres_Apellidos->ViewValue = $this->Nombres_Apellidos->CurrentValue;
		$this->Nombres_Apellidos->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->ViewValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->ViewCustomAttributes = "";

		// Detalles
		$this->Detalles->ViewValue = $this->Detalles->CurrentValue;
		$this->Detalles->ViewCustomAttributes = "";

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

		// Detalles
		$this->Detalles->LinkCustomAttributes = "";
		$this->Detalles->HrefValue = "";
		$this->Detalles->TooltipValue = "";

		// Fecha_denuncia
		$this->Fecha_denuncia->LinkCustomAttributes = "";
		$this->Fecha_denuncia->HrefValue = "";
		$this->Fecha_denuncia->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// CI_RUN
		// Nombres_Apellidos

		$this->Nombres_Apellidos->EditAttrs["class"] = "form-control";
		$this->Nombres_Apellidos->EditCustomAttributes = "";
		$this->Nombres_Apellidos->EditValue = $this->Nombres_Apellidos->CurrentValue;
		$this->Nombres_Apellidos->ViewCustomAttributes = "";

		// Unidad_Organizacional
		$this->Unidad_Organizacional->EditAttrs["class"] = "form-control";
		$this->Unidad_Organizacional->EditCustomAttributes = "";
		$this->Unidad_Organizacional->EditValue = $this->Unidad_Organizacional->CurrentValue;
		$this->Unidad_Organizacional->PlaceHolder = ew_RemoveHtml($this->Unidad_Organizacional->FldCaption());

		// Detalles
		$this->Detalles->EditAttrs["class"] = "form-control";
		$this->Detalles->EditCustomAttributes = "";
		$this->Detalles->EditValue = $this->Detalles->CurrentValue;
		$this->Detalles->PlaceHolder = ew_RemoveHtml($this->Detalles->FldCaption());

		// Fecha_denuncia
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
					if ($this->Nombres_Apellidos->Exportable) $Doc->ExportCaption($this->Nombres_Apellidos);
					if ($this->Unidad_Organizacional->Exportable) $Doc->ExportCaption($this->Unidad_Organizacional);
					if ($this->Detalles->Exportable) $Doc->ExportCaption($this->Detalles);
					if ($this->Fecha_denuncia->Exportable) $Doc->ExportCaption($this->Fecha_denuncia);
				} else {
					if ($this->CI_RUN->Exportable) $Doc->ExportCaption($this->CI_RUN);
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
						if ($this->Nombres_Apellidos->Exportable) $Doc->ExportField($this->Nombres_Apellidos);
						if ($this->Unidad_Organizacional->Exportable) $Doc->ExportField($this->Unidad_Organizacional);
						if ($this->Detalles->Exportable) $Doc->ExportField($this->Detalles);
						if ($this->Fecha_denuncia->Exportable) $Doc->ExportField($this->Fecha_denuncia);
					} else {
						if ($this->CI_RUN->Exportable) $Doc->ExportField($this->CI_RUN);
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
