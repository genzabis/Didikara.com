SET @id1 = (SELECT id FROM articles WHERE slug='5-sekolah-sukabumi-perbaikan-fasilitas');
SET @id2 = (SELECT id FROM articles WHERE slug='workshop-peningkatan-kapasitas-guru-daerah-terpencil');
SET @id3 = (SELECT id FROM articles WHERE slug='dialog-publik-membangun-pendidikan-berkualitas-di-desa');
SET @id4 = (SELECT id FROM articles WHERE slug='laporan-tahunan-pemetaan-masalah-pendidikan-2023');
SET @id5 = (SELECT id FROM articles WHERE slug='program-bantuan-buku-untuk-sekolah-terpencil');
SET @id6 = (SELECT id FROM articles WHERE slug='mengajar-di-pelosok-kisah-inspiratif-guru-muda');

INSERT INTO `article_images` (`article_id`,`role`,`url`,`alt`,`position`) VALUES
(@id1,'cover','https://pantaudidik.netlify.app/assets/atapbocor-DCQBDlo3.png','Perbaikan fasilitas 5 sekolah di Sukabumi',1),
(@id2,'cover','https://pantaudidik.netlify.app/assets/relawanmengajar-D56Zzer0.png','Workshop peningkatan kapasitas guru',1),
(@id3,'cover','https://your-cdn.example.com/dialog-publik.jpg','Dialog publik pendidikan di desa',1),
(@id4,'cover','https://bbpmpjatim.kemdikbud.go.id/main/wp-content/uploads/2025/08/WhatsApp-Image-2025-08-29-at-13.26.59_5d61d386-1.jpg','Laporan Tahunan 2023',1),
(@id5,'cover','https://pantaudidik.netlify.app/assets/bantuanbuku-Zk-_-zCc.png','Program bantuan buku',1),
(@id6,'cover','https://your-cdn.example.com/mengajar-pelosok.jpg','Guru muda mengajar di pelosok',1);
