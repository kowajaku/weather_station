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
                <title>WYKRES CISNIENIA</title>
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
                                $temp[]=$row["cisnienie"];
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
                                label: 'Wykres pomiaru ciœnienia atmosferycznego [hPa]',
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
echo '<form method="post" action="cisnienie.php">';
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

<input type="button" onclick="location.href='wilgotnosc.php';" value="WYKRES WILGOTNOŒCI" />

