<?php
  require_once "vendor/autoload.php";
 
?>
<?php
function store_submit_to_file($name, $email, $message){
    $fp = fopen("submit_file.txt", "a+");
    if ($fp){
        $ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
        $input = date("Y-m-d H:i:s") . "|" . $ip_address . "|" . $name . "|" . $email . "|" . $message . PHP_EOL;
        if(fwrite($fp, $input)){
            fclose($fp);
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}


function display_all_submits(){
    // Check if file exists before attempting to open it
    if (file_exists("submit_file.txt")) {
        $lines = file("submit_file.txt");
        foreach($lines as $line){
            $words = explode("|", $line);
            $i = 0;
            foreach($words as $word){
                if($i == 0){
                    echo "date: $word <br>";
                }elseif($i == 1){
                    echo "IP: $word <br>";
                }elseif($i == 2){
                    echo "Name: $word <br>";
                }elseif($i == 3){
                    echo "Email: $word <br>";
                }else{
                    echo "Message: $word <br>";
                    echo "<br> <br>";
                }
                $i++;
            }
        }
    } else {
        echo "File does not exist.";
    }
}



?>