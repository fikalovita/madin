<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_admin extends CI_Model
{
    public function tambah_guru($data)
    {
        return $this->db->insert('guru', $data);
    }
    public function get_all_siswa()
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas');
        $this->db->join('guru', 'guru.id_guru = kelas.id_guru');
        return $this->db->get();
    }
    public function get_guru()
    {
        $this->db->select('*');
        $this->db->from('guru');
        return $this->db->get();
    }
    public function hapus_guru($id_guru)
    {
        $this->db->where('id_guru', $id_guru);
        $this->db->delete('guru');
    }
    public function tambah_kelas($data)
    {
        return $this->db->insert('kelas', $data);
    }
    public function get_kelas()
    {
        $this->db->select('*');
        $this->db->from('kelas');
        $this->db->join('guru', 'guru.id_guru = kelas.id_guru');
        return $this->db->get();
    }
    public function tambah_siswa($data)
    {
        $this->db->insert('siswa', $data);
        // $id = $this->db->insert_id();
        // $this->db->query("INSERT INTO nilai (id_siswa) VALUES ($id)");
    }
    public function get_siswa($id_kelas)
    {
        $this->db->select('*');
        $this->db->where('id_kelas', $id_kelas);
        $this->db->from('siswa');
        return $this->db->get();
    }
    public function tampil_pelajaran()
    {
        $this->db->select('*');
        $this->db->from('pelajaran');
        $this->db->join('kelas', 'kelas.id_kelas = pelajaran.id_kelas');
        $this->db->where('pelajaran.id_kelas', $this->uri->segment(3));
        return $this->db->get();
    }
    public function tambah_pelajaran($data, $kelas)
    {
        $this->db->insert('pelajaran', $data);
        $this->db->query("INSERT INTO mengajar (id_kelas) VALUES ($kelas)");
    }

    public function hapus_pelajaran($id_pelajaran)
    {
        $this->db->where('id_pelajaran', $id_pelajaran);
        $this->db->delete('pelajaran');
    }
    public function get_guru_byId($id_guru)
    {
        $result = $this->db->where('id_guru', $id_guru)->get('guru');

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function ubah_guru($data, $id_guru)
    {
        $this->db->where('id_guru', $id_guru);
        $this->db->update('guru', $data);
    }

    public function hapus_kelas($id_kelas)
    {
        $this->db->where('id_kelas', $id_kelas);
        $this->db->delete('kelas');
    }
    public function ubah_kelas($id_kelas, $data)
    {
        $this->db->where('id_kelas', $id_kelas);
        $this->db->update('kelas', $data);
    }

    public function pindah_kelas($data, $id_siswa)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa', $data);
    }
    public function nilai_pelajaran($nisn_siswa)
    {
        return $this->db->insert('nilai', $nisn_siswa);
    }
    public function get_siswa_byId($id_siswa)
    {
        $result = $this->db->where('id_siswa', $id_siswa)->get('siswa');

        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }
    public function ubah_siswa($data, $id_siswa)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa', $data);
    }
    public function hapus_siswa($id_siswa)
    {
        $this->db->where($id_siswa);
        $this->db->delete('siswa');
    }
    public function get_all_kelas()
    {
        $this->db->select('*');
        $this->db->from('kelas');
        $this->db->join('guru', 'guru.id_guru = kelas.id_guru');
        return $this->db->get();
    }

    public function get_mengajar($kelas)
    {
        $this->db->select('*');
        $this->db->from('mengajar');
        $this->db->join('pelajaran', 'pelajaran.kode_pelajaran = mengajar.kode_pelajaran');
        $this->db->where('pelajaran.id_kelas', $kelas);
        return $this->db->get();
    }
    public function get_pelajaran()
    {
        $this->db->select('*');
        $this->db->from('pelajaran');
        return $this->db->get();
    }
    public function ubah_mengajar($data, $id_mengajar)
    {
        $this->db->where('id_mengajar', $id_mengajar);
        $this->db->update('mengajar', $data);
    }
    public function get_mengajar_all()
    {
        $this->db->select('*');
        $this->db->from('mengajar');
        $this->db->join('pelajaran', 'pelajaran.id_pelajaran = mengajar.id_pelajaran');
        return $this->db->get();
    }
    public function hapus_ajar($data, $id_mengajar)
    {
        $this->db->where('id_mengajar', $id_mengajar);
        $this->db->update('mengajar', $data);
    }

    public function list_siswa_kelas($id_kelas)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->where('siswa.id_kelas', $id_kelas);
        return $this->db->get();
    }

    public function get_nilai_siswa($id_siswa)
    {
        $this->db->select('*');
        $this->db->from('pelajaran');
        $this->db->join('nilai', 'nilai.id_pelajaran = pelajaran.id_pelajaran');
        $this->db->join('mengajar', 'mengajar.id_pelajaran = pelajaran.id_pelajaran');
        $this->db->where('nilai.id_siswa', $id_siswa);
        return $this->db->get();
    }

    public function kelas()
    {
        $this->db->select('*');
        $this->db->from('kelas');
        return $this->db->get();
    }

    public function siswa()
    {
        $this->db->select('*');
        $this->db->from('siswa');
        return $this->db->get();
    }

    public function pelajaran()
    {
        $this->db->select('*');
        $this->db->from('pelajaran');
        return $this->db->get();
    }

    public function ubah_pelajaran($data, $id_pelajaran)
    {
        $this->db->where('id_pelajaran', $id_pelajaran);
        $this->db->update('pelajaran', $data);
    }

    public function get_info()
    {
        $this->db->select('*');
        $this->db->from('info');
        return $this->db->get();
    }

    public function update_info($data, $id_info)
    {
        $this->db->where('id_info', $id_info);
        $this->db->update('info', $data);
    }

    public function siswa_excel($data)
    {
        return $this->db->insert_batch('siswa', $data);
    }
    public function guru_excel($data)
    {
        return $this->db->insert_batch('guru', $data);
    }
    public function pelajaran_excel($data, $data2)
    {
        $this->db->insert_batch('pelajaran', $data);
        $this->db->insert_batch('mengajar', $data2);
    }
    public function buka_kunci($id_nilai, $data)
    {
        $this->db->where('id_nilai', $id_nilai);
        $this->db->update('nilai', $data);
    }

    public function guru_kelas()
    {
        $this->db->select('*');
        $this->db->from('guru');
        $this->db->join('kelas', 'kelas.id_guru = guru.id_guru');
        return $this->db->get();
    }
}
