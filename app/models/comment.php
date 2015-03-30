<?php
class Comment extends AppModel
{
    const MIN_STRING_LENGTH = 1;
    const MAX_STRING_LENGTH = 200;
    
    public $validation = array(
        'body' => array(
            'length' => array(
                'validate_between', self::MIN_STRING_LENGTH, self::MAX_STRING_LENGTH,
                ),
            ),
        );

    public function write($thread)
    {
        if (!$this->validate()) {
            throw new ValidationException('invalid comment');
        }
        $db = DB::conn();
        $db->query('INSERT INTO comment SET thread_id = ?, user_id = ?, body = ?, created = NOW()', array($thread->id, $this->user_id, $this->body));
    }
    public static function getByThreadId($id, $offset, $limit)
    {
        $comments = array();
        $db = DB::conn();
        $query = sprintf("SELECT * FROM comment WHERE thread_id = ? ORDER BY created ASC LIMIT %d, %d", $offset, $limit);
        $rows = $db->rows($query, array($id));
        foreach ($rows as $row) {
            $row['username'] = User::getUserName($row['user_id']);
            $comments[] = new Comment($row);
        }
        return $comments;
    }
    public static function countAllComments($thread_id)
    {
        $db = DB::conn();
        return (int) $db->value("SELECT COUNT(*) FROM comment WHERE thread_id = ?", array($thread_id));
    }
}
