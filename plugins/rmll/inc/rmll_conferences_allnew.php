<?php
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_options.php';
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_fonctions.php';
    require_once _DIR_PLUGIN_RMLL.'inc/rmll.class.php';

class RmllSchedule {
    public $themes_rubriques = null;
    public $periods = null;
    public $days = null;
    public $confObj = null;
    public $page = '';
    public $filter_day = '';
    public $filter_theme = '';
    public $filter_keyword = '';
    public $filter_room = '';
    public $filter_lang = '';

    function RmllSchedule() {
        $this->periods =  $GLOBALS['rmll_periods'];
        $this->confObj = new Rmll_Conference();
        $this->page = isset($_GET['page']) ? trim($_GET['page']) : (isset($_POST['page']) ? trim($_POST['page']) : '');
        $this->filter_day = isset($_GET['d']) ? trim($_GET['d']) : (isset($_POST['d']) ? trim($_POST['d']) : '');
        $this->filter_theme = isset($_GET['t']) ? trim($_GET['t']) : (isset($_POST['t']) ? trim($_POST['t']) : '');
        $this->filter_keyword = isset($_GET['k']) ? trim($_GET['k']) : (isset($_POST['k']) ? trim($_POST['k']) : '');
        $this->filter_room = isset($_GET['r']) ? trim($_GET['r']) : (isset($_POST['r']) ? trim($_POST['r']) : '');
        $this->filter_lang = isset($_GET['l']) ? trim($_GET['l']) : (isset($_POST['l']) ? trim($_POST['l']) : '');

    }

    function display_conf_salle(&$c) {
        $ret = array();
        if ($c['nature_code'] != '')
            $ret[] = _T('rmll:nature_code_'.$c['nature_code']);
        if ($c['salle'] != '')
            $ret[] = $c['salle'];
        $ret = implode(', ', $ret);
        if ($ret != '') {
            $ret = '('.$ret.')';
        }
        return $ret;
    }

    function display_vars_selector() {
    ?>
        <input type="hidden" name="page" value="<?php echo $this->page; ?>" />
        <input type="hidden" name="lang" value="<?php echo $GLOBALS['lang']; ?>" />
        <input type="hidden" name="d" value="<?php echo $this->filter_day; ?>" />
        <input type="hidden" name="t" value="<?php echo $this->filter_theme; ?>" />
        <input type="hidden" name="k" value="<?php echo $this->filter_keyword; ?>" />
        <input type="hidden" name="r" value="<?php echo $this->filter_room; ?>" />
        <input type="hidden" name="l" value="<?php echo $this->filter_lang; ?>" />
    <?php
    }

