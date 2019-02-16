<?php

class FormBuilder {

    private $data, $custom;

    public function __construct($custom = false) {
        if (isset($_POST)) {
            $this->data = $_POST;
        }

        $this->custom = $custom; 
    }

    public function render($type) {
        switch ($type) {
            case "uploadForm":
                return $this->makeUploadForm();
            case "registerForm":
                return $this->makeRegisterForm();
            case "loginForm":
                return $this->makeLoginForm();
            case "settingForm":
                return $this->makeSettingsForm();
            default:
                echo "A strange error occurred.";
                break;
        }
    }

    public function openFormTag($action, $enctype = '') {
        return "
            <form
                action='$action'
                method='POST'
                enctype='$enctype'
            >
        ";
    }

    public function closeFormTag() {
        return "
            </form>
        ";
    }


    public function fileInput($title) {
        $name = strtolower($title);

        return "
            <div class='form-group'>
                <label for='form-file'>$title</label>
                <input
                    class='form-control-file'
                    type='file'
                    id='form-file'
                    name='$name'
                    required
                >
            </div>
        ";
    }

    public function textInput($title, $name) {
        $value = $this->data[$name];
        $input = "
            <input
                class='form-control'
                type='text'
                name='$name'
                value='$value'
                placeholder='$title'
                required
            >
        ";
        if ($this->custom) {
            $html = $input;
        } else {
            $html = "<div class='form-group'>$input</div>";
        }

        return $html;
    }

    public function passwordInput($title, $name) {
        return "
        <div class='form-group'>
            <input
                class='form-control'
                type='password'
                name='$name'
                placeholder='$title'
                required
            >
        </div>
        ";
    }

    public function textareaInput($title, $name) {
        $value = $this->data[$name];
        $input = "
            <textarea
                class='form-control'
                type='text'
                name='$name'
                value='$value'
                placeholder='$title'
                rows='3'
                required
            ></textarea>
        ";
        if ($this->custom) {
            $html = $input;
        } else {
            $html = "<div class='form-group'>$input</div>";
        }

        return $html;
    }

    public function privacyInput() {
        return "
            <div class='form-group'>
                <select class='form-control' name='privacy'>
                    <option value='0'>Private</option>
                    <option value='1'>Public</option>
                </select>
            </div>
        ";
    }

    public function categoriesInput($db) {
        $query = $db->prepare("SELECT * FROM categories");    
        $query->execute();     
        $html = "";

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $name = $row["name"];
            $html .= "<option value='$id'>$name</option>";
        }
        
        return "
            <div class='form-group'>
                <select class='form-control' name='category'>
                    $html
                </select>
            </div>
        ";
    }

    public function submitButton($text, $name) {
        return "
        <button type='submit' class='btn btn-primary' name='$name'>
            $text
        </button>
        ";
    }
}
?>