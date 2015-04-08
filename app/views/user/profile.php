<h1>Welcome <?php readable_text($user->username); ?></h1>
<div class="user-profile">
    <div class="avatar-big float-left">
        <img src='<?php echo $user_info['avatar']; ?>' height='200' width='200' class="img-rounded"/>
    </div>
    <div class="float-left user-details">
        <h3 style='margin-top:0; line-height:22px;'>User Details</h3>
    <div>
        <em>First Name</em> : <?php readable_text($user_info['first_name']); ?> <br />
        <em>Last Name</em> : <?php readable_text($user_info['last_name']); ?> <br />
        <em>Username Name</em> : <?php readable_text($user_info['username']); ?> <br />
        <em>Email Address</em> : <?php readable_text($user_info['email']); ?> <br />
    </div>
    <a href="<?php echo url('thread/user_thread', array('user_id'=>$user_info['id'])) ?>" class="btn btn-success btn-small">
        <span class="icon-th-list icon-white"></span>
        View <?php echo $user_info['username'] ?>'s thread
    </a>
  </div>
</div>
<div class="comment">
    <div class="recent">
        <h4>Recent Threads : </h4>
        <?php if (count($recent_threads) === 0): ?>
        <h6><em>No Threads yet<em></h6>
        <?php endif; ?>
        <?php foreach ($recent_threads as $thread): ?>
        <div class="thread-list">
            <a href="<?php readable_text(url('thread/view', array('thread_id'=>$thread->id))); ?>"><?php readable_text($thread->title); ?></a>
            <br />
            Author :
                <img class="img-rounded" src="<?php echo $thread->avatar ?>" alt="user avatar" width="20" height="20">
                <?php readable_text($thread->username); ?>
            <br />
            Date : <?php echo time_difference($thread->date); ?>
            <br />
            <em class="faded"><?php readable_text($thread->num_comment); ?> Comments</em>
            <br />
            <em class="faded">followed by <?php readable_text($thread->num_follow); ?> people</em>
            <br />
        </div>
        <?php endforeach; ?>
    </div>
    <div class="recent">
        <h4>Recent Comments : </h4>
        <?php if (count($recent_comments) === 0): ?>
        <h6><em>No Comments yet<em></h6>
        <?php endif; ?>
        <?php foreach ($recent_comments as $comment): ?>
        <div class="thread-list">
            <div class="meta">
            by :
                <img class="img-rounded" height="20" width="20" src="<?php echo $comment->avatar; ?>" alt="user avatar">
                <?php readable_text($comment->username) ?>
                <br />
                <em style='font-size:10px; color:#999;'><?php echo time_difference($comment->created) ?></em>
                <br />
                <em style='font-size:10px; color:#999;'>Liked by <?php echo $comment->like_count; ?> people</em>
                <br />
            </div>
            <hr style='margin:4px 0;' />
            <div class="comment-body"><?php echo readable_text($comment->body) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
