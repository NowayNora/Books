<h1>Books Manager</h1>
<h2>Add Phan Loai</h2>
<form action="" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="text" name="TENLOAI" placeholder="Phân loại" required>
    <input type="text" name="MOTALOAI" placeholder="Mô tả" required>
    <button type="submit" name="add_phanloai">Thêm phân loại</button>
</form>
<br />
<a href="/phanloai">Return to list</a>
</form>