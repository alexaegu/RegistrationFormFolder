<?php

class Third
{
    protected $ALogEmail;
    protected $APassword;
  
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
  
    public function __construct ()
    {
        $this->ALogEmail = $_POST['Aname'];
        $this->APassword = $_POST['Apassword'];
    
        require "pdodata.php";
    }
  
    ///////////////////////////////////
  
    // Используем public, так как функция будет вызвана извне объявления класса
    public function ResultOfAuthorization()
    {
        echo "<html> \n <head> \n <title> \n";
        echo "Результат авторизации";
        echo "</title> \n <meta charset = \"utf-8\">";
        echo "</head> \n <body> \n";
        
        // Проверим существование введённых данных в базе; если всё в порядке, зарегистрируем их
          $this->VerificationOfLoginEmailPassw();
          
        echo "Вы успешно авторизовались в системе. Поздравляем! </br> Для выхода из системы нажмите кнопку ниже";
        
        echo "<form action=\"exitfromsystem.php\" method=\"post\">";
        echo "<p><input type=\"submit\" value=\"Выйти из системы\"></p>";
        echo "</form>";
        
        echo "</body> \n </html> \n";
    }
 
    ///////////////////////////////////
 
    protected function VerificationOfLoginEmailPassw()
    {
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
        
        /* Известно, что функция PDO rowCount() не для всех СУБД возвращает правильное число строк в выборке запроса SELECT, а для запроса UPDATE она вообще вернёт ноль в случае, если обновление базы будет происходить с теми же самыми значениями: например, если подготовить UPDATE запрос для обновления пароля, но новый пароль установить таким же, каким был старый, то UPDATE такое обновление выполнит, но функция rowCount() для этого UPDATE вернёт ноль. Поэтому для подсчёта числа строк запроса воспользуемся другим подходом, не функцией rowCount() */
          
        // Число строк в таблице Enters
        $statement2 = $pdoVar->query('SELECT COUNT(*) FROM Enters');
        $chislostrok2 = ($statement2->fetchColumn());
          
        // Введём вспомогательный запрос для проверки того, не авторизован ли уже данный пользователь
        $statement2 = $pdoVar->query('SELECT Login1, Rate2 FROM Enters');
        
        // Запустим сессии для передачи логина зарегистрированного пользователя в файл "exitfromsystem.php"
        session_start();
    
        // Проверим сначала $ALogEmail: что это? Логин или e-mail? Если содержится символ собаки, то это e-mail; иначе - логин
        
        // $ALogEmail - это логин    
        if (mb_strpos($this->ALogEmail, "@") === FALSE) {
            $this->ALogEmail = mb_strtolower($this->ALogEmail);
      
            // Число строк в таблице Registrations
            $statement = $pdoVar->query('SELECT COUNT(*) FROM Registrations');
            $chislostrok = ($statement->fetchColumn());
            
            $statement = $pdoVar->query('SELECT Login1, Password1 FROM Registrations');
            for ($j = 1; $j <= $chislostrok; $j++) {
                $stroka = $statement->fetch();
                if (($stroka['Login1'] == ($this->ALogEmail)) && password_verify(($this->APassword), $stroka['Password1'])) {
                    /* Если логин и пароль в форме авторизации введены правильно, то прежде выполнения запроса на вставку данных в таблицу Enters, необходимо проверить, что пользователь с таким логином не является уже авторизованным (во избежание двойной одновременной авторизации одного и того же пользователя) */
                    for ($j2 = 1; $j2 <= $chislostrok2; $j2++) {
                        $stroka2 = $statement2->fetch();
                        if (($stroka2['Login1'] == ($this->ALogEmail)) && ($stroka2['Rate2'] == 1)) {
                            echo " Вы уже авторизованы! </br> Двойная авторизация невозможна! </br> Для выхода из системы нажмите кнопку ниже! </br>";
                            echo "<form action=\"exitfromsystem.php\" method=\"post\">";
                            $_SESSION['somevalue'] = $this->ALogEmail;
                            echo "<p><input type=\"submit\" value=\"Выйти из системы\"></p>";
                            echo "</form>";
                            exit;
                        }
                    }
                    $statement = $pdoVar->prepare("INSERT INTO Enters VALUES (:Login1, :Date2, :Rate2)");
                    // Здесь в поле логина вставляем значение $this->ALogEmail
                    $statement->bindValue (':Login1', $this->ALogEmail);
                    $statement->bindValue (':Date2', date("d-m-Y H:i:s"));
                    $statement->bindValue (':Rate2', 1);
                    $statement->execute();
                    /* Передаём в вызывающую функцию ResultOfAuthorization с помощью сессии логин, с которым произошла авторизация, так как нижеследующий оператор break приведёт к выходу не только из цикла, но и к завершению выполнения функции VerificationOfLoginEmailPassw, поскольку после данного цикла тела функции нет */
                    $_SESSION['somevalue'] = $this->ALogEmail;
                    break;
                }
            }
            if ($j == ($chislostrok + 1)) {
                echo "Ошибка в логине или пароле: таких данных в системе не зарегистрировано. </br>Вернитесь назад и попробуйте снова";
                exit;
            }
        } else {
            // $ALogEmail - это электронный адрес
            // Число строк в таблице Registrations
            $statement = $pdoVar->query('SELECT COUNT(*) FROM Registrations');
            $chislostrok = ($statement->fetchColumn());
          
            $statement = $pdoVar->query('SELECT Login1, Password1, Email1 FROM Registrations');
            for ($j = 1; $j <= $chislostrok; $j++) {
                $stroka = $statement->fetch();
                if (($stroka['Email1'] == ($this->ALogEmail)) && password_verify (($this->APassword), $stroka['Password1'])) {
                    /* Если логин и пароль в форме авторизации введены правильно, то прежде выполнения запроса на вставку данных в таблицу Enters, необходимо проверить, что пользователь с таким логином не является уже авторизованным (во избежание двойной одновременной авторизации одного и того же пользователя) */
                    for ($j2 = 1; $j2 <= $chislostrok2; $j2++) {
                        $stroka2 = $statement2->fetch();
                        if (($stroka2['Login1'] == $stroka['Login1']) && ($stroka2['Rate2'] == 1)) {
                            echo " Вы уже авторизованы! </br> Двойная авторизация невозможна! </br> Для выхода из системы нажмите кнопку ниже! </br>";
                            echo "<form action=\"exitfromsystem.php\" method=\"post\">";
                            $_SESSION['somevalue'] = $stroka['Login1'];
                            echo "<p><input type=\"submit\" value=\"Выйти из системы\"></p>";
                            echo "</form>";
                            exit;
                        }
                    }   
                    $statement = $pdoVar->prepare("INSERT INTO Enters VALUES (:Login1, :Date2, :Rate2)");
                    // Здесь в поле логина вставляем значение, которому соответствует e-mail $this->ALogEmail
                    $statement->bindValue (':Login1', $stroka['Login1']);
                    $statement->bindValue (':Date2', date("d-m-Y H:i:s"));
                    $statement->bindValue (':Rate2', 1);
                    $statement->execute();
                    /* Передаём в вызывающую функцию ResultOfAuthorization с помощью сессии логин, с которым произошла авторизация, так как нижеследующий оператор break приведёт к выходу не только из цикла, но и к завершению выполнения функции VerificationOfLoginEmailPassw, поскольку после данного цикла тела функции нет */
                    $_SESSION['somevalue'] = $stroka['Login1'];
                    break;
                }
            }
            if ($j == ($chislostrok + 1)) {
                echo "Ошибка в логине или пароле: таких данных в системе не зарегистрировано. </br>Вернитесь назад и попробуйте снова";
                exit;
            }      
        }
        
        $pdoVar = null;
    }
}

$var = new Third();
$var->ResultOfAuthorization();
