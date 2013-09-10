<?php
/**
 * ETALAB skin
 *
 * @file
 * @ingroup Skins
 * @author Axel Haustant (axel.haustant@etalab2.fr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if( !defined( 'MEDIAWIKI' ) ) die( "This is an extension to the MediaWiki package and cannot be run standalone." );

$wgExtensionCredits['skin'][] = array(
    'path' => __FILE__,
    'name' => 'ETALAB',
    'url' => "https://github.com/etalab/mediawiki-etalab-skin",
    'author' => 'Axel Haustant',
    'descriptionmsg' => 'etalab-desc',
);

$wgValidSkinNames['etalab'] = 'Etalab';
$wgAutoloadClasses['SkinEtalab'] = dirname(__FILE__).'/Etalab.skin.php';
$wgAutoloadClasses['EtalabTemplate'] = dirname(__FILE__).'/Etalab.template.php';
$wgExtensionMessagesFiles['Etalab'] = dirname(__FILE__).'/Etalab.i18n.php';

$wgResourceModules['skins.etalab'] = array(
    // 'styles' => array(
    //         'etalab/css/etalab.min.css' => array( 'media' => 'screen' ),
    // ),
    // 'scripts' => array(
    //         'etalab/js/modernizr.min.js',
    //         // 'strapping/strapping.js',
    // ),
    'remoteBasePath' => &$GLOBALS['wgStylePath'],
    'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);

# Default options to customize skin behavior
$wgEtalabDataUrl = 'http://data.gouv.fr';
