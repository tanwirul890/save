<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>save</title>


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html,body{
    width:100%;
    height:100%;
    background:#FFF5E4;
    font-family:'Poppins',sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
    color:white;
}

.intro{
    display:flex;
    align-items:center;
    justify-content:center;
}

/* LOGO S */
.logo-img{
    width:120px;
    display:block; /* HILANGIN GAP */
    opacity:0;
    animation:
        zoomIn 1.5s ease forwards,
        moveLeft .8s ease forwards 1.6s;
}

/* TEXT AVE */
.logo-text{
    font-size:3rem;
    font-weight:700;
    color:#EE6983;
    margin-top:50px;
    margin-left:-50px;    
    letter-spacing:0px;     
    line-height:1;
    opacity:0;
    animation:textAppear .9s ease forwards;
    animation-delay:2.4s;
}




/* ===== ANIMATIONS ===== */
@keyframes zoomIn{
    from{
        transform:scale(5);
        opacity:0;
    }
    to{
        transform:scale(1);
        opacity:1;
    }
}

@keyframes moveLeft{
    from{
        transform:translateX(0);
    }
    to{
        transform:translateX(-12px); /* dikit aja */
    }
}

@keyframes textAppear{
    from{
        transform:translateX(-10px);
        opacity:0;
    }
    to{
        transform:translateX(0);
        opacity:1;
    }
}

@keyframes fadeOut{
    to{
        opacity:0;
        transform:scale(1.05);
    }
}

.fade-out{
    animation: fadeOut 1s ease forwards;
}

</style>
</head>

<body>

<div class="intro" id="intro">
    <img src="./img/logo.png" alt="Zona Film" class="logo-img">
    <div class="logo-text">AVE</div>
</div>

<audio id="introSound" src="./video/intro.mp3"></audio>

<script>
document.addEventListener("DOMContentLoaded", function(){

    const audio = document.getElementById("introSound");

    // Play audio (fallback click)
    audio.volume = 0.9;
    audio.play().catch(()=>{
        document.body.addEventListener("click", ()=>audio.play(), {once:true});
    });

    // Fade out & redirect
    setTimeout(()=>{
        document.body.classList.add("fade-out");
        setTimeout(()=>{
            window.location.href = "login.php";
        },1000);
    },5500);

});
</script>

</body>
</html>
