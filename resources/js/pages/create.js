document.getElementById('searchBtn').addEventListener('click', function() {
    const artist = document.getElementById('artist').value;
    const album = document.getElementById('title').value;
    
    if (!artist || !album) {
        alert('Please fill in Artist and Album Title first');
        return;
    }
    
    document.getElementById('searchBtn').textContent = '⏳ Searching...';
    
    fetch('{{ route("search") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ artist: artist, album: album })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('searchBtn').textContent = '🔍 Search on Last.fm';
        
        if (data.success) {
            if (data.description) {
                document.getElementById('description').value = data.description;
            }
            if (data.cover_url) {
                document.getElementById('cover_url').value = data.cover_url;
            }
            if (data.lastfm_url) {
                document.getElementById('lastfm_url').value = data.lastfm_url;
            }
            alert('✅ Data loaded from Last.fm!');
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        document.getElementById('searchBtn').textContent = '🔍 Search on Last.fm';
        alert('Error connecting to server');
    });
});
