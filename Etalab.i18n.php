<?php
/**
 * Internationalization file for skin ETALAB.
 *
 * @file
 * @ingroup Skins
 */

$messages = array();

/*
 * Load translations from json files
 */
$files = glob(dirname(__FILE__).'/i18n/*.json');
foreach( $files as $file ) {
    $lang = substr( basename( $file ), 0, -5 );
    $data = (array)json_decode( file_get_contents( $file ) );
    unset( $data['@metadata'] );
    $messages[$lang] = $data ;
}
