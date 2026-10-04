
<?php
// weather_dashboard.php
// OpenWeatherMap API
$apiKey = '1b432c95ce3a256b86b4a8958ea9f346';

$cities = [
    'Liverpool,GB' => 'Liverpool, United Kingdom',
    'London,GB' => 'London, United Kingdom',
    'New York,US' => 'New York, United States',
    'Tokyo,JP' => 'Tokyo, Japan',
    'Bangkok,TH' => 'Bangkok, Thailand',
    'Paris,FR' => 'Paris, France',
    'Dubai,AE' => 'Dubai, UAE',
    'Singapore,SG' => 'Singapore',
    'Sydney,AU' => 'Sydney, Australia',
    'Seoul,KR' => 'Seoul, South Korea',
    'Toronto,CA' => 'Toronto, Canada',
    'Rome,IT' => 'Rome, Italy'
];

// ให้ PHP เรียก API แทน JavaScript
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');

    $city = $_GET['city'] ?? 'Liverpool,GB';

    if (!array_key_exists($city, $cities)) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid city']);
        exit;
    }

    $url = 'https://api.openweathermap.org/data/2.5/weather'
        . '?q=' . urlencode($city)
        . '&units=metric'
        . '&lang=th'
        . '&appid=' . urlencode($apiKey);

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true
        ]);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            http_response_code(502);
            echo json_encode(['message' => 'API connection failed: ' . $error]);
            exit;
        }
    } else {
        $context = stream_context_create([
            'http' => ['timeout' => 15, 'ignore_errors' => true]
        ]);
        $response = @file_get_contents($url, false, $context);
        $status = 200;

        if (isset($http_response_header[0]) &&
            preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $status = (int)$matches[1];
        }

        if ($response === false) {
            http_response_code(502);
            echo json_encode(['message' => 'Cannot connect to weather API']);
            exit;
        }
    }

    http_response_code($status);
    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BlueSky Weather Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
:root {
    --bg: #07172e;
    --panel: #102b4d;
    --blue: #258bff;
    --cyan: #5bd7ff;
    --muted: #a9c0dc;
}

* { box-sizing: border-box; }

