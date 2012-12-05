<?php

/*
* Plugin Osm
*
*/

include_spip('inc/osm.class');

class PoiArticle {
    var $categories = array();

    function PoiArticle($categories) {
        $this->categories = $categories;
    }

    function display() {
        if (!empty($this->categories)) {
            $markerObj = new Osm_Marker();
            $pois = $markerObj->get_all_by_categories($this->categories);
            if ($pois && !empty($pois)) {
                foreach($pois as $poi) {
                    echo '
                        <div class="osm-poi">
                            <h3>
                                <img src="/plugins/osm/markers/'.$poi['icone'].'" alt="" />
                                '.extraire_multi(nettoyer_raccourcis_typo($poi['nom']), $GLOBALS['lang']).'
                            </h3>
                            <div class="desc">
                                '.propre($poi['description']).'
                            </div>
                        </div>
                    ';
                }
            }
        }
    }
}
?>