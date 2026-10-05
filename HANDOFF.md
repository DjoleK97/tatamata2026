# HANDOFF — TataMata redizajn (tatamata.rs)

Datum: 2026-10-05 · Repo: `DjoleK97/tatamata2026` · Glavni branch: `master`

Ovaj fajl živi na GitHub-u na branchu **`handoff`** (namerno ne na `master`, jer push na `master` = deploy na produkciju).
- Web: https://github.com/DjoleK97/tatamata2026/blob/handoff/HANDOFF.md
- Iz gita: `git fetch origin handoff && git show origin/handoff:HANDOFF.md`

Kako da ga koristiš: u novoj sesiji (lokalnoj ili cloud) napiši *"Pročitaj HANDOFF.md sa brancha `handoff` u DjoleK97/tatamata2026 i nastavi odatle"*. Novi rad počinji od `origin/master`, ne od `handoff` brancha.
Ceo transkript prethodnih sesija (samo na Djoletovom računaru, ako treba neki detalj):
`C:\Users\Djole\.claude\projects\C--Users-Djole-ClaudeTestFolder--claude-worktrees-elegant-napier\1373649e-337a-4640-aba4-e307482ef46b.jsonl`

---

## 1. Projekat ukratko

- **TataMata** — srpska platforma za online video kurseve matematike (osnovna škola, srednja škola, fakultet).
- **Stack:** obični PHP + MySQL (bez frameworka), Apache, `.htaccess` za lepe URL-ove (`/prijava`, `/kurs/{id}`, `/pocetna`...).
- **Frontend:** Bootstrap 5.0.0-beta1 (CDN), jQuery 2.1.1, Font Awesome 5 (kit), Poppins (Google Fonts), Plyr 3.6.4 (video), Owl Carousel 1.3.3.
- **Mejl:** PHPMailer (composer), SMTP lozinka iz `env.php` (`SMTP_PASS`).
- **Bezbednost (već urađeno ranije, oznaka `SEC-FIX` u kodu):** CSRF (`csrf_protect()`, `csrf_field()`), `safe_redirect()`, `session_regenerate_id`, security headeri + CSP u `includes/header.php`.

### Deploy — VAŽNO
`.github/workflows/deploy.yml`: **svaki push na `master` se automatski deployuje na produkciju** (self-hosted runner → `/var/www/tatamata`, `git reset --hard origin/master`, `composer install`).
→ Push na `master` = odmah uživo na tatamata.rs. Radi na feature branchu i spajaj preko PR-a.

### Alati
- `gh` CLI **nije ulogovan** na ovoj mašini → PR-ove pravi preko GitHub web-a (`https://github.com/DjoleK97/tatamata2026/compare/master...<branch>`).
- Premeštanje sesije u cloud ne radi jer repo nije označen kao "trusted" u Claude Desktop podešavanjima.

---

## 2. Ključni fajlovi

| Fajl | Uloga |
|---|---|
| `includes/header.php` | `<head>`, CDN-ovi, CSP, navbar. Navbar se NE prikazuje na auth stranicama (prijava, registracija, zaboravljena-lozinka, kreiraj-novu-sifru). `#body-container` dobija klasu `home` na `index.php`. |
| `includes/footer.php` | Footer + `script.js`; na prijavi/registraciji učitava `bfp.js` + FingerprintJS (popunjava skrivena polja za uređaj — **ne brisati**, PHP ih čita). |
| `public/css/styles.css` | Ceo dizajn sistem (prepisan u redizajnu). |
| `public/js/script.js` | `.scrolled` na navbaru posle 35px, IntersectionObserver za `.animiraj` → `.vidljiv`, brojači, klipovi, AJAX pretraga, Owl. |
| `public/js/register.js` | **Više se ne učitava** (bio je za registraciju u 3 koraka). Fajl i dalje postoji. |
| `index.php` | Početna: hero, kursevi, usluge, preporuke, FAQ (jedan accordion), kontakt. |
| `prijava.php`, `registracija.php`, `zaboravljena-lozinka.php`, `kreiraj-novu-sifru.php` | Auth stranice — sada jednostavne, centrirane forme. |
| `kursevi.php`, `kurs.php`, `mojikursevi.php`, `profil.php`, `transakcije.php`, `usluga.php` | Redizajnirane u prvoj rundi. |

### Dizajn tokeni (`:root` u styles.css, srpski nazivi)
`--plava #3245f9`, `--plava-tamna`, `--plava-najtamnija #0a1270`, `--zuta #ffe066`, `--tamna #111827`, `--siva-*`, `--radius*`, `--senka*`, `--nav-h 72px`, `--max-sirina 1280px`.
Komponente/klase: `.animiraj/.vidljiv`, `.prazno-stanje` (empty state), `.stranica-header`, `.kurs-breadcrumb`, `.profil-sidebar-nav`, `.bedz-dostupno/.bedz-uskoro`, `.input-ikona`, `.login-form`, `.auth-panel-desno`, `.login-form-container`.

