<?php 

namespace modulus\main\comment\model;
use core\model;
use \library\error;
use \Valitron\Validator as v;
use old;
use User;

class commentModel extends model
{
    public function CommentColumn()
    {
        return $this->db->columns('comment');
    }    

	public function commentCount()
    {
	    return $this->db->t2count('comment', 'article', 'comment.comment_status= 1 && comment.user_id=?', [User::user_id()])->count;
    }    

    public function commentList($start, $limit)
    {
        return $this->db->t2where('comment', 'article', "comment_status= 1 && comment.user_id=?
            ORDER BY comment_id DESC LIMIT {$start}, {$limit}", [User::user_id()], 2, 2);
    }

    #
    public function OwnCommentShow($id)
    {
        return $this->db->t2where('comment', 'article', 'comment.user_id=? && comment_id=?', [
            User::user_id(), $id
        ]) ?: $this->return->code(404)->return('not_found', 'comment')->get()->http('comment/page/1');
    }

    #
    public function CommentByOwnArticleCount($id)
    {
        return $this->db->t2count('comment', 'article', "article.user_id=? AND article.article_id=?", 
            [User::user_id(), $id])->count;
    }    

    public function CommentByOwnArticleList($id, $start, $limit)
    {
        return $this->db->t2where('comment', 'article', "article.user_id=? AND article.article_status=1 AND article.article_id=? 
            ORDER BY comment_id DESC LIMIT {$start}, {$limit}", [User::user_id(), $id], 2, 2);
    }

    public function article($id)
    {
        return $this->db->t1where('article', "article_id=?", [$id]);
    }

    #
    public function CommentByUser($id)
    {
        return $this->db->t1where('comment', "comment_status= 1 && user_id=? && comment_id=?", [User::user_id(), $id]) ?:
            $this->return->code(404)->return('not_found', 'comment')->get()->http('comment/page/1');
    }

    #create
    public function CommentCreate()
    {
        $http1 = 'panel/comment/create';

        $form = [
            'comment_text',
            'article_id',
        ];

        #array diff keys
        array_different($form, $_POST) ?: 
            $this->return->code(404)->return('error_form')->get()->referer();

        #peel tags of array
        $data = peel_tag_array($_POST);
        old::create($data);

        #check via valitron
        $v = new v($data);

        $v->rule('required', 'article_id');
        $v->rule('required', 'comment_text');

        $v->rule('lengthMin', 'comment_text', 3);
        $v->rule('lengthMax', 'comment_text', 500);

        error::valitron($v, $http1);

        #
        $article = $this->db->t1where('article', 'article_type = 1 && 
            article_status = 1 && article_id=?', [$data['article_id']])
            ?:
        $this->return->code(404)->return('not_found')->get()->referer();

        #if not found comment
        $data += ['user_id' => User::user_id()];
        $create = $this->db->create('comment', $data);

        $create['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->referer();
        
        old::delete($data);

        #unset variables
        unset($http1); unset($data); unset($_POST); unset($v); unset($form);

        $this->return->code(200)->return('success')->get()->referer();
    }

    public function CommentDelete($id)
    {
        $comment = $this->db->t2where('comment', 'article', 
            'article.user_id=? && comment.comment_id=? OR
            comment.user_id=? && comment.comment_id=?', 
        [User::user_id(), $id, User::user_id(), $id])
            ?: $this->return->code(404)->return('not_found', 'comment')->json();

        $delete = $this->db->delete('comment', [
            'comment_id' => $comment->comment_id
        ], ['id' => 'comment_id']);

        $delete['status'] == TRUE ?:
            $this->return->code(404)->return('error')->json();

        unset($id); unset($delete); unset($comment);

        $this->return->code(200)->return('success')->json();
    }

    public function commentDestroy($id)
    {
        $http1 = 'comment/page/1';

        $comment = $this->db->t2where('comment', 'article', 
            'article.user_id=? && comment.comment_id=? OR
            comment.user_id=? && comment.comment_id=?', 
        [User::user_id(), $id, User::user_id(), $id])
            ?: $this->return->code(404)->return('not_found', 'comment')->json();
        
        $delete = $this->db->delete('comment', [
            'comment_id' => $comment->comment_id,
        ], ['id' => 'comment_id']);

        $delete['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->http($http1);

        unset($id); unset($delete); unset($comment);

        $this->return->code(200)->return('success')->get()->http($http1);
    }
}

