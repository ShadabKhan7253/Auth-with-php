<?php 

    class Token {
        protected $types = [
            'REMEMBER_ME' => 0,
            'FORGOT_PASSWORD' => 1,
            'VERIFICATION_EMAIL' => 2,
        ];

        protected Database $database;
        private static $REMEMBER_ME_EXPIRY_TIME = '30 minutes';
        private static $FORGOT_PASSWORD_EXPIRY_TIME = '10 minutes';
        private static $VERIFICATION_EMAIL_EXPIRY_TIME = '14400 minutes';
        public static $REMEMBER_ME_EXPIRY_TIME_IN_SECS = 1800;
        private string $table = "tokens";

        public function __construct(Database $database) {
            $this->database = $database;
        }

        public function build() {
            // $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,user_id INT,token VARCHAR(255) UNIQUE,expires_at DATETIME NOT NULL,type TINYINT)";
            $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (id BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT, user_id INT, token VARCHAR(255) UNIQUE, expires_at DATETIME NOT NULL, type TINYINT NOT NULL)";
            $this->database->query($sql);
        }

        public function getValidRememberMeToken($userId){
            
            return $this->getValidExistingToken($userId , $this->types['REMEMBER_ME']);
        }

        private function getValidExistingToken(int $userId , string $type) {
            if(!in_array($type , array_keys($this->types))) {
                return null;
            }

            $token = $this->database->rawQueryExecutor("SELECT * FROM {$this->table} WHERE user_id = $userId AND type = {$type} AND expires_at >= now()"); 
            return $token;
        }

        public function createRememberMeToken(int $userId) {
            $current = date('Y-m-d H:i:s');
            $expiryTime = date('Y-m-d H:i:s' , strtotime($current . "+" . Token::$REMEMBER_ME_EXPIRY_TIME));
            // dd($expiryTime); // string(19) "2023-04-05 17:10:18"
            return $this->createToken($userId , $this->types['REMEMBER_ME'] , $expiryTime);
        }

        public function createForgotPasswordToken(int $userId) {
            $current = date('Y-m-d H:i:s');
            $expiryTime = date('Y-m-d H:i:s' , strtotime($current . "+" . Token::$FORGOT_PASSWORD_EXPIRY_TIME));
            return $this->createToken($userId , $this->types['FORGOT_PASSWORD'] , $expiryTime);
        }
        
        public function createVerificationEmailToken(int $userId) {
            $current = date('Y-m-d H:i:s');
            $expiryTime = date('Y-m-d H:i:s' , strtotime($current . "+" . Token::$VERIFICATION_EMAIL_EXPIRY_TIME));
            return $this->createToken($userId , $this->types['VERIFICATION_EMAIL'] , $expiryTime);
        }
        
        private function createToken(int $userId, int $type , string $expiryTime) {
            $token = $this->getValidRememberMeToken($userId);
            if($token != null && !empty($token)) {
                return $token[0];
            }

            $token = Hash::generateRandomToken($userId);
            
            
            $data = [
                'user_id' => $userId,
                'token' => $token,
                'expires_at' => $expiryTime,
                'type' => $type
            ];
            // dd($data);
            // dd($this->database->table($this->table)->insert($data) ? $data : null);
            return $this->database->table($this->table)->insert($data) ? (object)$data : null;
        }
        
        public function deleteRememberMeToken(int $userId , bool $deleteOnlyValidate = false) {
            return $this->deleteToken($userId, $this->types['REMEMBER_ME'] , $deleteOnlyValidate);
        }

        public function deleteForgotPasswordToken(int $userId , bool $deleteOnlyValidate = false) {
            return $this->deleteToken($userId, $this->types['FORGOT_PASSWORD'] , $deleteOnlyValidate);
        }

        public function deleteVerificationEmailToken(int $userId , bool $deleteOnlyValidate = false) {
            return $this->deleteToken($userId, $this->types['VERIFICATION_EMAIL'] , $deleteOnlyValidate);
        }

        public function deleteToken(int $userId , int $type ,bool $deleteOnlyValidate) {
            $queryBuilder = $this->database->table($this->table)
                                           ->where('user_id' , $userId)
                                           ->where('type' , $type);

            if($deleteOnlyValidate) {
                $current = date('Y-m-d H:i:s');
                $queryBuilder = $queryBuilder->where('expires_at' , $current , ">=");
            }

            return $queryBuilder->delete();
        }

        public function isValidRememberMe(string $userToken) {
            return $this->isValid($userToken, $this->types['REMEMBER_ME']);
        }

        public function isValidForgotPassword(string $userToken) {
            return $this->isValid($userToken, $this->types['FORGOT_PASSWORD']);
        }

        public function isValidVerificationEmail(string $userToken) {
            return $this->isValid($userToken, $this->types['VERIFICATION_EMAIL']);
        }

        private function isValid(string $userToken , int $type) {
            $current = date('Y-m-d H:i:s');
            return $this->database->table($this->table)
                        ->where("token" ,$userToken)
                        ->where("expires_at" ,$current, ">=")
                        ->where("type" ,$type)
                        ->first();
        }
    }
?>