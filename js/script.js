// Toggle class active
const navbarNav = document.querySelector('.navbar-nav');

//ketika hamburger menu 
document.querySelector('#hamburger').onclick = () => {
    navbarNav.classList.toggle('active');
};

// klik di luar menghilangkan nav
const hamburger = document.querySelector('#hamburger');

document.addEventListener('click', function (e) {
    if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
        navbarNav.classList.remove('active');
    }
});

// Dapatkan semua link dalam profile-links
const profileLinks = document.querySelectorAll('.profile-links a');

// Tambahkan event listener untuk setiap link
profileLinks.forEach(link => {
    link.addEventListener('click', function (e) {
        // Cek jika link bukan logout
        if (!this.href.includes('logout.php')) {
            e.preventDefault(); // Mencegah default link behavior

            // Hapus class active dari semua link
            profileLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Tambahkan class active ke link yang diklik
            this.classList.add('active');

            // Tampilkan konten yang sesuai (jika ada)
            const targetId = this.getAttribute('href').replace('#', '');
            const contentSections = document.querySelectorAll('.content-section');

            contentSections.forEach(section => {
                if (section.id === targetId) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }
    });
});