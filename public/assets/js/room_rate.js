// const rateBar = document.querySelector('.rate-bar');
// const rateButtons = document.querySelectorAll('.rate-bar_btn');


// Угол веера в градусах
const arc = 40;
const step = rateBtns.length > 1 ? arc / (rateBtns.length - 1) : 0;

function getArcCoords(n, h) {
  const coords = [];
  for (let i = 0; i < n; i++) {
    const xNorm = (i / (n - 1)) * 2 - 1; // -1..1
    const y = h * (1 - xNorm * xNorm);
    coords.push(y);
  }
  return coords;
}

function placeCards(firstLaunch = false) {
    const heights = getArcCoords(rateBtns.length, 40);

    rateBtns.forEach((button, i) => {
        const angle = -arc / 2 + i * step;
        const offset = heights[i];
        if (button.classList.contains('active')) {
            button.style.transform = `rotate(${angle}deg) translateY(-${offset + 20}px)`;
        } else {
            button.style.transform = `rotate(${angle}deg) translateY(-${offset}px)`;
        }

        if (firstLaunch) {
            button.addEventListener('mouseenter', () => {
                button.style.transform = `rotate(${angle}deg) translateY(-${offset + 20}px)`;
            });

            button.addEventListener('mouseleave', () => {
                if (button.classList.contains('active')) return;
                button.style.transform = `rotate(${angle}deg) translateY(-${offset}px)`;
            });
        }
    });
}

placeCards(true);