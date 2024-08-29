<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS | Register</title>
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link href="../../../../demo/public/css/tailwind.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .invalid-feedback {
            display: none;
            color: #f87171;
            /* Tailwind's red-400 */
        }

        .invalid-feedback.show {
            display: block;
        }

        @media (max-width: 768px) {
            .cover {
                display: none;
            }

            .login-form-container {
                padding: 4rem 1.5rem;
                /* Adjust padding for mobile */
                width: 100%;
                background: white;
                /* Ensure background is white on mobile */
                min-height: 100vh;
                /* Ensure it takes up full viewport height */
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="flex flex-col md:flex-row min-h-screen">

