// Team page - loads team members from the WordPress REST API

const TEAM_ENDPOINT = '/wp-json/hh/v1/team';

function render(team) {
    const container = document.getElementById('team-list');
    container.innerHTML = team.map(person => `
        <div class="team-member">
            <h3>${person.name}</h3>
            <p class="suburb">${person.suburb || ''}</p>
        </div>
    `).join('');
}

fetch(TEAM_ENDPOINT)
    .then(response => response.json())
    .then(data => render(data));
