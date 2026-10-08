document.addEventListener("DOMContentLoaded", () => {
  document.body.classList.add("loaded");

  const header = document.querySelector(".site-header");
  const nav = document.querySelector(".nav");
  const menu = document.querySelector(".menu-toggle");
  const cursor = document.querySelector(".cursor-glow");

  // Mobile navigation
  const closeMenu = () => {
    nav.classList.remove("open");
    menu?.setAttribute("aria-expanded", "false");
    document.body.classList.remove("menu-open");
  };

  menu?.addEventListener("click", () => {
    const open = nav.classList.toggle("open");
    menu.setAttribute("aria-expanded", open);
    document.body.classList.toggle("menu-open", open);
  });

  document.querySelectorAll(".nav a").forEach(link => {
    link.addEventListener("click", closeMenu);
  });

  document.addEventListener("keydown", event => {
    if (event.key === "Escape") closeMenu();
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth > 900) closeMenu();
  }, {passive:true});

  // Header state
  const onScroll = () => {
    header.classList.toggle("scrolled", window.scrollY > 30);
  };
  window.addEventListener("scroll", onScroll, {passive:true});
  onScroll();

  // Smooth reveal using IntersectionObserver (GPU-friendly, no scroll-heavy animation)
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        obs.unobserve(entry.target);
      }
    });
  }, {threshold:0.12, rootMargin:"0px 0px -50px 0px"});

  document.querySelectorAll(".reveal").forEach(el => observer.observe(el));

  // Active navigation section
  const sections = [...document.querySelectorAll("main section[id]")];
  const links = [...document.querySelectorAll(".nav-link")];

  const sectionObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        links.forEach(link => link.classList.toggle(
          "active", link.getAttribute("href") === `#${entry.target.id}`
        ));
      }
    });
  }, {rootMargin:"-40% 0px -50% 0px", threshold:0});

  sections.forEach(section => sectionObserver.observe(section));

  // Lightweight cursor glow — disabled for touch devices.
  if (cursor && matchMedia("(pointer:fine)").matches) {
    let x = innerWidth / 2, y = innerHeight / 2, tx = x, ty = y;
    window.addEventListener("pointermove", e => { tx = e.clientX; ty = e.clientY; }, {passive:true});

    const animateCursor = () => {
      x += (tx - x) * 0.12;
      y += (ty - y) * 0.12;
      cursor.style.left = `${x}px`;
      cursor.style.top = `${y}px`;
      requestAnimationFrame(animateCursor);
    };
    animateCursor();
  } else if (cursor) cursor.style.display = "none";

  // Magnetic CTA — only on desktop, uses transforms for smooth GPU rendering.
  if (matchMedia("(pointer:fine)").matches) {
    document.querySelectorAll(".magnetic").forEach(btn => {
      btn.addEventListener("pointermove", e => {
        const r = btn.getBoundingClientRect();
        const dx = (e.clientX - (r.left + r.width / 2)) * 0.12;
        const dy = (e.clientY - (r.top + r.height / 2)) * 0.12;
        btn.style.transform = `translate3d(${dx}px,${dy}px,0)`;
      });
      btn.addEventListener("pointerleave", () => {
        btn.style.transform = "";
      });
    });
  }
});
