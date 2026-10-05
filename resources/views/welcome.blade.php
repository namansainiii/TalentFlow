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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        // Instant check to avoid gateway flash and optimize LCP
        if (localStorage.getItem('tf_in_workspace') === 'true' && localStorage.getItem('tf_token')) {
            document.documentElement.classList.add('in-workspace');
            try {
                const hashTab = window.location.hash ? window.location.hash.replace('#', '') : '';
                let savedTab = hashTab || localStorage.getItem('tf_active_tab') || 'dashboard';
                const savedUser = JSON.parse(localStorage.getItem('tf_user') || '{}');
                const role = savedUser.role?.name;
                if (savedTab === 'recruiters' && role !== 'admin') {
                    savedTab = 'dashboard';
                }
                if (savedTab === 'candidates' && role === 'candidate') {
                    savedTab = 'dashboard';
                }
                const validTabs = ['dashboard', 'jobs', 'pipeline', 'interviews', 'tasks', 'candidates', 'recruiters'];
                if (validTabs.includes(savedTab) && savedTab !== 'dashboard') {
                    const earlyStyle = document.createElement('style');
                    earlyStyle.id = 'earlyActiveTabStyle';
                    earlyStyle.textContent = `#panel-dashboard { display: none !important; } #panel-${savedTab} { display: block !important; }`;
                    document.head.appendChild(earlyStyle);
                }
            } catch (e) {}
        }
    </script>
    <style>
        html.in-workspace #authGatewayScreen {
            display: none !important;
        }
        html.in-workspace #appWorkspace {
            display: flex !important;
        }

        :root {
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            --border-focus: #6366f1;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --success: #059669;
            --success-light: #ecfdf5;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --danger: #e11d48;
            --danger-light: #fff1f2;
            --purple: #7c3aed;
            --purple-light: #f5f3ff;
            --cyan: #0284c7;
            --cyan-light: #f0f9ff;
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px -2px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 12px 24px -4px rgba(15, 23, 42, 0.08), 0 4px 8px -2px rgba(15, 23, 42, 0.03);
            --shadow-hover: 0 12px 28px -4px rgba(79, 70, 229, 0.15);
            --font-heading: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-body: 'Inter', system-ui, -apple-system, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .brand {
            font-family: var(--font-heading);
        }

        /* Top Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .nav-container {
            max-width: 1360px;
            margin: 0 auto;
            padding: 0 24px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-dark);
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        .brand-badge {
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.7rem;
            padding: 3px 9px;
            border-radius: var(--radius-full);
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .nav-tabs {
            display: flex;
            gap: 6px;
            list-style: none;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .nav-tabs button {
            background: transparent;
            border: none;
            padding: 7px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-tabs button:hover {
            color: var(--text-dark);
            background: rgba(255, 255, 255, 0.6);
        }

        .nav-tabs button.active {
            color: var(--primary);
            background: #ffffff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            font-weight: 700;
        }

        .user-nav-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-profile-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            padding: 4px 12px 4px 6px;
            border-radius: var(--radius-full);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .user-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            background: var(--primary-gradient);
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
        }

        .role-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .role-admin { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(225, 29, 72, 0.2); }
        .role-recruiter { background: var(--primary-light); color: var(--primary); border: 1px solid rgba(79, 70, 229, 0.2); }
        .role-candidate { background: var(--success-light); color: var(--success); border: 1px solid rgba(5, 150, 105, 0.2); }

        /* Notification Bell & Dropdown */
        .notif-wrapper {
            position: relative;
            display: inline-block;
        }

        .btn-icon-notif {
            position: relative;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            color: var(--text-dark);
            padding: 0;
            outline: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-icon-notif:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        .notif-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid #ffffff;
            animation: pulseBadge 2.2s infinite;
        }

        @keyframes pulseBadge {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .notif-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 360px;
            max-width: 90vw;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            z-index: 10000;
            overflow: hidden;
            animation: fadeInOverlay 0.15s ease-out;
        }

        .notif-dropdown-header {
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        .notif-mark-all {
            background: none;
            border: none;
            font-size: 0.78rem;
            color: var(--primary);
            cursor: pointer;
            font-weight: 600;
        }
        .notif-mark-all:hover {
            text-decoration: underline;
        }

        .notif-dropdown-body {
            max-height: 350px;
            overflow-y: auto;
        }

        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background 0.15s ease;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            text-align: left;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item.unread {
            background: var(--primary-light);
            border-left: 4px solid var(--primary);
        }

        .notif-item.urgent {
            background: var(--warning-light);
            border-left: 4px solid var(--warning);
        }

        .notif-icon {
            font-size: 1.15rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .notif-content {
            flex: 1;
        }

        .notif-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .notif-message {
            font-size: 0.76rem;
            color: var(--text-muted);
            line-height: 1.35;
        }

        .notif-time {
            font-size: 0.68rem;
            color: var(--text-light);
            margin-top: 3px;
        }

        .notif-empty {
            padding: 24px 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.82rem;
        }

        /* Container */
        .main-content {
            max-width: 1360px;
            margin: 28px auto;
            padding: 0 24px;
            flex: 1;
            width: 100%;
        }

        /* Header Title */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        .section-desc {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius);
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: #ffffff;
            border-color: var(--border);
            color: var(--text-dark);
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: var(--border-hover);
            color: var(--primary);
        }

        .btn-applied {
            background: #ecfdf5 !important;
            color: #059669 !important;
            border: 1px solid #10b981 !important;
            box-shadow: 0 1px 3px rgba(16, 185, 129, 0.1);
        }

        .btn-applied:hover {
            background: #d1fae5 !important;
            border-color: #059669 !important;
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.82rem;
            border-radius: var(--radius-sm);
        }

        .btn-success {
            background: var(--success);
            color: #ffffff;
        }

        .btn-success:hover {
            background: #047857;
        }

        /* Panels */
        .tab-panel {
            display: none;
            animation: fadeInTab 0.2s ease-out;
        }

        @keyframes fadeInTab {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tab-panel.active {
            display: block;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            box-shadow: var(--shadow-md);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--border-hover);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .icon-blue { background: var(--primary-light); color: var(--primary); }
        .icon-purple { background: var(--purple-light); color: var(--purple); }
        .icon-emerald { background: var(--success-light); color: var(--success); }
        .icon-amber { background: var(--warning-light); color: var(--warning); }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 4px 0 6px 0;
            line-height: 1.1;
            font-family: var(--font-heading);
        }

        .stat-sub {
            font-size: 0.82rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Card */
        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 18px;
            letter-spacing: -0.01em;
        }

        /* Pipeline Funnel Bars */
        .funnel-container {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .funnel-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .funnel-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .funnel-track {
            height: 10px;
            background: #f1f5f9;
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .funnel-fill {
            height: 100%;
            background: var(--primary-gradient);
            border-radius: var(--radius-full);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Filter Toolbar */
        .toolbar {
            display: flex;
            gap: 14px;
            margin-bottom: 22px;
            flex-wrap: wrap;
            background: #ffffff;
            padding: 14px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .input-text {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            font-size: 0.9rem;
            color: var(--text-dark);
            flex: 1;
            min-width: 240px;
            transition: all 0.2s ease;
        }

        .input-text:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .input-select {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            font-size: 0.9rem;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .input-select:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        /* Job Cards Grid */
        .jobs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        .job-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .job-card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .job-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .job-dept {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .job-desc {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin: 12px 0 16px 0;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 18px;
        }

        .skill-tag {
            font-size: 0.75rem;
            padding: 3px 9px;
            border-radius: var(--radius-full);
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .skill-tag.mandatory {
            background: var(--primary-light);
            color: var(--primary);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .job-footer {
            border-top: 1px solid var(--border);
            padding-top: 12px;
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .job-footer-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .job-salary {
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--success);
        }

        .job-deadline-badge {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .job-actions-row {
            display: grid;
            gap: 6px;
            width: 100%;
            align-items: center;
        }

        .job-actions-row.recruiter {
            grid-template-columns: repeat(3, 1fr) 1.25fr;
        }

        .job-actions-row.candidate {
            grid-template-columns: 1fr 1.35fr;
        }

        .job-actions-row .btn {
            justify-content: center;
            text-align: center;
            padding: 7px 6px;
            font-size: 0.82rem;
            white-space: nowrap;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: var(--radius-full);
            text-transform: capitalize;
            letter-spacing: 0.02em;
        }

        .badge-open, .badge-completed, .badge-hired, .badge-reviewed {
            background: var(--success-light);
            color: var(--success);
            border: 1px solid rgba(5, 150, 105, 0.2);
        }

        .badge-closed, .badge-cancelled, .badge-rejected, .badge-overdue {
            background: var(--danger-light);
            color: var(--danger);
            border: 1px solid rgba(225, 29, 72, 0.2);
        }

        .badge-pending, .badge-applied, .badge-screening {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .badge-shortlisted, .badge-interview, .badge-in_progress {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid rgba(79, 70, 229, 0.2);
        }

        /* Indeed-Style Pipeline Split Layout */
        .pipeline-split-layout {
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }

        .pipeline-sidebar {
            width: 260px;
            min-width: 260px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px 12px;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-heading {
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 12px 12px 12px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 8px;
        }

        .sidebar-stage-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-stage-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid transparent;
            background: transparent;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: left;
        }

        .sidebar-stage-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
        }

        .sidebar-stage-btn.active {
            background: var(--primary-light);
            color: var(--primary);
            border-color: rgba(99, 102, 241, 0.2);
            font-weight: 700;
        }

        .sidebar-stage-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-stage-icon {
            font-size: 1.1rem;
        }

        .sidebar-stage-count {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            background: #f1f5f9;
            color: var(--text-muted);
        }

        .sidebar-stage-btn.active .sidebar-stage-count {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .pipeline-main-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
            min-width: 0;
        }

        /* View Toggle Switch */
        .view-mode-switch {
            display: inline-flex;
            background: #f1f5f9;
            padding: 3px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .view-btn {
            border: none;
            background: transparent;
            padding: 5px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .view-btn.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
            font-weight: 700;
        }

        /* Toolbar Search & Sort */
        .pipeline-toolbar {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 14px 18px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .pipeline-search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 14px;
            flex: 1;
            min-width: 260px;
        }

        .pipeline-search-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .sort-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* Candidate List Cards */
        .pipeline-list-container {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .candidate-card-row {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            cursor: pointer;
        }

        .candidate-card-row:hover {
            border-color: #a5b4fc;
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .candidate-info-group {
            display: flex;
            align-items: center;
            gap: 18px;
            flex: 1;
            min-width: 0;
        }

        .candidate-large-avatar {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-full);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .candidate-text-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .candidate-row-name {
            font-size: 1.08rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .candidate-row-role {
            font-size: 0.86rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .candidate-meta-chips {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .match-bar-container {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            border: 1px solid var(--border);
        }

        .match-mini-track {
            width: 60px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .match-mini-fill {
            height: 100%;
            border-radius: 999px;
        }

        .candidate-actions-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .empty-pipeline-notice {
            background: #ffffff;
            border: 2px dashed var(--border);
            border-radius: var(--radius-lg);
            padding: 48px 24px;
            text-align: center;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        /* Modern Kanban Pipeline Board */
        .pipeline-board {
            display: flex;
            gap: 18px;
            overflow-x: auto;
            padding: 4px 4px 20px 4px;
            align-items: flex-start;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .pipeline-board::-webkit-scrollbar {
            height: 6px;
        }
        .pipeline-board::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .pipeline-col {
            width: 310px;
            min-width: 310px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            box-shadow: var(--shadow-sm);
        }

        .pipeline-col-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--text-dark);
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
            position: relative;
        }

        .pipeline-stage-indicator {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .stage-dot {
            width: 9px;
            height: 9px;
            border-radius: var(--radius-full);
        }

        /* App Items / Cards in Pipeline */
        .app-item {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 14px;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .app-item:hover {
            border-color: #a5b4fc;
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .app-item-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .app-candidate-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .app-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .app-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.25;
        }

        .app-role {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .match-score-pill {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            flex-shrink: 0;
        }

        .match-score-pill.high { background: var(--success-light); color: var(--success); border: 1px solid rgba(5, 150, 105, 0.2); }
        .match-score-pill.medium { background: var(--primary-light); color: var(--primary); border: 1px solid rgba(79, 70, 229, 0.2); }
        .match-score-pill.normal { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        .app-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
            margin-top: 2px;
        }

        .exp-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f8fafc;
            padding: 2px 7px;
            border-radius: var(--radius-sm);
            font-weight: 500;
            color: var(--text-muted);
        }

        .btn-advance-stage {
            color: var(--primary);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: gap 0.2s ease;
        }

        .app-item:hover .btn-advance-stage {
            gap: 8px;
        }

        .pipeline-empty-state {
            border: 2px dashed #e2e8f0;
            border-radius: var(--radius);
            padding: 24px 16px;
            text-align: center;
            color: var(--text-light);
            font-size: 0.82rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.5);
        }

        .pipeline-empty-state .empty-icon {
            font-size: 1.4rem;
            opacity: 0.6;
        }

        /* Clean Table */
        .table-wrap {
            overflow-x: auto;
            border-radius: var(--radius-lg);
        }

        table.clean-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        table.clean-table th {
            padding: 14px 18px;
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }

        table.clean-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
            vertical-align: middle;
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
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-backdrop.active {
            display: flex;
            animation: fadeInOverlay 0.2s ease-out;
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
            top: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 9999999 !important;
            max-width: 440px;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .toast-box {
                left: 12px;
                right: 12px;
                top: 12px;
                max-width: none;
            }
        }

        .toast-msg {
            background: #ffffff;
            border: 1px solid var(--border);
            border-left: 5px solid var(--primary);
            border-radius: var(--radius);
            padding: 12px 18px;
            font-size: 0.88rem;
            font-weight: 600;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            color: var(--text-dark);
            min-width: 280px;
            pointer-events: auto;
            animation: fadeIn 0.25s ease-out;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-msg.success {
            border-left-color: var(--success);
            background: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }

        .toast-msg.error {
            border-left-color: #dc2626;
            background: #fef2f2;
            color: #991b1b;
            border-color: #fca5a5;
            box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.25), 0 8px 10px -6px rgba(220, 38, 38, 0.1);
        }

        .toast-msg.info {
            border-left-color: var(--primary);
            background: #eef2ff;
            color: #3730a3;
            border-color: #c7d2fe;
        }

        /* Inline Form Error Banners */
        .auth-error-banner {
            display: none;
            margin-bottom: 14px;
            padding: 10px 14px;
            background-color: #fef2f2;
            border: 1px solid #f87171;
            border-left: 4px solid #dc2626;
            border-radius: 8px;
            color: #991b1b;
            font-size: 0.84rem;
            font-weight: 600;
            line-height: 1.4;
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.08);
            animation: fadeIn 0.2s ease-in-out;
        }

        .auth-error-banner.visible {
            display: block !important;
        }

        .form-control.input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
        }

        @keyframes shakeX {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .shake-animate {
            animation: shakeX 0.4s ease-in-out;
        }

        /* Password Requirements Box */
        .password-requirements-card {
            margin-top: 6px;
            margin-bottom: 8px;
            padding: 7px 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.73rem;
            color: #64748b;
            line-height: 1.35;
        }

        .password-requirements-card .req-title {
            font-weight: 700;
            color: #475569;
            margin-bottom: 4px;
            font-size: 0.71rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .password-requirements-card .req-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 8px;
        }

        .password-requirements-card .req-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #64748b;
            transition: color 0.15s ease;
            white-space: nowrap;
        }

        .password-requirements-card .req-item .req-icon {
            font-size: 0.8rem;
            line-height: 1;
            font-weight: 700;
            color: #94a3b8;
            width: 12px;
            text-align: center;
            flex-shrink: 0;
        }

        .password-requirements-card .req-item.met {
            color: #15803d;
            font-weight: 600;
        }

        .password-requirements-card .req-item.met .req-icon {
            color: #16a34a;
        }

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
            margin-bottom: 28px;
        }

        .gateway-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 1060px) {
            .gateway-grid {
                grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 640px) {
            .gateway-grid {
                grid-template-columns: 1fr;
                max-width: 480px;
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
            overflow: hidden;
        }

        .gateway-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .gateway-card-header {
            padding: 18px 20px 14px 20px;
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
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .gateway-card-desc {
            font-size: 0.81rem;
            color: #64748b;
            line-height: 1.45;
            min-height: 48px;
        }

        .gateway-card-body {
            padding: 18px 20px;
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

        /* Modern Loading Popup Overlay */
        .loading-popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInOverlay 0.2s ease-out forwards;
        }

        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .loading-popup-card {
            position: relative;
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 36px;
            max-width: 380px;
            width: 90%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(226, 232, 240, 0.8);
            animation: popInCard 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .loading-popup-close {
            position: absolute;
            top: 14px;
            right: 16px;
            background: none;
            border: none;
            font-size: 22px;
            line-height: 1;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
            transition: color 0.15s, background 0.15s;
        }
        .loading-popup-close:hover {
            color: #0f172a;
            background: #f1f5f9;
        }

        @keyframes popInCard {
            from {
                opacity: 0;
                transform: scale(0.88) translateY(12px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .loading-spinner-wrapper {
            position: relative;
            width: 68px;
            height: 68px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-spinner-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 4px solid #e2e8f0;
            border-top-color: #2563eb;
            border-right-color: #3b82f6;
            animation: spinRing 0.85s linear infinite;
        }

        @keyframes spinRing {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-spinner-core {
            font-size: 1.5rem;
            animation: pulseCore 1.5s ease-in-out infinite;
        }

        @keyframes pulseCore {
            0%, 100% { transform: scale(0.9); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        .loading-popup-title {
            font-size: 1.18rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }

        .loading-popup-subtitle {
            font-size: 0.86rem;
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.45;
        }

        .loading-progress-bar {
            width: 100%;
            height: 4px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .loading-progress-track {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, #2563eb, #38bdf8);
            border-radius: 999px;
            animation: progressSlide 1.2s ease-in-out infinite;
        }

        @keyframes progressSlide {
            0% { left: -40%; width: 40%; }
            50% { left: 30%; width: 50%; }
            100% { left: 100%; width: 40%; }
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
                    <span>TalentFlow</span>
                </div>
                <h1 class="gateway-headline">Welcome! Choose your access portal</h1>
                <p class="gateway-subtitle">
                    Select how you want to enter: <strong>Recruiter</strong>, <strong>Candidate</strong>, or <strong>Administrator</strong>. You can sign in with demo accounts using Quick Fill, or register a new account.
                </p>
            </div>

            <!-- 3 Role Portals Grid -->
            <!-- 3 Role Portals Grid: Candidate first, Recruiter second, Admin third -->
            <div class="gateway-grid">

                <!-- 1. CANDIDATE PORTAL -->
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
                                <span class="gateway-quick-fill-text">Seed: <code>Parampreet Singh</code></span>
                                <button type="button" class="gateway-quick-fill-btn" onclick="gatewayFill('candidate')">Quick Fill</button>
                            </div>

                            <div id="gatewayCandidateError" class="auth-error-banner"></div>

                            <form onsubmit="handleGatewayLogin(event, 'candidate')">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label class="form-label">Username or Email</label>
                                    <input type="text" class="form-control" name="email" id="gatewayCandidateEmail" placeholder="Parampreet Singh" required>
                                </div>
                                <div class="form-group" style="margin-bottom:16px;">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="gatewayCandidatePassword" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Sign In as Candidate</button>
                            </form>
                        </div>

                        <!-- Candidate Register Form -->
                        <div id="gatewayCandidateRegisterForm" style="display:none;">
                            <div id="gatewayCandidateRegisterError" class="auth-error-banner"></div>
                            <form onsubmit="handleGatewayRegister(event, 'candidate')">
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Your Full Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Parampreet Singh" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Personal Email *</label>
                                    <input type="email" class="form-control" name="email" placeholder="parampreet@example.com" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+1-555-0200">
                                </div>
                                <div class="form-group" style="margin-bottom:14px;">
                                    <label class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password" id="gatewayCandidateRegPassword" minlength="8" placeholder="e.g. Parampreet!7" required oninput="checkPasswordRequirements(this, 'gatewayCandidatePassReq')">
                                    <div class="password-requirements-card" id="gatewayCandidatePassReq">
                                        <div class="req-title">Password must contain:</div>
                                        <div class="req-grid">
                                            <span class="req-item" data-rule="len"><span class="req-icon">○</span> Min 8 chars</span>
                                            <span class="req-item" data-rule="upper"><span class="req-icon">○</span> 1 uppercase (A-Z)</span>
                                            <span class="req-item" data-rule="lower"><span class="req-icon">○</span> 1 lowercase (a-z)</span>
                                            <span class="req-item" data-rule="number"><span class="req-icon">○</span> 1 number (0-9)</span>
                                            <span class="req-item" data-rule="special" style="grid-column: span 2;"><span class="req-icon">○</span> 1 special symbol (!@#$%^&*...)</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register Candidate Account</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. RECRUITER PORTAL -->
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
                                <span class="gateway-quick-fill-text">Seed: <code>Chandan Kumar</code></span>
                                <button type="button" class="gateway-quick-fill-btn" onclick="gatewayFill('recruiter')">Quick Fill</button>
                            </div>

                            <div id="gatewayRecruiterError" class="auth-error-banner"></div>

                            <form onsubmit="handleGatewayLogin(event, 'recruiter')">
                                <div class="form-group" style="margin-bottom:12px;">
                                    <label class="form-label">Username or Email</label>
                                    <input type="text" class="form-control" name="email" id="gatewayRecruiterEmail" placeholder="Chandan Kumar" required>
                                </div>
                                <div class="form-group" style="margin-bottom:16px;">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="gatewayRecruiterPassword" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Sign In as Recruiter</button>
                            </form>
                        </div>

                        <!-- Recruiter Register Form -->
                        <div id="gatewayRecruiterRegisterForm" style="display:none;">
                            <div id="gatewayRecruiterRegisterError" class="auth-error-banner"></div>
                            <form onsubmit="handleGatewayRegister(event, 'recruiter')">
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Chandan Kumar" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Work Email *</label>
                                    <input type="email" class="form-control" name="email" placeholder="chandan@company.com" required>
                                </div>
                                <div class="form-group" style="margin-bottom:10px;">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+1-555-0100">
                                </div>
                                <div class="form-group" style="margin-bottom:14px;">
                                    <label class="form-label">Password *</label>
                                    <input type="password" class="form-control" name="password" id="gatewayRecruiterRegPassword" minlength="8" placeholder="e.g. Chandan!7" required oninput="checkPasswordRequirements(this, 'gatewayRecruiterPassReq')">
                                    <div class="password-requirements-card" id="gatewayRecruiterPassReq">
                                        <div class="req-title">Password must contain:</div>
                                        <div class="req-grid">
                                            <span class="req-item" data-rule="len"><span class="req-icon">○</span> Min 8 chars</span>
                                            <span class="req-item" data-rule="upper"><span class="req-icon">○</span> 1 uppercase (A-Z)</span>
                                            <span class="req-item" data-rule="lower"><span class="req-icon">○</span> 1 lowercase (a-z)</span>
                                            <span class="req-item" data-rule="number"><span class="req-icon">○</span> 1 number (0-9)</span>
                                            <span class="req-item" data-rule="special" style="grid-column: span 2;"><span class="req-icon">○</span> 1 special symbol (!@#$%^&*...)</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register Recruiter Account</button>
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
                            🛡️ Admin account is provided via system.
                        </div>

                        <div class="gateway-quick-fill-box">
                            <span class="gateway-quick-fill-text">Seed: <code>Namanpreet Kaur</code></span>
                            <button type="button" class="gateway-quick-fill-btn" onclick="gatewayFill('admin')">Quick Fill</button>
                        </div>

                        <div id="gatewayAdminError" class="auth-error-banner"></div>

                        <form onsubmit="handleGatewayLogin(event, 'admin')">
                            <div class="form-group" style="margin-bottom:12px;">
                                <label class="form-label">Username or Email</label>
                                <input type="text" class="form-control" name="email" id="gatewayAdminEmail" placeholder="Namanpreet Kaur" required>
                            </div>
                            <div class="form-group" style="margin-bottom:16px;">
                                <label class="form-label">Admin Password</label>
                                <input type="password" class="form-control" name="password" id="gatewayAdminPassword" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Sign In as Admin</button>
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
                <div style="display:flex; align-items:center; gap: 24px;">
                    <a href="/" class="brand">
                        <div class="brand-icon">TF</div>
                        <span>TalentFlow</span>
                    </a>

                    <ul class="nav-tabs">
                        <li><button class="active" onclick="switchTab('dashboard')">Dashboard</button></li>
                        <li><button onclick="switchTab('jobs')">Jobs</button></li>
                        <li id="tabNavItemPipeline"><button onclick="switchTab('pipeline')">Pipeline</button></li>
                        <li><button onclick="switchTab('interviews')">Interviews</button></li>
                        <li><button onclick="switchTab('tasks')">Tasks</button></li>
                        <li id="tabNavItemCandidates" style="display:none;"><button onclick="switchTab('candidates')">Candidates</button></li>
                        <li id="tabNavItemRecruiters" style="display:none;"><button onclick="switchTab('recruiters')">Recruiters</button></li>
                    </ul>
                </div>

                <!-- User Auth & Role Area -->
                <div class="user-nav-box">
                    <div id="userLoggedInBlock" style="display:none; align-items:center; gap:12px;">
                        
                        <div class="user-profile-pill" onclick="openCurrentUserProfile()" style="cursor:pointer;" title="Click to view profile details">
                            <div class="user-avatar-circle" id="navUserAvatar">AM</div>
                            <span id="navUserName" style="font-weight:700; font-size:0.88rem; color:var(--text-dark);">Alex Miller</span>
                        </div>

                        <!-- Notification Bell (with 24h task deadline reminders & status updates) -->
                        <div class="notif-wrapper" id="notifWrapper">
                            <button type="button" class="btn-icon-notif" id="btnNotifToggle" onclick="toggleNotifications(event)" title="Notifications (Deadline reminders & updates)" aria-label="Notifications">
                                <span class="bell-icon" style="font-size:0.95rem; font-weight:700;">🔔</span>
                                <span class="notif-badge" id="notifBadge" style="display: none;">0</span>
                            </button>
                            <div class="notif-dropdown" id="notifDropdown" style="display: none;">
                                <div class="notif-dropdown-header">
                                    <span style="font-weight:700; font-size:0.88rem; color:var(--text-dark);">Notifications</span>
                                    <button type="button" class="notif-mark-all" onclick="markAllNotificationsRead(event)">Mark all as read</button>
                                </div>
                                <div class="notif-dropdown-body" id="notifList">
                                    <div class="notif-empty">No notifications yet</div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-outline btn-sm" onclick="logout()" title="Logout">Logout</button>
                    </div>
                    <div id="userGuestBlock">
                        <button class="btn btn-primary btn-sm" onclick="openAuthModal('candidate', 'login')">Sign In / Register</button>
                    </div>
                </div>
            </div>
        </header>

    <!-- Main Workspace -->
    <main class="main-content">

        <!-- 1. DASHBOARD TAB -->
        <section class="tab-panel active" id="panel-dashboard">
            <div class="section-header">
                <div>
                    <h2 class="section-title" id="dashboardSectionTitle">Overview & Analytics</h2>
                    <p class="section-desc" id="dashboardSectionDesc">Key metrics and hiring funnel status across all active jobs.</p>
                </div>
                <div id="recruiterDashboardActions">
                    <button class="btn btn-primary btn-sm" onclick="openCreateJob()">+ Post Job</button>
                </div>
            </div>

            <div class="stats-grid" id="dashboardStatsGrid">
                <div class="stat-card" id="cardStatJobs">
                    <div class="stat-header">
                        <span class="stat-label" id="labelStatJobs">Total Jobs</span>
                    </div>
                    <div class="stat-value" id="statJobs">0</div>
                    <div class="stat-sub" id="statActiveJobs">0 open positions</div>
                </div>
                <div class="stat-card" id="cardStatCandidates">
                    <div class="stat-header">
                        <span class="stat-label" id="labelStatCandidates">Active Candidates</span>
                    </div>
                    <div class="stat-value" id="statCandidates" style="color:var(--primary);">0</div>
                    <div class="stat-sub" id="subStatCandidates">Screened & in pipeline</div>
                </div>
                <div class="stat-card" id="cardStatInterviews">
                    <div class="stat-header">
                        <span class="stat-label" id="labelStatInterviews">Interviews This Week</span>
                    </div>
                    <div class="stat-value" id="statInterviews">0</div>
                    <div class="stat-sub" id="subStatInterviews">Scheduled sessions</div>
                </div>
                <div class="stat-card" id="cardStatAvgScore">
                    <div class="stat-header">
                        <span class="stat-label" id="labelStatAvgScore">Avg Candidate Score</span>
                    </div>
                    <div class="stat-value" id="statAvgScore" style="color:var(--success);">0%</div>
                    <div class="stat-sub" id="subStatAvgScore">Resume skill match</div>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title" id="pipelineFunnelTitle">Hiring Pipeline Funnel</h3>
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
                    <button class="btn btn-primary btn-sm" onclick="openCreateJob()">+ Post New Job</button>
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

            <div id="jobsResumeAlert"></div>
            <div class="jobs-grid" id="jobsGrid"></div>
        </section>

        <!-- 3. PIPELINE TAB (Split Layout + List View) -->
        <section class="tab-panel" id="panel-pipeline">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Candidate Pipeline</h2>
                    <p class="section-desc">Technical applicant tracking system. Select a stage on the left sidebar to review candidate applications.</p>
                </div>
            </div>

            <!-- Pipeline Split Layout (Left Sidebar + Right Applicants Area) -->
            <div class="pipeline-split-layout">
                
                <!-- Left Stage Navigation Sidebar -->
                <aside class="pipeline-sidebar">
                    <div class="sidebar-heading">Filter By Stage</div>
                    <ul class="sidebar-stage-list" id="pipelineStageList">
                        <!-- Rendered by renderPipelineSidebar() -->
                    </ul>
                </aside>

                <!-- Right Applicants Main Content Area -->
                <main class="pipeline-main-area">
                    
                    <!-- Search & Filter Toolbar -->
                    <div class="pipeline-toolbar">
                        <div class="pipeline-search-box">
                            <input type="text" id="pipelineSearch" class="pipeline-search-input" placeholder="Search applicant name, position, or skills..." oninput="filterPipelineView()">
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <label class="sort-label">Sort by:</label>
                            <select id="pipelineSort" class="input-select" onchange="filterPipelineView()">
                                <option value="score_desc">Match Score (Highest)</option>
                                <option value="exp_desc">Experience (Highest)</option>
                                <option value="recent">Recently Applied</option>
                                <option value="name_asc">Candidate Name (A-Z)</option>
                            </select>
                        </div>
                    </div>

                    <!-- List View Container (Indeed-style list view) -->
                    <div id="pipelineContainerList" class="pipeline-list-container">
                        <!-- Rendered by renderPipelineList() -->
                    </div>

                </main>
            </div>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="candidatesTable"></tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- 7. RECRUITERS TAB (Admin) -->
        <section class="tab-panel" id="panel-recruiters">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Recruiter Directory</h2>
                    <p class="section-desc">Active recruiters, hiring managers, and recruiting activity.</p>
                </div>
            </div>

            <div class="card" style="padding:0; overflow:hidden;">
                <div class="table-wrap">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Recruiter Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Jobs Posted</th>
                                <th>Interviews</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recruitersTable"></tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>
    <!-- CANDIDATE DETAIL MODAL / PROFILE DRAWER -->
    <div class="modal-backdrop" id="modalCandidateDetail">
        <div class="modal-content" style="max-width: 620px;">
            <div class="modal-head">
                <h3 id="candidateDetailName">Candidate Profile</h3>
                <button class="btn-close" onclick="closeModal('modalCandidateDetail')">&times;</button>
            </div>
            <div class="modal-body" id="candidateDetailBody">
                <!-- Populated dynamically via openCandidateDetail(appId) or openCandidateProfileModal(candId) -->
            </div>
        </div>
    </div>

    <!-- RECRUITER DETAIL MODAL -->
    <div class="modal-backdrop" id="modalRecruiterDetail">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-head">
                <h3 id="recruiterDetailTitle">Recruiter Profile</h3>
                <button class="btn-close" onclick="closeModal('modalRecruiterDetail')">&times;</button>
            </div>
            <div class="modal-body" id="recruiterDetailBody">
                <!-- Populated dynamically via openRecruiterDetailModal(id) -->
            </div>
        </div>
    </div>

    <!-- NO RESUME WARNING MODAL -->
    <div class="modal-backdrop" id="modalNoResume">
        <div class="modal-content" style="max-width: 480px; text-align: center; padding: 28px 24px;">
            <div style="width: 64px; height: 64px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 16px;">
                📄
            </div>
            <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--text-dark); margin-bottom: 8px;">Resume Required to Apply</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.5; margin-bottom: 20px;">
                No resume was found on your profile. <strong style="color: #dc2626;">Please go to Profile Settings to add your resume to apply. Without a resume, no jobs can be applied!</strong>
            </p>
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px; margin-bottom: 24px; font-size: 0.85rem; color: #92400e; text-align: left; display: flex; gap: 10px; align-items: center;">
                <span style="font-size: 1.25rem;">💡</span>
                <span>Upload your PDF resume once in Profile Settings, and it will automatically be attached to all jobs you apply for.</span>
            </div>
            <div style="display: flex; justify-content: center; gap: 12px;">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNoResume')">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="closeModal('modalNoResume'); openCurrentUserProfile(true);" style="display: inline-flex; align-items: center; gap: 6px;">
                    <span>⚙️</span> Go to Profile Settings
                </button>
            </div>
        </div>
    </div>

    <!-- CURRENT USER PROFILE MODAL -->
    <div class="modal-backdrop" id="modalUserProfile">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-head">
                <h3 id="userProfileTitle">My Profile</h3>
                <button class="btn-close" onclick="closeModal('modalUserProfile')">&times;</button>
            </div>
            <div class="modal-body" id="userProfileBody">
                <!-- Populated dynamically via openCurrentUserProfile() -->
            </div>
        </div>
    </div>

    <!-- AUTHENTICATION PORTAL MODAL (Dedicated Recruiter, Candidate & Admin logins) -->
    <div class="modal-backdrop" id="modalAuth">
        <div class="modal-content" style="max-width: 480px;">
            <div class="modal-head">
                <h3 id="authModalTitle">Sign In to TalentFlow</h3>
                <button class="btn-close" onclick="closeModal('modalAuth')">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Portal Type: Candidate / Recruiter / Admin -->
                <div class="auth-portal-tabs">
                    <button class="auth-portal-tab active" id="tabPortalCandidate" onclick="switchPortal('candidate')">👤 Candidate</button>
                    <button class="auth-portal-tab" id="tabPortalRecruiter" onclick="switchPortal('recruiter')">👔 Recruiter</button>
                    <button class="auth-portal-tab" id="tabPortalAdmin" onclick="switchPortal('admin')">🛡️ Admin</button>
                </div>

                <!-- 1. CANDIDATE PORTAL -->
                <div id="portalCandidate">
                    <div class="auth-sub-tabs">
                        <button class="auth-sub-tab active" id="candidateSubLogin" onclick="switchSubAuth('candidate', 'login')">Candidate Login</button>
                        <button class="auth-sub-tab" id="candidateSubRegister" onclick="switchSubAuth('candidate', 'register')">Candidate Register</button>
                    </div>

                    <!-- Candidate Login -->
                    <form id="formCandidateLogin" onsubmit="handleAuthLogin(event, 'candidate')">
                        <div class="demo-pill" style="margin-bottom:12px;">
                            <span>Seed: <code>Parampreet Singh</code></span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillCreds('candidate', 'Parampreet Singh', 'Namanpreet!7')">Quick Fill</button>
                        </div>
                        <div id="modalCandidateError" class="auth-error-banner"></div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Username or Email</label>
                            <input type="text" class="form-control" name="email" id="candidateLoginEmail" placeholder="Parampreet Singh" required>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="candidateLoginPassword" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Login as Candidate</button>
                    </form>

                    <!-- Candidate Register -->
                    <form id="formCandidateRegister" style="display:none;" onsubmit="handleAuthRegister(event, 'candidate')">
                        <div id="modalCandidateRegisterError" class="auth-error-banner"></div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Your Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="Parampreet Singh">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required placeholder="parampreet@example.com">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+1-555-0200">
                        </div>
                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" id="modalCandidateRegPassword" minlength="8" required placeholder="e.g. Parampreet!7" oninput="checkPasswordRequirements(this, 'modalCandidatePassReq')">
                            <div class="password-requirements-card" id="modalCandidatePassReq">
                                <div class="req-title">Password must contain:</div>
                                <div class="req-grid">
                                    <span class="req-item" data-rule="len"><span class="req-icon">○</span> Min 8 chars</span>
                                    <span class="req-item" data-rule="upper"><span class="req-icon">○</span> 1 uppercase (A-Z)</span>
                                    <span class="req-item" data-rule="lower"><span class="req-icon">○</span> 1 lowercase (a-z)</span>
                                    <span class="req-item" data-rule="number"><span class="req-icon">○</span> 1 number (0-9)</span>
                                    <span class="req-item" data-rule="special" style="grid-column: span 2;"><span class="req-icon">○</span> 1 special symbol (!@#$%^&*...)</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register as Candidate</button>
                    </form>
                </div>

                <!-- 2. RECRUITER PORTAL -->
                <div id="portalRecruiter" style="display:none;">
                    <div class="auth-sub-tabs">
                        <button class="auth-sub-tab active" id="recruiterSubLogin" onclick="switchSubAuth('recruiter', 'login')">Recruiter Login</button>
                        <button class="auth-sub-tab" id="recruiterSubRegister" onclick="switchSubAuth('recruiter', 'register')">Recruiter Register</button>
                    </div>

                    <!-- Recruiter Login -->
                    <form id="formRecruiterLogin" onsubmit="handleAuthLogin(event, 'recruiter')">
                        <div class="demo-pill" style="margin-bottom:12px;">
                            <span>Seed: <code>Chandan Kumar</code></span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillCreds('recruiter', 'Chandan Kumar', 'Namanpreet!7')">Quick Fill</button>
                        </div>
                        <div id="modalRecruiterError" class="auth-error-banner"></div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Username or Email</label>
                            <input type="text" class="form-control" name="email" id="recruiterLoginEmail" placeholder="Chandan Kumar" required>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="recruiterLoginPassword" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Login as Recruiter</button>
                    </form>

                    <!-- Recruiter Register -->
                    <form id="formRecruiterRegister" style="display:none;" onsubmit="handleAuthRegister(event, 'recruiter')">
                        <div id="modalRecruiterRegisterError" class="auth-error-banner"></div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" required placeholder="Chandan Kumar">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Work Email *</label>
                            <input type="email" class="form-control" name="email" required placeholder="chandan@company.com">
                        </div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="+1-555-0100">
                        </div>
                        <div class="form-group" style="margin-bottom:14px;">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" id="modalRecruiterRegPassword" minlength="8" required placeholder="e.g. Chandan!7" oninput="checkPasswordRequirements(this, 'modalRecruiterPassReq')">
                            <div class="password-requirements-card" id="modalRecruiterPassReq">
                                <div class="req-title">Password must contain:</div>
                                <div class="req-grid">
                                    <span class="req-item" data-rule="len"><span class="req-icon">○</span> Min 8 chars</span>
                                    <span class="req-item" data-rule="upper"><span class="req-icon">○</span> 1 uppercase (A-Z)</span>
                                    <span class="req-item" data-rule="lower"><span class="req-icon">○</span> 1 lowercase (a-z)</span>
                                    <span class="req-item" data-rule="number"><span class="req-icon">○</span> 1 number (0-9)</span>
                                    <span class="req-item" data-rule="special" style="grid-column: span 2;"><span class="req-icon">○</span> 1 special symbol (!@#$%^&*...)</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Register as Recruiter</button>
                    </form>
                </div>

                <!-- 3. ADMIN PORTAL (Login only) -->
                <div id="portalAdmin" style="display:none;">
                    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:6px; padding:10px; font-size:0.82rem; color:#92400e; margin-bottom:14px;">
                        🛡️ <strong>Admin Portal:</strong> System administrator access with full control across jobs, candidates, pipeline, and analytics.
                    </div>

                    <form id="formAdminLogin" onsubmit="handleAuthLogin(event, 'admin')">
                        <div class="demo-pill" style="margin-bottom:12px;">
                            <span>Seed: <code>Namanpreet Kaur</code></span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillCreds('admin', 'Namanpreet Kaur', 'Namanpreet!7')">Quick Fill</button>
                        </div>
                        <div id="modalAdminError" class="auth-error-banner"></div>
                        <div class="form-group" style="margin-bottom:12px;">
                            <label class="form-label">Username or Email</label>
                            <input type="text" class="form-control" name="email" id="adminLoginEmail" placeholder="Namanpreet Kaur" required>
                        </div>
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Admin Password</label>
                            <input type="password" class="form-control" name="password" id="adminLoginPassword" placeholder="••••••••" required>
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
                <h3 id="modalJobHeading">Post New Job Opening</h3>
                <button class="btn-close" onclick="closeModal('modalJob')">&times;</button>
            </div>
            <form id="formJob" onsubmit="submitJob(event)">
                <input type="hidden" name="job_id" id="jobModalId" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Job Title *</label>
                        <input type="text" class="form-control" name="title" id="jobTitleInput" placeholder="e.g. Senior Laravel Developer" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Department *</label>
                        <input type="text" class="form-control" name="department" id="jobDepartmentInput" placeholder="e.g. Engineering" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Experience Required *</label>
                        <input type="text" class="form-control" name="experience" id="jobExperienceInput" placeholder="e.g. 3-5 years" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Range</label>
                        <input type="text" class="form-control" name="salary_range" id="jobSalaryInput" placeholder="e.g. $80,000 - $110,000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Application Deadline *</label>
                        <input type="date" class="form-control" name="application_deadline" id="jobDeadlineInput" required>
                    </div>
                    <div class="form-group" id="jobStatusGroup" style="display:none;">
                        <label class="form-label">Job Status</label>
                        <select class="form-control" name="status" id="jobStatusSelect">
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mandatory Skills (comma-separated)</label>
                        <input type="text" class="form-control" name="mandatory_skills" id="jobMandatorySkillsInput" placeholder="PHP, Laravel, MySQL, REST API">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bonus Skills (comma-separated)</label>
                        <input type="text" class="form-control" name="bonus_skills" id="jobBonusSkillsInput" placeholder="Docker, Vue.js, Redis">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Description *</label>
                        <textarea class="form-control" name="description" id="jobDescriptionInput" rows="4" placeholder="Brief job summary..." required></textarea>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJob')">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnSubmitJob">Save Job</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Job Details Modal -->
    <div class="modal-backdrop" id="modalJobView">
        <div class="modal-content" style="max-width: 680px;">
            <div class="modal-head">
                <div>
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                        <h3 id="viewJobTitle" style="margin:0; font-size:1.3rem; font-weight:800; color:var(--text-dark);">Job Details</h3>
                        <span id="viewJobStatusBadge" class="badge">Open</span>
                    </div>
                    <div id="viewJobSubtitle" style="font-size:0.86rem; color:var(--text-muted); margin-top:4px;"></div>
                </div>
                <button class="btn-close" onclick="closeModal('modalJobView')">&times;</button>
            </div>
            <div class="modal-body" style="max-height: calc(85vh - 140px); overflow-y: auto; padding: 20px 24px; display:flex; flex-direction:column; gap:18px;">
                <!-- Key Details Grid -->
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:12px; background:#f8fafc; padding:16px; border-radius:var(--radius-md); border:1px solid #e2e8f0;">
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Department</div>
                        <div id="viewJobDept" style="font-size:0.95rem; font-weight:700; color:var(--text-dark); margin-top:3px;">-</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Experience</div>
                        <div id="viewJobExp" style="font-size:0.95rem; font-weight:700; color:var(--text-dark); margin-top:3px;">-</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Salary Range</div>
                        <div id="viewJobSalary" style="font-size:0.95rem; font-weight:700; color:var(--success); margin-top:3px;">-</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Deadline</div>
                        <div id="viewJobDeadline" style="font-size:0.95rem; font-weight:700; color:var(--text-dark); margin-top:3px;">-</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Recruiter</div>
                        <div id="viewJobRecruiter" style="font-size:0.95rem; font-weight:600; color:var(--text-dark); margin-top:3px;">-</div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:0.04em;">Applicants</div>
                        <div id="viewJobApplicants" style="font-size:0.95rem; font-weight:700; color:var(--primary); margin-top:3px;">0 applied</div>
                    </div>
                </div>

                <!-- Mandatory Skills -->
                <div>
                    <h5 style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:#475569; margin:0 0 8px 0; letter-spacing:0.04em;">Mandatory Required Skills</h5>
                    <div id="viewJobMandatorySkills" class="skills-list" style="margin-bottom:0;"></div>
                </div>

                <!-- Bonus Skills -->
                <div id="viewJobBonusSkillsWrapper">
                    <h5 style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:#475569; margin:0 0 8px 0; letter-spacing:0.04em;">Bonus / Preferred Skills</h5>
                    <div id="viewJobBonusSkills" class="skills-list" style="margin-bottom:0;"></div>
                </div>

                <!-- Description -->
                <div>
                    <h5 style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:#475569; margin:0 0 8px 0; letter-spacing:0.04em;">Job Description</h5>
                    <div id="viewJobDescription" style="white-space:pre-wrap; color:#334155; font-size:0.92rem; line-height:1.65; background:#ffffff; padding:14px; border:1px solid #e2e8f0; border-radius:var(--radius-md);"></div>
                </div>
            </div>
            <div class="modal-foot" id="viewJobFooter"></div>
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
                        <div id="applyResumeNotice" style="margin-top:4px;"></div>
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
                    <div class="form-group">
                        <label class="form-label">Attach Files (Images, PDF, Documents — up to 5 only)</label>
                        <input type="file" class="form-control" name="files[]" id="taskFilesInput" multiple accept=".pdf,.doc,.docx,.txt,.rtf,.odt,image/*" onchange="handleTaskFileSelect(this)">
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">
                            Allowed formats: Images (JPG, PNG, WEBP, SVG), PDF, Documents (DOC, DOCX, TXT) • Max 5 files (10MB each)
                        </div>
                        <div id="taskFilesPreview" style="display:flex; flex-direction:column; gap:6px; margin-top:8px;"></div>
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
                <h3 id="submitTaskModalTitle">Submit Task Solution</h3>
                <button class="btn-close" onclick="closeModal('modalSubmitTask')">&times;</button>
            </div>
            <form id="formSubmitTask" onsubmit="submitTaskSolution(event)">
                <input type="hidden" name="task_id" id="submitTaskId">
                <div class="modal-body">
                    <div id="submitTaskInstructionsBox" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:14px; font-size:0.85rem;">
                        <div style="font-weight:700; color:var(--text-dark); margin-bottom:4px;">Task Description:</div>
                        <div id="submitTaskDescText" style="color:#475569; margin-bottom:8px; white-space:pre-wrap;"></div>
                        <div id="submitTaskAttachmentsContainer"></div>
                    </div>
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

    <!-- Global Loading Popup Overlay -->
    <div id="loadingPopupOverlay" class="loading-popup-overlay" style="display: none;" onclick="if(event.target===this) hideLoading();">
        <div class="loading-popup-card" onclick="event.stopPropagation();">
            <button type="button" class="loading-popup-close" onclick="hideLoading()" aria-label="Dismiss" title="Dismiss loading screen">&times;</button>
            <div class="loading-spinner-wrapper">
                <div class="loading-spinner-ring"></div>
                <div class="loading-spinner-core">⚡</div>
            </div>
            <h3 id="loadingPopupTitle" class="loading-popup-title">Loading...</h3>
            <p id="loadingPopupSubtitle" class="loading-popup-subtitle">Connecting to database & loading workspace...</p>
            <div class="loading-progress-bar">
                <div class="loading-progress-track"></div>
            </div>
        </div>
    </div>

    <!-- Toast Messages -->
    <div class="toast-box" id="toastBox"></div>

    <script>
        // State
        let initialUser = null;
        try {
            const saved = localStorage.getItem('tf_user');
            initialUser = saved ? JSON.parse(saved) : null;
        } catch (e) {}

        const state = {
            token: localStorage.getItem('tf_token') || '',
            currentUser: initialUser,
            jobs: [],
            applications: [],
            interviews: [],
            tasks: [],
            candidates: [],
            recruiters: []
        };

        // Global Loading Popup Controller
        let loadingTimeout = null;
        function showLoading(title = 'Loading...', subtitle = 'Please wait...') {
            const overlay = document.getElementById('loadingPopupOverlay');
            const titleEl = document.getElementById('loadingPopupTitle');
            const subEl = document.getElementById('loadingPopupSubtitle');
            if (titleEl) titleEl.textContent = title;
            if (subEl) subEl.textContent = subtitle;
            if (overlay) overlay.style.display = 'flex';

            // Auto-dismiss safety timeout: never block the user indefinitely
            clearTimeout(loadingTimeout);
            loadingTimeout = setTimeout(() => {
                hideLoading();
            }, 2500);
        }

        function hideLoading() {
            clearTimeout(loadingTimeout);
            const overlay = document.getElementById('loadingPopupOverlay');
            if (overlay) overlay.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', async () => {
            if (localStorage.getItem('tf_in_workspace') === 'true' && state.token) {
                showAppWorkspace();
                if (state.currentUser) {
                    try { updateRoleUI(); } catch (e) {}
                }

                // Restore active tab from hash or localStorage
                const hashTab = window.location.hash ? window.location.hash.replace('#', '') : '';
                const savedTab = hashTab || localStorage.getItem('tf_active_tab') || 'dashboard';
                switchTab(savedTab);

                // Silent workspace restore on refresh without blocking loading popup
                try {
                    await reloadAll();
                } catch (e) {
                    console.error('Silent reload error:', e);
                }

                // Re-affirm active tab after reloadAll
                const currentSavedTab = window.location.hash ? window.location.hash.replace('#', '') : (localStorage.getItem('tf_active_tab') || savedTab);
                switchTab(currentSavedTab);
            } else {
                hideLoading();
                showAuthGateway();
            }
        });

        window.addEventListener('hashchange', () => {
            if (localStorage.getItem('tf_in_workspace') === 'true' && state.token) {
                const hashTab = window.location.hash ? window.location.hash.replace('#', '') : '';
                if (hashTab) switchTab(hashTab);
            }
        });

        // Gateway Screen & Workspace Visibility
        function showAuthGateway() {
            document.documentElement.classList.remove('in-workspace');
            const gateway = document.getElementById('authGatewayScreen');
            const workspace = document.getElementById('appWorkspace');
            if (gateway) gateway.style.display = 'flex';
            if (workspace) workspace.style.display = 'none';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showAppWorkspace() {
            document.documentElement.classList.add('in-workspace');
            const gateway = document.getElementById('authGatewayScreen');
            const workspace = document.getElementById('appWorkspace');
            if (gateway) gateway.style.display = 'none';
            if (workspace) workspace.style.display = 'flex';
        }

        // Auth Error Helpers
        function showAuthError(elementId, message) {
            const el = document.getElementById(elementId);
            if (el) {
                el.innerHTML = `<div style="display:flex; align-items:flex-start; gap:8px;">
                    <span style="font-size:1.1rem; line-height:1.2;">⚠️</span>
                    <div style="flex:1;">${escapeHtml(message)}</div>
                </div>`;
                el.style.display = 'block';
                el.classList.add('visible');
            }
        }

        function clearAuthErrors() {
            document.querySelectorAll('.auth-error-banner').forEach(el => {
                el.style.display = 'none';
                el.classList.remove('visible');
                el.innerHTML = '';
            });
            document.querySelectorAll('.form-control.input-error').forEach(input => {
                input.classList.remove('input-error');
            });
        }

        // Live Password Complexity Checker
        function checkPasswordRequirements(input, indicatorId) {
            const val = input.value || '';
            const container = document.getElementById(indicatorId);
            if (!container) return;

            const rules = {
                len: val.length >= 8,
                upper: /[A-Z]/.test(val),
                lower: /[a-z]/.test(val),
                number: /[0-9]/.test(val),
                special: /[^A-Za-z0-9]/.test(val)
            };

            for (const [rule, passed] of Object.entries(rules)) {
                const item = container.querySelector(`[data-rule="${rule}"]`);
                if (item) {
                    if (passed) {
                        item.classList.add('met');
                        const icon = item.querySelector('.req-icon');
                        if (icon) icon.textContent = '✓';
                    } else {
                        item.classList.remove('met');
                        const icon = item.querySelector('.req-icon');
                        if (icon) icon.textContent = '○';
                    }
                }
            }
        }

        function validatePasswordRules(pwd) {
            if (!pwd || pwd.length < 8) return 'Password must be at least 8 characters long.';
            if (!/[A-Z]/.test(pwd)) return 'Password must contain at least 1 capital letter (A-Z).';
            if (!/[a-z]/.test(pwd)) return 'Password must contain at least 1 small letter (a-z).';
            if (!/[0-9]/.test(pwd)) return 'Password must contain at least 1 number (0-9).';
            if (!/[^A-Za-z0-9]/.test(pwd)) return 'Password must contain at least 1 special character (!@#$%^&*...).';
            return null;
        }

        document.addEventListener('input', (e) => {
            if (e.target && e.target.classList.contains('form-control')) {
                e.target.classList.remove('input-error');
                const parent = e.target.closest('#gatewayCandidateLoginForm, #gatewayCandidateRegisterForm, #gatewayRecruiterLoginForm, #gatewayRecruiterRegisterForm, .gateway-card-body, form');
                if (parent) {
                    const banner = parent.querySelector('.auth-error-banner');
                    if (banner) {
                        banner.style.display = 'none';
                        banner.classList.remove('visible');
                    }
                }
            }
        });

        function switchGatewayMode(role, mode) {
            clearAuthErrors();
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
            clearAuthErrors();
            if (role === 'candidate') {
                const e = document.getElementById('gatewayCandidateEmail');
                const p = document.getElementById('gatewayCandidatePassword');
                if (e && p) { e.value = 'Parampreet Singh'; p.value = 'Namanpreet!7'; }
                showToast('Candidate credentials populated!', 'success');
            } else if (role === 'recruiter') {
                const e = document.getElementById('gatewayRecruiterEmail');
                const p = document.getElementById('gatewayRecruiterPassword');
                if (e && p) { e.value = 'Chandan Kumar'; p.value = 'Namanpreet!7'; }
                showToast('Recruiter credentials populated!', 'success');
            } else if (role === 'admin') {
                const e = document.getElementById('gatewayAdminEmail');
                const p = document.getElementById('gatewayAdminPassword');
                if (e && p) { e.value = 'Namanpreet Kaur'; p.value = 'Namanpreet!7'; }
                showToast('Admin credentials populated!', 'success');
            }
        }

        async function handleGatewayLogin(e, role) {
            e.preventDefault();
            clearAuthErrors();
            const form = e.target;
            const email = form.email.value;
            const password = form.password.value;
            await performLogin(email, password, { role: role, form: form });
        }

        async function handleGatewayRegister(e, role) {
            e.preventDefault();
            clearAuthErrors();
            const form = e.target;
            const pwd = form.password.value;
            const pwdError = validatePasswordRules(pwd);
            if (pwdError) {
                const cap = role.charAt(0).toUpperCase() + role.slice(1);
                showAuthError(`gateway${cap}RegisterError`, pwdError);
                if (form.password) form.password.classList.add('input-error');
                form.classList.remove('shake-animate');
                void form.offsetWidth;
                form.classList.add('shake-animate');
                showToast(pwdError, 'error');
                return;
            }

            const payload = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone ? form.phone.value : null,
                password: pwd,
                role: role
            };

            showLoading('Creating Account...', 'Registering profile...');
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
                    localStorage.setItem('tf_user', JSON.stringify(data.user));
                    localStorage.setItem('tf_in_workspace', 'true');
                    showToast(`Registration successful! Welcome ${data.user.name}`, 'success');
                    showAppWorkspace();
                    updateRoleUI();
                    switchTab('dashboard');
                    await reloadAll();
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Registration failed');
                    const cap = role.charAt(0).toUpperCase() + role.slice(1);
                    showAuthError(`gateway${cap}RegisterError`, errorMsg);
                    if (form) {
                        form.querySelectorAll('input').forEach(inp => inp.classList.add('input-error'));
                        form.classList.remove('shake-animate');
                        void form.offsetWidth;
                        form.classList.add('shake-animate');
                    }
                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                showToast('Registration error', 'error');
            } finally {
                hideLoading();
            }
        }

        // Tab Switching
        function switchTab(name) {
            document.getElementById('earlyActiveTabStyle')?.remove();

            const validTabs = ['dashboard', 'jobs', 'pipeline', 'interviews', 'tasks', 'candidates', 'recruiters'];
            if (!validTabs.includes(name)) {
                name = 'dashboard';
            }

            // Role access check: Recruiters list is for Admin only, Candidates list is for Recruiters & Admin
            const role = state.currentUser?.role?.name;
            if (name === 'recruiters' && role !== 'admin') {
                name = 'dashboard';
            }
            if (name === 'candidates' && role === 'candidate') {
                name = 'dashboard';
            }

            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-links button, .nav-tabs button').forEach(b => b.classList.remove('active'));

            const panel = document.getElementById(`panel-${name}`);
            const btn = Array.from(document.querySelectorAll('.nav-links button, .nav-tabs button')).find(b => b.getAttribute('onclick')?.includes(`'${name}'`));

            if (panel) panel.classList.add('active');
            if (btn) btn.classList.add('active');

            try {
                localStorage.setItem('tf_active_tab', name);
                if (window.location.hash !== `#${name}`) {
                    history.replaceState(null, '', `#${name}`);
                }
            } catch (e) {}
        }

        // Auth Modal Portals
        function openAuthModal(portal = 'candidate', type = 'login') {
            clearAuthErrors();
            switchPortal(portal);
            switchSubAuth(portal, type);
            openModal('modalAuth');
        }

        function switchPortal(portal) {
            clearAuthErrors();
            document.querySelectorAll('.auth-portal-tab').forEach(b => b.classList.remove('active'));
            document.getElementById(`tabPortal${portal.charAt(0).toUpperCase() + portal.slice(1)}`).classList.add('active');

            document.getElementById('portalRecruiter').style.display = portal === 'recruiter' ? 'block' : 'none';
            document.getElementById('portalCandidate').style.display = portal === 'candidate' ? 'block' : 'none';
            document.getElementById('portalAdmin').style.display = portal === 'admin' ? 'block' : 'none';
        }

        function switchSubAuth(portal, type) {
            clearAuthErrors();
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
            clearAuthErrors();
            const emailInput = document.getElementById(`${portal}LoginEmail`);
            const passInput = document.getElementById(`${portal}LoginPassword`);
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = password;
            }
        }

        async function handleAuthLogin(e, portal) {
            e.preventDefault();
            clearAuthErrors();
            const form = e.target;
            const email = form.email.value;
            const password = form.password.value;
            await performLogin(email, password, { role: portal, form: form });
        }

        async function performLogin(email, password, options = {}) {
            clearAuthErrors();
            showLoading('Signing In...', 'Verifying credentials...');
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
                    localStorage.setItem('tf_user', JSON.stringify(data.user));
                    localStorage.setItem('tf_in_workspace', 'true');
                    showToast(`Logged in as ${data.user.name} (${data.user.role?.name || 'user'})`, 'success');
                    closeModal('modalAuth');
                    showAppWorkspace();
                    updateRoleUI();
                    const hashTab = window.location.hash ? window.location.hash.replace('#', '') : '';
                    const savedTab = hashTab || localStorage.getItem('tf_active_tab') || 'dashboard';
                    switchTab(savedTab);
                    await reloadAll();
                } else {
                    let errorMsg = 'Username/Password Does not match. Please try again!';
                    if (data.errors) {
                        if (data.errors.email && data.errors.email.length) {
                            errorMsg = data.errors.email.join(' ');
                        } else {
                            errorMsg = Object.values(data.errors).flat().join(' ');
                        }
                    } else if (data.message) {
                        errorMsg = data.message;
                    }

                    if (options.role) {
                        const cap = options.role.charAt(0).toUpperCase() + options.role.slice(1);
                        showAuthError(`gateway${cap}Error`, errorMsg);
                        showAuthError(`modal${cap}Error`, errorMsg);
                    } else {
                        showAuthError('gatewayCandidateError', errorMsg);
                        showAuthError('gatewayRecruiterError', errorMsg);
                        showAuthError('gatewayAdminError', errorMsg);
                    }

                    if (options.form) {
                        options.form.querySelectorAll('input').forEach(inp => inp.classList.add('input-error'));
                        options.form.classList.remove('shake-animate');
                        void options.form.offsetWidth;
                        options.form.classList.add('shake-animate');
                    }

                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                const connMsg = 'Authentication connection error. Please verify the server is running.';
                if (options.role) {
                    const cap = options.role.charAt(0).toUpperCase() + options.role.slice(1);
                    showAuthError(`gateway${cap}Error`, connMsg);
                    showAuthError(`modal${cap}Error`, connMsg);
                }
                showToast(connMsg, 'error');
            } finally {
                hideLoading();
            }
        }

        async function handleAuthRegister(e, role) {
            e.preventDefault();
            clearAuthErrors();
            const form = e.target;
            const pwd = form.password.value;
            const pwdError = validatePasswordRules(pwd);
            if (pwdError) {
                const cap = role.charAt(0).toUpperCase() + role.slice(1);
                showAuthError(`modal${cap}RegisterError`, pwdError);
                if (form.password) form.password.classList.add('input-error');
                form.classList.remove('shake-animate');
                void form.offsetWidth;
                form.classList.add('shake-animate');
                showToast(pwdError, 'error');
                return;
            }

            const payload = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone ? form.phone.value : null,
                password: pwd,
                role: role
            };

            showLoading('Creating Account...', 'Registering profile...');
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
                    localStorage.setItem('tf_user', JSON.stringify(data.user));
                    localStorage.setItem('tf_in_workspace', 'true');
                    showToast(`Registration successful! Welcome ${data.user.name}`, 'success');
                    closeModal('modalAuth');
                    showAppWorkspace();
                    updateRoleUI();
                    await reloadAll();
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Registration failed');
                    const cap = role.charAt(0).toUpperCase() + role.slice(1);
                    showAuthError(`modal${cap}RegisterError`, errorMsg);
                    if (form) {
                        form.querySelectorAll('input').forEach(inp => inp.classList.add('input-error'));
                        form.classList.remove('shake-animate');
                        void form.offsetWidth;
                        form.classList.add('shake-animate');
                    }
                    showToast(errorMsg, 'error');
                }
            } catch (err) {
                showToast('Registration error', 'error');
            } finally {
                hideLoading();
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
            const navAvatar = document.getElementById('navUserAvatar');
            if (navAvatar) {
                navAvatar.textContent = getInitials(user.name);
            }

            const role = user.role?.name || 'candidate';
            const badge = document.getElementById('navRoleBadge');
            if (badge) {
                badge.textContent = role;
                badge.className = `role-badge role-${role}`;
            }

            // Adapt navigation elements based on role
            const isRecruiterOrAdmin = role === 'recruiter' || role === 'admin';
            const isCandidate = role === 'candidate';

            // Stat card adaptation for Candidate vs Recruiter/Admin
            const cardStatScore = document.getElementById('cardStatAvgScore');
            const labelStatCandidates = document.getElementById('labelStatCandidates');
            const subStatCandidates = document.getElementById('subStatCandidates');
            const labelStatJobs = document.getElementById('labelStatJobs');
            const subStatInterviews = document.getElementById('subStatInterviews');
            const dashTitle = document.getElementById('dashboardSectionTitle');
            const dashDesc = document.getElementById('dashboardSectionDesc');
            const funnelTitle = document.getElementById('pipelineFunnelTitle');

            if (isCandidate) {
                // Remove avg candidate score
                if (cardStatScore) cardStatScore.style.display = 'none';
                // Remove active candidates -> change to Tasks This Week
                if (labelStatCandidates) labelStatCandidates.textContent = 'Tasks This Week';
                if (subStatCandidates) subStatCandidates.textContent = 'Active & upcoming deadlines';
                if (labelStatJobs) labelStatJobs.textContent = 'Available Jobs';
                if (subStatInterviews) subStatInterviews.textContent = 'Scheduled for you';
                if (dashTitle) dashTitle.textContent = 'Candidate Portal & Overview';
                if (dashDesc) dashDesc.textContent = 'Track your job applications, scheduled interviews, and technical tasks.';
                if (funnelTitle) funnelTitle.textContent = 'My Application Progress';
            } else {
                if (cardStatScore) cardStatScore.style.display = 'block';
                if (labelStatCandidates) labelStatCandidates.textContent = 'Active Candidates';
                if (subStatCandidates) subStatCandidates.textContent = 'Screened & in pipeline';
                if (labelStatJobs) labelStatJobs.textContent = 'Total Jobs';
                if (subStatInterviews) subStatInterviews.textContent = 'Scheduled sessions';
                if (dashTitle) dashTitle.textContent = 'Overview & Analytics';
                if (dashDesc) dashDesc.textContent = 'Key metrics and hiring funnel status across all active jobs.';
                if (funnelTitle) funnelTitle.textContent = 'Hiring Pipeline Funnel';
            }

            document.getElementById('recruiterDashboardActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('recruiterJobActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('recruiterInterviewActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            document.getElementById('recruiterTaskActions').style.display = isRecruiterOrAdmin ? 'block' : 'none';
            const isAdmin = user.role?.name === 'admin';
            const navCandidates = document.getElementById('tabNavItemCandidates');
            if (navCandidates) navCandidates.style.display = isRecruiterOrAdmin ? 'block' : 'none';
            const navRecruiters = document.getElementById('tabNavItemRecruiters');
            if (navRecruiters) navRecruiters.style.display = isAdmin ? 'block' : 'none';

            // Auto-fill apply form
            const applyName = document.getElementById('applyName');
            const applyEmail = document.getElementById('applyEmail');
            if (applyName && applyEmail) {
                applyName.value = user.name;
                applyEmail.value = user.email;
            }

            // Fetch in-app notifications & 24h deadline reminders
            loadNotifications();
        }

        async function logout() {
            const token = state.token;
            state.token = '';
            state.currentUser = null;
            localStorage.removeItem('tf_token');
            localStorage.removeItem('tf_in_workspace');
            localStorage.removeItem('tf_active_tab');
            if (window.location.hash) {
                history.replaceState(null, '', window.location.pathname);
            }
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
            const res = await fetch(path, { ...options, headers });
            if (res.status === 401 && state.token) {
                // Token expired or invalid
                localStorage.removeItem('tf_token');
                localStorage.removeItem('tf_in_workspace');
                state.token = '';
                state.currentUser = null;
                showAuthGateway();
            }
            return res;
        }

        // Unified High-Speed Workspace Reload
        async function reloadAll() {
            try {
                const res = await api('/api/workspace/bootstrap');
                if (res.ok) {
                    const data = await res.json();

                    if (data.candidates) {
                        state.candidates = data.candidates;
                    }

                    if (data.user) {
                        state.currentUser = data.user;
                        if (!state.currentUser.candidate && state.candidates) {
                            state.currentUser.candidate = state.candidates.find(c => c.email === state.currentUser.email || c.user_id === state.currentUser.id);
                        }
                        try { updateRoleUI(); } catch (e) { console.error('Error updating role UI:', e); }
                    }

                    if (data.analytics) {
                        try { renderAnalytics(data.analytics); } catch (e) { console.error('Error rendering analytics:', e); }
                    }

                    if (data.applications) {
                        state.applications = data.applications;
                    }

                    if (data.jobs) {
                        state.jobs = data.jobs;
                        try { renderJobs(data.jobs); } catch (e) { console.error('Error rendering jobs:', e); }
                    }

                    // Workspace is populated - dismiss loading popup immediately!
                    hideLoading();

                    if (data.applications) {
                        try {
                            renderPipeline(data.applications);
                            populateSelects(data.applications);
                        } catch (e) { console.error('Error rendering pipeline:', e); }
                    }

                    if (data.interviews) {
                        state.interviews = data.interviews;
                        try { renderInterviews(data.interviews); } catch (e) { console.error('Error rendering interviews:', e); }
                    }

                    if (data.tasks) {
                        state.tasks = data.tasks;
                        try { renderTasks(data.tasks); } catch (e) { console.error('Error rendering tasks:', e); }
                    }

                    if (data.candidates) {
                        state.candidates = data.candidates;
                        try { renderCandidates(data.candidates); } catch (e) { console.error('Error rendering candidates:', e); }
                    }

                    if (data.recruiters) {
                        state.recruiters = data.recruiters;
                        try { renderRecruiters(data.recruiters); } catch (e) { console.error('Error rendering recruiters:', e); }
                    }
                    return;
                }
            } catch (err) {
                console.warn('Bootstrap endpoint unavailable, falling back:', err);
            } finally {
                hideLoading();
            }

            // Fallback to individual requests if needed
            try {
                await Promise.all([
                    loadAnalytics(),
                    loadJobs(),
                    loadApplications(),
                    loadInterviews(),
                    loadTasks(),
                    loadCandidates(),
                    loadRecruiters()
                ]);
            } catch (err) {
                console.error('Error in fallback load:', err);
            } finally {
                hideLoading();
            }
        }

        function renderAnalytics(analytics) {
            if (!analytics) return;
            const sj = document.getElementById('statJobs');
            const saj = document.getElementById('statActiveJobs');
            const sc = document.getElementById('statCandidates');
            const si = document.getElementById('statInterviews');
            const sa = document.getElementById('statAvgScore');
            const cardScore = document.getElementById('cardStatAvgScore');

            const isCandidate = state.currentUser?.role?.name === 'candidate';

            if (sj) sj.textContent = analytics.total_jobs ?? 0;
            if (saj) saj.textContent = `${analytics.active_jobs ?? 0} open positions`;

            if (isCandidate) {
                // For candidate: "Tasks This Week"
                const myTasks = state.tasks || [];
                const activeTasksCount = analytics.tasks_this_week !== undefined
                    ? analytics.tasks_this_week
                    : myTasks.filter(t => t.status === 'Pending' || t.status === 'In Progress').length;
                if (sc) sc.textContent = activeTasksCount;
                if (cardScore) cardScore.style.display = 'none';
            } else {
                if (sc) sc.textContent = analytics.active_candidates ?? 0;
                if (sa) sa.textContent = `${analytics.average_candidate_score ?? 0}%`;
                if (cardScore) cardScore.style.display = 'block';
            }

            if (si) si.textContent = analytics.interviews_this_week ?? 0;

            const container = document.getElementById('funnelContainer');
            if (!container) return;
            container.innerHTML = '';
            const total = Math.max(1, analytics.total_applications || 1);

            if (analytics.pipeline_distribution) {
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
        }

        // 1. Analytics (Individual Fallback)
        async function loadAnalytics() {
            try {
                const res = await api('/api/dashboard/analytics');
                if (res.ok) {
                    const { analytics } = await res.json();
                    renderAnalytics(analytics);
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
            if (!container) return;
            container.innerHTML = '';

            const isCandidate = state.currentUser?.role?.name === 'candidate';
            const isRecruiter = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';
            const isAdmin = state.currentUser?.role?.name === 'admin';
            const isRecruiterOnly = state.currentUser?.role?.name === 'recruiter';
            const currentUserId = state.currentUser?.id;
            const userApplications = Array.isArray(state.applications) ? state.applications : [];

            // A recruiter only sees jobs posted by their specific account
            let displayJobs = jobs;
            if (isRecruiterOnly && currentUserId) {
                displayJobs = jobs.filter(j => {
                    const recId = j.recruiter_id ?? j.recruiter?.id;
                    return recId === currentUserId;
                });
            }

            if (!Array.isArray(displayJobs) || displayJobs.length === 0) {
                container.innerHTML = '<div style="color:var(--text-muted); padding:20px;">No job openings found.</div>';
                return;
            }

            let candidateHasResume = true;
            if (isCandidate) {
                const user = state.currentUser;
                const cand = (state.candidates || []).find(c => c.email === user.email || c.name === user.name) || user.candidate || {};
                candidateHasResume = !!(cand.latest_resume || (cand.resumes && cand.resumes.length > 0));
            }

            const alertContainer = document.getElementById('jobsResumeAlert');
            if (alertContainer) {
                if (isCandidate && !candidateHasResume) {
                    alertContainer.innerHTML = `
                        <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:40px; height:40px; border-radius:50%; background:#fef3c7; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">📄</div>
                                <div>
                                    <div style="font-weight:800; color:#92400e; font-size:0.95rem;">Resume Required to Apply</div>
                                    <div style="color:#b45309; font-size:0.85rem;">You haven't uploaded a resume yet. <strong>Please go to Profile Settings to add your resume to apply. Without a resume, no jobs can be applied!</strong></div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-primary" onclick="openCurrentUserProfile(true)" style="background:#d97706; border-color:#d97706; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
                                <span>⚙️</span> Add Resume in Profile Settings
                            </button>
                        </div>
                    `;
                } else {
                    alertContainer.innerHTML = '';
                }
            }

            displayJobs.forEach(job => {
                let skillsHtml = '';
                let skillsArr = [];
                if (Array.isArray(job.skills)) {
                    skillsArr = job.skills;
                } else if (typeof job.skills === 'string') {
                    try { skillsArr = JSON.parse(job.skills); } catch(e) { skillsArr = job.skills.split(',').map(s=>({name: s.trim()})); }
                }

                if (Array.isArray(skillsArr)) {
                    skillsArr.forEach(s => {
                        const name = typeof s === 'string' ? s : (s.name || '');
                        const isMandatory = typeof s === 'object' && s.is_mandatory;
                        if (name) {
                            skillsHtml += `<span class="skill-tag ${isMandatory ? 'mandatory' : ''}">${escapeHtml(name)}</span>`;
                        }
                    });
                }

                // Check if candidate already applied for this job
                const appliedApp = isCandidate
                    ? userApplications.find(a => (a.job_id === job.id || (a.job && a.job.id === job.id)))
                    : null;

                let actionButtonsHtml = '';
                if (isCandidate) {
                    if (appliedApp) {
                        actionButtonsHtml = `
                            <div class="job-actions-row candidate">
                                <button class="btn btn-outline btn-sm" onclick="viewJob(${job.id})" title="View Details">View Details</button>
                                <button class="btn btn-sm btn-applied" onclick="switchTab('pipeline')" title="Applied (${escapeHtml(appliedApp.status || 'Applied')}) - Click to view in Pipeline" style="background:#ecfdf5; color:#059669; border:1px solid #10b981; font-weight:700; border-radius:var(--radius-sm); cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                                    <span style="font-size:1.15em; font-weight:800; line-height:1;">✓</span> Applied
                                </button>
                            </div>
                        `;
                    } else {
                        actionButtonsHtml = `
                            <div class="job-actions-row candidate">
                                <button class="btn btn-outline btn-sm" onclick="viewJob(${job.id})" title="View Details">View Details</button>
                                <button class="btn btn-primary btn-sm" onclick="openApply(${job.id}, '${escapeJs(job.title)}')">Apply Now</button>
                            </div>
                        `;
                    }
                } else if (isRecruiter) {
                    const isOwner = (job.recruiter_id === currentUserId || job.recruiter?.id === currentUserId);
                    const canEditDelete = isAdmin || isOwner;

                    actionButtonsHtml = `
                        <div class="job-actions-row recruiter">
                            <button class="btn btn-outline btn-sm" onclick="viewJob(${job.id})" title="View Job Details">View</button>
                            ${canEditDelete ? `
                                <button class="btn btn-outline btn-sm" onclick="openEditJob(${job.id})" style="color:var(--primary); border-color:var(--primary);" title="Edit Job Opening">Edit</button>
                                <button class="btn btn-outline btn-sm" onclick="deleteJob(${job.id}, '${escapeJs(job.title)}')" style="color:var(--danger); border-color:var(--danger);" title="Delete Job Opening">Delete</button>
                            ` : ''}
                            <button class="btn btn-primary btn-sm" onclick="switchTab('pipeline')" title="View applicants in pipeline">Pipeline</button>
                        </div>
                    `;
                } else {
                    actionButtonsHtml = `
                        <div class="job-actions-row candidate">
                            <button class="btn btn-outline btn-sm" onclick="viewJob(${job.id})" title="View Details">View Details</button>
                            <button class="btn btn-primary btn-sm" onclick="openApply(${job.id}, '${escapeJs(job.title)}')">Apply Now</button>
                        </div>
                    `;
                }

                const deadlineText = job.application_deadline ? `📅 ${job.application_deadline.substring(0, 10)}` : '';

                const card = document.createElement('div');
                card.className = 'job-card';
                card.innerHTML = `
                    <div>
                        <div class="job-header">
                            <div>
                                <h4 class="job-title" onclick="viewJob(${job.id})" style="cursor:pointer;" title="Click to view details">${escapeHtml(job.title || 'Job Opening')}</h4>
                                <div class="job-dept">${escapeHtml(job.department || '')} • ${escapeHtml(job.experience || '')}</div>
                            </div>
                            <span class="badge badge-${job.status}">${job.status}</span>
                        </div>
                        <p class="job-desc">${escapeHtml(job.description || '')}</p>
                        <div class="skills-list">${skillsHtml}</div>
                    </div>
                    <div class="job-footer">
                        <div class="job-footer-meta">
                            <span class="job-salary">${escapeHtml(job.salary_range || 'Competitive')}</span>
                            ${deadlineText ? `<span class="job-deadline-badge">${escapeHtml(deadlineText)}</span>` : ''}
                        </div>
                        ${actionButtonsHtml}
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function filterJobs() {
            const search = document.getElementById('jobSearch').value.toLowerCase();
            const status = document.getElementById('jobStatus').value;
            const isRecruiterOnly = state.currentUser?.role?.name === 'recruiter';
            const currentUserId = state.currentUser?.id;

            let baseJobs = state.jobs || [];
            if (isRecruiterOnly && currentUserId) {
                baseJobs = baseJobs.filter(j => (j.recruiter_id === currentUserId || j.recruiter?.id === currentUserId));
            }

            const filtered = baseJobs.filter(j => {
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
                    if (state.jobs && state.jobs.length > 0) {
                        renderJobs(state.jobs);
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }

        function getInitials(name) {
            if (!name) return 'C';
            const parts = name.trim().split(' ');
            if (parts.length >= 2) return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            return name.substring(0, 2).toUpperCase();
        }

        let pipelineState = {
            activeStage: 'All',
            viewMode: 'list'
        };

        function setPipelineViewMode(mode) {
            pipelineState.viewMode = mode;
            const btnList = document.getElementById('btnViewList');
            const btnBoard = document.getElementById('btnViewBoard');
            const containerList = document.getElementById('pipelineContainerList');
            const containerBoard = document.getElementById('pipelineBoard');

            if (mode === 'list') {
                if (btnList) btnList.classList.add('active');
                if (btnBoard) btnBoard.classList.remove('active');
                if (containerList) containerList.style.display = 'flex';
                if (containerBoard) containerBoard.style.display = 'none';
            } else {
                if (btnBoard) btnBoard.classList.add('active');
                if (btnList) btnList.classList.remove('active');
                if (containerBoard) containerBoard.style.display = 'flex';
                if (containerList) containerList.style.display = 'none';
            }
        }

        function setPipelineStage(stage) {
            pipelineState.activeStage = stage;
            renderPipelineSidebar(state.applications || []);
            filterPipelineView();
        }

        function renderPipelineSidebar(apps) {
            const stages = ['All', 'Applied', 'Screening', 'Shortlisted', 'Interview', 'Technical Task', 'Hired', 'Rejected'];
            const sidebar = document.getElementById('pipelineStageList');
            if (!sidebar) return;
            sidebar.innerHTML = '';

            stages.forEach(st => {
                const count = st === 'All' ? apps.length : apps.filter(a => a.status === st).length;
                const li = document.createElement('li');
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `sidebar-stage-btn ${pipelineState.activeStage === st ? 'active' : ''}`;
                btn.onclick = () => setPipelineStage(st);

                btn.innerHTML = `
                    <div class="sidebar-stage-label">
                        <span>${st}</span>
                    </div>
                    <span class="sidebar-stage-count">${count}</span>
                `;
                li.appendChild(btn);
                sidebar.appendChild(li);
            });
        }

        function filterPipelineView() {
            const search = (document.getElementById('pipelineSearch')?.value || '').toLowerCase();
            const sort = document.getElementById('pipelineSort')?.value || 'score_desc';

            let filtered = (state.applications || []).filter(a => {
                const matchStage = pipelineState.activeStage === 'All' || a.status === pipelineState.activeStage;
                const name = (a.candidate?.name || '').toLowerCase();
                const role = (a.job?.title || '').toLowerCase();
                const skills = (a.candidate?.skills_summary || '').toLowerCase();
                const matchSearch = !search || name.includes(search) || role.includes(search) || skills.includes(search);
                return matchStage && matchSearch;
            });

            if (sort === 'score_desc') {
                filtered.sort((x, y) => (y.skill_score || 0) - (x.skill_score || 0));
            } else if (sort === 'exp_desc') {
                filtered.sort((x, y) => (y.candidate?.experience_years || 0) - (x.candidate?.experience_years || 0));
            } else if (sort === 'recent') {
                filtered.sort((x, y) => new Date(y.created_at || Date.now()) - new Date(x.created_at || Date.now()));
            } else if (sort === 'name_asc') {
                filtered.sort((x, y) => (x.candidate?.name || '').localeCompare(y.candidate?.name || ''));
            }

            renderPipelineList(filtered);
        }

        function renderPipelineList(apps) {
            const listContainer = document.getElementById('pipelineContainerList');
            if (!listContainer) return;
            listContainer.innerHTML = '';

            const isRecruiterOrAdmin = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';

            if (apps.length === 0) {
                listContainer.innerHTML = `
                    <div class="empty-pipeline-notice">
                        <h4 style="font-size:1.05rem; font-weight:700; color:var(--text-dark);">No applicants found</h4>
                        <p style="font-size:0.85rem; color:var(--text-muted);">There are no candidate applications in "${pipelineState.activeStage}" stage matching your filter.</p>
                    </div>
                `;
                return;
            }

            const stageConfig = {
                'Applied': { color: '#64748b' },
                'Screening': { color: '#0284c7' },
                'Shortlisted': { color: '#d97706' },
                'Interview': { color: '#7c3aed' },
                'Technical Task': { color: '#4f46e5' },
                'Hired': { color: '#059669' },
                'Rejected': { color: '#e11d48' }
            };

            apps.forEach(a => {
                const score = Math.round(a.skill_score || 0);
                const stageColor = stageConfig[a.status]?.color || '#4f46e5';

                const card = document.createElement('div');
                card.className = 'candidate-card-row';
                card.onclick = () => openCandidateDetail(a.id);

                if (isRecruiterOrAdmin) {
                    const initials = getInitials(a.candidate?.name);
                    card.innerHTML = `
                        <div class="candidate-info-group">
                            <div class="candidate-large-avatar" style="background:${stageColor};">${initials}</div>
                            <div class="candidate-text-details">
                                <div class="candidate-row-name">
                                    <span>${escapeHtml(a.candidate?.name || 'Candidate')}</span>
                                    <span class="badge badge-${a.status.toLowerCase().replace(' ', '_')}">${a.status}</span>
                                </div>
                                <div class="candidate-row-role">
                                    <span>Role: <strong>${escapeHtml(a.job?.title || 'Position')}</strong></span>
                                    <span>•</span>
                                    <span>Applied ${new Date(a.created_at || Date.now()).toLocaleDateString()}</span>
                                </div>
                                <div class="candidate-meta-chips">
                                    <div class="match-bar-container" title="Resume match score for required job skills">
                                        <span style="font-size:0.75rem; font-weight:700; color:${score >= 80 ? 'var(--success)' : 'var(--primary)'};">${score}% match</span>
                                        <div class="match-mini-track">
                                            <div class="match-mini-fill" style="width:${Math.max(6, score)}%; background:${score >= 80 ? 'var(--success)' : 'var(--primary)'};"></div>
                                        </div>
                                    </div>
                                    <span class="exp-badge">${a.candidate?.experience_years || 0} yrs exp</span>
                                    ${a.candidate?.education ? `<span class="exp-badge">${escapeHtml(a.candidate.education)}</span>` : ''}
                                    ${a.candidate?.skills_summary ? `<span class="exp-badge" style="max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">Skills: ${escapeHtml(a.candidate.skills_summary)}</span>` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="candidate-actions-group" onclick="event.stopPropagation();">
                            <button class="btn btn-outline btn-sm" onclick="openMove(${a.id}, '${escapeHtml(a.candidate?.name || 'Applicant')}', '${a.status}')">Move Stage</button>
                            <button class="btn btn-primary btn-sm" onclick="openCandidateDetail(${a.id})">View Profile</button>
                        </div>
                    `;
                } else {
                    const companyName = a.job?.recruiter?.name || 'Hiring Company';
                    const companyInitials = getInitials(companyName);
                    card.innerHTML = `
                        <div class="candidate-info-group">
                            <div class="candidate-large-avatar" style="background:${stageColor}; font-size:1.1rem;" title="${escapeHtml(companyName)}">${companyInitials}</div>
                            <div class="candidate-text-details">
                                <div class="candidate-row-name">
                                    <span>${escapeHtml(a.job?.title || 'Position')}</span>
                                    <span class="badge badge-${a.status.toLowerCase().replace(' ', '_')}">${a.status}</span>
                                </div>
                                <div class="candidate-row-role">
                                    <span>🏢 Company: <strong>${escapeHtml(companyName)}</strong></span>
                                    <span>•</span>
                                    <span>Dept: ${escapeHtml(a.job?.department || 'General')}</span>
                                    <span>•</span>
                                    <span>Applied ${new Date(a.created_at || Date.now()).toLocaleDateString()}</span>
                                </div>
                                <div class="candidate-meta-chips">
                                    <div class="match-bar-container" title="Your skill match score for this job">
                                        <span style="font-size:0.75rem; font-weight:700; color:${score >= 80 ? 'var(--success)' : 'var(--primary)'};">${score}% match</span>
                                        <div class="match-mini-track">
                                            <div class="match-mini-fill" style="width:${Math.max(6, score)}%; background:${score >= 80 ? 'var(--success)' : 'var(--primary)'};"></div>
                                        </div>
                                    </div>
                                    <span class="exp-badge">Required: ${escapeHtml(a.job?.experience || 'Not specified')}</span>
                                    ${a.job?.salary_range ? `<span class="exp-badge">💰 ${escapeHtml(a.job.salary_range)}</span>` : ''}
                                    ${a.job?.application_deadline ? `<span class="exp-badge">Deadline: ${new Date(a.job.application_deadline).toLocaleDateString()}</span>` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="candidate-actions-group" onclick="event.stopPropagation();">
                            <button class="btn btn-primary btn-sm" onclick="openCandidateDetail(${a.id})">View Job & Company</button>
                        </div>
                    `;
                }
                listContainer.appendChild(card);
            });
        }

        function renderPipelineBoard(apps) {
            // Board view has been removed per user preference
        }

        function renderPipeline(apps) {
            renderPipelineSidebar(apps);
            filterPipelineView();
        }

        function openCandidateDetail(appId) {
            const app = (state.applications || []).find(a => a.id === appId);
            if (!app) return;

            const isRecruiterOrAdmin = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';
            const nameEl = document.getElementById('candidateDetailName');
            const bodyEl = document.getElementById('candidateDetailBody');
            const score = Math.round(app.skill_score || 0);
            const resume = app.resume || app.candidate?.latest_resume || (app.candidate?.resumes && app.candidate.resumes[0]);

            const resumeHtml = `
                <div style="margin-bottom:20px; background:#f8fafc; padding:14px; border-radius:10px; border:1px solid var(--border);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">
                            ${isRecruiterOrAdmin ? 'Candidate Resume' : 'Your Submitted Resume'}
                        </div>
                        ${resume ? `<span class="badge badge-info" style="font-size:0.72rem; padding:2px 8px;">${resume.status ? resume.status.toUpperCase() : 'PDF'}</span>` : ''}
                    </div>
                    ${resume ? `
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; background:#fff; border:1px solid var(--border); border-radius:8px; padding:12px 14px;">
                            <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                                <span style="font-size:1.8rem; line-height:1;">📄</span>
                                <div style="min-width:0;">
                                    <div style="font-weight:700; font-size:0.92rem; color:var(--text-dark); text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">${escapeHtml(resume.file_name || 'Resume.pdf')}</div>
                                    <div style="font-size:0.78rem; color:var(--text-muted);">PDF Document ${resume.file_size ? '• ' + formatFileSize(resume.file_size) : ''}</div>
                                </div>
                            </div>
                            <div style="display:flex; gap:8px; flex-shrink:0;">
                                <button type="button" class="btn btn-outline btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;" onclick="viewResume(${resume.id})">
                                    <span>👁️</span> <span>View PDF</span>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;" onclick="downloadResume(${resume.id}, '${escapeJs(resume.file_name || 'Resume.pdf')}')">
                                    <span>⬇️</span> <span>Download</span>
                                </button>
                            </div>
                        </div>
                    ` : `
                        <div style="font-size:0.85rem; color:var(--text-muted); font-style:italic; padding:4px 0;">
                            No resume attached to this application.
                        </div>
                    `}
                </div>
            `;

            if (!isRecruiterOrAdmin) {
                // CANDIDATE VIEW: Display Company, Job Description, Status Stage details & submitted resume
                const companyName = app.job?.recruiter?.name || 'TalentFlow Hiring Company';
                const companyInitials = getInitials(companyName);

                if (nameEl) nameEl.textContent = `${app.job?.title || 'Job'} – Company & Application Details`;

                const stageMessages = {
                    'Applied': 'Your application has been received and is waiting for recruiter review.',
                    'Screening': 'Your resume and qualifications are currently being reviewed by the hiring team.',
                    'Shortlisted': 'Great news! Your profile has been shortlisted for this position.',
                    'Interview': 'You have advanced to the Interview stage! An interview has been scheduled or will be set up soon. Check your Interviews tab.',
                    'Technical Task': 'A technical assessment has been assigned to you. Head over to the Tasks tab to view instructions and submit your work.',
                    'Hired': '🎉 Congratulations! You have received a job offer and been hired for this role!',
                    'Rejected': 'Thank you for your interest. The company has decided to proceed with other candidates at this time.'
                };
                const statusNotice = stageMessages[app.status] || 'Your application is currently active in the hiring pipeline.';

                let skillsHtml = '';
                if (app.job?.skills && app.job.skills.length > 0) {
                    skillsHtml = `
                        <div style="margin-bottom:20px;">
                            <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Required Job Skills</h5>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                ${app.job.skills.map(s => `
                                    <span style="background:#f1f5f9; border:1px solid #cbd5e1; border-radius:6px; padding:4px 10px; font-size:0.82rem; font-weight:600; color:#334155; display:inline-flex; align-items:center; gap:6px;">
                                        ${escapeHtml(s.name)}
                                        ${s.pivot?.is_mandatory ? '<span style="color:#dc2626; font-size:0.7rem; font-weight:700;">*Required</span>' : '<span style="color:#64748b; font-size:0.7rem;">Optional</span>'}
                                    </span>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                if (bodyEl) {
                    bodyEl.innerHTML = `
                        <div style="display:flex; gap:16px; align-items:center; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                            <div class="candidate-large-avatar" style="background:var(--primary); width:56px; height:56px; font-size:1.3rem;">${companyInitials}</div>
                            <div style="flex:1;">
                                <h4 style="font-size:1.25rem; font-weight:800; color:var(--text-dark); margin-bottom:4px;">${escapeHtml(app.job?.title || 'Job Position')}</h4>
                                <div style="font-size:0.88rem; color:var(--text-dark); font-weight:600;">
                                    🏢 <strong>${escapeHtml(companyName)}</strong>
                                    ${app.job?.department ? `<span style="font-weight:400; color:var(--text-muted);"> • Dept: ${escapeHtml(app.job.department)}</span>` : ''}
                                </div>
                                ${app.job?.recruiter?.email ? `
                                    <div style="font-size:0.82rem; color:var(--text-muted); margin-top:2px;">
                                        ✉️ Recruiter Contact: <a href="mailto:${escapeHtml(app.job.recruiter.email)}" style="color:var(--primary); text-decoration:none;">${escapeHtml(app.job.recruiter.email)}</a>
                                        ${app.job?.recruiter?.phone ? ` • 📞 ${escapeHtml(app.job.recruiter.phone)}` : ''}
                                    </div>
                                ` : ''}
                            </div>
                            <span class="badge badge-${app.status.toLowerCase().replace(' ', '_')}" style="font-size:0.82rem; padding:4px 12px;">${app.status}</span>
                        </div>

                        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
                            <span style="font-size:1.3rem;">ℹ️</span>
                            <div style="flex:1; font-size:0.86rem; color:#1e40af; line-height:1.4;">
                                <strong>Application Stage: ${app.status}</strong><br>
                                ${statusNotice}
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; margin-bottom:20px;">
                            <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Experience Req.</div>
                                <div style="font-size:0.95rem; font-weight:700; color:var(--text-dark); margin-top:2px;">${escapeHtml(app.job?.experience || 'Not specified')}</div>
                            </div>
                            <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Salary Range</div>
                                <div style="font-size:0.95rem; font-weight:700; color:var(--text-dark); margin-top:2px;">${escapeHtml(app.job?.salary_range || 'Competitive')}</div>
                            </div>
                            <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Skill Match</div>
                                <div style="font-size:1.1rem; font-weight:800; color:${score >= 80 ? 'var(--success)' : 'var(--primary)'}; margin-top:2px;">${score}% Match</div>
                            </div>
                        </div>

                        <div style="margin-bottom:20px;">
                            <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Job Description</h5>
                            <div style="background:#f8fafc; padding:14px; border-radius:8px; border:1px solid var(--border); font-size:0.88rem; color:var(--text-dark); line-height:1.6; max-height:220px; overflow-y:auto; white-space:pre-line;">
                                ${escapeHtml(app.job?.description || 'No job description provided by the company.')}
                            </div>
                        </div>

                        ${skillsHtml}

                        ${resumeHtml}

                        <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:16px;">
                            <div style="font-size:0.8rem; color:var(--text-muted);">
                                Applied on ${new Date(app.created_at || Date.now()).toLocaleDateString()}
                            </div>
                            <div style="display:flex; gap:10px;">
                                ${app.status === 'Interview' ? `
                                    <button class="btn btn-primary" onclick="closeModal('modalCandidateDetail'); switchTab('interviews');">Go to Interviews 📅</button>
                                ` : ''}
                                ${app.status === 'Technical Task' ? `
                                    <button class="btn btn-primary" onclick="closeModal('modalCandidateDetail'); switchTab('tasks');">Go to Tasks 💻</button>
                                ` : ''}
                                <button class="btn btn-outline" onclick="closeModal('modalCandidateDetail')">Close</button>
                            </div>
                        </div>
                    `;
                }
            } else {
                // RECRUITER / ADMIN VIEW: Display Candidate Profile Details
                const initials = getInitials(app.candidate?.name);
                if (nameEl) nameEl.textContent = `${app.candidate?.name || 'Candidate'} – Profile Details`;

                if (bodyEl) {
                    bodyEl.innerHTML = `
                        <div style="display:flex; gap:16px; align-items:center; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                            <div class="candidate-large-avatar" style="background:var(--primary); width:56px; height:56px; font-size:1.3rem;">${initials}</div>
                            <div style="flex:1;">
                                <h4 style="font-size:1.2rem; font-weight:800; color:var(--text-dark); margin-bottom:2px;">${escapeHtml(app.candidate?.name || 'Applicant')}</h4>
                                <div style="font-size:0.86rem; color:var(--text-muted);">Email: ${escapeHtml(app.candidate?.email || 'N/A')} • Phone: ${escapeHtml(app.candidate?.phone || 'N/A')}</div>
                            </div>
                            <span class="badge badge-${app.status.toLowerCase().replace(' ', '_')}" style="font-size:0.82rem; padding:4px 12px;">${app.status}</span>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px;">
                            <div style="background:#f8fafc; padding:14px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Applied Position</div>
                                <div style="font-size:1rem; font-weight:700; color:var(--text-dark); margin-top:4px;">${escapeHtml(app.job?.title || 'Job Position')}</div>
                                <div style="font-size:0.8rem; color:var(--text-muted);">${escapeHtml(app.job?.department || '')}</div>
                            </div>
                            <div style="background:#f8fafc; padding:14px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Match Score</div>
                                <div style="font-size:1.3rem; font-weight:800; color:${score >= 80 ? 'var(--success)' : 'var(--primary)'}; margin-top:2px;">${score}% Match</div>
                                <div style="font-size:0.78rem; color:var(--text-muted);">Automated resume skill parser</div>
                            </div>
                        </div>

                        <div style="margin-bottom:18px;">
                            <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Experience & Education</h5>
                            <div style="font-size:0.88rem; color:var(--text-muted); line-height:1.5;">
                                • <strong>Work Experience:</strong> ${app.candidate?.experience_years || 0} Years<br>
                                • <strong>Education:</strong> ${escapeHtml(app.candidate?.education || 'Not specified')}
                            </div>
                        </div>

                        ${resumeHtml}

                        <div style="margin-bottom:22px;">
                            <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Extracted Skills Summary</h5>
                            <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border); font-size:0.85rem; color:var(--text-dark);">
                                ${escapeHtml(app.candidate?.skills_summary || 'No skills extracted')}
                            </div>
                        </div>

                        <div style="display:flex; justify-content:flex-end; gap:10px; border-top:1px solid var(--border); padding-top:16px;">
                            <button class="btn btn-outline" onclick="closeModal('modalCandidateDetail')">Close</button>
                            <button class="btn btn-primary" onclick="closeModal('modalCandidateDetail'); openMove(${app.id}, '${escapeHtml(app.candidate?.name || 'Applicant')}', '${app.status}')">Move Stage</button>
                        </div>
                    `;
                }
            }

            openModal('modalCandidateDetail');
        }

        function openCurrentUserProfile(isEditMode = false) {
            const user = state.currentUser;
            if (!user) return;

            const titleEl = document.getElementById('userProfileTitle');
            const bodyEl = document.getElementById('userProfileBody');
            if (titleEl) titleEl.textContent = isEditMode ? 'Edit Profile' : `${user.name} – Profile Details`;

            const cand = (state.candidates || []).find(c => c.email === user.email || c.name === user.name) || user.candidate || {};
            const latestResume = cand.latest_resume || (cand.resumes && cand.resumes[0]);

            const initials = getInitials(user.name);

            if (isEditMode) {
                if (bodyEl) {
                    bodyEl.innerHTML = `
                        <form onsubmit="saveCurrentUserProfile(event)">
                            <div style="display:flex; gap:16px; align-items:center; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                                <div class="user-avatar-circle" style="width:56px; height:56px; font-size:1.3rem;">${initials}</div>
                                <div style="flex:1;">
                                    <label class="form-label" style="margin-bottom:4px; font-weight:700;">Full Name</label>
                                    <input type="text" id="editProfName" class="input-text" value="${escapeHtml(user.name)}" required>
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                                <div>
                                    <label class="form-label" style="font-weight:700; margin-bottom:4px;">Phone Number</label>
                                    <input type="text" id="editProfPhone" class="input-text" value="${escapeHtml(cand.phone || user.phone || '')}" placeholder="+1 555-0192">
                                </div>
                                <div>
                                    <label class="form-label" style="font-weight:700; margin-bottom:4px;">Experience (Years)</label>
                                    <input type="number" min="0" max="50" id="editProfExp" class="input-text" value="${cand.experience_years || 0}">
                                </div>
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="form-label" style="font-weight:700; margin-bottom:4px;">Education</label>
                                <input type="text" id="editProfEdu" class="input-text" value="${escapeHtml(cand.education || '')}" placeholder="Degree, University">
                            </div>

                            <div style="margin-bottom:16px; background:${latestResume ? '#f8fafc' : '#fffbeb'}; padding:14px; border-radius:10px; border:1px solid ${latestResume ? 'var(--border)' : '#fde68a'};">
                                <label class="form-label" style="font-weight:700; margin-bottom:4px; color:${latestResume ? 'var(--text-dark)' : '#92400e'};">
                                    Upload / Update Profile Resume (PDF) ${latestResume ? '' : '<span style="color:#dc2626;">* (Required to apply)</span>'}
                                </label>
                                <input type="file" id="editProfResume" class="input-text" accept="application/pdf">
                                ${latestResume ? `<div style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">Currently saved: <strong>${escapeHtml(latestResume.file_name)}</strong></div>` : `<div style="font-size:0.8rem; color:#b45309; margin-top:4px; font-weight:600;">⚠️ Without a resume, no jobs can be applied. Please upload a PDF resume here and click <strong>Save Changes</strong>.</div>`}
                            </div>

                            <div style="margin-bottom:20px;">
                                <label class="form-label" style="font-weight:700; margin-bottom:4px;">Extracted Technical Skills</label>
                                <textarea id="editProfSkills" class="input-text" rows="3" placeholder="e.g. PHP, Laravel, React, SQL">${escapeHtml(cand.skills_summary || '')}</textarea>
                            </div>

                            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:16px;">
                                <button type="button" class="btn btn-outline" onclick="openCurrentUserProfile(false)">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    `;
                }
            } else {
                if (bodyEl) {
                    bodyEl.innerHTML = `
                        <div style="display:flex; gap:16px; align-items:center; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                            <div class="user-avatar-circle" style="width:56px; height:56px; font-size:1.3rem;">${initials}</div>
                            <div style="flex:1;">
                                <h4 style="font-size:1.25rem; font-weight:800; color:var(--text-dark); margin-bottom:2px;">${escapeHtml(user.name)}</h4>
                                <div style="font-size:0.88rem; color:var(--text-muted);">Email: ${escapeHtml(user.email || 'N/A')} ${(cand.phone || user.phone) ? '• Phone: ' + escapeHtml(cand.phone || user.phone) : ''}</div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                            <div style="background:#f8fafc; padding:14px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Experience</div>
                                <div style="font-size:1.1rem; font-weight:700; color:var(--text-dark); margin-top:4px;">${cand.experience_years || 0} Years</div>
                            </div>
                            <div style="background:#f8fafc; padding:14px; border-radius:10px; border:1px solid var(--border);">
                                <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Education</div>
                                <div style="font-size:0.95rem; font-weight:700; color:var(--text-dark); margin-top:4px;">${escapeHtml(cand.education || 'Not specified')}</div>
                            </div>
                        </div>

                        <div style="margin-bottom:16px; background:${latestResume ? '#f8fafc' : '#fef2f2'}; padding:14px; border-radius:10px; border:1px solid ${latestResume ? 'var(--border)' : '#fecaca'};">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="font-size:0.75rem; font-weight:700; color:${latestResume ? 'var(--text-muted)' : '#dc2626'}; text-transform:uppercase;">Profile Resume</div>
                                ${!latestResume ? '<span class="status-badge" style="background:#fee2e2; color:#dc2626; font-size:0.7rem; font-weight:700;">Action Required</span>' : `<span style="font-size:0.75rem; color:var(--text-muted);">${formatFileSize(latestResume.file_size)}</span>`}
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px; flex-wrap:wrap; gap:8px;">
                                <div style="font-size:0.95rem; font-weight:700; color:var(--text-dark);">
                                    ${latestResume ? `📄 ${escapeHtml(latestResume.file_name)}` : '<span style="color:#dc2626; font-weight:700;">No resume uploaded yet</span>'}
                                </div>
                                ${latestResume ? `
                                    <div style="display:flex; gap:6px;">
                                        <button type="button" class="btn btn-outline btn-sm" style="padding:4px 8px; font-size:0.78rem;" onclick="viewResume(${latestResume.id})">👁️ View</button>
                                        <button type="button" class="btn btn-outline btn-sm" style="padding:4px 8px; font-size:0.78rem;" onclick="downloadResume(${latestResume.id}, '${escapeJs(latestResume.file_name)}')">⬇️ Download</button>
                                    </div>
                                ` : ''}
                            </div>
                            <div style="font-size:0.8rem; color:${latestResume ? 'var(--text-muted)' : '#b91c1c'}; margin-top:6px; line-height:1.4;">
                                ${latestResume ? 'Automatically sent to recruiter when applying for jobs. You can upload/update your PDF resume in Edit Profile.' : '⚠️ <strong>Without a resume, no jobs can be applied.</strong> Please click <strong>Edit Profile</strong> below to upload your resume.'}
                            </div>
                        </div>

                        <div style="margin-bottom:22px;">
                            <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Extracted Technical Skills</h5>
                            <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border); font-size:0.85rem; color:var(--text-dark);">
                                ${escapeHtml(cand.skills_summary || 'No skills profile uploaded yet')}
                            </div>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:16px;">
                            <button class="btn btn-outline" style="color:var(--danger);" onclick="closeModal('modalUserProfile'); logout();">Logout</button>
                            <div style="display:flex; gap:10px;">
                                <button class="btn btn-primary" onclick="openCurrentUserProfile(true)">Edit Profile</button>
                                <button class="btn btn-outline" onclick="closeModal('modalUserProfile')">Close</button>
                            </div>
                        </div>
                    `;
                }
            }

            openModal('modalUserProfile');
        }

        async function uploadProfileResume(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.type !== 'application/pdf') {
                showToast('Please select a PDF file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('resume', file);
            if (state.currentUser?.email) {
                formData.append('email', state.currentUser.email);
            }

            showToast('Uploading and parsing resume...', 'info');

            try {
                const res = await api('/api/resumes/upload', {
                    method: 'POST',
                    body: formData
                });

                if (res.ok) {
                    const d = await res.json();
                    if (d.candidate) {
                        const idx = (state.candidates || []).findIndex(c => c.id === d.candidate.id || c.email === d.candidate.email);
                        if (idx >= 0) state.candidates[idx] = d.candidate;
                        else { if (!state.candidates) state.candidates = []; state.candidates.push(d.candidate); }
                        if (state.currentUser) state.currentUser.candidate = d.candidate;
                    }
                    showToast('Resume uploaded & skills extracted successfully!', 'success');
                    openCurrentUserProfile(false);
                    await reloadAll();
                } else {
                    const d = await res.json();
                    showToast(d.message || 'Failed to upload resume', 'error');
                }
            } catch (err) {
                showToast('Error uploading resume: ' + err.message, 'error');
            }
        }

        async function saveCurrentUserProfile(e) {
            if (e) e.preventDefault();
            const name = document.getElementById('editProfName')?.value.trim();
            const phone = document.getElementById('editProfPhone')?.value.trim();
            const exp = parseFloat(document.getElementById('editProfExp')?.value || '0');
            const edu = document.getElementById('editProfEdu')?.value.trim();
            const skills = document.getElementById('editProfSkills')?.value.trim();
            const resumeFile = document.getElementById('editProfResume')?.files[0];

            if (!name) {
                showToast('Please enter a valid name', 'error');
                return;
            }

            showLoading('Saving Profile...', 'Saving changes...');

            try {
                if (resumeFile) {
                    if (resumeFile.type !== 'application/pdf') {
                        showToast('Resume must be a PDF file', 'error');
                        hideLoading();
                        return;
                    }
                    const formData = new FormData();
                    formData.append('resume', resumeFile);
                    if (state.currentUser?.email) formData.append('email', state.currentUser.email);
                    await api('/api/resumes/upload', { method: 'POST', body: formData });
                }

                const res = await api('/api/profile', {
                    method: 'PUT',
                    body: JSON.stringify({
                        name: name,
                        phone: phone,
                        experience_years: exp,
                        education: edu,
                        skills_summary: skills
                    })
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data.user) {
                        state.currentUser = data.user;
                        localStorage.setItem('tf_user', JSON.stringify(data.user));
                    }
                    if (data.candidate) {
                        const idx = (state.candidates || []).findIndex(c => c.id === data.candidate.id || c.email === data.candidate.email);
                        if (idx >= 0) state.candidates[idx] = data.candidate;
                        else { if (!state.candidates) state.candidates = []; state.candidates.push(data.candidate); }
                        if (state.currentUser) state.currentUser.candidate = data.candidate;
                    }
                    showToast('Profile updated & saved to database!', 'success');
                    openCurrentUserProfile(false);
                    await reloadAll();
                } else {
                    const d = await res.json();
                    showToast(d.message || 'Failed to update profile', 'error');
                }
            } catch (err) {
                showToast('Error updating profile: ' + err.message, 'error');
            } finally {
                hideLoading();
            }
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

                let attachmentsHtml = '';
                if (Array.isArray(t.attachments) && t.attachments.length > 0) {
                    attachmentsHtml = `<div style="display:flex; flex-wrap:wrap; gap:4px; margin-top:5px;">` +
                        t.attachments.map((att, idx) => {
                            const ext = (att.name.split('.').pop() || '').toLowerCase();
                            const isImg = ['jpg', 'jpeg', 'png', 'webp', 'svg'].includes(ext);
                            const isPdf = ext === 'pdf';
                            const icon = isImg ? '🖼️' : (isPdf ? '📄' : '📝');
                            return `<a href="/api/technical-tasks/${t.id}/attachments/${idx}" target="_blank" class="skill-tag" style="text-decoration:none; display:inline-flex; align-items:center; gap:4px; font-size:0.72rem; padding:2px 7px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:4px; color:#334155; font-weight:600;" title="Download ${escapeHtml(att.name)}">
                                <span>${icon}</span>
                                <span style="max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${escapeHtml(att.name)}</span>
                            </a>`;
                        }).join('') + `</div>`;
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <div style="font-weight:600; color:var(--text-dark);">${escapeHtml(t.title)}</div>
                        ${attachmentsHtml}
                    </td>
                    <td>${escapeHtml(t.assigned_by?.name || 'Candidate')}</td>
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

            if (!candidates || candidates.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px; color:var(--text-muted);">No candidates found.</td></tr>';
                return;
            }

            candidates.forEach(c => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:600; display:flex; align-items:center; gap:8px;">
                        <div class="user-avatar-circle" style="width:32px; height:32px; font-size:0.78rem;">${getInitials(c.name)}</div>
                        <span>${escapeHtml(c.name)}</span>
                    </td>
                    <td>${escapeHtml(c.email)}</td>
                    <td>${c.experience_years ?? 0} years</td>
                    <td>${escapeHtml(c.education || 'N/A')}</td>
                    <td style="color:var(--text-muted); font-size:0.82rem; max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${escapeHtml(c.skills_summary || 'N/A')}</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="openCandidateProfileModal(${c.id})">View</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function openCandidateProfileModal(candId) {
            let candidate = (state.candidates || []).find(c => c.id === candId);

            try {
                const res = await api(`/api/candidates/${candId}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data.candidate) candidate = data.candidate;
                }
            } catch (err) {}

            if (!candidate) {
                showToast('Candidate not found', 'error');
                return;
            }

            const titleEl = document.getElementById('candidateDetailName');
            const bodyEl = document.getElementById('candidateDetailBody');
            if (titleEl) titleEl.textContent = `${candidate.name} – Candidate Profile`;

            const apps = (candidate.applications || []).length > 0
                ? candidate.applications
                : (state.applications || []).filter(a => (a.candidate?.id === candidate.id || a.candidate_id === candidate.id));

            let appsHtml = '';
            if (apps.length > 0) {
                appsHtml = `
                    <div style="margin-top:16px;">
                        <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Job Applications (${apps.length})</h5>
                        <div style="display:flex; flex-direction:column; gap:8px; max-height:200px; overflow-y:auto;">
                            ${apps.map(a => `
                                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border:1px solid var(--border); border-radius:8px; padding:10px 14px;">
                                    <div>
                                        <div style="font-weight:700; color:var(--text-dark); font-size:0.92rem;">${escapeHtml(a.job?.title || 'Job Position')}</div>
                                        <div style="font-size:0.78rem; color:var(--text-muted);">${escapeHtml(a.job?.department || '')} • Match Score: <strong style="color:var(--primary);">${Math.round(a.skill_score || 0)}%</strong></div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="badge badge-${(a.status || 'applied').toLowerCase().replace(' ', '_')}">${a.status || 'Applied'}</span>
                                        ${(state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin') ? `
                                            <button class="btn btn-outline btn-sm" onclick="closeModal('modalCandidateDetail'); openMove(${a.id}, '${escapeHtml(candidate.name)}', '${a.status}')">Move Stage</button>
                                        ` : ''}
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                appsHtml = `
                    <div style="margin-top:16px;">
                        <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Job Applications</h5>
                        <div style="color:var(--text-muted); font-size:0.85rem; font-style:italic; background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border);">No job applications submitted yet.</div>
                    </div>
                `;
            }

            const resume = candidate.latest_resume || (candidate.resumes && candidate.resumes[0]);
            const resumeHtml = `
                <div style="margin-top:16px; margin-bottom:16px; background:#f8fafc; padding:14px; border-radius:10px; border:1px solid var(--border);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Candidate Resume</div>
                        ${resume ? `<span class="badge badge-info" style="font-size:0.72rem; padding:2px 8px;">${resume.status ? resume.status.toUpperCase() : 'PDF'}</span>` : ''}
                    </div>
                    ${resume ? `
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; background:#fff; border:1px solid var(--border); border-radius:8px; padding:12px 14px;">
                            <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                                <span style="font-size:1.8rem; line-height:1;">📄</span>
                                <div style="min-width:0;">
                                    <div style="font-weight:700; font-size:0.92rem; color:var(--text-dark); text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">${escapeHtml(resume.file_name || 'Resume.pdf')}</div>
                                    <div style="font-size:0.78rem; color:var(--text-muted);">PDF Document ${resume.file_size ? '• ' + formatFileSize(resume.file_size) : ''}</div>
                                </div>
                            </div>
                            <div style="display:flex; gap:8px; flex-shrink:0;">
                                <button type="button" class="btn btn-outline btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;" onclick="viewResume(${resume.id})">
                                    <span>👁️</span> <span>View PDF</span>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;" onclick="downloadResume(${resume.id}, '${escapeJs(resume.file_name || 'Resume.pdf')}')">
                                    <span>⬇️</span> <span>Download</span>
                                </button>
                            </div>
                        </div>
                    ` : `
                        <div style="font-size:0.85rem; color:var(--text-muted); font-style:italic; padding:4px 0;">No resume uploaded for this candidate.</div>
                    `}
                </div>
            `;

            if (bodyEl) {
                bodyEl.innerHTML = `
                    <div style="display:flex; gap:16px; align-items:center; margin-bottom:18px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                        <div class="candidate-large-avatar" style="background:var(--primary); width:54px; height:54px; font-size:1.3rem;">${getInitials(candidate.name)}</div>
                        <div style="flex:1;">
                            <h4 style="font-size:1.2rem; font-weight:800; color:var(--text-dark); margin-bottom:2px;">${escapeHtml(candidate.name)}</h4>
                            <div style="font-size:0.85rem; color:var(--text-muted);">
                                ✉️ ${escapeHtml(candidate.email || 'N/A')} • 📞 ${escapeHtml(candidate.phone || 'N/A')}
                            </div>
                        </div>
                        <span class="role-badge role-candidate" style="font-size:0.8rem; padding:4px 10px;">Candidate</span>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                        <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border);">
                            <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Experience</div>
                            <div style="font-size:1.1rem; font-weight:700; color:var(--text-dark); margin-top:2px;">${candidate.experience_years ?? 0} Years</div>
                        </div>
                        <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border);">
                            <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Education</div>
                            <div style="font-size:0.95rem; font-weight:600; color:var(--text-dark); margin-top:2px;">${escapeHtml(candidate.education || 'Not specified')}</div>
                        </div>
                    </div>

                    <div style="margin-bottom:16px;">
                        <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:6px;">Extracted Skills</h5>
                        <div style="background:#f8fafc; padding:10px 12px; border-radius:8px; border:1px solid var(--border); font-size:0.85rem; color:var(--text-dark); line-height:1.5;">
                            ${escapeHtml(candidate.skills_summary || 'No skills summary provided')}
                        </div>
                    </div>

                    ${resumeHtml}
                    ${appsHtml}

                    <div style="display:flex; justify-content:flex-end; gap:10px; border-top:1px solid var(--border); padding-top:16px; margin-top:20px;">
                        <button class="btn btn-outline" onclick="closeModal('modalCandidateDetail')">Close</button>
                    </div>
                `;
            }

            openModal('modalCandidateDetail');
        }

        // 7. Recruiters (Admin / Recruiter Directory)
        async function loadRecruiters() {
            try {
                const res = await api('/api/recruiters');
                if (res.ok) {
                    const { data } = await res.json();
                    state.recruiters = data;
                    renderRecruiters(data);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function renderRecruiters(recruiters) {
            const tbody = document.getElementById('recruitersTable');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (!recruiters || recruiters.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:20px; color:var(--text-muted);">No recruiters found.</td></tr>';
                return;
            }

            recruiters.forEach(r => {
                const tr = document.createElement('tr');
                const roleLabel = r.role?.label || (r.role?.name === 'admin' ? 'Administrator' : 'Recruiter');
                const roleBadgeClass = r.role?.name === 'admin' ? 'role-admin' : 'role-recruiter';

                tr.innerHTML = `
                    <td style="font-weight:600; display:flex; align-items:center; gap:8px;">
                        <div class="user-avatar-circle" style="width:32px; height:32px; font-size:0.78rem;">${getInitials(r.name)}</div>
                        <span>${escapeHtml(r.name)}</span>
                    </td>
                    <td>${escapeHtml(r.email)}</td>
                    <td>${escapeHtml(r.phone || 'N/A')}</td>
                    <td><span class="role-badge ${roleBadgeClass}">${escapeHtml(roleLabel)}</span></td>
                    <td><strong>${r.posted_jobs_count ?? 0}</strong> jobs</td>
                    <td><strong>${r.conducted_interviews_count ?? 0}</strong> sessions</td>
                    <td>
                        <button class="btn btn-outline btn-sm" onclick="openRecruiterDetailModal(${r.id})">View</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function openRecruiterDetailModal(id) {
            let recruiter = (state.recruiters || []).find(r => r.id === id);

            try {
                const res = await api(`/api/recruiters/${id}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data.recruiter) recruiter = data.recruiter;
                }
            } catch (e) {}

            if (!recruiter) {
                showToast('Recruiter profile not found', 'error');
                return;
            }

            const titleEl = document.getElementById('recruiterDetailTitle');
            const bodyEl = document.getElementById('recruiterDetailBody');
            if (titleEl) titleEl.textContent = `${recruiter.name} – Recruiter Profile`;

            const roleLabel = recruiter.role?.label || (recruiter.role?.name === 'admin' ? 'Administrator' : 'Recruiter');
            const roleBadgeClass = recruiter.role?.name === 'admin' ? 'role-admin' : 'role-recruiter';

            const postedJobs = recruiter.posted_jobs || (state.jobs || []).filter(j => j.recruiter?.id === recruiter.id || j.recruiter_id === recruiter.id);

            let jobsHtml = '';
            if (postedJobs.length > 0) {
                jobsHtml = `
                    <div style="margin-top:16px;">
                        <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Posted Job Openings (${postedJobs.length})</h5>
                        <div style="display:flex; flex-direction:column; gap:8px; max-height:220px; overflow-y:auto;">
                            ${postedJobs.map(j => `
                                <div style="display:flex; justify-content:space-between; align-items:center; background:#f8fafc; border:1px solid var(--border); border-radius:8px; padding:10px 14px;">
                                    <div>
                                        <div style="font-weight:700; color:var(--text-dark); font-size:0.92rem;">${escapeHtml(j.title)}</div>
                                        <div style="font-size:0.78rem; color:var(--text-muted);">${escapeHtml(j.department || '')} • Deadline: ${new Date(j.application_deadline).toLocaleDateString()}</div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="badge badge-${j.status === 'open' ? 'interview' : 'rejected'}">${j.status}</span>
                                        <span style="font-size:0.8rem; font-weight:600; color:var(--text-dark);">${j.applications_count ?? 0} applicants</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                jobsHtml = `
                    <div style="margin-top:16px;">
                        <h5 style="font-size:0.88rem; font-weight:700; color:var(--text-dark); margin-bottom:8px;">Posted Job Openings</h5>
                        <div style="color:var(--text-muted); font-size:0.85rem; font-style:italic; background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border);">No job postings active currently.</div>
                    </div>
                `;
            }

            if (bodyEl) {
                bodyEl.innerHTML = `
                    <div style="display:flex; gap:16px; align-items:center; margin-bottom:18px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                        <div class="candidate-large-avatar" style="background:#2563eb; width:54px; height:54px; font-size:1.3rem;">${getInitials(recruiter.name)}</div>
                        <div style="flex:1;">
                            <h4 style="font-size:1.2rem; font-weight:800; color:var(--text-dark); margin-bottom:2px;">${escapeHtml(recruiter.name)}</h4>
                            <div style="font-size:0.85rem; color:var(--text-muted);">
                                ✉️ ${escapeHtml(recruiter.email || 'N/A')} • 📞 ${escapeHtml(recruiter.phone || 'N/A')}
                            </div>
                        </div>
                        <span class="role-badge ${roleBadgeClass}" style="font-size:0.8rem; padding:4px 10px;">${escapeHtml(roleLabel)}</span>
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; margin-bottom:16px;">
                        <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border); text-align:center;">
                            <div style="font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Jobs Posted</div>
                            <div style="font-size:1.3rem; font-weight:800; color:#2563eb; margin-top:2px;">${recruiter.posted_jobs_count ?? 0}</div>
                        </div>
                        <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border); text-align:center;">
                            <div style="font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Interviews</div>
                            <div style="font-size:1.3rem; font-weight:800; color:#059669; margin-top:2px;">${recruiter.conducted_interviews_count ?? 0}</div>
                        </div>
                        <div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid var(--border); text-align:center;">
                            <div style="font-size:0.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Tasks Assigned</div>
                            <div style="font-size:1.3rem; font-weight:800; color:#7c3aed; margin-top:2px;">${recruiter.assigned_tasks_count ?? 0}</div>
                        </div>
                    </div>

                    ${jobsHtml}

                    <div style="display:flex; justify-content:flex-end; gap:10px; border-top:1px solid var(--border); padding-top:16px; margin-top:20px;">
                        <button class="btn btn-outline" onclick="closeModal('modalRecruiterDetail')">Close</button>
                    </div>
                `;
            }

            openModal('modalRecruiterDetail');
        }

        // Modals & Action Helpers
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('active');
            if (id === 'modalTask') {
                taskSelectedFiles = [];
                const preview = document.getElementById('taskFilesPreview');
                if (preview) preview.innerHTML = '';
                const input = document.getElementById('taskFilesInput');
                if (input) input.value = '';
            }
        }

        function openCreateJob() {
            const f = document.getElementById('formJob');
            if (f) {
                f.reset();
                if (f.job_id) f.job_id.value = '';
            }
            const heading = document.getElementById('modalJobHeading');
            if (heading) heading.textContent = 'Post New Job Opening';
            const btnSubmit = document.getElementById('btnSubmitJob');
            if (btnSubmit) btnSubmit.textContent = 'Save Job';
            const statusGroup = document.getElementById('jobStatusGroup');
            if (statusGroup) statusGroup.style.display = 'none';

            const deadlineInput = document.getElementById('jobDeadlineInput');
            if (deadlineInput) {
                const today = new Date().toISOString().split('T')[0];
                deadlineInput.setAttribute('min', today);
            }

            openModal('modalJob');
        }

        function openEditJob(id) {
            const job = (state.jobs || []).find(j => j.id == id);
            if (!job) {
                showToast('Job opening not found', 'error');
                return;
            }

            const currentUserId = state.currentUser?.id;
            const isAdmin = state.currentUser?.role?.name === 'admin';
            const isOwner = (job.recruiter_id === currentUserId || job.recruiter?.id === currentUserId);
            if (!isAdmin && !isOwner) {
                showToast('You cannot edit a job opening posted by another recruiter.', 'error');
                return;
            }

            const f = document.getElementById('formJob');
            if (!f) return;
            f.reset();

            if (f.job_id) f.job_id.value = job.id;
            if (f.title) f.title.value = job.title || '';
            if (f.department) f.department.value = job.department || '';
            if (f.experience) f.experience.value = job.experience || '';
            if (f.salary_range) f.salary_range.value = job.salary_range || '';
            if (f.description) f.description.value = job.description || '';

            if (f.application_deadline) {
                f.application_deadline.removeAttribute('min');
                if (job.application_deadline) {
                    f.application_deadline.value = job.application_deadline.substring(0, 10);
                } else {
                    f.application_deadline.value = '';
                }
            }

            const statusGroup = document.getElementById('jobStatusGroup');
            if (statusGroup) statusGroup.style.display = 'block';
            if (f.status) f.status.value = job.status || 'open';

            // Extract skills
            let skillsArr = [];
            if (Array.isArray(job.skills)) {
                skillsArr = job.skills;
            } else if (typeof job.skills === 'string') {
                try { skillsArr = JSON.parse(job.skills); } catch(e) { skillsArr = job.skills.split(',').map(s=>({name: s.trim()})); }
            }

            const mandatory = [];
            const bonus = [];
            skillsArr.forEach(s => {
                const name = typeof s === 'string' ? s : (s.name || '');
                if (!name) return;
                if (typeof s === 'object' && s.is_mandatory === false) {
                    bonus.push(name);
                } else {
                    mandatory.push(name);
                }
            });

            if (f.mandatory_skills) f.mandatory_skills.value = mandatory.join(', ');
            if (f.bonus_skills) f.bonus_skills.value = bonus.join(', ');

            const heading = document.getElementById('modalJobHeading');
            if (heading) heading.textContent = `Edit Job: ${job.title}`;
            const btnSubmit = document.getElementById('btnSubmitJob');
            if (btnSubmit) btnSubmit.textContent = 'Update Job';

            closeModal('modalJobView');
            openModal('modalJob');
        }

        async function submitJob(e) {
            e.preventDefault();
            const f = e.target;
            const isEdit = Boolean(f.job_id && f.job_id.value);
            const url = isEdit ? `/api/jobs/${f.job_id.value}` : '/api/jobs';
            const method = isEdit ? 'PUT' : 'POST';

            const payload = {
                title: f.title.value,
                department: f.department.value,
                experience: f.experience.value,
                salary_range: f.salary_range.value,
                application_deadline: f.application_deadline.value,
                description: f.description.value,
                mandatory_skills: f.mandatory_skills.value.split(',').map(s => s.trim()).filter(Boolean),
                bonus_skills: f.bonus_skills.value.split(',').map(s => s.trim()).filter(Boolean)
            };

            if (isEdit && f.status) {
                payload.status = f.status.value;
            }

            showLoading(isEdit ? 'Updating Job...' : 'Creating Job...', 'Please wait...');

            try {
                const res = await api(url, {
                    method: method,
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    showToast(isEdit ? 'Job updated successfully!' : 'Job created successfully!', 'success');
                    closeModal('modalJob');
                    f.reset();
                    if (f.job_id) f.job_id.value = '';
                    await reloadAll();
                } else {
                    const d = await res.json();
                    const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || (isEdit ? 'Error updating job' : 'Error creating job'));
                    showToast(err, 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Failed to save job', 'error');
            } finally {
                hideLoading();
            }
        }

        async function deleteJob(id, title) {
            const job = (state.jobs || []).find(j => j.id == id);
            const jobTitle = title || job?.title || 'this job';

            const currentUserId = state.currentUser?.id;
            const isAdmin = state.currentUser?.role?.name === 'admin';
            const isOwner = !job || (job.recruiter_id === currentUserId || job.recruiter?.id === currentUserId);
            if (!isAdmin && !isOwner) {
                showToast('You cannot delete a job opening posted by another recruiter.', 'error');
                return;
            }

            if (!confirm(`Are you sure you want to delete the job opening "${jobTitle}"? This will also remove any related applications.`)) {
                return;
            }

            showLoading('Deleting Job...', 'Please wait...');
            try {
                const res = await api(`/api/jobs/${id}`, {
                    method: 'DELETE'
                });

                if (res.ok) {
                    showToast('Job deleted successfully!', 'success');
                    closeModal('modalJobView');
                    await reloadAll();
                } else {
                    const d = await res.json();
                    const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Error deleting job');
                    showToast(err, 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Failed to delete job', 'error');
            } finally {
                hideLoading();
            }
        }

        function viewJob(id) {
            const job = (state.jobs || []).find(j => j.id == id);
            if (!job) {
                showToast('Job details not found', 'error');
                return;
            }

            const isCandidate = state.currentUser?.role?.name === 'candidate';
            const isRecruiter = state.currentUser?.role?.name === 'recruiter' || state.currentUser?.role?.name === 'admin';
            const userApplications = Array.isArray(state.applications) ? state.applications : [];
            const appliedApp = isCandidate
                ? userApplications.find(a => (a.job_id === job.id || (a.job && a.job.id === job.id)))
                : null;

            document.getElementById('viewJobTitle').textContent = job.title || 'Job Opening';

            const badge = document.getElementById('viewJobStatusBadge');
            badge.className = `badge badge-${job.status || 'open'}`;
            badge.textContent = (job.status || 'open').toUpperCase();

            document.getElementById('viewJobSubtitle').textContent = `${job.department || 'General'} • ${job.experience || 'Experience unspecified'}`;
            document.getElementById('viewJobDept').textContent = job.department || 'General';
            document.getElementById('viewJobExp').textContent = job.experience || 'Not specified';
            document.getElementById('viewJobSalary').textContent = job.salary_range || 'Competitive';
            document.getElementById('viewJobDeadline').textContent = job.application_deadline ? job.application_deadline.substring(0, 10) : 'Open until filled';
            document.getElementById('viewJobRecruiter').textContent = job.recruiter?.name || 'Talent Acquisition';
            document.getElementById('viewJobApplicants').textContent = `${job.applications_count ?? 0} applied`;
            document.getElementById('viewJobDescription').textContent = job.description || 'No description provided for this opening.';

            // Render skills
            let skillsArr = [];
            if (Array.isArray(job.skills)) {
                skillsArr = job.skills;
            } else if (typeof job.skills === 'string') {
                try { skillsArr = JSON.parse(job.skills); } catch(e) { skillsArr = job.skills.split(',').map(s=>({name: s.trim()})); }
            }

            const mandContainer = document.getElementById('viewJobMandatorySkills');
            const bonusContainer = document.getElementById('viewJobBonusSkills');
            mandContainer.innerHTML = '';
            bonusContainer.innerHTML = '';

            let hasMandatory = false;
            let hasBonus = false;

            skillsArr.forEach(s => {
                const name = typeof s === 'string' ? s : (s.name || '');
                if (!name) return;
                const isMandatory = (typeof s === 'object' && s.is_mandatory !== false);
                const tag = document.createElement('span');
                if (isMandatory) {
                    hasMandatory = true;
                    tag.className = 'skill-tag mandatory';
                    tag.textContent = name;
                    mandContainer.appendChild(tag);
                } else {
                    hasBonus = true;
                    tag.className = 'skill-tag';
                    tag.textContent = name;
                    bonusContainer.appendChild(tag);
                }
            });

            if (!hasMandatory) {
                mandContainer.innerHTML = '<span style="color:var(--text-muted); font-size:0.85rem; font-style:italic;">None specified</span>';
            }
            if (!hasBonus) {
                document.getElementById('viewJobBonusSkillsWrapper').style.display = 'none';
            } else {
                document.getElementById('viewJobBonusSkillsWrapper').style.display = 'block';
            }

            // Footer buttons
            const footer = document.getElementById('viewJobFooter');
            footer.innerHTML = '';

            if (isCandidate) {
                const user = state.currentUser;
                const cand = (state.candidates || []).find(c => c.email === user.email || c.name === user.name) || user.candidate || {};
                const candidateHasResume = !!(cand.latest_resume || (cand.resumes && cand.resumes.length > 0));

                if (appliedApp) {
                    footer.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                            <button class="btn btn-sm btn-applied" onclick="closeModal('modalJobView'); switchTab('pipeline');" style="background:#ecfdf5; color:#059669; border:1px solid #10b981; font-weight:700; padding:7px 16px; border-radius:var(--radius-sm); cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                                <span style="font-size:1.15em; font-weight:800;">✓</span> Applied (${escapeHtml(appliedApp.status || 'Applied')})
                            </button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJobView')">Close</button>
                        </div>
                    `;
                } else if (!candidateHasResume) {
                    footer.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center; width:100%; gap:12px;">
                            <button class="btn btn-sm" onclick="closeModal('modalJobView'); openModal('modalNoResume');" style="background:#fee2e2; color:#dc2626; border:1px solid #fecaca; font-weight:700; padding:8px 16px; border-radius:var(--radius-sm); cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                                <span>📄⚠️</span> Resume Required to Apply
                            </button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJobView')">Close</button>
                        </div>
                    `;
                } else {
                    footer.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                            <button class="btn btn-primary btn-sm" onclick="closeModal('modalJobView'); openApply(${job.id}, '${escapeJs(job.title)}');">Apply for this Position</button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJobView')">Close</button>
                        </div>
                    `;
                }
            } else if (isRecruiter) {
                const isAdmin = state.currentUser?.role?.name === 'admin';
                const currentUserId = state.currentUser?.id;
                const isOwner = (job.recruiter_id === currentUserId || job.recruiter?.id === currentUserId);
                const canEditDelete = isAdmin || isOwner;

                footer.innerHTML = `
                    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap; width:100%; justify-content:space-between;">
                        <div style="display:flex; gap:8px;">
                            ${canEditDelete ? `
                                <button class="btn btn-outline btn-sm" onclick="openEditJob(${job.id})" style="color:var(--primary); border-color:var(--primary); font-weight:600;">
                                    <span style="margin-right:4px;">✏️</span> Edit Job
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="deleteJob(${job.id}, '${escapeJs(job.title)}')" style="color:var(--danger); border-color:var(--danger); font-weight:600;">
                                    <span style="margin-right:4px;">🗑️</span> Delete Job
                                </button>
                            ` : ''}
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button class="btn btn-primary btn-sm" onclick="closeModal('modalJobView'); switchTab('pipeline');">View Pipeline (${job.applications_count ?? 0})</button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJobView')">Close</button>
                        </div>
                    </div>
                `;
            } else {
                footer.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
                        <button class="btn btn-primary btn-sm" onclick="closeModal('modalJobView'); openApply(${job.id}, '${escapeJs(job.title)}');">Apply Now</button>
                        <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('modalJobView')">Close</button>
                    </div>
                `;
            }

            openModal('modalJobView');
        }

        async function openApply(id, title) {
            const user = state.currentUser;
            if (!user) {
                openAuthModal('candidate', 'login');
                return;
            }

            const existingApp = (state.applications || []).find(a => (a.job_id == id || (a.job && a.job.id == id)));
            if (existingApp) {
                showToast('You have already applied for this job opening.', 'info');
                switchTab('pipeline');
                return;
            }

            const cand = (state.candidates || []).find(c => c.email === user.email || c.name === user.name) || user.candidate || {};
            const latestResume = cand.latest_resume || (cand.resumes && cand.resumes[0]);

            // Without a resume, no jobs can be applied!
            if (!latestResume) {
                showToast('Please go to Profile Settings to add your resume to apply. Without a resume, no jobs can be applied!', 'error', 6000);
                openModal('modalNoResume');
                return;
            }

            showLoading('Submitting Application...', 'Please wait...');

            try {
                const formData = new FormData();
                formData.append('job_id', id);
                formData.append('name', user.name);
                formData.append('email', user.email);
                if (cand.phone || user.phone) formData.append('phone', cand.phone || user.phone);
                if (cand.experience_years) formData.append('experience_years', cand.experience_years);
                if (cand.skills_summary) formData.append('skills_summary', cand.skills_summary);
                formData.append('resume_id', latestResume.id);

                const res = await api(`/api/jobs/${id}/apply`, {
                    method: 'POST',
                    body: formData
                });

                if (res.ok) {
                    showToast(`Application submitted! Your profile details & resume have been sent to the recruiter.`, 'success');
                    await reloadAll();
                    switchTab('pipeline');
                } else {
                    const d = await res.json();
                    showToast(d.message || 'Application failed', 'error');
                    if (d.error === 'no_resume') {
                        openModal('modalNoResume');
                    }
                }
            } catch (err) {
                showToast('Application error: ' + err.message, 'error');
            } finally {
                hideLoading();
            }
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
                const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Application failed');
                showToast(err, 'error');
                if (d.error === 'no_resume') {
                    closeModal('modalApply');
                    openModal('modalNoResume');
                }
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
                const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Status update failed');
                showToast(err, 'error');
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
                const msg = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Conflict detected.');
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

        let taskSelectedFiles = [];

        function handleTaskFileSelect(input) {
            const preview = document.getElementById('taskFilesPreview');
            const files = Array.from(input.files || []);
            
            if (files.length > 5) {
                showToast('You can upload up to 5 files only.', 'error');
                input.value = '';
                taskSelectedFiles = [];
                if (preview) preview.innerHTML = '';
                return;
            }

            const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'];
            for (const f of files) {
                const ext = (f.name.split('.').pop() || '').toLowerCase();
                if (!allowedExtensions.includes(ext)) {
                    showToast(`File "${f.name}" has an unsupported format. Only images, PDF, and documents are allowed.`, 'error');
                    input.value = '';
                    taskSelectedFiles = [];
                    if (preview) preview.innerHTML = '';
                    return;
                }
                if (f.size > 10 * 1024 * 1024) {
                    showToast(`File "${f.name}" exceeds 10MB limit.`, 'error');
                    input.value = '';
                    taskSelectedFiles = [];
                    if (preview) preview.innerHTML = '';
                    return;
                }
            }

            taskSelectedFiles = files;
            renderTaskFilesPreview();
        }

        function renderTaskFilesPreview() {
            const preview = document.getElementById('taskFilesPreview');
            if (!preview) return;
            preview.innerHTML = '';
            taskSelectedFiles.forEach((file, idx) => {
                const ext = (file.name.split('.').pop() || '').toLowerCase();
                const isImg = ['jpg', 'jpeg', 'png', 'webp', 'svg'].includes(ext);
                const isPdf = ext === 'pdf';
                const icon = isImg ? '🖼️' : (isPdf ? '📄' : '📝');
                const sizeKb = Math.round(file.size / 1024);
                
                const item = document.createElement('div');
                item.style.cssText = 'display:flex; align-items:center; justify-content:space-between; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:6px 10px; font-size:0.8rem;';
                item.innerHTML = `
                    <div style="display:flex; align-items:center; gap:8px; overflow:hidden;">
                        <span>${icon}</span>
                        <span style="font-weight:600; color:#334155; max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${escapeHtml(file.name)}</span>
                        <span style="color:#94a3b8; font-size:0.75rem;">(${sizeKb} KB)</span>
                    </div>
                    <button type="button" style="background:none; border:none; color:#ef4444; font-size:1.1rem; cursor:pointer; padding:0 4px; line-height:1;" onclick="removeTaskFile(${idx})" title="Remove file">&times;</button>
                `;
                preview.appendChild(item);
            });
        }

        function removeTaskFile(index) {
            taskSelectedFiles.splice(index, 1);
            renderTaskFilesPreview();
            if (taskSelectedFiles.length === 0) {
                const input = document.getElementById('taskFilesInput');
                if (input) input.value = '';
            }
        }

        async function submitTask(e) {
            e.preventDefault();
            const f = e.target;
            const id = f.application_id.value;

            if (taskSelectedFiles.length > 5) {
                showToast('You can upload up to 5 files only.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('title', f.title.value);
            formData.append('description', f.description.value);
            formData.append('deadline', f.deadline.value.replace('T', ' ') + ':00');
            taskSelectedFiles.forEach(file => {
                formData.append('files[]', file);
            });

            const res = await api(`/api/applications/${id}/technical-tasks`, {
                method: 'POST',
                body: formData
            });

            if (res.ok) {
                showToast('Technical task assigned!', 'success');
                closeModal('modalTask');
                f.reset();
                taskSelectedFiles = [];
                const preview = document.getElementById('taskFilesPreview');
                if (preview) preview.innerHTML = '';
                await reloadAll();
                switchTab('tasks');
            } else {
                const d = await res.json();
                const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Error assigning task');
                showToast(err, 'error');
            }
        }

        async function startTask(id) {
            await api(`/api/technical-tasks/${id}/start`, { method: 'PATCH' });
            showToast('Task marked In Progress', 'success');
            await loadTasks();
        }

        function openSubmitTask(id) {
            document.getElementById('submitTaskId').value = id;
            const task = (state.tasks || []).find(t => t.id === id);
            const descEl = document.getElementById('submitTaskDescText');
            const attEl = document.getElementById('submitTaskAttachmentsContainer');
            
            if (task) {
                if (descEl) descEl.textContent = task.description || 'No specific instructions provided.';
                if (attEl) {
                    if (Array.isArray(task.attachments) && task.attachments.length > 0) {
                        attEl.innerHTML = `<div style="font-weight:700; color:var(--text-dark); margin:8px 0 4px 0;">Attached Brief & Files:</div>` +
                            `<div style="display:flex; flex-wrap:wrap; gap:6px;">` +
                            task.attachments.map((att, idx) => {
                                const ext = (att.name.split('.').pop() || '').toLowerCase();
                                const isImg = ['jpg', 'jpeg', 'png', 'webp', 'svg'].includes(ext);
                                const isPdf = ext === 'pdf';
                                const icon = isImg ? '🖼️' : (isPdf ? '📄' : '📝');
                                return `<a href="/api/technical-tasks/${task.id}/attachments/${idx}" target="_blank" class="skill-tag" style="text-decoration:none; display:inline-flex; align-items:center; gap:5px; font-size:0.75rem; padding:4px 8px; background:#ffffff; border:1px solid #cbd5e1; border-radius:6px; color:#2563eb; font-weight:600;" title="Download ${escapeHtml(att.name)}">
                                    <span>${icon}</span>
                                    <span>${escapeHtml(att.name)}</span>
                                </a>`;
                            }).join('') + `</div>`;
                    } else {
                        attEl.innerHTML = '';
                    }
                }
            }
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
                const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Submission error');
                showToast(err, 'error');
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
                const err = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Review error');
                showToast(err, 'error');
            }
        }

        // In-app Notifications & 24h Deadline Alerts
        let notificationsState = [];

        async function loadNotifications() {
            if (!state.token) return;
            try {
                const res = await api('/api/notifications');
                if (res.ok) {
                    const data = await res.json();
                    notificationsState = data.notifications?.data || data.notifications || [];
                    const unread = data.unread_count ?? notificationsState.filter(n => !n.read_at).length;
                    updateNotificationUI(unread, notificationsState);
                }
            } catch (err) {
                console.error('Error fetching notifications:', err);
            }
        }

        function updateNotificationUI(unreadCount, notifs) {
            const badge = document.getElementById('notifBadge');
            const list = document.getElementById('notifList');
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            }

            if (list) {
                if (!notifs || notifs.length === 0) {
                    list.innerHTML = '<div class="notif-empty">No notifications yet.<br><small style="color:var(--text-light); margin-top:4px; display:inline-block;">You will receive alerts here 24 hours before any task deadline.</small></div>';
                    return;
                }

                let html = '';
                notifs.forEach(n => {
                    const isUnread = !n.read_at;
                    const data = n.data || {};
                    const isDeadline = data.type === 'task_deadline_reminder';
                    const icon = isDeadline ? '⏰' : (data.type === 'task_submitted' ? '📬' : (data.type === 'interview' ? '💼' : '🔔'));
                    const title = data.title || (isDeadline ? 'Task Deadline Reminder' : 'Notification');
                    const message = data.message || 'You have an update regarding your application.';
                    const dateStr = n.created_at ? new Date(n.created_at).toLocaleDateString([], { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '';

                    html += `
                        <div class="notif-item ${isUnread ? 'unread' : ''} ${isDeadline ? 'urgent' : ''}" onclick="handleNotificationClick('${n.id}', '${data.type || ''}', event)">
                            <div class="notif-icon">${icon}</div>
                            <div class="notif-content">
                                <div class="notif-title">${escapeHtml(title)}</div>
                                <div class="notif-message">${escapeHtml(message)}</div>
                                <div class="notif-time">${dateStr}</div>
                            </div>
                        </div>
                    `;
                });
                list.innerHTML = html;
            }
        }

        function toggleNotifications(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('notifDropdown');
            if (!dropdown) return;
            const isVisible = dropdown.style.display === 'block';
            dropdown.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                loadNotifications();
            }
        }

        async function markAllNotificationsRead(e) {
            if (e) e.stopPropagation();
            try {
                const res = await api('/api/notifications/read-all', { method: 'POST' });
                if (res.ok) {
                    const badge = document.getElementById('notifBadge');
                    if (badge) badge.style.display = 'none';
                    document.querySelectorAll('.notif-item').forEach(el => el.classList.remove('unread'));
                    showToast('All notifications marked as read', 'success');
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function handleNotificationClick(id, type, e) {
            if (e) e.stopPropagation();
            try {
                await api(`/api/notifications/${id}/read`, { method: 'PATCH' });
                await loadNotifications();
            } catch (err) {}

            const dropdown = document.getElementById('notifDropdown');
            if (dropdown) dropdown.style.display = 'none';

            if (type === 'task_deadline_reminder' || type === 'task_submitted') {
                switchTab('tasks');
            } else if (type === 'interview') {
                switchTab('interviews');
            } else {
                switchTab('pipeline');
            }
        }

        // Close notification dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('notifWrapper');
            const dropdown = document.getElementById('notifDropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        function showToast(msg, type = 'success') {
            const box = document.getElementById('toastBox');
            if (!box) return;
            const el = document.createElement('div');
            el.className = `toast-msg ${type}`;
            const icon = type === 'success' ? '✅' : (type === 'error' ? '❌' : 'ℹ️');
            el.innerHTML = `<span style="font-size:1.05rem; line-height:1;">${icon}</span><span style="flex:1;">${escapeHtml(msg)}</span>`;
            box.appendChild(el);
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-6px)';
                el.style.transition = 'all 0.3s ease';
                setTimeout(() => el.remove(), 300);
            }, 4500);
        }

        function escapeHtml(s) {
            if (!s) return '';
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function escapeJs(s) {
            if (!s) return '';
            return String(s).replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        function formatFileSize(bytes) {
            if (!bytes || isNaN(bytes)) return '';
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function viewResume(resumeId) {
            if (!resumeId) {
                showToast('No resume file associated with this profile', 'error');
                return;
            }
            const token = state.token || localStorage.getItem('tf_token') || '';
            const url = `/api/resumes/${resumeId}/download?inline=1${token ? `&token=${encodeURIComponent(token)}` : ''}`;
            window.open(url, '_blank');
        }

        async function downloadResume(resumeId, fileName = 'resume.pdf') {
            if (!resumeId) {
                showToast('No resume file associated with this profile', 'error');
                return;
            }
            const token = state.token || localStorage.getItem('tf_token') || '';

            try {
                const res = await api(`/api/resumes/${resumeId}/download`);
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    showToast(err.message || 'Failed to download resume', 'error');
                    return;
                }
                const blob = await res.blob();
                const fileUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = fileUrl;
                a.download = fileName || 'resume.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                setTimeout(() => window.URL.revokeObjectURL(fileUrl), 10000);
            } catch (e) {
                console.error('Error downloading resume:', e);
                const directUrl = `/api/resumes/${resumeId}/download${token ? `?token=${encodeURIComponent(token)}` : ''}`;
                window.location.href = directUrl;
            }
        }
    </script>
</body>
</html>
