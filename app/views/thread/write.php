<h2><?php readable_text($thread->title) ?></h2>

<?php if ($comment->hasError()): ?>

<div class="alert alert-block">
    <h4 class="alert-heading">Validation error!</h4>

    <?php if (!empty($comment->validation_errors['body']['length'])): ?>
    <div><em>Comment</em> must be
        between <?php readable_text($comment->validation['body']['length'][1]) ?> and
        <?php readable_text($comment->validation['body']['length'][2]) ?> characters in length.
    </div>            
    <?php endif ?>
</div>                    

<?php endif ?>

<form class="well" method="post" action="<?php readable_text(url('thread/write')) ?>">
    <label>Comment</label>
    <textarea name="body" style="padding:1%; width:98%; height:100px;"><?php readable_text(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php readable_text($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
