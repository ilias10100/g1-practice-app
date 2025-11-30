</div> <!-- container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const progressElements = document.querySelectorAll('.circle-progress');
        progressElements.forEach(el => {
            const progress = el.dataset.progress;
            el.style.setProperty('--progress', progress + '%');
        });
    });
</script>
</body>
</html>
