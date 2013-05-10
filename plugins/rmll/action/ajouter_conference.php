<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/rmll.class');

function action_ajouter_conference_dist() {
    $securiser_action = charger_fonction('securiser_action', 'inc');
    $securiser_action();

    $id_article = _request('arg');

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
    $notes = Rmll_Helper::inPost('notes');
    $auditeurs = Rmll_Helper::inPost('auditeurs');

    $action = '';
    $suppr_evenement = Rmll_Helper::inPost('suppr_evenement');

    $ev = new Rmll_Event();
    if ($suppr_evenement) {
        $ev->delete($suppr_evenement);
        $action = 'del';
    }
    elseif ($evenement) {
        $ev->update(array(
            'id_jour' => $jour, 'id_horaire' => $horaire,
            'duree' => $duree, 'id_langue' => $langue,
            'id_nature' => $nature, 'id_niveau' => $niveau,
            'id_salle' => $salle,
            'id_article' => $id_article, 'intervenants' => $intervenants,
            'video' => $video, 'notes' => $notes, 
	    'auditeurs' => (int) $auditeurs), $evenement);
        $action = 'update';
    }
    else {
        $ev->save(array(
            'id_jour' => $jour, 'id_horaire' => $horaire,
            'duree' => $duree, 'id_langue' => $langue,
            'id_nature' => $nature, 'id_niveau' => $niveau,
            'id_salle' => $salle,
            'id_article' => $id_article, 'intervenants' => $intervenants,
            'video' => $video, 'notes' => $notes, 
	    'auditeurs' => (int) $auditeurs));
        $action = 'add';
    }

    $redirect = urldecode(_request('redirect'));
    if ($redirect) {
        redirige_par_entete($redirect);
    }
}

?>