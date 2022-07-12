<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_guru extends CI_Model
{

    public function guru()
    {
        return $this->db->insert('guru');
    }

    public function get_nilai()
    {
        $this->db->select('*');
        $this->db->from('nilai');
        return $this->db->get();
    }

    public function get_siswa()
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->where('aktif', 0);
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        return $this->db->get();
    }

    public function get_pelajaran()
    {
        $this->db->select('*');
        $this->db->from('mengajar');
        $this->db->join('pelajaran', 'pelajaran.kode_pelajaran = mengajar.kode_pelajaran');
        $this->db->where('id_guru', $this->session->userdata('id_guru'));
        return $this->db->get();
    }
    public function get_mapel()
    {
        $this->db->select('*');
        $this->db->where('id_pelajaran', $this->uri->segment(3));
        $this->db->from('pelajaran');
        return $this->db->get();
    }
    public function input_nilai($data)
    {
        $this->db->update_batch('nilai', $data, 'nilai.id_siswa');
    }

    public function update_guru($id_guru, $data)
    {
        $this->db->where('id_guru', $id_guru);
        $this->db->update('guru', $data);
    }

    public function get_nilai_pelajaran($id_pelajaran)
    {
        $this->db->select('*');
        $this->db->from('nilai');
        $this->db->join('siswa', 'siswa.id_siswa = nilai.id_siswa');
        $this->db->join('pelajaran', 'pelajaran.id_pelajaran = nilai.id_pelajaran');
        $this->db->where('nilai.id_pelajaran', $id_pelajaran);
        $this->db->where('id_guru', $this->session->userdata('id_guru'));
        $this->db->where('kunci', 0);
        return $this->db->get();
    }
    public function get_lihat_nilai($id_pelajaran)
    {
        $this->db->select('*');
        $this->db->from('nilai');
        $this->db->join('siswa', 'siswa.id_siswa = nilai.id_siswa');
        $this->db->join('pelajaran', 'pelajaran.id_pelajaran = nilai.id_pelajaran');
        $this->db->where('nilai.id_pelajaran', $id_pelajaran);
        $this->db->where('id_guru', $this->session->userdata('id_guru'));
        $this->db->where('kunci', 1);
        return $this->db->get();
    }

    public function update_nilai($id_nilai, $data)
    {
        $this->db->where('id_nilai', $id_nilai);
        $this->db->update('nilai', $data);
    }

    public function kunci_nilai($id_pelajaran, $data)
    {
        $this->db->where('id_pelajaran', $id_pelajaran);
        $this->db->where('id_guru', $this->session->userdata('id_guru'));
        $this->db->update('nilai', $data);
    }

    public function presensi()
    {
        $this->db->select('*');
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        $this->db->from('siswa');
        return $this->db->get();
    }
    public function get_presensi()
    {
        $this->db->select('*');
        $this->db->from('presensi');
        $this->db->join('siswa', 'siswa.id_siswa = presensi.id_siswa');
        $this->db->where('presensi.id_kelas', $this->session->userdata('id_kelas'));

        return $this->db->get();
    }
    public function update_presensi($id_presensi, $data)
    {
        $this->db->where('id_presensi', $id_presensi);
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        $this->db->update('presensi', $data);
    }

    public function get_nilai_kunci($id_pelajaran)
    {
        $this->db->select('*');
        $this->db->from('nilai');
        $this->db->where('kunci', 0);
        $this->db->where('id_guru', $this->session->userdata('id_guru'));
        $this->db->where('id_pelajaran', $id_pelajaran);

        return $this->db->get();
    }
    public function kunci_absensi($data)
    {
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        $this->db->update('presensi', $data);
    }
    public function tingkatan($data, $id_siswa)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa', $data);
    }
    public function get_kunci_presensi()
    {
        $this->db->select('*');
        $this->db->from('presensi');
        $this->db->where('kunci', 0);
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        return $this->db->get();
    }
    public function cetak()
    {
        $this->db->select('*');
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        $this->db->where('aktif', 0);
        $this->db->from('siswa');
        return $this->db->get();
    }

    public function cetak_raport($id_siswa)
    {
        $this->db->select('*');
        $this->db->from('nilai');
        $this->db->join('siswa', 'siswa.id_siswa = nilai.id_siswa');
        $this->db->join('pelajaran', 'pelajaran.id_pelajaran = nilai.id_pelajaran ');
        $this->db->where('nilai.id_siswa', $id_siswa);
        return $this->db->get();
    }
    public function get_siswa_id($id_siswa)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas.id_kelas = siswa.id_kelas');
        $this->db->join('guru', 'kelas.id_guru = guru.id_guru');
        $this->db->where('id_siswa', $id_siswa);
        return $this->db->get();
    }
    public function nilai_excel($data)
    {
        return $this->db->insert_batch('nilai', $data);
    }
    public function jumlah_nilai($id_siswa)
    {
        // return $query =  $this->db->query("select sum(nilai) as total FROM nilai where id_siswa = '.$id_siswa.'");
        $this->db->select_sum('nilai', 'total');
        $this->db->from('nilai');
        $this->db->where('id_siswa', $id_siswa);
        return $this->db->get();
    }
    public function rata($id_siswa)
    {
        $this->db->select('*');
        $this->db->from('nilai');
        $this->db->where('id_siswa', $id_siswa);
        return $this->db->get();
    }

    public function get_jilid()
    {
        $this->db->select('*');
        $this->db->from('jilid');

        return $this->db->get();
    }
    public function siswa_jilid()
    {
        $this->db->select('*');
        $this->db->from('jilid');
        return $this->db->get();
    }
    public function jilid()
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('jilid', 'jilid.id_jilid = siswa.id_jilid');
        $this->db->where('aktif', 0);
        $this->db->where('id_kelas', $this->session->userdata('id_kelas'));
        return $this->db->get();
    }
    public function tambah_jilid($data, $id_siswa)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa', $data);
    }

    public function hapus_jilid($data, $id_siswa)
    {
        $this->db->where('id_siswa', $id_siswa);
        $this->db->update('siswa', $data);
    }
    public function presensi_excel($data)
    {
        return $this->db->insert_batch('presensi', $data);
    }
    public function tambah_catatan($data)
    {
        return $this->db->insert_batch('catatan', $data);
    }
    public function get_catatan()
    {
        $this->db->select('*');
        $this->db->from('catatan');
        $this->db->join('siswa', 'siswa.id_siswa = catatan.id_siswa');
        $this->db->where('siswa.id_kelas', $this->db->session('id_kelas'));
        return $this->db->get();
    }
    public function FunctionName(Type $var = null)
    {
     
    }
}
