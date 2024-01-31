<html>
  <head>
    <title>Google recapcha demo - Codeforgeek</title>
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
  </head>
  <body>
    <h1>Google reCAPTHA Demo</h1>
    <form id="comment_form" action="form.php" method="post">
      <input type="email" name="email" placeholder="Type your email" size="40"><br><br>
      <textarea name="comment" rows="8" cols="39"></textarea><br><br>
       <div class="g-recaptcha" data-sitekey="6Lea68UUAAAAAG-baa07yG7Q-nAZL8SiVo8IyGur"></div>
      <input type="submit" name="submit" value="Post comment"><br><br>
     
    </form>
  </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript" ></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script type="text/javascript">
  
  $("#comment_form").validate({
rules: {
    comment: {
        required: true,
        
    },
    email: {
        required: true,
    },
},
submitHandler: function(form) {
    console.log(grecaptcha.getResponse());
    if (grecaptcha.getResponse()) {
       // form.submit();
    } else {
        alert('Please confirm captcha to proceed')
    }
}
});
</script>
