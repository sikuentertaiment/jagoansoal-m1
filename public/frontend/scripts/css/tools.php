<style>
:root {
  --primary: #2563EB;
  --primary-light: #3B82F6;
  --primary-dark: #1D4ED8;
  --primary-bg: rgba(37, 99, 235, 0.08);
  --secondary: #10B981;
  --secondary-light: #34D399;
  --secondary-dark: #059669;
  --secondary-bg: rgba(16, 185, 129, 0.08);
  --gray-50: #F9FAFB;
  --gray-100: #F3F4F6;
  --gray-200: #E5E7EB;
  --gray-300: #D1D5DB;
  --gray-400: #9CA3AF;
  --gray-500: #6B7280;
  --gray-600: #4B5563;
  --gray-700: #374151;
  --gray-800: #1F2937;
  --gray-900: #111827;
  --red-500: #EF4444;
  --red-600: #DC2626;
  --green-500: #10B981;
  --orange-500: #F59E0B;
  --blue-500: #3B82F6;
}

#page-tools {
  display: none;
  max-width: 1152px;
  margin: 0 auto;
  width: 100%;
}
#page-tools.active {
  display: block;
}

.tools-layout {
  display: flex;
  gap: 20px;
  min-height: calc(100vh - 55px);
  position: relative;
}

.tools-sidebar {
  width: 200px;
  min-width: 200px;
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(16px) saturate(180%);
  -webkit-backdrop-filter: blur(16px) saturate(180%);
  border: 1px solid rgba(0,0,0,0.06);
  border-radius: 10px;
  display: flex;
  flex-direction: column;
  padding: 8px 0;
  position: sticky;
  top: 63px;
  align-self: flex-start;
  height: calc(100vh - 79px);
  overflow-y: auto;
  z-index: 50;
  box-shadow: 0 1px 4px rgba(0,0,0,0.02);
}

.tools-sidebar-header {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: var(--gray-400);
  padding: 6px 16px 10px;
}

.tools-nav-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 7px 16px;
  color: var(--gray-600);
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.15s ease;
  border-left: 3px solid transparent;
}
.tools-nav-link:hover {
  background: var(--gray-50);
  color: var(--gray-800);
}
.tools-nav-link.active {
  color: var(--primary);
  background: var(--primary-bg);
  border-left-color: var(--primary);
  font-weight: 600;
}
.tools-nav-link i {
  width: 16px;
  text-align: center;
  font-size: 13px;
}

  .sidebar-overlay {
    display: none;
    position: fixed;
    top: 55px;
    left: 0;
    width: 100%;
    height: calc(100vh - 55px);
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 99;
  }
  .sidebar-overlay.open {
    display: block;
  }

.tools-content {
  flex: 1;
  min-width: 0;
  padding: 20px;
}

.tools-panel {
  display: none;
}
.tools-panel.active {
  display: block;
}

.panel-header {
  font-size: 18px;
  font-weight: 600;
  color: var(--gray-800);
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.panel-header i {
  color: var(--primary);
}

.panel-card {
  background: #fff;
  border: 1px solid var(--gray-200);
  border-radius: 10px;
  padding: 16px;
}

.form-group {
  margin-bottom: 14px;
}

.form-label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: var(--gray-700);
  margin-bottom: 4px;
}
.form-label i {
  margin-right: 4px;
  color: var(--primary);
}

.form-input, .form-select, .form-textarea {
  width: 100%;
  padding: 8px 12px;
  border: 1.5px solid var(--gray-200);
  border-radius: 8px;
  font-size: 13px;
  color: var(--gray-800);
  background: #fff;
  transition: border-color 0.15s ease;
  outline: none;
  font-family: inherit;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.form-help {
  font-size: 12px;
  color: var(--gray-400);
  margin: 4px 0 8px;
}

/* Tooltip */
.form-label .tip {
  position: relative;
  display: inline-flex;
  align-items: center;
  margin-left: 5px;
  color: var(--gray-400);
  cursor: help;
  font-size: 13px;
  vertical-align: middle;
  transition: color 0.15s;
}
.form-label .tip:hover { color: var(--primary); }
.form-label .tip .tip-text {
  position: absolute;
  bottom: calc(100% + 8px);
  left: 50%;
  transform: translateX(-50%) translateY(4px);
  background: #1f2937;
  color: #fff;
  font-size: 11px;
  line-height: 1.45;
  padding: 8px 12px;
  border-radius: 8px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: all 0.15s ease;
  max-width: 280px;
  white-space: normal;
  text-align: center;
  font-weight: 400;
  z-index: 2000;
}
.form-label .tip .tip-text::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border: 5px solid transparent;
  border-top-color: #1f2937;
}
.form-label .tip:hover .tip-text {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}

