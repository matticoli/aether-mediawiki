<?php
/**
 * Aether

 * @todo document
 * @file
 * @ingroup Skins
 */

require_once __DIR__.'/vendor/autoload.php';
require_once 'JsonManifestNetworkStrategy.php';

use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
        $cache = new FilesystemAdapter();

        $cdn = 'https://cdn.kde.org';
        $cdnPathPrefix = 'aether-devel';

        $cdnManifest = $cdn . '/' . $cdnPathPrefix . '/version/manifest.json';

        $cdnCSSFiles = ['/version/bootstrap.css', '/version/aether-mediawiki.css', '/version/aether-sidebar.css'];
        $cdnJSFiles = ['/version/bootstrap.js'];

        // $cache->delete('cdnFiles' . str_replace('/', '', implode('', $cdnCSSFiles) . implode('', $cdnJSFiles)));
        ini_set('realpath_cache_size', 0);

        $cdnFiles = $cache->get('cdnFiles' . str_replace('/', '', implode('', $cdnCSSFiles) . implode('', $cdnJSFiles)), function (ItemInterface $item) use ($cdnManifest, $cdnPathPrefix, $cdnCSSFiles, $cdnJSFiles) {
            $item->expiresAfter(600);
            $fileContent = file_get_contents($cdnManifest."?e");
            $manifestData = json_decode($fileContent, true);

            $convertPaths = function($cdnCSSFile) use ($cdnPathPrefix, $manifestData)  {
                return $manifestData[$cdnPathPrefix . $cdnCSSFile];
            };
            return [
                'css' => array_map($convertPaths, $cdnCSSFiles),
                'js' => array_map($convertPaths, $cdnJSFiles),
            ];
        });

        foreach ($cdnFiles['css'] as $cssFile) {
            $out->addStyle($cdn . $cssFile, 'all');
        }
        foreach ($cdnFiles['js'] as $jsFile) {
            $out->addScriptFile($cdn . $jsFile);
        }
    }

    /**
     * Override to pass our Config instance to it
     */
    public function setupTemplate( $classname, $repository = false, $cache_dir = false ) {
        return new $classname( $this->aetherConfig );
    }
}
