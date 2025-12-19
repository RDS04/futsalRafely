let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

const scheduleData = {
    '2024-1-15': {
        status: 'booked',
        endTime: '18:00'
    },
    '2024-1-15-2': {
        status: 'booked',
        endTime: '20:00'
    },
    '2024-1-18': {
        status: 'booked',
        endTime: '19:30'
    },
    '2024-1-20': {
        status: 'booked',
        endTime: '21:00'
    },
};

function renderCalendar() {
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    document.getElementById('monthYear').textContent = `${monthNames[currentMonth]} ${currentYear}`;

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    const calendarGrid = document.getElementById('calendarGrid');
    calendarGrid.innerHTML = '';

    for (let i = 0; i < firstDay; i++) {
        calendarGrid.innerHTML += '<div></div>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = `${currentYear}-${currentMonth + 1}-${day}`;
        const isBooked = scheduleData[dateKey];
        const bgColor = isBooked ? 'bg-red-400 hover:bg-red-500' : 'bg-green-400 hover:bg-green-500';

        calendarGrid.innerHTML += `
                        <button onclick="showDateDetail('${dateKey}')" 
                            class="p-3 rounded text-white font-semibold ${bgColor} transition cursor-pointer">
                            ${day}
                        </button>
                    `;
    }
}

function showDateDetail(dateKey) {
    const [year, month, day] = dateKey.split('-');
    const bookings = Object.entries(scheduleData)
        .filter(([key]) => key.startsWith(dateKey))
        .map(([_, data]) => data);

    const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    let detailHTML = `<p class="font-bold text-gray-800">${day} ${monthNames[parseInt(month)]} ${year}</p>`;

    if (bookings.length === 0) {
        detailHTML += '<p class="text-green-600 font-semibold mt-2">✓ Semua lapangan kosong</p>';
    } 
    else {
        bookings.forEach((booking, idx) => {
            detailHTML += `<p class="text-red-600 mt-2">Lapangan ${idx + 1}: Terpakai s/d ${booking.endTime}</p>`;
        });
    }
    document.getElementById('dateDetail').innerHTML = detailHTML;
}

function prevMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}
renderCalendar();

let currentSlide = 0;
const dots = document.querySelectorAll('.dot');
const totalSlides = 2;
const sliderTrack = document.getElementById('sliderTrack');
let autoSlideInterval;

function moveSlide(direction) {
    currentSlide += direction;
    if (currentSlide >= totalSlides) currentSlide = 0;
    if (currentSlide < 0) currentSlide = totalSlides - 1;

    updateSlider();
    resetAutoSlide();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlider();
    resetAutoSlide();
}

function updateSlider() {
    const offset = -currentSlide * 100;
    sliderTrack.style.transform = `translateX(${offset}%)`;

    // Update dots
    dots.forEach((dot, index) => {
        if (index === currentSlide) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function autoSlide() {
    currentSlide++;
    if (currentSlide >= totalSlides) currentSlide = 0;
    updateSlider();
}

function startAutoSlide() {
    autoSlideInterval = setInterval(autoSlide, 5000);
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
}
startAutoSlide();

document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profileBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownWrapper = document.getElementById('profileDropdown');

    profileBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!dropdownWrapper.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
    
});