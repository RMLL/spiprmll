<?php

/*
 * Plugin Rmll
 *
 */

include_spip('inc/presentation');

class Rmll_Event {

	/*
	 * Formulaire de saisie d'un événement du programme
	 */
	function Form ($id_article, $script, $ajaxcall = false) {
		$ret = '';

		$bouton = bouton_block_invisible("rmll-evenement-article");

        if (!$ajaxcall)
            $ret .= '<div class="rmll-editer-evenement" id="editer_evenement-'.$id_article.'">';
		$ret .= debut_cadre_enfonce(_DIR_PLUGIN_RMLL."/img_pack/armelle-24.png", true, "", $bouton._T('rmll:label_programme'));

		$evenement = null;
		$jour = null;
		$horaire = null;
		$duree = null;
		$langue = null;
		$nature = null;
		$niveau = null;
		$salle = null;
		$intervenants = null;
		$video = null;

		/*** encart affichant les données déjà enregistrées ***/
		$conference = new Rmll_Db('conference');
		$conf = $conference->get_one_where('id_article = '.Rmll_Db::esc($id_article));
		if ($conf) {
			$evenement = $conf['id_conference'];
			$jour = $conf['id_jour'];
			$horaire = $conf['id_horaire'];
			$duree = $conf['duree'];
			$langue = $conf['id_langue'];
			$nature = $conf['id_nature'];
			$niveau = $conf['id_niveau'];
			$salle = $conf['id_salle'];
			$intervenants = $conf['intervenants'];
			$video = $conf['video'];
		}

		/*** remplisssage des tableaux de données ***/
		/* jour */
		$table_jour = new Rmll_Db('jour');
		$jour_liste = array(0 => _T('rmll:label_indefini'));
		foreach ($table_jour->get_all('date') as $j)
			$jour_liste[$j['id_jour']] = Rmll_Helper::date_en_texte($j['date']);
		/* horaire */
		$table_horaire = new Rmll_Db('horaire');
		$horaire_liste = array(0 => _T('rmll:label_indefini'));
		foreach ($table_horaire->get_all('heure, minute') as $h)
			$horaire_liste[$h['id_horaire']] = sprintf("%02dh%02d", $h['heure'], $h['minute']);
		/* duree */
		$duree_liste = array(0 => _T('rmll:label_indefini'));
		for($i = 20; $i<=180; $i+=20)
			$duree_liste[$i] = sprintf("%02dh%02d", intval($i/60), ($i%60));
		/* langue */
		$table_langue = new Rmll_Db('langue');
		$langue_liste = array(0 => _T('rmll:label_indefini'));
		foreach ($table_langue->get_all('nom') as $h)
			$langue_liste[$h['id_langue']] = $h['nom'];
		/* nature */
		$table_nature = new Rmll_Db('nature');
		$nature_liste = array(0 => _T('rmll:label_indefini'));
		foreach ($table_nature->get_all('nom') as $h)
			$nature_liste[$h['id_nature']] = $h['nom'];
		/* niveau */
		$table_niveau = new Rmll_Db('niveau');
		$niveau_liste = array(0 => _T('rmll:label_indefini'));
		foreach ($table_niveau->get_all('nom') as $h)
			$niveau_liste[$h['id_niveau']] = $h['nom'];
		/* salle */
		$table_salle = new Rmll_Db('salle');
		$salle_liste = array(0 => _T('rmll:label_indefini'));
		foreach ($table_salle->get_all('nom') as $h)
			$salle_liste[$h['id_salle']] = $h['nom'];


		$form = '';

		if ($conf) {
			$suppr = Rmll_Helper::formulaire_cache('suppr_evenement', $evenement, true);
			$suppr .= Rmll_Helper::formulaire_image('suppr.png', '', null, true);
			$suppr = ajax_action_auteur('editer_evenement',$id_article, $script, "id_article=".$id_article, $suppr, '', '');

			$ret .= '<table width="100%" class="rmll-evenement-show">
                    <tr>
                        <td colspan="4" class="suppr">'.$suppr.'</td>
                    </tr>
					<tr>
                        <th>'._T('rmll:label_jour').' :</th>
						<td>'.$jour_liste[$jour].'</td>
						<th>'._T('rmll:label_langue').' :</th>
                        <td>'.$langue_liste[$langue].'</td>
					</tr>
					<tr>
						<th>'._T('rmll:label_horaire').' :</th>
						<td>'.$horaire_liste[$horaire].'</td>
						<th>'._T('rmll:label_duree').' :</th>
						<td>'.$duree_liste[$duree].'</td>
					</tr>
                    <tr>
                        <th>'._T('rmll:label_salle').' :</th>
                        <td colspan="3">'.$salle_liste[$salle].'</td>
                    </tr>
					<tr>
						<th>'._T('rmll:label_nature').' :</th>
						<td>'.$nature_liste[$nature].'</td>
						<th>'._T('rmll:label_niveau').' :</th>
						<td>'.$niveau_liste[$niveau].'</td>
					</tr>
					<tr>
						<th>'._T('rmll:label_intervenants').' :</th>
						<td colspan="3">'.$intervenants.'</td>
					</tr>
					<tr>
						<th>'._T('rmll:label_videourl').' :</th>
						<td colspan="3  ">'.$video.'</td>
					</tr>
				</table>
			';

			$form .= Rmll_Helper::formulaire_cache('evenement', $evenement, true);
		}

		$form .= '<table class="rmll-evenement-edit">
			<tr>
				<th>'.Rmll_Helper::formulaire_label(_T('rmll:label_jour').' :', null, true).'</th>
				<td>'.Rmll_Helper::formulaire_selection("jour", $jour_liste, $jour, null, true).'</td>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_langue').' :', null, true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("langue", $langue_liste, $langue, null, true).'</td>
			</tr>
			<tr>
				<th>'.Rmll_Helper::formulaire_label(_T('rmll:label_horaire').' :', null, true).'</th>
				<td>'.Rmll_Helper::formulaire_selection("horaire", $horaire_liste, $horaire, null, true).'</td>
				<th>'.Rmll_Helper::formulaire_label(_T('rmll:label_duree').' :', null, true).'</th>
				<td>'.Rmll_Helper::formulaire_selection("duree", $duree_liste, $duree, null, true).'</td>
			</tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_salle').' :', null, true).'</th>
                <td colspan="3">'.Rmll_Helper::formulaire_selection("salle", $salle_liste, $salle, null, true).'</td>
            </tr>
			<tr>
				<th>'.Rmll_Helper::formulaire_label(_T('rmll:label_nature').' :', null, true).'</th>
				<td>'.Rmll_Helper::formulaire_selection("nature", $nature_liste, $nature, null, true).'</td>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_niveau').' :', null, true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("niveau", $niveau_liste, $niveau, null, true).'</td>
			</tr>
			</tr>
			<tr>
				<th>'.Rmll_Helper::formulaire_label(_T('rmll:label_intervenants').' :', null, true).'</th>
				<td colspan="3">'.Rmll_Helper::formulaire_texte("intervenants", $intervenants, array('size' => 50), true).'</td>
			</tr>
			<tr>
				<th>'.Rmll_Helper::formulaire_label(_T('rmll:label_videourl').' :', null, true).'</th>
				<td colspan="3">'.Rmll_Helper::formulaire_texte("video", $video, array('size' => 50), true).'</td>
			</tr>
			</table>
			';

		$form .= Rmll_Helper::formulaire_soumettre(_T('rmll:label_enregistrer'), null, true);

		$form = '<div class="form-rmll">'.$form.'</div>';
		$form = debut_block_invisible('rmll-evenement-article').$form.fin_block();


		$ret .= ajax_action_auteur('editer_evenement',$id_article, $script, "id_article=".$id_article, $form, '', '');

		$ret .= fin_cadre_enfonce(true);
        if (!$ajaxcall)
            $ret .= '</div>';
		return $ret;
	}

