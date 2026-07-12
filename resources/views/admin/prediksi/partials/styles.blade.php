<style>
    /* ── Page Header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1a2b4a;
        line-height: 1.2;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: .8rem;
        color: #8ca0bf;
        margin-top: 4px;
    }

    .breadcrumb a {
        color: #8ca0bf;
        text-decoration: none;
        transition: color .15s;
    }

    .breadcrumb a:hover {
        color: #4A90D9;
    }

    .breadcrumb-sep {
        font-size: .7rem;
        opacity: .5;
    }

    .breadcrumb-current {
        color: #4A90D9;
        font-weight: 600;
    }

    .page-date {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .85rem;
        color: #6b7e9f;
        background: #fff;
        border: 1px solid #e2e8f4;
        border-radius: 10px;
        padding: 8px 14px;
    }

    /* ── Form Parameter Prediksi ── */
    .form-prediksi {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8eef8;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
    }

    .form-preset-grid {
        display: grid;
        grid-template-columns: 2.2fr 1fr 1fr;
        gap: 16px;
        align-items: flex-end;
    }

    @media (max-width: 992px) {
        .form-preset-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    .preset-select {
        padding: 10px 14px;
        border: 1px solid #cedbe9;
        border-radius: 10px;
        font-size: .85rem;
        color: #1a2b4a;
        font-family: inherit;
        outline: none;
        background: #fafdff;
        cursor: pointer;
        transition: border-color .15s;
        width: 100%;
    }

    .preset-select:focus {
        border-color: #4A90D9;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        align-items: flex-end;
    }

    @media (max-width: 992px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: .8rem;
        font-weight: 600;
        color: #4a5a7a;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-group input {
        padding: 10px 14px;
        border: 1px solid #cedbe9;
        border-radius: 10px;
        font-size: .85rem;
        color: #1a2b4a;
        font-family: inherit;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-group input:focus {
        border-color: #4A90D9;
        box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.15);
    }

    .btn-hitung {
        background: #4A90D9;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: background .2s, transform .15s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        height: 40px;
    }

    .btn-hitung:hover {
        background: #357abd;
        transform: translateY(-1px);
    }

    .btn-hitung:active {
        transform: translateY(0);
    }

    /* ── Warning Box ── */
    .warning-box {
        background: #fff3e6;
        border-left: 5px solid #f5a54a;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        box-shadow: 0 4px 12px rgba(245, 165, 74, 0.08);
    }

    .warning-icon {
        color: #f5a54a;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .warning-title {
        font-size: .95rem;
        font-weight: 700;
        color: #7c5212;
        margin-bottom: 4px;
    }

    .warning-desc {
        font-size: .82rem;
        color: #8f6629;
        line-height: 1.55;
    }

    .seeder-hint {
        background: rgba(245, 165, 74, 0.12);
        padding: 10px 14px;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
        font-size: .78rem;
        display: inline-block;
        margin-top: 12px;
        border: 1px dashed rgba(245, 165, 74, 0.35);
        color: #7c5212;
        font-weight: 600;
    }

    /* ── Stat Cards Grid ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 18px;
        border: 1px solid #e8eef8;
        display: flex;
        flex-direction: column;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
        transition: box-shadow .2s, transform .2s;
    }

    .stat-card:hover {
        box-shadow: 0 6px 20px rgba(74, 144, 217, .12);
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon.green {
        background: #e8f8ee;
        color: #34c472;
    }

    .stat-icon.purple {
        background: #f3ebfc;
        color: #8a63d2;
    }

    .stat-icon.blue {
        background: #e8f0fd;
        color: #4A90D9;
    }

    .stat-icon.orange {
        background: #fff3e6;
        color: #f5a54a;
    }

    .stat-label {
        font-size: .72rem;
        color: #8ca0bf;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: #1a2b4a;
        line-height: 1.1;
    }

    .stat-desc {
        font-size: .72rem;
        color: #a0aec0;
    }

    /* ── Main Layout ── */
    .prediksi-layout {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
        align-items: start;
    }

    @media (max-width: 1200px) {
        .prediksi-layout {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8eef8;
        padding: 22px 24px;
        box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
    }

    .card-title-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f4fb;
    }

    .card-title {
        font-size: .95rem;
        font-weight: 700;
        color: #1a2b4a;
    }

    /* ── Table Prediksi ── */
    .pred-table-wrap {
        max-height: 380px;
        overflow-y: auto;
    }

    .pred-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .8rem;
    }

    .pred-table th {
        background: #f5f8ff;
        color: #8ca0bf;
        font-weight: 600;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 10px 12px;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .pred-table td {
        padding: 11px 12px;
        color: #2d4060;
        border-bottom: 1px solid #f6f9fd;
        vertical-align: middle;
    }

    .pred-table tbody tr:hover td {
        background: #fafcff;
    }

    .badge-status {
        display: inline-block;
        font-size: .65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .badge-status.tinggi {
        background: #fee2e2;
        color: #ef4444;
    }

    .badge-status.sedang {
        background: #fff3e6;
        color: #f5a54a;
    }

    .badge-status.rendah {
        background: #e8f8ee;
        color: #34c472;
    }

    /* ── Teori & Rumus Section ── */
    .theory-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8eef8;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
        margin-bottom: 24px;
    }

    .formula-box {
        background: #f5f8ff;
        border-left: 4px solid #4A90D9;
        padding: 14px 18px;
        border-radius: 0 12px 12px 0;
        margin: 14px 0;
        font-family: 'Courier New', Courier, monospace;
        font-size: .85rem;
        color: #1a2b4a;
        line-height: 1.5;
        overflow-x: auto;
    }

    .parameter-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 14px;
    }

    @media (max-width: 768px) {
        .parameter-grid {
            grid-template-columns: 1fr;
        }
    }

    .param-card {
        background: #fafcff;
        border: 1px solid #e8eef8;
        border-radius: 10px;
        padding: 12px;
        text-align: center;
    }

    .param-symbol {
        font-size: 1.3rem;
        font-weight: 700;
        color: #4A90D9;
        margin-bottom: 4px;
    }

    .param-value {
        font-size: .85rem;
        font-weight: 600;
        color: #1a2b4a;
        margin-bottom: 2px;
    }

    .param-desc {
        font-size: .7rem;
        color: #8ca0bf;
    }

    .step-list {
        margin-left: 20px;
        font-size: .82rem;
        color: #4a5a7a;
        line-height: 1.6;
    }

    .step-list li {
        margin-bottom: 8px;
    }

    /* ── AI Analysis Card ── */
    .ai-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8eef8;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(74, 144, 217, .05);
        margin-bottom: 24px;
    }

    .ai-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f0f4fb;
        padding-bottom: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .ai-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-icon-pulse {
        color: #8a63d2;
        background: #f3ebfc;
        padding: 8px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-select {
        padding: 8px 12px;
        border: 1px solid #cedbe9;
        border-radius: 8px;
        font-size: .8rem;
        color: #1a2b4a;
        font-family: inherit;
        outline: none;
        background: #fafdff;
        cursor: pointer;
    }

    .btn-ai {
        background: #8a63d2;
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: background .2s, transform .15s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-ai:hover {
        background: #734bbd;
        transform: translateY(-1px);
    }

    .btn-ai:active {
        transform: translateY(0);
    }

    .ai-content {
        font-size: .85rem;
        color: #2d4060;
        line-height: 1.65;
        min-height: 120px;
    }

    .ai-placeholder-text {
        color: #8ca0bf;
        text-align: center;
        padding: 40px 0;
        font-style: italic;
    }

    /* AI Loading State */
    .ai-loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
        gap: 16px;
    }

    .ai-spinner {
        border: 3.5px solid #f3f3f3;
        border-top: 3.5px solid #8a63d2;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        animation: spin-ai 1s linear infinite;
    }

    @keyframes spin-ai {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .ai-loading-text {
        font-size: .82rem;
        color: #6b7e9f;
        font-weight: 600;
        animation: pulse-ai-text 1.5s ease-in-out infinite;
    }

    @keyframes pulse-ai-text {

        0%,
        100% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }
    }

    /* Markdown styling inside AI content */
    .ai-rendered-markdown h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a2b4a;
        margin-top: 20px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
        border-bottom: 1.5px solid #f0f4fb;
        padding-bottom: 6px;
    }

    .ai-rendered-markdown h3:first-child {
        margin-top: 0;
    }

    .ai-rendered-markdown p {
        margin-bottom: 12px;
    }

    .ai-rendered-markdown ul,
    .ai-rendered-markdown ol {
        margin-bottom: 14px;
        padding-left: 20px;
    }

    .ai-rendered-markdown li {
        margin-bottom: 6px;
    }

    .ai-rendered-markdown strong {
        color: #1a2b4a;
        font-weight: 700;
    }

    .ai-rendered-markdown blockquote {
        background: #f7f6fc;
        border-left: 4px solid #8a63d2;
        padding: 10px 16px;
        margin: 14px 0;
        border-radius: 0 8px 8px 0;
        font-style: italic;
        color: #4a5a7a;
    }

    .ai-rendered-markdown blockquote p {
        margin-bottom: 0;
    }

    /* Shimmer effect for placeholder skeleton */
    .shimmer-line {
        height: 12px;
        margin-bottom: 10px;
        background: linear-gradient(90deg, #f0f4f8 25%, #e2ebf4 50%, #f0f4f8 75%);
        background-size: 200% 100%;
        animation: loading-shimmer 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes loading-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>