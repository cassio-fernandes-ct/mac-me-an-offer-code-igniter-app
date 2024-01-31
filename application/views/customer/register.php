<?php 
$form_attr = [
	'class' =>  'form-horizontal'
];

$email_attr = [
	'type' 	=> 'text',
	'name'  => 'email_id',
	'id'    => 'email_id',
	'placeholder' => 'Enter Email Id',
	'class' => 'form-control',
];
$password_attr = [
	'type'  => 'password',
	'name'  => 'password',
	'id'    => 'password',
	'placeholder' => 'Enter Password',
	'class' => 'form-control',
];
$confirm_password_attr = [
	'type'  => 'password',
	'name'  => 'cpassword',
	'id'    => 'cpassword',
	'placeholder' => 'Enter Confirm Password',
	'class' => 'form-control',
];
$country_attr = [
	'name'  => 'country',
	'id'    => 'country',
	'placeholder' => 'Enter Country Name',
	'class' => 'form-control',
];
$fname_attr = [
	'name'  => 'fname',
	'id'    => 'fname',
	'placeholder' => 'Enter First Name',
	'class' => 'form-control',
];
$lname_attr = [
	'name'  => 'lname',
	'id'    => 'lname',
	'placeholder' => 'Enter Last Name',
	'class' => 'form-control',
];

$address_1_attr = [
	'name'  => 'address_line_1',
	'id'    => 'address_line_1',
	'placeholder' => 'Enter Address Line 1',
	'class' => 'form-control',
];
$address_2_attr = [
	'name'  => 'address_line_2',
	'id'    => 'address_line_2',
	'placeholder' => 'Enter Address Line 2',
	'class' => 'form-control',
];
$company_attr = [
	'name'  => 'company',
	'id'    => 'company',
	'placeholder' => 'Enter Company Name',
	'class' => 'form-control',
];
$city_attr = [
	'name'  => 'city',
	'id'    => 'city',
	'placeholder' => 'Enter City Name',
	'class' => 'form-control',
];
$state_attr = [
	'name'  => 'state',
	'id'    => 'state',
	'placeholder' => 'Enter State Name',
	'class' => 'form-control',
];
$pincode_attr = [
	'name'  => 'pincode',
	'id'    => 'pincode',
	'placeholder' => 'Enter Pincode ',
	'class' => 'form-control',
];
$phone_attr = [
	'name'  => 'phone',
	'id'    => 'phone',
	'placeholder' => 'Enter Phone Number ',
	'class' => 'form-control',
];


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Offer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2></h2>
    <?php require_once APPPATH.'views/common/display_errors.php' ?>


  <?php echo form_open(base_url().'register/success',$form_attr); ?>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Email Address</label>
      <div class="col-sm-10">
       		<?php echo form_input($email_attr); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2" for="">Password</label>
      <div class="col-sm-10">
       		<?php echo form_input($password_attr); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2" for="">Confirm Password</label>
      <div class="col-sm-10">
       		<?php echo form_input($confirm_password_attr); ?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Country</label>
      <div class="col-sm-10">
       		<?php echo form_input($country_attr); ?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">First Name</label>
      <div class="col-sm-10">
       		<?php echo form_input($fname_attr); ?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Last Name</label>
      <div class="col-sm-10">
       		<?php echo form_input($lname_attr); ?>
       
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Address Line 1 </label>
      <div class="col-sm-10">
       
       		<?php echo form_input($address_1_attr); ?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Address Line 2</label>
      <div class="col-sm-10">
       		<?php echo form_input($address_2_attr); ?>
       
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Company Name</label>
      <div class="col-sm-10">
       
       		<?php echo form_input($company_attr); ?>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">City</label>
      <div class="col-sm-10">
       		<?php echo form_input($city_attr); ?>
       
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">State/Province</label>
      <div class="col-sm-10">
       		<?php echo form_input($state_attr); ?>
       
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Zip/Postcode</label>
      <div class="col-sm-10">
       		<?php echo form_input($pincode_attr); ?>
       
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="">Phone Number</label>
      <div class="col-sm-10">
       		<?php echo form_input($phone_attr); ?>
       
      </div>
    </div>
   
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>

 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>

<script  type="text/javascript">
$(document).ready(function(){

 $("form").validate({
    rules: {
      email_id: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 7
      },
      cpassword :  {
        required: true,
        equalTo: "#password"
      },
      fname: "required",
      lname: "required",
      address_line_1 : "required",
      city : "required",
      state : "required",
      pincode : "required",
      phone : "required",
    },
    messages: {

      email_id: {
        required: "You must enter a valid email.",
        email: "You must enter a valid email."
      },
      password: {
        required: "You must enter a password.",
        minlength: "Passwords must be at least 7 characters and contain both alphabetic and numeric characters."
      },
      cpassword : {
        required: "You must enter a password.",
        equalTo: "Your passwords do not match."
      },
      fname: "The 'First Name' field cannot be blank.",
      lname: "The 'Last Name' field cannot be blank.",
      address_line_1 : "The 'Address Line 1' field cannot be blank.",
      city : "The 'Suburb/City' field cannot be blank.",
      state : "The 'State/Province' field cannot be blank.",
      pincode : "The 'Zip/Postcode' field cannot be blank.",
      phone : "The 'Phone Number' field cannot be blank.",
    },
    onfocusout: function (element) {
        $(element).valid();
    },
    highlight: function (element) {
     $(element).parent().addClass('error')
    },
    unhighlight: function (element) {
     $(element).parent().removeClass('error')
    },
    submitHandler: function(form) {
      form.submit();
    }
  });



});

</script>

</body>
</html>
