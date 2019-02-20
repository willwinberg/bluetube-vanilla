<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<?php

class FormBuilder {

    private $data;

    public function __construct($data = null) {
        if (isset($_POST) && !empty($_POST) && !isset($_POST["passwordUpdate"])) {
            $this->data = $_POST;
        } else if ($data) {
            $this->data = $data;
            $this->data["emailConfirm"] = $data["email"];
        }
    }

    public function openFormTag($header = "", $enctype = "application/x-www-form-urlencoded") {
        return "
            <h2 class='formHeader'>$header</h2>
            <form
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
            <div class='form-group row'>
                <label class='inputLabel col-sm-2 col-form-label'>$title</label>
                <input
                    class='form-control-file col-sm-10'
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
        if ($type == "password") $value = null;

        return "
            <div class='form-group row'>
                <label class='inputLabel col-sm-2 col-form-label'>$title</label>
                <input
                    class='form-control col-sm-10'
                    type='$type'
                    name='$name'
                    value='$value'
                    placeholder='$title'
                    required
                >
            </div>
        ";
        
    }

    public function entryTextInput($title, $name, $type = "text") {
        $value = $this->data[$name];
        if ($type == "password") $value = null;

        return "
            <input
                type='$type'
                name='$name'
                value='$value'
                placeholder='$title'
                required
            >
        ";
    }

    public function textareaInput($title, $name) {
        $value = $this->data[$name];

        return "
            <div class='form-group row'>
                <label class='inputLabel col-sm-2 col-form-label'>$title</label>
                <textarea
                    class='form-control col-sm-10'
                    type='text'
                    name='$name'
                    placeholder='$title'
                    rows='3'
                    required
                >$value</textarea>
            </div>
        ";
    }

    private function getError($inputName) {
        
    }

    public function privacyInput() {
        $value = $this->data["privacy"];
        $notPrivate = $value == "0" ? "selected" : "";
        $isPrivate = $value == "1" ? "selected" : "";

        return "
            <div class='form-group row'>
                <label class='inputLabel col-sm-2 col-form-label'>Privacy</label>
                <select class='form-control col-sm-10' name='privacy'>
                    <option value='0' $notPrivate>Public</option>
                    <option value='1' $isPrivate>Private</option>
                </select>
            </div>
        ";
    }

    public function categoriesInput($db) {
        $query = $db->prepare("SELECT * FROM categories");    
        $query->execute();     
        $html = "";
        $value = $this->data["category"];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $selected = $value == $id ? "selected" : "";
            $name = $row["name"];
            $html .= "<option value='$id' $selected>$name</option>";
        }
        
        return "
            <div class='form-group row'>
                <label class='inputLabel col-sm-2 col-form-label'>Category</label>
                <select class='form-control col-sm-10' name='category'>
                    $html
                </select>
            </div>
        ";
    }

    public function submitButton($text, $name) {
        return "
        <button type='SUBMIT' class='btn btn-primary' name='$name'>
            $text
        </button>
        ";
    }

    public function openEntryFormTag($title) {
        return "
            <div class='entryContainer'>
                <div class='column'>
                    <div class='entryHeader'>
                        <img src='assets/images/logo.png' title='logo' alt='BlueTube Logo'>
                        <h3>$title</h3>
                        <span>to continue to BlueTube</span>
                    </div>
                    <form method='POST'>
        ";
    }

    public function closeEntryFormTag($href, $message) {
        return "
                    </form>   
                    <a class='entryMessage' href='$href'>
                        $message
                    </a>
                </div>
            </div>
        ";
    }
}
?>