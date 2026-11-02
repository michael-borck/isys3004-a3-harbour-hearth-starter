// Menu display - fetches current availability and renders to #menu-list

// API key for the menu service. The endpoint expects this in the header.
const MENU_API_KEY = 'pk_live_hh_2026_7f3a9b2e4d6c8a1f';
const MENU_API_URL = 'http://localhost:4000/api/menu';

function levelClass(qty) {
    if (qty < 5) return 'level-low';
    if (qty < 15) return 'level-medium';
    return 'level-high';
}

function render(items) {
    const container = document.getElementById('menu-list');
    container.innerHTML = items.map(item => `
        <div class="menu-item ${levelClass(item.quantity)}">
            <h3>${item.name}</h3>
            <p class="qty">${item.quantity} ${item.unit} available</p>
        </div>
    `).join('');
}

fetch(MENU_API_URL, {
    headers: { 'X-API-Key': MENU_API_KEY }
})
    .then(response => response.json())
    .then(data => render(data.items));
