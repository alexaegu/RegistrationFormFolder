<?php

class Fourth
{
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
  
    public function __construct()
    {
        require "pdodata.php";
    }
  
    ///////////////////////////////////
  
    // Используем public, так как функция будет вызвана извне объявления класса
    public function ResultOfExit()
    {
        echo "<html> \n <head> \n <title> \n";
        echo "Выход из системы";
        echo "</title> \n <meta charset = \"utf-8\">";
        echo "</head> \n <body> \n";
    
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
    
        //Запустим сессии для передачи логина зарегистрированного пользователя на выход из системы"
        session_start();
      
        // Введём запрос для обновления Rate2 данного пользователя
        $statement = $pdoVar->prepare("UPDATE Enters SET Rate2 = 0 WHERE Login1 = :Login1");
        $statement->bindValue(':Login1', $_SESSION['somevalue']);
        $statement->execute();
    
        echo "Вы успешно вышли из системы. </br> Когда захотите войти в систему снова, авторизуйтесь. </br> До новых встреч!";
        echo "</body> \n </html> \n";
    
        $pdoVar = null;
    
        unset ($_SESSION['somevalue']);
        session_destroy();
    }
}

$var = new Fourth();
$var->ResultOfExit();
