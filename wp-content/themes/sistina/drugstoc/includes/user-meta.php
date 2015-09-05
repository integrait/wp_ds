<?php
  $p = get_user_meta($user->ID, 'phonenumber', true);
  $no = ($p != "") ? $p : 'Unavailable';
?>
<span id="revealnumber" data-reveal="<?php echo $no;?>" style="cursor: pointer;">
	<img style="width: 20px;" src="<?php echo home_url();?>/wp-content/themes/sistina/images/phone-512.png">View Phone Number
</span>
<script type="text/javascript">
  jQuery('#revealnumber').on('click', function (e) {
    var nb = jQuery(e.currentTarget).data('reveal');
    jQuery(this).html('<img style="width: 20px;" src="<?php echo home_url();?>/wp-content/themes/sistina/images/phone-512.png">' + nb);
  });
</script>