.form-error {
  font-size: 11px;
  color: var(--red-500);
  margin-top: 3px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

@media (max-width: 640px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}

.card-select-group {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.card-select {
  flex: 1;
  min-width: 100px;
  padding: 10px 12px;
  border: 2px solid var(--gray-200);
  border-radius: 8px;
  text-align: center;
  cursor: pointer;
  transition: all 0.15s ease;
  background: #fff;
  user-select: none;
}
.card-select:hover {
  border-color: var(--gray-300);
  background: var(--gray-50);
}
.card-select.selected {
  border-color: var(--primary);
  background: var(--primary-bg);
  color: var(--primary);
  font-weight: 600;
}
.card-select .card-select-icon {
  font-size: 18px;
  display: block;
  margin-bottom: 3px;
}
.card-select .card-select-label {
  font-size: 11px;
  font-weight: 500;
}

.generate-info {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--gray-500);
  background: var(--gray-50);
  padding: 8px 14px;
  border-radius: 6px;
  margin: 12px 0;
}
.generate-info i {
  color: var(--secondary);
  font-size: 13px;
}
.generate-info strong {
  color: var(--gray-700);
}

.btn-generate {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  width: 100%;
  padding: 10px 20px;
  background: var(--primary);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}
.btn-generate:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}
.btn-generate:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-secondary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  width: 100%;
  padding: 10px 20px;
  background: var(--secondary);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}
.btn-secondary:hover {
  background: var(--secondary-dark);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn-secondary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-outline {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 8px 16px;
  background: transparent;
  color: var(--primary);
  border: 1.5px solid var(--primary);
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}
.btn-outline:hover {
  background: var(--primary-bg);
}

.btn-danger {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 8px 16px;
  background: var(--red-500);
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}
.btn-danger:hover {
  background: var(--red-600);
}

.btn-sm {
  padding: 6px 12px;
  font-size: 11px;
  border-radius: 5px;
}

.gen-loading-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px 16px;
  text-align: center;
}

