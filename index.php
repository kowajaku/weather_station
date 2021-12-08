<!DOCTYPE html>
<html lang="pl-PL">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
crossorigin="anonymous">

    <title>Stacja pogodowa</title>
  </head>
  <body>
    <h1>STACJA POGODOWA</h1>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
crossorigin="anonymous"></script>
        <div class="container">
                <div class="row">
                        <div class="col">
                          <table class="table table-info table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">DATA POMIARU</th>
      <th scope="col">TEMPERATURA</th>
      <th scope="col">CIŒNIENIE</th>
          <th scope="col">WILGOTNOŒÆ</th>
    </tr>
  </thead>
  <tbody>
    <?php
        static $temp=0;

        if(isset($_GET['temp'])){
                $temp=$_GET['temp'];

                echo 'temp='.$_GET['temp'].'<br />';
                echo 'cis = '.$_GET['cis'].'<br />';
                echo 'wilg = '.$_GET['wilg'].'<br />';
                $info = getdate();
                $date = $info['mday'];
                $month = $info['mon'];
                $year = $info['year'];
                $hour = $info['hours'];
                $min = $info['minutes'];
                $sec = $info['seconds'];

                //$current_date = "$date-$month-$year $hour:$min:$sec";
                $current_date = date('Y-m-d H:i:s');
                $temp=$_GET['temp'];
                $cis=$_GET['cis'];
                $wilg=$_GET['wilg'];
                $zapytanie="INSERT INTO `pomiary` (`id`, `temperatura`, `cisnienie`, `wilgotnosc`, `data`) VALUES (NULL, '".$temp."', '".$cis."', '".$wilg."', '".$current_date."');";
                echo $zapytanie;

                //$baza=mysqli_connect("localhost","root","","stacja_pogodowa");
                $baza=mysqli_connect("mysql.agh.edu.pl","kowaljak","Hurm2J7iFJHzb2JE","kowaljak");


                if (mysqli_connect_errno())

                {
                        echo "Wyst¹pi³ b³¹d po³¹czenia z baz¹";
                }

                $wynik = mysqli_query($baza,$zapytanie);
        }
        else{
                //$baza=mysqli_connect("localhost","root","","stacja_pogodowa");
                $baza=mysqli_connect("mysql.agh.edu.pl","kowaljak","Hurm2J7iFJHzb2JE","kowaljak");
                if (mysqli_connect_errno())

                {
                        echo "Wyst¹pi³ b³¹d po³¹czenia z baz¹";
                }

                $wynik = mysqli_query($baza,"SELECT * FROM `pomiary` ORDER BY `pomiary`.`id` DESC LIMIT 5");

                $i=1;

                while($row = mysqli_fetch_array($wynik))

                        {
                                echo '<tr>';
                                        echo '<th scope="row">'.$i.'</th>';
                                        echo '<td>'.$row['data'].'</td>';
                                        echo '<td>'.$row['temperatura'].'°C</td>';
                                        echo '<td>'.$row['cisnienie'].'hPa </td>';
                                        echo '<td>'.$row['wilgotnosc'].' % </td>';
                                echo "</tr>";
                                #echo "temperatura: ".$row['temperatura']."?        cisnienie:". $row['cisnienie']."hPa      wilgotnosc".$row['wilgotnosc']." %         data:".$row['data'];
                                $i++;
                        }


                mysqli_close($baza);
        }

?>
  </tbody>
</table>
                        </div>

                </div>
        </div>
<input type="button" onclick="location.href='index.php';" value="STRONA G£ÓWNA" />
<input type="button" onclick="location.href='temperatura.php';" value="WYKRES TEMPERATURY" />
<input type="button" onclick="location.href='cisnienie.php';" value="WYKRES CIŒNIENIA" />
<input type="button" onclick="location.href='wilgotnosc.php';" value="WYKRES WILGOTNOŒCI" />
  </body>
</html>
