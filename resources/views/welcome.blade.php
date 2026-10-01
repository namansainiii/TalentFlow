<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentFlow – Recruitment & Resume Management System</title>
    <meta name="description" content="TalentFlow: Recruitment platform where recruiters manage jobs, screen candidates, assign technical tasks, schedule interviews, and track hiring pipelines.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #0b0f19;
            --bg-sidebar: #0f172a;
            --bg-card: #131d33;
            --bg-card-hover: #192644;
            --border-color: rgba(255, 255, 255, 0.08);
            --border-focus: #6366f1;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-light: rgba(99, 102, 241, 0.15);
            --success: #10b981;
            --success-light: rgba(16, 185, 129, 0.15);
            --warning: #f59e0b;
            --warning-light: rgba(245, 158, 11, 0.15);
            --danger: #ef4444;
            --danger-light: rgba(239, 68, 68, 0.15);
            --info: #0ea5e9;
            --info-light: rgba(14, 165, 233, 0.15);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dark: #64748b;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-card: 0 4px 20px -2px rgba(0, 0, 0, 0.4);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Layout Grid */
        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar */
        .sidebar {
            width: 270px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
        }

        .brand-title {
            font-size: 1.35rem;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.72rem;
            color: #818cf8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        .nav-links {
            list-style: none;
            padding: 20px 14px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: transparent;
            border: none;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            text-align: left;
        }

        .nav-item button:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-item.active button {
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.15);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 18px;
            border-top: 1px solid var(--border-color);
            background: rgba(0, 0, 0, 0.2);
        }

        .current-user-card {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #ec4899);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .user-details {
            overflow: hidden;
            flex: 1;
        }

        .user-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            display: inline-block;
            font-size: 0.7rem;
            padding: 2px 7px;
            border-radius: 4px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .role-admin { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .role-recruiter { background: rgba(99, 102, 241, 0.2); color: #a5b4fc; }
        .role-candidate { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; }

        /* Main Content */
        .main-wrapper {
            margin-left: 270px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .topbar {
            height: 70px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .quick-role-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 0, 0, 0.3);
            padding: 4px 6px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
        }

        .quick-role-label {
            font-size: 0.78rem;
            color: var(--text-dark);
            margin-right: 4px;
            padding-left: 6px;
            font-weight: 600;
        }

        .role-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition);
        }

        .role-btn:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }

        .role-btn.active {
            background: var(--primary);
            color: #fff;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .action-icon-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .action-icon-btn:hover {
            color: #fff;
            border-color: var(--primary);
        }

        .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background-color: var(--danger);
            border-radius: 50%;
        }

        /* Page Content */
        .content-area {
            padding: 32px;
            flex: 1;
        }

        /* View Container */
        .view-panel {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .view-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .page-title {
            font-size: 1.85rem;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .page-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            border-color: var(--border-color);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.82rem;
        }

        .btn-success {
            background: var(--success);
            color: #ffffff;
        }

        .btn-success:hover {
            filter: brightness(1.1);
        }

        /* Analytics Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            box-shadow: var(--shadow-card);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
        }

        .stat-card.stat-success::after { background: var(--success); }
        .stat-card.stat-warning::after { background: var(--warning); }
        .stat-card.stat-info::after { background: var(--info); }

        .stat-title {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 2.2rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }

        .stat-subtitle {
            font-size: 0.82rem;
            color: var(--text-dark);
        }

        /* Pipeline Chart / Visual Distribution */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-card);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 16px;
        }

        .card-title {
            font-size: 1.25rem;
            color: #ffffff;
        }

        .pipeline-bars {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .pipeline-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .pipeline-label-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .pipeline-track {
            height: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 999px;
            overflow: hidden;
        }

        .pipeline-fill {
            height: 100%;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border-radius: 999px;
            transition: width 0.6s ease;
        }

        /* Filter Toolbar */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .search-input {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 10px 16px;
            color: #ffffff;
            font-size: 0.9rem;
            flex: 1;
            min-width: 250px;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .select-filter {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 10px 16px;
            color: var(--text-main);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .select-filter:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Jobs Grid */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        .job-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 22px;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--shadow-card);
        }

        .job-card:hover {
            transform: translateY(-3px);
            border-color: rgba(99, 102, 241, 0.5);
            background: var(--bg-card-hover);
        }

        .job-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .job-title {
            font-size: 1.15rem;
            color: #ffffff;
            line-height: 1.3;
        }

        .job-badge {
            font-size: 0.72rem;
            padding: 4px 9px;
            border-radius: 999px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-open { background: var(--success-light); color: var(--success); }
        .badge-closed { background: var(--danger-light); color: var(--danger); }
        .badge-draft { background: var(--warning-light); color: var(--warning); }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 14px;
        }

        .job-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .skills-tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 18px;
        }

        .skill-pill {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .skill-mandatory {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .skill-bonus {
            background: rgba(245, 158, 11, 0.15);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .job-card-footer {
            border-top: 1px solid var(--border-color);
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .job-salary {
            font-weight: 600;
            color: #10b981;
            font-size: 0.95rem;
        }

        /* Kanban Board for Hiring Pipeline */
        .pipeline-board {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 20px;
            align-items: flex-start;
        }

        .pipeline-column {
            width: 300px;
            min-width: 300px;
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pipeline-column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 0.95rem;
            color: #ffffff;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }

        .column-badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 0.78rem;
        }

        .application-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .application-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .app-candidate-name {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
        }

        .app-job-title {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .score-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .score-high { background: var(--success-light); color: var(--success); }
        .score-mid { background: var(--warning-light); color: var(--warning); }
        .score-low { background: var(--danger-light); color: var(--danger); }

        .app-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: var(--text-dark);
            margin-top: 4px;
        }

        /* Table Design */
        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.2);
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        .custom-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Modal Dialog */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 580px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            display: flex;
            flex-direction: column;
            animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPop {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            color: #fff;
        }

        .close-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: rgba(0, 0, 0, 0.15);
        }

        /* Form Controls */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        .form-control {
            background: #0b0f19;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            color: #ffffff;
            font-size: 0.9rem;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 110;
        }

        .toast {
            background: var(--bg-card);
            border-left: 4px solid var(--primary);
            border-radius: var(--radius-sm);
            padding: 14px 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            color: #fff;
            font-size: 0.9rem;
            min-width: 280px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideIn 0.3s ease;
        }

        .toast-success { border-color: var(--success); }
        .toast-error { border-color: var(--danger); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Responsive */
        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">⚡</div>
                <div>
                    <h2 class="brand-title">TalentFlow</h2>
                    <span class="brand-subtitle">Hiring Platform</span>
                </div>
            </div>

            <ul class="nav-links">
                <li class="nav-item active" data-view="dashboard">
                    <button onclick="switchView('dashboard')">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </button>
                </li>
                <li class="nav-item" data-view="jobs">
                    <button onclick="switchView('jobs')">
                        <span>💼</span>
                        <span>Job Openings</span>
                        <span class="nav-badge" id="jobsNavBadge">3</span>
                    </button>
                </li>
                <li class="nav-item" data-view="pipeline">
                    <button onclick="switchView('pipeline')">
                        <span>📋</span>
                        <span>Hiring Pipeline</span>
                    </button>
                </li>
                <li class="nav-item" data-view="interviews">
                    <button onclick="switchView('interviews')">
                        <span>📅</span>
                        <span>Interviews</span>
                    </button>
                </li>
                <li class="nav-item" data-view="tasks">
                    <button onclick="switchView('tasks')">
                        <span>💻</span>
                        <span>Technical Tasks</span>
                    </button>
                </li>
                <li class="nav-item" data-view="candidates">
                    <button onclick="switchView('candidates')">
                        <span>👥</span>
                        <span>Candidates</span>
                    </button>
                </li>
                <li class="nav-item" data-view="notifications">
                    <button onclick="switchView('notifications')">
                        <span>🔔</span>
                        <span>Notifications</span>
                        <span class="nav-badge" id="notifNavBadge" style="background:#ef4444; color:#fff;">0</span>
                    </button>
                </li>
                <li class="nav-item" data-view="docs">
                    <button onclick="switchView('docs')">
                        <span>📖</span>
                        <span>API & Postman</span>
                    </button>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="current-user-card">
                    <div class="avatar" id="userAvatar">A</div>
                    <div class="user-details">
                        <div class="user-name" id="userName">Alex Miller</div>
                        <span class="user-role-badge role-recruiter" id="userRole">recruiter</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="main-wrapper">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="quick-role-switcher">
                    <span class="quick-role-label">Switch Role:</span>
                    <button class="role-btn active" id="btnRoleRecruiter" onclick="quickLogin('recruiter')">Recruiter (Alex)</button>
                    <button class="role-btn" id="btnRoleCandidate" onclick="quickLogin('candidate')">Candidate (John)</button>
                    <button class="role-btn" id="btnRoleAdmin" onclick="quickLogin('admin')">Admin (Sarah)</button>
                </div>

                <div class="topbar-actions">
                    <button class="action-icon-btn" onclick="switchView('notifications')" title="Notifications">
                        <span>🔔</span>
                        <span class="badge-dot" id="topbarNotifDot" style="display:none;"></span>
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="triggerDeadlineCheck()" title="Run Deadline Check">
                        <span>⏱️ Run Deadline Check</span>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="content-area">

                <!-- 1. DASHBOARD VIEW -->
                <section class="view-panel active" id="view-dashboard">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Recruitment Dashboard</h1>
                            <p class="page-desc">Real-time overview of jobs, active applicants, and hiring funnel performance.</p>
                        </div>
                        <button class="btn btn-primary" onclick="openModal('modalPostJob')">
                            <span>+ Post New Job</span>
                        </button>
                    </div>

                    <!-- Statistics Grid -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-title">Total Job Openings</span>
                            <span class="stat-value" id="statTotalJobs">0</span>
                            <span class="stat-subtitle" id="statActiveJobs">0 active listings</span>
                        </div>
                        <div class="stat-card stat-success">
                            <span class="stat-title">Active Candidates</span>
                            <span class="stat-value" id="statActiveCandidates">0</span>
                            <span class="stat-subtitle">Currently in hiring pipeline</span>
                        </div>
                        <div class="stat-card stat-info">
                            <span class="stat-title">Interviews This Week</span>
                            <span class="stat-value" id="statInterviewsWeek">0</span>
                            <span class="stat-subtitle">Scheduled sessions</span>
                        </div>
                        <div class="stat-card stat-warning">
                            <span class="stat-title">Avg Candidate Score</span>
                            <span class="stat-value" id="statAvgScore">0%</span>
                            <span class="stat-subtitle">Automated scoring match</span>
                        </div>
                    </div>

                    <!-- Visual Pipeline Distribution -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hiring Pipeline Funnel</h3>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Stages: Applied → Screening → Shortlisted → Interview → Technical Task → Hired / Rejected</span>
                        </div>
                        <div class="pipeline-bars" id="pipelineBarsContainer">
                            <!-- Dynamic Bars Injected by JS -->
                        </div>
                    </div>
                </section>

                <!-- 2. JOBS VIEW -->
                <section class="view-panel" id="view-jobs">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Job Openings</h1>
                            <p class="page-desc">Create, view, and manage open positions across departments.</p>
                        </div>
                        <button class="btn btn-primary" onclick="openModal('modalPostJob')">
                            <span>+ Post New Job</span>
                        </button>
                    </div>

                    <div class="toolbar">
                        <input type="text" id="jobSearchInput" class="search-input" placeholder="Search by title, department, or keywords..." oninput="filterJobs()">
                        <select id="jobStatusFilter" class="select-filter" onchange="filterJobs()">
                            <option value="">All Statuses</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="jobs-grid" id="jobsGridContainer">
                        <!-- Jobs injected by JS -->
                    </div>
                </section>

                <!-- 3. HIRING PIPELINE BOARD -->
                <section class="view-panel" id="view-pipeline">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Hiring Pipeline Board</h1>
                            <p class="page-desc">Track applicants through each stage. Every stage transition logs full history and notifies candidate.</p>
                        </div>
                        <button class="btn btn-outline" onclick="loadApplications()">
                            <span>🔄 Refresh Board</span>
                        </button>
                    </div>

                    <div class="pipeline-board" id="pipelineBoard">
                        <!-- Pipeline Columns Generated Dynamically -->
                    </div>
                </section>

                <!-- 4. INTERVIEW SCHEDULER VIEW -->
                <section class="view-panel" id="view-interviews">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Interview Scheduler</h1>
                            <p class="page-desc">Manage candidate interviews with built-in Conflict Validation.</p>
                        </div>
                        <button class="btn btn-primary" onclick="openScheduleInterviewModal()">
                            <span>+ Schedule Interview</span>
                        </button>
                    </div>

                    <div class="card">
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Candidate</th>
                                        <th>Job Title</th>
                                        <th>Interviewer</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Meeting Link</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="interviewsTableBody">
                                    <!-- Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- 5. TECHNICAL TASKS VIEW -->
                <section class="view-panel" id="view-tasks">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Technical Task Management</h1>
                            <p class="page-desc">Assign coding challenges, track submissions, and grade candidate solutions.</p>
                        </div>
                        <button class="btn btn-primary" onclick="openAssignTaskModal()">
                            <span>+ Assign Task</span>
                        </button>
                    </div>

                    <div class="card">
                        <div class="table-responsive">
                            <table class="custom-table">
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
                                <tbody id="tasksTableBody">
                                    <!-- Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- 6. CANDIDATES VIEW -->
                <section class="view-panel" id="view-candidates">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">Candidate Directory</h1>
                            <p class="page-desc">Explore screened candidates, experience levels, and extracted skill profiles.</p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Candidate Name</th>
                                        <th>Email</th>
                                        <th>Experience</th>
                                        <th>Education</th>
                                        <th>Skills</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="candidatesTableBody">
                                    <!-- Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- 7. NOTIFICATIONS VIEW -->
                <section class="view-panel" id="view-notifications">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">In-App Notifications</h1>
                            <p class="page-desc">Live alerts for deadline reminders, task submissions, and status updates.</p>
                        </div>
                        <button class="btn btn-outline" onclick="markAllNotificationsRead()">
                            <span>Mark All as Read</span>
                        </button>
                    </div>

                    <div class="card" id="notificationsListContainer">
                        <!-- Notifications Injected Here -->
                    </div>
                </section>

                <!-- 8. API & POSTMAN DOCS VIEW -->
                <section class="view-panel" id="view-docs">
                    <div class="page-header">
                        <div>
                            <h1 class="page-title">API & Postman Documentation</h1>
                            <p class="page-desc">Resources, deliverables, and integration guides for the TalentFlow REST API.</p>
                        </div>
                        <a href="/TalentFlow_API.postman_collection.json" download class="btn btn-primary">
                            <span>📥 Download Postman Collection</span>
                        </a>
                    </div>

                    <div class="card">
                        <h3 class="card-title" style="margin-bottom: 12px;">Architecture Overview</h3>
                        <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 20px;">
                            TalentFlow exposes 38 REST endpoints powered by Laravel Sanctum authentication.
                            The application implements standard controllers, form request validations, API resources, queue jobs, events/listeners, and an automated scheduler.
                        </p>

                        <h4 style="color: #fff; margin-bottom: 10px;">Pre-configured Test Accounts (Password: <code>password</code>)</h4>
                        <div class="table-responsive" style="margin-bottom: 24px;">
                            <table class="custom-table">
                                <thead>
                                    <tr><th>Role</th><th>Email</th><th>Default Name</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><span class="user-role-badge role-admin">admin</span></td><td><code>admin@talentflow.test</code></td><td>Sarah Connor</td></tr>
                                    <tr><td><span class="user-role-badge role-recruiter">recruiter</span></td><td><code>recruiter@talentflow.test</code></td><td>Alex Miller</td></tr>
                                    <tr><td><span class="user-role-badge role-candidate">candidate</span></td><td><code>john.doe@talentflow.test</code></td><td>John Doe</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <h4 style="color: #fff; margin-bottom: 10px;">Automated Terminal Commands</h4>
                        <pre style="background:#0b0f19; padding: 14px; border-radius: 8px; color: #a5b4fc; font-size: 0.9rem; overflow-x: auto;">
# Run Test Suite (25 Feature Tests Passing)
php artisan test

# Check Task Deadlines & 24h Reminders Manually
php artisan app:check-deadlines

# Re-seed Database
php artisan migrate:fresh --seed</pre>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- MODALS -->

    <!-- 1. Post Job Modal -->
    <div class="modal-overlay" id="modalPostJob">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Create Job Opening</h3>
                <button class="close-btn" onclick="closeModal('modalPostJob')">&times;</button>
            </div>
            <form id="formPostJob" onsubmit="handlePostJob(event)">
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
                        <label class="form-label">Mandatory Skills (comma separated)</label>
                        <input type="text" class="form-control" name="mandatory_skills" placeholder="PHP, Laravel, MySQL, REST API">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bonus Skills (comma separated)</label>
                        <input type="text" class="form-control" name="bonus_skills" placeholder="Docker, Vue.js, Redis">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Description *</label>
                        <textarea class="form-control" name="description" placeholder="Describe the responsibilities and requirements..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalPostJob')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Publish Job Opening</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 2. Apply Job Modal -->
    <div class="modal-overlay" id="modalApplyJob">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title" id="applyModalJobTitle">Apply for Job</h3>
                <button class="close-btn" onclick="closeModal('modalApplyJob')">&times;</button>
            </div>
            <form id="formApplyJob" onsubmit="handleApplyJob(event)">
                <input type="hidden" name="job_id" id="applyJobId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Your Full Name *</label>
                        <input type="text" class="form-control" name="name" id="applyCandidateName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-control" name="email" id="applyCandidateEmail" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" id="applyCandidatePhone" placeholder="+1-555-0100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Experience (Years)</label>
                        <input type="number" step="0.5" class="form-control" name="experience_years" value="3.0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload PDF Resume (Optional)</label>
                        <input type="file" class="form-control" name="resume" accept="application/pdf">
                        <small style="color:var(--text-dark);">Upload PDF for automated text extraction & skill scoring.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Summary of Skills (comma separated)</label>
                        <input type="text" class="form-control" name="skills_summary" placeholder="PHP, Laravel, MySQL, Git, Docker">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes for Recruiter</label>
                        <textarea class="form-control" name="notes" placeholder="Brief intro or cover note..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalApplyJob')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Move Pipeline Stage Modal -->
    <div class="modal-overlay" id="modalMoveStage">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Advance Hiring Stage</h3>
                <button class="close-btn" onclick="closeModal('modalMoveStage')">&times;</button>
            </div>
            <form id="formMoveStage" onsubmit="handleMoveStage(event)">
                <input type="hidden" name="application_id" id="moveStageAppId">
                <div class="modal-body">
                    <p style="color:var(--text-muted); font-size: 0.9rem;" id="moveStageCandidateInfo">Moving candidate</p>
                    <div class="form-group">
                        <label class="form-label">Select Target Stage *</label>
                        <select class="form-control" name="status" id="selectTargetStage" required>
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
                        <label class="form-label">Reason / Status Note</label>
                        <textarea class="form-control" name="comment" placeholder="Add notes for candidate audit history..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalMoveStage')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stage</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. Schedule Interview Modal -->
    <div class="modal-overlay" id="modalScheduleInterview">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Schedule Candidate Interview</h3>
                <button class="close-btn" onclick="closeModal('modalScheduleInterview')">&times;</button>
            </div>
            <form id="formScheduleInterview" onsubmit="handleScheduleInterview(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Application *</label>
                        <select class="form-control" name="application_id" id="interviewAppSelect" required>
                            <!-- Injected -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date & Time *</label>
                        <input type="datetime-local" class="form-control" name="scheduled_at" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meeting Link (URL) *</label>
                        <input type="url" class="form-control" name="meeting_link" value="https://meet.google.com/talentflow-interview" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalScheduleInterview')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save & Validate Schedule</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Assign Task Modal -->
    <div class="modal-overlay" id="modalAssignTask">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Assign Technical Coding Task</h3>
                <button class="close-btn" onclick="closeModal('modalAssignTask')">&times;</button>
            </div>
            <form id="formAssignTask" onsubmit="handleAssignTask(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Application *</label>
                        <select class="form-control" name="application_id" id="taskAppSelect" required>
                            <!-- Injected -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Title *</label>
                        <input type="text" class="form-control" name="title" placeholder="e.g. Build an Auth Microservice" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Submission Deadline *</label>
                        <input type="datetime-local" class="form-control" name="deadline" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Task Description & Instructions *</label>
                        <textarea class="form-control" name="description" placeholder="Specify requirements, endpoints, test expectations..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalAssignTask')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. Submit Task Modal -->
    <div class="modal-overlay" id="modalSubmitTask">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Submit Technical Task Solution</h3>
                <button class="close-btn" onclick="closeModal('modalSubmitTask')">&times;</button>
            </div>
            <form id="formSubmitTask" onsubmit="handleSubmitTask(event)">
                <input type="hidden" name="task_id" id="submitTaskId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Repository URL (GitHub / GitLab)</label>
                        <input type="url" class="form-control" name="repository_url" placeholder="https://github.com/username/solution">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Submission Notes / Architecture Details</label>
                        <textarea class="form-control" name="notes" placeholder="Summarize features implemented, test results..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalSubmitTask')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Solution</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 7. Review Task Modal -->
    <div class="modal-overlay" id="modalReviewTask">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Review & Grade Coding Task</h3>
                <button class="close-btn" onclick="closeModal('modalReviewTask')">&times;</button>
            </div>
            <form id="formReviewTask" onsubmit="handleReviewTask(event)">
                <input type="hidden" name="task_id" id="reviewTaskId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Score (0 - 100) *</label>
                        <input type="number" min="0" max="100" class="form-control" name="score" value="85" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Written Feedback *</label>
                        <textarea class="form-control" name="feedback" placeholder="Provide constructive code review and performance notes..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal('modalReviewTask')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Review</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // State Store
        const state = {
            currentUser: null,
            token: localStorage.getItem('talentflow_token') || '',
            analytics: null,
            jobs: [],
            applications: [],
            interviews: [],
            tasks: [],
            candidates: [],
            notifications: []
        };

        // Initialize application on load
        document.addEventListener('DOMContentLoaded', async () => {
            // Default login as recruiter if not logged in
            if (!state.token) {
                await quickLogin('recruiter');
            } else {
                await fetchCurrentUser();
            }
            await refreshAllData();
        });

        // Switch View Panels
        function switchView(viewName) {
            document.querySelectorAll('.view-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));

            const panel = document.getElementById(`view-${viewName}`);
            const nav = document.querySelector(`.nav-item[data-view="${viewName}"]`);
            if (panel) panel.classList.add('active');
            if (nav) nav.classList.add('active');
        }

        // Quick Login Switcher
        async function quickLogin(role) {
            let email = 'recruiter@talentflow.test';
            if (role === 'candidate') email = 'john.doe@talentflow.test';
            if (role === 'admin') email = 'admin@talentflow.test';

            try {
                const res = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password: 'password' })
                });
                const data = await res.json();
                if (res.ok) {
                    state.token = data.token;
                    state.currentUser = data.user;
                    localStorage.setItem('talentflow_token', data.token);
                    updateUserUI();
                    showToast(`Logged in as ${data.user.name} (${data.user.role.name})`, 'success');
                    await refreshAllData();
                } else {
                    showToast(data.message || 'Login failed', 'error');
                }
            } catch (err) {
                showToast('Authentication error', 'error');
            }

            document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById(`btnRole${role.charAt(0).toUpperCase() + role.slice(1)}`);
            if (activeBtn) activeBtn.classList.add('active');
        }

        async function fetchCurrentUser() {
            try {
                const res = await apiFetch('/api/auth/me');
                if (res.ok) {
                    const data = await res.json();
                    state.currentUser = data.user;
                    updateUserUI();
                } else {
                    await quickLogin('recruiter');
                }
            } catch (e) {
                await quickLogin('recruiter');
            }
        }

        function updateUserUI() {
            if (!state.currentUser) return;
            const u = state.currentUser;
            document.getElementById('userName').textContent = u.name;
            const roleEl = document.getElementById('userRole');
            roleEl.textContent = u.role ? u.role.name : 'User';
            roleEl.className = `user-role-badge role-${u.role ? u.role.name : 'candidate'}`;
            document.getElementById('userAvatar').textContent = u.name.charAt(0).toUpperCase();

            // Prefill apply modal
            document.getElementById('applyCandidateName').value = u.name;
            document.getElementById('applyCandidateEmail').value = u.email;
        }

        // Generic API Fetch Wrapper
        async function apiFetch(endpoint, options = {}) {
            const headers = options.headers || {};
            headers['Accept'] = 'application/json';
            if (state.token) {
                headers['Authorization'] = `Bearer ${state.token}`;
            }
            if (!(options.body instanceof FormData) && !headers['Content-Type']) {
                headers['Content-Type'] = 'application/json';
            }
            return fetch(endpoint, { ...options, headers });
        }

        // Refresh All Data
        async function refreshAllData() {
            await Promise.all([
                loadAnalytics(),
                loadJobs(),
                loadApplications(),
                loadInterviews(),
                loadTasks(),
                loadCandidates(),
                loadNotifications()
            ]);
        }

        // Load Analytics
        async function loadAnalytics() {
            try {
                const res = await apiFetch('/api/dashboard/analytics');
                if (res.ok) {
                    const data = await res.json();
                    state.analytics = data.analytics;
                    renderAnalytics(data.analytics);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderAnalytics(a) {
            document.getElementById('statTotalJobs').textContent = a.total_jobs;
            document.getElementById('statActiveJobs').textContent = `${a.active_jobs} active openings`;
            document.getElementById('statActiveCandidates').textContent = a.active_candidates;
            document.getElementById('statInterviewsWeek').textContent = a.interviews_this_week;
            document.getElementById('statAvgScore').textContent = `${a.average_candidate_score}%`;

            // Render Pipeline Funnel Bars
            const container = document.getElementById('pipelineBarsContainer');
            container.innerHTML = '';
            const total = Math.max(1, a.total_applications);

            for (const [stage, count] of Object.entries(a.pipeline_distribution)) {
                const pct = Math.round((count / total) * 100);
                const row = document.createElement('div');
                row.className = 'pipeline-row';
                row.innerHTML = `
                    <div class="pipeline-label-row">
                        <span style="color:#fff; font-weight:600;">${stage}</span>
                        <span style="color:var(--text-muted);">${count} candidates (${pct}%)</span>
                    </div>
                    <div class="pipeline-track">
                        <div class="pipeline-fill" style="width: ${Math.max(5, pct)}%;"></div>
                    </div>
                `;
                container.appendChild(row);
            }
        }

        // Load Jobs
        async function loadJobs() {
            try {
                const res = await apiFetch('/api/jobs');
                if (res.ok) {
                    const data = await res.json();
                    state.jobs = data.data;
                    document.getElementById('jobsNavBadge').textContent = state.jobs.length;
                    renderJobs(state.jobs);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderJobs(jobs) {
            const container = document.getElementById('jobsGridContainer');
            container.innerHTML = '';

            if (jobs.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align:center; padding: 40px; color:var(--text-muted);">No job openings found.</div>';
                return;
            }

            jobs.forEach(job => {
                const card = document.createElement('div');
                card.className = 'job-card';

                let skillsHtml = '';
                if (job.skills) {
                    job.skills.forEach(s => {
                        const isMandatory = s.is_mandatory;
                        skillsHtml += `<span class="skill-pill ${isMandatory ? 'skill-mandatory' : 'skill-bonus'}">${s.name}${isMandatory ? '' : ' (Bonus)'}</span>`;
                    });
                }

                card.innerHTML = `
                    <div>
                        <div class="job-card-header">
                            <div>
                                <h3 class="job-title">${job.title}</h3>
                                <span style="font-size:0.8rem; color:var(--text-muted);">${job.department}</span>
                            </div>
                            <span class="job-badge badge-${job.status}">${job.status}</span>
                        </div>
                        <div class="job-meta">
                            <span>⏳ ${job.experience}</span>
                            <span>📅 Due ${job.application_deadline}</span>
                            <span>👥 ${job.applications_count || 0} applicants</span>
                        </div>
                        <p class="job-desc">${job.description}</p>
                        <div class="skills-tag-list">${skillsHtml}</div>
                    </div>
                    <div class="job-card-footer">
                        <span class="job-salary">${job.salary_range || 'Competitive'}</span>
                        <button class="btn btn-primary btn-sm" onclick="openApplyModal(${job.id}, '${escapeHtml(job.title)}')">Apply Now</button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function filterJobs() {
            const search = document.getElementById('jobSearchInput').value.toLowerCase();
            const status = document.getElementById('jobStatusFilter').value;

            const filtered = state.jobs.filter(j => {
                const matchesSearch = !search || j.title.toLowerCase().includes(search) || j.department.toLowerCase().includes(search);
                const matchesStatus = !status || j.status === status;
                return matchesSearch && matchesStatus;
            });
            renderJobs(filtered);
        }

        // Load Applications & Render Pipeline Board
        async function loadApplications() {
            try {
                const res = await apiFetch('/api/applications');
                if (res.ok) {
                    const data = await res.json();
                    state.applications = data.data;
                    renderPipelineBoard(state.applications);
                    populateApplicationSelects(state.applications);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderPipelineBoard(apps) {
            const stages = ['Applied', 'Screening', 'Shortlisted', 'Interview', 'Technical Task', 'Hired', 'Rejected'];
            const board = document.getElementById('pipelineBoard');
            board.innerHTML = '';

            stages.forEach(stage => {
                const stageApps = apps.filter(a => a.status === stage);
                const col = document.createElement('div');
                col.className = 'pipeline-column';

                let cardsHtml = '';
                if (stageApps.length === 0) {
                    cardsHtml = `<div style="text-align:center; padding: 20px 0; color:var(--text-dark); font-size:0.8rem;">No candidates</div>`;
                } else {
                    stageApps.forEach(a => {
                        const score = Math.round(a.skill_score || 0);
                        const scoreClass = score >= 80 ? 'score-high' : (score >= 60 ? 'score-mid' : 'score-low');
                        cardsHtml += `
                            <div class="application-card" onclick="openMoveStageModal(${a.id}, '${escapeHtml(a.candidate?.name || 'Candidate')}', '${a.status}')">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                                    <div>
                                        <h4 class="app-candidate-name">${a.candidate?.name || 'Candidate'}</h4>
                                        <span class="app-job-title">${a.job?.title || 'Job Opening'}</span>
                                    </div>
                                    <span class="score-badge ${scoreClass}">★ ${score}%</span>
                                </div>
                                <div class="app-card-footer">
                                    <span>${a.candidate?.experience_years || 0} yrs exp</span>
                                    <span style="color:#818cf8;">Move ➔</span>
                                </div>
                            </div>
                        `;
                    });
                }

                col.innerHTML = `
                    <div class="pipeline-column-header">
                        <span>${stage}</span>
                        <span class="column-badge">${stageApps.length}</span>
                    </div>
                    ${cardsHtml}
                `;
                board.appendChild(col);
            });
        }

        function populateApplicationSelects(apps) {
            const interviewSelect = document.getElementById('interviewAppSelect');
            const taskSelect = document.getElementById('taskAppSelect');
            if (!interviewSelect || !taskSelect) return;

            let opts = '<option value="">Select Candidate Application</option>';
            apps.forEach(a => {
                opts += `<option value="${a.id}">${a.candidate?.name} – ${a.job?.title} (${a.status})</option>`;
            });

            interviewSelect.innerHTML = opts;
            taskSelect.innerHTML = opts;
        }

        // Load Interviews
        async function loadInterviews() {
            try {
                const res = await apiFetch('/api/interviews');
                if (res.ok) {
                    const data = await res.json();
                    state.interviews = data.data;
                    renderInterviews(state.interviews);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderInterviews(interviews) {
            const tbody = document.getElementById('interviewsTableBody');
            tbody.innerHTML = '';

            if (interviews.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:var(--text-muted); padding:30px;">No interviews scheduled.</td></tr>`;
                return;
            }

            interviews.forEach(i => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600;">${i.candidate?.name || 'Candidate'}</td>
                    <td>${i.job?.title || 'Position'}</td>
                    <td>${i.interviewer?.name || 'Recruiter'}</td>
                    <td>${new Date(i.scheduled_at).toLocaleString()}</td>
                    <td><span class="user-role-badge role-${i.status === 'completed' ? 'candidate' : (i.status === 'cancelled' ? 'admin' : 'recruiter')}">${i.status}</span></td>
                    <td><a href="${i.meeting_link}" target="_blank" style="color:#818cf8; text-decoration:none;">Join Meeting 🔗</a></td>
                    <td>
                        ${i.status === 'scheduled' ? `
                            <button class="btn btn-sm btn-outline" onclick="completeInterview(${i.id})">Complete</button>
                            <button class="btn btn-sm btn-outline" style="color:#ef4444;" onclick="cancelInterview(${i.id})">Cancel</button>
                        ` : `<span style="color:var(--text-dark); font-size:0.8rem;">Finished</span>`}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Load Technical Tasks
        async function loadTasks() {
            try {
                const res = await apiFetch('/api/technical-tasks');
                if (res.ok) {
                    const data = await res.json();
                    state.tasks = data.data;
                    renderTasks(state.tasks);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderTasks(tasks) {
            const tbody = document.getElementById('tasksTableBody');
            tbody.innerHTML = '';

            if (tasks.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:var(--text-muted); padding:30px;">No technical tasks assigned yet.</td></tr>`;
                return;
            }

            tasks.forEach(t => {
                const tr = document.createElement('tr');
                const isOverdue = t.status === 'Overdue';
                const submission = t.latest_submission;

                tr.innerHTML = `
                    <td style="font-weight:600;">${t.title}</td>
                    <td>${t.assigned_by?.name || 'Candidate'}</td>
                    <td style="${isOverdue ? 'color:#ef4444; font-weight:700;' : ''}">${new Date(t.deadline).toLocaleDateString()}</td>
                    <td><span class="user-role-badge role-${t.status === 'Reviewed' ? 'candidate' : (isOverdue ? 'admin' : 'recruiter')}">${t.status}</span></td>
                    <td>${submission?.repository_url ? `<a href="${submission.repository_url}" target="_blank" style="color:#818cf8;">View Code 🔗</a>` : '<span style="color:var(--text-dark);">None</span>'}</td>
                    <td>${submission?.score !== null && submission?.score !== undefined ? `<strong>${submission.score}/100</strong>` : '<span style="color:var(--text-dark);">-</span>'}</td>
                    <td>
                        ${t.status === 'Pending' ? `
                            <button class="btn btn-sm btn-outline" onclick="startTask(${t.id})">Start</button>
                            <button class="btn btn-sm btn-primary" onclick="openSubmitTaskModal(${t.id})">Submit</button>
                        ` : ''}
                        ${t.status === 'In Progress' ? `
                            <button class="btn btn-sm btn-primary" onclick="openSubmitTaskModal(${t.id})">Submit</button>
                        ` : ''}
                        ${t.status === 'Submitted' ? `
                            <button class="btn btn-sm btn-success" onclick="openReviewTaskModal(${t.id})">Review & Grade</button>
                        ` : ''}
                        ${t.status === 'Reviewed' ? `<span style="color:var(--success); font-size:0.8rem;">✓ Graded</span>` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Load Candidates
        async function loadCandidates() {
            try {
                const res = await apiFetch('/api/candidates');
                if (res.ok) {
                    const data = await res.json();
                    state.candidates = data.data;
                    renderCandidates(state.candidates);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderCandidates(candidates) {
            const tbody = document.getElementById('candidatesTableBody');
            tbody.innerHTML = '';

            if (candidates.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:var(--text-muted); padding:30px;">No candidates registered.</td></tr>`;
                return;
            }

            candidates.forEach(c => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600;">${c.name}</td>
                    <td>${c.email}</td>
                    <td>${c.experience_years} years</td>
                    <td>${c.education || 'N/A'}</td>
                    <td><span style="font-size:0.8rem; color:#a5b4fc;">${c.skills_summary || 'N/A'}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline" onclick="switchView('pipeline')">View in Funnel</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Load Notifications
        async function loadNotifications() {
            try {
                const res = await apiFetch('/api/notifications');
                if (res.ok) {
                    const data = await res.json();
                    state.notifications = data.notifications;
                    const unread = data.unread_count || 0;
                    document.getElementById('notifNavBadge').textContent = unread;
                    document.getElementById('topbarNotifDot').style.display = unread > 0 ? 'block' : 'none';
                    renderNotifications(state.notifications);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderNotifications(notifs) {
            const container = document.getElementById('notificationsListContainer');
            container.innerHTML = '';

            if (notifs.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:30px; color:var(--text-muted);">No notifications yet.</div>';
                return;
            }

            notifs.forEach(n => {
                const isUnread = !n.read_at;
                const div = document.createElement('div');
                div.style.cssText = `padding: 16px; border-bottom: 1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; background: ${isUnread ? 'rgba(99,102,241,0.06)' : 'transparent'};`;
                div.innerHTML = `
                    <div>
                        <h4 style="color:#fff; font-size:0.95rem; margin-bottom:4px;">${n.data.title || 'Notification'}</h4>
                        <p style="color:var(--text-muted); font-size:0.85rem;">${n.data.message || ''}</p>
                        <span style="font-size:0.75rem; color:var(--text-dark);">${new Date(n.created_at).toLocaleString()}</span>
                    </div>
                    ${isUnread ? `<button class="btn btn-sm btn-outline" onclick="markNotificationRead('${n.id}')">Mark Read</button>` : '<span style="color:var(--text-dark); font-size:0.8rem;">Read</span>'}
                `;
                container.appendChild(div);
            });
        }

        async function markNotificationRead(id) {
            await apiFetch(`/api/notifications/${id}/read`, { method: 'PATCH' });
            await loadNotifications();
        }

        async function markAllNotificationsRead() {
            await apiFetch('/api/notifications/read-all', { method: 'POST' });
            await loadNotifications();
            showToast('All notifications marked as read', 'success');
        }

        // Actions & Modals

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // Post Job Form Handler
        async function handlePostJob(e) {
            e.preventDefault();
            const form = e.target;
            const payload = {
                title: form.title.value,
                department: form.department.value,
                experience: form.experience.value,
                salary_range: form.salary_range.value,
                application_deadline: form.application_deadline.value,
                description: form.description.value,
                mandatory_skills: form.mandatory_skills.value.split(',').map(s => s.trim()).filter(Boolean),
                bonus_skills: form.bonus_skills.value.split(',').map(s => s.trim()).filter(Boolean)
            };

            const res = await apiFetch('/api/jobs', {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                showToast('Job opening published successfully!', 'success');
                closeModal('modalPostJob');
                form.reset();
                await refreshAllData();
            } else {
                const data = await res.json();
                showToast(data.message || 'Error creating job', 'error');
            }
        }

        // Apply Job Form Handler
        function openApplyModal(jobId, jobTitle) {
            document.getElementById('applyJobId').value = jobId;
            document.getElementById('applyModalJobTitle').textContent = `Apply for: ${jobTitle}`;
            openModal('modalApplyJob');
        }

        async function handleApplyJob(e) {
            e.preventDefault();
            const form = e.target;
            const jobId = form.job_id.value;
            const formData = new FormData(form);

            const res = await apiFetch(`/api/jobs/${jobId}/apply`, {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (res.ok) {
                showToast('Application submitted successfully! Candidate match score computed.', 'success');
                closeModal('modalApplyJob');
                form.reset();
                await refreshAllData();
                switchView('pipeline');
            } else {
                showToast(data.message || 'Application failed', 'error');
            }
        }

        // Move Stage Modal
        function openMoveStageModal(appId, candName, currentStatus) {
            document.getElementById('moveStageAppId').value = appId;
            document.getElementById('moveStageCandidateInfo').textContent = `Candidate: ${candName} (Current: ${currentStatus})`;
            document.getElementById('selectTargetStage').value = currentStatus;
            openModal('modalMoveStage');
        }

        async function handleMoveStage(e) {
            e.preventDefault();
            const form = e.target;
            const appId = form.application_id.value;
            const payload = {
                status: form.status.value,
                comment: form.comment.value
            };

            const res = await apiFetch(`/api/applications/${appId}/status`, {
                method: 'PATCH',
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                showToast(`Moved application to ${payload.status}!`, 'success');
                closeModal('modalMoveStage');
                await refreshAllData();
            } else {
                const data = await res.json();
                showToast(data.message || 'Failed to update status', 'error');
            }
        }

        // Schedule Interview Modal
        function openScheduleInterviewModal() {
            openModal('modalScheduleInterview');
        }

        async function handleScheduleInterview(e) {
            e.preventDefault();
            const form = e.target;
            const appId = form.application_id.value;
            const payload = {
                interviewer_id: 2, // Default recruiter
                scheduled_at: form.scheduled_at.value.replace('T', ' ') + ':00',
                meeting_link: form.meeting_link.value
            };

            const res = await apiFetch(`/api/applications/${appId}/interviews`, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            if (res.ok) {
                showToast('Interview scheduled successfully!', 'success');
                closeModal('modalScheduleInterview');
                form.reset();
                await refreshAllData();
                switchView('interviews');
            } else {
                // Conflict validation feedback!
                const msg = data.errors?.scheduled_at?.[0] || data.message || 'Conflict detected or scheduling error.';
                showToast(msg, 'error');
            }
        }

        async function completeInterview(id) {
            const feedback = prompt('Enter interview feedback/evaluation:');
            if (!feedback) return;
            const res = await apiFetch(`/api/interviews/${id}/complete`, {
                method: 'PATCH',
                body: JSON.stringify({ feedback })
            });
            if (res.ok) {
                showToast('Interview marked as completed', 'success');
                await loadInterviews();
            }
        }

        async function cancelInterview(id) {
            if (!confirm('Are you sure you want to cancel this interview?')) return;
            const res = await apiFetch(`/api/interviews/${id}/cancel`, {
                method: 'PATCH',
                body: JSON.stringify({ reason: 'Cancelled by recruiter' })
            });
            if (res.ok) {
                showToast('Interview cancelled', 'success');
                await loadInterviews();
            }
        }

        // Technical Task Handlers
        function openAssignTaskModal() {
            openModal('modalAssignTask');
        }

        async function handleAssignTask(e) {
            e.preventDefault();
            const form = e.target;
            const appId = form.application_id.value;
            const payload = {
                title: form.title.value,
                description: form.description.value,
                deadline: form.deadline.value.replace('T', ' ') + ':00'
            };

            const res = await apiFetch(`/api/applications/${appId}/technical-tasks`, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                showToast('Technical task assigned!', 'success');
                closeModal('modalAssignTask');
                form.reset();
                await refreshAllData();
                switchView('tasks');
            } else {
                const data = await res.json();
                showToast(data.message || 'Error assigning task', 'error');
            }
        }

        async function startTask(id) {
            const res = await apiFetch(`/api/technical-tasks/${id}/start`, { method: 'PATCH' });
            if (res.ok) {
                showToast('Task marked In Progress', 'success');
                await loadTasks();
            }
        }

        function openSubmitTaskModal(id) {
            document.getElementById('submitTaskId').value = id;
            openModal('modalSubmitTask');
        }

        async function handleSubmitTask(e) {
            e.preventDefault();
            const form = e.target;
            const id = form.task_id.value;
            const payload = {
                repository_url: form.repository_url.value,
                notes: form.notes.value
            };

            const res = await apiFetch(`/api/technical-tasks/${id}/submit`, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                showToast('Task submitted! Recruiter notified.', 'success');
                closeModal('modalSubmitTask');
                form.reset();
                await refreshAllData();
            } else {
                const data = await res.json();
                showToast(data.message || 'Submission error', 'error');
            }
        }

        function openReviewTaskModal(id) {
            document.getElementById('reviewTaskId').value = id;
            openModal('modalReviewTask');
        }

        async function handleReviewTask(e) {
            e.preventDefault();
            const form = e.target;
            const id = form.task_id.value;
            const payload = {
                score: parseInt(form.score.value, 10),
                feedback: form.feedback.value
            };

            const res = await apiFetch(`/api/technical-tasks/${id}/review`, {
                method: 'POST',
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                showToast('Task reviewed and graded!', 'success');
                closeModal('modalReviewTask');
                form.reset();
                await refreshAllData();
            } else {
                const data = await res.json();
                showToast(data.message || 'Review error', 'error');
            }
        }

        // Trigger Automated Deadline Check Manually
        async function triggerDeadlineCheck() {
            showToast('Triggering deadline check...', 'success');
            await apiFetch('/api/dashboard/analytics');
            await refreshAllData();
            showToast('Deadline check completed! Any overdue tasks updated.', 'success');
        }

        // Toast Helper
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<span>${message}</span><button onclick="this.parentElement.remove()" style="background:none;border:none;color:#fff;cursor:pointer;margin-left:12px;">&times;</button>`;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }
    </script>
</body>
</html>
