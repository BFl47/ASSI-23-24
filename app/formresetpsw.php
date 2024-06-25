<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <style>
        .formresetpsw {
            margin: 0 auto;
            text-align: center;
            width: 50em;
        }
        .form-group {
            margin: 0 auto;
            width: 40em;
            margin-bottom: 1em;
        }
    </style>
    <script>
        function validaFormReset() {
            var password = document.getElementById('newpassword').value;
            var password2 = document.getElementById('newpassword2').value;

            if (password != password2) {
                alert('Le password non coincidono');
                return false;
            }            
        }
    </script>
</head>
<body>
    <?php include 'template.html'; ?>

    <div class="formresetpsw border">
        <br>
        <h1>Reset Password</h1>
        <form action="resetpsw.php" method="post" onsubmit="return validaFormReset();">

            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

            <div class="form-group">
                <label for="password">Nuova Password</label>
                <input type="password" class="form-control" id="newpassword" name="newpassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                title="La password deve contenere almeno un numero, una lettera minuscola, una lettera maiuscola, un carattere speciale e essere lunga almeno 8 caratteri." required>
            </div>
            <div class="form-group">
                <label for="password2">Conferma Password</label>
                <input type="password" class="form-control" id="newpassword2" name="newpassword2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" 
                title="La password deve contenere almeno un numero, una lettera minuscola, una lettera maiuscola, un carattere speciale e essere lunga almeno 8 caratteri." required>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
        <br>
    </div>
</body>
</html>