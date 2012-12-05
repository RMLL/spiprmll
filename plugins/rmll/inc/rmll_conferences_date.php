<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");

    global $rmll_prog_date, $rmll_prog_page, $rmll_periods, $rmll_themes_rubriques;

    $objHoraire = new Rmll_Db('horaire');
    $horaires = $objHoraire->get_all('heure, minute');

    $objJour = new Rmll_Db('jour');
    $jours = $objJour->get_all();

    $rc = new Rmll_Conference();
    $conf = array();

    function get_prev_day() {
        global $rmll_prog_date, $jours;
        $ret = null;
        $prev = null;
        if (!empty($jours)) {
            foreach($jours as $j) {
                if ($j['date'] == $rmll_prog_date) {
                    $ret = $prev;
                    break;
                }
                else
                    $prev = $j['date'];
            }
        }
        return $ret;
    }

    function get_next_day() {
        global $rmll_prog_date, $jours;
        $ret = null;
        if (!empty($jours)) {
            for($i=0, $n=count($jours); $i<$n; $i++) {
                if ($jours[$i]['date'] == $rmll_prog_date && ($i+1 < $n)) {
                    $ret = $jours[$i+1]['date'];
                    break;
                }
           }
        }
        return $ret;
    }

    // cleaning empty themes and keep confs this day
    $datas = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);
    for($i=0, $n=count($datas); $i<$n; $i++) {
        $articles = $datas[$i]['articles'];
        $new_articles = array();
        if (!empty($articles)) {
            foreach($articles as $article) {
                if ($article['data']['jour'] == $rmll_prog_date) {
                    $article['data']['start'] = sprintf("%02d:%02d",
                        $article['data']['heure'], $article['data']['minute']);
                    $new_articles[] = $article;
                }
            }
            $datas[$i]['articles'] = $new_articles;
        }
        if (!empty($datas[$i]['articles']))
            $conf[] = $datas[$i];
    }
    $nb_themes = count($conf);

    $prev_day = get_prev_day();
    $next_day = get_next_day();
?>

<div class="rmll-ical-wrap">
    <img src="squelettes/images/ical.png" alt="" />
    <a href="spip.php?page=rmll_progical&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $rmll_prog_date; ?>">
        <?php echo _T('rmll:planning_ical_jour'); ?>
    </a>
</div>


<div class="rmll-pager-wrap">
    <?php if ($prev_day) { ?>
        <div class="prev">
            <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $prev_day; ?>">
                &lsaquo;&lsaquo; <?php echo ucfirst(nom_jour($prev_day)).' '.jour($prev_day); ?>
            </a>
        </div>
    <?php } if ($next_day) { ?>
        <div class="next">
            <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $next_day; ?>">
                <?php echo ucfirst(nom_jour($next_day)).' '.jour($next_day); ?> &rsaquo;&rsaquo;
            </a>
        </div>
    <?php } ?>
</div>

<?php
    if (!empty($conf)) {
?>
    <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
        <table class="rmll-schedule">
            <tr class="header">
                <th class="timeslot">&nbsp;</th>
                <?php
                    for($i=0; $i<$nb_themes; $i++) {
                ?>
                    <th class="conf">
                        <a href="spip.php?rubrique=<?php echo $conf[$i]['id']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>">
                            <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre']))); ?>
                        </a>
                    </th>
                <?php
                    }
                ?>
            </tr>
            <?php
                $k = -1;
                foreach($rmll_periods as $p) {
                    $k++;
                    if ($p['type'] == RMLL_PERIOD_PAUSE) {
                        ?><tr>
                            <td class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></td>
                            <td class="pause" colspan="<?php echo $nb_themes; ?>"><?php echo _T('rmll:pause_pause'); ?></td>
                            </tr>
                        <?php
                    }
                    elseif ($p['type'] == RMLL_PERIOD_LUNCH) {
                        ?><tr>
                            <td class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></td>
                            <td class="pause" colspan="<?php echo $nb_themes; ?>"><?php echo _T('rmll:pause_lunch'); ?></td>
                            </tr>
                        <?php
                    }
                    elseif ($p['type'] == RMLL_PERIOD_CONF) {
                    ?>
                        <tr>
                            <td class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></td>
                            <?php
                                for($i=0; $i<$nb_themes; $i++) {
                                ?>
                                    <td class="conf">
                                    <?php
                                        $articles = $conf[$i]['articles'];
                                        foreach($articles as $cf) {
                                            // si un conf dépasse dans la pause suivante on l'affiche
                                            if (isset($rmll_periods[$k+1]) && $rmll_periods[$k+1]['type'] != RMLL_PERIOD_CONF &&
                                                $cf['data']['start'] >= $p['start'] && $cf['data']['start'] < $rmll_periods[$k+1]['end']);
                                            elseif (!($cf['data']['start'] >= $p['start'] && $cf['data']['start'] <= $p['end']))
                                                continue;
                                            $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                            $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                                        ?>
                                            <div class="infos conf-color-<?php  echo get_color_theme($rmll_themes_rubriques, $conf[$i]['id']); ?>">
                                                <div class="time">
                                                    <span><?php echo $time; ?></span>
                                                    <?php if ($cf['data']['nature_code'] != '') { ?>
                                                        (<?php echo _T('rmll:nature_code_'.$cf['data']['nature_code']); ?>)
                                                    <?php } ?>
                                                </div>
                                                <div class="hider" id="<?php echo $bloc_id; ?>">
                                                    <div class="title">
                                                        <?php if (!empty($cf['data']['drap'])) { ?>
                                                            <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                                        <?php } ?>
                                                        <a href="spip.php?article=<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>">
                                                            <?php  echo supprimer_numero($cf['data']['titre']); ?>
                                                        </a>
                                                    </div>
                                                    <?php if (!empty($cf['data']['intervenants'])) { ?>
                                                        <div class="speaker">
                                                            <?php echo $cf['data']['intervenants']; ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    ?>
                                    </td>
                                <?php
                                }
                            ?>
                        </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
<?php
    }
?>

<div class="rmll-pager-wrap">
    <?php if ($prev_day) { ?>
        <div class="prev">
            <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $prev_day; ?>">
                &lsaquo;&lsaquo; <?php echo ucfirst(nom_jour($prev_day)).' '.jour($prev_day); ?>
            </a>
        </div>
    <?php } if ($next_day) { ?>
        <div class="next">
            <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $next_day; ?>">
                <?php echo ucfirst(nom_jour($next_day)).' '.jour($next_day); ?> &rsaquo;&rsaquo;
            </a>
        </div>
    <?php } ?>
</div>
