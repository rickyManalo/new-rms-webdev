<?php
//TODO: add some description/tooltip to fields like author describing how they work.
//that you can just type an author's name to add it to the database
if (isset($_POST['submitPB'])) {
    $date_published = $_POST["date_published"];
    $date_published = isset($_POST['date_published']) ? $_POST['date_published'] : null;
    if (!$date_published) {
        $date_published = null;
    } else {
        $date_published = $_POST["date_published"];
    }
    $if_funded = isset($_POST['funding_type']) ? $_POST['funding_type'] : null;
    if (!$if_funded) {
        $if_funded = "";
    } else {
        $if_funded = $_POST["funding_type"];
    }
    $sdg = $_POST["sdg_no"];
    $sdg_no = isset($_POST['sdg_no']) ? $_POST['sdg_no'] : null;
    if (!$sdg_no) {
        $sdg_no = null;
    } else {
        $sdg_no = $_POST["sdg_no"];
        $sdg_string = implode(", ", $sdg);
    }
    $quartile_sem = $_POST["quartile"];
    $quartile_year = $_POST["quartile_year"];
    $department = $_POST["research_area"];
    $college = $_POST["college"];
    $campus = $_POST["campus"];
    $title = $_POST["title_of_paper"];
    $type = $_POST["type_of_publication"];
    $url = $_POST["google_scholar_details"];
    $funding_nature = $_POST["nature_of_funding"];
    $publisher = $_POST["publisher"];
    $abstract = $_POST["abstract"];

    $authors_name = isset($_POST['author_name']) ? $_POST['author_name'] : null;

    if (!$authors_name) {
        $authors_name = "";
        $authors_string = "";
    } else {
        $author_ids = array();
    
        foreach ($authors_name as $name) {
    
            $url = 'http://localhost:5000/table_authors';
            $response = file_get_contents($url);
    
            if ($response !== false) {
                $data = json_decode($response, true);
    
                if (isset($data['table_authors'])) {
                    $authorIdColumn = array_column($data['table_authors'], 'author_id');
                    $authorNameColumn = array_column($data['table_authors'], 'author_name');
    
                    $authorMapping = array_combine($authorIdColumn, $authorNameColumn);
    
                    foreach ($authorMapping as $author_id => $author_name) {
                        if ($author_name == $name) { // Check if author_name matches the desired name
                            $author_ids[] = $author_id;
                        }
                    }
                }
            }
        }
    
        $authors_string = implode(",", $author_ids);
    }

    $quartileJoin = array();
    foreach ($quartile_sem as $index => $value) {
        $quartileJoin[] = $value . '_' . $quartile_year[$index];
    }
    $quartile = implode(", ", $quartileJoin);

    if ($if_funded == "internal") {
        $if_external = "BatState-U Research Fund";
    } else {
        $if_external = isset($_POST['funding_source']) ? $_POST['funding_source'] : null;
        if (!$if_external) {
            $if_external = "";
        } else {
            $if_external = $_POST["funding_source"];
        }
    }

    $postData = array(
        'date_published' => $date_published,
        'quartile' => $quartile,
        'authors' => $authors_string,
        'department' => $department,
        'college' => $college,
        'campus' => $campus,
        'title_of_paper' => $title,
        'type_of_publication' => $type,
        'funding_source' => $if_external,
        'google_scholar_details' => $url,
        'sdg_no' => $sdg_string,
        'funding_type' => $if_funded,
        'nature_of_funding' => $funding_nature,
        'publisher' => $publisher,
        'abstract' => $abstract,
        'number_of_citation' => 0
    );

    // Convert the data array to JSON
    $jsonData = json_encode($postData);

    // Set the cURL options
    $ch = curl_init('http://localhost:5000/table_publications');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        header("Location: ../../publications.php?upload=failed");
    } else {
        echo "Insert successful.";
        header("Location: ../../publications.php?upload=success");
    }

    // Close cURL session
    curl_close($ch);
} else {
    header("Location: ../../publications.php");
}
?>