.loading-spinner {
  width: 36px;
  height: 36px;
  border: 3px solid var(--gray-200);
  border-top-color: var(--primary);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  margin-bottom: 12px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.generation-loading-text {
  font-size: 14px;
  font-weight: 600;
  color: var(--gray-700);
}

.generation-loading-hint {
  font-size: 12px;
  color: var(--gray-400);
  margin-top: 4px;
}

.result-section {
  display: none;
}
.result-section.show {
  display: block;
}

.result-title {
  font-size: 15px;
  font-weight: 600;
  color: var(--gray-800);
  margin-bottom: 10px;
}

.result-card {
  background: white;
  border: 1px solid var(--gray-200);
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 8px;
}
.result-card .q-number {
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 4px;
  font-size: 12px;
}
.result-card .q-text {
  font-size: 13px;
  color: var(--gray-800);
  margin-bottom: 6px;
  line-height: 1.5;
}
.result-card .q-options {
  margin: 4px 0 4px 14px;
  font-size: 12px;
  color: var(--gray-600);
  line-height: 1.6;
}
.result-card .q-answer {
  font-size: 12px;
  color: var(--secondary);
  font-weight: 600;
  margin-top: 3px;
  padding: 4px 8px;
  background: var(--secondary-bg);
  border-radius: 4px;
  display: inline-block;
}
.result-card .q-explanation {
  font-size: 11px;
  color: var(--gray-500);
  margin-top: 4px;
  padding: 6px 8px;
  background: var(--gray-50);
  border-radius: 4px;
  border-left: 2px solid var(--gray-300);
}

.questions-actions {
  display: flex;
  gap: 8px;
  margin-top: 12px;
}
.questions-actions .btn-outline {
  flex: 1;
}

/* Error / Success messages */
.error-msg {
  display: none;
  font-size: 13px;
  color: var(--red-500);
  padding: 8px 12px;
  background: #FEF2F2;
  border-radius: 6px;
  margin-top: 8px;
}
.error-msg.show {
  display: block;
}

.success-msg {
  display: none;
  font-size: 13px;
  color: var(--secondary);
  padding: 8px 12px;
  background: #ECFDF5;
  border-radius: 6px;
  margin-top: 8px;
}
.success-msg.show {
  display: block;
}

/* Questions Bank Grid */
.tag {
  display: inline-block;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 8px;
  font-weight: 500;
  white-space: nowrap;
}
.tag-multiple_choice, .tag-PG { background: #DBEAFE; color: #1D4ED8; }
.tag-essay { background: #FEF3C7; color: #D97706; }
.tag-mixed { background: #E0E7FF; color: #4338CA; }
.tag-difficulty { margin-left: 4px; }
.tag-easy { background: #D1FAE5; color: #059669; }
.tag-medium { background: #FEF3C7; color: #D97706; }
.tag-hard { background: #FEE2E2; color: #DC2626; }


  opacity: 0.5;
}

/* Filters */
.filter-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 14px;
}
.filter-bar select, .filter-bar input {
  padding: 6px 10px;
  border: 1.5px solid var(--gray-200);
  border-radius: 6px;
  font-size: 12px;
  color: var(--gray-700);
  background: #fff;
  outline: none;
}
.filter-bar select:focus, .filter-bar input:focus {
  border-color: var(--primary);
}
.filter-bar .filter-search {
  flex: 1;
  min-width: 160px;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  margin-top: 16px;
}
.pagination-inner {
  display: flex;
  gap: 3px;
}
.pagination-btn {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid var(--gray-200);
  border-radius: 6px;
  background: #fff;
  color: var(--gray-600);
  font-size: 12px;
  cursor: pointer;
  transition: all 0.1s ease;
}
.pagination-btn:hover:not(:disabled) {
  border-color: var(--primary);
  color: var(--primary);
}
.pagination-btn.active {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}
.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}
.pagination-ellipsis {
  display: flex;
  align-items: center;
  padding: 0 4px;
  color: var(--gray-400);
}

/* Account page */
.account-header {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 16px;
}
.account-avatar {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--gray-200);
}
.account-avatar-placeholder {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: var(--gray-100);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: var(--gray-400);
}
.account-name {
  font-size: 16px;
  font-weight: 600;
  color: var(--gray-800);
}
.account-email {
  font-size: 12px;
  color: var(--gray-500);
}

.credit-card {
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  border-radius: 10px;
  padding: 16px;
  color: #fff;
  margin-bottom: 16px;
}
.credit-card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.credit-card-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.credit-card-icon {
  font-size: 26px;
}
.credit-card-title {
  font-size: 11px;
  opacity: 0.8;
  margin-bottom: 1px;
}
.credit-card-balance {
  font-size: 24px;
  font-weight: 700;
}
.credit-topup-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 8px 14px;
  background: rgba(255,255,255,0.2);
  border: 1px solid rgba(255,255,255,0.3);
  border-radius: 7px;
  color: #fff;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
  backdrop-filter: blur(4px);
}
.credit-topup-btn:hover {
  background: rgba(255,255,255,0.3);
}

.topup-history-title {
  font-size: 14px;
  font-weight: 600;
  color: var(--gray-700);
  margin-bottom: 8px;
}

.tx-table-wrapper {
  border: 1px solid var(--gray-200);
  border-radius: 8px;
  overflow: hidden;
  overflow-x: auto;
}
.tx-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}
.tx-table th {
  text-align: left;
  padding: 10px 14px;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--gray-500);
  background: var(--gray-50);
  border-bottom: 1px solid var(--gray-200);
  white-space: nowrap;
}
.tx-row {
  cursor: pointer;
  transition: background 0.1s;
}
.tx-row:hover {
  background: rgba(37, 99, 235, 0.02);
}
.tx-row:not(:last-child) .tx-cell {
  border-bottom: 1px solid var(--gray-50);
}
.tx-cell {
  padding: 10px 14px;
  color: var(--gray-600);
  white-space: nowrap;
}
.tx-cell.tx-credits {
  font-weight: 600;
  color: var(--gray-800);
}
.tx-cell.tx-price {
  font-weight: 500;
  color: var(--gray-700);
}
.tx-cell.tx-date {
  font-size: 11px;
  color: var(--gray-400);
}
.tx-status {
  display: inline-block;
  font-size: 10px;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 4px;
  text-transform: capitalize;
}
.tx-status-success {
  background: rgba(16, 185, 129, 0.1);
  color: #059669;
}
.tx-status-pending {
  background: rgba(245, 158, 11, 0.1);
  color: #d97706;
}
.tx-status-failed {
  background: rgba(239, 68, 68, 0.1);
  color: #dc2626;
}
.tx-credits-plus {
  font-weight: 600;
  color: #059669;
}
.tx-credits-minus {
  font-weight: 600;
  color: #dc2626;
}
.tx-empty {
  text-align: center;
  padding: 24px;
  color: var(--gray-400);
  font-size: 13px;
}

