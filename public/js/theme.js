// assets/js/theme.js

const root = document.documentElement;
const THEME_KEY = 'xd_theme_mode'; // 'auto' | 'dark' | 'light'
const mq = window.matchMedia('(prefers-color-scheme: dark)');

function applyTheme(mode) {
    if (mode === 'light') {
        root.setAttribute('data-theme', 'light');
    } else if (mode === 'dark') {
        root.setAttribute('data-theme', 'dark');
    } else {
        // auto = حسب إعداد النظام
        root.setAttribute('data-theme', mq.matches ? 'dark' : 'light');
    }
    updateToggleIcon(mode);
}

function updateToggleIcon(mode) {
    const btn = document.querySelector('.theme-toggle-btn');
    if (!btn) return;

    if (mode === 'light') {
        btn.textContent = '☀️';
    } else if (mode === 'dark') {
        btn.textContent = '🌙';
    } else {
        btn.textContent = '🌓';
    }
}

function getNextMode(current) {
    if (current === 'auto') return 'dark';
    if (current === 'dark') return 'light';
    return 'auto'; // لو كان light يرجع auto
}

function loadInitialTheme() {
    const saved = localStorage.getItem(THEME_KEY);
    const mode = ['light', 'dark', 'auto'].includes(saved) ? saved : 'auto';
    applyTheme(mode);

    if (mode === 'auto') {
        mq.addEventListener('change', () => applyTheme('auto'));
    }
}

function setMode(mode) {
    localStorage.setItem(THEME_KEY, mode);

    if (mode === 'auto') {
        mq.addEventListener('change', () => applyTheme('auto'));
    }

    applyTheme(mode);
}

document.addEventListener('DOMContentLoaded', () => {
    loadInitialTheme();

    const themeBtn = document.querySelector('.theme-toggle-btn');
    if (!themeBtn) return;

    themeBtn.addEventListener('click', () => {
        const current = localStorage.getItem(THEME_KEY) || 'auto';
        const next = getNextMode(current);
        setMode(next);
    });
});
