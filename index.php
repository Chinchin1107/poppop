<?php
    session_start();
    $mysql_db = mysqli_connect('localhost', 'u589000018_pop', 'Test_Var1107', 'u589000018_pop');

    if (($_GET['p'] != 's' and $_GET['p'] != 'l' and $_GET['p'] != 'q') and (!isset($_COOKIE['user']) or !isset($_COOKIE['pass']) or !mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM usr WHERE user=\'' . $_COOKIE['user'] . '\' AND pass=\'' . $_COOKIE['pass'] . '\';')))) {
        header('location: http://www.everypop.click/?p=s');
    }elseif (($_GET['p'] != 'p' and $_GET['p'] != 'q' and $_GET['p'] != 'add') and (isset($_COOKIE['user']) or isset($_COOKIE['pass']) or mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM usr WHERE user=\'' . $_COOKIE['user'] . '\' AND pass=\'' . $_COOKIE['pass'] . '\';')))) {
        header('location: http://www.everypop.click/?p=p');
    }
?>

<?php if ($_GET['p'] == 'add') : ?>
    <?php
        if (mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM _' . $_GET['pop'] . ' WHERE user=\'' . $_GET['user'] . '\';')) and $_GET['count'] <= 30) {
            mysqli_query($mysql_db, 'UPDATE _' . $_GET['pop'] . ' SET pop=pop + ' . $_GET['count'] . ' WHERE user=\'' . $_GET['user'] . '\';');
        }
    ?>
<?php endif; ?>

<?php if ($_GET['p'] != 'q' and $_GET['p'] != 'add') : ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./item/cursor.png">
    <link rel="stylesheet" href="./style/all.css">
<?php endif; ?>

<?php if ($_GET['p'] == 's' and $_GET['t'] == 's') : ?>
    <?php
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $alpha = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-.';
    
        function in_alpha($str) {
            global $alpha;
            for ($i = 0; $i < strlen($str); $i++) {
                $check = false;
                for ($j = 0; $j < strlen($alpha); $j++) {
                    if ($str[$i] == $alpha[$j]) {
                        $check = true;
                    }
                }
                if (!$check) {
                    return false;
                }
            }
            return true;
        }
    
        $error = array();
        if (empty($user)) {
            array_push($error, 'username is empty.');
        }elseif (!in_alpha($user)) {
            array_push($error, 'username must in ' . $alpha . '.');
        }elseif (strlen($user) > 15) {
            array_push($error, 'username must less than 15 characters');
        }elseif (mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM usr WHERE user=\'' . $user . '\';'))) {
            array_push($error, "username has already exists.");
        }
    
        if (empty($pass)) {
            array_push($error, 'password is empty.');
        }elseif (!in_alpha($pass)) {
            array_push($error, 'password must in ' . $alpha . '.');
        }elseif (strlen($pass) > 15) {
            array_push($error, 'password must less than 8 characters');
        }
    
        if (sizeof($error) == 0) {
            $pass = md5($pass);
            mysqli_query($mysql_db, 'INSERT INTO usr (user, pass) VALUES  (\'' . $user . '\', \'' . $pass . '\');');
            setcookie('user', $user, time() + 60 * 60 * 24 * 365 * 10);
            setcookie('pass', $pass, time() + 60 * 60 * 24 * 365 * 10);
            $_SESSION['cerror'] = false;
            header('location: http://www.everypop.click/');
        }else {
            $_SESSION['error'] = $error;
            $_SESSION['cerror'] = true;
            header('location: http://www.everypop.click/?p=s');
        }
    ?>
<?php elseif ($_GET['p'] == 's') : ?>
    <link rel="stylesheet" href="./style/ls.css">
    <title> Sign Up </title>
</head>
<body>
    <button class="link" onclick="window.location.href = 'http://www.everypop.click/?p=l'">
        login
    </button>
    <div class="box">
        <form action="/?p=s&t=s" method="POST">
            <h1> Sign Up </h1>
            <input type="text" name="user" class='user input' placeholder="username" minlength="1" maxlength="15">
            <input type="password" name="pass" class='pass input' placeholder="password" minlength="1" maxlength="8">
            <input type="submit" value="submit" class='submit'>
        </form>
    </div>
    <?php
        if (isset($_SESSION['error']) and $_SESSION['cerror']) {
            $le = '';
            foreach ($_SESSION['error'] as $err) {
                $le .= $err . '\n';
            }
            echo '<script> alert(\'' . $le . '\'); </script>';
            $_SESSION['cerror'] = false;
        }
    ?>
<?php endif ?>

