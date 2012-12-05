<?php
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_options.php';
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_fonctions.php';
    require_once _DIR_PLUGIN_RMLL.'inc/rmll.class.php';

    global $rmll_prog_page;

    function cleaner($text) {
        $text = nettoyer_raccourcis_typo($text);
        $text = extraire_multi($text);
        $text = supprimer_numero($text);
        $text = propre($text);
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        /*
        $text = utf8_unhtml($text);
        // special chars
        $text = str_replace(chr(197).chr(147), 'oe', $text); // œ
        $text = str_replace(chr(194).chr(160), ' ', $text); // espace insécable
        $text = str_replace(array("\n", "\r"), " ", $text);
        $text = preg_replace('/\s\s+/', ' ', $text);
        // suppression des quotes de MS Word (merci le copier/coller)
        $text = str_replace(array(chr(145), chr(146)), "'", $text);
        $text = str_replace(array(chr(147), chr(148)), "\"", $text);
        */
        return $text;
    }

    function compute_end($start, $ndays) {
        $end = $start;
        $date = strptime($start.' 12:00:00', '%Y-%m-%d %H:%M:%S');
        if ($date !== false) {
            $ts = mktime($date['tm_hour'], $date['tm_min'], $date['tm_sec'],
                $date['tm_mon']+1, $date['tm_mday'], 1900+$date['tm_year']);
            $end = strftime('%Y-%m-%d', $ts+ ($ndays*86400));
        }
        return $end;
    }

    function compute_dayindex($start, $current) {
        $date = strptime($start.' 12:00:00', '%Y-%m-%d %H:%M:%S');
        $ts_start = mktime($date['tm_hour'], $date['tm_min'], $date['tm_sec'],
                $date['tm_mon']+1, $date['tm_mday'], 1900+$date['tm_year']);
        $date = strptime($current.' 12:00:00', '%Y-%m-%d %H:%M:%S');
        $ts_current = mktime($date['tm_hour'], $date['tm_min'], $date['tm_sec'],
                $date['tm_mon']+1, $date['tm_mday'], 1900+$date['tm_year']);
        $ret = (int) (($ts_current - $ts_start) / 86400);
        return $ret+1;
    }

    function writeNoCData($text) {
        // dirty hack
        return str_replace(array('<', '>'), array('≪', '≫'), $text);
    }

    $lang = $GLOBALS['lang'];
    if (!in_array($lang, explode(',', RMLL_XMLSCHEDULE_SUPPORTED_LANGS))) {
        $lang = RMLL_XMLSCHEDULE_DEFAULT_LANG;
    }

    $rc = new Rmll_Conference(false);
    $conf = $rc->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang']);

    $allconfs = array();

    foreach($conf as $theme) {
        foreach($theme['articles'] as $article) {
            $day = $article['data']['jour'];
            if ($day == '') {
                continue;
            }
            if (!array_key_exists($day, $allconfs)) {
                $allconfs[$day] = array();
            }
            $room = $article['data']['salle'];
            if ($room == '') {
                $room = _T('rmll:undefined_room');
            }
            if (!array_key_exists($room, $allconfs[$day])) {
                $allconfs[$day][$room] = array();
            }
            $article['data']['theme'] = $theme;
            $allconfs[$day][$room][] = $article['data'];
/*
            $data = array();
            $data[] = $theme['id'];
            $data[] = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($theme['titre'])));
            $data[] = $article['data']['jour'];
            $data[] = sprintf("%02d:%02d", $article['data']['heure'], $article['data']['minute'])   ;
            $data[] = $article['data']['duree'];
            $data[] = $article['data']['salle'];
            $data[] = $article['data']['id_article'];
            $data[] = $article['data']['id_orig'];
            $data[] = supprimer_numero($article['data']['titre']);
            $data[] = $article['data']['intervenants'];
            $data[] = $article['data']['langue'];
            $data[] = $article['data']['nature'];
            $data[] = $article['data']['niveau'] == '' ? '' : _T('rmll:niveau_code_'.$article['data']['niveau']);
            $data[] = sprintf("http://%s/spip.php?article%d", $_SERVER['HTTP_HOST'], $article['data']['id_article']);
            $data[] = sprintf("http://%s/ecrire/?exec=articles&id_article=%d", $_SERVER['HTTP_HOST'], $article['data']['id_article']);
*/
            //break;
        }
        //break;
    }

    //var_dump($allconfs);

    ksort($allconfs);

    //var_dump($allconfs);
    //die();

    $w = new XMLWriter();
    $w->openURI('php://output');
    $w->startDocument('1.0', 'UTF-8');
    $w->setIndent(true);
    $w->setIndentString("\t");
    
    $w->startElement('schedule');
    $w->startElement('conference');
        $w->startElement('title');
        $w->text(writeNoCData(RMLL_XMLSCHEDULE_TITLE));
        $w->fullEndElement();
        $w->startElement('subtitle');
        $w->text(writeNoCData(RMLL_XMLSCHEDULE_SUBTITLE));
        $w->fullEndElement();
        $w->startElement('venue');
        $w->text(writeNoCData(RMLL_XMLSCHEDULE_PLACE));
        $w->fullEndElement();
        $w->startElement('city');
        $w->text(writeNoCData(RMLL_XMLSCHEDULE_CITY));
        $w->fullEndElement();
        $w->startElement('start');
        $w->text(RMLL_XMLSCHEDULE_START);
        $w->fullEndElement();
        $w->startElement('end');
        $w->text(compute_end(RMLL_XMLSCHEDULE_START, RMLL_XMLSCHEDULE_NUMDAYS));
        $w->fullEndElement();
        $w->startElement('days');
        $w->text(RMLL_XMLSCHEDULE_NUMDAYS);
        $w->fullEndElement();
        $w->startElement('day_change');
        $w->text('00:00');
        $w->fullEndElement();
        $w->startElement('timeslot_duration');
        $w->text(RMLL_XMLSCHEDULE_DURATION);
        $w->fullEndElement();
    $w->fullEndElement();

    foreach($allconfs as $date => $confsdate) {
        $w->startElement('day');
        $w->writeAttribute('index', compute_dayindex(RMLL_XMLSCHEDULE_START, $date));
        $w->writeAttribute('date', $date);
        foreach($confsdate as $room => $conflist) {
            $w->startElement('room');
            $w->writeAttribute('name', $room);

            foreach($conflist as $cnf) {
                $w->startElement('event');
                $w->writeAttribute('id', $cnf['id_article']);

                    $w->startElement('start');
                    $w->text(sprintf("%02d:%02d", $cnf['heure'], $cnf['minute']));
                    $w->fullEndElement();

                    $w->startElement('duration');
                    $w->text(sprintf('%02d:%02d', (int) $cnf['duree']/60, (int) $cnf['duree']%60));
                    $w->fullEndElement();

                    $w->startElement('room');
                    $w->text($room);
                    $w->fullEndElement();

                    $w->startElement('slug');
                    $w->text('conf_'.$cnf['id_article']);
                    $w->fullEndElement();

                    $w->startElement('title');
                    $w->text(writeNoCData(cleaner($cnf['titre'])));
                    $w->fullEndElement();

                    $w->startElement('subtitle');
                    $w->text('');
                    $w->fullEndElement();

                    $w->startElement('track');
                    $w->text(writeNoCData(cleaner($cnf['theme']['titre'])));
                    $w->fullEndElement();

                    $w->startElement('type');
                    $w->text($cnf['nature_code']);
                    $w->fullEndElement();

                    $w->startElement('language');
                    $w->text(aff_langue($cnf['drap']));
                    $w->fullEndElement();

                    $w->startElement('abstract');
                    $w->text(writeNoCData(''));
                    $w->fullEndElement();

                    $w->startElement('description');
                    $w->text(writeNoCData(cleaner($cnf['texte'])));
                    $w->fullEndElement();

                    $w->startElement('persons');
                        $persons = explode(',', trim($cnf['intervenants']));
                        $p = 0;
                        foreach($persons as $person) {
                            $person = trim($person);
                            if ($person != '') {
                                $w->startElement('person');
                                $w->writeAttribute('id', 1000*$cnf['id_article'] + ++$p);
                                $w->text($person);
                                $w->fullEndElement();
                            }
                        }
                    $w->fullEndElement();

                $w->fullEndElement();
            }
            $w->fullEndElement();
        }
        $w->fullEndElement();
    }


    $w->fullEndElement();

    $w->endDocument();
    $w->flush();













?>