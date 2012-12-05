<?php

/*
 * Plugin OSM
 *
 */

include_spip('inc/osm.class');

function exec_osm() {
    /* admin ou pas */
    Osm_Helper::test_acces_admin();

    Osm_Helper::debut_page(_T('osm:titre_page_gestion'));
    Osm_Helper::debut_gauche();

    Osm_Helper::menu_gestion();
    Osm_Helper::debut_droite();

    Osm_Helper::fin_page();
}
?>