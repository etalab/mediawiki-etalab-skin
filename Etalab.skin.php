<?php
/**
* Skin file for skin My Skin.
*
* @file
* @ingroup Skins
*/

/**
 * SkinTemplate class for My Skin skin
 * @ingroup Skins
 */
class SkinEtalab extends SkinTemplate {

        var $skinname = 'etalab',
            $stylename = 'etalab',
            $template = 'EtalabTemplate',
            $useHeadElement = true;

        /**
         * Initializes output page and sets up skin-specific parameters
         * @param $out OutputPage object to initialize
         */
        public function initPage( OutputPage $out ) {
            global $wgLocalStylePath,
                $wgEtalabDomain,
                $wgEtalabHomeUrl,
                $wgEtalabWikiUrl,
                $wgEtalabWikiAPIUrl,
                $wgEtalabQuestionsUrl;

            parent::initPage( $out );

            // Append CSS which includes IE only behavior fixes for hover support -
            // this is better than including this in a CSS fille since it doesn't
            // wait for the CSS file to load before fetching the HTC file.
            $min = $this->getRequest()->getFuzzyBool( 'debug' ) ? '' : '.min';

            $out->addHeadItem('style',
                '<link rel="stylesheet" media="screen" href="'.htmlspecialchars( $wgLocalStylePath )."/{$this->stylename}/css/etalab-mediawiki{$min}.css\">"
            );
            $out->addHeadItem( 'modernizr',
              '<!--[if lt IE 9]><script src="'.htmlspecialchars( $wgLocalStylePath )."/{$this->stylename}/js/modernizr.min.js\"></script><![endif]-->"
            );

            $out->addHeadItem('responsive', '<meta name="viewport" content="width=device-width, initial-scale=1.0">');
            // $out->addModuleScripts( 'skins.etalab' );

            // Reference to other site
            $out->addHeadItem('etalab-domain', '<meta name="domain" content="'.$wgEtalabDomain.'" />');
            $out->addHeadItem('etalab-home-url', '<link rel="home" href="'.$wgEtalabHomeUrl.'" />');
            $out->addHeadItem('etalab-wiki-url', '<link rel="wiki" href="'.$wgEtalabWikiUrl.'" />');
            $out->addHeadItem('etalab-wiki-api', '<link rel="wiki-api" href="'.$wgEtalabWikiAPIUrl.'" />');
            $out->addHeadItem('etalab-questions-url', '<link rel="questions" href="'.$wgEtalabQuestionsUrl.'" />');
        }

        /**
         * Overriden to add context variables
         */
        function setupTemplate($classname, $repository = false, $cache_dir = false) {
            $tpl = parent::setupTemplate($classname, $repository, $cache_dir);
            if ($this->getUser()) {
                $tpl->set('usermail', $this->getUser()->getEmail());
            }
            return $tpl;
        }

        public function doEditSectionLink( Title $nt, $section, $tooltip = null, $lang = false ) {
            $lang = wfGetLangObj( $lang );

            $attribs = array(
                'class' => 'btn btn-xs btn-default pull-right',
            );
            if ( !is_null( $tooltip ) ) {
                # Bug 25462: undo double-escaping.
                $tooltip = Sanitizer::decodeCharReferences( $tooltip );
                $attribs['title'] = wfMessage( 'editsectionhint' )->rawParams( $tooltip )
                    ->inLanguage( $lang )->text();
            }
            $link = Linker::link( $nt, wfMessage( 'editsection' )->inLanguage( $lang )->text(),
                $attribs,
                array( 'action' => 'edit', 'section' => $section ),
                array( 'noclasses', 'known' )
            );

            # Run the old hook.  This takes up half of the function . . . hopefully
            # we can rid of it someday.
            $attribs = '';
            if ( $tooltip ) {
                $attribs = wfMessage( 'editsectionhint' )->rawParams( $tooltip )
                    ->inLanguage( $lang )->escaped();
                $attribs = " title=\"$attribs\"";
            }
            $result = null;
            wfRunHooks( 'EditSectionLink', array( &$this, $nt, $section, $attribs, $link, &$result, $lang ) );
            if ( !is_null( $result ) ) {
                # For reverse compatibility, add the brackets *after* the hook is
                # run, and even add them to hook-provided text.  (This is the main
                # reason that the EditSectionLink hook is deprecated in favor of
                # DoEditSectionLink: it can't change the brackets or the span.)
                // $result = wfMessage( 'editsection-brackets' )->rawParams( $result )
                //     ->inLanguage( $lang )->escaped();
                return "<div class=\"btn-group editsection\">$result</div>";
            }

            # Add the brackets and the span, and *then* run the nice new hook, with
            # clean and non-redundant arguments.
            // $result = wfMessage( 'editsection-brackets' )->rawParams( $link )
            //     ->inLanguage( $lang )->escaped();
            // $result = "<span class=\"editsection\">$result</span>";
            $result = "<div class=\"btn-group editsection\">$link</div>";


            wfRunHooks( 'DoEditSectionLink', array( $this, $nt, $section, $tooltip, &$result, $lang ) );
            return $result;
            // $link = parent::doEditSectionLink($nt, $section, $tooltip, $lang);
            // return HTML::element('a', array(
            //     'class' => 'btn btn-xs btn-default pull-right',
            //     'title' => $tooltip,
            //     ), $nt);
            // // return HTML::rawElement('small', null, $link);
        }

}
