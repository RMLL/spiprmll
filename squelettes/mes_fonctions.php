<?php

function nom_mois_3l($numdate) {
    $ret = '';
    $date_array = recup_date($numdate);
    if ($date_array) {
        list($annee, $mois, $jour, $heures, $minutes, $secondes) = $date_array;
        $mois = (int) $mois;
        $ret = _T('date_mois_'.$mois);
        if ($mois == 6 || $mois == 7) {
            $ret = couper($ret, 4, '');
        }
        else {
            $ret = couper($ret, 3, '');
        }
    }
    return $ret;
}

?>