<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DietCake <?php eh(isset($thread->title) ? $thread->title : ( !empty($title) ? $title : 'Hello') ) ?></title>
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                padding-top: 60px;
            }
            .main_container{
                padding:20px;
                box-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1) inset, 0px 1px 10px rgba(0, 0, 0, 0.1);
            }
            .list-unstyled{
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .navbar-right{
                text-align: right;
            }
            .navbar a{
                color:#F6EA00;
            }
        </style>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class='navbar-header'>
                    <a class="brand" href="/">DietCake Hello</a>
                    <?php if(isset($_SESSION['username'])):?>

                    <p class="navbar-text navbar-right">Signed in as <a href="/user/index"><?php eh($_SESSION['username']); ?></a> <a style="margin-left:20px;" href='/user/logout'>Log out</a></p>
                    <?php endif ?>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="container main_container">

            <?php echo $_content_ ?>

        </div>

        <script>
            console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
        </script>

    </body>
</html>
