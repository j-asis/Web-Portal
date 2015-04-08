<?php

class CommentController extends AppController 
{
    public function edit()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User;
        $user->setInfoByUsername($_SESSION['username']);
        $check = Param::get('check', false);
        $title = " | Edit comment";
        $params = array(
            'comment_id' => Param::get('id', ''),
            'thread_id'  => Param::get('thread_id', ''),
            'user_id'    => $user->user_id,
        );
        $comment = new Comment($params);
        $comment_content = Comment::getContent(Param::get('id', 0));
        if (isset($comment_content->error)) {
            $comment->error = $comment_content->error;
            $this->set(get_defined_vars());
            return;
        }
        if ($comment_content->user_id !== $user->user_id) {
            $comment->error = 'Cannot edit other user\'s comment';
            $this->set(get_defined_vars());
            return;
        }
        if ($check) {
            $comment->body = Param::get('new_comment', '');
            try {
                $comment->edit();
            } catch (ValidationException $e) {
                $comment->error = 'Input Error, Please enter at from 1 to 200 charcters';
            } catch (Exception $e) {
                $comment->error = 'Unexpected Error occured';
            }
        }
        $this->set(get_defined_vars());
    }

    public function like()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User;
        $user->setInfoByUsername($_SESSION['username']);
        $type = Param::get('type', 'like');
        $comment_id = Param::get('comment_id', 0);
        $back_url = Param::get('back', '/');
        if ($comment_id === 0) {
            redirect(url('/'));
            return;
        }
        switch ($type) {
            case 'like':
                Likes::add($comment_id, $user->user_id);
                break;
            case 'unlike':
                Likes::remove($comment_id, $user->user_id);
                break;
            default:
                redirect(url('/'));
                break;
        }
        redirect($back_url);
    }
    
    public function most_liked()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $limit = getLimit(Likes::getCommentsByLikeCount());
        $user = new User;
        $user->setInfoByUsername($_SESSION['username']);
        $comments = Comment::getMostLiked($limit);
        $title = "Most Liked Comment";
        $sub_title = sprintf("Showing top %d most liked comments", count($comments));
        $this->set(get_defined_vars());
        $this->render('view');
    }
}
