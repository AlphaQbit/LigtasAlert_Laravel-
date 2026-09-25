<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LigtasAlert - Rescuee App</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 1.5rem;
            flex: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .facility-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: white;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .title {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            color: var(--primary);
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
        }

        .alert-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-type-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-type-btn {
            padding: 1rem;
            border: 2px solid var(--border);
            border-radius: 0.5rem;
            background: var(--surface);
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }

        .alert-type-btn:hover {
            border-color: var(--danger);
        }

        .alert-type-btn.selected {
            border-color: var(--danger);
            background: #FEE2E2;
        }

        .alert-type-btn .icon {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .alert-type-btn .name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .alert-type-btn .desc {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .activate-btn {
            width: 100%;
            padding: 1.5rem;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .activate-btn:hover {
            opacity: 0.9;
        }

        .activate-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateX(-50%) translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="facility-badge">LigtasAlert · Room 214</div>
            <h1 class="title">Send Alert</h1>
            <div class="status">
                <span class="status-dot"></span>
                STANDBY
            </div>
        </div>

        <div class="alert-card">
            <div class="alert-type-grid">
                <button class="alert-type-btn" data-type="Lockdown">
                    <div class="icon">🔒</div>
                    <div class="name">Lockdown</div>
                    <div class="desc">Active threat</div>
                </button>
                <button class="alert-type-btn" data-type="Fire">
                    <div class="icon">🔥</div>
                    <div class="name">Fire</div>
                    <div class="desc">Evacuate now</div>
                </button>
                <button class="alert-type-btn" data-type="Medical">
                    <div class="icon">🏥</div>
                    <div class="name">Medical</div>
                    <div class="desc">Medical emergency</div>
                </button>
                <button class="alert-type-btn" data-type="Evacuation">
                    <div class="icon">🚪</div>
                    <div class="name">Evacuation</div>
                    <div class="desc">Ordered evacuation</div>
                </button>
            </div>

            <div class="form-group">
                <label for="facility">Facility</label>
                <select id="facility">
                    <option value="building-a">Building A</option>
                    <option value="building-b">Building B</option>
                    <option value="campus">Campus</option>
                </select>
            </div>

            <div class="form-group">
                <label for="room">Room/Location</label>
                <input type="text" id="room" value="Room 214" placeholder="e.g., Room 214, Floor 3">
            </div>

            <div class="form-group">
                <label for="message">Additional Details (optional)</label>
                <textarea id="message" rows="2" placeholder="Any additional information..."></textarea>
            </div>

            <button class="activate-btn" id="sendAlert">🚨 SEND ALERT</button>
        </div>
    </div>

    <script>
        const API_BASE = '/api';
        let selectedType = null;

        // Select alert type
        document.querySelectorAll('.alert-type-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.alert-type-btn').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                selectedType = btn.dataset.type;
            });
        });

        // Send alert
        document.getElementById('sendAlert').addEventListener('click', async () => {
            if (!selectedType) {
                showToast('Please select an alert type');
                return;
            }

            const facility = document.getElementById('facility').value;
            const room = document.getElementById('room').value;
            const message = document.getElementById('message').value;

            if (!room) {
                showToast('Please enter a room/location');
                return;
            }

            const btn = document.getElementById('sendAlert');
            btn.disabled = true;
            btn.textContent = 'SENDING...';

            try {
                const response = await fetch(`${API_BASE}/alerts`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: selectedType,
                        facility_id: facility,
                        room: room,
                        message: message
                    })
                });

                if (!response.ok) throw new Error('Failed to send');

                showToast('Alert sent successfully!');

                // Reset
                document.querySelectorAll('.alert-type-btn').forEach(b => b.classList.remove('selected'));
                selectedType = null;
                document.getElementById('message').value = '';
            } catch (error) {
                showToast('Failed to send alert');
            }

            btn.disabled = false;
            btn.textContent = '🚨 SEND ALERT';
        });

        function showToast(message) {
            const existing = document.querySelector('.toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html>