<?php
    global $rmll_prog_page, $rmll_themes_rubriques, $rmll_periods;

    // Rubrique 'Programmes' id
    $rmll_prog_page = 4;

    // Rubrique for themes
    $rmll_themes_rubriques = array(22, 23, 24, 28, 30, 33, 35, 39, 25, 26, 27, 29, 31, 32, 34, 36, 37, 42, 47, 46);

    // periods (to build timetables)
    define('RMLL_PERIOD_CONF', 1);
    define('RMLL_PERIOD_PAUSE', 2);
    define('RMLL_PERIOD_LUNCH', 3);
    $rmll_periods = array(
        array('start' => '09:00', 'end' => '10:20', 'type' => RMLL_PERIOD_CONF),
        array('start' => '10:20', 'end' => '10:50', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '10:50', 'end' => '11:30', 'type' => RMLL_PERIOD_CONF),
        array('start' => '11:30', 'end' => '14:00', 'type' => RMLL_PERIOD_LUNCH),
        array('start' => '14:00', 'end' => '15:40', 'type' => RMLL_PERIOD_CONF),
        array('start' => '15:40', 'end' => '16:10', 'type' => RMLL_PERIOD_PAUSE),
        array('start' => '16:10', 'end' => '17:50', 'type' => RMLL_PERIOD_CONF),
    );
?>