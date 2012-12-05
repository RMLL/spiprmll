<?php
	global $rmll_prog_page;

	include("plugins/rmll/inc/rmll.class.php");

	$excluded_themes = array(65);

	function snip2trad($v) {
		$ret = $v;
		if (isset($GLOBALS[$GLOBALS['idx_lang']][$v]))
			$ret = $GLOBALS[$GLOBALS['idx_lang']][$v];
		return $ret;
	}

	function cleaner($text) {
		//$text = propre($text);
		$text = strip_tags($text);
		$text = utf8_unhtml($text);
		//$text = str_replace("\n", " ", $text);
		//$text = preg_replace('/\s\s+/', ' ', $text);
		/* suppression des quotes de MS Word (merci le copier/coller) */
		$text = str_replace(array(chr(145), chr(146), "’"), "'", $text);
		$text = str_replace(array(chr(147), chr(148)), "\"", $text);
	
		$text = supprimer_numero($text);
		$text = utf8_decode($text);
		
		// transformation latex
		$text = preg_replace("/([^{])\{([^}]*)\}([^}])/", "\\1\\textit{\\2}\\3", $text);
		$text = preg_replace("/([^{])\{\{([^}]*)\}\}([^}])/", "\\1\\textbf{\\2}\\3", $text);
		
		
		$text = trim($text);

		return $text;
	}

	function nature2nature($nature) {
		$ret = '';
		switch($nature) {
			case 'ag':
				$ret = 'ag';
				break;
			case 'atl':
				$ret = 'at';
				break;
			case 'conf':
				$ret = 'pr';
				break;
			case 'plen':
				$ret = 'pl';
				break;
			case 'tabler':
				$ret = 'tr';
				break;
		}
		return $ret;
	}

	function salle2salle ($salle) {
		$salle = $salle;
		$p = strpos($salle, '-');
		if ($p)
			$salle = substr($salle, 0, $p);
		$salle = trim($salle);
		return ($salle == '') ? 'vide' : $salle;
	}

	function salle2bat ($salle) {
		$bat = $salle;
		$p = strpos($salle, '-');
		if ($p)
			$bat = substr($salle, $p+1, strlen($salle));

		$bat = trim($bat);
		return ($bat == '') ? 'vide' : $bat;
	}

	$rc = new Rmll_Conference();
	$conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);

	/* Affichage des themes */
	foreach($conf as $theme) {
		if (in_array($theme['id'], $excluded_themes))
			continue;
?>
\Theme{<?php echo $theme['id']; ?>}{<?php echo cleaner(extraire_trad($theme['titre'])); ?>}{
}
{%MID_THEME
<?php echo cleaner(extraire_trad($theme['descriptif']))."\n"; ?>
}%FIN_THEME
<?php
	}
	
	/* Affichage conferences */
	foreach($conf as $theme) {
		if (in_array($theme['id'], $excluded_themes))
			continue;
		foreach($theme['articles'] as $article) {
			// on ne prend que les articles horodatés
			if ($article['data']['heure'] == 0 || $article['data']['duree'] == 0)
				continue;
?>

\Salle{<?php echo $theme['id']; ?>}{<?php printf("%s %02d:%02d", $article['data']['jour'], $article['data']['heure'], $article['data']['minute']); ?>}{<?php echo nature2nature($article['data']['nature_code']); ?>}{<?php echo salle2salle($article['data']['salle']); ?>}
\Batiment{<?php echo $theme['id']; ?>}{<?php printf("%s %02d:%02d", $article['data']['jour'], $article['data']['heure'], $article['data']['minute']); ?>}{<?php echo nature2nature($article['data']['nature_code']); ?>}{<?php echo salle2bat($article['data']['salle']); ?>}
\<?php echo ($article['data']['nature_code'] == 'atl') ? 'Atelier' : 'Conference' ?>{<?php echo $theme['id']; ?>}{<?php printf("%s %02d:%02d", $article['data']['jour'], $article['data']['heure'], $article['data']['minute']); ?>}
	{
		\Titre{<?php echo cleaner($article['data']['titre']); ?>}
		\Duree{<?php echo cleaner($article['data']['duree']); ?>}
		\SousTitre{}
		\Nature{<?php echo nature2nature($article['data']['nature_code']); ?>}
		\Langue{<?php echo cleaner($article['data']['drap']); ?>}
		\Auteur{<?php echo str_replace("\n", " ", cleaner($article['data']['intervenants'])); ?>}
	}
	{
		<?php echo cleaner($article['data']['descriptif'])."\n"; ?>
	}
<?php
		/*
			// si mode journalier, on filtre
			if (!empty($rmll_prog_date) && $article['data']['jour'] != $rmll_prog_date)
				continue;

			list($y, $m, $d) = explode("-", $article['data']['jour']);
			$tstamp = mktime(intval($article['data']['heure']), intval($article['data']['minute']), 0,
					intval($m), intval($d), intval($y));
			$start = date('Ymd', $tstamp).'T'.date('His', $tstamp);
			$tstamp += intval($article['data']['duree'])*60;
			$end = date('Ymd', $tstamp).'T'.date('His', $tstamp);
			$uid = sprintf("%s@%s", $article['data']['id_article'], $_SERVER['HTTP_HOST']);
			$summary = utf8_unhtml(supprimer_numero($article['data']['titre']));
			$url = sprintf("http://%s/spip.php?article=%d", $_SERVER['HTTP_HOST'], $article['data']['id_article']);
			$desc = cleaner($article['data']['descriptif']);
			$lieu = $article['data']['salle'];
		*/
		}
	}
?>