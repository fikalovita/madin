<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_admin extends CI_Model
{

    public function tambah_guru($data)
    {
        return $this->db->insert('guru', $data);
    }
    public function get_guru()
    {
        $this->db->select('*');
        $this->db->from('guru');
        return $this->db->get();
    }
}
