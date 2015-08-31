$j=jQuery.noConflict();
// Use jQuery via $j(...) wordpress practice
// This script simply adds the "are you sure" alert before firing off the mass-assign for page comments.
// 
$j(document).ready(function(){
	$j('#pcop_options_wrap a.toggle').click(function(e) {  
	    e.preventDefault();  		
	    thisHref    = $j(this).attr('href');  
	    if(confirm('Turn off comments on your saved pages?')) {  
	        window.location = thisHref;  
	    }
		});
});