-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th3 13, 2026 lúc 05:24 AM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlks_hotel`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mat_khau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id_admin`, `ho_ten`, `email`, `mat_khau`) VALUES
(1, 'Quản Trị Viên', 'admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdatphong`
--

DROP TABLE IF EXISTS `chitietdatphong`;
CREATE TABLE IF NOT EXISTS `chitietdatphong` (
  `id_chitiet` int NOT NULL AUTO_INCREMENT,
  `id_datphong` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_phong` int NOT NULL,
  `ngay_nhan` date NOT NULL,
  `ngay_tra` date NOT NULL,
  `gia_phong` decimal(10,2) NOT NULL,
  `so_dem` int GENERATED ALWAYS AS ((to_days(`ngay_tra`) - to_days(`ngay_nhan`))) STORED,
  `thanh_tien` decimal(12,2) GENERATED ALWAYS AS ((`gia_phong` * (to_days(`ngay_tra`) - to_days(`ngay_nhan`)))) STORED,
  PRIMARY KEY (`id_chitiet`),
  KEY `idx_datphong` (`id_datphong`),
  KEY `idx_phong` (`id_phong`),
  KEY `idx_ngay` (`ngay_nhan`,`ngay_tra`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdatphong`
--

INSERT INTO `chitietdatphong` (`id_chitiet`, `id_datphong`, `id_phong`, `ngay_nhan`, `ngay_tra`, `gia_phong`) VALUES
(44, 'BOOK-2026-0001', 2, '2026-01-05', '2026-01-08', 1800000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

DROP TABLE IF EXISTS `danhgia`;
CREATE TABLE IF NOT EXISTS `danhgia` (
  `id_danhgia` int NOT NULL AUTO_INCREMENT,
  `id_chitiet` int NOT NULL,
  `id_khachhang` int NOT NULL,
  `so_sao` tinyint NOT NULL,
  `noi_dung` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ngay_danhgia` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_danhgia`),
  UNIQUE KEY `uniq_one_review_per_booking` (`id_chitiet`),
  KEY `id_khachhang` (`id_khachhang`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`id_danhgia`, `id_chitiet`, `id_khachhang`, `so_sao`, `noi_dung`, `ngay_danhgia`) VALUES
(1, 1, 1, 5, 'Phòng sạch sẽ, nhân viên thân thiện!', '2025-12-06 15:41:06'),
(2, 2, 1, 4, 'Phòng đẹp, hồ bơi hơi nhỏ.', '2025-12-06 15:41:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `datphong`
--

DROP TABLE IF EXISTS `datphong`;
CREATE TABLE IF NOT EXISTS `datphong` (
  `ma_DP` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'VD: BOOK-2025-0001',
  `id_kh` int NOT NULL,
  `ngay_dat` datetime DEFAULT CURRENT_TIMESTAMP,
  `tong_tien` decimal(12,2) DEFAULT '0.00',
  `trang_thai` enum('cho_xac_nhan','da_xac_nhan','da_thanh_toan','da_huy','hoan_thanh') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cho_xac_nhan',
  `phuong_thuc_thanh_toan` enum('tien_mat','chuyen_khoan','vnpay') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `ly_do_huy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ma_DP`),
  KEY `id_admin` (`id_admin`),
  KEY `idx_trangthai` (`trang_thai`),
  KEY `idx_kh` (`id_kh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `datphong`
--

INSERT INTO `datphong` (`ma_DP`, `id_kh`, `ngay_dat`, `tong_tien`, `trang_thai`, `phuong_thuc_thanh_toan`, `id_admin`, `ly_do_huy`) VALUES
('BOOK-2026-0001', 2, '2026-01-05 02:29:27', 5400000.00, 'da_thanh_toan', 'vnpay', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE IF NOT EXISTS `khachhang` (
  `id_kh` int NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mat_khau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sdt` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_kh`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`id_kh`, `ho_ten`, `email`, `mat_khau`, `sdt`, `dia_chi`) VALUES
(1, 'Nguyễn Văn Khách', 'khach@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0909111222', NULL),
(2, 'Test', 'test123@gmail.com', '$2y$10$Ks4WmAkFmDNIR5t5dXTa2.YFS/0O0OkfD5.np8maATIs0sb5SR8S2', NULL, NULL),
(3, 'zyzy', 'dotuongvy4002@gmail.com', '$2y$10$RnEHGZZBQudq4NM9fCxRHe1BJwoC2EtH2AR/57H4M9wqTcQ9VZEIW', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong`
--

DROP TABLE IF EXISTS `phong`;
CREATE TABLE IF NOT EXISTS `phong` (
  `id_phong` int NOT NULL AUTO_INCREMENT,
  `ten_phong` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_phong` enum('Standard','Deluxe','Suite') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gia_mac_dinh` decimal(10,2) NOT NULL,
  `mo_ta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `hinh_anh` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `trang_thai` enum('Trống','Đã đặt','Bảo trì') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Trống',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_admin` int NOT NULL,
  PRIMARY KEY (`id_phong`),
  KEY `id_admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong`
--

INSERT INTO `phong` (`id_phong`, `ten_phong`, `loai_phong`, `gia_mac_dinh`, `mo_ta`, `hinh_anh`, `trang_thai`, `ngay_tao`, `id_admin`) VALUES
(1, 'Phòng 101', 'Standard', 850000.00, 'Phòng tiêu chuẩn', NULL, 'Đã đặt', '2025-12-06 15:41:06', 1),
(2, 'Phòng 202', 'Deluxe', 1800000.00, 'Phòng view biển', NULL, 'Trống', '2025-12-06 15:41:06', 1),
(3, 'Phòng 303', 'Suite', 3500000.00, 'Suite tổng thống', NULL, 'Đã đặt', '2025-12-06 15:41:06', 1),
(4, 'Phòng 992', 'Deluxe', 400000.00, '', NULL, 'Đã đặt', '2025-12-22 23:35:58', 1),
(6, 'Phòng 111', 'Deluxe', 1100000.00, '', NULL, 'Trống', '2026-01-03 21:15:56', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_tiennghi`
--

DROP TABLE IF EXISTS `phong_tiennghi`;
CREATE TABLE IF NOT EXISTS `phong_tiennghi` (
  `maphong` int NOT NULL,
  `matn` int NOT NULL,
  `soluong` int DEFAULT '1',
  `tinh_trang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Tốt',
  PRIMARY KEY (`maphong`,`matn`),
  KEY `matn` (`matn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong_tiennghi`
--

INSERT INTO `phong_tiennghi` (`maphong`, `matn`, `soluong`, `tinh_trang`) VALUES
(1, 1, 1, 'Tốt'),
(1, 3, 1, 'Tốt'),
(2, 1, 1, 'Tốt'),
(2, 2, 1, 'Tốt'),
(3, 1, 1, 'Tốt'),
(3, 2, 1, 'Tốt'),
(3, 3, 1, 'Tốt'),
(4, 1, 1, 'Tốt'),
(4, 2, 1, 'Tốt'),
(4, 3, 1, 'Tốt');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhtoan`
--

DROP TABLE IF EXISTS `thanhtoan`;
CREATE TABLE IF NOT EXISTS `thanhtoan` (
  `id_thanhtoan` int NOT NULL AUTO_INCREMENT,
  `id_datphong` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sotien` decimal(12,2) NOT NULL,
  `phuongthuc` enum('VNPay','Momo','Thẻ tín dụng','Tiền mặt','Chuyển khoản') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thoigian` datetime DEFAULT CURRENT_TIMESTAMP,
  `trang_thai` enum('Thành công','Thất bại','Đang xử lý') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Thành công',
  PRIMARY KEY (`id_thanhtoan`),
  UNIQUE KEY `id_datphong` (`id_datphong`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tiennghi`
--

DROP TABLE IF EXISTS `tiennghi`;
CREATE TABLE IF NOT EXISTS `tiennghi` (
  `matn` int NOT NULL AUTO_INCREMENT,
  `tentn` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`matn`),
  UNIQUE KEY `uniq_tentn` (`tentn`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tiennghi`
--

INSERT INTO `tiennghi` (`matn`, `tentn`) VALUES
(3, 'Điều hòa'),
(2, 'Hồ bơi'),
(1, 'Wifi');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `datphong`
--
ALTER TABLE `datphong`
  ADD CONSTRAINT `datphong_ibfk_1` FOREIGN KEY (`id_kh`) REFERENCES `khachhang` (`id_kh`) ON DELETE CASCADE,
  ADD CONSTRAINT `datphong_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `phong`
--
ALTER TABLE `phong`
  ADD CONSTRAINT `phong_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phong_tiennghi`
--
ALTER TABLE `phong_tiennghi`
  ADD CONSTRAINT `phong_tiennghi_ibfk_1` FOREIGN KEY (`maphong`) REFERENCES `phong` (`id_phong`) ON DELETE CASCADE,
  ADD CONSTRAINT `phong_tiennghi_ibfk_2` FOREIGN KEY (`matn`) REFERENCES `tiennghi` (`matn`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`id_datphong`) REFERENCES `datphong` (`ma_DP`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
