<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/rmll.class');

function action_editer_evenement_dist() {
	$securiser_action = charger_fonction('securiser_action', 'inc');
	$securiser_action();

	$id_article = _request('arg');
	$redirect = urldecode(_request('redirect'));

	$evenement = Rmll_Helper::inPost('evenement');
	$jour = Rmll_Helper::inPost('jour');
	$horaire = Rmll_Helper::inPost('horaire');
	$duree = Rmll_Helper::inPost('duree');
	$langue = Rmll_Helper::inPost('langue');
	$nature = Rmll_Helper::inPost('nature');
	$niveau = Rmll_Helper::inPost('niveau');
	$salle = Rmll_Helper::inPost('salle');
	$intervenants = Rmll_Helper::inPost('intervenants');
	$video = Rmll_Helper::inPost('video');

	$suppr_evenement = Rmll_Helper::inPost('suppr_evenement');

	$ev = new Rmll_Event();
	if ($suppr_evenement) {
		$ev->delete($suppr_evenement);
	}
	elseif ($evenement) {
		$ev->update(array(
			'id_jour' => $jour, 'id_horaire' => $horaire,
			'duree' => $duree, 'id_langue' => $langue,
			'id_nature' => $nature, 'id_niveau' => $niveau,
			'id_salle' => $salle,
			'id_article' => $id_article, 'intervenants' => $intervenants,
			'video' => $video), $evenement);
	}
	else {
		$ev->save(array(
			'id_jour' => $jour, 'id_horaire' => $horaire,
			'duree' => $duree, 'id_langue' => $langue,
			'id_nature' => $nature, 'id_niveau' => $niveau,
			'id_salle' => $salle,
			'id_article' => $id_article, 'intervenants' => $intervenants,
			'video' => $video));
	}

	if ($redirect)
		redirige_par_entete($redirect);
}

?>