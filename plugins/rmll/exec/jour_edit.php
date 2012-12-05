<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_jour_edit () {
    
	/* admin ou pas */
	Rmll_Helper::test_acces_admin();
	
	/* quelques controles ? */
	Rmll_Helper::faire_controles();
	
	debut_page(_T('rmll:titre_page_gestion'));
	
	
	debut_gauche();
	
	debut_cadre_enfonce();
	icone_horizontale(_T('rmll:label_liste'), 
		generer_url_ecrire("jour"), null, 
		_DIR_PLUGIN_RMLL.'/img_pack/liste.png');
	icone_horizontale(_T('rmll:label_ajout'),
		generer_url_ecrire("jour_edit"), null, 
		_DIR_PLUGIN_RMLL.'/img_pack/ajout.png');
	fin_cadre_enfonce();
	
	Rmll_Helper::menu_gestion();
	
	debut_droite();
	$table = new Rmll_Db('jour');
	
	/* Récupération des données */
	$id = Rmll_Helper::inPost('id');
	$jour = Rmll_Helper::inPost('jour');
	$mois = Rmll_Helper::inPost('mois');
	$annee = Rmll_Helper::inPost('annee');
	
	if ($id) {
		/* Edition */
		if ($jour !== null && $mois !== null && $annee !== null) {
			if ($table->update(array('date' => sprintf("%d-%02d-%02d", $annee, $mois, $jour)), $id)) {
				Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
				$id = $annee = $mois = $jour = null;
			}
			else
				Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
		}
		else {
			if ($row = $table->get_one($id))
				list($annee, $mois, $jour) = explode('-', $row['date']);
		}
	}
	else {
		/* Ajout */
		if ($jour !== null && $mois !== null && $annee !== null) {
			if ($table->insert(array('date' => sprintf("%d-%02d-%02d", $annee, $mois, $jour)))) {
				Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
				$id = $annee = $mois = $jour = null;
			}
			else
				Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
		}
	}
	
	Rmll_Helper::titre_gros(_T('rmll:label_gestion_jour'));
	Rmll_Helper::titre_moyen(_T('rmll:label_ajout'));
	
	/* remplissage et création du formulaire d'ajout */
	for ($liste_jours = array(), $i=1; $i<=31; $i++) $liste_jours[$i] = $i;
	for ($liste_mois = array(), $i=1; $i<=12;$i++) $liste_mois[$i] = date('F', mktime(0, 0, 0, $i, 15, 2000));
	for ($liste_annees = array(), $i=date('Y'), $n = $i+10; $i<=$n; $i++) $liste_annees[$i] = $i;
	
	Rmll_Helper::formulaire_debut('', array('class' => 'rmll'));
	if ($id)
		Rmll_Helper::formulaire_cache('id', $id);
	
?>
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_jour').' :');
		Rmll_Helper::formulaire_selection("jour", $liste_jours, $jour);
	?>
	</div>
	
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_mois').' :');
		Rmll_Helper::formulaire_selection("mois", $liste_mois, $mois);
	?>
	</div>

	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_annee').' :');
		Rmll_Helper::formulaire_selection("annee", $liste_annees, $annee);
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