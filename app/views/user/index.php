<h1>Welcome <?php readable_text($user->username); ?></h1>
<div class="user-profile">
  <div class="avatar-big float-left">
        <img src='<?php echo $user->user_details['avatar']; ?>' height='200' width='200' class="img-rounded"/>
  </div>
  <div class="float-left user-details">
        <h3 style='margin-top:0; line-height:22px;'>User Details</h3>
    <div>
        <em>First Name</em> : <?php readable_text($user->user_details['first_name']); ?> <br />
        <em>Last Name</em> : <?php readable_text($user->user_details['last_name']); ?> <br />
        <em>Username Name</em> : <?php readable_text($user->user_details['username']); ?> <br />
        <em>Email Address</em> : <?php readable_text($user->user_details['email']); ?> <br />
    </div>
  </div>
</div>