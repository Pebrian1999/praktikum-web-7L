<?php 
	if (isset($_POST['button_create'])) {

		$database = new Database();
		$db = $database->getConnection();

		$validateSql = "SELECT * FROM jabatan WHERE nama_jabatan = ?";
		$stmt = $db->prepare($validateSql);
		$stmt->bindParam(1, $_POST['nama_jabatan']);
		$stmt->execute();
		if($stmt->rowCount()>0){
?>
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
			<h5><i class="icon fas fa-ban"></i>GAGAL</h5>
			Nama Jabatan Sudah Ada
		</div>
<?php
		}else{
			$insertSql = "INSERT INTO jabatan SET nama_jabatan = ?, gapok_jabatan = ?, tunjangan_jabatan = ?, uang_makan_perhari = ?";
			$stmt = $db->prepare($insertSql);
			$stmt->bindParam(1, $_POST['nama_jabatan']);
			$stmt->bindParam(2, $_POST['gapok_jabatan']);
			$stmt->bindParam(3, $_POST['tunjangan_jabatan']);
			$stmt->bindParam(4, $_POST['uang_makan_perhari']);
			if ($stmt->execute()) {
				$_SESSION['hasil'] = true;
				$_SESSION['pesan'] = "Berhasil Simpan Data";
			}else{
				$_SESSION['hasil'] = false;
				$_SESSION['pesan'] = "Gagal Simpan Data";
			}
			echo "<meta http-equiv='refresh' content='0;url=?page=jabatanread'>";
		}
	}
 ?>
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb2">
			<div class="col-sm-6">
				<h1>Tambah Data Bagian</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="?page=home">Home</a></li>
					<li class="breadcrumb-item"><a href="?page=jabatanread">Jabatan</a></li>
					<li class="breadcrumb-item active">Tambah Data</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Tambah Bagian</h3>
		</div>
		<div class="card-body">
			<form method="POST">
				<div class="form-group">
					<label for="nama_bagian">Nama Bagian</label>
					<input type="text" name="nama_bagian" class="form-control">
				</div>
				<div class="form-group">
					<label for="karyawan_id">Kepala Bagian</label>
					<select class="form-control " name="karyawan_id">
					<option value="">-- Pilih Kepala Bagian--</option>
					<?php 
						$selectSql = "SELECT * from karyawan";
						$stmt_karyawan = $db->prepare($selectSql);
						$stmt_karyawan->execute();
						while ($row_karyawan = $stmt_karyawan->fetch(PDO::FETCH_ASSOC)){
							echo "<option value=\"" . $row_karyawan["id"] . "\">" . $row_karyawan["nama_lengkap"] . "</option>";
						}
					 ?>
					</select>
				</div>
				<div class="form-group">
					<label for="tunjangan_jabatan">Tunjangan</label>
					<input type="text" name="tunjangan_jabatan" class="form-control" 
					onkeypress='return (event.charCode > 47 && event.charCode < 58) || event.charCode == 46'>
				</div>
				<a href="?page=jabatanread" class="btn btn-danger btn-sm float-right">
					<i class="fa fa-times"></i> Batal
				</a>
				<button type="submit" name="button_create" class="btn btn-success btn-sm float-right">
					<i class="fa fa-save"></i> Simpan
				</button>
			</form>
		</div>
	</div>
</section>
<?php include_once "partials/scripts.php" ?>