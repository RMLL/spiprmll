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
	'auditeurs' => 'int(2)',
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
            array('date' => '2013-07-08'),
            array('date' => '2013-07-09'),
            array('date' => '2013-07-10'),
            array('date' => '2013-07-11'),
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
            array('heure' => 11, 'minute' => 40),
            array('heure' => 12, 'minute' => 00),
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
            array('heure' => 17, 'minute' => 40),
        ));

    sql_insertq_multi('spip_groupes_mots', array(
        array('id_groupe' => 1, 'titre' => 'Fils rouges', 
	'tables_liees' => 'articles,rubriques',  'obligatoire' => 'non'),
        array('id_groupe' => 2, 'titre' => 'Public cible', 
	'tables_liees' => 'articles,rubriques',  'obligatoire' => 'oui'),
	));

    sql_insertq_multi('spip_mots', array(
        array('id_mot' => 1, 'titre' => 'Au quotidien', 
	'id_groupe' => 1, 'type' => 'Fil rouge'),
        array('id_mot' => 2, 'titre' => 'Enjeux sociétaux', 
	'id_groupe' => 1, 'type' => 'Fil rouge'),
        array('id_mot' => 3, 'titre' => 'Open Data', 
	'id_groupe' => 1, 'type' => 'Fil rouge'),
        array('id_mot' => 4, 'titre' => 'Cloud', 
	'id_groupe' => 1, 'type' => 'Fil rouge'),
        array('id_mot' => 5, 'titre' => 'Grand public', 
	'id_groupe' => 2, 'type' => 'Public cible'),
        array('id_mot' => 6, 'titre' => 'Professionnels', 
	'id_groupe' => 2, 'type' => 'Public cible'),
        array('id_mot' => 7, 'titre' => 'Décideurs', 
	'id_groupe' => 2, 'type' => 'Public cible'),
        array('id_mot' => 8, 'titre' => 'Geeks', 
	'id_groupe' => 2, 'type' => 'Public cible'),
	));

   // Rubriques principales
   sql_insertq_multi('spip_rubriques', array(
       array('id_rubrique' => 100, 'id_parent' => 0, 
       'titre' => '<multi>Programme[en]Schedule[nl]Schedule</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 101, 'id_parent' => 100, 
       'titre' => '01. <multi>Général[en]General[nl]General</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 102, 'id_parent' => 100, 
       'titre' => '02. <multi>Divers[en]Miscellaneous[nl]Miscellaneous</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 110, 'id_parent' => 100, 
       'titre' => '10. <multi>Le Libre dans la société[en]Freedom in Society[nl]Het Vrije Gemeengoed</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 120, 'id_parent' => 100, 
       'titre' => '20. <multi>Cultures et Arts Libres[en]Libre/Free Culture &amp; Arts[nl]Vrije kultuur en kunsten</multi>', 
       'id_secteur' => 50, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 130, 'id_parent' => 100, 
       'titre' => '30. <multi>Média, presse, radio, télévision et graphisme professionnel[en]Media, Press, Radio, Television and Professional Graphics[nl]De media, pers, radio, televisie, grafisme</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 140, 'id_parent' => 100, 
       'titre' => '40. <multi>Entreprises et Logiciel Libre[en]Companies and Free Software[nl]Bedrijven en vrije software</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 150, 'id_parent' => 100, 
       'titre' => '50. <multi>Sciences et formation[en]Science and Education[nl]Wetenchap en vorming</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 160, 'id_parent' => 100, 
       'titre' => '60. <multi>Santé[en]Santé[nl]Gezondheid</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 170, 'id_parent' => 100, 
       'titre' => '70. <multi>Les données ouvertes[en]Open Data[nl]Opendata</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 180, 'id_parent' => 100, 
       'titre' => '80. <multi>Technique[en]Technical[nl]Techniek</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
   ));

   // Sessions (= sous-thèmes)
   sql_insertq_multi('spip_rubriques', array(
       array('id_rubrique' => 1, 'id_parent' => 110, 
       'titre' => '11. <multi>Juridique[en]Legal[nl]Juridisch</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 2, 'id_parent' => 110, 
       'titre' => '12. <multi>Économie[en]Economics[nl]Economisch</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 3, 'id_parent' => 110, 
       'titre' => '13. <multi>Politique[en]Politics[nl]Politiek</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 4, 'id_parent' => 110, 
       'titre' => '14. <multi>Communautés[en]Communities[nl]Gemeenschappen</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 5, 'id_parent' => 110, 
       'titre' => '15. <multi>Freedom Box[en]Freedom Box[nl]Freedom Box</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 6, 'id_parent' => 120, 
       'titre' => '21. <multi>Cultures et arts libres[en]Culture and Libre/Free Art[nl]Cultuur en kunsten onder vrije licentie</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 7, 'id_parent' => 120, 
       'titre' => '22. <multi>Le monde du jeu vidéo[en]The World of Video Game[nl]De wereld van het videospel</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 8, 'id_parent' => 130, 
       'titre' => '32. <multi>Radio, télévision[en]Radio, Television[nl]Radio, televisie</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 9, 'id_parent' => 140, 
       'titre' => '41. <multi>[en][nl]</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 10, 'id_parent' => 140, 
       'titre' => '42. <multi>[en][nl]</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 30, 'id_parent' => 140, 
       'titre' => '43. <multi>[en][nl]</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 31, 'id_parent' => 140, 
       'titre' => '44. <multi>[en][nl]</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 11, 'id_parent' => 180, 
       'titre' => '83. <multi>Matériel libre[en]Free Hardware[nl]Vrij materieel</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 12, 'id_parent' => 150, 
       'titre' => '51. <multi>Enseignement primaire et Éducation populaire[en]Primary School (K-6), Popular and non-formal Education[nl]Primary School (K-6), Popular and non-formal Education</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 26, 'id_parent' => 150, 
       'titre' => '52. <multi>Enseignement secondaire[en]Secondary Education (high school)[nl]Secondary Education</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 28, 'id_parent' => 150, 
       'titre' => '53. <multi>Université et recherche[en]University and Research[nl]University and Research</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 29, 'id_parent' => 150, 
       'titre' => '54. <multi>Enseignement de l\'informatique[en]Computer Teaching[nl]Computer Teaching</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 13, 'id_parent' => 160, 
       'titre' => '61. <multi>Imagerie et visualisation de données médicales[en]Imaging & Visualization of Medical Data[nl]Beeldvorming et visualisering van medische gegevens</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 14, 'id_parent' => 160, 
       'titre' => '62. <multi>Accessibilité, autonomie et gestion de la dépendance[en]Accessibility, Autonomy and Dependency Management[nl]Toegankelijkheid, autonomie en beheer van de afhankelijkheid</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 15, 'id_parent' => 160, 
       'titre' => '63. <multi>Systèmes d’information médicaux privés et hospitaliers[en]Private Medical Hospital Information Systems and Hospitals[nl]Medische gezondheidssystemen voor privaat gebruik en voor ziekenhuizen</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 16, 'id_parent' => 160, 
       'titre' => '64. <multi>Télésanté[en]Telehealth[nl]eHealth</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 17, 'id_parent' => 170, 
       'titre' => '71. <multi> Participation citoyenne et réutilisation des données[en]Citizen Participation and Data Reuse[nl]Deelname door het publiek/de bewoners/de inwoners/de stedelingen en hergebruik van data</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 18, 'id_parent' => 170, 
       'titre' => '72. <multi>La cartographie libre avec OpenStreetMap[en]Libre Cartography with OpenStreetMap[nl]Vrije cartografie met Openstreetmap</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 19, 'id_parent' => 180, 
       'titre' => '81. <multi>Administration Système[en]System Administration[nl]Systeembeheer</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 20, 'id_parent' => 180, 
       'titre' => '82. <multi>Développement logiciel[en]Software Development[nl]Softwareontwikkeling</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 21, 'id_parent' => 180, 
       'titre' => '83. <multi>Systèmes embarqués et matériel libre[en]Embedded Systems and Libre/Free Hardware[nl]Embedded systemen en vrije hardware</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 22, 'id_parent' => 180, 
       'titre' => '80. <multi>Systèmes d’exploitation[en]Operating Systems [nl]Besturingssystemen</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 23, 'id_parent' => 180, 
       'titre' => '84. <multi>Sécurité[en]Security[nl]Beveiliging</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 24, 'id_parent' => 180, 
       'titre' => '85. <multi>Internet[en]Internet[nl]Internet</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 25, 'id_parent' => 130, 
       'titre' => '33. <multi>Graphisme[en]Graphics[nl]Grafisme</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
       array('id_rubrique' => 27, 'id_parent' => 130, 
       'titre' => '31. <multi>Presse[en]Press[nl]Pers</multi>', 
       'id_secteur' => 100, 'export' => 'oui', 
       'statut' => 'publie', 'lang' => 'fr'),
    ));
}

?>
