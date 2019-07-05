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
 * SkinTemplate class for Neverland skin
 * @ingroup Skins
 */
class SkinAether extends SkinTemplate {

    public $skinname = 'aether';
    public $stylename = 'Aether';
    public $template = 'AetherTemplate';

    private $neverlandConfig;

    public function __construct() {
        $this->aetherConfig = ConfigFactory::getDefaultInstance()->makeConfig( 'aether' );
    }

    /**
     * Initializes output page and sets up skin-specific parameters
     * @param $out OutputPage object to initialize
     */
    public function initPage( OutputPage $out ) {

        parent::initPage( $out );

        // Append CSS which includes IE only behavior fixes for hover support -
        // this is better than including this in a CSS fille since it doesn't
        // wait for the CSS file to load before fetching the HTC file.
        $min = $this->getRequest()->getFuzzyBool( 'debug' ) ? '' : '.min';
        $out->addModules( array('skins.aether') );
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, shrink-to-fit=no' );
    }

    /**
     * Load skin and user CSS files in the correct order
     * @param $out OutputPage object
     */
    function setupSkinUserCss( OutputPage $out ){
        $out->addStyle( 'https://cdn.kde.org/aether/css/bootstrap.min.css', 'screen' );
        $out->addStyle(  $this->stylename . '/resources/main.css', 'screen' );
    }

    /**
     * Override to pass our Config instance to it
     */
    public function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
        return new $classname( $this->aetherConfig );
    }
}
