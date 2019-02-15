<?php
class FormProvider {
    public function __construct($db) {
        $this->db = $db;
    }

    public function makeVideoUploadForm() {
        $fileInput = $this->fileInput("File"); // file
        $titleInput = $this->textInput("Title"); // text
        $descriptionInput = $this->textareaInput("Description"); //textarea
        $privacyInput = $this->privacyInput(); //privacy
        $categoriesInput = $this->categoriesInput(); // categories
        $submitButton = $this->submitButton("Upload"); // upload

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
                $uploadButton
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
        return ("
            <div class='form-group'>
                <select class='form-control' name='privacyInput'>
                    <option value='0'>Private</option>
                    <option value='1'>Public</option>
                </select>
            </div>
        ");
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
                <select class='form-control' name='categoryInput'>
                    $html
                </select>
            </div>
        ";
    }

    private function submitButton($text) {
        return "
        <button type='submit' class='btn btn-primary' name='uploadButton'>
            $text
        </button>
        ";
    }
}
?>