/* Top-Up Modal */
.modal-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  z-index: 1000;
  justify-content: center;
  align-items: flex-start;
  padding: 20px;
}
.modal-overlay.active {
  display: flex;
}

.topup-modal {
  background: #fff;
  border-radius: 14px;
  max-width: 380px;
  width: 100%;
  overflow: hidden;
}

.topup-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px 0;
}
.topup-modal-header h3 {
  font-size: 16px;
  font-weight: 700;
  color: var(--gray-800);
}
.topup-modal-close {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  background: var(--gray-100);
  border-radius: 50%;
  cursor: pointer;
  color: var(--gray-500);
  font-size: 12px;
  transition: all 0.1s ease;
}
.topup-modal-close:hover {
  background: var(--gray-200);
}

.topup-modal-body {
  padding: 16px 20px 20px;
}

.topup-modal-label {
  font-size: 13px;
  font-weight: 500;
  color: var(--gray-700);
  margin-bottom: 12px;
}

.topup-slider-container {
  margin-bottom: 12px;
}

.topup-slider {
  width: 100%;
  -webkit-appearance: none;
  height: 5px;
  border-radius: 3px;
  background: linear-gradient(to right, var(--primary) 0%, var(--primary) var(--slider-fill, 3%), var(--gray-200) var(--slider-fill, 3%), var(--gray-200) 100%);
  outline: none;
  cursor: pointer;
}
.topup-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: var(--primary);
  cursor: pointer;
  border: 3px solid #fff;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.topup-slider-values {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
  color: var(--gray-400);
  margin-top: 4px;
}

.topup-amount-display {
  text-align: center;
  margin-bottom: 12px;
}
.topup-amount-display span:first-child {
  font-size: 34px;
  font-weight: 800;
  color: var(--primary);
}
.topup-amount-label {
  font-size: 13px;
  color: var(--gray-400);
  margin-left: 4px;
}

.topup-quick-select {
  display: flex;
  gap: 6px;
  margin-bottom: 12px;
}
.topup-quick-btn {
  flex: 1;
  padding: 8px;
  border: 1.5px solid var(--gray-200);
  border-radius: 6px;
  background: #fff;
  font-size: 12px;
  font-weight: 600;
  color: var(--gray-600);
  cursor: pointer;
  transition: all 0.1s ease;
}
.topup-quick-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
}

.topup-price-card {
  background: var(--gray-50);
  border-radius: 8px;
  padding: 10px 14px;
  margin-bottom: 12px;
}
.topup-price-row {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  font-weight: 600;
  color: var(--gray-700);
}
.topup-price-row span:last-child {
  color: var(--primary);
}

