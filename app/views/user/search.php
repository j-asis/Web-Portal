<h1><?php readable_text($title); ?></h1>
<em><?php readable_text($sub_title); ?> </em>
<?php if ($total > 5): ?>
    <!-- pagination -->
<div class='pagination pagination-small'>
    <ul>
        <?php if($pagination->current > 1): ?>
            <li><a href='<?php echo $url_params ?>&page=<?php echo $pagination->prev ?>'>Previous</a></li>
        <?php else: ?>
            <li><a href='#'>Previous</a></li>
        <?php endif ?>

        <?php for($i = 1; $i <= $pages; $i++): ?>
        <?php if($i == $page): ?>
        <li class="active"><a href='#'><?php echo $i ?></a></li>
        <?php else: ?>
        <li><a href='<?php echo $url_params ?>&page=<?php echo $i ?>'><?php echo $i ?></a></li>
        <?php endif; ?>
        <?php endfor; ?>

        <?php if(!$pagination->is_last_page): ?>
            <li><a href='<?php echo $url_params ?>&page=<?php echo $pagination->next ?>'>Next</a></li>
        <?php else: ?>
            <li><a href='#'>Next</a></li>
        <?php endif ?>
    </ul>   
</div>
<?php endif ?>
<hr></hr>

<?php foreach ($query_results as $result): ?>
<div class="comment">
    <div class="meta">
    
    <a href='<?php readable_text('/user/profile?user_id=' . $result['id']); ?>'>
        <img class="img-rounded" height="20" width="20" src="<?php echo $result['avatar']; ?>" alt="user avatar">
        <?php readable_text($result['username']) ?>
    </a>
    <br />
    <p>
        Username : <em><?php echo $result['username']; ?></em><br />
        First Name : <em><?php echo $result['first_name']; ?></em><br />
        Last Name : <em><?php echo $result['last_name']; ?></em><br />
        Email : <em><?php echo $result['email']; ?></em><br />
    </p>
    </div>
</div>
<?php endforeach; ?>