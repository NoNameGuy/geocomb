<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <p>Precos combustiveis</p>
    <select>
    <?php echo "Distritos: ";
        foreach ($districts as $key => $district) {
            echo "<option> $district->name </option>";

        }
    ?>
    </select>
    <a href="{{action('LandingController@fetchData')}}">Fetch data Aveiro</a>
</body>
</html>