/* Materials */
.materials-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 8px;
}
.material-item {
  background: #fff;
  border: 1px solid var(--gray-200);
  border-radius: 8px;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 8px;
}
.material-item .mi-left {
  flex: 1;
  min-width: 0;
}
.material-item .mi-title {
  font-size: 13px;
  font-weight: 600;
  color: var(--gray-800);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.material-item .mi-meta {
  font-size: 10px;
  color: var(--gray-400);
  margin-top: 1px;
}
.material-item .mi-actions {
  display: flex;
  gap: 4px;
  flex-shrink: 0;
}
.material-item .mi-actions button {
  width: 26px;
  height: 26px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 11px;
  transition: all 0.1s ease;
}
.material-item .mi-actions .mi-edit {
  background: var(--primary-bg);
  color: var(--primary);
}
.material-item .mi-actions .mi-delete {
  background: #FEF2F2;
  color: var(--red-500);
}

/* Upload area */
.upload-area {
  border: 2px dashed var(--gray-300);
  border-radius: 8px;
  padding: 16px;
  text-align: center;
  cursor: pointer;
  transition: all 0.15s ease;
  background: var(--gray-50);
}
.upload-area:hover, .upload-area.dragover {
  border-color: var(--primary);
  background: var(--primary-bg);
}
.upload-icon {
  font-size: 28px;
  color: var(--gray-300);
  display: block;
  margin-bottom: 6px;
}
.upload-text {
  font-size: 12px;
  color: var(--gray-500);
  margin-bottom: 2px;
}
.upload-hint {
  font-size: 11px;
  color: var(--gray-400);
}
.upload-input {
  display: none;
}

.upload-preview {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
}
.upload-preview img {
  max-width: 160px;
  max-height: 120px;
  border-radius: 6px;
}
.upload-remove {
  padding: 3px 8px;
  border: none;
  background: var(--gray-100);
  border-radius: 4px;
  cursor: pointer;
  font-size: 11px;
  color: var(--gray-500);
}

/* Input method tabs */
.input-method-tabs {
  display: flex;
  gap: 0;
  margin-bottom: 10px;
  border: 1px solid var(--gray-200);
  border-radius: 6px;
  overflow: hidden;
}
.input-method-tab {
  flex: 1;
  padding: 6px 10px;
  border: none;
  background: #fff;
  font-size: 11px;
  font-weight: 500;
  color: var(--gray-500);
  cursor: pointer;
  transition: all 0.1s ease;
}
.input-method-tab.active {
  background: var(--primary);
  color: #fff;
}
.input-method-tab:not(:last-child) {
  border-right: 1px solid var(--gray-200);
}

.input-method-content {
  display: none;
}
.input-method-content.active {
  display: block;
}

/* Materials table */
.mat-empty {
  text-align: center;
  padding: 40px 20px;
  color: var(--gray-400);
}
.mat-empty i {
  font-size: 36px;
  display: block;
  margin-bottom: 10px;
  opacity: 0.5;
}
.mat-actions {
  display: flex;
  gap: 4px;
}
.mat-btn {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 11px;
  transition: all 0.1s ease;
}
.mat-btn-view {
  color: var(--primary);
}
.mat-btn-edit {
  color: #D97706;
}
.mat-btn-delete {
  color: var(--red-500);
}

.material-detail-content {
  max-height: 60vh;
  overflow-y: auto;
  padding: 0 20px 20px;
  font-size: 13px;
  line-height: 1.7;
  color: var(--gray-700);
  white-space: pre-wrap;
}

@media (max-width: 640px) {
  .mat-hide-mobile {
    display: none;
  }
}

/* responsive */
@media (max-width: 1024px) {
  .tools-sidebar {
    width: 180px;
    min-width: 180px;
  }
}

@media (max-width: 768px) {
  .tools-layout {
    gap: 0;
  }
  .tools-content {
    padding: 16px;
  }
  .tools-sidebar {
    position: fixed;
    top: 55px;
    left: -280px;
    width: 260px;
    min-width: 260px;
    height: calc(100vh - 55px);
    border-radius: 0;
    border: none;
    border-right: 1px solid rgba(0,0,0,0.06);
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(16px) saturate(180%);
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    z-index: 100;
    transition: left 0.3s ease;
    padding: 16px 0;
  }
  .tools-sidebar.open {
    left: 0;
  }
  .sidebar-overlay {
    display: none;
    position: fixed;
    top: 55px;
    left: 0;
    width: 100%;
    height: calc(100vh - 55px);
    background: rgba(0,0,0,0.4);
    z-index: 99;
  }
.sidebar-overlay.open {
  display: block;
}
  .panel-header {
    font-size: 16px;
    margin-bottom: 12px;
  }
  .panel-card {
    padding: 12px;
  }
  .materials-grid {
    grid-template-columns: 1fr;
  }
  .card-select {
    min-width: 70px;
    padding: 8px 10px;
  }
  .card-select .card-select-icon {
    font-size: 16px;
  }
  .menu-toggle {
    display: flex !important;
  }
  .form-group {
    margin-bottom: 10px;
  }
  .form-row {
    gap: 8px;
  }
  .account-header {
    flex-direction: column;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .tools-content {
    padding: 12px;
  }
  .panel-card {
    padding: 10px;
  }
  .card-select-group {
    gap: 4px;
  }
  .card-select {
    min-width: 60px;
    padding: 6px 8px;
  }
  .card-select .card-select-icon {
    font-size: 14px;
  }
  .card-select .card-select-label {
    font-size: 10px;
  }
  .generate-info {
    flex-direction: column;
    text-align: center;
  }
  .materials-grid {
    gap: 6px;
  }
  .form-row {
    grid-template-columns: 1fr;
  }
  .topup-quick-select {
    flex-wrap: wrap;
  }
  .topup-quick-btn {
    min-width: calc(50% - 3px);
    flex: none;
  }
  .tx-table th,
  .tx-cell {
    padding: 8px 10px;
  }
  .tx-cell.tx-date {
    font-size: 10px;
  }
}

.menu-toggle {
  display: none;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: var(--gray-100);
  border: 1px solid var(--gray-200);
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
  color: var(--gray-600);
  cursor: pointer;
  margin-bottom: 12px;
}
.menu-toggle:hover {
  background: var(--gray-200);
}

.export-actions {
  display: flex;
  gap: 6px;
  margin-top: 10px;
}
.export-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px;
  border: 1.5px solid var(--gray-200);
  border-radius: 8px;
  background: #fff;
  font-size: 12px;
  font-weight: 500;
  color: var(--gray-600);
  cursor: pointer;
  transition: all 0.1s ease;
}
.export-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
  background: var(--primary-bg);
}

