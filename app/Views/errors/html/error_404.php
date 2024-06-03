<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= lang('Errors.pageNotFound') ?></title>

    <style>
        body {
            max-height: auto;
            background-image: linear-gradient(to bottom right, black, red);
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            color: #777;
            font-weight: 300;
            display: flex;
            justify-content: center;
            align-items: center;
            
        }
        .wrap {
            max-width: auto;
            max-height: auto;
            margin: 5rem auto;
            padding: 2rem;
            background: #fff;
            text-align: center;
            border: 1px solid #efefef;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        h1 {
            font-weight: lighter;
            letter-spacing: normal;
            font-size: 6rem;
            margin-top: 0;
            margin-bottom: 0;
            color: #222;
            animation: pulse 1.5s infinite;
        }
        p {
            margin-top: 1.5rem;
            font-size: 1.25rem;
        }
        pre {
            white-space: normal;
            margin-top: 1.5rem;
        }
        code {
            background: #fafafa;
            border: 1px solid #efefef;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: block;
        }
        .footer {
            margin-top: 2rem;
            border-top: 1px solid #efefef;
            padding: 1em 2em 0 2em;
            font-size: 85%;
            color: #999;
        }
        a:active,
        a:link,
        a:visited {
            color: #dd4814;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>404</h1>

        <p>
            <?php if (ENVIRONMENT !== 'production') : ?>
                <?= nl2br(esc($message)) ?>
            <?php else : ?>
                <?= lang('Errors.sorryCannotFind') ?>
            <?php endif; ?>
        </p>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> BFP Calapan City. All rights reserved.</p>
            <button onclick="history.go(-1);" >Back</button>
        </div>
    </div>
</body>
</html>
