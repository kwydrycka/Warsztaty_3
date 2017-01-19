
$(document).ready(function() {
    
  
 var endpoint = 'api/books.php';

    createBook();
    showAllBooks();
    
       
    function showAllBooks() { 
        $.ajax({
            url: endpoint,
            data: {},
            type: "GET",
            dataType: "json",
       
            success: function (json) { 
                   //  var books = json;
                    $.each(json, function (object, book) {
                          var book = $("<div id='" + book.id + "''class='displayBook'>" + book.name + "</div>");
                          $('.bookList').append(book);
                    });

                    $('.bookList div').one('click', showBookDetails);
                },

            error: function () {
                    alert("Error - show All Books");
                }
        });
    }
    
    function showBookDetails() {
        var display = $(this);  
        $.ajax({
            url: endpoint,
            data: 'id=' + $(this).get(0).id,
            type: 'GET',
            dataType: 'json',
            success: function (json) {

                     display.append("<div class='displayBook'> Author: " + json.author + "<br/>\
                                                              Description: " + json.description + '\
                            <form class="editBook"> \n\
                                   <h5>Edit Book:\n\</h5>' + 
                                   '<input type="text" name="name" value="' + json.name + '"/><br/>' 
                                 + '<input type="text" name="author" value="' + json.author + '"/><br/>' 
                                 + '<input type="text" name="description" value="'+ json.description + '"/><br/>' 
                                 + '<input type="submit" class="edit" value="Edit Book" name="' + json.id + '"/>\n\
                            </form> \n\
                            <a href class="delete" value="' + json.id + '">Delete Book</a<br/>\n\
                                  </div><br/>');

                     $('a').on('click', function (e) {
                        e.preventDefault();
                        deleteBook(this)
                     });
                     
                     $('.editBook').on('submit', function (e) {
                        e.preventDefault();
                        editBook(this)
                     });
            },
            error: function () {
                    alert("Error - show Details");
            }
        });
    }
    
    function editBook(form) {   
 
        var editData = {
        
            id: $(form).find('.edit').attr('name'),
            name: $(form).find('[name=name]').val(),
            author: $(form).find('[name=author]').val(),
            description: $(form).find('[name=description]').val()
        };
   
        
        $.ajax({
            url: endpoint,
            data: editData,
            type: "PUT",
            dataType: "json",
            success: function (data) {
                
                       
                    alert("Data was updated");
                    location.reload(true);
                },
            error: function () {
                    alert("Error - edit Book");
            }
        });
    }
    
    function deleteBook() {
        var bookToDelete = $('.delete').attr('value');
        $.ajax({
            url: endpoint,
            data:  {id: bookToDelete},
            type: "DELETE",
            dataType: "json",
           success: function (json) {
                    alert("Book removed");
                    location.reload(true);
                },
            error: function () {
                    alert("Error - delete Book");
                }
            });
    }
    
    function createBook() {
        $('form[name="addBook"]').on('submit', function(e) {
            e.preventDefault();

            var newData = {
                'name': $('input[name="name"]').val(),
                'author': $('input[name="author"]').val(),
                'description': $('input[name="description"]').val()
            };

            $.ajax({
                url: endpoint,
                data: newData,
                type: "POST",
                dataType: "json",
                success: function (json) {
                        alert("New Book was added");
                        window.location.reload(true);
                    },
                error: function () {
                        alert("Error - create Book");
                    }
            });
        });
    }
    
    
});