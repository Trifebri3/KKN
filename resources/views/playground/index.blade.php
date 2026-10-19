@extends('layouts.app')

@section('title', 'Playground dan Asisten Virtual | CAP 2026')

@section('content')
<div class="container">
    <div class="form-page-header">
        <a href="{{ route('screen.index') }}" class="btn btn-outline btn-sm" style="margin-bottom: 16px;">
            Kembali ke Layar Utama
        </a>
        <h2 style="font-size: 1.9rem; font-weight: 800; color: var(--slate-dark); margin-bottom: 6px;">
            Playground dan Pusat Informasi
        </h2>
        <p style="font-size: 0.95rem; color: var(--text-muted); max-width: 650px; margin: 0 auto;">
            Pusat tanya jawab Collaborative Action Program (CAP) 2026 serta konsultasi interaktif bersama asisten virtual.
        </p>
    </div>

    <!-- TABS NAVIGATION -->
    <div class="tabs-nav">
        <button type="button" class="tab-btn active" id="tabBtnQna" onclick="switchTab('qna')">
            Tanya Jawab Program
        </button>
        <button type="button" class="tab-btn" id="tabBtnChat" onclick="switchTab('chat')">
            Asisten Virtual
        </button>
    </div>

    <!-- TAB 1: KONTEN QNA -->
    <div class="tab-pane active" id="paneQna">
        <div class="container-narrow">
            <!-- Search Box -->
            <div class="qna-search-box">
                <input type="text" id="qnaSearchInput" class="qna-search-input" placeholder="Cari pertanyaan, data sampah, Bale Resik, fasilitas, atau anggaran..." onkeyup="filterFaqs()">
            </div>

            <!-- Category Pills Filter -->
            <div class="category-filter-bar" id="categoryFilterBar">
                <button type="button" class="category-chip active" onclick="filterCategory('all', this)">
                    Semua Kategori ({{ $faqs->count() }})
                </button>
                @foreach($categories as $cat)
                    <button type="button" class="category-chip" onclick="filterCategory('{{ $cat }}', this)">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

            <!-- FAQ Accordion List -->
            <div class="faq-list" id="faqList">
                @forelse($faqs as $faq)
                    <div class="faq-card" data-category="{{ $faq->kategori }}" data-question="{{ strtolower($faq->pertanyaan) }}" data-answer="{{ strtolower($faq->jawaban) }}">
                        <button type="button" class="faq-trigger" onclick="toggleFaq(this)">
                            <div>
                                <div class="faq-q-text">{{ $faq->pertanyaan }}</div>
                                <div class="faq-badges">
                                    <span class="faq-cat-badge">{{ $faq->kategori }}</span>
                                    @if($faq->badge)
                                        <span class="faq-highlight-badge">{{ $faq->badge }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="faq-icon-arrow">&#9660;</span>
                        </button>

                        <div class="faq-content">
                            <div>{{ $faq->jawaban }}</div>

                            <div class="faq-footer-actions">
                                <button type="button" class="faq-like-btn" onclick="likeFaq({{ $faq->id }}, this)">
                                    Membantu (<span class="like-num">{{ $faq->likes_count }}</span>)
                                </button>
                                <span style="font-size: 0.76rem; color: var(--text-dim);">Dokumentasi Resmi CAP 2026</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; color: var(--text-dim);">
                        Belum ada data materi tanya jawab.
                    </div>
                @endforelse
            </div>

            <!-- Ask Question Box -->
            <div class="card" style="text-align: center; padding: 24px; margin-bottom: 50px;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--slate-dark); margin-bottom: 6px;">
                    Memerlukan Informasi Tambahan?
                </h4>
                <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 16px;">
                    Apabila pertanyaan Anda belum tercantum dalam daftar di atas, Anda dapat mengirimkan pertanyaan baru kepada pengelola.
                </p>
                <button type="button" class="btn btn-primary btn-sm" onclick="openAskModal()">
                    Ajukan Pertanyaan Baru
                </button>
            </div>
        </div>
    </div>

    <!-- TAB 2: CHATBOT / VIRTUAL ASISTEN -->
    <div class="tab-pane" id="paneChat">
        <div class="chat-container">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-bot-profile">
                    <div class="bot-avatar">CAP</div>
                    <div class="bot-info">
                        <h3>Asisten Virtual CAP 2026</h3>
                        <p>Aktif • Layanan Konsultasi Otomatis</p>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-outline btn-sm" onclick="clearChatHistory()" title="Bersihkan riwayat percakapan">
                        Bersihkan Riwayat
                    </button>
                </div>
            </div>

            <!-- Chat Messages Box -->
            <div class="chat-messages" id="chatMessages">
                <!-- Bot Greeting -->
                <div class="message bot">
                    <div class="bot-avatar">CAP</div>
                    <div>
                        <div class="msg-bubble">
                            Selamat datang di layanan <strong>Asisten Virtual Collaborative Action Program (CAP) 2026</strong>.<br><br>
                            Layanan ini dapat memberikan penjelasan seputar laporan kegiatan, antara lain:
                            <br>• Hasil pengumpulan <strong>372 kg sampah pesisir</strong> dan pengelolaan TPS3R
                            <br>• Profil dan pembagian tugas <strong>kelompok Bale Resik</strong> (12 anggota)
                            <br>• Pengoperasian rumah organik dan fasilitas pengolahan residu
                            <br>• Direktori promosi <strong>purbayani-eco-tourism.siyota.org</strong> (83 UMKM, 37 titik lokasi)
                            <br>• Rincian anggaran program <strong>Rp15.607.041</strong>
                            <br>• Petunjuk presensi kunjungan tamu dan opsi mode anonim
                            <br><br>Silakan sampaikan pertanyaan Anda melalui kolom di bawah ini atau pilih salah satu topik yang tersedia.
                        </div>
                        <div class="msg-time">{{ now()->format('H:i') }}</div>
                    </div>
                </div>

                <!-- Existing messages from Database -->
                @foreach($chatHistory as $msg)
                    <div class="message {{ $msg->sender }}">
                        @if($msg->sender === 'bot')
                            <div class="bot-avatar">CAP</div>
                        @endif
                        <div>
                            <div class="msg-bubble">{!! nl2br(e($msg->message)) !!}</div>
                            <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Suggestion Chips -->
            <div class="chat-suggestions" id="chatSuggestions">
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Berapa sampah pesisir yang dikumpulkan?')">
                    Sampah Pesisir
                </button>
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Apa itu kelompok Bale Resik?')">
                    Kelompok Bale Resik
                </button>
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Berapa total anggaran program CAP 2026?')">
                    Total Anggaran
                </button>
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Fasilitas apa saja yang dibangun?')">
                    Fasilitas Pengolahan
                </button>
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Informasi portal purbayani-eco-tourism')">
                    Portal Wisata Purbayani
                </button>
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Bagaimana prosedur keamanan tungku minim asap?')">
                    Keamanan Tungku Residu
                </button>
                <button type="button" class="suggestion-chip" onclick="sendPrompt('Bagaimana cara presensi tanpa nama atau anonim?')">
                    Presensi Tamu Anonim
                </button>
            </div>

            <!-- Chat Input Bar -->
            <form id="chatForm" onsubmit="handleChatSubmit(event)" class="chat-input-bar">
                <input type="text" id="chatInput" class="chat-input" placeholder="Ketik pertanyaan Anda tentang program CAP 2026 di sini..." autocomplete="off">
                <button type="submit" class="chat-send-btn" id="chatSendBtn">
                    Kirim
                </button>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: AJUKAN PERTANYAAN BARU -->
<div class="modal-overlay" id="askModal">
    <div class="modal-card">
        <div class="modal-header">
            <h4 class="modal-title">Ajukan Pertanyaan Baru</h4>
            <button type="button" class="modal-close" onclick="closeAskModal()">&times;</button>
        </div>

        <form id="askForm" onsubmit="handleAskSubmit(event)">
            @csrf
            <div class="form-group">
                <label class="form-label" for="askNama">Nama Anda <span class="opt">(Bisa dikosongkan untuk Anonim)</span></label>
                <input type="text" id="askNama" class="form-input" placeholder="Masukkan nama Anda (atau biarkan kosong)">
            </div>

            <div class="form-group">
                <label class="form-label" for="askContact">Email atau Nomor Telepon <span class="opt">(Opsional untuk balasan)</span></label>
                <input type="text" id="askContact" class="form-input" placeholder="08xxxxxxxxxx atau email@anda.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="askPertanyaan">Isi Pertanyaan <span class="req">*</span></label>
                <textarea id="askPertanyaan" class="form-textarea" rows="3" required placeholder="Tuliskan pertanyaan seputar program CAP 2026..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block" id="askSubmitBtn" style="padding: 12px;">
                Kirim Pertanyaan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const btnQna = document.getElementById('tabBtnQna');
        const btnChat = document.getElementById('tabBtnChat');
        const paneQna = document.getElementById('paneQna');
        const paneChat = document.getElementById('paneChat');

        if (tab === 'chat') {
            btnQna.classList.remove('active');
            btnChat.classList.add('active');
            paneQna.classList.remove('active');
            paneChat.classList.add('active');
            window.location.hash = 'chat';
            scrollChatToBottom();
        } else {
            btnChat.classList.remove('active');
            btnQna.classList.add('active');
            paneChat.classList.remove('active');
            paneQna.classList.add('active');
            window.location.hash = 'qna';
        }
    }

    if (window.location.hash === '#chat') {
        switchTab('chat');
    }

    function toggleFaq(btn) {
        const card = btn.closest('.faq-card');
        card.classList.toggle('open');
    }

    function filterFaqs() {
        const query = document.getElementById('qnaSearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.faq-card');

        cards.forEach(card => {
            const q = card.getAttribute('data-question') || '';
            const a = card.getAttribute('data-answer') || '';
            if (q.includes(query) || a.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterCategory(category, chip) {
        document.querySelectorAll('.category-chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');

        const cards = document.querySelectorAll('.faq-card');
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (category === 'all' || cardCat === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function likeFaq(id, btn) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/playground/faq/${id}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            }
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const countEl = btn.querySelector('.like-num');
                if (countEl) countEl.textContent = res.likes_count;
            }
        })
        .catch(err => console.error(err));
    }

    const sessionId = '{{ $sessionId }}';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function scrollChatToBottom() {
        const container = document.getElementById('chatMessages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function sendPrompt(text) {
        document.getElementById('chatInput').value = text;
        handleChatSubmit(new Event('submit'));
    }

    function handleChatSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        const messagesBox = document.getElementById('chatMessages');
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });

        const userHtml = `
            <div class="message user">
                <div>
                    <div class="msg-bubble">${escapeHtml(message)}</div>
                    <div class="msg-time">${timeStr}</div>
                </div>
            </div>
        `;
        messagesBox.insertAdjacentHTML('beforeend', userHtml);
        input.value = '';
        scrollChatToBottom();

        const typingId = 'typing_' + Date.now();
        const typingHtml = `
            <div class="message bot" id="${typingId}">
                <div class="bot-avatar">CAP</div>
                <div>
                    <div class="msg-bubble" style="font-style: italic; color: var(--text-dim);">
                        Menyiapkan jawaban...
                    </div>
                </div>
            </div>
        `;
        messagesBox.insertAdjacentHTML('beforeend', typingHtml);
        scrollChatToBottom();

        fetch('{{ route('playground.chat') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                message: message,
                session_id: sessionId,
            })
        })
        .then(res => res.json())
        .then(res => {
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();

            if (res.status === 'success') {
                const replyData = res.data;
                const formattedText = formatMarkdown(replyData.reply);

                const botHtml = `
                    <div class="message bot">
                        <div class="bot-avatar">CAP</div>
                        <div>
                            <div class="msg-bubble">${formattedText}</div>
                            <div class="msg-time">${replyData.created_at || timeStr}</div>
                        </div>
                    </div>
                `;
                messagesBox.insertAdjacentHTML('beforeend', botHtml);
                scrollChatToBottom();

                if (replyData.suggestions && replyData.suggestions.length > 0) {
                    const suggBox = document.getElementById('chatSuggestions');
                    let sHtml = '';
                    replyData.suggestions.forEach(s => {
                        sHtml += `<button type="button" class="suggestion-chip" onclick="sendPrompt('${escapeHtml(s)}')">${escapeHtml(s)}</button>`;
                    });
                    suggBox.innerHTML = sHtml;
                }
            }
        })
        .catch(err => {
            console.error('Chat error:', err);
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();
        });
    }

    function clearChatHistory() {
        if (!confirm('Apakah Anda ingin mengosongkan riwayat percakapan ini?')) return;

        fetch('{{ route('playground.clear_chat') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ session_id: sessionId })
        })
        .then(res => res.json())
        .then(res => {
            window.location.reload();
        });
    }

    function openAskModal() {
        document.getElementById('askModal').classList.add('open');
    }

    function closeAskModal() {
        document.getElementById('askModal').classList.remove('open');
    }

    function handleAskSubmit(e) {
        e.preventDefault();
        const nama = document.getElementById('askNama').value;
        const email_or_phone = document.getElementById('askContact').value;
        const pertanyaan = document.getElementById('askPertanyaan').value;
        const submitBtn = document.getElementById('askSubmitBtn');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirimkan...';

        fetch('{{ route('playground.ask') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                nama: nama,
                email_or_phone: email_or_phone,
                pertanyaan: pertanyaan,
            })
        })
        .then(res => res.json())
        .then(res => {
            alert('Pertanyaan Anda telah kami terima dan akan ditinjau oleh tim pengelola.');
            closeAskModal();
            document.getElementById('askForm').reset();
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Pertanyaan';
        })
        .catch(err => {
            alert('Terjadi kendala saat mengirimkan pertanyaan. Silakan coba kembali.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Pertanyaan';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatMarkdown(text) {
        let escaped = escapeHtml(text);
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        escaped = escaped.replace(/\n/g, '<br>');
        return escaped;
    }
</script>
@endpush
