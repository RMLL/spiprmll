<?php

/*
 * Plugin Osm
 *
 */

include_spip('inc/meta');
include_spip('base/create');

function osm_declarer_tables_principales($tables_principales) {
    /*
    * Table des 'catégories'
    */
    $spip_osm_categories = array (
        'id_categorie' => 'int(11) NOT NULL auto_increment',
        'nom' => 'varchar(255) NOT NULL',
        'icone' => 'varchar(255) NOT NULL',
        'width' => 'int(11) NOT NULL',
        'height' => 'int(11) NOT NULL',
        'hidden' => 'int(1) NOT NULL DEFAULT 0',
    );
    $spip_osm_categories_key = array (
        'PRIMARY KEY' => 'id_categorie',
    );
    $tables_principales['spip_osm_categories'] = array (
        'field' => &$spip_osm_categories,
        'key' => &$spip_osm_categories_key,
    );

    /*
    * Table des 'markers'
    */
    $spip_osm_markers = array (
        'id_marker' => 'int(11) NOT NULL auto_increment',
        'id_categorie' => 'int(11) NOT NULL',
        'nom' => 'varchar(255) NOT NULL',
        'description' => 'TEXT NOT NULL',
        'longitude' => 'varchar(32) NOT NULL',
        'latitude' => 'varchar(32) NOT NULL',
        'accessible' => 'int(1) NOT NULL DEFAULT 0',
    );
    $spip_osm_markers_key = array (
        'PRIMARY KEY' => 'id_marker',
    );
    $tables_principales['spip_osm_markers'] = array (
        'field' => &$spip_osm_markers,
        'key' => &$spip_osm_markers_key,
    );

    return $tables_principales;
}

function osm_declarer_tables_interfaces($interface) {
    $interface['table_des_tables']['osm_categories'] = 'osm_categories';
    $interface['table_des_tables']['osm_markers'] = 'osm_markers';
    return $interface;
}

function osm_install($action, $prefix, $version_cible) {
    $nom_meta_base_version = $prefix.'_base_version';
    switch ($action) {
        case 'test':
            return (isset($GLOBALS['meta'][$nom_meta_base_version])
                AND version_compare($GLOBALS['meta'][$nom_meta_base_version],$version_cible,'>='));
            break;
        case 'install':
            if (function_exists($upgrade = $prefix.'_upgrade'))
                $upgrade($nom_meta_base_version, $version_cible);
            break;
        case 'uninstall':
            if (function_exists($vider_tables = $prefix.'_vider_tables'))
                $vider_tables($nom_meta_base_version);
            break;
    }
}

function osm_upgrade($nom_meta_base_version, $version_cible) {
    $db_version = 0.0;
    if ((!isset($GLOBALS['meta'][$nom_meta_base_version]))
        || (($db_version = $GLOBALS['meta'][$nom_meta_base_version]) != $version_cible)) {
        if (0.0 == $db_version) {
            creer_base();
            osm_peupler_base();
            ecrire_meta($nom_meta_base_version, $db_version = $version_cible);
        }
    }
}

function osm_vider_tables($nom_meta_base_version) {
    sql_drop_table('spip_osm_categories');
    sql_drop_table('spip_osm_markers');
    effacer_meta($nom_meta_base_version);
}

function osm_peupler_base() {
    sql_insertq_multi('spip_osm_categories', array(
        array('nom' => '<multi>Aéroport [en] Airport</multi>', 'icone' => 'airport.png'),
        array('nom' => '<multi>Hébergement [en] Accomodation</multi>', 'icone' => 'apartment.png'),
        array('nom' => '<multi>Distributeur de billets [en] Cash machine</multi>', 'icone' => 'bankeuro.png'),
        array('nom' => '<multi>Café/Bar [en] Coffee/Bar</multi>', 'icone' => 'bar.png'),
        array('nom' => '<multi>Arrêt de Bus[en] Bus station</multi>', 'icone' => 'bus.png'),
        array('nom' => '<multi>Camping [en] Camping site</multi>', 'icone' => 'campingsite.png'),
        array('nom' => '<multi>Cinéma [en] Cinema</multi>', 'icone' => 'cinema.png'),
        array('nom' => '<multi>Vélos en libre service [en] Self-service bicyles</multi>', 'icone' => 'cycling.png'),
        array('nom' => '<multi>Station service [en] Gaz station</multi>', 'icone' => 'gazstation.png'),
        array('nom' => '<multi>Hôpital [en] Hospital</multi>', 'icone' => 'hospital.png'),
        array('nom' => '<multi>Hôtel [en] Hotel</multi>', 'icone' => 'hotel.png'),
        array('nom' => '<multi>Parking [en] Parking</multi>', 'icone' => 'parking.png'),
        array('nom' => '<multi>Commissariat [en] Police Station</multi>', 'icone' => 'police.png'),
        array('nom' => '<multi>Bureau Postal [en] Post office</multi>', 'icone' => 'postaloffice.png'),
        array('nom' => '<multi>Restaurant [en] Restaurant</multi>', 'icone' => 'restaurant.png'),
        array('nom' => '<multi>Sandwicherie [en] Sandwich shop</multi>', 'icone' => 'sandwich.png'),
        array('nom' => '<multi>Supermarché [en] Supermarket</multi>', 'icone' => 'supermarket.png'),
        array('nom' => '<multi>Gare [en] Train station</multi>', 'icone' => 'train.png'),
        array('nom' => '<multi>Arrêt de Tram [en] Tram stop</multi>', 'icone' => 'tram.png'),
        array('nom' => '<multi>Accès WiFi [en] WiFi access</multi>', 'icone' => 'wifi.png'),
    ));
}

?>