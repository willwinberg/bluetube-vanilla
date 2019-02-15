<?php
class FormProvider {

    private $db, $type;

    public function __construct($db, $type) {
        $this->db = $db;
        $this->type = $type;
    }

    public function render() {
        switch ($this->type) {
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

    private function makeUploadForm() {
        $fileInput = $this->fileInput("File"); // file
        $titleInput = $this->textInput("Title"); // text
        $descriptionInput = $this->textareaInput("Description"); //textarea
        $privacyInput = $this->privacyInput(); //privacy
        $categoriesInput = $this->categoriesInput(); // categories
        $submitButton = $this->submitButton("Upload", "uploadButton"); // upload

        return "
            <form
                action='videoProcessing.php'
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
        ";
    }
    private function makeRegisterForm() {
        $fileInput = $this->fileInput("File"); // file
        $titleInput = $this->textInput("Title"); // text
        $descriptionInput = $this->textareaInput("Description"); //textarea
        $privacyInput = $this->privacyInput(); //privacy
        $categoriesInput = $this->categoriesInput(); // categories
        $submitButton = $this->submitButton("Upload", "uploadButton"); // upload

        return "
            <form
                action='videoProcessing.php'
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
        ";
    }

    private function fileInput($title) {
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

    private function textInput($title) {
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

    private function textareaInput($title) {
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

    private function privacyInput() {
        return "
            <div class='form-group'>
                <select class='form-control' name='privacy'>
                    <option value='0'>Private</option>
                    <option value='1'>private</option>
                </select>
            </div>
        ";
    }

    private function categoriesInput() {
        $query = $this->db->prepare("SELECT * FROM categories");    
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

    private function submitButton($text, $postTo) {
        return "
        <button type='submit' class='btn btn-primary' name='$postTo'>
            $text
        </button>
        ";
    }
}
?>