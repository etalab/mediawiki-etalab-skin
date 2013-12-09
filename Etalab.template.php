<?php

/**
 * Display a gravatar from email
 */
function gravatar($email, $size=32) {
    $gravatar_link = 'http://www.gravatar.com/avatar/' . md5($email) . '?s='.$size;
    return '<img src="' . $gravatar_link . '" />';
}

/**
 * Get an URL from a title
 */
function wikiUrl($target) {
    $title = Title::newFromText($target);
    return $title->getLinkUrl();
}

/**
 * Get a Home/Weckan URL
 */
function homeUrl($target, $lang='fr') {
    global $wgEtalabHomeUrl;
    return "$wgEtalabHomeUrl/$lang/$target";
}


/**
 * BaseTemplate class for ETALAB skin
 * @ingroup Skins
 */
class EtalabTemplate extends BaseTemplate {

    private $topics = null;

    private function getTopics($lang='fr') {
        if (!$this->topics) {
            global $wgEtalabHomeUrl, $wgArticlePath;
            $articlePrefix = str_replace('$1', '', $wgArticlePath);

            $this->topics = array();
            $json = file_get_contents(dirname(__FILE__).'/main_topics.json');
            $topics = json_decode($json, true);
            foreach ($topics as $topic) {
                $url = str_replace('{group}', "$wgEtalabHomeUrl/{lang}/group",  $topic['url']);
                $url = str_replace('{wiki}/', $articlePrefix,  $url);
                $this->topics[] = [
                    'title' => $topic['title'],
                    'url' => $url,
                ];
            }
        }

        $func = function($topic) use ($lang) {
            return [
                'title' => $topic['title'],
                'url' => str_replace('{lang}', $lang, $topic['url']),
            ];
        };
        return array_map($func, $this->topics);
    }

