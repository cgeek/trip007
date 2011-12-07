<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>后台管理</title>
    <link href="/static/css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
    <div class="wrap">
        <div class="login clearfix">
            <div class="login_pic">
                <img src="/static/images/welcome.jpg" style="padding:10px;width:500px;">
            </div>
            <div class="login_form">
                <form action="/login" method="POST">
                    <div class="login_box">
                        <p>
                            <input class="input_text" type="text" name="username" value="" /></p>
                        <p>
                            <input class="input_text" type="PASSWORD" name="password" value="" /></p>
                        <p>
                            <input class="button" type="submit" name="submit" value="登录" />
                            <label class="keep_login"> <input type="CHECKBOX" name="keep"/>保持登录  </label>
                        </p>
                    </div>
                </form>
            </div>


        </div>
    </div>
</body>
</html>
    
