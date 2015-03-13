<h1>All threads</h1>
<hr></hr>
<ul class="list-unstyled">
    <?php foreach ($threads as $v): ?>
    <li><a href="<?php eh(url('thread/view', array('thread_id' => $v->id))) ?>">
    <?php eh($v->title) ?></a></li>
    <?php endforeach ?>
</ul>
    <!-- pagination -->
<div class='pagination pagination-small'>
    <ul>
        <?php if($pagination->current > 1): ?>
            <li><a href='?page=<?php echo $pagination->prev ?>'>Previous</a></li>
        <?php else: ?>
            <li><a href='#'>Previous</a></li>
        <?php endif ?>

        <?php for($i = 1; $i <= $pages; $i++): ?>
        <?php if($i == $page): ?>
        <li><a href='#'><?php echo $i ?></a></li>
        <?php else: ?>
        <li><a href='?page=<?php echo $i ?>'><?php echo $i ?></a></li>
        <?php endif; ?>
        <?php endfor; ?>

        <?php if(!$pagination->is_last_page): ?>
            <li><a href='?page=<?php echo $pagination->next ?>'>Next</a></li>
        <?php else: ?>
            <li><a href='#'>Next</a></li>
        <?php endif ?>
    </ul>   
</div>

<a class="btn btn-large btn-primary" href="<?php eh(url('thread/create')) ?>">Create</a>