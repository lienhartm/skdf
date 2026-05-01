document.addEventListener('DOMContentLoaded', function () {
    const reservedFile = '/documents/reservation/sejour.json';
    const calendarRoot = document.querySelector('.calendar');
    const monthTitle = calendarRoot.querySelector('.month-title');
    const prevBtn = calendarRoot.querySelector('.prev');
    const nextBtn = calendarRoot.querySelector('.next');
    const daysContainer = calendarRoot.querySelector('.days');
    const arrivalInput = document.getElementById('arrivalDate');
    const departureInput = document.getElementById('departureDate');
    const reserveButton = document.getElementById('reserveButton');
    const errorBox = document.getElementById('calendar-error');
    const numDaysSpan = document.getElementById('numDays');
    const numAdultsInput = document.getElementById('numPeople');
    const numChildrenInput = document.getElementById('numChildren');
    const numAdult = document.getElementById('numAdult');
    const numChild = document.getElementById('numChild');


    document.getElementById('numPeople').addEventListener('change', function () {
        numAdult.textContent = this.value;
    });

    document.getElementById('numChildren').addEventListener('change', function () {
        numChild.textContent = this.value;
    });

    let currentDate = new Date();
    currentDate.setDate(1);
    currentDate.setHours(0, 0, 0, 0);

    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

    let selectedArrival = null;
    let selectedDeparture = null;
    const reservedRanges = [];
    const dailyCounts = new Map();
    const blockedDates = new Set();

    const dayNames = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];

    function parseDate(value) {
        const parts = value.split('-');
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function formatDate(date) {
        const y = date.getFullYear();
        const m = `${date.getMonth() + 1}`.padStart(2, '0');
        const d = `${date.getDate()}`.padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function nextDay(date) {
        const next = new Date(date);
        next.setDate(next.getDate() + 1);
        return next;
    }

    function addDays(date, count) {
        const result = new Date(date);
        result.setDate(result.getDate() + count);
        return result;
    }

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.add('visible');
    }

    function clearError() {
        errorBox.textContent = '';
        errorBox.classList.remove('visible');
    }

    function loadReservations() {
        fetch(reservedFile)
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Impossible de charger les réservations.');
                }
                return res.json();
            })
            .then(function (data) {
                data.forEach(function (item) {
                    reservedRanges.push({
                        start: parseDate(item.date_debut),
                        end: parseDate(item.date_fin),
                        totalPersons: (parseInt(item.nb_adulte, 10) || 0) + (parseInt(item.nb_enfant, 10) || 0),
                    });
                });
                buildBlockedDates();
                renderCalendar();
            })
            .catch(function (error) {
                showError(error.message);
                renderCalendar();
            });
    }

    function buildBlockedDates() {
        blockedDates.clear();
        dailyCounts.clear();

        // Les réservations sont enregistrées avec une date de départ exclusive.
        // Exemple : du 2 au 5 bloque les nuits 2, 3 et 4 uniquement.
        reservedRanges.forEach(function (range) {
            const start = new Date(range.start);
            const end = new Date(range.end);
            end.setDate(end.getDate() - 1);
            for (let date = new Date(start); date <= end; date.setDate(date.getDate() + 1)) {
                const dateString = formatDate(date);
                const currentCount = dailyCounts.get(dateString) || 0;
                const newCount = currentCount + range.totalPersons;
                dailyCounts.set(dateString, newCount);
                if (newCount >= 20) {
                    blockedDates.add(dateString);
                }
            }
        });
    }

    function isReserved(dateString) {
        return blockedDates.has(dateString);
    }

    function isBetweenSelected(dateString) {
        if (!selectedArrival || !selectedDeparture) {
            return false;
        }
        return dateString > selectedArrival && dateString < selectedDeparture;
    }

    function getDayClass(dateString, dateObj) {
        if (isReserved(dateString)) {
            return 'day blocked';
        }
        if (selectedArrival === dateString) {
            return 'day selected start';
        }
        if (selectedDeparture === dateString) {
            return 'day selected end';
        }
        if (isBetweenSelected(dateString)) {
            return 'day selected range';
        }
        if (dateString < formatDate(today)) {
            return 'day disabled';
        }
        return 'day';
    }

    function rangeOverlapsReserved(start, end) {
        const date = new Date(start);
        while (date <= end) {
            if (isReserved(formatDate(date))) {
                return true;
            }
            date.setDate(date.getDate() + 1);
        }
        return false;
    }

    function renderCalendar() {
        clearError();
        monthTitle.textContent = `${currentDate.toLocaleString('fr-FR', { month: 'long' })} ${currentDate.getFullYear()}`;
        daysContainer.innerHTML = '';

        dayNames.forEach(function (name) {
            const cell = document.createElement('div');
            cell.className = 'day day-name';
            cell.textContent = name;
            daysContainer.appendChild(cell);
        });

        const firstDayOfWeek = (currentDate.getDay() + 6) % 7;
        for (let i = 0; i < firstDayOfWeek; i += 1) {
            const empty = document.createElement('div');
            empty.className = 'day empty';
            daysContainer.appendChild(empty);
        }

        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
        for (let day = 1; day <= daysInMonth; day += 1) {
            const dateObj = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            const dateString = formatDate(dateObj);
            const cell = document.createElement('div');
            cell.className = getDayClass(dateString, dateObj);
            cell.textContent = day;
            cell.dataset.date = dateString;

            if (!cell.classList.contains('blocked') && !cell.classList.contains('disabled')) {
                cell.addEventListener('click', function () {
                    selectDate(dateString);
                });
            }

            daysContainer.appendChild(cell);
        }

        updateInputs();
    }

    function selectDate(dateString) {
        const dateObj = parseDate(dateString);
        if (dateString < formatDate(today)) {
            showError('Sélectionnez une date à partir d’aujourd’hui.');
            return;
        }
        if (isReserved(dateString)) {
            showError('Cette date est complète : capacité 20 personnes atteinte.');
            return;
        }

        if (!selectedArrival || (selectedArrival && selectedDeparture)) {
            selectedArrival = dateString;
            selectedDeparture = null;
            clearError();
            renderCalendar();
            return;
        }

        const arrivalObj = parseDate(selectedArrival);
        const selectedObj = parseDate(dateString);
        if (selectedObj <= arrivalObj) {
            selectedArrival = dateString;
            selectedDeparture = null;
            clearError();
            renderCalendar();
            return;
        }

        if (rangeOverlapsReserved(arrivalObj, selectedObj)) {
            showError('L’intervalle choisi chevauche une date déjà réservée.');
            return;
        }

        selectedDeparture = dateString;
        clearError();
        renderCalendar();
    }

    function updateInputs() {
        arrivalInput.value = selectedArrival || '';
        departureInput.value = selectedDeparture || '';
        if (selectedArrival && selectedDeparture) {
            const arrivalDate = parseDate(selectedArrival);
            const departureDate = parseDate(selectedDeparture);
            const timeDiff = departureDate - arrivalDate;
            const dayCount = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            numDaysSpan.textContent = dayCount;
        } else {
            numDaysSpan.textContent = '0';
        }
        arrivalInput.min = formatDate(today);
        departureInput.min = selectedArrival ? formatDate(addDays(parseDate(selectedArrival), 1)) : formatDate(addDays(today, 1));
    }

    function validateInputs() {
        clearError();
        if (!arrivalInput.value || !departureInput.value) {
            showError('Veuillez saisir une date d’arrivée et de départ.');
            return false;
        }

        const arrivalDate = parseDate(arrivalInput.value);
        const departureDate = parseDate(departureInput.value);
        if (departureDate <= arrivalDate) {
            showError('La date de départ doit être après la date d’arrivée.');
            return false;
        }
        if (arrivalDate < today) {
            showError('La date d’arrivée doit être aujourd’hui ou ultérieure.');
            return false;
        }
        if (rangeOverlapsReserved(arrivalDate, addDays(departureDate, -1))) {
            showError('La période choisie chevauche une réservation existante.');
            return false;
        }
        return true;
    }

    prevBtn.addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextBtn.addEventListener('click', function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    arrivalInput.addEventListener('change', function () {
        selectedArrival = arrivalInput.value || null;
        if (selectedDeparture && selectedArrival && parseDate(selectedDeparture) <= parseDate(selectedArrival)) {
            selectedDeparture = null;
        }
        renderCalendar();
    });

    departureInput.addEventListener('change', function () {
        selectedDeparture = departureInput.value || null;
        if (selectedArrival && selectedDeparture) {
            if (parseDate(selectedDeparture) <= parseDate(selectedArrival)) {
                showError('La date de départ doit être après la date d’arrivée.');
                selectedDeparture = null;
            } else if (rangeOverlapsReserved(parseDate(selectedArrival), addDays(parseDate(selectedDeparture), -1))) {
                showError('La période choisie chevauche une réservation existante.');
            }
        }
        renderCalendar();
    });

    reserveButton.addEventListener('click', function (event) {
        event.preventDefault();
        if (!validateInputs()) {
            return;
        }
        clearError();
        // Submit the form to redirect to PayPal payment page
        document.getElementById('reservationForm').submit();
    });

    loadReservations();
});
