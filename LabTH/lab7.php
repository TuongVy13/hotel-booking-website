<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Lab 7_4.1</h2>
   
    echo "BẢNG LOẠI SÁCH"\n
    echo "CREATE TABLE loai ("\n
    echo "    maloai VARCHAR(5) PRIMARY KEY,"\n
    echo "    tenloai VARCHAR(50)"\n
    echo ");"\n
    echo "\n"
   
    <hr>
    <h2>Lab 7_4.2</h2>
     echo "CREATE DATABASE bookstore"\n
    echo "USE bookstore"\n
    echo "Bang khachhang"\n
    echo "CREATE TABLE khachhang ("\n
    echo "email VARCHAR(50) PRIMARY KEY,"\n
    echo "matkhau VARCHAR(32),"\n
    echo "tenkh VARCHAR(50),"\n
    echo "diachi VARCHAR(100),"\n
    echo "dienthoai VARCHAR(11)"\n
    echo ");"\n
    echo "\n"

    echo "BẢNG HÓA ĐƠN"\n
    echo "CREATE TABLE hoadon ("\n
    echo "mahd INT(11) PRIMARY KEY,"\n
    echo "email VARCHAR(50),"\n 
    echo "ngayhd DATETIME,"\n
    echo "tennguoinhan VARCHAR(50),"\n
    echo "diachinguoinhan VARCHAR(80),"\n
    echo "ngaynhan DATE,"\n
    echo "dienthoainguoinhan VARCHAR(11),"\n
    echo "trangthai TINYINT(4),"\n
    echo ");"\n
    echo "\n"

    echo "BẢNG NHÀ XUẤT BẢN"\n
    echo "CREATE TABLE nhaxb ("\n
    echo "manxb VARCHAR(5) PRIMARY KEY,"\n
    echo "tennxb TEXT"\n
    echo ");"\n
    echo "\n"

    echo "BẢNG SÁCH"\n
    echo "CREATE TABLE sach ("\n
    echo "masach VARCHAR(15) PRIMARY KEY,"\n
    echo "tensach VARCHAR(250),"\n
    echo "mota TEXT,"\n
    echo "gia FLOAT,"\n
    echo "manxb VARCHAR(5),"\n
    echo "maloai VARCHAR(5),"\n
    echo ");"\n
    echo "\n"

    echo "BẢNG CHI TIẾT HÓA ĐƠN"\n
    echo "CREATE TABLE chitiethd ("\n
    echo "mahd INT(11),"\n
    echo "masach VARCHAR(15),"\n
    echo "soluong TINYINT(4),"\n
    echo "gia FLOAT,"\n
    echo ");"\n
    echo "\n"

    <hr>
    <h2>Lab 7_4.3</h2>
    echo "CONSTRAINT fk_hoadon_khachhang"\n
    echo "FOREIGN KEY (email) "\n
    echo "REFERENCES khachhang(email)"\n  
    echo "ON UPDATE CASCADE"\n    
    echo "ON DELETE RESTRICT"\n
    echo "\n"

    echo "CONSTRAINT fk_sach_nhaxb"\n
    echo "FOREIGN KEY (manxb) "\n   
    echo "REFERENCES nhaxb(manxb)"\n
    echo "ON UPDATE CASCADE"\n
    echo "ON DELETE RESTRICT,"\n
    echo "CONSTRAINT fk_sach_loai"\n
    echo "FOREIGN KEY (maloai) "\n  
    echo "REFERENCES loai(maloai)"\n
    echo "ON UPDATE CASCADE"\n
    echo "ON DELETE RESTRICT"\n

    echo "\n"\
    echo "CONSTRAINT fk_chitiethd_hoadon"\n
    echo "FOREIGN KEY (mahd) "\n
    echo "REFERENCES hoadon(mahd)"\n
    echo "ON UPDATE CASCADE"\n
    echo "ON DELETE CASCADE,"\n
    echo "CONSTRAINT fk_chitiethd_sach"\n
    echo "FOREIGN KEY (masach) "\n
    <hr>
    <h2>Lab 7_4.4</h2>
    echo "ALTER TABLE hoadon "\n
    echo "MODIFY trangthai TINYINT(4) DEFAULT 0;"\n
    <hr>
    <h2>Lab 7_4.5</h2>
    echo "CREATE OR REPLACE VIEW v_top10_sach_gia_cao AS"\n
    echo "SELECT masach, tensach, gia"\n
    echo "FROM sach"\n
    echo "ORDER BY gia DESC"\n
    echo "LIMIT 10;"\n
    <hr>
    <h2>Lab 7_4.6</h2>
    echo "DELIMITER $$"\n
    echo " CREATE PROCEDURE sp_sach_theo_loai(IN p_maloai VARCHAR(5)) "\n
    echo "BEGIN "\n
    echo "SELECT masach, tensach"\n
    echo " FROM sach"\n
    echo " WHERE maloai = p_maloai;"\n
    echo "END$$"\n
    echo "DELIMITER ;"\n
    <hr>
    <h2>Lab 7_5.1</h2>
    echo "SELECT s.masach, s.tensach, l.maloai, l.tenloai"\n
    echo " FROM sach s"\n
    echo " JOIN loai l ON s.maloai = l.maloai"\n
    echo "ORDER BY l.maloai, s.masach;\n

    <hr>
    <h2>Lab 7_5.2</h2>
    echo " DELIMITER $$ "\n
    echo "CREATE PROCEDURE sp_capnhat_gia("\n
    echo "IN p_masach VARCHAR(15),"\n
    echo "IN p_gia_moi FLOAT"\n
    echo ")\n"
    echo "BEGIN\n
    echo "    UPDATE sach\n"
    echo "    SET gia = p_gia_moi\n"
    echo "    WHERE masach = p_masach;\n
    echo "END$$"\n
    echo "DELIMITER ;"\n

    <hr>
    <h2>Lab 7_5.3</h2>
    echo "SELECT s.masach, s.tensach, SUM(c.soluong) AS tongban"\n
    echo "FROM chitiethd c"\n
    echo "JOIN sach s ON c.masach = s.masach"\n
    echo "GROUP BY s.masach, s.tensach"\n
    echo "ORDER BY tongban DESC"\n
    echo "LIMIT 1;"\n
    <hr>
    <h2>Lab 7_5.4</h2>
    echo " CREATE OR REPLACE VIEW v_top10_banchay AS"\n
    echo "SELECT s.masach, s.tensach, SUM(c.soluong) AS tongban"\n
    echo "FROM chitiethd c"\n
    echo "JOIN sach s ON c.masach = s.masach"\n
    echo "GROUP BY s.masach, s.tensach"\n
    echo "ORDER BY tongban DESC"\n
    echo "LIMIT 10;"\n;
    <hr>
    <h2>Lab 7_5.5</h2>"
    echo "mysqldump -u root -p bookstore > bookstore_backup.sql"\n
    <hr>
    <h2>Lab 7_5.6</h2>
    echo "DROP DATABASE bookstore;"\n
    <hr>
    <h2>lab 7_5.7</h2>
    echo "mysql -u root -p bookstore < bookstore_backup.sql"\n
</body>
</html>