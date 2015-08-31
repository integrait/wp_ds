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

//Add body classes
$body_classes = 'no_js maintenance';
if( ( yit_get_option( 'responsive-enabled' ) && !$GLOBALS['is_IE'] ) || ( yit_get_option( 'responsive-enabled' ) && yit_ie_version() >= 9 ) ) {
    $body_classes .= ' responsive';
}

$body_classes .= ' ' . yit_get_option( 'layout-type' );
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7"  class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8"  class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9"  class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes() ?>>
<![endif]-->

<!-- This doesn't work but i prefer to leave it here... maybe in the future the MS will support it... i hope... -->
<!--[if IE 10]>
<html id="ie10"  class="ie"<?php language_attributes() ?>>
<![endif]-->


<!--[if !IE]>
<html <?php language_attributes() ?>>
<![endif]-->

<!-- START HEAD -->
<head>
    <?php do_action( 'yit_head' ) ?>
    <?php wp_head() ?>
    <?php $newsletter['submit']['border'] = yit_get_option('maintenance-newsletter-submit-border-color'); ?>
    <?php $social_text = yit_get_option('maintenance_social_text_shortcode'); ?>
    <?php $title = yit_get_option('maintenance-general-title'); ?>

    <style type="text/css">
    	body {
			background: <?php echo $background['color'] ?> <?php if($background['image']): ?>url('<?php echo $background['image'] ?>') <?php echo $background['repeat'] ?> <?php echo $background['position'] ?> <?php echo $background['attachment'] ?><?php endif ?>;
    	}

    	#maintenance_container {
    		margin: 0 auto;
    		margin-top: 172px;
    		width: <?php echo $container['width'] ?>px;
    		min-height: <?php echo $container['height'] ?>px;
            border: 1px solid #eceaea;
    		<?php if($container['color']): ?>background-color: <?php echo $container['color'] ?><?php endif ?>;
    	}

    	#maintenance_logo {
    		margin-top: 24px;
    		text-align: center;
	    	<?php if( $logo['color'] ): ?>background: <?php echo $logo['color'] ?><?php endif ?>
    	}

		#maintenance_message,
        #maintenance_social_text{
			padding: 16px 29px 4px 29px;
		}

        #maintenance_social_text{
            padding-bottom: 16px;
            padding-top: 21px;
            text-align: center;
        }

        #maintenance_message h2,
        #maintenance_social_text h2{
                text-align: center;
                font-size:24px;
                color: #a3a3a1;
                font-weight: 900;
            }

        #maintenance_message p,
        #maintenance_social_text p{
                line-height: 22px;
                margin-top: 30px;
            }

        #maintenance_social_text .socials-square,
        #maintenance_social_text .fade-socials{
            float: none;
            display: inline-block;
            margin: 0;
        }

		#maintenance_newsletter .newsletter-section {
            padding: 0px 14px 0px 22px;
            width: auto;
			margin: 0 auto;
		}
        #maintenance_newsletter .newsletter-section form{
            margin-bottom:0;
        }

        #maintenance_newsletter .newsletter-section ul{
            margin-bottom: 7px;
        }

		#maintenance_newsletter .newsletter-section li { float: left; }

		#maintenance_newsletter .newsletter-section input.text-field {
			width: 391px;
			background: transparent;
			background-repeat: no-repeat;
			background-position: 10px center;
			border: 1px solid #d1d1cf;

			-moz-box-shadow: inset 1px 0px 6px #e5e5e5;
			-webkit-box-shadow: inset 1px 0px 6px #e5e5e5;
			box-shadow: inset 1px 0px 6px #e5e5e5;

			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			border-radius: 3px;

            margin: 0;
            height:36px;
		}

		#maintenance_newsletter .newsletter-section label {
			top: 17px;
			left: 44px;
		}

		#maintenance_newsletter .newsletter-section input.submit-field {
			background: <?php echo $newsletter['submit']['color'] ?>;
            border: none;
            height: 34px;
            text-transform: uppercase;
            text-shadow: #000 0 0px 0px !important;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            box-shadow: 0px 2px <?php  echo $newsletter['submit']['border'] ?>;
            position: relative;
		}

        #maintenance_newsletter .newsletter-section.group input[type="text"] {
            color: #000000!important;
        }

		#maintenance_newsletter .newsletter-section input.submit-field:hover {
			background: <?php echo $newsletter['submit']['hover'] ?>;
		}

        #maintenance_newsletter .newsletter-section input.submit-field:active {
			top:2px;
		}

        #maintenance_container .submit-button .sendmail {
            margin-left: 10px;
            margin-top: 1px;
        }


		@media (min-width: 768px) and (max-width: 979px) {

		}

		@media (max-width: 767px) {
            #maintenance_container .submit-button .sendmail {
                margin-top: 10px;
            }
            #maintenance_container { width: 90%; margin-bottom: 20px; margin-top: 20px; }
			#maintenance_message { padding: 10px 12px }
			#maintenance_newsletter { margin-top: 15px; padding: 10px 12px; }
			#maintenance_newsletter .newsletter-section li { float: none; text-align: center; display: inline-block; width: 100%;  }
			#maintenance_newsletter .newsletter-section label { left: 158px; }
			#maintenance_newsletter .newsletter-section input.text-field { width: 85%; margin-left: 0; }
			#maintenance_newsletter .newsletter-section input.submit-field { float: none; display: inline-block; }
		}

		@media (max-width: 480px) {
            #maintenance_newsletter .newsletter-section {padding: 0px 14px 0px 0px;}
            #maintenance_newsletter .newsletter-section label { left: 124px; }
		}

		@media (max-width: 320px) {

            #maintenance_logo img {max-width: 85%;}
            #maintenance_message h2, #maintenance_social_text h2 {line-height: 25px;}
            #maintenance_newsletter .newsletter-section {padding: 0;}
			#maintenance_newsletter .newsletter-section li { margin: 0; padding: 0; margin-bottom: 10px; }
			#maintenance_newsletter .newsletter-section label { left: 52px; }
		}


    	<?php echo $custom ?>
    </style>
</head>
<!-- END HEAD -->
<!-- START BODY -->
<body <?php body_class( $body_classes ) ?>>
	<div id="maintenance_container" class="clearfix">

            <?php if( $logo['image'] ): ?>
            <div id="maintenance_logo">
                <img src="<?php echo $logo['image'] ?>" alt="<?php bloginfo() ?>" />
            </div>
            <?php endif ?>


            <div id="maintenance_message">
                <?php if($title) : ?>
                <h2><?php echo $title ?></h2>
                <?php endif ?>

                <?php if( $message ): ?>
                <p><?php echo $message ?></p>
                <?php endif ?>
            </div>


            <?php if( $newsletter['enabled'] ): ?>
            <div id="maintenance_newsletter">
                <?php echo do_shortcode('[newsletter_form submit="' . __( 'Get Notify', 'yit' ) .'"]'); ?>
            </div>
            <?php endif ?>

            <div id="maintenance_social_text">
                <?php echo do_shortcode($social_text) ?>
            </div>
	</div>

	<?php wp_footer() ?>
</body>
</html>