.mobile-header-tools {
  display: none;
}

.export-panel {
  margin-top: 16px;
  padding: 16px;
  background: #f8fafc;
  border: 1px solid var(--gray-200);
}
.export-panel-title {
  font-size: 13px;
  font-weight: 600;
  color: var(--gray-700);
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.export-opts-row {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 12px;
  align-items: center;
}
.export-opt-toggle {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--gray-600);
  cursor: pointer;
  user-select: none;
}
.export-opt-toggle input[type="checkbox"] {
  width: 15px;
  height: 15px;
  accent-color: var(--primary);
  cursor: pointer;
}
.export-opt-select {
  font-size: 12px;
  padding: 4px 8px;
  border: 1px solid var(--gray-200);
  border-radius: 6px;
  background: white;
  color: var(--gray-700);
  cursor: pointer;
}
.export-btns {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
.export-btn-format {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 16px;
  border: 1.5px solid var(--gray-200);
  border-radius: 8px;
  background: white;
  font-size: 13px;
  font-weight: 600;
  color: var(--gray-600);
  cursor: pointer;
  transition: all 0.15s ease;
}
.export-btn-format:hover {
  border-color: var(--primary);
  color: var(--primary);
  background: var(--primary-bg);
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(37,99,235,0.1);
}
.export-btn-format.gform{
  white-space: nowrap;
}
.export-btn-format.txt:hover { border-color: #6b7280; color: #6b7280; background: #f3f4f6; }
.export-btn-format.pdf:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
.export-btn-format.doc:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
.export-btn-format.gform:hover { border-color: #34a853; color: #34a853; background: #f0fdf4; }

.mixed-rows { display:flex;flex-direction:column;gap:10px; }
.mixed-row { display:flex;gap:12px; }
.mix-field { flex:1; }
.mix-field label { display:block;font-size:12px;font-weight:600;color:var(--gray-600);margin-bottom:4px; }
.mix-field .form-input { width:100%; }
.mix-order { display:flex;align-items:center;gap:10px; }
.mix-order label { font-size:12px;font-weight:600;color:var(--gray-600);white-space:nowrap; }
.mix-order .form-select { flex:1; }

.export-advanced-toggle {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 600;
  color: var(--gray-600);
  cursor: pointer;
  padding: 8px 0;
  user-select: none;
  border-top: 1px solid var(--gray-200);
  margin-top: 8px;
}
.export-advanced-toggle:hover { color: var(--primary); }
.export-advanced-toggle .chevron {
  transition: transform 0.2s ease;
  font-size: 10px;
}
.export-advanced-toggle.open .chevron { transform: rotate(90deg); }
.export-advanced {
  padding: 4px 0 8px;
}
.export-advanced .export-opt-toggle {
  margin-bottom: 4px;
}
.export-input {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid var(--gray-200);
  border-radius: 6px;
  font-size: 12px;
  color: var(--gray-700);
  background: white;
  outline: none;
}
.export-input:focus { border-color: var(--primary); }
.export-textarea {
  width: 100%;
  padding: 6px 10px;
  border: 1px solid var(--gray-200);
  border-radius: 6px;
  font-size: 12px;
  color: var(--gray-700);
  background: white;
  outline: none;
  resize: vertical;
  font-family: inherit;
}
.export-textarea:focus { border-color: var(--primary); }
.biodata-field-row {
  display: flex;
  gap: 6px;
  margin-bottom: 6px;
  align-items: center;
}
.biodata-field-row .export-input { flex: 1; }
.info-field-row {
  display: flex;
  gap: 6px;
  margin-bottom: 6px;
  align-items: center;
}
.info-field-row .info-label { flex: 1; }
.info-field-row .info-value { flex: 1; }
.biodata-field-remove {
  width: 28px;
  height: 28px;
  border: none;
  border-radius: 6px;
  background: #fef2f2;
  color: #dc2626;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.biodata-field-remove:hover { background: #fee2e2; }
.export-add-field {
  font-size: 12px;
  color: var(--primary);
  background: none;
  border: 1px dashed var(--gray-300);
  border-radius: 6px;
  padding: 6px 12px;
  cursor: pointer;
  width: 100%;
  transition: all 0.15s;
}
.export-add-field:hover {
  border-color: var(--primary);
  background: var(--primary-bg);
}
.q-edit-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 28px;
  height: 28px;
  border: none;
  border-radius: 6px;
  background: var(--gray-100);
  color: var(--gray-500);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  transition: all 0.15s;
  opacity: 0;
}
.result-card:hover .q-edit-btn {
  opacity: 1;
}
.q-edit-btn:hover {
  background: var(--primary-bg);
  color: var(--primary);
}

.q-image {
  max-width: 100%;
  max-height: 200px;
  border-radius: 6px;
  margin: 6px 0;
  display: block;
  border: 1px solid var(--gray-200);
}

.edit-modal-image-preview {
  max-width: 200px;
  max-height: 120px;
  border-radius: 6px;
  margin: 6px 0;
  display: block;
  border: 1px solid var(--gray-200);
}

#questionDetailSection {
  display: none;
}
#questionDetailSection.active {
  display: block;
}

.q-detail-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: var(--primary);
  cursor: pointer;
  margin-bottom: 14px;
  background: none;
  border: none;
  padding: 4px 8px;
  border-radius: 6px;
  transition: background 0.15s;
}
.q-detail-back:hover {
  background: var(--primary-bg);
}

