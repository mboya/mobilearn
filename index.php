
<?php
    if (isset($_POST['tag'])&& $_POST['tag'] != '')
    {
        $response = array();

        require_once __DIR__ . '/db_connect.php';
        $db = new DB_CONNECT();

        $tag = stripslashes($_POST['tag']);

        if ($tag == 'login') {
            $un = stripslashes($_POST['nickname']);
            $pw = stripslashes($_POST['password']);

            $result = mysql_query("SELECT * FROM `users` WHERE`users_nickname` = '$un'");
            $count = mysql_num_rows($result);

            if ($count > 0){
                $result = mysql_fetch_array($result);
                $salt = $result['users_salt'];
                $encrypted_password = $result['users_encrypted_password'];
                $hash = base64_encode(sha1($pw . $salt, true) . $salt);

                if ($hash == $encrypted_password){
                    $response["users"] = array();

                    $users["users_names"] = $result["users_names"];
                    $users["users_phone_number"] = $result["users_phone_number"];

                    array_push($response["users"], $users);
                    $response["success"] = 1;
                    echo json_encode($response);
                }else{
                    $response["success"] = 0;
                    $response["message"] = "User/Password Missmatch";
                    echo json_encode($response);
                }
            } else {
                $response["success"] = 0;
                $response["message"] = "User Failed";
                echo json_encode($response);
            }
        }
        elseif ($tag == 'course'){
            $result = mysql_query("SELECT * FROM `courses`");
            if (mysql_num_rows($result) > 0){
                // looping through all the results
                $response['courses'] = array();
                while($row = mysql_fetch_array($result)){
                    $c = array();
                    $c['course_code'] = $row['course_code'];
                    $c['course_name'] = $row['course_name'];

                    array_push($response['courses'], $c);
                }
                $response['success'] = 1;
                echo json_encode($response);
            }else{
                $response['success'] = 0;
                echo json_encode($response);
            }
        }
    }
    else
    {
        echo "<h4 style='text-align:center;'> Access Denied ! </h4>";
    }


    /**
    written by: Mboya Berry (Mobile, Web Dev, Co-founder - Black Widow, Kenya)
    **/

?>
