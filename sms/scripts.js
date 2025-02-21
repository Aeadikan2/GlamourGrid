// Fetch available stylists
function fetchAvailableStylists() {
    fetch('fetch_stylists.php')
        .then(response => response.json())
        .then(stylists => {
            const stylistList = document.getElementById('available-stylists');
            stylistList.innerHTML = '';
            stylists.forEach(stylist => {
                const listItem = document.createElement('li');
                listItem.textContent = `${stylist.name} (${stylist.specialization})`;
                stylistList.appendChild(listItem);
            });
        })
        .catch(error => console.error('Error fetching stylists:', error));
}

// Fetch the client queue
function fetchQueue() {
    fetch('fetch_queue.php')
        .then(response => response.json())
        .then(queue => {
            const queueList = document.getElementById('queue-list');
            queueList.innerHTML = '';
            queue.forEach(client => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${client.queue_position}</td>
                    <td>${client.client_name}</td>
                    <td>${client.stylist_name || 'Not Assigned'}</td>
                `;
                queueList.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching queue:', error));
}

// Periodically update the displays
setInterval(fetchAvailableStylists, 30000); // Update stylists every 30 seconds
setInterval(fetchQueue, 15000); // Update queue every 15 seconds

// Initial load
fetchAvailableStylists();
fetchQueue();



