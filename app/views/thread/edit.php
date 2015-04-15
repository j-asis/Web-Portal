<h1>Edit Thread</h1>

<?php if(!empty($thread->validation_errors['thread_id']['exists'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Thread does not Exists!</h4>
        return to <a href="/">home page</a><br />
</div>

<?php elseif(!empty($thread->validation_errors['new_title']['length'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">
        <em>Comment </em>
        must be between 
        <?php echo $thread->validation['new_title']['length'][1]; ?>
        and 
        <?php echo $thread->validation['new_title']['length'][2]; ?>
        characters in length.
    </h4>
        return to <a href="/">home page</a><br />
</div>

<?php elseif(!empty($thread->validation_errors['current_user_id']['authenticate'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Cannot edit other user's thread</h4>
        return to <a href="/">home page</a><br />
</div>
<?php
return;
elseif ($check !== false): ?>
<div class="alert alert-success">
    <h4 class="alert-heading">Successfully Edited your Thread!</h4>
        return to <a href="<?php echo url('thread/view', array('thread_id'=>$thread->id)); ?>">thread</a><br />
</div>
<?php return; endif; ?>

<?php if (isset($thread->title)): ?>
<form action="<?php readable_text(url('')); ?>" class="form-horizontal" method="post">
        <div class="control-group">
            <label class="control-label">Thread Title: </label>
            <div class="controls">
                <textarea name="new_thread" class="input-block-level"><?php readable_text($thread->title); ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="check" value="true">
                <input type="submit" name="submit" value="Edit Thread" class="btn btn-success">
            </div>
        </div>
    </form>
<?php endif; ?>
