<?php

    class Validate {
        private $email_pattern = "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";
        private $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/";
        
        public function registration($name, $email, $password) {
            if (isset($name) && isset($email) && isset($password)) {
                if (!preg_match($this->email_pattern, $email)) {
                    return "Please enter a valid email address!!!";
                }

                if (strpos(strtolower(trim($name)), strtolower(trim($password))) !== false) {
                    return "Name should not be there in password!!!";
                }
                
                if(!preg_match($this -> password_pattern, $password)) {
                    return "Password Pattern is not matching!!!";
                }

                return "OK";
            } else {
                return "Fields can't be NULL";
            }
        }

        public function login($email, $password) {
            if (isset($email) && isset($password)) {
                if (!preg_match($this->email_pattern, $email)) {
                    return "Please enter a valid email address!!!";
                }
                
                if(!preg_match($this -> password_pattern, $password)) {
                    return "Password Pattern is not matching!!!";
                }

                return "OK";
            } else {
                return "Fields can't be NULL";
            }
        }
    }
?>