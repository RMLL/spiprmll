<?php

/*
 * Plugin Rmll
 *
 */


/*** Declaration des tables ***/

/*
 * pour eviter une reinit posterieure des tables modifiees
 */

include_spip('base/serial');

global $rmll_tables_principales;
$rmll_tables_principales = array();

/*
 * Table des 'jour'
 */

$spip_rmll_jour = array (
    "id_jour" => "int(11) NOT NULL auto_increment",
    "date" => "date NOT NULL",
    );

$spip_rmll_jour_key = array (
    "PRIMARY KEY" => "id_jour",
    );


$rmll_tables_principales['spip_rmll_jour'] = array (
    'field' => &$spip_rmll_jour,
    'key' => &$spip_rmll_jour_key,
    );


/*
 * Table des 'horaire'
 */

$spip_rmll_horaire = array (
    "id_horaire" => "int(11) NOT NULL auto_increment",
    "heure" => "int(11) NOT NULL",
    "minute" => "int(11) NOT NULL",
    );

$spip_rmll_horaire_key = array (
    "PRIMARY KEY" => "id_horaire",
    );


$rmll_tables_principales['spip_rmll_horaire'] = array (
    'field' => &$spip_rmll_horaire,
    'key' => &$spip_rmll_horaire_key,
    );


/*
 * Table des 'langue'
 */

$spip_rmll_langue = array (
    "id_langue" => "int(11) NOT NULL auto_increment",
    "code" => "varchar(2) NOT NULL",
    "nom" => "varchar(32) NOT NULL",
    );

$spip_rmll_langue_key = array (
    "PRIMARY KEY" => "id_langue",
    );


$rmll_tables_principales['spip_rmll_langue'] = array (
    'field' => &$spip_rmll_langue,
    'key' => &$spip_rmll_langue_key,
    );


/*
 * Table des 'nature'
 */

$spip_rmll_nature = array (
    "id_nature" => "int(11) NOT NULL auto_increment",
    "code" => "varchar(6) NOT NULL",
    "nom" => "varchar(32) NOT NULL",
    );

$spip_rmll_nature_key = array (
    "PRIMARY KEY" => "id_nature",
    );


$rmll_tables_principales['spip_rmll_nature'] = array (
    'field' => &$spip_rmll_nature,
    'key' => &$spip_rmll_nature_key,
    );

/*
 * Table des 'salle'
 */

$spip_rmll_salle = array (
    "id_salle" => "int(11) NOT NULL auto_increment",
    "capacite" => "int(11) NOT NULL",
    "nom" => "varchar(128) NOT NULL",
    );

$spip_rmll_salle_key = array (
    "PRIMARY KEY" => "id_salle",
    );


$rmll_tables_principales['spip_rmll_salle'] = array (
    'field' => &$spip_rmll_salle,
    'key' => &$spip_rmll_salle_key,
    );

/*
 * Table des 'niveaux'
 */

$spip_rmll_niveau = array (
    "id_niveau" => "int(11) NOT NULL auto_increment",
    "code" => "varchar(3) NOT NULL",
    "nom" => "varchar(32) NOT NULL",
    );

$spip_rmll_niveau_key = array (
    "PRIMARY KEY" => "id_niveau",
    );


$rmll_tables_principales['spip_rmll_niveau'] = array (
    'field' => &$spip_rmll_niveau,
    'key' => &$spip_rmll_niveau_key,
    );

/*
 * Table des 'conferences'
 */

$spip_rmll_conference = array (
    "id_conference" => "int(11) NOT NULL auto_increment",
    "id_jour" => "int(11) NOT NULL",
    "id_horaire" => "int(11) NOT NULL",
    "duree" => "int(11) NOT NULL",
    "id_langue" => "int(11) NOT NULL",
    "id_nature" => "int(11) NOT NULL",
    "id_salle" => "int(11) NOT NULL",
    "id_article" => "int(11) NOT NULL",
    "id_niveau" => "int(11) NOT NULL",
    "intervenants" => "varchar(255) NOT NULL",
    "video" => "varchar(255) NOT NULL",
    );

$spip_rmll_conference_key = array (
    "PRIMARY KEY" => "id_conference",
    );


$rmll_tables_principales['spip_rmll_conference'] = array (
    'field' => &$spip_rmll_conference,
    'key' => &$spip_rmll_conference_key,
    );


global $tables_principales;

$tables_principales = array_merge ($tables_principales, $rmll_tables_principales);


/*
 * Relations entre les tables
 */

/*
global $tables_relations;

$tables_relations['rmll_conferences']['id_jour'] = 'rmll_jours';
$tables_relations['rmll_conferences']['id_horaire'] = 'rmll_horaires';
$tables_relations['rmll_conferences']['id_langue'] = 'rmll_langues';
$tables_relations['rmll_conferences']['id_nature'] = 'rmll_natures';
$tables_relations['rmll_conferences']['id_salle'] = 'rmll_salles';
$tables_relations['rmll_conferences']['id_article'] = 'articles';
*/


/*
 * Table des tables
 */

/*
global $table_des_tables;

$table_des_tables['rmll_jours'] = 'rmll_jours';
$table_des_tables['rmll_horaires'] = 'rmll_horaires';
$table_des_tables['rmll_langues'] = 'rmll_langues';
$table_des_tables['rmll_natures'] = 'rmll_natures';
$table_des_tables['rmll_salles'] = 'rmll_salles';
$table_des_tables['rmll_conferences'] = 'rmll_conferences';
*/

?>