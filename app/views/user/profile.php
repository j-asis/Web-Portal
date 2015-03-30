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
    <a href="<?php echo url('thread/userThread', array('user_id'=>$user_info['id'])) ?>" class="btn btn-success btn-small">
        <span class="icon-th-list icon-white"></span>
        View <?php echo $user_info['username'] ?>'s thread
    </a>
  </div>
</div>