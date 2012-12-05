<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_langue_edit () {
    /* admin ou pas */
    Rmll_Helper::test_acces_admin();
    /* quelques controles ? */
    Rmll_Helper::faire_controles();
    Rmll_Helper::debut_page(_T('rmll:titre_page_gestion'));
    Rmll_Helper::debut_gauche();
    Rmll_Helper::debut_cadre_enfonce();
    Rmll_Helper::icone_horizontale(
        _T('rmll:label_liste'), 'langue','liste.png');
    Rmll_Helper::icone_horizontale(
        _T('rmll:label_ajout'), 'langue_edit','ajout.png');
    Rmll_Helper::fin_cadre_enfonce();
    Rmll_Helper::menu_gestion();
    Rmll_Helper::debut_droite();

    $table = new Rmll_Db('langue');

    /* Récupération des données */
    $id = Rmll_Helper::inPost('id');
    $code = Rmll_Helper::inPost('code');
    $nom = Rmll_Helper::inPost('nom');

    if ($id) {
        /* Edition */
        if ($code !== null && $nom !== null && (empty($code) || empty($nom))) {
            Rmll_Helper::boite_erreurs(_T('rmll:message_check_err'));
        }
        elseif (!empty($code) && !empty($nom)) {
            if ($table->update(array('code' => $code, 'nom' => $nom), $id)) {
                Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
                $id = $code = $nom = null;
            }
            else
                Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
        }
        else {
            if ($row = $table->get_one($id)) {
                $code = $row['code'];
                $nom = $row['nom'];
            }
        }
    }
    else {
        /* Ajout */
        if ($code !== null && $nom !== null) {
            if (!empty($code) && !empty($nom)) {
                if ($table->insert(array('code' => $code, 'nom' => $nom))) {
                    Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
                    $id = $code = $nom = null;
                }
                else
                    Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
            }
            else
                Rmll_Helper::boite_erreurs(_T('rmll:message_check_err'));
        }
    }

    Rmll_Helper::titre_gros(_T('rmll:label_gestion_langue'));
    Rmll_Helper::titre_moyen(_T('rmll:label_ajout'));

    Rmll_Helper::formulaire_debut('', array('class' => 'rmll'));
    if ($id)
        Rmll_Helper::formulaire_cache('id', $id);

?>
    <div class="element">
    <?php
        Rmll_Helper::formulaire_label(_T('rmll:label_code').' :');
        Rmll_Helper::formulaire_texte("code", $code, array('size' => 5, 'maxlength' => '2'));
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

    Rmll_Helper::fin_page();
}
?>