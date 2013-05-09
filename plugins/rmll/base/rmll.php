<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/meta');
include_spip('base/create');

function rmll_declarer_tables_principales($tables_principales) {
    /*
    * Table des 'jours'
    */
    $spip_rmll_jours = array (
        'id_jour' => 'int(11) NOT NULL auto_increment',
        'date' => 'date NOT NULL',
    );
    $spip_rmll_jours_key = array (
        'PRIMARY KEY' => 'id_jour',
    );
    $tables_principales['spip_rmll_jours'] = array (
        'field' => &$spip_rmll_jours,
        'key' => &$spip_rmll_jours_key,
    );

    /*
    * Table des 'horaires'
    */
    $spip_rmll_horaires = array (
        'id_horaire' => 'int(11) NOT NULL auto_increment',
        'heure' => 'int(11) NOT NULL',
        'minute' => 'int(11) NOT NULL',
    );
    $spip_rmll_horaires_key = array (
        'PRIMARY KEY' => 'id_horaire',
    );
    $tables_principales['spip_rmll_horaires'] = array (
        'field' => &$spip_rmll_horaires,
        'key' => &$spip_rmll_horaires_key,
    );

    /*
    * Table des 'langues'
    */
    $spip_rmll_langues = array (
        'id_langue' => 'int(11) NOT NULL auto_increment',
        'code' => 'varchar(2) NOT NULL',
        'nom' => 'varchar(32) NOT NULL',
    );
    $spip_rmll_langues_key = array (
        'PRIMARY KEY' => 'id_langue',
    );
    $tables_principales['spip_rmll_langues'] = array (
        'field' => &$spip_rmll_langues,
        'key' => &$spip_rmll_langues_key,
    );

    /*
    * Table des 'natures'
    */
    $spip_rmll_natures = array (
        'id_nature' => 'int(11) NOT NULL auto_increment',
        'code' => 'varchar(6) NOT NULL',
        'nom' => 'varchar(32) NOT NULL',
    );
    $spip_rmll_natures_key = array (
        'PRIMARY KEY' => 'id_nature',
    );
    $tables_principales['spip_rmll_natures'] = array (
        'field' => &$spip_rmll_natures,
        'key' => &$spip_rmll_natures_key,
    );

    /*
    * Table des 'salles'
    */
    $spip_rmll_salles = array (
        'id_salle' => 'int(11) NOT NULL auto_increment',
        'capacite' => 'int(11) NOT NULL',
        'nom' => 'varchar(128) NOT NULL',
    );
    $spip_rmll_salles_key = array (
        'PRIMARY KEY' => 'id_salle',
    );
    $tables_principales['spip_rmll_salles'] = array (
        'field' => &$spip_rmll_salles,
        'key' => &$spip_rmll_salles_key,
    );

    /*
    * Table des 'niveaux'
    */
    $spip_rmll_niveaux = array (
        'id_niveau' => 'int(11) NOT NULL auto_increment',
        'code' => 'varchar(3) NOT NULL',
        'nom' => 'varchar(32) NOT NULL',
    );
    $spip_rmll_niveaux_key = array (
        'PRIMARY KEY' => 'id_niveau',
    );
    $tables_principales['spip_rmll_niveaus'] = array (
        'field' => &$spip_rmll_niveaux,
        'key' => &$spip_rmll_niveaux_key,
    );

    /*
    * Table des 'conferences'
    */
    $spip_rmll_conferences = array (
        'id_conference' => 'int(11) NOT NULL auto_increment',
        'id_jour' => 'int(11) NOT NULL',
        'id_horaire' => 'int(11) NOT NULL',
        'duree' => 'int(11) NOT NULL',
        'id_langue' => 'int(11) NOT NULL',
        'id_nature' => 'int(11) NOT NULL',
        'id_salle' => 'int(11) NOT NULL',
        'id_article' => 'int(11) NOT NULL',
        'id_niveau' => 'int(11) NOT NULL',
        'intervenants' => 'varchar(255) NOT NULL',
        'video' => 'varchar(255) NOT NULL',
        'notes' => 'TEXT NOT NULL',
    );
    $spip_rmll_conferences_key = array (
        'PRIMARY KEY' => 'id_conference',
    );
    $tables_principales['spip_rmll_conferences'] = array (
        'field' => &$spip_rmll_conferences,
        'key' => &$spip_rmll_conferences_key,
    );

    return $tables_principales;
}

