
$(document).ready(function () {
    
 
     $('#create').on('click', function () {
         var form = $('#create-form')[0] ?? null;
         if (!form) console.log('Something went wrong..');
         
         var url = $('#create-form').attr('action');
         if (form.checkValidity() && form.reportValidity()) {
             var formData = new FormData(form);
             // Perform AJAX request
             $.ajax({
                 url: url,
                 type: 'POST',
                 data: formData,
                 contentType: false, // Don't set content type
                 processData: false, // Don't process the data
                 dataType: 'json',
                 success: function (response) {
                     showAlert(response.message, response.success ? 'primary' : 'danger');
                     if (response.success) {
                         $('#createborrowedBookModal').modal('hide');
                         setTimeout(function () {
                             location.reload();
                         }, 1000);
                     }
                 },
                 error: function (error) {
                     // Handle the error
                     console.error('Error submitting the form:', error);
                     showAlert('Something went wrong..!', 'danger');
                 },
                 complete: function (response) {
                     // This will be executed regardless of success or error
                     console.log('Request complete:', response);
 
                 }
             });
 
 
         } else {
             showAlert('Form is not valid. Please check your inputs.', 'danger');
         }
     });
 
     $('.edit-borrowed-book-btn').on('click', async function () {
         var borrowed_book_id = $(this).data('id');
         await getborrowedbookById(borrowed_book_id);
     })
    
     $('.delete-borrowed-book-btn').on('click', async function () {
         var borrowed_book_id = $(this).data('id');
         var is_confirm = confirm('Are you sure,Do you want to delete?');
         if (is_confirm) await deleteById(borrowed_book_id);
     })
 
     $('#update-book').on('click', function () {
         
         // Get the form element
         var form = $('#update-form')[0];
         form.reportValidity();
 
         // Check form validity
         if (form.checkValidity()) {
             // Serialize the form data
             var url = $('#update-form').attr('action');
             var formData = new FormData($('#update-form')[0]);
 
             // Perform AJAX request
             $.ajax({
                 url: url,
                 type: 'POST',
                 data: formData, // Form data
                 dataType: 'json',
                 contentType: false,
                 processData: false,
                 success: function (response) {
                     showAlert(response.message, response.success ? 'primary' : 'danger', 'edit-alert-container');
                     if (response.success) {
                         $('#edit-book-modal').modal('hide');
                         setTimeout(function () {
                             location.reload();
                         }, 1000);
                     }
                 },
                 error: function (error) {
                     // Handle the error
                     console.error('Error submitting the form:', error);
                 },
                 complete: function (response) {
                     // This will be executed regardless of success or error
                     console.log('Request complete:', response);
                 }
             });
         } else {
             var message = ('Form is not valid. Please check your inputs.');
             showAlert(message, 'danger');
         }
     });
 
    
 
   

 });
 
 async function getborrowedbookById(id) {
     var url = $('#Borrowed_Books-form').attr('action');
     $('#edit-additional-fields').empty();
 
     // Perform AJAX request
     $.ajax({
         url: url,
         type: 'GET',
         data: {
             borrowed_book_id : id,
             action: 'get_borrowed_book'
         }, // Form data
         dataType: 'json',
         success: function (response) {
             console.log(response);
 
             showAlert(response.message, response.success ? 'primary' : 'danger');
             if (response.success) {
                 var book_status = response.data.book_status;
                 var returned_at = response.data.returned_at;
        
                 $('#update_borrowed_books #book_status').val(book_status);
                 $('#update_borrowed_books #returned_at').val(returned_at);
 
                 $('#update_borrowed_books').modal('show');
             }
         },
         error: function (error) {
             // Handle the error
             console.error('Error submitting the form:', error);
         },
         complete: function (response) {
             // This will be executed regardless of success or error
             console.log('Request complete:', response);
         }
     });
 }
 
 

 