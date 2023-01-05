<?php 

namespace modulus\admin\category\model;
use core\model;
use \library\error;
use \Valitron\Validator as v;
use old;
use User;

class CategoryModel extends model
{
    public function categoryColumn()
    {        
        return $this->db->columns('category');
    }    
    
    #
    public function category()
    {
        return $this->db->t1where('category', "category_id>0", [], 2);
    }

    #
	public function categoryCount()
    {
        return $this->db->t1count('category', "category_id > 0", [])->count;
    }    

    public function categoryList($start, $limit)
    {
        return $this->db->t1where('category', "category_id > 0
            ORDER BY category_id DESC LIMIT {$start}, {$limit}", [], 2, 2);
    }
   
    #
    public function CategoryShow($id)
    {
        return $this->db->t1where('category', "category_id=?", [$id]) ?:
            $this->return->code(404)->return('not_found')->get()->referer();
    }

    public function categoryCreate()
    {
        $http1 = 'panel/category/create';

        $form = [
            'category_title',
            'category_text',
        ];

        #array diff keys
        array_different($form, $_POST) ?: 
            $this->return->code(404)->return('error_form')->get()->referer();

        #peel tags of array
        $data = peel_tag_array($_POST);
        old::create($data);

        #check via valitron
        $v = new v($data);

        $v->rule('required', 'category_title');
        $v->rule('required', 'category_text');

        $v->rule('lengthMin', 'category_title', 3);
        $v->rule('lengthMin', 'category_text', 3);

        $v->rule('lengthMax', 'category_title', 20);
        $v->rule('lengthMax', 'category_text', 200);

        error::valitron($v, $http1);

        #if not found category
        $create = $this->db->create('category', $data);

        $create['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->referer();
        
        old::delete($data);

        #unset variables
        unset($http1); unset($data); unset($_POST); unset($v); unset($form);

        $this->return->code(200)->return('success')->get()->referer();
    }

    public function categoryUpdate()
    {
        $form = [
            'category_title',
            'category_text',
            'category_created',
            'category_id',
        ];

        #array diff keys
        array_different($form, $_POST) ?: 
            $this->return->code(404)->return('error_form')->get()->referer();

        #peel tags of array
        $data = peel_tag_array($_POST);
        old::create($data);

        #check via valitron
        $http1 = "panel/category/update/{$data['category_id']}";
        $v = new v($data);

        $v->rule('required', 'category_title');
        $v->rule('required', 'category_text');
        $v->rule('required', 'category_created');

        $v->rule('lengthMin', 'category_title', 3);
        $v->rule('lengthMin', 'category_text', 3);

        $v->rule('lengthMax', 'category_title', 20);
        $v->rule('lengthMax', 'category_text', 300);

        error::valitron($v, $http1);

        #if not found category
        $data += ['category_updated' => date('Y-m-d H:i:s')];
        $update = $this->db->update('category', $data, ['id' => 'category_id']);

        $update['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->referer();
        
        old::delete($data);
        
        #unset variables
        unset($http1); unset($data); unset($_POST); unset($v); unset($form);

        $this->return->code(200)->return('success')->get()->referer();
    }

    public function categoryDelete($id)
    {
        $category = $this->db->t1where('category', 'category_id != 1 && category_id=?', [$id]) ?: 
            $this->return->code(404)->return('not_found')->json();

        $this->db->update('article', [
            'category_id' => $category->category_id, 
            'article.category_id' => 1, 
        ], ['id' => 'category_id', 'p2' => 'category_id']); 
        
        $delete = $this->db->delete('category', [
            'category_id' => $category->category_id
        ], ['id' => 'category_id']);

        $delete['status'] == TRUE ?:
            $this->return->code(404)->return('error')->json();

        unset($id); unset($delete); unset($category);

        $this->return->code(200)->return('success')->json();
    }

    public function categoryDestroy($id)
    {
        $http1 = 'panel/category/page/1';
        
        $category = $this->db->t1where('category', 'category_id=?', [$id]) ?: 
            $this->return->code(404)->return('not_found')->get()->http($http1);

        $this->db->update('article', [
            'category_id' => $category->category_id, 
            'article.category_id' => 1, 
        ], ['id' => 'category_id', 'p2' => 'category_id']); 
        
        $delete = $this->db->delete('category', [
            'category_id' => $category->category_id
        ], ['id' => 'category_id']);

        $delete['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->http($http1);

        unset($id); unset($delete); unset($category);

        $this->return->code(200)->return('success')->get()->http($http1);
    }

    public function categorySearchEngine()
    {
        $http1 = 'panel/category/search/data/';
        $http2 = 'panel/category/page/1';

        $form = [
            'page',
            'field_key',
            'field_value',
        ];

        #array diff keys
        array_different($form, $_GET) ?: 
            $this->return->code(404)->return('error_form')->get()->referer();

        #peel tags of array
        $data = peel_tag_array($_GET);
        $data += ['page_key' => 'panel/category/search/key/value'];

        #valitron
        $v = new v($data);

        $v->rule('required', 'page');
        $v->rule('required', 'field_key');
        $v->rule('required', 'field_value');

        $v->rule('lengthMin', 'field_value', 1);
        $v->rule('lengthMin', 'field_key', 2);

        $v->rule('lengthMax', 'field_value', 20);
        $v->rule('lengthMax', 'field_key', 100);

        $v->rule('equals', 'page', 'page_key');

        error::valitron($v, $http2);        
        
        $this->return->http("{$http1}{$data['field_key']}/{$data['field_value']}/page/1");
    }

    public function categorySearchCount($key, $value)
    {
        $http1 = 'panel/category/page/1';
        return $this->db->t1count('category', "{$key} LIKE ? ", [
            "%{$value}%"
        ])->count ?: $this->return->code(404)->return('not_found')->get()->http($http1);
    }

    public function categorySearch($key, $value, $start, $limit)
    {
        return $this->db->t1where('category', "{$key} LIKE ? 
            ORDER BY category.category_id DESC LIMIT {$start}, {$limit}", [
            "%{$value}%"
        ], 2, 2);
    }
}

