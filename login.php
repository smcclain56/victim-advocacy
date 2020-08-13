
    <!DOCTYPE html>
    <html>

    <head>
        
        <style>
            /*html {
                height: 100%;
            }*/
            body {
                height: 100%;
                /*(background: linear-gradient(180deg, #800000 50%, #333 50%);*/
            }

            .head {
                font-weight: 400;
                margin: 0;
                text-align: center;
                font-size: 1.3em;
                font-weight: bold;
            }

            .Test {
                margin-top: 80px;

            }
            .login-table {

                background: #f9f9f9;
                border-spacing: initial;
                margin: 15px auto;
                /*word-break: break-word;*/
                color: #333;
                border-radius:  8px;
                padding: 20px;
                width: 380px;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);;
                border: 2px solid;
                border-style: double;
                border-color: #800000;
            }
            /* To do make shadow box one hover over log in page

            */

            .login-table .field-column {
                padding: 4px 8px;
            }

            .Input {
                padding: 15px;
                border-bottom: 1px solid #800000;
                border-radius: 4px;
                margin: 3px auto;
                width: 90%;

            }

            .Button1 {
                background-color: #333;
                color: white;
                cursor: pointer;
                border-radius: 3px;
                border: #f5e9d4 1px solid;
                line-height: 40px;
                font-size: 1em;
                margin: 10px auto;
                width: 100%
            }

            input[type="submit"].Button1:hover {background-color: #800000;}

            input[type="submit"].Button1:active {background-color: #7CBF70;}




    </style>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">-->
    <link rel="stylesheet" href="style.css">
    <title>Login</title>

</head>
<!--<p><a href="http://129.114.104.163/~dbteam/showPassengers.php">Passenger list TEST</a></p>
-->
<body>
    <div class="Test">
        <form action="authenticate-user.php" method="post" name="Login_Form">
            <div class="login-table">
                <div class="head">
                    <h1>Victim Advocacy Login</h1>
                </div>
                <!-- Log in form -->
                <br>
                <?php if(isset($msg)){?>
                    <br>
                    <td colspan="2" align="center" valign="top"><?php echo $msg;?></td>
                <?php } ?>
                <div class="field-column">
                    <div>
                        <label>Username:</label>
                    </div>
                    <div>
                        <input name="Username" type="text" placeholder="Enter Username" required>
                    </div>
                </div>
                <div class="field-column">
                    <div>
                        <label>Password:</label>
                    </div>
                    <div>
                        <input name="Password" type="password" placeholder="Enter Password" required>
                    </div>
                </div>
                <div class="field-column">
                    <div>
                        <input class="Button1" type="submit" value="Submit">
                    </div>
                </div>
            </div>
        </form>
    </div>

</body>

</html>
