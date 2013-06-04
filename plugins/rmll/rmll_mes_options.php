<?php
    define('RMLL_PROG_ID', '100'); # rubrique racine
    define('RMLL_THEME_ID', '110,120,130,140,150,160,170'); # pour les thèmes subdivisés
    define('RMLL_SESSION_ID', '101,102,103,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,26,25,27,28,29'); # les thèmes finaux
    define('RMLL_KEYWORDS_GROUP_ID', 1);

    function rmll_is_prog($id) {
        return (int) $id === (int) RMLL_PROG_ID;
    }

    function rmll_is_theme($id) {
        return in_array($id, explode(',', RMLL_THEME_ID));
    }

    function rmll_is_session($id) {
        return in_array($id, explode(',', RMLL_SESSION_ID));
    }

    // periods (to build timetables)
    define('RMLL_PERIOD_CONF', 1);
    define('RMLL_PERIOD_PAUSE', 2);
    define('RMLL_PERIOD_LUNCH', 3);
    define('RMLL_PERIOD_CONF_PE', 4);

    $rmll_schedule_periods = array();
    $rmll_schedule_periods['default'] = array(
        array('start' => '09:20', 'end' => '10:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '10:40', 'end' => '11:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '11:00', 'end' => '12:20', 'type' => RMLL_PERIOD_CONF),
        array('start' => '12:20', 'end' => '14:00', 'type' => RMLL_PERIOD_LUNCH),
        array('start' => '14:00', 'end' => '16:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '16:00', 'end' => '16:20', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '16:20', 'end' => '18:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '18:00', 'end' => '20:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '20:00', 'end' => '23:59', 'type' => RMLL_PERIOD_CONF),
    );
    $rmll_schedule_periods['2013-07-09'] = array(
        array('start' => '09:20', 'end' => '10:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '10:40', 'end' => '11:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '11:00', 'end' => '12:20', 'type' => RMLL_PERIOD_CONF),
        array('start' => '12:20', 'end' => '15:00', 'type' => RMLL_PERIOD_LUNCH),
        array('start' => '15:00', 'end' => '18:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '18:00', 'end' => '19:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '19:00', 'end' => '23:59', 'type' => RMLL_PERIOD_CONF),
    );
    $rmll_schedule_periods['2013-07-10'] = array(
        array('start' => '10:00', 'end' => '12:20', 'type' => RMLL_PERIOD_CONF),
        array('start' => '12:20', 'end' => '14:00', 'type' => RMLL_PERIOD_LUNCH),
        array('start' => '14:00', 'end' => '16:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '16:00', 'end' => '16:20', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '16:20', 'end' => '18:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '18:00', 'end' => '20:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '20:00', 'end' => '23:59', 'type' => RMLL_PERIOD_CONF),
    );
    /*$rmll_schedule_periods['2012-07-07'] = array(
        array('start' => '10:00', 'end' => '18:00', 'type' => RMLL_PERIOD_CONF),
    ); */
    //$rmll_schedule_periods['2012-07-08'] = $rmll_schedule_periods['2012-07-07'];

    //$rmll_schedule_periods['theme_33'] = $rmll_schedule_periods['2012-07-07'];

    define('RMLL_XMLSCHEDULE_TITLE', 'RMLL/LSM '.date('Y'));
    define('RMLL_XMLSCHEDULE_SUBTITLE', 'RMLL/LSM meeting');
    define('RMLL_XMLSCHEDULE_PLACE', '...');
    define('RMLL_XMLSCHEDULE_CITY', 'Geneva');
    define('RMLL_XMLSCHEDULE_START', '2013-07-08'); // YYYY-MM-DD
    define('RMLL_XMLSCHEDULE_NUMDAYS', 5);
    define('RMLL_XMLSCHEDULE_DURATION', '00:40'); // HH:MM
    define('RMLL_XMLSCHEDULE_SUPPORTED_LANGS', 'fr,en'); // lang1,lang2,lang3
    define('RMLL_XMLSCHEDULE_DEFAULT_LANG', 'en'); 
?>