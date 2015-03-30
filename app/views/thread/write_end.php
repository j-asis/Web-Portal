<h2><?php readable_text($thread->title) ?></h2>

<p class="alert alert-success">
  You successfully wrote this comment.
</p>

<a href="<?php readable_text(url('thread/view', array('thread_id' => $thread->id))) ?>">
  &larr; Back to thread
</a>
