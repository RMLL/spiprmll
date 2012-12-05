<?php

/*
 * Plugin Osm
 *
 */

include_spip('inc/osm.class');

function exec_osm_marker_edit () {
    /* admin ou pas */
    Osm_Helper::test_acces_admin();
    Osm_Helper::debut_page(_T('osm:titre_page_gestion'));
    Osm_Helper::debut_gauche();
    Osm_Helper::debut_cadre_enfonce();
    Osm_Helper::icone_horizontale(
        _T('osm:liste'), 'osm_marker', 'liste.png');
    Osm_Helper::icone_horizontale(
        _T('osm:ajout'), 'osm_marker_edit', 'ajout.png');
    Osm_Helper::fin_cadre_enfonce();

    Osm_Helper::menu_gestion();
    Osm_Helper::debut_droite();

    $table = new Osm_Marker();
    $table_cat = new Osm_Db('categorie');

    /* Récupération des données */
    $id = Osm_Helper::inPost('id');
    $nom = Osm_Helper::inPost('nom', '<multi>');
    $category = Osm_Helper::inPost('category');
    $description = Osm_Helper::inPost('description', '<multi>');
    $longitude = Osm_Helper::inPost('longitude');
    $latitude = Osm_Helper::inPost('latitude');
    $accessible = Osm_Helper::inPost('accessible');

    $categories = array();
    foreach($table_cat->get_all() as $cat) {
        $categories[$cat['id_categorie']] = extraire_multi(nettoyer_raccourcis_typo($cat['nom']));
    }

    if ($id) {
        // Edition
        if ($nom && $category && $longitude && $latitude) {
            if ($table->update(array(
                    'nom' => $nom, 'description' => $description, 'id_categorie' => $category,
                    'longitude' => $longitude, 'latitude' => $latitude, 'accessible' => $accessible), $id)) {
                Osm_Helper::boite_infos(_T('osm:message_sauv_ok'));
                $id = $nom = $category = $longitude = $latitude = $description = $accessible = null;
            }
            else {
                Osm_Helper::boite_erreurs(_T('osm:message_sauv_err')."<br/>".$table->error());
            }
        }
        else {
            if ($row = $table->get_one($id)) {
                $nom = $row['nom'];
                $longitude = $row['longitude'];
                $latitude = $row['latitude'];
                $description = $row['description'];
                $category = $row['id_categorie'];
                $accessible = $row['accessible'];
            }
        }
    }
    elseif (Osm_Helper::isPost()) {
        // Ajout
        if ($nom && $category && $longitude && $latitude) {
            if ($table->insert(array('nom' => $nom, 'description' => $description, 'id_categorie' => $category,
                                    'longitude' => $longitude, 'latitude' => $latitude, 'accessible' => $accessible))) {
                Osm_Helper::boite_infos(_T('osm:message_sauv_ok'));
                $nom = $longitude = $latitude = $description = $accessible = null;
            }
            else {
                Osm_Helper::boite_erreurs(_T('osm:message_sauv_err')."<br/>".$table->error());
            }
        }
        else {
            Osm_Helper::boite_erreurs(_T('osm:message_sauv_err_nodatas'));
        }
    }

    Osm_Helper::titre_gros(_T('osm:titre_gestion_marker'));
    Osm_Helper::titre_moyen(_T('osm:ajout'));

    Osm_Helper::formulaire_debut('', array('class' => 'osm', 'enctype' => 'multipart/form-data'));
    if ($id) {
        Osm_Helper::formulaire_cache('id', $id);
    }
?>
    <div class="element">
        <div class="help">
            <?php echo _T('osm:help_use_multi'); ?>
        </div>
    <?php
        Osm_Helper::formulaire_label(_T('osm:label_nom'), array('for'=>'nom'));
        Osm_Helper::formulaire_texte('nom', $nom, array('size' => '50'));
    ?>
    </div>

    <div class="element">
    <?php
        Osm_Helper::formulaire_label(_T('osm:label_category'), array('for'=>'category'));
        Osm_Helper::formulaire_selection('category', $categories, $category);
    ?>
    </div>


    <div class="element">
    <?php
        Osm_Helper::formulaire_label(_T('osm:label_longitude'), array('for'=>'longitude'));
        Osm_Helper::formulaire_texte('longitude', $longitude, array('size' => '30'));
    ?>
    </div>

    <div class="element">
    <?php
        Osm_Helper::formulaire_label(_T('osm:label_latitude'), array('for'=>'latitude'));
        Osm_Helper::formulaire_texte('latitude', $latitude, array('size' => '30'));
    ?>
    </div>

    <div class="element">
    <?php
        $accessibility = array('0' => _T('osm:no'), '1' => _T('osm:yes'));
        Osm_Helper::formulaire_label(_T('osm:label_accessible'), array('for'=>'accessible'));
        Osm_Helper::formulaire_selection('accessible', $accessibility, $accessible);
    ?>
    </div>

    <div class="element">
        <div class="help">
            <?php echo _T('osm:help_use_multi'); ?>
        </div>
    <?php
        Osm_Helper::formulaire_label(_T('osm:label_description'), array('for'=>'description'));
        Osm_Helper::formulaire_zonetexte('description', $description, array('cols' => 50, 'rows' => 40));
    ?>
    </div>


    <div class="element">
    <?php
        Osm_Helper::formulaire_soumettre($id ? _T('osm:modifier') : _T('osm:ajouter'));
    ?>
    </div>
<?php
    Osm_Helper::formulaire_fin();

    Osm_Helper::fin_page();
}
?>