    function display_keyword_selector(&$keywords) {
        $selected = $this->filter_keyword;

        if (!empty($keywords)) {
            foreach($keywords as $id_group => $group_data) {
            ?>
            <div class="rmll-selector">
                <form action="" method="get">
                <?php
                    $this->display_vars_selector();
                ?>
                    <label for="keyword-selector">
                        <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($group_data['titre']))); ?> :
                    </label>
                    <select id="keyword-selector" name="k" onchange="javascript:this.form.submit();">
                        <option value="">
                            <?php echo _T('rmll:selection'); ?>
                        </option>
                    <?php
                        foreach($group_data['mots'] as $k => $v) {
                            $sel = '';
                            if ($k == $selected)
                                $sel = ' selected="selected"';
                    ?>
                        <option<?php echo $sel; ?> value="<?php echo $k; ?>">
                            <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($v))); ?>
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
    }

    function display_lang_selector(&$langs) {
        $selected = $this->filter_lang;

        if (!empty($langs)) {
        ?>
            <div class="rmll-selector">
                <form action="" method="get">
                <?php
                    $this->display_vars_selector();
                ?>
                    <label for="keyword-selector">
                        <?php echo _T('rmll:label_langue'); ?> :
                    </label>
                    <select id="keyword-selector" name="l" onchange="javascript:this.form.submit();">
                        <option value="">
                            <?php echo _T('rmll:selection'); ?>
                        </option>
                    <?php
                        foreach($langs as $k => $v) {
                            $sel = '';
                            if ($k == $selected)
                                $sel = ' selected="selected"';
                    ?>
                        <option<?php echo $sel; ?> value="<?php echo $k; ?>">
                            <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($v))); ?>
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

    function display_theme_selector(&$themes) {
        $selected = $this->filter_theme;

        foreach($themes as $id => $data) {
            $title = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($data['titre'])));
            if (!rmll_is_prog($data['id_parent'])) {
                $title = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($data['titre_parent']))) . ' &mdash; ' .$title;
            }
            $themes[$id] = $title;
        }
        asort($themes);
        if (!empty($themes)) {
        ?>
            <div class="rmll-selector">
                <form action="" method="get">
                <?php
                    $this->display_vars_selector();
                ?>
                    <label for="theme-selector"><?php echo _T('rmll:affichage_theme'); ?></label>
                    <select id="theme-selector" name="t" onchange="javascript:this.form.submit();">
                        <option value=""><?php echo _T('rmll:selection'); ?></option>
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
    }

    function display_room_selector(&$rooms) {
        $selected = $this->filter_room;

        foreach($rooms as $id => $title) {
            $rooms[$id] = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($title)));
        }
        asort($rooms);
        if (!empty($rooms)) {
        ?>
            <div class="rmll-selector">
                <form action="" method="get">
                <?php
                    $this->display_vars_selector();
                ?>
                    <label for="room-selector"><?php echo _T('rmll:affichage_room'); ?></label>
                    <select id="room-selector" name="r" onchange="javascript:this.form.submit();">
                        <option value=""><?php echo _T('rmll:selection'); ?></option>
                    <?php
                        foreach($rooms as $k => $v) {
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
    }

    function display_day_selector($days) {
        $selected = $this->filter_day;

        if (!empty($days)) {
        ?>
            <div class="rmll-selector">
                <form action="" method="get">
                <?php
                    $this->display_vars_selector();
                ?>
                    <label for="day-selector"><?php echo _T('rmll:affichage_jour'); ?></label>
                    <select id="day-selector" name="d" onchange="javascript:this.form.submit();">
                        <option value=""><?php echo _T('rmll:selection'); ?></option>
                    <?php
                        foreach($days as $j) {
                            $sel = '';
                            if ($j == $selected) {
                                $sel = ' selected="selected"';
                            }
                    ?>
                        <option<?php echo $sel; ?> value="<?php echo $j; ?>">
                            <?php echo ucfirst(nom_jour($j)).' '.jour($j); ?>
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
        if ($this->filter_day != '') {
            $this->display_day($this->filter_day, $this->filter_keyword, $this->filter_room, $this->filter_lang);
        }
        elseif ($this->filter_theme != '') {
            $this->display_theme($this->filter_theme, $this->filter_keyword, $this->filter_room, $this->filter_lang);
        }
        else {
            $this->display_all($this->filter_keyword, $this->filter_room, $this->filter_lang);
        }
    }

    function extract($datas, $theme_id = null, $day = null, $keyword = 0, $room = 0, $lang = 0) {
        $conf = $alldays = $days = $allthemes = $themes = $allrooms = $allkeywords = $alllangs = array();
        for($i=0, $n=count($datas); $i<$n; $i++) {
            $articles = $datas[$i]['articles'];
            $new_articles = array();
            if (!empty($articles)) {
                foreach($articles as $article) {
                    $article['data']['start'] = sprintf("%02d:%02d",
                            $article['data']['heure'], $article['data']['minute']);
                    if (!($article['data']['heure'] === null || $article['data']['minute'] === null)) {
                        $room_filter  = $room != 0 && $article['data']['id_salle'] != $room;
                        $keyword_filter = $keyword > 0;
                        $lang_filter = $lang != 0 && $article['data']['id_langue'] != $lang;
                        if (!empty($article['keywords'])) {
                            foreach($article['keywords'] as $keyid => $keydata) {
                                if ($keyword_filter && $keyid == $keyword) {
                                    $keyword_filter = false;
                                }
                                
                                if (!array_key_exists($keydata['id_groupe_mot'], $allkeywords)) {
                                    $allkeywords[$keydata['id_groupe_mot']] = array(
                                        'titre'=> $keydata['titre_groupe'],
                                        'mots' => array(),
                                    );
                                }
                                if (!array_key_exists($keyid, $allkeywords[$keydata['id_groupe_mot']]['mots'])) {
                                    $allkeywords[$keydata['id_groupe_mot']]['mots'][$keyid] = $keydata['titre'];
                                }
                            }
                        }
                        if ($day !== null) {
                            if ($article['data']['jour'] == $day) {
                                $new_articles[] = $article;
                                if (!array_key_exists($datas[$i]['id'], $themes)) {
                                    $themes[$datas[$i]['id']] = $datas[$i]['titre'];
                                }
                            }
                        }
                        else {
                            if (!$keyword_filter && !$room_filter && !$lang_filter) {
                                $new_articles[] = $article;
                                if (!array_key_exists($datas[$i]['id'], $themes)) {
                                    $themes[$datas[$i]['id']] = $datas[$i]['titre'];
                                }
                            }
                        }
                        if (!in_array($article['data']['jour'], $alldays)) {
                            $alldays[] = $article['data']['jour'];
                        }
                        if ($theme_id !== null) {
                            if ($datas[$i]['id'] == $theme_id) {
                                if (!in_array($article['data']['jour'], $days)) {
                                    $days[] = $article['data']['jour'];
                                }
                            }
                        }
                        else {
                            if (!$keyword_filter && !$room_filter && !$lang_filter) {
                                if (!in_array($article['data']['jour'], $days)) {
                                    $days[] = $article['data']['jour'];
                                }
                            }
                        }
                        if (!array_key_exists($datas[$i]['id'], $allthemes)) {
                            $allthemes[$datas[$i]['id']] = array(
                                'titre' => $datas[$i]['titre'],
                                'id_parent' => $datas[$i]['id_parent'],
                                'titre_parent' => $datas[$i]['titre_parent'],
                            );
                        }
                        if ($article['data']['id_salle'] > 0 && !array_key_exists($article['data']['id_salle'], $allrooms)) {
                            $allrooms[$article['data']['id_salle']] = $article['data']['salle'];
                        }
                        if (!array_key_exists($article['data']['id_langue'], $alllangs)) {
                            $alllangs[$article['data']['id_langue']] = $article['data']['langue'];
                        }
                    }
                }
                usort($new_articles, 'time_sorter');
                $datas[$i]['articles'] = $new_articles;
            }
            if (!empty($datas[$i]['articles'])) {
                if ($theme_id !== null) {
                    if ($datas[$i]['id'] == $theme_id) {
                        $conf[] = $datas[$i];
                    }
                }
                else {
                    $conf[] = $datas[$i];
                }
            }
        }
        sort($days);
        sort($alldays);

        return array($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs);
    }

    function display_all($keyword = null, $room = null, $lang = null) {
        $datas = $this->confObj->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang']);
        list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, null, null, $keyword, $room, $lang);
        if (!empty($alldays)) {
            $this->display_day_selector($alldays);
        }
        if (!empty($allthemes)) {
            $this->display_theme_selector($allthemes);
        }
        if (!empty($allrooms)) {
            $this->display_room_selector($allrooms);
        }
        if (!empty($allkeywords)) {
            $this->display_keyword_selector($allkeywords);
        }
        if (!empty($alllangs)) {
            $this->display_lang_selector($alllangs);
        }
        if (!empty($conf)) {
            $nb_themes = count($themes);
            ?>
            <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
                <table class="rmll-schedule">
                    <?php
                        foreach($days as $j) {
                            $daystr = ucfirst(nom_jour($j)).' '.jour($j);
                    ?>
                        <tr class="header">
                            <th class="timeslot">
                                <a
                                    href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $j; ?>"
                                    title="<?php echo _T('rmll:accessible_link_jour').' '.$daystr; ?>"
                                >
                                    <?php echo $daystr; ?>
                                </a>
                            </th>
                            <?php
                                for($i=0; $i<$nb_themes; $i++) {
                                    $themestr = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre'])));
                            ?>
                                <th class="conf">
                                    <a
                                        href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $conf[$i]['id']; ?>"
                                        title="<?php echo _T('rmll:accessible_goto_theme').' « '.$themestr.' »'; ?>"
                                    >
                                        <?php echo $themestr; ?>
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
                                        <th class="timeslot pause"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                        <td class="pause" colspan="<?php echo $nb_themes; ?>"><?php echo _T('rmll:pause_pause'); ?></td>
                                        </tr>
                                    <?php
                                }
                                elseif ($p['type'] == RMLL_PERIOD_LUNCH) {
                                    ?><tr>
                                        <th class="timeslot lunch pause"><?php printf("%s-%s", $p['start'], $p['end']); ?></th>
                                        <td class="lunch pause" colspan="<?php echo $nb_themes; ?>"><?php echo _T('rmll:pause_lunch'); ?></td>
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
                                                        $articlestr = supprimer_numero(extraire_multi($cf['data']['titre']));
                                                        if ($cf['data']['jour'] != $j) {
                                                            continue;
                                                        }
                                                        // si un conf dépasse dans la pause suivante on l'affiche
                                                        if (isset($rmll_periods[$k+1]) && $rmll_periods[$k+1]['type'] != RMLL_PERIOD_CONF &&
                                                            $cf['data']['start'] >= $p['start'] && $cf['data']['start'] < $rmll_periods[$k+1]['end']);
                                                        elseif (!($cf['data']['start'] >= $p['start'] && $cf['data']['start'] <= $p['end']))
                                                            continue;
                                                        $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                                        $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                                                    ?>
                                                        <div class="infos conf-color-<?php  echo get_color_theme($conf[$i]['id']); ?>">
                                                            <div class="hider" id="<?php echo $bloc_id; ?>">
                                                                <div class="title">
                                                                    <?php if (!empty($cf['data']['drap'])) {
                                                                        $codelang = $cf['data']['drap'];
                                                                    ?>
                                                                        <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $codelang ?>.png" alt="<?php echo _T($codelang) ?>" />
                                                                    <?php } ?>
                                                                    <a
                                                                        href="spip.php?article<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>"
                                                                        title="<?php echo _T('rmll:accessible_link_article').' « '.$articlestr.' »'; ?>"
                                                                    >
                                                                        <?php  echo $articlestr; ?>
                                                                    </a>
                                                                </div>
                                                                <div class="time">
                                                                    <span><?php echo $time; ?></span>
                                                                    <?php echo $this->display_conf_salle($cf['data']); ?>
                                                                </div>
                                                                <?php if (!empty($cf['data']['intervenants'])) { ?>
                                                                    <div class="speaker">
                                                                        <?php echo $cf['data']['intervenants']; ?>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if (!empty($cf['data']['keywords'])) { ?>
                                                                    <div class="keywords">
                                                                        <?php echo $cf['data']['keywords']; ?>
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
                    }
                    ?>
                </table>
            </div>
            <?php
        }
    }

    function display_day($day, $keyword = null, $room = null, $lang = null) {
        $datas = $this->confObj->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang']);
        list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, null, $day, $keyword, $room, $lang);
        if (!empty($alldays)) {
            $this->display_day_selector($alldays, $day);
        }
        if (!empty($allthemes)) {
            $this->display_theme_selector($allthemes, $theme_id);
        }
        if (!empty($allrooms)) {
            $this->display_room_selector($allrooms, $room);
        }
        if (!empty($allkeywords)) {
            $this->display_keyword_selector($allkeywords);
        }
        if (!empty($alllangs)) {
            $this->display_lang_selector($alllangs);
        }
        if (!empty($conf)) {
            $nb_themes = count($themes);
            ?>
            <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
                <table class="rmll-schedule">
                    <tr class="header">
                        <th class="timeslot">&nbsp;</th>
                        <?php
                            for($i=0; $i<$nb_themes; $i++) {
                                $themestr = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre'])));
                        ?>
                            <th class="conf">
                                <a
                                    href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $conf[$i]['id']; ?>"
                                    title="<?php echo _T('rmll:accessible_link_theme').' « '.$themestr.' »'; ?>"
                                >
                                    <?php echo $themestr; ?>
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
                                                    $articlestr = supprimer_numero(extraire_multi($cf['data']['titre']));
                                                    // si un conf dépasse dans la pause suivante on l'affiche
                                                    if (isset($rmll_periods[$k+1]) && $rmll_periods[$k+1]['type'] != RMLL_PERIOD_CONF &&
                                                        $cf['data']['start'] >= $p['start'] && $cf['data']['start'] < $rmll_periods[$k+1]['end']);
                                                    elseif (!($cf['data']['start'] >= $p['start'] && $cf['data']['start'] <= $p['end']))
                                                        continue;
                                                    $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                                    $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                                                ?>
                                                    <div class="infos conf-color-<?php  echo get_color_theme($conf[$i]['id']); ?>">
                                                        <div class="hider" id="<?php echo $bloc_id; ?>">
                                                            <div class="title">
                                                                <?php if (!empty($cf['data']['drap'])) { ?>
                                                                    <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                                                <?php } ?>
                                                                <a
                                                                    href="spip.php?article<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>"
                                                                    title="<?php echo _T('rmll:accessible_link_article').' « '.$articlestr.' »'; ?>"
                                                                >
                                                                    <?php  echo $articlestr; ?>
                                                                </a>
                                                            </div>
                                                            <div class="time">
                                                                <span><?php echo $time; ?></span>
                                                                <?php echo $this->display_conf_salle($cf['data']); ?>
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
    }

    function display_theme($theme_id, $keyword = null, $room = null, $lang = null) {
        $datas = $this->confObj->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang']);
        list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, $theme_id, null, $keyword, $room, $lang);
        if (!empty($alldays)) {
            $this->display_day_selector($alldays);
        }
        if (!empty($allthemes)) {
            $this->display_theme_selector($allthemes, $theme_id);
        }
        if (!empty($allrooms)) {
            $this->display_room_selector($allrooms, $room);
        }
        if (!empty($allkeywords)) {
            $this->display_keyword_selector($allkeywords);
        }
        if (!empty($alllangs)) {
            $this->display_lang_selector($alllangs);
        }
        if (!empty($conf)) {
            $conf = $conf[0];
            ?>
            <div class="rmll-schedule-wrap rmll-schedule-theme-wrap">
                <table class="rmll-schedule">
                    <tr class="header">
                        <th class="timeslot">&nbsp;</th>
                        <?php
                            foreach($days as $j) {
                                $daystr = ucfirst(nom_jour($j)).' '.jour($j);
                        ?>
                            <th class="conf">
                                <a
                                    href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $j; ?>"
                                    title="<?php echo _T('rmll:accessible_link_jour').' '.$daystr; ?>"
                                >
                                    <?php echo $daystr; ?>
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
                                                    $articlestr = supprimer_numero(extraire_multi($cf['data']['titre']));
                                                    if ($cf['data']['jour'] != $j)
                                                        continue;
                                                    // si un conf dépasse dans la pause suivante on l'affiche
                                                    if (isset($this->periods[$k+1]) && $this->periods[$k+1]['type'] != RMLL_PERIOD_CONF &&
                                                        $cf['data']['start'] >= $p['start'] && $cf['data']['start'] < $rthis->periods[$k+1]['end']);
                                                    elseif (!($cf['data']['start'] >= $p['start'] && $cf['data']['start'] <= $p['end']))
                                                        continue;
                                                    $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                                ?>
                                                    <div class="infos conf-color-<?php  echo get_color_theme($theme_id); ?>">
                                                        <div class="title">
                                                            <?php if (!empty($cf['data']['drap'])) { ?>
                                                                <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                                            <?php } ?>
                                                            <a
                                                                href="spip.php?article<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>"
                                                                title="<?php echo _T('rmll:accessible_link_article').' « '.$articlestr.' »'; ?>"
                                                            >
                                                                <?php  echo $articlestr; ?>
                                                            </a>
                                                        </div>
                                                        <div class="time">
                                                            <span><?php echo $time; ?></span>
                                                            <?php echo $this->display_conf_salle($cf['data']); ?>
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

    function display_acc_theme_selector(&$themes, $selected = null) {
        foreach($themes as $id => $data) {
            $title = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($data['titre'])));
            if (!rmll_is_prog($data['id_parent'])) {
                $title = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($data['titre_parent']))) . ' &mdash; ' .$title;
            }
            $themes[$id] = $title;
        }
        asort($themes);
        if (!empty($themes)) {
        ?>
            <div>
                <strong><?php echo _T('rmll:affichage_theme'); ?></strong>
            </div>
            <ul>
            <?php
                foreach($themes as $k => $v) {
            ?>
                <li>
            <?php
                    if ($k != $selected) {
                    ?>
                        <a
                            href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $k; ?>"
                            title="<?php echo _T('rmll:accessible_link_theme').' « '.$v.' »'; ?>"
                            >
                    <?php } ?>
                        <?php echo $v; ?>
                    <?php if ($k != $selected) { ?>
                        </a>
                    <?php } ?>
                </li>
                <?php
                }
            ?>
            </ul>
        <?php
        }
    }

    function display_acc_room_selector(&$rooms, $selected = null) {
        foreach($rooms as $id => $title) {
            $rooms[$id] = supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($title)));
        }
        asort($rooms);
        if (!empty($rooms)) {
        ?>
            <div>
                <strong><?php echo _T('rmll:affichage_room'); ?></strong>
            </div>
            <ul>
            <?php
                foreach($rooms as $k => $v) {
            ?>
                <li>
            <?php
                    if ($k != $selected) {
                    ?>
                        <a
                            href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;r=<?php echo $k; ?>"
                            title="<?php echo _T('rmll:accessible_link_room').' « '.$v.' »'; ?>"
                            >
                    <?php } ?>
                        <?php echo $v; ?>
                    <?php if ($k != $selected) { ?>
                        </a>
                    <?php } ?>
                </li>
                <?php
                }
            ?>
            </ul>
            <?php
        }
    }

    function display_acc_day_selector(&$days, $selected = null) {
        if (!empty($days)) {
            ?>
                <div>
                    <strong><?php echo _T('rmll:affichage_jour'); ?></strong>
                </div>
                <ul>
            <?php
                foreach($days as $day) {
                    $daystr = ucfirst(nom_jour($day)).' '.jour($day);
            ?>
                    <li>
                        <?php if ($day !== $selected) { ?>
                            <a
                                href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $day; ?>"
                                title="<?php echo _T('rmll:accessible_link_jour').' '.$daystr; ?>"
                                >
                        <?php } ?>
                            <?php echo $daystr; ?>
                        <?php if ($day !== $selected) { ?>
                            </a>
                        <?php } ?>
                    </li>
            <?php
                }
            ?>
                </ul>
            <?php
        }
    }

    function display_acc_keyword_selector(&$keywords, $selected = null) {
        if (!empty($keywords)) {
            foreach($keywords as $id_group => $group_data) {
            ?>
                <div>
                    <strong>
                        <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($group_data['titre']))); ?>
                    </strong>
                </div>
                <ul>
                    <?php foreach($group_data['mots'] as $k => $v) { ?>
                        <li>
                            <?php if ($k != $selected) { ?>
                                <a href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;k=<?php echo $k; ?>">
                            <?php } ?>
                                    <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($v))); ?>
                            <?php if ($k != $selected) { ?>
                                </a>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php
            }
        }
    }

    function display_acc_lang_selector(&$langs, $selected = null) {
        if (!empty($langs)) {
        ?>
            <div>
                <strong>
                    <?php echo _T('rmll:label_langue'); ?> :
                </strong>
            </div>
            <ul>
                <?php foreach($langs as $k => $v) { ?>
                    <li>
                        <?php if ($k != $selected) { ?>
                            <a href="spip.php?page=<?php echo $this->page; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;k=<?php echo $k; ?>">
                        <?php } ?>
                                <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($v))); ?>
                        <?php if ($k != $selected) { ?>
                            </a>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        <?php
        }
    }

    function display_acc() {
        $day = isset($_GET['d']) ? $_GET['d'] : '';
        $theme = isset($_GET['t']) ? $_GET['t'] : '';
        $keyword = isset($_GET['k']) ? $_GET['k'] : '';
        $room = isset($_GET['r']) ? $_GET['r'] : '';
        $lang = isset($_GET['l']) ? $_GET['l'] : '';

        $datas = $this->confObj->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang']);
        if (!empty($theme)) {
            list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, $theme, null, null, null, $lang);
        }
        elseif (!empty($room)) {
            list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, null, null, null, $room, $lang);
        }
        elseif (!empty($keyword)) {
            list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, null, null, $keyword, null, $lang);
        }
        else {
            list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords, $alllangs) = $this->extract($datas, null, $day, null, null, $lang);
        }

        $this->display_acc_day_selector($alldays, $day);
        $this->display_acc_theme_selector($allthemes, $theme);
        $this->display_acc_room_selector($allrooms, $room);
        $this->display_acc_keyword_selector($allkeywords, $keyword);
        $this->display_acc_lang_selector($alllangs, $lang);

        ?>
            <a id="skipcontent"></a>
        <?php
        if (!empty($theme)) {
            $this->display_acc_theme($theme, $days, $themes, $conf);
        }
        elseif (!empty($room)) {
            $this->display_acc_filter(null, $room, $days, $themes, $allkeywords, &$conf);
        }
        elseif (!empty($keyword)) {
            $this->display_acc_filter($keyword, null, $days, $themes, $allkeywords, &$conf);
        }
        else {
            $this->display_acc_day($day, $themes, $conf);
        }
    }

    function display_acc_day($day, &$themes, &$conf) {
        if (!empty($day)) {
        ?>
            <h2>
                <?php echo ucfirst(nom_jour($day)).' '.jour($day); ?>
            </h2>
        <?php
        }

        if (!empty($conf)) {
            $nb_themes = count($themes);
            ?>
            <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
                <div class="rmll-schedule">
                <?php
                    for($i=0; $i<$nb_themes; $i++) {
                ?>
                        <h3>
                            <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre']))); ?>
                        </h3>
                        <?php
                            if (!isset($conf[$i])) {
                                continue;
                            }
                            $articles = $conf[$i]['articles'];
                            foreach($articles as $cf) {
                                $articlestr = supprimer_numero(extraire_multi($cf['data']['titre']));
                                $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                            ?>
                                <div class="conf">
                                    <div class="infos conf-color-<?php  echo get_color_theme($conf[$i]['id']); ?>">
                                        <div class="title">
                                            <?php if (!empty($cf['data']['drap'])) { ?>
                                                <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                            <?php } ?>
                                            <a
                                                href="spip.php?article<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>"
                                                title="<?php echo _T('rmll:accessible_link_article').' « '.$articlestr.' »'; ?>"
                                                >
                                                <?php  echo $articlestr; ?>
                                            </a>
                                        </div>
                                        <div class="time">
                                            <span><?php echo $time; ?></span>
                                            <?php echo $this->display_conf_salle($cf['data']); ?>
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
                    }
                ?>
                </div>
            </div>
            <?php
        }
    }

    function display_acc_theme($theme, &$days, &$themes, &$conf) {
        ?>
            <h2>
                <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($themes[$theme]))); ?>
            </h2>
        <?php
        if (!empty($conf)) {
            ?>
            <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
                <div class="rmll-schedule">
                <?php
                    foreach($days as $day) {
                ?>
                        <h3>
                            <?php echo ucfirst(nom_jour($day)).' '.jour($day); ?>
                        </h3>
                        <?php
                            for($i=0, $n=count($themes); $i<$n; $i++) {
                                if (!isset($conf[$i])) {
                                    continue;
                                }
                                $articles = $conf[$i]['articles'];
                                foreach($articles as $cf) {
                                     if ($cf['data']['jour'] != $day) {
                                        continue;
                                    }
                                    $articlestr = supprimer_numero(extraire_multi($cf['data']['titre']));
                                    $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                    $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                                ?>
                                    <div class="conf">
                                        <div class="infos conf-color-<?php  echo get_color_theme($conf[$i]['id']); ?>">
                                            <div class="title">
                                                <?php if (!empty($cf['data']['drap'])) { ?>
                                                    <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                                <?php } ?>
                                                <a
                                                    href="spip.php?article<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>"
                                                    title="<?php echo _T('rmll:accessible_link_article').' « '.$articlestr.' »'; ?>"
                                                    >
                                                    <?php  echo $articlestr; ?>
                                                </a>
                                            </div>
                                            <div class="time">
                                                <span><?php echo $time; ?></span>
                                                <?php echo $this->display_conf_salle($cf['data']); ?>
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
                            }
                    }
                ?>
                </div>
            </div>
            <?php
        }
    }

    function display_acc_filter($keyword, $room, &$days, &$themes, $keywords, &$conf) {
        if ($keyword !== null) {
        ?>
            <h2>
                <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($keywords[$keyword]))); ?>
            </h2>
        <?php
        }
        if (!empty($conf)) {
            ?>
            <div class="rmll-schedule-wrap rmll-schedule-date-wrap">
                <div class="rmll-schedule">
                <?php
                    foreach($days as $day) {
                ?>
                        <h3>
                            <?php echo ucfirst(nom_jour($day)).' '.jour($day); ?>
                        </h3>
                        <?php
                            for($i=0, $n=count($themes); $i<$n; $i++) {
                                if (!isset($conf[$i])) {
                                    continue;
                                }
                                $articles = $conf[$i]['articles'];
                                $match = false;
                                foreach($articles as $cf) {
                                     if ($cf['data']['jour'] == $day) {
                                        $match = true;
                                        break;
                                    }
                                }
                                if ($match) {
                                ?>
                                    <h4>
                                        <?php echo supprimer_numero(extraire_multi(nettoyer_raccourcis_typo($conf[$i]['titre']))); ?>
                                    </h4>
                                <?php
                                }
                                foreach($articles as $cf) {
                                    if ($cf['data']['jour'] != $day) {
                                        continue;
                                    }
                                    $articlestr = supprimer_numero(extraire_multi($cf['data']['titre']));
                                    $time = get_slot_interval($cf['data']['heure'], $cf['data']['minute'], $cf['data']['duree']);
                                    $bloc_id = 'rmll-schedule-'.$cf['data']['id_article'];
                                ?>
                                    <div class="conf">
                                        <div class="infos conf-color-<?php  echo get_color_theme($conf[$i]['id']); ?>">
                                            <div class="title">
                                                <?php if (!empty($cf['data']['drap'])) { ?>
                                                    <img class="drap" src="plugins/rmll/img_pack/flags/<?php echo $cf['data']['drap'] ?>.png" alt="" />
                                                <?php } ?>
                                                <a
                                                    href="spip.php?article<?php echo $cf['data']['id_article']; ?>&amp;lang=<?php echo $GLOBALS['lang']; ?>"
                                                    title="<?php echo _T('rmll:accessible_link_article').' « '.$articlestr.' »'; ?>"
                                                    >
                                                    <?php  echo $articlestr; ?>
                                                </a>
                                            </div>
                                            <div class="time">
                                                <span><?php echo $time; ?></span>
                                                <?php echo $this->display_conf_salle($cf['data']); ?>
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
                            }
                    }
                ?>
                </div>
            </div>
            <?php
        }
    }
}
?>