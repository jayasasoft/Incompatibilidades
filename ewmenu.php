<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(23, "mci_DECLARACION_JURADA", $Language->MenuPhrase("23", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(2, "mi_t_funcionario", $Language->MenuPhrase("2", "MenuText"), "t_funcionariolist.php", 23, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}t_funcionario'), FALSE, FALSE);
$RootMenu->AddMenuItem(133, "mi_t_declaraciones", $Language->MenuPhrase("133", "MenuText"), "t_declaracioneslist.php", 23, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}t_declaraciones'), FALSE, FALSE);
$RootMenu->AddMenuItem(34, "mci_ADMINISTRACION", $Language->MenuPhrase("34", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(10, "mi_t_usuario", $Language->MenuPhrase("10", "MenuText"), "t_usuariolist.php", 34, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}t_usuario'), FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mi_userlevels", $Language->MenuPhrase("11", "MenuText"), "userlevelslist.php", 34, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(39, "mi_audittrail2", $Language->MenuPhrase("39", "MenuText"), "audittrail2list.php", 34, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}audittrail2'), FALSE, FALSE);
$RootMenu->AddMenuItem(67, "mci_SISTEMA", $Language->MenuPhrase("67", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(44, "mi_t_unidad_organizacional", $Language->MenuPhrase("44", "MenuText"), "t_unidad_organizacionallist.php", 67, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}t_unidad_organizacional'), FALSE, FALSE);
$RootMenu->AddMenuItem(94, "mi_t_grados", $Language->MenuPhrase("94", "MenuText"), "t_gradoslist.php", 67, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}t_grados'), FALSE, FALSE);
$RootMenu->AddMenuItem(38, "mci_CONSULTAS", $Language->MenuPhrase("38", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(134, "mi_declaraciones", $Language->MenuPhrase("134", "MenuText"), "declaracioneslist.php", 38, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}declaraciones'), FALSE, FALSE);
$RootMenu->AddMenuItem(68, "mi_conyugue", $Language->MenuPhrase("68", "MenuText"), "conyuguelist.php", 38, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}conyugue'), FALSE, FALSE);
$RootMenu->AddMenuItem(40, "mi_consanguineos", $Language->MenuPhrase("40", "MenuText"), "consanguineoslist.php", 38, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}consanguineos'), FALSE, FALSE);
$RootMenu->AddMenuItem(41, "mi_afinidad", $Language->MenuPhrase("41", "MenuText"), "afinidadlist.php", 38, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}afinidad'), FALSE, FALSE);
$RootMenu->AddMenuItem(42, "mi_parientes", $Language->MenuPhrase("42", "MenuText"), "parienteslist.php", 38, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}parientes'), FALSE, FALSE);
$RootMenu->AddMenuItem(157, "mi_Incompatibilidad", $Language->MenuPhrase("157", "MenuText"), "Incompatibilidadlist.php", 38, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}Incompatibilidad'), FALSE, FALSE);
$RootMenu->AddMenuItem(156, "mci_DENUNCIAS_", $Language->MenuPhrase("156", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(136, "mi_t_denuncias", $Language->MenuPhrase("136", "MenuText"), "t_denunciaslist.php", 156, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}t_denuncias'), FALSE, FALSE);
$RootMenu->AddMenuItem(160, "mi_consulta_denuncia", $Language->MenuPhrase("160", "MenuText"), "consulta_denuncialist.php", 156, "", AllowListMenu('{DBEDEDF3-C0F6-4CE5-A781-F1E2EFAF0B48}consulta_denuncia'), FALSE, FALSE);
$RootMenu->AddMenuItem(92, "mci_AYUDA", $Language->MenuPhrase("92", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(130, "mci_Manual", $Language->MenuPhrase("130", "MenuText"), "Manual.pdf", 92, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(131, "mci_Reglamento", $Language->MenuPhrase("131", "MenuText"), "reglamento.pdf", 92, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
