<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/rmll.class');

function exec_planjour() {

	/* quelques controles ? */
	Rmll_Helper::faire_controles();
	
	debut_page(_T('rmll:titre_menu_planning'));
	
	debut_gauche();
	
	Rmll_Helper::titre_gros(_T('rmll:label_planning_jours'));
	Rmll_Helper::menu_planning();

	debut_droite();
	
	$conf = new Rmll_Conference();
	
	$jours = new Rmll_Db('jour');
	$liste_jours = $jours->get_all('date');
	
	if ($liste_jours) {
		foreach($liste_jours as $j) {
			?>
				<a href=""><?php echo $j['date']; ?></a>
			<?php
		}
	}


	print "<pre>";
	var_dump($conf->get_all_sub(3));
	print "</pre>";
/*
	$liste_confs = $conf->get_all_ord_jour();

	foreach($liste_confs as $date => $lconfs) {
		if ($date == '')
			Rmll_Helper::titre_gros(_T('rmll:label_indefini'));
		else
			Rmll_Helper::titre_gros(ucfirst(Rmll_Helper::date_en_texte($date)));

		foreach($lconfs as $ids => $confs) {
			if ($confs['nom'] == '')
				Rmll_Helper::titre_moyen(_T('rmll:label_indefini'));
			else
				Rmll_Helper::titre_moyen($confs['nom']);
			foreach($confs['liste'] as $conf) {
		?>
			'<table class="rmll-evenement-show">
				<tr>
					<th><?php echo _T('rmll:label_horaire'); ?> :</th>
					<td><?php echo $conf['heure'].' '.$conf['minute']; ?></td>
					<th><?php echo _T('rmll:label_duree'); ?> :</th>
					<td><?php echo $conf['duree']; ?></td>
				</tr>
				<tr>
					<th><?php echo _T('rmll:label_langue'); ?> :</th>
					<td><?php echo $conf['langue']; ?></td>
					<th><?php echo _T('rmll:label_nature'); ?> :</th>
					<td colspan="3"><?php echo $conf['nature']; ?></td>
				</tr>
				<tr>
					<th><?php echo _T('rmll:label_intervenants'); ?> :</th>
					<td colspan="5"><?php echo $conf['intervenants']; ?></td>
				</tr>
				<tr>
					<th><?php echo _T('rmll:label_videourl'); ?> :</th>
					<td colspan="5"><?php echo $conf['video']; ?></td>
				</tr>
			</table>
		<?php
			}
		}
	}
*/
	echo fin_page();
}
?>
