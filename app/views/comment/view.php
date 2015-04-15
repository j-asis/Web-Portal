<h1><?php echo $title; ?></h1>
<em><?php echo $sub_title; ?> </em>
<?php if (isset($total)): ?>
<?php if ($total > 5): ?>
    <!-- pagination -->
<div class='pagination pagination-small'>
    <ul>
        <?php if($pagination->current > 1): ?>
            <li><a href='<?php echo $url_params ?>&page=<?php echo $pagination->prev ?>'>Previous</a></li>
        <?php else: ?>
            <li><a href='#'>Previous</a></li>
        <?php endif ?>

        <?php for($i = 1; $i <= $pages; $i++): ?>
        <?php if($i == $page): ?>
        <li class="active"><a href='#'><?php echo $i ?></a></li>
        <?php else: ?>
        <li><a href='<?php echo $url_params ?>&page=<?php echo $i ?>'><?php echo $i ?></a></li>
        <?php endif; ?>
        <?php endfor; ?>

        <?php if(!$pagination->is_last_page): ?>
            <li><a href='<?php echo $url_params ?>&page=<?php echo $pagination->next ?>'>Next</a></li>
        <?php else: ?>
            <li><a href='#'>Next</a></li>
        <?php endif ?>
    </ul>   
</div>
<?php endif ?>
<?php endif ?>

<hr></hr>
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
