<!DOCTYPE html>
<html>
<body>
<form action="insert_test_to_db.php" method="post" enctype="multipart/form-data">
    <label for="title">Nazwa testu: </label>
    <input id="title" name="title"/>
    <br>
    <br>
    <label for="max_time">Maksymalna długość rozwiązywania testu (minuty): </label>
    <input id="max_time" name="max_time"/>
    <br><br>
    <input type="submit" value="Dodaj test" name="submit">
</form>
<br>
<br>
<br>
<br>
</body>
</html>