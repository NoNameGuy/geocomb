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
    <a href="{{action('LandingController@fetchStationData')}}">Fetch data 165954 station</a>
    <a href="{{action('LandingController@mapsApi')}}">Maps Api</a>
</body>
</html>
