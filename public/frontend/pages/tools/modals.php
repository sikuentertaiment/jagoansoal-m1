<!-- Top-Up Modal -->
<div id="topupModal" class="modal-overlay">
  <div class="topup-modal">
    <div class="topup-modal-header">
      <h3 data-lang-key="topup.title">Top Up Credits</h3>
      <button class="topup-modal-close" onclick="closeTopUpModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="topup-modal-body">
      <p class="topup-modal-label" data-lang-key="topup.how_many">Berapa credits?</p>
      <div class="topup-slider-container">
        <input type="range" id="topupSlider" class="topup-slider" min="3" max="99" value="6" step="3" oninput="updateTopUpPrice()">
        <div class="topup-slider-values">
          <span>3</span>
          <span>99</span>
        </div>
      </div>
      <div class="topup-amount-display">
        <span id="topupCreditsDisplay">6</span>
        <span class="topup-amount-label" data-lang-key="topup.credits">credits</span>
      </div>
      <div class="topup-quick-select">
        <button class="topup-quick-btn" onclick="setTopUpAmount(6)">6</button>
        <button class="topup-quick-btn" onclick="setTopUpAmount(15)">15</button>
        <button class="topup-quick-btn" onclick="setTopUpAmount(30)">30</button>
        <button class="topup-quick-btn" onclick="setTopUpAmount(60)">60</button>
      </div>
      <div class="topup-price-card">
        <div class="topup-price-row">
          <span data-lang-key="topup.total">Total</span>
          <span id="topupTotalPrice">IDR 2,000</span>
        </div>
      </div>
      <button id="topupNowBtn" class="btn-generate" onclick="handleTopUp()">
        <i class="fas fa-credit-card"></i> <span data-lang-key="topup.now">Top Up Now</span>
      </button>
      <div id="topupError" class="error-msg"></div>
    </div>
  </div>
</div>

<!-- Transaction Detail Modal -->
<div id="txDetailModal" class="modal-overlay">
  <div class="topup-modal" style="max-width:420px;">
    <div class="topup-modal-header">
      <h3 data-lang-key="topup.tx_detail">Detail Transaksi</h3>
      <button class="topup-modal-close" onclick="closeTxDetailModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="topup-modal-body" id="txDetailContent"></div>
  </div>
</div>
