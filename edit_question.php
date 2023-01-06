<!DOCTYPE html>
<html>
<body>
<?php
include 'Database.php';
$question_id = $_GET['id'];
$test_id = $_GET['lid'];

$question = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM questions where id='$question_id'"));
?>
<form action="update_question.php" method="post" enctype="multipart/form-data">
    Pytanie:
    <br>
    <br>
    <textarea name="editor" id="editor">
        <?php
        echo $question[2];
        ?>
    </textarea>
    <br><br>
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br><br>
    Odpowiedzi:
    <br>
    <br>
    <?php

    if (strpos($question[7], 'a') !== false) {
        echo '<input type="checkbox" id="answer_a_checkbox" name="answer_a_checkbox" checked>';
    } else {
        echo '<input type="checkbox" id="answer_a_checkbox" name="answer_a_checkbox">';
    }
    echo '<input id="answer_a" name="answer_a" value="'.$question[3].'"/>';
    echo '<br><br>';

    if (strpos($question[7], 'b') !== false) {
        echo '<input type="checkbox" id="answer_b_checkbox" name="answer_b_checkbox" checked>';
    } else {
        echo '<input type="checkbox" id="answer_b_checkbox" name="answer_b_checkbox">';
    }
    echo '<input id="answer_b" name="answer_b" value="'.$question[4].'"/>';
    echo '<br><br>';

    if (strpos($question[7], 'c') !== false) {
        echo '<input type="checkbox" id="answer_c_checkbox" name="answer_c_checkbox" checked>';
    } else {
        echo '<input type="checkbox" id="answer_c_checkbox" name="answer_c_checkbox">';
    }
    echo '<input id="answer_c" name="answer_c" value="'.$question[5].'"/>';
    echo '<br><br>';

    if (strpos($question[7], 'd') !== false) {
        echo '<input type="checkbox" id="answer_d_checkbox" name="answer_d_checkbox" checked>';
    } else {
        echo '<input type="checkbox" id="answer_d_checkbox" name="answer_d_checkbox">';
    }
    echo '<input id="answer_d" name="answer_d" value="'.$question[6].'"/>';
    echo '<br><br>';

    echo "<input type='hidden' name='test_id' value='$test_id' />";
    echo "<input type='hidden' name='question_id' value='$question_id' />";
    ?>
    <input type="submit" value="Edytuj pytanie" name="submit">
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