body {
    margin: 0;
    min-height: 100vh;
    color: #eef7ff;
    font-family: "Segoe UI", Tahoma, sans-serif;
    background: linear-gradient(135deg, #06152c, #0a2a50 55%, #0b3d6d);
}

.navbar {
    background: #041227e8;
    border-bottom: 1px solid #24476e;
}

.brand-icon {
    width: 44px;
    height: 44px;
    display: grid;
    place-items: center;
    border-radius: 14px;
    background: linear-gradient(135deg, #31b9ff, #3867ff);
    font-size: 24px;
}

.panel {
    height: 100%;
    padding: 22px;
    border-radius: 20px;
    background: linear-gradient(145deg, #133153, #0a203d);
    border: 1px solid #77beff29;
    box-shadow: 0 12px 35px #0002;
}

.muted, .small-label { color: var(--muted); }
.small-label { font-size: .85rem; }

.city-select {
    color: white;
    background: #0b2341;
    border: 1px solid #315579;
    border-radius: 12px;
    padding: 10px;
    max-width: 100%;
}

.city-select option {
    background: #0b2341;
}

.btn-refresh {
    color: white;
    background: #1679e9;
    border: none;
    border-radius: 12px;
    padding: 10px 16px;
}

.hero {
    background: linear-gradient(120deg, #124b83, #102b4d);
    overflow: hidden;
}

.temp {
    font-size: clamp(3rem, 5vw, 5rem);
    font-weight: 800;
    line-height: 1.1;
}

.weather-icon {
    width: 100px;
    height: 100px;
}

.kpi-icon {
    width: 42px;
    height: 42px;
    display: grid;
    place-items: center;
    border-radius: 13px;
    background: #49aeff20;
    color: #69d2ff;
    font-size: 22px;
}

.kpi-value {
    font-size: 2rem;
    font-weight: 750;
}

.pill {
    display: inline-block;
    background: #58b3ff1f;
    border: 1px solid #58b3ff38;
    border-radius: 100px;
    padding: 7px 12px;
    font-size: .85rem;
}

.progress {
    height: 8px;
    background: #213f61;
}

.progress-bar {
    background: linear-gradient(90deg, #2c82ff, #54dcff);
}

.chart-wrap {
    height: 230px;
    position: relative;
}

.gauge {
    width: 145px;
    height: 82px;
    overflow: hidden;
    position: relative;
    margin: auto;
}

.gauge svg { width: 100%; height: 100%; }

.gauge-center {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 1.35rem;
    font-weight: 800;
}

.gauge-caption {
    text-align: center;
    color: var(--muted);
    font-size: .85rem;
    margin-top: 8px;
}

#map {
    height: 320px;
    border-radius: 14px;
    z-index: 1;
}

.leaflet-popup-content-wrapper,
.leaflet-popup-tip {
    background: #102b50;
    color: white;
}

.sun-box {
    padding: 16px;
    border-radius: 15px;
    background: #4495e31a;
    border: 1px solid #68beff24;
}

.sun-icon {
    color: #ffd26a;
    font-size: 28px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid #294967;
}

.detail-row:last-child { border-bottom: 0; }

@media(max-width: 767px) {
    .panel { padding: 17px; }
    #map { height: 260px; }
}
</style>
</head>

<body>

<nav class="navbar navbar-dark py-3">
    <div class="container-fluid px-3 px-lg-4">
        <div class="d-flex align-items-center gap-3">
            <div class="brand-icon">
                <i class="bi bi-cloud-sun-fill"></i>
            </div>
            <div>
                <div class="fw-bold fs-5">
                    BlueSky <span style="color:#63d6ff">Weather</span>
                </div>
                <div class="muted small">GLOBAL WEATHER DASHBOARD</div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2 mt-3 mt-lg-0">
            <select id="citySelect" class="city-select">
                <?php foreach ($cities as $key => $label): ?>
                    <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                        <?= $key === 'Liverpool,GB' ? 'selected' : '' ?>>
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn-refresh" id="refreshBtn">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>
</nav>

<main class="container-fluid px-3 px-lg-4 py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <div class="muted small">LIVE CONDITIONS / CURRENT WEATHER</div>
            <h1 class="h4 mt-1">Weather Overview</h1>
        </div>
        <span class="pill">
            <i class="bi bi-circle-fill text-success small"></i>
            <span id="statusText">กำลังโหลดข้อมูล...</span>
        </span>
    </div>

    <div id="errorBox" class="alert alert-danger d-none"></div>

    <!-- Current weather and KPI -->
    <div class="row g-3 mb-3">

        <div class="col-12 col-xl-5">
            <section class="panel hero">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="muted small">CURRENT WEATHER</div>
                        <h2 class="h3 fw-bold mt-2" id="cityName">Liverpool</h2>
                        <div class="muted" id="countryName">United Kingdom</div>

                        <div class="temp mt-4">
                            <span id="temperature">--</span><span style="font-size:.5em">°C</span>
                        </div>

                        <div id="description" class="mt-2">กำลังโหลด...</div>
                        <div class="muted small mt-2">
                            รู้สึกเหมือน <b id="feelsLike">--°C</b>
                        </div>
                    </div>

                    <img id="weatherIcon" class="weather-icon"
                         src="https://openweathermap.org/img/wn/02d@2x.png"
                         alt="Weather icon">
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <span class="pill">ต่ำสุด <b id="tempMin">--°</b></span>
                    <span class="pill">สูงสุด <b id="tempMax">--°</b></span>
                    <span class="pill">เมฆ <b id="clouds">--%</b></span>
                </div>
            </section>
        </div>

        <div class="col-6 col-xl-2">
            <section class="panel">
                <div class="d-flex justify-content-between">
                    <div class="small-label">ความชื้น</div>
                    <div class="kpi-icon"><i class="bi bi-droplet-half"></i></div>
                </div>
                <div class="kpi-value mt-3"><span id="humidity">--</span>%</div>
                <div class="progress mt-3">
                    <div id="humidityBar" class="progress-bar" style="width:0%"></div>
                </div>
                <div class="small-label mt-2">Relative humidity</div>
            </section>
        </div>

        <div class="col-6 col-xl-2">
            <section class="panel">
                <div class="d-flex justify-content-between">
                    <div class="small-label">ความเร็วลม</div>
                    <div class="kpi-icon"><i class="bi bi-wind"></i></div>
                </div>
                <div class="kpi-value mt-3"><span id="wind">--</span></div>
                <div class="small-label">เมตร/วินาที</div>
                <div class="small-label mt-3">
                    ทิศทาง <b id="windDir">--</b> <span id="windDeg">--°</span>
                </div>
            </section>
        </div>

        <div class="col-12 col-xl-3">
            <section class="panel">
                <div class="d-flex justify-content-between">
                    <div class="small-label">ความกดอากาศ</div>
                    <div class="kpi-icon"><i class="bi bi-speedometer2"></i></div>
                </div>
                <div class="kpi-value mt-3"><span id="pressure">--</span></div>
                <div class="small-label">hPa</div>
                <div class="small-label mt-3">
                    ทัศนวิสัย <b id="visibility">--</b> km
                </div>
            </section>
        </div>
    </div>

    <!-- Gauges, chart and sun times -->
    <div class="row g-3 mb-3">

        <div class="col-12 col-lg-4">
            <section class="panel">
                <h2 class="h6 mb-4">
                    <i class="bi bi-speedometer text-info me-2"></i>Weather Gauges
                </h2>

                <div class="row">
                    <div class="col-6">
                        <div class="gauge">
                            <svg viewBox="0 0 160 90">
                                <path d="M15 82 A65 65 0 0 1 145 82"
                                      fill="none" stroke="#244564"
                                      stroke-width="12" stroke-linecap="round"/>
                                <path id="humidityArc"
                                      d="M15 82 A65 65 0 0 1 145 82"
                                      fill="none" stroke="#51d8ff"
                                      stroke-width="12" stroke-linecap="round"
                                      stroke-dasharray="0 204.2"/>
                            </svg>
                            <div class="gauge-center"><span id="gHumidity">--</span>%</div>
                        </div>
                        <div class="gauge-caption">ความชื้น</div>
                    </div>

                    <div class="col-6">
                        <div class="gauge">
                            <svg viewBox="0 0 160 90">
                                <path d="M15 82 A65 65 0 0 1 145 82"
                                      fill="none" stroke="#244564"
                                      stroke-width="12" stroke-linecap="round"/>
                                <path id="windArc"
                                      d="M15 82 A65 65 0 0 1 145 82"
                                      fill="none" stroke="#6d9cff"
                                      stroke-width="12" stroke-linecap="round"
                                      stroke-dasharray="0 204.2"/>
                            </svg>
                            <div class="gauge-center"><span id="gWind">--</span></div>
                        </div>
                        <div class="gauge-caption">ลม (m/s)</div>
                    </div>
                </div>

                <hr style="border-color:#294967">

                <div class="d-flex justify-content-between small mb-2">
                    <span class="muted">ปริมาณเมฆ</span>
                    <b id="cloudLabel">--%</b>
                </div>
                <div class="progress">
                    <div id="cloudBar" class="progress-bar" style="width:0%"></div>
                </div>

                <div class="d-flex justify-content-between small mt-3 mb-2">
                    <span class="muted">อุณหภูมิ (ช่วง 0–45°C)</span>
                    <b id="tempScaleLabel">--</b>
                </div>
                <div class="progress">
                    <div id="tempBar" class="progress-bar" style="width:0%"></div>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-4">
            <section class="panel">
                <h2 class="h6 mb-1">
                    <i class="bi bi-pie-chart text-info me-2"></i>สัดส่วนสภาพท้องฟ้า
                </h2>
                <div class="small-label mb-3">Cloud cover vs. clear sky</div>
                <div class="chart-wrap"><canvas id="cloudChart"></canvas></div>
                <div class="small-label text-center mt-2" id="cloudSummary">กำลังโหลด...</div>
            </section>
        </div>

        <div class="col-12 col-lg-4">
            <section class="panel">
                <h2 class="h6 mb-3">
                    <i class="bi bi-sun text-warning me-2"></i>ดวงอาทิตย์
                </h2>

                <div class="sun-box d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="small-label">พระอาทิตย์ขึ้น</div>
                        <div class="fs-4 fw-bold" id="sunrise">--:--</div>
                    </div>
                    <i class="bi bi-sunrise sun-icon"></i>
                </div>

                <div class="sun-box d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small-label">พระอาทิตย์ตก</div>
                        <div class="fs-4 fw-bold" id="sunset">--:--</div>
                    </div>
                    <i class="bi bi-sunset sun-icon"></i>
                </div>

                <div class="small-label mt-3">
                    เวลาอัปเดต <b id="updatedAt">--</b>
                </div>
                <div class="small-label mt-2">
                    UTC offset <b id="timezone">--</b>
                </div>
            </section>
        </div>
    </div>

    <!-- Map and detailed weather -->
    <div class="row g-3">

        <div class="col-12 col-lg-7">
            <section class="panel">
                <h2 class="h6 mb-2">
                    <i class="bi bi-geo-alt text-info me-2"></i>ตำแหน่งบนแผนที่
                </h2>
                <div class="small-label mb-3">พิกัด Latitude / Longitude</div>

                <div id="map"></div>

                <div class="d-flex flex-wrap gap-3 mt-3 small-label">
                    <span>Lat: <b id="lat">--</b></span>
                    <span>Lon: <b id="lon">--</b></span>
                    <a id="mapLink" href="#" target="_blank" rel="noopener"
                       class="link-info ms-auto">เปิดแผนที่ ↗</a>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-5">
            <section class="panel">
                <h2 class="h6 mb-2">
                    <i class="bi bi-bar-chart-line text-info me-2"></i>รายละเอียดเพิ่มเติม
                </h2>
                <div class="small-label mb-2">ข้อมูลสภาพอากาศปัจจุบัน</div>

                <div class="detail-row">
                    <span class="muted">อุณหภูมิที่รู้สึกได้</span>
                    <b id="detailFeels">--°C</b>
                </div>
                <div class="detail-row">
                    <span class="muted">Weather main</span>
                    <b id="weatherMain">--</b>
                </div>
                <div class="detail-row">
                    <span class="muted">Description</span>
                    <b id="weatherDesc" class="text-end">--</b>
                </div>
                <div class="detail-row">
                    <span class="muted">ลมกระโชก</span>
                    <b id="gust">-- m/s</b>
                </div>
                <div class="detail-row">
                    <span class="muted">ความกดอากาศระดับน้ำทะเล</span>
                    <b id="seaLevel">-- hPa</b>
                </div>
            </section>
        </div>
    </div>

    <footer class="text-center muted small py-4">
        Weather data by OpenWeatherMap · Map © OpenStreetMap contributors
    </footer>
</main>

<script>
const citySelect = document.getElementById('citySelect');

let map;
let marker;
let cloudChart;

function setText(id, value) {
    document.getElementById(id).textContent = value;
}

function formatTime(timestamp, offset) {
    if (!timestamp) return '--:--';

    // ปรับ Unix timestamp ด้วย UTC offset ของเมือง
    return new Date((timestamp + offset) * 1000)
        .toLocaleTimeString('en-GB', {
            timeZone: 'UTC',
            hour: '2-digit',
            minute: '2-digit'
        });
}

function windDirection(degrees) {
    if (degrees == null) return '--';

    return ['N','NE','E','SE','S','SW','W','NW']
        [Math.round(degrees / 45) % 8];
}

function setGauge(id, value, max) {
    const circumference = 204.2;
    const portion = Math.max(0, Math.min(Number(value) / max, 1))
        * circumference;

    document.getElementById(id)
        .setAttribute('stroke-dasharray', `${portion} ${circumference}`);
}

function initializeMap() {
    map = L.map('map').setView([53.4084, -2.9916], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    marker = L.marker([53.4084, -2.9916])
        .addTo(map)
        .bindPopup('Liverpool');
}

function initializeChart() {
    cloudChart = new Chart(document.getElementById('cloudChart'), {
        type: 'pie',
        data: {
            labels: ['เมฆปกคลุม', 'ท้องฟ้าโปร่ง'],
            datasets: [{
                data: [0, 100],
                backgroundColor: ['#53d7ff', '#31517b'],
                borderColor: '#102b50',
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#c6d9ef',
                        padding: 18,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: context =>
                            `${context.label}: ${context.raw}%`
                    }
                }
            }
        }
    });
}

async function loadWeather() {
    const city = citySelect.value;
    const errorBox = document.getElementById('errorBox');
    const refreshBtn = document.getElementById('refreshBtn');

    errorBox.classList.add('d-none');
    setText('statusText', 'กำลังอัปเดต...');
    refreshBtn.disabled = true;

    try {
        // เรียก PHP endpoint แทนการเปิดเผย API Key ใน JavaScript
        const response = await fetch(
            `<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>?api=1&city=${encodeURIComponent(city)}`
        );

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || `HTTP ${response.status}`);
        }

        const main = data.main || {};
        const weather = data.weather?.[0] || {};
        const wind = data.wind || {};
        const sys = data.sys || {};
        const coord = data.coord || {};
        const clouds = data.clouds?.all ?? 0;
        const humidity = main.humidity ?? 0;
        const temperature = main.temp ?? 0;
        const offset = data.timezone ?? 0;

        setText('cityName', data.name || city);
        setText('countryName', sys.country || '');
        setText('temperature', Math.round(temperature));
        setText('feelsLike', `${Math.round(main.feels_like ?? temperature)}°C`);
        setText('description', weather.description || weather.main || '--');

        document.getElementById('weatherIcon').src = weather.icon
            ? `https://openweathermap.org/img/wn/${weather.icon}@2x.png`
            : 'https://openweathermap.org/img/wn/02d@2x.png';

        setText('tempMin', `${Math.round(main.temp_min ?? temperature)}°`);
        setText('tempMax', `${Math.round(main.temp_max ?? temperature)}°`);
        setText('clouds', `${clouds}%`);

        setText('humidity', humidity);
        document.getElementById('humidityBar').style.width = `${humidity}%`;

        setText('wind', Number(wind.speed ?? 0).toFixed(1));
        setText('windDir', windDirection(wind.deg));
        setText('windDeg', wind.deg != null ? `${wind.deg}°` : '--');

        setText('pressure', main.pressure ?? '--');
        setText('visibility',
            data.visibility != null
                ? (data.visibility / 1000).toFixed(1)
                : '--'
        );

        setText('gHumidity', humidity);
        setText('gWind', Number(wind.speed ?? 0).toFixed(1));

        setGauge('humidityArc', humidity, 100);
        setGauge('windArc', wind.speed ?? 0, 20);

        setText('cloudLabel', `${clouds}%`);
        document.getElementById('cloudBar').style.width = `${clouds}%`;

        const tempPercent = Math.max(0, Math.min(45, temperature)) / 45 * 100;
        document.getElementById('tempBar').style.width = `${tempPercent}%`;
        setText('tempScaleLabel', `${Math.round(temperature)}°C`);

        cloudChart.data.datasets[0].data = [clouds, 100 - clouds];
        cloudChart.update();

        setText('cloudSummary',
            `เมฆปกคลุม ${clouds}% · ท้องฟ้าโปร่ง ${100 - clouds}%`
        );

        setText('sunrise', formatTime(sys.sunrise, offset));
        setText('sunset', formatTime(sys.sunset, offset));
        setText('updatedAt', formatTime(data.dt, offset));
        setText('timezone',
            `${offset >= 0 ? '+' : ''}${(offset / 3600).toFixed(1)} ชั่วโมง`
        );

        setText('lat', coord.lat ?? '--');
        setText('lon', coord.lon ?? '--');

        document.getElementById('mapLink').href =
            `https://www.openstreetmap.org/?mlat=${coord.lat}&mlon=${coord.lon}#map=10/${coord.lat}/${coord.lon}`;

        if (coord.lat != null && coord.lon != null) {
            map.setView([coord.lat, coord.lon], 10);

            marker.setLatLng([coord.lat, coord.lon])
                .bindPopup(
                    `<b>${data.name || city}</b><br>` +
                    `${weather.description || ''}<br>` +
                    `${Math.round(temperature)}°C`
                );

            setTimeout(() => map.invalidateSize(), 100);
        }

        setText('detailFeels', `${Math.round(main.feels_like ?? temperature)}°C`);
        setText('weatherMain', weather.main || '--');
        setText('weatherDesc', weather.description || '--');
        setText('gust',
            wind.gust != null ? `${Number(wind.gust).toFixed(1)} m/s` : '--'
        );
        setText('seaLevel', main.sea_level ?? '--');

        setText('statusText', `อัปเดต ${formatTime(data.dt, offset)}`);

    } catch (error) {
        errorBox.textContent =
            `โหลดข้อมูลไม่สำเร็จ: ${error.message} ` +
            'กรุณาตรวจสอบ API Key, อินเทอร์เน็ต และการตั้งค่า PHP';
        errorBox.classList.remove('d-none');
        setText('statusText', 'เชื่อมต่อไม่สำเร็จ');
    } finally {
        refreshBtn.disabled = false;
    }
}

initializeMap();
initializeChart();

citySelect.addEventListener('change', loadWeather);
document.getElementById('refreshBtn').addEventListener('click', loadWeather);

loadWeather();
</script>

</body>
</html>