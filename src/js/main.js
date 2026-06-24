// theme switching functionality
function changeTheme(theme) {
    // set cookie to remember theme preference (expires in 1 year)
    document.cookie = `theme=${theme}; path=/; max-age=${60 * 60 * 24 * 365}`;

    // apply theme to document
    document.documentElement.setAttribute('data-theme', theme);

    // update all theme selectors to match
    const selectors = document.querySelectorAll('select[id$="themeSelect"], select[id$="themeSelectFooter"]');
    selectors.forEach(select => {
        select.value = theme;
    });

    // update theme dots if present
    const dots = document.querySelectorAll('.theme-dot');
    dots.forEach(dot => {
        dot.className = 'theme-dot ' + theme;
    });
}

// initialize theme on page load
document.addEventListener('DOMContentLoaded', function () {
    // check for theme cookie
    const cookies = document.cookie.split(';');
    let theme = 'pink'; // default

    for (let cookie of cookies) {
        const [name, value] = cookie.trim().split('=');
        if (name === 'theme' && (value === 'pink' || value === 'green')) {
            theme = value;
            break;
        }
    }

    // apply theme
    document.documentElement.setAttribute('data-theme', theme);

    // update all selectors
    const selectors = document.querySelectorAll('select[id$="themeSelect"], select[id$="themeSelectFooter"]');
    selectors.forEach(select => {
        select.value = theme;
    });

    // update theme dots
    const dots = document.querySelectorAll('.theme-dot');
    dots.forEach(dot => {
        dot.className = 'theme-dot ' + theme;
    });
});