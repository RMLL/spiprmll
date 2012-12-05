<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_rmllplanning() {
	
	/* quelques controles ? */
	Rmll_Helper::faire_controles();
	
	debut_page(_T('rmll:titre_page_planning'));
	
	debut_gauche();
	
	Rmll_Helper::menu_planning();
	
	debut_droite();	
	
	echo fin_page();
}
?>
