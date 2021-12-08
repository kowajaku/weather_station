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
kowaljak@student:~/public_html/stacja$ cat wilgotnosc.php
<?php
$host = "mysql.agh.edu.pl";
$username = "kowaljak";
$password = "Hurm2J7iFJHzb2JE";
$database = "kowaljak";
//$host = "localhost";
//$username = "root";
//$password = "";
//$database = "stacja_pogodowa";



try{
        //$baza=mysqli_connect("mysql.agh.edu.pl","kowaljak","Hurm2J7iFJHzb2JE","kowaljak");

        //$pdo = new PDO("mysql:host=localhost;database=$database",$username,$password);
        $pdo = new PDO("mysql:host=$host;database=$database",$username,$password);

        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
        die("ERROR: Could not connect.". $e->getMessage());

}
?>

<!doctype html>
<html lang="en">
        <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>WYKRES WILGOTNOŒCI</title>
                <style type="text/css">
                        .chartBox
                        {
                                width: 700px;

                        }
                </style>
        </head>
<body>
        <?php
        $ilosc = 10;
        $option = isset($_POST['ilosc']) ? $_POST['ilosc'] : false;
        if ($option) {
                $ilosc = $_POST['ilosc'];


    }
        try{
                $sql = "SELECT * FROM kowaljak.pomiary";

                $result = $pdo->query($sql);
                if($result->rowCount()>0)
                {
                        $temp=array();
                        $data=array();
                        while($row=$result->fetch())
                        {
                                $temp[]=$row["wilgotnosc"];
                                $date=$row["data"];
                                $dateInfo = date_parse_from_format('Y-m-d H:i:s', $date);
                                $d=mktime($dateInfo['hour'],$dateInfo['minute'],$dateInfo['second']);
                                $czas=date("H:i:s", $d);

                                $data[]=$czas;
                        }

                unset($result);
                } else{
                        echo "Error";
                }
        } catch(PDOException $e)
        {
                die("ERROR could not able to execute $sql". $e->getMessage());
        }
        if($ilosc != "wszystko")
        {


                $temp2=array();
                for($i=count($temp)-$ilosc; $i<count($temp); $i++)
                {
                        $temp2[] = $temp[$i];

                }
                $temp = $temp2;

                $data2=array();

                for($i=count($data)-$ilosc; $i<count($data); $i++)
                {
                        $data2[] = $data[$i];
                }
                $data = $data2;

        }
        unset($pdo);
        ?>


        <div class="chartBox">
                <canvas id="myChart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>

        //Setup Block
        const temperatura = <?php echo json_encode($temp); ?>;
        const daty_pomiarow = <?php echo json_encode($data); ?>;
        const data={
        labels: daty_pomiarow,
                        datasets: [{
                                label: 'Wykres pomiaru wilgotnoœci [%]',
                                data: temperatura,
                                backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                        }]
                };
        //Config Block
        const config={
        type: 'line',
                data,
                options: {
                        scales: {
                                y: {
                                        beginAtZero: true
                                }
                        }
                }
        };


        //Render Block
        const myChart= new Chart(
                document.getElementById('myChart'),
                config
        );


</script>
<?php
echo '<form method="post" action="wilgotnosc.php">';
echo '<select name="ilosc" id="wybor">';
$val=array(10,20,50,100,'wszystko');
for($i=0;$i<count($val);$i++)
{
        if($val[$i]==$ilosc)
        {
                echo '<option value="'.$val[$i].'" selected>'.$val[$i].'</option>';
        }
        else{
                echo '<option value="'.$val[$i].'">'.$val[$i].'</option>';
        }


}

echo '</select>';
echo '<input type="submit" value="filtruj"/>';
echo '</form>';

?>
<input type="button" onclick="location.href='index.php';" value="STRONA G£ÓWNA" />
<input type="button" onclick="location.href='temperatura.php';" value="WYKRES TEMPERATURY" />
<input type="button" onclick="location.href='cisnienie.php';" value="WYKRES CIŒNIENIA" />


        </body>
</html>
