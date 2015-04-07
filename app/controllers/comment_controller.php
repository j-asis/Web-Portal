<?php

class CommentController extends AppController 
{
    public function edit()
    {
        $user = new User;
        $check = Param::get('check', false);
        $title = " | Edit comment";
        $params = array(
            'comment_id' => Param::get('id', ''),
            'thread_id'  => Param::get('thread_id', ''),
            'user_id'    => $user->user_id,
        );
        $comment = new Comment($params);

        $comment_content = Comment::getCommentContent(Param::get('id', 0));
        $comment->error = isset($comment_content->error) ? $comment_content->error : null;
        if (isset($comment->error)) {
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
        $user = new User;
        $params = array(
            'comment_id' => Param::get('comment_id', 0),
            'type'       => Param::get('type', 'like'),
            'back'       => Param::get('back', '/'),
            'user_id'    => $user->user_id,
        );
        if ($params['comment_id'] === 0) {
            redirect(url('/'));
            return;
        }
        $comment = new Comment($params);
        $comment->like();
        redirect($comment->back);
    }
    
    public function most_liked()
    {
        $user = new User;
        $comments = Comment::getMostLiked();
        $title = "Most Liked Comment";
        $sub_title = sprintf("Showing top %d most liked comments", count($comments));
        $this->set(get_defined_vars());
        $this->render('view');
    }
}