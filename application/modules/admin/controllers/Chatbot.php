<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatbot extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {
        $this->load->view('chatbot_view');
    }

    public function ask() {
        $msg = strtolower($this->input->post('message'));

        // مثال بسيط: قواعد تحويل أسئلة إلى SQL
        if (strpos($msg, 'peugeot') !== false) {
            $query = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE brand='Peugeot'");
            $result = $query->row()->total;
            echo "عدد سيارات Peugeot هو: ".$result;
        }
        elseif (strpos($msg, 'تونس') !== false) {
            $query = $this->db->query("SELECT COUNT(*) as total FROM cars WHERE state='Tunis'");
            $result = $query->row()->total;
            echo "عدد السيارات في ولاية تونس هو: ".$result;
        }
        else {
            echo "المعذرة، ما فهمتش سؤالك 😅. جرب تكتب: Peugeot أو تونس...";
        }
    }
}
