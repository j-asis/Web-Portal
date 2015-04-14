<h1>Edit Comment</h1>

<?php if(!empty($comment->validation_errors['comment_id']['exists'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading"><?php echo $comment->validation_errors['comment_id']['exists']; ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php endif ?>

<?php if(!empty($comment->validation_errors['authenticate']['valid'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading"><?php echo $comment->validation_errors['authenticate']['valid']; ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php
return;
elseif ($check !== false): ?>
<div class="alert alert-success">
    <h4 class="alert-heading">Successfully Edited your comment!</h4>
        return to <a href="<?php echo url('thread/view', array('thread_id'=>$comment->thread_id)); ?>">thread</a><br />
</div>
<?php return; endif; ?>

<?php if (isset($comment_content->body)): ?>
<br /><br />
<form action="<?php readable_text(url('')); ?>" class="form-horizontal" method="post">
        <div class="control-group">
            <label class="control-label">New Comment: </label>
            <div class="controls">
                <textarea name="new_comment" placeholder="New Comment" class="input-block-level"><?php readable_text($comment_content->body); ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="check" value="true">
                <input type="submit" name="submit" value="Edit Comment" class="btn btn-success">
            </div>
        </div>
</form>
<?php endif; ?>