<?php if(isset($error)): ?>
<div class="alert alert-block">
    <?php if($error): ?>
        <h4 class="alert-heading"><?php readable_text($error); ?></h4>
        return to <a href="/">home page</a><br />
    <?php endif; ?>
    <?php if(isset($upload->error_message)): ?>
        <?php foreach ($upload->error_message as $message): ?>
            <em><?php readable_text($message); ?></em><br />
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php else: ?>
<p class="alert alert-success">
  You successfully Uploaded your avatar!
</p>

<a href="/">go to home page</a>

<?php endif; ?>
