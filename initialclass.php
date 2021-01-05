<?php

class First
{
    protected $Rname;
    protected $Rpassword;
    protected $Rpassword2;
    protected $Remail;
  
    public function __construct()
    {
        $this->Rname = mb_strtolower($_POST['name']);
        $this->Rpassword = $_POST['password'];
        $this->Rpassword2 = $_POST['password2'];
        $this->Remail = $_POST['email'];
    }
  
    ///////////////////////////////////
  
    protected function LoginVerification()
    {
        $lengthMin = 6;
        $lengthMax = 10;
    
        if ((mb_strlen($this->Rname) < $lengthMin) || (mb_strlen($this->Rname) > $lengthMax)) {
            echo "Длина логина должна составлять от $lengthMin до $lengthMax символов. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
    
        if (preg_match('/^[a-z]+$/', (mb_substr($this->Rname[0], 0, 1))) == 0) {
            echo "Логин должен начинаться с латинской буквы. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
    
        if (preg_match('/^[a-z0-9]+$/', ($this->Rname)) == 0) {
            echo "В логине могут быть только латинские буквы и цифры. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
    }
  
    ///////////////////////////////////
  
    protected function PasswordVerification()
    {
        $lengthMin = 6;
        $lengthMax = 10;
    
        if (($this->Rpassword) !== ($this->Rpassword2)) {
            echo "Введённые пароли должны совпадать. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        if ((mb_strlen($this->Rpassword) < $lengthMin) || (mb_strlen($this->Rpassword) > $lengthMax)) {
            echo "Длина пароля должна составлять от $lengthMin до $lengthMax символов. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        // Проверяем пароль поэлементно
        for ($i = 0; $i < mb_strlen($this->Rpassword); $i++) {
            if (preg_match('/^[a-zA-Z0-9]+$/', mb_substr($this->Rpassword, $i, 1)) == 0) {
                echo "Ничего кроме цифр и латинских букв в пароле быть не должно. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }
    
        /* В этом месте данной функции мы знаем, что проверяемый пароль состоит из латинских букв и / или цифр. Но мы не знаем, состоит ли он только из букв, только из цифр, или одновременно из того и другого. Нам нужен третий вариант. Только буквы или только цифры - неправильно. Проверим теперь пароль поэлементно на "только буквы" и "только цифры" */
    
        // Первый элемент пароля
        $element = mb_substr($this->Rpassword, 0, 1);
    
        // Проверим, цифра ли это
        if (preg_match('/^[0-9]+$/', $element) == 1) {
            // Проверяем поэлементно на цифры со второго элемента
            for ($i = 1; $i < mb_strlen($this->Rpassword); $i++) {
                if (preg_match('/^[0-9]+$/', mb_substr($this->Rpassword, $i, 1)) == 1) {
                    continue;
                } else {
                    break;
                }
            }
            if ($i == mb_strlen($this->Rpassword)) {
                echo "В пароле должны быть не только цифры, но и буквы. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        } else {
            // Если первый элемент пароля не цифра, то это буква. Проверим поэлементно пароль на буквы со второго элемента
            for ($i = 1; $i < mb_strlen($this->Rpassword); $i++) {
                if (preg_match('/^[a-zA-Z]+$/', mb_substr($this->Rpassword, $i, 1)) == 1) {
                    continue;
                } else {
                    break;
                }
            }
            if ($i == mb_strlen($this->Rpassword)) {
                echo "В пароле должны быть не только буквы, но и цифры. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }
    }
  
    ///////////////////////////////////
  
    protected function EmailVerification()
    {
        if ((mb_strlen($this->Remail) > 30)) {
            echo "Длина e-mail должна составлять не более 30 символов. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        if (preg_match('/[[:space:]]/', ($this->Remail)) == 1) {
            echo "В адресе не может быть пробелов. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        if (($this->Remail) == '@') {
            echo "Адрес не может состоять только из собаки. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        // Разделим адрес на части до собаки и после
        $emArray = explode('@', $this->Remail);

        if ((count($emArray) < 2)) {
            echo "Адрес не может быть пустым; в нём должна быть собака. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        if ((count($emArray) > 2)) {
            echo "В адресе не может быть больше одной собаки. </br> Вернитесь назад и попробуйте снова";
            exit;
        }

        if ((mb_strlen($emArray[0]) == 0) || (mb_strlen($emArray[1]) == 0)) {
            echo "Адрес не может начинаться или кончаться собакой. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
  
        $length0 = mb_strlen($emArray[0]) - 1;
        $length1 = mb_strlen($emArray[1]) - 1;
        if ((mb_substr($emArray[0], 0, 1) == '.') || (mb_substr($emArray[1], 0, 1) == '.') || (mb_substr($emArray[0], $length0, 1) == '.') || (mb_substr($emArray[1], $length1, 1) == '.')) {
            echo "Части адреса до собаки и после неё не могут начинаться или кончаться точкой. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
    
        // Разделим части адреса на части между точками: $emArray0 - до собаки, $emArray1 - после собаки
        $emArray0 = explode('.', $emArray[0]);
        $emArray1 = explode('.', $emArray[1]);

        for ($i = 0; $i < count($emArray0); $i++) {
            if (mb_strlen($emArray0[$i]) == 0) {
                echo "В адресе две точки не могут стоять рядом. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }

        for ($i = 0; $i < count($emArray1); $i++) {
            if (mb_strlen($emArray1[$i]) == 0) {
                echo "В адресе две точки не могут стоять рядом. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }

        // Выделяем первый символ в каждой части между точками до собаки и после неё; если этот символ - дефис, прерываем
        for ($i = 0; $i <count ($emArray0); $i++) {
            if (mb_substr($emArray0[$i], 0, 1) == '-') {
                echo "Элементы адреса не могут начинаться с дефиса. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }

        for ($i = 0; $i < count($emArray1); $i++) {
            if (mb_substr($emArray1[$i], 0, 1) == '-') {
                echo "Элементы адреса не могут начинаться с дефиса. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }

        // Проверяем каждую часть нашего адреса поэлементно: каждый элмент может быть одно из четырёх - дефис, подчёркивание, цифра или буква

        // Прежде всего мы объединим все элементы нашего адреса в единый стринг
        $ourString = '';
        for ($i = 0; $i < count($emArray0); $i++) {
            $ourString .= $emArray0[$i];
        }
        for ($i = 0; $i < count($emArray1); $i++) {
            $ourString .= $emArray1[$i];
        }

        // Затем мы полученный стринг проверяем поэлементно
        for ($i = 0; $i < mb_strlen($ourString); $i++) {
            if (preg_match('/^[a-zA-Z0-9_\-]+$/', mb_substr($ourString, $i, 1)) == 0) {
                echo "В адресе не может быть символов, отличающихся от дефиса, подчёркивания, цифр или букв.</br>";
                echo "Буквы должны быть латинскими. </br> Вернитесь назад и попробуйте снова";
                exit;
            }
        }
    }
  
    ///////////////////////////////////
  
    protected function Verification()
    {
        if ((mb_strlen($this->Rname) == 0) || (mb_strlen($this->Rpassword) == 0) || (mb_strlen($this->Rpassword2) == 0) || (mb_strlen($this->Remail) == 0)) {
            echo "Все поля обязательны для заполнения. </br> У вас есть незаполненные поля. </br> Вернитесь назад и попробуйте снова";
            exit;
        }
    
        // Проверим на корректность введённые данные для регистрации: логин, пароль и электронный адрес
        $this -> LoginVerification();
        $this -> PasswordVerification();
        $this -> EmailVerification();
    }
}
