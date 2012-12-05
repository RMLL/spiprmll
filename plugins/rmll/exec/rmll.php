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
	
	debut_page(_T('rmll:titre_page_gestion'));
	
	debut_gauche();
	
	Rmll_Helper::menu_gestion();
	
	debut_droite();	
	
	echo fin_page();
}
?>
