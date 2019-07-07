<?php
/**
 * Neverland - Modern version of MonoBook with fresh look and many usability
 * improvements.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */
 
 
<?php

if ( function_exists( 'wfLoadSkin' ) ) {
    wfLoadSkin( 'Aether' );
    /* wfWarn(
        'Deprecated PHP entry point used for Vector skin. Please use wfLoadSkin instead, ' .
        'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
    ); */
    return true;
} else {
    die( 'This version of the Neverland skin requires MediaWiki 1.25+' );
}
