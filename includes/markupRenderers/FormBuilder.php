<?php

class FormBuilder {

    private $data, $custom;

    public function __construct($data, $custom = false) {
        if (isset($_POST) && !empty($_POST)) {
            $this->data = $_POST;
        } else if ($data) {
            $this->data = $data;
            $this->data["emailConfirm"] = $data["email"];
        }
        $this->custom = $custom; 
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

    public function textInput($title, $name, $type = "text") {
        $value = $this->data[$name];
        if ($type == "password") $value = "";

        $input = "
            <input
                class='form-control'
                type='$type'
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