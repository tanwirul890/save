<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>

AOS.init({
    duration: 1000,
    once: true
});

document.getElementById('burger').addEventListener('click', function() {
    this.classList.toggle('active');
    document.getElementById('sidebar').classList.toggle('active');
});

document.getElementById('sidebar').addEventListener('click', function(e) {
    if (e.target.tagName === 'A') {
        document.getElementById('burger').classList.remove('active');
        this.classList.remove('active');
    }
});
</script>


</body>
</html>