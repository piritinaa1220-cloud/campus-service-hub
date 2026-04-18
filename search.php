<?php include 'includes/header.php'; ?>

<div class="card card-custom p-4">
    <h2 class="mb-3">Live Search Services</h2>
    <input type="text" id="searchInput" class="form-control mb-4" placeholder="Search by title, description, or owner name...">
    <div id="searchResults"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    function loadResults(query = '') {
        fetch('<?php echo BASE_URL; ?>/ajax/live_search.php?q=' + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                searchResults.innerHTML = data;
            })
            .catch(() => {
                searchResults.innerHTML = '<div class="alert alert-danger">Search failed. Please try again.</div>';
            });
    }

    searchInput.addEventListener('keyup', function () {
        loadResults(this.value);
    });

    loadResults('');
});
</script>

<?php include 'includes/footer.php'; ?>