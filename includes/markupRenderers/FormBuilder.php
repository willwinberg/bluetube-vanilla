<?php
class FormBuilder {

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

    public function openFormTag($action, $enctype) {
        if (!$enctype) $enctype = '';

        return "
            <div class='column'>
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
            </div>
        ";
    }

    public function makeRegisterForm() {
        $fileInput = $this->fileInput("File"); // file
        $titleInput = $this->textInput("Title"); // text
        $descriptionInput = $this->textareaInput("Description"); //textarea
        $privacyInput = $this->privacyInput(); //privacy
        $categoriesInput = $this->categoriesInput(); // categories
        $submitButton = $this->submitButton("Upload", "uploadButton"); // upload

        return "
        <div class='column'>
            <form
                action='processing.php'
                method='POST'
                enctype='multipart/form-data'
            >
                $fileInput
                $titleInput
                $descriptionInput
                $privacyInput
                $categoriesInput
                $submitButton
            </form>
        </div>
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

    public function textInput($title) {
        $name = strtolower($title);

        return "
            <div class='form-group'>
                <input
                    class='form-control'
                    type='text'
                    name='$name'
                    placeholder='$title'
                    required
                >
            </div>
        ";
    }

    public function emailInput($title) {
        $name = strtolower($title);

        return "
            <div class='form-group'>
                <input
                    class='form-control'
                    type='text'
                    name='$name'
                    placeholder='$title'
                    required
                >
            </div>
        ";
    }

    public function textareaInput($title) {
        $name = strtolower($title);

        return "
            <div class='form-group'>
                <textarea
                    class='form-control'
                    name='$name'
                    placeholder='$title'
                    rows='3'
                ></textarea>
            </div>
        ";
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