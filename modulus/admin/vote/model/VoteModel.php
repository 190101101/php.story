<?php 

namespace modulus\admin\vote\model;
use core\model;
use \library\error;
use \Valitron\Validator as v;
use old;

class VoteModel extends model
{
    public function voteColumn()
    {
        return $this->db->columns('vote');
    }    

	public function voteCount()
    {
	    return $this->db->t1count('vote', 'vote_id > 0', [])->count;
    }    

    public function voteList($start, $limit)
    {
        return $this->db->t1where('vote', "vote_id > 0
            ORDER BY vote_id DESC LIMIT {$start}, {$limit}", [], 2, 2);
    }

    public function voteShow($id)
    {
        return $this->db->t1where('vote', "vote_id = ?", [$id]) ?:
            $this->return->code(404)->return('not_found')->json();
    }

    public function VoteTruncate()
    {
        $drop = $this->db->drop('vote');

        $drop['status'] == TRUE 
            ? $this->return->code(200)->return('success')->get()->referer()
        : $this->return->code(404)->return('error')->get()->referer();
    }

    public function voteDelete($id)
    {
        $vote = $this->db->t1where('vote', 'vote_id=?', [$id]) ?: 
            $this->return->code(404)->return('not_found')->json();

        $delete = $this->db->delete('vote', [
            'vote_id' => $vote->vote_id
        ], ['id' => 'vote_id']);

        $delete['status'] == TRUE ?:
            $this->return->code(404)->return('error')->json();

        unset($id); unset($delete); unset($vote);

        $this->return->code(200)->return('success')->json();
    }

    public function voteDestroy($id)
    {
        $http1 = 'panel/vote/page/1';

        $vote = $this->db->t1where('vote', 'vote_id=?', [$id]) ?: 
            $this->return->code(404)->return('not_found')->get()->http($http1);

        $delete = $this->db->delete('vote', [
            'vote_id' => $vote->vote_id
        ], ['id' => 'vote_id']);

        $delete['status'] == TRUE ?:
            $this->return->code(404)->return('error')->get()->http($http1);

        unset($id); unset($delete); unset($vote);

        $this->return->code(200)->return('success')->get()->http($http1);
    }

    /**/
    public function voteSearchEngine()
    {
        $http1 = 'panel/vote/search/data/';
        $http2 = 'panel/vote/page/1';

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
        $data += ['page_key' => 'panel/vote/search/key/value'];

        #valitron
        $v = new v($data);

        $v->rule('required', 'page');
        $v->rule('required', 'field_key');
        $v->rule('required', 'field_value');

        $v->rule('lengthMin', 'field_value', 1);
        $v->rule('lengthMin', 'field_key', 2);

        $v->rule('lengthMax', 'field_value', 20);
        $v->rule('lengthMax', 'field_key', 20);

        $v->rule('equals', 'page', 'page_key');

        error::valitron($v, $http2);        
        
        $this->return->http("{$http1}{$data['field_key']}/{$data['field_value']}/page/1");
    }

    public function voteSearchCount($key, $value)
    {
        $http1 = 'panel/vote/page/1';
        return $this->db->t1count('vote', "{$key} LIKE ? ", [
            "%{$value}%"
        ])->count ?: $this->return->code(404)->return('not_found')->get()->http($http1);
    }

    public function voteSearch($key, $value, $start, $limit)
    {
        return $this->db->t1where('vote', "{$key} LIKE ? 
            ORDER BY vote.vote_id DESC LIMIT {$start}, {$limit}", [
            "%{$value}%"
        ], 2, 2);
    }
}

