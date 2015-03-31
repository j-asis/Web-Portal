<img src='<?php echo $user->user_details['avatar']; ?>' height='200' width='200' />
<form action='/user/upload' enctype="multipart/form-data" method='post'>
    <label class="btn btn-success">Browse Image : 
    <input type="file" name="avatar" style="display:none;">
    </label>
    <input class="btn btn-success" type="submit" value="Upload Image" name="submit">
</form>