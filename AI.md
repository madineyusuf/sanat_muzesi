>Benle birlikte toplam 3 kişilik bir grupla proje ödevi yapıyoruz, yardımcı olabilir misin? Hocamız proje açıklamasını şöyle vermiş:
(burada ödev açıklaması var kısaca) Açıklama bu kadardı. Biz de arkadaşlarımla şöyle bir proje yapmaya karar verdik: Bir sanat müzesi sitesi yapacağız. Sitede fotoğraflarıyla ve açıklamalarıyla birlikte çeşitli sanat eserleri sergilenecek. Kullanıcı sağ üst köşedeki profil kısmından kayıt yapabilir veya hesabına giriş yapabilir. Hesabına giriş yaptığı zaman da istediği sanat eserlerini favorilerine ekleyebilir ve profilinden favori eserlerini görebilir. Aynı zamanda eserlere yorum atabilir, yani her eserin altında aynı zamanda bir yorumlar kısmı olacak. kullanıcı isterse bu yorumları tıklayarak açabilecek veya geri tıklayıp bu paneli küçültebilecek(ki ekranda boş yere yer kaplamasın) Profil kısmından profil bilgilerini değiştirebilir (isim veya parola gibi). Kısaca böyle bir şey istiyoruz, neleri nasıl yapabiliriz :D Veya görev bölüşümünü bizim için yapabilir misin? Ayrıca bu saydığım istekler hocanın isteklerinin hepsini karşılıyor mu? Karşılamıyorsa neler yapabiliriz?

---

Harika bir fikir! Sanat müzesi konsepti hem görsel olarak Bootstrap ile çok şık duracak hem de hocanın istediği teknik altyapıyı kurmak için fazlasıyla esnek bir zemin sunuyor. Ayrıca sanat eserlerini sergileme fikri projeye çok estetik bir hava katacaktır.

Gelelim sorularına; projenizin hocanın kriterlerini ne kadar karşıladığına, bunu en güvenli şekilde nasıl hayata geçirebileceğinize ve adil bir görev bölüşümünü nasıl yapacağınıza birlikte bakalım.

---

## 1. Hocanın İsteklerini Karşılıyor mu? (Kritik Analiz)

Fikriniz hocanın isteklerinin **%90'ını** doğrudan karşılıyor ancak tam puan almak için **"Yorumlar"** özelliğine küçük bir ayar yapmanız gerekiyor.

Hoca, kullanıcılar tablosundan farklı bir tabloda **CRUD (Oluşturma, Okuma, Güncelleme, Silme)** işlemlerinin tamamının yapılmasını istiyor. Sizin fikrinizde durum şu an şöyle şekilleniyor:

* **Create (Oluşturma):** Eseri favoriye ekleme veya yorum yazma. (Başarılı)
* **Read (Okuma):** Favorileri profil sayfasında listeleme veya yorumları eserin altında gösterme. (Başarılı)
* **Update (Güncelleme):** Profil bilgilerini (isim/parola) değiştirme demişsiniz. **Fakat dikkat!** Hoca, güncelleme işlemini *kullanıcılar tablosundan farklı bir tabloda* istiyor olabilir. Profil güncellemesi `users` tablosunda olduğu için risk almamak adına **"Yorumu Düzenleme"** seçeneği eklemelisiniz.
* **Delete (Silme):** Fikrinizde bir şeyi silmekten bahsetmemişsiniz. Kullanıcının favorilerden bir eseri **çıkarabilmesi** veya yazdığı bir yorumu **silebilmesi** gerekir.

### 💡 Çözüm ve Öneri:

Projenin tam puan alması için "Yorumlar Sistemi"ni ana CRUD merkezi yapın:

1. **C (Create):** Kullanıcı esere yorum ekler.
2. **R (Read):** Yorumlar eserin altında listelenir.
3. **U (Update):** Kullanıcı kendi yazdığı yorumun yanındaki "Düzenle" butonuna basarak yorumunu günceller.
4. **D (Delete):** Kullanıcı kendi yazdığı yorumu "Sil" butonuna basarak kaldırır.

Bu sayede hocanın "farklı bir tabloda CRUD" şartını sıfır riskle yerine getirmiş olursunuz.

---

## 2. Neleri Nasıl Yapabiliriz? (Teknik Yol Haritası)

### Veritabanı Tasarımı (MySQL)

