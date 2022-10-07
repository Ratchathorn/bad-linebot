{{-- resources/views/layouts/main.blade.php --}}
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CS442 200 Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
<h1>
    Hello World
</h1>
<section id="profile">
    <img id="pictureUrl" width="200">
    <p id="userId"></p>
    <p id="displayName"></p>

</section>
<section>
        <input type="button" onclick="logButton()"  id="logBtn" value = "login with line">
</section>
<section>
    <form action="{{ route("goldtype.pushmessage") }}" method="POST" id="pushMessageForm">
        @csrf
        <h1>----------</h1>
        <div>
            <label for='userIdForm'>user id: do not change</label>
            <input type='password' id='userIdForm' name='userIdForm' value=''>
            </div>
        <div>
            <label for='message'>send a message to yourself</label>
            <input type='text' id='message' name='message'>
            </div>
        <button type='submit'>send something</button>
    </form>
</section>
<section>
    <h1>----------</h1>
    <div>
        <table class="table table-sm" id="goldtypeTable">
        </table>

        <form action="{{ route("goldtype.pushgoldtype") }}" method="POST" id="goldtypeAdd">
            @csrf
            <h1>add info to table or something like that idk</h1>
            <div>
                <label for='nameForm'>name</label>
                <input type='text' id='nameForm' name='nameForm'>
            </div>
            <div>
                <label for='sizeForm'>size</label>
                <input type='text' id='sizeForm' name='sizeForm'>
            </div>
            <button type='submit'>add something</button>
        </form>
    </div>
</section>
<script type="text/javascript">

    var profile = document.getElementById('profile');
    const userId = document.getElementById('userId');
    const userIdForm = document.getElementById('userIdForm');
    const pictureUrl = document.getElementById('pictureUrl');
    const displayName = document.getElementById('displayName');
    const statusMessage = document.getElementById('statusMessage');
    const btnRedirectUrl = document.getElementById('btnRedirectUrl');
    const logBtn = document.getElementById('logBtn');
    const pushMessageForm = document.getElementById('pushMessageForm');
    const goldtypeTable = document.getElementById('goldtypeTable');
    const goldtypeAdd = document.getElementById('goldtypeAdd');

    liff.init({
        liffId: '1657529490-9GAbAnd0', // Use own liffId
    }).then(() => {
        async function setUserProfile() {
            if (!liff.isLoggedIn()) {
                return;
            }
            profile = await liff.getProfile()



            console.log(profile);
            pictureUrl.src = profile.pictureUrl;
            userId.innerHTML = 'userID:'+profile.userId;
            userIdForm.value = profile.userId;
            displayName.innerHTML = 'displayName:'+profile.displayName;
            logBtn.value = "logout";
            {{--pushMessageForm.innerHTML = "@csrf\n" +--}}
            {{--    "<h1>----------</h1>\n" +--}}
            {{--    "<div>\n" +--}}
            {{--    "<label for='userIdForm'>user id: do not change</label>\n" +--}}
            {{--    "<input type='password' id='userIdForm' name='userIdForm' value=''>\n" +--}}
            {{--    "</div>\n" +--}}
            {{--    "<div>\n" +--}}
            {{--    "<label for='message'>send a message to yourself</label>\n" +--}}
            {{--    "<input type='text' id='message' name='message'>\n" +--}}
            {{--    "</div>\n" +--}}
            {{--    "<button type='submit'>send something</button>\n";--}}
            goldtypeTable.innerHTML = "<thead>\n" +
                "<tr>\n" +
                "<th>ID</th>\n" +
                "<th>Name</th>\n" +
                "<th>Size</th>\n" +
                "</tr>\n" +
                "</thead>\n" +
                "<tbody>\n" +
                "@foreach( $goldtypes as $g)\n" +
                "<tr>\n" +
                "<td> {{ $g->id }} </td>\n" +
                "<td> {{ $g->name }} </td>\n" +
                "<td> {{ $g->size }} </td>\n" +
                "</tr>\n" +
                "@endforeach\n" +
                "</tbody>\n";
            {{--goldtypeAdd.innerHTML = "@csrf\n" +--}}
            {{--    "<h1>add info to table or something like that idk</h1>\n" +--}}
            {{--    "<div>\n" +--}}
            {{--    "<label for='nameForm'>name</label>\n" +--}}
            {{--    "<input type='text' id='nameForm' name='nameForm' value=''>\n" +--}}
            {{--    "</div>\n" +--}}
            {{--    "<div>\n" +--}}
            {{--    "<label for='sizeForm'>size</label>\n" +--}}
            {{--    "<input type='text' id='sizeForm' name='sizeForm'>\n" +--}}
            {{--    "</div>\n" +--}}
            {{--    "<button type='submit'>add something</button>\n";--}}

        }

        if (liff.isLoggedIn()) {

            console.log("logged in");
            setUserProfile();
        }
        // start to use LIFF's api
    })
        .catch((err) => {
            console.log(err);
        });

    function logButton() {
        if (!liff.isLoggedIn()) {
            liff.login();
            console.log("logged in");
        } else {
            liff.logout();
            console.log("logged out");
            pictureUrl.src = "";
            userId.innerHTML = "";
            userIdForm.value = "";
            displayName.innerHTML = "";
            logBtn.value = "login with line";
            // pushMessageForm.innerHTML = "";
            goldtypeTable.innerHTML = "";
            // goldtypeAdd.innerHTML = "";
        }

    }



</script>




</body>

</html>
