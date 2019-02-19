<link rel="stylesheet" type="text/css" href="assets/css/FormBuilder.css">
<?php
require_once("includes/dataProcessors/Success.php");
require_once("includes/dataProcessors/Error.php");

class FormBuilder {

    private $data, $custom;

    public function __construct($data = null, $custom = false) {
        if (isset($_POST) && !empty($_POST) && !isset($_POST["passwordUpdate"])) {
            $this->data = $_POST;
        } else if ($data) {
            $this->data = $data;
            $this->data["emailConfirm"] = $data["email"];
        }
    
        $this->custom = $custom;
    }

    public function openFormTag($enctype = "application/x-www-form-urlencoded") {
        return "
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
        if ($type == "password") $value = null;

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
                placeholder='$title'
                rows='3'
                required
            >$value</textarea>
        ";
        if ($this->custom) {
            $html = $input;
        } else {
            $html = "<div class='form-group'>$input</div>";
        }

        return $html;
    }

    private function getError($inputName) {
        
    }

    public function privacyInput() {
        $value = $this->data["privacy"];
        $notPrivate = $value == "0" ? "selected" : "";
        $isPrivate = $value == "1" ? "selected" : "";

        return "
            <div class='form-group'>
                <select class='form-control' name='privacy'>
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
            echo $selected;
            $name = $row["name"];
            $html .= "<option value='$id' $selected>$name</option>";
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