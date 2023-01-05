<?php 

namespace modulus\main\article\controller;
use modulus\main\article\model\ArticleModel;
use core\controller;
use pagination;

class ArticleController extends controller
{
    public $article;
    
    public function __construct()
    {
        $this->article = new ArticleModel();
        $this->page = new pagination();
    }

    public function article()
    {
        $this->layout('general', 'main', 'article', 'article', [
            'page' => $p = $this->page->page($this->article->ArticleCount(), 5),
            'article' => $this->article->ArticleList($p->start, $p->limit),
            'column' => $this->article->ArticleColumn()
        ]);
    }

    public function show($id)
    {
        $this->layout('general', 'main', 'article', 'show', [
            'article' => $this->article->ArticleByUserId($id),
        ]);
    }

    public function create()
    {
        $this->layout('general', 'main', 'article', 'create', [
            'category' => $this->article->category()
        ]);
    }

    public function ArticleCreate()
    {
        $this->article->ArticleCreate();
    }

    public function update($id)
    {
        $this->layout('general', 'main', 'article', 'update', [
            'article' => $this->article->ArticleByUserId($id),
            'category' => $this->article->category()
        ]);
    }

    public function ArticleUpdate()
    {
        $this->article->ArticleUpdate();
    }

    public function ArticleType($id)
    {
        $this->article->ArticleType($id);
    }

    public function ArticleDelete($id)
    {
        $this->article->ArticleDelete($id);
    }

    public function ArticleSearchEngine()
    {
        $this->article->ArticleSearchEngine();
    }

    public function ArticleVote($vote, $id)
    {
        $this->article->ArticleVote($vote, $id);
    }

    public function ArticleSearch($value)
    {        
        $this->layout('main', 'main', 'article', 'search', [
            'page' => $p = $this->page->page($this->article->articleSearchCount($value), 10),
            'article' => $this->article->ArticleSearch($value, $p->start, $p->limit),
            'search' => (object) ['value' => $value],
        ]);
    }

    public function articleByUser($id)
    {
        $this->layout('main', 'main', 'article', 'byUser', [
            'page' => $p = $this->page->page($this->article->articleByUserCount($id), 10),
            'article' => $this->article->articleByUser($id, $p->start, $p->limit),
            'user' => $this->article->UserById($id)
        ]);
    }
    
    public function ArticleRead($id)
    {
        $this->layout('main', 'main', 'article', 'read', [
            'article' => $this->article->ArticleRead($id),
            'similar' => $this->article->ArticleSimilar(),
            'page' => $p = $this->page->page($this->article->CommentCountByArticle($id), 5),
            'comment' => $this->article->CommentByArticle($id, $p->start, $p->limit),
        ]);
    }

    public function ByCategory($id)
    {
        $this->layout('main', 'main', 'article', 'ByCategory', [
            'page' => $p = $this->page->page($this->article->ByCategoryCount($id), 12),
            'article' => $this->article->ByCategory($id, $p->start, $p->limit),
            'categories' => $this->article->category(),
            'category' => $this->article->CategoryById($id),
            'id' => $id
        ]);
    }
}
