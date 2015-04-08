<?php if (isset($user->auth_error)): ?>
<div class="alert alert-danger">
<h3><?php echo $user->auth_error ?></h3>
</div>
<?php 
return;
endif; ?>
<?php if ($is_success): ?>
<div class="alert alert-success">
Successfully deleted <?php echo $type; ?>!
</div>
<?php elseif (isset($user->error_message)): ?>
<div class="alert alert-danger">
<?php echo $user->error_message; ?>
</div>
<?php else: ?>
<br />

<div class="alert alert-danger">
<?php if ($type === 'user'): ?>
    <h4>Are you sure? Please enter password for confirmation</h4>
    <?php if ($password !== ''): ?>
        <h6>Wrong Password</h6>
    <?php endif; ?>
    <br />
    <form action='<?php readable_text(url('')); ?>' method='post'>
    <input class="input-block-level" type="password" name='password' placeholder='Confirm Delete Account' />
    <input type="hidden" name='check' value='true' />
    <br />
    <input type="submit" value='confirm' class="btn btn-danger btn-block" >
    </form>

<?php else: ?>
<h4>Are you sure you want to delete this <?php echo $type; ?>?</h4>
<a href='<?php echo $_SERVER['REQUEST_URI'].'&confirm=true'; ?>'>yes</a> or <a href='<?php echo $url_back; ?>'>no</a>
<?php endif; ?>
<br /><br />
</div>
<?php endif; ?>
<em><a href='<?php echo $url_back; ?>'>go back</a></em>
