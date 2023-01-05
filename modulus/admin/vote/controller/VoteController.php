<?php 

namespace modulus\admin\vote\controller;
use modulus\admin\vote\model\VoteModel;
use core\controller;
use pagination;

class VoteController extends controller
{
    public $vote;
    
    public function __construct()
    {
        $this->vote = new VoteModel();
        $this->page = new pagination();
    }

    public function vote()
    {
        $this->layout('panel', 'admin', 'vote', 'vote', [
            'page' => $p = $this->page->page($this->vote->voteCount(), 5),
            'vote' => $this->vote->voteList($p->start, $p->limit),
            'column' => $this->vote->voteColumn(),
        ]);
    }

    public function show($id)
    {
        $this->layout('panel', 'admin', 'vote', 'show', [
            'vote' => $this->vote->voteShow($id),
            'column' => $this->vote->voteColumn(),
        ]);
    }

    public function voteDelete($id)
    {
        $this->vote->voteDelete($id);
    }

    public function voteDestroy($id)
    {
        $this->vote->voteDestroy($id);
    }

    public function VoteTruncate()
    {
        $this->vote->VoteTruncate();
    }

    public function voteSearchEngine()
    {
        $this->vote->voteSearchEngine();
    }

    public function voteSearch($key, $value)
    {
        $this->layout('panel', 'admin', 'vote', 'search', [
            'page' => $p = $this->page->page($this->vote->voteSearchCount($key, $value), 10),
            'vote' => $this->vote->voteSearch($key, $value, $p->start, $p->limit),
            'column' => $this->vote->voteColumn(),
            'search' => (object) ['key' => $key, 'value' => $value],
        ]);
    }
}