<?php if ($_GET['p'] == 'l' and $_GET['t'] == 'l') : ?>
    <?php
        $user = $_POST['user'];
        $pass = md5($_POST['pass']);
        $alpha = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-.';
    
        function in_alpha($str) {
            global $alpha;
            for ($i = 0; $i < strlen($str); $i++) {
                $check = false;
                for ($j = 0; $j < strlen($alpha); $j++) {
                    if ($str[$i] == $alpha[$j]) {
                        $check = true;
                    }
                }
                if (!$check) {
                    return false;
                }
            }
            return true;
        }
    
        $error = array();
        if (empty($user)) {
            array_push($error, 'username is empty.');
        }elseif (!in_alpha($user)) {
            array_push($error, 'username must in ' . $alpha . '.');
        }elseif (strlen($user) > 15) {
            array_push($error, 'username must less than 15 characters');
        }elseif (!mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM usr WHERE user=\'' . $user . '\';'))) {
            array_push($error, "username does not have already exists.");
        }elseif (!mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM usr WHERE user=\'' . $user . '\' AND pass=\'' . $pass . '\';'))) {
            array_push($error, "your password is not incorrect.");
        }

        if (empty($pass)) {
            array_push($error, 'password is empty.');
        }elseif (!in_alpha($pass)) {
            array_push($error, 'password must in ' . $alpha . '.');
        }

        if (sizeof($error) == 0) {
            setcookie('user', $user, time() + 60 * 60 * 24 * 365 * 10);
            setcookie('pass', $pass, time() + 60 * 60 * 24 * 365 * 10);
            $_SESSION['cerror'] = false;
            header('location: http://www.everypop.click/');
        }else {
            $_SESSION['error'] = $error;
            $_SESSION['cerror'] = true;
            header('location: http://www.everypop.click/?p=l');
        }
    ?>
<?php elseif ($_GET['p'] == 'l') : ?>
    <link rel="stylesheet" href="./style/ls.css">
    <title> Login </title>
</head>
<body>
    <button class="link" onclick="window.location.href = 'http://www.everypop.click/?p=s'">
        sign up
    </button>
    <div class="box">
        <form action="/?p=l&t=l" method="POST">
            <h1> Login </h1>
            <input type="text" name="user" class='user input' placeholder="username" minlength="1" maxlength="15">
            <input type="password" name="pass" class='pass input' placeholder="password" minlength="1" maxlength="8">
            <input type="submit" value="submit" class='submit'>
        </form>
    </div>
    <?php
        if (isset($_SESSION['error']) and $_SESSION['cerror']) {
            $le = '';
            foreach ($_SESSION['error'] as $err) {
                $le .= $err . '\n';
            }
            echo '<script> alert(\'' . $le . '\'); </script>';
            $_SESSION['cerror'] = false;
        }
    ?>
<?php endif ?>

<?php if ($_GET['p'] == 'lo') : ?>
    <?php
        setcookie('user', $_COOKIE['user'], time());
        setcookie('pass', $_COOKIE['pass'], time());
        header('location: http://www.everypop.click/?p=s');
    ?>
<?php endif; ?>

