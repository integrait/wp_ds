<?php
function guaven_dym_settings()
{
    if (isset($_POST['guaven_dym_nonce_f']) and wp_verify_nonce($_POST['guaven_dym_nonce_f'],'guaven_dym_nonce')) {
    guaven_dym_is_checked("guaven_dym_corr_act");
    guaven_dym_is_checked("guaven_dym_sugg_act");
    guaven_dym_string_setting("guaven_dym_push_num", '1');
    guaven_dym_string_setting("guaven_dym_tags_list", 'wordpress');
    guaven_dym_string_setting("guaven_dym_sentence", 'Did you mean %s');
    guaven_dym_string_setting("guaven_dym_simil", '70');
}
    
    
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div><h2>Guaven DidYouMean</h2>

<form action="" method="post">
<?php wp_nonce_field( 'guaven_dym_nonce','guaven_dym_nonce_f'); ?>
<style type="text/css">
    #box-table-a{font-family:"Lucida Sans Unicode", "Lucida Grande", Sans-Serif;font-size:12px;text-align:left;border-collapse:collapse;margin:20px;}
    #box-table-a th{font-size:13px;font-weight:normal;background:#b9c9fe;border-top:4px solid #aabcfe;border-bottom:1px solid #fff;color:#039;padding:8px;}
    #box-table-a td{background:#e8edff;border-bottom:1px solid #fff;color:#669;border-top:1px solid transparent;padding:8px;}
    #box-table-a tr:hover td{background:#d0dafd;color:#339;}
</style>
<table class="form-table" id="box-table-a">
<tbody>
<tr valign="top">
<td colspan="2" scope="row"  style="background-color:#B7C1E7; !important"><label for="home"  ><b>If any problem and you can not get the needed result, don't panic, just email us via support@guaven.com
</label></td>
</tr>
<tr valign="top">
<td scope="row"><label>Keyword list</label></td>
<td>

  

<p>Enter custom keywords which should appear in your search suggestions.(one suggestion per line)</p>

 <textarea name="guaven_dym_tags_list" id="guaven_dym_tags_list" class="large-text code" rows="10"><?php
    echo get_option("guaven_dym_tags_list");
?></textarea>

</td>
</tr>

<tr><td>Pick some relevant keywords</td><td>
    
    <p>You can find more relevant keywords from your website content:</p>

<p>Show me <input name="guaven_dym_tags_num" type="number" step="1" min="1" id="guaven_dym_tags_num" value="20" class="small-text"> 
most popular tags of my website - <a href="javascript://" id="atags">Generate and show</a></p>

<p>Show me <input name="guaven_dym_contentwords_num" type="number" step="1" min="1" id="guaven_dym_contentwords_num" value="20" class="small-text"> 
most popular keywords based on my posts' contents <a href="javascript://" id="acontents">Generate and show</a>,</p>

<p>Show me <input name="guaven_dym_titlewords_num" type="number" step="1" min="1" id="guaven_dym_titlewords_num" value="20" class="small-text"> 
most popular keywords based on my posts' titles (<a href="javascript://" id="atitles">Generate and show</a>).</p>
<script>
jQuery( "#atags" ).click(function() {
  window.open("?page=guaven_didyoumean/functions.php&upca=tags&upcanum="+jQuery("#guaven_dym_tags_num").val(),"_self")
});
jQuery( "#acontents" ).click(function() {
  window.open("?page=guaven_didyoumean/functions.php&upca=contentwords&upcanum="+jQuery("#guaven_dym_contentwords_num").val(),"_self")
});
jQuery( "#atitles" ).click(function() {
  window.open("?page=guaven_didyoumean/functions.php&upca=titlewords&upcanum="+jQuery("#guaven_dym_titlewords_num").val(),"_self")
});
</script>

    
<?php
    if (!empty($_GET["upca"]) and in_array($_GET["upca"], array(
        "tags",
        "contentwords",
        "titlewords"
    ))) {
        echo '<br>
<div style="background-color:#8C96BA;padding:20px;border:2px solid;border-radius:25px;width:80%;color:white;height:auto" id="keyword_space">' . guaven_dym_make_cache($_GET["upca"]) . '</div>';
    }
?>


</td>
</tr>
<tr valign="top">
<td scope="row"><label for="home">Which features <br>do you want to use?</label></td>
<td  scope="row">
<p>
<label>
        <input name="guaven_dym_corr_act" type="checkbox" value="1" class="tog" <?php
    if (get_option("guaven_dym_corr_act") != '') {
        echo 'checked="checked"';
    }
?>>
        Correction (DidYouMean) feature     </label>
</label>
</p>
<p>
<label>
        <input name="guaven_dym_sugg_act" type="checkbox" value="1" class="tog" <?php
    if (get_option("guaven_dym_sugg_act") != '') {
        echo 'checked="checked"';
    }
?>>
        Suggestion feature     </label>
</label>
</p>
<p>You can disable suggestion feature if you already use another suggestion plugin(f.e. plugin which gives AJAX suggestions)</p>


    </td>
</tr>



<tr valign="top">
<td scope="row"><label for="home">Testing the result</label></td>
<td  scope="row">
<p>
1. To test autocomplete feature make sure Suggestion feature above is checked, then go to front page and start typing any relevant keyword there, you will see autosuggestion box.
</p>
<p>
2. To test DidYouMean feature 
a)make sure Correction feature above is checked. <br>
b)go to search.php of your theme, and place  <code>&lt;?php do_shortcode('[gdym_didyoumean]');?></code> code there. Then save the file.<br>
c)Go to front page, type any relevant keyword with mistake(for example: type Wodpress instead of Wordpress), and press enter. You will see didyoumean message there.<br>
<b>Warning:</b> If you put the shortcode inside php code, not inside to html code, then don't use &lt;?php and ?>, just use <code>do_shortcode('[gdym_didyoumean]');</code> <br><br>
P.S. If you want to customize place and view of you didyoumean message, you can do some css tricks around of above shortcode. 
<br>F.e. You can use such code <code>&lt;div style="some css code here"> &lt;?php do_shortcode('[gdym_didyoumean]');?> &lt;/div></code>
</p>


    </td>
</tr>


<tr valign="top">
<td scope="row"><label for="home">Parameters</label></td>
<td  scope="row">
<p>
<label>
        Push DidYouMean message when post search count gives less than 
<input name="guaven_dym_push_num" type="number" step="1" min="1" id="guaven_dym_push_num" value="<?php
    echo get_option("guaven_dym_push_num");
?>" class="small-text">
        results.    </label>
</label>
</p>

<p>
<label>
        Minimum DidYouMean similarity should be 
<input name="guaven_dym_simil" type="number" step="1" min="1" id="guaven_dym_simil" value="<?php
    echo get_option("guaven_dym_simil");
?>" class="small-text">%.</label>
</label>
</p>


<p>
<textarea name="guaven_dym_sentence" id="guaven_dym_sentence" class="large-text code" rows="2"><?php
    echo get_option("guaven_dym_sentence");
?></textarea>
</p>



    </td>
</tr>

</tbody></table>


<p>
<input type="hidden" name="guaven_settings" value="1">
<input type="submit" class="button button-primary" value="Save changes">
</p>
</form>
</div>

<?php
}

//////////////////////////////////
?>