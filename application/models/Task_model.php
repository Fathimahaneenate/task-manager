<?php
class Task_model extends CI_Model {

    
public function get_tasks($status = null, $sort = null)
{
    $this->db->from('tasks');

    // STATUS FILTER
    if (!empty($status)) {
        $this->db->where('status', $status);
    }

    // SORT LOGIC
    if ($sort == 'due_date') {
        $this->db->order_by('due_date', 'ASC');
    }
    elseif ($sort == 'priority') {
        $this->db->order_by("FIELD(priority,'high','medium','low')", NULL, FALSE);
    }
    else {
        $this->db->order_by('id', 'DESC');
    }
 $query = $this->db->get();
    return $query->result();
   
    // return $this->db->get()->result();
}


    public function insert_task($data) {
        return $this->db->insert('tasks', $data);
    }

    public function mark_complete($id) {
        return $this->db->where('id', $id)
                        ->update('tasks', ['status' => 'completed']);
    }

    public function get_counts() {
        $total = $this->db->count_all('tasks');

        $completed = $this->db->where('status', 'completed')
                              ->count_all_results('tasks');

        $pending = $this->db->where('status', 'pending')
                            ->count_all_results('tasks');

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending
        ];
    }

//     public function delete_task($id) {
//     return $this->db->delete('tasks', ['id' => $id]);
// }
}