<h1>Books Manager</h1>
<h2>Edit Phan Loai</h2>
<form action="" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="text" name="TENLOAI" placeholder="Phân loại" required>
    <input type="text" name="MOTALOAI" placeholder="Mô tả" required>
    <button type="submit" name="edit_phanloai">Fix phân loại</button>
</form>

<br />
<a href="/phanloai">Return to list</a>
<script>
let phanloaiId = "<?php echo $_GET['ID_LOAI'] ?>";
if (!!phanloaiId) {
    let phanloai_data = <?php echo json_encode($phanloais) ?>;
    document.getElementsByName('TENLOAI')[0].value = phanloai_data['TENLOAI'];
    document.getElementsByName('MOTALOAI')[0].value = phanloai_data['MOTALOAI'];
}
</script>