<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ha ocurrido un error</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #e6e6e6;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            max-width: 520px;
            width: 100%;
            padding: 3rem 2.5rem;
            text-align: center;
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .logo-wrapper img {
            max-height: 64px;
            max-width: 180px;
            object-fit: contain;
        }

        .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background-color: #b5b5b5;
            margin-bottom: 1.5rem;
        }

        .icon svg {
            width: 36px;
            height: 36px;
            color: #a41e24;
            stroke: #a41e24;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .message {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.75rem;
        }

        .support-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
        }

        .support-box p {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.375rem;
        }

        .support-box a {
            font-size: 1rem;
            font-weight: 600;
            color: #a41e24;
            text-decoration: none;
            letter-spacing: 0.01em;
        }

        .support-box a:hover {
            text-decoration: underline;
        }

        .btn-home {
            display: inline-block;
            padding: 0.75rem 2rem;
            background-color: #a41e24;
            color: #ffffff;
            font-size: 0.9375rem;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.15s ease;
        }

        .btn-home:hover {
            background-color: #a41e24;
        }

        .footer-note {
            margin-top: 2.5rem;
            font-size: 0.8125rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="card">

        <div class="logo-wrapper">
            <img src="/images/innovet-logo.png" alt="Innovet">
        </div>

        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>

        <h1>Ha ocurrido un error</h1>

        <p class="message">
            Ocurrió un problema inesperado. Si el problema continúa,<br>
            por favor comunícate con soporte.
        </p>

        <div class="support-box">
            <p>Para cualquier duda o aclaración, contáctanos en el correo de soporte:</p>
            <a href="mailto:soporte@ab-forti.com">soporte@ab-forti.com</a>
        </div>

        <a href="/" class="btn-home">Volver al inicio</a>

    </div>

    <p class="footer-note">&copy; {{ date('Y') }} Innovet &mdash; Todos los derechos reservados</p>
</body>
</html>
