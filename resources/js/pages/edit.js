document.getElementById('searchBtn').addEventListener('click', function() {
    const albumTitle = document.getElementById('search_title').value;
    const msgDiv = document.getElementById('searchMessage');
     const url = this.getAttribute('data-url'); 
    
    if (!albumTitle) {
        msgDiv.className = 'search-message error';
        msgDiv.innerHTML = '❌ Please enter album title';
        return;
    }
    
    msgDiv.className = 'search-message';
    msgDiv.innerHTML = '⏳ Searching...';
    msgDiv.style.display = 'block';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ title: albumTitle })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('album_id').value = data.album.id;
            document.getElementById('artist').value = data.album.artist;
            document.getElementById('title').value = data.album.title;
            document.getElementById('description').value = data.album.description || '';
            document.getElementById('cover_url').value = data.album.cover_url || '';
            document.getElementById('lastfm_url').value = data.album.lastfm_url || '';
            
            msgDiv.className = 'search-message success';
            msgDiv.innerHTML = '✅ Album found! You can now edit.';
        } else {
            msgDiv.className = 'search-message error';
            msgDiv.innerHTML = '❌ ' + data.message;
        }
        setTimeout(() => { msgDiv.className = 'search-message'; }, 3000);
    })
    .catch(error => {
        msgDiv.className = 'search-message error';
        msgDiv.innerHTML = '❌ Connection error';
        setTimeout(() => { msgDiv.className = 'search-message'; }, 3000);
    });
});