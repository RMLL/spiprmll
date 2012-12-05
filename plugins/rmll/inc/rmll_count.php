<?php
	global $rmll_prog_page;

	include("rmll.class.php");

	$rc = new Rmll_Conference();
	$conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);

	$nb_themes = 0;
	$nb_confs = 0;
	$nb_ateliers = 0;

	foreach($conf as $theme) {
		$nb_themes++;
		foreach($theme['articles'] as $article) {
			switch($article['data']['id_nature']) {
				case 1:
					$nb_confs++;
					break;
				case 2:
					$nb_ateliers++;
					break;
			}
		}
	}

	printf("#nombre_de_themes:nombre_conferences_et_ateliers:nombre_de_conference:nombre_d_ateliers\n");
	printf("%d:%d:%d:%d\n", $nb_themes, $nb_confs + $nb_ateliers, $nb_confs, $nb_ateliers);
?>