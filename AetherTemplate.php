<?php
/**
 * Neverland - Modern version of MonoBook with fresh look and many usability
 * improvements.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */
 
/**
 * QuickTemplate class for Neverland skin
 * @ingroup Skins
 */
class AetherTemplate extends BaseTemplate {

    /* Functions */

    /**
     * Outputs the entire contents of the (X)HTML page
     */
    public function execute() {

        // Build additional attributes for navigation urls
        $nav = $this->data['content_navigation'];

        if ( $this->config->get( 'AetherUseIconWatch' ) ) {
            $mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() )
                            ? 'unwatch'
                            : 'watch';

            if ( isset( $nav['actions'][$mode] ) ) {
                $nav['views'][$mode] = $nav['actions'][$mode];
                $nav['views'][$mode]['class'] = rtrim( 'icon ' . $nav['views'][$mode]['class'], ' ' );
                $nav['views'][$mode]['primary'] = true;
                unset( $nav['actions'][$mode] );
            }
        }

        $xmlID = '';

        foreach ( $nav as $section => $links ) {
            foreach ( $links as $key => $link ) {
                if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
                    $link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
                }

                $xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
                $nav[$section][$key]['attributes'] = ' id="' . Sanitizer::escapeId( $xmlID ) . '"';

                if ( $link['class'] ) {
                    $nav[$section][$key]['attributes'] = $nav[$section][$key]['attributes'] .
                        ' class="' . htmlspecialchars( $link['class'] ) . '"';
                    $nav[$section][$key]['class'] = '';
                }

                if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
                    $nav[$section][$key]['key'] = Linker::tooltip( $xmlID );
                } else {
                    $nav[$section][$key]['key'] =
                                            Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
                }
            }
        }

        $this->data['namespace_urls'] = $nav['namespaces'];
        $this->data['view_urls'] = $nav['views'];
        $this->data['action_urls'] = $nav['actions'];
        $this->data['variant_urls'] = $nav['variants'];

        // Reverse horizontally rendered navigation elements
        if ( $this->data['rtl'] ) {
            $this->data['view_urls'] = array_reverse( $this->data['view_urls'] );
            $this->data['namespace_urls'] = array_reverse( $this->data['namespace_urls'] );
            $this->data['personal_urls'] = array_reverse( $this->data['personal_urls'] );
        }

    // Output HTML Page
        $this->html( 'headelement' );
    ?>

    <header id="KGlobalHeader mb-2" class="header clearfix">
        <nav id="kHeaderNav" class="navbar navbar-expand-md container" >
            <a class="navbar-brand active" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" id="KGlobalLogo">
                <?php echo wfMessage( 'sitetitle' )->escaped(); ?>
            </a>
            <div class="collapse navbar-collapse justify-content-between" id="kde-navbar">
                <ul class="navbar-nav">
                    <!-- top-navigation -->
                    <?php $this->renderNavigation( 'NAMESPACES' ); ?>
                    <!-- /top-navigation -->
                </ul>
                <?php $this->renderNavigation( 'SEARCH' ); ?>
            </div>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#kde-navbar" aria-controls="kde-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </header>
    <main id="top" class="container main">
        <!-- content -->
        <div class="row">
            <div id="mw-js-message" class="alert alert-info" style="display:none;"
                <?php $this->html( 'userlangattributes' ) ?>>
            </div>

            <?php if ( $this->data['sitenotice'] ): ?>
                <!-- sitenotice -->
                <div id="siteNotice">
                    <?php $this->html( 'sitenotice' ) ?>
                </div>
                <!-- /sitenotice -->
            <?php endif; ?>

            <!-- bodyContent -->
            <div id="bodyContent" class="col-12 col-lg-9">
                <!-- page-actions -->
                <?php $this->renderNavigation( array( 'VIEWS', 'ACTIONS' ) ); ?>
                <!-- /page-actions -->

                <!-- firstHeading -->
                <h1 id="firstHeading">
                    <?php $this->html( 'title' ) ?>
                </h1>
                <!-- /firstHeading -->

                <!-- subtitle -->
                <div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
                <!-- /subtitle -->

                <?php if ( $this->data['undelete'] ): ?>
                    <!-- undelete -->
                    <div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
                    <!-- /undelete -->
                <?php endif; ?>

                <?php if( $this->data['newtalk'] ): ?>
                    <!-- newtalk -->
                    <div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
                    <!-- /newtalk -->
                <?php endif; ?>

                <?php if ( $this->data['showjumplinks'] ): ?>
                    <!-- jumpto -->
                    <div id="jump-to-nav" class="mw-jump">
                        <?php $this->msg( 'jumpto' ) ?> <a href="#mw-head"><?php $this->msg( 'jumptonavigation' ) ?></a>,
                        <a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
                    </div>
                    <!-- /jumpto -->
                <?php endif; ?>

                <!-- bodycontent -->
                <?php $this->html( 'bodycontent' ) ?>
                <!-- /bodycontent -->

                <?php if ( $this->data['printfooter'] ): ?>
                    <!-- printfooter -->
                    <div class="printfooter">
                        <?php $this->html( 'printfooter' ); ?>
                    </div>
                <!-- /printfooter -->
                <?php endif; ?>

                <?php if ( $this->data['catlinks'] ): ?>
                    <!-- catlinks -->
                    <?php $this->html( 'catlinks' ); ?>
                    <!-- /catlinks -->
                <?php endif; ?>

                <?php if ( $this->data['dataAfterContent'] ): ?>
                    <!-- dataAfterContent -->
                    <?php $this->html( 'dataAfterContent' ); ?>
                    <!-- /dataAfterContent -->
                <?php endif; ?>

                <div class="visualClear"></div>

                <!-- debughtml -->
                <?php $this->html( 'debughtml' ); ?>
                <!-- /debughtml -->

                <!-- pagestats -->
                <?php
                    foreach( $this->getFooterLinks() as $category => $links ):
                        if ( $category == 'info' ):
                            ?>
                                <br />
                                <div class="page-info">
                                    <?php foreach( $links as $link ): ?>
                                        <?php $this->html( $link ) ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php
                        endif;
                    endforeach;
                ?>
                <!-- /pagestats -->
            </div>
            <!-- /bodyContent -->

            <!-- panel -->
            <div class="col-12 col-lg-3 mt-2 sidebar noprint" valign="top">
                <div class="card">
                    <!-- logo -->
                        <img src="<?php echo $GLOBALS['wgScriptPath']; ?>/skins/Neverland/images/sidebar-logo.png" alt="" />
                    <!-- /logo -->

                    <ul class="list-unstyled ml-2 mr-2">
                        <?php
                            $this->renderNavigation( 'VARIANTS' );
                            $this->renderPortals( $this->data['sidebar'] );
                            $this->renderNavigation( 'PERSONAL' );
                        ?>
                    </ul>
                </div>
            </div>
            <!-- /panel -->
        </div>

        <!-- /content -->

        <!-- footer -->
        <div id="footerRow">
            <div class="navbar navbar-bottom Neverland" <?php $this->html( 'userlangattributes' ) ?>>
                <div class="navbar-inner">
                    <div class="container">
                        <?php
                            foreach( $this->getFooterLinks() as $category => $links ):
                                if ( $category == 'places' ):
                                    ?>
                                        <ul id="footer-<?php echo $category ?>" class="nav">
                                            <?php foreach( $links as $link ): ?>
                                                <li>
                                                    <i class="icon-<?php echo $link ?> icon-white"></i>
                                                    <?php $this->html( $link ) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php
                                endif;
                            endforeach;
                        ?>

                        <ul class="nav pull-right">
                            <li id="global-nav-links" class="dropdown dropdown-hover">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-target="#global-nav-links">
                                    <i class="icon-list icon-white"></i>
                                    KDE Links
                                    <b class="caret-up"></b>
                                </a>

                                <ul id="global-nav" class="dropdown-menu bottom-up"></ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <footer class="Neverland">
                <?php
                    foreach( $this->getFooterLinks() as $category => $links ) {
                        if ( $category == 'legals' ) {
                            foreach( $links as $link ) {
                                $this->html( $link );
                            }
                        }
                    }
                ?>
            </footer>
        </div>
        <!-- /footer -->

        <?php $this->printTrail(); ?>
        <script type="text/javascript" src="https://cdn.kde.org/aether/js/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.kde.org/aether/js/popper.min.js"></script>
        <script type="text/javascript" src="https://cdn.kde.org/aether/js/bootstrap.min.js"></script>
    </body>
