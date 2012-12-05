<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

include_spip('inc/rmll.class');

function exec_ajouter_conference_dist() {

	$id_article = intval(_request('id_article'));

	$ev = new Rmll_Event();
	$res = $ev->Form($id_article, 'articles', true);

	ajax_retour($res, false);
}

?>