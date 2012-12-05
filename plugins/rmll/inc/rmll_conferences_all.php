<?php
    require_once("plugins/rmll/rmll_mes_options.php");
    require_once("plugins/rmll/rmll_mes_fonctions.php");
    require_once("plugins/rmll/inc/rmll.class.php");


class RmllSchedule {
    var $prog_page = null;
    var $themes_rubriques = null;
    var $periods = null;
    var $days = null;
    var $confObj = null;

    function RmllSchedule() {
        $this->prog_page = $GLOBALS['rmll_prog_page'];
        $this->themes_rubriques = $GLOBALS['rmll_themes_rubriques'];
        $this->periods =  $GLOBALS['rmll_periods'];

        $objDays = new Rmll_Db('jour');
        $this->days = $objDays->get_all();
        $this->confObj = new Rmll_Conference();
    }

    function display_conf_salle(&$c) {
        $ret = array();
        if ($c['nature_code'] != '')
            $ret[] = _T('rmll:nature_code_'.$c['nature_code']);
        if ($c['salle'] != '')
            $ret[] = $c['salle'];

        return implode(', ', $ret);
    }

    function display_theme_selector(&$conf, $selected=null) {
        $themes = array();
        for($i=0, $n=count($conf); $i<$n; $i++) {
            $themes[$conf[$i]['id']] = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre'])));
        }
        asort($themes);
    ?>
        <div class="rmll-theme-selector">
            <form action="spip.php?page=rmll_progall&amp;lang=<?php echo $GLOBALS['lang']; ?>" method="post">
                <label for="theme-selector"><?php echo _T('rmll:affichage_theme'); ?></label>
                <select id="theme-selector" name="t" onchange="javascript:this.form.submit();">
                    <option value=""><?php echo _T('rmll:selection_theme'); ?></option>
                <?php
                    foreach($themes as $k => $v) {
                        $sel = '';
                        if ($k == $selected)
                            $sel = ' selected="selected"';
                ?>
                    <option<?php echo $sel; ?> value="<?php echo $k; ?>">
                        <?php echo $v; ?>
                    </option>
                <?php
                    }
                ?>
                </select>
                <noscript><input type="submit" value="ok" /></noscript>
            </form>
        </div>
    <?php
    }

    function display_day_selector($selected = null) {
        if (!empty($this->days)) {
        ?>
            <div class="rmll-day-selector">
                <form action="spip.php?page=rmll_progall&amp;lang=<?php echo $GLOBALS['lang']; ?>" method="post">
                    <label for="day-selector"><?php echo _T('rmll:affichage_jour'); ?></label>
                    <select id="day-selector" name="d" onchange="javascript:this.form.submit();">
                        <option value=""><?php echo _T('rmll:selection_jour'); ?></option>
                    <?php
                        foreach($this->days as $j) {
                            $sel = '';
                            if ($j['date'] == $selected)
                                $sel = ' selected="selected"';
                    ?>
                        <option<?php echo $sel; ?> value="<?php echo $j['date']; ?>">
                            <?php echo ucfirst(nom_jour($j['date'])).' '.jour($j['date']); ?>
                        </option>
                    <?php
                        }
                    ?>
                    </select>
                    <noscript><input type="submit" value="ok" /></noscript>
                </form>
            </div>
        <?php
        }
    }

    function display() {
        $day = isset($_GET['d']) ? $_GET['d'] : (isset($_POST['d']) ? $_POST['d'] : null);
        $theme = isset($_GET['t']) ? $_GET['t'] : (isset($_POST['t']) ? $_POST['t'] : null);
        if ($day)
            $this->display_day($day);
        elseif ($theme)
            $this->display_theme($theme);
        else
            $this->display_all();
    }