function rmll_declarer_tables_interfaces($interface) {
    $interface['table_des_tables']['rmll_jours'] = 'rmll_jours';
    $interface['table_des_tables']['rmll_horaires'] = 'rmll_horaires';
    $interface['table_des_tables']['rmll_langues'] = 'rmll_langues';
    $interface['table_des_tables']['rmll_natures'] = 'rmll_natures';
    $interface['table_des_tables']['rmll_salles'] = 'rmll_salles';
    $interface['table_des_tables']['rmll_niveaus'] = 'rmll_niveaus';
    $interface['table_des_tables']['rmll_conferences'] = 'rmll_conferences';

/*
    $interface['table_jointures']['spip_articles']['id_article'] = 'rmll_conferences';
    $interface['table_jointures']['spip_rmll_conference']['id_article'] = 'articles';
*/
    //$interface['exception_des_jointures']['statut_art']=array('spip_articles','id_article');

    //$interface['exceptions_des_tables']['rmll_conferences']['id_article'] = array('spip_articles', 'id_article');
    //$interface['exceptions_des_tables']['articles']['id_article'] = array('spip_rmll_conferences', 'id_article');

    //$interface['table_des_tables']['rmll_jour'] = 'rmll_jour';
    //$interface['table_des_tables']['rmll_jour'] = 'rmll_jour';
    //$interface['tables_jointures']['rmll_conferences'][] = 'rmll_natures';
    //$interface['tables_jointures']['spip_rmll_langues'][] = 'rmll_conferences';
    //$interface['tables_jointures']['spip_rmll_conferences']['id_nature'] = 'rmll_natures';
    //$interface['tables_jointures']['spip_rmll_conference'][] = 'spip_rmll_jour';
    //$interface['tables_jointures']['spip_rmll_jour'][] = 'spip_rmll_conference';
    //$interface['exceptions_des_jointures']['date_jour'][] = array('spip_rmll_jour', 'date');
    //$interface['exceptions_des_jointures']['nom_langue'][] = array('rmll_langues', 'nom');
    //$interface['exceptions_des_tables']['rmll_natures'][ 'id_nature'] = array('spip_rmll_conferences', 'id_nature');
    //$interface['exceptions_des_tables']['spip_rmll_langue'][ 'id_jour'] = array('spip_rmll_conference', 'id_jour');
    //$interface['tables_jointures']['spip_rmll_conference']['id_horaire'] = 'spip_rmll_horaire';
    //$interface['tables_jointures']['spip_rmll_conference']['id_langue'] = 'spip_rmll_langue';
    //$interface['tables_jointures']['spip_rmll_conference']['id_nature'] = 'spip_rmll_natrure';
    //$interface['tables_jointures']['spip_rmll_conference']['id_salle'] = 'spip_rmll_salle';
    return $interface;
}

function rmll_install($action, $prefix, $version_cible) {
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

function rmll_upgrade($nom_meta_base_version, $version_cible) {
    $db_version = 0.0;
    if ((!isset($GLOBALS['meta'][$nom_meta_base_version]))
        || (($db_version = $GLOBALS['meta'][$nom_meta_base_version]) != $version_cible)) {
        if (0.0 == $db_version) {
            creer_base();
            rmll_peupler_base();
            ecrire_meta($nom_meta_base_version, $db_version = $version_cible);
        }
    }
}

function rmll_vider_tables($nom_meta_base_version) {
    sql_drop_table('spip_rmll_jours');
    sql_drop_table('spip_rmll_horaires');
    sql_drop_table('spip_rmll_langues');
    sql_drop_table('spip_rmll_natures');
    sql_drop_table('spip_rmll_salles');
    sql_drop_table('spip_rmll_niveaus');
    sql_drop_table('spip_rmll_conferences');
    effacer_meta($nom_meta_base_version);
}

function rmll_peupler_base() {
    sql_insertq_multi('spip_rmll_jours', array(
            array('date' => '2010-07-06'),
            array('date' => '2010-07-07'),
            array('date' => '2010-07-08'),
            array('date' => '2010-07-09'),
            array('date' => '2010-07-10'),
            array('date' => '2010-07-11'),
        ));

    sql_insertq_multi('spip_rmll_langues', array(
            array('code' => 'fr', 'nom' => 'Français'),
            array('code' => 'en', 'nom' => 'Anglais'),
            array('code' => 'nl', 'nom' => 'Néerlandais'),
        ));

    sql_insertq_multi('spip_rmll_natures', array(
            array('code' => 'ag', 'nom' => 'Assemblée Générale'),
            array('code' => 'atl', 'nom' => 'Atelier'),
            array('code' => 'conf', 'nom' => 'Conférence'),
            array('code' => 'demo', 'nom' => 'Démo'),
            array('code' => 'plen', 'nom' => 'Plénière'),
            array('code' => 'tabler', 'nom' => 'Table ronde'),
            array('code' => 'visio', 'nom' => 'Visioconférence'),
        ));

    sql_insertq_multi('spip_rmll_niveaus', array(
            array('code' => 'con', 'nom' => 'Confirmé'),
            array('code' => 'deb', 'nom' => 'Débutant'),
            array('code' => 'exp', 'nom' => 'Expert'),
        ));

    sql_insertq_multi('spip_rmll_horaires', array(
            array('heure' => 9, 'minute' => 20),
            array('heure' => 9, 'minute' => 40),
            array('heure' => 10, 'minute' => 0),
            array('heure' => 10, 'minute' => 20),
            array('heure' => 11, 'minute' => 00),
            array('heure' => 11, 'minute' => 20),
            array('heure' => 14, 'minute' => 0),
            array('heure' => 14, 'minute' => 20),
            array('heure' => 14, 'minute' => 40),
            array('heure' => 15, 'minute' => 0),
            array('heure' => 15, 'minute' => 20),
            array('heure' => 15, 'minute' => 40),
            array('heure' => 16, 'minute' => 20),
            array('heure' => 16, 'minute' => 40),
            array('heure' => 17, 'minute' => 00),
            array('heure' => 17, 'minute' => 20),
        ));
}

?>