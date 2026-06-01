## Sanat Müzesi

* Feyza Yavuz: Kimlik Doğrulama ve Güvenlik Modülü
Genel Sorumluluk: Sistemin kullanıcı yönetim, güvenlik ve oturum kontrol süreçlerinin hem backend mimarisini kurmak hem de frontend arayüzünü tasarlamak.
Veritabanı ve Backend Yapılandırması (PHP & MySQL):
MySQL/MariaDB üzerinde kullanıcı verilerini tutacak olan kullanicilar tablosunun şemasını tasarlamak ve oluşturmak.
Kullanıcı kayıt sistemini (register.php) geliştirmek; güvenli veri alımı ve şifrelerin password_hash() fonksiyonu kullanılarak kriptografik hiyerarşide veritabanına kaydedilmesini sağlamak.
Oturum açma (login.php) işlemlerini, veritabanındaki hash'lenmiş veriyi password_verify() ile kontrol ederek ve PHP sessions (session_start()) mekanizmasını kullanarak doğrulamak.
Güvenli çıkış (logout.php) betiğini yazarak oturum verilerini (session_destroy()) temizlemek.
Yönetim sayfalarını koruma altına alan ve yetkisiz (giriş yapmamış) kullanıcıları doğrudan login sayfasına yönlendiren güvenlik kontrol filtrelerini geliştirmek.
Arayüz Tasarımı ve Entegrasyon (Bootstrap Frontend):
Kullanıcı giriş ve kayıt formlarını Bootstrap bileşenleri (Cards, Forms, Validation, Buttons) kullanarak tamamen stillendirmek ve mobil uyumlu (responsive) hale getirmek.
Sisteme başarılı giriş yapıldığında kullanıcıyı karşılayan, müze verilerine ait temel istatistikleri barındıran şık bir yönetim paneli (Dashboard) ana sayfası tasarlamak.
* Nurseza Karakaya: Koleksiyon Yönetimi ve Listeleme Modülü (Create & Read)
Genel Sorumluluk: Müzedeki sanat eserlerinin sisteme dinamik olarak dahil edilmesi ve galeride modern bir mimariyle sergilenmesi süreçlerinin full-stack geliştirilmesini üstlenmek.
Veritabanı ve Backend Yapılandırması :
Sanat eserlerine ait detaylı bilgileri (Eser Adı, Sanatçı, Yapım Yılı, Sanat Türü, Açıklama) depolayacak olan eserler tablosunu MySQL üzerinde oluşturmak.
Yeni sanat eseri ekleme formundan gelen verileri SQL enjeksiyonlarına karşı güvenli hale getirerek PDO bağlantısı ile veritabanına yazma (Create) mantığını kurmak.
Veritabanında kayıtlı olan tüm müze envanterini dinamik olarak çeken SQL SELECT sorgularını ve backend listeleme mantığını (Read) yazmak.
Arayüz Tasarımı ve Entegrasyon (Bootstrap Frontend):
Yeni eser ekleme sayfasının (eser-ekle.php) Bootstrap form elemanları, seçici menüler (<select>) ve metin alanları (<textarea>) kullanarak görsel tasarımını yapmak.
Müze koleksiyonunun sergilendiği ana galeri sayfasını (eserler.php), Bootstrap Cards (Kart yapıları) kullanarak estetik bir düzende listelemek.
Bootstrap Grid sistemi (row-cols-md-3) kullanarak, kartların büyük ekranlarda yan yana 3 adet listelenmesini, mobil ekranlarda ise alt alta esnek bir şekilde hizalanmasını (responsive) sağlamak.
* Madina Yusupova: Veri Modifikasyonu, Sistem Entegrasyonu ve DevOps (Update, Delete & Deploy)
Genel Sorumluluk: Mevcut verilerin yaşam döngüsünü yönetmek (düzenleme/silme), JavaScript entegrasyonlarını tamamlamak, tüm projeyi birleştirmek ve canlı sunucuya (hosting) taşımak.
Veritabanı ve Backend Yapılandırması:
Seçilen bir sanat eserinin benzersiz kimlik değerine (id) göre veritabanından kalıcı olarak kaldırılmasını sağlayan backend algoritmasını (eser-sil.php) yazmak (Delete).
Düzenlenecek eserin mevcut verilerini GET parametresi ile yakalayıp veritabanından çeken ve güncellenen yeni verileri UPDATE sorgusuyla kaydeden dinamik yapıyı kurmak (Update).
Tüm geliştiricilerin ortak kullanacağı, PDO sürücüsüne dayalı ana veritabanı bağlantı dosyasını (db.php) ve hata yakalama (try-catch) bloklarını inşa etmek.
Arayüz, JavaScript Entegrasyonu ve Canlıya Alma (Bootstrap & DevOps):
Eser düzenleme sayfasının (eser-duzenle.php) arayüzünü tasarlamak; veritabanından gelen eski verilerin form alanlarına otomatik olarak (value öznitelikleriyle) dolu gelmesini sağlamak.
Kullanıcının yanlışlıkla veri silmesini önlemek adına Bootstrap Modals (Açılır Pencere) bileşenini yerel JavaScript kodları ile entegre ederek "Silme Onay Mekanizması" geliştirmek.
Projenin GitHub deposunu yönetmek, hassas verileri (şifreler, API anahtarları) sansürlemek, Readme.md ve yapay zeka sohbet geçmişini içeren AI.md dosyalarını eksiksiz hazırlamak.
Uygulamayı yerel sunucudan (localhost) üniversitenin tahsis ettiği canlı hosting alanına FTP kullanarak taşımak ve canlı ortam veritabanı konfigürasyonlarını hatasız tamamlamak.
