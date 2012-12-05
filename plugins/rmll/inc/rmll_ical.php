<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");

    global $rmll_prog_page, $rmll_prog_date, $rmll_prog_theme;

    function cleaner($text) {
        $text = propre($text);
        $text = strip_tags($text);
        $text = utf8_unhtml($text);
        $text = str_replace(array("\n", "\r"), " ", $text);
        $text = preg_replace('/\s\s+/', ' ', $text);
        /* suppression des quotes de MS Word (merci le copier/coller) */
        $text = str_replace(array(chr(145), chr(146)), "'", $text);
        $text = str_replace(array(chr(147), chr(148)), "\"", $text);
        return $text;
    }

    $rc = new Rmll_Conference();
    $conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Rmll.info
X-WR-CALNAME:<?php echo _T('rmll:feed_ical_name')."\n"; ?>
X-WR-TIMEZONE:Europe/Paris
CALSCALE:GREGORIAN
X-WR-CALDESC:<?php echo _T('rmll:feed_ical_desc')."\n"; ?>
<?php
    foreach($conf as $theme) {
        /* si mode theme, on filtre */
        if (!empty($rmll_prog_theme) && $theme['id'] != $rmll_prog_theme)
            continue;
        foreach($theme['articles'] as $article) {
            /* on ne prend que les articles horodatés */
            if ($article['data']['heure'] == 0 || $article['data']['duree'] == 0)
                continue;
            /* si mode journalier, on filtre */
            if (!empty($rmll_prog_date) && $article['data']['jour'] != $rmll_prog_date)
                continue;

            list($y, $m, $d) = explode("-", $article['data']['jour']);
            $tstamp = mktime(intval($article['data']['heure']), intval($article['data']['minute']), 0,
                    intval($m), intval($d), intval($y));
            $start = date('Ymd', $tstamp).'T'.date('His', $tstamp);
            $tstamp += intval($article['data']['duree'])*60;
            $end = date('Ymd', $tstamp).'T'.date('His', $tstamp);
            $uid = sprintf("%s@%s", $article['data']['id_article'], $_SERVER['HTTP_HOST']);
            $summary = cleaner(utf8_unhtml(supprimer_numero($article['data']['titre'])));
            $url = sprintf("http://%s/spip.php?article=%d", $_SERVER['HTTP_HOST'], $article['data']['id_article']);
            $desc = cleaner($article['data']['descriptif']);
            $lieu = $article['data']['salle'];
?>
BEGIN:VEVENT
DTSTART:<?php print $start."\n"; ?>
DTEND:<?php print $end."\n"; ?>
UID:<?php print $uid."\n"; ?>
SUMMARY:<?php print $summary."\n"; ?>
URL:<?php print $url."\n"; ?>
DESCRIPTION:<?php print $desc."\n"; ?>
LOCATION:<?php print $lieu."\n"; ?>
END:VEVENT
<?php
        }
    }
?>
END:VCALENDAR