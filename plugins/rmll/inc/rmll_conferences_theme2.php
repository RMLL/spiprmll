<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");

    global $rmll_prog_page, $rmll_theme_id, $rmll_themes_rubriques;

    $jours = array();
    $rc = new Rmll_Conference();
    // cleaning empty themes and keep confs this day
    $datas = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);
    $conf = array();
    for($i=0, $n=count($datas); $i<$n; $i++) {
        if ($datas[$i]['id'] != $rmll_theme_id)
            continue;
        $articles = $datas[$i]['articles'];
        $new_articles = array();
        if (!empty($articles)) {
            foreach($articles as $article) {
                $article['data']['start'] = sprintf("%02d:%02d",
                        $article['data']['heure'], $article['data']['minute']);
                if (!($article['data']['heure'] === null || $article['data']['minute'] === null))
                    $new_articles[] = $article;
                if (!in_array($article['data']['jour'], $jours))
                    $jours[] = $article['data']['jour'];
            }
            usort($new_articles, 'time_sorter');
            $datas[$i]['articles'] = $new_articles;
        }
        if (!empty($datas[$i]['articles']))
            $conf[] = $datas[$i];
    }
    if (!empty($conf)&& isset($conf))
        $conf = $conf[0];
?>

<?php
    if (!empty($conf)) {
?>
    <div class="rmll-ical-wrap">
        <img src="squelettes/images/ical.png" alt="" />
        <a href="spip.php?page=rmll_progical&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $rmll_theme_id; ?>">
            <?php echo _T('rmll:planning_ical'); ?>
        </a>
    </div>

    <div class="rmll-schedule-wrap rmll-schedule-theme-wrap">
        <table class="rmll-schedule">
            <tr class="header">
                <th class="timeslot">&nbsp;</th>
                <?php
                    foreach($jours as $j) {
                ?>
                    <th class="conf">
                        <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $j; ?>">
                            <?php echo ucfirst(nom_jour($j)).' '.jour($j); ?>
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
                            <td class="pause" colspan="<?php echo count($jours); ?>"><?php echo _T('rmll:pause_pause'); ?></td>
                            </tr>
                        <?php
                    }
                    elseif ($p['type'] == RMLL_PERIOD_LUNCH) {
                        ?><tr>
                            <td class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></td>
                            <td class="pause" colspan="<?php echo count($jours); ?>"><?php echo _T('rmll:pause_lunch'); ?></td>
                            </tr>
                        <?php
                    }
                    elseif ($p['type'] == RMLL_PERIOD_CONF) {
                    ?>
                        <tr>
                            <td class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></td>
                            <?php
                                foreach($jours as $j) {
                                ?>
                                    <td class="conf">
                                    <?php
                                        $articles = $conf['articles'];
                                        foreach($articles as $cf) {
                                            if ($cf['data']['jour'] != $j)
                                                continue;
                                            // si un conf dépasse dans la pause suivante on l'affiche
                                            if (isset($rmll_periods[$k+1]) && $rmll_periods[$k+1]['type'] != RMLL_PERIOD_CONF &&
                                                $cf['data']['start'] >= $p['start'] && $cf['data']['start'] < $rmll_periods[$k+1]['end']);
                                            elseif (!($cf['data']['start'] >= $p['start'] && $cf['data']['start'] <= $p['end']))
                                                continue;
                                            $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                        ?>
                                            <div class="infos conf-color-<?php  echo get_color_theme($rmll_themes_rubriques, $rmll_theme_id); ?>">
                                                <div class="time">
                                                    <?php echo $time; ?>
                                                </div>
                                                <div class="title">
                                                    <?php if (!empty($cf['data']['drap'])) { ?>
                                                        <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                                    <?php } ?>
                                                    <a href="spip.php?article=<?php echo $cf['data']['id_article']; ?>">
                                                        <?php  echo supprimer_numero($cf['data']['titre']); ?>
                                                    </a>
                                                </div>
                                                <?php if (!empty($cf['data']['intervenants'])) { ?>
                                                    <div class="speaker">
                                                        <?php echo $cf['data']['intervenants']; ?>
                                                    </div>
                                                <?php } ?>
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
