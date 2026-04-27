async function loadServices() {
  const res = await fetch('../api/services.php');
  const services = await res.json();
  const grid = document.getElementById('services-grid');
  grid.innerHTML = '';

  services.forEach((service) => {
    const priceText = service.mostrar_precio
      ? `$${service.precio.toFixed(2)}`
      : (window.APP_LANG === 'en' ? 'Ask in store' : 'Consultar en sucursal');

    const card = document.createElement('article');
    card.className = 'card';
    card.innerHTML = `
      <h3>${service.nombre}</h3>
      <p>${service.descripcion}</p>
      <p><strong>${service.duracion_minutos} min</strong></p>
      <p>${priceText}</p>
      <button>${window.APP_LANG === 'en' ? 'Book' : 'Agendar'}</button>
    `;
    grid.appendChild(card);
  });
}

function enableScrollSpy() {
  const sections = document.querySelectorAll('main section[id]');
  const links = document.querySelectorAll('.nav a');

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      links.forEach((link) => {
        link.classList.toggle('active', link.getAttribute('href') === `#${entry.target.id}`);
      });
    });
  }, { threshold: 0.45 });

  sections.forEach((section) => observer.observe(section));
}

document.addEventListener('DOMContentLoaded', () => {
  loadServices();
  enableScrollSpy();
});
