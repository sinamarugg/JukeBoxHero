// Warten bis DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', () => {
    
    // Variabeln Global deklarieren
   // const apiUrl = 'https://257285-5.web.fhgr.ch/unload.php';

    function createChart(data) {
        const labels = data.map(song => song.name);
        const playCounts = data.map(song => song.playCount);

        const ctx = document.getElementById('chart').getContext('2d');
        new Chart(ctx, {  // It should be 'Chart', not 'chart'
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Play Count',
                    data: playCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    async function fetchData() {
        try {
            // Der Pfad zur unload.php muss entsprechend deiner Server-Konfiguration angepasst werden
            const response = await fetch('https://257285-5.web.fhgr.ch/unload.php');
            const data = await response.json();
            // Verarbeite die Daten hier
            console.log(data);

            // Sort the data by play count in descending order
            const sortedData = data.sort((a, b) => b.playCount - a.playCount);

            // Get the top 10 songs
            const top10Songs = sortedData.slice(0, 10);

            // Create a chart using the top 10 songs
            createChart(top10Songs);
        } catch (error) {
            console.error('Fehler beim Abrufen der Daten:', error);
        }
    }

    function createChart(data) {
        const labels = data.map(song => song.name);
        const playCounts = data.map(song => song.playCount);

        const ctx = document.getElementById('mychart').getContext('2d');
        new Chart(ctx, {  // It should be 'Chart', not 'chart'
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Play Count',
                    data: playCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    fetchData();

});

