<?php

/*
 * Plugin OSM
 *
 */

include_spip('inc/presentation');

if (!function_exists('json_encode')) {
    function json_encode($a=false) {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a)) {
            if (is_float($a)) {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a)) {
                static $jsonReplaces = array(
                    array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"', '\''),
                    array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"', '\\\''));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else {
                return $a;
            }
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
            if (key($a) !== $i) {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList) {
            foreach ($a as $v) $result[] = json_encode($v);
            return '[' . join(',', $result) . ']';
        }
        else {
            foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
            return '{' . join(',', $result) . '}';
        }
    }
}

class Osm_Helper {

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
            _DIR_PLUGIN_OSM.'/img_pack/'.$icone,
            '', false);
    }

    /*
    * Rendu des raccourcis
    */
    function menu_gestion() {
        Osm_Helper::debut_cadre_enfonce();
        Osm_Helper::icone_horizontale(
            _T('osm:titre_menu_category'), 'osm_category', 'category.png');
        Osm_Helper::icone_horizontale(
            _T('osm:titre_menu_marker'), 'osm_marker', 'marker.png');
        Osm_Helper::fin_cadre_enfonce();
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
        $m = '<div class="osm-erreur">'. $msg .'</div>';

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Boite de message d'attention
    */
    function boite_attention($msg, $astext = false) {
        $m = '<div class="osm-attention">'. $msg .'</div>';

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Boite de message d'infos
    */
    function boite_infos($msg, $astext = false) {
        $m = '<div class="osm-infos">'. $msg .'</div>';

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Titre (gros)
    */
    function titre_gros ($texte, $astext = false) {
        $m = '<h1 class="osm">'. $texte .'</h1>';

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Titre (moyen)
    */
    function titre_moyen ($texte, $astext = false) {
        $m = '<h2 class="osm">'. $texte .'</h2>';

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (début)
    */
    function formulaire_debut ($action, $prop = null, $astext = false) {
        $m = sprintf("<form action=\"%s\" method=\"post\"%s>", $action, Osm_Helper::attr($prop));

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (label)
    */
    function formulaire_label ($label, $prop = null, $astext = false) {
        $m = sprintf("<label%s>%s</label>", Osm_Helper::attr($prop), $label);

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (texte)
    */
    function formulaire_texte ($nom, $value='', $prop = null, $astext = false) {
        $m = sprintf("<input type=\"text\" name=\"%s\" id=\"%s\" value=\"%s\"%s />",
            $nom, $nom, $value, Osm_Helper::attr($prop));

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (zone texte)
    */
    function formulaire_zonetexte ($nom, $value='', $prop = null, $astext = false) {
        $m = sprintf("<textarea name=\"%s\" id=\"%s\"%s>%s</textarea>",
            $nom, $nom, Osm_Helper::attr($prop), $value);

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (fichier)
    */
    function formulaire_fichier ($nom, $prop = null, $astext = false) {
        $m = sprintf("<input type=\"file\" name=\"%s\" id=\"%s\"%s />",
            $nom, $nom, $value, Osm_Helper::attr($prop));

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
        $m = sprintf("<input type=\"submit\" value=\"%s\"%s />", $value, Osm_Helper::attr($prop));

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (bouton image)
    */
    function formulaire_image ($src, $value, $prop = null, $astext = false) {
        $m = sprintf("<input type=\"image\" src=\"%s/img_pack/%s\" value=\"%s\"%s/>",
            _DIR_PLUGIN_OSM, $src, $value, Osm_Helper::attr($prop));

        if ($astext) return $m;
        echo $m;
    }

    /*
    * Formulaire (sélection)
    */
    function formulaire_selection ($nom, $values, $defaut, $prop = null, $astext = false) {
        $m = sprintf("<select name=\"%s\" id=\"%s\"%s>",
            $nom, $nom, Osm_Helper::attr($prop));
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
    function nettoye_donnee($donnee, $tags = '') {
        return trim(strip_tags($donnee, $tags));
    }

    /*
    * Récupérer et nettoyer des valeurs de $_POST (sauf tags)
    */
    function inPost($value, $tags = '') {
        $ret = null;

        if ($value) {
            if (isset($_POST[$value])) {
                $ret = Osm_Helper::nettoye_donnee($_POST[$value], $tags);
            }
        }

        return $ret;
    }

    /*
    * Récupérer un fichier
    */
    function inFiles($value, $path = '') {
        $ret = null;

        if ($value) {
            if (isset($_FILES[$value]) && $_FILES[$value]['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES[$value]['tmp_name'];
                $real_name = sprintf('%s_%s', uniqid(''), $_FILES[$value]['name']);
                $name = sprintf('%s%s/%s', _DIR_PLUGIN_OSM, $path, $real_name);
                if (move_uploaded_file($tmp_name, $name) !== false) {
                    $ret = $real_name;
                }
            }
        }
        return $ret;
    }

    /*
    * Récupérer les infos sur un fichier image
    */
    function imageInfos($path) {
        $ret = null;
        if (file_exists($path)) {
            $ret = getimagesize($path);
            $ret['width'] = $ret[0];
            $ret['height'] = $ret[1];
        }
        return $ret;
    }

    /*
    * Formulaire 'posté' ?
    */
    function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

class Osm_Db {

    var $_table = '';
    var $_name = '';
    var $_error = '';

    function Osm_Db ($table) {
        $this->_name = $table;
        $this->_table = 'spip_osm_'.$table.'s';
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
        spip_log(sprintf("Osm Plugin [SQL QUERY] : [%s]", $req));
        return sql_query ($req);
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
        if ($order !== null)
            $req .= " ORDER BY ".$order;

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
            $this->_error = _T('osm:error_db_insert_nodatas');
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
            $this->_error = _T('osm:error_db_update_nodatas');
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

class Osm_Marker extends Osm_Db {

    function Osm_Marker () {
        $this->_name = 'marker';
        $this->_table = 'spip_osm_'.$this->_name.'s';
    }

    function get_all_by_categories($categories) {
        $this->_error = null;
        if (!empty($categories)) {
            $other_table = str_replace('marker', 'categorie', $this->_table);
            $req = sprintf('
                SELECT
                    *, %s.nom AS nom, %s.nom AS nom_categorie
                FROM
                    %s
                LEFT JOIN
                    %s ON %s.id_categorie = %s.id_categorie
                WHERE
                    %s.id_categorie IN (%s)
                ORDER BY
                    %s.nom',
                $this->_table, $other_table,
                $this->_table,
                $other_table, $other_table, $this->_table,
                $other_table, implode(',', $categories),
                $other_table, $this->_table);
            $query = $this->query ($req);
            if ($query !== false) {
                $res = array();
                while ($data = sql_fetch($query))
                    $res[] = $data;
                return $res;
            }
        }

        return false;
    }

    /*
    * Récupération de plusieurs enregistrements
    */
    function get_all() {
        $this->_error = null;

        $other_table = str_replace('marker', 'categorie', $this->_table);
        $req = sprintf('
            SELECT
                *, %s.nom AS nom, %s.nom AS nom_categorie
            FROM
                %s
            LEFT JOIN
                %s ON %s.id_categorie = %s.id_categorie
            ORDER BY
                %s.id_categorie, %s.nom',
            $this->_table, $other_table,
            $this->_table,
            $other_table, $other_table, $this->_table,
            $other_table, $this->_table);
        $query = $this->query ($req);
        if ($query !== false) {
            $res = array();
            while ($data = sql_fetch($query))
                $res[] = $data;
            return $res;
        }

        return false;
    }

    function is_hidden_category($id) {
        $ret = false;
        if (isset($_GET['filter'])) {
            $ret = true;
            $args = explode(',', trim($_GET['filter']));
            $ret = !in_array($id, $args);
        }
        return $ret;
    }

    function get_pois() {
        $datas = $this->get_all();
        $markers = array();
        if (is_array($datas)) {
            foreach($datas as $data) {
                $categorie = extraire_multi(nettoyer_raccourcis_typo($data['nom_categorie']), $GLOBALS['lang']);
                $nom = extraire_multi(nettoyer_raccourcis_typo($data['nom']), $GLOBALS['lang']);
                $description = propre($data['description']);
                if (!array_key_exists($data['id_categorie'], $markers)) {
                    $markers[$data['id_categorie']] = array(
                        'name' => $categorie,
                        'hidden' => ($data['hidden'] == 1) || $this->is_hidden_category($data['id_categorie']),
                        'markers' => array(),
                    );
                }

                $accessibility = '';
                if ($data['accessible']) {
                    $accessibility = sprintf('
                        <div class="accessibility">
                            %s
                        </div>', _T('osm:is_accessible'));
                }

                $description = sprintf('
                    <div class="title">
                        <div class="cat">%s</div>
                        <div class="name">%s</div>
                    </div>
                    <div class="desc">%s</div>
                    %s', $categorie, $nom, $description, $accessibility);

                $markers[$data['id_categorie']]['markers'][] = array(
                    'name' => sprintf('<strong>%s :</strong> %s', $categorie, $nom),
                    'icon' => $data['icone'],
                    'width' => (int) $data['width'],
                    'height' => (int) $data['height'],
                    'description' => $description,
                    'longitude' => (double) $data['longitude'],
                    'latitude' => (double) $data['latitude'],
                );
            }
        }
        $ret = json_encode($markers);
        return $ret;
    }
}

?>