<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");
    global $rmll_prog_page;

    $excluded_themes = array();
/*
    function callback_common($matches, $format) {

    }

    function callback_it($matches) {
        return callback_common($matches, '\textit{%s}');
    }

    function callback_bf($matches) {
        return callback_common($matches, '\textbf{%s}');
    }
*/
    function cleaner($text) {
        //$text = propre($text);
        $text = strip_tags($text);
        $text = utf8_unhtml($text);
        //$text = str_replace("\n", " ", $text);
        //$text = preg_replace('/\s\s+/', ' ', $text);

        // transformation latex
		/*
		$text = preg_replace("/([^{])\{\{([^}]*)\}\}([^}])/sU", "\\1\\textbf{\\2}\\3", $text);
        $text = preg_replace("/([^{])\{([^}]*)\}([^}])/sU", "\\1\\textit{\\2}\\3", $text);
		*/

		//$text = preg_replace("/([^{]*)\{\{\{([^}]*)\}\}\}([^}]*)/sU", "\\1\\begin{center}\\bfshape \\2\\end{center}\\3", $text);
		//$text = preg_replace("/([^{]*)\{\{([^}]*)\}\}([^}]*)/sU", "\\1\\textbf{\\2}\\3", $text);
        //$text = preg_replace("/([^{]*)\{([^}]*)\}([^}]*)/sU", "\\1\\textit{\\2}\\3", $text);

		$text = preg_replace("/([^{]*)\{\{\{(.*)\}\}\}([^}]*)/sU", "\\1[h]\\2[/h]\\3", ' '.$text.' ');
		$text = preg_replace("/([^{]*)\{\{(.*)\}\}([^}]*)/sU", "\\1[b]\\2[/b]\\3", $text);
        $text = preg_replace("/([^{]*)\{(.*)\}([^}]*)/sU", "\\1[i]\\2[/i]\\3", $text);

		$text = preg_replace("#\[h\](.*)\[/h\]#sU", "\\begin{center}\\bfshape \\1\\end{center}", $text);
		$text = preg_replace("#\[b\](.*)\[/b\]#sU", "\\textbf{\\1}", $text);
		$text = preg_replace("#\[i\](.*)\[/i\]#sU", "\\textit{\\1}", $text);

        //$text = preg_replace("#\[i\](.*)\[/i\]#sU", "callback_it", $text);

		//$text = preg_replace("#\[h\](.*)\[/h\]#sU", "\\begin{center}\\bfshape \\1\\end{center}", $text);
		//$text = preg_replace("/([^{]*)\{\{([^}]*)\}\}([^}]*)/sU", "\\1\\textbf{\\2}\\3", $text);
        //$text = preg_replace("/([^{]*)\{([^}]*)\}([^}]*)/sU", "\\1\\textit{\\2}\\3", $text);

        // les 'oe' sous toutes leurs formes
        $text = str_replace(chr(197).chr(147), '{\oe}', $text);
        $text = str_replace(chr(197).chr(146), '{\OE}', $text);

        // les points de suspensions unicode
        $text = str_replace(chr(226).chr(128).chr(166).chr(10), '...', $text);

        // suppression des quotes de MS Word (merci le copier/coller)
        $text = str_replace(array(chr(145), chr(146), chr(226).chr(128).chr(153)), "'", $text);
        $text = str_replace(array(chr(147), chr(148)), "\"", $text);

        $text = supprimer_numero($text);

        // on balance du latin1
        $text = utf8_decode($text);

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

    function salle2salle ($val) {
        $salle = $val = trim($val);
        $p = strpos($salle, '-');
        if ($p)
            $salle = trim(substr($salle, 0, $p));
        elseif ($val == '')
            $salle = 'vide';
        else
            $salle = 'N/A';

        // ugly hack 2009
        if ($GLOBALS['lang'] == 'en') {
            $salle = preg_replace('/^Salle /i', 'Room ', $salle);
            $salle = preg_replace('/^Amphi /i', 'Lecture room ', $salle);
        }
        return $salle;

    }

    function salle2bat ($val) {
        $bat = $val = trim($val);
        $p = strpos($bat, '-');
        if ($p)
            $bat = trim(substr($bat, $p+1, strlen($bat)));
        elseif ($val == '')
            $bat = 'vide';
        else
            $bat = 'N/A';
        return $bat;
    }

    function theme2theme($val) {
        // hack 2009
        $ret = $val;
        if ($val == '47')
            $ret = '42b';
        return $ret;
    }

    $rc = new Rmll_Conference();
    $conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);

    $longdesc = isset($_GET['longdesc']) && $_GET['longdesc'] == 1;

    /* Affichage des themes */
    foreach($conf as $theme) {
        if (in_array($theme['id'], $excluded_themes))
            continue;
?>
\Theme{<?php echo theme2theme($theme['id']); ?>}{<?php echo cleaner(extraire_multi($theme['titre'])); ?>}{
}
{%MID_THEME
<?php echo cleaner(extraire_multi($longdesc ? $theme['texte']: $theme['descriptif']))."\n"; ?>
}%FIN_THEME
<?php
    }

    /* Affichage conferences */
    foreach($conf as $theme) {
        if (in_array($theme['id'], $excluded_themes))
            continue;
        foreach($theme['articles'] as $article) {
            // on exclus les showcase (2009)
            if ($article['data']['nature_code'] == 'demo')
                continue;

            // on ne prend que les articles horodatés
            if ($article['data']['heure'] == 0 || $article['data']['duree'] == 0)
                continue;
?>

\Salle{<?php echo theme2theme($theme['id']); ?>}{<?php printf("%s %02d:%02d", $article['data']['jour'], $article['data']['heure'], $article['data']['minute']); ?>}{<?php echo nature2nature($article['data']['nature_code']); ?>}{<?php echo salle2salle($article['data']['salle']); ?>}
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
        <?php echo cleaner($longdesc ? $article['data']['texte'] : $article['data']['descriptif'])."\n"; ?>
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