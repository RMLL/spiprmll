<?php

	global $rmll_prog_date, $rmll_prog_page;
	include("plugins/rmll/inc/rmll.class.php");



	function snip2trad($v) {
		$ret = $v;
		if (isset($GLOBALS[$GLOBALS['idx_lang']][$v]))
			$ret = $GLOBALS[$GLOBALS['idx_lang']][$v];
		return $ret;
	}

	function sorter($a, $b) {
		if ($a['data']['heure'] == $b['data']['heure'])
			return $a['data']['minute'] - $b['data']['minute'];
		else
			return $a['data']['heure'] - $b['data']['heure'];
	} 


	$rc = new Rmll_Conference();
	$conf = $rc->get_all_sub($rmll_prog_page, $GLOBALS['lang']);
?>


	<div class="link-cal">
		<img src="squelettes/images/ical.png" alt="" />
		<a href="spip.php?page=progical&amp;lang=<?php echo $GLOBALS['lang']; ?>&amp;d=<?php echo $rmll_prog_date; ?>">
			<?php echo snip2trad('planning_ical_jour'); ?>
		</a>
	</div>

<?php
	foreach($conf as $theme) {
		$articles = $theme['articles'];
?>
		<h2>
			<a href="spip.php?rubrique=<?php echo $theme['id']; ?>">
				<?php echo supprimer_numero(extraire_trad(nettoyer_raccourcis_typo($theme['titre']))); ?>
			</a>
		</h2>
<?php
		usort($articles, 'sorter');
		foreach($articles as $article) {
			if ($article['data']['jour'] != $rmll_prog_date)
				continue;
?>
			<h3 class="article">
				<?php if (!empty($article['data']['drap'])) { ?>
					<img class="drap" src="squelettes/images/flags/<?php echo $article['data']['drap'] ?>.png" alt="" />
				<?php } ?>
				<?php echo supprimer_numero($article['data']['titre']); ?>
			</h3>
			<div class="description rmllevenements">
				<?php if (!empty($article['data']['intervenants'])) { ?>
					<div class="info">
						<span class="expl">
							<?php echo snip2trad('intervenants'); ?> :
						</span>
						<?php echo $article['data']['intervenants']; ?>
					</div>
				<?php } ?>

				<?php if (!empty($article['data']['nature'])) { ?>
					<div class="info">
						<span class="expl">
							<?php echo snip2trad('type_evenement'); ?> :
						</span>
						<?php echo $article['data']['nature']; ?>
					</div>
				<?php } ?>

				<?php if (!empty($article['data']['niveau'])) { ?>
					<div class="info">
						<span class="expl">
							<?php echo snip2trad('niveau'); ?> :
						</span>
						<?php echo aff_niveau($article['data']['niveau']); ?>
					</div>
				<?php } ?>

				<?php
				$horaire = sprintf("%02d:%02d", $article['data']['heure'], $article['data']['minute']);
				if ($horaire != "00:00") { ?>
					<div class="info">
						<span class="expl">
							<?php echo snip2trad('horaire'); ?> :
						</span>
						<?php echo $horaire; ?>
					</div>
				<?php } ?>

				<?php if (!empty($article['data']['langue'])) { ?>
					<div class="info">
						<span class="expl">
							<?php echo snip2trad('langue'); ?> :
						</span>
						<?php echo $article['data']['langue']; ?>
					</div>
				<?php } ?>

				<?php if (!empty($article['data']['salle'])) { ?>
					<div class="info">
						<span class="expl">
							<?php echo snip2trad('lieu'); ?> :
						</span>
						<?php echo $article['data']['salle']; ?>
					</div>
				<?php } ?>
			</div>

			<?php if (!empty($article['data']['descriptif'])) { ?>
				<div class="description">
					<?php echo propre($article['data']['descriptif']); ?>
				</div>
			<?php } ?>
			<h4 class="more"><a href="spip.php?article=<?php echo $article['data']['id_article']; ?>"><?php echo snip2trad('read_more'); ?></a></h4>
<?php
		}
	}
?>