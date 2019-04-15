<?php

class deployF {

private $deployf;

public function deployFile($req) {
	$data = $req['packageName'];
	$level = $req['level'];
	$fepackage = "Select Max(ver) AS Latest from fe";
	$bepackage = "Select Max(ver) AS Latest from be";
	if ($level == 'QA'){
		if($data == 'FEpackage-v'){
			$response = $this->deploydb->query($fepackage);
			$result = $response->fetch_assoc();
			$send_file = shell_exec('scp /feqav-'.$result.'.tar.gz johnny@192.168.43.61:/home/johnny/Desktop');
			$install_file = shell_exec('./installFEQA.php');
		}
		elseif($data == 'BEpackage-v'){
			$response = $this->deploydb->query($bepackage);
			$result = $response->fetch_assoc();
			$send_file = shell_exec('scp /beqav-'.$result.'.tar.gz ethan@192.168.43.62:/home/ethan/Desktop');
			$install_file = shell_exec('./installBEQA.php');
			
		}
				
	}
	elseif ($level == 'Prod'){
		if($data == 'FEpackage-v'){
			$response = $this->deploydb->query($fepackage);
			$result = $response->fetch_assoc();
			$send_file = shell_exec('scp /feqav-'.$result.'.tar.gz johnny@192.168.43.63:/home/johnny/Desktop');
			$install_file = shell_exec('./installFEQA.php');
		}
		elseif($data == 'BEpackage-v'){
			$response = $this->deploydb->query($bepackage);
			$result = $response->fetch_assoc();
			$send_file = shell_exec('scp /beqav-'.$result.'.tar.gz ethan@192.168.43.64:/home/ethan/Desktop');
			$install_file = shell_exec('./installBEQA.php');
			
		}
		
	}
	
}

public function movieAdd($user, $req){
	$usertable = $this->moviedb->real_escape_string($user);
	$title = $req['title'];
	$movieid = $req['id'];
	$release_date = $req['release_date'];
	$rating = $req['vote_average'];
	$check_table = "Select * from '$user' where id = '$movieid'";
	$new_data = "Insert into $usertable (title, id, release_date, rating) values ('$title', '$movieid', '$release_date', '$rating')";
	$bad_result = $this->moviedb->query($check_table);
	$rows = $bad_result->num_rows;
	if($rows > 0){
		return false;
	}
	else{
		$good_result = $this->moviedb->query($new_data);
		return true;
	}
} 

public function movieDelete($user, $req){
	$usertable = $this->moviedb->real_escape_string($user);
        $movieid = $req['id'];
        $check_table = "Select * from '$user' where id = '$movieid'";
        $remove_data = "Delete from $usertable where id = '$movieid'";
        $bad_result = $this->moviedb->query($check_table);
        $rows = $bad_result->num_rows;
        if($rows == 0){
                return false;
        }
        else{
                $good_result = $this->moviedb->query($remove_data);
                return true;
        }
}

public function wishMovies($user){
	$usertable = $this->moviedb->real_escape_string($user);
	$today = date("Y-m-d");
	$statement = "Select release_date from '$usertable'";
	$response = $this->moviedb->query($statement);
	while ($row = $response->fetch_array())
	{
		if($row["release_date"] > $today)
		{
			return $row;
		}
	}
}

public function upcomingMovies($req){
	$release_year = date('Y');
	$release_date = date('Y-m-d');
	$url = "https://api.themoviedb.org/3/discover/movie?api_key=db277983b36c1274dbe40cdeef5356e9&language=en-US&sort_by=primary_release_date.asc&include_adult=false&include_video=false&primary_release_year=". rawurlencode($release_year). "&primary_release_date.gte=".rawurlencode($release_date);
	$data = file_get_contents($url);
	$result = json_decode($data, true);
	return $result; 
	}

}

?>
