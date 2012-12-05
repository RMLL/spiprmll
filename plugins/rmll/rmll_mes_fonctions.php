<?php
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_options.php';

function balise_RMLL_GET_SESSIONS($p) {
    $_nom = interprete_argument_balise(1,$p);
    if ($_nom) {
        $p->code = "vide(\$Pile['vars'][$_nom] = explode(',', constant('RMLL_SESSION_ID')))";
    }
    else {
        $p->code = "''";
    }
    $p->interdire_scripts = false; // la balise ne renvoie rien
    return $p;
}

/*
function rmll_get_sessions_subs($session) {
    require_once _DIR_PLUGIN_RMLL.'inc/rmll.class.php';

    $rmllc = new Rmll_Conference();
    $ret = array($session) + $rmllc->get_all_sousrubriques($session);
    return $ret;
}

function balise_RMLL_GET_SESSIONS_SUBS($p) {
    $_nom = interprete_argument_balise(1,$p);
    $_session = interprete_argument_balise(2,$p);
    if ($_nom && $_session) {

        $p->code = "vide(\$Pile['vars'][$_nom] = rmll_get_sessions_subs($_session))";
    }
    else {
        $p->code = "''";
    }
    $p->interdire_scripts = false; // la balise ne renvoie rien
    return $p;
}
*/

function get_dl_filename($params) {
    if (trim($params['prefix']) == '') {
        $filename = 'schedule';
    }
    else {
        $filename = $params['prefix'];
    }

    if (trim($params['theme']) != '') {
        $filename .= '_theme_'.$params['theme'];
    }
    if (trim($params['keyword']) != '') {
        $filename .= '_transversal_'.$params['keyword'];
    }
    if (trim($params['date']) != '') {
        $filename .= '_day_'.$params['date'];
    }
    if (trim($params['nature']) != '') {
        $filename .= '_nature_'.str_replace(',', '-', $params['nature']);
    }

    if (trim($params['suffix']) == '') {
        $filename .= '_global';
    }
    else {
        $filename .= '_'.$params['suffix'];
    }
    return $filename;
}

function get_color_theme ($theme_id) {
    $ret = 0;
    $id = array_search($theme_id, explode(',', RMLL_SESSION_ID));
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
    return _T('rmll:'.$code);
}

function aff_date_complete($date){
    return ucfirst(nom_jour($date). ' ' .affdate($date));
}

function horairise($num){
	return sprintf("%02d", intval($num));
}

function aff_salle($salle) {
    /*
    $eq = array(
        'Amphi' => 'Lecture hall',
        'Salle' => 'Room',
        'Sous-sol' => 'Basement floor',
        'Rez de chaussé' => 'Ground floor',
        '1er étage' => '1st floor',
        '2nd étage' => '2nd floor',
    );
    if ($GLOBALS['lang'] != 'fr') {
        $salle = str_replace(array_keys($eq), array_values($eq), $salle);
    }
    */
    return $salle;
}

function aff_niveau($code) {
	$code = 'niveau_code_'.$code;
    return _T('rmll:'.$code);
}

function aff_langue($code) {
    $code = 'langue_code_'.$code;
    return _T('rmll:'.$code);
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


/* From http://alanwhipple.com/2011/05/25/php-truncate-string-preserving-html-tags-words/ */
function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = mb_strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                    unset($open_tags[$pos]);
                    }
                // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, mb_strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += mb_strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= mb_substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = mb_strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}


class HtmlCutString{
  function __construct($string, $limit, $ending){
    // create dom element using the html string
    $this->tempDiv = new DomDocument;
    $this->tempDiv->loadXML('<div>'.$string.'</div>');
    // keep the characters count till now
    $this->charCount = 0;
    $this->encoding = 'UTF-8';
    // character limit need to check
    $this->limit = $limit;
    $this->ending = $ending;
  }
  function cut(){
    // create empty document to store new html
    $this->newDiv = new DomDocument;
    // cut the string by parsing through each element
    $this->searchEnd($this->tempDiv->documentElement,$this->newDiv);
    $newhtml = $this->newDiv->saveHTML();
    return $newhtml;
  }

  function deleteChildren($node) {
    while (isset($node->firstChild)) {
      $this->deleteChildren($node->firstChild);
      $node->removeChild($node->firstChild);
    }
  }
  function searchEnd($parseDiv, $newParent){
    foreach($parseDiv->childNodes as $ele){
    // not text node
    if($ele->nodeType != 3){
      $newEle = $this->newDiv->importNode($ele,true);
      if(count($ele->childNodes) === 0){
        $newParent->appendChild($newEle);
        continue;
      }
      $this->deleteChildren($newEle);
      $newParent->appendChild($newEle);
      $res = $this->searchEnd($ele,$newEle);
      if($res)
        return $res;
      else{
        continue;
      }
    }

    // the limit of the char count reached
    if(mb_strlen($ele->nodeValue,$this->encoding) + $this->charCount >= $this->limit){
      $newEle = $this->newDiv->importNode($ele);
        $newEle->nodeValue = mb_substr($newEle->nodeValue,0, $this->limit - $this->charCount).$this->ending;
        $newParent->appendChild($newEle);
        return true;
    }
    $newEle = $this->newDiv->importNode($ele);
    $newParent->appendChild($newEle);
    $this->charCount += mb_strlen($newEle->nodeValue,$this->encoding);
    }
    return false;
  }
}

function cut_html_string($string, $limit){
  $output = new HtmlCutString($string, $limit);
  return $output->cut();
}

function filtre_couperhtml($texte, $n = 180, $ending = '...') {
    //return $texte;
    //return truncateHtml($texte, $n, $ending);
    $output = new HtmlCutString($texte, $n, $ending);
    return $output->cut();
}

function supprime_intertitre($texte) {
    return preg_replace('/\{\{\{.*\}\}\}/U', '', $texte);
}

function prepare_meta($texte) {
    $texte = preg_replace('#<h3[^>]*>.*</h3>#Ui', '', $texte);
    $texte = textebrut($texte);
    $texte = preg_replace("#(\r|\n| )+#", ' ', $texte);
    $texte = trim($texte);
    return $texte;
}

function resume($texte, $longueur = 300, $fin = '...') {
    $texte = supprime_intertitre($texte);
    $texte = supprime_img($texte);
    $texte = propre($texte);
    $texte = strip_tags($texte);
    $texte = preg_replace("/\r/", "\n", $texte);
    $texte = preg_replace("/[\n]+/", "\n", $texte);
    $texte = mb_substr($texte, 0, $longueur).$fin;
    $texte = nl2br($texte);
    return $texte;
}

?>