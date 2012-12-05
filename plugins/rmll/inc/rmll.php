<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

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