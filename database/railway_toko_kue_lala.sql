-- =====================================================================
-- TOKO KUE LALA - RAILWAY MYSQL DATA EXPORT
-- Tanggal Ekspor: 2026-11-22 18:41:44
-- Source: database.sqlite lokal
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+07:00';

-- ---------------------------------------------------------------------
-- Data untuk tabel: settings (9 baris)
-- ---------------------------------------------------------------------
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES 
  ('shop_name', 'Toko Kue Lala', '2026-11-06 03:31:45', '2026-11-06 03:31:45'),
  ('address', 'Jl. Matraman Dalam III No. 26B, Jakarta Timur', '2026-11-06 03:31:45', '2026-11-06 03:31:45'),
  ('phone', '085795580399', '2026-11-06 03:31:45', '2026-11-07 13:27:48'),
  ('pickup_hours', 'Senin - Sabtu, 08.00 - 20.00 WIB', '2026-11-06 03:31:45', '2026-11-06 03:31:45'),
  ('bank_bca', '1234567890 (a/n Lala Komalasari)', '2026-11-06 03:31:45', '2026-11-06 03:31:45'),
  ('dana', '085795580399 (Toko Kue Lala)', '2026-11-06 03:31:45', '2026-11-07 13:27:48'),
  ('gopay', '085795580399 (Toko Kue Lala)', '2026-11-06 03:31:45', '2026-11-07 13:27:48'),
  ('min_order_days', '1', '2026-11-06 03:31:45', '2026-11-06 03:31:45'),
  ('qris_image', NULL, '2026-11-06 03:31:45', '2026-11-06 03:31:45');

