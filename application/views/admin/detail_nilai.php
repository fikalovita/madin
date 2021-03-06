<div class="container-fluid px-4 py-4">
    <div class="card">
        <div class="card-header bg-success bg-opacity-25">
            <div class="row">
                <div class="col-md-6">
                    <i class="fas fa-table me-1"></i>
                    <b>List Nilai</b>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered table-responsive-lg" id="tabel-nilai">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Nama Guru</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($siswa as $key => $value) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value->nama_siswa ?></td>
                            <td class="text-center"><a href="<?= base_url('admin/nilai_siswa/' . $value->id_siswa) ?>" type="button" class="btn btn-success btn-sm">Lihat Nilai</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>