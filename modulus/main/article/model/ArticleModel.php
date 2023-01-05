<?php 

namespace modulus\main\article\model;
use core\model;
use \library\error;
use \Valitron\Validator as v;
use old;
use User;
use Article;

class ArticleModel extends model
{
    public function ArticleColumn()
    {
        return $this->db->columns('article');
    }    

    public function ArticleRead($id)
    {
        $article = $this->db->p3where('article', 'user', 'category', 'article_type=1 AND article_status=1 AND article_id=?', [$id]) ?:
            $this->return->code(404)->return('not_found')->get()->http();

        if(!Article::review($article->article_id)){
            $this->db->increment('article', 'article_view', $article->article_id);
            Article::create($article->article_id);
        }
        return $article;
    }

	public function ArticleCount()
    {
	    return $this->db->t1count('article', 'article_status=1 AND user_id=?', [
            User::user_id()
        ])->count;
    }    

    public function ArticleList($start, $limit)
    {
        return $this->db->t1where('article', "article_status=1 AND user_id=?
            ORDER BY article_id DESC LIMIT {$start}, {$limit}", [User::user_id()], 2, 2);
    }

    #
    public function UserById($id)
    {
        return $this->db->t1where('user', "user_id=?", [$id]);
    }

    #
    public function CategoryById($id)
    {
        return $this->db->t1where('category', "category_id=?", [$id]);
    }

    #
    public function category()
    {
        return $this->db->t1where('category', "category_id > 0", [], 2);
    }

    #
    public function ArticleByUserId($id)
    {
        return $this->db->t2where('article', 'category', "article.user_id=? AND article_status=1 AND article_id = ?", [User::user_id(), $id]) 
        ?: $this->return->code(404)->return('not_found', 'article')
            ->get()->http('article/page/1');
    }

    public function ArticleCreate()
    {
        $http1 = 'article/create';

        $form = [
            'article_title',
            'article_text',
            'article_type',
            'category_id',
        ];

        #array diff keys
        array_different($form, $_POST) ?: 
            $this->return->code(404)->return('error_form')->get()->referer();

        #peel tags of array
        $data = peel_tag_array($_POST);

        if(isset($data['cancel']) && $data['cancel'] == 1){
            old::delete($data);
            $this->return->code(200)->return('success')->get()->referer();
        }

        old::create($data);

        #valitron
        $v = new v($data);

        $v->rule('required', 'article_title');
        $v->rule('required', 'article_text');

        $v->rule('lengthMin', 'article_title', 3);
        $v->rule('lengthMin', 'article_text', 100);

        $v->rule('lengthMax', 'article_title', 20);
        $v->rule('lengthMax', 'article_text', 30000);

        error::valitron($v, $http1);

        #create
        $data += ['user_id' => User::user_id()];
        $data += ['article_slug' => seo(strtolower($data['article_title']))];
        $create = $this->db->create('article', $data, 1);

        $create['status'] == TRUE ?: 
            $this->return->code(404)->return('error')->get()->referer();

        old::delete($data);

        #unset variables
        unset($data); unset($_POST); unset($v); unset($form);

        $this->return->code(200)->return('success')->get()->referer();

    }

    public function ArticleUpdate()
    {
        $form = [
            'article_id',
            'article_title',
            'article_text',
            'article_type',
            'category_id',
        ];

        #array diff keys
        array_different($form, $_POST) ?: 
            $this->return->code(404)->return('error_form')->get()->referer();

        #peel tags of array
        $data = peel_tag_array($_POST);

        #check via valitron
        $v = new v($data);

        $v->rule('required', 'article_title');
        $v->rule('required', 'article_text');

        $v->rule('lengthMin', 'article_title', 3);
        $v->rule('lengthMin', 'article_text', 100);

        $v->rule('lengthMax', 'article_title', 20);
        $v->rule('lengthMax', 'article_text', 30000);

        $http1 = "article/update/{$data['article_id']}";
        error::valitron($v, $http1);

        #article by user id
        $this->ArticleByUserId($data['article_id']);

        #if not found article
        $data += ['user_id' => User::user_id()];
        $data += ['article_updated' => date('Y-m-d H:i:s')];
        $data += ['article_slug' => seo(strtolower($data['article_title']))];
        
        $update = $this->db->update('article', $data, ['id' => 'article_id']);

        $update['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->referer();
        
        #unset variables
        unset($http1); unset($data); unset($_POST); unset($v); unset($form);

        $this->return->code(200)->return('success')->get()->referer();
    }

    public function ArticleType($article_id)
    {
        $article = $this->db->t1where('article', 'user_id=? AND article_id=?', [
            User::user_id(), $article_id
        ]) ?: $this->return->code(404)->return('not_found')->json();

        $update = $this->db->update('article', [
            'article_id' => $article->article_id,
            'article_type' => $article->article_type == 1 ? 0 : 1,
        ], ['id' => 'article_id']);

        $update['status'] == TRUE
            ? $this->return->code(200)->return('success')->json()
            : $this->return->code(200)->return('error')->json();
    }

    public function ArticleDelete($id)
    {
        $article = $this->db->t1where('article', 'user_id=? AND article_id=?', [
            User::user_id(), $id
        ]) ?: $this->return->code(404)->return('not_found')->json();

        $update = $this->db->update('article', [
            'article_id' => $article->article_id,
            'article_status' => 0,
        ], ['id' => 'article_id']);

        $update['status'] == TRUE ?:
            $this->return->code(404)->return('error')->json();

        unset($id); unset($delete); unset($article);

        $this->return->code(200)->return('success')->json();
    }

    public function ArticleVote($vote, $id)
    {

        $http1 = "article/read/{$id}/page/1";

        $form = [
            'like',
            'dislike',
            'article_id',
        ];

        #in array
        in_array($vote, $form) ?: $this->return->code(404)->return('error_form')->get()->referer();

        #t1
        $article = $this->db->t1where('article', 'article_status=1 && article_type=1 && article_id=?', [$id]);
        
        #vote
        !$this->db->t1where('vote', 'user_id=? && article_id=?', [User::user_id(), $id]) 
            ?: $this->return->code(404)->return('already_vote')->get()->referer();

        $data = [
            'user_id' => User::user_id(),
            'article_id' => $id,
        ];

        $create = $this->db->create('vote', $data, 1);

        $update = $this->db->update('article', [
            'article_id' => $article->article_id,
            "article_{$vote}" => $article->{'article_'.$vote} + 1
        ], ['id' => 'article_id']);
        
        $create['status'] == TRUE || $update['status'] == TRUE ?: 
            $this->return->code(404)->return('error')->get()->referer();

        #unset variables
        unset($data); unset($_POST); unset($v); unset($form);

        $this->return->code(200)->return('success')->get()->http($http1);
    }

    public function ArticleSearchEngine()
    {        
        $http1 = 'article/search/data/';

        $form = [
            'page',
            'field_value',
        ];

        #array diff keys
        array_different($form, $_GET) ?: 
            $this->return->code(404)->return('error_form')->get()->http();

        #peel tags of array
        $data = peel_tag_array($_GET);
        $data += ['page_key' => 'article/search/key/value'];
        $data += ['field_key' => 'article.article_title'];

        #valitron
        $v = new v($data);

        $v->rule('required', 'page');
        $v->rule('required', 'page_key');
        $v->rule('required', 'field_key');
        $v->rule('required', 'field_value');

        $v->rule('lengthMin', 'field_value', 3);
        $v->rule('lengthMin', 'field_key', 3);

        $v->rule('lengthMax', 'field_value', 20);
        $v->rule('lengthMax', 'field_key', 100);

        $v->rule('equals', 'page', 'page_key');

        error::valitron($v);
        $seo = seo($data['field_value']);
        
        $this->return->http("{$http1}{$seo}/page/1");
    }

    public function articleSearchCount($value)
    {
        return count($this->db->t2where('article', 'user', " article.article_status=1 AND article.article_type=1 AND article.article_slug LIKE ? 
            ORDER BY article.article_id DESC", ["%{$value}%"], 2))
        ?: $this->return->code(404)->return('not_found')->get()->http();
    }

    public function ArticleSearch($value, $start, $limit)
    {
        return $this->db->t2where('article', 'user', " article.article_status=1 AND article.article_type=1 AND article.article_slug LIKE ? 
            ORDER BY article.article_id DESC LIMIT {$start}, {$limit}", ["%{$value}%"], 2, 2);
    }

    /**/
    public function articleByUserCount($id)
    {
        return count($this->db->t2where('article', 'user', " article.article_status=1 AND article.article_type=1 AND user.user_id=? 
            ORDER BY article.article_id DESC", [$id], 2))
        ?: $this->return->code(404)->return('not_found')->get()->http();
    }

    public function articleByUser($id, $start, $limit)
    {
        return $this->db->t2where('article', 'user', " article.article_status=1 AND article.article_type=1 AND user.user_id=? 
            ORDER BY article.article_id DESC LIMIT {$start}, {$limit}", [$id], 2, 2);
    }

    public function ArticleSimilar()
    {
        return Array_chunk($this->db->t1where("article", 
            "article_id < ? AND article_status=1 ORDER BY article_id DESC LIMIT 6", [
            $this->db->t1count('article', 'article_status=1', [])->count
        ], 2) ?: $this->db->t1where("article", 
            "article_id > 0 AND article_status=1 ORDER BY article_view ASC LIMIT 6", [
        ], 2), 3);
    }

    public function CommentCountByArticle($id)
    {
        return $this->db->t2count('comment', 'user', "article_id=?", [$id])->count;
    }    

    public function CommentByArticle($id, $start, $limit)
    {
        return $this->db->t2where('comment', 'user', "article_id=?
            ORDER BY comment_id DESC LIMIT {$start}, {$limit}", [$id], 2, 2);
    }

    public function ByCategoryCount($id)
    {
        return $this->db->p3count('article', 'user', 'category', 
            'article.article_type = 1 AND 
            article.article_status = 1 AND category.category_id=?', [$id])->count;
    }

    public function ByCategory($id, $start, $limit)
    {
        return $this->db->p3where('article', 'user', 'category', 
            "article.article_type = 1 AND article.article_status = 1 AND 
            category.category_id=? ORDER BY article.article_id 
            DESC LIMIT {$start}, {$limit}", [$id], 2, 2);
    }
}

