<?php
    define('RMLL_PROG_ID', '4');
    define('RMLL_THEME_ID', '52,19,20,21,22,23,24,25,50,51');
    define('RMLL_SESSION_ID', '52,30,31,32,33,34,35,20,21,22,23,24,25,50,51');
    define('RMLL_KEYWORDS_GROUP_ID', 2);

    function rmll_is_prog($id) {
        return (int) $id === (int) RMLL_PROG_ID;
    }

    function rmll_is_theme($id) {
        return in_array($id, explode(',', RMLL_THEME_ID));
    }

    // periods (to build timetables)
    define('RMLL_PERIOD_CONF', 1);
    define('RMLL_PERIOD_PAUSE', 2);
    define('RMLL_PERIOD_LUNCH', 3);
    $rmll_periods = array(
        array('start' => '09:20', 'end' => '10:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '10:40', 'end' => '11:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '11:00', 'end' => '11:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '11:40', 'end' => '14:00', 'type' => RMLL_PERIOD_LUNCH),
        array('start' => '14:00', 'end' => '16:00', 'type' => RMLL_PERIOD_CONF),
        array('start' => '16:00', 'end' => '16:20', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '16:20', 'end' => '17:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '17:40', 'end' => '19:00', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '19:00', 'end' => '23:59', 'type' => RMLL_PERIOD_CONF),
    );
?>