@media (max-width: 768px) {
  .mobile-header-tools {
    display: flex;
  }
}

.tutorial-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
}
.tutorial-card {
  background: #fff;
  border: 1px solid var(--gray-200);
  border-radius: 12px;
  overflow: hidden;
  transition: box-shadow 0.2s ease;
}
.tutorial-card:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}
.tutorial-card-video {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%;
  background: var(--gray-100);
}
.tutorial-card-video iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
.tutorial-card-body {
  padding: 14px 16px;
}
.tutorial-card-body h3 {
  font-size: 14px;
  font-weight: 600;
  color: var(--gray-800);
  margin: 0 0 6px;
}
.tutorial-card-body p {
  font-size: 12px;
  color: var(--gray-500);
  margin: 0;
  line-height: 1.5;
}

/* Quiz Mode Styles */
.quiz-header {
  text-align: center;
  padding: 20px 16px;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  border-radius: 12px;
  margin-bottom: 16px;
  color: #fff;
}
.quiz-header h2 {
  font-size: 18px;
  margin: 0 0 4px;
  color: #fff;
}
.quiz-header p {
  font-size: 13px;
  opacity: 0.85;
  margin: 0;
}
.quiz-progress {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 16px;
  font-size: 13px;
  color: var(--gray-500);
}
.quiz-progress-bar {
  flex: 1;
  max-width: 300px;
  height: 6px;
  background: var(--gray-200);
  border-radius: 3px;
  overflow: hidden;
}
.quiz-progress-fill {
  height: 100%;
  background: var(--primary);
  border-radius: 3px;
  transition: width 0.3s ease;
}
.quiz-question-card {
  background: #fff;
  border: 1px solid var(--gray-200);
  border-radius: 10px;
  padding: 16px;
  margin-bottom: 12px;
}
.quiz-question-number {
  font-size: 11px;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.quiz-question-text {
  font-size: 14px;
  font-weight: 500;
  color: var(--gray-800);
  margin-bottom: 12px;
  line-height: 1.6;
}
.quiz-question-image {
  max-width: 100%;
  max-height: 200px;
  border-radius: 8px;
  margin-bottom: 12px;
  display: block;
  border: 1px solid var(--gray-200);
}
.quiz-option {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border: 2px solid var(--gray-200);
  border-radius: 8px;
  margin-bottom: 6px;
  cursor: pointer;
  transition: all 0.15s ease;
  background: #fff;
  user-select: none;
}
.quiz-option:hover {
  border-color: var(--primary-light);
  background: var(--primary-bg);
}
.quiz-option.selected {
  border-color: var(--primary);
  background: var(--primary-bg);
}
.quiz-option.correct {
  border-color: var(--secondary);
  background: rgba(16, 185, 129, 0.08);
}
.quiz-option.wrong {
  border-color: var(--red-500);
  background: rgba(239, 68, 68, 0.08);
}
.quiz-option.correct-show {
  border-color: var(--secondary);
  background: rgba(16, 185, 129, 0.08);
}
.quiz-option-label {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: var(--gray-100);
  font-size: 12px;
  font-weight: 600;
  color: var(--gray-600);
  flex-shrink: 0;
  transition: all 0.15s;
}
.quiz-option.selected .quiz-option-label {
  background: var(--primary);
  color: #fff;
}
.quiz-option.correct .quiz-option-label {
  background: var(--secondary);
  color: #fff;
}
.quiz-option.wrong .quiz-option-label {
  background: var(--red-500);
  color: #fff;
}
.quiz-option.correct-show .quiz-option-label {
  background: var(--secondary);
  color: #fff;
}
.quiz-option-text {
  font-size: 13px;
  color: var(--gray-700);
  line-height: 1.4;
}
.quiz-option-icon {
  margin-left: auto;
  font-size: 14px;
  flex-shrink: 0;
}
.quiz-essay-textarea {
  width: 100%;
  padding: 10px 14px;
  border: 2px solid var(--gray-200);
  border-radius: 8px;
  font-size: 13px;
  color: var(--gray-800);
  background: #fff;
  outline: none;
  resize: vertical;
  min-height: 80px;
  font-family: inherit;
  transition: border-color 0.15s;
}
.quiz-essay-textarea:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
.quiz-essay-textarea.submitted {
  border-color: var(--secondary);
  background: rgba(16, 185, 129, 0.04);
}
.quiz-submit-area {
  text-align: center;
  margin-top: 20px;
}
.quiz-btn-submit {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 40px;
  background: var(--primary);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}
.quiz-btn-submit:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
}
.quiz-btn-submit:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

