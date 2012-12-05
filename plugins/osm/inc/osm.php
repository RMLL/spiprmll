<?php

/*
 * Plugin OSM
 *
 */

function osm_ajouter_headers ($flux) {
    $flux .= '<link rel="stylesheet" type="text/css" href="'._DIR_PLUGIN_OSM.'img_pack/osm.css" />';
    return $flux;
}

?>