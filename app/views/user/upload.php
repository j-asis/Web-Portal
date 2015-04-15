<?php if($upload->hasError()): ?>
<div class="alert alert-block">
    <?php if ($upload->error['no_file']): ?>
    <h3>No file was selected!</h3>
    <?php endif; ?>

    <?php if ($upload->error['upload']): ?>
    <h3>File Upload Error Occured!</h3>
    <?php endif; ?>

    <?php if ($upload->error['file_exists']): ?>
    <h3>File already exists!</h3>
    <?php endif; ?>

    <?php if ($upload->error['file']): ?>
    <h3>File is not an image!</h3>
    <?php endif; ?>

    <?php if ($upload->error['size']): ?>
    <h3>File size too big, can only upload 2000 bytes</h3>
    <?php endif; ?>

    <?php if ($upload->error['type']): ?>
    <h3>Wrong File type, can only upload jpeg, jpg, png, or gif</h3>
    <?php endif; ?>
</div>
<?php elseif(!$saved): ?>
    <div class="alert alert-block">
    <h4>Unexpected Error Occurred!</h4>
    </div>
<?php else: ?>
<p class="alert alert-success">
  You successfully Uploaded your avatar!
</p>

<a href="/">go to home page</a>

<?php endif; ?>