//TODO: add some description/tooltip to fields like author describing how they work. 
//that you can just type an author's name to add it to the database 
include dirname(__FILE__, 6) . '/helpers/db.php';

if (isset($_POST['submitPB'])) {
    $date_published = $_POST["date_published"];
    $date_published = isset($_POST['date_published']) ? $_POST['date_published'] : null;
    if (!$date_published) {
        $date_published = null;
    }else{
        $date_published = $_POST["date_published"];
    }
    $if_funded = isset($_POST['funding_type']) ? $_POST['funding_type'] : null;
    if (!$if_funded) {
        $if_funded = "";
    }else{
        $if_funded = $_POST["funding_type"];
    }
    $sdg = $_POST["sdg_no"];
    $sdg_no = isset($_POST['sdg_no']) ? $_POST['sdg_no'] : null;
    if (!$sdg_no) {
        $sdg_no = null;
    }else{
        $sdg_no = $_POST["sdg_no"];
        $sdg_string = implode(", ", $sdg);
    }
    $quartile_sem = $_POST["quartile"];
    $quartile_year = $_POST["quartile_year"];
    $department = $_POST["research_area"]; 
    $college = $_POST["college"];
    $campus = $_POST["campus"];
    $title = $_POST["title_of_paper"];
    $type = $_POST["type_of_publication"];
    $url = $_POST["google_scholar_details"];
    $funding_nature = $_POST["nature_of_funding"];
    $publisher = $_POST["publisher"]; 
    $abstract = $_POST["abstract"];

    $authors_name = isset($_POST['author_name']) ? $_POST['author_name'] : null;
    if (!$authors_name) {
        $authors_name = "";
        $authors_string = "";
    } else {
        $select_query = "SELECT author_id FROM table_authors WHERE author_name = $1";
        $select_stmt = pg_prepare($conn, "select_author_details", $select_query);

        $author_ids = array(); // Define the array outside the loop

        foreach ($authors_name as $name) { // Change variable name from $author_name to $authors_name
            $auth_name = pg_escape_string($conn, $name);

            if (!empty($auth_name)) { // Check if the name is not empty
                $sql = "INSERT INTO table_authors (author_name)
                        SELECT '$auth_name'
                        WHERE NOT EXISTS (SELECT 1 FROM table_authors WHERE author_name = '$auth_name')";
                pg_query($conn, $sql);

                $select_result = pg_execute($conn, "select_author_details", array($auth_name));

                while ($row = pg_fetch_assoc($select_result)) {
                    $author_ids[] = $row['author_id'];
                }
            }
        }

        $authors_string = implode(",", $author_ids); // join the array values with a comma delimiter
    }
    
    $quartileJoin = array();
    foreach ($quartile_sem as $index => $value) {
        $quartileJoin[] = $value . '_' . $quartile_year[$index];
    }
    $quartile = implode(", ", $quartileJoin);

    if ($if_funded == "internal") {
        $if_external = "BatState-U Research Fund";
      } else {
        $if_external = isset($_POST['funding_source']) ? $_POST['funding_source'] : null;
        if (!$if_external) {
            $if_external = "";
        }else{
            $if_external = $_POST["funding_source"];
        }
      }
    

    $insert_query = "INSERT INTO table_publications (date_published, quartile, authors, department, college, campus, title_of_paper, type_of_publication, funding_source, google_scholar_details, sdg_no, funding_type, nature_of_funding, publisher, abstract) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)";
    $insert_stmt = pg_prepare($conn, "insert_publication_details", $insert_query);
    $insert_result = pg_execute($conn, "insert_publication_details", array($date_published, $quartile, $authors_string, $department, $college, $campus, $title, $type, $if_external, $url, $sdg_string, $if_funded, $funding_nature, $publisher, $abstract));
    
    if ($insert_result) {
        echo "Insert successful.";
        header("Location: ../../publications.php?upload=success");
    } else {
        header("Location: ../../publications.php?upload=failed");
    }
} else {
    header("Location: ../../publications.php");
}   
