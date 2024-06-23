<style>
.button-list {

    display: flex;
    list-style: none;
    padding: 0px;
    justify-content: center;

}

.dropdown-menu {

    border-radius: 0px;

}

.navbar-expand-lg .navbar-nav .dropdown-menu {
    width: 210px;
    max-height: 500px;
    overflow-y: auto;
    overflow-x: hidden;
}

@media (min-width: 992px) {}

.user-notify p {

    margin-bottom: 0px;
    text-align: center;
    font-size: 11px;
}

.button-list li a:hover {
    background-color: #fff;
    color: #8B53FF;
}

.button-list li a {
    color: white;
    background-color: #8B53FF;
    padding: 3px;
    font-size: 10px;
    margin: 2px;
    text-transform: capitalize;

}

.left-content {

    padding: 0px;
    display: flex;
    justify-content: end;
}

.right-content {

    padding: 0px;

}

.dropdown-menu {

    width: 200px;

}

.nav-link {
    cursor: pointer;
}

#user-image {
    border-radius: 40px;
}

.notification {
    background: #f5f4f1;
    margin-right: 0px;
    margin-left: 0px;
    padding-top: 5px;
    padding-bottom: 4px;
}

@media (max-width: 992px) {

    .nofitication-nav {
        display: block;
    }

    .navbar-collapse .navbar-nav {
        border-top: 0px;
        padding-top: 0px;
        margin-top: 0px;
        cursor: pointer;
    }


}

@media (max-width: 768px) {

    .navbar-expand-lg .navbar-nav .dropdown-menu {
        width: 140px;
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .left-content {

        justify-content: center;
    }
}
</style>



<ul class="navbar-nav nav-flex-icons">
    <li class="nav-item avatar dropdown">
        <a class="nav-link  waves-effect waves-light" id="navbarDropdownMenuLink-5" onclick="getNotifications()"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

            <span id="box-badge"></span>

            <span class="badge badge-danger ml-2">{{auth()->user()->unreadNotifications->count()}}</span>
            <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-5">
            <div class="row  justify-content-center" id="notifications">

                Notifications

            </div>

            <span id="box"></span>



        </div>
    </li>

</ul>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
</script>

<script>
function getNotifications() {

    $.ajax({
        type: 'get',
        url: '/user-notifications',

        success: function(data) {


            $("#append").remove();
            $(".badge").remove();
            $(".not-record-found").remove();

            var e = $('<span></span>');
            $('#box').append(e);
            e.attr('id', 'append');

            var e = $('<span></span>');
            $('#box-badge').append(e);
            e.attr('class', 'badge badge-danger ml-2');

            if (data.html == '') {

                $('.badge').append(data.unreadcount);
                $("#notifications").remove();
                $('#append').after(
                    '<div class="row not-record-found justify-content-center">Not Record Found</div>');

            } else {

                $('.badge').append(data.unreadcount);
                $("#append").append(data.html);

            }

        }
    });
}
</script>