    function display_all() {
        // cleaning empty themes and keep confs this day
        $datas = $this->confObj->get_all_sub($this->prog_page, $GLOBALS['lang']);
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
        $this->display_day_selector();
        $this->display_theme_selector($conf);
        ?>
        <div id="rmll-timeline" class="rmll-schedule-all-wrap">
            <table class="rmll-schedule rmll-schedule-all">
                <?php
                    foreach($this->days as $j) {
                ?>
                    <tr class="header">
                        <th class="timeslot">
                            <?php echo ucfirst(nom_jour($j['date'])).' '.jour($j['date']); ?>
                        </th>
                        <?php
                            for($i=0; $i<$nb_themes; $i++) {
                        ?>
                            <th class="conf">
                                <a href="spip.php?page=rmll_progall&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $conf[$i]['id']; ?>">
                                    <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre']))); ?>
                                </a>
                            </th>
                        <?php
                            }
                        ?>
                    </tr>
                    <tr>
                        <th class="timeslot">
                            <?php echo ucfirst(nom_jour($j['date'])).' '.jour($j['date']); ?>
                        </th>
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
                                        <div class="infos conf-color-<?php  echo get_color_theme($this->themes_rubriques, $conf[$i]['id']); ?>">
                                            <div class="time">
                                                <span><?php echo $time; ?></span>
                                                (<?php echo $this->display_conf_salle($cf['data']); ?>)
                                            </div>
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
    <?php
    }

    function display_day($day) {
        // cleaning empty themes and keep confs this day
        $datas = $this->confObj->get_all_sub($this->prog_page, $GLOBALS['lang']);
        $confs = $conf = array();
        for($i=0, $n=count($datas); $i<$n; $i++) {
            if (!empty($datas[$i]['articles']))
                $confs[] = $datas[$i];
            $articles = $datas[$i]['articles'];
            $new_articles = array();
            if (!empty($articles)) {
                foreach($articles as $article) {
                    if ($article['data']['jour'] == $day) {
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
        $this->display_day_selector($day);
        $this->display_theme_selector($confs);
        ?>
        <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
            <table class="rmll-schedule">
                <tr class="header">
                    <th class="timeslot">&nbsp;</th>
                    <?php
                        for($i=0; $i<$nb_themes; $i++) {
                    ?>
                        <th class="conf">
                            <a href="spip.php?page=rmll_progall&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $conf[$i]['id']; ?>">
                                <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre']))); ?>
                            </a>
                        </th>
                    <?php
                        }
                    ?>
                </tr>
                <?php
                    $k = -1;
                    foreach($this->periods as $p) {
                        $k++;
                        if ($p['type'] == RMLL_PERIOD_PAUSE) {
                            ?><tr>
                                <th class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                <td class="pause" colspan="<?php echo $nb_themes; ?>"><?php echo _T('rmll:pause_pause'); ?></td>
                                </tr>
                            <?php
                        }
                        elseif ($p['type'] == RMLL_PERIOD_LUNCH) {
                            ?><tr>
                                <th class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                <td class="pause" colspan="<?php echo $nb_themes; ?>"><?php echo _T('rmll:pause_lunch'); ?></td>
                                </tr>
                            <?php
                        }
                        elseif ($p['type'] == RMLL_PERIOD_CONF) {
                        ?>
                            <tr>
                                <th class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
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
                                                <div class="infos conf-color-<?php  echo get_color_theme($this->themes_rubriques, $conf[$i]['id']); ?>">
                                                    <div class="time">
                                                        <span><?php echo $time; ?></span>
                                                        (<?php echo $this->display_conf_salle($cf['data']); ?>)
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


    function display_theme($theme_id) {
        // cleaning empty themes and keep confs this day
        $datas = $this->confObj->get_all_sub($this->prog_page, $GLOBALS['lang']);
        $confs = $conf = $days = array();
        for($i=0, $n=count($datas); $i<$n; $i++) {
            if (!empty($datas[$i]['articles']))
                $confs[] = $datas[$i];
            if ($datas[$i]['id'] != $theme_id)
                continue;
            $articles = $datas[$i]['articles'];
            $new_articles = array();
            if (!empty($articles)) {
                foreach($articles as $article) {
                    $article['data']['start'] = sprintf("%02d:%02d",
                            $article['data']['heure'], $article['data']['minute']);
                    if (!($article['data']['heure'] === null || $article['data']['minute'] === null))
                        $new_articles[] = $article;
                    if (!in_array($article['data']['jour'], $days))
                        $days[] = $article['data']['jour'];
                }
                usort($new_articles, 'time_sorter');
                $datas[$i]['articles'] = $new_articles;
            }
            if (!empty($datas[$i]['articles']))
                $conf[] = $datas[$i];
        }
        if (!empty($conf)&& isset($conf))
            $conf = $conf[0];

        $this->display_day_selector($day);
        $this->display_theme_selector($confs, $theme_id);
        ?>
        <div class="rmll-schedule-wrap rmll-schedule-theme-wrap">
            <table class="rmll-schedule">
                <tr class="header">
                    <th class="timeslot">&nbsp;</th>
                    <?php
                        foreach($days as $j) {
                    ?>
                        <th class="conf">
                            <a href="spip.php?page=rmll_progall&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $j; ?>">
                                <?php echo ucfirst(nom_jour($j)).' '.jour($j); ?>
                            </a>
                        </th>
                    <?php
                        }
                    ?>
                </tr>
                <?php
                    $k = -1;
                    foreach($this->periods as $p) {
                        $k++;
                        if ($p['type'] == RMLL_PERIOD_PAUSE) {
                            ?><tr>
                                <th class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                <td class="pause" colspan="<?php echo count($days); ?>"><?php echo _T('rmll:pause_pause'); ?></td>
                                </tr>
                            <?php
                        }
                        elseif ($p['type'] == RMLL_PERIOD_LUNCH) {
                            ?><tr>
                                <th class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                <td class="pause" colspan="<?php echo count($days); ?>"><?php echo _T('rmll:pause_lunch'); ?></td>
                                </tr>
                            <?php
                        }
                        elseif ($p['type'] == RMLL_PERIOD_CONF) {
                        ?>
                            <tr>
                                <th class="timeslot"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                <?php
                                    foreach($days as $j) {
                                    ?>
                                        <td class="conf">
                                        <?php
                                            $articles = $conf['articles'];
                                            foreach($articles as $cf) {
                                                if ($cf['data']['jour'] != $j)
                                                    continue;
                                                // si un conf dépasse dans la pause suivante on l'affiche
                                                if (isset($this->periods[$k+1]) && $this->periods[$k+1]['type'] != RMLL_PERIOD_CONF &&
                                                    $cf['data']['start'] >= $p['start'] && $cf['data']['start'] < $rthis->periods[$k+1]['end']);
                                                elseif (!($cf['data']['start'] >= $p['start'] && $cf['data']['start'] <= $p['end']))
                                                    continue;
                                                $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                            ?>
                                                <div class="infos conf-color-<?php  echo get_color_theme($this->themes_rubriques, $theme_id); ?>">
                                                    <div class="time">
                                                        <span><?php echo $time; ?></span>
                                                        (<?php echo $this->display_conf_salle($cf['data']); ?>)
                                                    </div>
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
}
?>