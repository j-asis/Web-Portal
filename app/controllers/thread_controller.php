<?php

class ThreadController extends AppController
{
    public function index()
    {
        $user = new User;
        $title = 'All Threads';
        $url_params = '?';
        $page = Param::get('page', 1);
        $per_page = 5;
        $pagination = new SimplePagination($page, $per_page);
        $threads = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countAll();
        $pages = ceil($total / $per_page);
        $sub_title = 'Total of '.$total.' threads';
        $this->set(get_defined_vars());
    }
    public function view()
    {
        $user = new User;
        $thread = Thread::get(Param::get('thread_id'));
        $thread_id = Param::get('thread_id');
        $thread_info = $thread->getThreadInfo($thread_id);

        $comment = new Comment;
        $comment->id = $thread_id;
        $comment_page = Param::get('comment_page',1);
        $per_page = 5;
        $pagination = new SimplePagination($comment_page, $per_page);
        $comments = Comment::getByThreadId($thread_id, $pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($comments);
        $total = Comment::countAllComments($thread_id);
        $pages = ceil($total / $per_page);

        $this->set(get_defined_vars());
    }
    public function write()
    {
        $user = new User;
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment;
        $page = Param::get('page_next','write');
        switch ($page) {
            case 'write':
                break;
            case 'write_end':
                $comment->user_id = User::getUserId($_SESSION['username']);
                $comment->body = Param::get('body');
                try {
                    $comment->write($thread);
                } catch (ValidationException $e) {
                    $page = 'write';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
                break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }
    public function create()
    {
        $user = new User;
        $thread = new Thread;
        $comment = new Comment;
        $page = Param::get('page_next', 'create');
        switch ($page) {
            case 'create':
                break;
            case 'create_end':
                $thread->title = Param::get('title');
                $comment->user_id = User::getUserId($_SESSION['username']);
                $comment->body = Param::get('body');
                try {
                    $thread->create($comment);
                } catch (ValidationException $e) {
                    $page = 'create';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found");
            break;
        }
        $this->set(get_defined_vars());
        $this->render($page);
    }
    public function edit()
    {
        $check = Param::get('check', false);
        $title = " | Edit thread";
        $user = new User;
        try {
            $thread_content = Thread::get(Param::get('id'));
        } catch (RecordNotFoundException $e) {
            $error = "Not Exsisting Thread";
        }
        if (!isset($error)) {
            if ($thread_content->user_id !== $user->user_id) {
                $thread->error = 'Cannot edit other user\'s thread';
            }
            $params = array(
                'thread_id' => Param::get('id'),
                'user_id' => $user->user_id,
                );
            if ($check) {
                $params['title'] = Param::get('new_thread', '');
                $thread = new Thread($params);
                try {
                    $thread->editThread();
                } catch (ValidationException $e) {
                    $thread->error = 'Input Error, Please enter at from 1 to 200 charcters';
                } catch (Exception $e) {
                    $thread->error = 'Unexpected Error occured';
                }
            } else {
                $thread = new Comment($params);
            }
        }
        $this->set(get_defined_vars());
    }
    public function top_threads()
    {
        $type = Param::get('type', '');
        if ($type === '') {
            redirect(url('/'));
        }
        switch ($type) {
            case 'comment':
                $title = 'Most Commented Thread';
                $sub_title = 'Showing top %d most commented threads';
                break;
            case 'follow':
                $title = 'Most Followed Thread';
                $sub_title = 'Showing top %dmost followed threads';
                break;
            default:
                break;
        }
        $user = new User;
        $thread = new Thread;
        $top_threads = $thread->topThreads($type);
        $sub_title = sprintf($sub_title, count($top_threads));
        $this->set(get_defined_vars());
        $this->render('top_threads');
    }
    public function user_thread()
    {
        $user = new User;
        $user_id = Param::get('user_id', $user->user_id);
        $url_params = '?user_id='.$user_id.'&';
        $title = $user->username . '\'s Threads';
        $page = Param::get('page', 1);
        $per_page = 5;
        $pagination = new SimplePagination($page, $per_page);
        $threads = Thread::userThread($user_id, $pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countByUser($user_id);
        $pages = ceil($total / $per_page);
        $sub_title = "total of $total threads";
        $this->set(get_defined_vars());
        $this->render('index');
    }
    public function follow()
    {
        $user = new User;
        $thread = new Thread;
        $thread->follow_id = Param::get('id');
        $thread->user_id = $user->user_id;
        $thread->follow_type = Param::get('type');
        $back = Param::get('back');
        try{
            $thread->follow();
        } catch(Exception $e) {
            $error = true;
        }
        $this->set(get_defined_vars());
    }
}
