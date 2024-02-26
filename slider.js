let currentSlide = 0;

function showSlide(index) {
  const slider = document.querySelector('.slider');
  const slideWidth = document.querySelector('.slide').offsetWidth;
  currentSlide = (index + slider.children.length) % slider.children.length;
  slider.style.transform = `translateX(${-currentSlide * slideWidth}px)`;
}

function prevSlide() {
  showSlide(currentSlide - 1);
}

function nextSlide() {
  showSlide(currentSlide + 1);
}
