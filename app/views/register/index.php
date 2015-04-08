<?php if(isset($register)): ?>
<?php if(isset($register->created) && !($register->hasError()) ): ?>
<h1>You Have Successfully Registered an Account! </h1>
<p>You can now go back to the <a href="/">log in</a> page</p>
<?php 
return;
endif;
?>
<?php endif; ?>


<?php if(isset($register)): ?>
<?php if($register->hasError()): ?>

<div class="alert alert-block">
    <h4 class="alert-heading">Validation error!</h4>

    <?php if (!empty($register->validation_errors['password']['match'])): ?>
    <div><em>Password Did Not Match!</em></div>
    <?php endif ?>
    <?php if (!empty($register->validation_errors['username']['exists'])): ?>
    <div><em>Username Already Taken!</em></div>
    <?php endif ?>
    <?php if (!empty($register->validation_errors['email']['exists'])): ?>
    <div><em>Email Already Registered!</em></div>
    <?php endif ?>
    
    <?php if (!empty($register->validation_errors['username']['valid'])): ?>
    <div><em>username may only consist of letter, number, and characters like _ and .</em></div>
    <?php endif ?>
    <?php if (!empty($register->validation_errors['first_name']['valid'])): ?>
    <div><em>first name may only consist of letters, space and a hyphen</em></div>
    <?php endif ?>
    <?php if (!empty($register->validation_errors['last_name']['valid'])): ?>
    <div><em>last name may only consist of letters, space and a hyphen</em></div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['password']['length'])): ?>
    <div><em>Password</em> must be between
        <?php $register->validation['password']['length'][1] ?> and
        <?php $register->validation['password']['length'][2] ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['first_name']['length'])): ?>
    <div><em>First Name</em> must be between
        <?php $register->validation['first_name']['length'][1] ?> and
        <?php $register->validation['first_name']['length'][2] ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['last_name']['length'])): ?>
    <div><em>Last Name</em> must be between
        <?php $register->validation['last_name']['length'][1] ?> and
        <?php $register->validation['last_name']['length'][2] ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['username']['length'])): ?>
    <div><em>Username</em> must be between
        <?php $register->validation['username']['length'][1] ?> and
        <?php $register->validation['username']['length'][2] ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($register->validation_errors['email']['length'])): ?>
    <div><em>Email</em> must be between
        <?php $register->validation['email']['length'][1] ?> and
        <?php $register->validation['email']['length'][2] ?> characters in length.
    </div>
    <?php endif ?>
<?php endif ?>


</div>

<?php endif ?>
<h2>Registration Form</h2>
<form class="form-horizontal" action="<?php readable_text(url('')) ?>" method="post">
    <div class="control-group">
        <label class="control-label">First Name: </label>
        <div class="controls">
            <input type='text' placeholder='First Name' name='first_name'>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Last Name: </label>
        <div class="controls">
            <input type='text' placeholder='Last Name' name='last_name'>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Username: </label>
        <div class="controls">
            <input type='text' placeholder='Username' name='username'>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Email: </label>
        <div class="controls">
            <input type='email' placeholder='Email' name='email'>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Password: </label>
        <div class="controls">
            <input type='password' placeholder='Password' name='password'>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Confirm Password: </label>
        <div class="controls">
            <input type='password' placeholder='Confirm Password' name='cpassword'>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="hidden" name='call' value='true'>
            <input type='submit' value='Create Account' class='btn btn-primary'> 
        </div>
    </div>
</form>