-- ---------------------------------------------------------------------
-- Data untuk tabel: categories (3 baris)
-- ---------------------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES 
  (1, 'Bolu & Kue Panggang', 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (2, 'Kue Tradisional', 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (3, 'Snack & Pastry', 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47');

-- ---------------------------------------------------------------------
-- Data untuk tabel: users (5 baris)
-- ---------------------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES 
  (1, 'Lala Komalasari', '081234567890', 'pemilik@tokokuelalala.id', '$2y$12$1Ewx0M3j3IL.AfGJzlIZG.I4lBwsHv.Di2KjvEzrmUtE1WuRXfqla', 'pemilik', 1, NULL, '2026-11-06 03:31:45', '2026-11-06 03:31:45'),
  (2, 'Budi Santoso (Kasir)', '081234567891', 'kasir@tokokuelalala.id', '$2y$12$aYGDo6ptIg0K5a9O0RJJIeeA6p/HFLRh/gwsJONU6hJcehR9rcYri', 'staf_kasir', 1, NULL, '2026-11-06 03:31:46', '2026-11-06 03:31:46'),
  (3, 'Dewi Lestari (Dapur)', '081234567892', 'produksi@tokokuelalala.id', '$2y$12$MivbnI8kXJV4x8GvbuTJIOsUVm/AxBYOOOb3zg7ghhjJWYfB6oC7O', 'staf_produksi', 1, NULL, '2026-11-06 03:31:46', '2026-11-06 03:31:46'),
  (4, 'Rina Wijaya', '081298765432', 'pelanggan1@email.com', '$2y$12$HfkpoN3n5w3/FHOnpFv81OaalfB3YLlVzR2B9F2xCZu7aHUTF79ja', 'pelanggan', 1, NULL, '2026-11-06 03:31:46', '2026-11-06 03:31:46'),
  (5, 'Andi Pratama', '081287654321', 'pelanggan2@email.com', '$2y$12$lcTq81v//fLI29Ttv2FzB.ezNsZoXHkjGJ1IPtPkXgIEYqNfzyKaS', 'pelanggan', 1, NULL, '2026-11-06 03:31:46', '2026-11-06 03:31:46');

-- ---------------------------------------------------------------------
-- Data untuk tabel: products (8 baris)
-- ---------------------------------------------------------------------
INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `photo`, `price`, `is_active`, `created_at`, `updated_at`) VALUES 
  (1, 1, 'Kue Bolu Marmer', 'Bolu marmer klasik dengan corak cokelat khas yang lembut, wangi butter harum, dan tekstur empuk moist di setiap gigitan.', NULL, 85000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (2, 2, 'Kue Apem', 'Kue apem kukus tradisional khas Toko Kue Lala dengan rasa manis legit gurih alami, tekstur kenyal empuk yang nikmat.', NULL, 45000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (3, 1, 'Kue Bolu Tape', 'Bolu tape singkong legit dengan perpaduan tape manis matang sempurna, beraroma harum menggoda dengan taburan keju gurih.', NULL, 90000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (4, 1, 'Kue Bolu Caramel / Sarang Semut (Ukuran S)', 'Kue bolu karamel legit dengan rongga sarang semut sempurna, rasa manis karamel legit yang khas dan kenyal moist. Ukuran Small.', NULL, 100000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (5, 1, 'Kue Bolu Caramel / Sarang Semut (Ukuran L)', 'Kue bolu karamel legit dengan rongga sarang semut sempurna, rasa manis karamel legit yang khas dan kenyal moist. Ukuran Large loyang besar.', NULL, 130000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (6, 3, 'Kue Soes (Isi 6 Buah)', 'Sus vla lembut renyah di luar dengan isian vla vanila susu yang melimpah, lumer, dan tidak terlalu manis. 1 porsi isi 6 buah.', NULL, 20000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (7, 2, 'Kue Lumpur (Isi 6 Buah)', 'Kue lumpur kentang lembut beraroma pandan dan santan gurih dengan topping kismis manis. 1 porsi isi 6 buah.', NULL, 20000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (8, 3, 'Kue Pie Buah (Isi 6 Buah)', 'Mini fruit pie dengan kulit pie yang renyah gurih, vla lembut, serta topping potongan buah segar berkilau. 1 porsi isi 6 buah.', NULL, 20000, 1, '2026-11-06 03:31:47', '2026-11-06 03:31:47');

-- ---------------------------------------------------------------------
-- Data untuk tabel: orders (14 baris)
-- ---------------------------------------------------------------------
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `customer_phone`, `customer_address`, `pickup_date`, `note`, `total`, `status`, `payment_method`, `payment_proof`, `payment_reject_reason`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES 
  (1, 'LALA-20261025-0001', 4, 'Rina Wijaya', '081298765432', 'Jl. Matraman Raya No. 45, Jakarta Timur', '2026-11-07 00:00:00', NULL, 85000, 'menunggu_pembayaran', 'whatsapp', NULL, NULL, NULL, NULL, '2026-10-25 21:31:47', '2026-11-06 03:31:47'),
  (2, 'LALA-20261026-0002', 5, 'Andi Pratama', '081287654321', 'Jl. Kayu Manis VIII No. 10, Matraman', '2026-11-08 00:00:00', NULL, 45000, 'diproses', 'whatsapp', NULL, NULL, 2, '2026-10-26 20:51:47', '2026-10-26 20:31:47', '2026-11-06 03:31:47'),
  (3, 'LALA-20261027-0003', NULL, 'Siti Nurhaliza', '085712345678', 'Jl. Pisangan Baru Timur No. 22, Jaktim', '2026-11-09 00:00:00', 'Tolong siapkan pisau kue dan lilin.', 200000, 'siap_diambil', 'whatsapp', NULL, NULL, 2, '2026-10-27 17:51:47', '2026-10-27 17:31:47', '2026-11-06 03:31:47'),
  (4, 'LALA-20261028-0004', NULL, 'Hendra Setiawan', '081389012345', 'Ambil Sendiri di Toko', '2026-11-06 00:00:00', NULL, 100000, 'selesai', 'whatsapp', NULL, NULL, 2, '2026-10-28 22:51:47', '2026-10-28 22:31:47', '2026-11-06 03:31:47'),
  (5, 'LALA-20261029-0005', NULL, 'Maya Kartika', '081912349988', 'Jl. Utan Kayu Utara No. 5B, Jakarta Timur', '2026-11-07 00:00:00', NULL, 130000, 'menunggu_pembayaran', 'whatsapp', NULL, NULL, NULL, NULL, '2026-10-29 17:31:47', '2026-11-06 03:31:47'),
  (6, 'LALA-20261030-0006', 4, 'Rina Wijaya', '081298765432', 'Jl. Matraman Raya No. 45, Jakarta Timur', '2026-11-08 00:00:00', 'Tolong siapkan pisau kue dan lilin.', 125000, 'diproses', 'whatsapp', NULL, NULL, 2, '2026-10-30 18:51:47', '2026-10-30 18:31:47', '2026-11-06 03:31:47'),
  (7, 'LALA-20261031-0007', 5, 'Andi Pratama', '081287654321', 'Jl. Kayu Manis VIII No. 10, Matraman', '2026-11-09 00:00:00', NULL, 20000, 'siap_diambil', 'whatsapp', NULL, NULL, 2, '2026-10-31 17:51:47', '2026-10-31 17:31:47', '2026-11-06 03:31:47'),
  (8, 'LALA-20261101-0008', NULL, 'Siti Nurhaliza', '085712345678', 'Jl. Pisangan Baru Timur No. 22, Jaktim', '2026-11-06 00:00:00', NULL, 20000, 'selesai', 'whatsapp', NULL, NULL, 2, '2026-11-01 20:51:47', '2026-11-01 20:31:47', '2026-11-06 03:31:47'),
  (9, 'LALA-20261102-0009', NULL, 'Hendra Setiawan', '081389012345', 'Ambil Sendiri di Toko', '2026-11-07 00:00:00', 'Tolong siapkan pisau kue dan lilin.', 185000, 'menunggu_pembayaran', 'whatsapp', NULL, NULL, NULL, NULL, '2026-11-02 17:31:47', '2026-11-06 03:31:47'),
  (10, 'LALA-20261104-0010', NULL, 'Maya Kartika', '081912349988', 'Jl. Utan Kayu Utara No. 5B, Jakarta Timur', '2026-11-08 00:00:00', NULL, 45000, 'diproses', 'whatsapp', NULL, NULL, 2, '2026-11-04 00:51:47', '2026-11-04 00:31:47', '2026-11-06 03:31:47'),
  (11, 'LALA-20261104-0011', 4, 'Rina Wijaya', '081298765432', 'Jl. Matraman Raya No. 45, Jakarta Timur', '2026-11-09 00:00:00', NULL, 180000, 'siap_diambil', 'whatsapp', NULL, NULL, 2, '2026-11-04 22:51:47', '2026-11-04 22:31:47', '2026-11-06 03:31:47'),
  (12, 'LALA-20261106-0012', 5, 'Andi Pratama', '081287654321', 'Jl. Kayu Manis VIII No. 10, Matraman', '2026-11-06 00:00:00', 'Tolong siapkan pisau kue dan lilin.', 220000, 'selesai', 'whatsapp', NULL, NULL, 2, '2026-11-06 00:51:47', '2026-11-06 00:31:47', '2026-11-06 03:31:47'),
  (13, 'LALA-20261107-0001', 1, 'Lala Komalasari', '081234567890', 'Ambil Sendiri di Toko (Jl. Matraman Dalam III No. 26B, Jakarta Timur)', '2026-11-08 00:00:00', NULL, 20000, 'diproses', 'whatsapp', NULL, NULL, NULL, NULL, '2026-11-07 13:28:11', '2026-11-07 13:30:06'),
  (14, 'LALA-20261107-0002', 1, 'jeki', '08888407643', 'Ambil Sendiri di Toko (Jl. Matraman Dalam III No. 26B, Jakarta Timur)', '2026-11-08 00:00:00', NULL, 20000, 'diproses', 'whatsapp', NULL, NULL, NULL, NULL, '2026-11-07 13:31:58', '2026-11-07 13:32:37');

-- ---------------------------------------------------------------------
-- Data untuk tabel: order_items (18 baris)
-- ---------------------------------------------------------------------
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `qty`, `size`, `cake_text`, `custom_note`, `subtotal`, `created_at`, `updated_at`) VALUES 
  (1, 1, 1, 'Kue Bolu Marmer', 85000, 1, NULL, NULL, NULL, 85000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (2, 2, 2, 'Kue Apem', 45000, 1, NULL, 'Selamat Ulang Tahun Andi Pratama', NULL, 45000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (3, 3, 3, 'Kue Bolu Tape', 90000, 2, NULL, NULL, NULL, 180000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (4, 3, 6, 'Kue Soes (Isi 6 Buah)', 20000, 1, NULL, NULL, NULL, 20000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (5, 4, 4, 'Kue Bolu Caramel / Sarang Semut (Ukuran S)', 100000, 1, NULL, 'Selamat Ulang Tahun Hendra Setiawan', NULL, 100000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (6, 5, 5, 'Kue Bolu Caramel / Sarang Semut (Ukuran L)', 130000, 1, NULL, NULL, NULL, 130000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (7, 6, 6, 'Kue Soes (Isi 6 Buah)', 20000, 2, NULL, 'Selamat Ulang Tahun Rina Wijaya', NULL, 40000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (8, 6, 1, 'Kue Bolu Marmer', 85000, 1, NULL, NULL, NULL, 85000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (9, 7, 7, 'Kue Lumpur (Isi 6 Buah)', 20000, 1, NULL, NULL, NULL, 20000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (10, 8, 8, 'Kue Pie Buah (Isi 6 Buah)', 20000, 1, NULL, 'Selamat Ulang Tahun Siti Nurhaliza', NULL, 20000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (11, 9, 1, 'Kue Bolu Marmer', 85000, 1, NULL, NULL, NULL, 85000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (12, 9, 4, 'Kue Bolu Caramel / Sarang Semut (Ukuran S)', 100000, 1, NULL, NULL, NULL, 100000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (13, 10, 2, 'Kue Apem', 45000, 1, NULL, 'Selamat Ulang Tahun Maya Kartika', NULL, 45000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (14, 11, 3, 'Kue Bolu Tape', 90000, 2, NULL, NULL, NULL, 180000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (15, 12, 4, 'Kue Bolu Caramel / Sarang Semut (Ukuran S)', 100000, 2, NULL, 'Selamat Ulang Tahun Andi Pratama', NULL, 200000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (16, 12, 7, 'Kue Lumpur (Isi 6 Buah)', 20000, 1, NULL, NULL, NULL, 20000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (17, 13, 6, 'Kue Soes (Isi 6 Buah)', 20000, 1, NULL, NULL, NULL, 20000, '2026-11-07 13:28:11', '2026-11-07 13:28:11'),
  (18, 14, 7, 'Kue Lumpur (Isi 6 Buah)', 20000, 1, NULL, NULL, NULL, 20000, '2026-11-07 13:31:58', '2026-11-07 13:31:58');

-- ---------------------------------------------------------------------
-- Data untuk tabel: order_status_logs (34 baris)
-- ---------------------------------------------------------------------
INSERT INTO `order_status_logs` (`id`, `order_id`, `from_status`, `to_status`, `user_id`, `note`, `created_at`) VALUES 
  (1, 1, NULL, 'menunggu_pembayaran', 4, 'Pesanan dibuat oleh Rina Wijaya', '2026-10-25 21:31:47'),
  (2, 2, NULL, 'menunggu_pembayaran', 5, 'Pesanan dibuat oleh Andi Pratama', '2026-10-26 20:31:47'),
  (3, 2, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-10-26 20:51:47'),
  (4, 3, NULL, 'menunggu_pembayaran', NULL, 'Pesanan dibuat oleh Siti Nurhaliza', '2026-10-27 17:31:47'),
  (5, 3, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-10-27 17:51:47'),
  (6, 3, 'diproses', 'siap_diambil', 3, 'Kue selesai dibuat & siap diambil di toko.', '2026-10-27 19:31:47'),
  (7, 4, NULL, 'menunggu_pembayaran', NULL, 'Pesanan dibuat oleh Hendra Setiawan', '2026-10-28 22:31:47'),
  (8, 4, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-10-28 22:51:47'),
  (9, 4, 'diproses', 'siap_diambil', 3, 'Kue selesai dibuat & siap diambil di toko.', '2026-10-29 00:31:47'),
  (10, 4, 'siap_diambil', 'selesai', 2, 'Pesanan telah diambil / dikirim kepada pelanggan.', '2026-10-29 03:31:47'),
  (11, 5, NULL, 'menunggu_pembayaran', NULL, 'Pesanan dibuat oleh Maya Kartika', '2026-10-29 17:31:47'),
  (12, 6, NULL, 'menunggu_pembayaran', 4, 'Pesanan dibuat oleh Rina Wijaya', '2026-10-30 18:31:47'),
  (13, 6, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-10-30 18:51:47'),
  (14, 7, NULL, 'menunggu_pembayaran', 5, 'Pesanan dibuat oleh Andi Pratama', '2026-10-31 17:31:47'),
  (15, 7, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-10-31 17:51:47'),
  (16, 7, 'diproses', 'siap_diambil', 3, 'Kue selesai dibuat & siap diambil di toko.', '2026-10-31 19:31:47'),
  (17, 8, NULL, 'menunggu_pembayaran', NULL, 'Pesanan dibuat oleh Siti Nurhaliza', '2026-11-01 20:31:47'),
  (18, 8, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-11-01 20:51:47'),
  (19, 8, 'diproses', 'siap_diambil', 3, 'Kue selesai dibuat & siap diambil di toko.', '2026-11-01 22:31:47'),
  (20, 8, 'siap_diambil', 'selesai', 2, 'Pesanan telah diambil / dikirim kepada pelanggan.', '2026-11-02 01:31:47'),
  (21, 9, NULL, 'menunggu_pembayaran', NULL, 'Pesanan dibuat oleh Hendra Setiawan', '2026-11-02 17:31:47'),
  (22, 10, NULL, 'menunggu_pembayaran', NULL, 'Pesanan dibuat oleh Maya Kartika', '2026-11-04 00:31:47'),
  (23, 10, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-11-04 00:51:47'),
  (24, 11, NULL, 'menunggu_pembayaran', 4, 'Pesanan dibuat oleh Rina Wijaya', '2026-11-04 22:31:47'),
  (25, 11, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-11-04 22:51:47'),
  (26, 11, 'diproses', 'siap_diambil', 3, 'Kue selesai dibuat & siap diambil di toko.', '2026-11-05 00:31:47'),
  (27, 12, NULL, 'menunggu_pembayaran', 5, 'Pesanan dibuat oleh Andi Pratama', '2026-11-06 00:31:47'),
  (28, 12, 'menunggu_pembayaran', 'diproses', 2, 'Pembayaran dikonfirmasi via WhatsApp oleh toko. Sedang diproses dapur.', '2026-11-06 00:51:47'),
  (29, 12, 'diproses', 'siap_diambil', 3, 'Kue selesai dibuat & siap diambil di toko.', '2026-11-06 02:31:47'),
  (30, 12, 'siap_diambil', 'selesai', 2, 'Pesanan telah diambil / dikirim kepada pelanggan.', '2026-11-06 05:31:47'),
  (31, 13, NULL, 'menunggu_pembayaran', 1, 'Pesanan baru dibuat oleh Lala Komalasari (WhatsApp: 081234567890).', '2026-11-07 13:28:11'),
  (32, 13, 'menunggu_pembayaran', 'diproses', 1, 'Status diubah oleh Lala Komalasari', '2026-11-07 13:30:06'),
  (33, 14, NULL, 'menunggu_pembayaran', 1, 'Pesanan baru dibuat oleh jeki (WhatsApp: 08888407643).', '2026-11-07 13:31:58'),
  (34, 14, 'menunggu_pembayaran', 'diproses', 1, 'Status diubah oleh Lala Komalasari', '2026-11-07 13:32:37');

-- ---------------------------------------------------------------------
-- Data untuk tabel: direct_sales (5 baris)
-- ---------------------------------------------------------------------
INSERT INTO `direct_sales` (`id`, `sale_number`, `user_id`, `payment_method`, `total`, `sold_at`, `created_at`, `updated_at`) VALUES 
  (1, 'POS-20261101-0001', 2, 'qris', 45000, '2026-11-01 19:31:47', '2026-11-01 19:31:47', '2026-11-06 03:31:47'),
  (2, 'POS-20261102-0002', 2, 'transfer', 90000, '2026-11-02 19:31:47', '2026-11-02 19:31:47', '2026-11-06 03:31:47'),
  (3, 'POS-20261103-0003', 2, 'tunai', 100000, '2026-11-03 22:31:47', '2026-11-03 22:31:47', '2026-11-06 03:31:47'),
  (4, 'POS-20261105-0004', 2, 'qris', 130000, '2026-11-05 00:31:47', '2026-11-05 00:31:47', '2026-11-06 03:31:47'),
  (5, 'POS-20261105-0005', 2, 'transfer', 20000, '2026-11-05 20:31:47', '2026-11-05 20:31:47', '2026-11-06 03:31:47');

-- ---------------------------------------------------------------------
-- Data untuk tabel: direct_sale_items (5 baris)
-- ---------------------------------------------------------------------
INSERT INTO `direct_sale_items` (`id`, `direct_sale_id`, `product_id`, `product_name`, `unit_price`, `qty`, `subtotal`, `created_at`, `updated_at`) VALUES 
  (1, 1, 2, 'Kue Apem', 45000, 1, 45000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (2, 2, 3, 'Kue Bolu Tape', 90000, 1, 90000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (3, 3, 4, 'Kue Bolu Caramel / Sarang Semut (Ukuran S)', 100000, 1, 100000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (4, 4, 5, 'Kue Bolu Caramel / Sarang Semut (Ukuran L)', 130000, 1, 130000, '2026-11-06 03:31:47', '2026-11-06 03:31:47'),
  (5, 5, 6, 'Kue Soes (Isi 6 Buah)', 20000, 1, 20000, '2026-11-06 03:31:47', '2026-11-06 03:31:47');

-- ---------------------------------------------------------------------
-- Data untuk tabel: migrations (17 baris)
-- ---------------------------------------------------------------------
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES 
  (1, '2024_01_01_000001_create_users_table', 1),
  (2, '2024_01_01_000002_create_categories_table', 1),
  (3, '2024_01_01_000003_create_products_table', 1),
  (4, '2024_01_01_000004_create_orders_table', 1),
  (5, '2024_01_01_000005_create_order_items_table', 1),
  (6, '2024_01_01_000006_create_order_status_logs_table', 1),
  (7, '2024_01_01_000007_create_ingredients_table', 1),
  (8, '2024_01_01_000008_create_stock_ins_table', 1),
  (9, '2024_01_01_000009_create_stock_usages_table', 1),
  (10, '2024_01_01_000010_create_stock_adjustments_table', 1),
  (11, '2024_01_01_000011_create_direct_sales_table', 1),
  (12, '2024_01_01_000012_create_direct_sale_items_table', 1),
  (13, '2024_01_01_000013_create_settings_table', 1),
  (14, '2024_01_01_000014_create_password_reset_tokens_table', 1),
  (15, '2024_01_01_000015_create_sessions_table', 1),
  (16, '2024_01_01_000016_create_cache_table', 1),
  (17, '2024_01_01_000017_create_jobs_table', 1);

-- ---------------------------------------------------------------------
-- Data untuk tabel: sessions (5 baris)
-- ---------------------------------------------------------------------
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES 
  ('3PnFGy11ZmVtQfY5iRhJm8hsoD5UBAjxMyA68Y7W', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJXa0d1OWdSNThmUXRNdjk1cXp1TXdMN29GNXAwVXVNTHZ3MTJuYUNPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIwMDQwOTIyNmExNGQxZjFjMDAzMGQ3MzIzZTg5OWE5NDRmMDYxMzYyZTcwNmUwMjFjYTQ5ZGEwMjAzMGFlYmU3In0=', 1794058017),
  ('ILGzelM43vTGleNN4WW4PPeIjKdSkor05Yjphi3I', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI2c3VPbzhVUkhrRVZiQm1aeHB2TkRaVjFoRWxOZm5MUFI2cUU2RURyIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6ImhvbWUifX0=', 1794058398),
  ('vZ3a2GOJewos59j9ne3LfSopvDPAz1iBTRAhUfiC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'eyJfdG9rZW4iOiJpeXlpQUlYeDIzUlJHT2ZtVFlQbG5QbEVWZ3RkeVhDNDlIRUxqQ0xqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795328933),
  ('iHEPtxlIqjFxi1IHPxk4ZbZXTge9Heg7R6EZrinw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/158.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ1ZHBnejRQYU4zV2VmOGVQdTNkNktMZDJQVWxscUpEbUNnOUhZOG1KIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1795328933),
  ('4UuNdMT0MjelBklblEKm4Bd5L9hvhK1sYzGsv2Y9', NULL, NULL, '', 'eyJfdG9rZW4iOiJMcjBZTjFKWlB5TklTVDJQcXRLY3JnY2hnMTVuVVg3SDlsZ0FBWVdsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzoiLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1795329801);

-- Aktifkan kembali Foreign Key Checks
SET FOREIGN_KEY_CHECKS = 1;
-- Ekspor selesai dengan sukses.
