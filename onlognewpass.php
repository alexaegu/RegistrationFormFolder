<?php

class Fifth
{
    protected $ALogEmail;
    protected $formedstring;
    
    protected $hostname;
    protected $dbname;
    protected $username;
    protected $passw;
    protected $charset;
    
    public function __construct()
    {
        $this->ALogEmail = $_POST['Aname2'];
      
        require "pdodata.php";
    }
    
    ///////////////////////////////////
    
    // Функция формирования случайной последовательности букв и цифр с возможными повторениями
    protected function RandomString()
    {
        // Минимальная и максимальная длина пароля
        $minlength = 6;
        $maxlength = 10;
        
        /* Стринги из которых берутся символы нового пароля: для букв и для цифр отдельно, так как пароль должен состоять из букв и цифр одновременно. Стринг для букв содержит 52 символа, стринг для цифр - 10 символов */
        $letterstring = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numberstring = '0123456789';
        
        // Определим длину нового пароля
        $passlength = rand($minlength, $maxlength);
        
        // Определим число букв в новом пароле
        $chislett = rand(1, $passlength - 1);
        // Число цифр в новом пароле
        $chisnumb = $passlength - $chislett;
        
        // $levelett - счётчик установленных в новом пароле букв, $levenumb - счётчик установленных в новом пароле цифр
        $levelett = 0;
        $levenumb = 0;
        
        /* Вспомогательный уровень, показывающий необходимость или отсутствие необходимости установки индикатора буквы/цифры пароля. Если этот уровень равен нулю, индикатор устанавливаем. Если равен 1, не устанавливаем. Установка индикатора выполняется ниже, в цикле */
        $level = 0;
        
        // Обновлённый пароль, результат действия функции
        $this->formedstring = '';
        
        for ($k = 1; $k <= $passlength; $k++) {
            // k-ый символ пароля - буква, если $indicator == 1 и цифра, если $indicator == 2
            // Индикатор устанавливаем, если уровень равен нулю
            if ($level == 0) {
                $indicator = rand(1, 2);
            }
          
            if ($indicator == 1) {
                if ($levelett < $chislett) {
                    $this->formedstring .= mb_substr($letterstring, rand(0, 51), 1);
                    $levelett += 1;
                } else {
                    $indicator = 2;
                    $level = 1;
                    /* Если программа зашла в этот блок else, значит число установленных букв в пароле достигло заранее заданной величины. Следовательно, необходимо устанавливать цифру. Для этого надо на 1 понизить счётчик цикла, чтобы не потерять цифру и вернуться на новую итерацию цикла. Фактически $k-- и далее $k++ означает, что новая итерация пойдёт с тем же значением $k, но будет устанавливаться уже цифра, а не буква */
                    $k--;
                    continue;
                }
            } else {
                if ($levenumb < $chisnumb) {
                    $this->formedstring .= mb_substr($numberstring, rand(0, 9), 1);
                    $levenumb += 1;
                } else {
                    $indicator = 1;
                    $level = 1;
                    /* Если программа зашла в этот блок else, значит число установленных цифр в пароле достигло заранее заданной величины. Следовательно, необходимо устанавливать букву. Для этого надо на 1 понизить счётчик цикла, чтобы не потерять букву и вернуться на новую итерацию цикла. Фактически $k-- и далее $k++ означает, что новая итерация пойдёт с тем же значением $k, но будет устанавливаться уже буква, а не цифра */
                    $k--;
                    continue;
                }
            }
        }
        
        return $this->formedstring;
    }
  
    ///////////////////////////////////
    
    // Используем public, так как функция будет вызвана извне объявления класса
    public function RenewPassword()
    {
        echo "<html> \n <head> \n <title> \n";
        echo "Обновление пароля";
        echo "</title> \n <meta charset = \"utf-8\">";
        echo "</head> \n <body> \n";
        
        $dsn = "mysql:host=$this->hostname;dbname=$this->dbname;charset=$this->charset";
        $pdoVar = new PDO($dsn, $this->username, $this->passw);
        
        // $ALogEmail - это электронный адрес
        if (mb_strpos($this->ALogEmail, "@") !== false) {
            /* Известно, что функция PDO rowCount() не для всех СУБД возвращает правильное число строк в выборке запроса SELECT, а для запроса UPDATE она вообще вернёт ноль в случае, если обновление базы будет происходить с теми же самыми значениями: например, если подготовить UPDATE запрос для обновления пароля, но новый пароль установить таким же, каким был старый, то UPDATE такое обновление выполнит, но функция rowCount() для этого UPDATE вернёт ноль. Поэтому для подсчёта числа строк запроса воспользуемся другим подходом, не функцией rowCount() */
          
            // Число строк в таблице Registrations
            $statement = $pdoVar->query('SELECT COUNT(*) FROM Registrations');
            $chislostrok = ($statement->fetchColumn());
          
            // Проверяем в таблице Registrations существование введённого электронного адреса
            $statement = $pdoVar->query('SELECT Login1, Email1 FROM Registrations');
            for ($j = 1; $j <= $chislostrok; $j++) {
                $stroka = $statement->fetch();
                if (($stroka['Email1'] == ($this->ALogEmail))) {
                    // Запомним логин в переменной
                    $yourlogin = $stroka['Login1'];
                    // Запоминаем обновлённый пароль в переменной
                    $yourpassword = $this->RandomString();
              
                    // Введём запрос для обновления пароля данного пользователя
                    $statement = $pdoVar->prepare("UPDATE Registrations SET Password1 = :Password1 WHERE Email1 = :Email1");
                    $statement->bindValue(':Password1', password_hash($yourpassword, PASSWORD_DEFAULT));
                    $statement->bindValue(':Email1', $this->ALogEmail);
                    $statement->execute();
                    break;
                }
            }
            if ($j == ($chislostrok + 1)) {
                echo "Введённый электронный адрес в системе не зарегистрирован. </br>Вернитесь назад и попробуйте снова";
                exit;
            }      
        } else {
            // $ALogEmail - это что-то иное, не электронный адрес (так как не содержит символ собаки)
            echo "Вы неправильно ввели электронный адрес для обновления пароля. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
        
        $message = "Ваш логин: $yourlogin </br> Ваш новый пароль: $yourpassword";
        $resMail = mail($this->ALogEmail, 'Обновление пароля', $message);
        if ($resMail !== true) {
            echo "Пароль обновлён, но почту отправить невозможно. Проверьте настройки почтового сервера";
            echo "</br> $message";
            exit;
        } 
        
        echo "$message </br>";
        echo "На ваш электронный адрес отправлен логин и новый пароль. </br> Используйте данные из полученной электронной почты для авторизации";
        echo "</body> \n </html> \n";
        
        $pdoVar = null;
        
    }
}

$var = new Fifth();
$var->RenewPassword();
