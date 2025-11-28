function editTrain(id) {
    // Redirect ke edit page dengan parameter ID
    window.location.href = `edit_train.html?id=${id}`;
}

// Di edit_train.html:
const urlParams = new URLSearchParams(window.location.search);
const trainId = urlParams.get('id');

// Fetch train data berdasarkan ID
fetch(`get_train.php?id=${trainId}`)
    .then(response => response.json())
    .then(train => {
        document.getElementById('train_name').value = train.train_name;
        document.getElementById('departure_station').value = train.departure_station;
        document.getElementById('arrival_station').value = train.arrival_sta;
    });