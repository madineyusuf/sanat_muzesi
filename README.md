# ▶︎ Sanat Müzesi Web Uygulaması 

Dünyanın en ünlü sanat eserlerini keşfedebileceğiniz, kategorilerine göre filtreleyip arayabileceğiniz, beğendiğiniz eserleri favorilerinize ekleyip toplulukla etkileşime geçebileceğiniz modern ve güvenli bir web uygulamasıdır

 **Canlı Demo:** [http://95.130.171.20/~st24360859922](http://95.130.171.20/~st24360859922)
 
 **Tanıtım Videosu:** [https://youtu.be/y-5IXS5a_pA?si=sFvzvGth1foneC3p](https://youtu.be/y-5IXS5a_pA?si=sFvzvGth1foneC3p)
 
---

##  Geliştiriciler
- **Nurseza Karakaya** - 24360859038
- **Madina Yusupova** - 24360859922
- **Feyza Yavuz** - 24360859055

---

## Ekran Görüntüleri

### Ana Sayfa
![Ana Sayfa](assets/images/anasayfa.png)

### Eser Detay Sayfası
![Eser Detay Sayfası](assets/images/detay1.png)
![Eser Detay Sayfası](assets/images/detay2.png)

### Kullanıcı Giriş Sayfası ve Profili
![Kullanıcı Giriş](assets/images/giris.png)
![Kullanıcı Giriş](assets/images/favori.png)

---


##  Özellikler 

- **Kullanıcı Yönetimi:** Güvenli kayıt olma, giriş yapma ve oturum (Session) yönetimi
- **Profil Güncelleme:** Kullanıcıların kendi kullanıcı adlarını ve şifrelerini güncelleyebileceği profil paneli
- **Dinamik Listeleme ve Arama:** Sanat eserlerini isme veya sanatçıya göre arama, türlerine göre filtreleme
- **Gelişmiş Etkileşim:** - Asenkron (AJAX / Fetch API) çalışan favorilere ekleme ve çıkarma sistemi
  - Eserlerin altına yorum yapma ve kullanıcıların yalnızca kendi yorumlarını silebilmesini sağlayan yetkilendirme mekanizması
- **Güvenlik Odaklı Mimari:**
  - SQL Injection saldırılarına karşı **PDO Prepared Statements** kullanımı
  - XSS (Cross-Site Scripting) saldırılarını önlemek için **HTML Purifier / htmlspecialchars** koruması
  - Şifrelerin veritabanında güvenli bir şekilde **`password_hash()`** (BCrypt) ile saklanması

---

## Kullanılan Teknolojiler 

- **Backend:** PHP 8.x
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript 
- **UI Framework:** Bootstrap 5 & Bootstrap Icons
- **Typography:** Playfair Display & Lato (Google Fonts)

---

## Veritabanı Mimarisi 

Proje ilişkisel bir veritabanı modeli üzerine kurulmuştur ve 4 temel tablodan oluşmaktadır:

| Tablo Adı | Açıklama | Temel Alanlar |
| :--- | :--- | :--- |
| `kullanicilar` | Kayıtlı kullanıcıların bilgilerini ve şifre hash'lerini tutar. | `id`, `kullanici_adi`, `email`, `sifre_hash` |
| `eserler` | Müzedeki sanat eserlerinin detaylarını barındırır. | `id`, `eser_adi`, `sanatci`, `yil`, `tur`, `aciklama`, `resim_url` |
| `favoriler` | Kullanıcılar ve eserler arasındaki beğeni ilişkisini tutar. | `id`, `kullanici_id`, `eser_id` |
| `yorumlar` | Eserlere yapılan kullanıcı yorumlarını ve tarihlerini saklar. | `id`, `kullanici_id`, `eser_id`, `icerik`, `olusturma_tarihi` |

---

## Kurulum ve Çalıştırma 

Projeyi yeral ortamınızda (XAMPP, WampServer vb.) veya bir uzak sunucuda çalıştırmak için aşağıdaki adımları takip edin:

### 1. Projeyi Klonlayın
```bash
git clone [https://github.com/kullanici_adi/repo_adi.git](https://github.com/kullanici_adi/repo_adi.git)
cd repo_adi
