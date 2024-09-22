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
    else if(sessiontoken && !hasPageVariable){
        document.location.href="./?p=caja";
    }else if(sessiontoken && hasPageVariable && urlParams.get('p')!="caja"){
        document.location.href="./?p=caja";
    }
</script>