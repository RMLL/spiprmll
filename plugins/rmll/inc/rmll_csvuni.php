<?php
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_options.php';
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_fonctions.php';
    require_once _DIR_PLUGIN_RMLL.'inc/rmll.class.php';

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

    function date_end($day, $time, $duration) {
        $end = '';
        if ($day != '' && $time != '') {
            list($y, $m, $d) = explode('-', $day);
            list($h, $min) = explode(':', $time);
            $ts = mktime($h, $min, 0, $m, $d, $y);
            $ts += $duration*60;
            $end = strftime('%H:%M', $ts);
        }
        return $end;
    }

    function get_license($data) {
        $license = '';
        foreach(explode("\n", str_replace("\r", "\n", $data)) as $line) {
            if (preg_match('/^CFP_LICENSE=(?P<license>.*)$/U', $line, $matches)) {
                $license = $matches['license'];
            }
        }
        return $license;
    }

    $t = isset($_GET['t']) ? explode(',', $_GET['t']) : array();
    $d = isset($_GET['d']) ? explode(',', $_GET['d']) : array();
    $n = isset($_GET['n']) ? explode(',', $_GET['n']) : array();
    $addid = isset($_GET['addid']) ? true : false;
    $onlycanceled = isset($_GET['onlycanceled']) ? true : false;

    $filterspos = array();
    $filtersneg = array();
    foreach($n as $i) {
        if (substr($i, 0, 1) == '!') {
            $filtersneg[] = substr($i, 1);
        }
        else {
            $filterspos[] = $i;
        }
    }
    //var_dump($filtersneg, $filterspos);

    $rc = new Rmll_Conference(false);
    $conf = $rc->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang']);

    $header = sprintf('"Salle";"Thème";"Intervenant(s)";"Titre";"Date";"Début";"Fin";"Licence";"Pour accord";"Signature"'."\n");
    if ($addid) {
        $header = sprintf('"Id";%s', $header);
    }
    printf($header);

    foreach($conf as $theme) {
        if (count($t) > 0 && !in_array($theme['id'], $t)) {
            continue;
        }
//if ($theme['id'] != 19) continue;
        foreach($theme['articles'] as $article) {
            $canceled = false;
            if (mb_strstr($article['data']['titre'], 'ANNULÉ') !== false ||  mb_strstr($article['data']['titre'], 'CANCELED') !== false) {
                $canceled = true;
            }
            if($canceled) {
                if (!$onlycanceled) {
                    continue;
                }
            }
            elseif($onlycanceled) {
                continue;
            }

            if ((!empty($filtersneg) && in_array($article['data']['nature_code'], $filtersneg)) || (!empty($filterspos) && !in_array($article['data']['nature_code'], $filterspos))) {
                continue;
            }
            
            if (count($d) > 0 && !in_array($article['data']['jour'], $d)) {
                continue;
            }
//var_dump($article); break;
            $start_time = sprintf("%02d:%02d", $article['data']['heure'], $article['data']['minute']);
            $end_time = date_end($article['data']['jour'], $start_time, $article['data']['duree']);
            $license = get_license($article['data']['notes']);
            $data = array();
            if ($addid) {
                $data[] = $article['data']['id_conference'];
            }
            $data[] = $article['data']['salle'];
            $data[] = trim(textebrut(supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($theme['titre'])))));
            $data[] = $article['data']['intervenants'];
            $data[] = trim(textebrut(supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($article['data']['titre']), $article['data']['drap']))));
            $data[] = $article['data']['jour'];
            $data[] = $start_time;
            $data[] = $end_time;
            $data[] = $license;
            $data[] = '';
            $data[] = '';
            //$data[] = $article['data']['nature_code'];
            echo csvline($data);
        }
    }
?>
