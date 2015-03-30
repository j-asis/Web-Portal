<h1>Welcome <?php readable_text($user->username); ?>, this is the profile of <?php readable_text($other_user['username']); ?></h1>
<hr />
<img src='<?php echo $other_user['avatar']; ?>' height='200' width='200' />
<h3>User Details</h3>
<div>
    <em>First Name</em> : <?php readable_text($other_user['first_name']); ?> <br />
    <em>Last Name</em> : <?php readable_text($other_user['last_name']); ?> <br />
    <em>Username Name</em> : <?php readable_text($other_user['username']); ?> <br />
    <em>Email Address</em> : <?php readable_text($other_user['email']); ?> <br />
</div>
