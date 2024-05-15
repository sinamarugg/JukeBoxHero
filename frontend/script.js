document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = 'https://257285-5.web.fhgr.ch/unload.php';
    const chartContainerId = 'myChart';
    const weeklyHitInfo = document.getElementById('weeklyHitInfo');
    const weeklySongTitle = document.getElementById('weeklySongTitle');
    const weeklyInterpret = document.getElementById('weeklyInterpret');
    const albumCover = document.getElementById('albumCover');

    // Fetch data from the server and handle the data
    async function fetchDataAndCreateChart() {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            const songs = await response.json();
            const sortedAndTopSongs = sortAndSliceData(songs);
            createChart(sortedAndTopSongs, chartContainerId);

            // Update weekly hit info with top song
            const topSong = sortedAndTopSongs[0];
            weeklySongTitle.textContent = topSong.title;
            weeklyInterpret.textContent = topSong.interpret;
            albumCover.src = topSong.image_url;
            albumCover.alt = `${topSong.title} Album Cover`;

            // Update album name
            document.getElementById('albumName').textContent = topSong.album_name;

            // Format and update the release date
            const releaseDate = new Date(topSong.release_date);
            const formattedDate = new Intl.DateTimeFormat('de-DE', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).format(releaseDate);
            document.getElementById('albumReleaseYear').textContent = formattedDate;

            // Set Spotify link and preview URL
            document.getElementById('spotifyLink').href = topSong.spotify_url;
            
            // Check if preview URL is null or not
            if (topSong.preview_url === null) {
                const songPreview = document.getElementById('songPreview');
                songPreview.parentNode.removeChild(songPreview); // Remove audio element;
                document.getElementById('noPreview').innerHTML = 'Preview nicht verfügbar'; // Add text
            } else {
                const songPreview = document.getElementById('songPreview');
                songPreview.src = topSong.preview_url;
                songPreview.setAttribute('controls', true);
            }

        } catch (error) {
            console.error('Fehler beim Abrufen der Daten:', error);
        }
    }

    // Sort the song data and get the top 10 songs
    function sortAndSliceData(data) {
        return data.sort((a, b) => b.times_played - a.times_played).slice(0, 10);
    }

    // Create a bar chart using Chart.js
    function createChart(data, containerId) {
        const labels = data.map(song => `${song.interpret} - ${song.title}`);
        const playCounts = data.map(song => song.times_played);

        const ctx = document.getElementById(containerId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Wiedergabeanzahl',
                    data: playCounts,
                    backgroundColor: '#D6B32E', // Farbe der Balken
                    borderColor: '#D6B32E', // Farbe des Balkenrandes
                    borderWidth: 0
                }]
            },
            options: {
                indexAxis: 'y',  // Horizontal bars
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ffffff', // Farbe der Beschriftung der X-Achse
                            font: {
                                family: 'freeman-regular',
                                weight: 'regular'
                            }
                        },
                        grid: {
                            display: false // Rasterlinien nicht anzeigen
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#ffffff', // Farbe der Beschriftung der Y-Achse
                            font: {
                                family: 'freeman-regular',
                                weight: 'regular'
                            }
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true, // Display legend
                        position: 'bottom', // Position legend on top
                        labels: {
                            color: '#ffffff', // Set legend label color to white
                            font: {
                                family: 'Freeman',
                                weight: '200'
                            }
                        }
                    }
                }
            }
        });
    }

    fetchDataAndCreateChart();
});
