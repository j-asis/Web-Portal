<h1><?php readable_text($title); ?></h1>
<em><?php echo $sub_title; ?> </em>
<hr>
    <?php foreach ($comments as $comment): ?>    
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
</div>
<hr style='margin:4px 0;' />

<div class="comment-body"><?php echo readable_text($comment->body) ?></div>

</div>
    <?php endforeach ?>
