<style>
#page-contact .page-card {
  max-width: 768px;
  margin: 0 auto;
  padding: 2rem;
}
#page-contact .contact-grid {
  display: grid;
  /*grid-template-columns: 1fr 1fr;*/
  gap: 1.5rem;
}
#page-contact .info-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
  margin-bottom: 1.5rem;
}
#page-contact .info-item {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid #f3f4f6;
}
#page-contact .info-item:last-child {
  border-bottom: none;
}
#page-contact .info-item .icon-box {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  font-size: 1.125rem;
}
#page-contact .info-item h4 {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.25rem;
}
#page-contact .info-item p {
  font-size: 0.9375rem;
  color: #1f2937;
  font-weight: 500;
}
#page-contact .form-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
#page-contact .form-card .form-group {
  margin-bottom: 1.25rem;
}
#page-contact .form-card label {
  display: block;
  font-size: 0.8125rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.375rem;
}
#page-contact .form-card input,
#page-contact .form-card textarea {
  width: 100%;
  padding: 0.625rem 0.875rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.875rem;
  color: #1f2937;
  background: #fff;
  outline: none;
  font-family: inherit;
  transition: border-color 0.15s;
}
#page-contact .form-card input:focus,
#page-contact .form-card textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
#page-contact .form-card textarea {
  resize: vertical;
  min-height: 120px;
}
#page-contact .form-card button[type="submit"] {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.75rem 1.5rem;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 0.9375rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s, transform 0.15s;
}
#page-contact .form-card button[type="submit"]:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}
#page-contact .form-card button[type="submit"]:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}
#page-contact .chat-card {
  background: linear-gradient(135deg, #25D366, #128C7E);
  border-radius: 12px;
  padding: 1.5rem;
  color: #fff;
  text-align: center;
}
#page-contact .chat-card i {
  font-size: 2.5rem;
  margin-bottom: 0.75rem;
}
#page-contact .chat-card h3 {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}
#page-contact .chat-card p {
  font-size: 0.875rem;
  opacity: 0.9;
  margin-bottom: 1rem;
  line-height: 1.6;
}
#page-contact .chat-card a {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: rgba(255,255,255,0.2);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 8px;
  color: #fff;
  font-size: 0.875rem;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.15s;
}
#page-contact .chat-card a:hover {
  background: rgba(255,255,255,0.3);
}
#page-contact .success-msg {
  display: none;
  font-size: 0.875rem;
  color: #059669;
  padding: 0.75rem 1rem;
  background: #ecfdf5;
  border-radius: 8px;
  margin-top: 1rem;
}
#page-contact .success-msg.show { display: block; }
@media (max-width: 640px) {
  #page-contact .contact-grid { grid-template-columns: 1fr; }
  #page-contact .page-card { padding: 1.25rem; }
}
</style>

<div id="page-contact" class="page">
  <div class="page-card" style="max-width:768px;margin:0 auto;padding:2rem;">

    <div style="text-align:center;margin-bottom:2rem;">
      <h1 style="font-size:1.875rem;font-weight:700;color:#111827;margin-bottom:0.5rem;">Hubungi Kami</h1>
      <p style="font-size:1rem;color:#6b7280;max-width:550px;margin:0 auto;line-height:1.7;" data-lang-key="contact.subtitle">Punya pertanyaan, saran, atau butuh bantuan? Tim kami siap membantu Anda.</p>
    </div>

    <div class="contact-grid">
      <div>
        <div class="info-card">
          <h3 style="font-size:1.125rem;font-weight:600;color:#1f2937;margin-bottom:0.75rem;" data-lang-key="contact.info.title">Informasi Kontak</h3>
          <div class="info-item">
            <div class="icon-box" style="background:rgba(37,99,235,0.1);color:#2563eb;">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <h4 data-lang-key="contact.email">Email</h4>
              <p data-lang-key="contact.email.value">gemalagifrominfinitydreams@gmail.com</p>
            </div>
          </div>
          <div class="info-item">
            <div class="icon-box" style="background:rgba(37,211,102,0.1);color:#25D366;">
              <i class="fab fa-whatsapp"></i>
            </div>
            <div>
              <h4 data-lang-key="contact.whatsapp">WhatsApp</h4>
              <p data-lang-key="contact.whatsapp.value">+62 822-8099-4738</p>
            </div>
          </div>
        </div>

        <div class="chat-card">
          <i class="fab fa-whatsapp"></i>
          <h3 data-lang-key="contact.chat.title">Chat dengan Admin</h3>
          <p data-lang-key="contact.chat.desc">Ingin berbicara langsung? Klik tombol di bawah untuk memulai chat dengan tim kami melalui WhatsApp.</p>
          <a href="https://wa.me/6282280994738" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp"></i> <span data-lang-key="contact.chat.btn">Chat WhatsApp</span>
          </a>
        </div>
      </div>

      <div class="hidden">
        <div class="form-card">
          <h3 style="font-size:1.125rem;font-weight:600;color:#1f2937;margin-bottom:1rem;" data-lang-key="contact.form.title">Kirim Pesan</h3>

          <div class="form-group">
            <label data-lang-key="contact.form.name">Nama Lengkap</label>
            <input type="text" id="contactName" data-lang-key="contact.form.name_placeholder" placeholder="Masukkan nama Anda">
          </div>

          <div class="form-group">
            <label data-lang-key="contact.form.email">Email</label>
            <input type="email" id="contactEmail" data-lang-key="contact.form.email_placeholder" placeholder="Masukkan email Anda">
          </div>

          <div class="form-group">
            <label data-lang-key="contact.form.message">Pesan</label>
            <textarea id="contactMessage" data-lang-key="contact.form.message_placeholder" placeholder="Tulis pesan Anda di sini..."></textarea>
          </div>

          <button type="submit" id="contactSubmitBtn" onclick="submitContactForm()">
            <i class="fas fa-paper-plane"></i>
            <span data-lang-key="contact.form.submit">Kirim Pesan</span>
          </button>

          <div id="contactSuccess" class="success-msg">
            <i class="fas fa-check-circle"></i>
            <span data-lang-key="contact.form.success">Pesan berhasil dikirim! Kami akan menghubungi Anda segera.</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function submitContactForm() {
  var btn = document.getElementById('contactSubmitBtn');
  var name = document.getElementById('contactName').value.trim();
  var email = document.getElementById('contactEmail').value.trim();
  var message = document.getElementById('contactMessage').value.trim();

  if (!name || !email || !message) {
    showAlert('Form Tidak Lengkap', 'Harap isi semua field', 'warning');
    return;
  }

  btn.disabled = true;
  btn.querySelector('span').setAttribute('data-lang-key', 'contact.form.sending');
  btn.querySelector('span').textContent = window.t('contact.form.sending');

  // Simulate send (replace with actual API call)
  setTimeout(function() {
    document.getElementById('contactSuccess').classList.add('show');
    btn.disabled = false;
    btn.querySelector('span').setAttribute('data-lang-key', 'contact.form.submit');
    btn.querySelector('span').textContent = window.t('contact.form.submit');
    document.getElementById('contactName').value = '';
    document.getElementById('contactEmail').value = '';
    document.getElementById('contactMessage').value = '';
  }, 1500);
}
</script>
