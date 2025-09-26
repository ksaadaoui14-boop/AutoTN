<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Analytics extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Analytics_model');
    }

    public function index(){
        $data = [];
        $data['brands'] = $this->Analytics_model->distinct_brands();
        $data['models'] = $this->Analytics_model->distinct_models();
        $data['states'] = $this->Analytics_model->distinct_states();
        $data['cities'] = $this->Analytics_model->distinct_cities();

        $this->load->view('default/content/analytics_view', $data);
    }

    public function ajax_data(){
        $filters = $this->input->get();
        $filters = array_map('trim', $filters);

        $total   = $this->Analytics_model->count_total($filters);
        $by_brand= $this->Analytics_model->count_group_by('brand', $filters);
        $by_city = $this->Analytics_model->count_group_by('city', $filters);
        $by_state= $this->Analytics_model->count_group_by('state', $filters);
        $by_date = $this->Analytics_model->counts_by_date('day', $filters);

        $resp = [
            'total'    => $total,
            'by_brand' => $by_brand,
            'by_city'  => $by_city,
            'by_state' => $by_state,
            'by_date'  => $by_date
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resp);
    }
}
