function deleteTrain(id) {
    if(confirm('Are you sure you want to delete this train?')) {
        fetch(`delete_train.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Train deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
                }
            });
    }
}