    /**
     * Outputs the entire contents of the page
     */
    public function execute() {
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();

        $this->html( 'headelement' );
        $this->render_top_nav();
        $this->render_sub_nav();
        ?>
        <section class="default">
            <div class="container">

                <?php if($this->data['sitenotice']) { ?>
                    <div id="siteNotice" class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php $this->html('sitenotice') ?>
                    </div>
                <?php } ?>


                <div class="row">
                    <div class="col-md-9 col-sm-9 smaller">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs">
                                    <?php foreach ( $this->data['content_navigation']['namespaces'] as $key => $tab ) { ?>
                                            <?php echo $this->makeListItem( $key, $tab ); ?>

                                    <?php } ?>
                                    <?php foreach ( $this->data['content_navigation']['views'] as $key => $tab ) {
                                        if ( isset( $tab['class'] ) ) {
                                            $tab['class'] .= ' pull-right';
                                        } else {
                                            $tab['class'] = 'pull-right';
                                        }
                                        echo $this->makeListItem( $key, $tab, ['class'=> 'pull-right'] );

                                    } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="page-header">
                            <h1 lang="fr">
                                <?php $this->html( 'title' ) ?>
                                <small><?php $this->html( 'tagline' ) ?></small>
                            </h1>
                        </div>
                        <?php $this->html( 'subtitle' ) ?>
                        <div class="row">
                            <div id="content" class="mw-body col-md-12" role="main">
                                <?php $this->html( 'bodytext' ) ?>
                            </div>
                        </div>
                        <!-- /bodyContent -->
                    </div>

                    <aside class="col-md-3 col-sm-3">
                        <?php $this->render_aside(); ?>
                    </aside>
                </div>

            </div>
        </section>

        <?php $this->render_footer(); ?>


        <!--[if lt IE 9]>
            <script src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('js/etalab-mediawiki-legacy.min.js') ) ?>"></script>
        <![endif]-->
        <!--[if gte IE 9]><!-->
            <script src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('js/etalab-mediawiki.min.js') ) ?>"></script>
        <!--<![endif]-->

        <?php $this->printTrail(); ?>
        </body>
        </html><?php

        wfRestoreWarnings();
    }

    private function render_top_nav() {
        global $wgEtalabHomeUrl;
        ?>
        <section class="header">
            <div class="container">
                <nav class="navbar navbar-default navbar-static-top" role="navigation">
                    <header class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse, .subnav-collapse, sidebg-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php echo $wgEtalabHomeUrl; ?>">Etalab2.fr</a>
                        <p class="navbar-text pull-right"><?php $this->msg( 'etalab-site-desc' ); ?></p>
                    </header>
                </nav>
            </div>
        </section>

        <section class="topmenu collapse navbar-collapse">
            <div class="container">
                <nav class="navbar navbar-default navbar-static-top" role="navigation">
                    <ul class="nav navbar-nav links">
                        <li>
                            <a href="http://wiki.etalab2.fr/wiki/FAQ"><?php $this->msg( 'faq' ); ?></a>
                        </li>
                        <li><a href="<?php echo homeUrl('organization', $this->data['userlang']); ?>"><?php $this->msg('publishers'); ?></a></li>
                        <li>
                            <a href="http://www.etalab.gouv.fr/pages/licence-ouverte-open-licence-5899923.html">
                                <?php $this->msg( 'open-license' ); ?>
                            </a>
                        </li>
                        <li><a href="<?php echo homeUrl('metrics', $this->data['userlang']); ?>"><?php $this->msg('metrics'); ?></a></li>
                        <li><a href="http://pfee.leaftr.com">Activiz</a></li>
                        <li><a href="http://www.etalab.gouv.fr/">Etalab</a></li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown user">
                            <button class="btn-link dropdown-toggle <?php if (!$this->data['loggedin']) { echo "tofix"; }?>" data-toggle="dropdown">
                                <?php if ($this->data['loggedin']) {
                                    echo gravatar($this->data['usermail'], 30) . ' ' . $this->data['username'];
                                } else {
                                    ?><?php $this->msg( 'sign-in-register' ); ?><?php
                                }
                                ?>
                            </button>
                            <ul class="dropdown-menu">
                                <?php if ($this->data['loggedin']) { ?>

                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['userpage']['href'] ?>" title="Profil">
                                        <span class="glyphicon glyphicon-user"></span>
                                        <?php $this->msg( 'profile' ); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['preferences']['href'] ?>"
                                        title="<?php echo $this->data['personal_urls']['preferences']['text'] ?>">
                                        <span class="glyphicon glyphicon-wrench"></span>
                                        <?php echo $this->data['personal_urls']['preferences']['text'] ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['mytalk']['href'] ?>"
                                        title="<?php echo $this->data['personal_urls']['mytalk']['text'] ?>">
                                        <span class="glyphicon glyphicon-comment"></span>
                                        <?php echo $this->data['personal_urls']['mytalk']['text'] ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['watchlist']['href'] ?>" title="Liste de suivi">
                                        <span class="glyphicon glyphicon-eye-open"></span>
                                        <?php echo $this->data['personal_urls']['watchlist']['text'] ?>
                                    </a>
                                </li>
                                <li role="presentation" class="divider"></li>
                                <li>
                                    <a href="<?php echo $this->data['personal_urls']['logout']['href'] ?>"
                                        title="<?php echo $this->data['personal_urls']['logout']['text'] ?>">
                                        <span class="glyphicon glyphicon-log-out"></span>
                                        <?php echo $this->data['personal_urls']['logout']['text'] ?>
                                    </a>
                                </li>

                                <?php } else { ?>

                                    <?php if ( $this->data['personal_urls']['login'] ) { ?>
                                    <!-- login -->
                                    <li>
                                        <a href="<?php echo $this->data['personal_urls']['login']['href'] ?>"
                                            title="<?php echo $this->data['personal_urls']['login']['text'] ?>">
                                            <span class="glyphicon glyphicon-user"></span>
                                            <?php echo $this->data['personal_urls']['login']['text'] ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php if ( $this->data['personal_urls']['anonlogin'] ) { ?>
                                    <!-- login -->
                                    <li>
                                        <a href="<?php echo $this->data['personal_urls']['anonlogin']['href'] ?>"
                                            title="<?php echo $this->data['personal_urls']['anonlogin']['text'] ?>">
                                            <span class="glyphicon glyphicon-user"></span>
                                            <?php echo $this->data['personal_urls']['anonlogin']['text'] ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <!-- register -->
                                    <?php if ( $this->data['personal_urls']['createaccount'] ) { ?>
                                    <li>
                                        <a href="<?php echo $this->data['personal_urls']['createaccount']['href'] ?>"
                                            title="<?php echo $this->data['personal_urls']['createaccount']['text'] ?>">
                                            <span class="glyphicon glyphicon-edit"></span>
                                            <?php echo $this->data['personal_urls']['createaccount']['text'] ?>
                                        </a>
                                    </li>
                                    <?php } ?>

                                <?php } ?>


                            </ul>
                        </li>
                        <li class="dropdown language">
                            <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/flags/'.strtolower($this->data['userlang']).'.png') ) ?>" />
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">
                                        <img src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/flags/fr.png') ) ?>" />
                                        Français
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/flags/en.png') ) ?>" />
                                        English
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>

        <?php
    }

    private function render_sub_nav() {
        global $wgEtalabHomeUrl;
        ?>
        <nav class="navbar navbar-static-top navbar-subnav" role="navigation">
            <div class="container">
                <div class="cover-marianne"></div>

                <div class="search_bar">
                    <form class="navbar-form" role="search"
                        action="<?php echo $wgEtalabHomeUrl . '/' . $this->data['userlang'] .'/search'; ?>">
                        <div class="form-group col-sm-4 col-md-4 col-lg-3 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn" type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <input id="search-input" name="q" type="search" class="form-control" autocomplete="off"
                                        placeholder="<?php $this->msg('search') ?>">
                            </div>
                        </div>
                        <div class="form-group col-sm-2 col-md-2 col-lg-3 col-xs-12 collapse subnav-collapse">
                            <div id="where-group" class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-globe"></span>
                                </span>
                                <input id="where-input" type="search" class="form-control" autocomplete="off"
                                        placeholder="<?php $this->msg('where') ?>">
                                <input id="ext_territory" name="ext_territory" type="hidden" />
                            </div>
                        </div>
                    </form>

                    <div class="form-group col-sm-2 col-md-2 col-lg-3 col-xs-12">
                        <button class="dropdown-toggle btn-block btn-light" data-toggle="dropdown">
                            <?php $this->msg('topics') ?>
                            <span class="glyphicon glyphicon-chevron-down pull-right hidden-sm"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="topics">
                            <?php foreach ($this->getTopics() as $topic) {
                                $title = $topic['title'];
                                $url = $topic['url'];
                            ?>
                            <li role="presentation">
                                <a role="menuitem" tabindex="-1" href="<?php echo $url; ?>" title="<?php echo $title; ?>">
                                    <?php echo $title; ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="col-sm-4 col-md-4 col-lg-3 col-xs-12 collapse subnav-collapse">
                        <a class="btn btn-primary btn-dark btn-block"
                                title="<?php $this->msg('publish-dataset') ?>"
                                href="<?php echo $wgEtalabHomeUrl . '/' . $this->data['userlang'] .'/dataset/new'; ?>">
                            <span class="glyphicon glyphicon-plus"></span>
                            <?php $this->msg('publish-dataset') ?>
                        </a>
                    </div>

                </div>
            </div>
        </nav>
        <?php
    }

    private function render_aside() { ?>
        <?php foreach ( $this->data['sidebar'] as $name => $content ) {
            if ( !$content ) {
                continue;
            }
            $msgObj = wfMessage( $name );
            $name = htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $name );
        ?>
            <!-- <?php echo htmlspecialchars( $name ); ?> -->
            <div class="card">
                <h3><?php echo htmlspecialchars( $name ); ?></h3>
            <!-- <div id="panel-<?php echo $name;?>" class="panel-body collapse in"> -->
                <ul class="list-unstyled">
                <?php
                    foreach( $content as $key => $val ) {
                        $navClasses = '';
                        if (array_key_exists('view', $this->data['content_navigation']['views']) && $this->data['content_navigation']['views']['view']['href'] == $val['href']) {
                            $navClasses = 'active';
                        }
                ?>

                    <li class="<?php echo $navClasses ?>"><?php echo $this->makeLink($key, $val); ?></li>
                <?php
                    }
            }?>
            </ul>
        </div>

        <!-- Toolbox -->
        <div class="card">
            <h3><?php $this->msg('toolbox') ?></h3>
        <!-- <div id="panel-toolbox" class="panel-body collapse in"> -->
            <ul class="list-unstyled">
            <?php
                    foreach ( $this->getToolbox() as $key => $tbitem ) { ?>
                            <?php echo $this->makeListItem( $key, $tbitem ); ?>

            <?php
                    }
                    wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) ); ?>
            </ul>
        </div>
        <?php
    }

    private function render_footer() { ?>
        <section class="footer">
            <div class="container">
                <footer class="row">

                    <section class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                        <h5><?php $this->msg( 'open-data' ); ?></h5>
                        <ul>
                            <li>
                                <a href="http://wiki.etalab2.fr/wiki/FAQ"><?php $this->msg( 'faq' ); ?></a>
                            </li>
                            <li><a href="<?php echo homeUrl('organization', $this->data['userlang']); ?>"><?php $this->msg('publishers'); ?></a></li>
                            <li>
                                <a href="http://www.etalab.gouv.fr/pages/licence-ouverte-open-licence-5899923.html">
                                    <?php $this->msg( 'open-license' ); ?>
                                </a>
                            </li>
                            <li><a href="http://www.etalab.gouv.fr/">ETALAB</a></li>
                            <li><a href="http://wiki.etalab2.fr/wiki/Cr%C3%A9dits"><?php $this->msg( 'credits' ); ?></a></li>
                        </ul>
                    </section>
                    <section class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                        <h5><?php $this->msg( 'topics' ); ?></h5>
                        <ul>
                            <?php foreach ($this->getTopics() as $topic) {
                                $title = $topic['title'];
                                $url = $topic['url'];
                            ?>
                            <li>
                                <a href="<?php echo $url; ?>" title="<?php echo $title; ?>">
                                    <?php echo $title; ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </section>

                    <section class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                        <h5><?php $this->msg( 'network' ); ?></h5>
                        <ul>
                            <li><a href="http://www.gouvernement.fr/">Gouvernement.fr </a></li>
                            <li><a href="http://www.france.fr/">France.fr</a></li>
                            <li><a href="http://www.legifrance.gouv.fr/">Legifrance.gouv.fr </a></li>
                            <li><a href="http://www.service-public.fr/">Service-public.fr</a></li>
                            <li><a href="http://opendatafrance.net/">Opendata France</a></li>
                        </ul>
                    </section>

                    <section class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                        <h5><?php $this->msg( 'contact' ); ?></h5>
                        <ul>
                            <li><a href="https://twitter.com/Etalab">Twitter</a></li>
                            <li><a href="mailto:info@data.gouv.fr">info@data.gouv.fr</a></li>
                        </ul>
                    </section>

                    <section class="col-xs-9 col-xs-offset-3 col-sm-offset-0 col-sm-2 col-md-2 col-lg-2">
                        <img class="logo" src="<?php echo htmlspecialchars( $this->getSkin()->getSkinStylePath('img/etalab-logo.png') ) ?>" />
                        <p>&copy; 2013 ETALAB, Inc.</p>
                    </section>

                    <p class="bottom-right"><a href="#"><?php $this->msg( 'back-to-top' ); ?></a></p>

                </footer>
            </div>
        </section>

    <?php }


}
