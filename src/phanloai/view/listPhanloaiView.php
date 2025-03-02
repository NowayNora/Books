<h1>PHAN LOAI SACH</h1>
<div class="book-item">
    <table border='1'>
        <thead>
            <tr>
                <th>ID_PHAN_LOAI</th>
                <th>PHAN_LOAI</th>
                <th>MO_TA</th>
                <th>OPTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($phanloais as $phanloai) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($phanloai['ID_LOAI']); ?></td>
                <td><?php echo htmlspecialchars($phanloai['TENLOAI']); ?></td>
                <td><?php echo htmlspecialchars($phanloai['MOTALOAI']); ?></td>
                <td><a href="/updatephanloai?ID_LOAI=<?php echo $phanloai['ID_LOAI']; ?>">Edit</a> |
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="ID_LOAI"
                            value="<?php echo htmlspecialchars($phanloai['ID_LOAI']); ?>">
                        <input type="submit" value="Xóa" onclick="return confirm('You want to delete?');">
                    </form>
                </td>
                <?php
                    ?>
            </tr>
            <?php
            }
            ?>
            <a><a href="/addphanloai">ADD PHANLOAI</a>
        </tbody>
    </table>
</div>

<!-- echo "<div class='book-item'>";
echo "<p><strong>" . $phanloai["title"] . "</strong> <span>" . $phanloai["description"] . "</span></p>";
echo "</div>"; -->