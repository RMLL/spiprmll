<?php
	global $rmll_prog_page_date, $rmll_prog_page;

	include("plugins/rmll/inc/rmll.class.php");
	$jours = new Rmll_Db('jour');
	$ljours = $jours->get_all();

	$rc = new Rmll_Conference();
	$conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);

	function prepare2js($texte) {
		//return addslashes(extraire_trad(traiter_raccourcis(str_replace(array("\r\r", "\r\n", "\r"), " ", $texte))));
		//return addslashes(extraire_trad(nettoyer_raccourcis_typo(str_replace(array("\r\r", "\r\n", "\r"), " ", $texte))));
		return addslashes(supprimer_numero(extraire_trad(str_replace(array("\r\r", "\r\n", "\r", "\n"), " ", propre($texte)))));
	}

	function snip2trad($v) {
		$ret = $v;
		if (isset($GLOBALS[$GLOBALS['idx_lang']][$v]))
			$ret = addslashes($GLOBALS[$GLOBALS['idx_lang']][$v]);
		return $ret;
	}

	function sorter($a, $b) {
		if ($a['data']['heure'] == $b['data']['heure'])
			return $a['data']['minute'] - $b['data']['minute'];
		else
			return $a['data']['heure'] - $b['data']['heure'];
	}

	?>
	<script type="text/javascript">
		//<![CDATA[
			var conferences = [
			<?php
				$clist = array();
				foreach($conf as $theme) {
					$articles = $theme['articles'];
					foreach($articles as $article) {
						$clist[] = sprintf("{ 'id' : %d, 'drap' : \"%s\", 'titre' : \"%s\", 'jour' : \"%s\", 'horaire' : \"%s\", 'duree' : \"%s\", 'langue' : \"%s\", 'nature' : \"%s\", 'niveau' : \"%s\", 'lieu' : \"%s\", 'description' : \"%s\", 'intervenants' : \"%s\" }",
							$article['data']['id_article'],
							$article['data']['drap'],
							prepare2js($article['data']['titre']),
							ucfirst(nom_jour($article['data']['jour']).' '.affdate($article['data']['jour'])),
							sprintf("%02d:%02d", $article['data']['heure'], $article['data']['minute']),
							prepare2js($article['data']['duree']),
							prepare2js($article['data']['langue']), prepare2js($article['data']['nature']),
							prepare2js(aff_niveau($article['data']['niveau'])),
							prepare2js($article['data']['salle']),
							prepare2js($article['data']['descriptif']),
							prepare2js($article['data']['intervenants'])
							);
					}
				}
				echo implode(",\n", $clist);
			?>
			];

			var tooltip_id = "tooltip";
			var tooltip_disp = false;
			var mouse_x = 0;
			var mouse_y = 0;

			function getMousePos (e) {
				if (!e) var e = window.event;
				if (e.pageX || e.pageY) 	{
					mouse_x = e.pageX;
					mouse_y = e.pageY;
				}
				else if (e.clientX || e.clientY) 	{
					mouse_x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
					mouse_y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
				}
			}

			function tooltip_show (text) {
				if (tooltip_disp)
					tooltip_hide ();
				o = document.getElementById(tooltip_id);
				if (o) {
					if (mouse_x != 0 && mouse_y != 0) {
						o.style.left = (mouse_x - 300) +"px";
						o.style.top = (mouse_y + 20) +"px";
						o.style.visibility = "visible";
						o.innerHTML = text;
						tooltip_disp = true;
					}
				}
			}

			function tooltip_hide () {
				o = document.getElementById(tooltip_id);
				if (tooltip_disp && o) {
					o.style.visibility = "hidden";
					tooltip_disp = false;
				}
			}

			function aff_programme (id) {

				for (var i = 0, n = conferences.length; i < n; i++) {
					if (conferences[i]['id'] == id) {
						var t = '<h1>';
						if (conferences[i]['drap'] != '')
							t += '<img class="drap" src="squelettes/images/flags/'+ conferences[i]['drap'] +'.png" alt="" />';
						t += conferences[i]['titre'] + '</h1>';
						t += '<div class="description rmllevenements">';
						t += '<div class="info"><span class="expl"><?php echo snip2trad('date'); ?> :</span>'+conferences[i]['jour']+'</div>';
						if (conferences[i]['intervenants'] != '')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('intervenants');?> :</span>'+conferences[i]['intervenants']+'</div>';
						if (conferences[i]['nature'] != '')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('type_evenement'); ?> :</span>'+conferences[i]['nature']+'</div>';
						if (conferences[i]['niveau'] != '')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('niveau'); ?> :</span>'+conferences[i]['niveau']+'</div>';
						if (conferences[i]['horaire'] != '00:00')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('horaire'); ?> :</span>'+conferences[i]['horaire']+'</div>';
						if (conferences[i]['duree'] != '0')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('duree'); ?> :</span>'+conferences[i]['duree']+' <?php echo snip2trad('minutes'); ?></div>';
						if (conferences[i]['langue'] != '')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('langue'); ?> :</span>'+conferences[i]['langue']+'</div>';
						if (conferences[i]['lieu'] != '')
							t += '<div class="info"><span class="expl"><?php echo snip2trad('lieu'); ?> :</span>'+conferences[i]['lieu']+'</div>';
						t += '</div>';

						if (conferences[i]['description'] != '')
							t += '<div class="description">'+conferences[i]['description']+'</div>';
						return tooltip_show(t);
					}
				}
				return false;
			}

			document.onmousemove = getMousePos;
		//]]>
	</script>

	<div id="tooltip" style="position: absolute; visibility : hidden;"></div>

	<br />
	<div class="link-cal">
		<img src="squelettes/images/ical.png" alt="" />
		<a href="spip.php?page=progical&amp;lang=<?php echo $GLOBALS['lang']; ?>">
			<?php echo snip2trad('planning_ical'); ?>
		</a>
	</div>

	<div class="planning-box">
		<table border="1" class="planning">
			<tr class="dates">
				<th></th>
				<?php
					foreach($ljours as $jo) {
				?>
					<th>
						<a href="<?php printf("%s&amp;d=%s&amp;lang=%s", $rmll_prog_page_date, $jo['date'], $GLOBALS['lang']); ?>">
							<?php echo ucfirst(nom_jour($jo['date']).' '.affdate($jo['date'])); ?>
						</a>
					</th>
				<?php
					}
				?>
			</tr>
			<?php
				$i = 0;
				foreach($conf as $theme) {
					$i++;
					$oddeven = ' '.(($i % 2) == 0 ? 'odd' : 'even');
					$articles = $theme['articles'];
					usort($articles, 'sorter');
			?>
				<tr class="conf<?php echo $oddeven; ?>">
					<th>
						<a href="spip.php?page=progical&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;t=<?php echo $theme['id']; ?>" title="<?php echo snip2trad('planning_ical_theme'); ?>">
							<img src="squelettes/images/ical-small.png" alt="<?php echo snip2trad('planning_ical_theme'); ?>" />
						</a>
						<a href="spip.php?rubrique=<?php echo $theme['id']; ?>"><?php echo supprimer_numero(extraire_trad(nettoyer_raccourcis_typo($theme['titre']))); ?></a>
					</th>
				<?php
					foreach($ljours as $jour) {
				?>
					<td>
					<?php
						foreach($articles as $article) {
							if ($article['data']['id_jour'] == $jour['id_jour']) {
								echo '<a href="spip.php?article='.$article['data']['id_article'].'" onmouseover="javascript:aff_programme(\''.$article['data']['id_article'].'\');" onmouseout="javascript:tooltip_hide();"></a>';
							}

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
