<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");

    global $rmll_prog_page, $rmll_themes_rubriques;

    $objJour = new Rmll_Db('jour');
    $jours = $objJour->get_all();

    $rc = new Rmll_Conference();
    // cleaning empty themes and keep confs this day
    $datas = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);
    $conf = array();
    for($i=0, $n=count($datas); $i<$n; $i++) {
        $articles = $datas[$i]['articles'];
        $new_articles = array();
        if (!empty($articles)) {
            foreach($articles as $article) {
                if (!($article['data']['heure'] === null || $article['data']['minute'] === null))
                    $new_articles[] = $article;
            }
            usort($new_articles, 'time_sorter');
            $datas[$i]['articles'] = $new_articles;
        }
        if (!empty($datas[$i]['articles']))
            $conf[] = $datas[$i];
    }
    $nb_themes = count($conf);
?>

<div class="rmll-listdays-wrap">
    <div class="msg"><?php echo _T('rmll:programme_intro'); ?></div>
    <ul>
        <?php
            foreach($jours as $j) {
        ?>
            <li>
                <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $j['date']; ?>">
                    <?php echo ucfirst(nom_jour($j['date'])).' '.jour($j['date']); ?>
                </a>
            </li>
        <?php
            }
        ?>
    </ul>
</div>

<div class="rmll-ical-wrap">
    <img src="squelettes/images/ical.png" alt="" />
    <a href="spip.php?page=rmll_progical&amp;lang=<?php echo $GLOBALS['lang']; ?>">
        <?php echo _T('rmll:planning_ical'); ?>
    </a>
</div>

<div id="rmll-timeline" class="rmll-schedule-wrap rmll-schedule-summary-wrap">
    <table class="rmll-schedule rmll-schedule-all">
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
            foreach($jours as $j) {
        ?>
            <tr>
                <td class="timeslot">
                    <a href="spip.php?page=rmll_progdate&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $j['date']; ?>">
                        <?php echo ucfirst(nom_jour($j['date'])).' '.jour($j['date']); ?>
                    </a>
                </td>
                <?php
                    for($i=0; $i<$nb_themes; $i++) {
                    ?>
                        <td class="conf">
                        <?php
                            $articles = $conf[$i]['articles'];
                            foreach($articles as $cf) {
                                if ($cf['data']['jour'] != $j['date'])
                                    continue;
                                $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                            ?>
                                <div class="infos conf-color-<?php  echo get_color_theme($rmll_themes_rubriques, $conf[$i]['id']); ?>">
                                    <div class="time">
                                        <?php echo $time; ?>
                                        <a href="spip.php?article=<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>" onclick="javascript:return !$('#<?php echo $bloc_id; ?>').toggle();">(+)</a>
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
                                        <?php if ($cf['data']['nature_code'] != '') { ?>
                                            <div class="nature">
                                                (<?php echo _T('rmll:nature_code_'.$cf['data']['nature_code']); ?>)
                                            </div>
                                        <?php } ?>
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
        ?>
    </table>
</div>
