<!DOCTYPE html>
<html>
<body>
<?php
include "Database.php";
$lesson_id = $_GET['id'];

$lesson = mysqli_fetch_array(Database::getConnection()->query("SELECT * FROM lessons WHERE id='$lesson_id'"));
$lesson_title = $lesson[2];
$text = $lesson[3];
?>

<form action="update_lesson.php" method="post" enctype="multipart/form-data">
    <label for="title">Nazwa lekcji: </label>
    <?php
    echo '<input id="title" name="title" value="' . $lesson_title . '"/>';
    ?>
    <br>
    <br>
    <textarea name="editor" id="editor">
        <?php
        echo $text;
        ?>
    </textarea>
    <br><br>
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br><br>
    <?php
    echo '<input type="hidden" name="id" id="id" value="' . $lesson_id . '">';
    ?>
    <input type="submit" value="Edytuj lekcje" name="submit">
</form>

<script src="ckeditor/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
<br>
<br>
<br>
<br>
</body>
</html>