Projeniz için en az 3 (favoriler de olacaksa 4) tabloya ihtiyacınız var:

* `users`: id, username, email, password (hash'lenmiş), created_at
* `artworks`: id, title, artist, description, image_url (Eserlerin sabit duracağı tablo)
* `comments`: id, user_id, artwork_id, comment_text, created_at *(İşte hocanın istediği o meşhur ikinci tablo)*
* `favorites` (Opsiyonel): id, user_id, artwork_id

### Arka Uç (Yalın PHP)

* **Güvenlik:** Kullanıcı kaydederken şifreyi direkt yazmayın. `password_hash($password, PASSWORD_DEFAULT)` kullanın. Giriş yaparken de `password_verify()` ile kontrol edin.
* **Oturum Yönetimi:** Giriş başarılı olduğunda `session_start();` yapıp `$_SESSION['user_id'] = $user['id'];` gibi verileri oturuma atayın. Sayfa başlarında bu session'ı kontrol edin.
* **Bağlantı:** Tüm veritabanı işlemlerini tek bir `db.php` dosyasında PDO kullanarak yapın ve diğer sayfalara `include` edin.

### Ön Uç (Bootstrap & JS)

* **Arayüz:** Bootstrap'in `navbar`, `card` (eserler için) ve `modal` (düzenleme pencereleri için) bileşenlerini kullanın. Hoca "stillendirilmemiş öge kalmasın" dediği için butonlardan input alanlarına kadar her şeye Bootstrap class'ları ekleyin.
* **JavaScript:** Yorumlar panelini daraltıp açmak için Bootstrap'in kendi **Collapse** bileşenini kullanabilirsiniz (hiç JS yazmanıza gerek kalmaz) veya vanilla JS ile küçük bir `click` eventi yazarak `display: none/block` açtırabilirsiniz.

---

## 3. 3 Kişilik Görev Bölüşümü

Projenin adil, çakışmasız ve düzenli ilerlemesi için rolleri şu şekilde bölebilirsiniz:

### 👤 1. Kişi: Backend & Veritabanı Sorumlusu (Altyapı Mimarı)

* **Görevi:** Veritabanını tasarlamak ve tabloları oluşturmak.
* **PHP Kodları:** Kullanıcı kayıt (`register.php`), giriş (`login.php`), çıkış (`logout.php`) işlemlerini yazmak. Şifre hashleme ve Session (oturum) kontrol mekanizmasını kurmak.
* **Teslimat:** Diğer arkadaşlarına çalışan bir login/register sistemi ve veritabanı bağlantı dosyasını (`db.php`) teslim eder.

### 👤 2. Kişi: Frontend & UI/UX Tasarımcısı (Arayüz Ustası)

* **Görevi:** Sitenin tüm görsel tasarımını Bootstrap kullanarak giydirmek.
* **Sayfalar:** Ana sayfa (eserlerin listesi), Eser detay sayfası (görsel, açıklama ve yorum alanı), Profil sayfası, Giriş/Kayıt sayfaları.
* **JavaScript:** Yorum panelinin açılıp kapanma animasyonunu/kodunu yazmak.
* **Teslimat:** Tamamen responsive (mobil uyumlu), hiçbir stillendirilmemiş ögesi kalmamış HTML/CSS şablonlarını hazırlar.

### 👤 3. Kişi: Entegrasyon, CRUD & DevOps Sorumlusu (Full-Stack & Yönetici)

* **Görevi:** 1. Kişinin backend kodları ile 2. Kişinin arayüz tasarımlarını birleştirmek.
* **PHP CRUD Kodları:** Eser detay sayfasında yorumları listeleme (Read), yorum ekleme (Create), yorum silme (Delete) ve yorum düzenleme (Update) işlevlerini yazmak.
* **Proje Yönetimi:** GitHub reposunu yönetmek. Hassas bilgileri (db şifreleri) gizlemek. Projeyi hocanın verdiği hosting alanına yüklemek ve canlıda test etmek. `Readme.md`, ekran görüntüleri, video kaydı ve bu yapay zeka konuşmalarını içerecek olan `AI.md` dosyasını hazırlamak.

---

## ⚠️ Hayati Uyarılar (Hocanın Notlarından)

1. **AI.md Dosyası:** Benimle ve diğer yapay zekalarla yaptığınız bu konuşmayı hemen kopyalayıp projenizin kök dizininde `AI.md` adında bir dosyaya yapıştırın. Süreç boyunca sorduğunuz her şeyi oraya eklemeyi unutmayın.
2. **.htaccess Yasak:** URL güzelleştirmek veya yönlendirmek için kesinlikle `.htaccess` dosyası oluşturmayın, hoca doğrudan sıfır puan veririm demiş.
3. **Git Güvenliği:** Veritabanı kullanıcı adı ve şifrenizi kodun içinde açıkça GitHub'a yüklemeyin. Localde çalışırken `localhost` olan yerleri canlıya alırken sunucu bilgileriyle değiştireceksiniz; GitHub'a yüklemeden önce bu bilgileri `XXXXXX` şeklinde sansürleyin.

Projeye başlamak için hazır mısınız? Eğer hazırsanız, ilk olarak hangi adımdan (örneğin veritabanı tablolarının SQL kodlarını oluşturmak veya Bootstrap master template'ini hazırlamak gibi) başlamak istersiniz?

---

>Şimdi o zaman ilk olarak herkesin ne yapması veya neye başlaması gerekiyor?  Ayrıca görev bölüşümünde sql'i göremedim kim sql yapacak? Ve bize lütfen bize kaç adet yazılması gereken kod dosyası olması gerektiğini tek tek açıklayabilir misin? Yani github repomuzun içinde hangi dosyalar olacak gibi duruyor şu an?

Harika sorular! Projenin ilk günlerinde kafadaki bu soru işaretlerini gidermek, sonradan yaşanacak karmaşayı ve kod çakışmalarını tamamen engeller. Sırasıyla tüm sorularını net bir şekilde yanıtlayayım.

---

### 1. SQL İşini Kim Yapacak? (SQL Rolü)

İlk görev bölüşümünde SQL'i **1. Kişi (Backend & Veritabanı Sorumlusu)** ile **3. Kişi (Entegrasyon & CRUD)** paylaşacak. Bunu şu şekilde düşünebilirsiniz:

* **1. Kişi (Veritabanı Tasarımı - DDL):** Veritabanını kimin oluşturacağını, tabloların (`users`, `artworks`, `comments`) hangi sütunlara sahip olacağını, hangisinin `PRIMARY KEY` hangisinin `FOREIGN KEY` (ilişkili anahtar) olacağını belirler ve boş tabloları oluşturacak SQL kodunu yazar.
* **3. Kişi (Veritabanı Sorguları - DML):** PHP kodlarının içerisine yerleştirilecek olan `SELECT * FROM comments`, `INSERT INTO comments...`, `UPDATE...` ve `DELETE...` gibi veriyi işleyen SQL sorgularını yazar.

---

### 2. İlk Olarak Herkes Neye Başlamalı? (Day 1 Planı)

Bugün bilgisayar başına geçtiğinizde herkesin yapması gereken ilk adımlar şunlardır:

* **Ortak Adım (Herkes):** İçinizden biri bir GitHub reposu açsın ve diğer iki kişiyi "Collaborator" (Katkıcı) olarak davet etsin. Reponun içine şimdiden boş bir `README.md` ve şu anki konuşmamızı içeren bir `AI.md` dosyası oluşturup yükleyin.
* **1. Kişi (Backend):** Bilgisayarına local sunucu (XAMPP veya WampServer gibi) kurmalı. Ardından phpMyAdmin'e girip veritabanını ve tabloları oluşturmalı. İlk iş olarak kayıt (`register.php`) ve giriş (`login.php`) backend mantığını (formdan veriyi al, şifreyi hashle, DB'ye kaydet) yazmaya başlamalı.
* **2. Kişi (Frontend):** Bilgisayarında boş HTML dosyaları açıp Bootstrap CDN bağlantılarını eklemeli. İlk olarak tüm sayfalarda ortak kullanılacak olan **Navbar (Menü)** ve **Footer (Alt Bilgi)** tasarımlarını yapmalı. Ardından ana sayfanın taslak arayüzünü (kartlar içinde sanat eserleri) çizmeye başlamalı.
* **3. Kişi (Koordinasyon & Hazırlık):** Sitede sergileyeceğiniz ilk 5-10 sanat eserinin yüksek kaliteli fotoğraflarını ve açıklamalarını internetten bulup bir klasörde toplamalı. 1. Kişi ile oturup veritabanı tablolarının netleşmesine yardım etmeli.

---

### 3. GitHub Reposunda Hangi Dosyalar Olacak? (Proje Yapısı)

Yalın PHP ile yazılmış, karmaşık olmayan ama profesyonel duran bir GitHub reposu tasarımı şu şekilde olmalıdır. Toplamda yazmanız gereken **yaklaşık 10-12 adet temel dosya** vardır.

İşte projenizin klasör ve dosya yapısı (Yol Haritası):

```text
sanat-muzesi/
│
├── includes/                --> Sürekli tekrarlanan kodları tek yerde toplarız
│   ├── header.php           --> Logonun, Bootstrap linklerinin ve Menünün (Navbar) olduğu dosya
│   └── footer.php           --> Sayfa alt bilgisi ve Bootstrap JS linklerinin olduğu dosya
│
├── assets/                  --> Görsel, CSS ve JS dosyalarımızın klasörü
│   ├── css/
│   │   └── style.css        --> Bootstrap harici çok küçük özel süslemeler gerekirse diye custom CSS
│   ├── js/
│   │   └── script.js        --> Yorum panelini açıp kapatacak JavaScript kodunuz
│   └── images/              --> Müzedeki sanat eserlerinin fotoğrafları (.jpg, .png)
│
├── db.php                   --> Veritabanı bağlantı dosyası (PDO kullanılacak, her sayfaya include edilecek)
├── index.php                --> ANA SAYFA (Müzedeki tüm eserlerin listelendiği yer)
├── eser-detay.php           --> ESER DETAY SAYFASI (Eserin büyük resmi, açıklaması ve yorumlar/CRUD alanı)
│
├── login.php                --> Giriş Yap sayfası ve backend kodları
├── register.php             --> Kayıt Ol sayfası ve backend kodları
├── logout.php               --> Oturumu Kapatıp ana sayfaya yönlendiren kısa PHP kodu
├── profil.php               --> Kullanıcının bilgilerini güncelleyebileceği sayfa
│
├── yorum-islem.php          --> Arka planda yorum ekleme, silme ve düzenleme taleplerini alan gizli backend dosyası
│
├── veritabanı.sql           --> 1. Kişinin oluşturduğu tabloların yedeği (Hoca ve arkadaşlarınız kolayca kursun diye)
├── README.md                --> (Zorunlu) Proje açıklaması, ekran görüntüleri ve video linki
└── AI.md                    --> (Zorunlu) Yapay zeka ile konuşma geçmişiniz

```

### Bu Dosyalar Ne İşe Yarayacak? (Kısa Açıklamalar)

1. **`db.php`:** Projenin kalbidir. Veritabanı kullanıcı adı ve şifresini barındırır. Canlı sunucuya (hosting) geçerken sadece bu dosyanın içindeki şifreleri değiştireceksiniz.
2. **`includes/header.php` ve `footer.php`:** PHP'nin en güzel yanıdır. Menüyü bir kez `header.php` içine yazarsınız; `index.php`, `login.php` gibi sayfaların en üstüne `include 'includes/header.php';` diyerek çağırırsınız. Böylece her sayfaya tek tek menü kodu yazmak zorunda kalmazsınız.
3. **`eser-detay.php`:** En kritik sayfanız burası. Kullanıcı ana sayfada bir esere tıkladığında `eser-detay.php?id=3` gibi bir urlye gidecek. Sayfa, 3 id'li eseri ve o esere ait yorumları veritabanından çekecek. Yorumların yanındaki **"Düzenle"** ve **"Sil"** butonları da bu sayfada olacak.
4. **`yorum-islem.php`:** Bu sayfa ekranda bir arayüz olarak görünmeyecek. Kullanıcı yorum yazıp "Gönder" dediğinde veya "Sil"e bastığında veriler bu dosyaya post edilecek. Bu dosya veritabanı işlemini yapıp kullanıcıyı tekrar `eser-detay.php` sayfasına geri yönlendirecek.

Bu yapı hocanın gözünde tam bir "Yalın (Plain) PHP" projesidir ve klasör düzeni için bile size artı puan kazandırır.

Şimdi hazırsanız, ilk adım olarak **1. Kişi için veritabanı tablolarını oluşturacak SQL kodlarını** yazarak başlayalım mı? Yoksa **2. Kişi için Bootstrap Navbar şablonunu mu** hazırlayalım?