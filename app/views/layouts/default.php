<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DietCake <?php readable_text( !empty($title) ? $title : ( isset($thread->title) ? $thread->title : 'Hello') ) ?></title>
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bootstrap/css/custom.css" rel="stylesheet">
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class='navbar-header'>
                    <a class="brand" href="<?php echo isset($home) ? $home : '/'; ?>">DietCake Hello</a>
                    <?php if(isset($_SESSION['username'])):?>
                    <p class="navbar-text navbar-right"><a class="menu" href="<?php readable_text(url('thread/index')); ?>">Threads</a> Logged in as <a href="/user/index"><?php readable_text($_SESSION['username']); ?></a> <a style="margin-left:20px;" href='/user/logout'>Log out</a></p>
                    <?php endif ?>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="container main_container">

            <?php echo $_content_ ?>

        </div>

        <script>
            console.log(<?php round(microtime(true) - TIME_START, 3) ?> + 'sec');
        </script>

    </body>
</html>
