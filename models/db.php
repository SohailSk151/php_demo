<?php
    
    class Database {
        private $host = "localhost";
        private $user = "root";
        private $password = "SohailSk@19";
        private $DBName = "user_auth";
        private $connection;

        public function __construct() {
            $this -> connection = mysqli_connect($this -> host, $this -> user, $this -> password, $this -> DBName);
        }

        function check_email($email, $mode) {
            if ($mode === "users") {
                $check_email = "SELECT id FROM users WHERE email = ?;";
            } else {
                $check_email = "SELECT id FROM admin WHERE email = ?;";
            }
            $check_stmt = $this -> connection -> prepare($check_email);
            $check_stmt -> bind_param("s", $email);
            $check_stmt -> execute();
            $check_stmt -> store_result();
            return $check_stmt -> num_rows;
        }

        function add_user($name, $email, $password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?);";
            $stmt = $this -> connection -> prepare($sql);
            $stmt -> bind_param("sss", $name, $email, $hashed_password);
            return $stmt -> execute();
        }

        function get_user($email, $password) {
            $sql = "SELECT * FROM users WHERE email = ?;";
            $stmt = $this -> connection ->prepare($sql);
            $stmt -> bind_param("s", $email);
            $stmt -> execute();
            $result = $stmt -> get_result();
            $connection = $result -> fetch_assoc();

            if ($connection && password_verify($password, $connection["password"])) {
                $_SESSION["user_id"] = $connection["id"];
                $_SESSION["user_name"] = $connection["name"];
                $stmt -> close();
                return "OK";
            } else {
                $stmt -> close();
                return "Invalid Email or Password";
            }
        }

        function get_all_users() {
            $sql = "SELECT name, email FROM users"; 
            $result = $this -> connection->query($sql);
            return $result;
        }

        function get_admin($email, $password) {
            $sql = "SELECT * FROM admin WHERE email = ?;";
            $stmt = $this -> connection->prepare($sql);
            $stmt -> bind_param("s", $email);
            $stmt -> execute();
            $result = $stmt->get_result();
            $connection = $result -> fetch_assoc();
            
            if($connection && password_verify( $password, $connection["password"] )) {
                $_SESSION["admin_id"] = $connection["id"];
                $_SESSION["admin_name"] = $connection["name"];
                return "OK";
            } else {
                $stmt -> close();
                return "Password is invalid!!";
            }
        }

        function add_admin($name, $email, $password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?);";
            $stmt = $this -> connection -> prepare($sql);
            $stmt -> bind_param("sss", $name, $email, $hashed_password);
            return $stmt -> execute();
        }

        function add_products($name, $description, $image, $price) {
            $sql = "INSERT INTO products (name, description, image, price) VALUES (?, ?, ?, ?);";
            $stmt = $this -> connection ->prepare($sql);
            $stmt -> bind_param("ssss", $name, $description, $image, $price);
            return $stmt -> execute();
        }

        function edit_product($id, $name, $description, $image, $price) {
            $update = $this -> connection->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
            $update->bind_param("ssssi", $name, $description, $price, $image, $id);
            return $update -> execute();
        }

        function delete_products($id) {
            $delete = $this -> connection->prepare("DELETE FROM products WHERE id=?");
            $delete->bind_param("i", $id);
            return $delete->execute();
        }

        function get_products() {
            $sql = "SELECT * FROM products";
            $result = $this -> connection->query($sql);
            return $result;
        }

        function get_product_by($id) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM products WHERE id=$id";
            $result = $this->connection->query($sql);
            $product = $result->fetch_assoc();
            return $product;
        }
    }
?>