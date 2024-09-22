<?php
session_start();
?>

<?php include('./parts/header.php') ?>
<body>
    <?php include('./parts/content.php') ?>
</body>
<?php include('./parts/footer.php') ?>

<script>
    var sessiontoken=sessionStorage.getItem('token');
    var urlParams = new URLSearchParams(window.location.search);
    var hasPageVariable=urlParams.has('p');
    var isLoginPage=(urlParams.get('p')=="login");
    if(!sessiontoken && !isLoginPage && !hasPageVariable){
        document.location.href="./?p=login";
    }else if(!sessiontoken && !isLoginPage && hasPageVariable){
        document.location.href="./?p=login";
    }
    // else if(sessiontoken && !hasPageVariable){
    //     document.location.href="./?p=home";
    // }else if(sessiontoken && hasPageVariable && urlParams.get('p')!="home"){
    //     document.location.href="./?p=home";
    // }
</script>