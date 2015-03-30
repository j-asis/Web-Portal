<h1>Welcome <?php readable_text($user->username); ?>, this is the profile of <?php readable_text($user_details['username']); ?></h1>
<hr />
<h3>User Details</h3>
<div>
    <em>First Name</em> : <?php readable_text($user_details['first_name']); ?> <br />
    <em>Last Name</em> : <?php readable_text($user_details['last_name']); ?> <br />
    <em>Username Name</em> : <?php readable_text($user_details['username']); ?> <br />
    <em>Email Address</em> : <?php readable_text($user_details['email']); ?> <br />
</div>
