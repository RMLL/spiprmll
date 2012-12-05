<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_salle_edit () {

    /* admin ou pas */
    Rmll_Helper::test_acces_admin();

    /* quelques controles ? */
    Rmll_Helper::faire_controles();

    debut_page(_T('rmll:titre_page_gestion'));

    debut_gauche();

    debut_cadre_enfonce();
    icone_horizontale(_T('rmll:label_liste'),
        generer_url_ecrire("salle"), null,
        _DIR_PLUGIN_RMLL.'/img_pack/liste.png');
    icone_horizontale(_T('rmll:label_ajout'),
        generer_url_ecrire("salle_edit"), null,
        _DIR_PLUGIN_RMLL.'/img_pack/ajout.png');
    fin_cadre_enfonce();

    Rmll_Helper::menu_gestion();

    debut_droite();
    $table = new Rmll_Db('salle');

    /* Récupération des données */
    $id = Rmll_Helper::inPost('id');
    $capacite = Rmll_Helper::inPost('capacite');
    $nom = Rmll_Helper::inPost('nom');

    if ($id) {
        /* Edition */
        if ($capacite !== null && $nom !== null && (empty($capacite) || empty($nom))) {
            Rmll_Helper::boite_erreurs(_T('rmll:message_check_err'));
        }
        elseif (!empty($capacite) && !empty($nom)) {
            if ($table->update(array('capacite' => $capacite, 'nom' => $nom), $id)) {
                Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
                $id = $capacite = $nom = null;
            }
            else
                Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
        }
        else {
            if ($row = $table->get_one($id)) {
                $capacite = $row['capacite'];
                $nom = $row['nom'];
            }
        }
    }
    else {
        /* Ajout */
        if ($capacite !== null && $nom !== null) {
            if (!empty($nom)) {
                if ($table->insert(array('capacite' => $capacite, 'nom' => $nom))) {
                    Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
                    $id = $capacite = $nom = null;
                }
                else
                    Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
            }
            else
                Rmll_Helper::boite_erreurs(_T('rmll:message_check_err'));
        }
    }

    Rmll_Helper::titre_gros(_T('rmll:label_gestion_salle'));
    Rmll_Helper::titre_moyen(_T('rmll:label_ajout'));

    /* remplissage et création du formulaire d'ajout */
    for ($liste_capacite = array(), $i=0; $i<=500; $i+=10) $liste_capacite[$i] = $i;

    Rmll_Helper::formulaire_debut('', array('class' => 'rmll'));
    if ($id)
        Rmll_Helper::formulaire_cache('id', $id);

?>
    <div class="element">
    <?php
        Rmll_Helper::formulaire_label(_T('rmll:label_capacite').' :');
        Rmll_Helper::formulaire_selection("capacite", $liste_capacite, $capacite);
    ?>
    </div>

    <div class="element">
    <?php
        Rmll_Helper::formulaire_label(_T('rmll:label_nom').' :');
        Rmll_Helper::formulaire_texte("nom", $nom);
    ?>
    </div>

    <div class="element">
    <?php
        Rmll_Helper::formulaire_soumettre($id ? _T('rmll:label_modifier') : _T('rmll:label_ajouter'));
    ?>
    </div>
<?php
    Rmll_Helper::formulaire_fin();

    echo fin_page();
}
?>