<?php

/*
* Plugin Rmll
*
*/

include_spip('inc/rmll.class');

function exec_conference() {
	/* admin ou pas */
	Rmll_Helper::test_acces_admin();
	/* quelques controles ? */
	Rmll_Helper::faire_controles();
	Rmll_Helper::debut_page(_T('rmll:titre_page_gestion'));
	Rmll_Helper::debut_gauche();
	Rmll_Helper::menu_gestion();
	Rmll_Helper::debut_droite();

	$table = new Rmll_Db('horaire');

	Rmll_Helper::titre_gros(_T('rmll:label_import_conference'));

	/* Récupération des données */
	$rubrique = Rmll_Helper::inPost('rubrique');
	if ($rubrique !== null) {
		$fichier = Rmll_Helper::inFile('fichier');
		$messages = $errors = array();
		Rmll_Conference::import_theme($fichier, $rubrique, $messages, $errors);
		if (!$fichier) {
			$errors[] = 'Impossible de lire/analyser le fichier';
		}
		if (!empty($errors)) {
		?>
			<div class="rmll-erreur">
			<?php
				foreach($errors as $error) {
				?>
					<li><?php echo $error; ?></li>
				<?php
				}
			?>
			</div>
		<?php
		}

		if (!empty($messages)) {
		?>
			<div class="rmll-infos">
			<?php
				foreach($messages as $message) {
				?>
					<li><?php echo $message; ?></li>
				<?php
				}
			?>
			</div>
		<?php
		}
	}
	?>
	<div class="rmll-attention">
		<?php echo _T('rmll:message_warning_import'); ?>
	</div>
	<?php

	Rmll_Helper::formulaire_debut(generer_url_ecrire('conference'), array('class' => 'rmll', 'enctype' => 'multipart/form-data'));
	?>
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_id_theme').' :');
		Rmll_Helper::formulaire_texte('rubrique');
	?>
	</div>
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_id_auteur').' :');
		Rmll_Helper::formulaire_texte('auteur');
	?>
	</div>
	<div class="element">
	<?php
		Rmll_Helper::formulaire_label(_T('rmll:label_fichier').' :');
		Rmll_Helper::formulaire_fichier('fichier');
	?>
	</div>
	<div class="element">
    <?php
        Rmll_Helper::formulaire_soumettre(_T('rmll:label_importer'));
    ?>
    </div>
	<?php
	Rmll_Helper::formulaire_fin();
	
	Rmll_Helper::fin_page();
}
?>