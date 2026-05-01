<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property Task_model $Task_model
 */
class Tasks extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Task_model');
        $this->load->library('session');
    }

    public function index()
    {

        $status = $this->input->get('status');

        $sort   = $this->input->get('sort');
        if ($status === null || $status === '') {
            $status = 'pending';
        }

        if ($status == 'all') {
            $status = null;
        }
        $data['tasks'] = $this->Task_model->get_tasks($status, $sort);
        $data['counts'] = $this->Task_model->get_counts();
        $this->load->view('tasks_view', $data);
    }
    public function add()
    {
        $title = $this->input->post('title');
        $due_date = $this->input->post('due_date');
        $priority = $this->input->post('priority');

        if (strtotime($due_date) < time()) {
            $this->session->set_flashdata('error', 'Past date not allowed');
            redirect('tasks');
            return;
        }

        $this->Task_model->insert_task([
            'title' => $title,
            'due_date' => $due_date,
            'priority' => $priority,
            'status' => 'pending'
        ]);

        redirect('tasks');
    }
    public function complete($id)
    {
        $this->Task_model->mark_complete($id);
        redirect('tasks');
    }
    // public function delete($id)
    // {
    //     $this->Task_model->delete_task($id);
    //     redirect('tasks');
    // }
}
