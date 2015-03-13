
<?php if(isset($register->created)): ?>
<h1>You Have Successfully Logged In! </h1>
<p>You can now go back to the <a href="/">log in</a> page</p>
<?php 
return;
endif;
//Exit and return since we do not need the form
?>

<?php if($register->hasError()): ?>

<div class="alert alert-block">
    <h4 class="alert-heading">Validation error!</h4>

    <?php if (!empty($register->validation_errors['password']['match'])): ?>    
    <div><em>Password Did Not Match!</em></div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['password']['length'])): ?>    
    <div><em>Password</em> must be between
        <?php eh($register->validation['password']['length'][1]) ?> and
        <?php eh($register->validation['password']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['first_name']['length'])): ?>    
    <div><em>First Name</em> must be between
        <?php eh($register->validation['first_name']['length'][1]) ?> and
        <?php eh($register->validation['first_name']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['last_name']['length'])): ?>    
    <div><em>Last Name</em> must be between
        <?php eh($register->validation['last_name']['length'][1]) ?> and
        <?php eh($register->validation['last_name']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['username']['length'])): ?>    
    <div><em>Username</em> must be between
        <?php eh($register->validation['username']['length'][1]) ?> and
        <?php eh($register->validation['username']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['email']['length'])): ?>    
    <div><em>Email</em> must be between
        <?php eh($register->validation['email']['length'][1]) ?> and
        <?php eh($register->validation['email']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>


</div>

<?php endif ?>

<form action='<?php eh(url('')) ?>' method='post'>
    <input class="span2" type='text' placeholder='First Name' name='first_name'>
    <input class="span2" type='text' placeholder='Last Name' name='last_name'>
    <input class="span2" class="span2" type='text' placeholder='Username' name='username'>
    <input class="span2" type='email' placeholder='Email' name='email'>
    <input class="span2" type='password' placeholder='Password' name='password'>
    <input class="span2" type='password' placeholder='Confirm Password' name='cpassword'>
    <input type="hidden" name='call' value='true'>
    <input type='submit' value='Create Account' class='btn btn-primary'>
</form>