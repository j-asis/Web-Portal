<?php
class ThreadController extends AppController                    
{

    public function index()                        
    {
        $page = Param::get('page', 1);
        $per_page = 5;
        $pagination = new SimplePagination($page, $per_page);
        $threads = Thread::getAll($pagination->start_index - 1, $pagination->count + 1);
        $pagination->checkLastPage($threads);
        $total = Thread::countAll();
        $pages = ceil($total / $per_page);
        $this->set(get_defined_vars());
    }

    public function view()
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comments = $thread->getComments();
        $this->set(get_defined_vars());
    }

    public function write()                        
    {
        $thread = Thread::get(Param::get('thread_id'));
        $comment = new Comment;
        $page = Param::get('page_next','write');
            
        switch ($page) {
            case 'write':
                break;
            case 'write_end':            
                $comment->username = Param::get('username');
                $comment->body = Param::get('body');
                try {
                    $thread->write($comment);
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
        $thread = new Thread;
        $comment = new Comment;
        $page = Param::get('page_next', 'create');
             
        switch ($page) {
            case 'create':
                break;
            case 'create_end':                
                $thread->title = Param::get('title');
                $comment->username = Param::get('username');
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

}