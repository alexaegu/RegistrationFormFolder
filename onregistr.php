<?php

require "initialclass.php";

class Second extends First
{
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
    
    public function __construct()
    {
        require "pdodata.php";
      
        // Здесь вызываем конструктор родительского класса, потому что создание экземпляра класса-потомка запускает автоматически только конструктор класса-потомка, но не конструктор класса-родителя; а нам нужно получить данные из полей формы регистрации, которые устанавливаются в конструкторе класса-родителя
        parent::__construct();
    }
    
    ///////////////////////////////////
    
    // Используем public, так как функция будет вызвана извне объявления класса
    public function ResultOfRegistration()
    {
        echo "<html> \n <head> \n <title> \n";
        echo "Результат регистрации";
        echo "</title> \n <meta charset = \"utf-8\">";
        echo "</head> \n <body> \n";
        $this->Verification();
    
        // Проверим существование введённых данных в базе; если всё в порядке, зарегистрируем их
        $this->VerificationInBase();
        
        echo "</body> \n </html> \n";
    }
   
     ///////////////////////////////////
   
    protected function VerificationInBase()
    {
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
        
        $statement = $pdoVar->query('SELECT Login1, Email1 FROM Registrations');
        while (($stroka = $statement->fetch()) !== false) {
            if ($stroka['Login1'] == ($this->Rname)) {
                echo "Такой логин в базе уже зарегистрирован. Вернитесь назад и попробуйте снова";
                exit;
            }
          
            if ($stroka['Email1'] == ($this->Remail)) {
                echo "Такой электронный адрес в базе уже зарегистрирован. Вернитесь назад и попробуйте снова";
                exit;
            }
        }
        
        $statement = $pdoVar->prepare("INSERT INTO Registrations VALUES (:Login1, :Password1, :Email1, :Date1)");
        $statement->bindValue(':Login1', $this->Rname);
        $statement->bindValue(':Password1', password_hash($this->Rpassword, PASSWORD_DEFAULT));
        $statement->bindValue(':Email1', $this->Remail);
        $statement->bindValue(':Date1', date("d-m-Y H:i:s"));
        $statement->execute();
        
        echo "Вы успешно зарегистрировались в системе. Поздравляем! </br> Сейчас можете вернуться назад и авторизоваться";
        
        $pdoVar = null;
    }
}

$var = new Second();
$var->ResultOfRegistration();
