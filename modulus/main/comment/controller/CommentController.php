<?php 

namespace modulus\main\comment\controller;
use modulus\main\comment\model\CommentModel;
use core\controller;
use pagination;

class CommentController extends controller
{
    public $comment;
    
    public function __construct()
    {
        $this->comment = new CommentModel();
        $this->page = new pagination();
    }

    public function comment()
    {
        $this->layout('general', 'main', 'comment', 'comment', [
            'page' => $p = $this->page->page($this->comment->commentCount(), 5),
            'comment' => $this->comment->commentList($p->start, $p->limit),
        ]);
    }

    public function OwnShow($id)
    {
        $this->layout('general', 'main', 'comment', 'ownShow', [
            'comment' => $this->comment->OwnCommentShow($id),
        ]);
    }

    public function CommentByOwnArticle($id)
    {
        $this->layout('general', 'main', 'comment', 'ownArticle', [
            'page' => $p = $this->page->page($this->comment->CommentByOwnArticleCount($id), 5),
            'comment' => $this->comment->CommentByOwnArticleList($id, $p->start, $p->limit),
            'article' => $this->comment->article($id),
            'id' => $id
        ]);
    }

    public function CommentCreate()
    {
        $this->comment->CommentCreate();
    }

    public function CommentDelete($id)
    {
        $this->comment->CommentDelete($id);
    }

    public function CommentDestroy($id)
    {
        $this->comment->CommentDestroy($id);
    }
}
