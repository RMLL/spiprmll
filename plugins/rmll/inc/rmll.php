<?php

/*
 * Plugin Rmll
 *
 */

define("_DIR_PLUGIN_RMLL", _DIR_PLUGINS."/rmll");

include_spip('inc/rmll.class');

function rmll_ajouter_item($boutons_admin) {

    /*
    $boutons_admin['naviguer']->sousmenu['rmllplanning'] = new Bouton (
        // icone
        _DIR_PLUGIN_RMLL."/img_pack/armelle-24.png",
        // intitulé
        _T('rmll:titre_menu_planning')
    );
    */
    /* si on est admin */

    if ($GLOBALS['connect_statut'] == "0minirezo" && $GLOBALS["connect_toutes_rubriques"]) {
        // Ajout d'un item au menu
        $boutons_admin['naviguer']->sousmenu['rmll'] = new Bouton (
        // icone
        _DIR_PLUGIN_RMLL."/img_pack/armelle-24.png",
        // intitulé
        _T('rmll:titre_menu_gestion')
        );
    }

    return $boutons_admin;
}


function rmll_ajouter_headers ($flux) {
    $flux .= '<link rel="stylesheet" type="text/css" href="'._DIR_PLUGIN_RMLL.'img_pack/rmll.css" />';
    return $flux;
}

function rmll_affiche_milieu($flux) {
    if ($flux['args']['exec'] == 'articles') {
        $ev = new Rmll_Event();
        $id_article = $flux['args']['id_article'];
        $flag = autoriser('modifier','article',$id_article);
        if ($flag)
            $flux['data'] .= $ev->Form($id_article, $flux['args']['exec']);
    }
    return $flux;
}

?>