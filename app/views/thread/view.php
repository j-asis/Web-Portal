<h1><?php eh($thread->title);?></h1>
<p>
    Created By : <?php eh($thread_info['username']); ?> <br>
    Date : <?php eh($thread_info['date']); ?> 
</p>
<?php foreach ($comments as $k => $v): ?>

<div class="comment">

<div class="meta">
    <?php if($k != 0): ?>

    <?php eh($k) ?> :
    <?php eh($v->username) ?> <?php eh($v->created) ?>    

    <?php endif ?>
</div>


<div <?php if($k == 0){ echo ' class="topic" ';} ?> ><?php echo readable_text($v->body) ?></div>

</div>

<?php endforeach ?>

<hr>

<form class="well" method="post" action="<?php eh(url('thread/write')) ?>">
    <label>Comment</label>
    <textarea name="body"><?php eh(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
