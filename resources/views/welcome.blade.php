<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow – Recruitment & Resume Management System</title>
    <meta name="description" content="TalentFlow: Simple recruitment and resume management platform.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --border-focus: #3b82f6;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --success: #16a34a;
            --success-light: #dcfce7;
            --warning: #d97706;
            --warning-light: #fef3c7;
            --danger: #dc2626;
            --danger-light: #fee2e2;
            --purple: #7c3aed;
            --purple-light: #f5f3ff;
            --radius: 8px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-sm);
        }

        .nav-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            text-decoration: none;
        }

        .brand-badge {
            background: var(--primary);
            color: #fff;
            font-size: 0.72rem;
            padding: 2px 8px;
            border-radius: 999px;
            font-weight: 600;
        }

        .nav-tabs {
            display: flex;
            gap: 4px;
            list-style: none;
        }

        .nav-tabs button {
            background: transparent;
            border: none;
            padding: 8px 14px;
            border-radius: var(--radius);
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .nav-tabs button:hover {
            color: var(--text-dark);
            background: #f1f5f9;
        }

        .nav-tabs button.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 600;
        }

        .user-nav-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .role-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .role-admin { background: var(--danger-light); color: var(--danger); }
        .role-recruiter { background: var(--primary-light); color: var(--primary); }
        .role-candidate { background: var(--success-light); color: var(--success); }

        /* Container */
        .main-content {
            max-width: 1240px;
            margin: 24px auto;
            padding: 0 20px;
            flex: 1;
            width: 100%;
        }

        /* Header Title */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .section-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius);
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-outline {
            background: #ffffff;
            border-color: var(--border);
            color: var(--text-dark);
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .btn-success {
            background: var(--success);
            color: #ffffff;
        }

        /* Panels */
        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
        }

        .stat-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 4px 0;
            line-height: 1.2;
        }

        .stat-sub {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Card */
        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 14px;
        }

        /* Pipeline Funnel Bars */
        .funnel-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .funnel-row {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .funnel-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .funnel-track {
            height: 8px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
        }

        .funnel-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        /* Filter Toolbar */
        .toolbar {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .input-text {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 8px 12px;
            font-size: 0.9rem;
            color: var(--text-dark);
            flex: 1;
            min-width: 220px;
        }

        .input-text:focus {
            outline: none;
            border-color: var(--border-focus);
        }

        .input-select {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 8px 12px;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        /* Job Cards Grid */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 18px;
        }

        .job-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .job-card:hover {
            border-color: #cbd5e1;
            box-shadow: var(--shadow-md);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .job-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .job-dept {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .job-desc {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 10px 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 16px;
        }

        .skill-tag {
            font-size: 0.72rem;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 500;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .skill-tag.mandatory {
            background: var(--primary-light);
            color: var(--primary);
            border-color: #bfdbfe;
        }

        .job-footer {
            border-top: 1px solid var(--border);
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .job-salary {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--success);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .badge-open, .badge-completed, .badge-hired, .badge-reviewed {
            background: var(--success-light);
            color: var(--success);
        }

        .badge-closed, .badge-cancelled, .badge-rejected, .badge-overdue {
            background: var(--danger-light);
            color: var(--danger);
        }

        .badge-pending, .badge-applied, .badge-screening {
            background: #f1f5f9;
            color: #475569;
        }

        .badge-shortlisted, .badge-interview, .badge-in_progress {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Kanban Pipeline Board */
        .pipeline-board {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 16px;
            align-items: flex-start;
        }

        .pipeline-col {
            width: 280px;
            min-width: 280px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pipeline-col-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-dark);
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .app-item {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 12px;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all 0.15s ease;
        }

        .app-item:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .app-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .app-role {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .app-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Clean Table */
        .table-wrap {
            overflow-x: auto;
        }

        table.clean-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.88rem;
        }

        table.clean-table th {
            padding: 10px 14px;
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        table.clean-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
        }

        table.clean-table tr:hover td {
            background: #f8fafc;
        }

        /* Modals */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 16px;
        }

        .modal-backdrop.active {
            display: flex;
        }

        .modal-content {
            background: #ffffff;
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 520px;
            border: 1px solid var(--border);
            overflow: hidden;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-head {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-head h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-muted);
            cursor: pointer;
            line-height: 1;
        }

        .modal-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow-y: auto;
        }

        .modal-foot {
            padding: 12px 20px;
            background: #f8fafc;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 0.88rem;
            color: var(--text-dark);
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--border-focus);
        }

        textarea.form-control {
            min-height: 70px;
            resize: vertical;
        }

        /* Auth Portal Navigation Tabs */
        .auth-portal-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: var(--radius);
            gap: 4px;
            margin-bottom: 14px;
        }

        .auth-portal-tab {
            flex: 1;
            text-align: center;
            padding: 8px 10px;
            border: none;
            background: transparent;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            border-radius: 6px;
            cursor: pointer;
        }

        .auth-portal-tab.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        }

        .auth-sub-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            margin-bottom: 14px;
            gap: 16px;
        }

        .auth-sub-tab {
            background: none;
            border: none;
            padding: 8px 0;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
        }

        .auth-sub-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        .demo-pill {
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.78rem;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Toast */
        .toast-box {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 120;
        }

        .toast-msg {
            background: #ffffff;
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: var(--radius);
            padding: 10px 16px;
            font-size: 0.85rem;
            box-shadow: var(--shadow-md);
            color: var(--text-dark);
            min-width: 250px;
            animation: fadeIn 0.2s;
        }

        .toast-msg.success { border-left-color: var(--success); }
        .toast-msg.error { border-left-color: var(--danger); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 850px) {
            .nav-container {
                height: auto;
                padding: 12px 16px;
                flex-direction: column;
                gap: 10px;
            }
            .nav-tabs {
                overflow-x: auto;
                width: 100%;
                padding-bottom: 4px;
            }
        }

        /* ================= AUTHENTICATION GATEWAY SCREEN ================= */
        .auth-gateway-screen {
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 50%, #e2e8f0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 20px 60px 20px;
        }

        .gateway-container {
            max-width: 1180px;
            width: 100%;
        }

        .gateway-hero {
            text-align: center;
            margin-bottom: 28px;
        }

        .gateway-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-size: 2.1rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .gateway-brand-badge {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .gateway-headline {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .gateway-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            max-width: 720px;
            margin: 0 auto;
            line-height: 1.5;
        }

        .gateway-quickbar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 26px;
            box-shadow: var(--shadow-sm);
        }

        .gateway-quickbar-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #1e293b;
        }

        .gateway-quickbar-btns {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-instant {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #334155;
            padding: 7px 15px;
            border-radius: 8px;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-instant:hover {
            background: #ffffff;
            border-color: #94a3b8;
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .btn-instant-admin:hover {
            border-color: #f87171;
            color: #dc2626;
            background: #fff5f5;
        }

        .btn-instant-recruiter:hover {
            border-color: #60a5fa;
            color: #2563eb;
            background: #f0f7ff;
        }

        .btn-instant-candidate:hover {
            border-color: #4ade80;
            color: #16a34a;
            background: #f0fdf4;
        }

        .gateway-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        @media (max-width: 960px) {
            .gateway-grid {
                grid-template-columns: 1fr;
                max-width: 520px;
                margin: 0 auto;
            }
        }

        .gateway-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .gateway-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .gateway-card-header {
            padding: 20px 22px 16px 22px;
            border-bottom: 1px solid #f1f5f9;
        }

        .gateway-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .gateway-role-icon {
            font-size: 1.8rem;
        }

        .gateway-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .gateway-card-desc {
            font-size: 0.83rem;
            color: #64748b;
            line-height: 1.45;
        }

        .gateway-card-body {
            padding: 20px 22px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .gateway-mode-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 8px;
            margin-bottom: 16px;
            gap: 4px;
        }

        .gateway-mode-tab {
            flex: 1;
            text-align: center;
            padding: 7px 10px;
            border: none;
            background: transparent;
            font-size: 0.82rem;
            font-weight: 600;
            color: #64748b;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .gateway-mode-tab.active {
            background: #ffffff;
            color: #2563eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .gateway-quick-fill-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 9px 12px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .gateway-quick-fill-text {
            font-size: 0.78rem;
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .gateway-quick-fill-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #2563eb;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .gateway-quick-fill-btn:hover {
            background: #eff6ff;
            border-color: #93c5fd;
        }
    </style>
</head>
<body>

    <!-- AUTHENTICATION GATEWAY SCREEN (Initial Landing Screen) -->
    <div id="authGatewayScreen" class="auth-gateway-screen">
        <div class="gateway-container">
            
            <!-- Hero Brand Banner -->
            <div class="gateway-hero">
                <div class="gateway-brand">
                    <span>⚡ TalentFlow</span>
                    <span class="gateway-brand-badge">ATS & Recruitment Portal</span>
                </div>
                <h1 class="gateway-headline">Welcome! Choose your access portal</h1>
                <p class="gateway-subtitle">
                    Select how you want to enter: <strong>Recruiter</strong>, <strong>Candidate</strong>, or <strong>Administrator</strong>. You can sign in with pre-seeded demo accounts using Quick Fill, or register a new account.
                </p>
            </div>

            <!-- Fast 1-Click Demo Evaluation Bar -->
            <div class="gateway-quickbar">
                <div class="gateway-quickbar-title">
                    <span>🚀 <strong>1-Click Instant Demo Login:</strong></span>
                    <span style="font-size:0.8rem; color:#64748b;">(Instant login with zero typing)</span>
                </div>
                <div class="gateway-quickbar-btns">
                    <button type="button" class="btn-instant btn-instant-recruiter" onclick="instantLogin('recruiter')">
                        👔 Instant Recruiter
                    </button>
                    <button type="button" class="btn-instant btn-instant-candidate" onclick="instantLogin('candidate')">
                        👤 Instant Candidate
                    </button>
                    <button type="button" class="btn-instant btn-instant-admin" onclick="instantLogin('admin')">
                        🛡️ Instant Admin
                    </button>
                </div>
            </div>

            <!-- 3 Role Portals Grid -->
            <div class="gateway-grid">

                <!-- 1. RECRUITER PORTAL -->
                <div class="gateway-card">
                    <div class="gateway-card-header">
                        <div class="gateway-card-top">
                            <span class="gateway-role-icon">👔</span>
                            <span class="role-badge role-recruiter">Hiring Team</span>
                        </div>
                        <h2 class="gateway-card-title">Recruiter Portal</h2>
                        <p class="gateway-card-desc">Post open jobs, screen applicants, manage Kanban stages, schedule interviews & review tasks.</p>
                    </div>

                    <div class="gateway-card-body">
                        <!-- Mode Selector: Login vs Register -->
                        <div class="gateway-mode-tabs">
                            <button type="button" class="gateway-mode-tab active" id="gatewayRecruiterTabLogin" onclick="switchGatewayMode('recruiter', 'login')">Sign In</button>
                            <button type="button" class="gateway-mode-tab" id="gatewayRecruiterTabRegister" onclick="switchGatewayMode('recruiter', 'register')">Register New</button>
                        </div>

                        <!-- Recruiter Login Form -->
                        <div id="gatewayRecruiterLoginForm">
                            <div class="gateway-quick-fill-box">
                                <span class="gateway-quick-fill-text">Seed: <code>recruiter@talentflow.test</code></span>
                                <button type="button" class="gateway-quick-fill-btn" onclick="gatewayFill('recruiter')">Quick Fill</button>
                            </div>

                            <form onsubmit="handleGatewayLogin(event, 'recruiter')">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label class="form-label">Recruiter Email</label>
                                    <input type="email" class="form-control" name="email" id="gatewayRecruiterEmail" placeholder="recruiter@talentflow.test" required>
                                </div>
                                <div class="form-group" style="margin-bottom:16px;">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="gatewayRecruiterPassword" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:8px;">Sign In as Recruiter</button>
                                <button type="button" class="btn btn-outline btn-sm" style="width:100%; justify-content:center;" onclick="instantLogin('recruiter')">⚡ 1-Click Demo Login</button>
                            </form>
                        </div>

                        <!-- Recruiter Register Form -->
                        <div id="gatewayRecruiterRegisterForm" style="display:none;">
                            <form onsubmit="handleGatewayRegister(event, 'recruiter')">
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Alex Miller" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Work Email *</label>
                                    <input type="email" class="form-control" name="email" placeholder="alex@company.com" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+1-555-0100">
                                </div>
                                <div class="form-group" style="margin-bottom:16px;">
                                    <label class="form-label">Password * (min 6 chars)</label>
                                    <input type="password" class="form-control" name="password" minlength="6" placeholder="At least 6 characters" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register Recruiter Account</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. CANDIDATE PORTAL -->
                <div class="gateway-card">
                    <div class="gateway-card-header">
                        <div class="gateway-card-top">
                            <span class="gateway-role-icon">👤</span>
                            <span class="role-badge role-candidate">Job Seeker</span>
                        </div>
                        <h2 class="gateway-card-title">Candidate Portal</h2>
                        <p class="gateway-card-desc">Search job listings, submit applications with resume & skills, monitor pipeline status & take tasks.</p>
                    </div>

                    <div class="gateway-card-body">
                        <!-- Mode Selector: Login vs Register -->
                        <div class="gateway-mode-tabs">
                            <button type="button" class="gateway-mode-tab active" id="gatewayCandidateTabLogin" onclick="switchGatewayMode('candidate', 'login')">Sign In</button>
                            <button type="button" class="gateway-mode-tab" id="gatewayCandidateTabRegister" onclick="switchGatewayMode('candidate', 'register')">Register New</button>
                        </div>

                        <!-- Candidate Login Form -->
                        <div id="gatewayCandidateLoginForm">
                            <div class="gateway-quick-fill-box">
                                <span class="gateway-quick-fill-text">Seed: <code>john.doe@talentflow.test</code></span>
                                <button type="button" class="gateway-quick-fill-btn" onclick="gatewayFill('candidate')">Quick Fill</button>
                            </div>

                            <form onsubmit="handleGatewayLogin(event, 'candidate')">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label class="form-label">Candidate Email</label>
                                    <input type="email" class="form-control" name="email" id="gatewayCandidateEmail" placeholder="john.doe@talentflow.test" required>
                                </div>
                                <div class="form-group" style="margin-bottom:16px;">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="gatewayCandidatePassword" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:8px;">Sign In as Candidate</button>
                                <button type="button" class="btn btn-outline btn-sm" style="width:100%; justify-content:center;" onclick="instantLogin('candidate')">⚡ 1-Click Demo Login</button>
                            </form>
                        </div>

                        <!-- Candidate Register Form -->
                        <div id="gatewayCandidateRegisterForm" style="display:none;">
                            <form onsubmit="handleGatewayRegister(event, 'candidate')">
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Your Full Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Personal Email *</label>
                                    <input type="email" class="form-control" name="email" placeholder="john@example.com" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+1-555-0200">
                                </div>
                                <div class="form-group" style="margin-bottom:16px;">
                                    <label class="form-label">Password * (min 6 chars)</label>
                                    <input type="password" class="form-control" name="password" minlength="6" placeholder="At least 6 characters" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register Candidate Account</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 3. ADMIN PORTAL -->
                <div class="gateway-card">
                    <div class="gateway-card-header">
                        <div class="gateway-card-top">
                            <span class="gateway-role-icon">🛡️</span>
                            <span class="role-badge role-admin">Administrator</span>
                        </div>
                        <h2 class="gateway-card-title">Admin Portal</h2>
                        <p class="gateway-card-desc">Master system configuration, oversee user accounts, view complete database pipeline & analytics.</p>
                    </div>

                    <div class="gateway-card-body">
                        <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:10px 12px; font-size:0.8rem; color:#92400e; margin-bottom:16px; line-height:1.4;">
                            🛡️ <strong>Admin Notice:</strong> Admin accounts are provisioned via system seeds. Self-registration is restricted for security.
                        </div>

                        <div class="gateway-quick-fill-box">
                            <span class="gateway-quick-fill-text">Seed: <code>admin@talentflow.test</code></span>
                            <button type="button" class="gateway-quick-fill-btn" onclick="gatewayFill('admin')">Quick Fill</button>
                        </div>

                        <form onsubmit="handleGatewayLogin(event, 'admin')">
                            <div class="form-group" style="margin-bottom:12px;">
                                <label class="form-label">Admin Email</label>
                                <input type="email" class="form-control" name="email" id="gatewayAdminEmail" placeholder="admin@talentflow.test" required>
                            </div>
                            <div class="form-group" style="margin-bottom:16px;">
                                <label class="form-label">Admin Password</label>
                                <input type="password" class="form-control" name="password" id="gatewayAdminPassword" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:8px;">Sign In as Admin</button>
                            <button type="button" class="btn btn-outline btn-sm" style="width:100%; justify-content:center;" onclick="instantLogin('admin')">⚡ 1-Click Demo Login</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- MAIN APP WORKSPACE CONTAINER (Hidden until authenticated) -->
    <div id="appWorkspace" style="display:none; flex-direction:column; min-height:100vh; width:100%;">

        <!-- Top Navbar -->
        <header class="navbar">
            <div class="nav-container">
                <div style="display:flex; align-items:center; gap: 20px;">
                    <a href="/" class="brand">
                        <span>⚡ TalentFlow</span>
                        <span class="brand-badge">Hiring System</span>
                    </a>

                    <ul class="nav-tabs">
                        <li><button class="active" onclick="switchTab('dashboard')">Dashboard</button></li>
                        <li><button onclick="switchTab('jobs')">Jobs</button></li>
                        <li id="tabNavItemPipeline"><button onclick="switchTab('pipeline')">Pipeline</button></li>
                        <li><button onclick="switchTab('interviews')">Interviews</button></li>
                        <li><button onclick="switchTab('tasks')">Tasks</button></li>
                        <li id="tabNavItemCandidates"><button onclick="switchTab('candidates')">Candidates</button></li>
                    </ul>
                </div>

                <!-- User Auth & Role Area -->
                <div class="user-nav-box">
                    <div id="userLoggedInBlock" style="display:none; align-items:center; gap:10px;">
                        <span id="navUserName" style="font-weight:600; font-size:0.88rem;">Alex Miller</span>
                        <span class="role-badge role-recruiter" id="navRoleBadge">recruiter</span>
                        <button class="btn btn-outline btn-sm" onclick="switchRole()" title="Switch to another role or persona">🔄 Switch Role</button>
                        <button class="btn btn-outline btn-sm" onclick="logout()" title="Logout">🚪 Logout</button>
                    </div>
                    <div id="userGuestBlock">
                        <button class="btn btn-primary btn-sm" onclick="showAuthGateway()">Sign In / Register</button>
                    </div>
                    <button class="btn btn-outline btn-sm" onclick="runDeadlineCheck()" title="Check task deadlines">⏱️ Check Deadlines</button>
                </div>
            </div>
        </header>

    <!-- Main Workspace -->
    <main class="main-content">

        <!-- 1. DASHBOARD TAB -->
        <section class="tab-panel active" id="panel-dashboard">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Overview & Analytics</h2>
                    <p class="section-desc">Key metrics and hiring funnel status across all active jobs.</p>
                </div>
                <div id="recruiterDashboardActions">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalJob')">+ Post Job</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Jobs</div>
                    <div class="stat-value" id="statJobs">0</div>
                    <div class="stat-sub" id="statActiveJobs">0 open positions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Active Candidates</div>
                    <div class="stat-value" id="statCandidates" style="color:var(--primary);">0</div>
                    <div class="stat-sub">Screened & in pipeline</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Interviews This Week</div>
                    <div class="stat-value" id="statInterviews">0</div>
                    <div class="stat-sub">Scheduled sessions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Avg Candidate Score</div>
                    <div class="stat-value" id="statAvgScore" style="color:var(--success);">0%</div>
                    <div class="stat-sub">Resume skill match</div>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">Hiring Pipeline Funnel</h3>
                <div class="funnel-container" id="funnelContainer"></div>
            </div>
        </section>

        <!-- 2. JOBS TAB -->
        <section class="tab-panel" id="panel-jobs">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Job Openings</h2>
                    <p class="section-desc">Manage openings, view mandatory and bonus skills, or submit an application.</p>
                </div>
                <div id="recruiterJobActions">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalJob')">+ Post New Job</button>
                </div>
            </div>

            <div class="toolbar">
                <input type="text" id="jobSearch" class="input-text" placeholder="Search job title, department..." oninput="filterJobs()">
                <select id="jobStatus" class="input-select" onchange="filterJobs()">
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                </select>
            </div>

            <div class="jobs-grid" id="jobsGrid"></div>
        </section>

        <!-- 3. PIPELINE KANBAN TAB -->
        <section class="tab-panel" id="panel-pipeline">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Hiring Pipeline</h2>
                    <p class="section-desc">Track applicants through stages. Click any candidate card to advance their stage.</p>
                </div>
                <button class="btn btn-outline btn-sm" onclick="loadApplications()">🔄 Refresh</button>
            </div>

            <div class="pipeline-board" id="pipelineBoard"></div>
        </section>

        <!-- 4. INTERVIEWS TAB -->
        <section class="tab-panel" id="panel-interviews">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Interviews</h2>
                    <p class="section-desc">Scheduled interview sessions with conflict validation.</p>
                </div>
                <div id="recruiterInterviewActions">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalInterview')">+ Schedule Interview</button>
                </div>
            </div>

            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-wrap">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Position</th>
                                <th>Interviewer</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="interviewsTable"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 5. TECHNICAL TASKS TAB -->
        <section class="tab-panel" id="panel-tasks">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Technical Tasks</h2>
                    <p class="section-desc">Assign coding challenges, track submissions, and grade solutions.</p>
                </div>
                <div id="recruiterTaskActions">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modalTask')">+ Assign Task</button>
                </div>
            </div>

            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-wrap">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Task Title</th>
                                <th>Candidate</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Submission</th>
                                <th>Score</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tasksTable"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 6. CANDIDATES TAB -->
        <section class="tab-panel" id="panel-candidates">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Candidate Directory</h2>
                    <p class="section-desc">Extracted skills, experience, and education profiles.</p>
                </div>
            </div>

            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-wrap">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Candidate Name</th>
                                <th>Email</th>
                                <th>Experience</th>
                                <th>Education</th>
                                <th>Extracted Skills</th>
                            </tr>
                        </thead>
                        <tbody id="candidatesTable"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>
    </div> <!-- /#appWorkspace -->

    <!-- AUTHENTICATION PORTAL MODAL (Dedicated Recruiter, Candidate & Admin logins) -->
    <div class="modal-backdrop" id="modalAuth">
        <div class="modal-content" style="max-width: 480px;">
            <div class="modal-head">
                <h3 id="authModalTitle">Sign In to TalentFlow</h3>
                <button class="btn-close" onclick="closeModal('modalAuth')">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Portal Type: Recruiter / Candidate / Admin -->
                <div class="auth-portal-tabs">
                    <button class="auth-portal-tab active" id="tabPortalRecruiter" onclick="switchPortal('recruiter')">👔 Recruiter</button>
                    <button class="auth-portal-tab" id="tabPortalCandidate" onclick="switchPortal('candidate')">👤 Candidate</button>
                    <button class="auth-portal-tab" id="tabPortalAdmin" onclick="switchPortal('admin')">🛡️ Admin</button>
                </div>

                <!-- 1. RECRUITER PORTAL -->
                <div id="portalRecruiter">
                    <div class="auth-sub-tabs">
                        <button class="auth-sub-tab active" id="recruiterSubLogin" onclick="switchSubAuth('recruiter', 'login')">Recruiter Login</button>
                        <button class="auth-sub-tab" id="recruiterSubRegister" onclick="switchSubAuth('recruiter', 'register')">Recruiter Register</button>
                    </div>

                    <!-- Recruiter Login -->
                    <form id="formRecruiterLogin" onsubmit="handleAuthLogin(event, 'recruiter')">
                        <div class="demo-pill" style="margin-bottom:12px;">
                            <span>Seed: <code>recruiter@talentflow.test</code></span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillCreds('recruiter', 'recruiter@talentflow.test', 'password')">Quick Fill</button>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Work Email</label>
                            <input type="email" class="form-control" name="email" id="recruiterLoginEmail" required>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="recruiterLoginPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Login as Recruiter</button>
                    </form>

                    <!-- Recruiter Register -->
                    <form id="formRecruiterRegister" style="display:none;" onsubmit="handleAuthRegister(event, 'recruiter')">
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="Alex Miller">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Work Email *</label>
                            <input type="email" class="form-control" name="email" required placeholder="alex@company.com">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+1-555-0100">
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" minlength="6" required placeholder="At least 6 characters">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register as Recruiter</button>
                    </form>
                </div>

                <!-- 2. CANDIDATE PORTAL -->
                <div id="portalCandidate" style="display:none;">
                    <div class="auth-sub-tabs">
                        <button class="auth-sub-tab active" id="candidateSubLogin" onclick="switchSubAuth('candidate', 'login')">Candidate Login</button>
                        <button class="auth-sub-tab" id="candidateSubRegister" onclick="switchSubAuth('candidate', 'register')">Candidate Register</button>
                    </div>

                    <!-- Candidate Login -->
                    <form id="formCandidateLogin" onsubmit="handleAuthLogin(event, 'candidate')">
                        <div class="demo-pill" style="margin-bottom:12px;">
                            <span>Seed: <code>john.doe@talentflow.test</code></span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillCreds('candidate', 'john.doe@talentflow.test', 'password')">Quick Fill</button>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="candidateLoginEmail" required>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="candidateLoginPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Login as Candidate</button>
                    </form>

                    <!-- Candidate Register -->
                    <form id="formCandidateRegister" style="display:none;" onsubmit="handleAuthRegister(event, 'candidate')">
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Your Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="John Doe">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required placeholder="john@example.com">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+1-555-0200">
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" minlength="6" required placeholder="At least 6 characters">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register as Candidate</button>
                    </form>
                </div>

                <!-- 3. ADMIN PORTAL (Login only) -->
                <div id="portalAdmin" style="display:none;">
                    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:6px; padding:10px; font-size:0.82rem; color:#92400e; margin-bottom:14px;">
                        🛡️ <strong>Admin Portal:</strong> System administrator access with full control across jobs, candidates, pipeline, and analytics.
                    </div>

                    <form id="formAdminLogin" onsubmit="handleAuthLogin(event, 'admin')">
                        <div class="demo-pill" style="margin-bottom:12px;">
                            <span>Seed: <code>admin@talentflow.test</code></span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillCreds('admin', 'admin@talentflow.test', 'password')">Quick Fill</button>
                        </div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Admin Email</label>
                            <input type="email" class="form-control" name="email" id="adminLoginEmail" required>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Admin Password</label>
                            <input type="password" class="form-control" name="password" id="adminLoginPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Login as Admin</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Job Modals -->
    <div class="modal-backdrop" id="modalJob">
        <div class="modal-content">
            <div class="modal-head">
                <h3>Post New Job Opening</h3>
                <button class="btn-close" onclick="closeModal('modalJob')">&times;</button>
            </div>
            <form id="formJob" onsubmit="submitJob(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Job Title *</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g. Senior Laravel Developer" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department *</label>
                        <input type="text" class="form-control" name="department" placeholder="e.g. Engineering" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Experience Required *</label>
                        <input type="text" class="form-control" name="experience" placeholder="e.g. 3-5 years" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Range</label>
                        <input type="text" class="form-control" name="salary_range" placeholder="e.g. $80,000 - $110,000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Application Deadline *</label>
                        <input type="date" class="form-control" name="application_deadline" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mandatory Skills (comma-separated)</label>
                        <input type="text" class="form-control" name="mandatory_skills" placeholder="PHP, Laravel, MySQL, REST API">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bonus Skills (comma-separated)</label>
                        <input type="text" class="form-control" name="bonus_skills" placeholder="Docker, Vue.js, Redis">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Description *</label>
                        <textarea class="form-control" name="description" placeholder="Brief job summary..." required></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJob')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Job</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Apply Job Modal -->
    <div class="modal-backdrop" id="modalApply">
        <div class="modal-content">
            <div class="modal-head">
                <h3 id="applyTitle">Apply for Job</h3>
                <button class="btn-close" onclick="closeModal('modalApply')">&times;</button>
            </div>
            <form id="formApply" onsubmit="submitApply(event)">
                <input type="hidden" name="job_id" id="applyJobId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="name" id="applyName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-control" name="email" id="applyEmail" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="+1-555-0100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" step="0.5" class="form-control" name="experience_years" value="3.0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">PDF Resume (Optional)</label>
                        <input type="file" class="form-control" name="resume" accept="application/pdf">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Skills (comma-separated)</label>
                        <input type="text" class="form-control" name="skills_summary" placeholder="PHP, Laravel, MySQL, Docker">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" placeholder="Cover note..."></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalApply')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Move Stage Modal -->
    <div class="modal-backdrop" id="modalMove">
        <div class="modal-content">
            <div class="modal-head">
                <h3>Move Application Stage</h3>
                <button class="btn-close" onclick="closeModal('modalMove')">&times;</button>
            </div>
            <form id="formMove" onsubmit="submitMove(event)">
                <input type="hidden" name="application_id" id="moveAppId">
                <div class="modal-body">
                    <p style="font-size:0.85rem; color:var(--text-muted);" id="moveInfo">Candidate info</p>
                    <div class="form-group">
                        <label class="form-label">Select Target Stage *</label>
                        <select class="form-control" name="status" id="moveSelectStatus" required>
                            <option value="Applied">Applied</option>
                            <option value="Screening">Screening</option>
                            <option value="Shortlisted">Shortlisted</option>
                            <option value="Interview">Interview</option>
                            <option value="Technical Task">Technical Task</option>
                            <option value="Hired">Hired</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stage Note / Reason</label>
                        <textarea class="form-control" name="comment" placeholder="History log note..."></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalMove')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Stage</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedule Interview Modal -->
    <div class="modal-backdrop" id="modalInterview">
        <div class="modal-content">
            <div class="modal-head">
                <h3>Schedule Interview</h3>
                <button class="btn-close" onclick="closeModal('modalInterview')">&times;</button>
            </div>
            <form id="formInterview" onsubmit="submitInterview(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Candidate Application *</label>
                        <select class="form-control" name="application_id" id="interviewAppSelect" required></select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date & Time *</label>
                        <input type="datetime-local" class="form-control" name="scheduled_at" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meeting Link *</label>
                        <input type="url" class="form-control" name="meeting_link" value="https://meet.google.com/talentflow-interview" required>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalInterview')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Schedule</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assign Task Modal -->
    <div class="modal-backdrop" id="modalTask">
        <div class="modal-content">
            <div class="modal-head">
                <h3>Assign Technical Task</h3>
                <button class="btn-close" onclick="closeModal('modalTask')">&times;</button>
            </div>
            <form id="formTask" onsubmit="submitTask(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Candidate Application *</label>
                        <select class="form-control" name="application_id" id="taskAppSelect" required></select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Title *</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g. Build an API Module" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deadline *</label>
                        <input type="datetime-local" class="form-control" name="deadline" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description & Instructions *</label>
                        <textarea class="form-control" name="description" placeholder="Specify task expectations..." required></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalTask')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Submit Task Solution Modal -->
    <div class="modal-backdrop" id="modalSubmitTask">
        <div class="modal-content">
            <div class="modal-head">
                <h3>Submit Task Solution</h3>
                <button class="btn-close" onclick="closeModal('modalSubmitTask')">&times;</button>
            </div>
            <form id="formSubmitTask" onsubmit="submitTaskSolution(event)">
                <input type="hidden" name="task_id" id="submitTaskId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Repository URL (GitHub / GitLab)</label>
                        <input type="url" class="form-control" name="repository_url" placeholder="https://github.com/user/project">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" placeholder="Notes on solution..."></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalSubmitTask')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit Solution</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Task Modal -->
    <div class="modal-backdrop" id="modalReviewTask">
        <div class="modal-content">
            <div class="modal-head">
                <h3>Grade & Review Task</h3>
                <button class="btn-close" onclick="closeModal('modalReviewTask')">&times;</button>
            </div>
            <form id="formReviewTask" onsubmit="submitReview(event)">
                <input type="hidden" name="task_id" id="reviewTaskId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Score (0 - 100) *</label>
                        <input type="number" min="0" max="100" class="form-control" name="score" value="85" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Written Feedback *</label>
                        <textarea class="form-control" name="feedback" placeholder="Code review notes..." required></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalReviewTask')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Review</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Messages -->
    <div class="toast-box" id="toastBox"></div>

    <script>
        // State
        const state = {
            token: localStorage.getItem('tf_token') || '',
            currentUser: null,
            jobs: [],
            applications: [],
            interviews: [],
            tasks: [],
            candidates: []
        };

        document.addEventListener('DOMContentLoaded', async () => {
            // First Login Role Gateway is displayed by default immediately
            showAuthGateway();

            // Only resume previous workspace if user was actively in workspace and has valid token
            if (localStorage.getItem('tf_in_workspace') === 'true' && state.token) {
                const valid = await checkUser();
                if (valid) {
                    showAppWorkspace();
                    await reloadAll();
                } else {
                    localStorage.removeItem('tf_in_workspace');
                    showAuthGateway();
                }
            }
        });

        // Gateway Screen & Workspace Visibility
        function showAuthGateway() {
            const gateway = document.getElementById('authGatewayScreen');
            const workspace = document.getElementById('appWorkspace');
            if (gateway) gateway.style.display = 'flex';
            if (workspace) workspace.style.display = 'none';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showAppWorkspace() {
            const gateway = document.getElementById('authGatewayScreen');
            const workspace = document.getElementById('appWorkspace');
            if (gateway) gateway.style.display = 'none';
            if (workspace) workspace.style.display = 'flex';
        }

        function switchGatewayMode(role, mode) {
            const cap = role.charAt(0).toUpperCase() + role.slice(1);
            const loginForm = document.getElementById(`gateway${cap}LoginForm`);
            const registerForm = document.getElementById(`gateway${cap}RegisterForm`);
            const loginTab = document.getElementById(`gateway${cap}TabLogin`);
            const registerTab = document.getElementById(`gateway${cap}TabRegister`);

            if (mode === 'login') {
                if (loginForm) loginForm.style.display = 'block';
                if (registerForm) registerForm.style.display = 'none';
                if (loginTab) loginTab.classList.add('active');
                if (registerTab) registerTab.classList.remove('active');
            } else {
                if (loginForm) loginForm.style.display = 'none';
                if (registerForm) registerForm.style.display = 'block';
                if (loginTab) loginTab.classList.remove('active');
                if (registerTab) registerTab.classList.add('active');
            }
        }

        function gatewayFill(role) {
            if (role === 'admin') {
                const e = document.getElementById('gatewayAdminEmail');
                const p = document.getElementById('gatewayAdminPassword');
                if (e && p) { e.value = 'admin@talentflow.test'; p.value = 'password'; }
                showToast('Admin credentials populated!', 'success');
            } else if (role === 'recruiter') {
                const e = document.getElementById('gatewayRecruiterEmail');
                const p = document.getElementById('gatewayRecruiterPassword');
                if (e && p) { e.value = 'recruiter@talentflow.test'; p.value = 'password'; }
                showToast('Recruiter credentials populated!', 'success');
            } else if (role === 'candidate') {
                const e = document.getElementById('gatewayCandidateEmail');
                const p = document.getElementById('gatewayCandidatePassword');
                if (e && p) { e.value = 'john.doe@talentflow.test'; p.value = 'password'; }
                showToast('Candidate credentials populated!', 'success');
            }
        }

        async function instantLogin(role) {
            if (role === 'admin') {
                await performLogin('admin@talentflow.test', 'password');
            } else if (role === 'candidate') {
                await performLogin('john.doe@talentflow.test', 'password');
            } else {
                await performLogin('recruiter@talentflow.test', 'password');
            }
        }

        async function handleGatewayLogin(e, role) {
            e.preventDefault();
            const form = e.target;
            const email = form.email.value;
            const password = form.password.value;
            await performLogin(email, password);
        }

        async function handleGatewayRegister(e, role) {
            e.preventDefault();
            const form = e.target;
            const payload = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone ? form.phone.value : null,
                password: form.password.value,
                role: role
            };

            try {
                const res = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (res.ok) {
                    state.token = data.token;
                    state.currentUser = data.user;
                    localStorage.setItem('tf_token', data.token);
                    localStorage.setItem('tf_in_workspace', 'true');
                    showToast(`Registration successful! Welcome ${data.user.name}`, 'success');
                    showAppWorkspace();
                    updateRoleUI();
                    await reloadAll();
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Registration failed');
                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                showToast('Registration error', 'error');
            }
        }

        // Tab Switching
        function switchTab(name) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-tabs button').forEach(b => b.classList.remove('active'));

            const panel = document.getElementById(`panel-${name}`);
            const btn = Array.from(document.querySelectorAll('.nav-tabs button')).find(b => b.getAttribute('onclick')?.includes(name));

            if (panel) panel.classList.add('active');
            if (btn) btn.classList.add('active');
        }

        // Auth Modal Portals
        function openAuthModal() {
            openModal('modalAuth');
        }

        function switchPortal(portal) {
            document.querySelectorAll('.auth-portal-tab').forEach(b => b.classList.remove('active'));
            document.getElementById(`tabPortal${portal.charAt(0).toUpperCase() + portal.slice(1)}`).classList.add('active');

            document.getElementById('portalRecruiter').style.display = portal === 'recruiter' ? 'block' : 'none';
            document.getElementById('portalCandidate').style.display = portal === 'candidate' ? 'block' : 'none';
            document.getElementById('portalAdmin').style.display = portal === 'admin' ? 'block' : 'none';
        }

        function switchSubAuth(portal, type) {
            const loginForm = document.getElementById(`form${portal.charAt(0).toUpperCase() + portal.slice(1)}Login`);
            const registerForm = document.getElementById(`form${portal.charAt(0).toUpperCase() + portal.slice(1)}Register`);
            const loginTab = document.getElementById(`${portal}SubLogin`);
            const registerTab = document.getElementById(`${portal}SubRegister`);

            if (type === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                loginTab.classList.add('active');
                registerTab.classList.remove('active');
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                loginTab.classList.remove('active');
                registerTab.classList.add('active');
            }
        }

        function fillCreds(portal, email, password) {
            const emailInput = document.getElementById(`${portal}LoginEmail`);
            const passInput = document.getElementById(`${portal}LoginPassword`);
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = password;
            }
        }

        async function handleAuthLogin(e, portal) {
            e.preventDefault();
            const form = e.target;
            const email = form.email.value;
            const password = form.password.value;
            await performLogin(email, password);
        }

        async function performLogin(email, password) {
            try {
                const res = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await res.json();
                if (res.ok) {
                    state.token = data.token;
                    state.currentUser = data.user;
                    localStorage.setItem('tf_token', data.token);
                    localStorage.setItem('tf_in_workspace', 'true');
                    showToast(`Logged in as ${data.user.name} (${data.user.role?.name || 'user'})`, 'success');
                    closeModal('modalAuth');
                    showAppWorkspace();
                    updateRoleUI();
                    await reloadAll();
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Login failed: Invalid credentials');
                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                showToast('Authentication connection error', 'error');
            }
        }

        async function handleAuthRegister(e, role) {
            e.preventDefault();
            const form = e.target;
            const payload = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone ? form.phone.value : null,
                password: form.password.value,
                role: role
            };

            try {
                const res = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (res.ok) {
                    state.token = data.token;
                    state.currentUser = data.user;
                    localStorage.setItem('tf_token', data.token);
                    localStorage.setItem('tf_in_workspace', 'true');
                    showToast(`Registration successful! Welcome ${data.user.name}`, 'success');
                    closeModal('modalAuth');
                    showAppWorkspace();
                    updateRoleUI();
                    await reloadAll();
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Registration failed');
                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                showToast('Registration error', 'error');
            }
        }

        async function checkUser() {
            try {
                const res = await api('/api/auth/me');
                if (res.ok) {
                    const data = await res.json();
                    state.currentUser = data.user;
                    updateRoleUI();
                    return true;
                }
            } catch (e) {}
            state.token = '';
            state.currentUser = null;
            localStorage.removeItem('tf_token');
            return false;
        }

        function updateRoleUI() {
            const user = state.currentUser;
            if (!user) {
                document.getElementById('userLoggedInBlock').style.display = 'none';
                document.getElementById('userGuestBlock').style.display = 'block';
                return;
            }

            document.getElementById('userLoggedInBlock').style.display = 'flex';
            document.getElementById('userGuestBlock').style.display = 'none';
            document.getElementById('navUserName').textContent = user.name;

            const role = user.role?.name || 'candidate';
            const badge = document.getElementById('navRoleBadge');
            badge.textContent = role;
            badge.className = `role-badge role-${role}`;

            // Adapt navigation elements based on role
            const isRecruiterOrAdmin = role === 'recruiter' || role === 'admin';
            document.getElementById('recruiterDashboardActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('recruiterJobActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('recruiterInterviewActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('recruiterTaskActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('tabNavItemCandidates').style.display = isRecruiterOrAdmin ? 'block' : 'none';

            // Auto-fill apply form
            const applyName = document.getElementById('applyName');
            const applyEmail = document.getElementById('applyEmail');
            if (applyName && applyEmail) {
                applyName.value = user.name;
                applyEmail.value = user.email;
            }
        }

        async function logout() {
            const token = state.token;
            state.token = '';
            state.currentUser = null;
            localStorage.removeItem('tf_token');
            localStorage.removeItem('tf_in_workspace');
            updateRoleUI();
            showToast('Logged out successfully', 'success');
            showAuthGateway();

            if (token) {
                try {
                    await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });
                } catch (e) {}
            }
        }

        async function switchRole() {
            await logout();
        }

        // API Fetch
        async function api(path, options = {}) {
            const headers = options.headers || {};
            headers['Accept'] = 'application/json';
            if (state.token) headers['Authorization'] = `Bearer ${state.token}`;
            if (!(options.body instanceof FormData) && !headers['Content-Type']) {
                headers['Content-Type'] = 'application/json';
            }
            return fetch(path, { ...options, headers });
        }

        // Reload Data
        async function reloadAll() {
            await Promise.all([
                loadAnalytics(),
                loadJobs(),
                loadApplications(),
                loadInterviews(),
                loadTasks(),
                loadCandidates()
            ]);
        }

        // 1. Analytics
        async function loadAnalytics() {
            try {
                const res = await api('/api/dashboard/analytics');
                if (res.ok) {
                    const { analytics } = await res.json();
                    document.getElementById('statJobs').textContent = analytics.total_jobs;
                    document.getElementById('statActiveJobs').textContent = `${analytics.active_jobs} open positions`;
                    document.getElementById('statCandidates').textContent = analytics.active_candidates;
                    document.getElementById('statInterviews').textContent = analytics.interviews_this_week;
                    document.getElementById('statAvgScore').textContent = `${analytics.average_candidate_score}%`;

                    // Render funnel
                    const container = document.getElementById('funnelContainer');
                    container.innerHTML = '';
                    const total = Math.max(1, analytics.total_applications);

                    for (const [stage, count] of Object.entries(analytics.pipeline_distribution)) {
                        const pct = Math.round((count / total) * 100);
                        const row = document.createElement('div');
                        row.className = 'funnel-row';
                        row.innerHTML = `
                            <div class="funnel-info">
                                <span>${stage}</span>
                                <span style="color:var(--text-muted);">${count} (${pct}%)</span>
                            </div>
                            <div class="funnel-track">
                                <div class="funnel-fill" style="width: ${Math.max(4, pct)}%;"></div>
                            </div>
                        `;
                        container.appendChild(row);
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }

        // 2. Jobs
        async function loadJobs() {
            try {
                const res = await api('/api/jobs');
                if (res.ok) {
                    const { data } = await res.json();
                    state.jobs = data;
                    renderJobs(data);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderJobs(jobs) {
            const container = document.getElementById('jobsGrid');
            container.innerHTML = '';

            if (jobs.length === 0) {
                container.innerHTML = '<div style="color:var(--text-muted); padding:20px;">No job openings found.</div>';
                return;
            }

            jobs.forEach(job => {
                let skillsHtml = '';
                if (job.skills) {
                    job.skills.forEach(s => {
                        skillsHtml += `<span class="skill-tag ${s.is_mandatory ? 'mandatory' : ''}">${s.name}</span>`;
                    });
                }

                const card = document.createElement('div');
                card.className = 'job-card';
                card.innerHTML = `
                    <div>
                        <div class="job-header">
                            <div>
                                <h4 class="job-title">${job.title}</h4>
                                <div class="job-dept">${job.department} • ${job.experience}</div>
                            </div>
                            <span class="badge badge-${job.status}">${job.status}</span>
                        </div>
                        <p class="job-desc">${job.description}</p>
                        <div class="skills-list">${skillsHtml}</div>
                    </div>
                    <div class="job-footer">
                        <span class="job-salary">${job.salary_range || 'Competitive'}</span>
                        <button class="btn btn-primary btn-sm" onclick="openApply(${job.id}, '${escapeHtml(job.title)}')">Apply</button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function filterJobs() {
            const search = document.getElementById('jobSearch').value.toLowerCase();
            const status = document.getElementById('jobStatus').value;

            const filtered = state.jobs.filter(j => {
                const matchSearch = !search || j.title.toLowerCase().includes(search) || j.department.toLowerCase().includes(search);
                const matchStatus = !status || j.status === status;
                return matchSearch && matchStatus;
            });
            renderJobs(filtered);
        }

        // 3. Applications / Pipeline
        async function loadApplications() {
            try {
                const res = await api('/api/applications');
                if (res.ok) {
                    const { data } = await res.json();
                    state.applications = data;
                    renderPipeline(data);
                    populateSelects(data);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderPipeline(apps) {
            const stages = ['Applied', 'Screening', 'Shortlisted', 'Interview', 'Technical Task', 'Hired', 'Rejected'];
            const board = document.getElementById('pipelineBoard');
            board.innerHTML = '';

            const isRecruiterOrAdmin = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';

            stages.forEach(stage => {
                const colApps = apps.filter(a => a.status === stage);
                const col = document.createElement('div');
                col.className = 'pipeline-col';

                let itemsHtml = '';
                if (colApps.length === 0) {
                    itemsHtml = '<div style="font-size:0.8rem; color:var(--text-light); text-align:center; padding:16px 0;">No candidates</div>';
                } else {
                    colApps.forEach(a => {
                        const score = Math.round(a.skill_score || 0);
                        itemsHtml += `
                            <div class="app-item" ${isRecruiterOrAdmin ? `onclick="openMove(${a.id}, '${escapeHtml(a.candidate?.name || 'Applicant')}', '${a.status}')"` : ''}>
                                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                    <div class="app-name">${a.candidate?.name || 'Candidate'}</div>
                                    <span class="badge ${score >= 80 ? 'badge-hired' : (score >= 60 ? 'badge-shortlisted' : 'badge-applied')}">${score}%</span>
                                </div>
                                <div class="app-role">${a.job?.title || 'Job'}</div>
                                <div class="app-footer">
                                    <span>${a.candidate?.experience_years || 0}y exp</span>
                                    ${isRecruiterOrAdmin ? '<span style="color:var(--primary); font-weight:600;">Move &rarr;</span>' : '<span style="color:var(--text-muted);">Stage Logged</span>'}
                                </div>
                            </div>
                        `;
                    });
                }

                col.innerHTML = `
                    <div class="pipeline-col-header">
                        <span>${stage}</span>
                        <span class="badge badge-pending">${colApps.length}</span>
                    </div>
                    ${itemsHtml}
                `;
                board.appendChild(col);
            });
        }

        function populateSelects(apps) {
            const intSel = document.getElementById('interviewAppSelect');
            const taskSel = document.getElementById('taskAppSelect');
            if (!intSel || !taskSel) return;

            let opts = '<option value="">Select candidate application...</option>';
            apps.forEach(a => {
                opts += `<option value="${a.id}">${a.candidate?.name} – ${a.job?.title} (${a.status})</option>`;
            });

            intSel.innerHTML = opts;
            taskSel.innerHTML = opts;
        }

        // 4. Interviews
        async function loadInterviews() {
            try {
                const res = await api('/api/interviews');
                if (res.ok) {
                    const { data } = await res.json();
                    state.interviews = data;
                    renderInterviews(data);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderInterviews(interviews) {
            const tbody = document.getElementById('interviewsTable');
            tbody.innerHTML = '';

            if (interviews.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:20px; color:var(--text-muted);">No interviews scheduled.</td></tr>';
                return;
            }

            const isRecruiterOrAdmin = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';

            interviews.forEach(i => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600;">${i.candidate?.name || 'Candidate'}</td>
                    <td>${i.job?.title || 'Position'}</td>
                    <td>${i.interviewer?.name || 'Recruiter'}</td>
                    <td>${new Date(i.scheduled_at).toLocaleString()}</td>
                    <td><span class="badge badge-${i.status}">${i.status}</span></td>
                    <td><a href="${i.meeting_link}" target="_blank" style="color:var(--primary); text-decoration:none;">Open Link</a></td>
                    <td>
                        ${(i.status === 'scheduled' && isRecruiterOrAdmin) ? `
                            <button class="btn btn-outline btn-sm" onclick="completeInterview(${i.id})">Done</button>
                            <button class="btn btn-outline btn-sm" style="color:var(--danger);" onclick="cancelInterview(${i.id})">Cancel</button>
                        ` : '<span style="color:var(--text-light);">-</span>'}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // 5. Tasks
        async function loadTasks() {
            try {
                const res = await api('/api/technical-tasks');
                if (res.ok) {
                    const { data } = await res.json();
                    state.tasks = data;
                    renderTasks(data);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderTasks(tasks) {
            const tbody = document.getElementById('tasksTable');
            tbody.innerHTML = '';

            if (tasks.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:20px; color:var(--text-muted);">No tasks assigned yet.</td></tr>';
                return;
            }

            const isRecruiterOrAdmin = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';

            tasks.forEach(t => {
                const sub = t.latest_submission;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600;">${t.title}</td>
                    <td>${t.assigned_by?.name || 'Candidate'}</td>
                    <td>${new Date(t.deadline).toLocaleDateString()}</td>
                    <td><span class="badge badge-${t.status.toLowerCase().replace(' ', '_')}">${t.status}</span></td>
                    <td>${sub?.repository_url ? `<a href="${sub.repository_url}" target="_blank" style="color:var(--primary);">View Solution</a>` : '<span style="color:var(--text-light);">None</span>'}</td>
                    <td>${sub?.score !== null && sub?.score !== undefined ? `<strong>${sub.score}/100</strong>` : '-'}</td>
                    <td>
                        ${t.status === 'Pending' ? `
                            <button class="btn btn-outline btn-sm" onclick="startTask(${t.id})">Start</button>
                            <button class="btn btn-primary btn-sm" onclick="openSubmitTask(${t.id})">Submit</button>
                        ` : ''}
                        ${t.status === 'In Progress' ? `
                            <button class="btn btn-primary btn-sm" onclick="openSubmitTask(${t.id})">Submit</button>
                        ` : ''}
                        ${(t.status === 'Submitted' && isRecruiterOrAdmin) ? `
                            <button class="btn btn-success btn-sm" onclick="openReviewTask(${t.id})">Grade</button>
                        ` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // 6. Candidates
        async function loadCandidates() {
            try {
                const res = await api('/api/candidates');
                if (res.ok) {
                    const { data } = await res.json();
                    state.candidates = data;
                    renderCandidates(data);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderCandidates(candidates) {
            const tbody = document.getElementById('candidatesTable');
            tbody.innerHTML = '';

            if (candidates.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:var(--text-muted);">No candidates found.</td></tr>';
                return;
            }

            candidates.forEach(c => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600;">${c.name}</td>
                    <td>${c.email}</td>
                    <td>${c.experience_years} years</td>
                    <td>${c.education || 'N/A'}</td>
                    <td style="color:var(--text-muted); font-size:0.82rem;">${c.skills_summary || 'N/A'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Modals & Action Helpers
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        async function submitJob(e) {
            e.preventDefault();
            const f = e.target;
            const res = await api('/api/jobs', {
                method: 'POST',
                body: JSON.stringify({
                    title: f.title.value,
                    department: f.department.value,
                    experience: f.experience.value,
                    salary_range: f.salary_range.value,
                    application_deadline: f.application_deadline.value,
                    description: f.description.value,
                    mandatory_skills: f.mandatory_skills.value.split(',').map(s => s.trim()).filter(Boolean),
                    bonus_skills: f.bonus_skills.value.split(',').map(s => s.trim()).filter(Boolean)
                })
            });

            if (res.ok) {
                showToast('Job created successfully!', 'success');
                closeModal('modalJob');
                f.reset();
                await reloadAll();
            } else {
                const d = await res.json();
                showToast(d.message || 'Error creating job', 'error');
            }
        }

        function openApply(id, title) {
            document.getElementById('applyJobId').value = id;
            document.getElementById('applyTitle').textContent = `Apply for: ${title}`;
            openModal('modalApply');
        }

        async function submitApply(e) {
            e.preventDefault();
            const f = e.target;
            const jobId = f.job_id.value;
            const formData = new FormData(f);

            const res = await api(`/api/jobs/${jobId}/apply`, {
                method: 'POST',
                body: formData
            });

            if (res.ok) {
                showToast('Application submitted! Score calculated.', 'success');
                closeModal('modalApply');
                f.reset();
                await reloadAll();
                switchTab('pipeline');
            } else {
                const d = await res.json();
                showToast(d.message || 'Application failed', 'error');
            }
        }

        function openMove(id, name, status) {
            document.getElementById('moveAppId').value = id;
            document.getElementById('moveInfo').textContent = `Candidate: ${name} (Currently: ${status})`;
            document.getElementById('moveSelectStatus').value = status;
            openModal('modalMove');
        }

        async function submitMove(e) {
            e.preventDefault();
            const f = e.target;
            const id = f.application_id.value;

            const res = await api(`/api/applications/${id}/status`, {
                method: 'PATCH',
                body: JSON.stringify({
                    status: f.status.value,
                    comment: f.comment.value
                })
            });

            if (res.ok) {
                showToast(`Status updated to ${f.status.value}!`, 'success');
                closeModal('modalMove');
                await reloadAll();
            } else {
                const d = await res.json();
                showToast(d.message || 'Status update failed', 'error');
            }
        }

        async function submitInterview(e) {
            e.preventDefault();
            const f = e.target;
            const id = f.application_id.value;

            const res = await api(`/api/applications/${id}/interviews`, {
                method: 'POST',
                body: JSON.stringify({
                    interviewer_id: 2,
                    scheduled_at: f.scheduled_at.value.replace('T', ' ') + ':00',
                    meeting_link: f.meeting_link.value
                })
            });

            const d = await res.json();
            if (res.ok) {
                showToast('Interview scheduled!', 'success');
                closeModal('modalInterview');
                f.reset();
                await reloadAll();
                switchTab('interviews');
            } else {
                const msg = d.errors?.scheduled_at?.[0] || d.message || 'Conflict detected.';
                showToast(msg, 'error');
            }
        }

        async function completeInterview(id) {
            const feedback = prompt('Enter interview notes/feedback:');
            if (!feedback) return;
            const res = await api(`/api/interviews/${id}/complete`, {
                method: 'PATCH',
                body: JSON.stringify({ feedback })
            });
            if (res.ok) {
                showToast('Interview marked complete', 'success');
                await loadInterviews();
            }
        }

        async function cancelInterview(id) {
            if (!confirm('Cancel this interview?')) return;
            const res = await api(`/api/interviews/${id}/cancel`, {
                method: 'PATCH',
                body: JSON.stringify({ reason: 'Cancelled' })
            });
            if (res.ok) {
                showToast('Interview cancelled', 'success');
                await loadInterviews();
            }
        }

        async function submitTask(e) {
            e.preventDefault();
            const f = e.target;
            const id = f.application_id.value;

            const res = await api(`/api/applications/${id}/technical-tasks`, {
                method: 'POST',
                body: JSON.stringify({
                    title: f.title.value,
                    description: f.description.value,
                    deadline: f.deadline.value.replace('T', ' ') + ':00'
                })
            });

            if (res.ok) {
                showToast('Technical task assigned!', 'success');
                closeModal('modalTask');
                f.reset();
                await reloadAll();
                switchTab('tasks');
            } else {
                const d = await res.json();
                showToast(d.message || 'Error assigning task', 'error');
            }
        }

        async function startTask(id) {
            await api(`/api/technical-tasks/${id}/start`, { method: 'PATCH' });
            showToast('Task marked In Progress', 'success');
            await loadTasks();
        }

        function openSubmitTask(id) {
            document.getElementById('submitTaskId').value = id;
            openModal('modalSubmitTask');
        }

        async function submitTaskSolution(e) {
            e.preventDefault();
            const f = e.target;
            const id = f.task_id.value;

            const res = await api(`/api/technical-tasks/${id}/submit`, {
                method: 'POST',
                body: JSON.stringify({
                    repository_url: f.repository_url.value,
                    notes: f.notes.value
                })
            });

            if (res.ok) {
                showToast('Task submitted! Recruiter notified.', 'success');
                closeModal('modalSubmitTask');
                f.reset();
                await reloadAll();
            } else {
                const d = await res.json();
                showToast(d.message || 'Submission error', 'error');
            }
        }

        function openReviewTask(id) {
            document.getElementById('reviewTaskId').value = id;
            openModal('modalReviewTask');
        }

        async function submitReview(e) {
            e.preventDefault();
            const f = e.target;
            const id = f.task_id.value;

            const res = await api(`/api/technical-tasks/${id}/review`, {
                method: 'POST',
                body: JSON.stringify({
                    score: parseInt(f.score.value, 10),
                    feedback: f.feedback.value
                })
            });

            if (res.ok) {
                showToast('Task graded & reviewed!', 'success');
                closeModal('modalReviewTask');
                await reloadAll();
            } else {
                const d = await res.json();
                showToast(d.message || 'Review error', 'error');
            }
        }

        async function runDeadlineCheck() {
            showToast('Checking deadlines & overdue tasks...', 'success');
            await api('/api/dashboard/analytics');
            await reloadAll();
            showToast('Deadline check finished!', 'success');
        }

        function showToast(msg, type = 'success') {
            const box = document.getElementById('toastBox');
            const el = document.createElement('div');
            el.className = `toast-msg ${type}`;
            el.textContent = msg;
            box.appendChild(el);
            setTimeout(() => el.remove(), 3500);
        }

        function escapeHtml(s) {
            if (!s) return '';
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }
    </script>
</body>
</html>
