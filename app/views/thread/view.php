<h1><?php eh($thread->title);?></h1>
<p>
    Created By : <?php eh($thread_info['username']); ?> <br>
    Date : <?php eh($thread_info['date']); ?> 
</p>
<?php foreach ($comments as $key => $comment): ?>

<div class="comment">

<div class="meta">

    <?php eh($key + 1) ?> :
    <?php eh($comment->username) ?> <?php eh($comment->created) ?>    

</div>


<div><?php echo readable_text($comment->body) ?></div>

</div>

<?php endforeach ?>

<?php if ($total > 5): ?>
    <!-- pagination -->
<div class='pagination pagination-small'>
    <ul>
        <?php if($pagination->current > 1): ?>
            <li><a href='?thread_id=<?php echo $thread_id; ?>&comment_page=<?php echo $pagination->prev ?>'>Previous</a></li>
        <?php else: ?>
            <li><a href='#'>Previous</a></li>
        <?php endif ?>

        <?php for($i = 1; $i <= $pages; $i++): ?>
        <?php if($i == $comment_page): ?>
        <li><a href='#'><?php echo $i ?></a></li>
        <?php else: ?>
        <li><a href='?thread_id=<?php echo $thread_id; ?>&comment_page=<?php echo $i ?>'><?php echo $i ?></a></li>
        <?php endif; ?>
        <?php endfor; ?>

        <?php if(!$pagination->is_last_page): ?>
            <li><a href='?thread_id=<?php echo $thread_id; ?>&comment_page=<?php echo $pagination->next ?>'>Next</a></li>
        <?php else: ?>
            <li><a href='#'>Next</a></li>
        <?php endif ?>
    </ul>   
</div>
<?php endif ?>


<hr>

<form class="well" method="post" action="<?php eh(url('thread/write')) ?>">
    <label>Comment</label>
    <textarea style='padding:1%; width:98%; height:100px;' name="body"><?php eh(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