<?php if ($_GET['p'] == 'p' and isset($_GET['t'])) : ?>
    <?php
        $qd = mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM pop WHERE id=\'' . $_GET['t'] . '\';'));
        if ($qd) {
            $id = $qd['id'];
            $name = $qd['name'];
            if (!mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM _' . $id . ' WHERE user=\'' . $_COOKIE['user'] . '\';'))) {
                mysqli_query($mysql_db, 'INSERT INTO _' . $id . ' (user, pop) VALUES (\'' . $_COOKIE['user'] . '\', 0);');
            }
        }else {
            header('location: http://www.everypop.com/?p=p');
        }
    ?>
    <style>
        html {
            background: url('pop/1/0.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    <link rel="stylesheet" href="./style/pp.css">
    <script>
        window.onload = () => { 
            var character = document.getElementById('character');
            var cpop = document.getElementById('cpop');
            var count = 0;
            character.src = './pop/<?php echo $id; ?>/-1.png';

            setInterval(() => {
                if (count <= 30 && count > 0) {
                    fetch('http://www.everypop.click/?p=add&pop=<?php echo $id; ?>&user=<?php echo $_COOKIE['user'];?>&count=' + count);
                }
                count = 0;
            }, 2000);

            const _in = () => {
                character.src = './pop/<?php echo $id; ?>/1.png';
                cpop.innerHTML = (parseInt(cpop.innerHTML.split(' ')[0]) + 1).toString() + ' pop';
                new Audio('./pop/<?php echo $id; ?>/2.mp3').play();
                count++;
            }

            const _out = () => character.src = './pop/<?php echo $id; ?>/-1.png';
            document.addEventListener('mousedown', _in);
            document.addEventListener('keydown', _in);
            document.addEventListener('touchstart', _in)
            document.addEventListener('mouseup', _out);
            document.addEventListener('keyup', _out);
            document.addEventListener('touchend', (ev) => {
                ev.preventDefault();
                _out();
            });

            var poppop = document.getElementById('poppop');
            var arrow = document.getElementById('arrow');
        }

        const renderScb = () => {
            fetch('http://www.everypop.click/?p=q&t=<?php echo $id; ?>').then(res => res.json()).then(data => {
                document.getElementById('poppop-data').innerHTML = '';
                var count = 0;
                for (var i in data) {
                    count += parseInt(data[i]);
                }
                var nd = document.createElement('div');
                nd.className = 'poppop-data-item';
                let p1 = document.createElement('p');
                p1.innerHTML = 'All';
                let p2 = document.createElement('p');
                p2.innerHTML = count.toString();
                nd.appendChild(p1);
                nd.appendChild(p2);
                document.getElementById('poppop-data').appendChild(nd);
                for (var i in data) {
                    if (i != 0) {
                        var nd = document.createElement('div');
                        nd.className = 'poppop-data-item';
                        if (i === '<?php echo $_COOKIE['user']; ?>') {
                            nd.style = 'background-color: rgba(100, 255, 100, .8);';
                        }
                        let p1 = document.createElement('p');
                        p1.innerHTML = i;
                        let p2 = document.createElement('p');
                        p2.innerHTML = data[i];
                        nd.appendChild(p1);
                        nd.appendChild(p2);
                        document.getElementById('poppop-data').appendChild(nd);
                    }
                }
            }).catch(error => console.log(error));
        }

        var stscb = false, itv;
        const scoreboard = () => {
            if (!stscb) {
                poppop.style = 'top: 20%;bottom: 0%;left: 0%;right: 0%;border-radius: 1rem 1rem 0rem 0rem;';
                setTimeout(() => {
                    document.getElementById('poppop-data').style = 'display: flex;opacity: 1;';
                }, 500);
                arrow.style = 'transform: rotate(180deg);';
                renderScb();
                itv = setInterval(() => renderScb(), 2000);
                document.getElementById('poppop-frame').style = 'height: 80%';
                stscb = true;
            }else {
                document.getElementById('poppop-data').style = '';
                document.getElementById('poppop-frame').style = '';
                poppop.style = '';
                arrow.style = '';
                clearInterval(itv);
                stscb = false;
            }
        }
    </script>
    <title> pop<?php echo $name ?> </title>
</head>
<body>
    <div class=box>
        <div class="box-popname" id="test"> pop<?php echo $name; ?> </div>
        <div class="box-cpop" id="cpop"><?php echo mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM _' . $id . ' WHERE user=\'' . $_COOKIE['user'] . '\';'))['pop']; ?> pop</div>
        <img class="box-character" id="character" src="./pop/<?php echo $id; ?>/1.png">
    </div>
    <div id="poppop" class="poppop" onclick="scoreboard();" ontouchend="scoreboard();">
        <div class="poppop-top">
            <button class="poppop-back" onclick="window.location.href = 'http://www.everypop.click/?p=p';" ontouchend="window.location.href = 'http://www.everypop.click/?p=p';"> back </button>
            <img id="arrow" src="./item/arrow.png">
            <button class="poppop-user"> <?php echo $_COOKIE['user'] ?> </button>
        </div>
        <div class="poppop-frame" id="poppop-frame">
            <div class="poppop-data" id="poppop-data">

            </div>
        </div>
    </div>
<?php elseif ($_GET['p'] == 'p') : ?>
    <link rel="stylesheet" href="./style/pl.css">
    <title> pop list </title>
</head>
<body>
    <button class="link" onclick="window.location.href = 'http://www.everypop.click/?p=lo'"> logout </button>
    <div class='user'> <?php echo $_COOKIE['user']; ?> </div>
    <center class='head'> Pop List </center>
    <div class='pop-box'>
        <?php
            $pop = mysqli_query($mysql_db, 'SELECT * FROM pop');
            while ($row = mysqli_fetch_array($pop)) { ?>
            <div class="pop-item-box"  onmouseout="document.getElementById('pop-item-box-img-<?php echo $row['id'] ?>').src = './pop/<?php echo $row['id'] ?>/-1.png';" onmouseover="document.getElementById('pop-item-box-img-<?php echo $row['id']; ?>').src = './pop/<?php echo $row['id'] ?>/1.png';" onclick="window.location.href = 'http://www.everypop.click/?p=p&t=<?php echo $row['id'] ?>';">
                <img class='img' id='pop-item-box-img-<?php echo $row['id'] ?>'" src="./pop/<?php echo $row['id'] ?>/-1.png"/>
                <center> pop<?php echo $row['name']; ?> </center>
            </div>
        <?php
            }
        ?>
    </div>
<?php endif; ?>

<?php if ($_GET['p'] != 'q' and $_GET['p'] != 'add') : ?>
</body>
</html>
<?php endif; ?>

<?php if ($_GET['p'] == 'q' and isset($_GET['t'])) : ?>
    <?php
        if (mysqli_fetch_assoc(mysqli_query($mysql_db, 'SELECT * FROM pop WHERE id=\'' . $_GET['t'] . '\';'))) {
            $data = array();
            $qr_ = mysqli_query($mysql_db, 'SELECT * FROM _'. $_GET['t'] . ';');
            while ($row = mysqli_fetch_array($qr_)) {
                $data[$row['user']] = $row['pop'];
            }
            echo json_encode($data);
        }else {
            header('location : http://www.everypop.click');
        }
    ?>
<?php endif; ?>
