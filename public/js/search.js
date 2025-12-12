// Ajax Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;
    
    if (!searchInput || !searchResults) return;
    
    // Show/hide results on focus/blur
    searchInput.addEventListener('focus', function() {
        if (this.value.length >= 2) {
            searchResults.style.display = 'block';
        }
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Handle search input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }
        
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    });
    
    // Handle Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            e.preventDefault();
            window.location.href = `/products?search=${encodeURIComponent(this.value)}`;
        }
    });
    
    // Perform Ajax search
    function performSearch(query) {
        fetch(`/search/products?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            displayResults(data, query);
        })
        .catch(error => {
            console.error('Search error:', error);
            searchResults.innerHTML = '<div class="no-results">Search temporarily unavailable</div>';
            searchResults.style.display = 'block';
        });
    }
    
    // Display search results
    function displayResults(results, query) {
        if (results.length === 0) {
            searchResults.innerHTML = `
                <div class="no-results">
                    <i class="bi bi-search mb-2"></i>
                    <p>No flowers found for "${query}"</p>
                    <small class="text-muted">Try different keywords</small>
                </div>
            `;
            searchResults.style.display = 'block';
            return;
        }
        
        let html = '';
        results.forEach(product => {
            html += `
                <a href="${product.url}" class="search-result-item d-flex align-items-center">
                    <img src="${product.image}" alt="${product.name}" class="search-result-image">
                    <div class="search-result-info">
                        <div class="search-result-name">${product.name}</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="search-result-category">${product.category}</span>
                            <span class="search-result-price">$${product.price}</span>
                        </div>
                    </div>
                </a>
            `;
        });
        
        // Add view all results link
        html += `
            <a href="/products?search=${encodeURIComponent(query)}" class="search-result-item text-center text-primary">
                <i class="bi bi-arrow-right"></i> View all results for "${query}"
            </a>
        `;
        
        searchResults.innerHTML = html;
        searchResults.style.display = 'block';
    }
});