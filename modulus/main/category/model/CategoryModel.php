<?php 

namespace modulus\main\category\model;
use core\model;
use library\cookie;

class CategoryModel extends model
{
    public function CategoryCount()
    {
        return $this->db->t1count('category', 'category_id > 0', [])->count;
    }

    public function CategoryList($start, $limit)
    {
        return $this->db->t1where('category', "category_id > 0
            ORDER BY category.category_id DESC LIMIT {$start}, {$limit}", [], 2, 2);
    }

    public function category()
    {
        return $this->db->t1where('category', "category.category_status = 1", [], 2, 2);
    }
}

