<?php
function guaven_dym_load_defaults()
{
    if (get_option("guaven_dym_installed") === false) {
        update_option("guaven_dym_installed", "1");
        update_option("guaven_dym_push_num", "1");
        update_option("guaven_dym_sugg_act", "yes");
        update_option("guaven_dym_corr_act", "yes");
        update_option("guaven_dym_sentence", 'Did you mean %s ?');
        update_option("guaven_dym_simil", '70');
        
    }
}



function guaven_dym_admin()
{
    add_submenu_page('options-general.php', 'Guaven DidYouMean Settings', 'Guaven DidYouMean', 'manage_options', __FILE__, 'guaven_dym_settings'); //add_menu_page('Guaven', 'Guaven', 5, __FILE__, 'guaven_fp_settings');
}


function guaven_dym_is_checked($par)
{
    if (isset($_POST["guaven_settings"])) {
        if (isset($_POST[$par]))
            $k = 'checked';
        else
            $k = '';
        update_option($par, $k);
    }
}

function guaven_dym_string_setting($par, $def)
{
    if (isset($_POST[$par])) {
        if (!empty($_POST[$par]))
            $k = $_POST[$par];
        else
            $k = $def;
        update_option($par, $k);
    }
}

function guaven_dym_enqueue()
{
   // wp_enqueue_script('jquery'); enable it if your theme doesn't include jquery
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_script('jquery-ui-datepicker');
    global $wp_scripts;
    wp_enqueue_style('jquery-ui', "//code.jquery.com/ui/".$wp_scripts->registered["jquery-ui-core"]->ver."/themes/smoothness/jquery-ui.css");
}
add_action('wp_enqueue_scripts', 'guaven_dym_enqueue');

function guaven_dym_incl_js()
{
?>
   <script>
  jQuery(function() {
 var guaven_dym_tags = [<?php
    
    
    
    $teqler             = explode("\n", get_option("guaven_dym_tags_list"));
    $teqler             = array_unique($teqler);
    $guaven_dym_js_echo = '';
    for ($i = 0; $i < count($teqler); $i++) {
        if ($i > 0)
            $guaven_dym_js_echo .= ',
';
        $guaven_dym_js_echo .= '"' . trim($teqler[$i]) . '"';
    }
    
    echo $guaven_dym_js_echo;
?>    ];
    jQuery('[name="s"]').autocomplete({
      source: guaven_dym_tags
    });
  });
  </script>
    <?php
}

if (get_option('guaven_dym_sugg_act') != '') {
    add_action('wp_footer', 'guaven_dym_incl_js');
}


function guaven_dym_typo_correcter()
{
    $typo   = explode("\n", get_option("guaven_dym_tags_list"));
    $retypo = '';
    $max    = 0;
    
    for ($i = 0; $i < count($typo); $i++) {
        similar_text(strtolower($_GET["s"]), strtolower(trim($typo[$i])), $percent);
        if ($percent > $max) {
            $max        = $percent;
            $k          = $i;
            $strlentypo = mb_strlen($typo[$i]);
        }
    }
    
    if ($max >= get_option("guaven_dym_simil") and $max<100)
        $retypo = trim($typo[$k]); //$query->set('s', $typo[$k]);
    
    return $retypo;
}


function guaven_dym_make_cache($type)
{
    
    
    if ($type == 'titlewords') {
        $gdym_allposts = get_posts(array(
            "numberposts" => -1,
            "post_status" => "publish"
        ));
        $allcon        = '';
        foreach ($gdym_allposts as $alpost) {
            $allcon .= ' ' . $alpost->post_title;
        }
        $gdym_reduce = array_count_values(explode(" ", strip_tags($allcon)));
        arsort($gdym_reduce);
        $gdym_newarr          = array_slice($gdym_reduce, 0, (int) $_GET["upcanum"]);
        $gdym_titlewords_list = '';
        foreach ($gdym_newarr as $nar => $val) {
            if (mb_strlen($nar) > 2)
                $gdym_titlewords_list .= $nar . '<br>';
        }
        $ret = $gdym_titlewords_list;
    }
    
    
    elseif ($type == 'contentwords') {
        $gdym_allposts = get_posts(array(
            "numberposts" => -1,
            "post_status" => "publish"
        ));
        $allcon        = '';
        foreach ($gdym_allposts as $alpost) {
            $allcon .= ' ' . $alpost->post_content;
        }
        $gdym_reduce = array_count_values(explode(" ", strip_tags($allcon)));
        arsort($gdym_reduce);
        $gdym_newarr            = array_slice($gdym_reduce, 0, (int) $_GET["upcanum"]);
        $gdym_contentwords_list = '';
        foreach ($gdym_newarr as $nar => $val) {
            if (mb_strlen($nar) > 2)
                $gdym_contentwords_list .= $nar . '<br>';
        }
        $ret = $gdym_contentwords_list;
    } elseif ($type == 'tags') {
        $tags                = get_tags(array(
            "order" => "DESC",
            "orderby" => "count",
            "number" => (int) $_GET["upcanum"]
        ));
        $guaven_dym_htmltags = '';
        foreach ($tags as $tag) {
            $guaven_dym_htmltags .= $tag->name . '<br>';
        }
        $ret = $guaven_dym_htmltags;
    }
    
    return '<p>Copy any of the words below or copy all list and paste them to above textfield. 
Then click to Save. You will get new, more relevant suggestion&correction list</p><h3 style="padding:4px;margin:0px">Top ' . $type . '</h3>' . $ret;
    
}



function guaven_dym_didyoumean($atts)
{
    global $wp_query;
    if ($wp_query->post_count <= 1) {
        $retypo = guaven_dym_typo_correcter();
        if ($retypo != '') {
            $message = get_option("guaven_dym_sentence");
            //printf($message, '<a href="' . home_url() . '?s=' . $retypo . '">' . $retypo . '</a>');
	    printf($message, '<a href="' . home_url() . '?s=' . $retypo . '&post_type=product">' . $retypo . '</a>');
            
        }
    }
    
}
add_shortcode('gdym_didyoumean', 'guaven_dym_didyoumean');
?>