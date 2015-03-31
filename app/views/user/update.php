<?php if ($user->hasError()): ?>

<div class="alert alert-block">
    <h4 class="alert-heading">Validation error!</h4>

    <?php if (!empty($user->validation_errors['new_username']['exists'])): ?>
    <div><em>Username Already Taken!</em></div>
    <?php endif ?>

    <?php if (!empty($user->validation_errors['new_first_name']['length'])): ?>
    <div><em>First Name</em> must be between
        <?php readable_text($user->validation['new_first_name']['length'][1]) ?> and
        <?php readable_text($user->validation['new_first_name']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($user->validation_errors['new_last_name']['length'])): ?>
    <div><em>Last Name</em> must be between
        <?php readable_text($user->validation['new_last_name']['length'][1]) ?> and
        <?php readable_text($user->validation['new_last_name']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($user->validation_errors['new_username']['length'])): ?>
    <div><em>Username</em> must be between
        <?php readable_text($user->validation['new_username']['length'][1]) ?> and
        <?php readable_text($user->validation['new_username']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($user->validation_errors['new_email']['length'])): ?>
    <div><em>Email</em> must be between
        <?php readable_text($user->validation['new_email']['length'][1]) ?> and
        <?php readable_text($user->validation['new_email']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>


</div>

<?php endif; ?>
<?php if (isset($update) && !isset($error) && !isset($db_error)): ?>
    <div class="alert alert-success">
        Successfully Updated profile! refresh to see changes
    </div>
<?php endif; ?>


Avatar : <br />
<a title='click to change' href='/user/upload_image'><img style='border:2px solid #DEDEDE; border-radius:6px; height:200px; width:200px;' src='<?php echo $user->user_details['avatar']; ?>' /></a>
<br />
<em style='font-size:10px; color:#333;'>Click Image to Edit</em>

<h3>User Details</h3>

<form action='<?php readable_text(url('')); ?>' method='post'>
    <label>Username :</label>
    <input type='text' name='username' value='<?php echo $user->user_details['username']; ?>' />
    <label>First Name :</label>
    <input type='text' name='first_name' value='<?php echo $user->user_details['first_name']; ?>' />
    <label>Last Name :</label>
    <input type='text' name='last_name' value='<?php echo $user->user_details['last_name']; ?>' />
    <label>Email :</label>
    <input type='text' name='email' value='<?php echo $user->user_details['email']; ?>' />
    <br />
    <input type='hidden' name='update' value='true'>
    <input class="btn btn-success" type='submit' value='update profile' >
</form>
