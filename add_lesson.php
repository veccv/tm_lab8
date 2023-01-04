<!DOCTYPE html>
<html>
<body>
<form action="insert_lesson_to_db.php" method="post" enctype="multipart/form-data">
    <label for="title">Nazwa lekcji: </label>
    <input id="title" name="title"/>
    <br>
    <br>
    <textarea name="editor" id="editor"></textarea>
    <br><br>
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br><br>
    <input type="submit" value="Dodaj lekcje" name="submit">
</form>

<script src="ckeditor/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
<br>
<br>
<br>
<br>
</body>
</html>