---

## 3. Šta je urađeno (hronološki)

**Runda 1 — kompletan redizajn** (spojeno u `master` kroz PR #1, #2, #3):
CSS dizajn sistem, header/navbar (glassmorphism), footer, početna, prijava, profil, kursevi, kurs (breadcrumb, novi login modal), registracija, zaboravljena/nova lozinka, moji kursevi, transakcije (sidebar, bedževi, kartice na mobilnom), usluga (2 kolone), `script.js`.

**Runda 2 — ispravke po korisnikovim screenshotovima** (commit `55ec0f7`, u `master` preko PR #3):
1. **Bele ivice sa strane na početnoj** — uklonjen `<div class="max-width-90">` koji je u `header.php` obavijao celu početnu (i zatvarajući `</div>` na vrhu `footer.php`). Hero sada ide celom širinom.
2. **Navbar se nije uklapao po bojama** — na početnoj je navbar providan dok si na vrhu (beo logo preko `filter: invert`, beli linkovi, žuti aktivni link, "ghost" dugme Kreiraj nalog); posle skrola prelazi u beli glassmorphism. Sve ostale stranice: uvek beo.
3. **Prijava** — uklonjen plavi levi panel; forma centrirana (max 460px), info tekst "Jedan nalog koristi samo jedna osoba..." ispod forme.
4. **Registracija** — uklonjen levi panel I progress bar u 3 koraka; sva polja u jednoj formi, jedno dugme "Kreiraj nalog" (`type="submit"`). Uklonjeno učitavanje `register.js`.
5. **Zaboravljena lozinka / Nova šifra** — uklonjen levi panel, centrirana forma.
6. `.auth-panel-desno` dobio `flex-direction: column`.

**Posle toga je korisnik sam dodao na `master`** (ja ih nisam pregledao):
`98c952e smal css change`, `581d7e9 logo and favicon update`, `43d8616 New logo svg`, `9e6b7c5 Cache busting za logo i CSS`, `9001c4c popravka srpskih slova i gresaka u dizajnu`.

---

## 4. Trenutno stanje gita

- `origin/master` @ `9001c4c` — sve gore navedeno je tu i deployovano.
- Branch `claude/elegant-napier` je potpuno spojen; nema otvorenog PR-a, nema nespojenog rada.
- Stari worktree (`.claude/worktrees/elegant-napier`) je sada na detached HEAD i iza mastera — **nova sesija treba da krene od svežeg `master`-a** (`git fetch && git checkout -b <novi-branch> origin/master`).
- Jedina lokalna izmena tamo: `.claude/settings.local.json` (lokalna podešavanja, ne commitovati).

---

## 5. Korisnikove preferencije (bitno za nastavak)

- Piše na **srpskom (latinica)**; odgovaraj na srpskom.
- Voli **jednostavno**: bez split-screen layouta, bez formi u više koraka, bez nepotrebnih ukrasa. Rekao je da su auth stranice bile "nepotrebno kompleksne".
- Gleda sajt na **PC-u** i smetaju mu sekcije ograničene širine sa belim prazninama sa strane — pozadine sekcija treba da idu celom širinom.
- Bitno mu je da se boje uklapaju (navbar ↔ plavi hero).
- Šalje screenshotove produkcije kao feedback.

---

## 6. Otvorene stavke / preporuke za sledeću sesiju

Ništa od ovoga korisnik nije eksplicitno tražio — predloži, ne radi bez dogovora:

1. **Bezbednost — open redirect u registraciji:** `registracija.php` radi `header("Location: " . clean($_POST['redirect']))` bez `safe_redirect()` (prijava ga koristi). Lako za ispraviti.
2. **Validacija posle uklanjanja `register.js`:** klijentska validacija (provera zauzetog emaila preko AJAX-a, obavezni checkboxovi) više ne postoji. Server proverava ime/prezime/šifre/zauzet email, ali **checkboxovi za uslove korišćenja se ne proveravaju** (ta provera je zakomentarisana u PHP-u). Razmotriti `required` na checkboxovima ili vraćanje PHP provere.
3. **Tekst greške na prijavi** kaže *Kliknite na "Novi korisnik"* — to dugme se sada zove "Kreiraj nalog".
4. **Mrtav kod:** CSS za `.auth-panel-levo`, `.auth-stats`, `#progressbar`, `.register-progress-item`; fajl `public/js/register.js`.
5. **Nije testirano u browseru lokalno** — izmene runde 2 nisu proverene na lokalnom serveru (nema podešenog dev servera u ovoj sesiji). Proveriti na produkciji: početna (puna širina, providan navbar + skrol, mobilni meni), sve 4 auth stranice, i da registracija stvarno upisuje korisnika (skrivena fingerprint polja).
6. Ranije je korisnik prijavio **404 pri otvaranju sajta** — uzrok nije zabeležen kao rešen u ovoj sesiji; ako se ponovi, proveriti `.htaccess`/rewrite i lokalni server.
