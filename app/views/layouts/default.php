<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Web Portal <?php readable_text( !empty($title) ? $title : ( isset($thread->title) ? $thread->title : ' | Welcome') ) ?></title>
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/bootstrap/css/custom.css" rel="stylesheet">
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class='navbar-header'>
                    <a class="brand" href="<?php echo isset($home) ? $home : '/'; ?>">DietCake Hello</a>
                    </div>
                    <div class="navbar-collapse" >
                        <?php if(isset($_SESSION['username'])):?>
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#"  data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="icon-list icon-white"></span> Threads <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/thread/index"><i class="icon-calendar"></i> Most Recent</a></li>
                                    <li><a href="/thread/mostComment"><i class="icon-comment"></i> Most Comments</a></li>
                                    <li><a href="/thread/mostFollowed"><i class="icon-thumbs-up"></i> Most Followed</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="caret"></span>
                                    <img class='avatar-icon' src='<?php echo $user->user_details['avatar'] ?>' height='30' width='30' />
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/user/update"><i class="icon-pencil"></i> Edit Profile</a></li>
                                    <li><a href="/user/changePassword"><i class="icon-lock"></i> Change Password</a></li>
                                    <li class="divider"></li>
                                    <li>
                                    <a href="/user/delete?type=user&url_back=/&id=<?php echo $user->user_id; ?>">
                                    <i class="icon-remove-sign"></i> Delete Account</a></li>
                                </ul>
                                
                            </li>
                            <li>
                                <a href="/user/index">
                                    <?php readable_text($_SESSION['username']); ?>
                                    <span class="icon-user icon-white"></span>
                                </a>
                            </li>
                            <li><a href='/user/logout'>Log out <span class="icon-off icon-white"></span></a></li>
                        </ul>
                        <?php endif ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="container main_container">

            <?php echo $_content_ ?>

        </div>

        <script>
            console.log(<?php readable_text(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>
    </body>
</html>
