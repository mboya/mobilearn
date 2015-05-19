
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

            $result = mysql_query("SELECT * FROM `users` WHERE`user_nickname` = '$un'");
            $count = mysql_num_rows($result);

            if ($count > 0){
                $result = mysql_fetch_array($result);
                $salt = $result['user_salt'];
                $encrypted_password = $result['user_password'];
                $hash = base64_encode(sha1($pw . $salt, true) . $salt);

                if ($hash == $encrypted_password){
                    $response["user"] = array();

                    $users["user_surname"] = $result["user_surname"];
                    $users["user_registration"] = $result["user_registration"];
                    $users["user_course"] = $result["user_course"]

                    array_push($response["user"], $users);
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
        elseif ($tag == 'register'){
            $sn = stripslashes($_POST['surname']);
            $ln = stripslashes($_POST['lastname']);
            $c = stripslashes($_POST['course']);
            $rn = stripslashes($_POST['registration']);
            $un = stripslashes($_POST['nickname']);

            $salt = sha1(rand());
            $salt = substr($salt, 0, 10);
            $pw = stripslashes($_POST['password']);
            $encrypted_password = base64_encode(sha1($pw.$salt, true));

            mysql_query('BEGIN');
            mysql_query('START TRANSACTION');
            $query = mysql_query("INSERT INTO `users`(`user_surname`, `user_lastname`, `user_course`, `user_registration`, `user_nickname`, `user_salt`, `user_password`, `created_at`, `updated_at`)
            VALUES ('$sn','$ln','$c','$rn',$un,$salt,$encrypted_password,NOW(),NOW())");
            if ($query){
                mysql_query('COMMIT');
                $response['success'] = 1;
                echo json_encode($response);
            }else{
                mysql_query('ROLLBACK');
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
