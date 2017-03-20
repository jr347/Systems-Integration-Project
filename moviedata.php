<?php

class movieDB {

private $movieDB

public function __construct(){
	$this->movieDB = new mysqli('localhost', 'root', 'godemper', 'movies');
	if($this->movieDB->connect_error){
	die('Connect Error (' . $this->movieDB->connect_errno . ')' . $this.->movieLU->connect_error);
	}
}

public function movieSearch($req){
	$movie = $this->movieDB->real_escape_string($req);
	$url = "https://api.themoviedb.org/3/search/movie?query=". $movie ."&language=en-US&api_key=db277983b36c1274dbe40cdeef5356e9";
	$data = file_get_contents($url);
	$result = json_decode($data, true);
	return $result; 

}

public function movieAdd($user, $req){
	$usertable = $this->movieDB->real_escape_string($user);
	$title = $req['title'];
	$movieid = $req['id'];
	$release_date = $req['release_date'];
	$rating = $req['vote_average'];
	$check_table = "Select * from '$user' where id = '$movieid'";
	$new_data = "Insert into $usertable (title, id, release_date, rating) values ('$title', '$movieid', '$release_date', '$rating')";
	$bad_result = $this->movieDB->query($check_table);
	$rows = $bad_result->num_rows;
	if($rows > 0){
		return false;
	}
	else{
		$good_result = $this->movieDB->query($new_data);
		return true
	}
} 

public function movieDelete($user, $req){
	$usertable = $this->movieDB->real_escape_string($user);
        $movieid = $req['id'];
        $check_table = "Select * from '$user' where id = '$movieid'";
        $remove_data = "Delete from $usertable where id = '$movieid'";
        $bad_result = $this->movieDB->query($check_table);
        $rows = $bad_result->num_rows;
        if($rows == 0){
                return false;
        }
        else{
                $good_result = $this->movieDB->query($remove_data);
                return true
        }
}

public function upcomingMovies($user){
	$usertable = $this->movieDB->real_escape_string($user);
	$today = date("Y-m-d");
	$statement = "Select release_date from '$usertable'";
	$response = $this->movieDB->query($statement);
	while ($row = $response->fetch_array())
	{
		if($row["release_date"] > $today)
		{
			return $row;
		}
	}
}

}

?>
