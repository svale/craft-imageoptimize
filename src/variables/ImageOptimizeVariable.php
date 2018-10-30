<?php
/**
 * Image Optimize plugin for Craft CMS 3.x
 *
 * Automatically optimize images after they've been transformed
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2017 nystudio107
 */

namespace nystudio107\imageoptimize\variables;

use nystudio107\imageoptimize\ImageOptimize;
use nystudio107\imageoptimize\models\OptimizedImage;

use craft\elements\Asset;
use craft\helpers\Template;

/**
 * @author    nystudio107
 * @package   ImageOptimize
 * @since     1.4.0
 */
class ImageOptimizeVariable extends ManifestVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Return an SVG box as a placeholder image
     *
     * @param             $width
     * @param             $height
     * @param string|null $color
     *
     * @return \Twig_Markup|null
     */
    public function placeholderBox($width, $height, $color = null)
    {
        return Template::raw(ImageOptimize::$plugin->placeholder->generatePlaceholderBox($width, $height, $color));
    }

    /**
     * @param Asset $asset
     * @param array $variants
     * @param bool  $generatePlaceholders
     *
     * @return OptimizedImage|null
     */
    public function createOptimizedImages(
        Asset $asset,
        $variants = null,
        $generatePlaceholders = false
    ) {
        // Override our settings for lengthy operations, since we're doing this via Twig
        ImageOptimize::$generatePlaceholders = $generatePlaceholders;

        return ImageOptimize::$plugin->optimizedImages->createOptimizedImages($asset, $variants);
    }

    /**
     * Returns whether `.webp` is a format supported by the server
     *
     * @return bool
     */
    public function serverSupportsWebP(): bool
    {
        $result = false;
        $variantCreators = ImageOptimize::$plugin->optimize->getActiveVariantCreators();
        foreach ($variantCreators as $variantCreator) {
            if ($variantCreator['creator'] === 'cwebp' && $variantCreator['installed']) {
                $result = true;
            }
        }

        return $result;
    }
}
