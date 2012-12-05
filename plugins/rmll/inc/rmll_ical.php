<?php
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_options.php';
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_fonctions.php';
    require_once _DIR_PLUGIN_RMLL.'inc/rmll.class.php';

class RmllScheduleIcal {

    var $confObj = null;
    var $theme = 0;
    var $keyword= 0;

    function RmllScheduleIcal() {
        $this->confObj = new Rmll_Conference();

        $this->theme = isset($_GET['t']) ? (int) $_GET['t'] : 0;
        $this->keyword = isset($_GET['k']) ? (int) $_GET['k'] : 0;
    }

    function cleaner($text) {
        $text = propre($text);
        $text = strip_tags($text);
        $text = utf8_unhtml($text);
        $text = str_replace(array("\n", "\r"), " ", $text);
        $text = preg_replace('/\s\s+/', ' ', $text);
        /* hyphens */
        $text = str_replace(array(chr(226).chr(128).chr(148), chr(226).chr(136).chr(146)), "-", $text);
        /* symbole oe */
        $text = str_replace(chr(197).chr(147), "oe", $text);
        /* symbole unicode <-> */
        $text = str_replace(chr(226).chr(134).chr(148), "<->", $text);
        /* suppression des quotes de MS Word (merci le copier/coller) */
        $text = str_replace(array(chr(145), chr(146)), "'", $text);
        $text = str_replace(array(chr(147), chr(148)), "\"", $text);
        return $text;
    }

    function display() {
        $conf = $this->confObj->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang'], RMLL_KEYWORDS_GROUP_ID);

        printf("BEGIN:VCALENDAR\n");
        printf("VERSION:2.0\n");
        printf("PRODID:-//Rmll.info\n");
        printf("X-WR-CALNAME:%s\n", _T('rmll:feed_ical_name'));
        printf("X-WR-TIMEZONE:Europe/Paris\n");
        printf("CALSCALE:GREGORIAN\n");
        printf("X-WR-CALDESC:%s\n", _T('rmll:feed_ical_desc'));

        foreach($conf as $theme) {
            /* si mode theme, on filtre */
            if ($this->theme > 0 &&  $this->theme != $theme['id']) {
                continue;
            }

            foreach($theme['articles'] as $article) {
                // si mode mot-clés
                if ($this->keyword > 0 && !array_key_exists($this->keyword, $article['keywords'])) {
                    continue;
                }

                /* on ne prend que les articles horodatés */
                if ($article['data']['heure'] == 0 || $article['data']['duree'] == 0) {
                    continue;
                }

                list($y, $m, $d) = explode("-", $article['data']['jour']);
                $tstamp = mktime(intval($article['data']['heure']), intval($article['data']['minute']), 0,
                        intval($m), intval($d), intval($y));
                $start = date('Ymd', $tstamp).'T'.date('His', $tstamp);
                $tstamp += intval($article['data']['duree'])*60;
                $end = date('Ymd', $tstamp).'T'.date('His', $tstamp);
                $uid = sprintf("%s@%s", $article['data']['id_article'], $_SERVER['HTTP_HOST']);
                $summary = $this->cleaner(supprimer_numero($article['data']['titre']));
                $url = sprintf("http://%s/spip.php?article%d", $_SERVER['HTTP_HOST'], $article['data']['id_article']);
                $desc = $this->cleaner($article['data']['descriptif']);
                $lieu = $article['data']['salle'];

                printf("BEGIN:VEVENT\n");
                printf("DTSTART:%s\n", $start);
                printf("DTEND:%s\n", $end);
                printf("UID:%s\n", $uid);
                printf("SUMMARY:%s\n", $summary);
                printf("URL:%s\n", $url);
                printf("DESCRIPTION:%s\n", $desc);
                printf("LOCATION:%s\n", $lieu);
                printf("END:VEVENT\n");
            }
        }
        printf("END:VCALENDAR\n");
    }
}

?>