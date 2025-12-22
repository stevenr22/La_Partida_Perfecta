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
    :root{
      --radius: 22px;
      --radius-sm: 14px;

      --shadow: 0 22px 60px rgba(0,0,0,.12);
      --shadow-soft: 0 12px 28px rgba(0,0,0,.10);

      --stroke: rgba(255,255,255,.60);
      --glass: rgba(255,255,255,.72);
      --glass-strong: rgba(255,255,255,.86);

      --text: #10121a;
      --muted: rgba(16,18,26,.62);

      --bg1: rgba(13,110,253,.18);
      --bg2: rgba(25,135,84,.16);
      --bg3: rgba(220,53,69,.14);
      --bg4: rgba(111,66,193,.14);

      --accent: #0d6efd;
      --ok: #198754;
      --bad: #dc3545;
      --warn: #ffc107;

      /* Kahoot colors */
      --a: #e11d48; /* rojo */
      --b: #2563eb; /* azul */
      --c: #f59e0b; /* amarillo */
      --d: #16a34a; /* verde */
      --vf: #6d28d9; /* morado V/F */

      --overlay: rgba(10,12,18,.55);
    }

    body{
      min-height:100vh;
      color:var(--text);
      background:
        radial-gradient(1200px 700px at 20% 10%, var(--bg1), transparent 60%),
        radial-gradient(900px 600px at 85% 25%, var(--bg2), transparent 60%),
        radial-gradient(1100px 650px at 45% 95%, var(--bg3), transparent 55%),
        radial-gradient(900px 600px at 60% 20%, var(--bg4), transparent 62%),
        #f6f7fb;
      overflow-x:hidden;
    }

    /* Blobs */
    .blob{
      position:fixed;
      width:560px; height:560px;
      filter: blur(34px);
      opacity:.22;
      z-index:-3;
      transform: translate3d(0,0,0);
      animation: floaty 10s ease-in-out infinite;
      border-radius: 45% 55% 60% 40% / 45% 45% 55% 55%;
      pointer-events:none;
    }
    .blob.b1{ left:-160px; top:-140px; background: rgba(13,110,253,.58); animation-duration: 12s; }
    .blob.b2{ right:-190px; top:60px; background: rgba(25,135,84,.55); animation-duration: 14s; animation-delay: -3s; }
    .blob.b3{ left:34%; bottom:-260px; background: rgba(220,53,69,.48); animation-duration: 16s; animation-delay: -6s; }
    .blob.b4{ right:25%; bottom:-240px; background: rgba(111,66,193,.48); animation-duration: 18s; animation-delay: -9s; }

    @keyframes floaty{
      0%,100%{ transform: translate(0,0) scale(1); }
      50%{ transform: translate(26px,-22px) scale(1.06); }
    }

    @media (prefers-reduced-motion: reduce){
      *{ animation: none !important; transition: none !important; }
    }

    /* Partículas flotantes */
    #bgParticles{
      position:fixed;
      inset:0;
      pointer-events:none;
      z-index:-2;
      opacity:.9;
    }

    /* FX */
    #fxCanvas{ position:fixed; inset:0; pointer-events:none; z-index:9999; }

    .game-wrap{ max-width:980px; margin:18px auto; padding:12px; }

    /* Topbar */
    .topbar{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:14px;
    }

    .brand{
      display:flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius:999px;
      background: var(--glass);
      border: 1px solid var(--stroke);
      box-shadow: var(--shadow-soft);
      backdrop-filter: blur(16px);
    }

    .brand .pill{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:8px 12px;
      border-radius:999px;
      font-weight:950;
      background: rgba(13,110,253,.14);
      border: 1px solid rgba(13,110,253,.18);
      letter-spacing:.2px;
    }

    .brand .hint{
      color: var(--muted);
      font-weight:800;
      font-size:.92rem;
      white-space:nowrap;
    }

    .controls{ display:flex; align-items:center; gap:10px; }

    .ctrl{
      display:flex; align-items:center; gap:10px;
      padding:10px 12px;
      border-radius:999px;
      background: var(--glass);
      border: 1px solid var(--stroke);
      box-shadow: var(--shadow-soft);
      backdrop-filter: blur(16px);
    }

    .ctrl .btn-ico{
      width:44px; height:44px;
      border-radius:999px;
      border:1px solid rgba(0,0,0,.08);
      background: rgba(255,255,255,.95);
      display:grid; place-items:center;
      font-size:18px;
      cursor:pointer;
      transition: transform .12s ease, box-shadow .2s ease;
      box-shadow: 0 12px 22px rgba(0,0,0,.10);
      user-select:none;
    }
    .ctrl .btn-ico:hover{ transform: translateY(-2px); box-shadow: 0 16px 30px rgba(0,0,0,.14); }
    .ctrl .btn-ico:active{ transform: translateY(0) scale(.98); }
    .ctrl .vol{ width:120px; }

    /* Timer */
    .timer-box{
      width:360px; max-width:58vw;
      padding:10px 12px;
      border-radius:999px;
      background: var(--glass);
      border: 1px solid var(--stroke);
      box-shadow: var(--shadow);
      backdrop-filter: blur(18px);
      display:flex; align-items:center; gap:10px;
      position:relative;
      overflow:hidden;
    }
    .timer-box::after{
      content:"";
      position:absolute;
      inset:-2px;
      background: radial-gradient(420px 140px at 20% 10%, rgba(13,110,253,.16), transparent 60%);
      opacity:.9;
      pointer-events:none;
    }
    .timer-box > *{ position:relative; }

    .timer-box .time{
      font-weight:980;
      width:72px;
      text-align:center;
      font-variant-numeric: tabular-nums;
      padding:7px 10px;
      border-radius:999px;
      background: var(--glass-strong);
      border:1px solid rgba(0,0,0,.06);
      box-shadow: 0 12px 20px rgba(0,0,0,.08);
    }

    .timer-progress{
      height:12px;
      border-radius:999px;
      overflow:hidden;
      background: rgba(0,0,0,.09);
      flex:1; position:relative;
    }
    .timer-bar{
      position:absolute; inset:0;
      transform-origin:left center;
      transform: scaleX(1);
      transition: transform .1s linear;
      background: linear-gradient(90deg, rgba(25,135,84,.95), rgba(13,110,253,.95));
    }
    .timer-box.danger .timer-bar{
      background: linear-gradient(90deg, rgba(220,53,69,.98), rgba(255,193,7,.98));
    }
    .timer-box.pulse{ animation: pulse 0.55s ease-in-out infinite; }
    @keyframes pulse{
      0%,100%{ transform: scale(1); }
      50%{ transform: scale(1.02); }
    }

    /* HUD (progreso + stats) */
    .hud{
      margin: 10px 0 14px 0;
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      align-items:center;
      justify-content:space-between;
    }
    .hud .chip{
      display:inline-flex;
      align-items:center;
      gap:10px;
      padding:10px 12px;
      border-radius:999px;
      background: var(--glass);
      border:1px solid var(--stroke);
      box-shadow: var(--shadow-soft);
      backdrop-filter: blur(16px);
      font-weight:950;
    }
    .hud .chip small{
      font-weight:800;
      color: var(--muted);
    }
    .hud .bar{
      flex: 1 1 260px;
      min-width: 240px;
      height: 12px;
      border-radius:999px;
      background: rgba(0,0,0,.10);
      overflow:hidden;
      border:1px solid rgba(0,0,0,.06);
    }
    .hud .bar > div{
      height:100%;
      width: 0%;
      transition: width .35s ease;
      background: linear-gradient(90deg, rgba(13,110,253,.95), rgba(111,66,193,.85));
    }

    /* Card */
    .card-game{
      border:0;
      border-radius: var(--radius);
      overflow:hidden;
      box-shadow: var(--shadow);
      background: rgba(255,255,255,.58);
      border: 1px solid rgba(255,255,255,.58);
      backdrop-filter: blur(20px);
      position:relative;
      transform: translateZ(0);
    }
    .card-game::before{
      content:"";
      position:absolute;
      inset:-2px;
      background: linear-gradient(135deg, rgba(13,110,253,.16), rgba(25,135,84,.12), rgba(111,66,193,.12));
      opacity:.85;
      pointer-events:none;
    }
    .card-game > *{ position:relative; }

    .card-header-game{
      padding:16px 16px 14px 16px;
      display:flex;
      align-items:flex-start;
      justify-content:space-between;
      gap:12px;
      background: rgba(255,255,255,.72);
      border-bottom: 1px solid rgba(0,0,0,.06);
    }

    .subtitle{
      margin:0;
      color: var(--muted);
      font-weight:900;
      font-size:.92rem;
      letter-spacing:.2px;
    }
    .question{
      margin:6px 0 0 0;
      font-weight:990;
      line-height:1.10;
      font-size: 1.46rem;
      text-wrap: balance;
    }

    .badge-soft{
      padding:10px 12px;
      border-radius:999px;
      font-weight:980;
      box-shadow: 0 12px 20px rgba(0,0,0,.08);
      border:1px solid rgba(0,0,0,.06);
      background: var(--glass-strong);
      display:inline-flex; align-items:center; gap:8px;
      white-space:nowrap;
    }

    .card-body{ padding: 16px; }

    /* Transición de pregunta */
    .stage{
      transition: transform .35s ease, opacity .35s ease, filter .35s ease;
      transform: translateY(0);
      opacity:1;
      filter: blur(0px);
    }
    .stage.loading{
      transform: translateY(8px);
      opacity:.45;
      filter: blur(2px);
    }

    /* Options (Kahoot colors) */
    .options{
      display:grid;
      grid-template-columns: repeat(2, minmax(0,1fr));
      gap:12px;
    }
    @media (max-width: 576px){
      .options{ grid-template-columns:1fr; }
      .timer-box{ width:100%; }
      .topbar{ flex-direction:column; align-items:stretch; }
      .controls{ justify-content:space-between; width:100%; }
      .brand{ width:100%; justify-content:space-between; }
    }

    .opt-btn{
      position:relative;
      border-radius: 20px;
      padding: 16px 14px;
      font-size: 1.06rem;
      font-weight: 980;
      border: 1px solid rgba(255,255,255,.35);
      background: rgba(255,255,255,.70);
      box-shadow: 0 16px 34px rgba(0,0,0,.12);
      text-align:left;
      display:flex; align-items:flex-start; gap:12px;
      min-height:78px;
      cursor:pointer;
      transition: transform .12s ease, box-shadow .18s ease, background .18s ease, filter .18s ease;
      overflow:hidden;
      outline: none;
      will-change: transform;
    }
    .opt-btn::before{
      content:"";
      position:absolute;
      inset:-2px;
      opacity:.18;
      background: radial-gradient(420px 240px at 10% 10%, rgba(255,255,255,.85), transparent 60%);
    }
    .opt-btn::after{
      content:"";
      position:absolute;
      inset:0;
      opacity:.18;
      background: linear-gradient(135deg, rgba(255,255,255,.25), transparent 55%);
      pointer-events:none;
    }
    .opt-btn > *{ position:relative; }

    .opt-btn:hover{
      transform: translateY(-2px) scale(1.01);
      box-shadow: 0 24px 52px rgba(0,0,0,.16);
      filter: saturate(1.05);
    }
    .opt-btn:active{ transform: translateY(0) scale(.995); }

    .opt-btn:focus-visible{
      box-shadow: 0 0 0 4px rgba(13,110,253,.22), 0 24px 52px rgba(0,0,0,.16);
    }

    .opt-tag{
      width:40px; height:40px;
      border-radius: 15px;
      display:grid; place-items:center;
      font-weight: 990;
      flex: 0 0 auto;
      background: rgba(255,255,255,.28);
      border: 1px solid rgba(255,255,255,.35);
      box-shadow: 0 12px 18px rgba(0,0,0,.10);
      color:#fff;
    }

    /* Colores por letra */
    .opt-a{ background: color-mix(in srgb, var(--a) 85%, white 15%); }
    .opt-b{ background: color-mix(in srgb, var(--b) 85%, white 15%); }
    .opt-c{ background: color-mix(in srgb, var(--c) 88%, white 12%); color:#10121a; }
    .opt-d{ background: color-mix(in srgb, var(--d) 85%, white 15%); }
    .opt-v{ background: color-mix(in srgb, var(--vf) 85%, white 15%); }
    .opt-f{ background: color-mix(in srgb, #111827 85%, white 15%); }

    .opt-c .opt-tag{ color:#10121a; }
    .opt-c .opt-text{ color:#10121a; }
    .opt-text{ color:#fff; }

    /* Mensaje */
    .message{
      border-radius: 18px;
      font-weight: 980;
      display:flex;
      align-items:center;
      justify-content:center;
      gap:10px;
      padding: 12px 14px;
      box-shadow: var(--shadow-soft);
      backdrop-filter: blur(10px);
    }

    .glow-win{ box-shadow: 0 0 0 4px rgba(25,135,84,.18), 0 26px 70px rgba(25,135,84,.22) !important; }
    .glow-lose{ box-shadow: 0 0 0 4px rgba(220,53,69,.18), 0 26px 70px rgba(220,53,69,.22) !important; }

    .shake{ animation: shake .35s ease-in-out; }
    @keyframes shake{
      0%{ transform: translateX(0) }
      25%{ transform: translateX(-8px) }
      50%{ transform: translateX(8px) }
      75%{ transform: translateX(-6px) }
      100%{ transform: translateX(0) }
    }

    .pop{ animation: pop .22s ease-out; }
    @keyframes pop{
      from{ transform: scale(.985); opacity:.75; }
      to{ transform: scale(1); opacity:1; }
    }

    .end-screen{ text-align:center; padding: 24px 10px; }
    .end-screen .big{ font-size: 2rem; font-weight: 990; margin-bottom: 6px; }
    .end-screen .mini{ color: var(--muted); font-weight:900; }

    /* Picker */
    .picker{ display:grid; gap:12px; }
    .picker .box{
      background: var(--glass);
      border-radius: var(--radius);
      border: 1px solid rgba(255,255,255,.58);
      box-shadow: var(--shadow);
      padding: 16px;
      backdrop-filter: blur(20px);
    }

    .music-chip{
      display:inline-flex; align-items:center; gap:8px;
      font-weight:980;
      padding:8px 10px;
      border-radius:999px;
      background: rgba(25,135,84,.12);
      border:1px solid rgba(25,135,84,.18);
      color: var(--text);
      user-select:none;
    }

    /* Overlay de feedback */
    .overlay{
      position:fixed;
      inset:0;
      display:none;
      z-index:10000;
      background: var(--overlay);
      backdrop-filter: blur(10px);
      align-items:center;
      justify-content:center;
      padding:16px;
    }
    .overlay.show{ display:flex; animation: ovIn .18s ease-out; }
    @keyframes ovIn{ from{ opacity:0; transform: scale(.98);} to{opacity:1; transform: scale(1);} }

    .ov-card{
      width:min(720px, 92vw);
      border-radius: 28px;
      background: var(--glass-strong);
      border: 1px solid rgba(255,255,255,.40);
      box-shadow: 0 30px 100px rgba(0,0,0,.35);
      padding: 18px;
      position:relative;
      overflow:hidden;
    }
    .ov-card::before{
      content:"";
      position:absolute;
      inset:-2px;
      opacity:.55;
      background: radial-gradient(520px 280px at 20% 10%, rgba(255,255,255,.55), transparent 60%);
    }
    .ov-card > *{ position:relative; }

    .ov-title{
      font-weight: 990;
      font-size: 1.8rem;
      margin: 0 0 6px 0;
      line-height:1.05;
    }
    .ov-sub{
      margin:0;
      font-weight:900;
      color: var(--muted);
    }
    .ov-badge{
      display:inline-flex;
      margin-top:12px;
      padding:10px 12px;
      border-radius:999px;
      font-weight:980;
      border: 1px solid rgba(0,0,0,.06);
      background: rgba(255,255,255,.85);
      box-shadow: 0 14px 24px rgba(0,0,0,.12);
      gap:8px;
      align-items:center;
    }

    .ov-ok{ outline: 4px solid rgba(25,135,84,.18); }
    .ov-bad{ outline: 4px solid rgba(220,53,69,.18); }
    .ov-warn{ outline: 4px solid rgba(255,193,7,.18); }

    /* Countdown overlay */
    #cdNumber{
      text-shadow: 0 18px 50px rgba(0,0,0,.25);
    }
    #cdBar{
      width:0%;
      height:100%;
      transition: width .35s ease;
      background: linear-gradient(90deg, rgba(255,193,7,.95), rgba(13,110,253,.95));
    }
    .cd-pop{ animation: cdPop .22s ease-out; }
    @keyframes cdPop{
      from{ transform: scale(.92); opacity:.5; }
      to{ transform: scale(1); opacity:1; }
    }
    .cd-go{ animation: cdGo .35s ease-out; }
    @keyframes cdGo{
      0%{ transform: scale(.95); filter: blur(1px); opacity:.65; }
      100%{ transform: scale(1.03); filter: blur(0px); opacity:1; }
    }
  </style>
</head>

<body>
  <div class="blob b1"></div>
  <div class="blob b2"></div>
  <div class="blob b3"></div>
  <div class="blob b4"></div>

  <canvas id="bgParticles"></canvas>
  <canvas id="fxCanvas"></canvas>

  <!-- Overlay feedback -->
  <div class="overlay" id="overlay">
    <div class="ov-card" id="ovCard">
      <div class="d-flex justify-content-between align-items-start gap-3">
        <div>
          <h2 class="ov-title" id="ovTitle">—</h2>
          <p class="ov-sub" id="ovSub">—</p>
        </div>
        <div class="text-end">
          <div class="ov-badge" id="ovBadge">⏭️ Siguiente...</div>
        </div>
      </div>
      <div class="mt-3">
        <div class="hud" style="margin:10px 0 0 0;">
          <span class="chip"><span>📊</span> <span id="ovStats">—</span></span>
          <div class="bar"><div id="ovBar"></div></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Countdown GO -->
  <div class="overlay" id="countdownOverlay" style="z-index:10001;">
    <div class="ov-card" id="cdCard" style="text-align:center;">
      <div style="font-weight:990; color: var(--muted); letter-spacing:.5px;">Prepárate…</div>
      <div id="cdNumber" style="font-size:5rem; font-weight:1000; line-height:1; margin:10px 0 6px 0;">3</div>
      <div id="cdText" style="font-weight:950; font-size:1.2rem;">¡Vamos!</div>

      <div class="mt-3">
        <div class="hud" style="margin:10px 0 0 0;">
          <span class="chip"><span>🎵</span> <span>¡Modo Quizz!</span></span>
          <div class="bar"><div id="cdBar"></div></div>
        </div>
      </div>
    </div>
  </div>

  <div class="game-wrap">
    <div class="topbar">
      <div class="brand">
        <span class="pill">🎮 Partida en curso</span>
        <span class="hint">Responde antes que se acabe el tiempo</span>
      </div>

      <div class="controls">
        <div class="ctrl">
          <div class="btn-ico" id="btnMusic" title="Silenciar / Activar">🔊</div>
          <input id="volMusic" class="form-range vol" type="range" min="0" max="100" value="35" title="Volumen">
        </div>

        <div class="timer-box" id="timerBox">
          <div class="time" id="timeText">--s</div>
          <div class="timer-progress"><div class="timer-bar" id="timerBar"></div></div>
        </div>
      </div>
    </div>

    <!-- HUD PRO -->
    <div class="hud">
      <span class="chip">
        🧠 <span id="qCounter">Pregunta 0</span>
        <small id="qTotalHint">/ ?</small>
      </span>

      <div class="bar" aria-label="Progreso">
        <div id="qProgress"></div>
      </div>

      <span class="chip">
        ✅ <span id="statOk">0</span>
        &nbsp;❌ <span id="statBad">0</span>
        &nbsp;🔥 Racha: <span id="statStreak">0</span>
      </span>
    </div>

    <div class="card card-game" id="cardGame">
      <div class="card-header-game">
        <div>
          <p class="subtitle" id="subTitulo"><?= ($origenJuego === "pin" ? "Preparación" : "Pregunta") ?></p>
          <h3 class="question" id="preguntaTexto"><?= ($origenJuego === "pin" ? "Selecciona la dificultad" : "Cargando pregunta...") ?></h3>
          <div class="mt-2 d-flex gap-2 flex-wrap">
            <span class="music-chip" id="musicState">🎵 Música: Auto (al primer click)</span>
          </div>
        </div>

        <div class="text-end">
          <span class="badge-soft" id="estadoBadge">✅ Listo</span>
        </div>
      </div>

      <div class="card-body">
        <?php if ($origenJuego === "pin"): ?>
          <div id="panelDificultad" class="picker">
            <div class="box pop">
              <div class="fw-bold mb-1">Dificultad según tu nivel</div>
              <div class="text-secondary small mb-3">
                Tu rol actual: <span class="fw-bold"><?= $idRolJugador > 0 ? $idRolJugador : "No detectado" ?></span>
              </div>

              <label class="fw-bold">Selecciona dificultad</label>
              <select id="id_dificultad" class="form-select form-select-lg mt-2">
                <option value="">Cargando...</option>
              </select>

              <div class="d-grid gap-2 mt-3">
                <button id="btnIniciar" class="btn btn-primary btn-lg" type="button" disabled>Iniciar juego</button>
                <a class="btn btn-outline-secondary" href="../index.php">Salir</a>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <div id="stage" class="stage">
          <div class="options <?= ($origenJuego === "pin" ? "d-none" : "") ?>" id="opcionesBox"></div>
          <div id="mensaje" class="alert d-none mt-3 message text-center"></div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>

  <script>
    const ORIGEN = <?= json_encode($origenJuego) ?>;
    const ID_ROL_JUGADOR = <?= (int)$idRolJugador ?>;
    

    let bloqueado=false, timerId=null, duracion=25, timeLeft=25, questionToken=0;
    const $timerBox=$("#timerBox"), $timerBar=$("#timerBar"), $timeText=$("#timeText"), $card=$("#cardGame");
    const $stage=$("#stage");

    // Stats PRO (frontend)
    let qIndex = 0;
    let qTotal = null;
    let okCount = 0;
    let badCount = 0;
    let streak = 0;

    function renderHud(){
      $("#qCounter").text(`Pregunta ${qIndex}`);
      $("#qTotalHint").text(qTotal ? `/ ${qTotal}` : `/ ?`);
      $("#statOk").text(okCount);
      $("#statBad").text(badCount);
      $("#statStreak").text(streak);

      let pct = 0;
      if(qTotal && qTotal > 0) pct = Math.min(100, Math.round((qIndex / qTotal) * 100));
      else pct = Math.min(92, qIndex * 8);
      $("#qProgress").css("width", pct + "%");
    }
    renderHud();

    // ========= SFX =========
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
    function sClick(){ beep(520,40,"sine",0.025); }
    function sWhoosh(){ beep(260,40,"sine",0.02); setTimeout(()=>beep(520,60,"sine",0.02),45); }

    // ========= SFX COUNTDOWN estilo Kahoot =========
    function sCountTick(){ beep(880, 55, "square", 0.03); }
    function sCountGo(){
      beep(523, 90, "triangle", 0.06);
      setTimeout(()=>beep(659, 110, "triangle", 0.06), 90);
      setTimeout(()=>beep(784, 130, "triangle", 0.06), 190);
    }

    // ========= MÚSICA (Lobby / Game / Hurry) + WIN/LOSE =========
    // Archivos:
    // ../assets/songs/lobby.mp3
    // ../assets/songs/quizzGame.mp3
    // ../assets/songs/hurry.mp3
    // ../assets/songs/win.mp3
    // ../assets/songs/lose.mp3

    const musicLobby = new Audio("../assets/songs/lobby.mp3");
    const musicGame  = new Audio("../assets/songs/quizzGame.mp3");
    const musicHurry = new Audio("../assets/songs/hurry.mp3");

    const sWin  = new Audio("../assets/songs/win.mp3");
    const sLose = new Audio("../assets/songs/lose.mp3");

    const tracks = [musicLobby, musicGame, musicHurry];
    tracks.forEach(a => { a.loop = true; a.preload = "auto"; a.volume = 0.35; });

    [sWin, sLose].forEach(a => { a.loop = false; a.preload = "auto"; a.volume = 0.95; });

    let musicOn = false;
    let currentTrack = null;
    let inHurry = false;

    function setAllVolume(v){ tracks.forEach(a => a.volume = v); }

    function pauseAllMusic(){
      tracks.forEach(a => { try{ a.pause(); a.currentTime = 0; }catch(e){} });
      currentTrack = null;
      inHurry = false;
    }

    async function fadeTo(track, ms=450){
      try{
        if(!musicOn) return;

        const targetVol = (parseInt($("#volMusic").val() || "35") / 100);

        if(currentTrack === track){
          if(currentTrack.paused) await currentTrack.play();
          return;
        }

        if(currentTrack){
          const start = currentTrack.volume;
          const steps = 12;
          for(let i=0;i<steps;i++){
            currentTrack.volume = start * (1 - (i+1)/steps);
            await new Promise(r=>setTimeout(r, ms/steps));
          }
          try{ currentTrack.pause(); }catch(e){}
          try{ currentTrack.currentTime = 0; }catch(e){}
        }

        currentTrack = track;
        currentTrack.currentTime = 0;
        currentTrack.volume = 0;
        await currentTrack.play();

        const steps2 = 12;
        for(let i=0;i<steps2;i++){
          currentTrack.volume = targetVol * ((i+1)/steps2);
          await new Promise(r=>setTimeout(r, ms/steps2));
        }
      }catch(e){}
    }

    function setMusicUI(){
      $("#musicState").text(musicOn ? "🎵 Música: ON (Auto)" : "🎵 Música: OFF");
      $("#btnMusic").text(musicOn ? "⏸️" : "🔊");
      $("#btnMusic").attr("title", musicOn ? "Silenciar" : "Activar");
    }

    function isLobbyStage(){
      return (ORIGEN !== "dashboard" && $("#panelDificultad").length && $("#panelDificultad").is(":visible"));
    }
    function isQuizStage(){ return !isLobbyStage(); }

    async function ensureStageTrack(){
      if(!musicOn) return;
      if(inHurry) return;

      if(isLobbyStage()){
        await fadeTo(musicLobby, 450);
      }else{
        await fadeTo(musicGame, 450);
      }
    }

    async function enterHurry(){
      if(!musicOn) return;
      if(inHurry) return;
      inHurry = true;
      await fadeTo(musicHurry, 320);
    }

    async function exitHurry(){
      if(!musicOn) return;
      if(!inHurry) return;
      inHurry = false;
      await ensureStageTrack();
    }

    async function playEndSound(aprobado){
      try{
        inHurry = false;

        if(currentTrack){
          currentTrack.volume = Math.max(0.06, currentTrack.volume * 0.20);
        }

        const a = aprobado ? sWin : sLose;
        a.currentTime = 0;
        await a.play();

        // pausa música luego (para que se escuche claro)
        setTimeout(()=>{ pauseAllMusic(); }, 2000);
      }catch(e){}
    }

    // ========= AUTOPLAY (al primer click/tecla) =========
    let userInteracted = false;
    function unlockAudio(){
      if(userInteracted) return;
      userInteracted = true;

      try{
        if(!audioCtx) audioCtx=new (window.AudioContext||window.webkitAudioContext)();
        if(audioCtx.state === "suspended") audioCtx.resume();
      }catch(e){}

      musicOn = true;
      ensureStageTrack();
      setMusicUI();
    }
    document.addEventListener("click", unlockAudio, { once:true });
    document.addEventListener("touchstart", unlockAudio, { once:true });
    document.addEventListener("keydown", unlockAudio, { once:true });

    $("#btnMusic").on("click", async function(){
      if(!musicOn){
        musicOn = true;
        await ensureStageTrack();
      }else{
        pauseAllMusic();
        musicOn = false;
      }
      setMusicUI();
    });

    $("#volMusic").on("input", function(){
      const v = Math.max(0, Math.min(100, parseInt(this.value||"35")));
      setAllVolume(v/100);
    });

    // ========= BACKGROUND PARTÍCULAS =========
    const bg = document.getElementById("bgParticles");
    const bctx = bg.getContext("2d");
    let BW=0,BH=0;
    let stars=[];
    function resizeBG(){
      BW = bg.width = window.innerWidth;
      BH = bg.height = window.innerHeight;
      stars = Array.from({length: 90}, () => ({
        x: Math.random()*BW,
        y: Math.random()*BH,
        r: 1 + Math.random()*2.6,
        a: 0.10 + Math.random()*0.38,
        vx: -0.18 + Math.random()*0.36,
        vy: -0.12 + Math.random()*0.28,
        tw: 0.003 + Math.random()*0.012
      }));
    }
    window.addEventListener("resize", resizeBG);
    resizeBG();

    let tStar=0;
    function loopBG(){
      tStar += 1;
      bctx.clearRect(0,0,BW,BH);

      const g = bctx.createRadialGradient(BW*0.28, BH*0.12, 10, BW*0.28, BH*0.12, Math.max(BW,BH));
      g.addColorStop(0, "rgba(13,110,253,0.12)");
      g.addColorStop(1, "rgba(255,255,255,0)");
      bctx.fillStyle = g;
      bctx.fillRect(0,0,BW,BH);

      for(const s of stars){
        s.x += s.vx; s.y += s.vy;
        if(s.x < -20) s.x = BW+20;
        if(s.x > BW+20) s.x = -20;
        if(s.y < -20) s.y = BH+20;
        if(s.y > BH+20) s.y = -20;

        const tw = 0.5 + 0.5*Math.sin(tStar*s.tw + s.x*0.01);
        bctx.beginPath();
        bctx.fillStyle = `rgba(255,255,255,${(s.a*tw).toFixed(3)})`;
        bctx.arc(s.x, s.y, s.r*tw, 0, Math.PI*2);
        bctx.fill();
      }
      requestAnimationFrame(loopBG);
    }
    loopBG();

    // ========= FX confetti/sad =========
    const fx=document.getElementById("fxCanvas");
    const ctx=fx.getContext("2d");
    let W=0,H=0, particles=[], animating=false;
    function resizeFx(){ W=fx.width=window.innerWidth; H=fx.height=window.innerHeight; }
    window.addEventListener("resize", resizeFx); resizeFx();

    function launchConfetti(){
      particles=[];
      for(let i=0;i<210;i++){
        particles.push({
          x:Math.random()*W, y:-20-Math.random()*H*0.3,
          vx:(Math.random()-0.5)*4.8, vy:2.2+Math.random()*7.0,
          r:4+Math.random()*7, rot:Math.random()*Math.PI,
          vr:(Math.random()-0.5)*0.30, life:65+Math.random()*80, t:"conf"
        });
      }
      animateFx();
    }
    function launchSad(){
      particles=[];
      for(let i=0;i<100;i++){
        particles.push({
          x:Math.random()*W, y:-20-Math.random()*H*0.2,
          vx:(Math.random()-0.5)*2.0, vy:3.1+Math.random()*4.8,
          r:3+Math.random()*4.2, life:70+Math.random()*65, t:"sad"
        });
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
            ctx.save();
            ctx.translate(p.x,p.y); ctx.rotate(p.rot);
            ctx.fillStyle=`hsl(${(p.x+p.y)%360},90%,55%)`;
            ctx.fillRect(-p.r,-p.r*0.3,p.r*2,p.r*0.6);
            ctx.restore();
          }else{
            ctx.beginPath();
            ctx.fillStyle="rgba(0,0,0,.18)";
            ctx.arc(p.x,p.y,p.r,0,Math.PI*2); ctx.fill();
          }
        });
        particles=particles.filter(p=>p.life>0 && p.y<H+40);
        if(particles.length>0) requestAnimationFrame(loop);
        else { ctx.clearRect(0,0,W,H); animating=false; }
      })();
    }

    // ========= Overlay feedback =========
    const $overlay = $("#overlay");
    const $ovCard = $("#ovCard");
    function showOverlay(type, title, sub){
      $("#ovTitle").text(title);
      $("#ovSub").text(sub);

      const stats = `✅ ${okCount}  ❌ ${badCount}  🔥 racha ${streak}`;
      $("#ovStats").text(stats);

      const pct = qTotal ? Math.min(100, Math.round((qIndex / qTotal) * 100)) : Math.min(92, qIndex * 8);
      $("#ovBar").css("width", pct + "%");

      $ovCard.removeClass("ov-ok ov-bad ov-warn");
      if(type==="ok") $ovCard.addClass("ov-ok");
      if(type==="bad") $ovCard.addClass("ov-bad");
      if(type==="warn") $ovCard.addClass("ov-warn");

      $overlay.addClass("show");
    }
    function hideOverlay(){ $overlay.removeClass("show"); }

    // ========= Countdown GO =========
    const $cdOverlay = $("#countdownOverlay");
    const $cdNumber  = $("#cdNumber");
    const $cdText    = $("#cdText");
    const $cdBar     = $("#cdBar");
    let countdownShown = false;

    function showCountdownGO(onDone){
      $cdOverlay.addClass("show");
      $("#cdCard").addClass("cd-pop");
      setTimeout(()=>$("#cdCard").removeClass("cd-pop"), 240);

      const steps = ["3","2","1","GO!"];
      let i = 0;
      $cdBar.css("width", "0%");

      function paint(){
        const val = steps[i];
        $cdNumber.text(val).addClass("cd-pop");
        setTimeout(()=>$cdNumber.removeClass("cd-pop"), 220);

        const pct = Math.min(100, Math.round(((i+1)/steps.length)*100));
        $cdBar.css("width", pct + "%");

        if(val === "GO!"){
          $cdText.text("🔥 ¡A jugar!");
          $("#cdCard").addClass("cd-go");
          sCountGo();
        }else{
          $cdText.text("Responde rápido 💥");
          sCountTick();
        }

        i++;
        if(i < steps.length){
          setTimeout(paint, 780);
        }else{
          setTimeout(async ()=>{
            $("#cdCard").removeClass("cd-go");
            $cdOverlay.removeClass("show");

            // ✅ Al terminar el countdown, fuerza QUIZZ GAME
            if(musicOn) await fadeTo(musicGame, 300);

            if(typeof onDone === "function") onDone();
          }, 520);
        }
      }
      paint();
    }

    // ========= TIMER =========
    function stopTimer(){ if(timerId) clearInterval(timerId); timerId=null; }

    function updateTimerUI(){
      $timeText.text(`${timeLeft}s`);
      const ratio=Math.max(0, Math.min(1, timeLeft/duracion));
      $timerBar.css("transform", `scaleX(${ratio})`);
      if(timeLeft<=6) $timerBox.addClass("danger pulse");
      else $timerBox.removeClass("danger pulse");
    }

    function startTimer(seconds, token){
      stopTimer();
      duracion=seconds; timeLeft=seconds; updateTimerUI();

      // ✅ al iniciar pregunta: asegura música de QUIZ (no lobby), y que hurry esté apagado
      exitHurry();
      ensureStageTrack();

      timerId=setInterval(async ()=>{
        if(token!==questionToken){ stopTimer(); return; }
        timeLeft--; updateTimerUI();

        if(timeLeft<=5 && timeLeft>0){
          sTickFinal();
          if(timeLeft === 5) enterHurry(); // ✅ últimos 5s: hurry
        }

        if(timeLeft<=0){
          stopTimer();
          exitHurry(); // ✅ sale de hurry
          responder("__TIMEOUT__");
        }
      }, 1000);
    }

    // ========= UI helpers =========
    function setEstado(t, icon="✅"){
      $("#estadoBadge").text(`${icon} ${t}`).addClass("pop");
      setTimeout(()=>$("#estadoBadge").removeClass("pop"), 220);
    }
    function limpiarMensaje(){
      $("#mensaje").addClass("d-none")
        .removeClass("alert-success alert-danger alert-warning")
        .text("");
      $card.removeClass("glow-win glow-lose shake pop");
    }
    function bloquearBotones(flag){
      $("#opcionesBox button, #respuestaCompletar, #btnEnviarCompletar").prop("disabled", flag);
      if(flag) $("#opcionesBox button").css("opacity", .86);
      else $("#opcionesBox button").css("opacity", 1);
    }
    function escapeHtml(str){
      return (str||"").toString()
        .replaceAll("&","&amp;")
        .replaceAll("<","&lt;")
        .replaceAll(">","&gt;")
        .replaceAll('"',"&quot;")
        .replaceAll("'","&#039;");
    }

    $(document).on("click", ".opt-btn", function(){ sClick(); });

    function stageLoading(flag){
      if(flag) $stage.addClass("loading");
      else $stage.removeClass("loading");
    }

    function classForLetter(tag){
      const t = (tag||"").toUpperCase();
      if(t === "A") return "opt-a";
      if(t === "B") return "opt-b";
      if(t === "C") return "opt-c";
      if(t === "D") return "opt-d";
      if(t === "V") return "opt-v";
      if(t === "F") return "opt-f";
      return "opt-b";
    }

    // ========= CARGAR PREGUNTA =========
    function cargarPregunta(){
      limpiarMensaje();
      stageLoading(true);
      setEstado("Cargando...", "⏳");
      $("#preguntaTexto").text("Cargando pregunta...");
      $("#opcionesBox").html(`<div class="text-center text-secondary pop">Preparando...</div>`);

      sWhoosh();
      bloqueado=true; bloquearBotones(true);
      questionToken++; const token=questionToken;

      // ✅ mientras haya juego, que suene lo correcto (lobby o quiz)
      ensureStageTrack();

      $.getJSON("../controllers/obtenerPreguntaController.php", function(res){
        if(token!==questionToken) return;

        stageLoading(false);

        // ===== FIN DEL JUEGO =====
        if(!res || !res.ok){
          stopTimer();
          exitHurry();
          setEstado("Finalizado", "🏁");

          $.post("../controllers/finalizarPartidaController.php", {}, function(fin){
            const correctas = fin?.correctas ?? 0;
            const total = fin?.total ?? 0;
            const aprobado = !!fin?.aprobado;

            qTotal = total || qTotal;
            renderHud();

            const titulo = aprobado ? "🎉 ¡Aprobaste!" : "😥 No aprobaste";
            const msg = aprobado
              ? `Lograste ${correctas}/${total}. ¡Excelente!`
              : `Lograste ${correctas}/${total}. Inténtalo otra vez.`;

            $("#preguntaTexto").text(titulo);
            $("#opcionesBox").html(`
              <div class="end-screen pop">
                <div class="big">${titulo}</div>
                <div class="mini mb-2">${msg}</div>
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

            // ✅ FIN: sonido win/lose + corta música
            playEndSound(aprobado);

            if(aprobado){ launchConfetti(); sCorrecto(); $card.addClass("glow-win"); }
            else { launchSad(); sIncorrecto(); $card.addClass("glow-lose"); }
          }, "json");

          return;
        }

        // ===== SIGUIENTE PREGUNTA =====
        qIndex++;
        renderHud();

        const t=parseInt(res.tiempo || 25);
        const tiempo=(isNaN(t) || t<=5) ? 25 : t;

        $("#subTitulo").text("Pregunta");
        $("#preguntaTexto").text(res.pregunta);
        setEstado("Responde ahora", "🔥");

        if(res.tipo === "completar"){
          $("#opcionesBox").html(`
            <div class="w-100 pop">
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
          setTimeout(()=>$("#respuestaCompletar").focus(), 120);
          return;
        }

        let html="";
        const letras=["A","B","C","D","V","F"];
        (res.opciones || []).forEach((op,i)=>{
          const tag=(op.letra || letras[i] || "?").toString();
          const txt=(op.texto || "").toString();
          const cls = classForLetter(tag);

          html += `
            <button type="button" class="opt-btn ${cls} pop" onclick="responder('${tag.replace(/'/g,"\\'")}')">
              <span class="opt-tag">${escapeHtml(tag)}</span>
              <span class="opt-text">${escapeHtml(txt)}</span>
            </button>
          `;
        });

        $("#opcionesBox").html(html);
        bloqueado=false; bloquearBotones(false);
        startTimer(tiempo, token);
      })
      .fail(function(xhr){
        stageLoading(false);
        stopTimer();
        exitHurry();
        setEstado("Error", "❌");
        $("#preguntaTexto").text("Error al cargar la pregunta ❌");
        $("#opcionesBox").html(`<div class="text-center text-danger pop">Revisa el controller / sesión</div>`);
        console.log(xhr.responseText);
      });
    }

    function enviarCompletar(){
      if(bloqueado) return;
      const txt = ($("#respuestaCompletar").val() || "").trim();
      if(!txt){
        $("#mensaje").removeClass("d-none alert-success alert-danger")
          .addClass("alert-warning").text("✍️ Escribe una respuesta");
        return;
      }
      sClick();
      responder(txt);
    }

    function responder(valor){
      if(bloqueado) return;

      bloqueado=true;
      bloquearBotones(true);
      setEstado("Enviando...", "📤");
      stopTimer();
      exitHurry(); // ✅ al responder se apaga hurry y vuelve a game

      $.post("../controllers/responderPreguntaController.php",
        { respuesta: valor },
        function(res){
          const ok = !!(res && res.correcta);

          if(valor === "__TIMEOUT__"){
            badCount++; streak = 0; renderHud();
            $("#mensaje").removeClass("d-none alert-success")
              .addClass("alert-warning").text("⏰ Tiempo agotado");
            $card.addClass("glow-lose shake");
            sIncorrecto(); launchSad();
            setEstado("Sin tiempo", "⏱️");
            showOverlay("warn", "⏰ ¡Tiempo agotado!", "Responde más rápido en la siguiente");
          }else if(ok){
            okCount++; streak++; renderHud();
            $("#mensaje").removeClass("d-none alert-danger alert-warning")
              .addClass("alert-success").text("✅ ¡Correcto!");
            $card.addClass("glow-win pop");
            sCorrecto(); launchConfetti();
            setEstado("¡Bien!", "🏆");
            showOverlay("ok", "✅ ¡Correcto!", `🔥 Racha: ${streak}`);
          }else{
            badCount++; streak = 0; renderHud();
            $("#mensaje").removeClass("d-none alert-success alert-warning")
              .addClass("alert-danger").text("❌ Incorrecto");
            $card.addClass("glow-lose shake");
            sIncorrecto(); launchSad();
            setEstado("Ups...", "😬");
            showOverlay("bad", "❌ Incorrecto", "¡No te rindas! La siguiente es tuya");
          }

          setTimeout(()=>{
            hideOverlay();
            cargarPregunta();
          }, 950);
        },
        "json"
      ).fail(function(xhr){
        setEstado("Error", "❌");
        $("#mensaje").removeClass("d-none alert-success")
          .addClass("alert-danger").text("Error en el servidor (AJAX) ❌");
        console.log(xhr.responseText);
        setTimeout(cargarPregunta, 1500);
      });
    }

    // ========= Selector por PIN =========
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
          $sel.append(`<option value="${d.id_dificultad}">${escapeHtml(d.nombre_dificultad)}</option>`);
        });

        $("#btnIniciar").prop("disabled", true);

        // ✅ si está en lobby, asegura lobby
        ensureStageTrack();
      }).fail(function(xhr){
        console.log(xhr.responseText);
        $("#id_dificultad").html('<option value="">Error cargando dificultades</option>');
        $("#btnIniciar").prop("disabled", true);
      });
    }

    $("#id_dificultad").on("change", function(){
      $("#btnIniciar").prop("disabled", !$(this).val());
      sClick();
    });

    $("#btnIniciar").on("click", async function(){
      const idDif = $("#id_dificultad").val();
      if(!idDif) return;
      sClick();

      $.post("../controllers/setDificultadController.php", { id_dificultad: idDif }, async function(res){
        if(res && res.ok){
          $("#panelDificultad").addClass("d-none");
          $("#opcionesBox").removeClass("d-none");
          $("#subTitulo").text("Pregunta");
          $("#preguntaTexto").text("Cargando pregunta...");

          // ✅ ya no es lobby => debe sonar quizzGame
          if(musicOn) await fadeTo(musicGame, 350);

          if(!countdownShown){
            countdownShown = true;
            showCountdownGO(()=>cargarPregunta());
          }else{
            cargarPregunta();
          }
        }else{
          alert(res.mensaje || "No se pudo guardar la dificultad");
        }
      }, "json").fail(function(xhr){
        console.log(xhr.responseText);
        alert("Error guardando dificultad");
      });
    });

    // init
    setMusicUI();

    if(ORIGEN === "dashboard"){
      $("#opcionesBox").removeClass("d-none");

      // ✅ dashboard siempre inicia en quiz (quizzGame)
      ensureStageTrack();

      if(!countdownShown){
        countdownShown = true;
        showCountdownGO(()=>cargarPregunta());
      }else{
        cargarPregunta();
      }
    }else{
      // ✅ en PIN inicia lobby
      ensureStageTrack();
      cargarDificultades();
    }
  </script>
</body>
</html>
