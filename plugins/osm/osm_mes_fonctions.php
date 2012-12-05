<?php

/*
* Plugin Osm
*
*/

include_spip('inc/osm.class');

function balise_OSM_JS_POI ($p) {
    $marker_obj = new Osm_Marker();
    $datas = $marker_obj->get_all();
    $markers = array();
    if (is_array($datas)) {
        foreach($datas as $data) {
            $categorie = extraire_multi(nettoyer_raccourcis_typo($data['nom_categorie']), $GLOBALS['lang']);
            $nom = extraire_multi(nettoyer_raccourcis_typo($data['nom']), $GLOBALS['lang']);
            $description = propre($data['description']);
            if (!array_key_exists($data['id_categorie'], $markers)) {
                $markers[$data['id_categorie']] = array(
                    'name' => $categorie,
                    'markers' => array(),
                );
            }
            $markers[$data['id_categorie']]['markers'][] = array(
                'name' => sprintf('<strong>%s :</strong> %s', $categorie, $nom),
                'icon' => $data['icone'],
                'width' => (int) $data['width'],
                'height' => (int) $data['height'],
                'description' => sprintf('<div class="title"><div class="cat">%s</div><div class="name">%s</div></div><div class="desc">%s</div>', $categorie, $nom, $description),
                'longitude' => (double) $data['longitude'],
                'latitude' => (double) $data['latitude'],
            );
        }
    }
    $p->code = '\''.json_encode($markers).'\'';
    return $p;
}

?>