	function save($values) {
		$conference = new Rmll_Db('conference');
		return $conference->insert($values);
	}

	function update($values, $id) {
		$conference = new Rmll_Db('conference');
		return $conference->update($values, $id);
	}

	function delete($id) {
		$conference = new Rmll_Db('conference');
		return $conference->delete($id);
	}
}

class Rmll_Helper {

	/*
	* Test d'accès à la page
	*/
	function test_acces_admin() {
		global $connect_statut, $connect_toutes_rubriques;

		if (!($connect_statut == '0minirezo' AND $connect_toutes_rubriques)) {
			debut_page(_T('icone_admin_plugin'), "configuration", "plugin");
			echo _T('avis_non_acces_page');
			fin_page();
			exit;
		}
	}

	/*
	 * Transformation d'une date en texte (français) à partir d'un timestamp
	 */
	function date_en_texte_tstamp($tstamp) {
		$mois = array(
			'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
			'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
		$jours = array(
			'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
		$j1 = $jours[intval(date('N', $tstamp))-1];
		$j2 = date('j', $tstamp);
		$m = $mois[intval(date('n', $tstamp))-1];
		$a = date('Y', $tstamp);

		return sprintf("%s %s %s %s", $j1, $j2, $m, $a);
	}

	/*
	 * Transformation d'une date en texte (français) à partir d'un format YYYY-MM-JJ
	 */
	function date_en_texte ($date) {
		$ret = $date;
		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $matches))
			$ret = Rmll_Helper::date_en_texte_tstamp(mktime(12, 0, 0, intval($matches[2]), intval($matches[3]), intval($matches[1])));
		return $ret;
	}

