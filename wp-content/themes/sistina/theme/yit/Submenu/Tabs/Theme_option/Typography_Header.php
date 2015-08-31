<?php
/**
 * Your Inspiration Themes
 *
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Class to print fields in the tab Typography -> Header
 *
 * @since 1.0.0
 */
class YIT_Submenu_Tabs_Theme_option_Typography_Header extends YIT_Submenu_Tabs_Abstract {
    /**
     * Default fields
     *
     * @var array
     * @since 1.0.0
     */
    public $fields;

    /**
     * Merge default fields with theme specific fields using the filter yit_submenu_tabs_theme_option_typography_header
     *
     * @param array $fields
     * @since 1.0.0
     */
    public function __construct() {
        $fields = $this->init();
        $this->fields = apply_filters( strtolower( __CLASS__ ), $fields );
    }

    /**
     * Set default values
     *
     * @return array
     * @since 1.0.0
     */
    public function init() {
        return array(
            /* === LOGO FONT === */
            10 => array(
                'id'   => 'logo-font',
                'type' => 'typography',
                'name' => __( 'Logo font', 'yit' ),
                'desc' => __( 'Select the type to use for the logo font. ', 'yit' ),
                'min'  => 1,
                'max'  => 80,
                'std'  => apply_filters( 'yit_logo-font_std', array(
                    'size'   => 42,
                    'unit'   => 'px',
                    'family' => 'Nunito',
                    'style'  => 'regular',
                    'color'  => '#3a3a39'
                ) ),
                'style' => apply_filters( 'yit_logo-font_style', array(
                    'selectors' => '#header #logo #textual, span.logo',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                ) )
            ),

            20 => array(
                'id'   => 'logo-highlight-font',
                'type' => 'typography',
                'name' => __( 'Logo font highlight', 'yit' ),
                'desc' => __( 'Select the type to use for the logo font highlight.', 'yit' ),
                'min'  => 1,
                'max'  => 80,
                'std'  => apply_filters( 'yit_logo-highlight-font_std', array(
                    'size'   => 42,
                    'unit'   => 'px',
                    'family' => 'Nunito',
                    'style'  => 'regular',
                    'color'  => '#c17836'
                ) ),
                'style' => array(
                    'selectors' => '#header #logo #textual span',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            ),

            30 => array(
                'id'   => 'logo-tagline-font',
                'type' => 'typography',
                'name' => __( 'Tagline font', 'yit' ),
                'desc' => __( 'Select the type to use for the tagline below the logo.', 'yit' ),
                'min'  => 1,
                'max'  => 30,
                'std'  => apply_filters( 'yit_logo-tagline-font_std', array(
                    'size'   => 14,
                    'unit'   => 'px',
                    'family' => 'Open Sans',
                    'style'  => 'regular',
                    'color'  => '#3a3a39'
                ) ),
                'style' => array(
                    'selectors' => '#header #logo #tagline',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            ),

            40 => array(
                'id'   => 'logo-tagline-highlight-font',
                'type' => 'typography',
                'name' => __( 'Tagline font highlight', 'yit' ),
                'desc' => __( 'Select the type to use for the tagline highlight.', 'yit' ),
                'min'  => 1,
                'max'  => 30,
                'std'  => apply_filters( 'yit_logo-tagline-highlight-font_std', array(
                    'size'   => 14,
                    'unit'   => 'px',
                    'family' => 'Open Sans',
                    'style'  => 'regular',
                    'color'  => '#c17836'
                ) ),
                'style' => array(
                    'selectors' => '#header #logo #tagline span',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            ),
            /* == END LOGO FONT === */
        );
    }
}