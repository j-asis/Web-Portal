<?php if (isset($error)): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading"><?php readable_text($error); ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php return; endif; ?>
<h1>Edit Thread</h1>

<?php if (isset($thread->error)): ?>
<div class="alert alert-block">
    <h4 class="alert-heading"><?php readable_text($thread->error); ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php elseif (isset($thread->auth_error)): ?>
<div class="alert alert-danger">
    <h4 class="alert-heading"><?php readable_text($thread->auth_error); ?></h4>
        return to <a href="/">home page</a><br />
</div>
<?php return; elseif ($check !== false): ?>
<div class="alert alert-success">
    <h4 class="alert-heading">Successfully Edited your comment!</h4>
        return to <a href="<?php echo url('thread/view', array('thread_id'=>$thread->thread_id)); ?>">thread</a><br />
</div>
<?php return; endif; ?>

<span>Old Thread Title: <em>&quot;<?php readable_text($thread_content->title); ?>&quot;</em></span>
<br /><br />
<form action="<?php readable_text(url('')); ?>" class="form-horizontal" method="post">
        <div class="control-group">
            <label class="control-label">New Thread Title: </label>
            <div class="controls">
                <textarea name="new_thread" placeholder="New Thread Title" class="input-block-level"></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="check" value="true">
                <input type="submit" name="submit" value="Edit Thread" class="btn btn-success">
            </div>
        </div>
    </form>