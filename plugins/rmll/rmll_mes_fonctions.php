<?php

function get_dl_filename($params) {
    $filename = "planning";
    if (trim($params['date']) == '')
        $filename .= '_global';
    else
        $filename .= '_'.trim($params['date']);
    if (trim($params['theme']) != '')
        $filename .= '_theme'.trim($params['theme']);
    return $filename;
}

function get_color_theme ($list_themes, $theme_id) {
    $ret = 0;
    $id = array_search($theme_id, $list_themes);
    if (!($id === false))
        $ret = $id;
    return $ret;
}

function get_slot_interval($h, $m, $d) {
    list($year, $month, $day) = explode('-', date('Y-n-j'));
    $t1 = mktime($h, $m, 0, $month, $day, $year);
    $t2 = $t1 + ($d*60);
    return sprintf("%s-%s", strftime("%R", $t1), strftime("%R", $t2));
}

function time_sorter($a, $b) {
    if ($a['data']['heure'] == $b['data']['heure'])
        return $a['data']['minute'] - $b['data']['minute'];
    else
        return $a['data']['heure'] - $b['data']['heure'];
}


function aff_nature($code) {
    $code = 'nature_code_'.$code;
    $ret = '';
    if (isset($GLOBALS[$GLOBALS['idx_lang']][$code]))
        $ret = $GLOBALS[$GLOBALS['idx_lang']][$code];
    return $ret;
}

function aff_date_complete($date){
    return ucfirst(nom_jour($date). ' ' .affdate($date));
}

function horairise($num){
	return sprintf("%02d", intval($num));
}

function aff_niveau($code) {
	$code = 'niveau_code_'.$code;
	$ret = '';
	if (isset($GLOBALS[$GLOBALS['idx_lang']][$code]))
		$ret = $GLOBALS[$GLOBALS['idx_lang']][$code];
	return $ret;
}

function code2utf($num) {
	if ($num < 128) return chr($num);
	if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
	if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	return '';
}

function utf8_unhtml($string) {
	static $trans_tbl;

	// replace numeric entities
	$string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $string);

	// replace literal entities
	if (!isset($trans_tbl)) {
		$trans_tbl = array();

		foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
			$trans_tbl[$key] = utf8_encode($val);
	}

	return strtr($string, $trans_tbl);
}

function normalise_html($text) {
	return str_replace(array("<", ">", "&"), array("&lt;", "&gt;", "&amp;"), $text);
}

?>