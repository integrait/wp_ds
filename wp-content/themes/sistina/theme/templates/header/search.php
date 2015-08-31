<?php if( yit_get_option('show-header-search') ): ?>
<?php

    $post_type = yit_get_option('search_type');
    if ( ! is_shop_installed() && $search_type == 'product' ) $search_type = 'post';
    
?>

<div id="headersearchform-container" class="group<?php if ( !yit_get_option('responsive-show-header-search') ) echo ' hidden-phone' ?>">
    <form role="search" method="get" id="headersearchform" action="<?php echo home_url( '/' ); ?>">
        <div class="group formborder">
            <input  type="text" value="" name="s" id="headers" placeholder="<?php $post_type == 'product' ? _e( 'Search by Brand Name, NAFDAC Number, Manufacturer or Composition', 'yit' ) : _e( 'Search here...', 'yit' ) ?>" />
            <input  type="submit" class="button" id="headersearchsubmit" value="<?php _e( 'Search', 'yit' ) ?>" />
            
            <div style="width:500;margin-left:auto;margin-right:auto;">
            
            </div>
            
        </div>
        <input type="hidden" name="post_type" value="<?php echo $post_type ?>" />
    </form>
</div>



<?php if( is_shop_installed() && 'product' === $post_type && yit_get_option('enable-ajax-search') ): ?>
<script type="text/javascript">
var search_loader_url = "<?php echo get_template_directory_uri() ?>/woocommerce/images/loading-search.gif";
jQuery(function($){
    $('#headers').autocomplete({
        //minChars: 3,
        appendTo: '#headersearchform-container .formborder',
        serviceUrl: woocommerce_params.ajax_url + '?action=yit_ajax_search_products',
        onSearchStart: function(){
            $(this).css('background', 'url('+search_loader_url+') no-repeat right center');
        },
        onSearchComplete: function(){
            $(this).css('background', 'transparent');
        },
        onSelect: function (suggestion) {
            if( suggestion.id != -1 ) {
                window.location.href = suggestion.url;
            }
        }
    });

})
</script>
<?php wp_enqueue_script('devbridge-jquery-autocomplete', YIT_THEME_ASSETS_URL . '/js/devbridge-jquery-autocomplete.js', array('jquery')); ?>
<?php endif ?>

<?php endif ?>