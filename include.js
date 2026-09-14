// Load header
fetch("header.html")
  .then(response => {
    if (!response.ok) throw new Error("Header not found");
    return response.text();
  })
  .then(data => {
    document.getElementById("header").innerHTML = data;

    // Hamburger toggle
    const hamburger = document.querySelector(".hamburger");
    const navLinks = document.querySelector(".nav-links");
    if (hamburger && navLinks) {
      hamburger.addEventListener("click", () => {
        navLinks.classList.toggle("active");
      });
    }

    // Active nav link — runs after header is injected
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.nav-links a:not(.nav-cta)').forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('nav-active');
      }
    });
  })
  .catch(err => console.error("Header load error:", err));

// Load footer
fetch("footer.html")
  .then(response => {
    if (!response.ok) throw new Error("Footer not found");
    return response.text();
  })
  .then(data => {
    document.getElementById("footer").innerHTML = data;
  })
  .catch(err => console.error("Footer load error:", err));
