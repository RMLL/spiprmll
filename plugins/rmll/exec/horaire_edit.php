<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_horaire_edit () {
    
	/* admin ou pas */
	Rmll_Helper::test_acces_admin();
	
	/* quelques controles ? */
	Rmll_Helper::faire_controles();
	
	debut_page(_T('rmll:titre_page_gestion'));
	
	
	debut_gauche();
	
	debut_cadre_enfonce();
	icone_horizontale(_T('rmll:label_liste'), 
		generer_url_ecrire("horaire"), null, 
		_DIR_PLUGIN_RMLL.'/img_pack/liste.png');
	icone_horizontale(_T('rmll:label_ajout'),
		generer_url_ecrire("horaire_edit"), null, 
		_DIR_PLUGIN_RMLL.'/img_pack/ajout.png');
	fin_cadre_enfonce();
	
	Rmll_Helper::menu_gestion();
	
	debut_droite();
	$table = new Rmll_Db('horaire');
	
	/* Récupération des données */
	$id = Rmll_Helper::inPost('id');
	$heure = Rmll_Helper::inPost('heure');
	$minute = Rmll_Helper::inPost('minute');
	
	if ($id) {
		/* Edition */
		if ($heure !== null && $minute !== null) {
			if ($table->update(array('heure' => $heure, 'minute' => $minute), $id)) {
				Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
				$id = $heure = $minute = null;
			}
			else
				Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
		}
		else {
			if ($row = $table->get_one($id)) {
				$heure = $row['heure'];
				$minute = $row['minute'];
			}
		}
	}
	else {
		/* Ajout */
		if ($heure !== null && $minute !== null) {
			if ($table->insert(array('heure' => $heure, 'minute' => $minute))) {
				Rmll_Helper::boite_infos(_T('rmll:message_sauv_ok'));
				$id = $heure = $minute = null;
			}
			else
				Rmll_Helper::boite_erreurs(_T('rmll:message_sauv_err')."<br/>".$table->error());
		}
	}
	
	Rmll_Helper::titre_gros(_T('rmll:label_gestion_horaire'));
	Rmll_Helper::titre_moyen(_T('rmll:label_ajout'));
	
	/* remplissage et création du formulaire d'ajout */
	for ($liste_heures = array(), $i=7; $i<=20; $i++) $liste_heures[$i] = sprintf("%02d", $i);
	for ($liste_minutes = array(), $i=0; $i<=55; $i+=5) $liste_minutes[$i] = sprintf("%02d", $i);
	
	Rmll_Helper::formulaire_debut('', array('class' => 'rmll'));
	if ($id)
		Rmll_Helper::formulaire_cache('id', $id);
	
?>
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_heure').' :');
		Rmll_Helper::formulaire_selection("heure", $liste_heures, $heure);
	?>
	</div>
	
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_minutes').' :');
		Rmll_Helper::formulaire_selection("minute", $liste_minutes, $minute);
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