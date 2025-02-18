<h2>Registro Exitoso</h2>

<?php if (isset($_SESSION['success'])): ?>
    <p class="success"><?= $_SESSION['success'] ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<p>Tu cuenta ha sido creada con éxito. Hemos enviado un correo electrónico de verificación a la dirección que proporcionaste.</p>

<p>Por favor, revisa tu bandeja de entrada (y la carpeta de spam, por si acaso) y haz clic en el enlace de verificación para activar tu cuenta.</p>

<p>Una vez que hayas verificado tu cuenta, podrás <a href="<?= BASE_URL ?>Auth/login">iniciar sesión</a>.</p>

<p>Si no recibes el correo en los próximos minutos, por favor <a href="<?= BASE_URL ?>Auth/resendVerification">solicita un nuevo correo de verificación</a>.</p>