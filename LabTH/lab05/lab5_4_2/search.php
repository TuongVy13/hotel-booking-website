<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sản phẩm</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .form-group { margin-bottom: 10px; }
        .result { margin-top: 20px; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; }
    </style>
</head>
<body>

    <h2>Tìm kiếm sản phẩm</h2>

    <form action="" method="get">
        
        <div class="form-group">
            <label>Tên sản phẩm:</label>
            <input type="text" name="txt_ten" required placeholder="Nhập tên sản phẩm...">
        </div>

        <div class="form-group">
            <label>Cách tìm:</label>
            <input type="radio" name="rd_cachtim" value="gandung" checked> Gần đúng
            <input type="radio" name="rd_cachtim" value="chinhxac"> Chính xác
        </div>

        <div class="form-group">
            <label>Loại sản phẩm:</label>
            <select name="sel_loai">
                <option value="tatca">Tất cả</option>
                <option value="loai1">Loại 1</option>
                <option value="loai2">Loại 2</option>
                <option value="loai3">Loại 3</option>
            </select>
        </div>

        <div class="form-group">
            <input type="submit" name="btn_tim" value="Tìm kiếm">
        </div>
    </form>

    <?php
    if (isset($_GET['btn_tim'])) {
        
        echo "<div class='result'>";
        echo "<h3>Kết quả tìm kiếm:</h3>";

        if (isset($_GET['txt_ten'])) {
            echo "Tên sản phẩm: <strong>" . $_GET['txt_ten'] . "</strong><br>";
        }

        if (isset($_GET['rd_cachtim'])) {
            $cach_tim = $_GET['rd_cachtim'];
            $hien_thi_cach_tim = ($cach_tim == 'gandung') ? "Gần đúng" : "Chính xác";
            echo "Cách tìm: <strong>" . $hien_thi_cach_tim . "</strong><br>";
        }

        if (isset($_GET['sel_loai'])) {
            $loai = $_GET['sel_loai'];
            
            if ($loai != "tatca") {
                echo "Loại sản phẩm: <strong>" . $loai . "</strong><br>";
            } else {
                
            }
        }
        
        echo "</div>";
    }
    ?>

</body>
</html>