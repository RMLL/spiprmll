/* Copyright (c) 2007 Makina Corpus, published under a modified BSD license.
 * Sylvain Beorchia - sylvain.beorchia@makina-corpus.net
 * 3.10.2007
 */

/**
 * Utility JS
 */

/**
* The ManageOL object
**/
var manageOL;

/*
*
**/
function init(extent) {
    manageOL = new ManageOL(extent);
}

/**
* Callback for search adress
**/
function addAddressToMap(response) {
    manageOL.addAddressToMap(response);
}

/**
* ZoomTo extent
**/
function zoomTo(extent) {
    manageOL.zoomTo(extent);
}

