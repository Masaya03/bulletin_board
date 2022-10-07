    <?php
    
    class tbtest{
        private $dsn;
        private $user;
        private $password;
        private $pdo;
        
        function __construct($dsn, $user, $password){
            $this->dsn = $dsn;
            $this->user = $user;
            $this->password = $password;
            
            // echo $this->dsn."<br>";
            // echo $this->user."<br>";
            // echo $this->password."<br>";
        }
        
        function Delete_table(){
            $sql = "DROP TABLE tbtest";
            $stmt = $this->pdo->query($sql);
            
        }
        
        function Print_info(){
            $sql = "SHOW CREATE TABLE tbtest";
            $result = $this->pdo-> query($sql);
            foreach($result as $row){
                echo $row[0];
                echo "<br>";
            }
        }
        
        function init(){
            $this->pdo = new PDO($this->dsn, $this->user, $this->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $sql = "CREATE TABLE IF NOT EXISTS tbtest"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "password char(32),"
            . "date DATETIME"
            .");";
            $stmt = $this->pdo->query($sql);
            // echo "connect success<br>";
    
        }
        
        function Insert($name, $comment, $password){
            $date = date("Y/m/d H:i:s");
            
            $sql = $this->pdo ->prepare("INSERT INTO tbtest (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
            $sql -> bindParam(":date", $date, PDO::PARAM_STR);
            $sql -> execute();
        }
        
        function Delete($id){
            $sql = "DELETE FROM tbtest where id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        function Get($id){
            $sql = 'SELECT * FROM tbtest WHERE id=:id LIMIT 1';
            $stmt = $this->pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $result = $stmt->fetchAll(); 
            
        //         foreach ($results as $row){
        //         //$rowの中にはテーブルのカラム名が入る
        //         echo "select is    ";
        //         echo $row['id'].', ';
        //         echo $row['name'].', ';
        //         echo $row['comment'].', ';
        //         echo $row["password"],", ";
        //         echo $row["date"]."<br>";
        //     echo "<hr>";
        //   }
            
            return $result[0];
        }
        
        function Update($id, $name, $comment, $password){
            $date = date("Y/m/d H:i:s");
            
            $sql = "UPDATE tbtest SET name=:name,comment=:comment,password=:password ,date=:date WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
            $stmt -> bindParam(":date", $date, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
        }
        
       
        function Print_all(){
            $sql = 'SELECT * FROM tbtest';
            $stmt = $this->pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].' ';
                echo $row['name'].' ';
                echo $row["date"]."<br>";
                echo $row['comment'].' <br>';
                // echo $row["password"],", ";
            echo "<hr>";
           }
        }    
    }
    ?>