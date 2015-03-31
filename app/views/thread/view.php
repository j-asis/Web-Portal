<h1><?php readable_text($thread->title);?></h1>
<p>
    Created By : <a href='<?php readable_text('/user/profile?user_id=' . $thread_info['user_id']); ?>'>
                    <img class="img-rounded" height="20" width="20" src="<?php echo $thread_info['avatar']; ?>">
                    <?php readable_text($thread_info['username']); ?>
                </a><br>
    Date : <?php readable_text($thread_info['date']); ?>
    <br />
    <em class="faded">followed by <?php readable_text($thread_info['num_follow']); ?> People</em>
    <br />
    <?php if (isset($user->followed_threads[$thread_info['id']])): ?>
    <a class="btn btn-small btn-success" href="<?php echo '/thread/follow?id='.$thread_info['id'].'&type=unfollow&back='.urlencode($_SERVER['REQUEST_URI']); ?>">
        <span class="icon-eye-close"></span>
            unfollow thread
    </a>
    <?php else: ?>
    <a class="btn btn-small btn-success" href="<?php echo '/thread/follow?id='.$thread_info['id'].'&type=follow&back='.urlencode($_SERVER['REQUEST_URI']); ?>">
        <span class="icon-eye-open"></span>
            follow thread
    </a>
    <?php endif; ?>
    <?php if ($user->user_id === $thread->user_id): ?>
        <a class="btn btn-small" href='/user/delete?type=thread&url_back=<?php echo urlencode('/'); ?>&id=<?php echo $comment->id; ?>'>
        <span class='icon-trash'></span> Delete
        </a>
        <a class="btn btn-small" href="<?php echo url('thread/edit', array('id'=>$thread->id)); ?>">
            <span class='icon-pencil'></span> Edit
        </a>
    <?php endif; ?>
</p>
<?php foreach ($comments as $key => $comment): ?>

<div class="comment">

<div class="meta">

    by : <a href='<?php readable_text('/user/profile?user_id=' . $comment->user_id); ?>'>
                    <img class="img-rounded" height="20" width="20" src="<?php echo $comment->avatar; ?>" alt="user avatar">
                    <?php readable_text($comment->username) ?>
                </a> <br />
    <em style='font-size:10px; color:#999;'><?php echo time_difference($comment->created) ?></em>
    <br />
    <em style='font-size:10px; color:#999;'>Liked by <?php echo $comment->like_count; ?> people</em>
    <br />
    <?php if(isset($user->liked_comments[$comment->id])): ?>
        <a class="btn btn-small btn-success" href="<?php echo url('comment/like', array('comment_id'=>$comment->id, 'type'=>'unlike', 'back'=>url(''))); ?>">
                <span class='icon-thumbs-down icon-white'></span> Unlike
        </a>
    <?php else: ?>
        <a class="btn btn-small btn-success" href="<?php echo url('comment/like', array('comment_id'=>$comment->id, 'type'=>'like', 'back'=>url(''))); ?>">
                <span class='icon-thumbs-up icon-white'></span> Like
        </a>
    <?php endif; ?>
    <?php if ($user->user_id === $comment->user_id): ?>
        <a class="btn btn-small" href='/user/delete?type=comment&url_back=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&id=<?php echo $comment->id; ?>'>
            <span class='icon-trash'></span> Delete
        </a>
        <a class="btn btn-small" href="<?php echo url('comment/edit', array('id'=>$comment->id, 'thread_id'=>$comment->thread_id)); ?>">
            <span class='icon-pencil'></span> Edit
        </a>
    <?php endif; ?>
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
        <li class="active"><a href='#'><?php echo $i ?></a></li>
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
