<?php

class Lotnik
{
    public $hog_type;
    public $competition;
    public $name;
    public $surname;
    public $gps_data;
    public $fly_start;
    public $fly_end;
    public $fly_duration;

    public function __construct($file_path) {
        
        $lines = file($file_path);
        
        if($lines === FALSE)
        {
            echo "Błąd";
            echo "</br>";
        } else {
            $pom = explode(" ", $lines[1]);
            $this->name = $pom[1];
            $this->surname = $pom[2];

            $pom = explode("HOGTYGLIDERTYPE:", $lines[2]);
            $this->hog_type = $pom[1];

            $pom = explode("HODTM100GPSDATUM:", $lines[4]);
            $this->gps_data = $pom[1];

            $pom = explode("HOCCLCOMPETITION CLASS:", $lines[5]);
            $this->competition = $pom[1];

            $b_lines = [];
            $j = 0;
            for($i=0;$i<count($lines)-1;$i++)
            {
                if(substr($lines[$i], 0, 1) == "B")
                {
                    $b_lines[$j] = $lines[$i];
                    $j++;
                }
            }
            $this->dataTime($b_lines);
            
            echo "</br>";
            $this->view_data();
            echo "</br>";
            $this->placeList($b_lines);
        }
    }

    public function dataTime($b_lines) {
        $min_value = new DateTime("23:59:59");
        $max_value = new DateTime("00:00:00");
        for($i=0;$i<count($b_lines);$i++)
        {
            $pom = substr($b_lines[$i], 1, 2);
            $pom .= ":".substr($b_lines[$i], 3, 2).":";
            $pom .= substr($b_lines[$i], 5, 2);

            $date = new DateTime($pom);
            if($date > $max_value)
            {
                $max_value = $date;
            } if ($date < $min_value)
            {
                $min_value = $date;
            }
        }
        $this->fly_start = $min_value;
        $this->fly_end = $max_value;
        $date = date_diff($max_value, $min_value);
        $this->fly_duration = date_diff($max_value, $min_value);
    }

    public function placeList($b_lines) {
        $str='<table id="points">
            <tr>
            <th>Czas</th>
            <th>Szerokość geograficzna</th>
            <th>Długość geograficzna</th>
            </tr>';
        echo $str;
        foreach ($b_lines as $line)
        {
            $pom = substr($line, 1, 2);
            $pom .= ":".substr($line, 3, 2).":";
            $pom .= substr($line, 5, 2);
            echo "<tr>";
            echo "<td>".$pom."</td>";


            $pom = substr($line, 7, 2);
            echo "<td>".$pom." stopnie ";
            $pom = substr($line, 9, 2);
            $pom .= ".".substr($line, 11, 3); 
            echo $pom." minuty ";
            $pom = substr($line, 14, 1);
            if($pom == "N")
            {
                $pom = "Północ";
            } else if($pom == "S")
            {
                $pom = "Południe";
            } else if ($pom == "E")
            {
                $pom = "Wschód";
            } else {
                $pom = "Zachód";
            }
            echo $pom."</td>";


            $pom = substr($line, 15, 3);
            echo "<td>".$pom." stopnie ";
            $pom = substr($line, 18, 2);
            $pom .= ".".substr($line, 20, 3);
            echo $pom." minuty ";
            $pom = substr($line, 23, 1);
            if($pom == "N")
            {
                $pom = "Północ";
            } else if($pom == "S")
            {
                $pom = "Południe";
            } else if ($pom == "E")
            {
                $pom = "Wschód";
            } else {
                $pom = "Zachód";
            }
            echo $pom."</td>";

            echo "</tr>";
        }
        echo "</table>";
    }

    public function view_data() {
        echo '<div class="information">';
        echo '<p class="inf"><b>Imie: </b>'.$this->name."</p>";
        echo '<p class="inf"><b>Nazwisko: </b>'.$this->surname."</p>";
        echo '<p class="inf"><b>Szybowiec: </b>'.$this->hog_type."</p>";
        echo '<p class="inf"><b>System odniesienia: </b>'.$this->gps_data."</p>";
        echo '<p class="inf"><b>Kompetencje: </b>'.$this->competition."</p>";
        echo '<p class="inf"><b>Start lotu: </b>'.$this->fly_start->format("H:i:s")."</p>";
        echo '<p class="inf"><b>Koniec lotu: </b>'.$this->fly_end->format("H:i:s")."</p>";
        echo '<p class="inf"><b>Długość lotu: </b>'.$this->fly_duration->format("%H:%i:%s")."</p>";
        echo "</div>";

    }
}

?>