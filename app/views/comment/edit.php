<?php if (isset($error)): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading"><?php echo $error; ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php return; endif; ?>
<h1>Edit Comment</h1>

<?php if (isset($comment->error)): ?>
<div class="alert alert-block">
    <h4 class="alert-heading"><?php echo $comment->error; ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php elseif (isset($comment->auth_error)): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading"><?php echo $comment->auth_error; ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php return; elseif ($check !== false): ?>
<div class="alert alert-success">
    <h4 class="alert-heading">Successfully Edited your comment!</h4>
        return to <a href="<?php echo url('thread/view', array('thread_id'=>$comment->thread_id)); ?>">thread</a><br />
</div>
<?php return; endif; ?>

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