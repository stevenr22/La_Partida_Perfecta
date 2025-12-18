<?php
session_start();

if (!isset($_SESSION["id_partida"], $_SESSION["id_quiz"])) {
  header("Location: ../index.php");
  exit;
}

$idPartida = (int)$_SESSION["id_partida"];
$idQuiz    = (int)$_SESSION["id_quiz"];

$idRolJugador = (int)($_SESSION["id_rol_jugador"] ?? 0);
$origenJuego  = $_SESSION["origen_juego"] ?? "pin";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>La Partida Perfecta | Juego</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
  <style>
    :root{ --card-radius:18px; --shadow:0 12px 30px rgba(0,0,0,.08); }
    body{
      background: radial-gradient(1200px 700px at 20% 10%, rgba(13,110,253,.12), transparent 60%),
                  radial-gradient(900px 500px at 80% 30%, rgba(25,135,84,.10), transparent 60%),
                  #f7f7fb;
      overflow-x:hidden;
    }
    .game-wrap{ max-width:820px; margin:20px auto; padding:10px; }
    .topbar{ display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:12px; }
    .brand{ display:flex; align-items:center; gap:10px; }
    .brand .badge{ font-size:.95rem; border-radius:999px; padding:.45rem .8rem; }
    .timer-box{
      width:320px; max-width:55vw; background:#fff; border-radius:999px;
      box-shadow:var(--shadow); padding:8px 10px; display:flex; align-items:center; gap:10px;
    }
    .timer-box .time{ font-weight:800; width:62px; text-align:center; font-variant-numeric:tabular-nums; }
    .timer-progress{ height:10px; border-radius:999px; overflow:hidden; background:rgba(0,0,0,.08); flex:1; position:relative; }
    .timer-bar{
      position:absolute; inset:0; width:100%;
      background: linear-gradient(90deg, rgba(25,135,84,.9), rgba(13,110,253,.9));
      transform-origin:left center; transform:scaleX(1); transition:transform .1s linear;
    }
    .timer-box.danger .timer-bar{ background: linear-gradient(90deg, rgba(220,53,69,.95), rgba(255,193,7,.95)); }
    .card-game{ border:0; border-radius:var(--card-radius); box-shadow:var(--shadow); overflow:hidden; }
    .card-header-game{
      background:#fff; border-bottom:1px solid rgba(0,0,0,.06);
      padding:14px 16px; display:flex; align-items:center; justify-content:space-between; gap:10px;
    }
    .question{ font-size:1.25rem; font-weight:800; line-height:1.25; margin:0; }
    .subtitle{ margin:0; color:#6c757d; font-size:.95rem; }
    .card-body{ padding:16px; background: rgba(255,255,255,.82); backdrop-filter: blur(8px); }
    .options{ display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:12px; }
    @media (max-width:576px){
      .options{ grid-template-columns:1fr; }
      .timer-box{ width:100%; }
      .topbar{ flex-direction:column; align-items:stretch; }
    }
    .opt-btn{
      border-radius:16px; padding:16px 14px; font-size:1.05rem; font-weight:700;
      border:2px solid rgba(13,110,253,.22); background:#fff;
      transition: transform .08s ease, box-shadow .18s ease, background .18s ease;
      box-shadow:0 8px 16px rgba(13,110,253,.08);
      text-align:left; display:flex; align-items:flex-start; gap:12px; min-height:72px;
    }
    .opt-btn:hover{ transform:translateY(-2px); box-shadow:0 12px 22px rgba(13,110,253,.12); background: rgba(13,110,253,.05); }
    .opt-tag{
      width:34px; height:34px; border-radius:12px; display:grid; place-items:center;
      background: rgba(13,110,253,.10); font-weight:900; flex:0 0 auto;
    }
    .message{
      border-radius:16px; font-weight:800; display:flex; align-items:center; justify-content:center;
      gap:10px; padding:12px 14px; box-shadow:var(--shadow);
    }
    .glow-win{ box-shadow:0 0 0 3px rgba(25,135,84,.22), 0 16px 40px rgba(25,135,84,.18) !important; }
    .glow-lose{ box-shadow:0 0 0 3px rgba(220,53,69,.22), 0 16px 40px rgba(220,53,69,.18) !important; }
    .shake{ animation:shake .35s ease-in-out; }
    @keyframes shake{ 0%{transform:translateX(0)} 25%{transform:translateX(-8px)} 50%{transform:translateX(8px)} 75%{transform:translateX(-6px)} 100%{transform:translateX(0)} }
    #fxCanvas{ position:fixed; inset:0; pointer-events:none; z-index:9999; }
    .end-screen{ text-align:center; padding:28px 10px; }
    .end-screen .big{ font-size:2rem; font-weight:900; margin-bottom:6px; }
    .picker{ display:grid; gap:12px; }
    .picker .box{ background:#fff; border-radius:18px; box-shadow:var(--shadow); padding:16px; }
  </style>
</head>

<body>
<canvas id="fxCanvas"></canvas>

<div class="game-wrap">

  <div class="topbar">
    <div class="brand">
      <span class="badge text-bg-primary">🎮 Partida en curso</span>
      <span class="text-secondary small">Responde antes que se acabe el tiempo</span>
    </div>

    <div class="timer-box" id="timerBox">
      <div class="time" id="timeText">--s</div>
      <div class="timer-progress"><div class="timer-bar" id="timerBar"></div></div>
    </div>
  </div>

  <div class="card card-game" id="cardGame">
    <div class="card-header-game">
      <div>
        <p class="subtitle" id="subTitulo"><?= ($origenJuego === "pin" ? "Preparación" : "Pregunta") ?></p>
        <h3 class="question" id="preguntaTexto"><?= ($origenJuego === "pin" ? "Selecciona la dificultad" : "Cargando pregunta...") ?></h3>
      </div>
      <div class="text-end">
        <span class="badge text-bg-light border" id="estadoBadge">Listo</span>
      </div>
    </div>

    <div class="card-body">

      <?php if ($origenJuego === "pin"): ?>
      <!-- Selector SOLO si viene por PIN -->
      <div id="panelDificultad" class="picker">
        <div class="box">
          <div class="fw-bold mb-1">Dificultad según tu nivel</div>
          <div class="text-secondary small mb-3">
            Tu rol actual: <span class="fw-bold"><?= $idRolJugador > 0 ? $idRolJugador : "No detectado" ?></span>
          </div>

          <label class="fw-bold">Selecciona dificultad</label>
          <select id="id_dificultad" class="form-select form-select-lg mt-2">
            <option value="">Cargando...</option>
          </select>

          <div class="d-grid gap-2 mt-3">
            <button id="btnIniciar" class="btn btn-primary btn-lg" type="button" disabled>
              Iniciar juego
            </button>
            <a class="btn btn-outline-secondary" href="../index.php">Salir</a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="options <?= ($origenJuego === "pin" ? "d-none" : "") ?>" id="opcionesBox"></div>
      <div id="mensaje" class="alert d-none mt-3 message text-center"></div>

    </div>
  </div>
</div>

<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script>
const ORIGEN = <?= json_encode($origenJuego) ?>;
const ID_ROL_JUGADOR = <?= (int)$idRolJugador ?>;

let bloqueado=false, timerId=null, duracion=25, timeLeft=25, questionToken=0;
const $timerBox=$("#timerBox"), $timerBar=$("#timerBar"), $timeText=$("#timeText"), $card=$("#cardGame");

// sonidos
let audioCtx=null;
function beep(freq=440, ms=120, type="sine", gain=0.05){
  try{
    if(!audioCtx) audioCtx=new (window.AudioContext||window.webkitAudioContext)();
    const o=audioCtx.createOscillator();
    const g=audioCtx.createGain();
    o.type=type; o.frequency.value=freq; g.gain.value=gain;
    o.connect(g); g.connect(audioCtx.destination); o.start();
    setTimeout(()=>o.stop(), ms);
  }catch(e){}
}
function sCorrecto(){ beep(784,90,"triangle",0.06); setTimeout(()=>beep(988,120,"triangle",0.06),90); }
function sIncorrecto(){ beep(220,140,"sawtooth",0.05); setTimeout(()=>beep(165,180,"sawtooth",0.05),120); }
function sTickFinal(){ beep(900,40,"square",0.03); }

// FX canvas
const fx=document.getElementById("fxCanvas");
const ctx=fx.getContext("2d");
let W=0,H=0, particles=[], animating=false;
function resizeFx(){ W=fx.width=window.innerWidth; H=fx.height=window.innerHeight; }
window.addEventListener("resize", resizeFx); resizeFx();

function launchConfetti(){
  particles=[]; for(let i=0;i<160;i++){
    particles.push({x:Math.random()*W,y:-20-Math.random()*H*0.3,vx:(Math.random()-0.5)*4,vy:2+Math.random()*6,r:4+Math.random()*6,rot:Math.random()*Math.PI,vr:(Math.random()-0.5)*0.2,life:60+Math.random()*60,t:"conf"});
  }
  animateFx();
}
function launchSad(){
  particles=[]; for(let i=0;i<80;i++){
    particles.push({x:Math.random()*W,y:-20-Math.random()*H*0.2,vx:(Math.random()-0.5)*1.5,vy:3+Math.random()*4,r:3+Math.random()*4,life:70+Math.random()*60,t:"sad"});
  }
  animateFx();
}
function animateFx(){
  if(animating) return; animating=true;
  (function loop(){
    ctx.clearRect(0,0,W,H);
    particles.forEach(p=>{
      p.x+=p.vx; p.y+=p.vy; p.life--;
      if(p.t==="conf"){
        p.rot+=p.vr;
        ctx.save(); ctx.translate(p.x,p.y); ctx.rotate(p.rot);
        ctx.fillStyle=`hsl(${Math.random()*360},90%,55%)`;
        ctx.fillRect(-p.r,-p.r*0.3,p.r*2,p.r*0.6); ctx.restore();
      }else{
        ctx.beginPath(); ctx.fillStyle="rgba(0,0,0,.18)";
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2); ctx.fill();
      }
    });
    particles=particles.filter(p=>p.life>0 && p.y<H+40);
    if(particles.length>0) requestAnimationFrame(loop);
    else { ctx.clearRect(0,0,W,H); animating=false; }
  })();
}

// timer
function stopTimer(){ if(timerId) clearInterval(timerId); timerId=null; }
function updateTimerUI(){
  $timeText.text(`${timeLeft}s`);
  const ratio=Math.max(0, Math.min(1, timeLeft/duracion));
  $timerBar.css("transform", `scaleX(${ratio})`);
  if(timeLeft<=6) $timerBox.addClass("danger"); else $timerBox.removeClass("danger");
}
function startTimer(seconds, token){
  stopTimer(); duracion=seconds; timeLeft=seconds; updateTimerUI();
  timerId=setInterval(()=>{
    if(token!==questionToken){ stopTimer(); return; }
    timeLeft--; updateTimerUI();
    if(timeLeft<=5 && timeLeft>0) sTickFinal();
    if(timeLeft<=0){ stopTimer(); responder("__TIMEOUT__"); }
  }, 1000);
}

// UI
function setEstado(t, tipo="light"){ $("#estadoBadge").attr("class", `badge text-bg-${tipo}`).text(t); }
function limpiarMensaje(){
  $("#mensaje").addClass("d-none").removeClass("alert-success alert-danger alert-warning").text("");
  $card.removeClass("glow-win glow-lose shake");
}
function bloquearBotones(flag){ $("#opcionesBox button, #respuestaCompletar, #btnEnviarCompletar").prop("disabled", flag); }
function escapeHtml(str){
  return (str||"").toString().replaceAll("&","&amp;").replaceAll("<","&lt;").replaceAll(">","&gt;").replaceAll('"',"&quot;").replaceAll("'","&#039;");
}

// cargar pregunta
function cargarPregunta(){
  limpiarMensaje();
  setEstado("Cargando...", "light");
  $("#preguntaTexto").text("Cargando pregunta...");
  $("#opcionesBox").html(`<div class="text-center text-secondary">Preparando...</div>`);

  bloqueado=true; bloquearBotones(true);
  questionToken++; const token=questionToken;

  $.getJSON("../controllers/obtenerPreguntaController.php", function(res){
    if(token!==questionToken) return;

    if(!res || !res.ok){
      stopTimer();
      setEstado("Finalizado", "secondary");

      $.post("../controllers/finalizarPartidaController.php", {}, function(fin){
        const correctas = fin?.correctas ?? 0;
        const total = fin?.total ?? 0;
        const aprobado = !!fin?.aprobado;

        const titulo = aprobado ? "🎉 ¡Aprobaste!" : "😥 No aprobaste";
        const msg = aprobado
          ? `Lograste ${correctas}/${total}. ¡Excelente!`
          : `Lograste ${correctas}/${total}. Inténtalo otra vez.`;

        $("#preguntaTexto").text(titulo);
        $("#opcionesBox").html(`
          <div class="end-screen">
            <div class="big">${titulo}</div>
            <div class="text-secondary mb-2">${msg}</div>
            <div class="badge ${aprobado ? "text-bg-success" : "text-bg-danger"} p-2 mb-3">
              Puntaje: ${correctas}/${total} — ${aprobado ? "APROBADO" : "REPROBADO"}
            </div>

            <div class="d-grid gap-2 mt-3">
              ${ORIGEN === "dashboard"
                ? `<a href="../views/dashboard.php" class="btn btn-success btn-lg">Volver al dashboard</a>`
                : `<a href="../views/completarCuenta.php" class="btn btn-primary btn-lg">Completar mi cuenta para ver certificados</a>
                   <a href="../index.php" class="btn btn-outline-secondary">Volver al inicio</a>`
              }
            </div>
          </div>
        `);

        if(aprobado){ launchConfetti(); sCorrecto(); }
        else { launchSad(); sIncorrecto(); }
      }, "json");

      return;
    }

    const t=parseInt(res.tiempo || 25);
    const tiempo=(isNaN(t) || t<=5) ? 25 : t;

    $("#subTitulo").text("Pregunta");
    $("#preguntaTexto").text(res.pregunta);
    setEstado("Responde ahora", "primary");

    if(res.tipo === "completar"){
      $("#opcionesBox").html(`
        <div class="w-100">
          <label class="fw-bold mb-2">Escribe tu respuesta:</label>
          <input type="text" id="respuestaCompletar" class="form-control form-control-lg"
                 placeholder="Escribe aquí..." autocomplete="off">
          <button type="button" id="btnEnviarCompletar" class="btn btn-primary btn-lg w-100 mt-3"
                  onclick="enviarCompletar()">
            Enviar respuesta
          </button>
        </div>
      `);

      bloqueado=false; bloquearBotones(false);
      startTimer(tiempo, token);
      setTimeout(()=>$("#respuestaCompletar").focus(), 100);
      return;
    }

    let html="";
    const letras=["A","B","C","D","V","F"];
    (res.opciones || []).forEach((op,i)=>{
      const tag=(op.letra || letras[i] || "?").toString();
      const txt=(op.texto || "").toString();
      html += `
        <button type="button" class="opt-btn" onclick="responder('${tag.replace(/'/g,"\\'")}')">
          <span class="opt-tag">${tag}</span>
          <span>${escapeHtml(txt)}</span>
        </button>
      `;
    });

    $("#opcionesBox").html(html);
    bloqueado=false; bloquearBotones(false);
    startTimer(tiempo, token);
  })
  .fail(function(xhr){
    stopTimer();
    setEstado("Error", "danger");
    $("#preguntaTexto").text("Error al cargar la pregunta ❌");
    $("#opcionesBox").html(`<div class="text-center text-danger">Revisa el controller / sesión</div>`);
    console.log(xhr.responseText);
  });
}

function enviarCompletar(){
  if(bloqueado) return;
  const txt = ($("#respuestaCompletar").val() || "").trim();
  if(!txt){
    $("#mensaje").removeClass("d-none alert-success alert-danger").addClass("alert-warning").text("Escribe una respuesta ✍️");
    return;
  }
  responder(txt);
}

function responder(valor){
  if(bloqueado) return;
  bloqueado=true;
  bloquearBotones(true);
  setEstado("Enviando...", "warning");
  stopTimer();

  $.post("../controllers/responderPreguntaController.php",
    { respuesta: valor },
    function(res){
      const ok = !!(res && res.correcta);

      if(valor === "__TIMEOUT__"){
        $("#mensaje").removeClass("d-none alert-success").addClass("alert-warning").text("⏰ Tiempo agotado");
        $card.addClass("glow-lose shake");
        sIncorrecto(); launchSad();
        setEstado("Sin tiempo", "secondary");
      }else if(ok){
        $("#mensaje").removeClass("d-none alert-danger alert-warning").addClass("alert-success").text("✔ ¡Correcto!");
        $card.addClass("glow-win");
        sCorrecto(); launchConfetti();
        setEstado("¡Bien!", "success");
      }else{
        $("#mensaje").removeClass("d-none alert-success alert-warning").addClass("alert-danger").text("✖ Incorrecto");
        $card.addClass("glow-lose shake");
        sIncorrecto(); launchSad();
        setEstado("Ups...", "danger");
      }

      setTimeout(cargarPregunta, 1200);
    },
    "json"
  ).fail(function(xhr){
    setEstado("Error", "danger");
    $("#mensaje").removeClass("d-none alert-success").addClass("alert-danger").text("Error en el servidor (AJAX) ❌");
    console.log(xhr.responseText);
    setTimeout(cargarPregunta, 1500);
  });
}

// selector por PIN
function cargarDificultades(){
  if(!ID_ROL_JUGADOR){
    $("#id_dificultad").html('<option value="">No se detectó el rol. Reingresa al juego.</option>');
    $("#btnIniciar").prop("disabled", true);
    return;
  }

  $.getJSON("../controllers/obtenerDificultadesRolController.php", function(data){
    const $sel = $("#id_dificultad");
    $sel.html('<option value="">-- Seleccionar --</option>');

    if(!data || data.length === 0){
      $sel.html('<option value="">No hay dificultades para tu rol</option>');
      $("#btnIniciar").prop("disabled", true);
      return;
    }

    data.forEach(d => {
      $sel.append(`<option value="${d.id_dificultad}">${d.nombre_dificultad}</option>`);
    });

    $("#btnIniciar").prop("disabled", true);
  }).fail(function(xhr){
    console.log(xhr.responseText);
    $("#id_dificultad").html('<option value="">Error cargando dificultades</option>');
    $("#btnIniciar").prop("disabled", true);
  });
}

$("#id_dificultad").on("change", function(){
  $("#btnIniciar").prop("disabled", !$(this).val());
});

$("#btnIniciar").on("click", function(){
  const idDif = $("#id_dificultad").val();
  if(!idDif) return;

  $.post("../controllers/setDificultadController.php", { id_dificultad: idDif }, function(res){
    if(res && res.ok){
      $("#panelDificultad").addClass("d-none");
      $("#opcionesBox").removeClass("d-none");
      $("#subTitulo").text("Pregunta");
      $("#preguntaTexto").text("Cargando pregunta...");
      cargarPregunta();
    }else{
      alert(res.mensaje || "No se pudo guardar la dificultad");
    }
  }, "json").fail(function(xhr){
    console.log(xhr.responseText);
    alert("Error guardando dificultad");
  });
});

// audio unlock
$(document).on("click", function(){
  if(audioCtx && audioCtx.state === "suspended") audioCtx.resume();
});

// init
if(ORIGEN === "dashboard"){
  $("#opcionesBox").removeClass("d-none");
  cargarPregunta();
} else {
  cargarDificultades();
}
</script>

</body>
</html>
