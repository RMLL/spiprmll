<?php

/*
 * Plugin Osm
 *
 */

include_spip('inc/osm.class');

function exec_osm_marker() {
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

    Osm_Helper::titre_gros(_T('osm:titre_menu_marker'));

    $table = new Osm_Marker();

    /* Suppression */
    $suppr_id = Osm_Helper::inPost('suppr_id');
    if ($suppr_id) {
        if ($table->delete($suppr_id)) {
            Osm_Helper::boite_infos(_T('osm:message_suppr_ok'));
        }
        else {
            Osm_Helper::boite_erreurs(_T('osm:message_suppr_err')."<br/>".$table->error());
        }
    }

    /* affichage des données */
    $datas = $table->get_all();
    if ($datas === false)
        Osm_Helper::boite_erreurs($table->error());
    elseif (!empty($datas)) {
        Osm_Helper::titre_moyen(_T('osm:liste'));

    ?>
        <table class="osm">
            <tr>
                <th nowrap="nowrap"><?php echo _T('osm:label_nom'); ?></th>
                <th nowrap="nowrap"><?php echo _T('osm:label_category'); ?></th>
                <th nowrap="nowrap"><?php echo _T('osm:label_longitude'); ?></th>
                <th nowrap="nowrap"><?php echo _T('osm:label_latitude'); ?></th>
                <th nowrap="nowrap"><?php echo _T('osm:label_accessible'); ?></th>
                <th></th>
            </tr>
    <?php
        foreach($datas as $d) {
    ?>
            <tr>
                <td><?php echo extraire_multi(nettoyer_raccourcis_typo($d['nom'])); ?></td>
                <td>
                    <img src="<?php printf('%s/%s/%s', _DIR_PLUGIN_OSM, OSM_MARKERS_PATH, $d['icone']); ?>" />
                    <br />
                    <?php echo extraire_multi(nettoyer_raccourcis_typo($d['nom_categorie'])); ?>
                </td>
                <td><?php echo $d['longitude']; ?></td>
                <td><?php echo $d['latitude']; ?></td>
                <td><?php echo $d['accessible'] == 1 ? _T('osm:yes') : _T('osm:no'); ?></td>
                <td>
            <?php
                    /* bouton d'édition */
                    Osm_Helper::formulaire_debut(generer_url_ecrire('osm_marker_edit'), array('class' => 'osm_embed'));
                    Osm_Helper::formulaire_cache('id', $d['id_marker']);
                    Osm_Helper::formulaire_image('edit.png', '', null);
                    Osm_Helper::formulaire_fin();

                    /* bouton de supprression */
                    Osm_Helper::formulaire_debut(generer_url_ecrire('osm_marker'), array('class' => 'osm_embed'));
                    Osm_Helper::formulaire_cache('suppr_id', $d['id_marker']);
                    Osm_Helper::formulaire_image(
                        'suppr.png', '', array(
                            'onclick' => "javacript:return confirm('". addslashes(_T('osm:message_suppr_confirm'))  ."');"));
                    Osm_Helper::formulaire_fin();
            ?>
                </td>
            <?php
        }
    ?>
        </table>
    <?php
    }
    else {
        Osm_Helper::boite_attention(_T('osm:message_liste_vide'));
    }

    Osm_Helper::fin_page();
}
?>