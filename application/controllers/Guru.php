<?php

use function PHPSTORM_META\map;

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;


class Guru extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_guru');
        $this->load->model('M_admin');
        if (!$this->session->userdata('guru')) {
            redirect('login');
        }
    }
    public function index()
    {
        $data = [
            'info' => $this->M_admin->get_info()->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/dashboard', $data);
        $this->load->view('guru/layout/footer');
    }
    public function profil_guru()
    {
        $this->load->view('guru/layout/header');
        $this->load->view('guru/profil_guru');
        $this->load->view('guru/layout/footer');
    }
    public function list_pelajaran()
    {
        $data = [

            'pelajaran' => $this->M_guru->get_pelajaran()->result(),

        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/list_pelajaran', $data);
        $this->load->view('guru/layout/footer');
    }
    public function penilaian($id_pelajaran)
    {
        $data = [
            'pelajaran' => $this->M_guru->get_mapel()->result(),
            'nilai' => $this->M_guru->get_nilai()->result(),
            'siswa' => $this->M_guru->get_siswa()->result(),

            'nilai_kunci' => $this->M_guru->get_nilai_kunci($id_pelajaran)
        ];
        $id_pelajaran = ['id_pelajaran' => $id_pelajaran];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/penilaian', $data);
        $this->load->view('guru/layout/footer');
    }
    public function cetak_nilai()
    {

        $data = [
            'nilai_cetak' => $this->M_guru->cetak()->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/cetak_nilai', $data);
        $this->load->view('guru/layout/footer');
    }
    public function edit_profile()
    {
        $this->load->view('guru/layout/header');
        $this->load->view('guru/edit_profile');
        $this->load->view('guru/layout/footer');
    }
    public function input_nilai()
    {
        $id_guru = $this->input->post('id_guru');
        $id_pelajaran = $this->input->post('id_pelajaran');
        $id_param = $this->input->post('id_param');
        $id_siswa = $this->input->post('id_siswa');
        $nilai = $this->input->post('nilai');
        $data = [];


        foreach ($id_siswa as $key => $value) {
            $data[] = [
                'id_siswa' => $id_siswa[$key],
                'id_pelajaran' => $id_pelajaran[$key],
                'id_guru' => $id_guru[$key],
                'nilai' => $nilai[$key],
                'tanggal_diupdate' => date('Y-m-d'),
            ];
        }

        $this->db->insert_batch('nilai', $data);
        $this->session->set_flashdata('pesan', 'disimpan');
        redirect('guru/penilaian/' . $id_param, 'refresh');
    }

    public function ubah_profile()
    {
        $nama_guru = $this->input->post('nama');
        $jabatan = $this->input->post('jabatan');
        $tempat_lahir = $this->input->post('tempat_lahir');
        $tgl_lahir = $this->input->post('tgl_lahir');
        $foto_guru = $_FILES['foto_guru']['name'];
        $id_guru = $this->input->post('id_guru');
        $foto_lama = $this->input->post('foto_lama');

        if ($foto_guru) {

            $config['upload_path'] = './assets/uploads';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto_guru')) {
                $data = $this->M_admin->get_guru_byId($id_guru);
                $foto = $data->foto_guru;
                if ($foto != 'user.jpg') {
                    $guru = './assets/uploads/' . $data->foto_guru;
                    unlink($guru);
                }

                $new_foto = $this->upload->data('file_name');
            }
        } else {

            $new_foto = $foto_lama;
        };

        $data = [
            'nama_guru' => $nama_guru,
            'foto_guru' => $new_foto,
            'tempat_lahir' => $tempat_lahir,
            'tgl_lahir' => $tgl_lahir,
            'jabatan' => $jabatan

        ];

        $this->M_guru->update_guru($id_guru, $data);
        $this->session->set_userdata($data);
    }
    public function update_nilai()
    {
        $id_pelajaran = $this->uri->segment(3);
        $data = [
            'nilai' => $this->M_guru->get_nilai_pelajaran($id_pelajaran)->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/view_nilai', $data);
        $this->load->view('guru/layout/footer');
    }
    public function lihat_nilai()
    {
        $id_pelajaran = $this->uri->segment(3);
        $data = [
            'nilai' => $this->M_guru->get_lihat_nilai($id_pelajaran)->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/lihat_nilai', $data);
        $this->load->view('guru/layout/footer');
    }
    public function ubah_nilai()
    {
        $id_nilai = $this->input->post('id_nilai');
        $nilai = $this->input->post('nilai');
        $deskripsi = $this->input->post('deskripsi');
        $param = $this->input->post('param');

        $data = [
            'nilai' => $nilai,
            'deskripsi' => $deskripsi

        ];

        $this->M_guru->update_nilai($id_nilai, $data);
        $this->session->set_flashdata('pesan', 'diubah');
        redirect('guru/update_nilai/' . $param, 'refresh');
    }

    public function kunci_nilai()
    {
        $id_pelajaran = $this->input->post('id_pelajaran');

        $data = [
            'kunci' => 1
        ];

        $this->M_guru->kunci_nilai($id_pelajaran, $data);
        $this->session->set_flashdata('pesan', 'dikunci');
        redirect('guru/penilaian/' . $id_pelajaran, 'refresh');
    }

    public function presensi()
    {
        $data = [
            'presensi' => $this->M_guru->presensi(),
            'kunci_presensi' => $this->M_guru->get_kunci_presensi()

        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/presensi', $data);
        $this->load->view('guru/layout/footer');
    }

    public function input_presensi()
    {
        $id_siswa = $this->input->post('id_siswa');
        $id_kelas = $this->input->post('id_kelas');
        $izin = $this->input->post('izin');
        $sakit = $this->input->post('sakit');
        $alpha = $this->input->post('alpha');

        $data = [];

        foreach ($id_siswa as $key => $value) {
            $data[] = [
                'id_siswa' => $id_siswa[$key],
                'id_kelas' => $id_kelas[$key],
                'izin' => $izin[$key],
                'sakit' => $sakit[$key],
                'alpha' => $alpha[$key]
            ];
        }

        $this->db->insert_batch('presensi', $data);
        $this->session->set_flashdata('pesan', 'disimpan');
        redirect('guru/presensi', 'refresh');
    }
    public function view_presensi()
    {
        $data = [

            'presensi' => $this->M_guru->get_presensi()->result()

        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/view_presensi', $data);
        $this->load->view('guru/layout/footer');
    }
    public function ubah_presensi()
    {
        $id_presensi = $this->input->post('id_presensi');
        $izin = $this->input->post('izin');
        $sakit = $this->input->post('sakit');
        $alpha = $this->input->post('alpha');

        $data = [
            'izin' => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha
        ];

        $this->M_guru->update_presensi($id_presensi, $data);
        $this->session->set_flashdata('pesan', 'diubah');
        redirect('guru/view_presensi', 'refresh');
    }
    public function data_siswa()
    {
        $data = [
            'siswa' => $this->M_guru->get_siswa()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/siswa', $data);
        $this->load->view('guru/layout/footer');
    }
    public function tingkatan_siswa()
    {
        $data = [
            'siswa' => $this->M_guru->get_siswa()->result(),
            'jilid' => $this->M_guru->Jilid()->result(),
            'jilid_siswa' => $this->M_guru->siswa_jilid()->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/tingkat', $data);
        $this->load->view('guru/layout/footer');
    }

    public function kunci_absensi()
    {
        $data = [
            'kunci' => 1
        ];

        $this->M_guru->kunci_absensi($data);
        $this->session->set_flashdata('pesan', 'dikunci');
        redirect('guru/presensi/' . 'refresh');
    }
    public function aksi_tingkatan()
    {
        $id_siswa = $this->input->post('id_siswa');
        $tingkat = $this->input->post('tingkat');
        $data = [
            'status' => $tingkat
        ];

        $this->M_guru->tingkatan($data, $id_siswa);
        $this->session->set_flashdata('pesan', 'disimpan');
        redirect('guru/tingkatan_siswa', 'refresh');
    }
    public function lihat_presensi()
    {
        $data = [
            'presensi' => $this->M_guru->get_presensi()->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/lihat_presensi', $data);
        $this->load->view('guru/layout/footer');
    }
    public function view_cetak()
    {

        $id_siswa = $this->uri->segment(3);
        $data = [
            'nilai' => $this->M_guru->cetak_raport($id_siswa)->result(),
            'siswa' => $this->M_guru->get_siswa_id($id_siswa)->result(),
            'total' => $this->M_guru->jumlah_nilai($id_siswa)->row()->total,
            'rata' => $this->M_guru->rata($id_siswa)->num_rows()
        ];

        // var_dump($total);
        // die();
        $this->load->view('guru/cetak', $data);
    }
    public function template_excel()
    {
        $nilai = $this->M_guru->get_siswa()->result_array();
        $nama_file = $this->M_guru->get_mapel()->result();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Nilai');
        $sheet->setCellValue('D1', 'Deskripsi');
        $start = 2;
        foreach ($nilai as $value) {
            $sheet->setCellValue('A' . $start, $value['nisn']);
            $sheet->setCellValue('B' . $start, $value['nama_siswa']);
            $sheet->setCellValue('F' . $start, $value['id_siswa']);
            $start++;
        };
        foreach ($nama_file as $key => $value) {
            $filename = $value->nama_pelajaran;
        }
        $writer = new Xls($spreadsheet);
        header("Content-Type:   application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename= ".' . $filename . '".xls  ');
        header("Cache-Control: max-age= 0", false);
        $writer->save('php://output');
    }
    public function import_excel()
    {
        $file = $_FILES['excel']['name'];
        $config['upload_path'] = './assets/uploads';
        $config['allowed_types'] = 'xlsx|xls';
        $config['detect_mime']     = TRUE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('excel')) {
        } else {
            $file = $this->upload->data('file_name');
            if ($config['allowed_types'] === 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            $spreadsheet = $reader->load('./assets/uploads/' . $file);
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            for ($i = 1; $i < count($sheet); $i++) {
                $data[] = [
                    'nilai' => $sheet[$i]['2'],
                    'deskripsi' => $sheet[$i]['3'],
                    'id_pelajaran' => $this->input->post('id_pelajaran'),
                    'tanggal_diupdate' => date('Y-m-d'),
                    'id_guru' => $this->session->userdata('id_guru'),
                    'id_siswa' => $sheet[$i]['5'],
                ];
            }
            $this->M_guru->nilai_excel($data);
            unlink('./assets/uploads/' . $file);
            $this->session->set_flashdata('pesan', 'diupload');
            redirect('guru/penilaian/' . $this->input->post('id_pelajaran'), 'refresh');
        }
    }
    public function jilid()
    {
        $data = [
            'siswa' => $this->M_guru->get_siswa(),
            'jilid' => $this->M_guru->get_jilid()->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/jilid', $data);
        $this->load->view('guru/layout/footer');
    }
    public function tambah_jilid()
    {
        $jilid = $this->input->post('jilid');
        $id_siswa = $this->input->post('id_siswa');
        // var_dump($jilid);
        // var_dump($id_siswa);
        $data = [
            'id_jilid' => $jilid
        ];
        // var_dump($data);
        // die();

        $this->M_guru->tambah_jilid($data, $id_siswa);
        // $this->session->set_flashdata('pesan', 'disimpan');
        // redirect('guru/jilid/', 'refresh');
    }

    public function hapus_jilid($id_siswa)
    {
        $data = [
            'id_jilid' => 0
        ];

        $this->M_guru->hapus_jilid($data, $id_siswa);
        $this->session->set_flashdata('pesan', 'dihapus');
        redirect('guru/jilid/', 'refresh');
    }

    public function template_presensi()
    {
        $nilai = $this->M_guru->get_siswa()->result_array();
        $filename = 'Template Presensi';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Id Siswa');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Nama Siswa');
        $sheet->setCellValue('D1', 'Sakit');
        $sheet->setCellValue('E1', 'Izin');
        $sheet->setCellValue('F1', 'Alpha');
        $start = 2;
        foreach ($nilai as $value) {
            $sheet->setCellValue('A' . $start, $value['id_siswa']);
            $sheet->setCellValue('B' . $start, $value['nisn']);
            $sheet->setCellValue('C' . $start, $value['nama_siswa']);
            $start++;
        };

        $writer = new Xls($spreadsheet);
        header("Content-Type:   application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename= ".' . $filename . '".xls  ');
        header("Cache-Control: max-age= 0", false);
        $writer->save('php://output');
    }

    public function import_presensi()
    {
        $file = $_FILES['excel']['name'];
        $config['upload_path'] = './assets/uploads';
        $config['allowed_types'] = 'xlsx|xls';
        $config['detect_mime']     = TRUE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('excel')) {
        } else {
            $file = $this->upload->data('file_name');
            if ($config['allowed_types'] === 'xlsx') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            } else {

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            $spreadsheet = $reader->load('./assets/uploads/' . $file);
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            for ($i = 1; $i < count($sheet); $i++) {
                $data[] = [
                    'id_siswa' => $sheet[$i]['0'],
                    'id_kelas' => $this->session->userdata('id_kelas'),
                    'sakit' => $sheet[$i]['3'],
                    'izin' => $sheet[$i]['4'],
                    'alpha' => $sheet[$i]['5']

                ];
            }
            $this->M_guru->presensi_excel($data);
            unlink('./assets/uploads/' . $file);
            $this->session->set_flashdata('pesan', 'diupload');
            redirect('guru/presensi/', 'refresh');
        }
    }
    public function catatan()
    {
        $data = [
            'siswa' => $this->M_guru->get_siswa()->result(),
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/catatan', $data);
        $this->load->view('guru/layout/footer');
    }
    public function tambah_catatan()
    {
        $catatan = $this->input->post('catatan');
        $id_siswa = $this->input->post('id_siswa');
        $data = [];
        foreach ($id_siswa as $key => $value) {
            $data[] = [
                'id_siswa' => $id_siswa[$key],
                'isi_catatan' => $catatan[$key]
            ];
        }

        $this->M_guru->tambah_catatan($data);
        $this->session->set_flashdata('pesan', 'disimpan');
        redirect('guru/catatan/', 'refresh');
    }

    public function edit_catatan()
    {
        $data = [
            'catatan' => $this->M_guru->get_catatan()->result()
        ];
        $this->load->view('guru/layout/header');
        $this->load->view('guru/edit_catatan', $data);
        $this->load->view('guru/layout/footer');
    }
}
