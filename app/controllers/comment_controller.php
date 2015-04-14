<?php

class CommentController extends AppController 
{
    public function edit()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $check = Param::get('check', false);
        $title = " | Edit comment";
        $params = array(
            'comment_id' => Param::get('id', ''),
            'thread_id'  => Param::get('thread_id', ''),
            'user_id'    => $user->user_id,
        );
        $comment = new Comment($params);
        $comment->error = '';
        try {
            $comment_content = Comment::getContent(Param::get('id', Comment::ERROR_COMMENT_ID));
        } catch (CommentNotFoundException $e) {
            $comment->validation_errors['comment_id']['exists'] = true;
            $this->set(get_defined_vars());
            return;
        }
        if ($comment_content->user_id !== $user->user_id) {
            $comment->validation_errors['authenticate']['valid'] = true;
            $this->set(get_defined_vars());
            return;
        }
        if ($check) {
            $comment->body = Param::get('new_comment', '');
            if (!$comment->validate()) {
                $comment->error = 'Input Error, Please enter at from 1 to 200 charcters';
            } else {
                try {
                    $comment->edit();
                } catch (Exception $e) {
                    $comment->error = 'Unexpected Error occured';
                }
            }
        }
        $this->set(get_defined_vars());
    }

    public function like()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $type = Param::get('type', 'like');
        $comment_id = Param::get('comment_id', Comment::ERROR_COMMENT_ID);
        $back_url = Param::get('back', '/');
        if ($comment_id === 0) {
            redirect(url('/'));
            return;
        }
        switch ($type) {
            case Comment::LIKE:
                Likes::add($comment_id, $user->user_id);
                break;
            case Comment::UNLIKE:
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
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $comments = Comment::getMostLiked($limit);
        $title = "Most Liked Comment";
        $sub_title = sprintf("Showing top %d most liked comments", count($comments));
        $this->set(get_defined_vars());
        $this->render('view');
    }
}
