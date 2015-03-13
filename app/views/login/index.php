<?php if($login->hasError() || $login->error): ?>
<div class="alert alert-block">
    <h4 class="alert-heading">Error!</h4>
    <?php if($login->error): ?>
            <h4 class="alert-heading">Wrong Username or Password</h4>
    <?php endif ?>
    <?php if (!empty($login->validation_errors['password']['length'])): ?>    
    <div><em>Password</em> must be between
        <?php eh($login->validation['password']['length'][1]) ?> and
        <?php eh($login->validation['password']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($login->validation_errors['username']['length'])): ?>    
    <div><em>Username</em> must be between
        <?php eh($login->validation['username']['length'][1]) ?> and
        <?php eh($login->validation['username']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

</div>
<?php endif ?>
<?php $title='User Log In';?>
<h1><?php eh($message); ?></h1>
<form action='<?php eh(url('')) ?>' method='post'>
    <input type='text' placeholder='Username' name='username'>
    <input type='password' placeholder='Password' name='password'>
    <input type="hidden" name='call' value='true'>
    <input type="submit" value='login' class='btn btn-primary'> 
</form>
<p>Not yet a member? <a href="/register/index">Sign up</a> now!</p>