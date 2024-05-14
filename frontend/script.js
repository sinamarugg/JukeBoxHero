document.addEventListener('DOMContentLoaded', () => {
    const apiUrl = 'https://257285-5.web.fhgr.ch/unload.php';
    const chartContainerId = 'myChart';
    const weeklyHitInfo = document.getElementById('weeklyHitInfo');
    const weeklySongTitle = document.getElementById('weeklySongTitle');
    const weeklyInterpret = document.getElementById('weeklyInterpret');
    const albumCover = document.getElementById('albumCover');
    const albumInfo = document.getElementById('albumInfo');
    const albumYear = document.getElementById('albumYear');
    const albumName = document.getElementById('albumName');
    const spotifyLink = document.getElementById('spotifyLink');

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
            albumCover.src = topSong.albumCoverUrl;
            albumCover.alt = `${topSong.title} Album Cover`;

            // Update album info
            albumInfo.textContent = `${topSong.albumName} (${topSong.albumYear})`;
            albumYear.textContent = topSong.albumYear;
            albumName.textContent = topSong.albumName;

            // Update Spotify link
            spotifyLink.href = topSong.spotifyLink;
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
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',  // Horizontal bars
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

    fetchDataAndCreateChart();
});
