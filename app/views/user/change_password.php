<?php if($user->hasError()): ?>
    <div class="alert alert-danger">
    <?php if (!empty($user->validation_errors['new_password']['match'])): ?>
    <div><em>Password did not match!</em></div>
    <?php endif ?>

    <?php if (!empty($user->validation_errors['old_password']['correct'])): ?>
    <div><em>Wrong Password!</em></div>
    <?php endif ?>
    </div>
<?php elseif($check): ?>
<div class="alert alert-success">
    <h4>Success!</h4>
    <em>You have successfully changed your password.</em>
</div>
<?php return; ?>
<?php endif; ?>
<div>
    <h3>Change Password:</h3>
    <form action="<?php readable_text(url('')); ?>" class="form-horizontal" method="post">
        <div class="control-group">
            <label class="control-label" for="inputEmail">Old Password</label>
            <div class="controls">
                <input type="password" name="old_password" placeholder="Old Password" required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputEmail">New Password</label>
            <div class="controls">
                <input type="password" name="new_password" placeholder="New Password" required>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputEmail">Confirm New Password</label>
            <div class="controls">
                <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="check" value="true">
                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
            </div>
        </div>
    </form>
</div>
