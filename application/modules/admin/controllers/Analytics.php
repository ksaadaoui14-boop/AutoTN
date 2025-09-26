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
    public function chatbot_api()
{
    $this->load->database();
    $question = strtolower($this->input->post('question'));

    if(strpos($question, 'عدد السيارات') !== false) {
        $count = $this->db->count_all('posts'); 
        echo "عندك حالياً $count سيارة في النظام.";
    }
    elseif(strpos($question, 'medenine') !== false) {
        $this->db->where('state', 'Medenine');
        $count = $this->db->count_all_results('posts');
        echo "عدد السيارات في Medenine هو: $count.";
    }
    else {
        echo "ملا حكاية 🙂 ما فهمتش سؤالك. جرّب تقول: 'عدد السيارات' أو 'السيارات في Medenine'";
    }
}

}
