<?php

class ThreadController extends AppController
{
    public function index()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $title = 'Most Recent Threads';
        $url_params = '?';
        $page = Param::get('page', Thread::DEFAULT_PAGE);
        $pagination = new SimplePagination($page, Thread::MAX_PER_PAGE);
        $threads = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countAll();
        $pages = ceil($total / Thread::MAX_PER_PAGE);
        $sub_title = 'Total of '.$total.' threads';
        $this->set(get_defined_vars());
    }

    public function view()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        try {
            $thread = Thread::get(Param::get('thread_id', Thread::ERROR_THREAD_ID));
        } catch (RecordNotFoundException $e) {
            $error = true;
        }
        if (isset($error)) {
            $this->set(get_defined_vars());
            return;
        }

        $thread_id = Param::get('thread_id', Thread::ERROR_THREAD_ID);
        $thread_info = objectToArray($thread->getThreadInfo($thread_id));
        $comment = new Comment();
        $comment->id = $thread_id;
        $comment_page = Param::get('comment_page', Comment::DEFAULT_PAGE);
        $pagination = new SimplePagination($comment_page, Comment::MAX_PER_PAGE);
        $comments = Comment::getByThreadId($thread_id, $pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($comments);
        $total = Comment::countAll($thread_id);
        $pages = ceil($total / Comment::MAX_PER_PAGE);
        $this->set(get_defined_vars());
    }

    public function write()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $thread = Thread::get(Param::get('thread_id', Thread::ERROR_THREAD_ID));
        $comment = new Comment();
        $page = Param::get('page_next', 'write');
        switch ($page) {
            case Thread::WRITE:
                break;
            case Thread::WRITE_END:
                $comment->user_id = User::getUserId($user->username);
                $comment->body    = Param::get('body', '');
                if (!$comment->validate()) {
                    $page = 'write';
                } else {
                    $comment->write($thread);
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
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $thread = new Thread();
        $comment = new Comment();
        $page = Param::get('page_next', 'create');
        switch ($page) {
            case Thread::CREATE:
                break;
            case Thread::CREATE_END:
                $thread->title    = Param::get('title', '');
                $comment->user_id = User::getUserId($user->username);
                $comment->body    = Param::get('body', '');
                $thread->validate();
                $comment->validate();
                if ($thread->hasError() || $comment->hasError()) {
                    $page = 'create';
                } else {
                    $thread->create($comment);
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
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $check = Param::get('check', false);
        $title = " | Edit thread";
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $params = array(
            'thread_id' => Param::get('id', Thread::ERROR_THREAD_ID),
            'user_id'   => $user->user_id,
        );
        $thread = new Thread($params);

        $thread_content = Thread::get(Param::get('id', Thread::ERROR_THREAD_ID));
        if (isset($thread_content->error)) {
            $thread->error = $thread_content->error;
            $this->set(get_defined_vars());
            return;
        }
        if ($thread_content->user_id !== $user->user_id) {
            $thread->error = 'Cannot edit other user\'s thread';
            $this->set(get_defined_vars());
            return;
        }
        if ($check) {
            $thread->title = Param::get('new_thread', '');
            if (!$thread->validate()) {
                $thread->error = 'Input Error, Please enter at from 1 to 200 charcters';
            }
            try {
                $thread->editThread();
            } catch (Exception $e) {
                $thread->error = 'Unexpected Error occured';
            }
        }
        $this->set(get_defined_vars());
    }

    public function top_threads()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $type = Param::get('type', '');
        if ($type === '') {
            redirect(url('/'));
        }
        if ($type === 'comment') {
            $limit = getLimit(Comment::getThreadsByCommentCount()); 
        } else {
            $limit = getLimit(Follow::getThreadsByFollowCount()); 
        }
        switch ($type) {
            case Thread::TOP_COMMENTED:
                $top_threads = Thread::getMostCommented($limit);
                $title = 'Most Commented Thread';
                $sub_title = "Showing top {$limit} most commented threads";
                break;
            case Thread::TOP_FOLLOWED:
                $top_threads = Thread::getMostFollowed($limit);
                $title = 'Most Followed Thread';
                $sub_title = "Showing top {$limit} most followed threads";
                break;
            default:
                break;
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $this->set(get_defined_vars());
    }

    public function user_thread()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $user_id = Param::get('user_id', $user->user_id);
        $url_params = '?user_id='.$user_id.'&';
        $title = $user->username . '\'s Threads';
        $page = Param::get('page', Thread::DEFAULT_PAGE);
        $pagination = new SimplePagination($page, Thread::MAX_PER_PAGE);
        $threads = Thread::userThread($user_id, $pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countByUser($user_id);
        $pages = ceil($total / Thread::MAX_PER_PAGE);
        $sub_title = "total of $total threads";
        $this->set(get_defined_vars());
        $this->render('index');
    }
    
    public function follow()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $thread_id = Param::get('id', Thread::ERROR_THREAD_ID);
        $back = Param::get('back', '/');
        $type = Param::get('type', 'follow');
        if ($thread_id === 0) {
            redirect(url('/'));
            return;
        }
        switch ($type) {
            case Thread::FOLLOW:
                Follow::add($thread_id, $user->user_id);
                break;
            case Thread::UNFOLLOW:
                Follow::remove($thread_id, $user->user_id);
                break;
            default:
                redirect(url('/'));
                break;
        }
        redirect($back);
    }
}
