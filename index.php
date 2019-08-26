<?php
	$username	= 'root';
	$password	= 'root';

	$host	= 'localhost';
	$db_Name	= 'HW4';

	$conn	=mysqli_connect ($host, $username, $password, $db_Name);

	$row = "";
	$rowArray = "";
	$flag = 0;
	$query	= "";
	$booksResult = "";
	

	if (($_SERVER['REQUEST_URI'] == '/books') or ($_SERVER['REQUEST_URI'] == '/books/'))
	{
		$query	= "SELECT Title, Price, Category FROM Book";
		$booksResult = mysqli_query ($conn, $query);

		if(mysqli_num_rows($booksResult) > 0)
		{
			while ($row = mysqli_fetch_assoc($booksResult))
			{
				$row_array = [];
				$row_array['Title'] = $row['Title'];
				$row_array['Price'] = $row['Price'];
				$row_array['Category'] = $row['Category'];
				$result[] = $row_array;
			}
			echo json_encode ($result, JSON_PRETTY_PRINT);
		}
	}
	else
	{
		$booksID	= explode ("/", $_SERVER ['REQUEST_URI'])[2];
		$query	= "SELECT * FROM Book WHERE Book_id = $booksID";

		$booksResult = mysqli_query ($conn, $query);

		if (mysqli_num_rows($booksResult) > 0)
		{
			$query	= "SELECT b.Book_id, b.Title, b.Year, b.Price, b.Category, GROUP_CONCAT(a.Author_Name) AS Author_Name
			FROM Book b
			JOIN Book_Author ba ON b.Book_id = ba.Book_id
			JOIN Author a ON ba.Author_id = a.Author_id
			WHERE b.Book_id = $booksID
			GROUP BY b.Book_id";

			$booksResult	= mysqli_query ($conn, $query);

			while ($row = mysqli_fetch_assoc($booksResult))
			{
				$array = [];
				$array['Title'] = $row['Title'];
				$array['Year'] = $row['Year'];
				$array['Price'] = $row['Price'];
				$array['Category'] = $row['Category'];
				$array['Author_Name'] = $row['Author_Name'];
				$result[] = $array;
			}
			echo json_encode ($result, JSON_PRETTY_PRINT);
		}
	}

?>