</html>

<?php
    }

    /**
     * Render a series of portals
     *
     * @param $portals array
     */
    private function renderPortals( $portals ) {
        // Force the rendering of the following portals
        if ( !isset( $portals['SEARCH'] ) ) {
            $portals['SEARCH'] = true;
        }
        if ( !isset( $portals['TOOLBOX'] ) ) {
            $portals['TOOLBOX'] = true;
        }
        if ( !isset( $portals['LANGUAGES'] ) ) {
            $portals['LANGUAGES'] = true;
        }
        // Render portals
        foreach ( $portals as $name => $content ) {
            if ( $content === false )
                continue;

            echo "\n<!-- {$name} -->\n";
            switch( $name ) {
                case 'SEARCH':
                    break;
                case 'TOOLBOX':
                    $this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
                    break;
                case 'LANGUAGES':
                    if ( $this->data['language_urls'] ) {
                        $this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
                    }
                    break;
                default:
                    $this->renderPortal( $name, $content );
                break;
            }
            echo "\n<!-- /{$name} -->\n";
        }
    }

    protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
        if ( $msg === null ) {
            $msg = $name;
        }

        ?>
            <li class="list-header" id='<?php echo Sanitizer::escapeId( "p-$name" ) ?>' <?php echo Linker::tooltip( 'p-' . $name ) ?>>
                <?php $msgObj = wfMessage( $msg ); echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg ); ?>
            </li>
        <?php
        if ( is_array( $content ) ) {
            foreach( $content as $key => $val ) {
                echo $this->makeListItem( $key, $val );
            }

            if ( $hook !== null ) {
                wfRunHooks( $hook, array( &$this, true ) );
            }
        } else {
            echo $content; /* Allow raw HTML block to be defined by extensions */
        }
    }

    /**
     * Render one or more navigations elements by name, automatically reveresed
     * when UI is in RTL mode
     *
     * @param $elements array
     */
    private function renderNavigation( $elements ) {
        global $wgNeverlandUseSimpleSearch;

        // If only one element was given, wrap it in an array, allowing more
        // flexible arguments
        if ( !is_array( $elements ) ) {
            $elements = array( $elements );
        // If there's a series of elements, reverse them when in RTL mode
        } elseif ( $this->data['rtl'] ) {
            $elements = array_reverse( $elements );
        }
        // Render elements
        foreach ( $elements as $name => $element ) {
            echo "\n<!-- {$name} -->\n";
            switch ( $element ) {
                case 'NAMESPACES':
                if ( count( $this->data['namespace_urls'] ) > 0 ) {
                    foreach ( $this->data['namespace_urls'] as $link ) {
                        if ( stripos( $link['attributes'], 'selected' ) === false ) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>>
                                <?php echo htmlspecialchars( $link['text'] ) ?>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="#" <?php echo $link['key'] ?>>
                                    <?php echo htmlspecialchars( $link['text'] ) ?>
                                </a>
                            </li>
                        <?php
                        }
                    }
                }
                break;

                case 'VARIANTS':
                if ( count( $this->data['variant_urls'] ) > 0 ) { ?>
                    <li class="list-header">
                        <?php $this->msg( 'variants' ) ?>
                    </li>

                    <?php foreach ( $this->data['variant_urls'] as $link ) { ?>
                        <li class="list-group-item" <?php echo $link['attributes'] ?>>
                            <a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>>
                                <?php echo htmlspecialchars( $link['text'] ) ?>
                            </a>
                        </li>
                    <?php }
                }
                break;

                case 'VIEWS':
                ?>
                    <div class="btn-group btn-group-sm float-right page-actions mt-2">
                <?php // Is closed later in the 'actions' section
                if ( count( $this->data['view_urls'] ) > 0 ) {
                    foreach ( $this->data['view_urls'] as $link ) { ?>
                        <a href="<?php echo htmlspecialchars( $link['href'] ) ?>" role="button" class="btn
                            <?php
                            echo (stripos( $link['attributes'], 'selected' ) !== false) ? 'btn-primary' : 'btn-secondary';
                            echo '"' . $link['key'] . '>';

                            if ( array_key_exists( 'text', $link ) ) { ?>
                                <i class="icon-<?php echo $link['id'] ?>
                                    <?php echo ( stripos( $link['attributes'], 'selected' ) === false ) ? 'icon-black' : 'icon-white'; ?>"></i>
                                <?php if ( strlen($link['text']) > 1 ) {
                                    echo htmlspecialchars( $link['text'] );
                                }
                            }
                        echo '</a>';
                    }
                }
                break;

                case 'ACTIONS':
                if ( count( $this->data['action_urls'] ) > 0 ) {
                    ?>
                        <button class="btn btn-secondary dropdown-toggle" role="button" data-toggle="dropdown" title="<?php $this->msg( 'actions' ) ?>"><?php $this->msg('actions'); ?></button>

                        <ul class="dropdown-menu">
                            <?php foreach ( $this->data['action_urls'] as $link ) { ?>
                                <a <?= $link['attributes'] ?> class="dropdown-item" href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>>
                                    <i class="icon-<?php echo $link['id'] ?> icon-black"></i>
                                    <?php echo htmlspecialchars( $link['text'] ) ?>
                                </a>
                            <?php } ?>
                        </ul>
                    <?php
                }
                ?>
                    </div> <!-- Opened in the 'views' section -->
                <?php
                break;

                case 'PERSONAL':
                if ( count( $this->data['personal_urls'] ) > 0 ) {
                    ?>
                        <li class="list-header">
                            <?php $this->msg( 'personaltools' ) ?>
                        </li>

                        <?php foreach( $this->getPersonalTools() as $key => $item ) { ?>
                            <?php echo $this->makeListItem( $key, $item ); ?>
                        <?php } ?>
                    <?php
                }
                break;

                case 'SEARCH':
                ?>
                    <form action="<?php $this->text( 'wgScript' ) ?>" id="searchform" class="form-inline mr-1">
                        <input id="searchInput" name="search" type="search" placeholder="<?php $this->msg( 'search' ) ?>"
                               class="form-control" autocomplete="off"
                        <?php if( isset( $this->data['search'] ) ): ?>
                            value="<?php $this->text( 'search' ) ?>"
                        <?php endif; ?> />

                        <input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
                    </form>
                <?php
                break;
            }
            echo "\n<!-- /{$name} -->\n";
        }
    }
}
