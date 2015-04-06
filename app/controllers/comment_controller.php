<?php

class CommentController extends AppController 
{
    public function edit()
    {
        $check = Param::get('check', false);
        $title = " | Edit comment";
        $user = new User;
        try {
            $comment_content = Comment::getCommentContent(Param::get('id', 0));
        } catch (RecordNotFoundException $e) {
            $error = "Not Exsisting Comment";
        }
        if (!isset($error)) {
            if ($comment_content->user_id !== $user->user_id) {
                $comment->error = 'Cannot edit other user\'s comment';
            }
            $params = array(
                'comment_id' => Param::get('id', ''),
                'thread_id'  => Param::get('thread_id', ''),
                'user_id'    => $user->user_id,
            );
            if ($check) {
                $params['body'] = Param::get('new_comment', '');
                $comment = new Comment($params);
                try {
                    $comment->editComment();
                } catch (ValidationException $e) {
                    $comment->error = 'Input Error, Please enter at from 1 to 200 charcters';
                } catch (Exception $e) {
                    $comment->error = 'Unexpected Error occured';
                }
            } else {
                $comment = new Comment($params);
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