<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_rmll() {
	/* admin ou pas */
	Rmll_Helper::test_acces_admin();
	/* quelques controles ? */
	Rmll_Helper::faire_controles();

	Rmll_Helper::debut_page(_T('rmll:titre_page_gestion'));
	Rmll_Helper::debut_gauche();
	Rmll_Helper::menu_gestion();
	Rmll_Helper::debut_droite();
    Rmll_Helper::fin_page();
}

?>
