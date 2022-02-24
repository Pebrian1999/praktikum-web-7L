<?php 
	if (isset($_GET['id'])){

		$database = new Database();
		$db = $database->getConnection();

		$id = $_GET['id'];
		$findSql = "SELECT * FROM karyawan inner join pengguna on karyawan.id=pengguna.id WHERE karyawan.id=?";
		$stmt = $db->prepare($findSql);
		$stmt->bindParam(1, $_GET['id']);
		$stmt->execute();
		$row =$stmt->fetch();
		if (isset($row['id'])){	
			if (isset($_POST['button_update'])) {

				$database = new Database();
				$db = $database->getConnection();

				$validateSql = "SELECT * FROM karyawan WHERE nik = ?";
		$stmt = $db->prepare($validateSql);
		$stmt->bindParam(1, $_POST['nik']);
		$stmt->execute();
		if($stmt->rowCount()>0){
?>
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h5><i class="icon fas fa-ban"></i>GAGAL</h5>
			NIK sama sudah ada
		</div>
<?php
		}else{
		$validateSql = "SELECT * FROM pengguna WHERE username = ?";
		$stmt = $db->prepare($validateSql);
		$stmt->bindParam(1, $_POST['username']);
		$stmt->execute();
		if($stmt->rowCount()>0){
?>
		<div class="alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h5><i class="icon fas fa-ban"></i>GAGAL</h5>
			Username sudah ada
		</div>
<?php
		}else{
			if ($_POST['password'] != $_POST['password_ulangi']) {
				?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
					<h5><i class="icon fas fa-ban">Gagal</i></h5> Password Tidak sama
				</div>
<?php
			}else{
				$md5Password = md5($_POST['password']);

				$insertSql = "UPDATE pengguna SET username = ?, password = ?, peran = ? WHERE id = ?";
				$stmt = $db->prepare($insertSql);
				$stmt ->bindParam(1,$_POST['username']);
				$stmt ->bindParam(2,$md5Password);
				$stmt ->bindParam(3,$_POST['peran']);
				$stmt ->bindParam(4,$_POST['id']);

				if ($stmt->execute()) {
					
					$pengguna_id = $db->lastInsertId();

					$insertKaryawanSql = "UPDATE karyawan set nik = ?, nama_lengkap = ?, handphone = ?, email = ?, tanggal_masuk=?, where id= ?";
					$stmtKaryawan->bindParam(1,$_POST['nik']);
					$stmtKaryawan->bindParam(2,$_POST['nama_lengkap']);
					$stmtKaryawan->bindParam(3,$_POST['handphone']);
					$stmtKaryawan->bindParam(4,$_POST['email']);
					$stmtKaryawan->bindParam(5,$_POST['tanggal_masuk']);
					$stmtKaryawan->bindParam(6,$pengguna_id);

					if ($stmtKaryawan->execute) {
						$_SESSION['hasil'] = true;
						$_SESSION['pesan'] = "Berhasil Simpan Data";
					}else{
						$_SESSION['hasil'] = false;
						$_SESSION['pesan'] = "Gagal Simpan Data";
					}
				}else{
					$_SESSION['hasil'] = false;
					$_SESSION['pesan'] = "Gagal Simpan Data";
				}
				echo "<meta http-equiv='refresh' content='0;url=?page=karyawanupdate'>";
			}
		}
	}
}
}
}
 ?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb2">
			<div class="col-sm-6">
				<h1>Ubah Data Karyawan</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="?page=home">Home</a></li>
					<li class="breadcrumb-item"><a href="?page=lokasiread">Karyawan</a></li>
					<li class="breadcrumb-item active">Ubah Data</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Ubah Karyawan</h3>
		</div>
		<div class="card-body">
			<form method="POST">
				<div class="form-group">
					<label for="nik">Nomor Induk Karyawan</label>
					<input type="hidden" name="id" class="form-control" value="<?php echo $row['id'] ?>">
					<input type="text" name="nik" class="form-control" value="<?php echo $row['nik']?>">
					<label for="nama_lengkap">Nama Lengkap</label>
					<input type="text" name="nama_lengkap" class="form-control" value="<?php echo $row['nama_lengkap']?>">
					<label for="handphone">Handphone</label>
					<input type="text" name="handphone" class="form-control" value="<?php echo $row['handphone']?>">
					<label for="email">Email</label>
					<input type="text" name="email" class="form-control" value="<?php echo $row['email']?>">
					<label for="tanggal_masuk">Tanggal Masuk</label>
					<input type="date" name="tanggal_masuk" class="form-control" value="<?php echo $row['tanggal_masuk']?>">
					<label for="username">Username</label>
					<input type="text" name="username" class="form-control" value="<?php echo $row['username']?>">
					<label for="password">Password</label>
					<input type="text" name="password" class="form-control" value="<?php echo $row['password']?>">
					<label for="password_ulangi">Password (Ulangi)</label>
					<input type="text" name="password_ulangi" class="form-control" value="<?php echo $row['password']?>">
					<label for="peran">Peran</label>
					<select class="form-control" name="peran">
						<option value="<?php echo $row['peran']?>"><?php echo $row['peran']?></option>
						<option value="ADMIN">ADMIN</option>
						<option value="USER">USER</option>
					</select>			
				</div>
				<a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
					<i class="fa fa-times"></i> Batal
				</a>
				<button type="submit" name="button_update" class="btn btn-success btn-sm float-right">
					<i class="fa fa-save"></i> Simpan
				</button>
			</form>
		</div>
	</div>
</section>
<?php include_once "partials/scripts.php" ?>