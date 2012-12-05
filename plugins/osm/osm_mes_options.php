<?php

//ini_set ("display_errors", "On");
//error_reporting(-1);

define('OSM_MARKERS_PATH', 'markers');

global $OSM_POI_ARTCILES;
$OSM_POI_ARTCILES = array (
    // format:
    // 'id_article' => array('id_categorie1', 'id_categorie2'),
    //'31' => array(33),
    //'29' => array(11,6),
);

function osm_get_poi_article($id_article, $id_trad = 0) {
    $ret = false;
    global $OSM_POI_ARTCILES;
    if (array_key_exists($id_article, $OSM_POI_ARTCILES)) {
        $ret = $OSM_POI_ARTCILES[$id_article];
    }
    elseif (array_key_exists($id_trad, $OSM_POI_ARTCILES)) {
        $ret = $OSM_POI_ARTCILES[$id_trad];
    }
    return $ret;
}

?>