<h1>Create a thread</h1>
                
<?php if ($thread->hasError() || $comment->hasError()): ?>
<div class="alert alert-block">

    <h4 class="alert-heading">Validation error!</h4>
    <?php if (!empty($thread->validation_errors['title']['length'])): ?>    
    <div><em>Title</em> must be between
        <?php readable_text($thread->validation['title']['length'][1]) ?> and
        <?php readable_text($thread->validation['title']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>

    <?php if (!empty($comment->validation_errors['body']['length'])): ?>
    <div><em>Comment</em> must be between
        <?php readable_text($comment->validation['body']['length'][1]) ?> and
        <?php readable_text($comment->validation['body']['length'][2]) ?> characters in length.
    </div>
    <?php endif ?>
</div>

<?php endif ?>

<form class="well" method="post" action="<?php readable_text(url('')) ?>">
    <label>Title</label>
    <input style='padding:1%; width:98%' type="text" class="span2" name="title" value="<?php readable_text(Param::get('title')) ?>">
    <label>Comment</label>
    <textarea style='padding:1%; width:98%; height:200px;' name="body" ><?php readable_text(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="page_next" value="create_end">
    <button type="submit" class="btn btn-primary">Submit</button>                
</form>
