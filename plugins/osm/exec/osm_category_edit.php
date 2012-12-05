<?php

/*
 * Plugin Osm
 *
 */

include_spip('inc/osm.class');

function exec_osm_category_edit () {
    /* admin ou pas */
    Osm_Helper::test_acces_admin();
    Osm_Helper::debut_page(_T('osm:titre_page_gestion'));
    Osm_Helper::debut_gauche();
    Osm_Helper::debut_cadre_enfonce();
    Osm_Helper::icone_horizontale(
        _T('osm:liste'), 'osm_category', 'liste.png');
    Osm_Helper::icone_horizontale(
        _T('osm:ajout'), 'osm_category_edit', 'ajout.png');
    Osm_Helper::fin_cadre_enfonce();

    Osm_Helper::menu_gestion();
    Osm_Helper::debut_droite();

    $table = new Osm_Db('categorie');

    /* Récupération des données */
    $id = Osm_Helper::inPost('id');
    $nom = Osm_Helper::inPost('nom', '<multi>');
    $icone = Osm_Helper::inFiles('icone', OSM_MARKERS_PATH);
    $hidden = Osm_Helper::inPost('hidden');

    if ($id) {
        // Edition
        if ($nom !== null) {
            if ($icone === null) {
                $update = $table->update(array('nom' => $nom, 'hidden' => $hidden), $id);
            }
            else {
                $icone_path = sprintf('%s/%s/%s', _DIR_PLUGIN_OSM, OSM_MARKERS_PATH, $icone);
                $infos = Osm_Helper::imageInfos($icone_path);
                $width = $height = 0;
                if ($infos) {
                    $width = $infos['width'];
                    $height = $infos['height'];
                }
                $update = $table->update(array('nom' => $nom, 'icone' => $icone, 'width' => $width, 'height' => $height, 'hidden' => $hidden), $id);
            }
            if ($update) {
                Osm_Helper::boite_infos(_T('osm:message_sauv_ok'));
                $id = $nom = $hidden = null;
            }
            else {
                Osm_Helper::boite_erreurs(_T('osm:message_sauv_err')."<br/>".$table->error());
            }
        }
        else {
            if ($row = $table->get_one($id)) {
                $nom = $row['nom'];
                $hidden = $row['hidden'];
            }
        }
    }
    elseif (Osm_Helper::isPost()) {
        // Ajout
        if ($nom && $icone) {
            $icone_path = sprintf('%s/%s/%s', _DIR_PLUGIN_OSM, OSM_MARKERS_PATH, $icone);
            $infos = Osm_Helper::imageInfos($icone_path);
            $width = $height = 0;
            if ($infos) {
                $width = $infos['width'];
                $height = $infos['height'];
            }
            if ($table->insert(array('nom' => $nom, 'icone' => $icone, 'width' => $width, 'height' => $height, 'hidden' => $hidden))) {
                Osm_Helper::boite_infos(_T('osm:message_sauv_ok'));
                $nom = $icone = $hidden = null;
            }
            else {
                Osm_Helper::boite_erreurs(_T('osm:message_sauv_err')."<br/>".$table->error());
            }
        }
        else {
            Osm_Helper::boite_erreurs(_T('osm:message_sauv_err_nodatas'));
        }
    }

    Osm_Helper::titre_gros(_T('osm:titre_gestion_category'));
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
        Osm_Helper::formulaire_label(_T('osm:label_icone'), array('for'=>'icone'));
        Osm_Helper::formulaire_fichier('icone');
    ?>
    </div>

    <div class="element">
    <?php
        $hide = array('0' => _T('osm:no'), '1' => _T('osm:yes'));
        Osm_Helper::formulaire_label(_T('osm:label_hidden'), array('for'=>'hidden'));
        Osm_Helper::formulaire_selection('hidden', $hide, $hidden);
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