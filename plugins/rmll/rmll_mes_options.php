<?php
    define('RMLL_PROG_ID', '6');
    // 2011 complet = '7,14,9,10,11,12,13,15,16,17,18,19,20'
    define('RMLL_SESSION_ID', '33,7,14,9,10,11,12,13,15,16,17,18,19,20');
    define('RMLL_KEYWORDS_GROUP_ID', 1);

    function rmll_is_prog($id) {
        return (int) $id === (int) RMLL_PROG_ID;
    }

    function rmll_is_session($id) {
        return in_array($id, explode(',', RMLL_SESSION_ID));
    }

    // periods (to build timetables)
    define('RMLL_PERIOD_CONF', 1);
    define('RMLL_PERIOD_PAUSE', 2);
    define('RMLL_PERIOD_LUNCH', 3);
    $rmll_periods = array(
        array('start' => '09:40', 'end' => '11:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '11:00', 'end' => '11:20', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '11:20', 'end' => '12:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '12:00', 'end' => '14:00', 'type' => RMLL_PERIOD_LUNCH),
        array('start' => '14:00', 'end' => '16:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '16:00', 'end' => '16:20', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '16:20', 'end' => '17:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '17:40', 'end' => '18:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '18:00', 'end' => '23:59', 'type' => RMLL_PERIOD_CONF),
    );

    define('RMLL_XMLSCHEDULE_TITLE', 'RMLL/LSM '.date('Y'));
    define('RMLL_XMLSCHEDULE_SUBTITLE', 'RMLL/LSM meeting');
    define('RMLL_XMLSCHEDULE_PLACE', '...');
    define('RMLL_XMLSCHEDULE_CITY', 'Strasbourg');
    define('RMLL_XMLSCHEDULE_START', '2011-07-09'); // YYYY-MM-DD
    define('RMLL_XMLSCHEDULE_NUMDAYS', 5);
    define('RMLL_XMLSCHEDULE_DURATION', '00:40'); // HH:MM
    define('RMLL_XMLSCHEDULE_SUPPORTED_LANGS', 'fr,en'); // lang1,lang2,lang3
    define('RMLL_XMLSCHEDULE_DEFAULT_LANG', 'en'); 
?>