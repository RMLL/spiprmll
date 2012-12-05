<?php
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_options.php';
    require_once _DIR_PLUGIN_RMLL.'rmll_mes_fonctions.php';
    require_once _DIR_PLUGIN_RMLL.'inc/rmll.class.php';

function h($txt) {
	echo $txt;
}

function pre($txt) {
	echo '<pre>';
	h($txt);
	echo '</pre>';
}

function style_table() {
?>
<style type="text/css">
.reverse {
	background-color: black;
	color: white;
}

.building {
	text-align: center;
}

table {
	/*border: 1px dashed black;*/
	border-collapse: collapse;
	word-space: break-word;
	padding: 0;
}

th,td {
	border: 1px solid #555;
	padding: 0;
}

td {
	vertical-align: top;
	font-size: 11px;
	font-family: verdana;
	line-height: 1.2;
}

tfoot tr:first-child td:first-child,
thead tr:first-child td:first-child {
	border: none;
}

tbody tr.room > th {
	vertical-align: middle;
	text-align:center;
	border-top-width: 2px;
	border-bottom-width: 2px;
}

/* Fin droit et entre horaire et 1er bâtiment */
th:first-child + td + td + td + td,
th:first-child + th + th + th + th,
tbody th:first-child {
	border-right: 4px double black;
}

.lunch, .pause {
	padding: 1px;
}

.building span {
	padding: 0 10px;
}

/* Chaque conf */
td div {
	border-bottom: 1px solid black;
	margin: 1px;
	padding-bottom: 5px;
}

td div strong {
	display: block;
}

</style>

<?php
}

function one_conf($t, $prev_theme = false) {
	if($t['titre']) {
		$txt = "
		<div>
			";
		if(!$prev_theme || $t['theme'] != $prev_theme) {
			$txt .= '<strong>' . $t['theme'] . '</strong>';
		}
		$txt .= $t['titre'] . "
			<span></span>
		</div>
		";
	}
	return $txt;
}


class RmllSchedule {
    public $themes_rubriques = null;
    public $periods = null;
    public $days = null;
    public $confObj = null;
    public $page = '';

    function RmllSchedule() {
        $this->periods =  $GLOBALS['rmll_periods'];
        $this->confObj = new Rmll_Conference();
        $this->page = isset($_GET['page']) ? trim($_GET['page']) : '';
    }

    function display() {
/*        $day = isset($_GET['d']) ? trim($_GET['d']) : (isset($_POST['d']) ? trim($_POST['d']) : '');
        $theme = isset($_GET['t']) ? trim($_GET['t']) : (isset($_POST['t']) ? trim($_POST['t']) : '');
        $keyword = isset($_GET['k']) ? trim($_GET['k']) : (isset($_POST['k']) ? trim($_POST['k']) : '');
        $room = isset($_GET['r']) ? trim($_GET['r']) : (isset($_POST['r']) ? trim($_POST['r']) : '');
        if ($day != '' && $room != '') {
*/
		$this->display_room();
/*		} else if ($day != '') {
            $this->display_day($day);
        }
        elseif ($theme != '') {
            $this->display_theme($theme);
        }
        else {
            $this->display_all($keyword, $room);
        }
*/    }

