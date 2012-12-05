<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");

    global $rmll_prog_page;
/*
    function cleaner($text) {
        $text = propre($text);
        $text = strip_tags($text);
        $text = utf8_unhtml($text);
        $text = str_replace(array("\n", "\r"), " ", $text);
        $text = preg_replace('/\s\s+/', ' ', $text);
        // suppression des quotes de MS Word (merci le copier/coller)
        $text = str_replace(array(chr(145), chr(146)), "'", $text);
        $text = str_replace(array(chr(147), chr(148)), "\"", $text);
        return $text;
    }
*/

    function csvline($ar) {
        $ret = '';
        for($i=0, $n=count($ar); $i<$n; $i++) {
            $ret .= '"'.str_replace('"', '\'', $ar[$i]).'"';
            if ($i+1 != $n)
                $ret .= ';';
        }
        return $ret."\n";
    }

    $rc = new Rmll_Conference(false);
    $conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);

    printf('"Theme Id";"Theme";"Jour";"Horaire";"Durée";"Salle";"Conf. Id";"Conf.";"Intervenant(s)";"Langue";"Nature";"Niveau";"Url"'."\n");

    foreach($conf as $theme) {
        foreach($theme['articles'] as $article) {
            $data = array();
            $data[] = $theme['id'];
            $data[] = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($theme['titre'])));
            $data[] = $article['data']['jour'];
            $data[] = sprintf("%02d:%02d", $article['data']['heure'], $article['data']['minute'])   ;
            $data[] = $article['data']['duree'];
            $data[] = $article['data']['salle'];
            $data[] = $article['data']['id_article'];
            $data[] = supprimer_numero($article['data']['titre']);
            $data[] = $article['data']['intervenants'];
            $data[] = $article['data']['langue'];
            $data[] = $article['data']['nature'];
            $data[] = $article['data']['niveau'] == '' ? '' : _T('rmll:niveau_code_'.$article['data']['niveau']);
            $data[] = sprintf("http://%s/spip.php?article=%d", $_SERVER['HTTP_HOST'], $article['data']['id_article']);
            echo csvline($data);
        }
    }
?>