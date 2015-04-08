<h1><?php readable_text($title); ?></h1>
<em><?php echo $sub_title; ?> </em>
<a class="btn btn-large btn-primary create-thread" href="<?php readable_text(url('thread/create')) ?>"><span class="icon-plus icon-white"></span>Create Thread</a>
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
<hr></hr>
    <?php foreach ($threads as $thread): ?>    
        <div class="thread-list">
        <a href="<?php readable_text(url('thread/view', array('thread_id' => $thread->id))) ?>">
            <?php readable_text($thread->title); ?>
        </a>
        <p>
            Author:
                <a href="<?php readable_text(url('user/profile', array('user_id'=>$thread->user_id))); ?>">
                    <img class="img-rounded" height="20" width="20" src="<?php echo $thread->avatar; ?>" alt="user avatar">
                    <?php readable_text($thread->username); ?>
                </a>
                <br />
            Date: <?php echo time_difference($thread->date); ?>
            <br />
            <em class="faded"><?php readable_text($thread->comment_count); ?> Comments</em>
            <br />
            <em class="faded">followed by <?php readable_text($thread->follow_count); ?> people</em>
            <br />
            <?php if (isset($user->followed_threads[$thread->id])): ?>
            <a class="btn btn-small btn-success follow-link" href="<?php echo '/thread/follow?id='.$thread->id.'&type=unfollow&back='.urlencode($_SERVER['REQUEST_URI']); ?>">
                <span class="icon-eye-open"></span>
                    unfollow thread
            </a>
            <?php else: ?>
            <a class="btn btn-small btn-success follow-link" href="<?php echo '/thread/follow?id='.$thread->id.'&type=follow&back='.urlencode($_SERVER['REQUEST_URI']); ?>">
                <span class="icon-eye-close"></span>
                    follow thread
            </a>
            <?php endif; ?>
        </p>
        </div>
    <?php endforeach ?>
