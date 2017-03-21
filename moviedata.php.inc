<?php
header('Content-Type: text/plain');

class movieDB {

private $moviedb;

public function __construct() {
	$this->moviedb = new mysqli('localhost', 'root', 'godemper', 'movies');
	if($this->moviedb->connect_error){
	die('Connect Error (' . $this->moviedb->connect_errno . ')' . $this->moviedb->connect_error);
	}
}

public function movieSearch($req) {
	$movie = $req;
	$url = "https://api.themoviedb.org/3/search/movie?query=". rawurlencode($movie) . "&language=en-US&api_key=db277983b36c1274dbe40cdeef5356e9";
	$data = file_get_contents($url);
	$result = json_decode($data, true);
	return $result; 

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
	$movie = date('Y');
	$url = "https://api.themoviedb.org/3/discover/movie?api_key=db277983b36c1274dbe40cdeef5356e9&language=en-US&sort_by=primary_release_date.asc&include_adult=false&include_video=false&primary_release_year=". rawurlencode($movie). "&primary_release_date.gte=".rawurlencode($req);
	$data = file_get_contents($url);
	$result = json_decode($data, true);
	return $result; 
	}

}

?>
