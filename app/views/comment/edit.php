<h1>Edit Comment</h1>

<?php if(!empty($comment->validation_errors['comment_id']['exists'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Comment does not Exists!</h4>
        return to <a href="/">home page</a><br />
</div>

<?php elseif(!empty($comment->validation_errors['new_body']['length'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">
        <em>Comment </em>
        must be between 
        <?php echo $comment->validation['new_body']['length'][1]; ?>
        and 
        <?php echo $comment->validation['new_body']['length'][2]; ?>
        characters in length.
    </h4>
        return to <a href="/">home page</a><br />
</div>

<?php elseif(!empty($comment->validation_errors['current_user_id']['authenticate'])): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading">Cannot edit other user's comment</h4>
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

<?php if (isset($comment->body)): ?>
<br /><br />
<form action="<?php readable_text(url('')); ?>" class="form-horizontal" method="post">
        <div class="control-group">
            <label class="control-label">New Comment: </label>
            <div class="controls">
                <textarea name="new_comment" placeholder="New Comment" class="input-block-level"><?php readable_text($comment->body); ?></textarea>
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