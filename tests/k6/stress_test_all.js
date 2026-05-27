import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    vus: 5,
    duration: '20s',

    thresholds: {
        // dilonggarkan karena Railway free tier + query UMKM lebih berat
        http_req_duration: ['p(95)<25000'],
        http_req_failed: ['rate<0.30'],
    },
};

const BASE_URL = 'https://sisehat-web-production.up.railway.app';

// =====================================================
// AMBIL CSRF TOKEN
// =====================================================

function getCsrfToken(html) {
    const match = html.match(/name="_token"\s+value="(.+?)"/);
    return match ? match[1] : null;
}

// =====================================================
// MAIN TEST
// =====================================================

export default function () {

    // =====================================================
    // COOKIE SESSION LARAVEL
    // =====================================================

    const jar = http.cookieJar();

    // =====================================================
    // 1. GET LOGIN
    // =====================================================

    const loginPage = http.get(
        `${BASE_URL}/login`,
        {
            jar,
            timeout: '60s',
        }
    );

    check(loginPage, {
        'GET /login status 200': (r) =>
            r.status === 200,
    });

    const csrfToken = getCsrfToken(loginPage.body);

    if (!csrfToken) {
        console.log('❌ CSRF token tidak ditemukan');
        return;
    }

    sleep(2);

    // =====================================================
    // 2. LOGIN
    // =====================================================

    const payload = {
        _token: csrfToken,

        // login pakai nama_user
        email: __ENV.LOGIN_USER || 'Kodlak',

        password: __ENV.LOGIN_PASS || 'Pass12345',
    };

    const loginRes = http.post(
        `${BASE_URL}/login`,
        payload,
        {
            jar,

            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },

            redirects: 0,

            timeout: '60s',
        }
    );

    console.log(`STATUS LOGIN: ${loginRes.status}`);

    check(loginRes, {
        'POST /login berhasil': (r) =>
            r.status === 302,
    });

    sleep(2);

    // =====================================================
    // 3. DASHBOARD
    // =====================================================

    const dashboard = http.get(
        `${BASE_URL}/dashboard`,
        {
            jar,
            timeout: '60s',
        }
    );

    check(dashboard, {
        'GET /dashboard status 200': (r) =>
            r.status === 200,
    });

    sleep(2);

    // =====================================================
    // 4. ENAM FAKTOR
    // =====================================================

    const faktor = http.get(
        `${BASE_URL}/enam-faktor`,
        {
            jar,
            timeout: '60s',
        }
    );

    check(faktor, {
        'GET /enam-faktor status 200': (r) =>
            r.status === 200,
    });

    sleep(2);

    // =====================================================
    // 5. REKOMENDASI
    // =====================================================

    const rekomendasi = http.get(
        `${BASE_URL}/rekomendasi`,
        {
            jar,
            timeout: '60s',
        }
    );

    check(rekomendasi, {
        'GET /rekomendasi status 200': (r) =>
            r.status === 200,
    });

    sleep(3);
}