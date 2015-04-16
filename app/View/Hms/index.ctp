<!-- BEGIN LOGO -->
  <div class="logo">
    <img src="<?php echo $webroot_path ; ?>/as/hm/hm-logo.png" alt="logo" /> 
  </div>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form id="contact-form" method="post" class="form-vertical login-form"  />
    <fieldset>
      <h3 class="form-title">Login to HousingMatters</h3>
      <div style="color:red;"><?php echo @$wrong; ?></div><br>
      <div class="control-group">
	  	<div class="controls">
        	<div class="input-icon left"><i class="icon-user"></i>
			<input type="text"  class="m-wrap" name="username" style="font-size:16px;" value="<?php echo @$bgColor ; ?>"  placeholder="Your email ID or mobile*">	
             </div>
		</div>
	  </div>
       <div class="control-group">
	  	<div class="controls">
        	<div class="input-icon left"><i class="icon-lock"></i>
			<input type="password"   class="m-wrap" placeholder="Password*" style="font-size:16px;" value="<?php echo @$txtColor; ?>" name="password" >
           
             </div>
		</div>
	  </div>  
      <div class="form-actions">
       <label class="checkbox">
        <div class="checker" id="uniform-undefined"><span><input type="checkbox" <?php if(!empty($bgColor)){?> checked="checked" <?php } ?> name="rememberme" value="1" style="opacity: 0;"></span></div> Remember me
        </label>
			<button type="submit" name="login" class="btn green  pull-right" style="font-size:16px; width:45%">Login  <i class="m-icon-swapright m-icon-white"></i></button>
      </div>
      <div class="forget-password">
        <h4>Forgot your password ?</h4>
        <p>
          no worries, click <a href="<?php echo $webroot_path ; ?>hms/forget" class="" id="forget-password">here</a>
          to reset your password.
        </p>
      </div>
      <center>
      <a href="<?php echo $webroot_path ; ?>hms/sign_up" class="btn blue " style="font-size:16px;" >New User Sign up</a> </center>
      </fieldset>
    </form>
    <!-- END LOGIN FORM -->        
 </div>
  <!-- END LOGIN -->
  <!-- BEGIN COPYRIGHT -->
  <div class="copyright">
    HousingMatters.
  </div>
  <!-- END COPYRIGHT -->
 <script>
$(document).ready(function(){
		$('#contact-form').validate({
	    rules: {
	      username: {
	       
	        required: true
	      },
	      password: {
	        required: true,
	      },
	    },
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });
}); 
</script>