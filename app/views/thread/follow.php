<?php if (isset($error)): ?>
<h3>Unexpected Error Occured</h3>
<?php return; endif;?>
<?php
    redirect($back);
?>