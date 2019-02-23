<?php

include '../src/ColorToVariableConverter.php';

use radfuse\ColorToVariableConverter;

$input = isset($_POST['source']) ? $_POST['source'] : '';
$preprocessor = isset($_POST['preprocessor']) ? $_POST['preprocessor'] : 'sass';
$prefix = isset($_POST['prefix']) ? $_POST['prefix'] : 'color';

$converter = new ColorToVariableConverter($input, $preprocessor, $prefix);
$result = $converter->getResult();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Color to Variable Converter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>
<body>
    <header>
        Color to Variable Converter
    </header>

    <section>
        <form method='post'>
            <div class="header text-center">
                <label>Original</label>
            </div>
            <textarea name='source' style='width: 100%' rows='15'><?= $input ?></textarea>

            <div class="text-center">
                <span>Prefix: </span><input type="text" name="prefix" value="<?= $prefix ?>"/>
                <select name="preprocessor">
                    <option value="sass" <?= $preprocessor == 'sass' ? 'selected' : '' ?>>SASS</option>
                    <option value="less" <?= $preprocessor == 'less' ? 'selected' : '' ?>>LESS</option>
                </select>
                <button>Convert</button>
            </div>
        </form>

        <hr>

        <div class="header text-center">
            <label>Converted</label>
        </div>
        <textarea style='width: 100%' rows='15' id="result"><?= $result ?></textarea>

        <?php if($input != ''): ?>
            <button onClick="copyToClipboard()">Copy to Clipboard</button>
            <span id="copied-alert">Copied!</span>
        <?php endif ?>
    </section>

    <script>
        function copyToClipboard() {
            var copyText = document.getElementById("result");
            var copiedAlert = document.getElementById("copied-alert");

            copyText.select();
            document.execCommand("copy");

            if (window.getSelection) {
                if (window.getSelection().empty) // Chrome
                    window.getSelection().empty();
                else if (window.getSelection().removeAllRanges) // Firefox
                    window.getSelection().removeAllRanges();
            }
            else if (document.selection) // IE
                document.selection.empty();

            copyText.blur();
            copiedAlert.classList.add("active");

            setTimeout(function(){ copiedAlert.classList.remove("active"); }, 2000);
        }
    </script>
</body>
</html>