	/*
	* Execution de controles au chargement de la page
	*/
	function faire_controles() {
	}

	/*
	* Nettoyer une donnée de $_{GET,POST,REQUEST,COOKIES} par exemple
	*/
	function nettoye_donnee($donnee) {
		return trim(strip_tags($donnee));
	}

	/*
	* Récupérer et nettoyer des valeurs de $_POST
	*/
	function inPost($value = null) {
		$ret = null;

		if ($value) {
			if (isset($_POST[$value]))
				return  Rmll_Helper::nettoye_donnee($_POST[$value]);
		}

		return null;
	}

	/*
	 * Rendu des raccourcis
	 */
	function menu_gestion() {
		debut_cadre_enfonce();
		icone_horizontale(_T('rmll:label_gestion_jour'),
				generer_url_ecrire("jour"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/jour.png');
		icone_horizontale(_T('rmll:label_gestion_horaire'),
				generer_url_ecrire("horaire"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/horaire.png');
		icone_horizontale(_T('rmll:label_gestion_langue'),
				generer_url_ecrire("langue"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/langue.png');
		icone_horizontale(_T('rmll:label_gestion_nature'),
				generer_url_ecrire("nature"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/nature.png');
		icone_horizontale(_T('rmll:label_gestion_niveau'),
				generer_url_ecrire("niveau"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/niveau.png');
		icone_horizontale(_T('rmll:label_gestion_salle'),
				generer_url_ecrire("salle"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/salle.png');
		fin_cadre_enfonce();
	}

	function menu_planning() {
		debut_cadre_enfonce();
		icone_horizontale(_T('rmll:label_planning_jours'),
				generer_url_ecrire("planjour"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/jour.png');
		icone_horizontale(_T('rmll:label_planning_salle'),
				generer_url_ecrire("plansalle"), null,
				_DIR_PLUGIN_RMLL.'/img_pack/salle.png');
		fin_cadre_enfonce();
	}

	/*
	 * Génération des attribues d'une balise html
	 */
	function attr ($prop) {
		if (!is_array($prop))
			return '';

		$p = array();
		foreach($prop as $k => $v)
			$p[] = sprintf("%s=\"%s\"", $k, $v);

		return ' '.trim(implode(" ", $p));
	}

	/*
	 * Boite de message d'erreurs
	 */
	function boite_erreurs($msg, $astext = false) {
		$m = '<div class="rmll-erreur">'. $msg .'</div>';

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Boite de message d'attention
	 */
	function boite_attention($msg, $astext = false) {
		$m = '<div class="rmll-attention">'. $msg .'</div>';

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Boite de message d'infos
	 */
	function boite_infos($msg, $astext = false) {
		$m = '<div class="rmll-infos">'. $msg .'</div>';

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Titre (gros)
	 */
	function titre_gros ($texte, $astext = false) {
		$m = '<h1 class="rmll">'. $texte .'</h1>';

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Titre (moyen)
	 */
	function titre_moyen ($texte, $astext = false) {
		$m = '<h2 class="rmll">'. $texte .'</h2>';

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (début)
	 */
	function formulaire_debut ($action, $prop = null, $astext = false) {
		$m = sprintf("<form action=\"%s\" method=\"post\"%s>", $action, Rmll_Helper::attr($prop));

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (label)
	 */
	function formulaire_label ($label, $prop = null, $astext = false) {
		$m = sprintf("<label%s>%s</label>", Rmll_Helper::attr($prop), $label);

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (texte)
	 */
	function formulaire_texte ($nom, $value='', $prop = null, $astext = false) {
		$m = sprintf("<input type=\"text\" name=\"%s\" id=\"%s\" value=\"%s\"%s />",
			$nom, $nom, $value, Rmll_Helper::attr($prop));

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (cache)
	 */
	function formulaire_cache ($nom, $value='', $astext = false) {
		$m = sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />", $nom, $value);

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (bouton soumettre)
	 */
	function formulaire_soumettre ($value, $prop = null, $astext = false) {
		$m = sprintf("<input type=\"submit\" value=\"%s\"%s />", $value, Rmll_Helper::attr($prop));

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (bouton image)
	 */
	function formulaire_image ($src, $value, $prop = null, $astext = false) {
		$m = sprintf("<input type=\"image\" src=\"%s/img_pack/%s\" value=\"%s\"%s/>",
			_DIR_PLUGIN_RMLL, $src, $value, Rmll_Helper::attr($prop));

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (sélection)
	 */
	function formulaire_selection ($nom, $values, $defaut, $prop = null, $astext = false) {
		$m = sprintf("<select name=\"%s\" id=\"%s\"%s />",
			$nom, $nom, Rmll_Helper::attr($prop));
		foreach($values as $k => $v) {
			$selected = '';
			if ($k == $defaut)
				$selected = ' selected="selected"';
			$m .= sprintf("<option%s value=\"%s\">%s</option>", $selected, $k, $v);
		}
		$m .= "</select>";

		if ($astext) return $m;
		echo $m;
	}

	/*
	 * Formulaire (fin)
	 */
	function formulaire_fin ($astext = false) {
		$m = '</form>';

		if ($astext) return $m;
		echo $m;
	}
}

class Rmll_Db {

	var $_table = '';
	var $_name = '';
	var $_error = '';

	function Rmll_Db ($table) {
		$this->_name = $table;
		$this->_table = 'spip_rmll_'.$table;
	}

	/*
	 * Préparer une valeur pour son insertion dans la base
	 */
	function esc($value) {
		if (is_string($value))
			$ret = _q($value);
		else
			$ret = $value;

		return $ret;
	}

	/*
	 * Faire une requête
	 */
	function query($req) {
		spip_log(sprintf("Rmll Plugin [SQL QUERY] : [%s]", $req));
		return spip_query ($req);
	}

	/*
	 * Récupérer des informations sur d'éventuelles erreurs
	 */
	function error() {
		$msg = array();
		if ($this->_error)
			$msg[] = '0000 : ' . $this->_error;
		if (spip_sql_errno() != 0)
			$msg[] = spip_sql_errno() . ' : ' . mysql_error();

		if (!empty($msg))
			return nl2br(implode("\n", $msg));

		return '';
	}

	/*
	 * Récupération d'un enregistrement
	 */
	function get_one($id) {
		$this->_error = null;

		$query = $this->query (sprintf("SELECT * FROM %s WHERE id_%s = %d", $this->_table, $this->_name, $id));
		if ($query !== false)
			return spip_fetch_array($query);

		return false;
	}

	/*
	 * Récupération d'un enregistrement en précisant le where
	 */
	function get_one_where($where) {
		$this->_error = null;

		$query = $this->query (sprintf("SELECT * FROM %s WHERE %s", $this->_table, $where));
		if ($query !== false)
			return spip_fetch_array($query);

		return false;
	}


	/*
	 * Récupération de plusieurs enregistrements
	 */
	function get_all($order = null) {
		$this->_error = null;

		$req = sprintf("SELECT * FROM %s", $this->_table, $this->_name);
		if ($order !== null) $req .= " ORDER BY ".$order;

		$query = $this->query ($req);
		if ($query !== false) {
			$res = array();
			while ($data = spip_fetch_array($query))
				$res[] = $data;
			return $res;
		}

		return false;
	}

	/*
	 * Insertion d'un enregistrement
	 */
	function insert($datas) {
		$this->_error = null;

		if (empty($datas)) {
			$this->_error = _T('rmll:error_db_insert_nodatas');
			return false;
		}

		$keys = implode(", ", array_keys($datas));
		$values = array();

		foreach(array_values($datas) as $v)
			$values[] = $this->esc($v);
		$values = implode(", ", $values);

		$this->query (sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->_table, $keys, $values));
		return (spip_insert_id() !== false);
	}

	/*
	 * Mise à jour d'un enregistrement
	 */
	function update($datas, $id = null) {
		$this->_error = null;

		if (empty($datas)) {
			$this->_error = _T('rmll:error_db_update_nodatas');
			return false;
		}

		$set = array();
		foreach($datas as $k => $v)
			$set[] = sprintf("%s = %s", $k, $this->esc($v));
		$set = implode(", ", $set);

		return $this->query (sprintf("UPDATE %s SET %s WHERE id_%s = %d", $this->_table, $set, $this->_name, $id));
	}

	/*
	 * Supression d'un enregistrement
	 */
	function delete($id) {
		$this->_error = null;
		return $this->query (sprintf("DELETE FROM %s WHERE id_%s = %d", $this->_table, $this->_name, $id));
	}
}

class Rmll_Conference extends Rmll_Db {

	function Rmll_Conference ($dayfilter = true) {
		$this->_name = 'conference';
		$this->_table = 'spip_rmll_'.$table;
        $this->_dayfilter = $dayfilter;
	}

	function get_trads($article_orig, $lang) {
		$ret = false;

		/* on choppe les articles */
		$req = sprintf("
			SELECT
				id_article, descriptif, titre, texte, lang
			FROM
				spip_articles
			WHERE
				id_trad = %s
			AND
				statut = 'publie'
			AND
				lang = %s
			", $this->esc($article_orig), $this->esc($lang));

		$query = $this->query ($req);
		if ($query !== false) {
			while ($data = spip_fetch_array($query)) {
				$ret = $data;
			}
		}

		return $ret;
	}

	function get_all_sub_sub($id_rub, $lang) {
		$ret = array();

		/* on choppe les articles */
		$req = sprintf("
			SELECT
				c.*, a.descriptif AS descriptif, a.texte AS texte, a.titre AS titre, j.date AS jour, h.minute AS minute, h.heure AS heure, l.nom AS langue, l.code AS drap, niv.code AS niveau, n.nom AS nature, n.code AS nature_code, s.nom AS salle
			FROM
				spip_rmll_conference c
			LEFT JOIN
				spip_articles AS a ON c.id_article = a.id_article
			LEFT JOIN
				spip_rmll_jour AS j ON c.id_jour = j.id_jour
			LEFT JOIN
				spip_rmll_horaire AS h ON c.id_horaire = h.id_horaire
			LEFT JOIN
				spip_rmll_langue AS l ON c.id_langue = l.id_langue
			LEFT JOIN
				spip_rmll_nature AS n ON c.id_nature = n.id_nature
			LEFT JOIN
				spip_rmll_niveau AS niv ON c.id_niveau = niv.id_niveau
			LEFT JOIN
				spip_rmll_salle AS s ON c.id_salle = s.id_salle
			WHERE
				c.id_jour > %d
			AND
				a.lang = 'fr'
			AND
				statut = 'publie'
			AND
				a.id_rubrique = %s
			ORDER BY
				j.date, h.heure, h.minute
			",
            $this->_dayfilter ? 0 : -1,
            $this->esc($id_rub));

		$query = $this->query ($req);
		if ($query !== false) {
			while ($data = spip_fetch_array($query)) {
				$r = array (
					'id' => $data['id_article'],
					'data' => $data,
                    'lang' => 'fr');
				if ($lang != 'fr') {
					$tr = $this->get_trads($data['id_article'], $lang);
					if ($tr) {
						$r['id'] = $tr['id_article'];
                        $r['data']['id_article'] = $tr['id_article'];
                        $r['lang'] = $tr['lang'];
						if (trim($tr['titre']) != '')
							$r['data']['titre'] = $tr['titre'];
						if (trim($tr['descriptif']) != '')
							$r['data']['descriptif'] = $tr['descriptif'];
                        if (trim($tr['texte']) != '')
                            $r['data']['texte'] = $tr['texte'];
					}
				}
				$ret[] = $r;
			}
		}

		/* on choppe les articles des sous rubriques */
		$req = sprintf("
			SELECT
				id_rubrique
			FROM
				spip_rubriques
			WHERE
				id_parent = %s", $this->esc($id_rub));

		$query = $this->query ($req);
		if ($query !== false) {
			while ($data = spip_fetch_array($query)) {
				$ret = array_merge($ret, $this->get_all_sub_sub($data['id_rubrique'], $lang));
			}
		}

		return $ret;
	}


	function get_all_sub($root_id, $lang) {

		/* on choppe le 1er niveau */
		$req = sprintf("
			SELECT
				id_rubrique, titre, descriptif, texte
			FROM
				spip_rubriques
			WHERE
				id_parent = %s
			ORDER BY
				titre", $this->esc($root_id));

		$query = $this->query ($req);
		if ($query !== false) {
			$res = array();
			while ($data = spip_fetch_array($query)) {
				$res[] = array (
					'id' => $data['id_rubrique'],
					'titre' => $data['titre'],
					'descriptif' => $data['descriptif'],
                    'texte' => $data['texte'],
					'articles' => $this->get_all_sub_sub($data['id_rubrique'], $lang));
			}
			return $res;
		}

		return false;
	}
}

?>