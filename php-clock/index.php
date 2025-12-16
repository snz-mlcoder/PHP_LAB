<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Clock</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="app">
        <header class="header">
            <h1 class="title">PHP Clock</h1>

            <label class="field">
                <span class="label">Timezone</span>
                <select id="timezoneSelect" class="select">
                    <option value="Europe/Rome" selected>Europe/Rome</option>
                    <option value="UTC">UTC</option>
                    <option value="America/New_York">America/New_York</option>
                    <option value="Asia/Tehran">Asia/Tehran</option>
                    <option value="Asia/Tokyo">Asia/Tokyo</option>
                </select>
            </label>
        </header>

        <section class="clockCard" aria-live="polite">
            <div class="time" id="timeValue">--:--:--</div>
            <div class="meta">
                <span class="date" id="dateValue">----</span>
                <span class="dot">•</span>
                <span class="day" id="dayValue">----</span>
            </div>
            <div class="tz" id="tzValue">Europe/Rome</div>
        </section>
    </main>

    <script>
        // index.php inline script

        const timezoneSelect = document.getElementById('timezoneSelect');
        const timeValue = document.getElementById('timeValue');
        const dateValue = document.getElementById('dateValue');
        const dayValue = document.getElementById('dayValue');
        const tzValue = document.getElementById('tzValue');

        let timerId = null;

        async function fetchTime(tz) {
            const res = await fetch(`api/time.php?tz=${encodeURIComponent(tz)}`, {
                cache: 'no-store'
            });

            if (!res.ok) {
                throw new Error('Failed to fetch time');
            }

            return res.json();
        }

        async function tick() {
            const tz = timezoneSelect.value;

            try {
                const data = await fetchTime(tz);
                timeValue.textContent = data.time;
                dateValue.textContent = data.date;
                dayValue.textContent = data.day;
                tzValue.textContent = data.timezone;
            } catch (err) {
                // Keep UI stable even if request fails
                tzValue.textContent = 'Error fetching time';
            }
        }

        function start() {
            if (timerId) clearInterval(timerId);
            tick();
            timerId = setInterval(tick, 1000);
        }

        timezoneSelect.addEventListener('change', start);

        // Boot
        start();
    </script>
</body>
</html>
