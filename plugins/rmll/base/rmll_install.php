<?php

/*
 * Plugin Rmll
 *
 */

function rmll_install($action) {

    switch ($action) {

    case 'test':
    /* les tables existent elles ? */
    spip_log("Plugin RMLL: test d'existance des tables dans la base");
    include_spip('base/abstract_sql');
    $desc = spip_abstract_showtable("spip_rmll_conferences", '', true);
    return (isset($desc['field']['id_conference']));
    break;

    case 'install':
    /* Création des tables */
    spip_log("Plugin RMLL: Création des tables");
    include_spip('base/create');
    include_spip('base/rmll');
    creer_base();
    break;

    case 'uninstall':
    /* Suppression des tables */
    spip_log("Plugin RMLL: Supression des tables");
    include_spip('base/rmll');
    global $rmll_tables_principales;

    foreach($rmll_tables_principales as $table => $struct) {
        spip_log("Plugin RMLL: Suppression de  la table : ".$table);
        spip_query("DROP TABLE ".$table);
    }
    break;
    }
}
