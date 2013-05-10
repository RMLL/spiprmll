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

        $lang = null;
        $article_obj = new Rmll_Article();
        $article = $article_obj->get_one($id_article);
        if ($article) {
            $lang = $article['lang'];
        }
        if ($lang != 'fr') {
            return '';
        }

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
            $notes = $conf['notes'];
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
        for($i = 20; $i<=480; $i+=20) {
            $duree_liste[$i] = sprintf("%02dh%02d", intval($i/60), ($i%60));
        }
        for($i = 30; $i<=480; $i+=30) {
            $duree_liste[$i] = sprintf("%02dh%02d", intval($i/60), ($i%60));
        }
        foreach(array(5, 50) as $i) {
            $duree_liste[$i] = sprintf("%02dh%02d", intval($i/60), ($i%60));
        }
        ksort($duree_liste);
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

        $form = $info = '';
        if ($conf) {
            $info = '<table width="100%" class="rmll-evenement-show">
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
                    <tr>
                        <th>'._T('rmll:label_notes').' :</th>
                        <td colspan="3  ">'.nl2br($notes).'</td>
                    </tr>
                </table>
            ';
        }

        $form .=  Rmll_Helper::formulaire_cache('evenement', $evenement, true)
        .'<table class="rmll-evenement-edit">
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_jour').' :', array('for'=>'jour'), true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("jour", $jour_liste, $jour, null, true).'</td>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_langue').' :', array('for'=>'langue'), true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("langue", $langue_liste, $langue, null, true).'</td>
            </tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_horaire').' :', array('for'=>'horaire'), true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("horaire", $horaire_liste, $horaire, null, true).'</td>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_duree').' :', array('for'=>'duree'), true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("duree", $duree_liste, $duree, null, true).'</td>
            </tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_salle').' :', array('for'=>'salle'), true).'</th>
                <td colspan="3">'.Rmll_Helper::formulaire_selection("salle", $salle_liste, $salle, null, true).'</td>
            </tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_nature').' :', array('for'=>'nature'), true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("nature", $nature_liste, $nature, null, true).'</td>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_niveau').' :', array('for'=>'niveau'), true).'</th>
                <td>'.Rmll_Helper::formulaire_selection("niveau", $niveau_liste, $niveau, null, true).'</td>
            </tr>
            </tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_intervenants').' :', array('for'=>'intervenants'), true).'</th>
                <td colspan="3">'.Rmll_Helper::formulaire_texte("intervenants", $intervenants, array('size' => 40), true).'</td>
            </tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_videourl').' :', array('for'=>'video'), true).'</th>
                <td colspan="3">'.Rmll_Helper::formulaire_texte("video", $video, array('size' => 40), true).'</td>
            </tr>
            <tr>
                <th>'.Rmll_Helper::formulaire_label(_T('rmll:label_notes').' :', array('for'=>'notes'), true).'</th>
                <td colspan="3">'.Rmll_Helper::formulaire_zonetexte("notes", $notes, array('cols'=>40, 'rows'=>10), true).'</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;">
                '.
                    Rmll_Helper::formulaire_soumettre(_T('rmll:label_enregistrer'), array('name' => 'tosave'), true)
                .'
                </td>
            </tr>
        </table>
        ';

        $content = '';
        if (!$ajaxcall || ($ajaxcall && $info == '')) {
            $content = '
                <div style="text-align:right;">
                    <input type="submit" id="button_edition_evenement" onclick="jQuery(\'#edition_evenement\').toggle();jQuery(\'#button_edition_evenement\').toggle()" value="'.($info == '' ? _T('rmll:label_ajouter_prog') : _T('rmll:label_modifier')).'" />
                </div>';
        }

        $content .= '
            <div id="edition_evenement" class="'.(!$ajaxcall || ($ajaxcall && $info == '') ? 'blocreplie' : '' ).'">
                <br/>
                <div class="titrem">'._T('rmll:label_edition_prog').'</div>
            ';

        if ($info != '') {
            $suppr = '<div>';
            $suppr .= Rmll_Helper::formulaire_cache('suppr_evenement', $evenement, true);
            $suppr .= Rmll_Helper::formulaire_soumettre(_T('rmll:label_supprimer'), null, true);
            $suppr .= '</div>';

            $suppr_msg = htmlspecialchars(str_replace('\'', '\\\'', _T('rmll:suppression_warning')));
            $declencheur = ajax_action_declencheur('this', 'ajouter_conference-'.$id_article);
            $declencheur = str_replace(array('return ', '"'), '', $declencheur);
            $declencheur = "\"return (confirm('".$suppr_msg."') && ".$declencheur.');"';

            $content .= redirige_action_post('ajouter_conference', $id_article, 'ajouter_conference', 'script='.$script.'&id_article='.$id_article, $suppr, " onsubmit=".$declencheur);
        }

        $content .= ajax_action_auteur('ajouter_conference',$id_article, $script, "id_article=".$id_article, $form);
        $content .= '</div>';

        if (!$ajaxcall) {
            $ret = debut_cadre_enfonce(_DIR_PLUGIN_RMLL.'img_pack/armelle-24.png', true, '');
            $ret .= debut_block_depliable(true, 'ajouter_conference-'.$id_article);
            $ret .= $info;
            $ret .= $content;
            $ret .= fin_block();
            $ret .= fin_cadre_enfonce(true);
        }
        else {
            $ret = $info.$content;
        }

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
        $jours = array('Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.');
        $j1 = $jours[intval(date('w', $tstamp))];
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
    * Affiche de début de la page
    */
    function debut_page($titre) {
        $commencer_page = charger_fonction('commencer_page', 'inc');
        echo $commencer_page($titre);
    }

    /*
    * Affiche du début gauche de la page
    */
    function debut_gauche($data = '') {
        echo debut_gauche($data, true);
    }

    /*
    * Affiche du début droite de la page
    */
    function debut_droite($data = '') {
        echo debut_droite($data, true);
    }

    /*
    * Affiche de la fin de la page
    */
    function fin_page() {
        echo fin_page(true);
    }

    /*
    * Début du cadre au style 'enfoncé'
    */
    function debut_cadre_enfonce($data = '') {
        echo debut_cadre_enfonce('', true);
    }

    /*
    * Fin du cadre au style 'enfoncé'
    */
    function fin_cadre_enfonce() {
        echo fin_cadre_enfonce(true);
    }

    /*
    * Affichage d'une icone de mnu
    */
    function icone_horizontale($titre, $url, $icone) {
        echo icone_horizontale(
            $titre,
            generer_url_ecrire($url),
            _DIR_PLUGIN_RMLL.'/img_pack/'.$icone,
            '', false);
    }

    /*
    * Rendu des raccourcis
    */
    function menu_gestion() {
        Rmll_Helper::debut_cadre_enfonce();
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_gestion_jour'), 'jour', 'jour.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_gestion_horaire'), 'horaire', 'horaire.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_gestion_langue'), 'langue', 'langue.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_gestion_nature'), 'nature', 'nature.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_gestion_niveau'), 'niveau', 'niveau.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_gestion_salle'), 'salle', 'salle.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_import_conference'), 'conference', 'importconf.png');
        Rmll_Helper::fin_cadre_enfonce();
    }

    function menu_planning() {
        Rmll_Helper::debut_cadre_enfonce();
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_planning_jours'), 'planjour','jour.png');
        Rmll_Helper::icone_horizontale(
            _T('rmll:label_planning_salle'), 'plansalle','salle.png');
        Rmll_Helper::fin_cadre_enfonce();
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
    * Formulaire (zone texte)
    */
    function formulaire_zonetexte ($nom, $value='', $prop = null, $astext = false) {
        $m = sprintf("<textarea name=\"%s\" id=\"%s\"%s>%s</textarea>",
            $nom, $nom, Rmll_Helper::attr($prop), $value);

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (fichier)
    */
    function formulaire_fichier ($nom, $value='', $prop = null, $astext = false) {
        $m = sprintf("<input type=\"file\" name=\"%s\" id=\"%s\" value=\"%s\"%s />",
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
        $m = sprintf("<select name=\"%s\" id=\"%s\"%s>",
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
    * Récupérer le chemin vers un fichier uploadé
    */
    function inFile($value = null) {
        $ret = null;
        if ($value) {
            if (isset($_FILES[$value]) && $_FILES[$value]['error'] == 0 && file_exists($_FILES[$value]['tmp_name']))
                return  $_FILES[$value]['tmp_name'];
        }
        return null;
    }
}

class Rmll_Db {

    protected $_table = '';
    protected $_name = '';
    protected $_error = '';

    function __construct ($table) {
        $this->_name = $table;
        $this->_table = 'spip_rmll_'.$table.'s';
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
        return sql_query ($req);
    }

    /*
    * Récupération du denier ID (autoincrement)
    */
    function last_id() {
		$ret = false;
        $req = sprintf('SELECT LAST_INSERT_ID() AS id');
        $query = sql_query ($req);
        if ($query) {
            $data = sql_fetch($query);
			if ($data && isset($data['id'])) {
				$ret = $data['id'];
			}
        }
		return $ret;
    }

    /*
    * Récupérer des informations sur d'éventuelles erreurs
    */
    function error() {
        $msg = array();
        if ($this->_error)
            $msg[] = '0000 : ' . $this->_error;
        if (sql_errno() != 0)
            $msg[] = sql_errno() . ' : ' . sql_error();

        if (!empty($msg))
            return nl2br(implode("\n", $msg));

        return '';
    }

    /*
    * Récupération d'un enregistrement
    */
    function get_one($id) {
        $this->_error = null;

        $q = sprintf("SELECT * FROM %s WHERE id_%s = %d", $this->_table, $this->_name, $id);
        $query = $this->query ($q);
        if ($query !== false)
            return sql_fetch($query);

        return false;
    }

    /*
    * Récupération d'un enregistrement en précisant le where
    */
    function get_one_where($where) {
        $this->_error = null;

        $q = sprintf("SELECT * FROM %s WHERE %s", $this->_table, $where);
        $query = $this->query ($q);
        if ($query !== false)
            return sql_fetch($query);

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
            while ($data = sql_fetch($query))
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
        return sql_insertq($this->_table, $datas);
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

        $where = '';
        if ($id) {
            $where = sprintf('id_%s = %d', $this->_name, (int) $id);
        }

        return sql_updateq($this->_table, $datas, $where);
    }

    /*
    * Supression d'un enregistrement
    */
    function delete($id) {
        $this->_error = null;
        return sql_delete($this->_table, sprintf('id_%s = %d', $this->_name, $id));
    }
}

class Rmll_Article extends Rmll_Db {
    function __construct () {
        $this->_name = 'article';
        $this->_table = 'spip_'.$this->_name.'s';
    }
}

class Rmll_Conference extends Rmll_Db {

    function Rmll_Conference ($dayfilter = true) {
        $this->_name = 'conference';
        $this->_table = 'spip_rmll_'.$table;
        $this->_dayfilter = $dayfilter;
    }

/*
    function get_all_sub_sub($id_rub, $lang) {
        $ret = array();

        // on choppe les articles
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
            while ($data = sql_fetch($query)) {
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

        // on choppe les articles des sous rubriques
        $req = sprintf("
            SELECT
                id_rubrique
            FROM
                spip_rubriques
            WHERE
                id_parent = %s", $this->esc($id_rub));

        $query = $this->query ($req);
        if ($query !== false) {
            while ($data = sql_fetch($query)) {
                $ret = array_merge($ret, $this->get_all_sub_sub($data['id_rubrique'], $lang));
            }
        }

        return $ret;
    }


    function get_all_sub($root_id, $lang) {

        // on choppe le 1er niveau
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
            while ($data = sql_fetch($query)) {
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
*/

    function get_keywords($article) {
        $ret = array();
        $req = sprintf('
            SELECT
                spip_mots.id_mot AS id_mot,
                spip_groupes_mots.id_groupe AS id_groupe_mot,
                spip_mots.titre AS titre,
                spip_groupes_mots.titre AS titre_groupe
            FROM
                spip_mots
            JOIN
                spip_groupes_mots ON spip_groupes_mots.id_groupe = spip_mots.id_groupe
            LEFT JOIN
                spip_mots_articles ON spip_mots_articles.id_mot=spip_mots.id_mot
            WHERE
                spip_mots_articles.id_article = %d
            ', (int) $article);
        $query = $this->query ($req);
        if ($query !== false) {
            while ($data = sql_fetch($query)) {
                $ret[$data['id_mot']] = array(
                    'titre' => $data['titre'],
                    'id_groupe_mot' => $data['id_groupe_mot'],
                    'titre_groupe' => $data['titre_groupe'],
                );
            }
        }
        return $ret;
    }

    function get_trads($article_orig, $lang) {
        $ret = false;
        $req = sprintf('
            SELECT
                id_article, descriptif, titre, texte, lang
            FROM
                spip_articles
            WHERE
                id_trad = %d
            AND
                statut = \'publie\'
            AND
                lang = %s
            ', (int) $article_orig, $this->esc($lang));

        $query = $this->query ($req);
        if ($query !== false) {
            while ($data = sql_fetch($query)) {
                $ret = $data;
            }
        }
        return $ret;
    }

    function get_all_sousrubriques($rubrique) {
        $ret = array();
        $req = sprintf("
            SELECT
                id_rubrique
            FROM
                spip_rubriques
            WHERE
                id_parent = %d
            ", (int) $rubrique);
        $query = $this->query ($req);
        if ($query !== false) {
            while ($data = sql_fetch($query)) {
                $ret[] = $data['id_rubrique'];
                $ret += $this->get_all_sousrubriques($data['id_rubrique']);
            }
        }
        return $ret;
    }

    function get_all_confs($rubriques, $lang) {
        $ret = array();
        $req = sprintf('
            SELECT
                c.*,
                a.descriptif AS descriptif,
                a.texte AS texte,
                a.titre AS titre,
                j.date AS jour,
                h.minute AS minute,
                h.heure AS heure,
                l.nom AS langue,
                l.code AS drap,
                niv.code AS niveau,
                n.nom AS nature,
                n.code AS nature_code,
                s.nom AS salle
            FROM
                spip_rmll_conferences c
            LEFT JOIN
                spip_articles AS a ON c.id_article = a.id_article
            LEFT JOIN
                spip_rmll_jours AS j ON c.id_jour = j.id_jour
            LEFT JOIN
                spip_rmll_horaires AS h ON c.id_horaire = h.id_horaire
            LEFT JOIN
                spip_rmll_langues AS l ON c.id_langue = l.id_langue
            LEFT JOIN
                spip_rmll_natures AS n ON c.id_nature = n.id_nature
            LEFT JOIN
                spip_rmll_niveaus AS niv ON c.id_niveau = niv.id_niveau
            LEFT JOIN
                spip_rmll_salles AS s ON c.id_salle = s.id_salle
            WHERE
                c.id_jour > %d
            AND
                a.lang = \'fr\'
            AND
                a.statut = \'publie\'
            AND
                a.id_rubrique IN (%s)
            ORDER BY
                j.date, h.heure, h.minute
            ',
            $this->_dayfilter ? 0 : -1,
            implode(',', $rubriques));

        $query = $this->query ($req);
        if ($query !== false) {
            while ($data = sql_fetch($query)) {
                $data['id_orig'] = $data['id_article'];
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
                $r['keywords'] = $this->get_keywords($data['id_article']);
                $ret[] = $r;
            }
        }

        /*
        // on choppe les articles des sous rubriques
        $req = sprintf("
            SELECT
                id_rubrique
            FROM
                spip_rubriques
            WHERE
                id_parent = %s", $this->esc($id_rub));

        $query = $this->query ($req);
        if ($query !== false) {
            while ($data = sql_fetch($query)) {
                $ret = array_merge($ret, $this->get_all_sub_sub($data['id_rubrique'], $lang));
            }
        }
        */
        return $ret;
    }

    function get_confs ($sessions, $lang) {
        $ret = false;

        if (!is_array($sessions)) {
            $sessions = (array) $sessions;
        }

        if (!empty($sessions)) {
            $req = sprintf('
                SELECT
                    r1.id_rubrique AS id_rubrique,
                    r1.titre AS titre,
                    r1.descriptif AS descriptif,
                    r1.texte AS texte,
                    r1.id_parent AS id_parent,
                    r2.titre AS titre_parent
                FROM
                    spip_rubriques AS r1
                LEFT JOIN
                    spip_rubriques AS r2 ON r1.id_parent = r2.id_rubrique
                WHERE
                    r1.id_rubrique IN (%s)
                ORDER BY
                    r1.titre', implode(',', $sessions));
            $query = $this->query ($req);
            if ($query !== false) {
                $ret = array();
                while ($data = sql_fetch($query)) {
                    $rubriques = $this->get_all_sousrubriques($data['id_rubrique']);
                    $rubriques[] = $data['id_rubrique'];
                    $ret[] = array (
                        'id' => $data['id_rubrique'],
                        'titre' => $data['titre'],
                        'descriptif' => $data['descriptif'],
                        'texte' => $data['texte'],
                        'articles' => $this->get_all_confs($rubriques, $lang),
                        'id_parent' => $data['id_parent'],
                        'titre_parent' => $data['titre_parent'],
                    );
                }
            }
        }
        return $ret;
    }

	function import_theme($fichier, $rubrique, &$messages = array(), &$errors = array()) {

		$lang_data = array(
			'English' => 'en', 'French' => 'fr',
			'Anglais' => 'en', 'Français' => 'fr',
			'Néerlandais' => 'nl', 'Dutch' => 'nl',
		);
		$nature_data = array(
			'workshop' => 'atl',
			'conference' => 'conf',
		);

		if ((int) $rubrique > 0) {
			$ret = null;
			$fh = fopen($fichier, 'r');
			if ($fh !== false) {
				$i = 0;
				while (($data = fgetcsv($fh, 0, ';')) !== false) {
					$i++;
					if ($i == 1) continue;
					list($id, $created_date, $date, $status, $language, $topic, $title, $translated_title, $nature, $number_of_slots, $abstract, $translated_abstract, $slides_language, $license, $capture, $capture_license, $constraints, $for_general_public, $for_professionals, $for_decision_makers, $for_geeks, $fil_rouge_auquotidien, $fil_rouge_enjeuxsocietaux, $fil_rouge_opendata, $fil_rouge_cloud, $speakers, $biography, $translated_biography, $charges, $city, $country, $transportation, $cost, $notes) = $data;
					$abstract = str_replace("¬", "\n", $abstract);
					$translated_abstract = str_replace("¬", "\n", $translated_abstract);
					$constraints = str_replace("¬", "\n", $constraints);
					$lang_conf = array_key_exists($language, $lang_data) ? $lang_data[$language] : 'en';
					$nature_code = array_key_exists($nature, $nature_data) ? $nature_data[$nature] : 'conf';
					$speakersArr = explode("¬", $speakers);
					$bio = str_replace("¬", "\n", $biography);
					$translated_bio = str_replace("¬", "\n", $translated_biography);
					$notes = str_replace("¬", "\n", $notes);

					if ($status != 1) continue;

					if ($lang_conf == 'fr') {
					  $titre_fr = $title;
					  $texte_fr = sprintf("\n\n{{{Résumé}}}\n\n%s\n\n{{{Biographie}}}\n\n%s\n\n", $abstract, $bio);
					  $titre_en = $translated_title;
					  $texte_en = sprintf("\n\n{{{Abstract}}}\n\n%s\n\n{{{Biography}}}\n\n%s\n\n", $translated_abstract, $translated_bio);
					  $titre_nl = $titre_en;
					  $texte_nl = $texte_en;
					}
					else if ($lang_conf == 'nl') {
					  $titre_nl = $title;
					  $texte_nl = sprintf("\n\n{{{Abstract}}}\n\n%s\n\n{{{Biografie}}}\n\n%s\n\n", $abstract, $bio);
					  $titre_en = $translated_title;
					  $texte_en = sprintf("\n\n{{{Abstract}}}\n\n%s\n\n{{{Biography}}}\n\n%s\n\n", $translated_abstract, $translated_bio);
					  $titre_fr = $titre_en;
					  $texte_fr = $texte_en;
					}
					else { //lang_conf='en'
					  $titre_en = $title;
					  $texte_en = sprintf("\n\n{{{Abstract}}}\n\n%s\n\n{{{Biografie}}}\n\n%s\n\n", $abstract, $bio);
					  $titre_fr = $translated_title;
					  $texte_fr = sprintf("\n\n{{{Abstract}}}\n\n%s\n\n{{{Biography}}}\n\n%s\n\n", $translated_abstract, $translated_bio);
					  $titre_nl = $titre_en;
					  $texte_nl = $texte_en;
					}
					$notes = sprintf("CFP_ID=%d\nCFP_TOPIC=%s\nCFP_LICENSE=%s\n\n%s",
						$id, $topic, $license, $notes);

					$speakers = array();
					foreach($speakersArr as $speaker) {
						$speakers[] = preg_replace('#^(.*)\s+\[.*$#', '\1', $speaker);
					}

					// quelle langue ?
					$lang_db = new Rmll_Db('langue');
					$lang_rec = $lang_db->get_one_where(sprintf('code like %s', $lang_db->esc($lang_conf)));
					if ($lang_rec === false) {
						$errors[] = sprintf('Langue inconnue \'%s\' pour l\'enregistrement \'%d\'', $lang_conf, $id);
						continue;
					}
					// nature
					$nature_db = new Rmll_Db('nature');
					$nature_rec = $nature_db->get_one_where(sprintf('code like %s', $lang_db->esc($nature_code)));
					if ($lang_rec === false) {
						$errors[] = sprintf('Nature inconnue \'%s\' pour l\'enregistrement \'%d\' (mais insertion qd mm)', $nature_code, $id);
					}

					// déjà insérée ?
					$conf_db = new Rmll_Db('conference');
					$conf_rec = $conf_db->get_one_where(sprintf('notes like %s', $lang_db->esc(sprintf('%%CFP_ID=%d%%', $id))));
					if ($conf_rec === false) {

						$fields = array (
							'titre' => sprintf('<multi>%s [en] %s [nl] %s</multi>', $titre_fr, $titre_en, $titre_nl),
							'id_rubrique' => $rubrique,
							'texte' => sprintf("<multi>%s[en]%s[nl]%s</multi>", $texte_fr, $texte_en, $texte_nl),
							'date' => date('Y-m-d H:i:s'),
							'statut' => 'publie',
							'date_modif' => date('Y-m-d H:i:s'),
							'lang' => 'fr',
						);
						$article_db = new Rmll_Article();
						if ($article_db->insert($fields)) {
							$messages[] = sprintf('Insertion de l\'article lié à la conf \'%d\'', $id);
						}
						else {
							$errors[] = sprintf('Echec lors de l\'insertion de l\'article lié à la conf \'%d\' (%d, %s)', $id, sql_errno(), sql_error());
						}
						$fields = array(
							'id_langue' => $lang_rec['id_langue'],
							'id_article' => $article_db->last_id(),
							'notes' => $notes,
							'intervenants' => implode(', ', $speakers),
							'id_nature' => $nature_rec['id_nature'],
						);
						if ($conf_db->insert($fields)) {
							$messages[] = sprintf('Insertion de la conf \'%d\'', $id);
						}
						else {
							$errors[] = sprintf('Echec lors de l\'insertion de la conf (%d, %s)', $id, sql_errno(), sql_error());
						}
					}
					else {
						$messages[] = sprintf('Conf \'%d\' déjà importée', $id);
					}
					//break;
				}
				fclose($fh);
			}
		}
		else {
			$errors[] = sprintf('L\'Id de la rubrique semble invalide');
		}
		return $ret;
	}
}

?>
