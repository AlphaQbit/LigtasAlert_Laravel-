<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LigtasAlert Admin</title>
    <style>
        :root {
            --primary: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
            --bg: #F9FAFB;
            --surface: #FFFFFF;
            --border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --standby: #D1FAE5;
            --active: #FEE2E2;
        }

        @media (prefers-color-scheme: dark) {
            :root:not([data-theme="light"]) {
                --bg: #111827;
                --surface: #1F2937;
                --border: #374151;
                --text-primary: #F9FAFB;
                --text-secondary: #D1D5DB;
                --standby: #064E3B;
                --active: #7F1D1D;
            }
        }

        :root[data-theme="dark"] {
            --bg: #111827;
            --surface: #1F2937;
            --border: #374151;
            --text-primary: #F9FAFB;
            --text-secondary: #D1D5DB;
            --standby: #064E3B;
            --active: #7F1D1D;
            color-scheme: dark;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            line-height: 1.5;
        }

        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .header-title {
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-tabs {
            display: flex;
            gap: 0.25rem;
            border-bottom: 1px solid var(--border);
        }

        .nav-tab {
            padding: 0.75rem 1.5rem;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .nav-tab:hover {
            color: var(--text-primary);
        }

        .nav-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            font-weight: 600;
        }

        .facility-selector {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .facility-selector label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .facility-selector select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            background: var(--surface);
            color: var(--text-primary);
            font-size: 0.875rem;
            cursor: pointer;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
            display: grid;
            gap: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stat-card.active .stat-number {
            color: var(--danger);
        }

        .stat-card.standby .stat-number {
            color: var(--primary);
        }

        .alerts-section {
            display: grid;
            gap: 1rem;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alerts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }

        .alert-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .alert-card.active {
            border-left-color: var(--danger);
            background: var(--active);
        }

        .alert-card.acknowledged {
            border-left-color: var(--warning);
        }

        .alert-card.resolved {
            border-left-color: var(--primary);
            background: var(--standby);
            opacity: 0.7;
        }

        .alert-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .alert-type {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.active {
            background: var(--danger);
            color: white;
        }

        .badge.acknowledged {
            background: var(--warning);
            color: white;
        }

        .badge.resolved {
            background: var(--primary);
            color: white;
        }

        .alert-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        .detail-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .detail-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        .alert-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
        }

        .btn {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-primary);
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn:hover {
            background: var(--border);
        }

        .btn.primary {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn.primary:hover {
            opacity: 0.9;
        }

        .btn.danger {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        .btn.danger:hover {
            opacity: 0.9;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .activity-log {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            overflow: hidden;
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: var(--text-secondary);
            font-weight: 500;
            min-width: 70px;
        }

        .activity-text {
            flex: 1;
        }

        .activity-icon {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 0.375rem;
            flex-shrink: 0;
        }

        .activity-icon.alert {
            background: var(--danger);
        }

        .activity-icon.resolved {
            background: var(--primary);
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 100;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .tab-content {
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Contacts Tab */
        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .contact-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .contact-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .contact-info {
            flex: 1;
        }

        .contact-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .contact-role {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .contact-status {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .contact-status.online {
            background: var(--primary);
        }

        .contact-status.offline {
            background: var(--text-secondary);
        }

        /* Facilities Tab */
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .facility-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1rem;
            text-align: center;
        }

        .facility-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .facility-stats {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* History Tab */
        .history-grid {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .history-item {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .history-type {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .history-type.fire { background: #FEE2E2; color: #DC2626; }
        .history-type.medical { background: #DBEAFE; color: #2563EB; }
        .history-type.lockdown { background: #FEF3C7; color: #D97706; }
        .history-type.evacuation { background: #FCE7F3; color: #DB2777; }
        .history-type.custom { background: #E5E7EB; color: #374151; }

        .history-location {
            font-weight: 500;
        }

        .history-time {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border);
        }

        .timeline-item {
            position: relative;
            padding: 0.75rem 0;
            padding-left: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 1rem;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid var(--bg);
        }

        .timeline-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .timeline-event {
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .alerts-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="nav-container">
            <div class="nav-header">
                <div class="header-title">
                    🚨 LigtasAlert Admin
                </div>
                <div class="facility-selector">
                    <label for="facility">Facility:</label>
                    <select id="facility">
                        <option value="all">All Facilities</option>
                    </select>
                </div>
            </div>
            <div class="nav-tabs">
                <button class="nav-tab active" data-tab="dashboard">Dashboard</button>
                <button class="nav-tab" data-tab="contacts">Contacts</button>
                <button class="nav-tab" data-tab="history">History</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div id="dashboardTab" class="tab-content active">
            <div class="stats-grid">
                <div class="stat-card active">
                    <div class="stat-number" id="activeCount">-</div>
                    <div class="stat-label">Active Alerts</div>
                </div>
                <div class="stat-card standby">
                    <div class="stat-number" id="respondersCount">-</div>
                    <div class="stat-label">Responders On Duty</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="resolvedCount">-</div>
                    <div class="stat-label">Resolved Today</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="avgResponseTime">-</div>
                    <div class="stat-label">Avg Response Time</div>
                </div>
            </div>

            <div class="alerts-section">
                <div class="section-title">Active Incidents</div>
                <div class="alerts-grid" id="alertsGrid">
                    <div class="loading">Loading alerts...</div>
                </div>
            </div>

            <div class="alerts-section">
                <div class="section-title">Recent Activity</div>
                <div class="activity-log" id="activityLog">
                    <div class="loading">Loading activity...</div>
                </div>
            </div>
        </div>

        <div id="contactsTab" class="tab-content" style="display:none;">
            <div class="alerts-section">
                <div class="section-title">Emergency Contacts</div>
                <div class="contacts-grid" id="contactsGrid">
                    <div class="loading">Loading contacts...</div>
                </div>
            </div>

            <div class="alerts-section">
                <div class="section-title">Facilities & Zones</div>
                <div class="facilities-grid" id="facilitiesGrid">
                    <div class="loading">Loading facilities...</div>
                </div>
            </div>
        </div>

        <div id="historyTab" class="tab-content" style="display:none;">
            <div class="alerts-section">
                <div class="section-title">Alert History</div>
                <div class="history-grid" id="historyGrid">
                    <div class="loading">Loading history...</div>
                </div>
            </div>

            <div class="alerts-section">
                <div class="section-title">Response Timeline</div>
                <div class="timeline" id="timeline">
                    <div class="loading">Loading timeline...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        let refreshInterval;

        async function fetchAPI(endpoint, options = {}) {
            try {
                const response = await fetch(`${API_BASE}/${endpoint}`, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    ...options
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                return await response.json();
            } catch (error) {
                console.error('API Error:', error);
                showToast('Connection error. Retrying...', 'error');
                return null;
            }
        }

        async function loadStats() {
            const data = await fetchAPI('stats');
            if (data) {
                document.getElementById('activeCount').textContent = data.active_count || 0;
                document.getElementById('respondersCount').textContent = data.responders_on_duty || 0;
                document.getElementById('resolvedCount').textContent = data.resolved_today || 0;
            }
        }

        async function loadFacilities() {
            const data = await fetchAPI('facilities');
            if (data && Array.isArray(data)) {
                const select = document.getElementById('facility');
                data.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f.id;
                    opt.textContent = f.name;
                    select.appendChild(opt);
                });
            }
        }

        async function loadAlerts() {
            const facility = document.getElementById('facility').value;
            const endpoint = facility === 'all'
                ? 'alerts?status=active'
                : `alerts?status=active&facility=${facility}`;

            const data = await fetchAPI(endpoint);
            const grid = document.getElementById('alertsGrid');

            if (!data) {
                grid.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-secondary);">Failed to load alerts. Check console.</div>';
                return;
            }

            if (data.alerts.length === 0) {
                grid.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-secondary);">No active alerts</div>';
                return;
            }

            grid.innerHTML = data.alerts.map(alert => `
                <div class="alert-card ${alert.status}" data-id="${alert.id}">
                    <div class="alert-header">
                        <div class="alert-type">${alert.type}</div>
                        <span class="badge ${alert.status}">${alert.status}</span>
                    </div>
                    <div class="alert-details">
                        <div class="detail-row">
                            <span class="detail-label">Location:</span>
                            <span class="detail-value">${alert.room}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Reported:</span>
                            <span class="detail-value">${formatTime(alert.created_at)}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Recipients:</span>
                            <span class="detail-value">${alert.recipients}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Responders:</span>
                            <span class="detail-value">${alert.responders?.length || 0}</span>
                        </div>
                    </div>
                    <div class="alert-actions">
                        <button class="btn primary" onclick="acknowledgeAlert('${alert.id}')" ${alert.status !== 'active' ? 'disabled' : ''}>
                            Acknowledge
                        </button>
                        <button class="btn danger" onclick="resolveAlert('${alert.id}')" ${alert.status === 'resolved' ? 'disabled' : ''}>
                            Resolve
                        </button>
                    </div>
                </div>
            `).join('');
        }

        async function acknowledgeAlert(id) {
            const data = await fetchAPI(`alerts/${id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    status: 'acknowledged',
                    acknowledged_by: 'Admin'
                })
            });

            if (data) {
                showToast('Alert acknowledged', 'success');
                loadAlerts();
            }
        }

        async function resolveAlert(id) {
            const data = await fetchAPI(`alerts/${id}`, {
                method: 'PUT',
                body: JSON.stringify({ status: 'resolved' })
            });

            if (data) {
                showToast('Alert resolved', 'success');
                loadAlerts();
                loadStats();
            }
        }

        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);

            if (diff < 60) return 'Just now';
            if (diff < 3600) return `${Math.floor(diff / 60)} min ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;

            return date.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit'
            });
        }

        function showToast(message, type = 'success') {
            const existing = document.querySelector('.toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 3000);
        }

        function switchTab(tabName) {
            // Update nav tabs
            document.querySelectorAll('.nav-tab').forEach(t => {
                t.classList.toggle('active', t.dataset.tab === tabName);
            });

            // Update content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            document.getElementById(`${tabName}Tab`).style.display = 'block';

            // Load tab data
            if (tabName === 'contacts') {
                loadContacts();
                loadFacilitiesList();
            } else if (tabName === 'history') {
                loadHistory();
                loadTimeline();
            }
        }

        async function loadContacts() {
            const grid = document.getElementById('contactsGrid');
            const contacts = [
                { name: 'John Smith', role: 'Building A - Floor 1', online: true, initials: 'JS' },
                { name: 'Maria Garcia', role: 'Building A - Floor 2', online: true, initials: 'MG' },
                { name: 'David Lee', role: 'Building A - Floor 3', online: false, initials: 'DL' },
                { name: 'Sarah Johnson', role: 'Building B - Security', online: true, initials: 'SJ' },
                { name: 'Mike Chen', role: 'Campus Maintenance', online: false, initials: 'MC' },
                { name: 'Lisa Wong', role: 'Building B - Floor 1', online: true, initials: 'LW' }
            ];

            grid.innerHTML = contacts.map(c => `
                <div class="contact-card">
                    <div class="contact-avatar">${c.initials}</div>
                    <div class="contact-info">
                        <div class="contact-name">${c.name}</div>
                        <div class="contact-role">${c.role}</div>
                    </div>
                    <div class="contact-status ${c.online ? 'online' : 'offline'}" title="${c.online ? 'Online' : 'Offline'}"></div>
                </div>
            `).join('');
        }

        async function loadFacilitiesList() {
            const grid = document.getElementById('facilitiesGrid');
            const data = await fetchAPI('stats');

            grid.innerHTML = `
                <div class="facility-card">
                    <div class="facility-name">Building A</div>
                    <div class="facility-stats">3 Floors • 24 Rooms</div>
                </div>
                <div class="facility-card">
                    <div class="facility-name">Building B</div>
                    <div class="facility-stats">4 Floors • 32 Rooms</div>
                </div>
                <div class="facility-card">
                    <div class="facility-name">Campus</div>
                    <div class="facility-stats">All Zones • Common Areas</div>
                </div>
            `;
        }

        async function loadHistory() {
            const grid = document.getElementById('historyGrid');
            const allAlerts = await fetchAPI('alerts');

            if (!allAlerts || allAlerts.alerts.length === 0) {
                grid.innerHTML = '<div class="loading">No alert history</div>';
                return;
            }

            grid.innerHTML = allAlerts.alerts.map(alert => `
                <div class="history-item">
                    <div class="history-left">
                        <span class="history-type ${alert.type.toLowerCase()}">${alert.type}</span>
                        <span class="history-location">${alert.room}</span>
                    </div>
                    <span class="history-time">${formatTime(alert.created_at)}</span>
                </div>
            `).join('');
        }

        async function loadTimeline() {
            const timeline = document.getElementById('timeline');
            const allAlerts = await fetchAPI('alerts');

            const events = [
                { time: '2:45 PM', event: 'Fire alert resolved in Room 214', type: 'resolved' },
                { time: '2:30 PM', event: 'Evacuation order issued for Campus Wide', type: 'alert' },
                { time: '2:28 PM', event: 'Lockdown lifted - Building B East Wing', type: 'resolved' },
                { time: '2:25 PM', event: 'Custom alert sent to Room 101', type: 'alert' },
                { time: '2:20 PM', event: 'All-clear signal broadcast - Building A', type: 'resolved' }
            ];

            timeline.innerHTML = events.map(e => `
                <div class="timeline-item">
                    <div class="timeline-time">${e.time}</div>
                    <div class="timeline-event">${e.event}</div>
                </div>
            `).join('');
        }

        function init() {
            loadFacilities();
            loadStats();
            loadAlerts();

            // Tab navigation
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.addEventListener('click', () => switchTab(tab.dataset.tab));
            });

            // Facility filter
            document.getElementById('facility').addEventListener('change', loadAlerts);

            // Poll for new alerts every 500ms for instant updates
            refreshInterval = setInterval(() => {
                loadStats();
                loadAlerts();
            }, 500);
        }

        init();
    </script>
</body>
</html>