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
        $comment_id = Param::get('id', Comment::ERROR_COMMENT_ID);
        try {
            $comment = Comment::getContent($comment_id);
        } catch (CommentNotFoundException $e) {
            $comment = new Comment(array('comment_id' => $comment_id));
        }
        $comment->current_user_id = $user->user_id;
        $comment->validate();

        if ($check) {
            $comment->new_body = Param::get('new_comment', '');
            try {
                $comment->edit();
            } catch (ValidationException $e) {
                $comment->error = true;
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
        if ($comment_id === Comment::ERROR_COMMENT_ID) {
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

    public function delete()
    {
        if (!isset($_SESSION['username'])) {
            redirect(url('/'));
        }
        $user = new User();
        $user->setInfoByUsername($_SESSION['username']);
        $id = Param::get('id', '');
        $url_back = urldecode(Param::get('url_back', '/'));
        $confirm = Param::get('confirm', 'false');
        $is_success = false;
        $type = "Comment";
        if ($confirm === 'true') {
            $is_success = Comment::deleteById($id);
        }
        $this->set(get_defined_vars());
        $this->render('user/delete');
    }
}
