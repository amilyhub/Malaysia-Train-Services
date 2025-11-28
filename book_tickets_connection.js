<script>
    // Train data from PHP
    const trainData = <?php echo json_encode($trains); ?>;
    
    let selectedTrain = null;

    // Select train function - SIMPLE & FIXED
    function selectTrain(button, trainId) {
        // Remove selected class from all cards
        document.querySelectorAll('.train-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selected class to clicked card
        const trainCard = button.closest('.train-card');
        trainCard.classList.add('selected');
        
        // Find selected train
        selectedTrain = trainData.find(train => train.id == trainId);
        
        if (selectedTrain) {
            // Update selected train info
            document.getElementById('selected-train-name').textContent = selectedTrain.train_class + ' - ' + selectedTrain.train_name;
            document.getElementById('selected-train-details').innerHTML = `
                <strong>Route:</strong> ${selectedTrain.departure_station} → ${selectedTrain.arrival_station}<br>
                <strong>Time:</strong> ${selectedTrain.departure_time} - ${selectedTrain.arrival_time}<br>
                <strong>Duration:</strong> ${selectedTrain.duration}<br>
                <strong>Price:</strong> RM ${parseFloat(selectedTrain.price).toFixed(2)}<br>
                <strong>Available Seats:</strong> ${selectedTrain.available_seats}<br>
                <strong>Service Type:</strong> ${selectedTrain.service_type}
            `;
            
            // Show selected train info and booking summary
            document.getElementById('selected-train-info').style.display = 'block';
            document.getElementById('booking-summary').style.display = 'block';
            
            // Enable proceed button
            document.getElementById('proceed-btn').disabled = false;
            
            // Update booking summary
            updateBookingSummary();
        }
    }

    // Update booking summary
    function updateBookingSummary() {
        if (!selectedTrain) return;
        
        const passengers = parseInt(document.getElementById('passenger-count').value);
        const ticketPrice = parseFloat(selectedTrain.price) * passengers;
        const totalAmount = ticketPrice + 2.00; // Service fee
        
        document.getElementById('summary-price').textContent = 'RM ' + ticketPrice.toFixed(2);
        document.getElementById('summary-passengers').textContent = passengers;
        document.getElementById('summary-total').textContent = 'RM ' + totalAmount.toFixed(2);
    }

    // Update summary when passenger count changes
    document.getElementById('passenger-count').addEventListener('change', updateBookingSummary);

    // Form submission - UPDATED untuk redirect ke payment.php
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedTrain) {
            alert('Please select a train first.');
            return;
        }
        
        // Validate passenger count doesn't exceed available seats
        const passengers = parseInt(document.getElementById('passenger-count').value);
        if (passengers > selectedTrain.available_seats) {
            alert(`Sorry, only ${selectedTrain.available_seats} seats available for this train.`);
            return;
        }
        
        // Collect form data
        const formData = {
            passengerName: document.getElementById('passenger-name').value,
            passengerEmail: document.getElementById('passenger-email').value,
            passengerPhone: document.getElementById('passenger-phone').value,
            ticketType: document.getElementById('ticket-type').value,
            passengerCount: document.getElementById('passenger-count').value,
            travelDate: document.getElementById('travel-date').value,
            train: selectedTrain,
            totalAmount: parseFloat(document.getElementById('summary-total').textContent.replace('RM ', ''))
        };
        
        // Send data to server using form submission
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'payment.php';
        
        Object.keys(formData).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = typeof formData[key] === 'object' ? JSON.stringify(formData[key]) : formData[key];
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    });

    // Search functionality (jika mau dipakai)
    document.getElementById('search-btn').addEventListener('click', function() {
        const fromStation = document.getElementById('from-station').value;
        const toStation = document.getElementById('to-station').value;
        
        if (!fromStation || !toStation) {
            alert('Please select both departure and arrival stations.');
            return;
        }
        
        const filteredTrains = trainData.filter(train => 
            train.departure_station === fromStation && train.arrival_station === toStation
        );
        
        // Refresh train cards display
        displayFilteredTrains(filteredTrains);
        
        if (filteredTrains.length === 0) {
            document.getElementById('trains-grid').innerHTML = '<div class="no-trains">No trains found for the selected route. Please try different stations.</div>';
        }
    });

    // Function to display filtered trains
    function displayFilteredTrains(trains) {
        const trainsGrid = document.getElementById('trains-grid');
        trainsGrid.innerHTML = '';
        
        if (!trains || trains.length === 0) {
            trainsGrid.innerHTML = '<div class="no-trains">No trains available. Please try different search criteria.</div>';
            return;
        }
        
        trains.forEach(train => {
            const trainCard = document.createElement('div');
            trainCard.className = 'train-card';
            trainCard.innerHTML = `
                <div class="train-header">
                    <div class="train-number">${train.train_class}</div>
                    <div class="train-class">${train.train_name}</div>
                </div>
                <div class="train-route">${train.departure_station} → ${train.arrival_station}</div>
                <div class="train-details">
                    <div class="detail-item">
                        <span class="detail-label">Departure</span>
                        <span class="detail-value">${train.departure_time}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Arrival</span>
                        <span class="detail-value">${train.arrival_time}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value">${train.duration}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Available Seats</span>
                        <span class="detail-value">${train.available_seats}</span>
                    </div>
                </div>
                <div class="train-price">RM ${parseFloat(train.price).toFixed(2)}</div>
                <button type="button" class="btn-select" onclick="selectTrain(this, ${train.id})" ${train.available_seats === 0 ? 'disabled' : ''}>
                    ${train.available_seats === 0 ? 'Sold Out' : 'Select Train'}
                </button>
            `;
            trainsGrid.appendChild(trainCard);
        });
    }

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('travel-date').min = today;

    // Initialize date field with tomorrow's date
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('travel-date').value = tomorrow.toISOString().split('T')[0];

    // Form interactions
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
</script>