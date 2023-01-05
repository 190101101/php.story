<?php 

namespace modulus\main\Home\model;
use core\model;
use library\cookie;

class HomeModel extends model
{
    public function ArticleCount()
    {
        return count($this->db->sql("
            SELECT article_id FROM article
            INNER JOIN user ON user.user_id = article.user_id
            WHERE article.article_type = 1 
            AND article.article_status = 1
            ORDER BY article.article_id DESC", 2)
        );
    }

    public function ArticleList($start, $limit)
    {
        return $this->db->t2where('article', 'user', 
            "article.article_status = 1 AND article.article_type = 1
            ORDER BY article.article_id DESC LIMIT {$start}, {$limit}", [], 2, 2);
    }

    public function mode($mode)
    {
        $mode == 1
            ? cookie::create('css_mode', 2)
            : cookie::create('css_mode', 1);
        $this->return->referer();
    }
}