    function extract($datas, $theme_id = null, $day = null, $keyword = 0, $room = 0) {
        $conf = $alldays = $days = $allthemes = $themes = $allrooms = $allkeywords = array();
/*// PHV
echo "<pre style='font-size: 13px'>EXTRACT\n";
echo "ROOM=$room JOUR=$day\n";
//print_r($datas);
*/        for($i=0, $n=count($datas); $i<$n; $i++) {
/*// PHV
echo "\n\nTHEME $i\n";
*/            $articles = $datas[$i]['articles'];
            $new_articles = array();
            if (!empty($articles)) {
                foreach($articles as $article) {
                    $article['data']['start'] = sprintf("%02d:%02d",
                            $article['data']['heure'], $article['data']['minute']);
                    if (!($article['data']['heure'] === null || $article['data']['minute'] === null)) {
                        $room_filter  = $room != 0 && $article['data']['id_salle'] != $room;
                        $keyword_filter = $keyword > 0;
                        if (!empty($article['keywords'])) {
                            foreach($article['keywords'] as $keyid => $keytitle) {
                                if ($keyword_filter && $keyid == $keyword) {
                                    $keyword_filter = false;
                                 }
                                if (!array_key_exists($keyid, $allkeywords)) {
                                    $allkeywords[$keyid] = $keytitle;
                                }
                            }
                        }
// PHV
//echo " #" . $article['data']['id_salle'] . "." . substr($article['data']['jour'], -2);
                        if ($room != 0 && $day !== null) {
                            if (($article['data']['jour'] == $day) && ($article['data']['id_salle'] == $room)) {
                                $new_articles[] = $article;
                                if (!array_key_exists($datas[$i]['id'], $themes)) {
                                    $themes[$datas[$i]['id']] = $datas[$i]['titre'];
                                }
                            }
                        } else if ($day !== null) {
                            if ($article['data']['jour'] == $day) {
                                $new_articles[] = $article;
                                if (!array_key_exists($datas[$i]['id'], $themes)) {
                                    $themes[$datas[$i]['id']] = $datas[$i]['titre'];
                                }
                            }
                        } else {
                            if (!$keyword_filter && !$room_filter) {
                                $new_articles[] = $article;
                                if (!array_key_exists($datas[$i]['id'], $themes)) {
                                    $themes[$datas[$i]['id']] = $datas[$i]['titre'];
                                }
                            }
                        }
                        if (!in_array($article['data']['jour'], $alldays)) {
                            $alldays[] = $article['data']['jour'];
                        }
                        if (($article['data']['jour'] == $day) || $day == 0) {
	                        if ($theme_id !== null) {
	                            if ($datas[$i]['id'] == $theme_id) {
	                                if (!in_array($article['data']['jour'], $days)) {
	                                    $days[] = $article['data']['jour'];
	                                }
	                            }
	                        } else {
	                            if (!$keyword_filter && !$room_filter) {
	                                if (!in_array($article['data']['jour'], $days)) {
	                                    $days[] = $article['data']['jour'];
	                                }
	                            }
	                        }
                        }
                        if (!array_key_exists($datas[$i]['id'], $allthemes)) {
                            $allthemes[$datas[$i]['id']] = $datas[$i]['titre'];
                        }
                        if ($article['data']['id_salle'] > 0 && !array_key_exists($article['data']['id_salle'], $allrooms)) {
                            $allrooms[$article['data']['id_salle']] = $article['data']['salle'];
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
// PHV
/*echo "<pre style='font-size:13px;'>";
print_r($conf);
echo "</pre>";
*/        sort($days);
        sort($alldays);
		// On enlève JGP (2 premiers jours)
		array_shift($days);
		array_shift($days);
/*echo "<pre style='font-size:13px;'>";
print_r($days);
echo "</pre>";
*/
        return array($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords);
    }

	function display_room() {
		$datas = $this->confObj->get_confs(explode(',', RMLL_SESSION_ID), $GLOBALS['lang'], RMLL_KEYWORDS_GROUP_ID);
		list($conf, $alldays, $days, $allthemes, $themes, $allrooms, $allkeywords) = $this->extract($datas, null, $day, $keyword, $room);
if (!empty($conf)) {
	$timeslot = array(	'09:40' => array('10:00', '10:20', '10:40'),
						'11:20' => array('11:40'),
						'14:00' => array('14:20', '14:40', '15:00', '15:20', '15:40'),
						'16:20' => array('16:40', '17:00', '17:20'));
	
	$excluded_id_rooms = array(21, 22, 23);
	$building_names = array('Bâtiment Droit - ', 'Patio - ', ' (1er étage)');	// il faut c/c avec ce fichier en UTF-8
	$short_building_names = array('', '', '');
	
	style_table();

/*	echo "<pre style='font-size:13px;'>";
	print_r($conf);
	echo "</pre>";
*/
	//pre(print_r($allrooms));
	ksort($allrooms);
	$nb_themes = count($themes);
	$nb_salle = count($allrooms);
	
	$prog = array();
	for($i=0; $i<$nb_themes; $i++) {
		$articles = $conf[$i]['articles'];
		foreach($articles as $article) {
/*			echo "<pre style='font-size:13px;background-color: pink'>";
			print_r($article);
			echo "</pre>";
*/
			$articlestr = supprimer_numero(extraire_multi($article['data']['titre']));
			$jour = $article['data']['jour'];
			$salle = supprimer_numero(extraire_multi($article['data']['salle']));
			$debut = $article['data']['start'];
			
			$prog[$jour][$debut][$salle] = array('titre' => $articlestr,
												 'theme' => supprimer_numero(extraire_multi($datas[$i]['titre'])),
												 'intervenants' => supprimer_numero(extraire_multi($article['data']['intervenants'])));
		}
	}
	ksort($prog);
	
	// HEADER
	h('
	<table>
		<thead>
			<tr>
				<td></td>
				<th colspan="4" class="reverse building">DROIT</th>
				<th colspan="16" class="reverse building">PATIO <span>&mdash;</span> Amphis au RDC et Salles au 1er étage</th>
			</tr>
			<tr>
		</thead>
	');

	// FOOTER reprend nom bâtiments pour lisibilité
	h('
		<tfoot>
			<tr>
				<td></td>
				<th colspan="4" class="reverse building">DROIT</th>
				<th colspan="16" class="reverse building">PATIO <span>&mdash;</span> Amphis au RDC et Salles au 1er étage</th>
			</tr>
		</tfoot>
		<tbody>
	');
	foreach($days as $j) {
/*		echo "<pre style='font-size:13px;'>";
		print_r($prog);
		echo "</pre>";
*/
		$daystr = ucfirst(nom_jour($j))."&nbsp;".jour($j);
		h("
			<tr class='room'>
				<th class='reverse '>$daystr</th>\n");
		foreach($allrooms as $k=>$room) {
			if(!in_array($k, $excluded_id_rooms)) {
				h('<th>' . /*$k.'#' . */
					str_replace($building_names, $short_building_names, $room) .
					"</th>\n");
			}
		}
		h("</tr>\n");

		// TOUS LES HORAIRES
		$nb_pause = 0;
		foreach($this->periods as $p) {
			if ($p['type'] == RMLL_PERIOD_PAUSE) {
				++$nb_pause;
				// printf("%s-%s", $p['start'], $p['end']);
				// echo _T('rmll:pause_pause');
				// On n'imprime pas la période 17h40-18h (car bordure plus épaisse à la place)
				if($nb_pause<4) {
					h('
			<tr>
				<td class="timeslot pause" colspan="21"></td>
			</tr>
					');
				}
			} elseif ($p['type'] == RMLL_PERIOD_LUNCH) {
				++$nb_pause;
				h('
			<tr>
				<td class="timeslot lunch pause" colspan="20"></td>
			</tr>
				');
			} elseif ($p['type'] == RMLL_PERIOD_CONF && $nb_pause<4) {	// On n'imprime pas la période 18h-minuit
				// UNE LIGNE HORAIRE
				$tranches = $timeslot[$p['start']];
				h('
						<tr>
							<th>' . $p['start'].'&thinsp;&ndash;&thinsp;'.$p['end'].'</th>
				');
				foreach($allrooms as $k=>$room) {
					if(!in_array($k, $excluded_id_rooms)) {
						//debug "J=$j/start=${p['start']}/room=$room #"
						$prev_theme = false;	// gestion de l'affichage du thème avant la 1ère conf d'une série mais plus ensuite
						h('<td>');
						h( one_conf($prog[$j][$p['start']][$room], $prev_theme) );
						$prev_theme = $prog[$j][$p['start']][$room]['theme'];
						if($tranches !== false) {
							foreach($tranches as $tranche) {
								h( one_conf($prog[$j][$tranche][$room], $prev_theme) );
								$prev_theme = $prog[$j][$p['start']][$room]['theme'];
							}
						h("\n</td>\n");
						}
					}
				}
				h('
						</tr>
				');
			}
		} // fin tous les horaires
	}//fin tous les jours

	h('
		</tbody>
	</table>');
}
	}

}
?>