/* Quiz Result */
.quiz-result-header {
  text-align: center;
  padding: 28px 16px;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  border-radius: 12px;
  margin-bottom: 16px;
  color: #fff;
}
.quiz-result-score {
  font-size: 48px;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 4px;
  color: #fff;
}
.quiz-result-label {
  font-size: 14px;
  opacity: 0.85;
  margin: 0;
}
.quiz-result-stats {
  display: flex;
  justify-content: center;
  gap: 24px;
  margin-top: 12px;
}
.quiz-stat-item {
  text-align: center;
}
.quiz-stat-value {
  font-size: 20px;
  font-weight: 700;
}
.quiz-stat-label {
  font-size: 11px;
  opacity: 0.75;
}
.quiz-result-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-top: 20px;
  flex-wrap: wrap;
}
.quiz-result-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 24px;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}
.quiz-result-btn-primary {
  background: var(--primary);
  color: #fff;
}
.quiz-result-btn-primary:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
}
.quiz-result-btn-outline {
  background: transparent;
  color: var(--primary);
  border: 1.5px solid var(--primary);
}
.quiz-result-btn-outline:hover {
  background: var(--primary-bg);
}
.quiz-result-item {
  background: #fff;
  border: 1px solid var(--gray-200);
  border-radius: 10px;
  margin-bottom: 8px;
  overflow: hidden;
}
.quiz-result-item-header {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  cursor: pointer;
  transition: background 0.15s;
  user-select: none;
}
.quiz-result-item-header:hover {
  background: var(--gray-50);
}
.quiz-result-item-icon {
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  font-size: 12px;
  flex-shrink: 0;
}
.quiz-result-item-icon.correct {
  background: rgba(16, 185, 129, 0.15);
  color: var(--secondary);
}
.quiz-result-item-icon.wrong {
  background: rgba(239, 68, 68, 0.15);
  color: var(--red-500);
}
.quiz-result-item-icon.essay {
  background: rgba(245, 158, 11, 0.15);
  color: #d97706;
}
.quiz-result-item-num {
  font-size: 12px;
  font-weight: 600;
  color: var(--gray-500);
  min-width: 50px;
}
.quiz-result-item-text {
  flex: 1;
  font-size: 13px;
  color: var(--gray-700);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.quiz-result-item-arrow {
  font-size: 12px;
  color: var(--gray-400);
  transition: transform 0.2s;
}
.quiz-result-item.open .quiz-result-item-arrow {
  transform: rotate(90deg);
}
.quiz-result-item-body {
  display: none;
  padding: 0 16px 16px;
  border-top: 1px solid var(--gray-100);
}
.quiz-result-item.open .quiz-result-item-body {
  display: block;
}
.quiz-result-detail-row {
  display: flex;
  gap: 6px;
  padding: 6px 0;
  font-size: 13px;
}
.quiz-result-detail-label {
  font-weight: 600;
  color: var(--gray-500);
  min-width: 80px;
  flex-shrink: 0;
}
.quiz-result-detail-value {
  color: var(--gray-700);
  flex: 1;
}
.quiz-result-detail-value.correct-answer {
  color: var(--secondary);
  font-weight: 600;
}
.quiz-result-detail-value.user-wrong {
  color: var(--red-500);
}
</style>
