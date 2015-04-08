<?php if(isset($error_message)): ?>
<div class="alert alert-danger">
    <h4>Error!</h4>
    <em><?php readable_text($error_message); ?></em>
</div>
<?php endif; ?>
<?php if(isset($change_success)): ?>
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
                <input type="password" name="cnew_password" placeholder="Confirm New Password" required>
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
