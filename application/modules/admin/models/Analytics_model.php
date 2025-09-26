<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Analytics_model extends CI_Model {
    protected $table;
    public function __construct(){
        parent::__construct();
        $this->load->database(); 
        $this->table = 'db_tabprefixposts'; // ✅ الجدول الصحيح
    }

    // ==== Distinct Values with Names ====
    public function distinct_brands(){
        return $this->db->select('DISTINCT(b.id) as id, b.name')
                        ->from($this->table.' p')
                        ->join('db_tabprefixbrandmodels b','b.id = p.brand','left')
                        ->where('b.type','brand')
                        ->order_by('b.name','ASC')
                        ->get()->result_array();
    }

    public function distinct_models(){
        return $this->db->select('DISTINCT(m.id) as id, m.name')
                        ->from($this->table.' p')
                        ->join('db_tabprefixbrandmodels m','m.id = p.model','left')
                        ->where('m.type','model')
                        ->order_by('m.name','ASC')
                        ->get()->result_array();
    }

    public function distinct_states(){
        return $this->db->select('DISTINCT(s.id) as id, s.name')
                        ->from($this->table.' p')
                        ->join('db_tabprefixlocations s','s.id = p.state','left')
                        ->where('s.type','state')
                        ->order_by('s.name','ASC')
                        ->get()->result_array();
    }

    public function distinct_cities(){
        return $this->db->select('DISTINCT(c.id) as id, c.name')
                        ->from($this->table.' p')
                        ->join('db_tabprefixlocations c','c.id = p.city','left')
                        ->where('c.type','city')
                        ->order_by('c.name','ASC')
                        ->get()->result_array();
    }

    // ==== Count with Filters ====
    public function count_total($filters = []){
        $this->apply_filters($filters);
        return $this->db->count_all_results($this->table);
    }

    public function count_group_by($groupby, $filters = []){
        $this->apply_filters($filters);

        $col = $groupby;
        if($groupby == 'brand'){
            $this->db->select('b.name as label, COUNT(*) as total', false)
                     ->from($this->table.' p')
                     ->join('db_tabprefixbrandmodels b','b.id = p.brand','left')
                     ->group_by('b.name');
        }
        elseif($groupby == 'model'){
            $this->db->select('m.name as label, COUNT(*) as total', false)
                     ->from($this->table.' p')
                     ->join('db_tabprefixbrandmodels m','m.id = p.model','left')
                     ->group_by('m.name');
        }
        elseif($groupby == 'state'){
            $this->db->select('s.name as label, COUNT(*) as total', false)
                     ->from($this->table.' p')
                     ->join('db_tabprefixlocations s','s.id = p.state','left')
                     ->group_by('s.name');
        }
        elseif($groupby == 'city'){
            $this->db->select('c.name as label, COUNT(*) as total', false)
                     ->from($this->table.' p')
                     ->join('db_tabprefixlocations c','c.id = p.city','left')
                     ->group_by('c.name');
        }

        $this->db->order_by('total','DESC');
        return $this->db->get()->result_array();
    }

    public function counts_by_date($period = 'day', $filters = []){
        $this->apply_filters($filters);

        if($period == 'month'){
            $this->db->select("FROM_UNIXTIME(create_time, '%Y-%m') as date, COUNT(*) as total", false)
                     ->from($this->table)
                     ->group_by("FROM_UNIXTIME(create_time, '%Y-%m')")
                     ->order_by("date","ASC");
        } else {
            $this->db->select("FROM_UNIXTIME(create_time, '%Y-%m-%d') as date, COUNT(*) as total", false)
                     ->from($this->table)
                     ->group_by("FROM_UNIXTIME(create_time, '%Y-%m-%d')")
                     ->order_by("date","ASC");
        }
        return $this->db->get()->result_array();
    }

    // ==== Filters ====
    protected function apply_filters($filters){
        if(!empty($filters['brand'])) $this->db->where('brand', $filters['brand']);
        if(!empty($filters['model'])) $this->db->where('model', $filters['model']);
        if(!empty($filters['state'])) $this->db->where('state', $filters['state']);
        if(!empty($filters['city'])) $this->db->where('city', $filters['city']);
        if(isset($filters['min_price']) && $filters['min_price'] !== '') $this->db->where('price >=', (float)$filters['min_price']);
        if(isset($filters['max_price']) && $filters['max_price'] !== '') $this->db->where('price <=', (float)$filters['max_price']);
        if(!empty($filters['date_from'])) $this->db->where("FROM_UNIXTIME(create_time, '%Y-%m-%d') >=", $filters['date_from']);
        if(!empty($filters['date_to'])) $this->db->where("FROM_UNIXTIME(create_time, '%Y-%m-%d') <=", $filters['date_to']);
    }
}
