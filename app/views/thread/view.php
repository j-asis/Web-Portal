<h1><?php readable_text($thread->title);?></h1>
<p>
    Created By : <a href='<?php readable_text('/user/viewUser?user_id=' . $thread_info['user_id']); ?>'><?php readable_text($thread_info['username']); ?></a> <br>
    Date : <?php readable_text($thread_info['date']); ?> 
</p>
<?php foreach ($comments as $key => $comment): ?>

<div class="comment">

<div class="meta">

    Comment by : <a href='<?php readable_text('/user/viewUser?user_id=' . $comment->user_id); ?>'><?php readable_text($comment->username) ?></a> <br />
    <em style='font-size:10px; color:#999;'><?php echo time_difference($comment->created) ?></em>

</div>
<hr style='margin:4px 0;' />

<div class="comment-body"><?php echo readable_text($comment->body) ?></div>

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

<form class="well" method="post" action="<?php readable_text(url('thread/write')) ?>">
    <label>Comment</label>
    <textarea style='padding:1%; width:98%; height:100px;' name="body"><?php readable_text(Param::get('body')) ?></textarea>
    <br />
    <input type="hidden" name="thread_id" value="<?php readable_text($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
