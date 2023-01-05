<!DOCTYPE html>
<html>
<body>
<form action="insert_question_to_db.php" method="post" enctype="multipart/form-data">
    Pytanie:
    <br>
    <br>
    <textarea name="editor" id="editor"></textarea>
    <br><br>
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br><br>
    Odpowiedzi:
    <br>
    <br>
    <input type="checkbox" id="answer_a_checkbox" name="answer_a_checkbox">
    <input id="answer_a" name="answer_a"/>
    <br><br>
    <input type="checkbox" id="answer_b_checkbox" name="answer_b_checkbox">
    <input id="answer_b" name="answer_b"/>
    <br><br>
    <input type="checkbox" id="answer_c_checkbox" name="answer_c_checkbox">
    <input id="answer_c" name="answer_c"/>
    <br><br>
    <input type="checkbox" id="answer_d_checkbox" name="answer_d_checkbox">
    <input id="answer_d" name="answer_d"/>
    <br><br>
    <?php
    $test_id = $_GET['id'];
    echo "<input type='hidden' name='test_id' value='$test_id' />"
    ?>
    <input type="submit" value="Dodaj pytanie" name="submit">
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